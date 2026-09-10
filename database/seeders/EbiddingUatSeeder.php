<?php

namespace Database\Seeders;

use App\OrganizationUnit;
use App\Support\TenderProcessStatus;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * UAT sample: eBidding tender with open vendor bidding window.
 *
 * Run:
 *   php artisan db:seed --class=EbiddingUatSeeder
 *
 * Login:
 *   Admin  — admin@suksel.com / admin123  → /eBidding/index
 *   Vendor — vendor01@dummy.stos.local / Vendor@12345 → /eBidding/{id}
 */
class EbiddingUatSeeder extends Seeder
{
    public const REF_VENDOR_OPEN = 'UAT/EBID/001';

    public const REF_AGENCY_PREP = 'UAT/EBID/002';

    public function run(): void
    {
        foreach ([
            'ebidding_jadual_bidaans',
            'jawatankuasa_perolehan_pemilihan_headers',
            'jawatankuasa_perolehan_pemilihan_items',
            'jawatankuasa_perolehan_pemilihan_petenders',
        ] as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->error("Missing table {$table}. Run eBidding / JP pemilihan migrations first.");

                return;
            }
        }

        if (! Schema::hasColumn('tenders', 'is_ebidding')
            || ! Schema::hasColumn('tenders', 'ebidding_process_stage_id')) {
            $this->command?->error('tenders.is_ebidding / ebidding_process_stage_id missing. Run migrations.');

            return;
        }

        $creator = User::query()->where('email', 'admin@suksel.com')->first()
            ?? User::query()
                ->whereHas('roles', fn ($q) => $q->where('name', 'Admin'))
                ->orderBy('id')
                ->first();

        if (! $creator) {
            $this->command?->error('No Admin user found. Run CreateAdminUser first.');

            return;
        }

        if (! $creator->organization_unit_id) {
            $ouId = OrganizationUnit::query()->value('id');
            if (! $ouId) {
                $this->command?->error('No organization unit found.');

                return;
            }
            $creator->organization_unit_id = $ouId;
            $creator->save();
        }

        $vendors = Vendor::query()->orderBy('id')->limit(3)->get();
        if ($vendors->isEmpty()) {
            $this->command?->error('No vendors found. Run DummyVendorSeeder first.');

            return;
        }

        $kategoriId = Schema::hasTable('ref_kategori_jenis_perolehans')
            ? (DB::table('ref_kategori_jenis_perolehans')->where('name', 'Bekalan')->value('id')
                ?? DB::table('ref_kategori_jenis_perolehans')->orderBy('id')->value('id'))
            : 2;

        // 1) Open vendor bidding window (stage 2)
        $openId = $this->seedTender(
            ref: self::REF_VENDOR_OPEN,
            name: 'UAT eBidding — Vendor Window Open',
            noSuffix: 'OPEN01',
            creator: $creator,
            kategoriId: $kategoriId,
            stage: 2,
            vendors: $vendors,
            windowOpen: true,
        );

        // 2) Agency prep (stage 1) — jadual / taklimat before mula bidaan
        $prepId = $this->seedTender(
            ref: self::REF_AGENCY_PREP,
            name: 'UAT eBidding — Agency Prep (before mula)',
            noSuffix: 'PREP01',
            creator: $creator,
            kategoriId: $kategoriId,
            stage: 1,
            vendors: $vendors,
            windowOpen: false,
        );

