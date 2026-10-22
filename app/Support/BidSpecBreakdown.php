<?php

namespace App\Support;

use App\Models\EbiddingVendorBidItem;
use App\Models\JawatankuasaPerolehanPemilihanItem;
use App\Models\SpesifikasiKerjaItem;
use App\Models\TechnicalSpecificationItem;
use App\Models\TenderVendorDokumenResponse;
use App\Services\VendorDokumenResponseService;
use App\Tender;
use Illuminate\Support\Collection;

/**
 * Per-vendor child spesifikasi / harga bidaan breakdown for reusable UI.
 */
class BidSpecBreakdown
{
    /**
     * @return array<int, array{
     *   vendor_id: int,
     *   vendor_name: string,
     *   items: array<int, array{
     *     spesifikasi: string,
     *     kuantiti: string,
     *     unit_ukuran: string,
     *     previous_price: float|null,
     *     bid_price: float|null,
     *     is_new_bid: bool,
     *     is_carried_forward: bool
     *   }>
     * }>
     */
    public static function forTender(Tender $tender): array
    {
        $items = JawatankuasaPerolehanPemilihanItem::query()
            ->where('tender_id', $tender->id)
            ->with(['petenders' => function ($q) {
                $q->orderBy('sort_order')->with('vendor:id,name');
            }])
            ->orderBy('sort_order')
            ->get();

        if ($items->isEmpty()) {
            return [];
        }

        $tenderName = trim((string) ($tender->name ?? ''));
        $lineItems = $items->filter(function (JawatankuasaPerolehanPemilihanItem $item) use ($tenderName, $items) {
            $isOverall = $tenderName !== ''
                && trim((string) $item->perihal_item) === $tenderName
                && $items->count() > 1;

            return ! $isOverall;
        })->values();

        if ($lineItems->isEmpty()) {
            $lineItems = $items->values();
        }

        $bids = EbiddingVendorBidItem::query()
            ->where('tender_id', $tender->id)
            ->whereNotNull('submitted_at')
            ->get()
            ->groupBy(fn ($bid) => (int) $bid->vendor_id . ':' . (int) $bid->pemilihan_item_id);

        // Per-line harga tawaran (not the cloned overall total on every pemilihan row).
        $linePrices = self::linePricesByVendorTitle($tender);

        /** @var array<int, array{vendor_id: int, vendor_name: string, items: array<int, array<string, mixed>>}> $byVendor */
        $byVendor = [];

        foreach ($lineItems as $item) {
            foreach ($item->petenders as $petender) {
                $vendorId = (int) ($petender->vendor_id ?? 0);
                if ($vendorId <= 0) {
                    continue;
                }

                if (! isset($byVendor[$vendorId])) {
                    $byVendor[$vendorId] = [
                        'vendor_id' => $vendorId,
                        'vendor_name' => (string) ($petender->vendor->name ?? '-'),
                        'items' => [],
                    ];
                }

                $bidKey = $vendorId . ':' . (int) $item->id;
                $bid = ($bids->get($bidKey) ?? collect())->first();
                $titleKey = self::normalizeTitle((string) ($item->perihal_item ?? ''));
                $linePrice = $linePrices[$vendorId][$titleKey] ?? null;
                // pemilihan_petenders.harga_tawaran is the overall offer, copied onto
                // every line when eBidding expands items. Use it only for a single line.
                $previous = $linePrice;
                if ($previous === null && $lineItems->count() === 1 && $petender->harga_tawaran !== null) {
                    $previous = (float) $petender->harga_tawaran;
                }
                $bidPrice = $bid ? (float) $bid->bid_price : null;
                $isCarriedForward = $bid
                    ? (bool) ($bid->is_carried_forward ?? false)
                    : true;
                $isNewBid = $bid && ! $isCarriedForward;

                $byVendor[$vendorId]['items'][] = [
                    'spesifikasi' => (string) ($item->perihal_item ?? '-'),
                    'kuantiti' => (string) ($item->kuantiti ?? ''),
                    'unit_ukuran' => (string) ($item->unit_ukuran ?? '-'),
                    'previous_price' => $previous,
                    'bid_price' => $bidPrice !== null ? $bidPrice : $previous,
                    'is_new_bid' => $isNewBid,
                    'is_carried_forward' => $isCarriedForward,
                ];
            }
        }

        return $byVendor;
    }

