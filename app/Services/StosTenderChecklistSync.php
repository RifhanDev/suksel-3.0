<?php

namespace App\Services;

use App\Models\FinancialChecklistHeader;
use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaHeader;
use App\Models\KewanganKerjaItem;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\SpesifikasiKerjaItem;
use App\Models\StandardChecklistItem;
use App\Models\TechnicalChecklistHeader;
use App\Models\TechnicalChecklistItem;
use App\Models\TechnicalSpecificationDocument;
use App\Tender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pull checklist data from STOS API into suksel tables so penyediaan-iklan
 * Dokumen/Tender tab reflects what was saved under pengurusan-spesifikasi.
 */
class StosTenderChecklistSync
{
    public function __construct(protected StosBackendClient $stos) {}

    public function syncForTender(Tender $tender): void
    {
        if (! $this->stos->isConfigured() || empty($tender->uuid)) {
            return;
        }

        try {
            if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
                $this->syncSpesifikasiKerja($tender);
                $this->syncKewanganKerja($tender);

                return;
            }

            $this->syncTechnicalChecklist($tender);
            $this->syncFinancialChecklist($tender);
        } catch (\Throwable $e) {
            Log::warning('StosTenderChecklistSync failed', [
                'tender_id' => $tender->id,
                'uuid' => $tender->uuid,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function syncTechnicalChecklist(Tender $tender): void
    {
        $response = $this->stos->get('/api/technical-checklists/' . $tender->uuid);
        if (! $response->successful()) {
            return;
        }

        $data = $response->json('data');
        if (! is_array($data)) {
            return;
        }

        DB::transaction(function () use ($tender, $data) {
            $header = TechnicalChecklistHeader::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['uuid' => $data['uuid'] ?? (string) Str::uuid(), 'status' => 'draft']
            );

            $header->fill([
                'uuid' => $data['uuid'] ?? $header->uuid,
                'max_score' => $data['max_score'] ?? $header->max_score ?? 0,
                'passing_score' => $data['passing_score'] ?? $header->passing_score ?? 0,
                'passing_percentage' => $data['passing_percentage'] ?? $header->passing_percentage ?? 0,
                'status' => $data['status'] ?? $header->status ?? 'draft',
            ])->save();

            $this->syncChecklistItems(
                TechnicalChecklistItem::class,
                'technical_checklist_header_id',
                $header->id,
                $data['items'] ?? [],
                fn (array $item) => [
                    'source_type' => $item['source_type'] ?? 'manual',
                    'title' => $item['title'] ?? '',
                    'mechanism' => $item['mechanism'] ?? null,
                    'vendor_action' => $item['vendor_action'] ?? null,
                    'score' => $item['score'] ?? 0,
                    'status' => $item['status'] ?? 'draft',
                    'sort_order' => $item['sort_order'] ?? 0,
                    'standard_item_id' => $this->resolveStandardItemId($item['standard_item_uuid'] ?? null),
                    'specification_document_id' => $this->resolveSpecificationDocumentId($item['specification_document_uuid'] ?? null),
                ]
            );
        });
    }

    protected function syncFinancialChecklist(Tender $tender): void
    {
        $response = $this->stos->get('/api/financial-checklists/' . $tender->uuid);
        if (! $response->successful()) {
            return;
        }

        $data = $response->json('data');
        if (! is_array($data)) {
            return;
        }

        DB::transaction(function () use ($tender, $data) {
            $header = FinancialChecklistHeader::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['uuid' => $data['uuid'] ?? (string) Str::uuid(), 'status' => 'draft']
            );

            $header->fill([
                'uuid' => $data['uuid'] ?? $header->uuid,
                'max_score' => $data['max_score'] ?? $header->max_score ?? 0,
                'passing_score' => $data['passing_score'] ?? $header->passing_score ?? 0,
                'passing_percentage' => $data['passing_percentage'] ?? $header->passing_percentage ?? 0,
                'status' => $data['status'] ?? $header->status ?? 'draft',
            ])->save();

            $this->syncChecklistItems(
                FinancialChecklistItem::class,
                'financial_checklist_header_id',
                $header->id,
                $data['items'] ?? [],
                fn (array $item) => [
                    'source_type' => $item['source_type'] ?? 'manual',
                    'title' => $item['title'] ?? '',
                    'mechanism' => $item['mechanism'] ?? null,
                    'vendor_action' => $item['vendor_action'] ?? null,
                    'score' => $item['score'] ?? 0,
                    'status' => $item['status'] ?? 'draft',
                    'sort_order' => $item['sort_order'] ?? 0,
                    'standard_item_id' => $this->resolveStandardItemId($item['standard_item_uuid'] ?? null),
                    'technical_item_id' => $this->resolveTechnicalItemId($tender->id, $item['technical_item_uuid'] ?? null),
                ]
            );
        });
    }

    protected function syncSpesifikasiKerja(Tender $tender): void
    {
        $response = $this->stos->get('/api/spesifikasi-kerja/' . $tender->uuid);
        if (! $response->successful()) {
            return;
        }

        $data = $response->json('data');
        if (! is_array($data)) {
            return;
        }

        DB::transaction(function () use ($tender, $data) {
            $header = SpesifikasiKerjaHeader::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['uuid' => $data['uuid'] ?? (string) Str::uuid(), 'status' => 'draft']
            );

            $header->fill([
                'uuid' => $data['uuid'] ?? $header->uuid,
                'status' => $data['status'] ?? $header->status ?? 'draft',
            ])->save();

            $keptIds = [];
            $rawItems = $data['items'] ?? [];

            foreach ($rawItems as $index => $item) {
                if (! is_array($item)) {
                    continue;
                }

                // Nested CR-004 shape: parent Item + specs[]
                if (array_key_exists('specs', $item) || array_key_exists('nama_item', $item) || array_key_exists('unit', $item)) {
                    $parent = $this->upsertSpesifikasiKerjaItem($header->id, $item, [
                        'parent_id' => null,
                        'nama_item' => $item['nama_item'] ?? $item['item'] ?? $item['title'] ?? '',
                        'spesifikasi' => null,
                        'unit' => $item['unit'] ?? null,
                        'kuantiti' => $item['kuantiti'] ?? $item['kekerapan'] ?? null,
                        'ya_tidak' => null,
                        'catatan' => null,
                        'kadar' => $item['kadar'] ?? null,
                        'sort_order' => $item['sort_order'] ?? $index,
                    ]);
                    $keptIds[] = $parent->id;

                    foreach ($item['specs'] ?? $item['children'] ?? [] as $specIndex => $spec) {
                        if (! is_array($spec)) {
                            continue;
                        }

                        $child = $this->upsertSpesifikasiKerjaItem($header->id, $spec, [
                            'parent_id' => $parent->id,
                            'nama_item' => null,
                            'spesifikasi' => $spec['spesifikasi'] ?? $spec['title'] ?? '',
                            'unit' => null,
                            'kuantiti' => null,
                            'ya_tidak' => $spec['ya_tidak'] ?? $spec['pematuhan'] ?? null,
                            'catatan' => $spec['catatan'] ?? null,
                            'kadar' => null,
                            'sort_order' => $spec['sort_order'] ?? $specIndex,
                        ]);
                        $keptIds[] = $child->id;
                    }

                    continue;
                }

                // Legacy flat shape
                $record = $this->upsertSpesifikasiKerjaItem($header->id, $item, [
                    'parent_id' => null,
                    'nama_item' => null,
                    'spesifikasi' => $item['spesifikasi'] ?? '',
                    'unit' => $item['unit'] ?? null,
                    'kuantiti' => $item['kuantiti'] ?? null,
                    'ya_tidak' => $item['ya_tidak'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                    'kadar' => $item['kadar'] ?? null,
                    'sort_order' => $item['sort_order'] ?? $index,
                ]);
                $keptIds[] = $record->id;
            }

            SpesifikasiKerjaItem::query()
                ->where('spesifikasi_kerja_header_id', $header->id)
                ->when(! empty($keptIds), fn ($query) => $query->whereNotIn('id', $keptIds))
                ->delete();
        });
    }

    /**
     * @param  array<string, mixed>  $source
     * @param  array<string, mixed>  $payload
     */
    protected function upsertSpesifikasiKerjaItem(int $headerId, array $source, array $payload): SpesifikasiKerjaItem
    {
        $record = ! empty($source['uuid'])
            ? SpesifikasiKerjaItem::query()
                ->where('spesifikasi_kerja_header_id', $headerId)
                ->where('uuid', $source['uuid'])
                ->first()
            : null;

        $record ??= new SpesifikasiKerjaItem([
            'uuid' => $source['uuid'] ?? (string) Str::uuid(),
            'spesifikasi_kerja_header_id' => $headerId,
        ]);

        $record->fill($payload)->save();

        return $record;
    }

    protected function syncKewanganKerja(Tender $tender): void
    {
        $response = $this->stos->get('/api/kewangan-kerja/' . $tender->uuid);
        if (! $response->successful()) {
            return;
        }

        $data = $response->json('data');
        if (! is_array($data)) {
            return;
        }

        DB::transaction(function () use ($tender, $data) {
            $header = KewanganKerjaHeader::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                ['uuid' => $data['uuid'] ?? (string) Str::uuid(), 'status' => 'draft']
            );

            $header->fill([
                'uuid' => $data['uuid'] ?? $header->uuid,
                'max_score' => $data['max_score'] ?? $header->max_score ?? 0,
                'passing_score' => $data['passing_score'] ?? $header->passing_score ?? 0,
                'passing_percentage' => $data['passing_percentage'] ?? $header->passing_percentage ?? 0,
                'harga_indikatif' => $data['harga_indikatif'] ?? $header->harga_indikatif ?? 0,
                'status' => $data['status'] ?? $header->status ?? 'draft',
            ])->save();

            $this->syncChecklistItems(
                KewanganKerjaItem::class,
                'kewangan_kerja_header_id',
                $header->id,
                $data['items'] ?? [],
                fn (array $item) => [
                    'source_type' => $item['source_type'] ?? 'manual',
                    'title' => $item['title'] ?? '',
                    'mechanism' => $item['mechanism'] ?? null,
                    'vendor_action' => $item['vendor_action'] ?? null,
                    'score' => $item['score'] ?? 0,
                    'status' => $item['status'] ?? 'draft',
                    'sort_order' => $item['sort_order'] ?? 0,
                    'standard_item_id' => $this->resolveStandardItemId($item['standard_item_uuid'] ?? null),
                ]
            );
        });
    }

