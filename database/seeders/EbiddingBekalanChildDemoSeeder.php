<?php

namespace Database\Seeders;

use App\Http\Controllers\EbiddingController;
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
 * Bekalan eBidding demo: ICT equipment (laptop / monitor / mouse).
 * Technical specification parents only on vendor bid page (no SpesifikasiKerja).
 *
 *   php artisan db:seed --class=EbiddingBekalanChildDemoSeeder
 *
 * Login:
 *   Admin  — admin@suksel.com
 *   Vendor — vendor01@dummy.stos.local
 */
class EbiddingBekalanChildDemoSeeder extends Seeder
{
    private const REF = 'UAT/EBID/BEKALAN-CHILD';

    /** Primary demo vendor that must always be eligible to bid. */
    private const DEMO_VENDOR_ID = 7;

    /** Anggaran keseluruhan (RM). */
    private const ANGGARAN = 185500;

    public function run(): void
    {
        foreach ([
            'ebidding_jadual_bidaans',
            'jawatankuasa_perolehan_pemilihan_headers',
            'jawatankuasa_perolehan_pemilihan_items',
            'jawatankuasa_perolehan_pemilihan_petenders',
            'technical_checklist_headers',
            'technical_specification_documents',
            'technical_specification_items',
            'technical_specification_details',
        ] as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->error("Missing table {$table}. Run migrations first.");

                return;
            }
        }

        if (! Schema::hasColumn('tenders', 'is_ebidding')
            || ! Schema::hasColumn('tenders', 'ebidding_process_stage_id')) {
            $this->command?->error('tenders.is_ebidding / ebidding_process_stage_id missing. Run migrations.');

            return;
        }

        $creator = User::query()->where('email', 'admin@suksel.com')->first()
            ?? User::query()->whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->orderBy('id')->first();

        if (! $creator) {
            $this->command?->error('No Admin user found (admin@suksel.com).');

            return;
        }

        if (! $creator->organization_unit_id) {
            $ouId = OrganizationUnit::query()->value('id');
            if ($ouId) {
                $creator->organization_unit_id = $ouId;
                $creator->save();
            }
        }

        $demoVendor = $this->ensureDemoVendor();
        if (! $demoVendor) {
            $this->command?->error('Could not ensure vendor id ' . self::DEMO_VENDOR_ID . '.');

            return;
        }

        $others = Vendor::query()
            ->where('id', '!=', self::DEMO_VENDOR_ID)
            ->whereIn('id', function ($q) {
                $q->select('vendor_id')
                    ->from('users')
                    ->whereNotNull('vendor_id')
                    ->where('email', 'like', '%@dummy.stos.local');
            })
            ->orderBy('id')
            ->limit(2)
            ->get();

        if ($others->isEmpty()) {
            $others = Vendor::query()
                ->where('id', '!=', self::DEMO_VENDOR_ID)
                ->orderBy('id')
                ->limit(2)
                ->get();
        }

        // Vendor 7 first so petender sort / labels stay stable.
        $vendors = collect([$demoVendor])->merge($others)->unique('id')->values();

        $kategoriId = Schema::hasTable('ref_kategori_jenis_perolehans')
            ? (DB::table('ref_kategori_jenis_perolehans')->where('name', 'Bekalan')->value('id')
                ?? DB::table('ref_kategori_jenis_perolehans')->where('name', 'like', '%Bekalan%')->value('id')
                ?? 1)
            : 1;

        $tenderId = $this->upsertTender($creator, $kategoriId);
        $this->resetChildren($tenderId);
        $this->seedPemilihanHeader($tenderId);
        $this->seedTechnicalSpecification($tenderId, $creator->id);
        $this->seedTenderVendors($tenderId, $vendors);
        $this->seedEligibles($tenderId, $vendors);
        $this->seedJadualOpen($tenderId);

        $tender = \App\Tender::query()->findOrFail($tenderId);
        app(EbiddingController::class)->syncPemilihanItemsFromSpecification($tender);

        $itemIds = DB::table('jawatankuasa_perolehan_pemilihan_items')
            ->where('tender_id', $tenderId)
            ->orderBy('sort_order')
            ->pluck('id');

