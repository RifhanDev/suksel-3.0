<?php

namespace Database\Seeders;

use App\Tender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Data ujian untuk halaman Cut Off (/cut-off).
 *
 * Cipta kumpulan syarikat ujian (jika belum wujud) dan sertakan 20-30 daripadanya
 * (rawak) untuk SETIAP tender yang berstatus_process_id = 8 (Selesai Penilaian
 * Pembuka — fasa Cut Off), supaya team tester tak perlu tambah syarikat satu-satu
 * secara manual untuk setiap tender semasa ujian.
 *
 * Run:
 *   php artisan db:seed --class=CutOffTestDataSeeder
 *
 * Selamat dijalankan berulang kali (idempoten):
 *   - Kumpulan syarikat ujian (kod pendaftaran bermula "CUTOFF-TEST-") dikesan &
 *     digunakan semula — tak cipta syarikat baharu berganda pada setiap run.
 *   - Tender yang SUDAH ADA sertaan syarikat (tender_vendors) dilangkau —
 *     tak tambah/duplikasi data sedia ada.
 *
 * Setiap syarikat: participate=1, cancel_fg=0 (lulus Penilaian Pembuka),
 * harga_tawaran rawak ±20% sekitar Anggaran Jabatan (AJ) tender berkenaan.
 */
class CutOffTestDataSeeder extends Seeder
{
    /**
     * Prefix pendaftaran untuk kesan/guna-semula kumpulan syarikat ujian.
     */
    private const REG_PREFIX = 'CUTOFF-TEST-';

    private const VENDOR_POOL_SIZE = 30;

    private const JOIN_MIN = 20;

    private const JOIN_MAX = 30;

    public function run(): void
    {
        $orgUnitId = DB::table('organization_units')->value('id');
        if (! $orgUnitId) {
            $this->command?->error('CutOffTestDataSeeder: tiada organization_units langsung. Jalankan seeder asas dahulu.');

            return;
        }

        $vendorIds = $this->ensureVendorPool($orgUnitId);
        if (empty($vendorIds)) {
            $this->command?->error('CutOffTestDataSeeder: gagal menyediakan kumpulan syarikat ujian.');

            return;
        }

        $this->command?->info('CutOffTestDataSeeder: kumpulan syarikat ujian sedia (' . count($vendorIds) . ' syarikat).');

        $tenders = Tender::query()
            ->where('status_process_id', 8)
            ->get(['id', 'uuid', 'name', 'anggaran_jabatan', 'price', 'organization_unit_id']);

        if ($tenders->isEmpty()) {
            $this->command?->warn('CutOffTestDataSeeder: tiada tender berstatus_process_id = 8 (fasa Cut Off) buat masa ini.');

            return;
        }

        $seeded = 0;
        $skipped = 0;

        foreach ($tenders as $tender) {
            $alreadyHasVendors = DB::table('tender_vendors')->where('tender_id', $tender->id)->exists();

            if ($alreadyHasVendors) {
                $skipped++;
                $this->command?->line("  - Tender #{$tender->id} ({$tender->name}): dilangkau — sudah ada syarikat sertai.");

                continue;
            }

            $joinCount = $this->joinVendorsToTender($tender, $vendorIds, $orgUnitId);
            $seeded++;
            $this->command?->info("  - Tender #{$tender->id} ({$tender->name}): {$joinCount} syarikat disertakan.");
        }

        $this->command?->info('CutOffTestDataSeeder: siap.');
        $this->command?->info("  Tender diproses : {$seeded}");
        $this->command?->info("  Tender dilangkau: {$skipped} (sudah ada data)");
    }

