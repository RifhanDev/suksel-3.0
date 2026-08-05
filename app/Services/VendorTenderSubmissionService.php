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
    public function __construct(protected TenderProcessStatusService $statusService) {}

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

            $purchase->submitted = 1;
            $purchase->save();

            $this->advanceToHantarDokumenSyarikat($tender);

            return $purchase->fresh();
        });
    }

    /**
     * Company pertama yang hantar dokumen menaikkan tender 5 → 6, membolehkan modul
     * Penyediaan Mesyuarat memaparkannya. Company lain yang belum hantar TIDAK
     * tersekat — tempoh key-in (tarikh tutup) yang menentukan, bukan status proses.
     */
    protected function advanceToHantarDokumenSyarikat(Tender $tender): void
    {
        // Tender e-Bidding dikecualikan: EbiddingController memetakan status 5 =
        // peringkat Vendor dan 6 = Agency Admin (Semakan), jadi menaikkan status
        // akan melompatkan paparan e-bidding ke peringkat semakan serta-merta.
        if ($tender->is_ebidding) {
            return;
        }

        if ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::PENYEDIAAN_IKLAN) {
            return;
        }

        $this->statusService->advanceStatus($tender, TenderProcessStatus::HANTAR_DOKUMEN_SYARIKAT);
    }

    /**
     * Tarikh tutup — bukan status proses — yang menentukan sama ada company masih
     * boleh hantar. Status boleh naik ke 6 (company pertama hantar) atau 7 (urusetia
     * hantar jemputan mesyuarat) sementara tempoh iklan masih terbuka; company lain
     * mesti kekal boleh hantar. Tempoh dikuatkuasakan oleh
     * vendorDokumenWindowBlockedReason() dalam readiness(), dipanggil sebelum ini.
     */
    protected function assertTenderAcceptsVendorSubmission(Tender $tender): void
    {
        $status = (int) ($tender->status_process_id ?? 0);

        if ($status < TenderProcessStatus::PENYEDIAAN_IKLAN) {
            throw ValidationException::withMessages([
                'tender' => 'Tender belum dibuka untuk penghantaran tawaran.',
            ]);
        }

        // Jaring keselamatan: menjelang Penilaian Pembuka, jawatankuasa sudah membuka
        // tawaran — tiada penghantaran baharu boleh diterima walau apa pun tarikh.
        // Perlu kerana isWithinVendorDokumenWindow() gagal-terbuka bila tiada tarikh.
        if ($status >= TenderProcessStatus::PENILAIAN_PEMBUKA) {
            throw ValidationException::withMessages([
                'tender' => 'Tender telah masuk fasa penilaian. Penghantaran tawaran telah ditutup.',
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
