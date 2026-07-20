<?php

namespace App\Services;

use App\TenderVendor;
use Illuminate\Support\Facades\DB;

class KodPembekalService
{
    /**
     * Assign kod_pembekal (e.g. 1/20, 2/20) to all purchasers of a tender.
     * Sequence follows purchase order (tender_vendors.id ASC).
     */
    public function syncForTender(int $tenderId): void
    {
        DB::transaction(function () use ($tenderId) {
            $purchases = TenderVendor::query()
                ->where('tender_id', $tenderId)
                ->where('participate', 1)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $total = $purchases->count();

            foreach ($purchases as $index => $purchase) {
                $sequence = $index + 1;
                $code = $total > 0 ? "{$sequence}/{$total}" : null;

                if ($purchase->kod_pembekal !== $code) {
                    $purchase->kod_pembekal = $code;
                    $purchase->saveQuietly();
                }
            }
        });
    }

    /**
     * @return array<int, int> tender_id => purchasers synced
     */
    public function syncAll(): int
    {
        $tenderIds = TenderVendor::query()
            ->where('participate', 1)
            ->distinct()
            ->pluck('tender_id');

        foreach ($tenderIds as $tenderId) {
            $this->syncForTender((int) $tenderId);
        }

        return $tenderIds->count();
    }
}
