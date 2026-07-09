<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Models\Jawatankuasa;
use App\Services\StosBackendClient;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PenyediaanMesyuaratController extends Controller
{
    use AdvancesTenderProcessStatus;
    use Helper;

    private const JENIS_LABELS = [
        'spec' => 'Jawatankuasa Spesifikasi',
        'open' => 'Jawatankuasa Pembuka',
        'tech' => 'Jawatankuasa Penilaian Teknikal',
        'fin' => 'Jawatankuasa Penilaian Kewangan',
        'eval' => 'Jawatankuasa Penilaian Sebut Harga/Tender',
        'harga' => 'Jawatankuasa Penilaian Sebut Harga/Tender',
    ];

    public function __construct(protected StosBackendClient $stos) {}

    public function index(Request $request)
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
        $tenders = $this->listTendersForMesyuarat($request);

        return view('newModule.penyediaanMesyuarat.index_perincian', compact('tenders'));
    }

    public function indexKehadiran()
    {
        $tenders = $this->listTendersForMesyuarat();

        return view('newModule.penyediaanMesyuarat.index_jawatankuasa', compact('tenders'));
    }

    public function show(Request $request)
    {
        $tender = $this->resolveTenderFromQuery($request);
        $tabJenis = $this->tabJenisForTender($tender);

        $membersByJenis = $this->loadMembersByTabJenis($tender, $tabJenis);
        $meetingsByJenis = $this->fetchMeetingsFromApi($tender->id, $tabJenis);
        $tempatMesyuarat = $this->fetchTempatMesyuaratFromApi($tender->id);

        $uiTabs = $this->buildUiTabs($tabJenis);
        $jenisByUiTab = collect($uiTabs)->pluck('jenis', 'ui')->all();
        $meetingsForJs = collect($uiTabs)->mapWithKeys(function ($tab) use ($meetingsByJenis) {
            return [$tab['ui'] => ($meetingsByJenis[$tab['jenis']] ?? collect())->values()];
        })->all();

        $meetingStatusByJenis = collect($tabJenis)->mapWithKeys(function (string $jenis) use ($meetingsByJenis) {
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

    public function showKehadiran(Request $request)
    {
        $tender = Tender::with('tenderer')
            ->where('uuid', $request->query('tender'))
            ->firstOrFail();

        $visibleJenis = $this->resolveVisibleJenis($tender);

        if (empty($visibleJenis)) {
            return redirect()
                ->route('jawatankuasaMesyuarat')
                ->with('error', 'Tiada jawatankuasa yang layak. Sila lengkapkan pelantikan jawatankuasa terlebih dahulu.');
        }

        $meetingId = $request->query('meeting_id') ? (int) $request->query('meeting_id') : null;
        $kehadiranData = $this->fetchKehadiranFromApi($tender->id, $meetingId);

        $uiTabs = $this->buildUiTabs($visibleJenis);
        $jenisByUiTab = collect($uiTabs)->pluck('jenis', 'ui')->all();
        $meetingsByJenis = collect($kehadiranData['meetings_by_jenis'] ?? []);
        $membersByJenis = collect($kehadiranData['members_by_jenis'] ?? []);
        $selectedMeeting = $kehadiranData['selected_meeting'] ?? null;
        $untukKelulusan = (bool) ($kehadiranData['untuk_kelulusan'] ?? false);
        $activeJenis = $selectedMeeting['jenis_jawatankuasa'] ?? ($visibleJenis[0] ?? null);
        $stosConfigured = $this->stos->isConfigured();

        return view('newModule.penyediaanMesyuarat.kehadiran_mesyuarat', compact(
            'tender',
            'uiTabs',
            'jenisByUiTab',
            'meetingsByJenis',
            'membersByJenis',
            'selectedMeeting',
            'untukKelulusan',
            'activeJenis',
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

    public function simpanKehadiran(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->stos->isConfigured()) {
            return response()->json(['message' => 'STOS backend tidak dikonfigurasi.'], 503);
        }

        $visibleJenis = $this->resolveVisibleJenis($tender);

        $validated = $request->validate([
            'tender' => ['required', 'string', 'exists:tenders,uuid'],
            'jenis_jawatankuasa' => ['required', Rule::in($visibleJenis)],
            'penyediaan_mesyuarat_id' => ['required', 'integer'],
            'untuk_kelulusan' => ['nullable', 'boolean'],
            'attendance' => ['required', 'array', 'min:1'],
            'attendance.*.jawatankuasa_id' => ['required', 'integer'],
            'attendance.*.hadir' => ['nullable', 'boolean'],
        ]);

        try {
            $response = $this->stos->saveKehadiranMesyuarat($tender->id, [
                'jenis_jawatankuasa' => $validated['jenis_jawatankuasa'],
                'penyediaan_mesyuarat_id' => $validated['penyediaan_mesyuarat_id'],
                'untuk_kelulusan' => $validated['untuk_kelulusan'] ?? false,
                'attendance' => $validated['attendance'],
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'message' => $response->json('message') ?? 'Gagal menyimpan kehadiran mesyuarat.',
                ], $response->status());
            }

            return response()->json([
                'message' => $response->json('message') ?? 'Kehadiran mesyuarat berjaya disimpan.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Kehadiran mesyuarat persist failed', [
                'tender_id' => $tender->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Ralat sistem: ' . $e->getMessage()], 500);
        }
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
                // STOS renders the invitation (HTML body + PDF memo) and returns the
                // recipients. Actual delivery goes through the shared mail-server queue
                // here — only after STOS confirmed success — so this stays consistent
                // with the rest of the system's email pipeline.
                $queued = $this->queueMesyuaratInvitations($response->json('data.recipients') ?? []);
                $message .= ' (' . $queued . ' emel beratur untuk dihantar)';
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

    /**
     * Queue each meeting invitation (already rendered by STOS) through the shared
     * mail-server pipeline. Returns the number of emails successfully queued.
     *
     * @param  array  $recipients  [{ to, subject, content, attachments: [{ filename, mime, data }] }]
     */
    protected function queueMesyuaratInvitations(array $recipients): int
    {
        $queued = 0;

        foreach ($recipients as $recipient) {
            $to = $recipient['to'] ?? null;

            if (empty($to)) {
                continue;
            }

            $result = $this->createEmailQueue(
                $recipient['content'] ?? '',
                $to,
                $recipient['subject'] ?? '',
                $recipient['attachments'] ?? []
            );

            if ($this->emailSendSucceeded($result)) {
                $queued++;
            } else {
                Log::error('Penyediaan mesyuarat invitation queue failed', [
                    'to' => $to,
                    'reason' => $result,
                ]);
            }
        }

        return $queued;
    }

    protected function listTendersForMesyuarat(?Request $request = null): \Illuminate\Support\Collection
    {
        $query = Tender::query()
            ->with('tenderer')
            ->where('status_process_id', 5);

        if ($request?->filled('no_tender')) {
            $term = $request->input('no_tender');
            $query->where(function ($q) use ($term) {
                $q->where('ref_number', 'like', "%{$term}%")
                    ->orWhere('no_tender', 'like', "%{$term}%");
            });
        }

        if ($request?->filled('tajuk')) {
            $query->where('name', 'like', '%' . $request->input('tajuk') . '%');
        }

        if ($request?->filled('status')) {
            $query->where('status', 'like', '%' . $request->input('status') . '%');
        }

        if ($request?->filled('tarikh')) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $request->input('tarikh'))->format('Y-m-d');
                $query->whereDate('advertise_start_date', '<=', $date)
                    ->whereDate('advertise_stop_date', '>=', $date);
            } catch (\Exception $e) {
                // ignore invalid date
            }
        }

        return $query->orderByDesc('id')
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
                    'status' => $tender->status ?? 'Dalam Proses',
                ];
            })
            ->values();
    }

    protected function fetchKehadiranFromApi(int $tenderId, ?int $meetingId): array
    {
        if (! $this->stos->isConfigured()) {
            return [];
        }

        try {
            $response = $this->stos->getKehadiranMesyuarat($tenderId, $meetingId);

            if (! $response->successful()) {
                Log::warning('STOS get kehadiran-mesyuarat failed', [
                    'tender_id' => $tenderId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return [];
            }

            return $response->json('data') ?? [];
        } catch (\Throwable $e) {
            Log::warning('STOS get kehadiran-mesyuarat exception', [
                'tender_id' => $tenderId,
                'error' => $e->getMessage(),
            ]);

            return [];
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
            $sources = $jenis === 'eval' ? ['eval', 'harga'] : [$jenis];
            $rows = collect($sources)
                ->flatMap(fn (string $source) => $grouped[$source] ?? [])
                ->map(function ($row) {
                $tarikh = $row['tarikh_mesyuarat'] ?? null;

                return [
                    'id' => $row['id'] ?? null,
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
        $tabJenis = $this->tabJenisForTender($tender);

        return $request->validate([
            'tender' => ['required', 'string', 'exists:tenders,uuid'],
            'jenis_jawatankuasa' => ['required', Rule::in($tabJenis)],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.tarikh_mesyuarat' => ['required', 'date', 'after_or_equal:today'],
            'rows.*.masa' => ['required', 'string', 'max:10'],
            'rows.*.tempat' => ['required', 'string', 'max:255'],
        ], [
            'rows.*.tarikh_mesyuarat.after_or_equal' => 'Tarikh mesyuarat mestilah hari ini atau selepasnya.',
        ]);
    }

    /**
     * Tab jenis ikut PDF: bergantung tender_peringkat sahaja.
     */
    private function tabJenisForTender(Tender $tender): array
    {
        if ((int) ($tender->tender_peringkat ?? 2) === 1) {
            return ['open', 'eval'];
        }

        return ['open', 'tech', 'fin'];
    }

    private function jenisForMemberQuery(string $tabJenis): array
    {
        return $tabJenis === 'eval' ? ['eval', 'harga'] : [$tabJenis];
    }

    private function loadMembersByTabJenis(Tender $tender, array $tabJenis): \Illuminate\Support\Collection
    {
        return collect($tabJenis)->mapWithKeys(function (string $jenis) use ($tender) {
            $members = Jawatankuasa::with('user')
                ->where('tender_id', $tender->id)
                ->whereIn('jenis_jawatankuasa', $this->jenisForMemberQuery($jenis))
                ->whereNotNull('user_id')
                ->orderBy('id')
                ->get();

            return [$jenis => $members];
        });
    }

    private function resolveVisibleJenis(Tender $tender): array
    {
        return $this->tabJenisForTender($tender);
    }

    private function resolveTenderFromQuery(Request $request): Tender
    {
        $identifier = $request->query('tender');

        if (empty($identifier)) {
            abort(404);
        }

        $query = Tender::with('tenderer');

        if (is_numeric($identifier)) {
            $query->where('id', (int) $identifier);
        } else {
            $query->where('uuid', $identifier);
        }

        return $query->firstOrFail();
    }

    private function buildUiTabs(array $visibleJenis): array
    {
        return collect($visibleJenis)->map(function ($jenis) {
            return [
                'ui' => $this->jenisToUiTab($jenis),
                'jenis' => $jenis,
                'label' => self::JENIS_LABELS[$jenis] ?? $jenis,
            ];
        })->values()->all();
    }

    private function jenisToUiTab(string $jenis): string
    {
        return match ($jenis) {
            'spec' => 'spesifikasi',
            'open' => 'pembuka',
            'tech' => 'teknikal',
            'fin' => 'kewangan',
            'eval', 'harga' => 'sebutharga',
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
