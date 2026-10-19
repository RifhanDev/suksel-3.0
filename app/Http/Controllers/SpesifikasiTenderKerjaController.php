<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\SpesifikasiKerjaFile;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\SpesifikasiKerjaItem;
use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\StosStoredFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpesifikasiTenderKerjaController extends Controller
{
    use UpdatesTenderProcessAfterChecklistSubmit;
    /**
     * Display the Penyediaan Spesifikasi Tender page with existing data.
     */
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
            $apiUrl   = $this->url('spesifikasi-kerja/' . $tenderUuid);
            $response = $this->api()->get($apiUrl);

            if ($response->successful()) {
                $checklistData = $response->json('data');
            } else {
                Log::warning('SpesifikasiTenderKerjaController@index: API request failed', [
                    'tender_uuid' => $tenderUuid,
                    'api_url'     => $apiUrl,
                    'status'      => $response->status(),
                    'body'        => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('SpesifikasiTenderKerjaController@index: STOS API connection failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);
        }

        if (! $checklistData) {
            $header = SpesifikasiKerjaHeader::where('tender_id', $tender->id)
                ->with(['items.files', 'items.specs.files', 'files'])
                ->first();

            if ($header) {
                $checklistData = $this->serializeLocalSpesifikasi($header, $tender);
            }
        }

        return view(
            'newModule.jawatankuasaSpesifikasi.form_penyediaan_spesifikasi_tender',
            compact('tender', 'checklistData')
        );
    }

    /**
     * Save (draft) the specification items — proxied to backend API.
     */
    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('spesifikasi-kerja/' . $tenderUuid),
            $request->except('_token')
        );

        return response()->json($response->json(), $response->status());
    }

    /**
     * Submit the specification — proxied to backend API.
     */
    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('spesifikasi-kerja/' . $tenderUuid . '/submit'),
            $request->except('_token')
        );

        if ($response->successful()) {
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
        }

        return response()->json($response->json(), $response->status());
    }

    /**
     * Upload a per-item supporting document — stored directly on SUKSel 3.0 server storage.
     */
    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'file_type' => ['nullable', 'string', 'max:50'],
            'checklist_item_uuid' => ['nullable', 'string'],
            'spesifikasi_kerja_item_uuid' => ['nullable', 'string'],
        ]);

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();

        $header = SpesifikasiKerjaHeader::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'uuid' => (string) Str::uuid(),
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]
        );

        $itemUuid = $request->input('checklist_item_uuid') ?: $request->input('spesifikasi_kerja_item_uuid');
        $item = null;
        if (!empty($itemUuid)) {
            $item = SpesifikasiKerjaItem::where('spesifikasi_kerja_header_id', $header->id)
                ->where('uuid', $itemUuid)
                ->first();

            if (!$item) {
                $item = SpesifikasiKerjaItem::where('uuid', $itemUuid)->first();
            }
        }

        $uploadedFile = $request->file('file');
        $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
        $storedName = now()->format('YmdHis') . '_' . Str::uuid() . '_' . $safeOriginalName;
        $path = $uploadedFile->storeAs('spesifikasi-kerja/' . $tender->uuid, $storedName, 'public');

        $file = SpesifikasiKerjaFile::create([
            'uuid' => (string) Str::uuid(),
            'spesifikasi_kerja_header_id' => $header->id,
            'spesifikasi_kerja_item_id' => $item?->id,
            'file_type' => $request->input('file_type') ?: 'support',
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize() ?: 0,
            'uploaded_by' => auth()->id(),
        ]);

        $downloadUrl = route('penyediaanSpekTender.downloadFile', [
            'tenderUuid' => $tender->uuid,
            'fileUuid' => $file->uuid,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dimuat naik ke storan SUKSel 3.0.',
            'data' => [
                'uuid' => $file->uuid,
                'checklist_item_uuid' => $item?->uuid,
                'spesifikasi_kerja_item_uuid' => $item?->uuid,
                'file_type' => $file->file_type,
                'original_name' => $file->original_name,
                'stored_name' => $file->stored_name,
                'path' => $file->path,
                'mime_type' => $file->mime_type,
                'size' => (int) $file->size,
                'url' => $downloadUrl,
                'uploaded_by' => $file->uploaded_by,
                'created_at' => $file->created_at?->toISOString(),
            ],
        ], 200);
    }

    /**
     * Open an uploaded specification file through this app.
     */
    public function downloadFile(string $tenderUuid, string $fileUuid)
    {
        $this->ensureAccess();

        $tender = Tender::query()->where('uuid', $tenderUuid)->firstOrFail();

        $file = SpesifikasiKerjaFile::query()
            ->where('uuid', $fileUuid)
            ->whereHas('header', fn ($query) => $query->where('tender_id', $tender->id))
            ->first();

        if ($file && $file->path) {
            $path = ltrim((string) $file->path, '/');
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->response($path, $file->original_name, [
                    'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
                ]);
            }
        }

        if (! $file) {
            $file = $this->findRemoteSpecificationFile($tenderUuid, $fileUuid);
        }

        if (! $file) {
            abort(404, 'Fail tidak dijumpai.');
        }

        $name = is_array($file) ? ($file['original_name'] ?? 'Dokumen') : $file->original_name;
        $path = is_array($file) ? ($file['path'] ?? null) : $file->path;
        $mime = is_array($file) ? ($file['mime_type'] ?? null) : $file->mime_type;

        return StosStoredFile::response([
            'original_name' => $name ?: 'Dokumen',
            'path' => $path,
            'mime_type' => $mime,
            'download_api' => 'spesifikasi-kerja-files/' . $fileUuid . '/download',
        ]);
    }

    /**
     * Delete an uploaded file — deleted directly on SUKSel 3.0 server storage.
     */
    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $file = SpesifikasiKerjaFile::where('uuid', $fileUuid)->first();
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

        try {
            $response = $this->api()->delete(
                $this->url('spesifikasi-kerja-files/' . $fileUuid)
            );
            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }
        } catch (\Throwable $e) {
            Log::warning('SpesifikasiTenderKerjaController@deleteFile: STOS API fallback failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fail dipadam.',
        ]);
    }

    private function serializeLocalSpesifikasi(SpesifikasiKerjaHeader $header, Tender $tender): array
    {
        return [
            'uuid' => $header->uuid,
            'tender_uuid' => $tender->uuid,
            'status' => $header->status,
            'submitted_at' => optional($header->submitted_at)->toIso8601String(),
            'items' => $header->items->map(function ($item) use ($tender) {
                return [
                    'uuid' => $item->uuid,
                    'nama_item' => $item->nama_item,
                    'spesifikasi' => $item->spesifikasi,
                    'unit' => $item->unit,
                    'kuantiti' => (float) $item->kuantiti,
                    'kadar' => (float) $item->kadar,
                    'jumlah' => $item->jumlah(),
                    'catatan' => $item->catatan,
                    'sort_order' => (int) $item->sort_order,
                    'files' => $item->files->map(fn ($f) => [
                        'uuid' => $f->uuid,
                        'file_type' => $f->file_type,
                        'original_name' => $f->original_name,
                        'stored_name' => $f->stored_name,
                        'path' => $f->path,
                        'mime_type' => $f->mime_type,
                        'size' => (int) $f->size,
                        'url' => route('penyediaanSpekTender.downloadFile', ['tenderUuid' => $tender->uuid, 'fileUuid' => $f->uuid]),
                    ])->values()->all(),
                    'specs' => $item->specs->map(function ($spec) use ($tender) {
                        return [
                            'uuid' => $spec->uuid,
                            'nama_item' => $spec->nama_item,
                            'spesifikasi' => $spec->spesifikasi,
                            'unit' => $spec->unit,
                            'kuantiti' => (float) $spec->kuantiti,
                            'kadar' => (float) $spec->kadar,
                            'jumlah' => $spec->jumlah(),
                            'catatan' => $spec->catatan,
                            'sort_order' => (int) $spec->sort_order,
                            'files' => $spec->files->map(fn ($f) => [
                                'uuid' => $f->uuid,
                                'file_type' => $f->file_type,
                                'original_name' => $f->original_name,
                                'stored_name' => $f->stored_name,
                                'path' => $f->path,
                                'mime_type' => $f->mime_type,
                                'size' => (int) $f->size,
                                'url' => route('penyediaanSpekTender.downloadFile', ['tenderUuid' => $tender->uuid, 'fileUuid' => $f->uuid]),
                            ])->values()->all(),
                        ];
                    })->values()->all(),
                ];
            })->values()->all(),
            'files' => $header->files
                ->filter(fn ($file) => $file->spesifikasi_kerja_item_id === null)
                ->map(fn ($f) => [
                    'uuid' => $f->uuid,
                    'file_type' => $f->file_type,
                    'original_name' => $f->original_name,
                    'stored_name' => $f->stored_name,
                    'path' => $f->path,
                    'mime_type' => $f->mime_type,
                    'size' => (int) $f->size,
                    'url' => route('penyediaanSpekTender.downloadFile', ['tenderUuid' => $tender->uuid, 'fileUuid' => $f->uuid]),
                ])
                ->values()
                ->all(),
        ];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function api()
    {
        return Http::withoutVerifying()->timeout(30)->withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ]);
    }

    private function url(string $path): string
    {
        return config('services.stos_backend.url') . '/api/' . $path;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findRemoteSpecificationFile(string $tenderUuid, string $fileUuid): ?array
    {
        $response = $this->api()->get($this->url('spesifikasi-kerja/' . $tenderUuid));
        if (! $response->successful()) {
            return null;
        }

        $data = $response->json('data') ?? [];
        $files = $data['files'] ?? [];

        foreach ($data['items'] ?? [] as $item) {
            if (! is_array($item)) {
                continue;
            }

            foreach ($item['files'] ?? [] as $file) {
                $files[] = $file;
            }

            foreach ($item['specs'] ?? [] as $spec) {
                if (! is_array($spec)) {
                    continue;
                }

                foreach ($spec['files'] ?? [] as $file) {
                    $files[] = $file;
                }
            }
        }

        foreach ($files as $file) {
            if (is_array($file) && ($file['uuid'] ?? '') === $fileUuid) {
                return $file;
            }
        }

        return null;
    }
}
