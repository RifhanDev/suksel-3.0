<?php

namespace Database\Seeders;

use App\Support\TenderProcessStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Dummy: Pembelian & Bayaran Manual di Agensi (Iklan Sahaja).
 * Vendor sees "Tambah ke Senarai" (not Beli), then dokumen unlocks.
 *
 *   php artisan db:seed --class=IklanSahajaManualDemoSeeder
 */
class IklanSahajaManualDemoSeeder extends Seeder
{
    private const REF = 'UAT/IKLAN/MANUAL';

    public function run(): void
    {
        foreach (['tenders', 'ref_kaedah_dokumens', 'tender_iklan_dokumens'] as $table) {
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

        $kaedahId = DB::table('ref_kaedah_dokumens')->where('code', 'manual')->value('id');
        if (! $kaedahId) {
            $this->command?->error('Kaedah manual missing. Run KaedahDokumenSeeder first.');

            return;
        }

        $ouId = $creator->organization_unit_id
            ?? DB::table('organization_units')->where('name', 'like', '%SUK%')->value('id')
            ?? DB::table('organization_units')->value('id');

        $tenderId = $this->upsertTender($creator, (int) $kaedahId, (int) $ouId);
        $this->seedPenyediaanIklan($tenderId);
        $this->seedIklanDokumen($tenderId, (int) $creator->id);
        $this->seedEligibleVendor($tenderId);

        // Ensure no prior purchase so "Tambah ke Senarai" is still clickable.
        $vendorId = (int) (DB::table('users')->where('email', 'vendor01@dummy.stos.local')->value('vendor_id') ?? 0);
        if ($vendorId > 0) {
            DB::table('tender_vendors')->where('tender_id', $tenderId)->where('vendor_id', $vendorId)->delete();
        }

        $tender = DB::table('tenders')->where('id', $tenderId)->first();
        $docCount = DB::table('tender_iklan_dokumens')->where('tender_id', $tenderId)->count();

        $this->command?->info('Iklan Sahaja (manual) demo ready.');
        $this->command?->info("  Tender #{$tenderId} — {$tender->name}");
        $this->command?->info('  REF: ' . self::REF);
        $this->command?->info("  Iklan dokumen: {$docCount}");
        $this->command?->info('  Kaedah: Pembelian & Bayaran Manual di Agensi (Iklan Sahaja)');
        $this->command?->info('  Button: Tambah ke Senarai');
        $this->command?->info("  URL: /tenders/{$tenderId}");
        $this->command?->info('  Vendor: vendor01@dummy.stos.local');
        $this->command?->info('  Admin:  admin@suksel.com');
    }

    private function upsertTender(User $creator, int $kaedahId, int $ouId): int
    {
        $existing = DB::table('tenders')->where('ref_number', self::REF)->first();
        $open = Carbon::now()->subDay();
        $close = Carbon::now()->addDays(14);

        $kategoriId = Schema::hasTable('ref_kategori_jenis_perolehans')
            ? (DB::table('ref_kategori_jenis_perolehans')->where('name', 'Bekalan')->value('id') ?? 1)
            : 1;

        $payload = [
            'name' => 'UAT Iklan Sahaja — Bekalan Alat Tulis Pejabat (Bayaran Manual di Agensi)',
            'ref_number' => self::REF,
            'user_id' => $creator->id,
            'creator_id' => $creator->id,
            'officer_id' => $creator->id,
            'approver_id' => $creator->id,
            'organization_unit_id' => $ouId,
            'anggaran_jabatan' => 25000,
            'price' => 50,
            'advertise_start_date' => $open->toDateString(),
            'advertise_stop_date' => $close->toDateString(),
            'document_start_date' => $open->toDateString(),
            'document_stop_date' => $close->toDateString(),
            'submission_datetime' => $close->format('Y-m-d 17:00:00'),
            'tarikh_dicipta' => $open->toDateString(),
            'kategori_perolehan_id' => $kategoriId,
            'kaedah_dokumen_id' => $kaedahId,
            'sumber_peruntukan' => 'pembangunan',
            'terbuka_kepada' => 'semua',
            'zon_lokasi' => 0,
            'only_bumiputera' => 0,
            'only_selangor' => 0,
            'only_advertise' => 1,
            'type' => 'quotation',
            'status_process_id' => TenderProcessStatus::PENYEDIAAN_IKLAN,
            'invitation' => 0,
            'briefing_required' => 0,
            'allow_exception' => 0,
            'publish_prices' => 0,
            'publish_shortlists' => 0,
            'publish_winner' => 0,
            'is_ebidding' => 0,
            'updated_at' => now(),
        ];

        $cols = array_flip(Schema::getColumnListing('tenders'));
        $payload = array_filter($payload, static fn ($_, $k) => isset($cols[$k]), ARRAY_FILTER_USE_BOTH);

        if ($existing) {
            DB::table('tenders')->where('id', $existing->id)->update($payload);

            return (int) $existing->id;
        }

        $payload['uuid'] = (string) Str::uuid();
        $payload['no_tender'] = 'IKLAN-MNL-' . now()->format('ymd');
        $payload['created_at'] = now();
        if (! isset($cols['uuid'])) {
            unset($payload['uuid']);
        }
        if (! isset($cols['no_tender'])) {
            unset($payload['no_tender']);
        }

        return (int) DB::table('tenders')->insertGetId($payload);
    }

    private function seedPenyediaanIklan(int $tenderId): void
    {
        if (! Schema::hasTable('penyediaan_iklans')) {
            return;
        }

        $open = Carbon::now()->subDay();
        $close = Carbon::now()->addDays(14);

        $meta = [
            'iklan' => [
                'tarikh_iklan' => $open->format('d/m/Y'),
                'masa_iklan' => '00:00',
                'tarikh_tutup' => $close->format('d/m/Y'),
                'masa_tutup' => '17:00',
                'tarikh_jual' => $open->format('d/m/Y'),
                'tempoh_iklan' => '14',
                'taklimat' => [],
                'syarat' => [
                    'only_advertise' => true,
                    'invitation' => false,
                    'only_bumiputera' => false,
                    'only_selangor' => '0',
                    'district_list_rule' => [],
                ],
                'dokumen_sokongan' => [],
            ],
            'pegawai' => [],
            'kelulusan' => [],
        ];

        $existing = DB::table('penyediaan_iklans')->where('tender_id', $tenderId)->first();
        if ($existing) {
            DB::table('penyediaan_iklans')->where('id', $existing->id)->update([
                'meta' => json_encode($meta),
                'updated_at' => now(),
            ]);

            return;
        }

        $row = [
            'tender_id' => $tenderId,
            'meta' => json_encode($meta),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('penyediaan_iklans', 'uuid')) {
            $row['uuid'] = (string) Str::uuid();
        }
        if (Schema::hasColumn('penyediaan_iklans', 'status')) {
            $row['status'] = 'submitted';
        }

        DB::table('penyediaan_iklans')->insert($row);
    }

    private function seedIklanDokumen(int $tenderId, int $uploaderId): void
    {
        DB::table('tender_iklan_dokumens')->where('tender_id', $tenderId)->delete();

        $docs = [
            ['name' => 'Dokumen Sebut Harga (Manual)', 'file' => 'dokumen-sebut-harga-manual.pdf'],
            ['name' => 'Senarai Spesifikasi Alat Tulis', 'file' => 'spesifikasi-alat-tulis.pdf'],
        ];

        foreach ($docs as $i => $doc) {
            $uuid = (string) Str::uuid();
            $relPath = "tender-iklan-dokumen/{$tenderId}/{$uuid}.pdf";
            $body = "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF\n"
                . "UAT Iklan Sahaja — {$doc['name']}\n";

            Storage::disk('local')->put($relPath, $body);

            DB::table('tender_iklan_dokumens')->insert([
                'tender_id' => $tenderId,
                'name' => $doc['name'],
                'original_name' => $doc['file'],
                'path' => $relPath,
                'mime' => 'application/pdf',
                'size' => strlen($body),
                'uploaded_by' => $uploaderId,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedEligibleVendor(int $tenderId): void
    {
        if (! Schema::hasTable('tender_eligibles')) {
            return;
        }

        $vendorId = DB::table('users')->where('email', 'vendor01@dummy.stos.local')->value('vendor_id');
        if (! $vendorId) {
            return;
        }

        DB::table('tender_eligibles')->updateOrInsert(
            ['tender_id' => $tenderId, 'vendor_id' => $vendorId],
            ['created_at' => now(), 'updated_at' => now()]
        );
    }
}
