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

    /** Force status (allows 12→11 for bidaan loop). */
    public function setStatus(Tender $tender, int $status): void
    {
        Tender::query()
            ->where('id', $tender->id)
            ->update(['status_process_id' => $status]);

        $tender->status_process_id = $status;
    }

    public function syncPerakuanJabatanCompletion(Tender $tender): void
    {
        $kertas = PerakuanJabatanKertasTaklimat::query()
            ->where('tender_id', $tender->id)
            ->with(['items.files'])
            ->first();

        $pengesyoran = PerakuanJabatanPengesyoranPembekal::query()
            ->where('tender_id', $tender->id)
            ->first();

        if ((bool) ($tender->is_ebidding ?? false)) {
            $stage = (int) ($tender->ebidding_process_stage_id ?? 0);

            // Still preparing jadual or vendors are bidding — stay on Perakuan Jabatan (11).
            if ($stage > 0 && $stage < 3) {
                return;
            }

            // Post-bidaan review: Laporan Bidaan + pengesahan required before returning to JP (12).
            if ($stage >= 3) {
                $laporan = $kertas?->items->firstWhere('slot_key', 'laporan_bidaan');
                $hasLaporan = $laporan && $laporan->files->isNotEmpty();
                if (! $hasLaporan || ! $pengesyoran?->pengesahan_bidaan) {
                    return;
                }

                $this->setStatus($tender, TenderProcessStatus::PERAKUAN_JABATAN);

                return;
            }
        }

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

        $tender->status_process_id = $status;
    }
}
