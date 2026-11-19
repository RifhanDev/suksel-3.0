<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Label status untuk senarai tender — PAPARAN SAHAJA.
 * Tidak mengubah status_process_id atau apa-apa data.
 *
 * Jenis tender:
 *  1. Penilaian    — kaedah online_penilaian / percuma: proses penuh 1–16.
 *  2. Iklan        — kaedah online: syarikat beli dokumen, berhenti selepas tarikh tutup.
 *  3. Iklan Sahaja — kaedah manual: iklan sahaja, tiada pembelian dalam sistem.
 *  Tender 2.0 (status_process_id & kaedah_dokumen_id NULL) ikut tetapan 2.0 sendiri:
 *    only_advertise = 0 → jenis 2, only_advertise = 1 → jenis 3.
 *  Pembelian / Lantikan Terus guna kod status sendiri (1, 5, 31, 32, 33).
 *
 * Jenis 1 dalam tempoh iklan: status boleh naik ke 6/7 sebelum tarikh tutup
 * (syarikat pertama hantar dokumen / jemputan mesyuarat). Bila status masih 5,
 * label utama = "Telah Disiarkan"; bila >= 6, label utama = peringkat semasa
 * dan "Telah Disiarkan" menjadi baris kecil sehingga tarikh tutup.
 */
final class TenderStatusPresenter
{
    public const KIND_PENILAIAN = 'penilaian';

    public const KIND_IKLAN = 'iklan';

    public const KIND_IKLAN_SAHAJA = 'iklan_sahaja';

    public const KIND_TERUS = 'terus';

    public const SEDANG_DIIKLANKAN = 'Telah Disiarkan';

    public const IKLAN_DITUTUP = 'Iklan Telah Ditutup';

    /** Peringkat semasa (langkah yang sedang menunggu) bagi jenis 1. */
    private const PENILAIAN_STAGE_LABELS = [
        TenderProcessStatus::PELANTIKAN_JAWATANKUASA => 'Spesifikasi Teknikal',
        TenderProcessStatus::SPESIFIKASI_TEKNIKAL => 'Spesifikasi Kewangan',
        TenderProcessStatus::SPESIFIKASI_KEWANGAN => 'Penyediaan Iklan',
        TenderProcessStatus::HANTAR_DOKUMEN_SYARIKAT => 'Penyediaan Mesyuarat',
        TenderProcessStatus::PENYEDIAAN_MESYUARAT => 'Penilaian Pembuka',
        TenderProcessStatus::PENILAIAN_PEMBUKA => 'Cut Off',
        TenderProcessStatus::CUT_OFF => 'Penilaian Teknikal',
        TenderProcessStatus::PENILAIAN_TEKNIKAL => 'Penilaian Kewangan',
        TenderProcessStatus::PENILAIAN_KEWANGAN => 'Perakuan Jabatan',
        TenderProcessStatus::PERAKUAN_JABATAN => 'Jawatankuasa Perolehan',
        TenderProcessStatus::JAWATANKUASA_PEROLEHAN => 'Penyediaan Surat Niat',
        TenderProcessStatus::PENYEDIAAN_SURAT_NIAT => 'Penyediaan Surat SST',
        TenderProcessStatus::PENYEDIAAN_SURAT_SST => 'Surat SST Dihantar',
        TenderProcessStatus::SELESAI => 'Selesai',
    ];

    private const TERUS_LABELS = [
        1 => 'Draf',
        5 => 'Disiarkan',
        31 => 'Cut Off',
        32 => 'Syarikat Dipilih',
        33 => 'Diterima',
    ];

    private const EBIDDING_STAGE_LABELS = [
        1 => 'Bidaan: Agency Admin',
        2 => 'Bidaan: Vendor',
        3 => 'Bidaan: Agency Admin (Semakan)',
        4 => 'Bidaan: Admin SULP',
    ];

    /**
     * Lajur yang perlu dipilih oleh query senarai supaya label tepat tanpa query tambahan.
     *
     * @return list<string>
     */
    public static function requiredColumns(): array
    {
        return [
            'type',
            'status_process_id',
            'kaedah_dokumen_id',
            'only_advertise',
            'approver_id',
            'submission_datetime',
            'advertise_stop_date',
            'is_ebidding',
            'ebidding_process_stage_id',
        ];
    }

    public static function kind($tender): string
    {
        if (in_array((string) $tender->type, ['pembelian_terus', 'lantikan_terus'], true)) {
            return self::KIND_TERUS;
        }

        if ($tender->isLegacy20()) {
            return $tender->only_advertise ? self::KIND_IKLAN_SAHAJA : self::KIND_IKLAN;
        }

        $code = (string) ($tender->kaedahDokumen?->code ?? '');

        return match ($code) {
            'online' => self::KIND_IKLAN,
            'manual' => self::KIND_IKLAN_SAHAJA,
            default => self::KIND_PENILAIAN,
        };
    }

