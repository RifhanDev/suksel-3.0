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
        $penentuan = $row['penentuan'] ?? [];
        $vendorsCutOff = $row['vendors_cutoff'] ?? [];
        $frequency = $row['frequency'] ?? [];
        $selection = $row['selection'] ?? null;

        // Jadual per-syarikat (No, Ruj, Tend Price, BW, Z, %Bwaj, %Bwam)
        $rows = collect($vendorsCutOff)->map(function ($r) {
            $isFreak = ($r['bw'] ?? null) === 'FREAK';

            return [
                'no' => $r['bil'] ?? '-',
                'ruj' => $r['ruj'] ?? '-',
                'price' => number_format((float) ($r['tend_price'] ?? 0), 2),
                'bw' => $isFreak ? 'FREAK' : number_format((float) ($r['bw'] ?? 0), 2),
                'z' => isset($r['z_score']) && $r['z_score'] !== null ? number_format((float) $r['z_score'], 2) : '-',
                'pct_aj' => $this->formatPeratus($r['pct_bwaj'] ?? null),
                'pct_mean' => ($r['pct_bwam'] ?? null) === 'FREAK' ? 'FREAK' : $this->formatPeratus($r['pct_bwam'] ?? null),
                'freak' => $isFreak,
            ];
        })->all();

        // Carta/jadual taburan frekuensi
        $bins = array_map(fn ($b) => $b['label'], $frequency);
        $freq = array_map(fn ($b) => (int) $b['freq'], $frequency);
        if (empty($bins)) {
            $bins = ['Less', '-90%', '-70%', '-50%', '-30%', '-10%', '10%', '30%', '50%', '70%', '90%', 'More'];
            $freq = array_fill(0, 12, 0);
        }

        // Peraturan minimum pilihan checkbox (mesti sepadan dengan STOS Api\CutOffController::simpan()):
        // <=10 baris atau semua FREAK -> wajib pilih SEMUA; selain itu >= 10.
        $totalCount = count($rows);
        $allFreak = $totalCount > 0 && collect($rows)->every(fn ($r) => $r['freak']);
        $requireAll = $totalCount <= 10 || $allFreak;

        return view('newModule.cut_off.show', [
            'tenderUuid' => $uuid,
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
            // Penentuan Harga Cutoff
            'melebihi10' => $penentuan['melebihi_10'] ?? '-',
            'meanFormula' => $this->formatDuaNilai($penentuan['norco'] ?? null, $penentuan['cond2'] ?? null),
            'cutoffTanpaPcp' => $this->formatPenentuan($penentuan['cutoff_tanpa_pcp'] ?? null),
            'cutoffTermasukPcp' => $this->formatPenentuan($penentuan['cutoff_termasuk_pcp'] ?? null),
            'hargaCutOffFinal' => $this->formatPenentuan($penentuan['harga_cutoff'] ?? null),
            // Jadual syarikat + frekuensi
            'rows' => $rows,
            'bins' => $bins,
            'freq' => $freq,
            // State pemilihan (draf/submitted) — untuk pra-isi checkbox & status butang
            'selectionStatus' => $selection['status'] ?? null, // null | 'draft' | 'submitted'
            'selectedRefs' => $selection['selected_refs'] ?? [],
            'totalCount' => $totalCount,
            'requireAll' => $requireAll,
            'minSelect' => 10,
        ]);
    }

    /**
     * Format peratus: nombor pecahan (0.0758) → "7.58%". '-' bila null.
     */
    private function formatPeratus($value): string
    {
        return $value === null ? '-' : number_format((float) $value * 100, 2) . '%';
    }

    /**
     * Format nilai penentuan: nombor → "RM x,xxx.00"; string (TIADA/Tidak Berkenaan/-)
     * dipaparkan seadanya; null → "-".
     */
    private function formatPenentuan($value): string
    {
        if ($value === null) {
            return '-';
        }

        if (is_string($value)) {
            return $value; // TIADA / Tidak Berkenaan / -
        }

        return 'RM ' . number_format((float) $value, 2);
    }

    /**
     * Format dua nilai ("RM a and RM b") untuk baris Mean-15% / Mean-SD.
     */
    private function formatDuaNilai($a, $b): string
    {
        if ($a === null || $b === null) {
            return '-';
        }

        return 'RM ' . number_format((float) $a, 2) . ' and RM ' . number_format((float) $b, 2);
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

    /**
     * Simpan (draf) pemilihan checkbox jadual utama cut-off. Pengiraan & pengesahan
     * peraturan minimum-pilihan (>=10, atau SEMUA jika <=10 baris / semua FREAK)
     * dilakukan di penilaian (STOS) — suksel hanya hantar & terima.
     */
    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'tender' => ['required', 'string'],
            'selected_refs' => ['required', 'array', 'min:1'],
            'selected_refs.*' => ['string'],
        ]);

        if (! $this->stos->isConfigured()) {
            return response()->json(['message' => 'STOS backend tidak dikonfigurasi.'], 503);
        }

        try {
            $response = $this->stos->simpanCutOff($validated['tender'], [
                'selected_refs' => $validated['selected_refs'],
                'user_id' => auth()->id(),
            ]);

            $body = $response->json();

            if (! $response->successful()) {
                return response()->json([
                    'message' => $body['message'] ?? 'Gagal menyimpan perincian cut-off.',
                ], $response->status());
            }

            return response()->json([
                'message' => $body['message'] ?? 'Perincian cut-off telah disimpan.',
                'data' => $body['data'] ?? [],
            ]);
        } catch (\Throwable $e) {
            Log::error('Ralat menyimpan cut-off ke STOS.', [
                'tender' => $validated['tender'],
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Ralat sistem: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hantar (muktamadkan) cut-off. Dua langkah:
     *   1. Muktamadkan pemilihan di penilaian (STOS) — mesti sudah ada draf (Simpan).
     *   2. Naikkan status tender 8 -> 9 (kekal di suksel, ikut corak semua modul lain).
     */
    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if ($this->stos->isConfigured()) {
            try {
                $finalizeResponse = $this->stos->finalizeCutOff($tender->uuid, ['user_id' => auth()->id()]);
                $finalizeBody = $finalizeResponse->json();

                if (! $finalizeResponse->successful()) {
                    return response()->json([
                        'message' => $finalizeBody['message'] ?? 'Gagal memuktamadkan cut-off.',
                    ], $finalizeResponse->status());
                }
            } catch (\Throwable $e) {
                Log::error('Ralat memuktamadkan cut-off di STOS.', [
                    'tender_uuid' => $tender->uuid,
                    'error' => $e->getMessage(),
                ]);

                return response()->json(['message' => 'Ralat sistem: ' . $e->getMessage()], 500);
            }
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
