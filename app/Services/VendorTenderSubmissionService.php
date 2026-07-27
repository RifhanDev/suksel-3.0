<?php

namespace App\Services;

use App\Support\TenderProcessStatus;
use App\Support\TenderDokumenPresenter;
use App\Tender;
use App\TenderVendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendorTenderSubmissionService
{
    /**
     * @return array{ready: bool, errors: array<int, string>, purchase: ?TenderVendor}
     */
    public function readiness(Tender $tender, int $vendorId): array
    {
        $purchase = $this->findPurchase($tender, $vendorId);

        if (! $purchase) {
            return [
                'ready' => false,
                'errors' => ['Sila beli dokumen tender terlebih dahulu.'],
                'purchase' => null,
            ];
        }

        if ($purchase->submitted) {
            return [
                'ready' => false,
                'errors' => ['Tawaran telah dihantar dan tidak boleh dihantar semula.'],
                'purchase' => $purchase,
            ];
        }

        $windowReason = $tender->vendorDokumenWindowBlockedReason();
        if ($windowReason !== null) {
            return [
                'ready' => false,
                'errors' => [$windowReason],
                'purchase' => $purchase,
            ];
        }

        $errors = $this->collectValidationErrors($tender, $vendorId);

        return [
            'ready' => $errors === [],
            'errors' => $errors,
            'purchase' => $purchase,
        ];
    }

    public function submit(Tender $tender, int $vendorId): TenderVendor
    {
        $readiness = $this->readiness($tender, $vendorId);

        if (! $readiness['purchase']) {
            throw ValidationException::withMessages(['vendor' => $readiness['errors'][0] ?? 'Akses tidak sah.']);
        }

        if ($readiness['purchase']->submitted) {
            throw ValidationException::withMessages(['vendor' => 'Tawaran telah dihantar.']);
        }

        if ($readiness['errors'] !== []) {
            throw ValidationException::withMessages(['checklist' => $readiness['errors']]);
        }

        $this->assertTenderAcceptsVendorSubmission($tender);

        return DB::transaction(function () use ($tender, $vendorId) {
            $purchase = TenderVendor::query()
                ->where('tender_id', $tender->id)
                ->where('vendor_id', $vendorId)
                ->where('participate', 1)
                ->lockForUpdate()
                ->firstOrFail();

            if ($purchase->submitted) {
                throw ValidationException::withMessages(['vendor' => 'Tawaran telah dihantar.']);
            }

            // Only mark this vendor's purchase as submitted.
            // Do not advance tender status_process_id — other vendors must still
            // be able to submit during the same iklan window.
            $purchase->submitted = 1;
            $purchase->save();

            return $purchase->fresh();
        });
    }

    /**
     * Vendor submission stays open after penyediaan iklan until mesyuarat/evaluation.
     * Status must not flip on the first vendor submit (that blocked other companies).
     */
    protected function assertTenderAcceptsVendorSubmission(Tender $tender): void
    {
        $status = (int) ($tender->status_process_id ?? 0);

        if (
            $status < TenderProcessStatus::PENYEDIAAN_IKLAN
            || $status >= TenderProcessStatus::PENYEDIAAN_MESYUARAT
        ) {
            throw ValidationException::withMessages([
                'tender' => 'Tender tidak berada dalam fasa penghantaran tawaran.',
            ]);
        }
    }

    public function assertEditable(Tender $tender, int $vendorId): void
    {
        $purchase = $this->findPurchase($tender, $vendorId);

        if ($purchase && $purchase->submitted) {
            throw ValidationException::withMessages([
                'vendor' => 'Tawaran telah dihantar. Maklumat tidak boleh dikemaskini.',
            ]);
        }

        $windowReason = $tender->vendorDokumenWindowBlockedReason();
        if ($windowReason !== null) {
            throw ValidationException::withMessages([
                'vendor' => $windowReason,
            ]);
        }
    }

    public function isSubmitted(Tender $tender, int $vendorId): bool
    {
        $purchase = $this->findPurchase($tender, $vendorId);

        return $purchase ? (bool) $purchase->submitted : false;
    }

    protected function findPurchase(Tender $tender, int $vendorId): ?TenderVendor
    {
        return TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where('participate', 1)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * @return array<int, string>
     */
    protected function collectValidationErrors(Tender $tender, int $vendorId): array
    {
        $errors = [];

        $items = TenderDokumenPresenter::for($tender)->items('vendor', $vendorId);

        foreach ($items as $item) {
            if ($this->isItemComplete($item)) {
                continue;
            }

            $title = $item['nama'] ?? $item['title'] ?? 'Dokumen';
            $errors[] = "Dokumen \"{$title}\" belum lengkap.";
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    protected function isItemComplete(array $item): bool
    {
        $action = $item['action'] ?? '';

        if ($action === 'download_only') {
            return true;
        }

        return ($item['vendor_status'] ?? 'draft') === 'submitted';
    }
}
