<?php

namespace App\Services;

use App\Models\PenyataBank;
use App\Models\PenyataBankScoringItem;
use App\Models\Tender;
use Illuminate\Support\Str;

class PenyataBankPersistenceService
{
    /**
     * @return array<string, mixed>|null
     */
    public function loadForTender(Tender $tender): ?array
    {
        $record = PenyataBank::query()
            ->with(['scoringItems', 'bulans', 'files'])
            ->where('tender_id', $tender->id)
            ->first();

        if (! $record) {
            return null;
        }

        return $this->toPayload($record);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function saveForTender(Tender $tender, array $payload): PenyataBank
    {
        $record = PenyataBank::query()->firstOrNew(['tender_id' => $tender->id]);

        if (! $record->exists) {
            $record->uuid = (string) Str::uuid();
            $record->status = 'draft';
            $record->created_by = auth()->id();
        }

        $accounts = $this->normalizeAccounts($payload);
        $first = $accounts[0] ?? [];

        $record->fill([
            'dari_bulan' => $first['dari_bulan'] ?? $payload['dari_bulan'] ?? null,
            'dari_tahun' => $first['dari_tahun'] ?? $payload['dari_tahun'] ?? null,
            'hingga_bulan' => $first['hingga_bulan'] ?? $payload['hingga_bulan'] ?? null,
            'hingga_tahun' => $first['hingga_tahun'] ?? $payload['hingga_tahun'] ?? null,
            'jumlah_keseluruhan' => $payload['jumlah_keseluruhan'] ?? $first['jumlah_keseluruhan'] ?? 0,
            'purata' => $payload['purata'] ?? $first['purata'] ?? 0,
            'jenis_skor_purata' => $payload['jenis_skor_purata'] ?? $record->jenis_skor_purata,
            'accounts' => $accounts,
            'updated_by' => auth()->id(),
        ]);

        if (($payload['status'] ?? null) === 'submitted') {
            $record->status = 'submitted';
        } elseif ($record->status === null) {
            $record->status = 'draft';
        }

        $record->save();

        $this->syncBulans($record, $first['bulans'] ?? $payload['bulans'] ?? []);
        $this->syncScoringItems($record, $payload['scoring_items'] ?? []);

        return $record->fresh(['scoringItems', 'bulans', 'files']);
    }

    /**
     * @param  array<string, mixed>  $vendorPayload
     * @return array<string, mixed>
     */
    public function mergeVendorPayload(?array $tenderPayload, ?array $vendorPayload): ?array
    {
        if (! $tenderPayload && ! $vendorPayload) {
            return null;
        }

        if (! $tenderPayload) {
            return $vendorPayload;
        }

        if (! $vendorPayload) {
            return $tenderPayload;
        }

        $merged = $tenderPayload;
        $tenderAccounts = $tenderPayload['accounts'] ?? [];
        $vendorAccounts = $vendorPayload['accounts'] ?? [];

        if ($tenderAccounts !== []) {
            $merged['accounts'] = array_map(function (array $tenderAccount, int $index) use ($vendorAccounts) {
                $vendorAccount = $vendorAccounts[$index] ?? [];

                return array_merge($tenderAccount, [
                    'bulans' => $this->mergeBulans(
                        $tenderAccount['bulans'] ?? [],
                        $vendorAccount['bulans'] ?? []
                    ),
                    'jumlah_keseluruhan' => $vendorAccount['jumlah_keseluruhan'] ?? $tenderAccount['jumlah_keseluruhan'] ?? 0,
                    'purata' => $vendorAccount['purata'] ?? $tenderAccount['purata'] ?? 0,
                    'files' => $vendorAccount['files'] ?? $tenderAccount['files'] ?? [],
                ]);
            }, $tenderAccounts, array_keys($tenderAccounts));
        } elseif ($vendorAccounts !== []) {
            $merged['accounts'] = $vendorAccounts;
        }

        foreach (['jumlah_keseluruhan', 'purata', 'jumlah_keseluruhan_grand'] as $key) {
            if (array_key_exists($key, $vendorPayload)) {
                $merged[$key] = $vendorPayload[$key];
            }
        }

        if (! empty($vendorPayload['bulans'])) {
            $merged['bulans'] = $this->mergeBulans(
                $tenderPayload['bulans'] ?? [],
                $vendorPayload['bulans']
            );
        }

        return $merged;
    }

    /**
     * @return array<string, mixed>
     */
    protected function toPayload(PenyataBank $record): array
    {
        $accounts = $record->accounts;

        if (! is_array($accounts) || $accounts === []) {
            $accounts = [[
                'dari_bulan' => $record->dari_bulan,
                'dari_tahun' => $record->dari_tahun,
                'hingga_bulan' => $record->hingga_bulan,
                'hingga_tahun' => $record->hingga_tahun,
                'bulans' => $record->bulans->map(fn ($b) => [
                    'bulan' => (int) $b->bulan,
                    'tahun' => (int) $b->tahun,
                    'jumlah' => (float) $b->jumlah,
                ])->values()->all(),
                'jumlah_keseluruhan' => (float) $record->jumlah_keseluruhan,
                'purata' => (float) $record->purata,
                'files' => $record->files->map(fn ($f) => [
                    'uuid' => $f->uuid,
                    'original_name' => $f->original_name,
                    'size' => $f->size,
                ])->values()->all(),
            ]];
        }

        $first = $accounts[0] ?? [];

        return [
            'dari_bulan' => $first['dari_bulan'] ?? $record->dari_bulan,
            'dari_tahun' => $first['dari_tahun'] ?? $record->dari_tahun,
            'hingga_bulan' => $first['hingga_bulan'] ?? $record->hingga_bulan,
            'hingga_tahun' => $first['hingga_tahun'] ?? $record->hingga_tahun,
            'bulans' => $first['bulans'] ?? [],
            'jumlah_keseluruhan' => $first['jumlah_keseluruhan'] ?? (float) $record->jumlah_keseluruhan,
            'purata' => $first['purata'] ?? (float) $record->purata,
            'jenis_skor_purata' => $record->jenis_skor_purata,
            'scoring_items' => $record->scoringItems->map(fn ($item) => [
                'dari' => (float) $item->dari,
                'hingga' => $item->hingga !== null ? (float) $item->hingga : null,
                'skema' => $item->skema,
            ])->values()->all(),
            'accounts' => $accounts,
            'status' => $record->status,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<array<string, mixed>>
     */
    protected function normalizeAccounts(array $payload): array
    {
        $accounts = $payload['accounts'] ?? [];

        if ($accounts === [] && ($payload['dari_bulan'] ?? null)) {
            $accounts = [[
                'dari_bulan' => $payload['dari_bulan'],
                'dari_tahun' => $payload['dari_tahun'] ?? null,
                'hingga_bulan' => $payload['hingga_bulan'] ?? null,
                'hingga_tahun' => $payload['hingga_tahun'] ?? null,
                'bulans' => $payload['bulans'] ?? [],
                'jumlah_keseluruhan' => $payload['jumlah_keseluruhan'] ?? 0,
                'purata' => $payload['purata'] ?? 0,
            ]];
        }

        return array_values(array_map(function (array $account) {
            return [
                'dari_bulan' => isset($account['dari_bulan']) ? (int) $account['dari_bulan'] : null,
                'dari_tahun' => isset($account['dari_tahun']) ? (int) $account['dari_tahun'] : null,
                'hingga_bulan' => isset($account['hingga_bulan']) ? (int) $account['hingga_bulan'] : null,
                'hingga_tahun' => isset($account['hingga_tahun']) ? (int) $account['hingga_tahun'] : null,
                'bulans' => array_values($account['bulans'] ?? []),
                'jumlah_keseluruhan' => (float) ($account['jumlah_keseluruhan'] ?? 0),
                'purata' => (float) ($account['purata'] ?? 0),
                'files' => array_values($account['files'] ?? []),
            ];
        }, $accounts));
    }

    /**
     * @param  list<array<string, mixed>>  $bulans
     */
    protected function syncBulans(PenyataBank $record, array $bulans): void
    {
        $record->bulans()->delete();

        foreach ($bulans as $bulan) {
            if (empty($bulan['bulan']) || empty($bulan['tahun'])) {
                continue;
            }

            $record->bulans()->create([
                'uuid' => (string) Str::uuid(),
                'bulan' => (int) $bulan['bulan'],
                'tahun' => (int) $bulan['tahun'],
                'jumlah' => (float) ($bulan['jumlah'] ?? 0),
            ]);
        }
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    protected function syncScoringItems(PenyataBank $record, array $items): void
    {
        $record->scoringItems()->delete();

        foreach ($items as $index => $item) {
            $record->scoringItems()->create([
                'uuid' => (string) Str::uuid(),
                'dari' => (float) ($item['dari'] ?? 0),
                'hingga' => array_key_exists('hingga', $item) && $item['hingga'] !== null
                    ? (float) $item['hingga']
                    : null,
                'skema' => $item['skema'] ?? null,
                'sort_order' => $index,
            ]);
        }
    }

    /**
     * @param  list<array<string, mixed>>  $tenderBulans
     * @param  list<array<string, mixed>>  $vendorBulans
     * @return list<array<string, mixed>>
     */
    protected function mergeBulans(array $tenderBulans, array $vendorBulans): array
    {
        $vendorByKey = [];
        foreach ($vendorBulans as $bulan) {
            $key = ($bulan['bulan'] ?? '') . '-' . ($bulan['tahun'] ?? '');
            $vendorByKey[$key] = $bulan;
        }

        if ($tenderBulans === []) {
            return $vendorBulans;
        }

        return array_map(function (array $bulan) use ($vendorByKey) {
            $key = ($bulan['bulan'] ?? '') . '-' . ($bulan['tahun'] ?? '');

            return array_merge($bulan, $vendorByKey[$key] ?? []);
        }, $tenderBulans);
    }
}
