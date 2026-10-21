<?php

namespace Tests\Feature;

use App\Models\FinancialChecklistHeader;
use App\Models\FinancialChecklistItem;
use App\Models\TenderKewanganEvaluation;
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

class PenilaianKewanganProgressionTest extends TestCase
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
            'name'       => 'Unit Tender Test ' . Str::random(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function createTender(int $statusProcessId = 10, array $attributes = []): Tender
    {
        $id = DB::table('tenders')->insertGetId(array_merge([
            'uuid'                  => (string) Str::uuid(),
            'no_tender'             => 'QT-TEST-' . Str::random(6),
            'status_process_id'     => $statusProcessId,
            'kategori_perolehan_id' => 1, // Bekalan
            'kaedah_perolehan_id'   => 1,
            'organization_unit_id'  => $this->orgId,
            'anggaran_jabatan'      => 100000.00,
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

    public function test_only_technically_qualified_vendors_participate_in_kewangan(): void
    {
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vendor1 = $this->createVendor('Vendor 1 Qualified', 'REG-1');
        $vendor2 = $this->createVendor('Vendor 2 Failed Teknikal', 'REG-2');
        $vendor3 = $this->createVendor('Vendor 3 Qualified', 'REG-3');

        // Qualified vendor 1
        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor1->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/3',
        ]);

        // Disqualified vendor 2 in Penilaian Teknikal
        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor2->id,
            'participate'           => 1,
            'cancel_fg'             => 1,
            'eliminated_process_id' => TenderProcessStatus::PENILAIAN_TEKNIKAL, // 10
            'eliminated_reason'     => 'Gagal Teknikal',
            'eliminated_at'         => now(),
            'kod_pembekal'          => '2/3',
        ]);

        // Qualified vendor 3
        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendor3->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '3/3',
        ]);

        $controller = app(\App\Http\Controllers\PenilaianKewanganController::class);
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('loadEligibleTechnicalParticipants');
        $method->setAccessible(true);

        $eligible = $method->invoke($controller, $tender);

        $eligibleVendorIds = $eligible->pluck('vendor_id')->all();

        $this->assertContains($vendor1->id, $eligibleVendorIds);
        $this->assertContains($vendor3->id, $eligibleVendorIds);
        $this->assertNotContains($vendor2->id, $eligibleVendorIds, 'Vendor eliminated in Penilaian Teknikal must not be eligible.');
    }

    public function test_step1_progression_and_confirmation_rules(): void
    {
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $v1 = $this->createVendor('V1', 'R1');
        $v2 = $this->createVendor('V2', 'R2');

        TenderVendor::query()->create([
            'tender_id'   => $tender->id,
            'vendor_id'   => $v1->id,
            'participate' => 1,
            'cancel_fg'   => 0,
        ]);
        TenderVendor::query()->create([
            'tender_id'   => $tender->id,
            'vendor_id'   => $v2->id,
            'participate' => 1,
            'cancel_fg'   => 0,
        ]);

        $fciUuid = (string) Str::uuid();

        $controller = app(\App\Http\Controllers\PenilaianKewanganController::class);
        $ref = new \ReflectionClass($controller);
        $step1PassedMethod = $ref->getMethod('getStep1PassedVendorIds');
        $step1PassedMethod->setAccessible(true);

        // Before any evaluation: none passed
        $passedBefore = $step1PassedMethod->invoke($controller, $tender, [$fciUuid]);
        $this->assertEmpty($passedBefore, 'No vendors should pass Step 1 when unevaluated.');

        // V1 evaluated mematuhi (1), V2 evaluated tidak mematuhi (0)
        TenderKewanganEvaluation::query()->create([
            'tender_id'            => $tender->id,
            'vendor_id'            => $v1->id,
            'checklist_item_uuid'  => $fciUuid,
            'status_pematuhan'     => 1,
            'skor'                 => 10,
        ]);
        TenderKewanganEvaluation::query()->create([
            'tender_id'            => $tender->id,
            'vendor_id'            => $v2->id,
            'checklist_item_uuid'  => $fciUuid,
            'status_pematuhan'     => 0,
            'skor'                 => 0,
        ]);

        $passedAfter = $step1PassedMethod->invoke($controller, $tender, [$fciUuid]);
        $this->assertContains($v1->id, $passedAfter);
        $this->assertNotContains($v2->id, $passedAfter, 'V2 failed Step 1 so it cannot be in step1PassedVendorIds.');
    }

    public function test_step2_requires_step1_pass_and_qualifies_for_step3(): void
    {
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $v1 = $this->createVendor('V1', 'R1');
        $v2 = $this->createVendor('V2', 'R2');

        TenderVendor::query()->create(['tender_id' => $tender->id, 'vendor_id' => $v1->id, 'participate' => 1, 'cancel_fg' => 0]);
        TenderVendor::query()->create(['tender_id' => $tender->id, 'vendor_id' => $v2->id, 'participate' => 1, 'cancel_fg' => 0]);

        $fciUuid = (string) Str::uuid();
        $penyataUuid = (string) Str::uuid();

        // V1 passes Step 1, V2 passes Step 1
        TenderKewanganEvaluation::query()->create([
            'tender_id' => $tender->id, 'vendor_id' => $v1->id, 'checklist_item_uuid' => $fciUuid, 'status_pematuhan' => 1,
        ]);
        TenderKewanganEvaluation::query()->create([
            'tender_id' => $tender->id, 'vendor_id' => $v2->id, 'checklist_item_uuid' => $fciUuid, 'status_pematuhan' => 1,
        ]);

        // In Step 2 (Penyata Bank): V1 passes (1), V2 fails (0)
        TenderKewanganEvaluation::query()->create([
            'tender_id' => $tender->id, 'vendor_id' => $v1->id, 'checklist_item_uuid' => $penyataUuid, 'status_pematuhan' => 1,
        ]);
        TenderKewanganEvaluation::query()->create([
            'tender_id' => $tender->id, 'vendor_id' => $v2->id, 'checklist_item_uuid' => $penyataUuid, 'status_pematuhan' => 0,
        ]);

        $controller = app(\App\Http\Controllers\PenilaianKewanganController::class);
        $ref = new \ReflectionClass($controller);
        $step2PassedMethod = $ref->getMethod('getStep2PassedVendorIds');
        $step2PassedMethod->setAccessible(true);

        $step2Passed = $step2PassedMethod->invoke($controller, $tender, [$fciUuid], [$penyataUuid]);

        $this->assertContains($v1->id, $step2Passed);
        $this->assertNotContains($v2->id, $step2Passed, 'V2 failed Penyata Bank so it cannot pass Step 2.');
    }

    public function test_step4_and_submission_never_resurrects_technically_eliminated_vendors(): void
    {
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vPass = $this->createVendor('VPass', 'RPASS');
        $vFailFin = $this->createVendor('VFailFin', 'RFAILFIN');
        $vFailTech = $this->createVendor('VFailTech', 'RFAILTECH');

        $tvPass = TenderVendor::query()->create([
            'tender_id' => $tender->id, 'vendor_id' => $vPass->id, 'participate' => 1, 'cancel_fg' => 0,
        ]);
        $tvFailFin = TenderVendor::query()->create([
            'tender_id' => $tender->id, 'vendor_id' => $vFailFin->id, 'participate' => 1, 'cancel_fg' => 0,
        ]);
        $tvFailTech = TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vFailTech->id,
            'participate'           => 1,
            'cancel_fg'             => 1,
            'eliminated_process_id' => TenderProcessStatus::PENILAIAN_TEKNIKAL, // 10
            'eliminated_reason'     => 'Gagal teknikal terdahulu',
            'eliminated_at'         => now()->subDay(),
        ]);

        TenderKewanganProgress::query()->create([
            'tender_id'           => $tender->id,
            'current_step'        => 3,
            'step1_confirmed_at'  => now(),
            'step1_confirmed_by'  => 1,
            'step2_confirmed_at'  => now(),
            'step2_confirmed_by'  => 1,
            'step3_confirmed_at'  => now(),
            'step3_confirmed_by'  => 1,
        ]);

        // Create Financial Checklist items for the tender: 1 documentation item and 1 bank statement item
        $fHeader = \App\Models\FinancialChecklistHeader::query()->create([
            'tender_id'          => $tender->id,
            'uuid'               => (string) Str::uuid(),
            'max_score'          => 100,
            'passing_score'      => 50,
            'passing_percentage' => 50,
        ]);
        $fItem = \App\Models\FinancialChecklistItem::query()->create([
            'financial_checklist_header_id' => $fHeader->id,
            'uuid'                          => (string) Str::uuid(),
            'title'                         => 'Dokumen Kewangan Wajib',
            'vendor_action'                 => 'vendor_upload',
            'score'                         => 100,
        ]);
        $pItem = \App\Models\FinancialChecklistItem::query()->create([
            'financial_checklist_header_id' => $fHeader->id,
            'uuid'                          => (string) Str::uuid(),
            'title'                         => 'Penyata Bulanan Bank',
            'vendor_action'                 => 'online_form',
            'score'                         => 0,
        ]);

        $controller = app(\App\Http\Controllers\PenilaianKewanganController::class);

        $request = new \Illuminate\Http\Request([
            'tender' => $tender->no_tender,
        ]);

        // 1. Initially, when vendors are not evaluated yet, submission MUST fail with 422
        $responsePending = $controller->hantar($request);
        $this->assertEquals(422, $responsePending->getStatusCode());
        $this->assertStringContainsString('belum selesai dinilai', $responsePending->getData()->message);

        // 2. Evaluate vendors:
        // VPass passes Step 1, Step 2, and Step 3 (score 80/100)
        TenderKewanganEvaluation::query()->create([
            'tender_id'           => $tender->id,
            'vendor_id'           => $vPass->id,
            'checklist_item_uuid' => $fItem->uuid,
            'status_pematuhan'    => 1,
            'skor'                => 80,
        ]);
        TenderKewanganEvaluation::query()->create([
            'tender_id'           => $tender->id,
            'vendor_id'           => $vPass->id,
            'checklist_item_uuid' => $pItem->uuid,
            'status_pematuhan'    => 1,
            'skor'                => 0,
        ]);

        // VFailFin fails Step 1 (status_pematuhan = 0)
        TenderKewanganEvaluation::query()->create([
            'tender_id'           => $tender->id,
            'vendor_id'           => $vFailFin->id,
            'checklist_item_uuid' => $fItem->uuid,
            'status_pematuhan'    => 0,
            'skor'                => 0,
        ]);

        // 3. Now submit again -> should succeed with 200
        $responseSuccess = $controller->hantar($request);
        $this->assertEquals(200, $responseSuccess->getStatusCode());

        // Refresh models
        $tender->refresh();
        $tvPass->refresh();
        $tvFailFin->refresh();
        $tvFailTech->refresh();

        // 4. Tender must advance to Perakuan Jabatan (11 / TenderProcessStatus::PENILAIAN_KEWANGAN)
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, (int) $tender->status_process_id);

        // 5. Technically disqualified vendor MUST REMAIN ELIMINATED (never resurrected!)
        $this->assertEquals(1, (int) $tvFailTech->cancel_fg, 'Technically disqualified vendor must never be resurrected!');
        $this->assertEquals(TenderProcessStatus::PENILAIAN_TEKNIKAL, (int) $tvFailTech->eliminated_process_id);

        // 6. Financially disqualified vendor must be eliminated with PENILAIAN_KEWANGAN (11)
        $this->assertEquals(1, (int) $tvFailFin->cancel_fg);
        $this->assertEquals(TenderProcessStatus::PENILAIAN_KEWANGAN, (int) $tvFailFin->eliminated_process_id);

        // 7. Financially qualified vendor must remain active (cancel_fg = 0)
        $this->assertEquals(0, (int) $tvPass->cancel_fg);
        $this->assertNull($tvPass->eliminated_process_id);
    }

    public function test_step3_evaluation_succeeds_when_steps_1_and_2_are_confirmed(): void
    {
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_TEKNIKAL);
        $v = $this->createVendor('V1', 'R1');

        TenderVendor::query()->create([
            'tender_id'   => $tender->id,
            'vendor_id'   => $v->id,
            'participate' => 1,
            'cancel_fg'   => 0,
        ]);

        $header = FinancialChecklistHeader::query()->create([
            'tender_id' => $tender->id,
            'passing_percentage' => 50,
            'max_score' => 100,
        ]);

        $step1Item = FinancialChecklistItem::query()->create([
            'uuid' => (string) Str::uuid(),
            'financial_checklist_header_id' => $header->id,
            'title' => 'Dokumen Sokongan Kewangan',
            'action_type' => 'document',
        ]);

        $step3Item = FinancialChecklistItem::query()->create([
            'uuid' => (string) Str::uuid(),
            'financial_checklist_header_id' => $header->id,
            'title' => 'Spesifikasi Kewangan',
            'action_type' => 'specification',
            'score' => 100,
        ]);

        // Mark steps 1 & 2 as confirmed in progress
        TenderKewanganProgress::query()->create([
            'tender_id' => $tender->id,
            'current_step' => 3,
            'step1_confirmed_at' => now(),
            'step2_confirmed_at' => now(),
        ]);

        // Evaluate Step 1
        TenderKewanganEvaluation::query()->create([
            'tender_id' => $tender->id,
            'vendor_id' => $v->id,
            'checklist_item_uuid' => $step1Item->uuid,
            'status_pematuhan' => 1,
        ]);

        $this->actingAs($this->user);

        $request = Request::create('/penilaian-kewangan/simpan-pematuhan', 'POST', [
            'tender' => (string) $tender->id,
            'vendor_id' => $v->id,
            'checklist_item_uuid' => $step3Item->uuid,
            'status_pematuhan' => 1,
            'skor' => 85,
            'step' => 3,
        ]);

        $controller = app(\App\Http\Controllers\PenilaianKewanganController::class);
        $response = $controller->simpanPematuhan($request);

        $this->assertEquals(200, $response->getStatusCode(), (string) $response->getContent());
        $this->assertDatabaseHas('tender_kewangan_evaluations', [
            'tender_id' => $tender->id,
            'vendor_id' => $v->id,
            'checklist_item_uuid' => $step3Item->uuid,
            'skor' => 85,
        ]);
    }

    public function test_type1_cut_off_submitted_enforces_selection(): void
    {
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_TEKNIKAL);

        $vendorIn = $this->createVendor('Vendor In CutOff', 'REG-T1-IN');
        $vendorOut = $this->createVendor('Vendor Out CutOff', 'REG-T1-OUT');

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendorIn->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '1/2',
        ]);

        TenderVendor::query()->create([
            'tender_id'             => $tender->id,
            'vendor_id'             => $vendorOut->id,
            'participate'           => 1,
            'cancel_fg'             => 0,
            'eliminated_process_id' => null,
            'kod_pembekal'          => '2/2',
        ]);

        DB::table('cut_off_selections')->insert([
            'tender_id'     => $tender->id,
            'status'        => 'submitted',
            'selected_refs' => json_encode(['1/2']),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $controller = app(\App\Http\Controllers\PenilaianKewanganController::class);
        $method = new \ReflectionMethod($controller, 'loadEligibleTechnicalParticipants');
        $method->setAccessible(true);
        $participants = $method->invoke($controller, $tender);

        $this->assertCount(1, $participants);
        $this->assertEquals($vendorIn->id, $participants->first()->vendor_id);
    }
}
