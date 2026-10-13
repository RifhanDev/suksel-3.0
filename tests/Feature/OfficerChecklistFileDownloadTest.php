<?php

namespace Tests\Feature;

use App\Models\FinancialChecklistFile;
use App\Models\FinancialChecklistHeader;
use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaHeader;
use App\Models\KewanganKerjaItem;
use App\Models\TechnicalChecklistFile;
use App\Models\TechnicalChecklistHeader;
use App\Models\TechnicalChecklistItem;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class OfficerChecklistFileDownloadTest extends TestCase
{
    use DatabaseTransactions;

    protected int $orgId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgId = DB::table('organization_units')->insertGetId([
            'name'       => 'Unit Test ' . Str::random(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function createTender(int $kategoriPerolehanId = 1): Tender
    {
        $id = DB::table('tenders')->insertGetId([
            'uuid'                  => (string) Str::uuid(),
            'no_tender'             => 'TEST-TDR-' . strtoupper(Str::random(6)),
            'status_process_id'     => TenderProcessStatus::PENILAIAN_TEKNIKAL,
            'kategori_perolehan_id' => $kategoriPerolehanId,
            'kaedah_perolehan_id'   => 1,
            'organization_unit_id'  => $this->orgId,
            'anggaran_jabatan'      => 100000.00,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        return Tender::query()->findOrFail($id);
    }

    protected function createVendorUser(Tender $tender, bool $participate = true): User
    {
        $vendor = Vendor::query()->create([
            'name'                 => 'Syarikat Ujian ' . Str::random(4),
            'registration'         => 'REG-' . Str::random(6),
            'organization_unit_id' => $this->orgId,
        ]);

        TenderVendor::query()->create([
            'tender_id'   => $tender->id,
            'vendor_id'   => $vendor->id,
            'participate' => $participate ? 1 : 0,
            'cancel_fg'   => 0,
        ]);

        $user = User::query()->create([
            'name'      => 'Pengguna Vendor ' . Str::random(4),
            'email'     => 'vendor_' . Str::random(6) . '@example.test',
            'password'  => bcrypt('password'),
            'vendor_id' => $vendor->id,
        ]);

        return $user;
    }

    public function test_financial_checklist_officer_files_render_with_real_url_and_filename(): void
    {
        Storage::fake('public');

        $tender = $this->createTender(1); // Bekalan
        $vendorUser = $this->createVendorUser($tender, true);

        $header = FinancialChecklistHeader::query()->create([
            'tender_id' => $tender->id,
            'uuid'      => (string) Str::uuid(),
            'max_score' => 100,
        ]);

        $item = FinancialChecklistItem::query()->create([
            'financial_checklist_header_id' => $header->id,
            'uuid'                          => (string) Str::uuid(),
            'source_type'                   => 'manual',
            'title'                         => 'Surat Akuan Pembida',
            'mechanism'                     => 'ptj_muat_naik',
            'vendor_action'                 => 'muat_turun_naik',
            'score'                         => 10,
        ]);

        // Place a real dummy file in public storage
        $storedPath = 'financial-checklists/' . $tender->uuid . '/test_akuan_pembida.pdf';
        Storage::disk('public')->put($storedPath, '%PDF-1.4 dummy pdf content');

        $file = FinancialChecklistFile::query()->create([
            'uuid'                          => (string) Str::uuid(),
            'financial_checklist_header_id' => $header->id,
            'financial_checklist_item_id'   => $item->id,
            'file_type'                     => 'support',
            'original_name'                 => 'Surat Akuan Pembida Asal.pdf',
            'stored_name'                   => 'test_akuan_pembida.pdf',
            'path'                          => $storedPath,
            'mime_type'                     => 'application/pdf',
            'size'                          => 1024,
        ]);

        $presenter = TenderDokumenPresenter::for($tender);
        $items = $presenter->items('vendor', $vendorUser->vendor_id);
        $this->assertNotEmpty($items);

        $akuanItem = collect($items)->firstWhere('uuid', $item->uuid);
        $this->assertNotNull($akuanItem);
        $this->assertEquals('download_upload', $akuanItem['action']);

        $adminFiles = $akuanItem['admin_content']['files'] ?? [];
        $this->assertCount(1, $adminFiles, 'Should have exactly 1 officer-uploaded file');

        $firstFile = $adminFiles[0];
        $this->assertEquals($file->uuid, $firstFile['uuid'], 'File UUID must match and not be null');
        $this->assertEquals('Surat Akuan Pembida Asal.pdf', $firstFile['name'], 'File name must match original_name and not placeholder');
        $this->assertNotEquals('#', $firstFile['url'], 'File URL must not be "#" placeholder');
        $this->assertStringContainsString('/checklist-files/financial/' . $file->uuid . '/download', $firstFile['url']);
    }

    public function test_vendor_can_download_officer_uploaded_financial_file(): void
    {
        Storage::fake('public');

        $tender = $this->createTender(1);
        $vendorUser = $this->createVendorUser($tender, true);

        $header = FinancialChecklistHeader::query()->create([
            'tender_id' => $tender->id,
            'uuid'      => (string) Str::uuid(),
            'max_score' => 100,
        ]);

        $item = FinancialChecklistItem::query()->create([
            'financial_checklist_header_id' => $header->id,
            'uuid'                          => (string) Str::uuid(),
            'title'                         => 'Surat Akuan Pembida',
            'mechanism'                     => 'ptj_muat_naik',
            'vendor_action'                 => 'muat_turun_naik',
            'score'                         => 10,
        ]);

        $storedPath = 'financial-checklists/' . $tender->uuid . '/test_download.pdf';
        Storage::disk('public')->put($storedPath, '%PDF-1.4 dummy content');

        $file = FinancialChecklistFile::query()->create([
            'uuid'                          => (string) Str::uuid(),
            'financial_checklist_header_id' => $header->id,
            'financial_checklist_item_id'   => $item->id,
            'file_type'                     => 'support',
            'original_name'                 => 'Surat Akuan Pembida Asal.pdf',
            'stored_name'                   => 'test_download.pdf',
            'path'                          => $storedPath,
            'mime_type'                     => 'application/pdf',
            'size'                          => 1024,
        ]);

        // Participating vendor can download
        $response = $this->actingAs($vendorUser)
            ->get(route('tenderChecklist.download', [
                'tender'   => $tender->id,
                'section'  => 'financial',
                'fileUuid' => $file->uuid,
            ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('inline; filename="Surat Akuan Pembida Asal.pdf"', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_unauthorized_vendor_cannot_download_officer_file(): void
    {
        Storage::fake('public');

        $tender = $this->createTender(1);
        $nonParticipatingVendorUser = $this->createVendorUser($tender, false); // participate = 0

        $header = FinancialChecklistHeader::query()->create([
            'tender_id' => $tender->id,
            'uuid'      => (string) Str::uuid(),
        ]);

        $item = FinancialChecklistItem::query()->create([
            'financial_checklist_header_id' => $header->id,
            'uuid'                          => (string) Str::uuid(),
            'title'                         => 'Surat Akuan Pembida',
        ]);

        $file = FinancialChecklistFile::query()->create([
            'uuid'                          => (string) Str::uuid(),
            'financial_checklist_header_id' => $header->id,
            'financial_checklist_item_id'   => $item->id,
            'path'                          => 'dummy.pdf',
            'original_name'                 => 'dummy.pdf',
        ]);

        $response = $this->actingAs($nonParticipatingVendorUser)
            ->get(route('tenderChecklist.download', [
                'tender'   => $tender->id,
                'section'  => 'financial',
                'fileUuid' => $file->uuid,
            ]));

        $response->assertStatus(403);
    }

    public function test_vendor_can_download_officer_uploaded_technical_file(): void
    {
        Storage::fake('public');

        $tender = $this->createTender(1);
        $vendorUser = $this->createVendorUser($tender, true);

        $header = TechnicalChecklistHeader::query()->create([
            'tender_id' => $tender->id,
            'uuid'      => (string) Str::uuid(),
            'max_score' => 100,
        ]);

        $item = TechnicalChecklistItem::query()->create([
            'technical_checklist_header_id' => $header->id,
            'uuid'                          => (string) Str::uuid(),
            'source_type'                   => 'manual',
            'title'                         => 'Borang Teknikal',
            'mechanism'                     => 'ptj_muat_naik',
            'vendor_action'                 => 'muat_turun_naik',
            'score'                         => 10,
        ]);

        $storedPath = 'technical-checklists/' . $tender->uuid . '/test_tech_doc.pdf';
        Storage::disk('public')->put($storedPath, '%PDF-1.4 dummy tech content');

        $file = TechnicalChecklistFile::query()->create([
            'uuid'                          => (string) Str::uuid(),
            'technical_checklist_header_id' => $header->id,
            'technical_checklist_item_id'   => $item->id,
            'file_type'                     => 'support',
            'original_name'                 => 'Borang Spesifikasi Teknikal.pdf',
            'stored_name'                   => 'test_tech_doc.pdf',
            'path'                          => $storedPath,
            'mime_type'                     => 'application/pdf',
            'size'                          => 2048,
        ]);

        $response = $this->actingAs($vendorUser)
            ->get(route('tenderChecklist.download', [
                'tender'   => $tender->id,
                'section'  => 'technical',
                'fileUuid' => $file->uuid,
            ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('inline; filename="Borang Spesifikasi Teknikal.pdf"', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_vendor_can_download_officer_uploaded_kewangan_kerja_file(): void
    {
        Storage::fake('public');

        $tender = $this->createTender(3); // Category 3 = Kerja
        $vendorUser = $this->createVendorUser($tender, true);

        $header = KewanganKerjaHeader::query()->create([
            'tender_id' => $tender->id,
            'uuid'      => (string) Str::uuid(),
            'max_score' => 100,
        ]);

        $item = KewanganKerjaItem::query()->create([
            'kewangan_kerja_header_id' => $header->id,
            'uuid'                     => (string) Str::uuid(),
            'source_type'              => 'manual',
            'title'                    => 'Borang Kewangan Kerja',
            'mechanism'                => 'ptj_muat_naik',
            'vendor_action'            => 'muat_turun_naik',
            'score'                    => 10,
        ]);

        $storedPath = 'kewangan-kerja/' . $tender->uuid . '/test_kk_doc.pdf';
        Storage::disk('public')->put($storedPath, '%PDF-1.4 dummy kk content');

        $fileUuid = (string) Str::uuid();
        DB::table('kewangan_kerja_files')->insert([
            'uuid'                     => $fileUuid,
            'kewangan_kerja_header_id' => $header->id,
            'kewangan_kerja_item_id'   => $item->id,
            'file_type'                => 'support',
            'original_name'            => 'Borang Kewangan Kerja Asal.pdf',
            'stored_name'              => 'test_kk_doc.pdf',
            'path'                     => $storedPath,
            'mime_type'                => 'application/pdf',
            'size'                     => 1024,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $response = $this->actingAs($vendorUser)
            ->get(route('tenderChecklist.download', [
                'tender'   => $tender->id,
                'section'  => 'kewangan_kerja',
                'fileUuid' => $fileUuid,
            ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('inline; filename="Borang Kewangan Kerja Asal.pdf"', $response->headers->get('Content-Disposition') ?? '');
    }
}
