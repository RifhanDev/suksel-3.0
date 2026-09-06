<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Models\FinancialChecklistHeader;
use App\Models\Jawatankuasa;
use App\Models\PenyediaanIklan;
use App\Models\PenyediaanMesyuaratMeeting;
use App\Models\TenderKewanganEvaluation;
use App\Models\TenderKewanganLaporan;
use App\Models\TenderKewanganProgress;
use App\Models\TenderVendorDokumenResponse;
use App\Models\TechnicalChecklistHeader;
use App\Services\StosBackendClient;
use App\Support\ChecklistMechanism;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianKewanganController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;
    use RestrictsTenderByRole;

    /**
     * 2-peringkat = 'fin'; 1-peringkat = 'eval'/'harga' (same committee handles kewangan).
     *
     * @var list<string>
     */
    protected array $financialCommitteeJenis = ['fin', 'eval', 'harga'];

    public function __construct()
    {
        $this->menuMiddleware('FinancialEvaluation:list');
    }

    public function index(Request $request)
    {
        $query = $this->applyCommitteeAppointment(
            Tender::query()->where('status_process_id', TenderProcessStatus::penilaianKewanganListStatus()),
            $this->financialCommitteeJenis
        );

        if ($request->filled('no_tender')) {
            $noTender = trim($request->input('no_tender'));
            $query->where(function ($q) use ($noTender) {
                $q->where('no_tender', 'like', "%{$noTender}%")
                  ->orWhere('ref_number', 'like', "%{$noTender}%");
            });
        }

        if ($request->filled('tajuk')) {
            $tajuk = trim($request->input('tajuk'));
            $query->where('name', 'like', "%{$tajuk}%");
        }

        if ($request->filled('tarikh')) {
            $query->whereDate('submission_datetime', $request->input('tarikh'));
        }

        $totalCount = (clone $query)->count();

        $tenders = $this->mapTendersForProcessList(
            $query->orderByDesc('id')
                ->get(['id', 'uuid', 'no_tender', 'ref_number', 'name', 'submission_datetime', 'status_process_id', 'kategori_perolehan_id']),
            function (Tender $tender, string $noTender) {
                $identifier = $tender->uuid ?: $tender->id;
                if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
                    return route('penilaianKewanganKerja.show', $identifier);
                }
                return route('penilaianKewangan.show', $identifier);
            }
        );

        return view('newModule.penilaian_kewangan.index', compact('tenders', 'totalCount'));
    }

    public function show(string $tender_no)
    {
        $tender = Tender::query()
            ->with(['tenderer'])
            ->where(function ($q) use ($tender_no) {
                $q->where('no_tender', $tender_no)
                  ->orWhere('ref_number', $tender_no)
                  ->orWhere('uuid', $tender_no);
                if (is_numeric($tender_no)) {
                    $q->orWhere('id', (int) $tender_no);
                }
            })
            ->first();

        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);

        if ($tender && (int) ($tender->kategori_perolehan_id ?? 0) === 3) {
            return redirect()->route('penilaianKewanganKerja.show', $tender->uuid ?: $tender->id);
        }

        $no_tender_display = $tender ? ($tender->no_tender ?: $tender->ref_number ?: (string) $tender->id) : $tender_no;
        $tajuk_display = $tender->name ?? '-';
        $ptj_display = $tender->tenderer->name ?? '-';
        $tempoh_sah_laku = 90;

        $submissionDate = $tender && $tender->submission_datetime ? \Carbon\Carbon::parse($tender->submission_datetime) : null;
        $sah_laku_tamat = $submissionDate ? $submissionDate->copy()->addDays($tempoh_sah_laku)->format('d/m/Y') : '-';

        $status_label = 'Menunggu Penilaian Kewangan';
        if ($tender && isset($tender->status_process_id)) {
            if ($tender->status_process_id == TenderProcessStatus::penilaianKewanganListStatus()) {
                $status_label = 'Menunggu Penilaian Kewangan';
            } else {
                $status_label = TenderProcessStatus::label($tender->status_process_id);
            }
        }

        $kewanganItems = [];
        $penyataBankItems = [];
        $vendors = [];
        $dokumenByVendor = [];
        $semakPayload = [];
        $pembekalMelepasi = [];
        $pembekalTidakMelepasi = [];
        $pembekalBelumDinilai = [];
        $penyataDokumenByVendor = [];

        if ($tender) {
            $tenderDokumen = TenderDokumenPresenter::for($tender);
            $checklistItems = $tenderDokumen->items('admin');

            $isNotMuatTurun = fn (array $item) => strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? '')) !== 'muat turun';

            // Retrieve Penyata Bank identifiers dynamically from standard_checklist_items table
            $penyataBankUrls = \App\Models\StandardChecklistItem::query()
                ->where(function ($q) {
                    $q->where('action_url', 'like', '%penyata-bank%')
                      ->orWhere('title', 'like', '%penyata bank%')
                      ->orWhere('title', 'like', '%penyata bulanan%');
                })
                ->pluck('action_url')
                ->filter()
                ->toArray();

            $isNotPenyataBank = function (array $item) use ($penyataBankUrls) {
                $title = strtolower(trim($item['title'] ?? $item['nama'] ?? ''));
                $actionUrl = strtolower(trim($item['admin_content']['form']['url'] ?? $item['action_url'] ?? ''));
                $formKey = strtolower(trim($item['admin_content']['form']['form_key'] ?? ''));

                if (str_contains($title, 'penyata bank') || str_contains($title, 'penyata bulanan bank') || $formKey === 'penyata_bank') {
                    return false;
                }

                foreach ($penyataBankUrls as $url) {
                    if ($actionUrl !== '' && str_contains($actionUrl, strtolower($url))) {
                        return false;
                    }
                }

                return true;
            };

            $kewanganItems = collect($checklistItems)
                ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
                ->filter($isNotMuatTurun)
                ->filter($isNotPenyataBank)
                ->values()
                ->all();

            $penyataBankItems = collect($checklistItems)
                ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
                ->filter($isNotMuatTurun)
                ->filter(fn (array $item) => ! $isNotPenyataBank($item))
                ->values()
                ->all();

            if (empty($penyataBankItems)) {
                $stdPenyataBank = \App\Models\StandardChecklistItem::query()
                    ->where('is_active', 1)
                    ->where(function ($q) {
                        $q->where('action_url', 'like', '%penyata-bank%')
                          ->orWhere('title', 'like', '%penyata bank%')
                          ->orWhere('title', 'like', '%penyata bulanan%');
                    })
                    ->first();

                if ($stdPenyataBank) {
                    $penyataBankItems = [[
                        'uuid'       => $stdPenyataBank->uuid,
                        'title'      => $stdPenyataBank->title,
                        'nama'       => $stdPenyataBank->title,
                        'mekanisma'  => $stdPenyataBank->mechanism_default ?: 'Borang Atas Talian',
                        'tindakan'   => $stdPenyataBank->mechanism_default ?: 'Borang Atas Talian',
                        'action'     => 'online_form',
                        'source'     => 'financial',
                        'admin_content' => [
                            'form' => [
                                'url'      => $stdPenyataBank->action_url ?: '/penyata-bank',
                                'form_key' => 'penyata_bank',
                            ],
                        ],
                    ]];
                }
            }

            $participants = $tender->participants()
                ->where('participate', 1)
                ->with('vendor')
                ->orderBy('id')
                ->get();

            $dokumenByVendor = [];
            foreach ($participants as $participant) {
                $vendorId = (int) $participant->vendor_id;
                $dokumenByVendor[$vendorId] = $tenderDokumen->items('vendor', $vendorId);
            }

            $kewanganUuids = collect($kewanganItems)->pluck('uuid')->filter()->all();

            $vendors = $participants->map(function ($participant) use ($tender, $kewanganUuids) {
                // Resolve vendor's total financial offered price (Financial Specification responses only)
                $harga = null;
                $responses = \App\Models\TenderVendorDokumenResponse::query()
                    ->where('tender_id', $tender->id)
                    ->where('vendor_id', $participant->vendor_id)
                    ->where('response_type', 'specification')
                    ->where(function ($q) use ($kewanganUuids) {
                        $q->whereIn('section', ['financial', 'kewangan_kerja']);
                        if (! empty($kewanganUuids)) {
                            $q->orWhereIn('checklist_item_uuid', $kewanganUuids);
                        }
                    })
                    ->get();

                $totalSpec = 0.0;
                $hasPrices = false;
                foreach ($responses as $resp) {
                    $itemPrices = $resp->payload['item_prices'] ?? [];
                    if (is_array($itemPrices)) {
                        foreach ($itemPrices as $val) {
                            if (is_numeric($val) && (float) $val > 0) {
                                $totalSpec += (float) $val;
                                $hasPrices = true;
                            }
                        }
                    }
                }

                if ($hasPrices) {
                    $harga = $totalSpec;
                } elseif (filled($participant->harga_tawaran) && (float) $participant->harga_tawaran > 0) {
                    $harga = (float) $participant->harga_tawaran;
                }

                // Resolve Bumiputera status
                $isBumi = false;
                if ($participant->is_bumiputera !== null) {
                    $isBumi = (int) $participant->is_bumiputera === 1;
                } elseif ($participant->vendor) {
                    $isBumi = (bool) ($participant->vendor->mof_bumi || $participant->vendor->cidb_bumi || ((float) $participant->vendor->bumi_percentage >= 50));
                }
                $bumiputeraStatusLabel = $isBumi ? 'Bumiputera' : 'Bukan Bumiputera';

                return [
                    'vendor_id'         => (int) $participant->vendor_id,
                    'name'              => $participant->vendor?->name ?: ('Vendor #' . $participant->vendor_id),
                    'kod'               => $participant->kod_pembekal ?: null,
                    'is_bumiputera'     => $isBumi ? 1 : 0,
                    'bumiputera_status' => $bumiputeraStatusLabel,
                    'harga_tawaran'     => $harga ? (float) $harga : null,
                    'harga_tawaran_fmt' => $harga ? number_format((float) $harga, 2) : '-',
                ];
            })->values()->all();

            $allStepItems = array_merge($kewanganItems, $penyataBankItems);
            $semakPayload = $this->buildSemakPayload($tender, [], $allStepItems, $dokumenByVendor, $vendors);

            // Load existing Penilaian Kewangan evaluations and merge into semakPayload
            $evaluations  = $this->loadEvaluations($tender);
            $semakPayload = $this->mergeEvaluationsIntoPayload($semakPayload, $evaluations);

            // Calculate Step 1 Rumusan: Passed vs Failed vs Pending Vendors
            $totalStep1Items = count($kewanganItems);

            foreach ($vendors as $v) {
                $vendorId = (int) $v['vendor_id'];
                $failedReasons = [];
                $passedCount = 0;
                $evaluatedCount = 0;

                foreach ($kewanganItems as $item) {
                    $itemUuid = $item['uuid'] ?? '';
                    $itemTitle = $item['title'] ?? $item['nama'] ?? 'Item Senarai Semak';
                    $evalKey = "{$vendorId}:{$itemUuid}";
                    $eval = $evaluations[$evalKey] ?? null;

                    if ($eval) {
                        $evaluatedCount++;
                        if ($eval->status_pematuhan === 0) {
                            $catatanTxt = filled($eval->catatan) ? ": {$eval->catatan}" : '';
                            $failedReasons[] = "{$itemTitle}{$catatanTxt}";
                        } else if ($eval->status_pematuhan === 1) {
                            $passedCount++;
                        }
                    }
                }

                if (count($failedReasons) > 0) {
                    $pembekalTidakMelepasi[] = [
                        'vendor_id' => $vendorId,
                        'kod'       => $v['kod'],
                        'name'      => $v['name'],
                        'ulasan'    => 'Tidak mematuhi syarat: ' . implode('; ', $failedReasons),
                        'failed_reasons' => $failedReasons,
                    ];
                } else if ($passedCount === $totalStep1Items) {
                    $pembekalMelepasi[] = [
                        'vendor_id' => $vendorId,
                        'kod'       => $v['kod'],
                        'name'      => $v['name'],
                        'ulasan'    => 'Mematuhi semua syarat pematuhan dokumentasi kewangan.',
                    ];
                } else {
                    // Vendor has not been fully evaluated yet
                    $pending = $totalStep1Items - $evaluatedCount;
                    $pembekalBelumDinilai[] = [
                        'vendor_id'      => $vendorId,
                        'kod'            => $v['kod'],
                        'name'           => $v['name'],
                        'evaluated'      => $evaluatedCount,
                        'total'          => $totalStep1Items,
                        'pending'        => $pending,
                        'ulasan'         => "{$evaluatedCount}/{$totalStep1Items} item telah dinilai. {$pending} item lagi menunggu penilaian.",
                    ];
                }
            }
        }

        $penyataBankRecord = $tender ? \App\Models\PenyataBank::query()
            ->with(['bulans', 'scoringItems', 'files'])
            ->where('tender_id', $tender->id)
            ->first() : null;

        $penyataBankConfig = [
            'dari_bulan'    => $penyataBankRecord?->dari_bulan,
            'dari_tahun'    => $penyataBankRecord?->dari_tahun,
            'hingga_bulan'  => $penyataBankRecord?->hingga_bulan,
            'hingga_tahun'  => $penyataBankRecord?->hingga_tahun,
            'bulans'        => [],
            'scoring_items' => [],
        ];

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April',
            5 => 'Mei', 6 => 'Jun', 7 => 'Julai', 8 => 'Ogos',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember',
        ];

        if ($penyataBankRecord && $penyataBankRecord->bulans->count() > 0) {
            foreach ($penyataBankRecord->bulans as $b) {
                $mNum = (int) $b->bulan;
                $mYr = (int) $b->tahun;
                $mName = ($monthNames[$mNum] ?? ("Bulan " . $mNum)) . ' ' . $mYr;
                $penyataBankConfig['bulans'][] = [
                    'bulan'  => $mNum,
                    'tahun'  => $mYr,
                    'nama'   => $mName,
                    'jumlah' => (float) $b->jumlah,
                ];
            }
        } elseif ($penyataBankRecord && $penyataBankRecord->dari_bulan && $penyataBankRecord->dari_tahun) {
            $startM = (int) $penyataBankRecord->dari_bulan;
            $startY = (int) $penyataBankRecord->dari_tahun;
            for ($i = 0; $i < 3; $i++) {
                $mNum = (($startM - 1 + $i) % 12) + 1;
                $mYr = $startY + (int) floor(($startM - 1 + $i) / 12);
                $mName = ($monthNames[$mNum] ?? ("Bulan " . $mNum)) . ' ' . $mYr;
                $penyataBankConfig['bulans'][] = [
                    'bulan'  => $mNum,
                    'tahun'  => $mYr,
                    'nama'   => $mName,
                    'jumlah' => 0.00,
                ];
            }
        } else {
            // Default fallback: 3 months
            $penyataBankConfig['bulans'] = [
                ['bulan' => 6, 'tahun' => 2025, 'nama' => 'Bulan 6 (Jun 2025)', 'jumlah' => 0.00],
                ['bulan' => 7, 'tahun' => 2025, 'nama' => 'Bulan 7 (Julai 2025)', 'jumlah' => 0.00],
                ['bulan' => 8, 'tahun' => 2025, 'nama' => 'Bulan 8 (Ogos 2025)', 'jumlah' => 0.00],
            ];
        }

        if ($penyataBankRecord && $penyataBankRecord->scoringItems->count() > 0) {
            foreach ($penyataBankRecord->scoringItems as $s) {
                $penyataBankConfig['scoring_items'][] = [
                    'dari'   => (float) $s->dari,
                    'hingga' => $s->hingga !== null ? (float) $s->hingga : null,
                    'skema'  => (string) $s->skema,
                ];
            }
        } else {
            // Default reference scale fallback
            $penyataBankConfig['scoring_items'] = [
                ['dari' => 0.00, 'hingga' => 10064.99, 'skema' => '0'],
                ['dari' => 10065.00, 'hingga' => null, 'skema' => '10'],
            ];
        }

        $penyataBankFiles = $tender ? \Illuminate\Support\Facades\DB::table('penyata_bank_files')
            ->join('penyata_banks', 'penyata_banks.id', '=', 'penyata_bank_files.penyata_bank_id')
            ->where('penyata_banks.tender_id', $tender->id)
            ->select('penyata_bank_files.*')
            ->get()
            ->map(function ($f) {
                $path = $f->path ?? '';
                $cleanPath = ltrim(str_replace('public/', '', $path), '/');
                return [
                    'uuid'          => $f->uuid,
                    'name'          => $f->original_name,
                    'original_name' => $f->original_name,
                    'path'          => $cleanPath,
                    'file_path'     => $cleanPath,
                    'url'           => ! empty($f->uuid) ? route('tenderDokumen.download', $f->uuid) : asset('storage/' . $cleanPath),
                    'size'          => $f->size,
                    'uploaded_by'   => $f->uploaded_by,
                ];
            })
            ->all() : [];

        // Tender-level template files stay on config for scoring/bulan only — NOT for petender dokumen.
        $penyataBankConfig['files'] = [];

        $vendorFormPayloads = $tender ? \Illuminate\Support\Facades\DB::table('tender_vendor_form_payloads')
            ->where('tender_id', $tender->id)
            ->where('form_key', 'penyata_bank')
            ->get()
            ->keyBy('vendor_id')
            ->map(fn ($r) => json_decode($r->payload, true))
            ->all() : [];

        $penyataDokumenByVendor = $this->buildPenyataDokumenByVendor(
            $tender,
            $vendors ?? [],
            $vendorFormPayloads,
            $dokumenByVendor ?? [],
            collect($penyataBankItems ?? [])->pluck('uuid')->filter()->all(),
            $penyataBankFiles
        );

        $progress = null;
        if ($tender) {
            $progress = TenderKewanganProgress::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['current_step' => 1]
            );
        }

        $rumusanStep3Data = $this->calculateStep3Rumusan($tender, $semakPayload);

        $rumusanLaporanData = $this->calculateStep4Rumusan($tender, $kewanganItems, $penyataBankItems, $semakPayload, $rumusanStep3Data);

        $laporanRecord = null;
        if ($tender) {
            $laporanRecord = TenderKewanganLaporan::query()
                ->where('tender_id', $tender->id)
                ->first();
        }

        $readOnly = $tender ? ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus()) : false;

        $isKerja = $tender ? ((int) ($tender->kategori_perolehan_id ?? 0) === 3) : false;
        $viewPath = $isKerja ? 'newModule.penilaian_kewangan.penilaian.show' : 'newModule.penilaian_kewangan.show';

        return view($viewPath, compact(
            'tender_no',
            'tender',
            'no_tender_display',
            'tajuk_display',
            'ptj_display',
            'tempoh_sah_laku',
            'sah_laku_tamat',
            'status_label',
            'kewanganItems',
            'penyataBankItems',
            'vendors',
            'dokumenByVendor',
            'vendorFormPayloads',
            'semakPayload',
            'pembekalMelepasi',
            'pembekalTidakMelepasi',
            'pembekalBelumDinilai',
            'penyataBankConfig',
            'penyataDokumenByVendor',
            'rumusanStep3Data',
            'rumusanLaporanData',
            'laporanRecord',
            'readOnly',
            'progress'
        ));
    }

    /**
     * Printable Laporan Jawatankuasa Penilaian Harga.
     *
     * Sections 1-5 read live data; 6 onwards are still hardcoded from the sample report.
     */
    public function cetakLaporan(string $tender_no)
    {
        $tender = Tender::query()
            ->with('tenderer')
            ->where(function ($q) use ($tender_no) {
                $q->where('no_tender', $tender_no)
                  ->orWhere('ref_number', $tender_no)
                  ->orWhere('uuid', $tender_no);
                if (is_numeric($tender_no)) {
                    $q->orWhere('id', (int) $tender_no);
                }
            })
            ->first();

        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);

        $dokumenKewangan = $this->loadDokumenKewangan($tender);
        $jphJenis = $this->resolveJphJenis($tender);

        $petenderHarga = $this->loadPetenderHarga($tender);
        $berharga = $petenderHarga->filter(fn (array $p) => $p['harga'] !== null);
        $terendah = $berharga->sortBy('harga')->first();
        $tertinggi = $berharga->sortByDesc('harga')->first();

        $peringkat = $this->buildPeringkatPenilaian($tender, $petenderHarga);
        $laporanRecord = $tender
            ? TenderKewanganLaporan::query()->where('tender_id', $tender->id)->first()
            : null;

        $anggaran = (float) ($tender->anggaran_jabatan ?? 0);
        $modalMinimum = $anggaran > 0 ? 'RM' . number_format($anggaran * 0.03, 2) : '-';

        $pengesyoran = $this->buildPengesyoran($tender, $peringkat['rumusan'], $petenderHarga);

        $mesyuarat = PenyediaanMesyuaratMeeting::query()
            ->where('tender_id', $tender?->id)
            ->whereIn('jenis_jawatankuasa', $jphJenis)
            ->orderByDesc('tarikh_mesyuarat')
            ->first();

        return view('newModule.penilaian_kewangan.laporan_cetak', [
            'tender' => $tender,
            'noTender' => $tender->no_tender ?: ($tender->ref_number ?: '-'),
            'latarBelakang' => $this->buildLatarBelakang($tender, $dokumenKewangan->count()),
            'jphMembers' => $this->loadJphMembers($tender, $jphJenis),
            'tarikhPemakluman' => $this->resolveTarikhPemakluman($tender, $jphJenis),
            'tarikhMesyuarat' => $mesyuarat?->tarikh_mesyuarat
                ? $this->formatTarikhMalay(Carbon::parse($mesyuarat->tarikh_mesyuarat))
                : null,
            'dokumenKewangan' => $dokumenKewangan->map(fn ($item, int $idx) => [
                'bil' => $idx + 1,
                'keterangan' => $item->title ?: '-',
            ]),
            'dokumenTeknikal' => $this->loadDokumenTeknikal($tender)->map(fn ($item, int $idx) => [
                'bil' => $idx + 1,
                'keterangan' => $item->title ?: '-',
            ]),
            'jadual2' => $peringkat['jadual2'],
            'jadual3' => $peringkat['jadual3'],
            'jadual4' => $peringkat['jadual4'],
            'bilPeringkat1' => $this->bilanganPerkataan($peringkat['jadual2']->count()),
            'bilPeringkat2' => $this->bilanganPerkataan($peringkat['jadual3']->count()),
            'bilPeringkat3' => $this->bilanganPerkataan($peringkat['jadual4']->count()),
            'modalMinimum' => $modalMinimum,
            'catatanPeringkat1' => $laporanRecord?->catatan_peringkat1,
            'catatanPeringkat2' => $laporanRecord?->catatan_peringkat2,
            'catatanPeringkat3' => $laporanRecord?->catatan_peringkat3,
            'petenderDisyorkan' => $pengesyoran['disyorkan'],
            'petenderLayakLain' => $pengesyoran['layakLain'],
            'pengesyoranJustifikasi' => collect($laporanRecord?->pengesyoran_justifikasi ?? [])
                ->map(fn ($t) => trim((string) $t))
                ->filter()
                ->values(),
            'petenderHarga' => $petenderHarga,
            'bilanganPetender' => $this->bilanganPerkataan($petenderHarga->count()),
            'petenderTerendah' => $terendah,
            'petenderTertinggi' => $tertinggi,
            'kodBidangMof' => $this->flattenKodBidang($tender, 'mof'),
            'kodBidangCidb' => $this->flattenKodBidang($tender, 'cidb'),
            'gredCidb' => $this->loadGredCidb($tender),
            // Matches canParticipate(): anything other than 'and' behaves as OR, casing ignored.
            'mofCidbRule' => strtoupper((string) ($tender?->mof_cidb_rule ?: 'or')) === 'AND' ? 'Dan' : 'Atau',
        ]);
    }

    /**
     * Flattens the grouped kod bidang into printable rows, carrying the operator that joins
     * each row to the next: inner_rule within a group, join_rule between groups, none at the end.
     */
    private function flattenKodBidang(?Tender $tender, string $codeType): Collection
    {
        if (! $tender) {
            return collect();
        }

        $groups = TenderCode::query()
            ->with('code')
            ->where('tender_id', $tender->id)
            ->where('code_type', $codeType)
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->filter(fn (TenderCode $row) => $row->code)
            ->groupBy('order')
            ->values();

        $rows = collect();
        $bil = 1;

        foreach ($groups as $groupIndex => $group) {
            $items = $group->values();

            foreach ($items as $itemIndex => $row) {
                $isLastInGroup = $itemIndex === $items->count() - 1;
                $isLastGroup = $groupIndex === $groups->count() - 1;

                $operator = null;
                if (! $isLastInGroup) {
                    $operator = $row->inner_rule;
                } elseif (! $isLastGroup) {
                    $operator = $row->join_rule;
                }

                $rows->push([
                    'bil' => $bil++,
                    'kod' => $row->code->code,
                    'keterangan' => $this->tajukKod($row->code->name),
                    'operator' => $operator ? ($operator === 'and' ? 'Dan' : 'Atau') : null,
                ]);
            }
        }

        return $rows;
    }

    private function loadGredCidb(?Tender $tender): Collection
    {
        if (! $tender) {
            return collect();
        }

        return TenderCode::query()
            ->with('code')
            ->where('tender_id', $tender->id)
            ->where('code_type', 'cidb-g')
            ->orderBy('id')
            ->get()
            ->filter(fn (TenderCode $row) => $row->code)
            ->values()
            ->map(fn (TenderCode $row, int $idx) => [
                'bil' => $idx + 1,
                'kod' => $row->code->code,
                'keterangan' => $this->tajukKod($row->code->name),
            ]);
    }

    /** Code descriptions are stored upper-case and may carry stray newlines. */
    private function tajukKod(?string $text): string
    {
        $clean = preg_replace('/\s+/', ' ', trim((string) $text));

        return ucwords(mb_strtolower($clean), " \t\r\n\f\v/(-");
    }

    /**
     * 2-peringkat appoints the Kewangan committee under 'fin'; 1-peringkat has no such tab
     * and handles kewangan under 'eval'/'harga' instead.
     *
     * @return list<string>
     */
    private function resolveJphJenis(?Tender $tender): array
    {
        if (! $tender) {
            return ['fin'];
        }

        $hasFin = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', 'fin')
            ->whereNotNull('user_id')
            ->exists();

        return $hasFin ? ['fin'] : $this->financialCommitteeJenis;
    }

    /**
     * @param  list<string>  $jenisList
     */
    private function loadJphMembers(?Tender $tender, array $jenisList): Collection
    {
        if (! $tender) {
            return collect();
        }

        $perananLabels = ['1' => 'Pengerusi', '2' => 'Setiausaha', '3' => 'Ahli'];
        $letters = range('a', 'z');

        return Jawatankuasa::query()
            ->with('user.organizationunit')
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $jenisList)
            ->whereNotNull('user_id')
            ->orderBy('peranan')
            ->orderBy('id')
            ->get()
            ->filter(fn (Jawatankuasa $row) => $row->user)
            ->values()
            ->map(fn (Jawatankuasa $row, int $idx) => [
                'letter' => ($letters[$idx] ?? '-') . '.',
                'peranan_label' => $perananLabels[(string) $row->peranan] ?? 'Ahli',
                'name' => $row->user->name,
                'jawatan' => $row->user->jawatan ?: null,
                'department' => $row->user->department ?: null,
                'agensi' => optional($row->user->organizationunit)->name,
            ]);
    }

    /**
     * @param  list<string>  $jenisList
     */
    private function resolveTarikhPemakluman(?Tender $tender, array $jenisList): ?string
    {
        if (! $tender) {
            return null;
        }

        $tarikh = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $jenisList)
            ->whereNotNull('dihantar_pemakluman_pada')
            ->min('dihantar_pemakluman_pada');

        return $tarikh ? $this->formatTarikhMalay(Carbon::parse($tarikh)) : null;
    }

    /**
     * The financial checklist items the petender must submit — the same set Langkah 1
     * evaluates, and what "Bilangan Dokumen ... Untuk Dinilai" counts.
     */
    private function loadDokumenKewangan(?Tender $tender): Collection
    {
        if (! $tender) {
            return collect();
        }

        $header = FinancialChecklistHeader::query()
            ->where('tender_id', $tender->id)
            ->with('items')
            ->first();

        return collect($header?->items ?? [])
            ->reject(fn ($item) => $item->mechanism === ChecklistMechanism::PTJ_MUAT_NAIK
                && $item->vendor_action === ChecklistMechanism::VENDOR_ACTION_MUAT_TURUN)
            ->values();
    }

    /**
     * Petender list with their offered price, ordered as they appear in the tender.
     *
     * Price precedence mirrors show(): the summed financial specification prices when the
     * petender filled them in, otherwise the recorded harga_tawaran — so the report and the
     * evaluation screen can never disagree.
     */
    private function loadPetenderHarga(?Tender $tender): Collection
    {
        if (! $tender) {
            return collect();
        }

        $isPenyataBank = fn (?string $title) => $title
            && (str_contains(mb_strtolower($title), 'penyata bank')
                || str_contains(mb_strtolower($title), 'penyata bulanan'));

        $kewanganUuids = $this->loadDokumenKewangan($tender)
            ->reject(fn ($item) => $isPenyataBank($item->title))
            ->pluck('uuid')
            ->filter()
            ->all();

        $participants = $tender->participants()
            ->with('vendor')
            ->where('participate', 1)
            ->orderBy('id')
            ->get();

        $total = $participants->count();

        return $participants->values()->map(function ($participant, int $idx) use ($tender, $kewanganUuids, $total) {
            $responses = TenderVendorDokumenResponse::query()
                ->where('tender_id', $tender->id)
                ->where('vendor_id', $participant->vendor_id)
                ->where('response_type', 'specification')
                ->where(function ($q) use ($kewanganUuids) {
                    $q->whereIn('section', ['financial', 'kewangan_kerja']);
                    if (! empty($kewanganUuids)) {
                        $q->orWhereIn('checklist_item_uuid', $kewanganUuids);
                    }
                })
                ->get();

            $totalSpec = 0.0;
            $hasPrices = false;
            foreach ($responses as $resp) {
                foreach ((array) ($resp->payload['item_prices'] ?? []) as $val) {
                    if (is_numeric($val) && (float) $val > 0) {
                        $totalSpec += (float) $val;
                        $hasPrices = true;
                    }
                }
            }

            $harga = null;
            if ($hasPrices) {
                $harga = $totalSpec;
            } elseif (filled($participant->harga_tawaran) && (float) $participant->harga_tawaran > 0) {
                $harga = (float) $participant->harga_tawaran;
            }

            return [
                'bil' => $idx + 1,
                'vendor_id' => (int) $participant->vendor_id,
                'no_petender' => $participant->kod_pembekal ?: (($idx + 1) . '/' . $total),
                'nama' => $participant->vendor?->name ?: ('Vendor #' . $participant->vendor_id),
                'harga' => $harga,
                'harga_display' => $harga !== null ? number_format($harga, 2) : '-',
            ];
        });
    }

    /**
     * The three evaluation stages for section 7, computed through the same
     * calculateStep3Rumusan()/calculateStep4Rumusan() the Langkah 4 screen uses.
     *
     * @return array<string, mixed>
     */
    private function buildPeringkatPenilaian(?Tender $tender, Collection $petenderHarga): array
    {
        $empty = ['jadual2' => collect(), 'jadual3' => collect(), 'jadual4' => collect(), 'rumusan' => []];

        if (! $tender) {
            return $empty;
        }

        $items = $this->loadDokumenKewangan($tender);
        if ($items->isEmpty()) {
            return $empty;
        }

        $isPenyataBank = fn ($item) => $item->title
            && (str_contains(mb_strtolower($item->title), 'penyata bank')
                || str_contains(mb_strtolower($item->title), 'penyata bulanan'));

        $kewanganItems = $items->reject($isPenyataBank)->map(fn ($i) => ['uuid' => $i->uuid])->values()->all();
        $penyataItems = $items->filter($isPenyataBank)->map(fn ($i) => ['uuid' => $i->uuid])->values()->all();

        $semakPayload = [];
        foreach ($items as $item) {
            $semakPayload[$item->uuid] = ['max_score' => (float) $item->score];
        }

        $rumusanStep3 = $this->calculateStep3Rumusan($tender, $semakPayload);
        $rumusan = $this->calculateStep4Rumusan($tender, $kewanganItems, $penyataItems, $semakPayload, $rumusanStep3);

        $hargaByVendor = $petenderHarga->keyBy('vendor_id');
        $noPetenderByVendor = $petenderHarga->pluck('no_petender', 'vendor_id');

        $kewanganUuids = array_column($kewanganItems, 'uuid');
        $catatanKewangan = $this->loadCatatan($tender, $kewanganUuids);
        $catatanSkor = $this->loadCatatan($tender, $kewanganUuids, 'catatan_skor');
        $catatanPenyata = $this->loadCatatan($tender, array_column($penyataItems, 'uuid'));

        $jadual2 = collect($rumusan)->values()->map(function (array $row, int $idx) use ($noPetenderByVendor, $catatanKewangan) {
            $lulus = $row['step1_status'] === 'melepasi';
            $belum = $row['step1_status'] === 'belum_dinilai';

            return [
                'bil' => $idx + 1,
                'no_petender' => $noPetenderByVendor[$row['vendor_id']] ?? '-',
                'keputusan' => $belum ? 'Belum Dinilai' : ($lulus ? 'Lulus' : 'Gagal'),
                'ulasan' => ($catatanKewangan[$row['vendor_id']] ?? null)
                    ?? ($belum
                        ? 'Belum Dinilai'
                        : ($lulus ? 'Layak Ke Penilaian Peringkat Kedua' : 'Tidak Layak Ke Penilaian Peringkat Kedua')),
            ];
        });

        $jadual3 = collect($rumusan)
            ->filter(fn (array $row) => $row['step1_status'] === 'melepasi')
            ->values()
            ->map(function (array $row, int $idx) use ($noPetenderByVendor, $catatanPenyata) {
                $lulus = $row['step2_status'] === 'melepasi';
                $belum = $row['step2_status'] === 'belum_dinilai';

                return [
                    'bil' => $idx + 1,
                    'no_petender' => $noPetenderByVendor[$row['vendor_id']] ?? '-',
                    'keputusan' => $belum ? 'Belum Dinilai' : ($lulus ? 'Lulus' : 'Gagal'),
                    'ulasan' => $catatanPenyata[$row['vendor_id']]
                        ?? ($belum
                            ? 'Belum Dinilai'
                            : ($lulus ? 'Layak Ke Peringkat Penilaian Ketiga' : 'Tidak Layak Ke Peringkat Penilaian Ketiga')),
                ];
            });

        $jadual4 = collect($rumusan)
            ->filter(fn (array $row) => $row['step1_status'] === 'melepasi' && $row['step2_status'] === 'melepasi')
            ->values()
            ->map(function (array $row, int $idx) use ($noPetenderByVendor, $hargaByVendor, $catatanSkor) {
                $lulus = $row['step3_status'] === 'melepasi';
                $belum = $row['step3_status'] === 'belum_dinilai';

                return [
                    'bil' => $idx + 1,
                    'no_petender' => $noPetenderByVendor[$row['vendor_id']] ?? '-',
                    'harga_display' => $hargaByVendor[$row['vendor_id']]['harga_display'] ?? '-',
                    'keputusan' => $belum ? 'Belum Dinilai' : ($lulus ? 'Lulus' : 'Gagal'),
                    'ulasan' => $catatanSkor[$row['vendor_id']]
                        ?? ($belum
                            ? 'Belum Dinilai'
                            : ($lulus ? 'Layak Ke Peringkat Pengesyoran' : 'Tidak Layak Ke Peringkat Pengesyoran')),
                ];
            });

        return compact('jadual2', 'jadual3', 'jadual4', 'rumusan');
    }

    /**
     * @param  array<int, array<string, mixed>>  $rumusan
     * @return array<string, mixed>
     */
    private function buildPengesyoran(?Tender $tender, array $rumusan, Collection $petenderHarga): array
    {
        $anggaran = (float) ($tender->anggaran_jabatan ?? 0);
        $hargaByVendor = $petenderHarga->keyBy('vendor_id');

        $layak = collect($rumusan)
            ->filter(fn (array $row) => ! empty($row['is_layak']))
            ->map(function (array $row) use ($hargaByVendor, $anggaran) {
                $petender = $hargaByVendor[$row['vendor_id']] ?? null;
                $harga = $petender['harga'] ?? null;
                $bwaj = ($anggaran > 0 && $harga !== null)
                    ? round((($harga - $anggaran) / $anggaran) * 100, 2)
                    : null;

                return [
                    'vendor_id' => $row['vendor_id'],
                    'no_petender' => $petender['no_petender'] ?? '-',
                    'harga' => $harga,
                    'harga_display' => $petender['harga_display'] ?? '-',
                    'bwaj' => $bwaj,
                    'bwaj_display' => $bwaj !== null ? number_format($bwaj, 2) : '-',
                ];
            })
            ->sortBy(fn (array $row) => $row['harga'] ?? PHP_FLOAT_MAX)
            ->values();

        return [
            'disyorkan' => $layak->first(),
            'layakLain' => $layak->skip(1)->values()->map(fn (array $row, int $idx) => $row + ['bil' => $idx + 1]),
        ];
    }

    /**
     * The evaluator's catatan per vendor, for the given checklist items — this is what the
     * Ulasan column prints.
     *
     * @param  list<string>  $uuids
     * @return array<int, string>
     */
    private function loadCatatan(Tender $tender, array $uuids, string $column = 'catatan'): array
    {
        if (empty($uuids)) {
            return [];
        }

        return TenderKewanganEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereIn('checklist_item_uuid', $uuids)
            ->get()
            ->groupBy('vendor_id')
            ->map(fn ($rows) => $rows
                ->map(fn ($r) => $this->bacaCatatan($r->{$column}))
                ->filter()
                ->unique()
                ->implode(' '))
            ->filter(fn (string $text) => $text !== '')
            ->all();
    }

    /**
     * Kemampuan Kewangan items store catatan as JSON alongside the modal scores, so the
     * evaluator's words live under a nested key rather than in the column itself.
     */
    private function bacaCatatan(?string $raw): ?string
    {
        $raw = trim((string) $raw);

        if ($raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $raw = trim((string) ($decoded['catatan'] ?? ''));
        }

        return $raw !== '' ? $raw : null;
    }

    /** "enam (6)" — the sample report spells the count out before the numeral. */
    private function bilanganPerkataan(int $n): string
    {
        $words = [
            0 => 'sifar', 1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima',
            6 => 'enam', 7 => 'tujuh', 8 => 'lapan', 9 => 'sembilan', 10 => 'sepuluh',
            11 => 'sebelas', 12 => 'dua belas', 13 => 'tiga belas', 14 => 'empat belas',
            15 => 'lima belas', 16 => 'enam belas', 17 => 'tujuh belas', 18 => 'lapan belas',
            19 => 'sembilan belas', 20 => 'dua puluh',
        ];

        return isset($words[$n]) ? "{$words[$n]} ({$n})" : (string) $n;
    }

    /** The technical checklist items, listed under 5.2 alongside the financial ones. */
    private function loadDokumenTeknikal(?Tender $tender): Collection
    {
        if (! $tender) {
            return collect();
        }

        $header = TechnicalChecklistHeader::query()
            ->where('tender_id', $tender->id)
            ->with('items')
            ->first();

        return collect($header?->items ?? [])
            ->reject(fn ($item) => $item->mechanism === ChecklistMechanism::PTJ_MUAT_NAIK
                && $item->vendor_action === ChecklistMechanism::VENDOR_ACTION_MUAT_TURUN)
            ->values();
    }

    /** @return array<string, mixed> */
    private function buildLatarBelakang(?Tender $tender, int $bilanganDokumen): array
    {
        if (! $tender) {
            return [];
        }

        $iklanRecord = PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
        $iklanMeta = ($iklanRecord && is_array($iklanRecord->meta)) ? ($iklanRecord->meta['iklan'] ?? []) : [];
        $tempohSahLaku = $iklanMeta['tempoh_sah_laku'] ?? null;

        $anggaran = $tender->anggaran_jabatan;

        return [
            'agensi_pelaksana' => $tender->tenderer?->name ?: '-',
            'kaedah_perolehan' => ($tender->isSebutHargaKaedah() ? 'Sebut Harga' : 'Tender') . ' Terbuka Melalui Sistem Perolehan Selangor',
            'anggaran_jabatan' => ($anggaran !== null && (float) $anggaran > 0)
                ? 'RM' . number_format((float) $anggaran, 2)
                : '-',
            'tarikh_iklan' => $tender->advertise_start_date ? $this->formatTarikhMalay(Carbon::parse($tender->advertise_start_date)) : '-',
            'tarikh_jual' => $tender->document_start_date ? $this->formatTarikhMalay(Carbon::parse($tender->document_start_date)) : '-',
            'tarikh_tutup' => $tender->submission_datetime ? $this->formatTarikhMalay(Carbon::parse($tender->submission_datetime)) : '-',
            'masa_tutup' => $tender->masa_tutup_display,
            'tempoh_sah_laku' => $tempohSahLaku ? $tempohSahLaku . ' hari selepas tarikh tutup tender' : '-',
            'bilangan_dokumen' => $bilanganDokumen,
        ];
    }

    private function formatTarikhMalay(Carbon $date): string
    {
        $bulanMs = ['', 'Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'];

        return $date->day . ' ' . $bulanMs[$date->month] . ' ' . $date->year;
    }

    /**
     * AJAX: Save compliance evaluation for a single item (Penilaian Kewangan)
     */
    public function simpanPematuhan(Request $request): JsonResponse
    {
        $request->validate([
            'tender'               => 'required|string',
            'vendor_id'            => 'required|integer',
            'checklist_item_uuid'  => 'required|uuid',
            'status_pematuhan'     => 'required',
            'catatan'              => 'nullable|string|max:2000',
        ]);

        $rawStatus = $request->input('status_pematuhan');
        $statusInt = ($rawStatus === 'mematuhi' || $rawStatus === '1' || (int) $rawStatus === 1) ? 1 : 0;

        if ($statusInt === 0) {
            $request->validate([
                'catatan' => 'required|string|min:1|max:2000',
            ]);
        }

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $stepParam = (int) $request->input('step', 0);
        $checkUuid = (string) $request->input('checklist_item_uuid');

        $isStep3Item = ($stepParam === 3);
        $isStep2Item = ($stepParam === 2);

        if (! $isStep2Item && ! $isStep3Item) {
            $stdPenyataUuids = \App\Models\StandardChecklistItem::query()
                ->where(function ($q) {
                    $q->where('action_url', 'like', '%penyata-bank%')
                      ->orWhere('title', 'like', '%penyata bank%')
                      ->orWhere('title', 'like', '%penyata bulanan%');
                })
                ->pluck('uuid')
                ->toArray();

            $finPenyataUuids = \App\Models\FinancialChecklistItem::query()
                ->where(function ($q) {
                    $q->where('title', 'like', '%penyata bank%')
                      ->orWhere('title', 'like', '%penyata bulanan%');
                })
                ->pluck('uuid')
                ->toArray();

            $allPenyataUuids = array_merge($stdPenyataUuids, $finPenyataUuids);
            $isStep2Item = in_array($checkUuid, $allPenyataUuids, true);
        }

        if ($isStep3Item) {
            if ($progress->isStep3Confirmed() || $progress->current_step > 3) {
                return response()->json([
                    'message' => 'Penilaian Spesifikasi Kewangan telah disahkan dan tidak boleh diubah lagi.'
                ], 422);
            }
        } elseif ($isStep2Item) {
            if ($progress->isStep2Confirmed() || $progress->current_step > 2) {
                return response()->json([
                    'message' => 'Penilaian Penyata Bulanan Bank telah disahkan dan tidak boleh diubah lagi.'
                ], 422);
            }
        } else {
            if ($progress->isStep1Confirmed() || $progress->current_step > 1) {
                return response()->json([
                    'message' => 'Penilaian pematuhan dokumentasi telah disahkan dan tidak boleh diubah lagi.'
                ], 422);
            }
        }

        $skorInput = (float) $request->input('skor', 0);

        // Resolve max_score for checklist item from Teknikal process
        $maxScore = 0.0;
        $techCheckItem = \Illuminate\Support\Facades\DB::table('financial_checklist_items as fci')
            ->leftJoin('technical_checklist_items as tci', 'tci.id', '=', 'fci.technical_item_id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->where('fci.uuid', $checkUuid)
            ->orWhere('tci.uuid', $checkUuid)
            ->select(
                'fci.score as fci_score',
                'tci.specification_document_id',
                'tci.score as tci_score',
                'tsd.total_score as doc_total_score'
            )
            ->first();

        $specDocId = $techCheckItem?->specification_document_id;

        if ($specDocId) {
            $detailsSum = (float) \Illuminate\Support\Facades\DB::table('technical_specification_items as tsi')
                ->join('technical_specification_details as tsd_dt', 'tsd_dt.technical_specification_item_id', '=', 'tsi.id')
                ->where('tsi.technical_specification_document_id', $specDocId)
                ->sum('tsd_dt.max_score');

            if ($detailsSum > 0) {
                $maxScore = $detailsSum;
            } elseif ($techCheckItem->doc_total_score && (float) $techCheckItem->doc_total_score > 0) {
                $maxScore = (float) $techCheckItem->doc_total_score;
            }
        }

        if ($maxScore <= 0 && $techCheckItem) {
            if ((float) ($techCheckItem->fci_score ?? 0) > 0) {
                $maxScore = (float) $techCheckItem->fci_score;
            } elseif ((float) ($techCheckItem->tci_score ?? 0) > 0) {
                $maxScore = (float) $techCheckItem->tci_score;
            }
        }

        $isModalScore = $request->has('skor_modal_berbayar') || $request->has('skor_modal_dibenarkan');

        if ($isModalScore) {
            $skorBerbayar = (float) $request->input('skor_modal_berbayar', 0);
            $skorDibenarkan = (float) $request->input('skor_modal_dibenarkan', 0);
            $maxBerbayar = (float) $request->input('max_modal_berbayar', 0);
            $maxDibenarkan = (float) $request->input('max_modal_dibenarkan', 0);

            if ($maxBerbayar > 0 && $skorBerbayar > $maxBerbayar) {
                $fmtMax = ($maxBerbayar == (int) $maxBerbayar) ? (string) (int) $maxBerbayar : number_format($maxBerbayar, 2);
                return response()->json([
                    'message' => "Skor Modal Berbayar tidak boleh melebihi skor maksimum ({$fmtMax})."
                ], 422);
            }

            if ($maxDibenarkan > 0 && $skorDibenarkan > $maxDibenarkan) {
                $fmtMax = ($maxDibenarkan == (int) $maxDibenarkan) ? (string) (int) $maxDibenarkan : number_format($maxDibenarkan, 2);
                return response()->json([
                    'message' => "Skor Modal Dibenarkan tidak boleh melebihi skor maksimum ({$fmtMax})."
                ], 422);
            }

            $skorInput = $skorBerbayar + $skorDibenarkan;
            $catatanSave = json_encode([
                'skor_modal_berbayar'   => $skorBerbayar,
                'skor_modal_dibenarkan' => $skorDibenarkan,
                'catatan'               => (string) ($request->input('catatan') ?? ''),
            ]);
        } else {
            if ($skorInput > $maxScore) {
                $fmtMax = ($maxScore == (int) $maxScore) ? (string) (int) $maxScore : number_format($maxScore, 2);
                return response()->json([
                    'message' => "Skor tidak boleh melebihi skor maksimum ({$fmtMax})."
                ], 422);
            }
            $catatanSave = ($statusInt === 0) ? trim((string) ($request->input('catatan') ?? '')) : $request->input('catatan');
        }

        $record = TenderKewanganEvaluation::query()->firstOrNew([
            'tender_id'           => $tender->id,
            'vendor_id'           => (int) $request->input('vendor_id'),
            'checklist_item_uuid' => (string) $request->input('checklist_item_uuid'),
        ]);

        $fields = [
            'status_pematuhan' => $statusInt,
            'skor'             => $skorInput,
            'updated_by'       => Auth::id(),
        ];

        // Langkah 1 and Langkah 3 write to the same row, so each keeps its own note column.
        // The modal-score branch still writes its JSON to catatan — the scores live inside it.
        if ((int) $request->input('step') === 3) {
            $fields['catatan_skor'] = trim((string) ($request->input('catatan') ?? '')) ?: null;

            if ($isModalScore) {
                $fields['catatan'] = $catatanSave;
            }
        } else {
            $fields['catatan'] = $catatanSave;
        }

        $record->fill($fields);

        if (! $record->exists) {
            $record->created_by = Auth::id();
        }

        $record->save();

        $isItemSelesai = false;
        if ($tender) {
            $allVendors = $tender->participants()
                ->pluck('vendor_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $failedStep1VendorIds = \App\Models\TenderKewanganEvaluation::query()
                ->where('tender_id', $tender->id)
                ->where('status_pematuhan', 0)
                ->whereNotIn('checklist_item_uuid', function ($q) {
                    $q->select('uuid')->from('standard_checklist_items')->where('action_url', 'like', '%penyata-bank%')
                        ->orWhere('title', 'like', '%penyata bank%')->orWhere('title', 'like', '%penyata bulanan%');
                })
                ->whereNotIn('checklist_item_uuid', function ($q) {
                    $q->select('uuid')->from('financial_checklist_items')->where('title', 'like', '%penyata bank%')
                        ->orWhere('title', 'like', '%penyata bulanan%');
                })
                ->pluck('vendor_id')
                ->unique()
                ->toArray();

            $eligibleVendorIds = array_diff($allVendors, $failedStep1VendorIds);
            $totalEligible = count($eligibleVendorIds);

            if ($totalEligible > 0) {
                $evaluatedCount = \App\Models\TenderKewanganEvaluation::query()
                    ->where('tender_id', $tender->id)
                    ->where('checklist_item_uuid', $checkUuid)
                    ->whereIn('vendor_id', $eligibleVendorIds)
                    ->whereNotNull('skor')
                    ->count();
                $isItemSelesai = ($evaluatedCount === $totalEligible);
            }
        }

        return response()->json([
            'message'          => 'Penilaian pematuhan kewangan telah disimpan.',
            'status_pematuhan' => $record->status_pematuhan,
            'catatan'          => $record->catatan,
            'catatan_skor'     => $record->catatan_skor,
            'skor'             => $record->skor,
            'is_item_selesai'  => $isItemSelesai,
        ]);
    }

    /**
     * AJAX: Update step progress or confirmation state in database
     */
    public function kemaskiniLangkah(Request $request): JsonResponse
    {
        $request->validate([
            'tender'      => 'required|string',
            'step'        => 'nullable|integer|between:1,3',
            'confirmed'   => 'nullable',
            'target_step' => 'nullable|integer|between:1,4',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $userId = Auth::id();
        $now    = now();

        // Handle step confirmation toggle
        if ($request->has('step') && $request->has('confirmed')) {
            $stepNum   = (int) $request->input('step');
            $confirmed = filter_var($request->input('confirmed'), FILTER_VALIDATE_BOOLEAN);

            if (! $confirmed && $progress->current_step > $stepNum) {
                return response()->json([
                    'message' => 'Langkah ini telah disahkan dan proses telah mara ke peringkat seterusnya. Pengesahan tidak boleh dibatalkan.'
                ], 422);
            }

            if ($stepNum === 1) {
                $progress->step1_confirmed_at = $confirmed ? $now : null;
                $progress->step1_confirmed_by = $confirmed ? $userId : null;
                if (! $confirmed) {
                    $progress->step2_confirmed_at = null;
                    $progress->step2_confirmed_by = null;
                    $progress->step3_confirmed_at = null;
                    $progress->step3_confirmed_by = null;
                    $progress->current_step = 1;
                }
            } else if ($stepNum === 2) {
                if ($confirmed && ! $progress->isStep1Confirmed()) {
                    return response()->json(['message' => 'Sila sahkan Langkah 1 terlebih dahulu.'], 422);
                }

                if ($confirmed) {
                    $stdPenyataUuids = \App\Models\StandardChecklistItem::query()
                        ->where(function ($q) {
                            $q->where('action_url', 'like', '%penyata-bank%')
                              ->orWhere('title', 'like', '%penyata bank%')
                              ->orWhere('title', 'like', '%penyata bulanan%');
                        })
                        ->pluck('uuid')
                        ->toArray();

                    $finPenyataUuids = \App\Models\FinancialChecklistItem::query()
                        ->where(function ($q) {
                            $q->where('title', 'like', '%penyata bank%')
                              ->orWhere('title', 'like', '%penyata bulanan%');
                        })
                        ->pluck('uuid')
                        ->toArray();

                    $penyataUuids = array_merge($stdPenyataUuids, $finPenyataUuids);

                    $failedStep1VendorIds = TenderKewanganEvaluation::query()
                        ->where('tender_id', $tender->id)
                        ->whereNotIn('checklist_item_uuid', $penyataUuids)
                        ->where('status_pematuhan', 0)
                        ->pluck('vendor_id')
                        ->unique()
                        ->toArray();

                    $eligibleVendorIds = $tender->participants()
                        ->where('participate', 1)
                        ->whereNotIn('vendor_id', $failedStep1VendorIds)
                        ->pluck('vendor_id')
                        ->toArray();

                    if (count($eligibleVendorIds) > 0 && count($penyataUuids) > 0) {
                        $evaluatedVendorCount = TenderKewanganEvaluation::query()
                            ->where('tender_id', $tender->id)
                            ->whereIn('checklist_item_uuid', $penyataUuids)
                            ->whereIn('vendor_id', $eligibleVendorIds)
                            ->whereNotNull('status_pematuhan')
                            ->distinct('vendor_id')
                            ->count('vendor_id');

                        if ($evaluatedVendorCount < count($eligibleVendorIds)) {
                            return response()->json([
                                'message' => 'Semua pembekal yang melepasi Langkah 1 mesti dinilai terlebih dahulu sebelum membuat pengesahan akhir Langkah 2.'
                            ], 422);
                        }
                    }
                }

                $progress->step2_confirmed_at = $confirmed ? $now : null;
                $progress->step2_confirmed_by = $confirmed ? $userId : null;
                if (! $confirmed) {
                    $progress->step3_confirmed_at = null;
                    $progress->step3_confirmed_by = null;
                    if ($progress->current_step > 2) {
                        $progress->current_step = 2;
                    }
                }
            } else if ($stepNum === 3) {
                if ($confirmed && ! $progress->isStep2Confirmed()) {
                    return response()->json(['message' => 'Sila sahkan Langkah 2 terlebih dahulu.'], 422);
                }

                if ($confirmed) {
                    $tenderDokumen = TenderDokumenPresenter::for($tender);
                    $checklistItems = $tenderDokumen->items('admin');
                    $isNotMuatTurun = fn (array $item) => strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? '')) !== 'muat turun';

                    $penyataBankUrls = \App\Models\StandardChecklistItem::query()
                        ->where(function ($q) {
                            $q->where('action_url', 'like', '%penyata-bank%')
                              ->orWhere('title', 'like', '%penyata bank%')
                              ->orWhere('title', 'like', '%penyata bulanan%');
                        })
                        ->pluck('action_url')
                        ->filter()
                        ->toArray();

                    $isNotPenyataBank = function (array $item) use ($penyataBankUrls) {
                        $title = strtolower(trim($item['title'] ?? $item['nama'] ?? ''));
                        $actionUrl = strtolower(trim($item['admin_content']['form']['url'] ?? $item['action_url'] ?? ''));
                        $formKey = strtolower(trim($item['admin_content']['form']['form_key'] ?? ''));

                        if (str_contains($title, 'penyata bank') || str_contains($title, 'penyata bulanan bank') || $formKey === 'penyata_bank') {
                            return false;
                        }

                        foreach ($penyataBankUrls as $url) {
                            if ($actionUrl !== '' && str_contains($actionUrl, strtolower($url))) {
                                return false;
                            }
                        }

                        return true;
                    };

                    $kewanganItems = collect($checklistItems)
                        ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
                        ->filter($isNotMuatTurun)
                        ->filter($isNotPenyataBank)
                        ->values()
                        ->all();

                    $penyataBankItems = collect($checklistItems)
                        ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
                        ->filter($isNotMuatTurun)
                        ->filter(fn (array $item) => ! $isNotPenyataBank($item))
                        ->values()
                        ->all();

                    $allStepItems    = array_merge($kewanganItems, $penyataBankItems);
                    $participants    = $tender->participants()->with('vendor')->where('participate', 1)->get();

                    $vendors = $participants->map(fn ($p) => [
                        'vendor_id' => (int) $p->vendor_id,
                        'name'      => $p->vendor?->name ?: ('Vendor #' . $p->vendor_id),
                        'kod'       => $p->kod_pembekal ?: null,
                    ])->all();

                    $semakPayload = $this->buildSemakPayload($tender, [], $allStepItems, [], $vendors);
                    $evaluations  = $this->loadEvaluations($tender);
                    $semakPayload = $this->mergeEvaluationsIntoPayload($semakPayload, $evaluations);

                    $rumusan3 = $this->calculateStep3Rumusan($tender, $semakPayload);

                    $tidakMelepasiVendorIds = collect($rumusan3['pembekal_tidak_melepasi'])->pluck('vendor_id')->toArray();
                    $melepasiVendorIds = collect($rumusan3['pembekal_melepasi'])->pluck('vendor_id')->toArray();

                    if (count($tidakMelepasiVendorIds) > 0) {
                        \Illuminate\Support\Facades\DB::table('tender_vendors')
                            ->where('tender_id', $tender->id)
                            ->whereIn('vendor_id', $tidakMelepasiVendorIds)
                            ->update([
                                'eliminated_process_id' => 3,
                                'eliminated_reason'     => 'Tidak melepasi penanda aras penilaian kewangan (' . $rumusan3['passing_percentage'] . '%)',
                                'eliminated_at'         => $now,
                            ]);
                    }

                    if (count($melepasiVendorIds) > 0) {
                        \Illuminate\Support\Facades\DB::table('tender_vendors')
                            ->where('tender_id', $tender->id)
                            ->whereIn('vendor_id', $melepasiVendorIds)
                            ->where('eliminated_process_id', 3)
                            ->update([
                                'eliminated_process_id' => null,
                                'eliminated_reason'     => null,
                                'eliminated_at'         => null,
                            ]);
                    }
                }

                $progress->step3_confirmed_at = $confirmed ? $now : null;
                $progress->step3_confirmed_by = $confirmed ? $userId : null;
            }
        }

        // Handle target step change
        if ($request->filled('target_step')) {
            $targetStep = (int) $request->input('target_step');
            if ($targetStep > 2 && ! $progress->isStep2Confirmed()) {
                return response()->json([
                    'message' => 'Sila sahkan Langkah 2 (Pengesahan Akhir) terlebih dahulu sebelum meneruskan ke Langkah 3.'
                ], 422);
            }
            if ($progress->isStepUnlocked($targetStep)) {
                $progress->current_step = $targetStep;
            } else {
                return response()->json(['message' => 'Langkah tersebut masih terkunci.'], 422);
            }
        }

        $progress->save();

        return response()->json([
            'message'      => 'Kemajuan langkah telah dikemaskini.',
            'current_step' => $progress->current_step,
            'confirmed'    => [
                'step1' => $progress->isStep1Confirmed(),
                'step2' => $progress->isStep2Confirmed(),
                'step3' => $progress->isStep3Confirmed(),
            ],
            'unlocked'     => [
                'step1' => $progress->isStepUnlocked(1),
                'step2' => $progress->isStepUnlocked(2),
                'step3' => $progress->isStepUnlocked(3),
                ],
        ]);
    }

    /**
     * Calculate Step 3 Rumusan (Vendor total scores, passing threshold comparison, ranking, and pass/fail status).
     *
     * @param  \App\Tender  $tender
     * @param  array<string, mixed>  $semakPayload
     * @return array<string, mixed>
     */
    protected function calculateStep3Rumusan(Tender $tender, array $semakPayload = []): array
    {
        $header = \Illuminate\Support\Facades\DB::table('financial_checklist_headers')
            ->where('tender_id', $tender->id)
            ->first();

        $passingPercentage = $header ? (float) $header->passing_percentage : 50.0;
        $passingScoreConf  = $header ? (float) $header->passing_score : 0.0;

        $stdPenyataUuids = \App\Models\StandardChecklistItem::query()
            ->where(function ($q) {
                $q->where('action_url', 'like', '%penyata-bank%')
                  ->orWhere('title', 'like', '%penyata bank%')
                  ->orWhere('title', 'like', '%penyata bulanan%');
            })
            ->pluck('uuid')
            ->toArray();

        $finPenyataUuids = \App\Models\FinancialChecklistItem::query()
            ->where(function ($q) {
                $q->where('title', 'like', '%penyata bank%')
                  ->orWhere('title', 'like', '%penyata bulanan%');
            })
            ->pluck('uuid')
            ->toArray();

        $penyataUuids = array_merge($stdPenyataUuids, $finPenyataUuids);

        $failedStep1VendorIds = TenderKewanganEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereNotIn('checklist_item_uuid', $penyataUuids)
            ->where('status_pematuhan', 0)
            ->pluck('vendor_id')
            ->unique()
            ->toArray();

        $eligibleParticipants = $tender->participants()
            ->with('vendor')
            ->where('participate', 1)
            ->get()
            ->reject(fn ($p) => in_array((int) $p->vendor_id, $failedStep1VendorIds, true));

        $totalMaxScore = 0.0;
        if (! empty($semakPayload)) {
            foreach ($semakPayload as $uuid => $itemPayload) {
                if (! in_array($uuid, $penyataUuids, true)) {
                    $totalMaxScore += (float) ($itemPayload['max_score'] ?? 0);
                }
            }
        }

        if ($totalMaxScore <= 0) {
            $totalMaxScore = (float) ($header?->max_score ?? 100.0);
        }

        $effectivePassingScore = $passingScoreConf > 0
            ? $passingScoreConf
            : round(($passingPercentage / 100.0) * $totalMaxScore, 2);

        $evaluations = $this->loadEvaluations($tender);

        $melepasiList = [];
        $tidakMelepasiList = [];

        foreach ($eligibleParticipants as $participant) {
            $vId = (int) $participant->vendor_id;
            $vendorName = $participant->vendor?->name ?: ('Vendor #' . $vId);
            $vendorKod = $participant->kod_pembekal ?: ($participant->vendor?->kod_pembekal ?? '-');

            $vendorTotalScore = 0.0;

            if (! empty($semakPayload)) {
                foreach ($semakPayload as $uuid => $itemPayload) {
                    if (in_array($uuid, $penyataUuids, true)) {
                        continue;
                    }
                    $evalKey = "{$vId}:{$uuid}";
                    $eval = $evaluations[$evalKey] ?? null;

                    if ($eval) {
                        $vendorTotalScore += (float) ($eval->skor ?? 0);
                    }
                }
            }

            $scorePct = ($totalMaxScore > 0) ? round(($vendorTotalScore / $totalMaxScore) * 100, 2) : 0.0;
            $isMelepasi = ($scorePct >= $passingPercentage) || ($vendorTotalScore >= $effectivePassingScore);

            $fmtTotal = (number_format($vendorTotalScore, 0) == $vendorTotalScore) ? number_format($vendorTotalScore, 0) : number_format($vendorTotalScore, 2);
            $fmtMax = (number_format($totalMaxScore, 0) == $totalMaxScore) ? number_format($totalMaxScore, 0) : number_format($totalMaxScore, 2);

            $vendorData = [
                'vendor_id'     => $vId,
                'kod'           => $vendorKod,
                'name'          => $vendorName,
                'total_score'   => $vendorTotalScore,
                'max_score'     => $totalMaxScore,
                'percentage'    => $scorePct,
                'is_melepasi'   => $isMelepasi,
                'score_fmt'     => "{$fmtTotal} / {$fmtMax}",
                'status_label'  => $isMelepasi ? 'Melepasi Penanda Aras' : 'Tidak Melepasi Penanda Aras',
            ];

            if ($isMelepasi) {
                $melepasiList[] = $vendorData;
            } else {
                $vendorData['catatan'] = "Jumlah skor ({$scorePct}%) tidak melepasi penanda aras kelulusan ({$passingPercentage}%).";
                $tidakMelepasiList[] = $vendorData;
            }
        }

        usort($melepasiList, fn ($a, $b) => $b['total_score'] <=> $a['total_score']);

        $rank = 1;
        foreach ($melepasiList as $idx => &$item) {
            if ($idx > 0 && $item['total_score'] < $melepasiList[$idx - 1]['total_score']) {
                $rank = $idx + 1;
            }
            $item['kedudukan'] = $rank;
        }
        unset($item);

        return [
            'passing_percentage'      => $passingPercentage,
            'passing_score'           => $effectivePassingScore,
            'total_max_score'         => $totalMaxScore,
            'pembekal_melepasi'       => $melepasiList,
            'pembekal_tidak_melepasi' => $tidakMelepasiList,
            'count_melepasi'          => count($melepasiList),
            'count_tidak_melepasi'    => count($tidakMelepasiList),
        ];
    }

    /**
     * Load evaluations from tender_kewangan_evaluations
     */
    protected function loadEvaluations(Tender $tender): array
    {
        return TenderKewanganEvaluation::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy(fn ($item) => "{$item->vendor_id}:{$item->checklist_item_uuid}")
            ->all();
    }

    /**
     * Merge saved evaluation values back into the semakPayload
     *
     * @param  array<string, array<string, mixed>>  $semakPayload
     * @param  array<string, \App\Models\TenderKewanganEvaluation>  $evaluations
     * @return array<string, array<string, mixed>>
     */
    protected function mergeEvaluationsIntoPayload(array $semakPayload, array $evaluations): array
    {
        foreach ($semakPayload as $uuid => &$payload) {
            $isProfilPetender = !empty($payload['profil_petender_detail']);
            $isSpesifikasi    = !empty($payload['spesifikasi_detail']);

            foreach ($payload['vendors'] as &$vendorRow) {
                $vendorId = (int) $vendorRow['vendor_id'];
                $evalKey  = "{$vendorId}:{$uuid}";
                $eval     = $evaluations[$evalKey] ?? null;

                $vendorRow['status_pematuhan']      = $eval ? ($eval->status_pematuhan === 1 ? 'mematuhi' : 'tidak_mematuhi') : null;
                $vendorRow['catatan']               = $eval ? $eval->catatan : null;
                $vendorRow['catatan_skor']          = $eval ? $eval->catatan_skor : null;
                $vendorRow['skor']                  = ($eval && $eval->skor !== null) ? (float) $eval->skor : null;
                $vendorRow['skor_modal_berbayar']   = null;
                $vendorRow['skor_modal_dibenarkan'] = null;

                if ($eval && filled($eval->catatan)) {
                    $json = json_decode($eval->catatan, true);
                    if (is_array($json) && isset($json['skor_modal_berbayar'])) {
                        $vendorRow['skor_modal_berbayar']   = (float) $json['skor_modal_berbayar'];
                        $vendorRow['skor_modal_dibenarkan'] = (float) ($json['skor_modal_dibenarkan'] ?? 0);
                        $vendorRow['catatan']               = $json['catatan'] ?? '';
                    }
                }

                // Determine if vendor was evaluated in Step 3 (not just Step 1)
                // Step 1 saves status_pematuhan=1 with skor=0 for Muat Naik items.
                // Step 3 saves meaningful scores: skor > 0 for spesifikasi/muat_naik,
                // or skor_modal_berbayar/dibenarkan for profil_petender.
                $step3Evaluated = false;
                if ($eval) {
                    if ($isProfilPetender) {
                        $step3Evaluated = ($vendorRow['skor_modal_berbayar'] !== null && $vendorRow['skor_modal_berbayar'] !== '') || ($eval->skor !== null);
                    } else {
                        $step3Evaluated = ($vendorRow['skor'] !== null && $vendorRow['skor'] !== '');
                    }
                }
                $vendorRow['step3_evaluated'] = $step3Evaluated;
            }
            unset($vendorRow);
        }
        unset($payload);

        return $semakPayload;
    }

    /**
     * Dokumen sokongan penyata bank, keyed by vendor_id — never mix across syarikat.
     *
     * @param  array<int, array<string, mixed>>  $vendors
     * @param  array<int|string, mixed>  $vendorFormPayloads
     * @param  array<int, array<int, array<string, mixed>>>  $dokumenByVendor
     * @param  list<string>  $penyataUuids
     * @param  list<array<string, mixed>>  $legacyPenyataFiles
     * @return array<int, list<array<string, mixed>>>
     */
    protected function buildPenyataDokumenByVendor(
        Tender $tender,
        array $vendors,
        array $vendorFormPayloads,
        array $dokumenByVendor,
        array $penyataUuids,
        array $legacyPenyataFiles = []
    ): array {
        $byVendor = [];
        $uploaderVendorMap = $this->mapUploaderUserIdToVendorId($legacyPenyataFiles);

        foreach ($vendors as $vendor) {
            $vendorId = (int) ($vendor['vendor_id'] ?? 0);
            if ($vendorId <= 0) {
                continue;
            }

            $files = [];

            $payload = $vendorFormPayloads[$vendorId]
                ?? $vendorFormPayloads[(string) $vendorId]
                ?? null;
            if (is_array($payload)) {
                $files = array_merge($files, $this->extractFilesFromPenyataPayload($payload));
            }

            // Checklist uploads for penyata items only (vendor_content — never admin template files).
            foreach ($dokumenByVendor[$vendorId] ?? [] as $item) {
                $itemUuid = (string) ($item['uuid'] ?? '');
                if ($penyataUuids !== [] && ! in_array($itemUuid, $penyataUuids, true)) {
                    continue;
                }
                $itemFiles = $item['vendor_content']['files'] ?? [];
                if (is_array($itemFiles)) {
                    $files = array_merge($files, $itemFiles);
                }
            }

            // STOS may hold per-vendor files even when local payload has none.
            $files = array_merge($files, $this->fetchPenyataFilesFromStos($tender, $vendorId));

            // Legacy local penyata_bank_files: only if uploaded_by maps to this vendor.
            foreach ($legacyPenyataFiles as $file) {
                $uploaderId = (int) ($file['uploaded_by'] ?? 0);
                if ($uploaderId > 0 && ($uploaderVendorMap[$uploaderId] ?? null) === $vendorId) {
                    $files[] = $file;
                }
            }

            $byVendor[$vendorId] = $this->normalizePenyataDokumenFiles($files);
        }

        return $byVendor;
    }

    /**
     * @param  list<array<string, mixed>>  $legacyPenyataFiles
     * @return array<int, int> user_id => vendor_id
     */
    protected function mapUploaderUserIdToVendorId(array $legacyPenyataFiles): array
    {
        $userIds = collect($legacyPenyataFiles)
            ->pluck('uploaded_by')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($userIds === []) {
            return [];
        }

        return User::query()
            ->whereIn('id', $userIds)
            ->whereNotNull('vendor_id')
            ->pluck('vendor_id', 'id')
            ->map(fn ($vid) => (int) $vid)
            ->all();
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<array<string, mixed>>
     */
    protected function extractFilesFromPenyataPayload(array $payload): array
    {
        $files = [];
        $keys = ['dokumen_sokongan', 'files', 'attachments', 'dokumen', 'lampiran', 'penyata_bank_files', 'dokumen_penyata_bank', 'dokumens'];

        foreach ($keys as $key) {
            $val = $payload[$key] ?? null;
            if (is_array($val)) {
                if ($this->isFileMeta($val)) {
                    $files[] = $val;
                } else {
                    foreach ($val as $row) {
                        if (is_array($row) && $this->isFileMeta($row)) {
                            $files[] = $row;
                        }
                    }
                }
            }
        }

        foreach ($payload['accounts'] ?? [] as $account) {
            if (! is_array($account)) {
                continue;
            }
            foreach ($account['files'] ?? [] as $file) {
                if (is_array($file) && $this->isFileMeta($file)) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function fetchPenyataFilesFromStos(Tender $tender, int $vendorId): array
    {
        $stos = app(StosBackendClient::class);
        if (! $stos->isConfigured() || empty($tender->uuid)) {
            return [];
        }

        try {
            $path = 'penyata-banks/' . $tender->uuid;
            $url = StosBackendClient::apiUrl($path) . '?vendor_id=' . $vendorId;

            $response = StosBackendClient::http()->get($url);
            if (! $response->successful()) {
                return [];
            }

            $data = $response->json('data') ?? [];
            if (! is_array($data)) {
                return [];
            }

            // Reject payloads that belong to another vendor.
            $payloadVendor = (int) ($data['vendor_id'] ?? $data['vendorId'] ?? 0);
            if ($payloadVendor > 0 && $payloadVendor !== $vendorId) {
                return [];
            }

            return $this->extractFilesFromPenyataPayload($data);
        } catch (\Throwable $e) {
            Log::warning('Gagal ambil dokumen penyata bank dari STOS.', [
                'tender_id' => $tender->id,
                'vendor_id' => $vendorId,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    protected function isFileMeta(array $row): bool
    {
        return isset($row['uuid'])
            || isset($row['url'])
            || isset($row['path'])
            || isset($row['file_path'])
            || isset($row['original_name'])
            || isset($row['name'])
            || isset($row['filename']);
    }

    /**
     * @param  list<array<string, mixed>>  $files
     * @return list<array<string, mixed>>
     */
    protected function normalizePenyataDokumenFiles(array $files): array
    {
        $seen = [];
        $normalized = [];

        foreach ($files as $file) {
            if (! is_array($file) || ! $this->isFileMeta($file)) {
                continue;
            }

            $uuid = (string) ($file['uuid'] ?? '');
            $name = (string) ($file['name'] ?? $file['original_name'] ?? $file['filename'] ?? $file['title'] ?? 'Dokumen Sokongan');
            $path = ltrim(str_replace(['\\', 'public/'], ['/', ''], (string) ($file['path'] ?? $file['file_path'] ?? $file['filepath'] ?? '')), '/');
            $url = (string) ($file['url'] ?? '');

            if ($uuid !== '') {
                $url = route('tenderDokumen.download', $uuid);
            } elseif ($url === '' && $path !== '') {
                $url = route('tenderDokumen.streamByPath', array_filter([
                    'path' => $path,
                    'name' => $name,
                    'uuid' => $uuid !== '' ? $uuid : null,
                ]));
            }

            if ($url === '' || $url === '#') {
                continue;
            }

            $key = $uuid !== '' ? 'u:' . $uuid : 'p:' . $url . '|' . $name;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $normalized[] = [
                'uuid' => $uuid !== '' ? $uuid : null,
                'name' => $name,
                'original_name' => $name,
                'path' => $path !== '' ? $path : null,
                'url' => $url,
                'mime_type' => $file['mime_type'] ?? null,
            ];
        }

        return array_values($normalized);
    }

    /**
     * Build semakPayload keyed by checklist item UUID.
     *
     * @param  \App\Tender  $tender
     * @param  array<int, array<string, mixed>>  $teknikalItems
     * @param  array<int, array<string, mixed>>  $kewanganItems
     * @param  array<int, array<int, array<string, mixed>>>  $dokumenByVendor
     * @param  array<int, array{vendor_id: int, name: string, kod: string}>  $vendors
     * @return array<string, array<string, mixed>>
     */
    protected function buildSemakPayload(Tender $tender, array $teknikalItems, array $kewanganItems, array $dokumenByVendor, array $vendors): array
    {
        $payload = [];

        $profilPetenderConfig = \Illuminate\Support\Facades\DB::table('profil_petenders')
            ->where('tender_id', $tender->id)
            ->first();

        $profilScoringItems = [];
        if ($profilPetenderConfig) {
            $profilScoringItems = \Illuminate\Support\Facades\DB::table('profil_petender_scoring_items')
                ->where('profil_petender_id', $profilPetenderConfig->id)
                ->orderBy('jenis_skor')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('jenis_skor')
                ->all();
        }

        $vendorFormPayloads = \Illuminate\Support\Facades\DB::table('tender_vendor_form_payloads')
            ->where('tender_id', $tender->id)
            ->where('form_key', 'profil_petender')
            ->get()
            ->keyBy('vendor_id')
            ->map(fn ($r) => json_decode($r->payload, true))
            ->all();

        foreach (array_merge($teknikalItems, $kewanganItems) as $item) {
            $uuid = (string) ($item['uuid'] ?? '');
            if ($uuid === '') {
                continue;
            }

            $vendorRows = [];
            foreach ($vendors as $vendor) {
                $vendorId   = (int) $vendor['vendor_id'];
                $vendorItem = collect($dokumenByVendor[$vendorId] ?? [])
                    ->firstWhere('uuid', $uuid);

                $content = $vendorItem['vendor_content'] ?? [
                    'key_in'        => null,
                    'specification' => [],
                    'files'         => [],
                    'status'        => 'draft',
                ];
                $status  = $vendorItem['vendor_status'] ?? ($content['status'] ?? 'draft');
                $files   = $content['files'] ?? [];

                $summary = match ($item['action'] ?? '') {
                    'vendor_upload', 'download_upload' => count($files) > 0
                        ? count($files) . ' fail dimuat naik'
                        : 'Tiada fail',
                    'key_in' => filled($content['key_in'] ?? null)
                        ? 'Telah diisi'
                        : 'Belum diisi',
                    'view_specification' => ($status === 'submitted')
                        ? 'Spesifikasi dihantar'
                        : 'Belum dihantar',
                    'online_form' => ($status === 'submitted')
                        ? 'Borang dihantar'
                        : 'Belum dihantar',
                    default => ($status === 'submitted') ? 'Dihantar' : 'Belum dihantar',
                };

                $formUrl = $vendorItem['admin_content']['form']['url'] ?? ($item['admin_content']['form']['url'] ?? null);
                if ($formUrl && ($item['action'] ?? '') === 'online_form') {
                    $separator = str_contains($formUrl, '?') ? '&' : '?';
                    if (! str_contains($formUrl, 'vendor_id=')) {
                        $formUrl .= $separator . 'vendor_id=' . $vendorId;
                        $separator = '&';
                    }
                    if (! str_contains($formUrl, 'modal=1')) {
                        $formUrl .= $separator . 'modal=1';
                        $separator = '&';
                    }
                    if (! str_contains($formUrl, 'mode=view')) {
                        $formUrl .= $separator . 'mode=view';
                    }
                }
                if (($item['action'] ?? '') === 'view_specification') {
                    $formUrl = route('tenderDokumen.specificationForm', [
                        'tender'    => $tender->id,
                        'itemUuid'  => $uuid,
                        'vendor_id' => $vendorId,
                        'modal'     => 1,
                        'mode'      => 'view',
                        'summary'   => 'dokumentasi',
                    ]);
                }

                $itemHarga = $vendor['harga_tawaran'] ?? null;

                $techUuids = \Illuminate\Support\Facades\DB::table('technical_checklist_headers as tch')
                    ->join('technical_checklist_items as tci', 'tci.technical_checklist_header_id', '=', 'tch.id')
                    ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
                    ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
                    ->where('tch.tender_id', $tender->id)
                    ->select('tci.uuid as tci_uuid', 'tsd.uuid as tsd_uuid', 'sp.uuid as sp_uuid')
                    ->get();

                $searchItemUuids = array_unique(array_filter(array_merge(
                    [$uuid],
                    $techUuids->pluck('tci_uuid')->all(),
                    $techUuids->pluck('tsd_uuid')->all(),
                    $techUuids->pluck('sp_uuid')->all()
                )));

                $itemSpecResp = \App\Models\TenderVendorDokumenResponse::query()
                    ->where('tender_id', $tender->id)
                    ->where('vendor_id', $vendorId)
                    ->where('response_type', 'specification')
                    ->where(function ($q) use ($searchItemUuids) {
                        if (!empty($searchItemUuids)) {
                            $q->whereIn('checklist_item_uuid', $searchItemUuids);
                        }
                    })
                    ->first();

                if (!$itemSpecResp) {
                    $itemSpecResp = \App\Models\TenderVendorDokumenResponse::query()
                        ->where('tender_id', $tender->id)
                        ->where('vendor_id', $vendorId)
                        ->where('response_type', 'specification')
                        ->first();
                }

                if ($itemSpecResp) {
                    $itemPrices = $itemSpecResp->payload['item_prices'] ?? [];
                    if (is_array($itemPrices)) {
                        $specSum = 0.0;
                        $hasItemPrices = false;
                        foreach ($itemPrices as $val) {
                            if (is_numeric($val) && (float) $val > 0) {
                                $specSum += (float) $val;
                                $hasItemPrices = true;
                            }
                        }
                        if ($hasItemPrices) {
                            $itemHarga = $specSum;
                        }
                    }
                }

                $itemHargaFmt = $itemHarga ? number_format((float) $itemHarga, 2) : '-';

                $vendorRows[] = [
                    'vendor_id'         => $vendorId,
                    'name'              => $vendor['name'],
                    'kod'               => $vendor['kod'],
                    'is_bumiputera'     => $vendor['is_bumiputera'] ?? null,
                    'bumiputera_status' => $vendor['bumiputera_status'] ?? '-',
                    'harga_tawaran'     => $itemHarga,
                    'harga_tawaran_fmt' => $itemHargaFmt,
                    'item_prices'       => $itemSpecResp ? ($itemSpecResp->payload['item_prices'] ?? []) : [],
                    'status'            => $status,
                    'status_label'      => $status === 'submitted' ? 'Hantar' : 'Menunggu',
                    'summary'           => $summary,
                    'files'             => $files,
                    'form_url'          => $formUrl,
                    'form_key'          => $item['admin_content']['form']['form_key'] ?? null,
                    'status_pematuhan'  => null,
                    'catatan'           => null,
                ];
            }

            $maxScore = 0.0;
            $spesifikasiDetail = null;
            if (($item['action'] ?? '') === 'view_specification' || ($item['mechanism'] ?? '') === 'spesifikasi' || in_array($item['source_type'] ?? '', ['specification', 'specification_document'], true)) {
                $rows = $item['admin_content']['rows'] ?? [];

                $pricingMap = [];
                $techItem = \Illuminate\Support\Facades\DB::table('financial_checklist_items as fci')
                    ->leftJoin('technical_checklist_items as tci', 'tci.id', '=', 'fci.technical_item_id')
                    ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
                    ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
                    ->where('fci.uuid', $uuid)
                    ->orWhere('tci.uuid', $uuid)
                    ->orWhere('sp.uuid', $uuid)
                    ->orWhere('tsd.uuid', $uuid)
                    ->select(
                        'sp.id as sp_id',
                        'sp.anggaran_jabatan',
                        'fci.score as fci_score',
                        'tci.id as tci_id',
                        'tci.specification_document_id',
                        'tci.score as tci_score',
                        'tsd.id as tsd_id',
                        'tsd.total_score as doc_total_score'
                    )
                    ->first();

                $specDocId = $techItem?->specification_document_id ?? $techItem?->tsd_id ?? null;

                // Fallback: If specDocId is still null, look up technical_checklist_items for this tender
                if (!$specDocId) {
                    $fallbackTci = \Illuminate\Support\Facades\DB::table('technical_checklist_headers as tch')
                        ->join('technical_checklist_items as tci', 'tci.technical_checklist_header_id', '=', 'tch.id')
                        ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
                        ->where('tch.tender_id', $tender->id)
                        ->where(function ($q) {
                            $q->where('tci.source_type', 'specification_document')
                              ->orWhere('tci.mechanism', 'spesifikasi');
                        })
                        ->select('tci.id as tci_id', 'tci.specification_document_id', 'sp.id as sp_id', 'sp.anggaran_jabatan')
                        ->first();

                    if ($fallbackTci) {
                        $specDocId = $fallbackTci->specification_document_id;
                        if ($techItem) {
                            $techItem->sp_id = $fallbackTci->sp_id;
                            if (empty($techItem->anggaran_jabatan)) {
                                $techItem->anggaran_jabatan = $fallbackTci->anggaran_jabatan;
                            }
                        }
                    }
                }

                // If specDocId is found and rows are empty or contain only 1 generic fallback row, build full items & details tree
                if ($specDocId && (empty($rows) || count($rows) <= 1)) {
                    $specItems = \App\Models\TechnicalSpecificationItem::query()
                        ->with('details')
                        ->where('technical_specification_document_id', $specDocId)
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->get();

                    if ($specItems->isNotEmpty()) {
                        $builtRows = [];
                        $itemIndex = 0;
                        foreach ($specItems as $sItem) {
                            $itemIndex++;
                            $builtRows[] = [
                                'kind'          => 'item',
                                'bil'           => (string) $itemIndex,
                                'id'            => $sItem->id,
                                'item_uuid'     => $sItem->uuid,
                                'spec_item_id'  => $sItem->id,
                                'title'         => $sItem->title,
                                'quantity'      => $sItem->quantity,
                                'unit'          => $sItem->unit,
                                'response_type' => null,
                            ];

                            $detailIndex = 0;
                            foreach ($sItem->details as $sDetail) {
                                $detailIndex++;
                                $builtRows[] = [
                                    'kind'          => 'detail',
                                    'bil'           => $itemIndex . '.' . $detailIndex,
                                    'id'            => $sDetail->id,
                                    'item_uuid'     => $sItem->uuid,
                                    'detail_uuid'   => $sDetail->uuid,
                                    'spec_item_id'  => $sItem->id,
                                    'title'         => $sDetail->description,
                                    'quantity'      => null,
                                    'unit'          => null,
                                    'response_type' => $sDetail->response_type,
                                ];
                            }
                        }
                        if (!empty($builtRows)) {
                            $rows = $builtRows;
                        }
                    }
                }

                if ($specDocId) {
                    $detailsSum = (float) \Illuminate\Support\Facades\DB::table('technical_specification_items as tsi')
                        ->join('technical_specification_details as tsd_dt', 'tsd_dt.technical_specification_item_id', '=', 'tsi.id')
                        ->where('tsi.technical_specification_document_id', $specDocId)
                        ->sum('tsd_dt.max_score');

                    if ($detailsSum > 0) {
                        $maxScore = $detailsSum;
                    } elseif ($techItem && $techItem->doc_total_score && (float) $techItem->doc_total_score > 0) {
                        $maxScore = (float) $techItem->doc_total_score;
                    }
                }

                if ($maxScore <= 0 && $techItem) {
                    if ((float) ($techItem->fci_score ?? 0) > 0) {
                        $maxScore = (float) $techItem->fci_score;
                    } elseif ((float) ($techItem->tci_score ?? 0) > 0) {
                        $maxScore = (float) $techItem->tci_score;
                    }
                }

                if ($maxScore <= 0) {
                    $maxScore = 100.0;
                }

                $spId = $techItem?->sp_id ?? null;
                if (!$spId) {
                    $spId = \Illuminate\Support\Facades\DB::table('specification_pricings')
                        ->where('tender_id', $tender->id)
                        ->orWhereIn('technical_checklist_item_id', function ($q) use ($tender) {
                            $q->select('tci.id')
                                ->from('technical_checklist_items as tci')
                                ->join('technical_checklist_headers as tch', 'tch.id', '=', 'tci.technical_checklist_header_id')
                                ->where('tch.tender_id', $tender->id);
                        })
                        ->value('id');
                }

                if ($spId) {
                    $pricingMap = \Illuminate\Support\Facades\DB::table('specification_pricing_items as spi')
                        ->leftJoin('technical_specification_items as tsi', 'tsi.id', '=', 'spi.spec_item_id')
                        ->where('spi.specification_pricing_id', $spId)
                        ->select('spi.*', 'tsi.uuid as tsi_uuid')
                        ->get()
                        ->flatMap(function ($spi) {
                            $val = [
                                'harga'    => (float) ($spi->harga ?? 0),
                                'kuantiti' => (float) ($spi->kuantiti ?? 0),
                            ];
                            $map = [
                                (string) $spi->spec_item_id => $val,
                                (string) $spi->uuid         => $val,
                                (string) $spi->id           => $val,
                            ];
                            if (!empty($spi->tsi_uuid)) {
                                $map[(string) $spi->tsi_uuid] = $val;
                            }
                            return $map;
                        })
                        ->all();
                }

                $spesifikasiDetail = [
                    'document_title'   => $item['admin_content']['document_title'] ?? ($item['title'] ?? '-'),
                    'anggaran_jabatan' => (float) ($techItem?->anggaran_jabatan ?? $tender->anggaran_jabatan ?? 0),
                    'rows'             => $rows,
                    'pricing_items'    => $pricingMap,
                    'max_score'        => $maxScore,
                ];
            }

            if ($maxScore <= 0) {
                $checkItemScore = (float) \Illuminate\Support\Facades\DB::table('financial_checklist_items as fci')
                    ->leftJoin('technical_checklist_items as tci', 'tci.id', '=', 'fci.technical_item_id')
                    ->where('fci.uuid', $uuid)
                    ->orWhere('tci.uuid', $uuid)
                    ->value(\Illuminate\Support\Facades\DB::raw('COALESCE(fci.score, tci.score, 0)'));

                if ($checkItemScore > 0) {
                    $maxScore = $checkItemScore;
                } elseif (isset($item['score']) && (float) $item['score'] > 0) {
                    $maxScore = (float) $item['score'];
                } else {
                    $maxScore = 10.0;
                }
            }

            $profilPetenderDetail = null;
            $titleLower = strtolower(trim($item['title'] ?? $item['nama'] ?? ''));
            $formKey = strtolower(trim($item['admin_content']['form']['form_key'] ?? ''));

            if (($item['action'] ?? '') === 'online_form' || $formKey === 'profil_petender' || str_contains($titleLower, 'profil petender')) {
                $modalBerbayarCollection = collect($profilScoringItems['modal_berbayar'] ?? []);
                $modalDibenarkanCollection = collect($profilScoringItems['modal_dibenarkan'] ?? []);

                $maxBerbayar = $modalBerbayarCollection->max(fn ($i) => (float) ($i->skema ?? 0)) ?: 0.0;
                $maxDibenarkan = $modalDibenarkanCollection->max(fn ($i) => (float) ($i->skema ?? 0)) ?: 0.0;

                $profilPetenderDetail = [
                    'config'                 => $profilPetenderConfig,
                    'modal_berbayar_items'   => $modalBerbayarCollection->values()->all(),
                    'max_modal_berbayar'     => $maxBerbayar,
                    'modal_dibenarkan_items' => $modalDibenarkanCollection->values()->all(),
                    'max_modal_dibenarkan'   => $maxDibenarkan,
                    'vendor_payloads'        => $vendorFormPayloads,
                ];
            }

            $submittedCount = collect($vendorRows)
                ->where('status', 'submitted')
                ->count();

            $payload[$uuid] = [
                'uuid'                   => $uuid,
                'title'                  => $item['title'] ?? $item['nama'] ?? '-',
                'action'                 => $item['action'] ?? '',
                'tindakan'               => $item['tindakan'] ?? '-',
                'submitted_count'        => $submittedCount,
                'vendor_count'           => count($vendors),
                'max_score'              => $maxScore,
                'spesifikasi_detail'     => $spesifikasiDetail,
                'profil_petender_detail' => $profilPetenderDetail,
                'vendors'                => $vendorRows,
            ];
        }

        return $payload;
    }

    /**
     * Calculate summary matrix for all participating vendors across all 3 steps for Step 4 Report.
     *
     * @param Tender $tender
     * @param array $kewanganItems
     * @param array $penyataBankItems
     * @param array $semakPayload
     * @param array $rumusanStep3Data
     * @return array<int, array<string, mixed>>
     */
    protected function calculateStep4Rumusan(Tender $tender, array $kewanganItems, array $penyataBankItems, array $semakPayload, array $rumusanStep3Data): array
    {
        $penyataUuids = collect($penyataBankItems)->pluck('uuid')->filter()->all();
        $kewanganUuids = collect($kewanganItems)->pluck('uuid')->filter()->all();

        $evaluations = $this->loadEvaluations($tender);
        $participants = $tender->participants()->with('vendor')->where('participate', 1)->get();

        $step3PassedMap = collect($rumusanStep3Data['pembekal_melepasi'] ?? [])->keyBy('vendor_id');
        $step3FailedMap = collect($rumusanStep3Data['pembekal_tidak_melepasi'] ?? [])->keyBy('vendor_id');

        $summaryList = [];

        foreach ($participants as $p) {
            $vId = (int) $p->vendor_id;
            $kod = $p->kod_pembekal ?: ($p->vendor?->kod_pembekal ?? ('Vendor #' . $vId));
            $name = $p->vendor?->name ?: ('Vendor #' . $vId);

            // Step 1: Pematuhan Dokumentasi
            $step1Status = 'melepasi';
            $step1EvaluatedCount = 0;
            $totalStep1Count = count($kewanganUuids);

            if ($totalStep1Count > 0) {
                foreach ($kewanganUuids as $uuid) {
                    $evalKey = "{$vId}:{$uuid}";
                    $eval = $evaluations[$evalKey] ?? null;
                    if ($eval) {
                        $step1EvaluatedCount++;
                        if ((int) $eval->status_pematuhan === 0) {
                            $step1Status = 'tidak_melepasi';
                        }
                    }
                }
                if ($step1Status !== 'tidak_melepasi' && $step1EvaluatedCount < $totalStep1Count) {
                    $step1Status = 'belum_dinilai';
                }
            } else {
                $step1Status = 'melepasi';
            }

            // Step 2: Penyata Bulanan Bank
            $step2Status = '-';
            if ($step1Status === 'melepasi') {
                $step2Status = 'melepasi';
                $step2EvaluatedCount = 0;
                $totalStep2Count = count($penyataUuids);

                if ($totalStep2Count > 0) {
                    foreach ($penyataUuids as $uuid) {
                        $evalKey = "{$vId}:{$uuid}";
                        $eval = $evaluations[$evalKey] ?? null;
                        if ($eval) {
                            $step2EvaluatedCount++;
                            if ((int) $eval->status_pematuhan === 0) {
                                $step2Status = 'tidak_melepasi';
                            }
                        }
                    }
                    if ($step2Status !== 'tidak_melepasi' && $step2EvaluatedCount < $totalStep2Count) {
                        $step2Status = 'belum_dinilai';
                    }
                }
            } elseif ($step1Status === 'tidak_melepasi') {
                $step2Status = '-';
            }

            // Step 3: Pematuhan Spesifikasi Kewangan
            $step3Status = '-';
            if ($step1Status === 'melepasi' && $step2Status === 'melepasi') {
                if (isset($step3PassedMap[$vId])) {
                    $step3Status = 'melepasi';
                } elseif (isset($step3FailedMap[$vId])) {
                    $step3Status = 'tidak_melepasi';
                } else {
                    $step3Status = 'belum_dinilai';
                }
            } else {
                $step3Status = '-';
            }

            // Final eligibility rule
            $isLayak = ($step1Status === 'melepasi' && $step2Status === 'melepasi' && $step3Status === 'melepasi');

            $summaryList[] = [
                'vendor_id'    => $vId,
                'kod'          => $kod,
                'name'         => $name,
                'step1_status' => $step1Status, // 'melepasi', 'tidak_melepasi', 'belum_dinilai'
                'step2_status' => $step2Status, // 'melepasi', 'tidak_melepasi', 'belum_dinilai', '-'
                'step3_status' => $step3Status, // 'melepasi', 'tidak_melepasi', 'belum_dinilai', '-'
                'is_layak'     => $isLayak,
                'keputusan'    => $isLayak ? 'Layak' : 'Tidak Layak',
            ];
        }

        return $summaryList;
    }

    /**
     * AJAX: Save draft report Pengesyoran / Catatan
     */
    public function simpanLaporanDraft(Request $request): JsonResponse
    {
        $request->validate([
            'tender'                  => 'required|string',
            'catatan_peringkat1'     => 'nullable|string|max:5000',
            'catatan_peringkat2'     => 'nullable|string|max:5000',
            'catatan_peringkat3'     => 'nullable|string|max:5000',
            'pengesyoran_justifikasi' => 'nullable|array',
            'pengesyoran_justifikasi.*' => 'nullable|string|max:2000',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $justifikasiList = collect($request->input('pengesyoran_justifikasi', []))
            ->map(fn ($val) => trim((string) $val))
            ->filter(fn ($val) => $val !== '')
            ->values()
            ->all();

        $laporan = TenderKewanganLaporan::query()->firstOrNew([
            'tender_id' => $tender->id,
        ]);

        $laporan->fill([
            'catatan_peringkat1'     => $request->input('catatan_peringkat1'),
            'catatan_peringkat2'     => $request->input('catatan_peringkat2'),
            'catatan_peringkat3'     => $request->input('catatan_peringkat3'),
            'pengesyoran_justifikasi' => $justifikasiList,
            'updated_by'              => Auth::id(),
        ]);

        if (! $laporan->exists) {
            $laporan->created_by = Auth::id();
            $laporan->status = 'draft';
        }

        $laporan->save();

        return response()->json(['message' => 'Draft laporan penilaian kewangan telah disimpan.']);
    }

    /**
     * Submit final Penilaian Kewangan report, determine final vendor eligibility, update process.
     */
    public function hantar(Request $request): JsonResponse
    {
        $request->validate([
            'tender'                  => 'required|string',
            'catatan_peringkat1'     => 'nullable|string|max:5000',
            'catatan_peringkat2'     => 'nullable|string|max:5000',
            'catatan_peringkat3'     => 'nullable|string|max:5000',
            'pengesyoran_justifikasi' => 'nullable|array',
            'pengesyoran_justifikasi.*' => 'nullable|string|max:2000',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus()) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian kewangan (status ' . TenderProcessStatus::penilaianKewanganListStatus() . ').',
            ], 422);
        }

        $userId = Auth::id();
        $now = now();

        $justifikasiList = collect($request->input('pengesyoran_justifikasi', []))
            ->map(fn ($val) => trim((string) $val))
            ->filter(fn ($val) => $val !== '')
            ->values()
            ->all();

        DB::transaction(function () use ($tender, $request, $justifikasiList, $userId, $now) {
            // 1. Save / Finalize Report
            $laporan = TenderKewanganLaporan::query()->firstOrNew([
                'tender_id' => $tender->id,
            ]);

            $laporan->fill([
                'catatan_peringkat1'     => $request->input('catatan_peringkat1'),
                'catatan_peringkat2'     => $request->input('catatan_peringkat2'),
                'catatan_peringkat3'     => $request->input('catatan_peringkat3'),
                'pengesyoran_justifikasi' => $justifikasiList,
                'status'                  => 'submitted',
                'submitted_at'            => $now,
                'submitted_by'            => $userId,
                'updated_by'              => $userId,
            ]);

            if (! $laporan->exists) {
                $laporan->created_by = $userId;
            }
            $laporan->save();

            // 2. Update Progress
            $progress = TenderKewanganProgress::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['current_step' => 1]
            );
            $progress->current_step = 4;
            $progress->save();

            // 3. Determine Final Eligible Vendors & Update Vendor Process Status
            $tenderDokumen = TenderDokumenPresenter::for($tender);
            $checklistItems = $tenderDokumen->items('admin');
            $isNotMuatTurun = fn (array $item) => strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? '')) !== 'muat turun';

            $penyataBankUrls = \App\Models\StandardChecklistItem::query()
                ->where(function ($q) {
                    $q->where('action_url', 'like', '%penyata-bank%')
                      ->orWhere('title', 'like', '%penyata bank%')
                      ->orWhere('title', 'like', '%penyata bulanan%');
                })
                ->pluck('action_url')
                ->filter()
                ->toArray();

            $isNotPenyataBank = function (array $item) use ($penyataBankUrls) {
                $title = strtolower(trim($item['title'] ?? $item['nama'] ?? ''));
                $actionUrl = strtolower(trim($item['admin_content']['form']['url'] ?? $item['action_url'] ?? ''));
                $formKey = strtolower(trim($item['admin_content']['form']['form_key'] ?? ''));

                if (str_contains($title, 'penyata bank') || str_contains($title, 'penyata bulanan bank') || $formKey === 'penyata_bank') {
                    return false;
                }

                foreach ($penyataBankUrls as $url) {
                    if ($actionUrl !== '' && str_contains($actionUrl, strtolower($url))) {
                        return false;
                    }
                }

                return true;
            };

            $kewanganItems = collect($checklistItems)
                ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
                ->filter($isNotMuatTurun)
                ->filter($isNotPenyataBank)
                ->values()
                ->all();

            $penyataBankItems = collect($checklistItems)
                ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
                ->filter($isNotMuatTurun)
                ->filter(fn (array $item) => ! $isNotPenyataBank($item))
                ->values()
                ->all();

            $allStepItems = array_merge($kewanganItems, $penyataBankItems);
            $participants = $tender->participants()->with('vendor')->where('participate', 1)->get();

            $vendors = $participants->map(fn ($p) => [
                'vendor_id' => (int) $p->vendor_id,
                'name'      => $p->vendor?->name ?: ('Vendor #' . $p->vendor_id),
                'kod'       => $p->kod_pembekal ?: null,
            ])->all();

            $semakPayload = $this->buildSemakPayload($tender, [], $allStepItems, [], $vendors);
            $rumusan3 = $this->calculateStep3Rumusan($tender, $semakPayload);
            $summary4 = $this->calculateStep4Rumusan($tender, $kewanganItems, $penyataBankItems, $semakPayload, $rumusan3);

            foreach ($participants as $participant) {
                $vId = (int) $participant->vendor_id;
                $vSummary = collect($summary4)->firstWhere('vendor_id', $vId);

                $isLayak = $vSummary['is_layak'] ?? false;

                if ($isLayak) {
                    // Passed all 3 required financial evaluation stages -> Proceed to next process
                    $participant->update([
                        'cancel_fg'            => 0,
                        'eliminated_process_id' => null,
                        'eliminated_reason'     => null,
                        'eliminated_at'         => null,
                    ]);
                } else {
                    // Failed at least one stage -> Disqualified/Eliminated from advancing
                    $failedStages = [];
                    if (($vSummary['step1_status'] ?? '') === 'tidak_melepasi') {
                        $failedStages[] = 'Pematuhan Dokumentasi';
                    }
                    if (($vSummary['step2_status'] ?? '') === 'tidak_melepasi') {
                        $failedStages[] = 'Penyata Bulanan Bank';
                    }
                    if (($vSummary['step3_status'] ?? '') === 'tidak_melepasi') {
                        $failedStages[] = 'Pematuhan Spesifikasi Kewangan';
                    }

                    $reasonTxt = count($failedStages) > 0
                        ? 'Tidak melepasi penilaian kewangan peringkat: ' . implode(', ', $failedStages)
                        : 'Tidak melepasi keseluruhan penilaian kewangan.';

                    $participant->eliminate(TenderProcessStatus::PENILAIAN_KEWANGAN, $reasonTxt);
                }
            }

            // 4. Update Tender Process status -> 11
            $this->advanceTenderProcess($tender, TenderProcessStatus::PENILAIAN_KEWANGAN, TenderProcessStatus::penilaianKewanganListStatus());
        });

        return response()->json(['message' => 'Penilaian kewangan berjaya dihantar.']);
    }
}
