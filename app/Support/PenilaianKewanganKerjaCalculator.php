<?php

namespace App\Support;

class PenilaianKewanganKerjaCalculator
{
    public const DEFAULT_MIN_CAPITAL_PERCENT = 1.5;

    /**
     * Calculate financial capability metrics based on Treasury Circular PK 2.1 & Borang 3 standards.
     *
     * @param  array<string, mixed>  $lembaran
     * @param  array<string, mixed>  $penyataBank
     * @param  array<string, mixed>  $bonSaham
     * @return array<string, mixed>
     */
    public function calculateKemampuanKewangan(
        array $lembaran,
        array $penyataBank,
        array $bonSaham,
        float $anggaranJabatan,
        float $minCapitalPct = self::DEFAULT_MIN_CAPITAL_PERCENT
    ): array {
        $asetTetap = (float) ($lembaran['aset_tetap'] ?? 0);
        $asetSemasa = (float) ($lembaran['aset_semasa'] ?? 0);
        $liabilitiSemasa = (float) ($lembaran['liabiliti_semasa'] ?? 0);
        $liabilitiTetap = (float) ($lembaran['liabiliti_tetap'] ?? 0);
        $wangTunai = (float) ($lembaran['wang_tunai'] ?? 0);
        $bakiKredit = (float) ($lembaran['baki_kemudahan_kredit'] ?? $lembaran['baki_kredit'] ?? 0);

        // 1. Modal Pusingan (D) = Aset Semasa - Liabiliti Semasa
        $modalPusingan = $asetSemasa - $liabilitiSemasa;

        // 2. Penyata Bank: 3-month average
        $pbGrandTotal = (float) ($penyataBank['grand_total'] ?? 0);
        $pbAccounts = $penyataBank['accounts'] ?? [];
        if ($pbGrandTotal <= 0 && ! empty($pbAccounts)) {
            $pbGrandTotal = array_sum(array_column($pbAccounts, 'total'));
        }
        $purataBank = $pbGrandTotal > 0 ? ($pbGrandTotal / 3) : 0.0;

        // 3. Wang Dalam Tangan & Bon/Saham
        $wangDalamTangan = max(0, $wangTunai);
        $bsGrandTotal = (float) ($bonSaham['grand_total'] ?? 0);
        $bsAccounts = $bonSaham['accounts'] ?? [];
        if ($bsGrandTotal <= 0 && ! empty($bsAccounts)) {
            $bsGrandTotal = array_sum(array_column($bsAccounts, 'jumlah_deposit'));
        }
        $asetCair = $wangDalamTangan + $bsGrandTotal;

        // 4. Jumlah Modal Tersedia (K)
        // K = Purata 3 Bulan (F) + Wang Tangan (G) + Bon/Saham (H) + Aset Cair (I) + Kemudahan Kredit (J)
        $jumlahModal = $purataBank + $wangDalamTangan + $bsGrandTotal + $asetCair + $bakiKredit;

        // 5. Modal Minimum Requirement (1.5% of Anggaran Jabatan / Nilai Perolehan)
        $modalMinimum = $anggaranJabatan * ($minCapitalPct / 100);
        $surplusModal = $jumlahModal - $modalMinimum;
        $isCukupModal = ($modalPusingan >= $modalMinimum) || ($jumlahModal >= $modalMinimum);

        return [
            'aset_tetap'             => $asetTetap,
            'aset_semasa'            => $asetSemasa,
            'liabiliti_semasa'       => $liabilitiSemasa,
            'liabiliti_tetap'        => $liabilitiTetap,
            'wang_tunai'             => $wangTunai,
            'baki_kredit'            => $bakiKredit,
            'modal_pusingan'         => $modalPusingan,
            'modal_pusingan_disp'    => 'RM ' . number_format($modalPusingan, 2),
            'pb_grand_total'         => $pbGrandTotal,
            'pb_accounts'            => $pbAccounts,
            'purata_bank'            => $purataBank,
            'purata_bank_disp'       => 'RM ' . number_format($purataBank, 2),
            'wang_tangan'            => $wangDalamTangan,
            'wang_tangan_disp'       => 'RM ' . number_format($wangDalamTangan, 2),
            'bon_saham_total'        => $bsGrandTotal,
            'bon_saham_total_disp'   => 'RM ' . number_format($bsGrandTotal, 2),
            'bon_saham_accounts'     => $bsAccounts,
            'aset_cair'              => $asetCair,
            'aset_cair_disp'         => 'RM ' . number_format($asetCair, 2),
            'jumlah_modal'           => $jumlahModal,
            'jumlah_modal_disp'      => 'RM ' . number_format($jumlahModal, 2),
            'modal_minimum'          => $modalMinimum,
            'modal_minimum_disp'     => 'RM ' . number_format($modalMinimum, 2),
            'peratus_modal_minimum'  => $minCapitalPct,
            'surplus_modal'          => $surplusModal,
            'surplus_modal_disp'     => 'RM ' . number_format($surplusModal, 2),
            'is_cukup_modal'         => $isCukupModal,
            'status_modal_badge'     => $isCukupModal ? 'Mencukupi' : 'Tidak Mencukupi',
        ];
    }