    /**
     * @param  class-string  $modelClass
     */
    protected function syncChecklistItems(
        string $modelClass,
        string $headerForeignKey,
        int $headerId,
        array $items,
        callable $mapPayload
    ): void {
        $keptIds = [];

        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                continue;
            }

            $record = ! empty($item['uuid'])
                ? $modelClass::query()->where($headerForeignKey, $headerId)->where('uuid', $item['uuid'])->first()
                : null;

            $record ??= new $modelClass([
                'uuid' => $item['uuid'] ?? (string) Str::uuid(),
                $headerForeignKey => $headerId,
            ]);

            $payload = $mapPayload($item);
            $payload['sort_order'] = $payload['sort_order'] ?? $index;

            $record->fill($payload)->save();
            $keptIds[] = $record->id;
        }

        $modelClass::query()
            ->where($headerForeignKey, $headerId)
            ->when(! empty($keptIds), fn ($query) => $query->whereNotIn('id', $keptIds))
            ->delete();
    }

    protected function resolveStandardItemId(?string $uuid): ?int
    {
        if (! $uuid) {
            return null;
        }

        return StandardChecklistItem::query()->where('uuid', $uuid)->value('id');
    }

    protected function resolveSpecificationDocumentId(?string $uuid): ?int
    {
        if (! $uuid) {
            return null;
        }

        return TechnicalSpecificationDocument::query()->where('uuid', $uuid)->value('id');
    }

    protected function resolveTechnicalItemId(int $tenderId, ?string $uuid): ?int
    {
        if (! $uuid) {
            return null;
        }

        return TechnicalChecklistItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $tenderId))
            ->where('uuid', $uuid)
            ->value('id');
    }
}
