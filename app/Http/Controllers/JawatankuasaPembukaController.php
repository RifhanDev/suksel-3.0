<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JawatankuasaPembukaController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index()
    {
        $tenders = Tender::query()
            ->with('tenderer')
            ->where('status_process_id', TenderProcessStatus::penilaianPembukaListStatus())
            ->orderByDesc('id')
            ->get()
            ->map(function (Tender $tender) {
                return [
                    'uuid' => $tender->uuid,
                    'name' => $tender->name ?: '-',
                    'no_tender' => $tender->no_tender ?: $tender->ref_number ?: '-',
                    'tarikh_jual' => $tender->advertise_start_date
                        ? Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                        : '-',
                    'tarikh_tutup' => $tender->advertise_stop_date
                        ? Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                        : '-',
                    'harga' => number_format((float) ($tender->price ?? 0), 2),
                ];
            })
            ->values()
            ->all();

        return view('newModule.jawatankuasaPembuka.index', compact('tenders'));
    }

    public function show(Request $request)
    {
        return view('newModule.jawatankuasaPembuka.jawatankuasa_pembuka', [
            'tender' => $this->resolveTenderByIdentifier($request->query('tender')),
        ]);
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENILAIAN_PEMBUKA,
            TenderProcessStatus::penilaianPembukaListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian pembuka (status ' . TenderProcessStatus::penilaianPembukaListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Penilaian pembuka berjaya dihantar.']);
    }
}
