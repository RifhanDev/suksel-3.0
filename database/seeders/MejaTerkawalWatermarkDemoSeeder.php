<?php

namespace Database\Seeders;

use App\Models\PenyediaanIklan;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\Upload;
use App\User;
use Carbon\Carbon;
use FPDF;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Local demo tender with Dokumen Meja Terkawal PDF (no stamp on disk).
 * Download via Muat Turun to see download-time watermark + ID.
 *
 *   php artisan db:seed --class=MejaTerkawalWatermarkDemoSeeder
 */
class MejaTerkawalWatermarkDemoSeeder extends Seeder
{
    private const REF = 'UAT/MEJA/WATERMARK';

    public function run(): void
    {
        foreach (['tenders', 'uploads', 'penyediaan_iklans'] as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->error("Missing table {$table}.");

                return;
            }
        }

        $creator = User::query()->where('email', 'admin@suksel.com')->first()
            ?? User::query()->orderBy('id')->first();

        if (! $creator) {
            $this->command?->error('No user found to own the tender.');

            return;
        }

        $ouId = $creator->organization_unit_id
            ?? DB::table('organization_units')->value('id');

        $tenderId = $this->upsertTender($creator, (int) $ouId);
        $tender = Tender::query()->findOrFail($tenderId);

        // Drop previous demo meja uploads for a clean re-seed.
        $oldUploads = Upload::query()
            ->where('uploadable_type', 'App\Tender')
            ->where('uploadable_id', $tenderId)
            ->where('public', 1)
            ->where('label', 'like', 'Demo Meja%')
            ->get();

        foreach ($oldUploads as $old) {
            $abs = rtrim((string) $old->path, '/\\') . DIRECTORY_SEPARATOR . $old->name;
            if (! str_starts_with($abs, DIRECTORY_SEPARATOR)) {
                $abs = public_path($abs);
            }
            if (is_file($abs)) {
                @unlink($abs);
            }
            $old->delete();
        }

        $pdfPath = $this->writeSamplePdf();
        $upload = $this->createMejaUpload($tender, $pdfPath, 'Demo Meja Terkawal — JILID 1.pdf');
        @unlink($pdfPath);

        $this->linkPenyediaanIklan($tenderId, $upload);

        $url = url('/tenders/' . $tenderId);
        $dl = route('tenders.files', [$tenderId, $upload->id]);

        $this->command?->info('Meja watermark demo ready.');
        $this->command?->info("  Tender #{$tenderId} — {$tender->name}");
        $this->command?->info('  REF: ' . self::REF);
        $this->command?->info("  Upload #{$upload->id} (public=1, unwatermarked on disk)");
        $this->command?->info("  Tender URL: {$url}");
        $this->command?->info("  Direct download: {$dl}");
        $this->command?->info('  Login as admin or vendor, open Dokumen Meja Terkawal → Muat Turun');
    }

    private function upsertTender(User $creator, int $ouId): int
    {
        $existing = DB::table('tenders')->where('ref_number', self::REF)->first();
        $open = Carbon::now()->subDay();
        $close = Carbon::now()->addDays(14);

        $payload = [
            'name' => 'UAT Dokumen Meja Terkawal — Watermark Demo',
            'ref_number' => self::REF,
            'user_id' => $creator->id,
            'creator_id' => $creator->id,
            'officer_id' => $creator->id,
            'approver_id' => $creator->id,
            'organization_unit_id' => $ouId ?: null,
            'anggaran_jabatan' => 10000,
            'price' => 0,
            'advertise_start_date' => $open->toDateString(),
            'advertise_stop_date' => $close->toDateString(),
            'document_start_date' => $open->toDateString(),
            'document_stop_date' => $close->toDateString(),
            'submission_datetime' => $close->format('Y-m-d 17:00:00'),
            'tarikh_dicipta' => $open->toDateString(),
            'only_bumiputera' => 0,
            'only_selangor' => 0,
            'only_advertise' => 0,
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

        $payload['created_at'] = now();
        if (isset($cols['uuid'])) {
            $payload['uuid'] = (string) Str::uuid();
        }
        if (isset($cols['no_tender'])) {
            $payload['no_tender'] = 'MEJA-WM-' . now()->format('ymd');
        }

        return (int) DB::table('tenders')->insertGetId($payload);
    }

    private function writeSamplePdf(): string
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Dokumen Meja Terkawal — Demo', 0, 1, 'C');
        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->MultiCell(0, 7, "Ini adalah PDF demo tanpa watermark.\nMuat turun dari sistem untuk melihat stem \"Dokumen Meja Terkawal\" + kotak ID.");
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, 'Halaman 2 — pastikan stem muncul di setiap halaman.', 0, 1);

        $tmp = tempnam(sys_get_temp_dir(), 'meja_demo_');
        $path = $tmp . '.pdf';
        @unlink($tmp);
        $pdf->Output('F', $path);

        return $path;
    }

    private function createMejaUpload(Tender $tender, string $sourcePath, string $label): Upload
    {
        $hash = Str::random(40);
        $relativeDir = 'uploads/' . $hash;
        $destDir = public_path($relativeDir . '/');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = 'demo-meja-terkawal.pdf';
        if (! copy($sourcePath, $destDir . $filename)) {
            throw new \RuntimeException('Failed to copy demo PDF.');
        }

        $fullPath = $destDir . $filename;
        $size = (int) filesize($fullPath);

        $upload = new Upload();
        $upload->fill([
            'name' => $filename,
            'size' => $size,
            'type' => 'application/pdf',
            'public' => 1,
            'label' => $label,
            'path' => $relativeDir . '/',
            'url' => url($relativeDir),
            'uploadable_type' => 'App\Tender',
            'uploadable_id' => $tender->id,
            'user_id' => $tender->creator_id,
        ]);
        $upload->save();

        return $upload;
    }

    private function linkPenyediaanIklan(int $tenderId, Upload $upload): void
    {
        $open = Carbon::now()->subDay();
        $close = Carbon::now()->addDays(14);

        $docs = [[
            'upload_id' => (int) $upload->id,
            'nama' => $upload->label,
            'original_name' => $upload->name,
            'path' => trim((string) $upload->path, '/') . '/' . $upload->name,
            'url' => route('tenders.files', [$tenderId, $upload->id]),
        ]];

        $meta = [
            'iklan' => [
                'tarikh_iklan' => $open->format('d/m/Y'),
                'masa_iklan' => '00:00',
                'tarikh_tutup' => $close->format('d/m/Y'),
                'masa_tutup' => '17:00',
                'tarikh_jual' => $open->format('d/m/Y'),
                'tempoh_iklan' => '14',
                'taklimat' => [],
                'syarat' => [],
                'dokumen_sokongan' => $docs,
            ],
            'pegawai' => [],
            'kelulusan' => [],
        ];

        $existing = PenyediaanIklan::query()->where('tender_id', $tenderId)->first();
        if ($existing) {
            $existing->meta = $meta;
            $existing->save();

            return;
        }

        $row = [
            'tender_id' => $tenderId,
            'meta' => $meta,
        ];
        if (Schema::hasColumn('penyediaan_iklans', 'uuid')) {
            $row['uuid'] = (string) Str::uuid();
        }
        if (Schema::hasColumn('penyediaan_iklans', 'status')) {
            $row['status'] = 'submitted';
        }

        PenyediaanIklan::query()->create($row);
    }
}
