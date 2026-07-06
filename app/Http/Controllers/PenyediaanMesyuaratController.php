<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Models\Jawatankuasa;
use App\Services\StosBackendClient;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PenyediaanMesyuaratController extends Controller
{
    use AdvancesTenderProcessStatus;

    private const JENIS_LABELS = [
        'spec' => 'Jawatankuasa Spesifikasi',
        'open' => 'Jawatankuasa Pembuka',
        'tech' => 'Jawatankuasa Penilaian Teknikal',
        'fin' => 'Jawatankuasa Penilaian Kewangan',
        'harga' => 'Jawatankuasa Penilaian Sebut Harga/Tender',
    ];

    public function __construct(protected StosBackendClient $stos) {}

    public function index()
    {
        $tenders = Tender::query()
            ->with('tenderer')
            ->where('status_process_id', TenderProcessStatus::penyediaanMesyuaratListStatus())
            ->whereHas('jawatankuasas', function ($query) {
                $query->whereNotNull('user_id')
                    ->whereNotNull('dihantar_pemakluman_pada');
            })
            ->orderByDesc('id')
            ->get()
            ->map(function (Tender $tender) {
                return [
                    'uuid' => $tender->uuid,
                    'name' => $tender->name ?: '-',
                    'no_tender' => $tender->no_tender ?: $tender->ref_number ?: '-',
                    'ptj' => $tender->tenderer?->name ?? '-',
                    'tarikh_jual' => $tender->advertise_start_date
                        ? Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                        : '-',
                    'tarikh_tutup' => $tender->advertise_stop_date
                        ? Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                        : '-',
                    'harga' => number_format((float) ($tender->price ?? 0), 2),
                    'status' => $tender->status ?? '-',
                ];
            })
            ->values();

        return view('newModule.penyediaanMesyuarat.index_perincian', compact('tenders'));
    }

    public function show(Request $request)
    {
        $tender = Tender::with('tenderer')
            ->where('uuid', $request->query('tender'))
            ->firstOrFail();

        $visibleJenis = $this->resolveVisibleJenis($tender);

        if (empty($visibleJenis)) {
            return redirect()
                ->route('perincianMesyuarat')
                ->with('error', 'Tiada jawatankuasa yang layak. Sila lengkapkan pelantikan jawatankuasa terlebih dahulu.');
        }

        $membersByJenis = Jawatankuasa::with('user')
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $visibleJenis)
            ->whereNotNull('user_id')
            ->whereNotNull('dihantar_pemakluman_pada')
            ->orderBy('id')
            ->get()
            ->groupBy('jenis_jawatankuasa');

        $meetingsByJenis = $this->fetchMeetingsFromApi($tender->id, $visibleJenis);
        $tempatMesyuarat = $this->fetchTempatMesyuaratFromApi($tender->id);

        $uiTabs = collect($visibleJenis)->map(function ($jenis) {
            return [
                'ui' => $this->jenisToUiTab($jenis),
                'jenis' => $jenis,
                'label' => self::JENIS_LABELS[$jenis] ?? $jenis,
            ];
        })->values()->all();

        $jenisByUiTab = collect($uiTabs)->pluck('jenis', 'ui')->all();

        $meetingsForJs = collect($uiTabs)->mapWithKeys(function ($tab) use ($meetingsByJenis) {
            $rows = ($meetingsByJenis[$tab['jenis']] ?? collect())->values();

            return [$tab['ui'] => $rows];
        })->all();

        $meetingStatusByJenis = collect($visibleJenis)->mapWithKeys(function (string $jenis) use ($meetingsByJenis) {
            $rows = $meetingsByJenis[$jenis] ?? collect();
            $status = $rows->isNotEmpty() ? ($rows->last()['status'] ?? 'Draf') : 'Belum Disimpan';

            return [$jenis => $status];
        })->all();

        $stosConfigured = $this->stos->isConfigured();

        return view('newModule.penyediaanMesyuarat.perincian_mesyuarat', compact(
            'tender',
            'uiTabs',
            'jenisByUiTab',
            'meetingsForJs',
            'membersByJenis',
            'meetingsByJenis',
            'meetingStatusByJenis',
            'tempatMesyuarat',
            'stosConfigured'
        ));
    }

    public function simpan(Request $request)
    {
        return $this->persist($request, false);
    }

    public function hantar(Request $request)
    {
        return $this->persist($request, true);
    }

    protected function persist(Request $request, bool $submit): \Illuminate\Http\JsonResponse
    {
        $tender = $this->resolveTender($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->stos->isConfigured()) {
            return response()->json(['message' => 'STOS backend tidak dikonfigurasi.'], 503);
        }

        $validated = $this->validateMeetingPayload($request, $tender);
        $apiPayload = [
            'jenis_jawatankuasa' => $validated['jenis_jawatankuasa'],
            'rows' => $validated['rows'],
        ];

        try {
            $response = $submit
                ? $this->stos->submitPenyediaanMesyuarat($tender->id, $apiPayload)
                : $this->stos->savePenyediaanMesyuarat($tender->id, $apiPayload);

            if (! $response->successful()) {
                $message = $response->json('message') ?? 'Gagal memproses perincian mesyuarat.';

                return response()->json(['message' => $message], $response->status());
            }

            $message = $submit
                ? 'Jemputan mesyuarat berjaya dihantar.'
                : 'Perincian mesyuarat berjaya disimpan.';

            if ($submit) {
                $emailsSent = $response->json('data.emails_sent');
                if (is_numeric($emailsSent)) {
                    $message .= ' (' . (int) $emailsSent . ' emel dihantar)';
                }

                $this->tryAdvancePenyediaanMesyuarat($tender);
            }

            return response()->json(['message' => $message]);
        } catch (\Throwable $e) {
            Log::error('Penyediaan mesyuarat persist failed', [
                'tender_id' => $tender->id,
                'submit' => $submit,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Ralat sistem: ' . $e->getMessage()], 500);
        }
    }

    protected function fetchTempatMesyuaratFromApi(int $tenderId): array
    {
        if (! $this->stos->isConfigured()) {
            return [];
        }

        try {
            $response = $this->stos->getPenyediaanMesyuarat($tenderId);

            if (! $response->successful()) {
                return [];
            }

            $data = $response->json('data') ?? $response->json() ?? [];

            return $data['tempat_mesyuarat'] ?? [];
        } catch (\Throwable $e) {
            Log::warning('STOS get tempat mesyuarat exception', [
                'tender_id' => $tenderId,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    protected function fetchMeetingsFromApi(int $tenderId, array $visibleJenis): \Illuminate\Support\Collection
    {
        if (! $this->stos->isConfigured()) {
            return collect();
        }

        try {
            $response = $this->stos->getPenyediaanMesyuarat($tenderId);

            if (! $response->successful()) {
                Log::warning('STOS get penyediaan-mesyuarat failed', [
                    'tender_id' => $tenderId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return collect();
            }

            return $this->mapMeetingsFromApi($response->json(), $visibleJenis);
        } catch (\Throwable $e) {
            Log::warning('STOS get penyediaan-mesyuarat exception', [
                'tender_id' => $tenderId,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    protected function mapMeetingsFromApi(?array $body, array $visibleJenis): \Illuminate\Support\Collection
    {
        $data = $body['data'] ?? $body ?? [];
        $grouped = $data['meetings_by_jenis']
            ?? $data['penyediaan_mesyuarat']
            ?? $data['meetings']
            ?? [];

        if (isset($grouped[0]) && is_array($grouped[0])) {
            $grouped = collect($grouped)
                ->groupBy('jenis_jawatankuasa')
                ->map(fn ($rows) => $rows->values()->all())
                ->all();
        }

        return collect($visibleJenis)->mapWithKeys(function ($jenis) use ($grouped) {
            $rows = collect($grouped[$jenis] ?? [])->map(function ($row) {
                $tarikh = $row['tarikh_mesyuarat'] ?? null;

                return [
                    'tarikh_mesyuarat' => $tarikh
                        ? Carbon::parse($tarikh)->format('Y-m-d')
                        : null,
                    'masa' => $row['masa'] ?? '',
                    'tempat' => $row['tempat'] ?? '',
                    'status' => $row['status'] ?? 'Draf',
                ];
            })->filter(fn ($row) => ! empty($row['tarikh_mesyuarat']))->values();

            return [$jenis => $rows];
        });
    }

    private function validateMeetingPayload(Request $request, Tender $tender): array
    {
        $visibleJenis = $this->resolveVisibleJenis($tender);

        return $request->validate([
            'tender' => ['required', 'string', 'exists:tenders,uuid'],
            'jenis_jawatankuasa' => ['required', Rule::in($visibleJenis)],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.tarikh_mesyuarat' => ['required', 'date', 'after_or_equal:today'],
            'rows.*.masa' => ['required', 'string', 'max:10'],
            'rows.*.tempat' => ['required', 'string', 'max:255'],
        ], [
            'rows.*.tarikh_mesyuarat.after_or_equal' => 'Tarikh mesyuarat mestilah hari ini atau selepasnya.',
        ]);
    }

    private function resolveVisibleJenis(Tender $tender): array
    {
        $existing = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->whereNotNull('user_id')
            ->whereNotNull('dihantar_pemakluman_pada')
            ->distinct()
            ->pluck('jenis_jawatankuasa')
            ->all();

        $order = ['open', 'tech', 'fin', 'harga'];

        return array_values(array_intersect($order, $existing));
    }

    private function jenisToUiTab(string $jenis): string
    {
        return match ($jenis) {
            'spec' => 'spesifikasi',
            'open' => 'pembuka',
            'tech' => 'teknikal',
            'fin' => 'kewangan',
            'harga' => 'sebutharga',
            default => $jenis,
        };
    }

    private function tryAdvancePenyediaanMesyuarat(Tender $tender): void
    {
        $visibleJenis = $this->resolveVisibleJenis($tender);

        if (empty($visibleJenis)) {
            return;
        }

        $meetingsByJenis = $this->fetchMeetingsFromApi($tender->id, $visibleJenis);
        $draftStatuses = ['Draf', 'Belum Disimpan', ''];

        foreach ($visibleJenis as $jenis) {
            $rows = $meetingsByJenis[$jenis] ?? collect();
            $hasSubmitted = $rows->contains(function (array $row) use ($draftStatuses) {
                $status = trim((string) ($row['status'] ?? ''));

                return $status !== '' && ! in_array($status, $draftStatuses, true);
            });

            if (! $hasSubmitted) {
                return;
            }
        }

        $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENYEDIAAN_MESYUARAT,
            TenderProcessStatus::penyediaanMesyuaratListStatus()
        );
    }

    private function resolveTender($identifier): ?Tender
    {
        if (empty($identifier)) {
            return null;
        }

        if (is_numeric($identifier)) {
            return Tender::query()->where('id', (int) $identifier)->first();
        }

        return Tender::query()->where('uuid', $identifier)->first();
    }
}
