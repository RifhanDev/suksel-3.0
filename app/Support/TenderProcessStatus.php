<?php

namespace App\Support;

final class TenderProcessStatus
{
    public const CIPTA_TENDER = 1;

    public const PELANTIKAN_JAWATANKUASA = 2;

    public const SPESIFIKASI_TEKNIKAL = 3;

    public const SPESIFIKASI_KEWANGAN = 4;

    public const PENYEDIAAN_IKLAN = 5;

    public const HANTAR_DOKUMEN_SYARIKAT = 6;

    public const PENYEDIAAN_MESYUARAT = 7;

    public const PENILAIAN_PEMBUKA = 8;

    public const CUT_OFF = 9;

    public const PENILAIAN_TEKNIKAL = 10;

    public const PENILAIAN_KEWANGAN = 11;

    public const PERAKUAN_JABATAN = 12;

    public const JAWATANKUASA_PEROLEHAN = 13;

    public const PENYEDIAAN_SURAT_NIAT = 14;

    public const PENYEDIAAN_SURAT_SST = 15;

    public static function label(int $status): string
    {
        return match ($status) {
            self::CIPTA_TENDER => 'Selesai Cipta Tender',
            self::PELANTIKAN_JAWATANKUASA => 'Selesai Pelantikan Jawatankuasa',
            self::SPESIFIKASI_TEKNIKAL => 'Selesai Spesifikasi Teknikal',
            self::SPESIFIKASI_KEWANGAN => 'Selesai Spesifikasi Kewangan',
            self::PENYEDIAAN_IKLAN => 'Selesai Penyediaan Iklan',
            self::HANTAR_DOKUMEN_SYARIKAT => 'Selesai Hantar Dokumen Syarikat',
            self::PENYEDIAAN_MESYUARAT => 'Selesai Penyediaan Mesyuarat',
            self::PENILAIAN_PEMBUKA => 'Selesai Penilaian Pembuka',
            self::CUT_OFF => 'Selesai Cut Off',
            self::PENILAIAN_TEKNIKAL => 'Selesai Penilaian Teknikal',
            self::PENILAIAN_KEWANGAN => 'Selesai Penilaian Kewangan',
            self::PERAKUAN_JABATAN => 'Selesai Perakuan Jabatan',
            self::JAWATANKUASA_PEROLEHAN => 'Selesai Jawatankuasa Perolehan (Pemilihan syarikat)',
            self::PENYEDIAAN_SURAT_NIAT => 'Selesai Penyediaan Surat Niat',
            self::PENYEDIAAN_SURAT_SST => 'Selesai Penyediaan Surat SST',
            default => 'Tidak Diketahui',
        };
    }

    /** @return list<int> */
    public static function pengurusanSpesifikasiListStatuses(): array
    {
        return [self::PELANTIKAN_JAWATANKUASA, self::SPESIFIKASI_TEKNIKAL];
    }

    public static function penyediaanIklanListStatus(): int
    {
        return self::SPESIFIKASI_KEWANGAN;
    }

    public static function penyediaanMesyuaratListStatus(): int
    {
        return self::HANTAR_DOKUMEN_SYARIKAT;
    }

    public static function penilaianPembukaListStatus(): int
    {
        return self::PENYEDIAAN_MESYUARAT;
    }

    public static function cutOffListStatus(): int
    {
        return self::PENILAIAN_PEMBUKA;
    }

    public static function penilaianTeknikalListStatus(): int
    {
        return self::CUT_OFF;
    }

    public static function penilaianKewanganListStatus(): int
    {
        return self::PENILAIAN_TEKNIKAL;
    }

    public static function perakuanJabatanListStatus(): int
    {
        return self::PENILAIAN_KEWANGAN;
    }

    public static function jawatankuasaPerolehanListStatus(): int
    {
        return self::PERAKUAN_JABATAN;
    }

    public static function penyediaanSuratNiatListStatus(): int
    {
        return self::JAWATANKUASA_PEROLEHAN;
    }

    public static function penyediaanSuratSstListStatus(): int
    {
        return self::PENYEDIAAN_SURAT_NIAT;
    }
}
