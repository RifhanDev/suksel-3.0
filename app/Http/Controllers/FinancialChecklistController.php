<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\PenyediaanIklanNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinancialChecklistController extends Controller
{
    use UpdatesTenderProcessAfterChecklistSubmit;
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $apiUrl  = $this->url('financial-checklists/' . $tenderUuid);
        $response = $this->api()->get($apiUrl);

        $checklistData = null;

        if ($response->successful()) {
            $checklistData = $response->json('data');
        } else {
            Log::warning('FinancialChecklistController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'api_url'     => $apiUrl,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        // Check if $checklistData['items'] already has a specification_document item (linked via technical_item_id)
        $hasSpecItem = collect($checklistData['items'] ?? [])->contains(function ($item) {
            return ($item['source_type'] ?? '') === 'specification_document' || ($item['mechanism'] ?? '') === 'spesifikasi';
        });

        // Only fetch and merge from technical checklist if financial checklist does NOT have it yet
        if (!$hasSpecItem) {
            $specItems = [];
            try {
                $techApiUrl = $this->url('technical-checklists/' . $tenderUuid);
                $techResponse = $this->api()->get($techApiUrl);
                if ($techResponse->successful()) {
                    $techItems = $techResponse->json('data.items') ?? [];
                    foreach ($techItems as $tItem) {
                        if (($tItem['source_type'] ?? '') === 'specification_document' || ($tItem['mechanism'] ?? '') === 'spesifikasi') {
                            $specItems[] = $tItem;
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('FinancialChecklistController@index: STOS API request for technical-checklists failed', [
                    'error' => $e->getMessage(),
                ]);
            }

            // Local DB fallback for specification_document items if API returned empty
            if (empty($specItems)) {
                $dbSpecItems = \Illuminate\Support\Facades\DB::table('technical_checklist_items as tci')
                    ->join('technical_checklist_headers as tch', 'tch.id', '=', 'tci.technical_checklist_header_id')
                    ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
                    ->where('tch.tender_id', $tender->id)
                    ->where(function ($q) {
                        $q->where('tci.source_type', 'specification_document')
                          ->orWhereNotNull('tci.specification_document_id');
                    })
                    ->select([
                        'tci.uuid',
                        'tci.source_type',
                        'tci.title',
                        'tci.mechanism',
                        'tci.score',
                        'tci.status',
                        'tsd.uuid as specification_document_uuid',
                    ])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'uuid' => $item->uuid,
                            'source_type' => 'specification_document',
                            'title' => $item->title,
                            'mechanism' => 'spesifikasi',
                            'score' => (float) $item->score,
                            'status' => $item->status ?: 'submitted',
                            'specification_document_uuid' => $item->specification_document_uuid ?: $item->uuid,
                            'technical_item_uuid' => $item->specification_document_uuid ?: $item->uuid,
                        ];
                    })
                    ->all();

                $specItems = $dbSpecItems;
            }

            if (!empty($specItems)) {
                if (!$checklistData) {
                    $checklistData = ['items' => []];
                }
                if (!isset($checklistData['items']) || !is_array($checklistData['items'])) {
                    $checklistData['items'] = [];
                }
                foreach ($specItems as $sItem) {
                    array_unshift($checklistData['items'], $sItem);
                }
            }
        }

        $standardItems = [];
        try {
            $standardApiUrl   = $this->url('standard-checklist-items?category=financial');
            $standardResponse = $this->api()->get($standardApiUrl);

            if ($standardResponse->successful()) {
                $standardItems = $standardResponse->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            Log::warning('FinancialChecklistController@index: STOS API request failed, using local DB fallback', [
                'error' => $e->getMessage(),
            ]);
        }

        if (empty($standardItems)) {
            $standardItems = \Illuminate\Support\Facades\DB::table('standard_checklist_items')
                ->where('category', 'financial')
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        }

        return view('jawatankuasaSpesifikasi.senarai_kewangan_bekalan', [
            'tender' => $tender,
            'checklistData' => $checklistData,
            'standardItems' => $standardItems,
            'afterSpecificationUrl' => PenyediaanIklanNavigation::afterSpecificationUrl($tender),
        ]);
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('financial-checklists/' . $tenderUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('financial-checklists/' . $tenderUuid . '/submit'), $request->except('_token'));

        if ($response->successful()) {
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
        }

        return response()->json($response->json(), $response->status());
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->attach(
            'file',
            $request->file('file')->get(),
            $request->file('file')->getClientOriginalName()
        )->post($this->url('financial-checklists/' . $tenderUuid . '/files'), $request->except(['_token', 'file']));

        return response()->json($response->json(), $response->status());
    }

    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->delete($this->url('financial-checklist-files/' . $fileUuid));

        return response()->json($response->json(), $response->status());
    }

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function api()
    {
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }
}