    /**
     * @return array{main: string, sub: ?string, tags: list<string>}
     */
    public static function present($tender): array
    {
        $kind = self::kind($tender);
        $legacy = $kind !== self::KIND_TERUS && $tender->isLegacy20();
        $status = (int) ($tender->status_process_id ?? 0);

        $tags = [];
        if ($legacy) {
            $tags[] = '2.0';
        }
        if ($kind === self::KIND_IKLAN_SAHAJA) {
            $tags[] = 'Iklan Sahaja';
        }

        if ($kind === self::KIND_TERUS) {
            return ['main' => self::TERUS_LABELS[$status] ?? 'Dalam Proses', 'sub' => null, 'tags' => $tags];
        }

        // Jenis 2 & 3 (termasuk tender 2.0): hanya iklan dibuka / ditutup.
        if ($kind !== self::KIND_PENILAIAN) {
            if (! self::isPublished($tender, $legacy)) {
                return ['main' => $legacy ? 'Belum Disiarkan' : 'Penyediaan Iklan', 'sub' => null, 'tags' => $tags];
            }

            return ['main' => self::iklanLabel($tender), 'sub' => null, 'tags' => $tags];
        }

        // Jenis 1: sebelum siar.
        if ($status <= TenderProcessStatus::CIPTA_TENDER) {
            if ($tender->hasCompleteJawatankuasa()) {
                $main = 'Pelantikan Jawatankuasa';
            } elseif ($tender->hasAnyJawatankuasa()) {
                $main = 'Draf Pelantikan Jawatankuasa';
            } else {
                $main = 'Tiada Jawatan Kuasa';
            }

            return ['main' => $main, 'sub' => null, 'tags' => $tags];
        }

        if ($status < TenderProcessStatus::PENYEDIAAN_IKLAN || ! self::isPublished($tender, false)) {
            return ['main' => self::PENILAIAN_STAGE_LABELS[$status] ?? 'Penyediaan Iklan', 'sub' => null, 'tags' => $tags];
        }

        // Status 5: masih dalam iklan — label utama ikut tarikh tutup.
        if ($status === TenderProcessStatus::PENYEDIAAN_IKLAN) {
            return ['main' => self::iklanLabel($tender), 'sub' => null, 'tags' => $tags];
        }

        // Status >= 6: label utama = peringkat semasa; "Telah Disiarkan" jadi baris kecil.
        $main = self::PENILAIAN_STAGE_LABELS[$status] ?? 'Dalam Proses';
        $sub = self::isClosed($tender) ? null : self::SEDANG_DIIKLANKAN;

        $ebiddingStage = (int) ($tender->ebidding_process_stage_id ?? 0);
        if ($tender->is_ebidding && $ebiddingStage > 0 && in_array($status, [TenderProcessStatus::PENILAIAN_KEWANGAN, TenderProcessStatus::PERAKUAN_JABATAN], true)) {
            $sub = self::EBIDDING_STAGE_LABELS[$ebiddingStage] ?? $sub;
        }

        return ['main' => $main, 'sub' => $sub, 'tags' => $tags];
    }

    public static function html($tender): string
    {
        $p = self::present($tender);

        $html = '<div class="tender-status-cell">' . e($p['main']);

        if (! empty($p['sub'])) {
            $html .= '<small style="display:block;color:#64748b;margin-top:2px;">' . e($p['sub']) . '</small>';
        }

        if (! empty($p['tags'])) {
            $html .= '<div>';
            foreach ($p['tags'] as $tag) {
                $html .= '<span style="display:inline-block;margin:4px 4px 0 0;padding:1px 6px;border-radius:4px;font-size:11px;line-height:16px;background:#eef2f7;color:#475569;">'
                    . e($tag) . '</span>';
            }
            $html .= '</div>';
        }

        return $html . '</div>';
    }

    /** Disiar: 2.0 → approver_id diisi; 3.0 → approver_id diisi dan status >= 5. */
    private static function isPublished($tender, bool $legacy): bool
    {
        if (empty($tender->approver_id)) {
            return false;
        }

        return $legacy || (int) ($tender->status_process_id ?? 0) >= TenderProcessStatus::PENYEDIAAN_IKLAN;
    }

    private static function iklanLabel($tender): string
    {
        return self::isClosed($tender) ? self::IKLAN_DITUTUP : self::SEDANG_DIIKLANKAN;
    }

    /** Tarikh tutup = submission_datetime (lajur "Tarikh Tutup", sama dengan senarai syarikat). */
    private static function isClosed($tender): bool
    {
        if (! empty($tender->submission_datetime)) {
            return Carbon::parse($tender->submission_datetime)->lte(Carbon::now());
        }

        if (! empty($tender->advertise_stop_date)) {
            return Carbon::parse($tender->advertise_stop_date)->endOfDay()->lte(Carbon::now());
        }

        return false;
    }
}
