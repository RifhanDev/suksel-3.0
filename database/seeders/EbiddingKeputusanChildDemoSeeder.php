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
 * Demo for Keputusan Mesyuarat: mother + child specs, harga bidaan + info-icon breakdown.
 *
 * Run:
 *   php artisan db:seed --class=EbiddingKeputusanChildDemoSeeder
 *
 * Open (admin):
 *   /eBidding/{id}  and  /jawatankuasa-perolehan/form?tender={id}
 */
class EbiddingKeputusanChildDemoSeeder extends Seeder
{
    public const REF = 'UAT/EBID/KEPUTUSAN-CHILD';

    public function run(): void
    {
        foreach ([
            'ebidding_jadual_bidaans',
            'jawatankuasa_perolehan_pemilihan_headers',
            'jawatankuasa_perolehan_pemilihan_items',
            'jawatankuasa_perolehan_pemilihan_petenders',
            'spesifikasi_kerja_headers',
            'spesifikasi_kerja_items',
        ] as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->error("Missing table {$table}.");

                return;
            }
        }

        $creator = User::query()->where('email', 'admin@suksel.com')->first()
            ?? User::query()->whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->orderBy('id')->first();

        if (! $creator) {
            $this->command?->error('No Admin user found.');

            return;
        }

        if (! $creator->organization_unit_id) {
            $ouId = OrganizationUnit::query()->value('id');
            if ($ouId) {
                $creator->organization_unit_id = $ouId;
                $creator->save();
            }
        }

        $vendors = Vendor::query()
            ->whereIn('id', function ($q) {
                $q->select('vendor_id')
                    ->from('users')
                    ->whereNotNull('vendor_id')
                    ->where('email', 'like', '%@dummy.stos.local');
            })
            ->orderBy('id')
            ->limit(3)
            ->get();

        if ($vendors->isEmpty()) {
            $vendors = Vendor::query()->orderBy('id')->limit(3)->get();
        }

        if ($vendors->count() < 2) {
            $this->command?->error('Need at least 2 vendors. Run DummyVendorSeeder first.');

            return;
        }

        $kategoriId = Schema::hasTable('ref_kategori_jenis_perolehans')
            ? (DB::table('ref_kategori_jenis_perolehans')->where('name', 'Bekalan')->value('id')
                ?? DB::table('ref_kategori_jenis_perolehans')->orderBy('id')->value('id'))
            : 2;

        $tenderId = $this->upsertTender($creator, $kategoriId);
        $this->resetChildren($tenderId);
        $this->seedHeader($tenderId);
        $childItemIds = $this->seedSpecsAndPemilihan($tenderId, $vendors);
        $this->seedTenderVendors($tenderId, $vendors);
        $this->seedJadualEnded($tenderId);
        $this->seedBids($tenderId, $vendors, $childItemIds);

