<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Http\Controllers\Concerns\AuthorizesTenderFileAccess;
use App\Models\TenderVendorDokumenFile;
use App\Services\VendorDokumenResponseService;
use App\Support\TenderDokumenPresenter;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VendorTenderDokumenController extends Controller
{
    use HandlesTenderFormAccess;
    use AuthorizesTenderFileAccess;

    public function __construct(
        protected VendorDokumenResponseService $responses,
        protected \App\Services\VendorTenderSubmissionService $submissions,
    ) {}

    public function upload(Request $request, Tender $tender, string $itemUuid)
    {
        $vendorId = $this->requireVendorId();
        $this->submissions->assertEditable($tender, $vendorId);
        $item = $this->findChecklistItem($tender, $itemUuid, 'vendor', $vendorId);

        if (! in_array($item['action'], ['vendor_upload', 'download_upload'], true)) {
            throw ValidationException::withMessages([
                'item' => 'Item ini tidak memerlukan muat naik dokumen.',
            ]);
        }

        $data = $request->validate([
            'file' => 'required|file|max:10240',
            'section' => 'required|string|in:technical,financial,kewangan_kerja,spesifikasi_kerja',
        ]);

        if ($data['section'] !== $item['section']) {
            throw ValidationException::withMessages([
                'section' => 'Seksyen dokumen tidak sepadan.',
            ]);
        }

        $file = $this->responses->uploadFile(
            $tender,
            $vendorId,
            $itemUuid,
            $data['section'],
            $data['file']
        );

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dimuat naik.',
            'data' => [
                'uuid' => $file->uuid,
                'name' => $file->original_name,
                'url' => $file->url(),
                'size' => $file->size,
            ],
        ]);
    }

    public function deleteFile(string $fileUuid)
    {
        $vendorId = $this->requireVendorId();
        $file = TenderVendorDokumenFile::query()->where('uuid', $fileUuid)->firstOrFail();
        $tender = \App\Tender::query()->findOrFail($file->tender_id);
        $this->submissions->assertEditable($tender, $vendorId);

        $this->responses->deleteFile($file, $vendorId);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dibuang.',
        ]);
    }

    public function download(string $fileUuid)
    {
        $file = TenderVendorDokumenFile::query()->where('uuid', $fileUuid)->first();

        if (! $file) {
            $pbFile = \App\Models\PenyataBankFile::query()->where('uuid', $fileUuid)->first();
            if ($pbFile) {
                $pbFile->loadMissing('penyataBank');
                $tenderId = (int) ($pbFile->penyataBank?->tender_id ?? 0);
                if ($tenderId > 0) {
                    $tender = Tender::query()->find($tenderId);
                    if ($tender) {
                        $this->assertCanAccessTenderFile($tender, null);
                    }
                }

                $rawPath = ltrim(str_replace('public/', '', (string) $pbFile->path), '/');
                $candidates = [
                    storage_path('app/private/' . $rawPath),
                    storage_path('app/public/' . $rawPath),
                    storage_path('app/' . $rawPath),
                    public_path('storage/' . $rawPath),
                    public_path($rawPath),
                    base_path('../STOS-EPENILAIAN-WEB/storage/app/private/' . $rawPath),
                    base_path('../STOS-EPENILAIAN-WEB/storage/app/public/' . $rawPath),
                    base_path('../STOS-EPENILAIAN-WEB/storage/app/' . $rawPath),
                ];
                $absolutePath = null;
                foreach ($candidates as $cand) {
                    if (is_file($cand)) {
                        $absolutePath = $cand;
                        break;
                    }
                }
                if ($absolutePath && is_file($absolutePath)) {
                    $downloadName = $pbFile->original_name ?: $pbFile->stored_name;
                    $mime = $pbFile->mime_type ?: (function_exists('mime_content_type') ? mime_content_type($absolutePath) : null) ?: 'application/pdf';
                    $ext = strtolower(pathinfo($downloadName, PATHINFO_EXTENSION));
                    $isPdf = ($ext === 'pdf' || str_contains(strtolower($mime), 'pdf'));
                    $disposition = $isPdf ? 'inline' : 'attachment';

                    return response()->file($absolutePath, [
                        'Content-Type' => $mime,
                        'Content-Disposition' => $disposition . '; filename="' . addslashes($downloadName) . '"',
                    ]);
                }

                return \App\Support\StosStoredFile::response([
                    'uuid'          => $pbFile->uuid,
                    'original_name' => $pbFile->original_name ?: $pbFile->stored_name,
                    'path'          => $pbFile->path,
                    'mime_type'     => $pbFile->mime_type ?: 'application/pdf',
                    'download_api'  => 'penyata-bank-files/' . $pbFile->uuid . '/download',
                ]);
            }
            abort(404, 'Fail tidak dijumpai.');
        }

        $tender = Tender::query()->findOrFail($file->tender_id);
        $this->assertCanAccessTenderFile($tender, (int) $file->vendor_id);

        $absolutePath = $file->absolutePath();
        if (! $absolutePath || ! is_file($absolutePath)) {
            $rawPath = ltrim(str_replace('public/', '', (string) $file->path), '/');
            $candidates = [
                storage_path('app/private/' . $rawPath),
                storage_path('app/public/' . $rawPath),
                storage_path('app/' . $rawPath),
                public_path('storage/' . $rawPath),
                public_path($rawPath),
                base_path('../STOS-EPENILAIAN-WEB/storage/app/private/' . $rawPath),
                base_path('../STOS-EPENILAIAN-WEB/storage/app/public/' . $rawPath),
                base_path('../STOS-EPENILAIAN-WEB/storage/app/' . $rawPath),
            ];
            foreach ($candidates as $cand) {
                if (is_file($cand)) {
                    $absolutePath = $cand;
                    break;
                }
            }
        }

        if ($absolutePath && is_file($absolutePath)) {
            $downloadName = $file->original_name ?: $file->stored_name;
            $mime = $file->mime_type ?: (function_exists('mime_content_type') ? mime_content_type($absolutePath) : null) ?: 'application/octet-stream';
            $ext = strtolower(pathinfo($downloadName, PATHINFO_EXTENSION));
            $isPdf = ($ext === 'pdf' || str_contains(strtolower($mime), 'pdf'));
            $disposition = $isPdf ? 'inline' : 'attachment';

            return response()->file($absolutePath, [
                'Content-Type' => $mime,
                'Content-Disposition' => $disposition . '; filename="' . addslashes($downloadName) . '"',
            ]);
        }

        return \App\Support\StosStoredFile::response([
            'uuid'          => $file->uuid,
            'original_name' => $file->original_name ?: $file->stored_name,
            'path'          => $file->path,
            'mime_type'     => $file->mime_type ?: 'application/octet-stream',
        ]);
    }

    public function streamByPath(Request $request)
    {
        if (! Auth::check()) {
            abort(403, 'Sila log masuk untuk memuat turun fail.');
        }

        $path = (string) $request->query('path', '');
        $name = (string) $request->query('name', 'Dokumen Sokongan');
        $uuid = (string) $request->query('uuid', '');

        if ($uuid !== '') {
            $pbFile = \App\Models\PenyataBankFile::where('uuid', $uuid)->first();
            if ($pbFile) {
                $pbFile->loadMissing('penyataBank');
                $tenderId = (int) ($pbFile->penyataBank?->tender_id ?? 0);
                if ($tenderId > 0) {
                    $tender = Tender::query()->find($tenderId);
                    if ($tender) {
                        $this->assertCanAccessTenderFile($tender, null);
                    }
                }

                return \App\Support\StosStoredFile::response([
                    'uuid'          => $pbFile->uuid,
                    'original_name' => $pbFile->original_name ?: $name,
                    'path'          => $pbFile->path,
                    'mime_type'     => $pbFile->mime_type ?: 'application/pdf',
                    'download_api'  => 'penyata-bank-files/' . $pbFile->uuid . '/download',
                ]);
            }
            $vFile = \App\Models\TenderVendorDokumenFile::where('uuid', $uuid)->first();
            if ($vFile) {
                $tender = Tender::query()->findOrFail($vFile->tender_id);
                $this->assertCanAccessTenderFile($tender, (int) $vFile->vendor_id);

                return \App\Support\StosStoredFile::response([
                    'uuid'          => $vFile->uuid,
                    'original_name' => $vFile->original_name ?: $name,
                    'path'          => $vFile->path,
                    'mime_type'     => $vFile->mime_type ?: 'application/octet-stream',
                ]);
            }
        }

        if ($path === '') {
            abort(404, 'Fail tidak dijumpai.');
        }

        $cleanPath = ltrim(str_replace('public/', '', $path), '/');

        return \App\Support\StosStoredFile::response([
            'path'          => $cleanPath,
            'original_name' => $name,
            'mime_type'     => 'application/pdf',
        ]);
    }

    public function saveKeyIn(Request $request, Tender $tender, string $itemUuid)
    {
        $vendorId = $this->requireVendorId();
        $this->submissions->assertEditable($tender, $vendorId);
        $item = $this->findChecklistItem($tender, $itemUuid, 'vendor', $vendorId);

        if (($item['action'] ?? '') !== 'key_in') {
            throw ValidationException::withMessages([
                'item' => 'Item ini tidak memerlukan input maklumat.',
            ]);
        }

        $data = $request->validate([
            'value' => 'required|string|max:5000',
            'section' => 'required|string|in:technical,financial,kewangan_kerja,spesifikasi_kerja',
        ]);

        if ($data['section'] !== $item['section']) {
            throw ValidationException::withMessages([
                'section' => 'Seksyen dokumen tidak sepadan.',
            ]);
        }

        $response = $this->responses->saveKeyIn(
            $tender,
            $vendorId,
            $itemUuid,
            $data['section'],
            $data['value']
        );

        return response()->json([
            'success' => true,
            'message' => 'Maklumat berjaya disimpan.',
            'data' => [
                'value' => $response->payload['value'] ?? '',
                'status' => $response->status,
            ],
        ]);
    }

    public function specificationForm(Tender $tender, string $itemUuid, Request $request)
    {
        $this->ensureTenderFormAccess($tender);

        $queryVendorId = (int) $request->query('vendor_id');
        $isVendor = $this->isVendorFormMode();

        if ($isVendor) {
            $vendorId = $this->requireVendorId();
            $this->submissions->assertEditable($tender, $vendorId);
        } else {
            // Staff / jawatankuasa review of a specific petender.
            $vendorId = $queryVendorId > 0 ? $queryVendorId : null;
        }

        // Always resolve the checklist definition from admin mode so financial
        // specification mirrors (excluded from vendor-mode lists) still get rows.
        $item = $this->findChecklistItem($tender, $itemUuid, 'admin');
        $item = $this->ensureSpecificationRows($tender, $item);

        if (($item['action'] ?? '') !== 'view_specification') {
            abort(404, 'Item dokumen spesifikasi tidak dijumpai.');
        }

        if ($vendorId) {
            $item = $this->attachVendorSpecificationContent($tender, $item, $vendorId);
        }

        $viewName = (! $isVendor && $vendorId) ? 'tenders.dokumen.specification_review' : 'tenders.dokumen.specification_form';

        $vendorInfo = null;
        if (! $isVendor && $vendorId) {
            $vendorModel = \App\Vendor::query()->find($vendorId);
            $vendorInfo  = [
                'id'   => $vendorId,
                'name' => $vendorModel?->name ?: ('Petender #' . $vendorId),
                'kod'  => $vendorModel?->registration ?: '-',
            ];
        }

        // Match Jawatankuasa Pembuka: staff modal review uses dokumentasi layout.
        $summary = $request->query('summary');
        if (! $isVendor && $summary === null && $request->boolean('modal')) {
            $summary = 'dokumentasi';
        }

        return view($viewName, array_merge([
            'tender' => $tender,
            'item' => $item,
            'vendor' => $vendorInfo,
            'isReadOnly' => ! $isVendor,
            'summary' => $summary,
        ], $this->formViewVars($tender)));
    }

    public function saveSpecification(Request $request, Tender $tender, string $itemUuid)
    {
        $user = Auth::user();
        $isAdmin = $user && ($user->hasRole('Admin') || $user->can('tender:specification-management'));

        if ($isAdmin) {
            $this->ensureTenderFormAccess($tender);
            $vendorId = (int) $request->input('vendor_id');
            if ($vendorId <= 0) {
                throw ValidationException::withMessages([
                    'vendor_id' => 'Vendor diperlukan untuk menyimpan pematuhan.',
                ]);
            }
        } else {
            $vendorId = $this->requireVendorId();
            $this->submissions->assertEditable($tender, $vendorId);
        }

        $item = $this->findChecklistItem($tender, $itemUuid, 'vendor', $vendorId);

        if (($item['action'] ?? '') !== 'view_specification') {
            throw ValidationException::withMessages([
                'item' => 'Item ini tidak memerlukan maklum balas spesifikasi.',
            ]);
        }

        $data = $request->validate([
            'section' => 'required|string|in:technical,financial,kewangan_kerja,spesifikasi_kerja',
            'vendor_id' => 'nullable|integer|min:1',
            'item_prices' => 'nullable|array',
            'item_prices.*' => 'nullable|string|max:5000',
            'details' => 'nullable|array',
            'details.*' => 'nullable|array',
            'details.*.pematuhan' => 'nullable|string|max:10',
            'details.*.cadangan' => 'nullable|string|max:5000',
            'vendor_tempoh_siap_val' => 'nullable|integer|min:1',
            'vendor_tempoh_siap_unit' => 'nullable|string|max:50',
        ]);

        if ($data['section'] !== $item['section']) {
            throw ValidationException::withMessages([
                'section' => 'Seksyen dokumen tidak sepadan.',
            ]);
        }

        $response = $this->responses->saveSpecificationResponses(
            $tender,
            $vendorId,
            $itemUuid,
            $data['section'],
            $data,
            $isAdmin
        );

        if (! $isAdmin && $data['section'] === 'spesifikasi_kerja') {
            // Vendor offer only — SpesifikasiKerjaItem.kadar (PTJ) is never modified.
            $this->responses->syncHargaTawaranFromSpecification(
                $tender,
                $vendorId,
                $this->responses->normalizeSpecificationPayload($response->payload)['item_prices'] ?? []
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Maklum balas spesifikasi berjaya disimpan.',
            'data' => [
                'specification' => $this->responses->normalizeSpecificationPayload($response->payload),
                'status' => $response->status,
            ],
        ]);
    }

    public function downloadSpesifikasiKerjaFile(Tender $tender, string $fileUuid)
    {
        $this->ensureTenderFormAccess($tender);
        $this->assertCanAccessTenderFile($tender);

        $file = \App\Models\SpesifikasiKerjaFile::query()
            ->where('uuid', $fileUuid)
            ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
            ->firstOrFail();

        $path = ltrim(str_replace('\\', '/', (string) $file->path), '/');
        $absolute = storage_path('app/public/' . $path);
        if (is_file($absolute)) {
            return response()->file($absolute, [
                'Content-Type' => $file->mime_type ?: 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
            ]);
        }

        $stosPath = rtrim((string) config('services.stos_backend.storage_path'), '/') . '/' . $path;
        if ($stosPath !== '/' . $path && is_file($stosPath)) {
            return response()->file($stosPath, [
                'Content-Type' => $file->mime_type ?: 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
            ]);
        }

        $remote = rtrim((string) config('services.stos_backend.url'), '/') . '/storage/' . $path;
        $api = \App\Services\StosBackendClient::http()
            ->withHeaders(['Accept' => '*/*'])
            ->get($remote);

        if (! $api->successful()) {
            abort(404, 'Fail tidak dijumpai.');
        }

        return response($api->body(), 200, [
            'Content-Type' => $file->mime_type ?: ($api->header('Content-Type') ?: 'application/octet-stream'),
            'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
        ]);
    }

    protected function requireVendorId(): int
    {
        $vendorId = $this->vendorId();
        if (! $vendorId) {
            abort(403, 'Akses vendor diperlukan.');
        }

        return $vendorId;
    }

    /**
     * Rebuild specification rows when admin_content is empty / placeholder-only.
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function ensureSpecificationRows(Tender $tender, array $item): array
    {
        if (($item['action'] ?? '') !== 'view_specification') {
            return $item;
        }

        $rows = $item['admin_content']['rows'] ?? [];
        $hasRealRows = collect($rows)->contains(function ($row) {
            return is_array($row) && (
                ! empty($row['item_uuid'])
                || ! empty($row['detail_uuid'])
                || (($row['kind'] ?? '') === 'spec')
            );
        });

        if ($hasRealRows) {
            return $item;
        }

        $uuid = (string) ($item['uuid'] ?? '');
        $related = \Illuminate\Support\Facades\DB::table('financial_checklist_items as fci')
            ->leftJoin('technical_checklist_items as tci', 'tci.id', '=', 'fci.technical_item_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->where(function ($q) use ($uuid) {
                $q->where('fci.uuid', $uuid)
                    ->orWhere('tci.uuid', $uuid)
                    ->orWhere('sp.uuid', $uuid)
                    ->orWhere('tsd.uuid', $uuid);
            })
            ->select('tci.id as tci_id', 'tci.specification_document_id', 'tsd.id as tsd_id')
            ->first();

        $techItem = null;
        if ($related?->tci_id) {
            $techItem = \App\Models\TechnicalChecklistItem::query()->find($related->tci_id);
        }

        if (! $techItem) {
            $techItem = \App\Models\TechnicalChecklistItem::query()
                ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
                ->whereNotNull('specification_document_id')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();
        }

        if (! $techItem && (int) ($tender->kategori_perolehan_id ?? 0) === 3) {
            $header = \App\Models\SpesifikasiKerjaHeader::query()
                ->where('tender_id', $tender->id)
                ->with(['items' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'), 'items.specs.files'])
                ->first();
            if ($header && $header->items->isNotEmpty()) {
                $built = (new \App\Support\TenderDokumenContentBuilder($tender))
                    ->buildForSpesifikasiKerjaHeader($header, $header->items);
                $item['admin_content'] = $built['admin_content'] ?? $item['admin_content'];
                $item['section'] = $built['section'] ?? $item['section'];
                $item['uuid'] = $built['uuid'] ?? $item['uuid'];

                return $item;
            }
        }

        if ($techItem) {
            $built = (new \App\Support\TenderDokumenContentBuilder($tender))
                ->buildForChecklistItem($techItem, 'technical');
            $item['admin_content'] = $built['admin_content'] ?? $item['admin_content'];
            if (empty($item['section']) || $item['section'] === 'financial') {
                // Keep original section for saves, but ensure rows come from technical doc.
            }
        }

        return $item;
    }

    /**
     * Attach vendor-submitted specification answers, searching related checklist UUIDs.
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function attachVendorSpecificationContent(Tender $tender, array $item, int $vendorId): array
    {
        $candidateUuids = $this->specificationResponseCandidateUuids($tender, (string) ($item['uuid'] ?? ''));

        $response = \App\Models\TenderVendorDokumenResponse::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where('response_type', 'specification')
            ->when($candidateUuids !== [], fn ($q) => $q->whereIn('checklist_item_uuid', $candidateUuids))
            ->orderByDesc('id')
            ->first();

        if (! $response) {
            $response = \App\Models\TenderVendorDokumenResponse::query()
                ->where('tender_id', $tender->id)
                ->where('vendor_id', $vendorId)
                ->where('response_type', 'specification')
                ->orderByDesc('id')
                ->first();
        }

        $files = \App\Models\TenderVendorDokumenFile::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->when($candidateUuids !== [], fn ($q) => $q->whereIn('checklist_item_uuid', $candidateUuids))
            ->orderBy('id')
            ->get();

        $item['vendor_content'] = [
            'key_in' => null,
            'specification' => $response
                ? $this->responses->normalizeSpecificationPayload(
                    is_array($response->payload) ? $response->payload : null
                )
                : ['item_prices' => [], 'details' => []],
            'files' => $files->map(fn ($file) => [
                'uuid' => $file->uuid,
                'name' => $file->original_name,
                'url' => $file->url(),
                'size' => $file->size,
            ])->values()->all(),
            'status' => $response?->status ?? ($files->isNotEmpty() ? 'submitted' : 'draft'),
        ];

        return $item;
    }

    /**
     * @return list<string>
     */
    protected function specificationResponseCandidateUuids(Tender $tender, string $itemUuid): array
    {
        $uuids = array_filter([$itemUuid]);

        $related = \Illuminate\Support\Facades\DB::table('financial_checklist_items as fci')
            ->leftJoin('technical_checklist_items as tci', 'tci.id', '=', 'fci.technical_item_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->where(function ($q) use ($itemUuid) {
                $q->where('fci.uuid', $itemUuid)
                    ->orWhere('tci.uuid', $itemUuid)
                    ->orWhere('sp.uuid', $itemUuid)
                    ->orWhere('tsd.uuid', $itemUuid);
            })
            ->select('fci.uuid as fci_uuid', 'tci.uuid as tci_uuid', 'sp.uuid as sp_uuid', 'tsd.uuid as tsd_uuid')
            ->first();

        if ($related) {
            $uuids = array_merge($uuids, array_filter([
                $related->fci_uuid,
                $related->tci_uuid,
                $related->sp_uuid,
                $related->tsd_uuid,
            ]));
        }

        $techUuids = \Illuminate\Support\Facades\DB::table('technical_checklist_headers as tch')
            ->join('technical_checklist_items as tci', 'tci.technical_checklist_header_id', '=', 'tch.id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->where('tch.tender_id', $tender->id)
            ->select('tci.uuid as tci_uuid', 'tsd.uuid as tsd_uuid', 'sp.uuid as sp_uuid')
            ->get();

        foreach ($techUuids as $row) {
            $uuids[] = $row->tci_uuid;
            $uuids[] = $row->tsd_uuid;
            $uuids[] = $row->sp_uuid;
        }

        return array_values(array_unique(array_filter($uuids)));
    }

    /**
     * @return array<string, mixed>
     */
    protected function findChecklistItem(
        Tender $tender,
        string $itemUuid,
        string $mode = 'admin',
        ?int $vendorId = null
    ): array {
        $presenter = TenderDokumenPresenter::for($tender);
        $item = collect($presenter->items($mode, $vendorId))
            ->first(fn (array $row) => ($row['uuid'] ?? '') === $itemUuid);

        // Staff review: vendor-scoped list can miss the PTJ checklist row; fall back to admin.
        if (! $item && $mode === 'vendor') {
            $item = collect($presenter->items('admin'))
                ->first(fn (array $row) => ($row['uuid'] ?? '') === $itemUuid);
        }

        if (! $item) {
            abort(404, 'Item dokumen tidak dijumpai.');
        }

        return $item;
    }
}
