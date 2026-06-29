<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
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

    public function __construct(protected VendorDokumenResponseService $responses) {}

    public function upload(Request $request, Tender $tender, string $itemUuid)
    {
        $vendorId = $this->vendorId();
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

        $this->responses->deleteFile($file, $vendorId);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dibuang.',
        ]);
    }

    public function saveKeyIn(Request $request, Tender $tender, string $itemUuid)
    {
        $vendorId = $this->vendorId();
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

    public function specificationForm(Tender $tender, string $itemUuid)
    {
        $this->ensureTenderFormAccess($tender);

        $isVendor = $this->isVendorFormMode();
        $vendorId = $isVendor ? $this->vendorId() : null;

        $item = $this->findChecklistItem($tender, $itemUuid, $isVendor ? 'vendor' : 'admin', $vendorId);

        if (($item['action'] ?? '') !== 'view_specification') {
            abort(404, 'Item dokumen spesifikasi tidak dijumpai.');
        }

        return view('tenders.dokumen.specification_form', array_merge([
            'tender' => $tender,
            'item' => $item,
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

        return response()->json([
            'success' => true,
            'message' => 'Maklum balas spesifikasi berjaya disimpan.',
            'data' => [
                'specification' => $this->responses->normalizeSpecificationPayload($response->payload),
                'status' => $response->status,
            ],
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
