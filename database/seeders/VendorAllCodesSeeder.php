<?php

namespace Database\Seeders;

use App\VendorCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Memberikan SEMUA kod bidang MOF dan CIDB kepada setiap vendor dummy.
 *
 * Jalankan selepas DummyVendorSeeder:
 *   php artisan db:seed --class=VendorAllCodesSeeder
 *
 * Tujuannya supaya setiap vendur dummy layak untuk mana-mana tender semasa
 * ujian, tanpa perlu memadankan kod satu per satu.
 *
 * Struktur yang ditulis mengikut apa yang aplikasi BACA, bukan apa yang mudah
 * ditulis — kedua-duanya berbeza di sini:
 *
 *   MOF  → satu baris code_vendor per kod, code_type='mof', parent_id=NULL.
 *
 *   CIDB → bersarang. Baris induk code_type='cidb-g' (gred), kemudian kod
 *          bidang sebagai anak dengan code_type='cidb' dan parent_id menunjuk
 *          kepada baris gred itu. Perhatikan percanggahan nama: jenis dalam
 *          jadual `codes` ialah 'cidb-c', tetapi code_type dalam `code_vendor`
 *          mestilah 'cidb' — itu yang Vendor::getCidbCodesAttribute() dan
 *          padanan kelayakan tender cari.
 *
 * Sarang itu bukan hiasan. Tender::(padanan kelayakan) mencari baris 'cidb-g'
 * vendor yang sepadan dengan gred yang tender minta, kemudian hanya menerima
 * kod bidang yang parent_id-nya salah satu baris gred tersebut. Kod bidang yang
 * ditulis rata (tanpa induk) tidak akan pernah melayakkan vendor.
 *
 * Kod bidang dilampirkan di bawah SETIAP gred yang wujud, kerana tender berbeza
 * meminta gred berbeza; melampirkan di bawah satu gred sahaja akan menyebabkan
 * vendor gagal layak untuk tender yang meminta gred yang lain.
 *
 * Seeder ini idempoten — menjalankannya semula tidak menghasilkan pendua.
 */
class VendorAllCodesSeeder extends Seeder
{
    /** Bilangan baris setiap kali insert pukal, supaya tidak melebihi had paket MySQL. */
    private const CHUNK = 500;

    public function run(): void
    {
        $vendorIds = $this->targetVendorIds();

        if ($vendorIds === []) {
            $this->command?->error(
                'VendorAllCodesSeeder: tiada vendor dummy dijumpai. Jalankan DummyVendorSeeder dahulu.'
            );

            return;
        }

        $mofCodeIds   = $this->codeIds('mof');
        $gradeCodeIds = $this->codeIds('cidb-g');
        $cidbCodeIds  = $this->codeIds('cidb-c');

        if ($mofCodeIds === [] && $cidbCodeIds === []) {
            $this->command?->error('VendorAllCodesSeeder: jadual codes kosong untuk mof dan cidb-c.');

            return;
        }

        $this->command?->info(sprintf(
            'VendorAllCodesSeeder: %d vendor, %d kod MOF, %d kod bidang CIDB, %d gred CIDB.',
            count($vendorIds),
            count($mofCodeIds),
            count($cidbCodeIds),
            count($gradeCodeIds)
        ));

        if ($gradeCodeIds === [] && $cidbCodeIds !== []) {
            $this->command?->warn(
                '  Tiada gred (codes.type=cidb-g) — kod bidang CIDB dilangkau, kerana tanpa '
                .'baris gred induk ia tidak akan melayakkan vendor untuk mana-mana tender.'
            );
        }

        $mofAdded  = 0;
        $cidbAdded = 0;

        foreach ($vendorIds as $vendorId) {
            $mofAdded  += $this->attachMof($vendorId, $mofCodeIds);
            $cidbAdded += $this->attachCidb($vendorId, $gradeCodeIds, $cidbCodeIds);
        }

        $this->command?->info(sprintf(
            'VendorAllCodesSeeder: siap. %d baris MOF dan %d baris CIDB ditambah.',
            $mofAdded,
            $cidbAdded
        ));
    }

