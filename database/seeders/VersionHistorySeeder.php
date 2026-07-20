<?php

namespace Database\Seeders;

use App\Models\VersionHistory;
use Illuminate\Database\Seeder;

class VersionHistorySeeder extends Seeder
{
    /**
     * Seed the version_histories table with initial data.
     */
    public function run(): void
    {
        $records = [
            [
                'version' => '1.0',
                'released_at' => '2015-06-08',
                'notes' => "Live",
            ],
            [
                'version' => '1.1',
                'released_at' => '2015-11-01',
                'notes' => "Masukkan Syarikat Tidak Layak Tender / Sebut Harga Menggunakan Fungsi Kebenaran Khas\nCetak Resit Pembayaran Untuk Tender / Sebut Harga Secara Pukal",
            ],
            [
                'version' => '1.2',
                'released_at' => '2016-10-14',
                'notes' => "Halang Transaksi Pembayaran Agensi\nMengemaskini semula data-data maklumat hubungan kontraktor\nSemakan pendaftaran syarikat\nBuang agensi pengesahan\nPaparan kod bidang CIDB\nMaklumat ralat\nMuat turun laporan dalam format Excel\nLaporan syarikat berdasarkan kod bidang\nLaporan produktiviti Staff\nPaparan notifikasi kod bidang tidak layak\nPenukaran alamat emel oleh pegawai syarikat\nPaparan status pembayaran sewaktu transaksi\nMedan \"Daerah\" dalam data syarikat\nMuat naik kehadiran syarikat ke taklimat & lawatan tapak",
            ],
            [
                'version' => '1.3',
                'released_at' => '2017-09-04',
                'notes' => "Penambahbaikan Modul UPEN\nMenolak pendaftaran kontraktor\nMenerima pendaftaran kontraktor\nMenolak permintaan perubahan kontraktor\nMenerima permintaan perubahan kontraktor\nMenyenarai hitam kontraktor\nTetapan peranan",
            ],
        ];

        foreach ($records as $record) {
            VersionHistory::firstOrCreate(
                ['version' => $record['version'], 'released_at' => $record['released_at']],
                $record
            );
        }
    }
}