        $this->command?->info('Keputusan Mesyuarat child-demo ready.');
        $this->command?->info("  Tender : #{$tenderId} (" . self::REF . ')');
        $this->command?->info("  eBidding : /eBidding/{$tenderId}");
        $this->command?->info("  JP form  : /jawatankuasa-perolehan/form?tender={$tenderId}");
        $this->command?->info('  Login    : admin@suksel.com / admin123');
    }

    private function upsertTender(User $creator, $kategoriId): int
    {
        $existing = DB::table('tenders')->where('ref_number', self::REF)->first();
        $open = Carbon::now()->subDays(5)->toDateString();
        $close = Carbon::now()->subDay()->toDateString();

        $payload = [
            'name' => 'UAT Keputusan Mesyuarat — Ibu & Anak Spec',
            'ref_number' => self::REF,
            'user_id' => $creator->id,
            'organization_unit_id' => $creator->organization_unit_id,
            'anggaran_jabatan' => 85000,
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
            'ebidding_process_stage_id' => 3,
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

        $cols = array_flip(Schema::getColumnListing('tenders'));
        $payload = array_filter($payload, static fn ($_, $k) => isset($cols[$k]), ARRAY_FILTER_USE_BOTH);

        if ($existing) {
            DB::table('tenders')->where('id', $existing->id)->update($payload);

            return (int) $existing->id;
        }

        $payload['uuid'] = (string) Str::uuid();
        $payload['no_tender'] = 'EBID' . now()->format('ymd') . 'CHILD';
        $payload['created_at'] = now();
        if (! isset($cols['uuid'])) {
            unset($payload['uuid']);
        }
        if (! isset($cols['no_tender'])) {
            unset($payload['no_tender']);
        }

        return (int) DB::table('tenders')->insertGetId($payload);
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

        if (Schema::hasTable('tender_vendors')) {
            DB::table('tender_vendors')->where('tender_id', $tenderId)->delete();
        }

        $headerIds = DB::table('spesifikasi_kerja_headers')->where('tender_id', $tenderId)->pluck('id');
        if ($headerIds->isNotEmpty()) {
            DB::table('spesifikasi_kerja_items')->whereIn('spesifikasi_kerja_header_id', $headerIds)->delete();
            DB::table('spesifikasi_kerja_headers')->whereIn('id', $headerIds)->delete();
        }
    }

    private function seedHeader(int $tenderId): void
    {
        $headerPayload = [
            'tender_id' => $tenderId,
            'keputusan_mesyuarat' => 'Lulus untuk Bidaan',
            'kaedah_memuktamadkan_pembekal' => 'Bidaan',
            'pemilihan_berdasarkan' => '1 item',
            'loi_loa_disediakan_oleh' => 'Urusetia atau Setiausaha Sebut Harga',
            'bil_mesyuarat' => 'UAT-CHILD-01/2026',
            'no_kod' => 'UAT-CHILD',
            'sahkan_layak_bidaan' => 1,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('jawatankuasa_perolehan_pemilihan_headers', 'catatan_bidaan')) {
            $headerPayload['catatan_bidaan'] = 'Demo: bidaan untuk penjimatan harga item anak.';
        }
        DB::table('jawatankuasa_perolehan_pemilihan_headers')->insert($headerPayload);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     * @return list<int>
     */
    private function seedSpecsAndPemilihan(int $tenderId, $vendors): array
    {
        $headerCols = array_flip(Schema::getColumnListing('spesifikasi_kerja_headers'));
        $headerPayload = [
            'uuid' => (string) Str::uuid(),
            'tender_id' => $tenderId,
            'status' => 'submitted',
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $headerPayload = array_filter($headerPayload, static fn ($_, $k) => isset($headerCols[$k]), ARRAY_FILTER_USE_BOTH);
        $headerId = (int) DB::table('spesifikasi_kerja_headers')->insertGetId($headerPayload);

        $tree = [
            [
                'nama' => 'Peralatan ICT',
                'children' => [
                    ['nama' => 'Laptop Notebook (i5 / 16GB / 512GB)', 'unit' => 'Unit', 'qty' => 10, 'prices' => [22000, 21500, 22800]],
                    ['nama' => 'Monitor LED 24 inch', 'unit' => 'Unit', 'qty' => 10, 'prices' => [4500, 4300, 4700]],
                    ['nama' => 'Mouse Wireless', 'unit' => 'Unit', 'qty' => 20, 'prices' => [350, 320, 380]],
                ],
            ],
            [
                'nama' => 'Perisian & Lesen',
                'children' => [
                    ['nama' => 'Microsoft 365 Business (10 user)', 'unit' => 'Lesen', 'qty' => 1, 'prices' => [4800, 4650, 5100]],
                    ['nama' => 'Antivirus Endpoint (10 device)', 'unit' => 'Lesen', 'qty' => 1, 'prices' => [2100, 1980, 2250]],
                ],
            ],
            [
                'nama' => 'Rangkaian & Aksesori',
                'children' => [
                    ['nama' => 'Switch 24-Port Gigabit', 'unit' => 'Unit', 'qty' => 2, 'prices' => [3200, 3050, 3400]],
                    ['nama' => 'Kabel UTP Cat6 (kotak)', 'unit' => 'Kotak', 'qty' => 5, 'prices' => [900, 860, 950]],
                    ['nama' => 'Patch Panel 24-Port', 'unit' => 'Unit', 'qty' => 2, 'prices' => [650, 620, 700]],
                ],
            ],
        ];

        $itemCols = array_flip(Schema::getColumnListing('spesifikasi_kerja_items'));
        $childItemIds = [];
        $sort = 0;

        foreach ($tree as $parent) {
            $sort++;
            $parentPayload = [
                'uuid' => (string) Str::uuid(),
                'spesifikasi_kerja_header_id' => $headerId,
                'parent_id' => null,
                'sort_order' => $sort,
                'nama_item' => $parent['nama'],
                'spesifikasi' => $parent['nama'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $parentPayload = array_filter($parentPayload, static fn ($_, $k) => isset($itemCols[$k]), ARRAY_FILTER_USE_BOTH);
            $parentId = (int) DB::table('spesifikasi_kerja_items')->insertGetId($parentPayload);

            foreach ($parent['children'] as $child) {
                $sort++;
                $childPayload = [
                    'uuid' => (string) Str::uuid(),
                    'spesifikasi_kerja_header_id' => $headerId,
                    'parent_id' => $parentId,
                    'sort_order' => $sort,
                    'nama_item' => $child['nama'],
                    'spesifikasi' => $child['nama'],
                    'kuantiti' => $child['qty'],
                    'unit' => $child['unit'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $childPayload = array_filter($childPayload, static fn ($_, $k) => isset($itemCols[$k]), ARRAY_FILTER_USE_BOTH);
                DB::table('spesifikasi_kerja_items')->insert($childPayload);

                $pemilihanId = (int) DB::table('jawatankuasa_perolehan_pemilihan_items')->insertGetId([
                    'tender_id' => $tenderId,
                    'sort_order' => count($childItemIds) + 1,
                    'perihal_item' => $child['nama'],
                    'jenis_item' => 'Bekalan',
                    'unit_ukuran' => $child['unit'],
                    'jenis_harga' => 'Harga Setara',
                    'dibatalkan' => 'Tidak',
                    'pembekal_dipilih' => 0,
                    'kuantiti' => $child['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $childItemIds[] = $pemilihanId;

                foreach ($vendors->values() as $vIndex => $vendor) {
                    $price = $child['prices'][$vIndex] ?? $child['prices'][0];
                    $row = [
                        'pemilihan_item_id' => $pemilihanId,
                        'sort_order' => $vIndex + 1,
                        'bil_label' => ($vIndex + 1) . '/' . $vendors->count(),
                        'status_bumiputra' => $vIndex === 0 ? 'Ya' : 'Tidak',
                        'harga_tawaran' => $price,
                        'jumlah_skor' => 85 - ($vIndex * 3),
                        'kedudukan_penilaian' => $vIndex + 1,
                        'status_mof' => 'Aktif',
                        'tindakan_disiplin' => '—',
                        'keputusan_urusetia' => 'Layak',
                        'catatan_urusetia' => 'Demo petender',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    if (Schema::hasColumn('jawatankuasa_perolehan_pemilihan_petenders', 'vendor_id')) {
                        $row['vendor_id'] = $vendor->id;
                    }
                    DB::table('jawatankuasa_perolehan_pemilihan_petenders')->insert($row);
                }
            }
        }

        return $childItemIds;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     */
    private function seedTenderVendors(int $tenderId, $vendors): void
    {
        if (! Schema::hasTable('tender_vendors')) {
            return;
        }

        $cols = array_flip(Schema::getColumnListing('tender_vendors'));
        // Must match sum of child harga tawaran per vendor.
        $totals = [
            22000 + 4500 + 350 + 4800 + 2100 + 3200 + 900 + 650,
            21500 + 4300 + 320 + 4650 + 1980 + 3050 + 860 + 620,
            22800 + 4700 + 380 + 5100 + 2250 + 3400 + 950 + 700,
        ];

        foreach ($vendors->values() as $vIndex => $vendor) {
            $amount = $totals[$vIndex] ?? $totals[0];
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
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $row = array_filter($row, static fn ($_, $k) => isset($cols[$k]), ARRAY_FILTER_USE_BOTH);
            DB::table('tender_vendors')->insert($row);
        }
    }

    private function seedJadualEnded(int $tenderId): void
    {
        $start = Carbon::now()->subDays(3);
        $end = Carbon::now()->subHours(2);
        $row = [
            'tender_id' => $tenderId,
            'tarikh_bidaan_mula' => $start->toDateString(),
            'masa_bidaan_mula' => $start->format('H:i'),
            'tarikh_bidaan_tamat' => $end->toDateString(),
            'masa_bidaan_tamat' => $end->format('H:i'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('ebidding_jadual_bidaans', 'started_at')) {
            $row['started_at'] = $start;
        }
        DB::table('ebidding_jadual_bidaans')->insert($row);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Vendor>  $vendors
     * @param  list<int>  $childItemIds
     */
    private function seedBids(int $tenderId, $vendors, array $childItemIds): void
    {
        if (! Schema::hasTable('ebidding_vendor_bid_items') || $childItemIds === []) {
            return;
        }

        $hasCarried = Schema::hasColumn('ebidding_vendor_bid_items', 'is_carried_forward');
        $vendors = $vendors->values();

        foreach ($vendors as $vIndex => $vendor) {
            foreach ($childItemIds as $i => $itemId) {
                $old = (float) DB::table('jawatankuasa_perolehan_pemilihan_petenders')
                    ->where('pemilihan_item_id', $itemId)
                    ->where('vendor_id', $vendor->id)
                    ->value('harga_tawaran');

                $isNew = false;
                $price = $old;

                if ($vIndex === 0) {
                    $isNew = true;
                    $price = round($old * 0.92, 2);
                } elseif ($vIndex === 1) {
                    $isNew = $i < (int) ceil(count($childItemIds) / 2);
                    $price = $isNew ? round($old * 0.95, 2) : $old;
                }

                $row = [
                    'tender_id' => $tenderId,
                    'vendor_id' => $vendor->id,
                    'pemilihan_item_id' => $itemId,
                    'bid_price' => $price,
                    'submitted_at' => now()->subHours(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if ($hasCarried) {
                    $row['is_carried_forward'] = $isNew ? 0 : 1;
                }
                DB::table('ebidding_vendor_bid_items')->insert($row);
            }
        }
    }
}
