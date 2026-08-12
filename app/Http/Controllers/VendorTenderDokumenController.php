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
        $vendorId = $this->vendorId();
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
        $vendorId = $this->vendorId();
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
        $file = TenderVendorDokumenFile::query()->where('uuid', $fileUuid)->firstOrFail();
        $tender = Tender::query()->findOrFail($file->tender_id);

        $this->assertCanAccessTenderFile($tender, (int) $file->vendor_id);

        $absolutePath = $file->absolutePath();
        if (! $absolutePath || ! is_file($absolutePath)) {
            abort(404, 'Fail tidak dijumpai.');
        }

        $downloadName = $file->original_name ?: $file->stored_name;
        $mime = $file->mime_type ?: mime_content_type($absolutePath) ?: 'application/octet-stream';

        return response()->file($absolutePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . addslashes($downloadName) . '"',
        ]);
    }

    public function saveKeyIn(Request $request, Tender $tender, string $itemUuid)
    {
        $vendorId = $this->vendorId();
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

        $isVendor = $this->isVendorFormMode();
        if ($isVendor && $this->vendorId()) {
            $this->submissions->assertEditable($tender, $this->vendorId());
        }
        $vendorId = $isVendor ? $this->vendorId() : ((int) $request->query('vendor_id') ?: null);

        $item = $this->findChecklistItem($tender, $itemUuid, $vendorId ? 'vendor' : 'admin', $vendorId);

        if (($item['action'] ?? '') !== 'view_specification') {
            abort(404, 'Item dokumen spesifikasi tidak dijumpai.');
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

        return view($viewName, array_merge([
            'tender' => $tender,
            'item' => $item,
            'vendor' => $vendorInfo,
            'isReadOnly' => ! $isVendor,
            // Pilihan paparan ringkasan khusus panggilan (cth. 'dokumentasi' untuk
            // Langkah 1 Penilaian Teknikal). Kosong = paparan penuh sedia ada
            // (digunakan oleh Jawatankuasa Pembuka & lain-lain, tidak berubah).
            'summary' => $request->query('summary'),
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
            $vendorId = $this->vendorId();
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

    protected function vendorId(): int
    {
        $user = Auth::user();

        if (! $user || ! $user->vendor_id) {
            abort(403, 'Akses vendor diperlukan.');
        }

        return (int) $user->vendor_id;
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
        $item = collect(TenderDokumenPresenter::for($tender)->items($mode, $vendorId))
            ->first(fn (array $row) => ($row['uuid'] ?? '') === $itemUuid);

        if (! $item) {
            abort(404, 'Item dokumen tidak dijumpai.');
        }

        return $item;
    }
}
