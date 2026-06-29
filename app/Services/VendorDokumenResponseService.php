<?php

namespace App\Services;

use App\Models\TenderVendorDokumenFile;
use App\Models\TenderVendorDokumenResponse;
use App\Tender;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VendorDokumenResponseService
{
    public function assertVendorParticipation(Tender $tender, int $vendorId): void
    {
        if (! $tender->hasParticipate($vendorId)) {
            throw ValidationException::withMessages([
                'vendor' => 'Sila beli dokumen tender terlebih dahulu.',
            ]);
        }
    }

    /**
     * @return array<string, array{key_in: ?string, specification: array<string, string>, files: array<int, array<string, mixed>>, status: string}>
     */
    public function responsesByItemUuid(Tender $tender, int $vendorId): array
    {
        $keyIns = TenderVendorDokumenResponse::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->get()
            ->keyBy('checklist_item_uuid');

        $files = TenderVendorDokumenFile::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->orderBy('id')
            ->get()
            ->groupBy('checklist_item_uuid');

        $uuids = $keyIns->keys()->merge($files->keys())->unique();

        $result = [];
        foreach ($uuids as $uuid) {
            $response = $keyIns->get($uuid);
            $itemFiles = $files->get($uuid, collect());

            $result[$uuid] = [
                'key_in' => $response?->response_type === 'key_in' ? ($response->payload['value'] ?? null) : null,
                'specification' => $response?->response_type === 'specification'
                    ? ($response->payload['responses'] ?? [])
                    : [],
                'files' => $itemFiles->map(fn (TenderVendorDokumenFile $file) => [
                    'uuid' => $file->uuid,
                    'name' => $file->original_name,
                    'url' => $file->url(),
                    'size' => $file->size,
                ])->values()->all(),
                'status' => $this->resolveItemStatus($response, $itemFiles->count()),
            ];
        }

        return $result;
    }

    public function uploadFile(
        Tender $tender,
        int $vendorId,
        string $checklistItemUuid,
        string $section,
        UploadedFile $file
    ): TenderVendorDokumenFile {
        $this->assertVendorParticipation($tender, $vendorId);
        $this->assertValidSection($section);

        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $directory = "uploads/tender-dokumen/{$tender->id}/{$vendorId}/{$checklistItemUuid}";
        $path = $file->storeAs($directory, $storedName, 'public');

        return TenderVendorDokumenFile::query()->create([
            'uuid' => (string) Str::uuid(),
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'checklist_item_uuid' => $checklistItemUuid,
            'section' => $section,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function deleteFile(TenderVendorDokumenFile $file, int $vendorId): void
    {
        if ((int) $file->vendor_id !== $vendorId) {
            abort(403);
        }

        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();
    }

    public function saveKeyIn(
        Tender $tender,
        int $vendorId,
        string $checklistItemUuid,
        string $section,
        string $value
    ): TenderVendorDokumenResponse {
        $this->assertVendorParticipation($tender, $vendorId);
        $this->assertValidSection($section);

        $record = TenderVendorDokumenResponse::query()->firstOrNew([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'checklist_item_uuid' => $checklistItemUuid,
        ]);

        if (! $record->exists) {
            $record->uuid = (string) Str::uuid();
        }

        $record->fill([
            'section' => $section,
            'response_type' => 'key_in',
            'payload' => ['value' => trim($value)],
            'status' => trim($value) === '' ? 'draft' : 'submitted',
            'updated_by' => Auth::id(),
        ])->save();

        return $record;
    }

    /**
     * @param  array<string, string|null>  $responses  keyed by specification detail uuid
     */
    public function saveSpecificationResponses(
        Tender $tender,
        int $vendorId,
        string $checklistItemUuid,
        string $section,
        array $responses
    ): TenderVendorDokumenResponse {
        $this->assertVendorParticipation($tender, $vendorId);
        $this->assertValidSection($section);

        $normalized = [];
        foreach ($responses as $detailUuid => $value) {
            $detailUuid = trim((string) $detailUuid);
            if ($detailUuid === '') {
                continue;
            }
            $normalized[$detailUuid] = is_string($value) ? trim($value) : $value;
        }

        $record = TenderVendorDokumenResponse::query()->firstOrNew([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'checklist_item_uuid' => $checklistItemUuid,
        ]);

        if (! $record->exists) {
            $record->uuid = (string) Str::uuid();
        }

        $hasValue = collect($normalized)->contains(fn ($value) => $value !== null && $value !== '');

        $record->fill([
            'section' => $section,
            'response_type' => 'specification',
            'payload' => ['responses' => $normalized],
            'status' => $hasValue ? 'submitted' : 'draft',
            'updated_by' => Auth::id(),
        ])->save();

        return $record;
    }

    protected function assertValidSection(string $section): void
    {
        if (! in_array($section, ['technical', 'financial', 'kewangan_kerja', 'spesifikasi_kerja'], true)) {
            throw ValidationException::withMessages([
                'section' => 'Seksyen dokumen tidak sah.',
            ]);
        }
    }

    protected function resolveItemStatus(?TenderVendorDokumenResponse $response, int $fileCount): string
    {
        if ($fileCount > 0) {
            return 'submitted';
        }

        if ($response?->response_type === 'specification') {
            $responses = $response->payload['responses'] ?? [];
            if (is_array($responses) && collect($responses)->contains(fn ($value) => $value !== null && $value !== '')) {
                return $response->status === 'submitted' ? 'submitted' : 'draft';
            }
        }

        if ($response && ($response->status === 'submitted' || ! empty($response->payload['value']))) {
            return 'submitted';
        }

        return 'draft';
    }
}
