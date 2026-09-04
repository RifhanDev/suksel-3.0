<?php

namespace Tests\Feature;

use App\Models\EbiddingJadualBidaan;
use App\Models\PerakuanJabatanKertasTaklimat;
use App\Models\PerakuanJabatanPengesyoranPembekal;
use App\Services\TenderProcessStatusService;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProcurementFlowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_bidaan_status_loop_uses_eleven_twelve_thirteen(): void
    {
        $this->skipIfWorkflowSchemaMissing();

        $service = app(TenderProcessStatusService::class);
        $tender = $this->createTender(TenderProcessStatus::PENILAIAN_KEWANGAN, 1);

        // First pass PJ complete -> 12
        PerakuanJabatanKertasTaklimat::query()->create([
            'tender_id' => $tender->id,
            'catatan' => 'OK',
            'submitted_at' => now(),
        ]);
        PerakuanJabatanPengesyoranPembekal::query()->create([
            'tender_id' => $tender->id,
            'catatan' => 'Layak',
            'sahkan_petender_layak' => true,
            'pengesahan_bidaan' => false,
            'submitted_at' => now(),
        ]);
        $service->syncPerakuanJabatanCompletion($tender->fresh());
        $tender->refresh();
        $this->assertSame(TenderProcessStatus::PERAKUAN_JABATAN, (int) $tender->status_process_id);

        // JP chooses Bidaan -> back to 11 / stage 1
        $this->assertTrue(TenderProcessStatus::allowsBidaanKaedah(1));
        $this->assertFalse(TenderProcessStatus::allowsBidaanKaedah(3));

        Tender::query()->where('id', $tender->id)->update([
            'is_ebidding' => true,
            'ebidding_process_stage_id' => 1,
            'status_process_id' => TenderProcessStatus::PENILAIAN_KEWANGAN,
        ]);
        $tender->refresh();
        $this->assertSame(TenderProcessStatus::PENILAIAN_KEWANGAN, (int) $tender->status_process_id);
        $this->assertSame(1, (int) $tender->ebidding_process_stage_id);

        // While stage < 3, first-pass submitted_at must NOT push back to 12
        $service->syncPerakuanJabatanCompletion($tender->fresh());
        $tender->refresh();
        $this->assertSame(TenderProcessStatus::PENILAIAN_KEWANGAN, (int) $tender->status_process_id);

        // After bidding window / review stage, laporan + pengesahan required
        Tender::query()->where('id', $tender->id)->update([
            'ebidding_process_stage_id' => 3,
            'status_process_id' => TenderProcessStatus::PENILAIAN_KEWANGAN,
        ]);
        EbiddingJadualBidaan::query()->create([
            'tender_id' => $tender->id,
            'tarikh_bidaan_mula' => Carbon::yesterday()->toDateString(),
            'masa_bidaan_mula' => '09:00',
            'tarikh_bidaan_tamat' => Carbon::yesterday()->toDateString(),
            'masa_bidaan_tamat' => '17:00',
            'started_at' => now()->subDay(),
            'submitted_at' => now()->subDay(),
        ]);

        $service->syncPerakuanJabatanCompletion($tender->fresh());
        $tender->refresh();
        $this->assertSame(TenderProcessStatus::PENILAIAN_KEWANGAN, (int) $tender->status_process_id);

        $header = PerakuanJabatanKertasTaklimat::query()->where('tender_id', $tender->id)->first();
        $laporan = $header->items()->create([
            'slot_key' => 'laporan_bidaan',
            'kandungan' => 'Laporan Bidaan',
            'sort_order' => 99,
        ]);
        $laporan->files()->create([
            'file_path' => 'uploads/test/laporan.pdf',
            'file_original_name' => 'laporan.pdf',
        ]);

        PerakuanJabatanPengesyoranPembekal::query()
            ->where('tender_id', $tender->id)
            ->update(['pengesahan_bidaan' => true]);

        $service->syncPerakuanJabatanCompletion($tender->fresh());
        $tender->refresh();
        $this->assertSame(TenderProcessStatus::PERAKUAN_JABATAN, (int) $tender->status_process_id);

        // Non-bidaan JP path -> 13
        $service->setStatus($tender, TenderProcessStatus::JAWATANKUASA_PEROLEHAN);
        $tender->refresh();
        $this->assertSame(TenderProcessStatus::JAWATANKUASA_PEROLEHAN, (int) $tender->status_process_id);
    }

    public function test_kerja_does_not_allow_bidaan_kaedah(): void
    {
        $this->assertFalse(TenderProcessStatus::allowsBidaanKaedah(3));
        $this->assertTrue(TenderProcessStatus::allowsBidaanKaedah(1));
        $this->assertTrue(TenderProcessStatus::allowsBidaanKaedah(2));
    }

    private function createTender(int $statusProcessId, int $kategoriPerolehanId = 1): Tender
    {
        $orgId = DB::table('organization_units')->insertGetId([
            'name' => 'Unit Tender',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'name' => 'Tender Ujian Flow',
            'organization_unit_id' => $orgId,
            'status_process_id' => $statusProcessId,
            'is_ebidding' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('tenders', 'kategori_perolehan_id')) {
            $payload['kategori_perolehan_id'] = $kategoriPerolehanId;
        }

        $tenderId = DB::table('tenders')->insertGetId($payload);

        return Tender::query()->findOrFail($tenderId);
    }

    private function skipIfWorkflowSchemaMissing(): void
    {
        $hasTenderColumns = Schema::hasColumns('tenders', [
            'status_process_id',
            'is_ebidding',
            'ebidding_process_stage_id',
        ]);

        $requiredTables = [
            'perakuan_jabatan_kertas_taklimats',
            'perakuan_jabatan_kertas_taklimat_items',
            'perakuan_jabatan_kertas_taklimat_item_files',
            'perakuan_jabatan_pengesyoran_pembekals',
            'ebidding_jadual_bidaans',
        ];

        $missingTable = collect($requiredTables)->first(fn (string $table) => ! Schema::hasTable($table));

        if (! $hasTenderColumns || $missingTable) {
            $this->markTestSkipped(
                'Workflow schema not available in current DB. Run latest migrations before executing this test.'
            );
        }
    }
}
