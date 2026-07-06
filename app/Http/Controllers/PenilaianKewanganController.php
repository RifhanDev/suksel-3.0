<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\Request;

class PenilaianKewanganController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index()
    {
        $tenders = $this->mapTendersForProcessList(
            Tender::query()
                ->where('status_process_id', TenderProcessStatus::penilaianKewanganListStatus())
                ->orderByDesc('id')
                ->get(['id', 'uuid', 'no_tender', 'ref_number', 'name', 'submission_datetime']),
            fn (Tender $tender, string $noTender) => route('penilaianKewangan.show', $noTender)
        );

        return view('newModule.penilaian_kewangan.index', compact('tenders'));
    }

    public function show(string $tender_no)
    {
        return view('newModule.penilaian_kewangan.show', compact('tender_no'));
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENILAIAN_KEWANGAN,
            TenderProcessStatus::penilaianKewanganListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian kewangan (status ' . TenderProcessStatus::penilaianKewanganListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Penilaian kewangan berjaya dihantar.']);
    }
}