    /**
     * Summarize current work commitments (Kerja Semasa).
     *
     * @param  array<int, array<string, mixed>>  $rawItems
     * @return array<string, mixed>
     */
    public function summarizeKerjaSemasa(array $rawItems): array
    {
        $items = [];
        $totalNilaiKontrak = 0.0;
        $totalBakiKerja = 0.0;
        $projekTerbesar = 0.0;

        foreach ($rawItems as $idx => $item) {
            $nama = trim((string) ($item['nama'] ?? $item['nama_projek'] ?? $item['tajuk'] ?? ''));
            if ($nama === '' || $nama === '-') {
                continue;
            }

            $harga = (float) ($item['harga'] ?? $item['nilai_kontrak'] ?? $item['nilai'] ?? 0);
            $sebenar = (float) ($item['kemajuan_sebenar'] ?? $item['peratus_siap'] ?? 0);
            $jadual = (float) ($item['kemajuan_jadual'] ?? 0);

            // Calculate remaining work value: Contract * (1 - kemajuan_sebenar/100)
            $baki = (float) ($item['baki_nilai_kerja'] ?? ($harga * max(0, (100 - $sebenar) / 100)));

            $totalNilaiKontrak += $harga;
            $totalBakiKerja += $baki;
            if ($harga > $projekTerbesar) {
                $projekTerbesar = $harga;
            }

            $items[] = [
                'bil'              => count($items) + 1,
                'nama'             => $nama,
                'majikan'          => trim((string) ($item['majikan'] ?? $item['agensi'] ?? $item['kementerian'] ?? '-')),
                'no_kontrak'       => trim((string) ($item['no_kontrak'] ?? '-')),
                'harga'            => $harga,
                'harga_disp'       => 'RM ' . number_format($harga, 2),
                'kemajuan_sebenar' => $sebenar,
                'kemajuan_jadual'  => $jadual,
                'baki_nilai_kerja' => $baki,
                'baki_disp'        => 'RM ' . number_format($baki, 2),
                'tarikh_mula'      => $item['tarikh_tapak'] ?? $item['tarikh_mula'] ?? '-',
                'tarikh_siap'      => $item['tarikh_siap'] ?? '-',
            ];
        }

        return [
            'items'                 => $items,
            'total_nilai_kontrak'   => $totalNilaiKontrak,
            'total_baki_kerja'      => $totalBakiKerja,
            'total_baki_kerja_disp' => 'RM ' . number_format($totalBakiKerja, 2),
            'projek_terbesar'       => $projekTerbesar,
            'projek_terbesar_disp'  => 'RM ' . number_format($projekTerbesar, 2),
            'bil_projek'            => count($items),
        ];
    }

    /**
     * Summarize past project experience (Pengalaman Kerja).
     *
     * @param  array<int, array<string, mixed>>  $rawItems
     * @return array<string, mixed>
     */
    public function summarizePengalamanKerja(array $rawItems): array
    {
        $items = [];
        $totalNilai = 0.0;
        $projekTerbesar = 0.0;

        foreach ($rawItems as $item) {
            $tajuk = trim((string) ($item['tajuk'] ?? $item['nama_projek'] ?? $item['senarai_kerja'] ?? ''));
            if ($tajuk === '' || $tajuk === '-') {
                continue;
            }

            $nilai = (float) ($item['nilai_kerja'] ?? $item['nilai_kontrak'] ?? $item['nilai'] ?? 0);
            $totalNilai += $nilai;
            if ($nilai > $projekTerbesar) {
                $projekTerbesar = $nilai;
            }

            $items[] = [
                'bil'             => count($items) + 1,
                'tajuk'           => $tajuk,
                'pelanggan'       => trim((string) ($item['pelanggan'] ?? $item['majikan'] ?? '-')),
                'pic'             => trim((string) ($item['pic'] ?? $item['nama_pic'] ?? '-')),
                'telefon_pic'     => trim((string) ($item['telefon_pic'] ?? $item['no_telefon_pic'] ?? '-')),
                'nilai'           => $nilai,
                'nilai_disp'      => 'RM ' . number_format($nilai, 2),
                'tahun_siap'      => $item['tahun_siap'] ?? $item['tarikh_siap'] ?? '-',
                'tempoh'          => $item['tempoh'] ?? '-',
                'cpc_url'         => $item['cpc_url'] ?? $item['dokumen_url'] ?? $item['url'] ?? null,
                'cpc_name'        => $item['cpc_name'] ?? $item['dokumen_name'] ?? $item['name'] ?? null,
            ];
        }

        return [
            'items'                => $items,
            'total_nilai'          => $totalNilai,
            'total_nilai_disp'     => 'RM ' . number_format($totalNilai, 2),
            'projek_terbesar'      => $projekTerbesar,
            'projek_terbesar_disp' => 'RM ' . number_format($projekTerbesar, 2),
            'bil_projek'           => count($items),
        ];
    }

    /**
     * Summarize technical staff members (Senarai Kakitangan Teknikal).
     *
     * @param  array<int, array<string, mixed>>  $rawItems
     * @return array<string, mixed>
     */
    public function summarizeKakitanganTeknikal(array $rawItems): array
    {
        $items = [];
        foreach ($rawItems as $item) {
            $nama = trim((string) ($item['nama_pegawai'] ?? $item['nama'] ?? ''));
            if ($nama === '' || $nama === '-') {
                continue;
            }

            $items[] = [
                'bil'                => count($items) + 1,
                'nama_pegawai'       => $nama,
                'kategori'           => trim((string) ($item['kategori'] ?? 'A')),
                'jawatan'            => trim((string) ($item['jawatan'] ?? $item['sijil_professional'] ?? $item['kelayakan'] ?? '-')),
                'tahap_pendidikan'   => trim((string) ($item['tahap_pendidikan'] ?? '-')),
                'sijil_professional' => trim((string) ($item['sijil_professional'] ?? '-')),
                'jumlah_pengalaman'  => (int) ($item['jumlah_pengalaman'] ?? $item['pengalaman_tahun'] ?? 0),
                'dokumens'           => $item['dokumens'] ?? [],
            ];
        }

        return [
            'items'          => $items,
            'bil_kakitangan' => count($items),
        ];
    }
}
