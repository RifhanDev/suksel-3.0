<?php

namespace Tests\Feature;

use App\Models\Ref\RefKategoriJenisPerolehan;
use App\Models\Ref\RefTypeOfPerolehan;
use App\Services\PenilaianKewanganWorkflowResolver;
use App\Tender;
use Tests\TestCase;

class PenilaianKewanganRoutingTest extends TestCase
{
    private PenilaianKewanganWorkflowResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new PenilaianKewanganWorkflowResolver();
    }

    private function makeTender(int $kategoriId, string $kaedahKind, float $anggaran, ?int $detailId = null, ?string $detailName = null): Tender
    {
        $tender = new Tender();
        $tender->id = 999;
        $tender->uuid = 'uuid-999';
        $tender->no_tender = 'TND-999';
        $tender->type = $kaedahKind;
        $tender->kategori_perolehan_id = $kategoriId;
        $tender->anggaran_jabatan = $anggaran;
        $tender->kategori_perolehan_detail_id = $detailId;

        // Mock relations
        $kategori = new RefKategoriJenisPerolehan();
        $kategori->id = $kategoriId;
        $kategori->name = match ($kategoriId) {
            1 => 'Perkhidmatan',
            2 => 'Bekalan',
            3 => 'Kerja',
            default => 'Lain-lain',
        };
        $tender->setRelation('kategoriPerolehan', $kategori);

        if ($detailId || $detailName) {
            $detail = new RefTypeOfPerolehan();
            $detail->id = $detailId ?: 1;
            $detail->name = $detailName ?: 'Standard';
            $tender->setRelation('kategoriPerolehanDetail', $detail);
        }

        return $tender;
    }

    /** Scenario 1: Kerja + Tender + AJ 15M + Bangunan => Type 3: Tender Kerja Besar */
    public function test_scenario_1_kerja_tender_besar(): void
    {
        $t = $this->makeTender(3, 'tender', 15000000.00, 1, 'Bangunan');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_BESAR, $this->resolver->classify($t));
        $this->assertSame('kerja_besar', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewanganKerja.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
        $meta = $this->resolver->resolveActionMeta($t);
        $this->assertTrue($meta['is_supported']);
        $this->assertSame('Nilai', $meta['button_label']);
    }

    /** Scenario 2: Kerja + Tender + AJ 10M + Bangunan => Type 5: Tender Kerja Kecil Selain M&E */
    public function test_scenario_2_kerja_tender_10m_bangunan(): void
    {
        $t = $this->makeTender(3, 'tender', 10000000.00, 1, 'Bangunan');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_KECIL_BUKAN_ME, $this->resolver->classify($t));
        $this->assertSame('kerja_kecil_other', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewanganKerja.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 3: Kerja + Tender + AJ 10M + M&E (id 15) => Type 4: Tender Kerja Kecil M&E */
    public function test_scenario_3_kerja_tender_10m_me_id(): void
    {
        $t = $this->makeTender(3, 'tender', 10000000.00, 15, 'Mekanikal & Elektrikal');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_KECIL_ME, $this->resolver->classify($t));
        $this->assertSame('kerja_kecil_me', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewanganKerja.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 4: Kerja + Tender + AJ 5M + Kejuruteraan Awam => Type 5: Tender Kerja Kecil Selain M&E */
    public function test_scenario_4_kerja_tender_5m_awam(): void
    {
        $t = $this->makeTender(3, 'tender', 5000000.00, 2, 'Kejuruteraan Awam');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_KECIL_BUKAN_ME, $this->resolver->classify($t));
        $this->assertSame('kerja_kecil_other', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewanganKerja.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 5: Kerja + Tender + AJ 5M + Mekanikal (name match) => Type 4: Tender Kerja Kecil M&E */
    public function test_scenario_5_kerja_tender_5m_mekanikal_name(): void
    {
        $t = $this->makeTender(3, 'tender', 5000000.00, 99, 'Kerja-kerja Mekanikal');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_KECIL_ME, $this->resolver->classify($t));
        $this->assertSame('kerja_kecil_me', $t->getKerjaClassification());
    }

    /** Scenario 6: Kerja + Tender + AJ 5M + Elektrikal (name match) => Type 4: Tender Kerja Kecil M&E */
    public function test_scenario_6_kerja_tender_5m_elektrikal_name(): void
    {
        $t = $this->makeTender(3, 'tender', 5000000.00, 98, 'Pemasangan Elektrikal');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_KECIL_ME, $this->resolver->classify($t));
        $this->assertSame('kerja_kecil_me', $t->getKerjaClassification());
    }

    /** Scenario 7: Kerja + Tender + AJ 10.01M + M&E => Type 3: Tender Kerja Besar */
    public function test_scenario_7_kerja_tender_above_threshold_even_me(): void
    {
        $t = $this->makeTender(3, 'tender', 10010000.00, 15, 'M&E');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_TENDER_KERJA_BESAR, $this->resolver->classify($t));
        $this->assertSame('kerja_besar', $t->getKerjaClassification());
    }

    /** Scenario 8: Kerja + Sebut Harga + AJ 300K => Type 2: Sebut Harga Kerja */
    public function test_scenario_8_kerja_sebut_harga_300k(): void
    {
        $t = $this->makeTender(3, 'quotation', 300000.00, 1, 'Bangunan');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_SEBUT_HARGA_KERJA, $this->resolver->classify($t));
        $this->assertSame('sebut_harga_kerja', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewanganSebutHargaKerja.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
        $meta = $this->resolver->resolveActionMeta($t);
        $this->assertTrue($meta['is_supported']);
        $this->assertFalse($meta['disabled']);
        $this->assertSame('Nilai', $meta['button_label']);
        $this->assertSame('Sebut Harga Kerja', $meta['badge_label']);
    }

    /** Scenario 9: Kerja + Sebut Harga + AJ 12M => Type 2: Sebut Harga Kerja */
    public function test_scenario_9_kerja_sebut_harga_12m(): void
    {
        $t = $this->makeTender(3, 'quotation', 12000000.00, 1, 'Bangunan');
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_SEBUT_HARGA_KERJA, $this->resolver->classify($t));
        $this->assertSame('sebut_harga_kerja', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewanganSebutHargaKerja.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 10: Bekalan + Tender + AJ 15M => Type 1: Normal */
    public function test_scenario_10_bekalan_tender(): void
    {
        $t = $this->makeTender(2, 'tender', 15000000.00);
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_NORMAL, $this->resolver->classify($t));
        $this->assertSame('non_kerja', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewangan.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 11: Bekalan + Sebut Harga + AJ 200K => Type 1: Normal */
    public function test_scenario_11_bekalan_sebut_harga(): void
    {
        $t = $this->makeTender(2, 'quotation', 200000.00);
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_NORMAL, $this->resolver->classify($t));
        $this->assertSame('non_kerja', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewangan.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 12: Perkhidmatan + Tender + AJ 15M => Type 1: Normal */
    public function test_scenario_12_perkhidmatan_tender(): void
    {
        $t = $this->makeTender(1, 'tender', 15000000.00);
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_NORMAL, $this->resolver->classify($t));
        $this->assertSame('non_kerja', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewangan.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Scenario 13: Perkhidmatan + Sebut Harga + AJ 200K => Type 1: Normal */
    public function test_scenario_13_perkhidmatan_sebut_harga(): void
    {
        $t = $this->makeTender(1, 'quotation', 200000.00);
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_NORMAL, $this->resolver->classify($t));
        $this->assertSame('non_kerja', $t->getKerjaClassification());
        $this->assertSame(route('penilaianKewangan.show', 'uuid-999'), $this->resolver->resolveShowUrl($t));
    }

    /** Edge Case: Invalid data (e.g. 0 budget for Kerja tender) */
    public function test_edge_case_zero_budget_kerja_tender(): void
    {
        $t = $this->makeTender(3, 'tender', 0.0);
        $this->assertSame(PenilaianKewanganWorkflowResolver::WORKFLOW_INVALID, $this->resolver->classify($t));
        $this->assertNull($this->resolver->resolveShowUrl($t));
        $meta = $this->resolver->resolveActionMeta($t);
        $this->assertFalse($meta['is_supported']);
        $this->assertTrue($meta['disabled']);
    }
}
