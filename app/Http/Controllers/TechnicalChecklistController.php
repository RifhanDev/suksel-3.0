<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\TechnicalChecklistFile;
use App\Models\TechnicalChecklistHeader;
use App\Models\TechnicalChecklistItem;
use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TechnicalChecklistController extends Controller
{
    use UpdatesTenderProcessAfterChecklistSubmit;
    public function index(string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $checklistData = null;

        try {
            $apiUrl = $this->url('technical-checklists/' . $tenderUuid);
            $response = $this->api()->get($apiUrl);

            if ($response->successful()) {
                $checklistData = $response->json('data');
            } else {
                Log::warning('TechnicalChecklistController@index: API request failed', [
                    'tender_uuid' => $tenderUuid,
                    'tender_id'   => $tender->id,
                    'api_url'     => $apiUrl,
                    'status'      => $response->status(),
                    'body'        => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('TechnicalChecklistController@index: STOS API connection failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);
        }

        // Local DB fallback for checklistData if API failed or returned empty
        if (! $checklistData) {
            $header = TechnicalChecklistHeader::where('tender_id', $tender->id)
                ->with(['items.files', 'files'])
                ->first();

            if ($header) {
                $checklistData = $this->serializeLocalChecklist($header, $tender);
            }
        }

        $standardItems = [];
        try {
            $standardApiUrl   = $this->url('standard-checklist-items?category=technical');
            $standardResponse = $this->api()->get($standardApiUrl);

            if ($standardResponse->successful()) {
                $standardItems = $standardResponse->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            Log::warning('TechnicalChecklistController@index: STOS API request failed, using local DB fallback', [
                'error' => $e->getMessage(),
            ]);
        }

        if (empty($standardItems)) {
            $standardItems = \Illuminate\Support\Facades\DB::table('standard_checklist_items')
                ->where('category', 'technical')
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        }

        return view('TechnicalChecklist.senarai_teknikal', compact('tender', 'checklistData', 'standardItems'));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $response = $this->api()->post($this->url('technical-checklists/' . $tenderUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $response = $this->api()->post($this->url('technical-checklists/' . $tenderUuid . '/submit'), $request->except('_token'));

        if ($response->successful()) {
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
        }

        return response()->json($response->json(), $response->status());
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'file_type' => ['nullable', 'string', 'max:50'],
            'checklist_item_uuid' => ['nullable', 'string'],
        ]);

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();

        $header = TechnicalChecklistHeader::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'uuid' => (string) Str::uuid(),
                'created_by' => auth()->id(),
            ]
        );

        $item = null;
        $itemUuid = $request->input('checklist_item_uuid');
        if (!empty($itemUuid)) {
            $item = TechnicalChecklistItem::where('technical_checklist_header_id', $header->id)
                ->where('uuid', $itemUuid)
                ->first();

            if (!$item) {
                $item = TechnicalChecklistItem::where('uuid', $itemUuid)->first();
            }
        }

        $uploadedFile = $request->file('file');
        $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
        $storedName = now()->format('YmdHis') . '_' . Str::uuid() . '_' . $safeOriginalName;
        $path = $uploadedFile->storeAs('technical-checklists/' . $tender->uuid, $storedName, 'public');

        $file = TechnicalChecklistFile::create([
            'uuid' => (string) Str::uuid(),
            'technical_checklist_header_id' => $header->id,
            'technical_checklist_item_id' => $item?->id,
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
        $this->ensureSpecificationAccess();

        $file = TechnicalChecklistFile::where('uuid', $fileUuid)->first();
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
            $response = $this->api()->delete($this->url('technical-checklist-files/' . $fileUuid));
            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }
        } catch (\Throwable $e) {
            Log::warning('TechnicalChecklistController@deleteFile: STOS API fallback failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fail dipadam.',
        ]);
    }

    private function serializeFile(TechnicalChecklistFile $file, ?Tender $tender = null, ?TechnicalChecklistItem $item = null): array
    {
        $tenderId = $tender?->id ?? $file->header?->tender_id ?? 0;
        $downloadUrl = $tenderId > 0
            ? route('tenderChecklist.download', [
                'tender' => $tenderId,
                'section' => 'technical',
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

    private function serializeLocalChecklist(TechnicalChecklistHeader $header, Tender $tender): array
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
                    'specification_document_uuid' => null,
                    'files' => $item->files->map(fn ($f) => $this->serializeFile($f, $tender, $item))->values()->all(),
                ];
            })->values()->all(),
            'files' => $header->files
                ->filter(fn ($file) => $file->technical_checklist_item_id === null)
                ->map(fn ($file) => $this->serializeFile($file, $tender))
                ->values()
                ->all(),
            'created_at' => optional($header->created_at)->toIso8601String(),
            'updated_at' => optional($header->updated_at)->toIso8601String(),
        ];
    }

    private function ensureSpecificationAccess(): void
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
