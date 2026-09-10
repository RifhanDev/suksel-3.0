<?php

namespace Database\Seeders;

use App\OrganizationUnit;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * UAT sample: published Pembelian Terus with vendor offers ready for Cut Off.
 *
 * Run:
 *   php artisan db:seed --class=PembelianTerusCutoffUatSeeder
 */
class PembelianTerusCutoffUatSeeder extends Seeder
{
    public const REF_NUMBER = 'UAT/PT/CUTOFF/001';

    public function run(): void
    {
        foreach (['pembelian_terus_items', 'pembelian_terus_offers', 'pembelian_terus_offer_items'] as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->error("Missing table {$table}. Run migrations first (2027_07_14_000001).");

                return;
            }
        }

        $creator = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'Agency User'))
            ->whereNotNull('organization_unit_id')
            ->orderBy('id')
            ->first()
            ?? User::query()->where('username', 'agencyuser')->first();

        if (! $creator) {
            $this->command?->error('No Agency User found. Create agencyuser first.');

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
        if ($vendors->count() < 2) {
            $this->command?->error('Need at least 2 vendors in vendors table.');

            return;
        }

        $kaedahId = DB::table('ref_kaedah_perolehans')
            ->where('name', 'like', '%Pembelian Terus%')
            ->value('id');

        $kategoriId = Schema::hasTable('ref_kategori_jenis_perolehans')
            ? DB::table('ref_kategori_jenis_perolehans')->where('active', 1)->value('id')
            : null;

        $today = Carbon::today();
        $open = $today->copy()->subDays(7)->format('Y-m-d');
        $close = $today->copy()->subDay()->format('Y-m-d'); // yesterday → cut-off allowed

        $existing = DB::table('tenders')->where('ref_number', self::REF_NUMBER)->first();

        $tenderPayload = [
            'name' => 'UAT Pembelian Terus — Cut Off Sample',
            'ref_number' => self::REF_NUMBER,
            'creator_id' => $creator->id,
            'organization_unit_id' => $creator->organization_unit_id,
            'price' => 25000,
            'harga_indikatif' => 25000,
            'anggaran_jabatan' => 25000,
            'advertise_start_date' => $open,
            'advertise_stop_date' => $close,
            'document_start_date' => $open,
            'document_stop_date' => $close,
            'submission_datetime' => $close . ' 23:59:59',
            'tarikh_dicipta' => $open,
            'kaedah_perolehan_id' => $kaedahId,
            'kategori_perolehan_id' => $kategoriId,
            'sumber_peruntukan' => 'pembangunan',
            'terbuka_kepada' => 'semua',
            'zon_lokasi' => 0,
            'only_bumiputera' => 0,
            'type' => 'pembelian_terus',
            'status_process_id' => 5, // published — shows in Cut Off list
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

        // Keep only columns that exist on this DB.
        $tenderCols = array_flip(Schema::getColumnListing('tenders'));
        $tenderPayload = array_filter(
            $tenderPayload,
            static fn ($_, $key) => isset($tenderCols[$key]),
            ARRAY_FILTER_USE_BOTH
        );

        if ($existing) {
            DB::table('tenders')->where('id', $existing->id)->update($tenderPayload);
            $tenderId = (int) $existing->id;
            $this->command?->info("Updated tender #{$tenderId} (" . self::REF_NUMBER . ').');
        } else {
            $tenderPayload['uuid'] = (string) Str::uuid();
            $tenderPayload['no_tender'] = 'PT' . now()->format('ymd') . 'UATC01';
            $tenderPayload['created_at'] = now();
            if (! isset($tenderCols['uuid'])) {
                unset($tenderPayload['uuid']);
            }
            if (! isset($tenderCols['no_tender'])) {
                unset($tenderPayload['no_tender']);
            }
            $tenderId = (int) DB::table('tenders')->insertGetId($tenderPayload);
            $this->command?->info("Created tender #{$tenderId} (" . self::REF_NUMBER . ').');
        }

        // Reset child rows for idempotent re-seed.
        $offerIds = DB::table('pembelian_terus_offers')->where('tender_id', $tenderId)->pluck('id');
        if ($offerIds->isNotEmpty()) {
            DB::table('pembelian_terus_offer_items')->whereIn('offer_id', $offerIds)->delete();
        }
        DB::table('pembelian_terus_offers')->where('tender_id', $tenderId)->delete();
        DB::table('pembelian_terus_items')->where('tender_id', $tenderId)->delete();

        $itemSpecs = [
            ['nama_item' => 'Laptop Notebook', 'kuantiti' => 10, 'sst' => 1],
            ['nama_item' => 'Mouse Wireless', 'kuantiti' => 20, 'sst' => 1],
        ];

        $itemIds = [];
        foreach ($itemSpecs as $i => $spec) {
            $itemIds[] = (int) DB::table('pembelian_terus_items')->insertGetId([
                'tender_id' => $tenderId,
                'nama_item' => $spec['nama_item'],
                'kuantiti' => $spec['kuantiti'],
                'sst' => $spec['sst'],
                'sort_order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $unitPrices = [
            // vendor index => [item0 unit, item1 unit]
            [2200.00, 45.00],
            [2100.00, 42.50],
            [2350.00, 48.00],
        ];

        foreach ($vendors->values() as $vIndex => $vendor) {
            $prices = $unitPrices[$vIndex] ?? $unitPrices[0];
            $total = 0;
            $totalSst = 0;
            $lines = [];

            foreach ($itemIds as $i => $itemId) {
                $qty = (float) $itemSpecs[$i]['kuantiti'];
                $unit = (float) $prices[$i];
                $line = $unit * $qty;
                $withSst = $line * 1.05;
                $total += $line;
                $totalSst += $withSst;
                $lines[] = [
                    'item_id' => $itemId,
                    'brand' => ['Acer', 'Logitech', 'Dell', 'HP', 'Lenovo'][$vIndex % 5],
                    'harga_seunit' => $unit,
                    'harga_keseluruhan' => $line,
                    'harga_sst' => $withSst,
                ];
            }

            $quotePath = $this->storeDummyQuotation($tenderId, (int) $vendor->id);

            $offerId = (int) DB::table('pembelian_terus_offers')->insertGetId([
                'tender_id' => $tenderId,
                'vendor_id' => $vendor->id,
                'total_harga' => $total,
                'total_harga_sst' => $totalSst,
                'quotation_path' => $quotePath,
                'quotation_original_name' => 'UAT_Quotation_Vendor_' . $vendor->id . '.pdf',
                'submitted' => 1,
                'shortlisted' => 0,
                'selected' => 0,
                'decision' => null,
                'submitted_at' => now()->subHours(3 - $vIndex),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($lines as $line) {
                DB::table('pembelian_terus_offer_items')->insert([
                    'offer_id' => $offerId,
                    'item_id' => $line['item_id'],
                    'brand' => $line['brand'],
                    'harga_seunit' => $line['harga_seunit'],
                    'harga_keseluruhan' => $line['harga_keseluruhan'],
                    'harga_sst' => $line['harga_sst'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command?->info('Pembelian Terus Cut Off UAT data ready.');
        $this->command?->info("  Tender ID : {$tenderId}");
        $this->command?->info('  Ref       : ' . self::REF_NUMBER);
        $this->command?->info('  Status    : 5 (published)');
        $this->command?->info("  Tarikh tutup : {$close} (yesterday)");
        $this->command?->info('  Offers    : ' . $vendors->count() . ' submitted');
        $this->command?->info('Open: /pembelian-terus/cut-off-projek then cut-off-details/' . $tenderId);
    }

    private function storeDummyQuotation(int $tenderId, int $vendorId): string
    {
        $relative = "pembelian-terus/quotations/{$tenderId}/uat-vendor-{$vendorId}.pdf";

        // Minimal valid PDF so the cut-off "open in new tab" flow works.
        $pdf = "%PDF-1.1\n"
            . "1 0 obj<< /Type /Catalog /Pages 2 0 R >>endobj\n"
            . "2 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1 >>endobj\n"
            . "3 0 obj<< /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] "
            . "/Contents 4 0 R /Resources<< /Font<< /F1 5 0 R >> >> >>endobj\n"
            . "4 0 obj<< /Length 68 >>stream\n"
            . "BT /F1 16 Tf 40 100 Td (UAT Quotation Vendor {$vendorId}) Tj ET\n"
            . "endstream\nendobj\n"
            . "5 0 obj<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>endobj\n"
            . "xref\n0 6\n0000000000 65535 f \n"
            . "trailer<< /Size 6 /Root 1 0 R >>\nstartxref\n0\n%%EOF\n";

        Storage::disk('public')->put($relative, $pdf);

        return $relative;
    }
}
