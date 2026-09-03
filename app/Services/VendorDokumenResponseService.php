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
                    ? $this->normalizeSpecificationPayload($response->payload)
                    : ['item_prices' => [], 'details' => []],
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
     * @return array{item_prices: array<string, string>, details: array<string, array{pematuhan?: string, cadangan?: string}>}
     */
    public function normalizeSpecificationPayload(?array $payload): array
    {
        if (! is_array($payload)) {
            return ['item_prices' => [], 'details' => []];
        }

        if (isset($payload['item_prices']) || isset($payload['details']) || isset($payload['vendor_tempoh_siap_val'])) {
            return [
                'item_prices' => collect($payload['item_prices'] ?? [])
                    ->mapWithKeys(fn ($value, $key) => [trim((string) $key) => is_string($value) ? trim($value) : (string) $value])
                    ->all(),
                'details' => collect($payload['details'] ?? [])
                    ->mapWithKeys(function ($value, $key) {
                        $key = trim((string) $key);
                        if (! is_array($value)) {
                            return [$key => ['pematuhan' => '', 'cadangan' => is_string($value) ? trim($value) : '']];
                        }

                        return [$key => [
                            'pematuhan' => trim((string) ($value['pematuhan'] ?? '')),
                            'cadangan' => trim((string) ($value['cadangan'] ?? '')),
                        ]];
                    })
                    ->all(),
                'vendor_tempoh_siap_val'  => isset($payload['vendor_tempoh_siap_val']) && $payload['vendor_tempoh_siap_val'] !== '' && $payload['vendor_tempoh_siap_val'] !== null ? (int) $payload['vendor_tempoh_siap_val'] : null,
                'vendor_tempoh_siap_unit' => isset($payload['vendor_tempoh_siap_unit']) && $payload['vendor_tempoh_siap_unit'] !== '' ? trim((string) $payload['vendor_tempoh_siap_unit']) : null,
            ];
        }

        $legacy = $payload['responses'] ?? $payload;
        $details = [];

        if (is_array($legacy)) {
            foreach ($legacy as $uuid => $value) {
                $uuid = trim((string) $uuid);
                if ($uuid === '' || $value === null || $value === '') {
                    continue;
                }
                $value = is_string($value) ? trim($value) : (string) $value;
                $details[$uuid] = in_array($value, ['yes', 'no'], true)
                    ? ['pematuhan' => $value, 'cadangan' => '']
                    : ['pematuhan' => '', 'cadangan' => $value];
            }
        }

        return ['item_prices' => [], 'details' => $details];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function saveSpecificationResponses(
        Tender $tender,
        int $vendorId,
        string $checklistItemUuid,
        string $section,
        array $data,
        bool $adminSave = false
    ): TenderVendorDokumenResponse {
        $this->assertVendorParticipation($tender, $vendorId);
        $this->assertValidSection($section);

        $normalized = $this->normalizeSpecificationPayload($data);

        // Per-vendor row: unique(tender_id, vendor_id, checklist_item_uuid).
        // Never writes to spesifikasi_kerja_items (PTJ/admin reference stays intact).
        $record = TenderVendorDokumenResponse::query()->firstOrNew([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'checklist_item_uuid' => $checklistItemUuid,
        ]);

        $existing = $this->normalizeSpecificationPayload($record->exists ? $record->payload : null);

        $itemPrices = [];
        foreach ($normalized['item_prices'] as $itemUuid => $value) {
            $itemUuid = trim((string) $itemUuid);
            if ($itemUuid === '') {
                continue;
            }
            $itemPrices[$itemUuid] = trim((string) $value);
        }

        if ($section === 'spesifikasi_kerja') {
            $itemPrices = $this->filterKerjaVendorItemPrices($tender, $itemPrices);
        }

        $details = [];
        foreach ($normalized['details'] as $detailUuid => $fields) {
            $detailUuid = trim((string) $detailUuid);
            if ($detailUuid === '') {
                continue;
            }
            $details[$detailUuid] = [
                'pematuhan' => trim((string) ($fields['pematuhan'] ?? '')),
                'cadangan' => trim((string) ($fields['cadangan'] ?? '')),
            ];
        }

        if ($adminSave) {
            // Admin/Jawatankuasa may only update pematuhan — never vendor offers (item_prices).
            $mergedDetails = $existing['details'];
            foreach ($details as $detailUuid => $fields) {
                if (! isset($mergedDetails[$detailUuid])) {
                    $mergedDetails[$detailUuid] = ['pematuhan' => '', 'cadangan' => ''];
                }
                $mergedDetails[$detailUuid]['pematuhan'] = $fields['pematuhan'];
            }
            $details = $mergedDetails;
            $itemPrices = $existing['item_prices'];
        } else {
            // Vendor updates cadangan / item_prices; keep any existing pematuhan from admin.
            $mergedDetails = $existing['details'];
            foreach ($details as $detailUuid => $fields) {
                if (! isset($mergedDetails[$detailUuid])) {
                    $mergedDetails[$detailUuid] = ['pematuhan' => '', 'cadangan' => ''];
                }
                $mergedDetails[$detailUuid]['cadangan'] = $fields['cadangan'];
            }
            $details = $mergedDetails;
        }

        $tempohVal = isset($data['vendor_tempoh_siap_val']) && $data['vendor_tempoh_siap_val'] !== '' && $data['vendor_tempoh_siap_val'] !== null ? (int) $data['vendor_tempoh_siap_val'] : ($existing['vendor_tempoh_siap_val'] ?? null);
        $tempohUnit = isset($data['vendor_tempoh_siap_unit']) && $data['vendor_tempoh_siap_unit'] !== '' ? trim((string) $data['vendor_tempoh_siap_unit']) : ($existing['vendor_tempoh_siap_unit'] ?? null);

        if (! $record->exists) {
            $record->uuid = (string) Str::uuid();
        }

        $hasValue = collect($itemPrices)->contains(fn ($value) => $value !== '')
            || collect($details)->contains(fn ($fields) => ($fields['pematuhan'] ?? '') !== '' || ($fields['cadangan'] ?? '') !== '')
            || ($tempohVal !== null);

        $payloadToSave = [
            'item_prices'             => $itemPrices,
            'details'                 => $details,
            'vendor_tempoh_siap_val'  => $tempohVal,
            'vendor_tempoh_siap_unit' => $tempohUnit,
        ];

        $record->fill([
            'section' => $section,
            'response_type' => 'specification',
            'payload' => $payloadToSave,
            'status' => $hasValue ? 'submitted' : 'draft',
            'updated_by' => Auth::id(),
        ])->save();

        if (! $adminSave && $tempohVal !== null) {
            $participant = $tender->participants()->where('vendor_id', $vendorId)->first();
            if ($participant) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('tender_vendors', 'tempoh')) {
                    $participant->tempoh = $tempohVal;
                    $participant->save();
                } elseif (\Illuminate\Support\Facades\Schema::hasColumn('tender_vendors', 'project_timeline')) {
                    $participant->project_timeline = (string) $tempohVal;
                    $participant->save();
                }
            }
        }

        return $record;
    }

    /**
     * Keep only child spesifikasi rows for this tender.
     * Vendor offers live in item_prices — SpesifikasiKerjaItem.kadar (PTJ) is never updated here.
     *
     * @param  array<string, string>  $itemPrices
     * @return array<string, string>
     */
    public function filterKerjaVendorItemPrices(Tender $tender, array $itemPrices): array
    {
        if ($itemPrices === []) {
            return [];
        }

        $allowed = \App\Models\SpesifikasiKerjaItem::query()
            ->whereIn('uuid', array_keys($itemPrices))
            ->whereNotNull('parent_id')
            ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
            ->pluck('uuid')
            ->all();

        $allowedLookup = array_fill_keys($allowed, true);
        $filtered = [];
        foreach ($itemPrices as $uuid => $value) {
            if (isset($allowedLookup[$uuid])) {
                $filtered[$uuid] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Jumlah keseluruhan from vendor item_prices.
     * Kerja: sum(kadar × kuantiti) using PTJ kuantiti from spesifikasi_kerja_items.
     * Bekalan / other: sum(item_prices) as line totals.
     *
     * @param  array<string, string|int|float>  $itemPrices
     */
    public function calculateSpecificationTotal(Tender $tender, array $itemPrices, ?string $section = null): float
    {
        if ($itemPrices === []) {
            return 0.0;
        }

        $isKerja = $section === 'spesifikasi_kerja'
            || \App\Models\SpesifikasiKerjaItem::query()
                ->whereIn('uuid', array_keys($itemPrices))
                ->whereNotNull('parent_id')
                ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
                ->exists();

        if (! $isKerja) {
            $total = 0.0;
            foreach ($itemPrices as $value) {
                $total += (float) str_replace(',', '', (string) $value);
            }

            return round($total, 2);
        }

        $specs = \App\Models\SpesifikasiKerjaItem::query()
            ->whereIn('uuid', array_keys($itemPrices))
            ->whereNotNull('parent_id')
            ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
            ->get(['uuid', 'kuantiti'])
            ->keyBy('uuid');

        $total = 0.0;
        foreach ($itemPrices as $uuid => $kadarRaw) {
            $spec = $specs->get($uuid);
            if (! $spec) {
                continue;
            }
            $kadar = (float) str_replace(',', '', (string) $kadarRaw);
            $qty = (float) ($spec->kuantiti ?? 0);
            $total += round($kadar * $qty, 2);
        }

        return round($total, 2);
    }

    /**
     * Sync vendor's harga_tawaran from specification offers only.
     * Does not modify SpesifikasiKerjaItem (PTJ/admin kadar).
     *
     * @param  array<string, string>  $itemPrices  keyed by child spec uuid => vendor kadar
     */
    public function syncHargaTawaranFromSpecification(Tender $tender, int $vendorId, array $itemPrices): void
    {
        $total = $this->calculateSpecificationTotal($tender, $itemPrices, 'spesifikasi_kerja');

        $participant = $tender->participants()->where('vendor_id', $vendorId)->first();
        if ($participant) {
            $participant->harga_tawaran = $total;
            $participant->save();
        }
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
            $spec = $this->normalizeSpecificationPayload($response->payload);
            $hasSpecValue = collect($spec['item_prices'] ?? [])->contains(fn ($value) => $value !== '')
                || collect($spec['details'] ?? [])->contains(
                    fn ($fields) => ($fields['pematuhan'] ?? '') !== '' || ($fields['cadangan'] ?? '') !== ''
                );
            if ($hasSpecValue) {
                return $response->status === 'submitted' ? 'submitted' : 'draft';
            }
        }

        if ($response && ($response->status === 'submitted' || ! empty($response->payload['value']))) {
            return 'submitted';
        }

        return 'draft';
    }
}
