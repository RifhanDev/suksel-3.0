<?php

namespace App\Services;

use App\Models\TechnicalChecklistItem;
use App\Models\TechnicalSpecificationItem;
use App\Models\TenderTeknikalBorangEvaluation;
use App\Models\TenderTeknikalLaporan;
use App\Models\TenderTeknikalPematuhanEvaluation;
use App\Models\TenderTeknikalSpesifikasiEvaluation;
use App\Tender;
use App\TenderVendor;
use Illuminate\Support\Collection;

/**
 * Read-side helpers for the Penilaian Teknikal page renders (show(), index status, mapping).
 * All CRUD/write logic now lives in the STOS backend — see PenilaianTeknikalController::callStos().
 */
class TeknikalEvaluationService
{
    // Langkah 1: Pematuhan Dokumentasi

    /** All pematuhan evaluations for a tender, keyed "{vendor_id}:{checklist_item_uuid}" for O(1) lookup. */
    public function loadPematuhanEvaluations(Tender $tender): array
    {
        return TenderTeknikalPematuhanEvaluation::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy(fn (TenderTeknikalPematuhanEvaluation $e) => "{$e->vendor_id}:{$e->checklist_item_uuid}")
            ->all();
    }

    /** Splits vendors into layak/tidak layak; an unevaluated item counts as a failure. */
    public function computeRumusanPematuhan(Collection $vendors, Collection $items, array $evaluations): array
    {
        $layak = [];
        $tidakLayak = [];

        foreach ($vendors as $vendor) {
            $vendorId = (int) $vendor->vendor_id;
            $reasons = [];

            foreach ($items as $item) {
                $evaluation = $evaluations["{$vendorId}:{$item->uuid}"] ?? null;
                $itemTitle = $item->title ?: '-';

                if (! $evaluation) {
                    $reasons[] = ['text' => "Belum dinilai bagi \"{$itemTitle}\".", 'catatan' => null];
                    continue;
                }

                if ($evaluation->isFailed()) {
                    $reasons[] = ['text' => "Tidak mematuhi bagi \"{$itemTitle}\".", 'catatan' => $evaluation->catatan ?: null];
                }
            }

            $entry = [
                'vendor_id' => $vendorId,
                'kod_pembekal' => $vendor->kod_pembekal,
            ];

            if (empty($reasons)) {
                $entry['ulasan'] = 'Melepasi semua semakan pematuhan dokumentasi.';
                $layak[] = $entry;
            } else {
                $entry['reasons'] = $reasons;
                $tidakLayak[] = $entry;
            }
        }

        return ['layak' => $layak, 'tidak_layak' => $tidakLayak];
    }

    // Langkah 2: Spesifikasi Teknikal

    /** Specification items (with details + scoreRules preloaded) keyed by checklist_item_uuid. */
    public function loadSpecificationStructure(Collection $checklistItems): Collection
    {
        $documentIds = $checklistItems
            ->pluck('specification_document_id')
            ->filter()
            ->unique()
            ->values();

        if ($documentIds->isEmpty()) {
            return collect();
        }

        $specItemsByDocument = TechnicalSpecificationItem::query()
            ->with(['details.scoreRules'])
            ->whereIn('technical_specification_document_id', $documentIds)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('technical_specification_document_id');

        return $checklistItems
            ->filter(fn (TechnicalChecklistItem $item) => $item->specification_document_id)
            ->mapWithKeys(fn (TechnicalChecklistItem $item) => [
                $item->uuid => $specItemsByDocument->get($item->specification_document_id, collect())->values(),
            ]);
    }

    /** Flattens a specification structure to its details only, in memory. */
    public function flattenSpecificationDetails(Collection $specificationItems): Collection
    {
        return $specificationItems->flatMap(fn (TechnicalSpecificationItem $item) => $item->details);
    }

    /** All specification evaluations, keyed "{vendor_id}:{specification_detail_uuid}". */
    public function loadSpesifikasiEvaluations(Tender $tender): array
    {
        return TenderTeknikalSpesifikasiEvaluation::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy(fn (TenderTeknikalSpesifikasiEvaluation $e) => "{$e->vendor_id}:{$e->specification_detail_uuid}")
            ->all();
    }

    /** Per-vendor score totals for one item, plus is_complete (every detail evaluated). */
    public function computeSpesifikasiRollup(Collection $vendors, Collection $details, array $evaluations): array
    {
        $detailCount = $details->count();

        return $vendors->map(function (TenderVendor $vendor) use ($details, $evaluations, $detailCount) {
            $skorAutomatik = 0.0;
            $skorManual = 0.0;
            $evaluatedCount = 0;

            foreach ($details as $detail) {
                $evaluation = $evaluations["{$vendor->vendor_id}:{$detail->uuid}"] ?? null;
                if ($evaluation) {
                    $evaluatedCount++;
                    $skorAutomatik += (float) ($evaluation->skor_automatik ?? 0);
                    $skorManual += (float) ($evaluation->skor_manual ?? 0);
                }
            }

            return [
                'vendor_id' => (int) $vendor->vendor_id,
                'kod_pembekal' => $vendor->kod_pembekal,
                'skor_automatik' => round($skorAutomatik, 2),
                'skor_manual' => round($skorManual, 2),
                'jumlah_skor' => round($skorAutomatik + $skorManual, 2),
                'is_complete' => $detailCount > 0 && $evaluatedCount === $detailCount,
            ];
        })->values()->all();
    }

    // Langkah 2: Borang Atas Talian (Senarai Pengalaman Kerja / Kerja Dalam Tangan)

    /** All borang evaluations for a tender, keyed "{vendor_id}:{checklist_item_uuid}". */
    public function loadBorangEvaluations(Tender $tender): array
    {
        return TenderTeknikalBorangEvaluation::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy(fn (TenderTeknikalBorangEvaluation $e) => "{$e->vendor_id}:{$e->checklist_item_uuid}")
            ->all();
    }

    // Langkah 3: Penyediaan Laporan

    /** Saved Langkah 3 report record for a tender, or null if never saved. */
    public function loadLaporan(Tender $tender): ?TenderTeknikalLaporan
    {
        return TenderTeknikalLaporan::query()->where('tender_id', $tender->id)->first();
    }
}