        // Base offered price per item (before eBidding).
        $prices = [142500, 31500, 11500];
        foreach ($itemIds as $idx => $itemId) {
            foreach ($vendors->values() as $vIdx => $vendor) {
                $base = $prices[$idx] ?? 10000;
                DB::table('jawatankuasa_perolehan_pemilihan_petenders')->updateOrInsert(
                    [
                        'pemilihan_item_id' => $itemId,
                        'vendor_id' => $vendor->id,
                    ],
                    [
                        'sort_order' => $vIdx + 1,
                        'bil_label' => ($vIdx + 1) . '/' . $vendors->count(),
                        'status_bumiputra' => 'Ya',
                        'harga_tawaran' => $base - ($vIdx * 500),
                        'jumlah_skor' => 85 - ($vIdx * 3),
                        'kedudukan_penilaian' => $vIdx + 1,
                        'status_mof' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $vendorUser = User::query()
            ->where('vendor_id', self::DEMO_VENDOR_ID)
            ->orderBy('id')
            ->first();

        $this->command?->info('Bekalan eBidding ICT demo ready.');
        $this->command?->info("  Tender #{$tenderId} — {$tender->name}");
        $this->command?->info('  REF: ' . self::REF);
        $this->command?->info('  Items: Laptop / Monitor / Mouse');
        $this->command?->info('  Admin:  admin@suksel.com');
        $this->command?->info('  Vendor id: ' . self::DEMO_VENDOR_ID . ' (' . $demoVendor->name . ')');
        $this->command?->info("  eBidding: /eBidding/{$tenderId}");
        $this->command?->info("  JP form:  /jawatankuasa-perolehan/form?tender={$tenderId}");
        if ($vendorUser) {
            $this->command?->info('  Vendor login: ' . $vendorUser->email);
        } else {
            $this->command?->warn('  No user linked to vendor_id=' . self::DEMO_VENDOR_ID);
        }
    }

    /**
     * Ensure vendors.id = 7 exists and is usable for this demo (server + local).
     */
    private function ensureDemoVendor(): ?Vendor
    {
        $vendor = Vendor::query()->find(self::DEMO_VENDOR_ID);
        if ($vendor) {
            return $vendor;
        }

        $linkedUser = User::query()->where('vendor_id', self::DEMO_VENDOR_ID)->orderBy('id')->first();
        $name = $linkedUser
            ? trim((string) ($linkedUser->name ?: 'Demo Vendor ' . self::DEMO_VENDOR_ID))
            : 'Demo Vendor ICT (ID ' . self::DEMO_VENDOR_ID . ')';

        $now = now();
        $row = [
            'id' => self::DEMO_VENDOR_ID,
            'name' => $name,
            'registration' => 'DEMO-V' . self::DEMO_VENDOR_ID,
            'completed' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $cols = array_flip(Schema::getColumnListing('vendors'));
        $row = array_filter($row, static fn ($_, $k) => isset($cols[$k]), ARRAY_FILTER_USE_BOTH);

        DB::table('vendors')->insert($row);

        return Vendor::query()->find(self::DEMO_VENDOR_ID);
    }

    private function upsertTender(User $creator, mixed $kategoriId): int
    {
        $existing = DB::table('tenders')->where('ref_number', self::REF)->first();
        $open = Carbon::now()->subDays(3)->toDateString();
        $close = Carbon::now()->addDays(14)->toDateString();

        $payload = [
            'name' => 'BEKALAN PERALATAN ICT (LAPTOP, MONITOR & MOUSE) BAGI PEJABAT SUK SELANGOR',
            'ref_number' => self::REF,
            'user_id' => $creator->id,
            'creator_id' => $creator->id,
            'organization_unit_id' => $creator->organization_unit_id ?? 22,
            'anggaran_jabatan' => self::ANGGARAN,
            'price' => self::ANGGARAN,
            'advertise_start_date' => $open,
            'advertise_stop_date' => $close,
            'document_start_date' => $open,
            'document_stop_date' => $close,
            'submission_datetime' => $close . ' 12:00:00',
            'tarikh_dicipta' => $open,
            'kategori_perolehan_id' => $kategoriId,
            'sumber_peruntukan' => 'pembangunan',
            'terbuka_kepada' => 'semua',
            'zon_lokasi' => 0,
            'only_bumiputera' => 0,
            'type' => 'quotation',
            'status_process_id' => TenderProcessStatus::PENILAIAN_KEWANGAN,
            'is_ebidding' => 1,
            'ebidding_process_stage_id' => 2,
            'publish_prices' => 0,
            'publish_shortlists' => 0,
            'publish_winner' => 0,
            'invitation' => 0,
            'briefing_required' => 0,
            'allow_exception' => 0,
            'only_selangor' => 0,
            'only_advertise' => 0,
            'approver_id' => $creator->id,
            'updated_at' => now(),
        ];

        $cols = array_flip(Schema::getColumnListing('tenders'));
        $payload = array_filter($payload, static fn ($_, $k) => isset($cols[$k]), ARRAY_FILTER_USE_BOTH);

        if ($existing) {
            DB::table('tenders')->where('id', $existing->id)->update($payload);

            return (int) $existing->id;
        }

        $payload['uuid'] = (string) Str::uuid();
        $payload['no_tender'] = 'EBID' . now()->format('ymd') . 'ICT';
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
        $itemIds = DB::table('jawatankuasa_perolehan_pemilihan_items')->where('tender_id', $tenderId)->pluck('id');
        if ($itemIds->isNotEmpty()) {
            DB::table('jawatankuasa_perolehan_pemilihan_petenders')->whereIn('pemilihan_item_id', $itemIds)->delete();
            if (Schema::hasTable('ebidding_vendor_bid_items')) {
                DB::table('ebidding_vendor_bid_items')->whereIn('pemilihan_item_id', $itemIds)->delete();
            }
        }
        DB::table('jawatankuasa_perolehan_pemilihan_items')->where('tender_id', $tenderId)->delete();
        DB::table('jawatankuasa_perolehan_pemilihan_headers')->where('tender_id', $tenderId)->delete();

        if (Schema::hasTable('ebidding_vendor_bid_items')) {
            DB::table('ebidding_vendor_bid_items')->where('tender_id', $tenderId)->delete();
        }
        if (Schema::hasTable('ebidding_jadual_bidaans')) {
            DB::table('ebidding_jadual_bidaans')->where('tender_id', $tenderId)->delete();
        }
        if (Schema::hasTable('tender_vendors')) {
            DB::table('tender_vendors')->where('tender_id', $tenderId)->delete();
        }
        if (Schema::hasTable('tender_eligibles')) {
            DB::table('tender_eligibles')->where('tender_id', $tenderId)->delete();
        }

        if (Schema::hasTable('spesifikasi_kerja_headers')) {
            $headerIds = DB::table('spesifikasi_kerja_headers')->where('tender_id', $tenderId)->pluck('id');
            if ($headerIds->isNotEmpty() && Schema::hasTable('spesifikasi_kerja_items')) {
                $kerjaItemIds = DB::table('spesifikasi_kerja_items')
                    ->whereIn('spesifikasi_kerja_header_id', $headerIds)
                    ->pluck('id');
                if ($kerjaItemIds->isNotEmpty() && Schema::hasColumn('spesifikasi_kerja_items', 'parent_id')) {
                    DB::table('spesifikasi_kerja_items')->whereIn('parent_id', $kerjaItemIds)->delete();
                }
                DB::table('spesifikasi_kerja_items')->whereIn('spesifikasi_kerja_header_id', $headerIds)->delete();
                DB::table('spesifikasi_kerja_headers')->whereIn('id', $headerIds)->delete();
            }
        }

        if (Schema::hasTable('technical_checklist_headers')) {
            $techHeaderIds = DB::table('technical_checklist_headers')->where('tender_id', $tenderId)->pluck('id');
            if ($techHeaderIds->isNotEmpty()) {
                $docIds = DB::table('technical_checklist_items')
                    ->whereIn('technical_checklist_header_id', $techHeaderIds)
                    ->whereNotNull('specification_document_id')
                    ->pluck('specification_document_id')
                    ->unique()
                    ->filter();

                DB::table('technical_checklist_items')->whereIn('technical_checklist_header_id', $techHeaderIds)->delete();
                DB::table('technical_checklist_headers')->whereIn('id', $techHeaderIds)->delete();

                foreach ($docIds as $docId) {
                    $specItemIds = DB::table('technical_specification_items')
                        ->where('technical_specification_document_id', $docId)
                        ->pluck('id');
                    if ($specItemIds->isNotEmpty()) {
                        DB::table('technical_specification_details')
                            ->whereIn('technical_specification_item_id', $specItemIds)
                            ->delete();
                        DB::table('technical_specification_items')
                            ->whereIn('id', $specItemIds)
                            ->delete();
                    }
                    DB::table('technical_specification_documents')->where('id', $docId)->delete();
                }
            }
        }
    }

    private function seedPemilihanHeader(int $tenderId): void
    {
        $headerPayload = [
            'tender_id' => $tenderId,
            'keputusan_mesyuarat' => 'Lulus untuk Bidaan',
            'kaedah_memuktamadkan_pembekal' => 'Bidaan',
            'pemilihan_berdasarkan' => 'Item',
            'loi_loa_disediakan_oleh' => 'Urusetia atau Setiausaha Sebut Harga',
            'bil_mesyuarat' => 'JKP/ICT/01/2026',
            'no_kod' => 'JKP-ICT-01',
            'sahkan_layak_bidaan' => 1,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('jawatankuasa_perolehan_pemilihan_headers', 'catatan_bidaan')) {
            $headerPayload['catatan_bidaan'] = 'Bidaan bagi bekalan peralatan ICT — laptop, monitor dan mouse.';
        }
        DB::table('jawatankuasa_perolehan_pemilihan_headers')->insert($headerPayload);
    }

    private function seedTechnicalSpecification(int $tenderId, int $userId): void
    {
        $headerId = (int) DB::table('technical_checklist_headers')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'tender_id' => $tenderId,
            'max_score' => 100,
            'passing_score' => 70,
            'passing_percentage' => 70,
            'status' => 'submitted',
            'submitted_at' => now(),
            'submitted_by' => $userId,
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $docId = (int) DB::table('technical_specification_documents')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'title' => 'Spesifikasi Teknikal Bekalan Peralatan ICT',
            'item_type' => 'bekalan',
            'specification_type' => 'teknikal',
            'status' => 'submitted',
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('technical_checklist_items')->insert([
            'uuid' => (string) Str::uuid(),
            'technical_checklist_header_id' => $headerId,
            'source_type' => 'specification_document',
            'title' => 'Dokumen Spesifikasi Teknikal',
            'mechanism' => 'online_form',
            'vendor_action' => 'view_specification',
            'score' => 0,
            'status' => 'active',
            'sort_order' => 1,
            'specification_document_id' => $docId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $groups = [
            [
                'title' => 'Laptop HP ProBook 450 G10 (atau setara)',
                'quantity' => 25,
                'unit' => 'UNIT',
                'details' => [
                    'Pemproses Intel Core i7 Generasi ke-13 (minimum) atau setara AMD Ryzen 7.',
                    'RAM 16GB DDR4/DDR5 (boleh dinaik taraf) dan storan SSD NVMe minimum 512GB.',
                    'Skrin 15.6" FHD (1920x1080), sistem pengendalian Windows 11 Pro (lesen sah), garanti minimum 3 tahun on-site.',
                ],
            ],
            [
                'title' => 'Monitor LED 24" Full HD',
                'quantity' => 25,
                'unit' => 'UNIT',
                'details' => [
                    'Saiz paparan 23.8"–24" IPS/VA, resolusi Full HD 1920x1080, kadar muat semula minimum 75Hz.',
                    'Port HDMI dan DisplayPort / VGA (adapter dibenarkan), pemegang boleh laras ketinggian atau VESA mount.',
                    'Termasuk kabel HDMI dan penyesuai kuasa; garanti minimum 2 tahun.',
                ],
            ],
            [
                'title' => 'Mouse Optical USB (Wired)',
                'quantity' => 50,
                'unit' => 'UNIT',
                'details' => [
                    'Mouse optical berwayar USB plug-and-play, resolusi minimum 1000 DPI.',
                    'Sesuai untuk kegunaan pejabat harian; warna hitam; garanti minimum 1 tahun.',
                ],
            ],
        ];

        foreach ($groups as $i => $group) {
            $itemId = (int) DB::table('technical_specification_items')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'technical_specification_document_id' => $docId,
                'title' => $group['title'],
                'quantity' => $group['quantity'],
                'unit' => $group['unit'],
                'sort_order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($group['details'] as $dIdx => $description) {
                DB::table('technical_specification_details')->insert([
                    'uuid' => (string) Str::uuid(),
                    'technical_specification_item_id' => $itemId,
                    'description' => $description,
                    'response_type' => 'yes_no',
                    'score_mode' => 'none',
                    'max_score' => null,
                    'sort_order' => $dIdx + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedTenderVendors(int $tenderId, $vendors): void
    {
        if (! Schema::hasTable('tender_vendors')) {
            return;
        }

        foreach ($vendors as $idx => $vendor) {
            DB::table('tender_vendors')->insert([
                'tender_id' => $tenderId,
                'vendor_id' => $vendor->id,
                'participate' => 1,
                'amount' => self::ANGGARAN,
                'harga_tawaran' => self::ANGGARAN - ($idx * 2500),
                'ref_number' => 'UAT/ICT/' . ($idx + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedEligibles(int $tenderId, $vendors): void
    {
        if (! Schema::hasTable('tender_eligibles')) {
            return;
        }

        foreach ($vendors as $vendor) {
            DB::table('tender_eligibles')->insert([
                'tender_id' => $tenderId,
                'vendor_id' => $vendor->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedJadualOpen(int $tenderId): void
    {
        $start = Carbon::now()->subMinutes(5);
        $end = Carbon::now()->addHours(6);

        $row = [
            'tender_id' => $tenderId,
            'tarikh_bidaan_mula' => $start->toDateString(),
            'masa_bidaan_mula' => $start->format('H:i:s'),
            'tarikh_bidaan_tamat' => $end->toDateString(),
            'masa_bidaan_tamat' => $end->format('H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('ebidding_jadual_bidaans', 'started_at')) {
            $row['started_at'] = $start;
        }
        if (Schema::hasColumn('ebidding_jadual_bidaans', 'submitted_at')) {
            $row['submitted_at'] = $start;
        }

        DB::table('ebidding_jadual_bidaans')->insert($row);
    }
}
