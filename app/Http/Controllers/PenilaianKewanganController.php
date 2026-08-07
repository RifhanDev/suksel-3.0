<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\TenderKewanganEvaluation;
use App\Models\TenderKewanganProgress;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianKewanganController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index(Request $request)
    {
        $query = Tender::query()
            ->where('status_process_id', TenderProcessStatus::penilaianKewanganListStatus());

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
                ->get(['id', 'uuid', 'no_tender', 'ref_number', 'name', 'submission_datetime', 'status_process_id']),
            fn (Tender $tender, string $noTender) => route('penilaianKewangan.show', $noTender)
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

            $vendors = $participants->map(function ($participant) {
                return [
                    'vendor_id' => (int) $participant->vendor_id,
                    'name'      => $participant->vendor?->name ?: ('Vendor #' . $participant->vendor_id),
                    'kod'       => $participant->kod_pembekal ?: null,
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
                } else if ($totalStep1Items > 0 && $passedCount === $totalStep1Items) {
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

        $progress = null;
        if ($tender) {
            $progress = TenderKewanganProgress::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['current_step' => 1]
            );
        }

        return view('newModule.penilaian_kewangan.show', compact(
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
            'semakPayload',
            'pembekalMelepasi',
            'pembekalTidakMelepasi',
            'pembekalBelumDinilai',
            'penyataBankConfig',
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

        $isStep2Item = ($stepParam === 2);

        if (! $isStep2Item) {
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

        if ($isStep2Item) {
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

        $record = TenderKewanganEvaluation::query()->firstOrNew([
            'tender_id'           => $tender->id,
            'vendor_id'           => (int) $request->input('vendor_id'),
            'checklist_item_uuid' => (string) $request->input('checklist_item_uuid'),
        ]);

        $record->fill([
            'status_pematuhan' => $statusInt,
            'catatan'          => ($statusInt === 0) ? trim((string) ($request->input('catatan') ?? '')) : $request->input('catatan'),
            'skor'             => (float) $request->input('skor', 0),
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
                'step4' => $progress->isStepUnlocked(4),
            ],
        ]);
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
            foreach ($payload['vendors'] as &$vendorRow) {
                $vendorId = (int) $vendorRow['vendor_id'];
                $evalKey  = "{$vendorId}:{$uuid}";
                $eval     = $evaluations[$evalKey] ?? null;

                $vendorRow['status_pematuhan'] = $eval ? ($eval->status_pematuhan === 1 ? 'mematuhi' : 'tidak_mematuhi') : null;
                $vendorRow['catatan']          = $eval ? $eval->catatan : null;
                $vendorRow['skor']             = $eval ? (float) ($eval->skor ?? 0) : 0;
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
                    ]);
                }

                $vendorRows[] = [
                    'vendor_id'    => $vendorId,
                    'name'         => $vendor['name'],
                    'kod'          => $vendor['kod'],
                    'status'       => $status,
                    'status_label' => $status === 'submitted' ? 'Hantar' : 'Menunggu',
                    'summary'      => $summary,
                    'files'        => $files,
                    'form_url'     => $formUrl,
                    'form_key'     => $item['admin_content']['form']['form_key'] ?? null,
                    'status_pematuhan' => null,
                    'catatan'          => null,
                ];
            }

            $submittedCount = collect($vendorRows)
                ->where('status', 'submitted')
                ->count();

            $payload[$uuid] = [
                'uuid'            => $uuid,
                'title'           => $item['title'] ?? $item['nama'] ?? '-',
                'action'          => $item['action'] ?? '',
                'tindakan'        => $item['tindakan'] ?? '-',
                'submitted_count' => $submittedCount,
                'vendor_count'    => count($vendors),
                'vendors'         => $vendorRows,
            ];
        }

        return $payload;
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENILAIAN_KEWANGAN,
            TenderProcessStatus::penilaianKewanganListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian kewangan (status ' . TenderProcessStatus::penilaianKewanganListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Penilaian kewangan berjaya dihantar.']);
    }
}
