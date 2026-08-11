<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\TenderVendorDokumenResponse;
use App\Services\JawatankuasaPembukaService;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JawatankuasaPembukaController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function __construct(protected JawatankuasaPembukaService $service) {}

    // ─────────────────────────────────────────────────────────────────
    // Index – list tenders awaiting Pembuka evaluation
    // ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $tenders = Tender::query()
            ->with('tenderer')
            ->where('status_process_id', TenderProcessStatus::penilaianPembukaListStatus())
            ->orderByDesc('id')
            ->get()
            ->map(function (Tender $tender) {
                return [
                    'uuid'          => $tender->uuid,
                    'name'          => $tender->name ?: '-',
                    'no_tender'     => $tender->no_tender ?: $tender->ref_number ?: '-',
                    'tarikh_jual'   => $tender->advertise_start_date
                        ? Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                        : '-',
                    'tarikh_tutup'  => $tender->advertise_stop_date
                        ? Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                        : '-',
                    'harga'         => number_format((float) ($tender->price ?? 0), 2),
                ];
            })
            ->values()
            ->all();

        return view('newModule.jawatankuasaPembuka.index', compact('tenders'));
    }

    // ─────────────────────────────────────────────────────────────────
    // Show – evaluation stepper page
    // ─────────────────────────────────────────────────────────────────

    public function show(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        if (! $tender) {
            return redirect()
                ->route('indexJawatankuasaPembuka')
                ->with('error', 'Tender tidak ditemui.');
        }

        $tender->loadMissing('tenderer');

        $tenderDokumen = TenderDokumenPresenter::for($tender);

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

        $checklistItems  = $tenderDokumen->items('admin');
        $isNotMuatTurun  = fn (array $item) => strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? '')) !== 'muat turun';
        $isExcludedKewangan = function (array $item) {
            $tindakan   = strtolower(trim($item['tindakan'] ?? ''));
            $mekanisma  = strtolower(trim($item['mechanism'] ?? $item['mekanisma'] ?? ''));
            $sourceType = strtolower(trim($item['source_type'] ?? ''));

            return $tindakan === 'muat turun'
                || $mekanisma === 'muat turun'
                || $tindakan === 'spesifikasi'
                || $mekanisma === 'spesifikasi'
                || in_array($sourceType, ['specification', 'specification_document'], true);
        };

        $teknikalItems   = collect($checklistItems)
            ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['technical', 'spesifikasi_kerja'], true))
            ->filter($isNotMuatTurun)
            ->values()
            ->all();
        $kewanganItems   = collect($checklistItems)
            ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
            ->reject($isExcludedKewangan)
            ->values()
            ->all();

        $vendors = $participants->map(function ($participant) {
            return [
                'vendor_id' => (int) $participant->vendor_id,
                'name'      => $participant->vendor?->name ?: ('Vendor #' . $participant->vendor_id),
                'kod'       => $participant->kod_pembekal ?: null,
            ];
        })->values()->all();

        $semakPayload = $this->buildSemakPayload($tender, $teknikalItems, $kewanganItems, $dokumenByVendor, $vendors);

        // Load existing evaluations and merge into semakPayload for pre-filling dropdowns
        $evaluations  = $this->service->loadEvaluations($tender);
        $semakPayload = $this->mergeEvaluationsIntoPayload($semakPayload, $evaluations);

        return view('newModule.jawatankuasaPembuka.jawatankuasa_pembuka', [
            'tender'        => $tender,
            'tenderDokumen' => $tenderDokumen,
            'teknikalItems' => $teknikalItems,
            'kewanganItems' => $kewanganItems,
            'vendors'       => $vendors,
            'dokumenByVendor' => $dokumenByVendor,
            'semakPayload'  => $semakPayload,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // AJAX: Save compliance evaluation for a single item
    // ─────────────────────────────────────────────────────────────────

    public function simpanPematuhan(Request $request): JsonResponse
    {
        $request->validate([
            'tender'               => 'required|string',
            'vendor_id'            => 'required|integer',
            'checklist_item_uuid'  => 'required|uuid',
            'status_pematuhan'     => 'required|in:0,1',
            'catatan'              => 'nullable|string|max:2000',
        ]);

        // When status_pematuhan = 0 (Tiada), catatan is required
        if ((int) $request->input('status_pematuhan') === 0) {
            $request->validate([
                'catatan' => 'required|string|min:1|max:2000',
            ]);
        }

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $record = $this->service->saveEvaluation(
            $tender,
            (int)    $request->input('vendor_id'),
            (string) $request->input('checklist_item_uuid'),
            (int)    $request->input('status_pematuhan'),
            $request->input('catatan')
        );

        return response()->json([
            'message'          => 'Penilaian pematuhan telah disimpan.',
            'status_pematuhan' => $record->status_pematuhan,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // AJAX: Compute rumusan data (qualification status per vendor)
    // ─────────────────────────────────────────────────────────────────

    public function getRumusanData(Request $request): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $tenderDokumen   = TenderDokumenPresenter::for($tender);
        $participants    = $tender->participants()
            ->where('participate', 1)
            ->with('vendor')
            ->orderBy('id')
            ->get();

        $dokumenByVendor = [];
        foreach ($participants as $participant) {
            $vendorId = (int) $participant->vendor_id;
            $dokumenByVendor[$vendorId] = $tenderDokumen->items('vendor', $vendorId);
        }

        $checklistItems = $tenderDokumen->items('admin');
        $isNotMuatTurun = fn (array $i) => strtolower(trim($i['tindakan'] ?? $i['mekanisma'] ?? '')) !== 'muat turun';
        $isExcludedKewangan = function (array $item) {
            $tindakan   = strtolower(trim($item['tindakan'] ?? ''));
            $mekanisma  = strtolower(trim($item['mechanism'] ?? $item['mekanisma'] ?? ''));
            $sourceType = strtolower(trim($item['source_type'] ?? ''));

            return $tindakan === 'muat turun'
                || $mekanisma === 'muat turun'
                || $tindakan === 'spesifikasi'
                || $mekanisma === 'spesifikasi'
                || in_array($sourceType, ['specification', 'specification_document'], true);
        };

        $teknikalItems  = collect($checklistItems)
            ->filter(fn (array $i) => in_array($i['source'] ?? $i['section'] ?? '', ['technical', 'spesifikasi_kerja'], true))
            ->filter($isNotMuatTurun)
            ->values()->all();
        $kewanganItems  = collect($checklistItems)
            ->filter(fn (array $i) => in_array($i['source'] ?? $i['section'] ?? '', ['financial', 'kewangan_kerja'], true))
            ->reject($isExcludedKewangan)
            ->values()->all();

        $vendors = $participants->map(fn ($p) => [
            'vendor_id'     => (int) $p->vendor_id,
            'name'          => $p->vendor?->name ?: ('Vendor #' . $p->vendor_id),
            'kod'           => $p->kod_pembekal ?: null,
            // Include existing rumusan values or auto-calculated specification price
            'is_bumiputera' => $p->is_bumiputera,
            'harga_tawaran' => $this->resolveVendorHargaTawaran($tender, (int) $p->vendor_id, $p->harga_tawaran),
        ])->values()->all();

        $semakPayload = $this->buildSemakPayload($tender, $teknikalItems, $kewanganItems, $dokumenByVendor, $vendors);
        $evaluations  = $this->service->loadEvaluations($tender);

        $result = $this->service->computeVendorQualifications($vendors, $semakPayload, $evaluations);

        return response()->json($result);
    }

    /**
     * Resolve vendor's Harga Tawaran. If empty/null/0, auto-calculates total specification price
     * from tender_vendor_dokumen_responses where response_type = 'specification'.
     */
    protected function resolveVendorHargaTawaran(Tender $tender, int $vendorId, mixed $existingHarga): mixed
    {
        if (filled($existingHarga) && (float) $existingHarga > 0) {
            return (float) $existingHarga;
        }

        $responses = TenderVendorDokumenResponse::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where('response_type', 'specification')
            ->get();

        $totalSpecPrice = 0;
        $hasPrices = false;

        foreach ($responses as $response) {
            $payload = $response->payload ?? [];
            $itemPrices = $payload['item_prices'] ?? [];
            if (is_array($itemPrices)) {
                foreach ($itemPrices as $val) {
                    if (is_numeric($val) && (float) $val > 0) {
                        $totalSpecPrice += (float) $val;
                        $hasPrices = true;
                    }
                }
            }
        }

        return $hasPrices ? number_format($totalSpecPrice, 2, '.', '') : $existingHarga;
    }

    // ─────────────────────────────────────────────────────────────────
    // Hantar – finalise and advance process
    // ─────────────────────────────────────────────────────────────────

    public function hantar(Request $request): JsonResponse
    {
        $request->validate([
            'tender'         => 'required|string',
            'pilihan'        => 'nullable|string',
            'rumusan'        => 'nullable|array',
            'rumusan.*.vendor_id'      => 'required|integer',
            'rumusan.*.is_bumiputera'  => 'nullable|in:0,1',
            'rumusan.*.harga_tawaran'  => 'nullable|numeric|min:0',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        // Re-compute qualification so the backend is the single source of truth
        $tenderDokumen = TenderDokumenPresenter::for($tender);
        $participants  = $tender->participants()
            ->where('participate', 1)
            ->with('vendor')
            ->orderBy('id')
            ->get();

        $dokumenByVendor = [];
        foreach ($participants as $p) {
            $vendorId = (int) $p->vendor_id;
            $dokumenByVendor[$vendorId] = $tenderDokumen->items('vendor', $vendorId);
        }

        $checklistItems = $tenderDokumen->items('admin');
        $isNotMuatTurun = fn (array $i) => strtolower(trim($i['tindakan'] ?? $i['mekanisma'] ?? '')) !== 'muat turun';
        $isExcludedKewangan = function (array $item) {
            $tindakan   = strtolower(trim($item['tindakan'] ?? ''));
            $mekanisma  = strtolower(trim($item['mechanism'] ?? $item['mekanisma'] ?? ''));
            $sourceType = strtolower(trim($item['source_type'] ?? ''));

            return $tindakan === 'muat turun'
                || $mekanisma === 'muat turun'
                || $tindakan === 'spesifikasi'
                || $mekanisma === 'spesifikasi'
                || in_array($sourceType, ['specification', 'specification_document'], true);
        };

        $teknikalItems  = collect($checklistItems)
            ->filter(fn (array $i) => in_array($i['source'] ?? $i['section'] ?? '', ['technical', 'spesifikasi_kerja'], true))
            ->filter($isNotMuatTurun)
            ->values()->all();
        $kewanganItems  = collect($checklistItems)
            ->filter(fn (array $i) => in_array($i['source'] ?? $i['section'] ?? '', ['financial', 'kewangan_kerja'], true))
            ->reject($isExcludedKewangan)
            ->values()->all();

        $vendors = $participants->map(fn ($p) => [
            'vendor_id' => (int) $p->vendor_id,
            'name'      => $p->vendor?->name ?: ('Vendor #' . $p->vendor_id),
            'kod'       => $p->vendor?->registration ?: (string) $p->vendor_id,
        ])->values()->all();

        $semakPayload  = $this->buildSemakPayload($tender, $teknikalItems, $kewanganItems, $dokumenByVendor, $vendors);
        $evaluations   = $this->service->loadEvaluations($tender);

        // ── Validate completeness ─────────────────────────────────────
        $missing = $this->service->findMissingEvaluations($vendors, $semakPayload, $evaluations);

        if (! empty($missing)) {
            $details = collect($missing)
                ->map(fn ($m) => "{$m['vendor']}: {$m['item']}")
                ->implode('; ');

            return response()->json([
                'message' => 'Terdapat item yang belum dinilai. Sila semak semula.',
                'missing' => $missing,
                'details' => $details,
            ], 422);
        }

        // ── Persist rumusan (Bumiputera + harga) ─────────────────────
        $qualifications = $this->service->computeVendorQualifications($vendors, $semakPayload, $evaluations);
        $tidakLayak     = $qualifications['tidak_layak'];

        $this->service->persistRumusan(
            $tender,
            $request->input('rumusan', []),
            $tidakLayak,
            TenderProcessStatus::PENILAIAN_PEMBUKA
        );

        // ── Advance tender status ─────────────────────────────────────
        // Always proceed to Cut-Off → target status = PENILAIAN_PEMBUKA (status_process_id = 8)
        $targetStatus = TenderProcessStatus::PENILAIAN_PEMBUKA;

        if (! $this->advanceTenderProcess(
            $tender,
            $targetStatus,
            TenderProcessStatus::penilaianPembukaListStatus()
        )) {
            return response()->json([
                'message' => 'Tender tidak sedia untuk penilaian pembuka (status semasa tidak sesuai).',
            ], 422);
        }

        return response()->json(['message' => 'Penilaian pembuka berjaya diselesaikan.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // Private Helpers
    // ─────────────────────────────────────────────────────────────────

    /**
     * Merge saved evaluation values back into the semakPayload so the Blade
     * view and frontend JS can pre-fill dropdowns and textareas.
     *
     * @param  array<string, array<string, mixed>>  $semakPayload
     * @param  array<string, \App\Models\TenderPembukaEvaluation>  $evaluations
     * @return array<string, array<string, mixed>>
     */
    protected function mergeEvaluationsIntoPayload(array $semakPayload, array $evaluations): array
    {
        foreach ($semakPayload as $uuid => &$payload) {
            foreach ($payload['vendors'] as &$vendorRow) {
                $vendorId = (int) $vendorRow['vendor_id'];
                $evalKey  = "{$vendorId}:{$uuid}";
                $eval     = $evaluations[$evalKey] ?? null;

                $vendorRow['status_pematuhan'] = $eval ? $eval->status_pematuhan : null; // null = belum disemak
                $vendorRow['catatan']          = $eval ? $eval->catatan : null;
            }
            unset($vendorRow);
        }
        unset($payload);

        return $semakPayload;
    }

    /**
     * Build semakPayload keyed by checklist item UUID.
     * (Preserved from original controller, unchanged.)
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
                    // Evaluation fields (will be merged in show())
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
}
