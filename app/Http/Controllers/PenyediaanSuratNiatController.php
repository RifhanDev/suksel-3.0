<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\Request;

class PenyediaanSuratNiatController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index()
    {
        $tenders = Tender::query()
            ->where('status_process_id', TenderProcessStatus::penyediaanSuratNiatListStatus())
            ->orderByDesc('id')
            ->get()
            ->map(fn (Tender $tender) => $this->mapTenderAdvertRow($tender, 'penyediaanSuratNiat'))
            ->values()
            ->all();

        return view('newModule.penyediaanSuratNiat.index', compact('tenders'));
    }

    public function show(Request $request)
    {
        return view('newModule.penyediaanSuratNiat.penyediaanSuratNiat', [
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
            TenderProcessStatus::PENYEDIAAN_SURAT_NIAT,
            TenderProcessStatus::penyediaanSuratNiatListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penyediaan surat niat (status ' . TenderProcessStatus::penyediaanSuratNiatListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Penyediaan surat niat berjaya dihantar.']);
    }

    private function mapTenderAdvertRow(Tender $tender, string $routeName): array
    {
        return [
            'uuid' => $tender->uuid,
            'name' => $tender->name ?: '-',
            'tarikh_jual' => $tender->advertise_start_date
                ? \Carbon\Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                : '-',
            'tarikh_tutup' => $tender->advertise_stop_date
                ? \Carbon\Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                : '-',
            'harga' => number_format((float) ($tender->price ?? 0), 2),
            'show_url' => route($routeName, ['tender' => $tender->uuid]),
        ];
    }
}
