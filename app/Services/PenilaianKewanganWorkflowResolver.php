<?php

namespace App\Services;

use App\Tender;

class PenilaianKewanganWorkflowResolver
{
    public const WORKFLOW_NORMAL = 'normal';
    public const WORKFLOW_SEBUT_HARGA_KERJA = 'sebut_harga_kerja';
    public const WORKFLOW_TENDER_KERJA_BESAR = 'tender_kerja_besar';
    public const WORKFLOW_TENDER_KERJA_KECIL_ME = 'tender_kerja_kecil_me';
    public const WORKFLOW_TENDER_KERJA_KECIL_BUKAN_ME = 'tender_kerja_kecil_bukan_me';
    public const WORKFLOW_INVALID = 'invalid';

    public const THRESHOLD_KERJA_BESAR = 10000000.00;

    /**
     * Determine the workflow classification for a tender at Penilaian Kewangan stage.
     */
    public function classify(Tender $tender): string
    {
        // 1. Check Procurement Category
        $kategoriId = (int) ($tender->kategori_perolehan_id ?? 0);
        $kategoriName = strtolower(trim($tender->kategoriPerolehan->name ?? ''));

        $isKerja = ($kategoriId === 3 || $kategoriName === 'kerja');
        $isBekalanOrPerkhidmatan = in_array($kategoriId, [1, 2], true)
            || in_array($kategoriName, ['perkhidmatan', 'bekalan'], true);

        // If category is neither Kerja nor Bekalan/Perkhidmatan
        if (! $isKerja && ! $isBekalanOrPerkhidmatan) {
            return self::WORKFLOW_INVALID;
        }

        // 2. Check Procurement Method (Tender vs Sebut Harga)
        $kaedahKind = $tender->kaedahPerolehanKind(); // 'tender', 'quotation', or null

        if (! in_array($kaedahKind, ['tender', 'quotation'], true)) {
            return self::WORKFLOW_INVALID;
        }

        // If Bekalan (2) or Perkhidmatan (1), method Tender or Sebut Harga -> Type 1: Normal
        if (! $isKerja) {
            return self::WORKFLOW_NORMAL;
        }

        // Category is Kerja (3):
        // If method is Sebut Harga ('quotation') -> Type 2: Reserved Sebut Harga Kerja
        if ($kaedahKind === 'quotation') {
            return self::WORKFLOW_SEBUT_HARGA_KERJA;
        }

        // Method is Tender ('tender'):
        $anggaran = (float) ($tender->anggaran_jabatan ?? $tender->harga_indikatif ?? $tender->price ?? 0);
        if ($anggaran <= 0.0) {
            return self::WORKFLOW_INVALID;
        }

        // Greater than RM10,000,000 -> Type 3: Tender Kerja Besar
        if ($anggaran > self::THRESHOLD_KERJA_BESAR) {
            return self::WORKFLOW_TENDER_KERJA_BESAR;
        }

        // RM10,000,000 or below -> Check M&E
        if ($this->isME($tender)) {
            return self::WORKFLOW_TENDER_KERJA_KECIL_ME;
        }

        return self::WORKFLOW_TENDER_KERJA_KECIL_BUKAN_ME;
    }

    /**
     * Check if a Kerja tender falls under Mechanical & Electrical (M&E).
     */
    public function isME(Tender $tender): bool
    {
        $detailId = (int) ($tender->kategori_perolehan_detail_id ?? 0);
        $detailName = mb_strtoupper(trim($tender->kategoriPerolehanDetail->name ?? ''));

        return ($detailId === 15
            || str_contains($detailName, 'M&E')
            || str_contains($detailName, 'MEKANIKAL')
            || str_contains($detailName, 'ELEKTRIKAL'));
    }

    /**
     * Resolve the target evaluation URL for the "Nilai" button.
     * Returns null if workflow is reserved or invalid.
     */
    public function resolveShowUrl(Tender $tender): ?string
    {
        $identifier = $tender->uuid ?: $tender->id;
        $workflow = $this->classify($tender);

        return match ($workflow) {
            self::WORKFLOW_NORMAL => route('penilaianKewangan.show', $identifier),
            self::WORKFLOW_SEBUT_HARGA_KERJA => route('penilaianKewanganSebutHargaKerja.show', $identifier),
            self::WORKFLOW_TENDER_KERJA_BESAR,
            self::WORKFLOW_TENDER_KERJA_KECIL_ME,
            self::WORKFLOW_TENDER_KERJA_KECIL_BUKAN_ME => route('penilaianKewanganKerja.show', $identifier),
            default => null,
        };
    }

    /**
     * Resolve action button metadata for the listing table.
     *
     * @return array{
     *     workflow: string,
     *     show_url: ?string,
     *     is_supported: bool,
     *     button_label: string,
     *     disabled: bool,
     *     badge_label: ?string,
     *     notice_message: ?string
     * }
     */
    public function resolveActionMeta(Tender $tender): array
    {
        $workflow = $this->classify($tender);
        $url = $this->resolveShowUrl($tender);

        return match ($workflow) {
            self::WORKFLOW_NORMAL,
            self::WORKFLOW_TENDER_KERJA_BESAR,
            self::WORKFLOW_TENDER_KERJA_KECIL_ME,
            self::WORKFLOW_TENDER_KERJA_KECIL_BUKAN_ME => [
                'workflow'       => $workflow,
                'show_url'       => $url,
                'is_supported'   => true,
                'button_label'   => 'Nilai',
                'disabled'       => false,
                'badge_label'    => null,
                'notice_message' => null,
            ],
            self::WORKFLOW_SEBUT_HARGA_KERJA => [
                'workflow'       => $workflow,
                'show_url'       => $url,
                'is_supported'   => true,
                'button_label'   => 'Nilai',
                'disabled'       => false,
                'badge_label'    => 'Sebut Harga Kerja',
                'notice_message' => null,
            ],
            default => [
                'workflow'       => $workflow,
                'show_url'       => null,
                'is_supported'   => false,
                'button_label'   => 'Maklumat Tidak Lengkap',
                'disabled'       => true,
                'badge_label'    => 'Ralat Data',
                'notice_message' => 'Maklumat perolehan, kaedah, atau anggaran jabatan tidak lengkap untuk penilaian.',
            ],
        };
    }
}
