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
                fn (Tender $tender) => route('cutOff.show', $tender->uuid)
            );
        } catch (\Throwable $e) {
            Log::warning('Ralat mengambil senarai cut-off dari STOS.', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Butiran tender untuk halaman Cut Off. Dicari melalui uuid (bukan no_tender —
     * medan itu boleh kosong/bertindih antara tender). Query dilakukan di penilaian
     * (STOS): suksel hantar uuid → STOS cari tender → pulangkan → suksel petakan.
     */
    public function show(string $uuid)
    {
        if (! $this->stos->isConfigured()) {
            abort(503, 'STOS backend tidak dikonfigurasi.');
        }

        try {
            $response = $this->stos->getCutOffTender($uuid);

            if (! $response->successful()) {
                abort($response->status() === 404 ? 404 : 502, 'Tender tidak ditemui.');
            }

            $row = $response->json('data') ?? [];
        } catch (\Throwable $e) {
            Log::warning('Ralat mengambil butiran cut-off dari STOS.', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            abort(502, 'Ralat menghubungi STOS.');
        }

        $tender_no = $row['no_tender'] ?: $row['ref_number'] ?: (string) ($row['id'] ?? $uuid);
        $hargaCutOff = $row['harga_cutoff'] ?? [];
        $statisticalAttribute = $row['statistical_attribute'] ?? [];

        return view('newModule.cut_off.show', [
            'tender_no' => $tender_no,
            'tajuk' => $row['name'] ?? '-',
            'aj' => $this->formatHarga($hargaCutOff['aj'] ?? null),
            'pcp' => $this->formatHarga($hargaCutOff['pcp'] ?? null),
            'bwa' => $this->formatHarga($hargaCutOff['bwa'] ?? null),
            'nt' => (int) ($statisticalAttribute['nt'] ?? 1),
            'mean' => $this->formatHarga($statisticalAttribute['mean'] ?? null),
            'overallMean' => $this->formatHarga($statisticalAttribute['overall_mean'] ?? null),
            'sd' => $this->formatHarga($statisticalAttribute['sd'] ?? null),
            'cv' => $this->formatCv($statisticalAttribute['cv'] ?? null),
        ]);
    }

    /**
     * Format nilai RM: '-' untuk kosong/sifar, selain itu format 2 titik perpuluhan
     * dengan pemisah ribu (ikut gaya paparan sedia ada di halaman ini).
     */
    private function formatHarga($value): string
    {
        $value = (float) ($value ?? 0);

        return $value > 0 ? number_format($value, 2) : '-';
    }

    /**
     * Format Coefficient of Variation: nisbah (bukan RM), 4 titik perpuluhan.
     * '-' bila tiada data (contoh: tiada syarikat lulus Penilaian Pembuka).
     */
    private function formatCv($value): string
    {
        return $value === null ? '-' : number_format((float) $value, 4);
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