        $this->command?->info('eBidding UAT data ready.');
        $this->command?->info("  Open bidding : #{$openId} (" . self::REF_VENDOR_OPEN . ') → /eBidding/' . $openId);
        $this->command?->info("  Agency prep  : #{$prepId} (" . self::REF_AGENCY_PREP . ') → /eBidding/' . $prepId);
        $this->command?->info('  Index        : /eBidding/index');
        $this->command?->info('  Admin login  : admin@suksel.com');
        $this->command?->info('  Vendor login : vendor01@dummy.stos.local / Vendor@12345');
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     */
    private function seedTender(
        string $ref,
        string $name,
        string $noSuffix,
        User $creator,
        mixed $kategoriId,
        int $stage,
        $vendors,
        bool $windowOpen,
    ): int {
        $today = Carbon::today();
        $open = $today->copy()->subDays(30)->format('Y-m-d');
        $close = $today->copy()->subDays(7)->format('Y-m-d');

        $existing = DB::table('tenders')->where('ref_number', $ref)->first();

        $tenderPayload = [
            'name' => $name,
            'ref_number' => $ref,
            'creator_id' => $creator->id,
            'organization_unit_id' => $creator->organization_unit_id,
            'price' => 50000,
            'harga_indikatif' => 50000,
            'anggaran_jabatan' => 50000,
            'advertise_start_date' => $open,
            'advertise_stop_date' => $close,
            'document_start_date' => $open,
            'document_stop_date' => $close,
            'submission_datetime' => $close . ' 17:00:00',
            'tarikh_dicipta' => $open,
            'kategori_perolehan_id' => $kategoriId,
            'sumber_peruntukan' => 'pembangunan',
            'terbuka_kepada' => 'semua',
            'zon_lokasi' => 0,
            'only_bumiputera' => 0,
            'type' => 'tender',
            'status_process_id' => TenderProcessStatus::PENILAIAN_KEWANGAN,
            'is_ebidding' => 1,
            'ebidding_process_stage_id' => $stage,
            'publish_prices' => 0,
            'publish_shortlists' => 0,
            'publish_winner' => 0,
            'invitation' => 0,
            'briefing_required' => 0,
            'allow_exception' => 0,
            'only_selangor' => 0,
            'only_advertise' => 0,
            'jawatankuasa' => 0,
            'lawatan_tapak' => 0,
            'penilaian_fizikal' => 0,
            'mof_cidb_rule' => 'AND',
            'updated_at' => now(),
        ];

        $tenderCols = array_flip(Schema::getColumnListing('tenders'));
        $tenderPayload = array_filter(
            $tenderPayload,
            static fn ($_, $key) => isset($tenderCols[$key]),
            ARRAY_FILTER_USE_BOTH
        );

        if ($existing) {
            DB::table('tenders')->where('id', $existing->id)->update($tenderPayload);
            $tenderId = (int) $existing->id;
            $this->command?->info("Updated tender #{$tenderId} ({$ref}).");
        } else {
            $tenderPayload['uuid'] = (string) Str::uuid();
            $tenderPayload['no_tender'] = 'EBID' . now()->format('ymd') . $noSuffix;
            $tenderPayload['created_at'] = now();
            if (! isset($tenderCols['uuid'])) {
                unset($tenderPayload['uuid']);
            }
            if (! isset($tenderCols['no_tender'])) {
                unset($tenderPayload['no_tender']);
            }
            $tenderId = (int) DB::table('tenders')->insertGetId($tenderPayload);
            $this->command?->info("Created tender #{$tenderId} ({$ref}).");
        }

        $this->resetChildren($tenderId);
        $itemIds = $this->seedPemilihan($tenderId, $vendors);
        $this->seedTenderVendors($tenderId, $vendors, $itemIds);
        $this->seedEligibles($tenderId, $vendors);
        $this->seedJadual($tenderId, $windowOpen);

        return $tenderId;
    }

