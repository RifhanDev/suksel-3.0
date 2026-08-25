<?php

namespace App\Services;

use App\Models\TenderPembukaEvaluation;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JawatankuasaPembukaService
{
    // ─────────────────────────────────────────────────────────────────
    // Evaluation (Pematuhan) Operations
    // ─────────────────────────────────────────────────────────────────

    /**
     * Save or update a compliance evaluation for a single vendor + checklist item.
     *
     * @return TenderPembukaEvaluation
     */
    public function saveEvaluation(
        Tender $tender,
        int    $vendorId,
        string $checklistItemUuid,
        int    $statusPematuhan,
        ?string $catatan = null
    ): TenderPembukaEvaluation {
        $record = TenderPembukaEvaluation::query()->firstOrNew([
            'tender_id'            => $tender->id,
            'vendor_id'            => $vendorId,
            'checklist_item_uuid'  => $checklistItemUuid,
        ]);

        $record->fill([
            'status_pematuhan' => $statusPematuhan,
            'catatan'          => trim((string) ($catatan ?? '')) ?: null,
            'updated_by'       => Auth::id(),
        ]);

        if (! $record->exists) {
            $record->created_by = Auth::id();
        }

        $record->save();

        return $record;
    }

    /**
     * Load all existing evaluations for a tender, keyed by
     * "{vendor_id}:{checklist_item_uuid}" for fast lookup.
     *
     * @return array<string, TenderPembukaEvaluation>
     */
    public function loadEvaluations(Tender $tender): array
    {
        return TenderPembukaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy(fn (TenderPembukaEvaluation $e) => "{$e->vendor_id}:{$e->checklist_item_uuid}")
            ->all();
    }

    /**
     * Save every row for one checklist item in a single transaction, then return
     * the fresh aggregate counts needed to compute that item's Status Penilaian.
     *
     * @param  array<int, array{vendor_id: int, status_pematuhan: int, catatan: ?string}>  $rows
     * @return array{evaluated_count: int, total_vendors: int}
     */
    public function saveEvaluationsBatch(Tender $tender, string $checklistItemUuid, array $rows): array
    {
        DB::transaction(function () use ($tender, $checklistItemUuid, $rows) {
            foreach ($rows as $row) {
                $this->saveEvaluation(
                    $tender,
                    (int) $row['vendor_id'],
                    $checklistItemUuid,
                    (int) $row['status_pematuhan'],
                    $row['catatan'] ?? null
                );
            }
        });

        $evaluatedCount = TenderPembukaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('checklist_item_uuid', $checklistItemUuid)
            ->count();

        $totalVendors = $tender->participants()->where('participate', 1)->count();

        return ['evaluated_count' => $evaluatedCount, 'total_vendors' => $totalVendors];
    }

    // ─────────────────────────────────────────────────────────────────
    // Disqualification Logic (Rumusan)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Evaluate every participating vendor against both failure rules
     * and return a structured summary.
     *
     * Rule 1: Status Penyerahan = Tiada  (vendor did not upload / submit)
     * Rule 2: Status Pematuhan  = Tiada  (officer manually marked failure)
     *
     * @param  array<int, array{vendor_id: int, name: string, kod: string}>  $vendors
     * @param  array<string, array<string, mixed>>  $semakPayload   Keyed by checklist_item_uuid
     * @param  array<string, TenderPembukaEvaluation>  $evaluations  Keyed by "{vendor_id}:{uuid}"
     * @return array{
     *     layak:     array<int, array<string, mixed>>,
     *     tidak_layak: array<int, array<string, mixed>>
     * }
     */
    public function computeVendorQualifications(
        array $vendors,
        array $semakPayload,
        array $evaluations
    ): array {
        $layak      = [];
        $tidakLayak = [];

        foreach ($vendors as $vendor) {
            $vendorId = (int) $vendor['vendor_id'];
            $reasons  = [];

            foreach ($semakPayload as $uuid => $payload) {
                $evalKey  = "{$vendorId}:{$uuid}";
                $eval     = $evaluations[$evalKey] ?? null;
                $itemTitle = $payload['title'] ?? '-';

                // ── Rule 1: Vendor failed to submit ──────────────────
                $vendorRow = collect($payload['vendors'] ?? [])
                    ->firstWhere('vendor_id', $vendorId);

                $submitted = ($vendorRow['status'] ?? '') === 'submitted';

                if (! $submitted) {
                    $reasons[] = "Tiada penyerahan bagi \"{$itemTitle}\".";
                }

                // ── Rule 2: Officer marked Tiada ─────────────────────
                if ($eval && $eval->isFailed()) {
                    $catatan   = filled($eval->catatan) ? " Catatan: {$eval->catatan}" : '';
                    $reasons[] = "Status Pematuhan Tiada bagi \"{$itemTitle}\".{$catatan}";
                }
            }

            $entry = [
                'vendor_id'     => $vendorId,
                'name'          => $vendor['name'],
                'kod'           => $vendor['kod'],
                'is_bumiputera' => $vendor['is_bumiputera'] ?? null,
                'harga_tawaran' => $vendor['harga_tawaran'] ?? null,
                'is_layak'      => empty($reasons),
                'reasons'       => $reasons,
            ];

            if (empty($reasons)) {
                $layak[] = $entry;
            } else {
                $tidakLayak[] = $entry;
            }
        }

        return [
            'layak'       => $layak,
            'tidak_layak' => $tidakLayak,
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    // Finalisation (Selesai / Hantar)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Validate that every checklist item has been reviewed for every vendor.
     * Returns an array of missing evaluations, empty array = all complete.
     *
     * @param  array<int, array<string, mixed>>  $vendors
     * @param  array<string, array<string, mixed>>  $semakPayload
     * @param  array<string, TenderPembukaEvaluation>  $evaluations
     * @return array<int, array{vendor: string, item: string}>
     */
    public function findMissingEvaluations(
        array $vendors,
        array $semakPayload,
        array $evaluations
    ): array {
        $missing = [];

        foreach ($vendors as $vendor) {
            $vendorId   = (int) $vendor['vendor_id'];
            $vendorName = $vendor['name'];

            foreach ($semakPayload as $uuid => $payload) {
                $evalKey = "{$vendorId}:{$uuid}";
                if (! isset($evaluations[$evalKey])) {
                    $missing[] = [
                        'vendor' => $vendorName,
                        'item'   => $payload['title'] ?? $uuid,
                    ];
                }
            }
        }

        return $missing;
    }

    /**
     * Persist the rumusan data (Bumiputera status + offer price) for each vendor
     * and eliminate disqualified vendors.
     *
     * @param  Tender  $tender
     * @param  array<int, array{vendor_id: int, is_bumiputera: int, harga_tawaran: ?float}>  $rumusanData
     * @param  array<int, array{vendor_id: int, reasons: array<int, string>}>  $tidakLayak
     * @param  int  $currentProcessId   The process ID at which elimination occurs.
     */
    public function persistRumusan(
        Tender $tender,
        array  $rumusanData,
        array  $tidakLayak,
        int    $currentProcessId
    ): void {
        // Build a quick lookup for disqualified vendors
        $eliminatedVendorIds = collect($tidakLayak)
            ->keyBy('vendor_id')
            ->all();

        foreach ($rumusanData as $row) {
            $vendorId    = (int) $row['vendor_id'];
            $participant = TenderVendor::query()
                ->where('tender_id', $tender->id)
                ->where('vendor_id', $vendorId)
                ->where('participate', 1)
                ->first();

            if (! $participant) {
                continue;
            }

            // Save Bumiputera + harga regardless of eligibility
            $participant->is_bumiputera  = isset($row['is_bumiputera']) ? (int) $row['is_bumiputera'] : null;
            $participant->harga_tawaran  = isset($row['harga_tawaran']) && $row['harga_tawaran'] !== '' && $row['harga_tawaran'] !== null
                ? (float) $row['harga_tawaran']
                : null;
            $participant->save();

            // Eliminate disqualified vendors
            if (isset($eliminatedVendorIds[$vendorId])) {
                $reasons = $eliminatedVendorIds[$vendorId]['reasons'] ?? [];
                $participant->eliminate(
                    $currentProcessId,
                    implode(' ', $reasons)
                );
            }
        }
    }
}
