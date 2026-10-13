<?php

namespace Tests\Unit;

use App\Support\PenilaianKewanganKerjaCalculator;
use PHPUnit\Framework\TestCase;

class PenilaianKewanganKerjaCalculatorTest extends TestCase
{
    private PenilaianKewanganKerjaCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new PenilaianKewanganKerjaCalculator();
    }

    public function test_calculate_kemampuan_kewangan_with_1_point_5_percent_threshold(): void
    {
        $anggaranJabatan = 500000.00; // RM 500,000

        $lembaran = [
            'aset_tetap'             => 320000.00,
            'aset_semasa'            => 210000.00,
            'liabiliti_semasa'       => 85500.00,
            'liabiliti_tetap'        => 110000.00,
            'wang_tunai'             => 20000.00,
            'baki_kemudahan_kredit'  => 0.00,
        ];

        $penyataBank = [
            'grand_total' => 144600.00, // 3 months total
            'accounts'    => [],
        ];

        $bonSaham = [
            'grand_total' => 0.00,
            'accounts'    => [],
        ];

        $res = $this->calculator->calculateKemampuanKewangan(
            $lembaran,
            $penyataBank,
            $bonSaham,
            $anggaranJabatan,
            1.5
        );

        // Modal Pusingan = 210,000 - 85,500 = 124,500
        $this->assertEquals(124500.00, $res['modal_pusingan']);
        // Purata Bank = 144,600 / 3 = 48,200
        $this->assertEquals(48200.00, $res['purata_bank']);
        // Wang Tangan = 20,000
        $this->assertEquals(20000.00, $res['wang_tangan']);
        // Modal Minimum (1.5% of 500k) = 7,500
        $this->assertEquals(7500.00, $res['modal_minimum']);
        // Surplus
        $this->assertTrue($res['is_cukup_modal']);
        $this->assertSame('Mencukupi', $res['status_modal_badge']);
    }

    public function test_summarize_kerja_semasa_computes_remaining_work(): void
    {
        $records = [
            [
                'nama'             => 'Projek Saliran Parit 1',
                'majikan'          => 'JPS Selangor',
                'harga'            => 200000.00,
                'kemajuan_sebenar' => 50, // 50% done -> 100k remaining
            ],
            [
                'nama'             => 'Projek Kolam Takungan',
                'majikan'          => 'MPK',
                'harga'            => 150000.00,
                'kemajuan_sebenar' => 80, // 80% done -> 30k remaining
            ],
        ];

        $summary = $this->calculator->summarizeKerjaSemasa($records);

        $this->assertSame(2, $summary['bil_projek']);
        $this->assertEquals(350000.00, $summary['total_nilai_kontrak']);
        $this->assertEquals(130000.00, $summary['total_baki_kerja']);
        $this->assertEquals(200000.00, $summary['projek_terbesar']);
    }

    public function test_summarize_pengalaman_kerja(): void
    {
        $records = [
            [
                'tajuk'       => 'Penaiktarafan Kolam Tadahan',
                'pelanggan'   => 'JKR',
                'nilai_kerja' => 450000.00,
                'tahun_siap'  => '2024',
            ],
        ];

        $summary = $this->calculator->summarizePengalamanKerja($records);

        $this->assertSame(1, $summary['bil_projek']);
        $this->assertEquals(450000.00, $summary['total_nilai']);
        $this->assertEquals(450000.00, $summary['projek_terbesar']);
    }
}
