<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\TenderKewanganKerjaEvaluation;
use App\Models\TenderKewanganLaporan;
use App\Models\TenderKewanganProgress;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianKewanganKerjaController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    /**
     * Display Penilaian Kewangan (Kerja) Dashboard Overview.
     */
    public function show(string $tender_no)
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            abort(404, 'Tender tidak ditemui.');
        }

        $no_tender_display = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;
        $tajuk_display = $tender->name ?? '-';
        $ptj_display = $tender->tenderer->name ?? '-';
        $tempoh_sah_laku = 90;

        $submissionDate = $tender->submission_datetime ? \Carbon\Carbon::parse($tender->submission_datetime) : null;
        $sah_laku_tamat = $submissionDate ? $submissionDate->copy()->addDays($tempoh_sah_laku)->format('d/m/Y') : '-';

        $status_label = 'Menunggu Penilaian Kewangan';
        if (isset($tender->status_process_id)) {
            if ($tender->status_process_id == TenderProcessStatus::penilaianKewanganListStatus()) {
                $status_label = 'Menunggu Penilaian Kewangan';
            } else {
                $status_label = TenderProcessStatus::label($tender->status_process_id);
            }
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $borangAccess = $this->computeBorangAccessList($progress);
        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());

        return view('newModule.penilaian_kewangan.penilaian.show', compact(
            'tender_no',
            'tender',
            'no_tender_display',
            'tajuk_display',
            'ptj_display',
            'tempoh_sah_laku',
            'sah_laku_tamat',
            'status_label',
            'progress',
            'borangAccess',
            'readOnly'
        ));
    }

    /**
     * Render specific Borang (Borang 1 to 15) page with dynamic data.
     */
    public function getBorang(string $tender_no, string $borang_code)
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            abort(404, 'Tender tidak ditemui.');
        }

        $validBorangs = [
            'borang1', 'borang2', 'borang3', 'lembaran', 'akaun_bank', 'bon_saham',
            'borang4', 'borang5', 'borang6', 'borang7', 'serupa', 'sebanding',
            'borang8', 'borang9', 'kerjaSerupa', 'kerjaSebanding', 'borang10',
            'borang11', 'borang12', 'borang13', 'borang14', 'borang15',
        ];

        if (! in_array($borang_code, $validBorangs, true)) {
            abort(404, 'Borang tidak sah.');
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $borangAccess = $this->computeBorangAccessList($progress);

        $aliasMap = [
            'lembaran'       => 'borang3',
            'akaun_bank'     => 'borang3',
            'bon_saham'      => 'borang3',
            'kerjaSerupa'    => 'borang9',
            'kerjaSebanding' => 'borang9',
        ];

        $targetBorangCode = $aliasMap[$borang_code] ?? $borang_code;

        if (isset($borangAccess[$targetBorangCode]) && ! $borangAccess[$targetBorangCode]['is_unlocked']) {
            $prevTitle = $borangAccess[$targetBorangCode]['prev_title'] ?? 'Borang terdahulu';
            $currentTitle = $borangAccess[$targetBorangCode]['title'] ?? strtoupper($borang_code);

            return redirect()
                ->route('penilaianKewanganKerja.show', $tender_no)
                ->with('error_locked', "{$currentTitle} belum boleh diakses. Sila selesaikan {$prevTitle} terlebih dahulu.");
        }

        $viewName = 'newModule.penilaian_kewangan.penilaian.' . $borang_code;

        if (! view()->exists($viewName)) {
            abort(404, "Pandangan {$borang_code} tidak ditemui.");
        }

        $kriteriaList = [
            1 => [
                'id' => 1,
                'code' => 'K1',
                'name' => 'Borang Tender Ditandatangani',
                'description' => 'Borang Tender telah ditandatangani oleh pemutus/wakil sah syarikat.'
            ],
            2 => [
                'id' => 2,
                'code' => 'K2',
                'name' => 'Penandatangan Diberi kuasa?',
                'description' => 'Surat Kuasa (Power of Attorney) atau dokumen rasmi yang mengesahkan penandatangan.'
            ],
            3 => [
                'id' => 3,
                'code' => 'K3',
                'name' => 'Harga Tender / Tempoh Tercatat di Borang Tender',
                'description' => 'Harga tawaran dan tempoh siap projek dinyatakan dengan jelas dan sempurna.'
            ],
            4 => [
                'id' => 4,
                'code' => 'K4',
                'name' => 'Pendaftaran Masih Sah Semasa Tutup Tender',
                'description' => 'Pendaftaran CIDB / PKK / SSM masih sah berkuat kuasa pada tarikh tutup tender.'
            ],
            5 => [
                'id' => 5,
                'code' => 'K5',
                'name' => 'Mengembalikan Kesemua Dokumen Asas Tender',
                'description' => 'Petender mengembalikan kesemua dokumen tender yang ditetapkan.'
            ],
            6 => [
                'id' => 6,
                'code' => 'K6',
                'name' => 'Tempoh Tidak Melebihi Tempoh Siap Maksimum',
                'description' => 'Tempoh siap yang ditawarkan petender tidak melebihi tempoh siap maksimum.'
            ],
            7 => [
                'id' => 7,
                'code' => 'K7',
                'name' => 'Surat Akuan Pembida Ditandatangani (Integrity Pact)',
                'description' => 'Surat Akuan Pembida (Integrity Pact) ditandatangani dengan sempurna.'
            ],
        ];

        $participants = \App\TenderVendor::query()
            ->with(['vendor'])
            ->where('tender_id', $tender->id)
            ->get();

        if ($participants->isEmpty()) {
            $existingVendors = \App\Vendor::query()->take(2)->get();
            if ($existingVendors->count() > 0) {
                foreach ($existingVendors as $idx => $v) {
                    \App\TenderVendor::query()->firstOrCreate([
                        'tender_id' => $tender->id,
                        'vendor_id' => $v->id,
                    ], [
                        'ref_number'   => 'TV-' . $tender->id . '-' . ($idx + 1),
                        'kod_pembekal' => 'V' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                        'participate'  => 1,
                    ]);
                }
                $participants = \App\TenderVendor::query()
                    ->with(['vendor'])
                    ->where('tender_id', $tender->id)
                    ->get();
            }
        }

        $evaluations = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', $borang_code)
            ->get()
            ->keyBy('vendor_id');

        $vendorSummary = [];
        $kriteriaStatusMap = [];

        foreach ($kriteriaList as $kId => $kDef) {
            $kriteriaStatusMap[$kId] = [
                'evaluated_count' => 0,
                'total_count'     => count($participants),
                'is_complete'     => false,
            ];
        }

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRecord = $evaluations->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            $kData = $payload['kriteria'] ?? [];
            $failedReasons = [];
            $allEvaluated = true;
            $isOverallSempurna = true;

            foreach ($kriteriaList as $kId => $kDef) {
                $item = $kData[(string) $kId] ?? $kData[$kId] ?? null;
                if ($item && isset($item['status']) && $item['status'] !== '') {
                    $kriteriaStatusMap[$kId]['evaluated_count']++;
                    if ($item['status'] === 'Tidak Sempurna' || $item['status'] === 'Tidak') {
                        $isOverallSempurna = false;
                        $catatanTxt = ! empty($item['catatan']) ? ': ' . $item['catatan'] : '';
                        $failedReasons[] = 'Kriteria ' . $kId . ' (' . $kDef['name'] . ')' . $catatanTxt;
                    }
                } else {
                    $allEvaluated = false;
                    $isOverallSempurna = false;
                }
            }

            $finalStatus = 'Belum Selesai';
            if ($allEvaluated) {
                $finalStatus = $isOverallSempurna ? 'Sempurna' : 'Tidak Sempurna';
            }

            $vendorSummary[$vId] = [
                'vendor_id'      => $vId,
                'kod_pembekal'   => $p->kod_pembekal ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT)),
                'vendor_name'    => $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId),
                'final_status'   => $finalStatus,
                'is_sempurna'    => ($finalStatus === 'Sempurna'),
                'failed_reasons' => $failedReasons,
                'catatan'        => $evalRecord ? $evalRecord->catatan : null,
                'kriteria_data'  => $kData,
            ];
        }

        foreach ($kriteriaStatusMap as $kId => &$kStat) {
            $kStat['is_complete'] = ($kStat['total_count'] > 0 && $kStat['evaluated_count'] >= $kStat['total_count']);
        }
        unset($kStat);

        $no_tender_display = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;
        $tajuk_display = $tender->name ?? '-';
        $ptj_display = $tender->tenderer->name ?? '-';
        $tempoh_sah_laku = 90;

        $submissionDate = $tender->submission_datetime ? \Carbon\Carbon::parse($tender->submission_datetime) : null;
        $sah_laku_tamat = $submissionDate ? $submissionDate->copy()->addDays($tempoh_sah_laku)->format('d/m/Y') : '-';

        $status_label = 'Menunggu Penilaian Kewangan';
        if (isset($tender->status_process_id)) {
            if ($tender->status_process_id == TenderProcessStatus::penilaianKewanganListStatus()) {
                $status_label = 'Menunggu Penilaian Kewangan';
            } else {
                $status_label = TenderProcessStatus::label($tender->status_process_id);
            }
        }

        $b2DocsList = [
            'imbangan' => [
                'id'          => 'imbangan',
                'name'        => 'Lembaran Imbangan',
                'icon'        => 'bi-journal-bookmark',
                'showDiaudit' => true,
            ],
            'penyata_bank' => [
                'id'          => 'penyata_bank',
                'name'        => 'Penyata Bulanan / Akaun Bank',
                'icon'        => 'bi-bank',
                'showDiaudit' => false,
            ],
            'bon_saham' => [
                'id'          => 'bon_saham',
                'name'        => 'Bon atau Saham',
                'icon'        => 'bi-cash-stack',
                'showDiaudit' => false,
            ],
            'prestasi' => [
                'id'          => 'prestasi',
                'name'        => 'Prestasi Kerja Semasa Petender',
                'icon'        => 'bi-briefcase',
                'showDiaudit' => false,
            ],
            'laporan_ca' => [
                'id'          => 'laporan_ca',
                'name'        => 'Laporan Bank atau Borang CA',
                'icon'        => 'bi-file-earmark-text',
                'showDiaudit' => false,
            ],
            'laporan_penyelia' => [
                'id'          => 'laporan_penyelia',
                'name'        => 'Laporan Penyelia Projek Bagi Kerja Semasa (Borang GA)',
                'icon'        => 'bi-person-badge',
                'showDiaudit' => false,
            ],
        ];

        $b2Evaluations = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'borang2')
            ->get()
            ->keyBy('vendor_id');

        $b2VendorSummary = [];
        $b2DocStatusMap = [];

        foreach ($b2DocsList as $dId => $dDef) {
            $b2DocStatusMap[$dId] = [
                'evaluated_count' => 0,
                'total_count'     => count($participants),
                'is_complete'     => false,
            ];
        }

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRecord = $b2Evaluations->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            $docsData = $payload['docs'] ?? [];
            $failedReasons = [];
            $allEvaluated = true;
            $isOverallCukup = true;
            $tanpaBorangGA = false;

            foreach ($b2DocsList as $dId => $dDef) {
                $item = $docsData[$dId] ?? null;
                if ($item && isset($item['dikemukakan']) && $item['dikemukakan'] !== '') {
                    $b2DocStatusMap[$dId]['evaluated_count']++;

                    $dikemukakan = $item['dikemukakan'] ?? 'Tidak';
                    $diaudit = $item['diaudit'] ?? 'T.K.S';
                    $catatan = $item['catatan'] ?? '';

                    if ($dikemukakan === 'Tidak') {
                        if ($dId === 'laporan_penyelia') {
                            $tanpaBorangGA = true;
                        } else {
                            $isOverallCukup = false;
                            $catTxt = ! empty($catatan) ? ': ' . $catatan : '';
                            $failedReasons[] = $dDef['name'] . ': Dikemukakan = Tidak' . $catTxt;
                        }
                    }

                    if ($dDef['showDiaudit'] && $diaudit === 'Tidak') {
                        $isOverallCukup = false;
                        $catTxt = ! empty($catatan) ? ': ' . $catatan : '';
                        $failedReasons[] = $dDef['name'] . ': Diaudit = Tidak' . $catTxt;
                    }
                } else {
                    $allEvaluated = false;
                    $isOverallCukup = false;
                }
            }

            $finalStatus = 'Belum Selesai';
            if ($allEvaluated) {
                if ($isOverallCukup) {
                    $finalStatus = $tanpaBorangGA ? '*Cukup' : 'Cukup';
                } else {
                    $finalStatus = 'Tidak Cukup';
                }
            }

            $b2VendorSummary[$vId] = [
                'vendor_id'      => $vId,
                'kod_pembekal'   => $p->kod_pembekal ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT)),
                'vendor_name'    => $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId),
                'final_status'   => $finalStatus,
                'is_cukup'       => str_contains($finalStatus, 'Cukup'),
                'failed_reasons' => $failedReasons,
                'catatan'        => $evalRecord ? $evalRecord->catatan : null,
                'docs_data'      => $docsData,
            ];
        }

        foreach ($b2DocStatusMap as $dId => &$dStat) {
            $dStat['is_complete'] = ($dStat['total_count'] > 0 && $dStat['evaluated_count'] >= $dStat['total_count']);
        }
        unset($dStat);

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());

        // Prepare Borang 3 dynamic vendor data (Lembaran Imbangan, Penyata Bank, Bon & Saham)
        $b3LembaranDb = \App\Models\LembaranImbangan::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy('vendor_id');

        $b3LembaranPayloads = \App\Models\TenderVendorFormPayload::query()
            ->where('tender_id', $tender->id)
            ->where('form_key', 'lembaran_imbangan')
            ->get()
            ->keyBy('vendor_id');

        $b3PenyataBankDb = \App\Models\PenyataBank::query()
            ->with(['bulans'])
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy('vendor_id');

        $b3PenyataBankPayloads = \App\Models\TenderVendorFormPayload::query()
            ->where('tender_id', $tender->id)
            ->where('form_key', 'penyata_bank')
            ->get()
            ->keyBy('vendor_id');

        $b3BonSahamDb = \App\Models\BonSaham::query()
            ->with('accounts')
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy('vendor_id');

        $b3BonSahamPayloads = \App\Models\TenderVendorFormPayload::query()
            ->where('tender_id', $tender->id)
            ->where('form_key', 'bon_saham')
            ->get()
            ->keyBy('vendor_id');

        $subDate = $tender->submission_datetime ? \Carbon\Carbon::parse($tender->submission_datetime) : now();
        $b3Month3 = $subDate->copy()->subMonth();
        $b3Month2 = $subDate->copy()->subMonths(2);
        $b3Month1 = $subDate->copy()->subMonths(3);
        $b3MonthsLabels = [
            $b3Month1->format('M Y'),
            $b3Month2->format('M Y'),
            $b3Month3->format('M Y'),
        ];

        $b3VendorData = [];

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $kodPembekal = $p->kod_pembekal ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT));
            $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId);

            // 1. Lembaran Imbangan Data
            $lembaranObj = $b3LembaranDb->get($vId);
            $lembaranPayload = $b3LembaranPayloads->get($vId)?->payload ?? [];

            $asetTetap = (float) ($lembaranObj->aset_tetap ?? $lembaranPayload['aset_tetap'] ?? 0);
            $asetSemasa = (float) ($lembaranObj->aset_semasa ?? $lembaranPayload['aset_semasa'] ?? 0);
            $liabilitiSemasa = (float) ($lembaranObj->liabiliti_semasa ?? $lembaranPayload['liabiliti_semasa'] ?? 0);
            $liabilitiTetap = (float) ($lembaranObj->liabiliti_tetap ?? $lembaranPayload['liabiliti_tetap'] ?? 0);
            $wangTunai = (float) ($lembaranObj->wang_tunai ?? $lembaranPayload['wang_tunai'] ?? 0);
            $bakiKredit = (float) ($lembaranObj->baki_kemudahan_kredit ?? $lembaranPayload['baki_kemudahan_kredit'] ?? 0);

            // 2. Penyata Bank Data
            $pbObj = $b3PenyataBankDb->get($vId);
            $pbPayload = $b3PenyataBankPayloads->get($vId)?->payload ?? [];

            $pbAccounts = [];
            if ($pbObj && !empty($pbObj->accounts) && is_array($pbObj->accounts)) {
                $pbAccounts = $pbObj->accounts;
            } elseif (!empty($pbPayload['accounts']) && is_array($pbPayload['accounts'])) {
                $pbAccounts = $pbPayload['accounts'];
            } elseif ($pbObj && $pbObj->bulans->count() > 0) {
                $pbAccounts = [[
                    'bank_name' => 'BANK 1',
                    'bulans' => $pbObj->bulans->map(fn($b) => [
                        'bulan' => $b->bulan,
                        'tahun' => $b->tahun,
                        'jumlah' => (float)$b->jumlah
                    ])->all()
                ]];
            }

            $formattedPbAccounts = [];
            $pbGrandTotal = 0.00;

            foreach ($pbAccounts as $idx => $acc) {
                $bankName = $acc['bank_name'] ?? $acc['bank_institusi'] ?? $acc['bank'] ?? ('BANK ' . ($idx + 1));
                $bulans = $acc['bulans'] ?? [];
                
                $mAmounts = [0.00, 0.00, 0.00];
                if (is_array($bulans)) {
                    foreach ($bulans as $bIdx => $bVal) {
                        if (is_array($bVal)) {
                            $mAmounts[$bIdx % 3] = (float) ($bVal['jumlah'] ?? $bVal['amount'] ?? 0);
                        } else {
                            $mAmounts[$bIdx % 3] = (float) $bVal;
                        }
                    }
                }
                $accTotal = array_sum($mAmounts);
                $pbGrandTotal += $accTotal;

                $formattedPbAccounts[] = [
                    'account_no' => $acc['account_no'] ?? ($idx + 1),
                    'bank_name' => strtoupper($bankName),
                    'monthly_amounts' => $mAmounts,
                    'total' => $accTotal,
                ];
            }

            // 3. Bon & Saham Data
            $bsObj = $b3BonSahamDb->get($vId);
            $bsPayload = $b3BonSahamPayloads->get($vId)?->payload ?? [];

            $bsAccounts = [];
            if ($bsObj && $bsObj->accounts->count() > 0) {
                foreach ($bsObj->accounts as $bsAcc) {
                    $bsAccounts[] = [
                        'bank_institusi' => strtoupper($bsAcc->bank_institusi ?? '-'),
                        'jumlah_deposit' => (float) $bsAcc->jumlah_deposit,
                    ];
                }
            } elseif (!empty($bsPayload['accounts']) && is_array($bsPayload['accounts'])) {
                foreach ($bsPayload['accounts'] as $bsAcc) {
                    $bsAccounts[] = [
                        'bank_institusi' => strtoupper($bsAcc['bank_institusi'] ?? '-'),
                        'jumlah_deposit' => (float) ($bsAcc['jumlah_deposit'] ?? 0),
                    ];
                }
            }

            $bsGrandTotal = (float) ($bsObj->jumlah_keseluruhan ?? array_sum(array_column($bsAccounts, 'jumlah_deposit')));

            // Tab 1 (Ringkasan Modal) Derived & Calculated Fields
            $modalPusinganD = $asetSemasa - $liabilitiSemasa;
            $baki3BulanE = $pbGrandTotal;
            $purata3BulanF = $baki3BulanE / 3;
            $wangDalamTanganG = max(0, $wangTunai);
            $bonSahamH = $bsGrandTotal;
            $asetCairI = $wangDalamTanganG + $bonSahamH;
            $borangCA1KreditJ = $bakiKredit;

            // Total Available Capital (k) = (f) + (g) + (h) + (i) + (j)
            $jumlahModalK = $purata3BulanF + $wangDalamTanganG + $bonSahamH + $asetCairI + $borangCA1KreditJ;

            // Minimum Capital Required = 3% of Builder's Work Value / Harga Indikatif Tender
            $hargaIndikatif = (float) ($tender->harga_indikatif ?? 0);
            $modalMinimum = $hargaIndikatif * 0.03;

            // Net Usable Liquid Capital (m) = Jumlah Modal (k) - Modal Minimum
            $mudahCairBolehGunaM = $jumlahModalK - $modalMinimum;

            $b3VendorData[$vId] = [
                'vendor_id'       => $vId,
                'kod_pembekal'    => $kodPembekal,
                'vendor_name'     => $vendorName,
                // Tab 1 Calculated Fields
                'modal_pusingan'   => $modalPusinganD,
                'baki_3_bulan'     => $baki3BulanE,
                'purata_3_bulan'   => $purata3BulanF,
                'wang_tangan_g'    => $wangDalamTanganG,
                'bon_saham_h'      => $bonSahamH,
                'aset_cair_i'      => $asetCairI,
                'ca1_kredit_j'     => $borangCA1KreditJ,
                'jumlah_modal_k'   => $jumlahModalK,
                'modal_minimum'    => $modalMinimum,
                'mudah_cair_m'     => $mudahCairBolehGunaM,
                // Lembaran Imbangan
                'aset_tetap'      => $asetTetap,
                'aset_semasa'     => $asetSemasa,
                'liabiliti_semasa'=> $liabilitiSemasa,
                'liabiliti_tetap' => $liabilitiTetap,
                'wang_tunai'      => $wangTunai,
                'baki_kredit'     => $bakiKredit,
                'pinjaman_lulus'  => null, // Unavailable source -> red 0.00
                // Penyata Bank
                'pb_accounts'     => $formattedPbAccounts,
                'pb_grand_total'  => $pbGrandTotal,
                // Bon & Saham
                'bs_accounts'     => $bsAccounts,
                'bs_grand_total'  => $bsGrandTotal,
            ];
        }

        // Prepare Borang 4 dynamic vendor data (Prestasi Kerja Semasa & Evaluations)
        $b4PrestasiDb = \App\Models\TenderPrestasiKerja::query()
            ->with(['items'])
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy('vendor_id');

        $b4PrestasiPayloads = \App\Models\TenderVendorFormPayload::query()
            ->where('tender_id', $tender->id)
            ->whereIn('form_key', ['prestasi_kerja', 'prestasi_kerja_semasa'])
            ->get()
            ->keyBy('vendor_id');

        $b4EvaluationsDb = \App\Models\TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'borang4')
            ->get()
            ->keyBy('vendor_id');

        $b4VendorData = [];

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $kodPembekal = $p->kod_pembekal ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT));
            $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId);

            $pObj = $b4PrestasiDb->get($vId);
            $pPayload = $b4PrestasiPayloads->get($vId)?->payload ?? [];

            $formattedItems = [];

            if ($pObj && $pObj->items->count() > 0) {
                foreach ($pObj->items as $item) {
                    $formattedItems[] = [
                        'nama'             => $item->nama ?: '-',
                        'no_kontrak'       => $item->no_kontrak ?: '-',
                        'harga'            => (float) $item->harga,
                        'tarikh_tapak'     => $item->tarikh_tapak ?: '-',
                        'tempoh'           => (int) $item->tempoh,
                        'tarikh_siap'      => $item->tarikh_siap ?: '-',
                        'tarikh_penilaian' => $item->tarikh_penilaian ?: '-',
                        'luputan'          => (int) $item->luputan,
                        'kemajuan_sebenar' => (float) $item->kemajuan_sebenar,
                        'kemajuan_jadual'  => (float) $item->kemajuan_jadual,
                    ];
                }
            } elseif (!empty($pPayload['items']) && is_array($pPayload['items'])) {
                foreach ($pPayload['items'] as $item) {
                    $formattedItems[] = [
                        'nama'             => $item['nama'] ?? '-',
                        'no_kontrak'       => $item['no_kontrak'] ?? '-',
                        'harga'            => (float) ($item['harga'] ?? 0),
                        'tarikh_tapak'     => $item['tarikh_tapak'] ?? '-',
                        'tempoh'           => (int) ($item['tempoh'] ?? 0),
                        'tarikh_siap'      => $item['tarikh_siap'] ?? '-',
                        'tarikh_penilaian' => $item['tarikh_penilaian'] ?? '-',
                        'luputan'          => (int) ($item['luputan'] ?? 0),
                        'kemajuan_sebenar' => (float) ($item['kemajuan_sebenar'] ?? 0),
                        'kemajuan_jadual'  => (float) ($item['kemajuan_jadual'] ?? 0),
                    ];
                }
            }

            // Evaluation status
            $evalObj = $b4EvaluationsDb->get($vId);
            $evalPayload = $evalObj?->payload ?? [];
            if (is_string($evalPayload)) {
                $evalPayload = json_decode($evalPayload, true) ?: [];
            }

            $isEvaluated = ($evalObj !== null) || !empty($evalPayload);
            $projekSakit = $evalPayload['projek_sakit'] ?? 'TIADA';
            $statusPematuhan = $evalObj?->status_pematuhan ?? ($projekSakit === 'ADA' ? 0 : 1);
            $statusPrestasiLabel = $statusPematuhan === 1 ? 'MEMUASKAN' : 'TIDAK MEMUASKAN';

            $b4VendorData[$vId] = [
                'vendor_id'             => $vId,
                'kod_pembekal'          => $kodPembekal,
                'vendor_name'           => $vendorName,
                'items'                 => $formattedItems,
                'is_evaluated'          => $isEvaluated,
                'projek_sakit'          => $projekSakit,
                'status_pematuhan'      => $statusPematuhan,
                'status_prestasi_label' => $statusPrestasiLabel,
                'catatan'               => $evalObj?->catatan ?? '',
            ];
        }

        // Prepare Borang 5 dynamic vendor data & evaluation summary (Peringkat Pertama)
        $b5CriteriaList = [
            'borang1' => [
                'code'  => 'borang1',
                'label' => 'Kesempurnaan Tender (Borang 1)',
                'icon'  => 'bi-file-earmark-check',
            ],
            'borang2' => [
                'code'  => 'borang2',
                'label' => 'Kecukupan Dokumen (Borang 2)',
                'icon'  => 'bi-folder-check',
            ],
            'borang3' => [
                'code'  => 'borang3',
                'label' => 'Kecukupan Modal (Borang 3)',
                'icon'  => 'bi-calculator',
            ],
            'borang4' => [
                'code'  => 'borang4',
                'label' => 'Prestasi Kerja Semasa (Borang 4)',
                'icon'  => 'bi-speedometer2',
            ],
        ];

        $b5Evals = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'borang5')
            ->get()
            ->keyBy('vendor_id');

        $b5VendorSummary = [];
        $b5CriteriaStatusMap = [];
        $b5BilanganBerjaya = 0;

        foreach ($b5CriteriaList as $cCode => $cDef) {
            $b5CriteriaStatusMap[$cCode] = [
                'evaluated_count' => 0,
                'total_count'     => count($participants),
                'is_complete'     => false,
            ];
        }

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $kodPembekal = $p->kod_pembekal ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT));
            $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId);

            $evalRecord = $b5Evals->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            $kData = $payload['kriteria'] ?? [];

            // Compute suggested default criteria values from Borang 1 to 4 for initial modal display
            $b1Rec = $evaluations->get($vId);
            $b1Suggested = ($b1Rec && (int) $b1Rec->status_pematuhan === 1) ? 'Sempurna' : (($b1Rec && (int) $b1Rec->status_pematuhan === 0) ? 'Tidak Sempurna' : 'Sempurna');

            $b2Rec = $b2Evaluations->get($vId);
            $b2Suggested = ($b2Rec && (int) $b2Rec->status_pematuhan === 1) ? 'Sempurna' : (($b2Rec && (int) $b2Rec->status_pematuhan === 0) ? 'Tidak Sempurna' : 'Sempurna');

            $b3Data = $b3VendorData[$vId] ?? null;
            $b3Suggested = ($b3Data && ($b3Data['mudah_cair_m'] ?? 0) >= 0) ? 'Sempurna' : 'Tidak Sempurna';

            $b4Rec = $b4EvaluationsDb->get($vId);
            $b4Suggested = ($b4Rec && (int) $b4Rec->status_pematuhan === 1) ? 'Sempurna' : (($b4Rec && (int) $b4Rec->status_pematuhan === 0) ? 'Tidak Sempurna' : 'Sempurna');

            $suggestedKData = [
                'borang1' => ['status' => $b1Suggested, 'catatan' => $b1Rec->catatan ?? ''],
                'borang2' => ['status' => $b2Suggested, 'catatan' => $b2Rec->catatan ?? ''],
                'borang3' => ['status' => $b3Suggested, 'catatan' => ($b3Data && ($b3Data['mudah_cair_m'] ?? 0) < 0) ? 'Modal pusingan tidak mencukupi' : ''],
                'borang4' => ['status' => $b4Suggested, 'catatan' => $b4Rec->catatan ?? ''],
            ];

            $failedReasons = [];
            $allEvaluated = true;
            $isOverallSempurna = true;

            foreach ($b5CriteriaList as $cCode => $cDef) {
                $item = $kData[$cCode] ?? null;
                if ($item && isset($item['status']) && $item['status'] !== '') {
                    $b5CriteriaStatusMap[$cCode]['evaluated_count']++;
                    if ($item['status'] === 'Tidak Sempurna' || $item['status'] === 'Tidak') {
                        $isOverallSempurna = false;
                        $catTxt = ! empty($item['catatan']) ? ': ' . $item['catatan'] : '';
                        $failedReasons[] = $cDef['label'] . ' - Tidak Sempurna' . $catTxt;
                    }
                } else {
                    $allEvaluated = false;
                    $isOverallSempurna = false;
                }
            }

            $finalStatus = 'Belum Selesai';
            if ($allEvaluated) {
                $finalStatus = $isOverallSempurna ? 'Sempurna' : 'Tidak Sempurna';
            }

            if ($finalStatus === 'Sempurna') {
                $b5BilanganBerjaya++;
            }

            $b5VendorSummary[$vId] = [
                'vendor_id'          => $vId,
                'kod_pembekal'       => $kodPembekal,
                'vendor_name'        => $vendorName,
                'final_status'       => $finalStatus,
                'is_sempurna'        => ($finalStatus === 'Sempurna'),
                'failed_reasons'     => $failedReasons,
                'catatan'            => $evalRecord ? $evalRecord->catatan : (! empty($failedReasons) ? implode('; ', $failedReasons) : ($allEvaluated ? 'Memenuhi syarat kelayakan penilaian Peringkat Pertama.' : '')),
                'kriteria_data'      => $kData,
                'suggested_kriteria' => $suggestedKData,
            ];
        }

        foreach ($b5CriteriaStatusMap as $cCode => &$cStat) {
            $cStat['is_complete'] = ($cStat['total_count'] > 0 && $cStat['evaluated_count'] >= $cStat['total_count']);
        }
        unset($cStat);

        // Prepare Borang 6 dynamic vendor data (Petender Yang Lulus Penilaian Peringkat Pertama - Borang 5 Sempurna)
        $b6PassingVendors = [];

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRecord = $b5Evals->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            $isSempurna = ($evalRecord && (int) $evalRecord->status_pematuhan === 1) || (($payload['keputusan_akhir'] ?? '') === 'Sempurna');

            if ($isSempurna) {
                $kodPembekal = $p->kod_pembekal ?: ($p->ref_number ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT)));
                $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId);
                $hargaTawaran = (float) ($p->harga_tawaran ?: ($p->price ?? 0));

                $b6PassingVendors[] = [
                    'vendor_id'     => $vId,
                    'kod_pembekal'  => $kodPembekal,
                    'vendor_name'   => $vendorName,
                    'harga_tawaran' => $hargaTawaran,
                    'harga_display' => number_format($hargaTawaran, 2),
                ];
            }
        }

        usort($b6PassingVendors, function ($a, $b) {
            return $a['harga_tawaran'] <=> $b['harga_tawaran'];
        });

        // Prepare Borang 7 data (Analisa Nilai Baki Kerja Dalam Tangan for passing vendors)
        $b7VendorSummary = [];

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRecord = $b5Evals->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            // Only vendors who passed Borang 5 / Peringkat Pertama
            $isSempurna = ($evalRecord && (int) $evalRecord->status_pematuhan === 1) || (($payload['keputusan_akhir'] ?? '') === 'Sempurna');

            if (! $isSempurna) {
                continue;
            }

            $kodPembekal = $p->kod_pembekal ?: ($p->ref_number ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT)));
            $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId);

            $pObj = $b4PrestasiDb->get($vId);
            $pPayload = $b4PrestasiPayloads->get($vId)?->payload ?? [];

            $rawItems = [];
            if ($pObj && $pObj->items->count() > 0) {
                foreach ($pObj->items as $item) {
                    $rawItems[] = [
                        'nama'             => $item->nama ?: '-',
                        'no_kontrak'       => $item->no_kontrak ?: '-',
                        'harga'            => (float) $item->harga,
                        'tarikh_tapak'     => $item->tarikh_tapak ?: '-',
                        'tempoh'           => (int) $item->tempoh,
                        'tarikh_siap'      => $item->tarikh_siap ?: '-',
                        'tarikh_penilaian' => $item->tarikh_penilaian ?: '-',
                        'luputan'          => (int) $item->luputan,
                        'kemajuan_sebenar' => $item->kemajuan_sebenar !== null ? (float) $item->kemajuan_sebenar : null,
                        'kemajuan_jadual'  => $item->kemajuan_jadual !== null ? (float) $item->kemajuan_jadual : null,
                    ];
                }
            } elseif (! empty($pPayload['items']) && is_array($pPayload['items'])) {
                foreach ($pPayload['items'] as $item) {
                    $rawItems[] = [
                        'nama'             => $item['nama'] ?? '-',
                        'no_kontrak'       => $item['no_kontrak'] ?? '-',
                        'harga'            => (float) ($item['harga'] ?? 0),
                        'tarikh_tapak'     => $item['tarikh_tapak'] ?? '-',
                        'tempoh'           => (int) ($item['tempoh'] ?? 0),
                        'tarikh_siap'      => $item['tarikh_siap'] ?? '-',
                        'tarikh_penilaian' => $item['tarikh_penilaian'] ?? '-',
                        'luputan'          => (int) ($item['luputan'] ?? 0),
                        'kemajuan_sebenar' => isset($item['kemajuan_sebenar']) ? (float) $item['kemajuan_sebenar'] : null,
                        'kemajuan_jadual'  => isset($item['kemajuan_jadual']) ? (float) $item['kemajuan_jadual'] : null,
                    ];
                }
            }

            $computedItems = [];
            $sumDisiapkan = 0.0;
            $sumNbk = 0.0;
            $sumNtbk = 0.0;

            foreach ($rawItems as $idx => $rItem) {
                $nilaiKontrak = (float) ($rItem['harga'] ?? 0);
                $peratusSiap = $rItem['kemajuan_sebenar'];
                $peratusBelumSiap = ($peratusSiap !== null) ? max(0.0, 100.0 - $peratusSiap) : null;

                // Calculate Nilai Kerja Disiapkan
                $nilaiDisiapkan = ($peratusSiap !== null) ? ($nilaiKontrak * ($peratusSiap / 100.0)) : 0.0;

                // Calculate NBK = Nilai Kontrak - Nilai Kerja Disiapkan
                $nbk = ($peratusSiap !== null) ? ($nilaiKontrak - $nilaiDisiapkan) : 0.0;

                // Calculate Baki Tempoh Penyiapan (Bulan) from tarikh_siap if available
                $bakiBulan = null;
                $tarikhSiapStr = $rItem['tarikh_siap'] ?? '';
                if (! empty($tarikhSiapStr) && $tarikhSiapStr !== '-') {
                    try {
                        $siapDate = \Carbon\Carbon::parse($tarikhSiapStr);
                        $months = now()->diffInMonths($siapDate, false);
                        $bakiBulan = ($months > 0) ? (int) ceil($months) : 0;
                    } catch (\Exception $e) {
                        $bakiBulan = (int) ($rItem['tempoh'] ?? 0);
                    }
                }

                // Calculate NTBK = NBK / (Baki Tempoh / 12)
                $ntbk = 0.0;
                if ($nbk > 0) {
                    if ($bakiBulan !== null && $bakiBulan > 0) {
                        $ntbk = $nbk / ($bakiBulan / 12.0);
                    } else {
                        $ntbk = $nbk; // fallback to full NBK if duration 0 or passed
                    }
                }

                $sumDisiapkan += $nilaiDisiapkan;
                $sumNbk += $nbk;
                $sumNtbk += $ntbk;

                $computedItems[] = [
                    'bil'                     => $idx + 1,
                    'nama'                    => $rItem['nama'],
                    'nilai_kontrak'           => $nilaiKontrak,
                    'nilai_kontrak_display'   => number_format($nilaiKontrak, 2),
                    'wkp_wps_display'         => null, // Missing data rule: red 0.00
                    'kerja_pembina_display'   => null, // Missing data rule: red 0.00
                    'peratus_siap'            => $peratusSiap,
                    'peratus_siap_display'    => ($peratusSiap !== null) ? number_format($peratusSiap, 2) . '%' : null,
                    'peratus_belum_siap'      => $peratusBelumSiap,
                    'peratus_belum_display'   => ($peratusBelumSiap !== null) ? number_format($peratusBelumSiap, 2) . '%' : null,
                    'tarikh_siap'             => $tarikhSiapStr,
                    'baki_bulan'              => $bakiBulan,
                    'nilai_disiapkan'         => $nilaiDisiapkan,
                    'nilai_disiapkan_display' => ($peratusSiap !== null) ? number_format($nilaiDisiapkan, 2) : '0.00',
                    'ntbk'                    => $ntbk,
                    'ntbk_display'            => number_format($ntbk, 2),
                    'nbk'                     => $nbk,
                    'nbk_display'             => ($peratusSiap !== null) ? number_format($nbk, 2) : '0.00',
                ];
            }

            $b7VendorSummary[$vId] = [
                'vendor_id'             => $vId,
                'kod_pembekal'          => $kodPembekal,
                'vendor_name'           => $vendorName,
                'items'                 => $computedItems,
                'jumlah_disiapkan'      => $sumDisiapkan,
                'jumlah_disiapkan_disp' => number_format($sumDisiapkan, 2),
                'jumlah_ntbk'           => $sumNtbk,
                'jumlah_ntbk_disp'      => number_format($sumNtbk, 2),
                'jumlah_nbk'            => $sumNbk,
                'jumlah_nbk_disp'       => number_format($sumNbk, 2),
            ];
        }

        // Compute TSP (Tempoh Siap Penegang - Median duration of passing vendors)
        $allVendorTsList = [];
        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRecord = $b5Evals->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }
            $isSempurna = ($evalRecord && (int) $evalRecord->status_pematuhan === 1) || (($payload['keputusan_akhir'] ?? '') === 'Sempurna');
            if ($isSempurna) {
                $ts = (float) ($p->tempoh ?: ($p->tempoh_siap ?: ($p->duration ?? 0)));
                if ($ts > 0) {
                    $allVendorTsList[] = $ts;
                }
            }
        }

        $computedTsp = null;
        if (count($allVendorTsList) > 0) {
            sort($allVendorTsList, SORT_NUMERIC);
            $countTs = count($allVendorTsList);
            if ($countTs % 2 !== 0) {
                $computedTsp = (float) $allVendorTsList[floor($countTs / 2)];
            } else {
                $mid1 = $allVendorTsList[($countTs / 2) - 1];
                $mid2 = $allVendorTsList[$countTs / 2];
                $computedTsp = (float) (($mid1 + $mid2) / 2.0);
            }
        }

        // Prepare Borang 8 data (Analisa Kedudukan Kewangan for passing vendors)
        $b8VendorSummary = [];

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRecord = $b5Evals->get($vId);
            $payload = $evalRecord ? ($evalRecord->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            // Only vendors who passed Borang 5 / Peringkat Pertama
            $isSempurna = ($evalRecord && (int) $evalRecord->status_pematuhan === 1) || (($payload['keputusan_akhir'] ?? '') === 'Sempurna');

            if (! $isSempurna) {
                continue;
            }

            $kodPembekal = $p->kod_pembekal ?: ($p->ref_number ?: ('V' . str_pad($p->id, 3, '0', STR_PAD_LEFT)));
            $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId);

            $b3Info = $b3VendorData[$vId] ?? [];

            // Financial Inputs from Borang 3 / Lembaran Imbangan / Penyata Bank
            $asetSemasa = (float) ($b3Info['aset_semasa'] ?? 0);
            $liabilitiSemasa = (float) ($b3Info['liabiliti_semasa'] ?? 0);
            $asetTetap = (float) ($b3Info['aset_tetap'] ?? 0);
            $liabilitiTetap = (float) ($b3Info['liabiliti_tetap'] ?? 0);
            $wangTunai = (float) ($b3Info['wang_tunai'] ?? 0);
            $bakiKredit = (float) ($b3Info['ca1_kredit_j'] ?? ($b3Info['baki_kredit'] ?? 0));
            $wdtsVal = (float) ($b3Info['purata_3_bulan'] ?? (($b3Info['baki_3_bulan'] ?? 0) / 3));

            // Formula Calculations
            // 1. MP = (Aset Semasa - Liabiliti Semasa) + max(0, WDTS - WDT)
            $mpVal = ($asetSemasa - $liabilitiSemasa) + max(0.0, $wdtsVal - $wangTunai);
            // 2. JA = Aset Semasa + Aset Tetap
            $jaVal = $asetSemasa + $asetTetap;
            // 3. JL = Liabiliti Semasa + Liabiliti Tetap
            $jlVal = $liabilitiSemasa + $liabilitiTetap;
            // 4. NW = JA - JL
            $nwVal = $jaVal - $jlVal;
            // 5. KK
            $kkVal = $bakiKredit;
            // 6. WDTS = $wdtsVal
            // 7. Harga Tender (T)
            $hargaTawaran = (float) ($p->harga_tawaran ?: ($p->price ?? 0));
            // 8. Anggaran Jabatan (AJ)
            $ajVal = (float) ($tender->harga_indikatif ?? 0);
            // 9. WKP (Missing source -> null / red 0.00)
            // 10. WPS (Missing source -> null / red 0.00)
            // 11. TSP (Median duration across passing vendors)
            $tspVal = $computedTsp;
            // 12. TS (Vendor proposed duration)
            $tsTemp = (float) ($p->tempoh ?: ($p->tempoh_siap ?: ($p->duration ?? 0)));
            $tsVal = ($tsTemp > 0) ? $tsTemp : null;
            // 13. NTBK (Borang 7 - Missing WKP/WPS -> null / red 0.00)
            // 14. KB (Formula max)
            $kb1 = ((10.0 * $mpVal) + (5.0 * ($nwVal - $mpVal)));
            $kb2 = ((10.0 * $mpVal) + (9.0 * $kkVal));
            $kb3 = ((10.0 * $wdtsVal) + (9.0 * $kkVal));
            $kbVal = max($kb1, $kb2, $kb3);

            // 15. NTP = [ AJ - (WKP + WPS) ] / TSP => AJ / TSP when WKP/WPS = 0
            $ntpVal = ($tspVal !== null && $tspVal > 0) ? ($ajVal / $tspVal) : null;

            // 16. % KB vs NTP = (KB * 100) / NTP
            $peratusKbNtpVal = ($ntpVal !== null && $ntpVal > 0) ? (($kbVal * 100.0) / $ntpVal) : null;

            $b8VendorSummary[$vId] = [
                'vendor_id'           => $vId,
                'kod_pembekal'        => $kodPembekal,
                'vendor_name'         => $vendorName,
                'modal_pusingan'      => $mpVal,
                'modal_pusingan_disp' => number_format($mpVal, 2),
                'b8_items'            => [
                    'item1_mp'       => ['val' => $mpVal,           'disp' => number_format($mpVal, 2),                                          'is_null' => false],
                    'item2_ja'       => ['val' => $jaVal,           'disp' => number_format($jaVal, 2),                                          'is_null' => false],
                    'item3_jl'       => ['val' => $jlVal,           'disp' => number_format($jlVal, 2),                                          'is_null' => false],
                    'item4_nw'       => ['val' => $nwVal,           'disp' => number_format($nwVal, 2),                                          'is_null' => false],
                    'item5_kk'       => ['val' => $kkVal,           'disp' => number_format($kkVal, 2),                                          'is_null' => false],
                    'item6_wdts'     => ['val' => $wdtsVal,         'disp' => number_format($wdtsVal, 2),                                        'is_null' => false],
                    'item7_t'        => ['val' => $hargaTawaran,    'disp' => number_format($hargaTawaran, 2),                                   'is_null' => false],
                    'item8_aj'       => ['val' => $ajVal,           'disp' => number_format($ajVal, 2),                                          'is_null' => false],
                    'item9_wkp'      => ['val' => null,             'disp' => '0.00',                                                            'is_null' => true],
                    'item10_wps'     => ['val' => null,             'disp' => '0.00',                                                            'is_null' => true],
                    'item11_tsp'     => ['val' => $tspVal,          'disp' => ($tspVal !== null) ? number_format($tspVal, 2) : '0.00',          'is_null' => ($tspVal === null)],
                    'item12_ts'      => ['val' => $tsVal,           'disp' => ($tsVal !== null) ? number_format($tsVal, 2) : '0.00',           'is_null' => ($tsVal === null)],
                    'item13_ntbk'    => ['val' => null,             'disp' => '0.00',                                                            'is_null' => true],
                    'item14_kb'      => ['val' => $kbVal,           'disp' => number_format($kbVal, 2),                                          'is_null' => false],
                    'item15_ntp'     => ['val' => $ntpVal,          'disp' => ($ntpVal !== null) ? number_format($ntpVal, 2) : '0.00',          'is_null' => ($ntpVal === null)],
                    'item16_peratus' => ['val' => $peratusKbNtpVal, 'disp' => ($peratusKbNtpVal !== null) ? number_format($peratusKbNtpVal, 2) . ' %' : '0.00 %', 'is_null' => ($peratusKbNtpVal === null)],
                ]
            ];
        }

        return view($viewName, compact(
            'tender',
            'tender_no',
            'no_tender_display',
            'tajuk_display',
            'ptj_display',
            'tempoh_sah_laku',
            'sah_laku_tamat',
            'status_label',
            'participants',
            'kriteriaList',
            'kriteriaStatusMap',
            'vendorSummary',
            'b2DocsList',
            'b2DocStatusMap',
            'b2VendorSummary',
            'evaluations',
            'readOnly',
            'borang_code',
            'progress',
            'borangAccess',
            'b3VendorData',
            'b3MonthsLabels',
            'b4VendorData',
            'b5CriteriaList',
            'b5CriteriaStatusMap',
            'b5VendorSummary',
            'b5BilanganBerjaya',
            'b6PassingVendors',
            'b7VendorSummary',
            'b8VendorSummary'
        ));
    }

    /**
     * AJAX: Save evaluation for a specific Borang 1 criterion across participating vendors.
     */
    public function simpanBorang1Kriteria(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'kriteria_id'             => 'required|integer|min:1|max:7',
            'evaluations'             => 'required|array',
            'evaluations.*.vendor_id' => 'required|integer',
            'evaluations.*.status'    => 'required|string',
            'evaluations.*.catatan'   => 'nullable|string|max:1000',
        ]);

        $kriteriaId = (int) $request->input('kriteria_id');
        $evaluationsData = $request->input('evaluations');

        $kriteriaNames = [
            1 => 'Borang Tender Ditandatangani',
            2 => 'Penandatangan Diberi kuasa?',
            3 => 'Harga Tender / Tempoh Tercatat di Borang Tender',
            4 => 'Pendaftaran Masih Sah Semasa Tutup Tender',
            5 => 'Mengembalikan Kesemua Dokumen Asas Tender',
            6 => 'Tempoh Tidak Melebihi Tempoh Siap Maksimum',
            7 => 'Surat Akuan Pembida Ditandatangani (Integrity Pact)',
        ];

        $kriteriaName = $kriteriaNames[$kriteriaId] ?? "Kriteria {$kriteriaId}";

        \Illuminate\Support\Facades\DB::transaction(function () use ($tender, $kriteriaId, $kriteriaName, $kriteriaNames, $evaluationsData) {
            foreach ($evaluationsData as $item) {
                $vendorId = (int) $item['vendor_id'];
                $rawStatus = trim($item['status']);
                $statusVal = ($rawStatus === 'Tidak' || $rawStatus === 'Tidak Sempurna') ? 'Tidak Sempurna' : 'Sempurna';
                $catatanVal = $item['catatan'] ?? '';

                $record = TenderKewanganKerjaEvaluation::query()->firstOrNew([
                    'tender_id'   => $tender->id,
                    'vendor_id'   => $vendorId,
                    'borang_code' => 'borang1',
                ]);

                $payload = $record->payload ?? [];
                if (is_string($payload)) {
                    $payload = json_decode($payload, true) ?: [];
                }

                if (! isset($payload['kriteria'])) {
                    $payload['kriteria'] = [];
                }

                $payload['kriteria'][(string) $kriteriaId] = [
                    'id'      => $kriteriaId,
                    'name'    => $kriteriaName,
                    'status'  => $statusVal,
                    'catatan' => $catatanVal,
                ];

                $allSempurna = true;
                $allEvaluated = true;
                $failedReasons = [];

                for ($i = 1; $i <= 7; $i++) {
                    $kItem = $payload['kriteria'][(string) $i] ?? null;
                    if (! $kItem || empty($kItem['status'])) {
                        $allEvaluated = false;
                        $allSempurna = false;
                    } elseif ($kItem['status'] === 'Tidak Sempurna' || $kItem['status'] === 'Tidak') {
                        $allSempurna = false;
                        $catTxt = ! empty($kItem['catatan']) ? ': ' . $kItem['catatan'] : '';
                        $failedReasons[] = 'Kriteria ' . $i . ' (' . ($kriteriaNames[$i] ?? "K{$i}") . ')' . $catTxt;
                    }
                }

                $payload['sebab_gagal'] = $failedReasons;
                $payload['keputusan_akhir'] = $allEvaluated ? ($allSempurna ? 'Sempurna' : 'Tidak Sempurna') : 'Belum Selesai';

                $record->fill([
                    'status_pematuhan' => $allEvaluated ? ($allSempurna ? 1 : 0) : null,
                    'payload'          => $payload,
                    'catatan'          => ! empty($failedReasons) ? implode('; ', $failedReasons) : 'Semua dokumen lengkap dan mematuhi syarat.',
                    'updated_by'       => Auth::id(),
                ]);

                if (! $record->exists) {
                    $record->created_by = Auth::id();
                }

                $record->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Penilaian {$kriteriaName} telah berjaya disimpan.",
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 1 evaluation, updating progress and unlocking Borang 2.
     */
    public function simpanBorang1Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang1'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 1 (Analisa Kesempurnaan Tender) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Save evaluation for a specific Borang 2 document item across participating vendors.
     */
    public function simpanBorang2Kriteria(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'doc_id'                  => 'required|string',
            'evaluations'             => 'required|array',
            'evaluations.*.vendor_id' => 'required|integer',
            'evaluations.*.dikemukakan' => 'required|string',
            'evaluations.*.diaudit'   => 'nullable|string',
            'evaluations.*.catatan'   => 'nullable|string|max:1000',
        ]);

        $docId = $request->input('doc_id');
        $evaluationsData = $request->input('evaluations');

        $b2DocsList = [
            'imbangan'         => 'Lembaran Imbangan',
            'penyata_bank'     => 'Penyata Bulanan / Akaun Bank',
            'bon_saham'        => 'Bon atau Saham',
            'prestasi'         => 'Prestasi Kerja Semasa Petender',
            'laporan_ca'       => 'Laporan Bank atau Borang CA',
            'laporan_penyelia' => 'Laporan Penyelia Projek Bagi Kerja Semasa (Borang GA)',
        ];

        $docName = $b2DocsList[$docId] ?? $docId;

        \Illuminate\Support\Facades\DB::transaction(function () use ($tender, $docId, $docName, $b2DocsList, $evaluationsData) {
            foreach ($evaluationsData as $item) {
                $vendorId = (int) $item['vendor_id'];
                $dikemukakan = trim($item['dikemukakan'] ?? 'Tidak');
                $diaudit = trim($item['diaudit'] ?? 'T.K.S');
                $catatanVal = $item['catatan'] ?? '';

                $record = TenderKewanganKerjaEvaluation::query()->firstOrNew([
                    'tender_id'   => $tender->id,
                    'vendor_id'   => $vendorId,
                    'borang_code' => 'borang2',
                ]);

                $payload = $record->payload ?? [];
                if (is_string($payload)) {
                    $payload = json_decode($payload, true) ?: [];
                }

                if (! isset($payload['docs'])) {
                    $payload['docs'] = [];
                }

                $payload['docs'][$docId] = [
                    'id'          => $docId,
                    'name'        => $docName,
                    'dikemukakan' => $dikemukakan,
                    'diaudit'     => $diaudit,
                    'catatan'     => $catatanVal,
                ];

                $allCukup = true;
                $allEvaluated = true;
                $tanpaGA = false;
                $failedReasons = [];

                foreach ($b2DocsList as $dKey => $dTitle) {
                    $dItem = $payload['docs'][$dKey] ?? null;
                    if (! $dItem || empty($dItem['dikemukakan'])) {
                        $allEvaluated = false;
                        $allCukup = false;
                    } else {
                        if ($dItem['dikemukakan'] === 'Tidak') {
                            if ($dKey === 'laporan_penyelia') {
                                $tanpaGA = true;
                            } else {
                                $allCukup = false;
                                $catTxt = ! empty($dItem['catatan']) ? ': ' . $dItem['catatan'] : '';
                                $failedReasons[] = $dTitle . ': Dikemukakan = Tidak' . $catTxt;
                            }
                        }

                        if ($dKey === 'imbangan' && isset($dItem['diaudit']) && $dItem['diaudit'] === 'Tidak') {
                            $allCukup = false;
                            $catTxt = ! empty($dItem['catatan']) ? ': ' . $dItem['catatan'] : '';
                            $failedReasons[] = $dTitle . ': Diaudit = Tidak' . $catTxt;
                        }
                    }
                }

                $payload['sebab_gagal'] = $failedReasons;
                $payload['keputusan_akhir'] = $allEvaluated ? ($allCukup ? ($tanpaGA ? '*Cukup' : 'Cukup') : 'Tidak Cukup') : 'Belum Selesai';

                $record->fill([
                    'status_pematuhan' => $allEvaluated ? ($allCukup ? 1 : 0) : null,
                    'payload'          => $payload,
                    'catatan'          => ! empty($failedReasons) ? implode('; ', $failedReasons) : ($tanpaGA ? 'Cukup (Tanpa Borang GA).' : 'Dokumen kewangan lengkap dikemukakan.'),
                    'updated_by'       => Auth::id(),
                ]);

                if (! $record->exists) {
                    $record->created_by = Auth::id();
                }

                $record->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Penilaian {$docName} telah berjaya disimpan.",
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 2 evaluation, updating progress and unlocking Borang 3.
     */
    public function simpanBorang2Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang2'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 2 (Analisa Kecukupan Dokumen) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 3 evaluation, updating progress and unlocking Borang 4.
     */
    public function simpanBorang3Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang3'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 3 (Analisa Kecukupan Modal) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Save Borang 4 vendor performance evaluation.
     */
    public function simpanBorang4PenilaianVendor(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'vendor_id'    => 'required|integer',
            'projek_sakit' => 'required|string',
            'catatan'      => 'nullable|string|max:1000',
        ]);

        $vendorId = (int) $request->input('vendor_id');
        $projekSakit = strtoupper(trim($request->input('projek_sakit', 'TIADA')));
        $catatanVal = $request->input('catatan', '');

        $statusPematuhan = ($projekSakit === 'ADA') ? 0 : 1;
        $statusLabel = ($statusPematuhan === 1) ? 'MEMUASKAN' : 'TIDAK MEMUASKAN';

        $record = TenderKewanganKerjaEvaluation::query()->firstOrNew([
            'tender_id'   => $tender->id,
            'vendor_id'   => $vendorId,
            'borang_code' => 'borang4',
        ]);

        $payload = [
            'projek_sakit'          => $projekSakit,
            'status_prestasi_label' => $statusLabel,
            'evaluated_at'          => now()->toDateTimeString(),
            'evaluated_by'          => Auth::id(),
        ];

        $record->fill([
            'status_pematuhan' => $statusPematuhan,
            'payload'          => $payload,
            'catatan'          => $catatanVal ?: ("Prestasi Kerja Semasa disahkan: {$statusLabel}."),
            'updated_by'       => Auth::id(),
        ]);

        if (! $record->exists) {
            $record->created_by = Auth::id();
        }

        $record->save();

        return response()->json([
            'success'               => true,
            'message'               => "Penilaian Prestasi Kerja bagi petender telah berjaya disimpan.",
            'vendor_id'             => $vendorId,
            'projek_sakit'          => $projekSakit,
            'status_pematuhan'      => $statusPematuhan,
            'status_prestasi_label' => $statusLabel,
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 4 evaluation, updating progress and unlocking Borang 5.
     */
    public function simpanBorang4Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang4'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 4 (Analisa Data-Data Penilaian Prestasi Petender) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Save evaluation for a specific Borang 5 criterion across participating vendors.
     */
    public function simpanBorang5Kriteria(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'borang_target'           => 'required|string|in:borang1,borang2,borang3,borang4',
            'evaluations'             => 'required|array',
            'evaluations.*.vendor_id' => 'required|integer',
            'evaluations.*.status'    => 'required|string',
            'evaluations.*.catatan'   => 'nullable|string|max:1000',
        ]);

        $borangTarget = $request->input('borang_target');
        $evaluationsData = $request->input('evaluations');

        $b5CriteriaList = [
            'borang1' => 'Kesempurnaan Tender (Borang 1)',
            'borang2' => 'Kecukupan Dokumen (Borang 2)',
            'borang3' => 'Kecukupan Modal (Borang 3)',
            'borang4' => 'Prestasi Kerja Semasa (Borang 4)',
        ];

        $criterionLabel = $b5CriteriaList[$borangTarget] ?? $borangTarget;

        \Illuminate\Support\Facades\DB::transaction(function () use ($tender, $borangTarget, $criterionLabel, $b5CriteriaList, $evaluationsData) {
            foreach ($evaluationsData as $item) {
                $vendorId = (int) $item['vendor_id'];
                $rawStatus = trim($item['status']);
                $statusVal = ($rawStatus === 'Tidak' || $rawStatus === 'Tidak Sempurna') ? 'Tidak Sempurna' : 'Sempurna';
                $catatanVal = $item['catatan'] ?? '';

                $record = TenderKewanganKerjaEvaluation::query()->firstOrNew([
                    'tender_id'   => $tender->id,
                    'vendor_id'   => $vendorId,
                    'borang_code' => 'borang5',
                ]);

                $payload = $record->payload ?? [];
                if (is_string($payload)) {
                    $payload = json_decode($payload, true) ?: [];
                }

                if (! isset($payload['kriteria'])) {
                    $payload['kriteria'] = [];
                }

                $payload['kriteria'][$borangTarget] = [
                    'code'    => $borangTarget,
                    'label'   => $criterionLabel,
                    'status'  => $statusVal,
                    'catatan' => $catatanVal,
                ];

                $allSempurna = true;
                $allEvaluated = true;
                $failedReasons = [];

                foreach ($b5CriteriaList as $cCode => $cLabel) {
                    $kItem = $payload['kriteria'][$cCode] ?? null;
                    if (! $kItem || empty($kItem['status'])) {
                        $allEvaluated = false;
                        $allSempurna = false;
                    } elseif ($kItem['status'] === 'Tidak Sempurna' || $kItem['status'] === 'Tidak') {
                        $allSempurna = false;
                        $catTxt = ! empty($kItem['catatan']) ? ': ' . $kItem['catatan'] : '';
                        $failedReasons[] = $cLabel . ' - Tidak Sempurna' . $catTxt;
                    }
                }

                $payload['sebab_gagal'] = $failedReasons;
                $payload['keputusan_akhir'] = $allEvaluated ? ($allSempurna ? 'Sempurna' : 'Tidak Sempurna') : 'Belum Selesai';

                $record->fill([
                    'status_pematuhan' => $allEvaluated ? ($allSempurna ? 1 : 0) : null,
                    'payload'          => $payload,
                    'catatan'          => ! empty($failedReasons) ? implode('; ', $failedReasons) : 'Memenuhi syarat kelayakan penilaian Peringkat Pertama.',
                    'updated_by'       => Auth::id(),
                ]);

                if (! $record->exists) {
                    $record->created_by = Auth::id();
                }

                $record->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Penilaian {$criterionLabel} telah berjaya disimpan.",
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 5 evaluation, updating progress and unlocking Borang 6.
     */
    public function simpanBorang5Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'chk_sah' => 'required|accepted',
        ], [
            'chk_sah.required' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'chk_sah.accepted' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
        ]);

        $participants = \App\TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('participate', 1)
            ->get();

        $evaluations = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'borang5')
            ->get()
            ->keyBy('vendor_id');

        $b5CriteriaList = ['borang1', 'borang2', 'borang3', 'borang4'];

        foreach ($participants as $p) {
            $vId = $p->vendor_id;
            $evalRec = $evaluations->get($vId);
            $payload = $evalRec ? ($evalRec->payload ?? []) : [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }
            $kData = $payload['kriteria'] ?? [];

            foreach ($b5CriteriaList as $cCode) {
                if (! isset($kData[$cCode]['status']) || empty($kData[$cCode]['status'])) {
                    return response()->json([
                        'message' => 'Sila lengkapkan semakan 4 kriteria untuk semua petender terlebih dahulu.',
                    ], 422);
                }
            }
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang5'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->peringkat1_confirmed_at = now();
        $progress->peringkat1_confirmed_by = Auth::id();
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 5 (Jadual Keputusan Penilaian Peringkat Pertama) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 6, updating progress and unlocking Borang 7.
     */
    public function simpanBorang6Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'chk_sah' => 'required|accepted',
        ], [
            'chk_sah.required' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'chk_sah.accepted' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
        ]);

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang6'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 6 (Senarai Petender Yang Lulus Penilaian Peringkat Pertama) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 7, updating progress and unlocking Borang 8.
     */
    public function simpanBorang7Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'chk_sah' => 'required|accepted',
        ], [
            'chk_sah.required' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'chk_sah.accepted' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
        ]);

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang7'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 7 (Analisa Nilai Baki Kerja Dalam Tangan) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 8, updating progress and unlocking Borang 9 to 14 simultaneously.
     */
    public function simpanBorang8Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'chk_sah' => 'required|accepted',
        ], [
            'chk_sah.required' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'chk_sah.accepted' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
        ]);

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang8'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 8 (Analisa Kedudukan Kewangan) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 14, updating progress and unlocking Borang 15.
     */
    public function simpanBorang14Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'chk_sah' => 'required|accepted',
        ], [
            'chk_sah.required' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'chk_sah.accepted' => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
        ]);

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $statusData['borang14'] = [
            'status'       => 'completed',
            'completed_at' => now()->toDateTimeString(),
            'completed_by' => Auth::id(),
        ];

        $progress->borang_status = $statusData;
        $progress->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Maklumat Borang 14 (Jadual Keputusan Penilaian Peringkat Ketiga) telah berjaya disahkan dan disimpan!',
            'redirect' => route('penilaianKewanganKerja.show', $tender_no),
        ]);
    }

    /**
     * AJAX: Submit and finalize Borang 15 (Final Penilaian Kewangan recommendation & completion).
     */
    public function simpanBorang15Muktamad(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah dikunci atau selesai.'], 422);
        }

        $request->validate([
            'chk_sah'          => 'required|accepted',
            'selected_vendors' => 'required|array|min:1',
        ], [
            'chk_sah.required'          => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'chk_sah.accepted'          => 'Sila tandakan kotak pengesahan sebelum menyimpan keputusan.',
            'selected_vendors.required' => 'Sila pilih sekurang-kurangnya satu petender yang disyorkan.',
            'selected_vendors.min'      => 'Sila pilih sekurang-kurangnya satu petender yang disyorkan.',
        ]);

        $selectedVendorIds = array_map('intval', (array) $request->input('selected_vendors', []));

        DB::transaction(function () use ($tender, $selectedVendorIds) {
            // 1. Update / Create TenderKewanganLaporan record
            $laporanRecord = TenderKewanganLaporan::query()->firstOrNew([
                'tender_id' => $tender->id,
            ]);

            $laporanRecord->fill([
                'catatan_rumusan'         => 'Laporan Penilaian Kewangan (Kerja) disahkan.',
                'pengesyoran_justifikasi' => ['recommended_vendor_ids' => $selectedVendorIds],
                'status'                  => 'submitted',
                'submitted_at'            => now(),
                'submitted_by'            => Auth::id(),
                'updated_by'              => Auth::id(),
            ]);

            if (! $laporanRecord->exists) {
                $laporanRecord->created_by = Auth::id();
            }

            $laporanRecord->save();

            // 2. Process all participating vendors for this tender
            $allTenderVendors = TenderVendor::where('tender_id', $tender->id)->get();

            foreach ($allTenderVendors as $tv) {
                if (in_array((int) $tv->vendor_id, $selectedVendorIds, true)) {
                    // Selected / Recommended vendor: Keep active
                    $tv->update([
                        'cancel_fg' => 0,
                    ]);
                } else {
                    // Non-recommended vendor: Mark eliminated at Penilaian Kewangan
                    $tv->eliminate(
                        TenderProcessStatus::PENILAIAN_KEWANGAN,
                        'Tidak disyorkan dalam Penilaian Kewangan (Borang 15)'
                    );
                }
            }

            // 3. Mark Borang 15 as completed in progress tracker
            $progress = TenderKewanganProgress::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['current_step' => 1]
            );

            $statusData = $progress->borang_status ?? [];
            if (is_string($statusData)) {
                $statusData = json_decode($statusData, true) ?: [];
            }

            $statusData['borang15'] = [
                'status'       => 'completed',
                'completed_at' => now()->toDateTimeString(),
                'completed_by' => Auth::id(),
            ];

            $progress->borang_status = $statusData;
            $progress->save();

            // 4. Advance tender process status from 10 to 11 (Penilaian Kewangan completed)
            $nextStatus = TenderProcessStatus::PENILAIAN_KEWANGAN;
            $this->advanceTenderProcess($tender, $nextStatus, TenderProcessStatus::penilaianKewanganListStatus());
        });

        return response()->json([
            'success'  => true,
            'message'  => 'Tahniah! Penilaian Kewangan telah berjaya diselesaikan dan tender telah diteruskan ke proses seterusnya.',
            'redirect' => route('penilaianKewangan'),
        ]);
    }

    /**
     * Compute sequential access control for Borang 1 to 15.
     */
    public function computeBorangAccessList(TenderKewanganProgress $progress): array
    {
        $borangDefinitions = [
            1  => ['code' => 'borang1',  'title' => 'Borang 1'],
            2  => ['code' => 'borang2',  'title' => 'Borang 2'],
            3  => ['code' => 'borang3',  'title' => 'Borang 3'],
            4  => ['code' => 'borang4',  'title' => 'Borang 4'],
            5  => ['code' => 'borang5',  'title' => 'Borang 5'],
            6  => ['code' => 'borang6',  'title' => 'Borang 6'],
            7  => ['code' => 'borang7',  'title' => 'Borang 7'],
            8  => ['code' => 'borang8',  'title' => 'Borang 8'],
            9  => ['code' => 'borang9',  'title' => 'Borang 9'],
            10 => ['code' => 'borang10', 'title' => 'Borang 10'],
            11 => ['code' => 'borang11', 'title' => 'Borang 11'],
            12 => ['code' => 'borang12', 'title' => 'Borang 12'],
            13 => ['code' => 'borang13', 'title' => 'Borang 13'],
            14 => ['code' => 'borang14', 'title' => 'Borang 14'],
            15 => ['code' => 'borang15', 'title' => 'Borang 15'],
        ];

        $statusData = $progress->borang_status ?? [];
        if (is_string($statusData)) {
            $statusData = json_decode($statusData, true) ?: [];
        }

        $b8Status = $statusData['borang8'] ?? null;
        $b8Completed = false;
        if (is_array($b8Status)) {
            $b8Completed = (($b8Status['status'] ?? '') === 'completed');
        } elseif (is_string($b8Status)) {
            $b8Completed = ($b8Status === 'completed');
        }

        $accessList = [];
        $prevCompleted = true; // Borang 1 is unlocked by default

        foreach ($borangDefinitions as $num => $def) {
            $code = $def['code'];
            $title = $def['title'];

            $rawStatus = $statusData[$code] ?? null;
            $isCompleted = false;
            if (is_array($rawStatus)) {
                $isCompleted = (($rawStatus['status'] ?? '') === 'completed');
            } elseif (is_string($rawStatus)) {
                $isCompleted = ($rawStatus === 'completed');
            }

            $prevNum = $num - 1;
            $prevDef = $prevNum >= 1 ? $borangDefinitions[$prevNum] : null;

            // Special requirement: Once Borang 8 is completed, Borang 9 to 14 are all unlocked simultaneously
            $isUnlocked = $prevCompleted;
            if ($num >= 9 && $num <= 14 && $b8Completed) {
                $isUnlocked = true;
            }

            $accessList[$code] = [
                'code'         => $code,
                'title'        => $title,
                'borang_num'   => $num,
                'is_unlocked'  => $isUnlocked,
                'is_completed' => $isCompleted,
                'prev_code'    => $prevDef ? $prevDef['code'] : null,
                'prev_title'   => $prevDef ? $prevDef['title'] : null,
            ];

            // Next borang is unlocked IF current borang is completed OR if num >= 8 and b8Completed for 9-14
            $prevCompleted = $isCompleted || ($num >= 8 && $num <= 13 && $b8Completed);
        }

        return $accessList;
    }

    /**
     * AJAX: Save draft / evaluation payload for a given Borang.
     */
    public function simpanBorang(Request $request, string $tender_no, string $borang_code): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $readOnly = ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus());
        if ($readOnly) {
            return response()->json(['message' => 'Penilaian ini telah selesai atau dikunci.'], 422);
        }

        $vendorId = (int) $request->input('vendor_id');
        $statusPematuhan = $request->has('status_pematuhan') ? (int) $request->input('status_pematuhan') : null;
        $catatan = $request->input('catatan');
        $payload = $request->input('payload', []);

        $record = TenderKewanganKerjaEvaluation::query()->firstOrNew([
            'tender_id'   => $tender->id,
            'vendor_id'   => $vendorId,
            'borang_code' => $borang_code,
        ]);

        $record->fill([
            'status_pematuhan' => $statusPematuhan,
            'payload'          => $payload,
            'catatan'          => $catatan,
            'updated_by'       => Auth::id(),
        ]);

        if (! $record->exists) {
            $record->created_by = Auth::id();
        }

        $record->save();

        return response()->json([
            'success' => true,
            'message' => "Maklumat {$borang_code} telah berjaya disimpan.",
            'data'    => $record,
        ]);
    }

    /**
     * AJAX: Milestone Sign-off Handler (Borang 6 for Stage 1, Borang 13 for Stage 2).
     */
    public function sahkanPeringkat(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $peringkat = (int) $request->input('peringkat', 1);

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        if ($peringkat === 1) {
            $progress->peringkat1_confirmed_at = now();
            $progress->peringkat1_confirmed_by = Auth::id();
        } elseif ($peringkat === 2) {
            $progress->peringkat2_confirmed_at = now();
            $progress->peringkat2_confirmed_by = Auth::id();
        }

        $progress->save();

        return response()->json([
            'success' => true,
            'message' => "Pengesahan Peringkat {$peringkat} telah berjaya direkodkan.",
        ]);
    }

    /**
     * AJAX: Final Report Submission (Borang 15).
     */
    public function hantar(Request $request, string $tender_no): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $currentStatus = (int) ($tender->status_process_id ?? 0);
        if ($currentStatus !== TenderProcessStatus::penilaianKewanganListStatus()) {
            return response()->json(['message' => 'Tender ini telah pun dihantar ke peringkat seterusnya.'], 422);
        }

        $laporanRecord = TenderKewanganLaporan::query()->firstOrNew([
            'tender_id' => $tender->id,
        ]);

        $laporanRecord->fill([
            'catatan_rumusan' => $request->input('catatan_rumusan', 'Laporan Penilaian Kewangan (Kerja) disahkan.'),
            'pengesyoran'     => $request->input('pengesyoran'),
            'status'          => 'submitted',
            'submitted_at'    => now(),
            'submitted_by'    => Auth::id(),
            'updated_by'      => Auth::id(),
        ]);

        if (! $laporanRecord->exists) {
            $laporanRecord->created_by = Auth::id();
        }

        $laporanRecord->save();

        // Advance tender process status to next stage
        $nextStatus = TenderProcessStatus::penilaianKewanganNextStatus();
        $this->advanceTenderProcess($tender, $nextStatus, TenderProcessStatus::penilaianKewanganListStatus());

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penilaian Kewangan (Kerja) telah berjaya dihantar!',
            'redirect' => route('penilaianKewangan'),
        ]);
    }
}