    private function resetChildren(int $tenderId): void
    {
        $itemIds = DB::table('jawatankuasa_perolehan_pemilihan_items')
            ->where('tender_id', $tenderId)
            ->pluck('id');

        if ($itemIds->isNotEmpty()) {
            DB::table('jawatankuasa_perolehan_pemilihan_petenders')
                ->whereIn('pemilihan_item_id', $itemIds)
                ->delete();
        }

        DB::table('jawatankuasa_perolehan_pemilihan_items')->where('tender_id', $tenderId)->delete();
        DB::table('jawatankuasa_perolehan_pemilihan_headers')->where('tender_id', $tenderId)->delete();

        if (Schema::hasTable('ebidding_vendor_bid_items')) {
            DB::table('ebidding_vendor_bid_items')->where('tender_id', $tenderId)->delete();
        }

        DB::table('ebidding_jadual_bidaans')->where('tender_id', $tenderId)->delete();

        if (Schema::hasTable('tender_eligibles')) {
            DB::table('tender_eligibles')->where('tender_id', $tenderId)->delete();
        }

        if (Schema::hasTable('tender_vendors')) {
            DB::table('tender_vendors')->where('tender_id', $tenderId)->delete();
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     * @return list<int>
     */
    private function seedPemilihan(int $tenderId, $vendors): array
    {
        DB::table('jawatankuasa_perolehan_pemilihan_headers')->insert([
            'tender_id' => $tenderId,
            'keputusan_mesyuarat' => 'Lulus untuk Bidaan',
            'kaedah_memuktamadkan_pembekal' => 'Bidaan',
            'pemilihan_berdasarkan' => 'Harga Terendah',
            'loi_loa_disediakan_oleh' => 'Urusetia',
            'bil_mesyuarat' => 'UAT-EBID-01/2026',
            'no_kod' => 'UAT-EBID',
            'sahkan_layak_bidaan' => 1,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $itemSpecs = [
            [
                'perihal_item' => 'Laptop Notebook (i5 / 16GB / 512GB)',
                'jenis_item' => 'Bekalan',
                'unit_ukuran' => 'Unit',
                'jenis_harga' => 'Harga Setara',
                'kuantiti' => 10,
            ],
            [
                'perihal_item' => 'Monitor LED 24 inch',
                'jenis_item' => 'Bekalan',
                'unit_ukuran' => 'Unit',
                'jenis_harga' => 'Harga Setara',
                'kuantiti' => 10,
            ],
        ];

        $basePrices = [
            [22000.00, 4500.00],
            [21500.00, 4300.00],
            [22800.00, 4700.00],
        ];

        $itemIds = [];
        foreach ($itemSpecs as $i => $spec) {
            $itemId = (int) DB::table('jawatankuasa_perolehan_pemilihan_items')->insertGetId([
                'tender_id' => $tenderId,
                'sort_order' => $i + 1,
                'perihal_item' => $spec['perihal_item'],
                'jenis_item' => $spec['jenis_item'],
                'unit_ukuran' => $spec['unit_ukuran'],
                'jenis_harga' => $spec['jenis_harga'],
                'dibatalkan' => 'Tidak',
                'pembekal_dipilih' => 0,
                'kuantiti' => $spec['kuantiti'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $itemIds[] = $itemId;

            foreach ($vendors->values() as $vIndex => $vendor) {
                $price = $basePrices[$vIndex][$i] ?? $basePrices[0][$i];
                $petender = [
                    'pemilihan_item_id' => $itemId,
                    'sort_order' => $vIndex + 1,
                    'bil_label' => 'P' . ($vIndex + 1),
                    'status_bumiputra' => $vIndex === 0 ? 'Ya' : 'Tidak',
                    'harga_tawaran' => $price,
                    'jumlah_skor' => 80 - ($vIndex * 2),
                    'kedudukan_penilaian' => $vIndex + 1,
                    'status_mof' => 'Aktif',
                    'tindakan_disiplin' => null,
                    'lembaga_pengarah_file_path' => null,
                    'keputusan_urusetia' => 'Layak',
                    'catatan_urusetia' => 'UAT petender',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (Schema::hasColumn('jawatankuasa_perolehan_pemilihan_petenders', 'vendor_id')) {
                    $petender['vendor_id'] = $vendor->id;
                }

                DB::table('jawatankuasa_perolehan_pemilihan_petenders')->insert($petender);
            }
        }

        return $itemIds;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     * @param  list<int>  $itemIds
     */
    private function seedTenderVendors(int $tenderId, $vendors, array $itemIds): void
    {
        if (! Schema::hasTable('tender_vendors')) {
            return;
        }

        $cols = array_flip(Schema::getColumnListing('tender_vendors'));
        $baseAmounts = [26500.00, 25800.00, 27500.00];

        foreach ($vendors->values() as $vIndex => $vendor) {
            $amount = $baseAmounts[$vIndex] ?? $baseAmounts[0];
            $row = [
                'tender_id' => $tenderId,
                'vendor_id' => $vendor->id,
                'amount' => $amount,
                'harga_tawaran' => $amount,
                'price' => $amount,
                'submitted' => 1,
                'participate' => 1,
                'exception' => 0,
                'winner' => 0,
                'cancel_fg' => 0,
                'is_bumiputera' => $vIndex === 0 ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $row = array_filter(
                $row,
                static fn ($_, $key) => isset($cols[$key]),
                ARRAY_FILTER_USE_BOTH
            );
            DB::table('tender_vendors')->insert($row);
        }

        unset($itemIds); // selection uses petender prices; amount is for vendor "harga sebelum bidaan"
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     */
    private function seedEligibles(int $tenderId, $vendors): void
    {
        if (! Schema::hasTable('tender_eligibles')) {
            return;
        }

        foreach ($vendors as $vendor) {
            DB::table('tender_eligibles')->insert([
                'tender_id' => $tenderId,
                'vendor_id' => $vendor->id,
                'email' => 1,
                'sent_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedJadual(int $tenderId, bool $windowOpen): void
    {
        $today = Carbon::today();

        if ($windowOpen) {
            $row = [
                'tender_id' => $tenderId,
                'tarikh_bidaan_mula' => $today->copy()->subDay()->format('Y-m-d'),
                'masa_bidaan_mula' => '00:00:00',
                'tarikh_bidaan_tamat' => $today->copy()->addDays(7)->format('Y-m-d'),
                'masa_bidaan_tamat' => '23:59:00',
                'started_at' => now()->subDay(),
                'submitted_at' => now()->subDay(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        } else {
            $row = [
                'tender_id' => $tenderId,
                'tarikh_bidaan_mula' => $today->copy()->addDay()->format('Y-m-d'),
                'masa_bidaan_mula' => '09:00:00',
                'tarikh_bidaan_tamat' => $today->copy()->addDays(3)->format('Y-m-d'),
                'masa_bidaan_tamat' => '17:00:00',
                'started_at' => null,
                'submitted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('ebidding_jadual_bidaans')->insert($row);
    }
}
