<?php

namespace Database\Seeders;

use App\Tender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Data ujian untuk halaman Cut Off (/cut-off).
 *
 * Cipta kumpulan syarikat ujian (jika belum wujud) dan TAMBAH (top-up) syarikat
 * ujian ke SETIAP tender yang berstatus_process_id = 8 (Selesai Penilaian
 * Pembuka — fasa Cut Off) sehingga cukup 20-30 syarikat, supaya team tester tak
 * perlu tambah syarikat satu-satu secara manual semasa ujian.
 *
 * Run:
 *   php artisan db:seed --class=CutOffTestDataSeeder
 *
 * PENTING — tender di server sebenar mungkin sudah ada 1-2 syarikat BENAR yang
 * membeli tender secara betul (bukan data ujian). Seeder ini TIDAK mengabaikan
 * tender sebegitu — ia TOP-UP baki syarikat ujian sehingga jumlah keseluruhan
 * (syarikat sebenar + syarikat ujian) cukup 20-30, tanpa mengubah/memadam
 * syarikat sebenar yang sudah ada.
 *
 * Selamat dijalankan berulang kali (idempoten):
 *   - Kumpulan syarikat ujian (kod pendaftaran bermula "CUTOFF-TEST-") dikesan &
 *     digunakan semula — tak cipta syarikat baharu berganda pada setiap run.
 *   - Tender yang SUDAH ADA >= 20 syarikat (sebenar + ujian) dilangkau — tak
 *     tambah lagi.
 *   - Syarikat ujian yang sudah menyertai sesuatu tender tidak akan
 *     ditambah/duplikasi semula ke tender yang sama.
 *
 * Setiap syarikat ujian yang ditambah: participate=1, cancel_fg=0 (lulus
 * Penilaian Pembuka), harga_tawaran rawak ±20% sekitar Anggaran Jabatan (AJ)
 * tender berkenaan.
 */
class CutOffTestDataSeeder extends Seeder
{
    /**
     * Prefix pendaftaran untuk kesan/guna-semula kumpulan syarikat ujian.
     */
    private const REG_PREFIX = 'CUTOFF-TEST-';

    private const VENDOR_POOL_SIZE = 30;

    /**
     * Tender dengan bilangan syarikat (sebenar + ujian) >= nilai ini dianggap
     * sudah cukup dan dilangkau.
     */
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

        $topped = 0;
        $skipped = 0;

        foreach ($tenders as $tender) {
            $existingVendorIds = DB::table('tender_vendors')
                ->where('tender_id', $tender->id)
                ->pluck('vendor_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $existingCount = count($existingVendorIds);

            if ($existingCount >= self::JOIN_MIN) {
                $skipped++;
                $this->command?->line("  - Tender #{$tender->id} ({$tender->name}): dilangkau — sudah ada {$existingCount} syarikat (cukup).");

                continue;
            }

            $added = $this->topUpVendorsForTender($tender, $vendorIds, $existingVendorIds, $orgUnitId);

            if ($added === 0) {
                $skipped++;
                $this->command?->warn("  - Tender #{$tender->id} ({$tender->name}): tiada syarikat ujian tersedia untuk ditambah (kumpulan telah digunakan sepenuhnya untuk tender ini).");

                continue;
            }

            $topped++;
            $this->command?->info("  - Tender #{$tender->id} ({$tender->name}): {$added} syarikat ujian ditambah (asal {$existingCount} → jumlah " . ($existingCount + $added) . ').');
        }

        $this->command?->info('CutOffTestDataSeeder: siap.');
        $this->command?->info("  Tender di-top-up: {$topped}");
        $this->command?->info("  Tender dilangkau: {$skipped}");
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
     * Tambah (top-up) syarikat ujian ke satu tender sehingga jumlah keseluruhan
     * (sebenar + ujian) mencapai sasaran rawak 20-30. Syarikat ujian yang sudah
     * menyertai tender ini (dari run sebelumnya) TIDAK dipilih semula.
     *
     * @param  list<int>  $vendorPoolIds     Kumpulan penuh syarikat ujian.
     * @param  list<int>  $existingVendorIds Syarikat (sebenar + ujian) yang sudah menyertai tender ini.
     */
    private function topUpVendorsForTender(
        Tender $tender,
        array $vendorPoolIds,
        array $existingVendorIds,
        int $fallbackOrgUnitId
    ): int {
        $target = random_int(self::JOIN_MIN, self::JOIN_MAX);
        $needed = $target - count($existingVendorIds);

        if ($needed <= 0) {
            return 0;
        }

        // Jangan pilih syarikat ujian yang sudah menyertai tender ini.
        $availablePoolIds = array_values(array_diff($vendorPoolIds, $existingVendorIds));
        $needed = min($needed, count($availablePoolIds));

        if ($needed <= 0) {
            return 0;
        }

        $chosenVendorIds = $needed === count($availablePoolIds)
            ? $availablePoolIds
            : (array) array_rand(array_flip($availablePoolIds), $needed);

        $baseline = (float) ($tender->anggaran_jabatan ?: $tender->price ?: 100000);

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
