<?php

namespace Tests\Feature;

use App\Models\PenyataBank;
use App\Models\PenyataBankFile;
use App\Models\SpesifikasiKerjaFile;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\TenderVendorDokumenFile;
use App\Tender;
use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorTenderDokumenDownloadTest extends TestCase
{
    use DatabaseTransactions;

    private User $adminUser;
    private Tender $tender;
    private Vendor $vendor;
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::query()->first() ?: User::query()->create([
            'name'     => 'Admin Pegawai Test',
            'username' => 'admin_test_' . uniqid(),
            'email'    => 'admin_' . uniqid() . '@selangor.gov.my',
            'password' => bcrypt('password'),
        ]);

        $adminRole = Role::query()->firstOrCreate(['name' => 'Admin']);
        $this->adminUser->assignRole($adminRole);

        $this->tender = Tender::query()->first() ?: Tender::query()->create([
            'uuid'                  => (string) Str::uuid(),
            'no_tender'             => 'TEST-TDR-' . strtoupper(Str::random(6)),
            'tajuk'                 => 'Tender Ujian Dokumen',
            'status_process_id'     => 1,
            'kategori_perolehan_id' => 1,
            'kaedah_perolehan_id'   => 1,
            'anggaran_jabatan'      => 100000.00,
        ]);

        $this->vendor = Vendor::query()->first();
        if (! $this->vendor) {
            $orgId = \Illuminate\Support\Facades\DB::table('organization_units')->value('id') ?: 1;
            $this->vendor = Vendor::query()->create([
                'name'                 => 'Vendor Ujian Sdn Bhd',
                'registration'         => 'REG-' . Str::random(6),
                'organization_unit_id' => $orgId,
            ]);
        }

        $this->tempDir = storage_path('app/public/test_docs_' . uniqid());
        if (! File::isDirectory($this->tempDir)) {
            File::makeDirectory($this->tempDir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (File::isDirectory($this->tempDir)) {
            File::deleteDirectory($this->tempDir);
        }

        parent::tearDown();
    }

    public function test_pdf_document_serves_with_inline_disposition_for_browser_tab_viewing(): void
    {
        $fileName = 'Penyata_Bank_Mac_2026.pdf';
        $relativeSub = basename($this->tempDir) . '/' . $fileName;
        $absPath = $this->tempDir . '/' . $fileName;
        File::put($absPath, '%PDF-1.4 test pdf content');

        $penyataBank = PenyataBank::query()->firstOrCreate(
            ['tender_id' => $this->tender->id],
            [
                'vendor_id' => $this->vendor->id,
                'uuid'      => (string) Str::uuid(),
            ]
        );

        $pbFile = PenyataBankFile::query()->create([
            'penyata_bank_id' => $penyataBank->id,
            'vendor_id'       => $this->vendor->id,
            'uuid'            => (string) Str::uuid(),
            'original_name'   => $fileName,
            'stored_name'     => $fileName,
            'path'            => $relativeSub,
            'mime_type'       => 'application/pdf',
            'size'            => 1024,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('tenderDokumen.download', $pbFile->uuid));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type') ?? '');
        $this->assertStringContainsString('inline; filename="Penyata_Bank_Mac_2026.pdf"', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_non_pdf_document_serves_with_attachment_disposition_for_file_download(): void
    {
        $fileName = 'Lampiran_Kewangan.xlsx';
        $relativeSub = basename($this->tempDir) . '/' . $fileName;
        $absPath = $this->tempDir . '/' . $fileName;
        File::put($absPath, 'dummy excel spreadsheet data');

        $vFile = TenderVendorDokumenFile::query()->create([
            'tender_id'     => $this->tender->id,
            'vendor_id'     => $this->vendor->id,
            'item_uuid'     => (string) Str::uuid(),
            'uuid'          => (string) Str::uuid(),
            'original_name' => $fileName,
            'path'          => $relativeSub,
            'mime_type'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'size'          => 2048,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('tenderDokumen.download', $vFile->uuid));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename="Lampiran_Kewangan.xlsx"', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_missing_document_returns_404_not_found(): void
    {
        $vFile = TenderVendorDokumenFile::query()->create([
            'tender_id'     => $this->tender->id,
            'vendor_id'     => $this->vendor->id,
            'item_uuid'     => (string) Str::uuid(),
            'uuid'          => (string) Str::uuid(),
            'original_name' => 'NonExistentFile.pdf',
            'path'          => 'non_existent_folder/file_never_uploaded.pdf',
            'mime_type'     => 'application/pdf',
            'size'          => 1024,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('tenderDokumen.download', $vFile->uuid));

        $response->assertStatus(404);
    }

    public function test_unauthorized_vendor_cannot_access_other_vendor_document(): void
    {
        $fileName = 'Dokumen_Sulit_Vendor_A.pdf';
        $relativeSub = basename($this->tempDir) . '/' . $fileName;
        $absPath = $this->tempDir . '/' . $fileName;
        File::put($absPath, '%PDF-1.4 private content');

        $vFile = TenderVendorDokumenFile::query()->create([
            'tender_id'     => $this->tender->id,
            'vendor_id'     => $this->vendor->id,
            'item_uuid'     => (string) Str::uuid(),
            'uuid'          => (string) Str::uuid(),
            'original_name' => $fileName,
            'path'          => $relativeSub,
            'mime_type'     => 'application/pdf',
            'size'          => 1024,
        ]);

        // Create a different vendor user without Admin or Reviewer role
        $orgId = $this->vendor->organization_unit_id ?: 1;
        $otherVendor = Vendor::query()->create([
            'name'                 => 'Vendor Penceroboh Sdn Bhd',
            'registration'         => 'REG-' . Str::random(6),
            'organization_unit_id' => $orgId,
        ]);

        $otherVendorUser = User::query()->create([
            'name'                => 'Pengguna Vendor Lain',
            'email'               => 'vendor_other_' . uniqid() . '@example.test',
            'password'            => bcrypt('password'),
            'vendor_id'           => $otherVendor->id,
            'password_changed_at' => now(),
        ]);

        $response = $this->actingAs($otherVendorUser)
            ->get(route('tenderDokumen.download', $vFile->uuid));

        $response->assertStatus(403);
    }

    public function test_download_spesifikasi_kerja_file_inline_for_pdf_and_attachment_for_non_pdf(): void
    {
        $header = SpesifikasiKerjaHeader::query()->firstOrCreate(
            ['tender_id' => $this->tender->id],
            ['uuid' => (string) Str::uuid()]
        );

        // 1. PDF
        $pdfName = 'Spesifikasi_Piawai.pdf';
        $relativePdf = basename($this->tempDir) . '/' . $pdfName;
        File::put($this->tempDir . '/' . $pdfName, '%PDF-1.4 spec');

        $pdfFile = SpesifikasiKerjaFile::query()->create([
            'uuid'                        => (string) Str::uuid(),
            'spesifikasi_kerja_header_id' => $header->id,
            'original_name'               => $pdfName,
            'stored_name'                 => $pdfName,
            'path'                        => $relativePdf,
            'mime_type'                   => 'application/pdf',
            'size'                        => 1024,
        ]);

        $responsePdf = $this->actingAs($this->adminUser)
            ->get(route('tenderDokumen.spesifikasiKerjaFile', [
                'tender'   => $this->tender->id,
                'fileUuid' => $pdfFile->uuid,
            ]));

        $responsePdf->assertStatus(200);
        $this->assertStringContainsString('inline; filename="Spesifikasi_Piawai.pdf"', $responsePdf->headers->get('Content-Disposition') ?? '');

        // 2. Non-PDF (ZIP)
        $zipName = 'Pelan_Lukisan_Tender.zip';
        $relativeZip = basename($this->tempDir) . '/' . $zipName;
        File::put($this->tempDir . '/' . $zipName, 'PK dummy zip content');

        $zipFile = SpesifikasiKerjaFile::query()->create([
            'uuid'                        => (string) Str::uuid(),
            'spesifikasi_kerja_header_id' => $header->id,
            'original_name'               => $zipName,
            'stored_name'                 => $zipName,
            'path'                        => $relativeZip,
            'mime_type'                   => 'application/zip',
            'size'                        => 2048,
        ]);

        $responseZip = $this->actingAs($this->adminUser)
            ->get(route('tenderDokumen.spesifikasiKerjaFile', [
                'tender'   => $this->tender->id,
                'fileUuid' => $zipFile->uuid,
            ]));

        $responseZip->assertStatus(200);
        $this->assertStringContainsString('attachment; filename="Pelan_Lukisan_Tender.zip"', $responseZip->headers->get('Content-Disposition') ?? '');
    }
}
