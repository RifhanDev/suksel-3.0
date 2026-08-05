<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\Jawatankuasa;
use App\Models\PenyediaanIklan;
use App\Models\PenyediaanMesyuaratMeeting;
use App\Models\TechnicalChecklistHeader;
use App\Models\TechnicalChecklistItem;
use App\Models\TenderTeknikalKerjaLampiran;
use App\Models\TenderTeknikalPematuhanEvaluation;
use App\Services\OnlineFormStatusService;
use App\Services\StosBackendClient;
use App\Services\TeknikalEvaluationService;
use App\Services\TeknikalKerjaEvaluationService;
use App\Services\VendorDokumenResponseService;
use App\Support\ChecklistMechanism;
use App\Support\TenderDokumenActionResolver;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PenilaianTeknikalController extends Controller
{
    use AdvancesTenderProcessStatus;
    use HandlesTenderFormAccess;
    use ResolvesTenderForProcess;

    public function __construct(
        protected TeknikalEvaluationService $service,
        protected TeknikalKerjaEvaluationService $kerjaService,
        protected StosBackendClient $stos
    ){}

    // Bekalan/perkhidmatan flow — methods below serve the active 'penilaianTeknikal*' routes.

    /** DataTables feed for the Penilaian Teknikal list; index() below is unrouted. */
    public function indexDatatable(Request $request)
    {
        if ($request->ajax()) {
            return $this->indexAjax($request);
        }

        return view('newModule.penilaian_teknikal.teknikal_index');
    }

    protected function indexAjax(Request $request)
    {
        $tenders = Tender::query()
            // CUT_OFF = pending; PENILAIAN_TEKNIKAL = just submitted — kept so evaluators can
            // still reopen a completed review instead of it vanishing from the list.
            ->whereIn('status_process_id', [
                TenderProcessStatus::penilaianTeknikalListStatus(),
                TenderProcessStatus::PENILAIAN_TEKNIKAL,
            ])
            ->where(function ($query) {
                $query->where('is_ebidding', false)
                    ->orWhereNull('is_ebidding');
            }); // e-bidding tenders use a different legacy stage mapping — excluded.

        // Only when the user hasn't clicked a column header — otherwise this would
        // out-rank whatever column DataTables just asked to sort by. Request::filled()
        // treats any array (even an empty one) as "filled", so check emptiness directly.
        if (blank($request->input('order'))) {
            $tenders->orderByDesc('created_at');
        }

        if ($request->filled('filter_no_tender')) {
            $filterNoTender = trim((string) $request->input('filter_no_tender'));

            $tenders->where(function ($query) use ($filterNoTender) {
                $query->where('no_tender', 'like', '%' . $filterNoTender . '%')
                    ->orWhere('ref_number', 'like', '%' . $filterNoTender . '%');
            });
        }

        if ($request->filled('filter_tajuk')) {
            $filterTajuk = trim((string) $request->input('filter_tajuk'));
            $tenders->where('name', 'like', '%' . $filterTajuk . '%');
        }

        if ($request->filled('filter_status')) {
            // Status is computed from evaluation data, not a SQL column — filtered in memory
            // over this stage's tenders only, not the whole table.
            $filterStatus = (string) $request->input('filter_status');

            $matchingIds = (clone $tenders)
                ->get(['id', 'kategori_perolehan_id'])
                ->filter(fn (Tender $t) => $this->resolveTenderRingkasanStatus($t) === $filterStatus)
                ->pluck('id');

            $tenders->whereIn('id', $matchingIds);
        }

        if ($request->filled('filter_tarikh')) {
            try {
                $filterDate = Carbon::createFromFormat('d/m/Y', (string) $request->input('filter_tarikh'))
                    ->format('Y-m-d');

                $tenders->whereDate('submission_datetime', $filterDate);
            } catch (\Throwable $th) {
                // Ignore invalid date input, keep the query usable.
            }
        }

        return Datatables::of($tenders)
            ->editColumn('no_tender', function ($tender) {
                $noTender = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;

                return e($noTender);
            })
            ->editColumn('name', function ($tender) {
                return e($tender->name ?: '-');
            })
            ->editColumn('submission_datetime', function ($tender) {
                return !empty($tender->submission_datetime)
                    ? Carbon::parse($tender->submission_datetime)->format('d/m/Y')
                    : '-';
            })
            ->editColumn('created_at', function ($tender) {
                return !empty($tender->created_at)
                    ? Carbon::parse($tender->created_at)->format('d/m/Y H:i')
                    : '-';
            })
            ->addColumn('status_label', function ($tender) {
                $label = $this->resolveTenderRingkasanStatus($tender);

                $badgeClass = match ($label) {
                    'Belum Dinilai' => 'badge-status-neutral',
                    'Selesai' => 'badge-status-success',
                    default => 'badge-status-warning',
                };

                return '<div class="text-center"><span class="badge-status ' . $badgeClass . '">' . e($label) . '</span></div>';
            })
            ->addColumn('tindakan', function ($tender) {
                // kategori_perolehan_id 1/2 = bekalan/perkhidmatan (3-step), 3 = kerja.
                $showUrl = (int) $tender->kategori_perolehan_id === 3
                    ? route('penilaianTeknikalKerja.show', $tender->uuid)
                    : route('penilaianTeknikal.show', $tender->uuid);

                return '<a href="' . e($showUrl) . '" class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1">'
                    . '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'
                    . '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>'
                    . ' Lihat</a>';
            })
            ->rawColumns(['status_label', 'tindakan'])
            ->make(true);
    }

    /**
     * Overall tender status for the index list: Belum Dinilai, Dalam Proses, or Selesai.
     * Independent of status_process_id, which stays constant across this filtered list.
     */
    private function resolveTenderRingkasanStatus(Tender $tender): string
    {
        if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
            $vendors = $this->kerjaService->loadShortlistedVendors($tender);
            $total = $vendors->count();

            if ($total === 0) {
                return 'Belum Dinilai';
            }

            $evaluations = $this->kerjaService->loadEvaluations($tender);
            $evaluatedCount = $vendors->filter(fn (TenderVendor $v) => isset($evaluations[$v->vendor_id]))->count();

            if ($evaluatedCount === 0) {
                return 'Belum Dinilai';
            }

            return $evaluatedCount === $total ? 'Selesai' : 'Dalam Proses';
        }

        $vendors = $this->loadPenilaianTeknikalVendors($tender);
        $checklistItems = $this->loadStep1ChecklistItems($tender);

        if ($vendors->isEmpty() || $checklistItems->isEmpty()) {
            return 'Belum Dinilai';
        }

        $pematuhanEvaluations = $this->service->loadPematuhanEvaluations($tender);
        $step1Items = $this->mapTechnicalChecklistItems($checklistItems, $tender, $vendors, $pematuhanEvaluations);

        $step2Vendors = $this->loadPematuhanLulusVendors($tender);
        $structure = $this->service->loadSpecificationStructure($checklistItems);
        $spesifikasiEvaluations = $this->service->loadSpesifikasiEvaluations($tender);
        $borangEvaluations = $this->service->loadBorangEvaluations($tender);
        $step2Items = $this->mapStep2ChecklistItems($checklistItems, $step2Vendors, $structure, $spesifikasiEvaluations, $borangEvaluations);

        $allItems = $step1Items->concat($step2Items);

        $hasProgress = $allItems->contains(fn (array $item) => $item['status_label'] !== 'Menunggu Penilaian');
        if (! $hasProgress) {
            return 'Belum Dinilai';
        }

        $allComplete = $allItems->every(fn (array $item) => $item['status_label'] === 'Telah Dinilai');

        return $allComplete ? 'Selesai' : 'Dalam Proses';
    }

    public function show(string $uuid)
    {
        $tender = $this->resolveTender($uuid);
        // Langkah 1 vendor list — frozen record, see loadPenilaianTeknikalVendors().
        $shortlistedVendors = $this->loadPenilaianTeknikalVendors($tender);
        $pematuhanEvaluations = $this->service->loadPematuhanEvaluations($tender);

        $step1ChecklistItems = $this->loadStep1ChecklistItems($tender);
        $step1Items = $this->mapTechnicalChecklistItems(
            $step1ChecklistItems,
            $tender,
            $shortlistedVendors,
            $pematuhanEvaluations
        );

        // Langkah 2 vendor list — separate frozen record of who passed Pematuhan.
        $step2Vendors = $this->loadPematuhanLulusVendors($tender);
        $step2Structure = $this->service->loadSpecificationStructure($step1ChecklistItems);
        $spesifikasiEvaluations = $this->service->loadSpesifikasiEvaluations($tender);
        $borangEvaluations = $this->service->loadBorangEvaluations($tender);
        $step2Items = $this->mapStep2ChecklistItems(
            $step1ChecklistItems,
            $step2Vendors,
            $step2Structure,
            $spesifikasiEvaluations,
            $borangEvaluations
        );

        // "All items evaluated" is not proof of confirmation (Seterusnya still needs an
        // explicit tick) — pematuhan/spesifikasi_confirmed_at are the reliable resume markers.
        $laporan = $this->service->loadLaporan($tender);
        $pematuhanConfirmed = (bool) ($laporan?->pematuhan_confirmed_at);
        $spesifikasiConfirmed = (bool) ($laporan?->spesifikasi_confirmed_at);
        // spesifikasiConfirmed alone is enough for 'laporan' — Langkah 3 is only reachable
        // after that gate, so a fully-submitted tender has already passed it too.
        $resumeTab = $spesifikasiConfirmed ? 'laporan' : ($pematuhanConfirmed ? 'teknikal-2' : 'teknikal-1');
        $fullySubmitted = (bool) ($laporan?->submitted_at);

        return view('newModule.penilaian_teknikal.teknikal', [
            'tender_no'  => $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id,
            'tender'     => $tender,
            'step1Items' => $step1Items,
            'step2Items' => $step2Items,
            'pematuhanConfirmed' => $pematuhanConfirmed,
            'spesifikasiConfirmed' => $spesifikasiConfirmed,
            'fullySubmitted' => $fullySubmitted,
            'resumeTab'  => $resumeTab,
            'shortlistedVendors' => $shortlistedVendors,
            'vendorDokumenResponses' => $this->loadVendorDokumenResponses($tender, $shortlistedVendors),
            'pematuhanEvaluations' => collect($pematuhanEvaluations)
                ->map(fn (TenderTeknikalPematuhanEvaluation $e) => [
                    'status_pematuhan' => $e->status_pematuhan,
                    'catatan' => $e->catatan,
                ])
                ->all(),
        ]);
    }

    /** Raw Langkah 1 checklist items, shared by show() and rumusanPematuhan(). Bekalan/perkhidmatan only. */
    private function loadStep1ChecklistItems(Tender $tender): Collection
    {
        if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
            return collect();
        }

        $header = TechnicalChecklistHeader::query()
            ->where('tender_id', $tender->id)
            ->with('items')
            ->first();

        return collect($header?->items ?? [])
            ->reject(fn (TechnicalChecklistItem $item) => $item->mechanism === ChecklistMechanism::PTJ_MUAT_NAIK
                && $item->vendor_action === ChecklistMechanism::VENDOR_ACTION_MUAT_TURUN)
            ->values();
    }

    /**
     * Vendors eligible to START Penilaian Teknikal — active or eliminated at this stage only.
     * Frozen list: doesn't shrink when a later step eliminates a vendor (keeps Langkah 1 historical).
     */
    private function loadPenilaianTeknikalVendors(Tender $tender): Collection
    {
        return TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('participate', 1)
            ->where(function ($query) {
                $query->where('cancel_fg', 0)
                    ->orWhere('eliminated_process_id', TenderProcessStatus::PENILAIAN_TEKNIKAL);
            })
            ->orderBy('id')
            ->get(['id', 'vendor_id', 'kod_pembekal'])
            ->values();
    }

    /**
     * Vendors that passed Langkah 1, recomputed from evaluations — not cancel_fg.
     * Also frozen: doesn't shrink when Langkah 3 later eliminates a Langkah-2 vendor.
     */
    private function loadPematuhanLulusVendors(Tender $tender): Collection
    {
        $vendors = $this->loadPenilaianTeknikalVendors($tender);
        $items = $this->loadStep1ChecklistItems($tender);
        $evaluations = $this->service->loadPematuhanEvaluations($tender);

        $rumusan = $this->service->computeRumusanPematuhan($vendors, $items, $evaluations);
        $lulusVendorIds = collect($rumusan['layak'])->pluck('vendor_id')->all();

        return $vendors->filter(fn (TenderVendor $vendor) => in_array((int) $vendor->vendor_id, $lulusVendorIds, true))
            ->values();
    }

    /** Uploaded documents per vendor per checklist item, via the shared VendorDokumenResponseService. */
    private function loadVendorDokumenResponses(Tender $tender, Collection $vendors): array
    {
        $service = app(VendorDokumenResponseService::class);

        return $vendors
            ->mapWithKeys(fn (TenderVendor $vendor) => [
                $vendor->vendor_id => $service->responsesByItemUuid($tender, $vendor->vendor_id),
            ])
            ->all();
    }

    /** Maps checklist items to Langkah 1 table rows. */
    private function mapTechnicalChecklistItems(iterable $items, Tender $tender, Collection $vendors, array $evaluations): Collection
    {
        return collect($items)
            ->map(function (TechnicalChecklistItem $item) use ($tender, $vendors, $evaluations) {
                $evaluatedCount = $vendors
                    ->filter(fn (TenderVendor $vendor) => isset($evaluations["{$vendor->vendor_id}:{$item->uuid}"]))
                    ->count();
                $status = $this->resolveStatusPenilaian($evaluatedCount, $vendors->count());

                return [
                    'uuid'            => $item->uuid,
                    'title'          => $item->title ?: '-',
                    'mechanism'      => $item->mechanism,
                    'mechanism_label' => ChecklistMechanism::label($item->mechanism, $item->source_type),
                    'is_spesifikasi' => $item->source_type === ChecklistMechanism::SOURCE_SPECIFICATION,
                    'is_borang_atas_talian' => TenderDokumenActionResolver::resolve($item->source_type, $item->mechanism, $item->vendor_action) === 'online_form',
                    'form_url'       => $this->resolveBorangAtasTalianUrl($item, $tender),
                    'can_menilai'    => true,
                    'status_label'   => $status['label'],
                    'status_badge_class' => $status['badge'],
                ];
            })
            ->values();
    }

    /** Status Penilaian for one item, computed in memory from already-loaded evaluation counts. */
    private function resolveStatusPenilaian(int $evaluatedCount, int $totalVendors): array
    {
        if ($totalVendors === 0 || $evaluatedCount === 0) {
            return ['label' => 'Menunggu Penilaian', 'badge' => 'badge-status-neutral'];
        }

        if ($evaluatedCount < $totalVendors) {
            return ['label' => 'Sedang Menilai', 'badge' => 'badge-status-warning'];
        }

        return ['label' => 'Telah Dinilai', 'badge' => 'badge-status-success'];
    }

    /**
     * Maps ALL checklist items (every mechanism) to Langkah 2 rows, not just Spesifikasi.
     * Spesifikasi completeness comes from computeSpesifikasiRollup(); others from $borangEvaluations.
     */
    private function mapStep2ChecklistItems(Collection $items, Collection $vendors, Collection $structure, array $spesifikasiEvaluations, array $borangEvaluations): Collection
    {
        return $items
            ->map(function (TechnicalChecklistItem $item) use ($vendors, $structure, $spesifikasiEvaluations, $borangEvaluations) {
                $isSpesifikasi = $item->source_type === ChecklistMechanism::SOURCE_SPECIFICATION;
                $isItemLevelScored = in_array($item->mechanism, [
                    ChecklistMechanism::BORANG_ATAS_TALIAN,
                    ChecklistMechanism::PETENDER_MUAT_NAIK,
                    ChecklistMechanism::PTJ_MUAT_NAIK,
                ], true);

                if ($isSpesifikasi) {
                    $details = $this->service->flattenSpecificationDetails($structure->get($item->uuid, collect()));
                    $rollup = $this->service->computeSpesifikasiRollup($vendors, $details, $spesifikasiEvaluations);
                    $completedCount = collect($rollup)->where('is_complete', true)->count();
                } elseif ($isItemLevelScored) {
                    $completedCount = $vendors
                        ->filter(fn (TenderVendor $vendor) => isset($borangEvaluations["{$vendor->vendor_id}:{$item->uuid}"])
                            && $borangEvaluations["{$vendor->vendor_id}:{$item->uuid}"]->skor_manual !== null)
                        ->count();
                } else {
                    $completedCount = 0;
                }

                $status = $this->resolveStatusPenilaian($completedCount, $vendors->count());

                return [
                    'uuid'            => $item->uuid,
                    'title'           => $item->title ?: '-',
                    'mechanism'       => $item->mechanism,
                    'mechanism_label' => ChecklistMechanism::label($item->mechanism, $item->source_type),
                    'is_spesifikasi'  => $isSpesifikasi,
                    'is_item_level_scored' => $isItemLevelScored,
                    'status_label'    => $status['label'],
                    'status_badge_class' => $status['badge'],
                ];
            })
            ->values();
    }

    /** Resolves an item-level checklist item (Borang/Muat Naik) scoped to this tender. */
    private function resolveBorangChecklistItem(Tender $tender, string $uuid): TechnicalChecklistItem
    {
        return TechnicalChecklistItem::query()
            ->where('uuid', $uuid)
            ->whereIn('mechanism', [
                ChecklistMechanism::BORANG_ATAS_TALIAN,
                ChecklistMechanism::PETENDER_MUAT_NAIK,
                ChecklistMechanism::PTJ_MUAT_NAIK,
            ])
            ->whereHas('header', fn ($query) => $query->where('tender_id', $tender->id))
            ->firstOrFail();
    }

    /** Maps vendor_action to its OnlineFormStatusService form_key. Only 2 forms exist today. */
    private function resolveBorangFormKey(TechnicalChecklistItem $item): ?string
    {
        return match ($item->vendor_action) {
            'pengalaman-kerja'   => 'pengalaman_kerja',
            'kerja-dalam-tangan' => 'kerja_dalam_tangan',
            default              => null,
        };
    }

    /**
     * Existing online form URL for a checklist item, mapped by vendor_action.
     * Only Pengalaman Kerja & Kerja Dalam Tangan appear here — other forms belong to kewangan.
     */
    private function resolveBorangAtasTalianUrl(TechnicalChecklistItem $item, Tender $tender): ?string
    {
        $action = TenderDokumenActionResolver::resolve($item->source_type, $item->mechanism, $item->vendor_action);
        if ($action !== 'online_form' || empty($tender->uuid)) {
            return null;
        }

        return match ($item->vendor_action) {
            'pengalaman-kerja'   => route('penilaianTeknikal.pengalamanKerjaReview', ['tender' => $tender->id]),
            'kerja-dalam-tangan' => route('penilaianTeknikal.kerjaDalamTanganReview', ['tender' => $tender->id]),
            default              => null,
        };
    }

    public function simpanPematuhan(Request $request): JsonResponse
    {
        $request->validate([
            'tender'                     => 'required|string',
            'checklist_item_uuid'        => 'required|uuid',
            'rows'                       => 'required|array|min:1',
            'rows.*.vendor_id'           => 'required|integer',
            'rows.*.status_pematuhan'    => 'required|in:0,1',
            'rows.*.catatan'             => 'nullable|string|max:2000',
        ]);

        $result = $this->callStos('save pematuhan', ['tender' => $request->input('tender')],
            fn () => $this->stos->savePematuhanTeknikal([
                'tender' => $request->input('tender'),
                'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                'rows' => $request->input('rows'),
                'acting_user_id' => Auth::id(),
            ]));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menyimpan penilaian pematuhan.',
            ], $result['status']);
        }

        $data = $result['body']['data'] ?? [];

        return response()->json([
            'message' => $result['message'] ?: 'Penilaian pematuhan telah disimpan.',
            'item_status' => $this->presentStatusPenilaian(
                (int) ($data['evaluated_count'] ?? 0),
                (int) ($data['total_vendors'] ?? 0)
            ),
        ]);
    }

    /** Shapes the evaluation counts into the badge payload the front-end expects. */
    private function presentStatusPenilaian(int $evaluatedCount, int $totalVendors): array
    {
        $status = $this->resolveStatusPenilaian($evaluatedCount, $totalVendors);

        return ['label' => $status['label'], 'badge_class' => $status['badge']];
    }

    /**
     * Calls the STOS backend and normalises the outcome for the browser. Returns the decoded
     * body on success; on failure logs it and yields the message plus a client-safe status.
     *
     * @return array{ok: bool, body: array, status: int, message: string}
     */
    private function callStos(string $action, array $context, \Closure $request): array
    {
        try {
            $response = $request();
            $body = $response->json() ?? [];

            if ($response->successful()) {
                return ['ok' => true, 'body' => $body, 'status' => 200, 'message' => $body['message'] ?? ''];
            }

            Log::error('Backend API error', array_merge($context, [
                'action' => $action,
                'status' => $response->status(),
                'body' => $response->body(),
            ]));

            return [
                'ok' => false,
                'body' => $body,
                'status' => in_array($response->status(), [404, 422], true) ? $response->status() : 502,
                'message' => $body['message'] ?? '',
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to {$action} via API", array_merge($context, ['error' => $e->getMessage()]));

            return ['ok' => false, 'body' => [], 'status' => 502, 'message' => ''];
        }
    }

    public function rumusanPematuhan(Tender $tender): JsonResponse
    {
        $result = $this->callStos('load rumusan pematuhan', ['tender_id' => $tender->id],
            fn () => $this->stos->getRumusanPematuhanTeknikal($tender->id));

        if (! $result['ok']) {
            return response()->json(['message' => 'Ralat memuatkan rumusan pematuhan dokumentasi.'], $result['status']);
        }

        return response()->json($result['body']['data'] ?? ['layak' => [], 'tidak_layak' => []]);
    }

    public function hantarPematuhan(Tender $tender): JsonResponse
    {
        $result = $this->callStos('submit pematuhan', ['tender_id' => $tender->id],
            fn () => $this->stos->hantarPematuhanTeknikal($tender->id, ['acting_user_id' => Auth::id()]));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menghantar pematuhan dokumentasi.',
            ], $result['status']);
        }

        Log::info('Pematuhan teknikal submitted via backend API', [
            'tender_id' => $tender->id,
            'eliminated_count' => $result['body']['data']['eliminated_count'] ?? null,
        ]);

        return response()->json([
            'message' => $result['message'] ?: 'Pematuhan dokumentasi telah dihantar.',
            'tidak_layak_count' => $result['body']['data']['eliminated_count'] ?? 0,
        ]);
    }

    public function confirmSpesifikasi(Request $request): JsonResponse
    {
        $request->validate(['tender' => 'required|string']);

        $result = $this->callStos('confirm spesifikasi', ['tender' => $request->input('tender')],
            fn () => $this->stos->confirmSpesifikasiTeknikal([
                'tender' => $request->input('tender'),
                'acting_user_id' => Auth::id(),
            ]));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa mengesahkan pematuhan spesifikasi teknikal.',
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message'] ?: 'Pematuhan spesifikasi teknikal telah disahkan.',
        ]);
    }

    public function spesifikasiRollup(Tender $tender, string $checklistItemUuid): JsonResponse
    {
        $result = $this->callStos('load spesifikasi rollup', ['tender_id' => $tender->id, 'item' => $checklistItemUuid],
            fn () => $this->stos->getSpesifikasiRollup($tender->id, $checklistItemUuid));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat memuatkan senarai pembekal.',
            ], $result['status']);
        }

        $data = $result['body']['data'] ?? [];

        return response()->json([
            'rows' => $data['rows'] ?? [],
            'item_status' => $this->presentStatusPenilaian(
                (int) ($data['completed_count'] ?? 0),
                (int) ($data['total_vendors'] ?? 0)
            ),
        ]);
    }

    public function spesifikasiDetail(Tender $tender, string $checklistItemUuid, int $vendorId): JsonResponse
    {
        $result = $this->callStos('load spesifikasi detail', ['tender_id' => $tender->id, 'vendor_id' => $vendorId],
            fn () => $this->stos->getSpesifikasiDetail($tender->id, $checklistItemUuid, $vendorId));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat memuatkan spesifikasi.',
            ], $result['status']);
        }

        // Cadangan Petender is the vendor's own submitted answer, shown read-only. It stays
        // sourced locally because VendorDokumenResponseService is shared with other modules.
        $vendorCadangan = app(VendorDokumenResponseService::class)
            ->responsesByItemUuid($tender, $vendorId)[$checklistItemUuid]['specification']['details']
            ?? [];

        $rows = collect($result['body']['data']['rows'] ?? [])->map(function (array $row) use ($vendorCadangan) {
            if (($row['kind'] ?? '') === 'detail') {
                $row['vendor_cadangan'] = $vendorCadangan[$row['detail_uuid']]['cadangan'] ?? null;
            }

            return $row;
        })->all();

        return response()->json(['rows' => $rows]);
    }

    public function simpanSpesifikasi(Request $request): JsonResponse
    {
        $request->validate([
            'tender'                     => 'required|string',
            'vendor_id'                  => 'required|integer',
            'checklist_item_uuid'        => 'required|uuid',
            'rows'                       => 'required|array|min:1',
            'rows.*.detail_uuid'         => 'required|uuid',
            'rows.*.input_value'         => 'nullable|string|max:255',
            'rows.*.skor_manual'         => 'nullable|numeric|min:0',
            'rows.*.catatan'             => 'nullable|string|max:2000',
        ]);

        $result = $this->callStos('save spesifikasi', ['tender' => $request->input('tender')],
            fn () => $this->stos->saveSpesifikasiTeknikal([
                'tender' => $request->input('tender'),
                'vendor_id' => $request->input('vendor_id'),
                'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                'rows' => $request->input('rows'),
                'acting_user_id' => Auth::id(),
            ]));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menyimpan penilaian spesifikasi.',
            ], $result['status']);
        }

        $data = $result['body']['data'] ?? [];

        return response()->json([
            'message' => $result['message'] ?: 'Penilaian spesifikasi telah disimpan.',
            'rollup' => $data['rollup'] ?? null,
            'item_status' => $this->presentStatusPenilaian(
                (int) ($data['completed_count'] ?? 0),
                (int) ($data['total_vendors'] ?? 0)
            ),
        ]);
    }

    /**
     * Score rows (one per vendor) for one item-level checklist item — shared by all 3
     * non-Spesifikasi mechanisms; "Dokumen"/"Status Penyerahan" source differs per mechanism.
     */
    public function borangRows(Tender $tender, string $checklistItemUuid): JsonResponse
    {
        $result = $this->callStos('load borang rows', ['tender_id' => $tender->id, 'item' => $checklistItemUuid],
            fn () => $this->stos->getBorangEvaluations($tender->id, $checklistItemUuid));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat memuatkan senarai pembekal.',
            ], $result['status']);
        }

        $data = $result['body']['data'] ?? [];
        $item = $this->resolveBorangChecklistItem($tender, $checklistItemUuid);
        $isBorangAtasTalian = ($data['mechanism'] ?? null) === ChecklistMechanism::BORANG_ATAS_TALIAN;
        $maxScore = (float) ($data['max_score'] ?? 0);

        // Submission state and document links come from services shared with other modules,
        // so they are merged in here rather than duplicated in the backend.
        $formUrl = $isBorangAtasTalian ? $this->resolveBorangAtasTalianUrl($item, $tender) : null;
        $formKey = $isBorangAtasTalian ? $this->resolveBorangFormKey($item) : null;
        $onlineFormStatus = app(OnlineFormStatusService::class);
        $vendorDokumenService = app(VendorDokumenResponseService::class);

        $rows = collect($data['rows'] ?? [])->map(function (array $row) use (
            $tender, $item, $isBorangAtasTalian, $formUrl, $formKey, $onlineFormStatus, $vendorDokumenService, $maxScore
        ) {
            $vendorId = (int) $row['vendor_id'];

            if ($isBorangAtasTalian) {
                $penyerahan = $formKey ? $onlineFormStatus->vendorStatus($tender, $vendorId, $formKey) : null;
                $submitted = $penyerahan !== null && $penyerahan['status'] === 'submitted';
                $docUrl = $formUrl
                    ? $formUrl . (str_contains($formUrl, '?') ? '&' : '?') . 'vendor_id=' . $vendorId . '&modal=1'
                    : null;
                $docMode = 'form';
                $files = [];
            } else {
                $response = $vendorDokumenService->responsesByItemUuid($tender, $vendorId)[$item->uuid] ?? null;
                $submitted = ($response['status'] ?? 'draft') === 'submitted';
                $docUrl = null;
                $docMode = 'upload';
                $files = collect($response['files'] ?? [])
                    ->map(fn ($file) => ['name' => $file['name'], 'url' => $file['url']])
                    ->values()
                    ->all();
            }

            return array_merge($row, [
                'doc_mode' => $docMode,
                'doc_url' => $docUrl,
                'files' => $files,
                'status_penyerahan' => $submitted ? 'Hantar' : 'Tidak Hantar',
                'status_penyerahan_badge' => $submitted ? 'badge-status-success' : 'badge-status-neutral',
                'max_score' => $maxScore,
            ]);
        })->values();

        return response()->json([
            'rows' => $rows,
            'item_status' => $this->presentStatusPenilaian(
                (int) ($data['scored_count'] ?? 0),
                (int) ($data['total_vendors'] ?? 0)
            ),
        ]);
    }

    public function simpanBorang(Request $request): JsonResponse
    {
        $request->validate([
            'tender'              => 'required|string',
            'checklist_item_uuid' => 'required|uuid',
            'rows'                       => 'required|array|min:1',
            'rows.*.vendor_id'           => 'required|integer',
            'rows.*.skor_manual'         => 'nullable|numeric|min:0',
            'rows.*.catatan'             => 'nullable|string|max:2000',
        ]);

        $result = $this->callStos('save borang', ['tender' => $request->input('tender')],
            fn () => $this->stos->saveBorangTeknikal([
                'tender' => $request->input('tender'),
                'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                'rows' => $request->input('rows'),
                'acting_user_id' => Auth::id(),
            ]));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menyimpan penilaian dokumen.',
            ], $result['status']);
        }

        $data = $result['body']['data'] ?? [];

        return response()->json([
            'message' => $result['message'] ?: 'Penilaian dokumen telah disimpan.',
            'item_status' => $this->presentStatusPenilaian(
                (int) ($data['scored_count'] ?? 0),
                (int) ($data['total_vendors'] ?? 0)
            ),
        ]);
    }

    /** Langkah 2 summary — each vendor's total score across every mechanism vs the passing benchmark. */
    public function rumusanPenilaianTeknikal(Tender $tender): JsonResponse
    {
        $result = $this->callStos('load rumusan penilaian teknikal', ['tender_id' => $tender->id],
            fn () => $this->stos->getRumusanPenilaianTeknikal($tender->id));

        if (! $result['ok']) {
            return response()->json(['message' => 'Ralat memuatkan rumusan penilaian teknikal.'], $result['status']);
        }

        return response()->json($result['body']['data'] ?? [
            'layak' => [], 'tidak_layak' => [], 'passing_percentage' => 0, 'max_score' => 0,
        ]);
    }

    public function pengalamanKerjaReview(Tender $tender, Request $request)
    {
        $this->ensureTenderFormAccess($tender);

        $local = $this->loadVendorFormPayload($tender, 'pengalaman_kerja');
        $remote = $this->fetchOnlineFormData('pengalaman-kerja', $tender->uuid, (int) $request->query('vendor_id'));

        return view('newModule.penilaian_teknikal.review.pengalaman_kerja_review', array_merge([
            'items'    => $local['items'] ?? [],
            'dokumens' => $remote['dokumens'] ?? [],
        ], $this->formViewVars($tender)));
    }

    public function kerjaDalamTanganReview(Tender $tender, Request $request)
    {
        $this->ensureTenderFormAccess($tender);

        $local = $this->loadVendorFormPayload($tender, 'kerja_dalam_tangan');
        $remote = $this->fetchOnlineFormData('kerja-dalam-tangan', $tender->uuid, (int) $request->query('vendor_id'));

        return view('newModule.penilaian_teknikal.review.kerja_dalam_tangan_review', array_merge([
            'items'    => $local['items'] ?? [],
            'dokumens' => $remote['dokumens'] ?? [],
        ], $this->formViewVars($tender)));
    }

    /**
     * @return array{items?: array, dokumens?: array}
     */
    private function fetchOnlineFormData(string $apiSlug, string $tenderUuid, ?int $vendorId): array
    {
        $url = StosBackendClient::apiUrl($apiSlug . '/' . $tenderUuid);
        if ($vendorId) {
            $url .= '?vendor_id=' . $vendorId;
        }

        $response = StosBackendClient::http()->get($url);

        return $response->successful() ? (array) $response->json('data') : [];
    }

    /** Resolves a tender from the index-page link's uuid. */
    private function resolveTender(string $uuid): Tender
    {
        return Tender::query()->where('uuid', $uuid)->firstOrFail();
    }

    /** catatan_pematuhan/catatan_spesifikasi/pengesyoran_intro are rich text now (contenteditable) —
     *  keep only the formatting they actually use, strip everything else before it reaches the API. */
    private function sanitizeCatatanFields(array $data): array
    {
        foreach (['catatan_pematuhan', 'catatan_spesifikasi', 'pengesyoran_intro'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = strip_tags($data[$field], '<strong><br>');
            }
        }

        return $data;
    }

    /** Saved Langkah 3 report text, if any — reloaded each time the report step is shown. */
    public function laporanPenilaianTeknikal(Tender $tender): JsonResponse
    {
        $result = $this->callStos('load laporan', ['tender_id' => $tender->id],
            fn () => $this->stos->getLaporanTeknikal($tender->id));

        $data = $result['ok'] ? ($result['body']['data'] ?? []) : [];

        return response()->json([
            'catatan_pematuhan' => $data['catatan_pematuhan'] ?? null,
            'catatan_spesifikasi' => $data['catatan_spesifikasi'] ?? null,
            'pengesyoran_intro' => $data['pengesyoran_intro'] ?? null,
            'pengesyoran_justifikasi' => $data['pengesyoran_justifikasi'] ?? [],
        ]);
    }

    /** Printable report — a standalone HTML page the browser turns into PDF via window.print(). */
    public function cetakLaporan(Tender $tender)
    {
        $perananLabels = ['1' => 'Pengerusi', '2' => 'Setiausaha', '3' => 'Ahli'];

        $jptMembers = Jawatankuasa::with('user')
            ->where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', 'tech')
            ->whereNotNull('user_id')
            ->orderBy('peranan')
            ->orderBy('id')
            ->get()
            ->filter(fn (Jawatankuasa $row) => $row->user)
            ->map(fn (Jawatankuasa $row) => [
                'peranan_label' => $perananLabels[(string) $row->peranan] ?? 'Ahli',
                'name' => $row->user->name,
                'jawatan' => $row->user->jawatan ?: '-',
                'tarikh_lantikan' => optional($row->created_at)->format('d.m.Y'),
            ])
            ->values();

        $mesyuaratTeknikal = PenyediaanMesyuaratMeeting::query()
            ->where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', 'tech')
            ->orderByDesc('tarikh_mesyuarat')
            ->first();

        $dokumenTeknikal = $this->loadStep1ChecklistItems($tender)
            ->values()
            ->map(fn (TechnicalChecklistItem $item, int $idx) => [
                'bil' => $idx + 1,
                'keterangan' => $item->title ?: '-',
            ]);

        $latarBelakang = $this->buildLatarBelakang($tender, $dokumenTeknikal->count());

        $rumusanPematuhanResult = $this->callStos('load rumusan pematuhan (laporan cetak)', ['tender_id' => $tender->id],
            fn () => $this->stos->getRumusanPematuhanTeknikal($tender->id));
        $rumusanPematuhan = $rumusanPematuhanResult['ok']
            ? ($rumusanPematuhanResult['body']['data'] ?? ['layak' => [], 'tidak_layak' => []])
            : ['layak' => [], 'tidak_layak' => []];

        $keputusanByVendorId = [];
        foreach ($rumusanPematuhan['layak'] ?? [] as $row) {
            $keputusanByVendorId[(int) $row['vendor_id']] = 'LULUS';
        }
        foreach ($rumusanPematuhan['tidak_layak'] ?? [] as $row) {
            $keputusanByVendorId[(int) $row['vendor_id']] = 'GAGAL';
        }

        $step1Vendors = $this->loadPenilaianTeknikalVendors($tender)->values();
        $totalStep1Vendors = $step1Vendors->count();

        $penilaianPeringkatPertama = $step1Vendors->map(function (TenderVendor $vendor, int $idx) use ($keputusanByVendorId, $totalStep1Vendors) {
            $keputusan = $keputusanByVendorId[(int) $vendor->vendor_id] ?? 'GAGAL';

            return [
                'bil' => $idx + 1,
                'no_petender' => ($idx + 1) . '/' . $totalStep1Vendors,
                'keputusan' => $keputusan,
                'ulasan' => $keputusan === 'LULUS'
                    ? 'Semua Dokumen Lengkap Dan Mencukupi Dan Layak Ke Penilaian Peringkat Kedua'
                    : 'Dokumen Tidak Lengkap Dan/Atau Tidak Mencukupi Dan Tidak Layak Ke Penilaian Peringkat Kedua',
            ];
        });

        $laporanResult = $this->callStos('load laporan (laporan cetak)', ['tender_id' => $tender->id],
            fn () => $this->stos->getLaporanTeknikal($tender->id));
        $laporanData = $laporanResult['ok'] ? ($laporanResult['body']['data'] ?? []) : [];
        $catatanPematuhan = $laporanData['catatan_pematuhan'] ?? null;
        $catatanSpesifikasi = $laporanData['catatan_spesifikasi'] ?? null;
        $pengesyoranIntro = $laporanData['pengesyoran_intro'] ?? null;
        $pengesyoranJustifikasi = $laporanData['pengesyoran_justifikasi'] ?? [];

        $rumusanTeknikalResult = $this->callStos('load rumusan penilaian teknikal (laporan cetak)', ['tender_id' => $tender->id],
            fn () => $this->stos->getRumusanPenilaianTeknikal($tender->id));
        $rumusanPenilaianTeknikal = $rumusanTeknikalResult['ok'] ? ($rumusanTeknikalResult['body']['data'] ?? []) : [];
        $passingPercentage = $rumusanPenilaianTeknikal['passing_percentage'] ?? null;

        $passingPercentageFormatted = $passingPercentage !== null
            ? rtrim(rtrim(number_format((float) $passingPercentage, 2), '0'), '.')
            : 'Value to be confirm where to fetch it';

        $peratusByVendorId = [];
        $keputusanStep2ByVendorId = [];
        $kedudukanByVendorId = [];
        foreach ($rumusanPenilaianTeknikal['layak'] ?? [] as $row) {
            $peratusByVendorId[(int) $row['vendor_id']] = $row['peratus'] ?? 0;
            $keputusanStep2ByVendorId[(int) $row['vendor_id']] = 'LULUS';
            $kedudukanByVendorId[(int) $row['vendor_id']] = $row['kedudukan'] ?? null;
        }
        foreach ($rumusanPenilaianTeknikal['tidak_layak'] ?? [] as $row) {
            $peratusByVendorId[(int) $row['vendor_id']] = $row['peratus'] ?? 0;
            $keputusanStep2ByVendorId[(int) $row['vendor_id']] = 'GAGAL';
        }

        $step2Vendors = $this->loadPematuhanLulusVendors($tender)->values();
        $totalStep2Vendors = $step2Vendors->count();

        $penilaianPeringkatKedua = $step2Vendors->map(function (TenderVendor $vendor, int $idx) use ($peratusByVendorId, $keputusanStep2ByVendorId, $kedudukanByVendorId, $totalStep2Vendors, $passingPercentageFormatted) {
            $vendorId = (int) $vendor->vendor_id;
            $keputusan = $keputusanStep2ByVendorId[$vendorId] ?? 'GAGAL';

            return [
                'bil' => $idx + 1,
                'no_petender' => ($idx + 1) . '/' . $totalStep2Vendors,
                'markah_teknikal' => $peratusByVendorId[$vendorId] ?? 0,
                'keputusan' => $keputusan,
                'kedudukan' => $kedudukanByVendorId[$vendorId] ?? null,
                'ulasan' => $keputusan === 'LULUS'
                    ? 'Layak Ke Penilaian Peringkat Pengesyoran'
                    : "Tidak Layak Ke Penilaian Peringkat Pengesyoran Kerana Markah Lulus Teknikal Kurang Dari {$passingPercentageFormatted}%",
            ];
        });

        // Everyone who passed Step 2 except the top-ranked vendor — 6.1 addresses the winner
        // separately, this is the "also eligible" list for 6.2.
        $petenderLainLulus = $penilaianPeringkatKedua
            ->filter(fn (array $row) => $row['keputusan'] === 'LULUS' && (int) ($row['kedudukan'] ?? 0) !== 1)
            ->pluck('no_petender')
            ->values();

        $petenderLainLulusText = null;
        if ($petenderLainLulus->count() === 1) {
            $petenderLainLulusText = $petenderLainLulus->first();
        } elseif ($petenderLainLulus->count() > 1) {
            $petenderLainLulusText = $petenderLainLulus->slice(0, -1)->implode(', ') . ' dan ' . $petenderLainLulus->last();
        }

        return view('newModule.penilaian_teknikal.laporan_cetak', [
            'tender' => $tender,
            'latarBelakang' => $latarBelakang,
            'jptMembers' => $jptMembers,
            'tarikhMesyuaratTeknikal' => $mesyuaratTeknikal?->tarikh_mesyuarat,
            'dokumenTeknikal' => $dokumenTeknikal,
            'penilaianPeringkatKedua' => $penilaianPeringkatKedua,
            'passingPercentageFormatted' => $passingPercentageFormatted,
            'penilaianPeringkatPertama' => $penilaianPeringkatPertama,
            'catatanSpesifikasi' => $catatanSpesifikasi,
            'pengesyoranIntro' => $pengesyoranIntro,
            'pengesyoranJustifikasi' => $pengesyoranJustifikasi,
            'petenderLainLulusText' => $petenderLainLulusText,
            'catatanPematuhan' => $catatanPematuhan,
            'tarikhLaporanDicetak' => $this->formatTarikhMalay(now()),
        ]);
    }

    /** "29 Julai 2024" — Carbon has no built-in Malay locale here, so map months manually. */
    private function formatTarikhMalay(Carbon $date): string
    {
        $bulanMs = ['', 'Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'];

        return $date->day . ' ' . $bulanMs[$date->month] . ' ' . $date->year;
    }

    private function buildLatarBelakang(Tender $tender, int $bilanganDokumen): array
    {
        $iklanRecord = PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
        $iklanMeta = ($iklanRecord && is_array($iklanRecord->meta)) ? ($iklanRecord->meta['iklan'] ?? []) : [];
        $tempohSahLaku = $iklanMeta['tempoh_sah_laku'] ?? null;

        return [
            'agensi_pelaksana' => $tender->tenderer?->name ?? '-',
            'kaedah_perolehan' => ($tender->isSebutHargaKaedah() ? 'Sebut Harga' : 'Tender') . ' Terbuka Melalui Sistem Perolehan Selangor',
            'tarikh_iklan' => $tender->advertise_start_date ? $this->formatTarikhMalay(Carbon::parse($tender->advertise_start_date)) : '-',
            'tarikh_jual' => $tender->document_start_date ? $this->formatTarikhMalay(Carbon::parse($tender->document_start_date)) : '-',
            'tarikh_tutup' => $tender->submission_datetime ? $this->formatTarikhMalay(Carbon::parse($tender->submission_datetime)) : '-',
            'masa_tutup' => $tender->masa_tutup_display,
            'tempoh_sah_laku' => $tempohSahLaku ? $tempohSahLaku . ' Hari' : '-',
            'bilangan_dokumen' => $bilanganDokumen,
        ];
    }

    /**
     * Saves the Langkah 3 draft text without ending the process — no elimination, no status
     * change. Rejected once the report is already submitted.
     */
    public function simpanDrafLaporan(Request $request): JsonResponse
    {
        $request->validate([
            'tender' => 'required|string',
            'catatan_pematuhan' => 'nullable|string|max:5000',
            'catatan_spesifikasi' => 'nullable|string|max:5000',
            'pengesyoran_intro' => 'nullable|string|max:5000',
            'pengesyoran_justifikasi' => 'nullable|array',
            'pengesyoran_justifikasi.*' => 'nullable|string|max:2000',
        ]);

        $result = $this->callStos('save draf laporan', ['tender' => $request->input('tender')],
            fn () => $this->stos->saveDrafLaporanTeknikal(array_merge(
                $this->sanitizeCatatanFields($request->only(['tender', 'catatan_pematuhan', 'catatan_spesifikasi', 'pengesyoran_intro', 'pengesyoran_justifikasi'])),
                ['acting_user_id' => Auth::id()]
            )));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menyimpan draf laporan.',
            ], $result['status']);
        }

        return response()->json(['message' => $result['message'] ?: 'Draf laporan telah disimpan.']);
    }

    /**
     * Submits Langkah 3 — ends Penilaian Teknikal for bekalan/perkhidmatan: eliminates vendors
     * below the benchmark, records the winner, and advances the tender to Penilaian Kewangan.
     */
    public function hantar(Request $request)
    {
        $request->validate([
            'tender' => 'required|string',
            'catatan_pematuhan' => 'nullable|string|max:5000',
            'catatan_spesifikasi' => 'nullable|string|max:5000',
            'pengesyoran_intro' => 'nullable|string|max:5000',
            'pengesyoran_justifikasi' => 'nullable|array',
            'pengesyoran_justifikasi.*' => 'nullable|string|max:2000',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        // Check the status before the backend writes anything, otherwise a tender that cannot
        // advance would still have its vendors eliminated and its report marked submitted.
        if ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianTeknikalListStatus()) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian teknikal (status ' . TenderProcessStatus::penilaianTeknikalListStatus() . ').',
            ], 422);
        }

        $result = $this->callStos('submit penilaian teknikal', ['tender_id' => $tender->id],
            fn () => $this->stos->hantarPenilaianTeknikal(array_merge(
                $this->sanitizeCatatanFields($request->only(['tender', 'catatan_pematuhan', 'catatan_spesifikasi', 'pengesyoran_intro', 'pengesyoran_justifikasi'])),
                ['acting_user_id' => Auth::id()]
            )));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menghantar penilaian teknikal.',
            ], $result['status']);
        }

        $pemenang = $result['body']['data']['winning_vendor'] ?? null;
        $this->advanceTenderProcess($tender, TenderProcessStatus::PENILAIAN_TEKNIKAL, TenderProcessStatus::penilaianTeknikalListStatus());

        Log::info('Penilaian teknikal submitted via backend API', [
            'tender_id' => $tender->id,
            'winning_vendor_id' => $pemenang['vendor_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Penilaian teknikal berjaya dihantar.'
                . ($pemenang ? " Pembekal disyorkan: {$pemenang['kod_pembekal']}." : ''),
            'winning_vendor' => $pemenang,
        ]);
    }

    // Not part of the flow above: index() is unrouted (another dev's code, kept as-is);
    // showTeknikalKerja() is the separate kerja flow.

    public function index()
    {
        $tenders = $this->mapTendersForProcessList(
            Tender::query()
                ->where('status_process_id', TenderProcessStatus::penilaianTeknikalListStatus())
                ->orderByDesc('id')
                ->get(['id', 'uuid', 'no_tender', 'ref_number', 'name', 'submission_datetime', 'kategori_perolehan_id']),
            function (Tender $tender, string $noTender) {
                if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
                    return route('penilaianTeknikalKerja.show', $noTender);
                }

                return route('penilaianTeknikal.show', $noTender);
            }
        );

        return view('newModule.penilaian_teknikal.teknikal_index', compact('tenders'));
    }

    public function showTeknikalKerja(string $uuid)
    {
        $tender = Tender::with('tenderer')->where('uuid', $uuid)->firstOrFail();
        $tender_no = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;

        $vendors = $this->kerjaService->loadShortlistedVendors($tender);
        $evaluations = $this->kerjaService->loadEvaluations($tender);

        $borangTeknikalRows = $vendors->map(function (TenderVendor $vendor) use ($tender, $evaluations) {
            $evaluation = $evaluations[$vendor->vendor_id] ?? null;

            return [
                'vendor_id' => (int) $vendor->vendor_id,
                'rujukan' => $vendor->kod_pembekal,
                'harga' => $this->kerjaService->resolveHargaTawaran($tender, $vendor),
                'status' => $evaluation->status ?? null,
            ];
        })->values();

        $lampiranList = $this->kerjaService->loadLampiran($tender)->map(fn (TenderTeknikalKerjaLampiran $file) => [
            'uuid' => $file->uuid,
            'name' => $file->display_name,
            'url' => $file->url(),
            'size' => $file->size,
        ])->values();

        // Once submitted, the tender has moved past this stage — read-only, same guard as
        // hantarTeknikalKerja() below.
        $readOnly = (int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianTeknikalListStatus();

        return view('newModule.penilaian_teknikal.teknikal_kerja', compact('tender_no', 'tender', 'borangTeknikalRows', 'lampiranList', 'readOnly'));
    }

    /** Allows lampiran edits only while the tender is still at Penilaian Teknikal (kerja). */
    private function kerjaBolehDiubah(?Tender $tender): bool
    {
        return $tender !== null
            && (int) ($tender->status_process_id ?? 0) === TenderProcessStatus::penilaianTeknikalListStatus();
    }

    public function uploadLampiranKerja(Request $request, Tender $tender): JsonResponse
    {
        $data = $request->validate([
            'file' => 'required|file|max:10240',
            'display_name' => 'nullable|string|max:255',
        ]);

        if (! $this->kerjaBolehDiubah($tender)) {
            return response()->json(['message' => 'Penilaian teknikal telah dihantar — lampiran tidak boleh diubah.'], 422);
        }

        $lampiran = $this->kerjaService->uploadLampiran($tender, $data['file'], $data['display_name'] ?? null);

        return response()->json([
            'message' => 'Lampiran berjaya dimuat naik.',
            'data' => [
                'uuid' => $lampiran->uuid,
                'name' => $lampiran->display_name,
                'url' => $lampiran->url(),
                'size' => $lampiran->size,
            ],
        ]);
    }

    public function renameLampiranKerja(Request $request, TenderTeknikalKerjaLampiran $lampiran): JsonResponse
    {
        $data = $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        if (! $this->kerjaBolehDiubah($lampiran->tender)) {
            return response()->json(['message' => 'Penilaian teknikal telah dihantar — lampiran tidak boleh diubah.'], 422);
        }

        $this->kerjaService->renameLampiran($lampiran, $data['display_name']);

        return response()->json([
            'message' => 'Nama lampiran berjaya dikemaskini.',
            'data' => ['uuid' => $lampiran->uuid, 'name' => $lampiran->display_name],
        ]);
    }

    public function deleteLampiranKerja(TenderTeknikalKerjaLampiran $lampiran): JsonResponse
    {
        if (! $this->kerjaBolehDiubah($lampiran->tender)) {
            return response()->json(['message' => 'Penilaian teknikal telah dihantar — lampiran tidak boleh dibuang.'], 422);
        }

        $this->kerjaService->deleteLampiran($lampiran);

        return response()->json(['message' => 'Lampiran berjaya dibuang.']);
    }

    public function downloadLampiranKerja(TenderTeknikalKerjaLampiran $lampiran)
    {
        $absolutePath = $lampiran->absolutePath();
        if (! $absolutePath || ! is_file($absolutePath)) {
            abort(404, 'Fail tidak dijumpai.');
        }

        return response()->download($absolutePath, $lampiran->display_name ?: $lampiran->original_name);
    }

    /** Submits Borang Teknikal, eliminates failing vendors, and advances the tender — kerja's single-page equivalent of hantar(). */
    public function hantarTeknikalKerja(Request $request): JsonResponse
    {
        $request->validate([
            'tender' => 'required|string',
            'rows' => 'required|array|min:1',
            'rows.*.vendor_id' => 'required|integer',
            'rows.*.status' => 'required|in:lulus,tidak_lulus',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        // Check the status before the backend writes anything, otherwise a tender that cannot
        // advance would still have its vendors evaluated and eliminated.
        if ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianTeknikalListStatus()) {
            return response()->json([
                'message' => 'Penilaian teknikal bagi tender ini telah dihantar atau belum sedia untuk dinilai.',
            ], 422);
        }

        $result = $this->callStos('submit penilaian teknikal kerja', ['tender_id' => $tender->id],
            fn () => $this->stos->hantarPenilaianTeknikalKerja([
                'tender' => $request->input('tender'),
                'rows' => $request->input('rows'),
                'acting_user_id' => Auth::id(),
            ]));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menghantar penilaian teknikal.',
            ], $result['status']);
        }

        $this->advanceTenderProcess($tender, TenderProcessStatus::PENILAIAN_TEKNIKAL, TenderProcessStatus::penilaianTeknikalListStatus());

        Log::info('Penilaian teknikal kerja submitted via backend API', [
            'tender_id' => $tender->id,
            'eliminated_count' => $result['body']['data']['eliminated_count'] ?? null,
        ]);

        return response()->json(['message' => $result['message'] ?: 'Penilaian teknikal berjaya dihantar.']);
    }
}
