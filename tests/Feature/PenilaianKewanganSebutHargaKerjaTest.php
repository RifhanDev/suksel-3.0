<?php

namespace Tests\Feature;

use App\Models\TenderKewanganEvaluation;
use App\Models\TenderKewanganKerjaEvaluation;
use App\Models\TenderKewanganLaporan;
use App\Models\TenderKewanganProgress;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PenilaianKewanganSebutHargaKerjaTest extends TestCase
{
    use DatabaseTransactions;

    private ?Tender $tender = null;
    private ?User $user = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->first() ?: User::query()->create([
            'name'     => 'Pegawai Penilai Test',
            'username' => 'penilai_test_' . uniqid(),
            'email'    => 'penilai_' . uniqid() . '@selangor.gov.my',
            'password' => bcrypt('password'),
        ]);

        $this->tender = Tender::query()->where('status_process_id', TenderProcessStatus::PENILAIAN_KEWANGAN)->first();
        if (! $this->tender) {
            $this->tender = Tender::query()->first();
        }
    }

    public function test_type2_routes_are_registered(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.show'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.simpanDokumen'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.sahkanLangkah1'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.simpanKewangan'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.sahkanLangkah2'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.simpanKerja'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.sahkanLangkah3'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.simpanLaporanDraf'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('penilaianKewanganSebutHargaKerja.hantar'));
    }

    public function test_show_method_resolves_and_renders(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        // Mock role bypass or assign admin role to test user so assertCommitteeAppointment passes
        $adminRole = \Spatie\Permission\Models\Role::query()->firstOrCreate(['name' => 'Admin']);
        $this->user->assignRole($adminRole);
        $this->actingAs($this->user);

        // Ensure tender is classified as Sebut Harga Kerja for test
        $this->tender->type = 'quotation';
        $this->tender->kategori_perolehan_id = 3;
        $this->tender->save();

        $response = $this->get(route('penilaianKewanganSebutHargaKerja.show', $this->tender->no_tender ?: $this->tender->id));

        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }

    public function test_simpan_dokumen_persists_to_tender_kewangan_evaluations(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $this->actingAs($this->user);

        $docUuid = (string) \Illuminate\Support\Str::uuid();
        $vendorId = 9991;

        $response = $this->postJson(route('penilaianKewanganSebutHargaKerja.simpanDokumen'), [
            'tender_id' => $this->tender->id,
            'doc_id'    => $docUuid,
            'decisions' => [
                [
                    'vendor_id' => $vendorId,
                    'status'    => 'sempurna',
                    'catatan'   => 'Lengkap dan teratur.',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tender_kewangan_evaluations', [
            'tender_id'           => $this->tender->id,
            'vendor_id'           => $vendorId,
            'checklist_item_uuid' => $docUuid,
            'status_pematuhan'    => 1,
            'catatan'             => 'Lengkap dan teratur.',
        ]);
    }

    public function test_sahkan_langkah1_updates_progress(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $this->actingAs($this->user);

        $response = $this->postJson(route('penilaianKewanganSebutHargaKerja.sahkanLangkah1'), [
            'tender_id' => $this->tender->id,
            'confirmed' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tender_kewangan_progress', [
            'tender_id' => $this->tender->id,
        ]);

        $progress = TenderKewanganProgress::query()->where('tender_id', $this->tender->id)->first();
        $this->assertNotNull($progress->step1_confirmed_at);
        $this->assertGreaterThanOrEqual(2, $progress->current_step);
    }

    public function test_simpan_kewangan_persists_to_tender_kewangan_kerja_evaluations(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $this->actingAs($this->user);

        $vendorId = 9992;
        $response = $this->postJson(route('penilaianKewanganSebutHargaKerja.simpanKewangan'), [
            'tender_id' => $this->tender->id,
            'vendor_id' => $vendorId,
            'decision'  => 'memuaskan',
            'catatan'   => 'Modal mencukupi had minimum 1.5%.',
            'payload'   => [
                'modal_pusingan' => 150000.00,
                'purata_bank'    => 50000.00,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tender_kewangan_kerja_evaluations', [
            'tender_id'        => $this->tender->id,
            'vendor_id'        => $vendorId,
            'borang_code'      => 'sh_kemampuan_kewangan',
            'status_pematuhan' => 1,
            'catatan'          => 'Modal mencukupi had minimum 1.5%.',
        ]);
    }

    public function test_simpan_kerja_persists_to_tender_kewangan_kerja_evaluations(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $this->actingAs($this->user);

        $vendorId = 9993;
        $response = $this->postJson(route('penilaianKewanganSebutHargaKerja.simpanKerja'), [
            'tender_id' => $this->tender->id,
            'vendor_id' => $vendorId,
            'decision'  => 'memuaskan',
            'catatan'   => 'Kapasiti kerja terbaik.',
            'payload'   => [
                'baki_kerja' => 120000.00,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tender_kewangan_kerja_evaluations', [
            'tender_id'        => $this->tender->id,
            'vendor_id'        => $vendorId,
            'borang_code'      => 'sh_penilaian_kerja',
            'status_pematuhan' => 1,
            'catatan'          => 'Kapasiti kerja terbaik.',
        ]);
    }

    public function test_simpan_laporan_draf(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $this->actingAs($this->user);

        $response = $this->postJson(route('penilaianKewanganSebutHargaKerja.simpanLaporanDraf'), [
            'tender_id'          => $this->tender->id,
            'catatan_peringkat1' => 'Ulasan draf kewangan dan kerja.',
            'catatan_peringkat3' => 'Syarat khas SST.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tender_kewangan_laporans', [
            'tender_id'          => $this->tender->id,
            'catatan_peringkat1' => 'Ulasan draf kewangan dan kerja.',
            'status'             => 'draft',
        ]);
    }

    public function test_vendor_kakitangan_and_documents_are_retrieved_correctly(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $adminRole = \Spatie\Permission\Models\Role::query()->firstOrCreate(['name' => 'Admin']);
        $this->user->assignRole($adminRole);
        $this->actingAs($this->user);

        // Ensure tender is Sebut Harga Kerja
        $this->tender->type = 'quotation';
        $this->tender->kategori_perolehan_id = 3;
        $this->tender->save();

        // Ensure a participant vendor with valid foreign key
        $vendor = \App\Vendor::query()->first() ?: \App\Vendor::query()->create([
            'name'         => 'Test Petender Sdn Bhd',
            'company_name' => 'Test Petender Sdn Bhd',
        ]);
        $vendorId = $vendor->id;

        $participant = $this->tender->participants()->firstWhere('vendor_id', $vendorId);
        if (! $participant) {
            $this->tender->participants()->create([
                'vendor_id'     => $vendorId,
                'kod_pembekal'  => '1/1',
                'harga_tawaran' => 150000.00,
                'tempoh_siap'   => 12,
                'cancel_fg'     => 0,
            ]);
        }

        // Create a technical staff record
        $staffUuid = (string) \Illuminate\Support\Str::uuid();
        $staff = \App\Models\TenderKakitanganTeknikal::create([
            'uuid'               => $staffUuid,
            'tender_uuid'        => $this->tender->uuid,
            'vendor_id'          => $vendorId,
            'nama_pegawai'       => 'Ir. Test Engineer',
            'tahap_pendidikan'   => 'Diploma dan Ijazah',
            'jumlah_pengalaman'  => 8,
            'sijil_professional' => 'BEM Ir.',
            'kategori'           => 'Kategori B',
            'sort_order'         => 1,
        ]);

        // Attach an uploaded document
        $doc = \App\Models\TenderKakitanganTeknikalDokumen::create([
            'uuid'            => (string) \Illuminate\Support\Str::uuid(),
            'tender_uuid'     => $this->tender->uuid,
            'vendor_id'       => $vendorId,
            'kakitangan_uuid' => $staffUuid,
            'original_name'   => 'sijil_ir_engineer.pdf',
            'stored_name'     => 'stored_sijil.pdf',
            'path'            => 'kakitangan-teknikal/' . $this->tender->uuid . '/stored_sijil.pdf',
            'mime_type'       => 'application/pdf',
            'size'            => 1024,
            'uploaded_by'     => $this->user->id,
        ]);

        $response = $this->get(route('penilaianKewanganSebutHargaKerja.show', $this->tender->no_tender ?: $this->tender->id));
        $response->assertStatus(200);

        $type2Data = $response->viewData('type2Data');
        $this->assertNotNull($type2Data);
        $this->assertNotEmpty($type2Data['vendors']);

        $targetVendor = collect($type2Data['vendors'])->firstWhere('id', $vendorId);
        $this->assertNotNull($targetVendor);

        // Assert staff data is present and summarized
        $this->assertStringContainsString('1 Orang', $targetVendor['step3']['kakitangan']);
        $this->assertEquals(1, $targetVendor['step3']['kakitangan_data']['bil_kakitangan']);
        $staffItem = $targetVendor['step3']['kakitangan_data']['items'][0];
        $this->assertEquals('Ir. Test Engineer', $staffItem['nama_pegawai']);
        $this->assertEquals('Diploma dan Ijazah', $staffItem['tahap_pendidikan']);
        $this->assertNotEmpty($staffItem['dokumens']);
        $this->assertEquals('sijil_ir_engineer.pdf', $staffItem['dokumens'][0]['original_name']);
        $this->assertNotEmpty($staffItem['dokumens'][0]['file_url']);
    }

    public function test_vendor_penyata_bank_amounts_and_multiple_documents_are_retrieved_and_isolated(): void
    {
        if (! $this->tender) {
            $this->markTestSkipped('No tender found in DB.');
        }

        $adminRole = \Spatie\Permission\Models\Role::query()->firstOrCreate(['name' => 'Admin']);
        $this->user->assignRole($adminRole);
        $this->actingAs($this->user);

        $this->tender->type = 'quotation';
        $this->tender->kategori_perolehan_id = 3;
        $this->tender->save();

        // Ensure 2 distinct vendors
        $existingVendors = \App\Vendor::query()->take(2)->get();
        if ($existingVendors->count() < 2) {
            $firstVendor = \App\Vendor::query()->first();
            $orgUnitId = $firstVendor ? $firstVendor->organization_unit_id : 1;
            $vendorA = $firstVendor ?: \App\Vendor::query()->create(['name' => 'Vendor Test A', 'organization_unit_id' => $orgUnitId]);
            $vendorB = \App\Vendor::query()->create(['name' => 'Vendor Test B', 'organization_unit_id' => $orgUnitId]);
        } else {
            $vendorA = $existingVendors[0];
            $vendorB = $existingVendors[1];
        }

        $this->tender->participants()->updateOrCreate(
            ['vendor_id' => $vendorA->id],
            ['kod_pembekal' => '1/2', 'harga_tawaran' => 200000.00, 'tempoh_siap' => 10, 'cancel_fg' => 0]
        );
        $this->tender->participants()->updateOrCreate(
            ['vendor_id' => $vendorB->id],
            ['kod_pembekal' => '2/2', 'harga_tawaran' => 220000.00, 'tempoh_siap' => 10, 'cancel_fg' => 0]
        );

        // Vendor A Penyata Bank Payload with 3 months and 2 files
        \App\Models\TenderVendorFormPayload::updateOrCreate(
            ['tender_id' => $this->tender->id, 'vendor_id' => $vendorA->id, 'form_key' => 'penyata_bank'],
            [
                'uuid'    => (string) \Illuminate\Support\Str::uuid(),
                'payload' => [
                    'purata' => 500000,
                    'accounts' => [
                        [
                            'bank_name' => 'Maybank Islamic Berhad',
                            'account_no' => '562100112233',
                            'bulans' => [
                                ['bulan' => 7, 'tahun' => 2026, 'jumlah' => 400000],
                                ['bulan' => 8, 'tahun' => 2026, 'jumlah' => 500000],
                                ['bulan' => 9, 'tahun' => 2026, 'jumlah' => 600000],
                            ],
                            'purata' => 500000,
                            'jumlah_keseluruhan' => 1500000,
                            'files' => [
                                ['name' => 'vendor_a_bank_july.pdf', 'url' => 'http://test/vendor_a_bank_july.pdf', 'uuid' => null],
                                ['name' => 'vendor_a_bank_august.pdf', 'url' => 'http://test/vendor_a_bank_august.pdf', 'uuid' => null],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Vendor B Penyata Bank Payload with 3 months and 1 file
        \App\Models\TenderVendorFormPayload::updateOrCreate(
            ['tender_id' => $this->tender->id, 'vendor_id' => $vendorB->id, 'form_key' => 'penyata_bank'],
            [
                'uuid'    => (string) \Illuminate\Support\Str::uuid(),
                'payload' => [
                    'purata' => 120000,
                    'accounts' => [
                        [
                            'bank_name' => 'CIMB Bank Berhad',
                            'account_no' => '800122334455',
                            'bulans' => [
                                ['bulan' => 7, 'tahun' => 2026, 'jumlah' => 100000],
                                ['bulan' => 8, 'tahun' => 2026, 'jumlah' => 120000],
                                ['bulan' => 9, 'tahun' => 2026, 'jumlah' => 140000],
                            ],
                            'purata' => 120000,
                            'jumlah_keseluruhan' => 360000,
                            'files' => [
                                ['name' => 'vendor_b_bank_statement.pdf', 'url' => 'http://test/vendor_b_bank_statement.pdf', 'uuid' => null],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Add Step 1 checklist uploads: Vendor A has 2 files, Vendor B has 1 file
        $presenter = \App\Support\TenderDokumenPresenter::for($this->tender);
        $rawItems = $presenter->items('admin');
        $firstItem = collect($rawItems)->first();
        $checklistUuid = $firstItem['uuid'] ?? (string) \Illuminate\Support\Str::uuid();

        \App\Models\TenderVendorDokumenFile::create([
            'uuid'                => (string) \Illuminate\Support\Str::uuid(),
            'tender_id'           => $this->tender->id,
            'vendor_id'           => $vendorA->id,
            'checklist_item_uuid' => $checklistUuid,
            'original_name'       => 'vendor_a_doc_1.pdf',
            'stored_name'         => 'stored_a1.pdf',
            'path'                => 'tenders/' . $this->tender->id . '/stored_a1.pdf',
            'mime_type'           => 'application/pdf',
            'size'                => 1024,
        ]);
        \App\Models\TenderVendorDokumenFile::create([
            'uuid'                => (string) \Illuminate\Support\Str::uuid(),
            'tender_id'           => $this->tender->id,
            'vendor_id'           => $vendorA->id,
            'checklist_item_uuid' => $checklistUuid,
            'original_name'       => 'vendor_a_doc_2.pdf',
            'stored_name'         => 'stored_a2.pdf',
            'path'                => 'tenders/' . $this->tender->id . '/stored_a2.pdf',
            'mime_type'           => 'application/pdf',
            'size'                => 2048,
        ]);
        \App\Models\TenderVendorDokumenFile::create([
            'uuid'                => (string) \Illuminate\Support\Str::uuid(),
            'tender_id'           => $this->tender->id,
            'vendor_id'           => $vendorB->id,
            'checklist_item_uuid' => $checklistUuid,
            'original_name'       => 'vendor_b_doc_1.pdf',
            'stored_name'         => 'stored_b1.pdf',
            'path'                => 'tenders/' . $this->tender->id . '/stored_b1.pdf',
            'mime_type'           => 'application/pdf',
            'size'                => 1024,
        ]);

        $response = $this->get(route('penilaianKewanganSebutHargaKerja.show', $this->tender->no_tender ?: $this->tender->id));
        $response->assertStatus(200);

        $type2Data = $response->viewData('type2Data');
        $this->assertNotNull($type2Data);

        $vAData = collect($type2Data['vendors'])->firstWhere('id', $vendorA->id);
        $vBData = collect($type2Data['vendors'])->firstWhere('id', $vendorB->id);
        $this->assertNotNull($vAData);
        $this->assertNotNull($vBData);

        // 1. Verify Vendor A bank monthly amounts are populated and NOT 0
        $vAAccts = $vAData['step2']['metrics']['pb_accounts'];
        $this->assertNotEmpty($vAAccts);
        $this->assertEquals([400000.0, 500000.0, 600000.0], $vAAccts[0]['monthly_amounts']);
        $this->assertEquals(500000.0, $vAAccts[0]['purata']);
        $this->assertEquals('RM 500,000.00', $vAData['step2']['purata_bank']);

        // 2. Verify Vendor B bank monthly amounts
        $vBAccts = $vBData['step2']['metrics']['pb_accounts'];
        $this->assertNotEmpty($vBAccts);
        $this->assertEquals([100000.0, 120000.0, 140000.0], $vBAccts[0]['monthly_amounts']);
        $this->assertEquals(120000.0, $vBAccts[0]['purata']);
        $this->assertEquals('RM 120,000.00', $vBData['step2']['purata_bank']);

        // 3. Verify all bank files are displayed and strictly isolated between vendors
        $vABankFiles = $vAData['step2']['bank_files'];
        $vBBankFiles = $vBData['step2']['bank_files'];
        $this->assertCount(2, $vABankFiles);
        $vABankNames = collect($vABankFiles)->pluck('name')->all();
        $this->assertContains('vendor_a_bank_july.pdf', $vABankNames);
        $this->assertContains('vendor_a_bank_august.pdf', $vABankNames);
        $this->assertNotContains('vendor_b_bank_statement.pdf', $vABankNames);

        $this->assertCount(1, $vBBankFiles);
        $vBBankNames = collect($vBBankFiles)->pluck('name')->all();
        $this->assertContains('vendor_b_bank_statement.pdf', $vBBankNames);
        $this->assertNotContains('vendor_a_bank_july.pdf', $vBBankNames);

        // 4. Verify Step 1 checklist multiple files are displayed and strictly isolated
        if ($firstItem) {
            $vADocEntry = $vAData['step1']['docs'][$checklistUuid] ?? null;
            $vBDocEntry = $vBData['step1']['docs'][$checklistUuid] ?? null;
            $this->assertNotNull($vADocEntry);
            $this->assertNotNull($vBDocEntry);

            $this->assertCount(2, $vADocEntry['files']);
            $vADocNames = collect($vADocEntry['files'])->pluck('name')->all();
            $this->assertContains('vendor_a_doc_1.pdf', $vADocNames);
            $this->assertContains('vendor_a_doc_2.pdf', $vADocNames);
            $this->assertNotContains('vendor_b_doc_1.pdf', $vADocNames);

            $this->assertCount(1, $vBDocEntry['files']);
            $vBDocNames = collect($vBDocEntry['files'])->pluck('name')->all();
            $this->assertContains('vendor_b_doc_1.pdf', $vBDocNames);
            $this->assertNotContains('vendor_a_doc_1.pdf', $vBDocNames);
        }
    }
}
