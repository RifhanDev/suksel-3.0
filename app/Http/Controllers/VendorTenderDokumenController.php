<?php

namespace App\Http\Controllers;

use App\Models\TenderVendorDokumenFile;
use App\Services\VendorDokumenResponseService;
use App\Support\TenderDokumenPresenter;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VendorTenderDokumenController extends Controller
{
    public function __construct(protected VendorDokumenResponseService $responses) {}

    public function upload(Request $request, Tender $tender, string $itemUuid)
    {
        $vendorId = $this->vendorId();
        $item = $this->findChecklistItem($tender, $itemUuid);

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
        $item = $this->findChecklistItem($tender, $itemUuid);

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
    protected function findChecklistItem(Tender $tender, string $itemUuid): array
    {
        $item = collect(TenderDokumenPresenter::for($tender)->items('admin'))
            ->first(fn (array $row) => ($row['uuid'] ?? '') === $itemUuid);

        if (! $item) {
            abort(404, 'Item dokumen tidak dijumpai.');
        }

        return $item;
    }
}