    /**
     * @param  array<int, array{vendor_id: int, vendor_name: string, items: array<int, array<string, mixed>>}>  $breakdown
     * @return array<int, array<string, mixed>>
     */
    public static function itemsForVendor(array $breakdown, int $vendorId): array
    {
        return $breakdown[$vendorId]['items'] ?? [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array{previous: float, bid: float}
     */
    public static function totalsForItems(array $items): array
    {
        $previous = 0.0;
        $bid = 0.0;
        foreach ($items as $item) {
            $prev = $item['previous_price'] ?? null;
            $bidPrice = $item['bid_price'] ?? null;
            if ($prev !== null && $prev !== '') {
                $previous += (float) $prev;
            }
            if ($bidPrice !== null && $bidPrice !== '') {
                $bid += (float) $bidPrice;
            } elseif ($prev !== null && $prev !== '') {
                $bid += (float) $prev;
            }
        }

        return [
            'previous' => round($previous, 2),
            'bid' => round($bid, 2),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>|array<int, array<string, mixed>>  $rows
     * @param  array<int, array{vendor_id: int, vendor_name: string, items: array<int, array<string, mixed>>}>  $breakdown
     * @return Collection<int, array<string, mixed>>|array<int, array<string, mixed>>
     */
    public static function attachToRows($rows, array $breakdown)
    {
        $map = function (array $row) use ($breakdown) {
            $vendorId = (int) ($row['vendor_id'] ?? 0);
            $items = self::itemsForVendor($breakdown, $vendorId);
            $row['spec_items'] = $items;

            // Align table harga with the sum of real per-item offers.
            // If line prices could not be resolved, keep the original overall
            // offer — do not multiply it by the number of items.
            if ($items !== []) {
                $overallOffer = isset($row['harga_tawaran']) ? (float) $row['harga_tawaran'] : 0.0;
                $totals = self::totalsForItems($items);
                $row['harga_tawaran'] = $totals['previous'] > 0 ? $totals['previous'] : $overallOffer;
                $row['harga_bidaan'] = $totals['bid'];
                $newCount = collect($items)->where('is_new_bid', true)->count();
                $oldCount = collect($items)->where('is_new_bid', false)->count();
                $row['is_new_bid'] = $newCount > 0 && $oldCount === 0;
                $row['is_carried_forward'] = $oldCount > 0 && $newCount === 0;
            }

            return $row;
        };

        if ($rows instanceof Collection) {
            return $rows->map($map)->values();
        }

        return array_map($map, $rows);
    }

    /**
     * Vendor offer per spesifikasi line, keyed by vendor id then normalised title.
     * Kerja: kadar × kuantiti. Bekalan: item_prices is already the line total.
     *
     * @return array<int, array<string, float>>
     */
    private static function linePricesByVendorTitle(Tender $tender): array
    {
        $responses = TenderVendorDokumenResponse::query()
            ->where('tender_id', $tender->id)
            ->where(function ($q) {
                $q->where('section', 'spesifikasi_kerja')
                    ->orWhere('section', 'like', 'specification%')
                    ->orWhere('response_type', 'specification');
            })
            ->orderByDesc('id')
            ->get();

        $service = app(VendorDokumenResponseService::class);
        $pricesByVendor = [];
        $uuids = [];

        foreach ($responses as $response) {
            $vendorId = (int) $response->vendor_id;
            if ($vendorId <= 0 || isset($pricesByVendor[$vendorId])) {
                continue;
            }

            $payload = $service->normalizeSpecificationPayload(
                is_array($response->payload) ? $response->payload : null
            );
            $prices = $payload['item_prices'] ?? [];
            $pricesByVendor[$vendorId] = $prices;
            foreach (array_keys($prices) as $uuid) {
                $uuids[(string) $uuid] = true;
            }
        }

        if ($uuids === []) {
            return [];
        }

        $kerja = SpesifikasiKerjaItem::query()
            ->whereIn('uuid', array_keys($uuids))
            ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
            ->get(['id', 'uuid', 'spesifikasi', 'nama_item', 'kuantiti', 'parent_id']);

        $bekalan = TechnicalSpecificationItem::query()
            ->whereIn('uuid', array_keys($uuids))
            ->get(['uuid', 'title']);

        $map = [];
        foreach ($pricesByVendor as $vendorId => $prices) {
            foreach ($prices as $uuid => $raw) {
                $kerjaItem = $kerja->firstWhere('uuid', $uuid);
                if ($kerjaItem) {
                    // Parent rows are headings; priced lines are children (or a leaf parent).
                    if ($kerjaItem->parent_id === null && $kerja->where('parent_id', $kerjaItem->id)->isNotEmpty()) {
                        continue;
                    }
                    $kadar = (float) str_replace(',', '', (string) $raw);
                    $qty = (float) ($kerjaItem->kuantiti ?? 0);
                    $line = round($kadar * ($qty > 0 ? $qty : 1), 2);
                    $title = trim((string) ($kerjaItem->spesifikasi ?: $kerjaItem->nama_item));
                    if ($title !== '') {
                        $map[$vendorId][self::normalizeTitle($title)] = $line;
                    }

                    continue;
                }

                $bekalanItem = $bekalan->firstWhere('uuid', $uuid);
                if (! $bekalanItem) {
                    continue;
                }

                $title = trim((string) ($bekalanItem->title ?? ''));
                if ($title === '') {
                    continue;
                }

                $map[$vendorId][self::normalizeTitle($title)] = round(
                    (float) str_replace(',', '', (string) $raw),
                    2
                );
            }
        }

        return $map;
    }

    private static function normalizeTitle(string $title): string
    {
        $title = mb_strtolower(trim($title));

        return preg_replace('/\s+/', ' ', $title) ?? $title;
    }
}
