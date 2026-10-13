<?php

namespace App\Support;

use App\Models\EbiddingVendorBidItem;
use App\Models\JawatankuasaPerolehanPemilihanItem;
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
                $previous = $petender->harga_tawaran !== null ? (float) $petender->harga_tawaran : null;
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

            // Align table harga with modal totals (sum of child line items).
            if ($items !== []) {
                $totals = self::totalsForItems($items);
                $row['harga_tawaran'] = $totals['previous'];
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
}
