<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\TenderPembukaEvaluation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Pembuka-specific live session data (evaluation values and item statuses).
 *
 * Committee-agnostic concerns — reservations, akuan, activity log — live in
 * EvaluationSessionController. Evaluation values are stored locally in
 * tender_pembuka_evaluations, unlike Penilaian Teknikal which uses the STOS backend.
 */
class JawatankuasaPembukaSesiController extends Controller
{
    use ResolvesTenderForProcess;

    /**
     * Status Penilaian for the given checklist items.
     *
     * The caller passes the uuids it is displaying, so this resolves in two queries
     * without rebuilding the checklist through TenderDokumenPresenter.
     */
    public function itemStatuses(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'uuid',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $uuids = $request->query('items');

        $evaluatedCounts = TenderPembukaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereIn('checklist_item_uuid', $uuids)
            ->groupBy('checklist_item_uuid')
            ->selectRaw('checklist_item_uuid, COUNT(*) as evaluated_count')
            ->pluck('evaluated_count', 'checklist_item_uuid');

        $totalVendors = $tender->participants()->where('participate', 1)->count();

        $statuses = [];
        foreach ($uuids as $uuid) {
            $statuses[$uuid] = $this->resolveStatusPenilaian(
                (int) ($evaluatedCounts[$uuid] ?? 0),
                $totalVendors
            );
        }

        return response()->json(['data' => ['statuses' => $statuses]]);
    }

    /** Mirrors JawatankuasaPembukaController::resolveStatusPenilaian(). */
    protected function resolveStatusPenilaian(int $evaluatedCount, int $totalVendors): array
    {
        if ($totalVendors === 0) {
            return ['label' => 'Tiada Petender', 'badge' => 'badge-status-neutral'];
        }
        if ($evaluatedCount === 0) {
            return ['label' => 'Menunggu Penilaian', 'badge' => 'badge-status-neutral'];
        }
        if ($evaluatedCount < $totalVendors) {
            return ['label' => 'Sedang Menilai', 'badge' => 'badge-status-warning'];
        }

        return ['label' => 'Telah Dinilai', 'badge' => 'badge-status-success'];
    }

    /** Bumiputera status per vendor, from the vendor profile (vendors.mof_bumi). */
    public function bumiputeraStatuses(Request $request): JsonResponse
    {
        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $statuses = DB::table('tender_vendors as tv')
            ->where('tv.tender_id', $tender->id)
            ->where('tv.participate', 1)
            ->leftJoin('vendors as v', 'v.id', '=', 'tv.vendor_id')
            ->get(['tv.vendor_id', 'v.mof_bumi'])
            ->mapWithKeys(fn ($row) => [
                (int) $row->vendor_id => [
                    'is_bumiputera' => (int) ((bool) $row->mof_bumi),
                    'label' => $row->mof_bumi ? 'Bumiputera' : 'Bukan Bumiputera',
                ],
            ])
            ->all();

        return response()->json(['data' => ['bumiputera' => $statuses]]);
    }

    /**
     * Current evaluation state for one checklist item, so an evaluator's saved
     * result appears on their colleagues' screens on the next poll instead of
     * requiring a page reload.
     */
    public function evaluations(Request $request): JsonResponse
    {
        $request->validate([
            'checklist_item_uuid' => 'required|uuid',
        ]);

        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $rows = TenderPembukaEvaluation::query()
            ->where('tender_pembuka_evaluations.tender_id', $tender->id)
            ->where('tender_pembuka_evaluations.checklist_item_uuid', $request->query('checklist_item_uuid'))
            // updated_by is the most recent evaluator; created_by covers rows saved
            // before that column was populated.
            ->leftJoin('users', 'users.id', '=', \Illuminate\Support\Facades\DB::raw(
                'COALESCE(tender_pembuka_evaluations.updated_by, tender_pembuka_evaluations.created_by)'
            ))
            ->get([
                'tender_pembuka_evaluations.vendor_id',
                'tender_pembuka_evaluations.status_pematuhan',
                'tender_pembuka_evaluations.catatan',
                'tender_pembuka_evaluations.updated_at',
                'users.id as evaluator_id',
                'users.name as evaluator_name',
            ])
            ->map(fn ($row) => [
                'vendor_id' => (int) $row->vendor_id,
                'status_pematuhan' => $row->status_pematuhan === null ? null : (int) $row->status_pematuhan,
                'catatan' => $row->catatan ?? '',
                'evaluator_id' => $row->evaluator_id ? (int) $row->evaluator_id : null,
                'evaluator_name' => $row->evaluator_name ?: null,
                'evaluated_at' => optional($row->updated_at)->toIso8601String(),
            ])
            ->values()
            ->all();

        return response()->json(['data' => ['evaluations' => $rows]]);
    }
}
