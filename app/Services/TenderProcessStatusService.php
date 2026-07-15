<?php

namespace App\Services;

use App\Models\FinancialChecklistHeader;
use App\Models\KewanganKerjaHeader;
use App\Models\PerakuanJabatanKertasTaklimat;
use App\Models\PerakuanJabatanPengesyoranPembekal;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\TechnicalChecklistHeader;
use App\Support\TenderProcessStatus;
use App\Tender;

class TenderProcessStatusService
{
    public function __construct(protected StosTenderChecklistSync $checklistSync) {}

    public function markPelantikanJawatankuasaSelesai(Tender $tender): void
    {
        if (! $tender->hasCompleteJawatankuasa()) {
            return;
        }

        $this->advanceTo($tender, TenderProcessStatus::PELANTIKAN_JAWATANKUASA);
    }

    public function syncAfterChecklistSubmit(Tender $tender): void
    {
        $this->checklistSync->syncForTender($tender);
        $tender->refresh();

        if ($this->isTechnicalSpecificationComplete($tender)) {
            $this->advanceTo($tender, TenderProcessStatus::SPESIFIKASI_TEKNIKAL);
        }

        if ($this->isPengurusanSpesifikasiComplete($tender)) {
            $this->advanceTo($tender, TenderProcessStatus::SPESIFIKASI_KEWANGAN);
        }
    }

    public function isTechnicalSpecificationComplete(Tender $tender): bool
    {
        if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
            return $this->headerSubmitted(SpesifikasiKerjaHeader::class, $tender->id);
        }

        return $this->headerSubmitted(TechnicalChecklistHeader::class, $tender->id);
    }

    public function isPengurusanSpesifikasiComplete(Tender $tender): bool
    {
        if (! $this->isTechnicalSpecificationComplete($tender)) {
            return false;
        }

        if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
            return $this->headerSubmitted(KewanganKerjaHeader::class, $tender->id);
        }

        return $this->headerSubmitted(FinancialChecklistHeader::class, $tender->id);
    }

    protected function headerSubmitted(string $modelClass, int $tenderId): bool
    {
        return $modelClass::query()
            ->where('tender_id', $tenderId)
            ->where('status', 'submitted')
            ->exists();
    }

    public function advanceStatus(Tender $tender, int $status): void
    {
        $this->advanceTo($tender, $status);
    }

    public function syncPerakuanJabatanCompletion(Tender $tender): void
    {
        $kertas = PerakuanJabatanKertasTaklimat::query()
            ->where('tender_id', $tender->id)
            ->first();

        $pengesyoran = PerakuanJabatanPengesyoranPembekal::query()
            ->where('tender_id', $tender->id)
            ->first();

        if (! $kertas?->submitted_at || ! $pengesyoran?->submitted_at) {
            return;
        }

        $this->advanceTo($tender, TenderProcessStatus::PERAKUAN_JABATAN);
    }

    protected function advanceTo(Tender $tender, int $status): void
    {
        if ((int) ($tender->status_process_id ?? 0) >= $status) {
            return;
        }

        Tender::query()
            ->where('id', $tender->id)
            ->update(['status_process_id' => $status]);
    }
}
