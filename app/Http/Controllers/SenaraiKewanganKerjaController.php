<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\KewanganKerjaFile;
use App\Models\KewanganKerjaHeader;
use App\Models\KewanganKerjaItem;
use App\Models\PenyataBank;
use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\PenyediaanIklanNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SenaraiKewanganKerjaController extends Controller
{
    use UpdatesTenderProcessAfterChecklistSubmit;
    /**
     * Display the Senarai Kewangan Kerja page with existing checklist data.
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
            $apiUrl   = $this->url('kewangan-kerja/' . $tenderUuid);
            $response = $this->api()->get($apiUrl);

            if ($response->successful()) {
                $checklistData = $response->json('data');
            } else {
                Log::warning('SenaraiKewanganKerjaController@index: API request failed', [
                    'tender_uuid' => $tenderUuid,
                    'api_url'     => $apiUrl,
                    'status'      => $response->status(),
                    'body'        => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('SenaraiKewanganKerjaController@index: STOS API connection failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);
        }

        if (! $checklistData) {
            $header = KewanganKerjaHeader::where('tender_id', $tender->id)
                ->with(['items.files', 'files'])
                ->first();

            if ($header) {
                $checklistData = $this->serializeLocalKewanganKerja($header, $tender);
            }
        }

        // Collect standard_item_uuids currently in use in tbl-kewangan
        $usedStandardUuids = array_values(array_filter(
            array_map(fn ($item) => $item['standard_item_uuid'] ?? null, $checklistData['items'] ?? [])
        ));

        // Load standard items for the "Senarai Semak Standard" modal (excluding mandatory 'borang_atas_talian' and already used items)
        $standardItems = [];
        try {
            $standardResponse = $this->api()->get($this->url('standard-checklist-items?category=kewangan_kerja'));

            if ($standardResponse->successful()) {
                $standardItems = $standardResponse->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            Log::warning('SenaraiKewanganKerjaController@index: STOS API request failed, using local DB fallback', [
                'error' => $e->getMessage(),
            ]);
        }

        if (empty($standardItems)) {
            $standardItems = \Illuminate\Support\Facades\DB::table('standard_checklist_items')
                ->where('category', 'kewangan_kerja')
                ->where('type', '!=', 'borang_atas_talian')
                ->when(!empty($usedStandardUuids), fn ($q) => $q->whereNotIn('uuid', $usedStandardUuids))
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        } else {
            $standardItems = array_values(array_filter(
                $standardItems,
                fn ($item) => ($item['type'] ?? '') !== 'borang_atas_talian'
                    && !in_array($item['uuid'] ?? '', $usedStandardUuids, true)
            ));
        }

        return view(
            'newModule.jawatankuasaSpesifikasi.senarai_kewangan_kerja',
            [
                'tender' => $tender,
                'physicalSubmissionEnabled' => (int) $tender->penilaian_fizikal === 1,
                // Reuse the canonical procurement resolver without changing this controller's model.
                'procurementLabel' => (new \App\Tender())->newFromBuilder($tender->getAttributes())->isSebutHargaKaedah()
                    ? 'Sebut Harga' : 'Tender',
                'checklistData' => $checklistData,
                'standardItems' => $standardItems,
                'afterSpecificationUrl' => PenyediaanIklanNavigation::afterSpecificationUrl($tender),
            ]
        );
    }

    /**
     * Save draft — proxied to STOS-EPENILAIAN-WEB.
     */
    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('kewangan-kerja/' . $tenderUuid),
            $request->except('_token')
        );

        return response()->json($response->json(), $response->status());
    }

    /**
     * Submit checklist — proxied to STOS-EPENILAIAN-WEB.
     */
    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('kewangan-kerja/' . $tenderUuid . '/submit'),
            $request->except('_token')
        );

        if ($response->successful()) {
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
        }

        return response()->json($response->json(), $response->status());
    }

    /**
     * Upload a file — stored directly on SUKSel 3.0 server storage.
     */
    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $request->validate([
            'file' => \App\Support\AllowedAttachment::rules(true, 25600),
            'file_type' => ['nullable', 'string', 'max:50'],
            'checklist_item_uuid' => ['nullable', 'string'],
        ]);

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();

        $header = KewanganKerjaHeader::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'uuid' => (string) Str::uuid(),
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]
        );

        $item = null;
        $itemUuid = $request->input('checklist_item_uuid');
        if (!empty($itemUuid)) {
            $item = KewanganKerjaItem::where('kewangan_kerja_header_id', $header->id)
                ->where('uuid', $itemUuid)
                ->first();

            if (!$item) {
                $item = KewanganKerjaItem::where('uuid', $itemUuid)->first();
            }
        }

        $file = DB::transaction(function () use ($item, $tender, $header, $request) {
            if ($item) {
                $item = KewanganKerjaItem::whereKey($item->id)->lockForUpdate()->firstOrFail();
                if ($item->mechanism === 'ptj_muat_naik' && $item->files()->exists()) {
                    throw ValidationException::withMessages([
                        'file' => ['Setiap item PTJ Muat Naik hanya dibenarkan mempunyai satu dokumen. Padam dokumen sedia ada sebelum memuat naik yang baharu.'],
                    ]);
                }
            }

            $uploadedFile = $request->file('file');
            $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
            $storedName = now()->format('YmdHis') . '_' . Str::uuid() . '_' . $safeOriginalName;
            $path = \App\Support\AttachmentStorage::storeAs($uploadedFile, 'kewangan-kerja/' . $tender->uuid, $storedName);

            return KewanganKerjaFile::create([
                'uuid' => (string) Str::uuid(),
                'kewangan_kerja_header_id' => $header->id,
                'kewangan_kerja_item_id' => $item?->id,
                'file_type' => $request->input('file_type') ?: 'support',
                'original_name' => $uploadedFile->getClientOriginalName(),
                'stored_name' => $storedName,
                'path' => $path,
                'mime_type' => $uploadedFile->getClientMimeType(),
                'size' => $uploadedFile->getSize() ?: 0,
                'uploaded_by' => auth()->id(),
            ]);
        });

        $downloadUrl = route('tenderChecklist.download', [
            'tender' => $tender->id,
            'section' => 'kewangan_kerja',
            'fileUuid' => $file->uuid,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dimuat naik ke storan SUKSel 3.0.',
            'data' => [
                'uuid' => $file->uuid,
                'checklist_item_uuid' => $item?->uuid,
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
     * Delete a file — deleted directly on SUKSel 3.0 server storage.
     */
    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $file = KewanganKerjaFile::where('uuid', $fileUuid)->first();
        if ($file) {
            if ($file->path && \App\Support\AttachmentStorage::exists($file->path)) {
                \App\Support\AttachmentStorage::delete($file->path);
            }
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fail berjaya dipadam dari storan SUKSel 3.0.',
            ]);
        }

        try {
            $response = $this->api()->delete(
                $this->url('kewangan-kerja-files/' . $fileUuid)
            );
            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }
        } catch (\Throwable $e) {
            Log::warning('SenaraiKewanganKerjaController@deleteFile: STOS API fallback failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fail dipadam.',
        ]);
    }

    private function serializeLocalKewanganKerja(KewanganKerjaHeader $header, Tender $tender): array
    {
        $penyataBankFormUpdated = $this->isPenyataBankFormUpdated($tender);

        return [
            'uuid' => $header->uuid,
            'tender_uuid' => $tender->uuid,
            'max_score' => (float) $header->max_score,
            'passing_score' => (float) $header->passing_score,
            'passing_percentage' => (float) $header->passing_percentage,
            'status' => $header->status,
            'submitted_at' => optional($header->submitted_at)->toIso8601String(),
            'items' => $header->items->map(function ($item) use ($tender, $penyataBankFormUpdated) {
                $isPenyataBankItem = $item->title === 'Penyata Bulanan / Akaun Bank';
                $mechanism = $item->mechanism;
                $vendorAction = $item->vendor_action;
                if ((int) $tender->penilaian_fizikal !== 1
                    && ($mechanism === 'serahan_tangan' || $vendorAction === 'serahan_tangan')) {
                    $mechanism = 'petender_muat_naik';
                    $vendorAction = 'muat_naik';
                }

                return [
                    'uuid' => $item->uuid,
                    'source_type' => $item->source_type,
                    'title' => $item->title,
                    'mechanism' => $mechanism,
                    'vendor_action' => $vendorAction,
                    'penghantaran_fizikal' => (int) $tender->penilaian_fizikal === 1 && (bool) $item->penghantaran_fizikal,
                    'score' => (float) $item->score,
                    'status' => $isPenyataBankItem
                        ? ($penyataBankFormUpdated && (float) $item->score > 0 ? 'submitted' : 'draft')
                        : $item->status,
                    'form_updated' => $isPenyataBankItem ? $penyataBankFormUpdated : true,
                    'sort_order' => (int) $item->sort_order,
                    'standard_item_uuid' => null,
                    'files' => $item->files->map(fn ($f) => [
                        'uuid' => $f->uuid,
                        'file_type' => $f->file_type,
                        'original_name' => $f->original_name,
                        'stored_name' => $f->stored_name,
                        'path' => $f->path,
                        'mime_type' => $f->mime_type,
                        'size' => (int) $f->size,
                        'url' => route('tenderChecklist.download', ['tender' => $tender->id, 'section' => 'kewangan_kerja', 'fileUuid' => $f->uuid]),
                    ])->values()->all(),
                ];
            })->values()->all(),
            'files' => $header->files
                ->filter(fn ($file) => $file->kewangan_kerja_item_id === null)
                ->map(fn ($f) => [
                    'uuid' => $f->uuid,
                    'file_type' => $f->file_type,
                    'original_name' => $f->original_name,
                    'stored_name' => $f->stored_name,
                    'path' => $f->path,
                    'mime_type' => $f->mime_type,
                    'size' => (int) $f->size,
                    'url' => route('tenderChecklist.download', ['tender' => $tender->id, 'section' => 'kewangan_kerja', 'fileUuid' => $f->uuid]),
                ])
                ->values()
                ->all(),
        ];
    }

    private function isPenyataBankFormUpdated(Tender $tender): bool
    {
        $penyata = PenyataBank::where('tender_id', $tender->id)->first();

        return $penyata !== null
            && $penyata->dari_bulan !== null
            && $penyata->dari_tahun !== null;
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
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }
}
