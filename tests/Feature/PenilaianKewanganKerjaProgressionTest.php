<?php

namespace Tests\Feature;

use App\Http\Controllers\PenilaianKewanganKerjaController;
use App\Models\TenderKewanganKerjaEvaluation;
use App\Models\TenderKewanganProgress;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class PenilaianKewanganKerjaProgressionTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected int $orgId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first() ?? User::factory()->create();
        Auth::login($this->user);

        $this->orgId = DB::table('organization_units')->insertGetId([
            'name'       => 'Unit Kerja Test ' . Str::random(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function createKerjaTender(float $anggaranJabatan = 15000000.00, int $statusProcessId = 10, array $attributes = []): Tender
    {
        $id = DB::table('tenders')->insertGetId(array_merge([
            'uuid'                  => (string) Str::uuid(),
            'name'                  => 'Tender Kerja Ujian ' . Str::random(5),
            'no_tender'             => 'QT-KERJA-' . Str::random(6),
            'status_process_id'     => $statusProcessId,
            'kategori_perolehan_id' => 3, // Kerja
            'kaedah_perolehan_id'   => 1, // Tender
            'type'                  => 'tender',
            'organization_unit_id'  => $this->orgId,
            'anggaran_jabatan'      => $anggaranJabatan,
            'price'                 => $anggaranJabatan,
            'created_at'            => now(),
            'updated_at'            => now(),
        ], $attributes));

        return Tender::query()->findOrFail($id);
    }

    protected function createVendor(string $name, string $registration): Vendor
    {
        return Vendor::query()->create([
            'name'                 => $name,
            'registration'         => $registration,
            'organization_unit_id' => $this->orgId,
        ]);
    }

    public function test_kerja_getBorang_only_includes_technically_qualified_vendors(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vendor1 = $this->createVendor('Vendor 1 Qualified', 'REG-K1');
        $vendor2 = $this->createVendor('Vendor 2 Disqualified Pembuka', 'REG-K2');
        $vendor3 = $this->createVendor('Vendor 3 Disqualified Teknikal', 'REG-K3');

        // Qualified Vendor 1
        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor1->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/3',
        ]);

        // Disqualified Vendor 2 at Pembuka
        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor2->id,
            'participate'           => 1,
            'cancel_fg'             => 1,
            'eliminated_process_id' => TenderProcessStatus::PENILAIAN_PEMBUKA, // 8
            'eliminated_reason'     => 'Gagal Pembuka',
            'eliminated_at'         => now(),
            'kod_pembekal'          => '2/3',
        ]);

        // Disqualified Vendor 3 at Teknikal
        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor3->id,
            'participate'           => 1,
            'cancel_fg'             => 1,
            'eliminated_process_id' => TenderProcessStatus::PENILAIAN_TEKNIKAL, // 10
            'eliminated_reason'     => 'Gagal Teknikal',
            'eliminated_at'         => now(),
            'kod_pembekal'          => '3/3',
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $view = $controller->getBorang($tender->no_tender, 'borang1');

        $participants = $view->getData()['participants'];

        $this->assertCount(1, $participants);
        $this->assertEquals($vendor1->id, $participants->first()->vendor_id);
    }

    public function test_kerja_borang_beyond_borang1_only_includes_vendors_who_passed_borang1(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vendor1 = $this->createVendor('Vendor 1 Sempurna', 'REG-S1');
        $vendor2 = $this->createVendor('Vendor 2 Tidak Sempurna', 'REG-S2');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor1->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/2',
        ]);

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor2->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '2/2',
        ]);

        // Progress unlock Borang 2
        $progress = TenderKewanganProgress::query()->create([
            'tender_id'     => $tender->id,
            'current_step'  => 2,
            'borang_status' => [
                'borang1' => ['status' => 'completed'],
            ],
        ]);

        // Vendor 1 passes Borang 1 (Sempurna)
        TenderKewanganKerjaEvaluation::query()->create([
            'tender_id'        => $tender->id,
            'vendor_id'        => $vendor1->id,
            'borang_code'      => 'borang1',
            'status_pematuhan' => 1,
            'payload'          => ['keputusan_akhir' => 'Sempurna'],
        ]);

        // Vendor 2 fails Borang 1 (Tidak Sempurna)
        TenderKewanganKerjaEvaluation::query()->create([
            'tender_id'        => $tender->id,
            'vendor_id'        => $vendor2->id,
            'borang_code'      => 'borang1',
            'status_pematuhan' => 0,
            'payload'          => ['keputusan_akhir' => 'Tidak Sempurna'],
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $view = $controller->getBorang($tender->no_tender, 'borang2');

        $participants = $view->getData()['participants'];

        $this->assertCount(1, $participants);
        $this->assertEquals($vendor1->id, $participants->first()->vendor_id);
    }

    public function test_kerja_save_endpoints_reject_disqualified_vendors(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vendorDisqualified = $this->createVendor('Vendor Eliminated', 'REG-ELIM');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendorDisqualified->id,
            'participate'           => 1,
            'cancel_fg'             => 1,
            'eliminated_process_id' => TenderProcessStatus::PENILAIAN_TEKNIKAL,
            'eliminated_reason'     => 'Gagal Spesifikasi Teknikal',
            'eliminated_at'         => now(),
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);

        // Test 1: simpanBorang4PenilaianVendor
        $req1 = new Request([
            'vendor_id'    => $vendorDisqualified->id,
            'projek_sakit' => 'TIADA',
        ]);
        $res1 = $controller->simpanBorang4PenilaianVendor($req1, $tender->no_tender);
        $this->assertEquals(422, $res1->getStatusCode());
        $this->assertStringContainsString('disingkirkan pada penilaian terdahulu', $res1->getData()->message);

        // Test 2: simpanBorang generic
        $req2 = new Request([
            'vendor_id'        => $vendorDisqualified->id,
            'status_pematuhan' => 1,
        ]);
        $res2 = $controller->simpanBorang($req2, $tender->no_tender, 'borang2');
        $this->assertEquals(422, $res2->getStatusCode());
        $this->assertStringContainsString('disingkirkan pada penilaian terdahulu', $res2->getData()->message);
    }

    public function test_kerja_simpan_borang15_never_resurrects_disqualified_vendors(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vPass = $this->createVendor('Vendor Pass Kewangan', 'REG-KP1');
        $vFailFin = $this->createVendor('Vendor Fail Kewangan', 'REG-KP2');
        $vFailTek = $this->createVendor('Vendor Fail Teknikal', 'REG-KP3');

        $tvPass = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vPass->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/3',
        ]);

        $tvFailFin = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vFailFin->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '2/3',
        ]);

        $tvFailTek = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vFailTek->id,
            'participate'           => 1,
            'cancel_fg'             => 1,
            'eliminated_process_id' => TenderProcessStatus::PENILAIAN_TEKNIKAL, // 10
            'eliminated_reason'     => 'Gagal Teknikal',
            'eliminated_at'         => now(),
            'kod_pembekal'          => '3/3',
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);

        // Attempting to select technically disqualified vendor MUST be rejected
        $reqIllegal = new Request([
            'chk_sah'          => 1,
            'selected_vendors' => [$vFailTek->id],
        ]);
        $resIllegal = $controller->simpanBorang15Muktamad($reqIllegal, $tender->no_tender);
        $this->assertEquals(422, $resIllegal->getStatusCode());
        $this->assertStringContainsString('tidak layak atau telah disingkirkan', $resIllegal->getData()->message);

        // Valid selection: only $vPass is recommended
        $reqValid = new Request([
            'chk_sah'          => 1,
            'selected_vendors' => [$vPass->id],
        ]);
        $resValid = $controller->simpanBorang15Muktamad($reqValid, $tender->no_tender);
        $this->assertEquals(200, $resValid->getStatusCode());

        // Refresh tender vendors from DB
        $tvPass->refresh();
        $tvFailFin->refresh();
        $tvFailTek->refresh();

        // 1. Recommended vendor is active
        $this->assertEquals(0, $tvPass->cancel_fg);
        $this->assertNull($tvPass->eliminated_process_id);

        // 2. Unselected eligible vendor is eliminated at Penilaian Kewangan (11)
        $this->assertEquals(1, $tvFailFin->cancel_fg);
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, $tvFailFin->eliminated_process_id);

        // 3. Vendor disqualified at Teknikal MUST REMAIN eliminated at Teknikal (10)
        $this->assertEquals(1, $tvFailTek->cancel_fg);
        $this->assertEquals(TenderProcessStatus::PENILAIAN_TEKNIKAL, $tvFailTek->eliminated_process_id);
    }

    public function test_cut_off_submitted_enforces_selection_in_kerja(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vendorCutOff = $this->createVendor('Vendor In CutOff', 'REG-CO1');
        $vendorOutCutOff = $this->createVendor('Vendor Out CutOff', 'REG-CO2');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendorCutOff->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/2',
        ]);

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendorOutCutOff->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '2/2',
        ]);

        // Insert submitted cut_off_selections containing ONLY vendorCutOff
        DB::table('cut_off_selections')->insert([
            'tender_id'     => $tender->id,
            'status'        => 'submitted',
            'selected_refs' => json_encode(['1/2']),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $view = $controller->getBorang($tender->no_tender, 'borang1');

        $participants = $view->getData()['participants'];

        $this->assertCount(1, $participants);
        $this->assertEquals($vendorCutOff->id, $participants->first()->vendor_id);
    }

    public function test_kerja_borang6_muktamad_finalizes_type5_tenders(): void
    {
        // Type 5: Kerja Kecil Selain M&E (<= 10M, non-M&E: 13 = Bangunan)
        $tender = $this->createKerjaTender(5000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL, [
            'kategori_perolehan_detail_id' => 13,
        ]);

        $vPass = $this->createVendor('Vendor Type5 Pass', 'REG-T5P');
        $vFail = $this->createVendor('Vendor Type5 Fail', 'REG-T5F');

        $tvPass = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vPass->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/2',
        ]);

        $tvFail = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vFail->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '2/2',
        ]);

        // Borang 1 evaluations
        TenderKewanganKerjaEvaluation::query()->create([
            'tender_id'        => $tender->id,
            'vendor_id'        => $vPass->id,
            'borang_code'      => 'borang1',
            'status_pematuhan' => 1,
            'payload'          => ['keputusan_akhir' => 'Sempurna'],
        ]);

        TenderKewanganKerjaEvaluation::query()->create([
            'tender_id'        => $tender->id,
            'vendor_id'        => $vFail->id,
            'borang_code'      => 'borang1',
            'status_pematuhan' => 0,
            'payload'          => ['keputusan_akhir' => 'Tidak Sempurna'],
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $res = $controller->simpanBorang6Muktamad(new Request(['chk_sah' => 1]), $tender->no_tender);

        $this->assertEquals(200, $res->getStatusCode());

        $tender->refresh();
        $tvPass->refresh();
        $tvFail->refresh();

        // Tender status advanced to 11
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, $tender->status_process_id);

        // Vendor who passed Borang 1 is active
        $this->assertEquals(0, $tvPass->cancel_fg);
        $this->assertNull($tvPass->eliminated_process_id);

        // Vendor who failed is eliminated at Penilaian Kewangan (11)
        $this->assertEquals(1, $tvFail->cancel_fg);
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, $tvFail->eliminated_process_id);
    }

    public function test_kerja_borang8_muktamad_finalizes_type4_tenders(): void
    {
        // Type 4: Kerja Kecil M&E (<= 10M, detailId = 15)
        $tender = $this->createKerjaTender(5000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL, [
            'kategori_perolehan_detail_id' => 15,
        ]);

        $vPass = $this->createVendor('Vendor Type4 Pass', 'REG-T4P');
        $vFail = $this->createVendor('Vendor Type4 Fail', 'REG-T4F');

        $tvPass = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vPass->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/2',
        ]);

        $tvFail = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vFail->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '2/2',
        ]);

        // Borang 1 evaluations
        TenderKewanganKerjaEvaluation::query()->create([
            'tender_id'        => $tender->id,
            'vendor_id'        => $vPass->id,
            'borang_code'      => 'borang1',
            'status_pematuhan' => 1,
            'payload'          => ['keputusan_akhir' => 'Sempurna'],
        ]);

        TenderKewanganKerjaEvaluation::query()->create([
            'tender_id'        => $tender->id,
            'vendor_id'        => $vFail->id,
            'borang_code'      => 'borang1',
            'status_pematuhan' => 0,
            'payload'          => ['keputusan_akhir' => 'Tidak Sempurna'],
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $res = $controller->simpanBorang8Muktamad(new Request(['chk_sah' => 1]), $tender->no_tender);

        $this->assertEquals(200, $res->getStatusCode());

        $tender->refresh();
        $tvPass->refresh();
        $tvFail->refresh();

        // Tender status advanced to 11
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, $tender->status_process_id);

        // Vendor who passed Borang 1 is active
        $this->assertEquals(0, $tvPass->cancel_fg);
        $this->assertNull($tvPass->eliminated_process_id);

        // Vendor who failed is eliminated at Penilaian Kewangan (11)
        $this->assertEquals(1, $tvFail->cancel_fg);
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, $tvFail->eliminated_process_id);
    }

    public function test_simpan_borang1_kriteria_saves_successfully(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);
        $vendor = $this->createVendor('Vendor Kriteria Test', 'REG-KRIT1');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/1',
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $request = new Request([
            'kriteria_id' => 1,
            'evaluations' => [
                [
                    'vendor_id' => $vendor->id,
                    'status'    => 'Sempurna',
                    'catatan'   => 'Lengkap dan teratur.',
                ],
            ],
        ]);

        $response = $controller->simpanBorang1Kriteria($request, $tender->no_tender);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getData()->success);

        $this->assertDatabaseHas('tender_kewangan_kerja_evaluations', [
            'tender_id'   => $tender->id,
            'vendor_id'   => $vendor->id,
            'borang_code' => 'borang1',
        ]);
    }

    public function test_simpan_borang2_kriteria_saves_successfully(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);
        $vendor = $this->createVendor('Vendor B2 Test', 'REG-B2T');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/1',
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $request = new Request([
            'doc_id'      => 'doc_1',
            'evaluations' => [
                [
                    'vendor_id'   => $vendor->id,
                    'dikemukakan' => 'Ya',
                    'diaudit'     => 'Ya',
                    'catatan'     => 'Dokumen diaudit dan lengkap.',
                ],
            ],
        ]);

        $response = $controller->simpanBorang2Kriteria($request, $tender->no_tender);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getData()->success);

        $this->assertDatabaseHas('tender_kewangan_kerja_evaluations', [
            'tender_id'   => $tender->id,
            'vendor_id'   => $vendor->id,
            'borang_code' => 'borang2',
        ]);
    }

    public function test_simpan_borang5_kriteria_saves_successfully(): void
    {
        $tender = $this->createKerjaTender(15000000.00, TenderProcessStatus::PENILAIAN_TEKNIKAL);
        $vendor = $this->createVendor('Vendor B5 Test', 'REG-B5T');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/1',
        ]);

        $controller = app(PenilaianKewanganKerjaController::class);
        $request = new Request([
            'borang_target' => 'borang1',
            'evaluations'   => [
                [
                    'vendor_id' => $vendor->id,
                    'status'    => 'Sempurna',
                    'catatan'   => 'Lulus peringkat.',
                ],
            ],
        ]);

        $response = $controller->simpanBorang5Kriteria($request, $tender->no_tender);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getData()->success);

        $this->assertDatabaseHas('tender_kewangan_kerja_evaluations', [
            'tender_id'   => $tender->id,
            'vendor_id'   => $vendor->id,
            'borang_code' => 'borang5',
        ]);
    }
}
