<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\FinancialChecklistFile;
use App\Models\FinancialChecklistHeader;
use App\Models\FinancialChecklistItem;
use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\PenyediaanIklanNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $checklistData = null;

        try {
            $apiUrl  = $this->url('financial-checklists/' . $tenderUuid);
            $response = $this->api()->get($apiUrl);

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
        } catch (\Throwable $e) {
            Log::warning('FinancialChecklistController@index: STOS API connection failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);
        }

        if (! $checklistData) {
            $header = FinancialChecklistHeader::where('tender_id', $tender->id)
                ->with(['items.files', 'files'])
                ->first();

            if ($header) {
                $checklistData = $this->serializeLocalChecklist($header, $tender);
            }
        }

        if (!empty($checklistData['items']) && is_array($checklistData['items'])) {
            $dbTechScores = \Illuminate\Support\Facades\DB::table('technical_checklist_items as tci')
                ->join('technical_checklist_headers as tch', 'tch.id', '=', 'tci.technical_checklist_header_id')
                ->where('tch.tender_id', $tender->id)
                ->where(function ($q) {
                    $q->where('tci.source_type', 'specification_document')
                      ->orWhere('tci.mechanism', 'spesifikasi');
                })
                ->pluck('tci.score', 'tci.uuid')
                ->all();

            foreach ($checklistData['items'] as &$item) {
                if (($item['source_type'] ?? '') === 'specification_document' || ($item['mechanism'] ?? '') === 'spesifikasi') {
                    if (empty($item['score']) || (float)$item['score'] == 0) {
                        $techUuid = $item['technical_item_uuid'] ?? $item['specification_document_uuid'] ?? null;
                        if ($techUuid && isset($dbTechScores[$techUuid])) {
                            $item['score'] = (float) $dbTechScores[$techUuid];
                        } elseif (!empty($dbTechScores)) {
                            $item['score'] = (float) reset($dbTechScores);
                        }
                    }
                }
            }
            unset($item);
        }

        // Drop orphaned kewangan "spesifikasi" copies whose teknikal row was deleted
        // (FK is nullOnDelete, so copies can linger after senarai-teknikal hapus).
        if (! empty($checklistData['items']) && is_array($checklistData['items'])) {
            $checklistData['items'] = array_values(array_filter(
                $checklistData['items'],
                function ($item) {
                    $isSpec = ($item['source_type'] ?? '') === 'specification_document'
                        || ($item['mechanism'] ?? '') === 'spesifikasi';
                    if (! $isSpec) {
                        return true;
                    }

                    // STOS serializeItem only sets technical_item_uuid when the link is live.
                    return ! empty($item['technical_item_uuid'] ?? null);
                }
            ));
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

        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'file_type' => ['nullable', 'string', 'max:50'],
            'checklist_item_uuid' => ['nullable', 'string'],
        ]);

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();

        $header = FinancialChecklistHeader::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'uuid' => (string) Str::uuid(),
                'created_by' => auth()->id(),
            ]
        );

        $item = null;
        $itemUuid = $request->input('checklist_item_uuid');
        if (!empty($itemUuid)) {
            $item = FinancialChecklistItem::where('financial_checklist_header_id', $header->id)
                ->where('uuid', $itemUuid)
                ->first();

            if (!$item) {
                $item = FinancialChecklistItem::where('uuid', $itemUuid)->first();
            }
        }

        $uploadedFile = $request->file('file');
        $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
        $storedName = now()->format('YmdHis') . '_' . Str::uuid() . '_' . $safeOriginalName;
        $path = $uploadedFile->storeAs('financial-checklists/' . $tender->uuid, $storedName, 'public');

        $file = FinancialChecklistFile::create([
            'uuid' => (string) Str::uuid(),
            'financial_checklist_header_id' => $header->id,
            'financial_checklist_item_id' => $item?->id,
            'file_type' => $request->input('file_type') ?: 'support',
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize() ?: 0,
            'uploaded_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dimuat naik ke storan SUKSel 3.0.',
            'data' => $this->serializeFile($file, $tender, $item),
        ], 200);
    }

    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $file = FinancialChecklistFile::where('uuid', $fileUuid)->first();
        if ($file) {
            if ($file->path && Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fail berjaya dipadam dari storan SUKSel 3.0.',
            ]);
        }

        // Backward compatibility fallback if row only exists on STOS API
        try {
            $response = $this->api()->delete($this->url('financial-checklist-files/' . $fileUuid));
            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }
        } catch (\Throwable $e) {
            Log::warning('FinancialChecklistController@deleteFile: STOS API fallback failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fail dipadam.',
        ]);
    }

    private function serializeFile(FinancialChecklistFile $file, ?Tender $tender = null, ?FinancialChecklistItem $item = null): array
    {
        $tenderId = $tender?->id ?? $file->header?->tender_id ?? 0;
        $downloadUrl = $tenderId > 0
            ? route('tenderChecklist.download', [
                'tender' => $tenderId,
                'section' => 'financial',
                'fileUuid' => $file->uuid,
            ])
            : Storage::disk('public')->url($file->path);

        return [
            'uuid' => $file->uuid,
            'checklist_item_uuid' => $item?->uuid ?? $file->item?->uuid,
            'file_type' => $file->file_type,
            'original_name' => $file->original_name,
            'stored_name' => $file->stored_name,
            'path' => $file->path,
            'mime_type' => $file->mime_type,
            'size' => (int) $file->size,
            'url' => $downloadUrl,
            'uploaded_by' => $file->uploaded_by,
            'created_at' => $file->created_at?->toISOString(),
        ];
    }

    private function serializeLocalChecklist(FinancialChecklistHeader $header, Tender $tender): array
    {
        return [
            'uuid' => $header->uuid,
            'tender_uuid' => $tender->uuid,
            'max_score' => (float) $header->max_score,
            'passing_score' => (float) $header->passing_score,
            'passing_percentage' => (float) $header->passing_percentage,
            'status' => $header->status,
            'submitted_at' => optional($header->submitted_at)->toIso8601String(),
            'items' => $header->items->map(function ($item) use ($tender) {
                return [
                    'uuid' => $item->uuid,
                    'source_type' => $item->source_type,
                    'title' => $item->title,
                    'mechanism' => $item->mechanism,
                    'vendor_action' => $item->vendor_action,
                    'score' => (float) $item->score,
                    'status' => $item->status,
                    'sort_order' => (int) $item->sort_order,
                    'standard_item_uuid' => null,
                    'files' => $item->files->map(fn ($f) => $this->serializeFile($f, $tender, $item))->values()->all(),
                ];
            })->values()->all(),
            'files' => $header->files
                ->filter(fn ($file) => $file->financial_checklist_item_id === null)
                ->map(fn ($file) => $this->serializeFile($file, $tender))
                ->values()
                ->all(),
            'created_at' => optional($header->created_at)->toIso8601String(),
            'updated_at' => optional($header->updated_at)->toIso8601String(),
        ];
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
