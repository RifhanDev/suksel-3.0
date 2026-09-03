<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\TenderTeknikalPematuhanEvaluation;
use App\Support\TenderProcessStatus;
use App\TenderVendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Penilaian Teknikal-specific live session data (evaluation values and item statuses). */
class PenilaianTeknikalSesiController extends Controller
{
    use ResolvesTenderForProcess;

    /**
     * Status Penilaian for the Langkah 1 checklist items the caller is displaying,
     * so a colleague's save updates everyone's outer table on the next poll.
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

        $evaluatedCounts = TenderTeknikalPematuhanEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereIn('checklist_item_uuid', $uuids)
            ->groupBy('checklist_item_uuid')
            ->selectRaw('checklist_item_uuid, COUNT(DISTINCT vendor_id) as evaluated_count')
            ->pluck('evaluated_count', 'checklist_item_uuid');

        $totalVendors = TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('participate', 1)
            ->where(function ($query) {
                $query->where('cancel_fg', 0)
                    ->orWhere('eliminated_process_id', TenderProcessStatus::PENILAIAN_TEKNIKAL);
            })
            ->count();

        $statuses = [];
        foreach ($uuids as $uuid) {
            $statuses[$uuid] = $this->resolveStatusPenilaian(
                (int) ($evaluatedCounts[$uuid] ?? 0),
                $totalVendors
            );
        }

        // Returned here so an idle member's page unlocks the next step within one
        // poll of the Pengerusi confirming, instead of needing a reload.
        $laporan = DB::table('tender_teknikal_laporans')
            ->where('tender_id', $tender->id)
            ->first(['pematuhan_confirmed_at', 'spesifikasi_confirmed_at']);

        return response()->json(['data' => [
            'statuses' => $statuses,
            'confirmed' => [
                'pematuhan' => (bool) ($laporan->pematuhan_confirmed_at ?? null),
                'spesifikasi' => (bool) ($laporan->spesifikasi_confirmed_at ?? null),
            ],
        ]]);
    }

    /** Mirrors PenilaianTeknikalController::resolveStatusPenilaian(). */
    protected function resolveStatusPenilaian(int $evaluatedCount, int $totalVendors): array
    {
        if ($totalVendors === 0 || $evaluatedCount === 0) {
            return ['label' => 'Menunggu Penilaian', 'badge_class' => 'badge-status-neutral'];
        }

        if ($evaluatedCount < $totalVendors) {
            return ['label' => 'Sedang Menilai', 'badge_class' => 'badge-status-warning'];
        }

        return ['label' => 'Telah Dinilai', 'badge_class' => 'badge-status-success'];
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

        $rows = TenderTeknikalPematuhanEvaluation::query()
            ->where('tender_teknikal_pematuhan_evaluations.tender_id', $tender->id)
            ->where('tender_teknikal_pematuhan_evaluations.checklist_item_uuid', $request->query('checklist_item_uuid'))
            ->leftJoin('users', 'users.id', '=', DB::raw(
                'COALESCE(tender_teknikal_pematuhan_evaluations.updated_by, tender_teknikal_pematuhan_evaluations.created_by)'
            ))
            ->get([
                'tender_teknikal_pematuhan_evaluations.vendor_id',
                'tender_teknikal_pematuhan_evaluations.status_pematuhan',
                'tender_teknikal_pematuhan_evaluations.catatan',
                'users.name as evaluator_name',
            ])
            ->map(fn ($row) => [
                'vendor_id' => (int) $row->vendor_id,
                'status_pematuhan' => $row->status_pematuhan === null ? null : (int) $row->status_pematuhan,
                'catatan' => $row->catatan ?? '',
                'evaluator_name' => $row->evaluator_name ?: null,
            ])
            ->values()
            ->all();

        return response()->json(['data' => ['evaluations' => $rows]]);
    }
}
