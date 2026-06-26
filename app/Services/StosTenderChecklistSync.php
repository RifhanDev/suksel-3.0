<?php

namespace App\Services;

use App\Models\FinancialChecklistHeader;
use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaHeader;
use App\Models\KewanganKerjaItem;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\SpesifikasiKerjaItem;
use App\Models\TechnicalChecklistHeader;
use App\Models\TechnicalChecklistItem;
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

            $this->syncChecklistItems(
                SpesifikasiKerjaItem::class,
                'spesifikasi_kerja_header_id',
                $header->id,
                $data['items'] ?? [],
                fn (array $item) => [
                    'spesifikasi' => $item['spesifikasi'] ?? '',
                    'ya_tidak' => $item['ya_tidak'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                    'sort_order' => $item['sort_order'] ?? 0,
                ]
            );
        });
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
}