    /**
     * Sedia (cipta jika belum wujud, guna semula jika sudah wujud) kumpulan
     * syarikat ujian, dikenal pasti melalui prefix pendaftaran.
     *
     * @return list<int>
     */
    private function ensureVendorPool(int $orgUnitId): array
    {
        $existingIds = DB::table('vendors')
            ->where('registration', 'like', self::REG_PREFIX . '%')
            ->pluck('id')
            ->all();

        if (count($existingIds) >= self::VENDOR_POOL_SIZE) {
            return $existingIds;
        }

        $namaFirma = [
            'Nama', 'Bina', 'Maju', 'Sinar', 'Cahaya', 'Bestari', 'Perkasa', 'Anggun', 'Wawasan', 'Prestasi',
            'Global', 'Utama', 'Sejahtera', 'Gemilang', 'Setia', 'Mulia', 'Ceria', 'Harmoni', 'Ikram', 'Sri',
            'Bumi', 'Delta', 'Vista', 'Metro', 'Prima', 'Sentosa', 'Amanah', 'Pinnacle', 'Zenith', 'Elite',
        ];
        $jenisFirma = ['Sdn Bhd', 'Enterprise', 'Resources', 'Trading', 'Holdings', 'Group', 'Corporation'];

        $needed = self::VENDOR_POOL_SIZE - count($existingIds);
        $startAt = count($existingIds) + 1;

        for ($i = $startAt; $i < $startAt + $needed; $i++) {
            $nama = $namaFirma[array_rand($namaFirma)] . ' ' . $namaFirma[array_rand($namaFirma)] . ' ' . $jenisFirma[array_rand($jenisFirma)];
            $regNo = self::REG_PREFIX . str_pad((string) $i, 3, '0', STR_PAD_LEFT);

            $existingIds[] = DB::table('vendors')->insertGetId([
                'registration' => $regNo,
                'name' => $nama,
                'organization_type' => 'Sdn Bhd',
                'organization_unit_id' => $orgUnitId,
                'officer_name' => 'Wakil Ujian ' . $i,
                'officer_designation' => 'Pengarah Urusan',
                'officer_email' => 'wakil.ujian' . $i . '@cutofftest.local',
                'officer_tel' => '012-' . random_int(1000000, 9999999),
                'completed' => 1,
                'expiry_date' => now()->addYear()->format('Y-m-d'),
                'blacklisted_until' => '1970-01-01',
                'registration_paid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $existingIds;
    }

    /**
     * Sertakan 20-30 syarikat (rawak) daripada kumpulan ke satu tender, dengan
     * harga_tawaran rawak ±20% sekitar Anggaran Jabatan tender berkenaan.
     *
     * @param  list<int>  $vendorPoolIds
     */
    private function joinVendorsToTender(Tender $tender, array $vendorPoolIds, int $fallbackOrgUnitId): int
    {
        $baseline = (float) ($tender->anggaran_jabatan ?: $tender->price ?: 100000);
        $joinCount = min(random_int(self::JOIN_MIN, self::JOIN_MAX), count($vendorPoolIds));
        $chosenVendorIds = (array) array_rand(array_flip($vendorPoolIds), $joinCount);

        $transactionId = DB::table('transactions')->insertGetId([
            'type' => 'cut_off_test_seed',
            'method' => 'seeder',
            'status' => 'success',
            'organization_unit_id' => $tender->organization_unit_id ?: $fallbackOrgUnitId,
            'amount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rows = [];
        foreach ($chosenVendorIds as $vendorId) {
            $factor = random_int(80, 120) / 100;
            $hargaTawaran = round($baseline * $factor, 2);

            $rows[] = [
                'tender_id' => $tender->id,
                'vendor_id' => $vendorId,
                'transaction_id' => $transactionId,
                'ref_number' => $tender->name . ' ONLINE ' . $vendorId,
                'price' => $tender->price ?: 0,
                'amount' => $tender->price ?: 0,
                'participate' => 1,
                'submitted' => 1,
                'cancel_fg' => 0,
                'harga_tawaran' => $hargaTawaran,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tender_vendors')->insert($rows);

        return count($rows);
    }
}
