<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\Request;

class PenilaianTeknikalController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index()
    {
        $tenders = $this->mapTendersForProcessList(
            Tender::query()
                ->where('status_process_id', TenderProcessStatus::penilaianTeknikalListStatus())
                ->orderByDesc('id')
                ->get(['id', 'uuid', 'no_tender', 'ref_number', 'name', 'submission_datetime', 'kategori_perolehan_id']),
            function (Tender $tender, string $noTender) {
                if ((int) ($tender->kategori_perolehan_id ?? 0) === 3) {
                    return route('penilaianTeknikalKerja.show', $noTender);
                }

                return route('penilaianTeknikal.show', $noTender);
            }
        );

        return view('newModule.penilaian_teknikal.teknikal_index', compact('tenders'));
    }

    public function show(string $tender_no)
    {
        return view('newModule.penilaian_teknikal.teknikal', compact('tender_no'));
    }

    public function showTeknikalKerja(string $tender_no)
    {
        return view('newModule.penilaian_teknikal.teknikal_kerja', compact('tender_no'));
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENILAIAN_TEKNIKAL,
            TenderProcessStatus::penilaianTeknikalListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian teknikal (status ' . TenderProcessStatus::penilaianTeknikalListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Penilaian teknikal berjaya dihantar.']);
    }
}
