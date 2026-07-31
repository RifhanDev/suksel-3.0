<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Services\StosBackendClient;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CutOffController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function __construct(protected StosBackendClient $stos) {}

    public function index(Request $request)
    {
        $tenders = $this->fetchCutOffTenders($request);

        return view('newModule.cut_off.index', compact('tenders'));
    }

    /**
     * Senarai tender untuk halaman Cut Off. Query & penapisan dilakukan di penilaian
     * (STOS): suksel hantar filter → STOS query status_process_id = 8 + filter →
     * pulangkan → suksel petakan.
     */
    private function fetchCutOffTenders(Request $request): array
    {
        if (! $this->stos->isConfigured()) {
            Log::warning('STOS backend tidak dikonfigurasi untuk senarai cut-off.');

            return [];
        }

        $filters = array_filter([
            'no_tender' => $request->input('no_tender'),
            'tajuk' => $request->input('tajuk'),
            'tarikh' => $request->input('tarikh'),
        ], fn ($value) => $value !== null && $value !== '');

        try {
            $response = $this->stos->getCutOffTenders($filters);

            if (! $response->successful()) {
                Log::warning('STOS cut-off list gagal.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return [];
            }

            $rows = $response->json('data') ?? [];

            return $this->mapTendersForProcessList(
                Tender::hydrate($rows),
                fn (Tender $tender, string $noTender) => route('cutOff.show', $noTender)
            );
        } catch (\Throwable $e) {
            Log::warning('Ralat mengambil senarai cut-off dari STOS.', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
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
