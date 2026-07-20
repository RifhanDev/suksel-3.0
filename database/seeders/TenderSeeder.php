<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenderSeeder extends Seeder
{
    public function run(): void
    {
        $orgUnitId = DB::table('organization_units')->value('id');
        $userId = DB::table('users')->value('id');

        if (! $orgUnitId || ! $userId) {
            $this->command?->error('TenderSeeder: run OrganizationUnitsSeeder and CreateAdminUser first.');
            return;
        }

        $kaedahTender = DB::table('ref_kaedah_perolehans')->where('name', 'Tender')->value('id');
        $kaedahSebutHarga = DB::table('ref_kaedah_perolehans')->where('name', 'Sebut Harga')->value('id');
        $kategoriBekalan = DB::table('ref_kategori_jenis_perolehans')->where('name', 'Bekalan')->value('id');
        $kategoriKerja = DB::table('ref_kategori_jenis_perolehans')->where('name', 'Kerja')->value('id');
        $detailBekalan = DB::table('ref_type_of_perolehans')
            ->where('ref_kategori_jenis_perolehan_id', $kategoriBekalan)
            ->where('name', 'Bekalan')
            ->value('id');
        $detailKerja = DB::table('ref_type_of_perolehans')
            ->where('ref_kategori_jenis_perolehan_id', $kategoriKerja)
            ->where('name', 'Bangunan')
            ->value('id');
        $jenisTender = DB::table('ref_type_of_tenders')->where('name', 'Konvensional')->value('id');
        $jenisKontrak = DB::table('ref_type_of_contracts')->where('name', 'Bukan Kementerian')->value('id');
        $lokaliti = DB::table('ref_lokalitis')->value('id');

        $now = now();

        $tenders = [
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Sebut Harga Bekalan Peralatan ICT',
                'ref_number' => 'SH-2026-001',
                'no_tender' => 'QT260000000001',
                'creator_id' => $userId,
                'officer_id' => $userId,
                'organization_unit_id' => $orgUnitId,
                'price' => 50000.00,
                'harga_indikatif' => 50000.00,
                'anggaran_jabatan' => 48000.00,
                'type' => 'quotation',
                'kaedah_perolehan_id' => $kaedahSebutHarga,
                'kategori_perolehan_id' => $kategoriBekalan,
                'kategori_perolehan_detail_id' => $detailBekalan,
                'jenis_tender_id' => $jenisTender,
                'jenis_kontrak_id' => $jenisKontrak,
                'lokaliti_id' => $lokaliti,
                'sumber_peruntukan' => 'mengurus',
                'terbuka_kepada' => 'semua',
                'document_start_date' => $now->copy()->addDays(7)->toDateString(),
                'document_stop_date' => $now->copy()->addDays(30)->toDateString(),
                'submission_datetime' => $now->copy()->addDays(35)->format('Y-m-d H:i:s'),
                'tarikh_dicipta' => $now->toDateString(),
                'status_process_id' => 1,
                'jawatankuasa' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Tender Kerja Naik Taraf Bangunan',
                'ref_number' => 'T-2026-002',
                'no_tender' => 'T260000000002',
                'creator_id' => $userId,
                'officer_id' => $userId,
                'organization_unit_id' => $orgUnitId,
                'price' => 250000.00,
                'harga_indikatif' => 250000.00,
                'anggaran_jabatan' => 240000.00,
                'type' => 'tender',
                'kaedah_perolehan_id' => $kaedahTender,
                'kategori_perolehan_id' => $kategoriKerja,
                'kategori_perolehan_detail_id' => $detailKerja,
                'jenis_tender_id' => $jenisTender,
                'jenis_kontrak_id' => $jenisKontrak,
                'lokaliti_id' => $lokaliti,
                'sumber_peruntukan' => 'pembangunan',
                'terbuka_kepada' => 'semua',
                'document_start_date' => $now->copy()->addDays(14)->toDateString(),
                'document_stop_date' => $now->copy()->addDays(45)->toDateString(),
                'submission_datetime' => $now->copy()->addDays(50)->format('Y-m-d H:i:s'),
                'tarikh_dicipta' => $now->toDateString(),
                'status_process_id' => 1,
                'jawatankuasa' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($tenders as $tender) {
            $exists = DB::table('tenders')->where('no_tender', $tender['no_tender'])->exists();
            if (! $exists) {
                DB::table('tenders')->insert($tender);
            }
        }

        $this->command?->info('TenderSeeder: sample tenders created (or already exist).');
    }
}