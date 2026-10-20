<?php

namespace App\Services;

use App\TenderVendor;
use Illuminate\Support\Facades\DB;

class KodPembekalService
{
    /**
     * Assign kod_pembekal (e.g. 1/20, 2/20) after vendor "hantar dokumen".
     * Only rows with participate=1 AND submitted=1 get a code; others are cleared.
     * Sequence follows tender_vendors.id ASC among submitted vendors.
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

            $submitted = $purchases->where('submitted', 1)->values();
            $total = $submitted->count();
            $codesById = [];

            foreach ($submitted as $index => $purchase) {
                $codesById[(int) $purchase->id] = $total > 0 ? ($index + 1) . '/' . $total : null;
            }

            foreach ($purchases as $purchase) {
                $code = $codesById[(int) $purchase->id] ?? null;

                if ($purchase->kod_pembekal !== $code) {
                    $purchase->kod_pembekal = $code;
                    $purchase->saveQuietly();
                }
            }
        });
    }

    /**
     * @return int number of tenders synced
     */
    public function syncAll(): int
    {
        $tenderIds = TenderVendor::query()
            ->where('participate', 1)
            ->where('submitted', 1)
            ->distinct()
            ->pluck('tender_id');

        foreach ($tenderIds as $tenderId) {
            $this->syncForTender((int) $tenderId);
        }

        return $tenderIds->count();
    }
}
