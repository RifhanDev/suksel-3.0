<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Models\TenderKewanganEvaluation;
use App\Models\TenderKewanganLaporan;
use App\Models\TenderKewanganProgress;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianKewanganController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;
    use RestrictsTenderByRole;

    public function __construct()
    {
        $this->menuMiddleware('FinancialEvaluation:list');
    }

    public function index(Request $request)
    {
        $query = $this->applyCommitteeAppointment(
            Tender::query()->where('status_process_id', TenderProcessStatus::penilaianKewanganListStatus()),
            'fin'
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

        $this->assertCommitteeAppointment($tender, 'fin');

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
        $vendors = [];
        $dokumenByVendor = [];
        $semakPayload = [];
        $pembekalMelepasi = [];
        $pembekalTidakMelepasi = [];
        $pembekalBelumDinilai = [];

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

        $penyataBankRecord = \App\Models\PenyataBank::query()
            ->with(['bulans', 'scoringItems', 'files'])
            ->where('tender_id', $tender->id)
            ->first();

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

        $penyataBankFiles = \Illuminate\Support\Facades\DB::table('penyata_bank_files')
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
            ->all();

        if (! isset($penyataBankConfig['files']) || ! is_array($penyataBankConfig['files'])) {
            $penyataBankConfig['files'] = [];
        }
        $penyataBankConfig['files'] = array_merge($penyataBankConfig['files'], $penyataBankFiles);

        $vendorFormPayloads = \Illuminate\Support\Facades\DB::table('tender_vendor_form_payloads')
            ->where('tender_id', $tender->id)
            ->where('form_key', 'penyata_bank')
            ->get()
            ->keyBy('vendor_id')
            ->map(fn ($r) => json_decode($r->payload, true))
            ->all();

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
            'rumusanStep3Data',
            'rumusanLaporanData',
            'laporanRecord',
            'readOnly',
            'progress'
        ));
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

        if ($request->has('skor_modal_berbayar') || $request->has('skor_modal_dibenarkan')) {
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

        $record->fill([
            'status_pematuhan' => $statusInt,
            'catatan'          => $catatanSave,
            'skor'             => $skorInput,
            'updated_by'       => Auth::id(),
        ]);

        if (! $record->exists) {
            $record->created_by = Auth::id();
        }

        $record->save();

        return response()->json([
            'message'          => 'Penilaian pematuhan kewangan telah disimpan.',
            'status_pematuhan' => $record->status_pematuhan,
            'catatan'          => $record->catatan,
            'skor'             => $record->skor,
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
                        $step3Evaluated = ($vendorRow['skor_modal_berbayar'] !== null && $vendorRow['skor_modal_berbayar'] !== '');
                    } elseif ($isSpesifikasi) {
                        $step3Evaluated = ($vendorRow['skor'] !== null && (float) $vendorRow['skor'] > 0);
                    } else {
                        // Muat Naik: Step 1 saves skor=0. Step 3 saves skor=max_score (>0).
                        $step3Evaluated = ($vendorRow['skor'] !== null && (float) $vendorRow['skor'] > 0);
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