    /**
     * Vendor yang dicipta DummyVendorSeeder, dikenal pasti melalui domain emel
     * pegawainya. Domain diambil daripada seeder itu sendiri supaya kedua-duanya
     * kekal selari apabila domain berubah.
     *
     * @return list<int>
     */
    private function targetVendorIds(): array
    {
        return DB::table('vendors')
            ->where('officer_email', 'like', '%@'.DummyVendorSeeder::EMAIL_DOMAIN)
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @return list<int>
     */
    private function codeIds(string $type): array
    {
        return DB::table('codes')
            ->where('type', $type)
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @param  list<int>  $codeIds
     */
    private function attachMof(int $vendorId, array $codeIds): int
    {
        $existing = DB::table('code_vendor')
            ->where('vendor_id', $vendorId)
            ->where('code_type', 'mof')
            ->pluck('code_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $missing = array_diff($codeIds, $existing);

        if ($missing === []) {
            return 0;
        }

        $rows = [];

        foreach ($missing as $codeId) {
            $rows[] = [
                'parent_id' => null,
                'vendor_id' => $vendorId,
                'code_id'   => $codeId,
                'code_type' => 'mof',
            ];
        }

        $this->insertRows($rows);

        return count($rows);
    }

    /**
     * @param  list<int>  $gradeCodeIds
     * @param  list<int>  $cidbCodeIds
     */
    private function attachCidb(int $vendorId, array $gradeCodeIds, array $cidbCodeIds): int
    {
        if ($gradeCodeIds === [] || $cidbCodeIds === []) {
            return 0;
        }

        $added = 0;

        foreach ($gradeCodeIds as $gradeCodeId) {
            $parentId = $this->ensureGradeRow($vendorId, $gradeCodeId);

            $existing = DB::table('code_vendor')
                ->where('vendor_id', $vendorId)
                ->where('code_type', 'cidb')
                ->where('parent_id', $parentId)
                ->pluck('code_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $missing = array_diff($cidbCodeIds, $existing);

            if ($missing === []) {
                continue;
            }

            $rows = [];

            foreach ($missing as $codeId) {
                $rows[] = [
                    'parent_id' => $parentId,
                    'vendor_id' => $vendorId,
                    'code_id'   => $codeId,
                    'code_type' => 'cidb',
                ];
            }

            $this->insertRows($rows);
            $added += count($rows);
        }

        return $added;
    }

    /**
     * Baris gred vendor, dicipta jika belum ada. Id-nya menjadi parent_id bagi
     * kod bidang di bawahnya.
     */
    private function ensureGradeRow(int $vendorId, int $gradeCodeId): int
    {
        $existing = DB::table('code_vendor')
            ->where('vendor_id', $vendorId)
            ->where('code_type', 'cidb-g')
            ->where('code_id', $gradeCodeId)
            ->value('id');

        if ($existing !== null) {
            return (int) $existing;
        }

        $vendorCode = new VendorCode;
        $vendorCode->parent_id = null;
        $vendorCode->vendor_id = $vendorId;
        $vendorCode->code_id   = $gradeCodeId;
        $vendorCode->code_type = 'cidb-g';
        $vendorCode->save();

        return (int) $vendorCode->id;
    }

    /**
     * @param  list<array<string, int|string|null>>  $rows
     */
    private function insertRows(array $rows): void
    {
        // Insert terus melalui query builder: VendorCode mematikan timestamps dan
        // baris sedia ada dalam jadual ini memang menyimpan created_at/updated_at
        // sebagai NULL, jadi tiada apa yang perlu diisi.
        foreach (array_chunk($rows, self::CHUNK) as $chunk) {
            DB::table('code_vendor')->insert($chunk);
        }
    }
}
