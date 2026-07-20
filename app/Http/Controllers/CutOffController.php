<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\Request;

class CutOffController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index()
    {
        $tenders = $this->mapTendersForProcessList(
            Tender::query()
                ->where('status_process_id', TenderProcessStatus::cutOffListStatus())
                ->orderByDesc('id')
                ->get(['id', 'uuid', 'no_tender', 'ref_number', 'name', 'submission_datetime']),
            fn (Tender $tender, string $noTender) => route('cutOff.show', $noTender)
        );

        return view('newModule.cut_off.index', compact('tenders'));
    }

    public function show(string $tender_no)
    {
        return view('newModule.cut_off.show', ['tender_no' => $tender_no]);
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::CUT_OFF,
            TenderProcessStatus::cutOffListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk cut off (status ' . TenderProcessStatus::cutOffListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Cut off berjaya dihantar.']);
    }
}
