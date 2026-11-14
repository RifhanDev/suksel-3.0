<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Models\Jawatankuasa;
use App\Services\StosBackendClient;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\User;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PenyediaanMesyuaratController extends Controller
{
    use AdvancesTenderProcessStatus;
    use RestrictsTenderByRole;
    use Helper;

    private const JENIS_LABELS = [
        'spec' => 'Jawatankuasa Spesifikasi',
        'open' => 'Jawatankuasa Pembuka',
        'tech' => 'Jawatankuasa Penilaian Teknikal',
        'fin' => 'Jawatankuasa Penilaian Kewangan',
        'eval' => 'Jawatankuasa Penilaian Sebut Harga/Tender',
        'harga' => 'Jawatankuasa Penilaian Sebut Harga/Tender',
    ];

    /**
     * Kekalkan tender dalam senarai Perincian Mesyuarat sepanjang status 6 hingga
     * 10 supaya pegawai boleh membuat perubahan sehingga fasa ini berakhir.
     */
    private const MESYUARAT_PHASE_STATUSES = [
        TenderProcessStatus::HANTAR_DOKUMEN_SYARIKAT,
        TenderProcessStatus::PENYEDIAAN_MESYUARAT,
        TenderProcessStatus::PENILAIAN_PEMBUKA,
        TenderProcessStatus::CUT_OFF,
        TenderProcessStatus::PENILAIAN_TEKNIKAL,
    ];

    public function __construct(protected StosBackendClient $stos)
    {
        $this->menuMiddleware('Meeting:list', [
            'except' => ['indexKehadiran', 'showKehadiran', 'simpanKehadiran'],
        ]);
        $this->menuMiddleware('MeetingAttendance:list', [
            'only' => ['indexKehadiran', 'showKehadiran', 'simpanKehadiran'],
        ]);
    }

    public function index(Request $request)
    {
        $tenders = $this->listTendersForMesyuarat($request);

        return view('newModule.penyediaanMesyuarat.index_perincian', compact('tenders'));
    }

    public function indexKehadiran(Request $request)
    {
        $tenders = $this->listTendersForKehadiran($request);

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
        $this->assertOwnAgency($tender);

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

    public function kemaskiniAhli(Request $request): \Illuminate\Http\JsonResponse
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! in_array((int) $tender->status_process_id, self::MESYUARAT_PHASE_STATUSES, true)) {
            return response()->json(['message' => 'Kemaskini ahli jawatankuasa tidak dibenarkan pada peringkat tender ini.'], 422);
        }

        $jenisYangLayak = $this->tabJenisForTender($tender);
        $validated = $request->validate([
            'tender' => ['required', 'string', Rule::in([$tender->uuid])],
            'jenis_jawatankuasa' => ['required', Rule::in($jenisYangLayak)],
            'rows' => ['required', 'array'],
            'rows.*.user_id' => ['required', 'integer', 'distinct', 'exists:users,id'],
            'rows.*.p_p' => ['required', Rule::in(['0', '1'])],
            'rows.*.peranan' => ['required', Rule::in(['1', '2', '3'])],
        ]);

        $jenis = $validated['jenis_jawatankuasa'];
        $rows = collect($validated['rows'])->map(fn ($row) => [
            'user_id' => (int) $row['user_id'],
            'p_p' => (string) $row['p_p'],
            'peranan' => (string) $row['peranan'],
        ]);
        $required = Jawatankuasa::minimumRequirements()[$jenis] ?? ['pengerusi' => 1, 'ahli' => 2];
        $pengerusiCount = $rows->where('peranan', Jawatankuasa::PERANAN_PENGERUSI)->count();
        $ahliCount = $rows->where('peranan', Jawatankuasa::PERANAN_AHLI)->count();
        if (! Jawatankuasa::satisfiesRequirement($jenis, $pengerusiCount, $ahliCount)) {
            throw ValidationException::withMessages([
                'rows' => ["Jawatankuasa memerlukan sekurang-kurangnya {$required['pengerusi']} Pengerusi dan {$required['ahli']} Ahli."],
            ]);
        }

        $users = User::query()
            ->whereNull('vendor_id')
            ->whereNotNull('ic_number')
            ->where('ic_number', '!=', '')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->whereIn('id', $rows->pluck('user_id'))
            ->get()
            ->keyBy('id');
        if ($users->count() !== $rows->count() || $users->contains(fn (User $user) => ! filter_var($user->email, FILTER_VALIDATE_EMAIL))) {
            throw ValidationException::withMessages([
                'rows' => ['Pilih akaun pegawai yang mempunyai nombor kad pengenalan dan alamat e-mel yang sah.'],
            ]);
        }

        $storedJenis = $this->jenisForMemberQuery($jenis);
        $current = Jawatankuasa::with('user')
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $storedJenis)
            ->whereNotNull('user_id')
            ->orderBy('id')
            ->get();
        $currentByUser = $current->keyBy('user_id');
        $meta = Jawatankuasa::withInactive()
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $storedJenis)
            ->orderByDesc('id')
            ->first();

        $affected = [];
        $keptUserIds = [];
        DB::transaction(function () use ($rows, $users, $currentByUser, $current, $tender, $jenis, $meta, &$affected, &$keptUserIds) {
            foreach ($rows as $row) {
                $userId = $row['user_id'];
                $keptUserIds[] = $userId;
                $user = $users->get($userId);
                $existing = $currentByUser->get($userId);

                if ($existing) {
                    $changed = (string) $existing->peranan !== $row['peranan'] || (string) $existing->p_p !== $row['p_p'];
                    if ($changed || ! $existing->dihantar_pemakluman_pada) {
                        $affected[] = [
                            'action' => 'updated',
                            'jawatankuasa_id' => $existing->id,
                            'to' => $user?->email,
                            'name' => $user?->name ?? '-',
                            'old_peranan' => (string) $existing->peranan,
                            'old_p_p' => (string) $existing->p_p,
                            'peranan' => $row['peranan'],
                            'p_p' => $row['p_p'],
                        ];
                    }
                    if ($changed) {
                        $existing->update(['is_current' => false]);
                        $replacement = Jawatankuasa::query()->create([
                            'tender_id' => $tender->id,
                            'jenis_jawatankuasa' => $existing->jenis_jawatankuasa,
                            'p_p' => $row['p_p'],
                            'peranan' => $row['peranan'],
                            'user_id' => $userId,
                            'catatan' => $existing->catatan,
                            'dokumen_sokongan_nama' => $existing->dokumen_sokongan_nama,
                            'dokumen_sokongan_path' => $existing->dokumen_sokongan_path,
                            'dihantar_pemakluman_pada' => null,
                            'is_current' => true,
                        ]);
                        $affected[array_key_last($affected)]['jawatankuasa_id'] = $replacement->id;
                        $affected[array_key_last($affected)]['rollback'] = [
                            'deactivate_id' => $replacement->id,
                            'activate_id' => $existing->id,
                        ];
                    }
                    continue;
                }

                $newMember = Jawatankuasa::query()->create([
                    'tender_id' => $tender->id,
                    'jenis_jawatankuasa' => $jenis,
                    'p_p' => $row['p_p'],
                    'peranan' => $row['peranan'],
                    'user_id' => $userId,
                    'catatan' => $meta?->catatan,
                    'dokumen_sokongan_nama' => $meta?->dokumen_sokongan_nama,
                    'dokumen_sokongan_path' => $meta?->dokumen_sokongan_path,
                    'dihantar_pemakluman_pada' => null,
                    'is_current' => true,
                ]);
                $affected[] = [
                    'action' => 'added',
                    'jawatankuasa_id' => $newMember->id,
                    'rollback' => ['deactivate_id' => $newMember->id],
                    'to' => $user?->email,
                    'name' => $user?->name ?? '-',
                    'peranan' => $row['peranan'],
                    'p_p' => $row['p_p'],
                ];
            }

            foreach ($current as $member) {
                if (in_array((int) $member->user_id, $keptUserIds, true)) {
                    continue;
                }
                $affected[] = [
                    'action' => 'removed',
                    'jawatankuasa_id' => $member->id,
                    'rollback' => ['activate_id' => $member->id],
                    'to' => $member->user?->email,
                    'name' => $member->user?->name ?? '-',
                    'old_peranan' => (string) $member->peranan,
                    'old_p_p' => (string) $member->p_p,
                    'peranan' => null,
                    'p_p' => null,
                ];
                $member->update(['is_current' => false]);
            }
        });

        $jenisLabel = self::JENIS_LABELS[$jenis] ?? $jenis;
        $perananLabels = ['1' => 'Pengerusi', '2' => 'Setiausaha', '3' => 'Ahli'];
        $notified = 0;
        $failedChanges = [];
        foreach ($affected as $change) {
            if (empty($change['to'])) {
                $failedChanges[] = $change;
                continue;
            }
            $viewData = [
                'tender' => $tender,
                'jenisLabel' => $jenisLabel,
                'recipientName' => $change['name'],
                'action' => $change['action'],
                'oldRole' => $perananLabels[$change['old_peranan'] ?? ''] ?? null,
                'newRole' => $perananLabels[$change['peranan'] ?? ''] ?? null,
                'oldPp' => isset($change['old_p_p']) ? ($change['old_p_p'] === '1' ? 'Ya' : 'Tidak') : null,
                'newPp' => isset($change['p_p']) ? ($change['p_p'] === '1' ? 'Ya' : 'Tidak') : null,
            ];
            $subject = 'Kemaskini keahlian ' . $jenisLabel . ' - ' . ($tender->no_tender ?: $tender->ref_number ?: 'Tender');
            $result = $this->createEmailQueue(
                view('emails.pemakluman_kemaskini_jawatankuasa', $viewData)->render(),
                $change['to'],
                $subject
            );
            if ($this->emailSendSucceeded($result)) {
                $notified++;
                if (! empty($change['jawatankuasa_id'])) {
                    Jawatankuasa::query()->whereKey($change['jawatankuasa_id'])
                        ->update(['dihantar_pemakluman_pada' => Carbon::now()]);
                }
            } else {
                $failedChanges[] = $change;
            }
        }

        if ($failedChanges) {
            DB::transaction(function () use ($failedChanges) {
                foreach ($failedChanges as $change) {
                    $rollback = $change['rollback'] ?? [];
                    if (! empty($rollback['deactivate_id'])) {
                        Jawatankuasa::withInactive()->whereKey($rollback['deactivate_id'])
                            ->update(['is_current' => false]);
                    }
                    if (! empty($rollback['activate_id'])) {
                        Jawatankuasa::withInactive()->whereKey($rollback['activate_id'])
                            ->update(['is_current' => true]);
                    }
                }
            });
        }

        $syncMessage = null;
        if ($this->stos->isConfigured()) {
            try {
                $sync = $this->stos->syncPenyediaanMesyuaratAttendees($tender->id, $jenis);
                if (! $sync->successful()) {
                    $syncMessage = 'Senarai kehadiran belum dapat diselaraskan. Sila muat semula atau hubungi pentadbir.';
                    Log::error('Meeting attendee snapshot sync failed after committee update', [
                        'tender_id' => $tender->id,
                        'jenis_jawatankuasa' => $jenis,
                        'status' => $sync->status(),
                        'body' => $sync->json(),
                    ]);
                }
            } catch (\Throwable $e) {
                $syncMessage = 'Senarai kehadiran belum dapat diselaraskan. Sila muat semula atau hubungi pentadbir.';
                Log::error('Meeting attendee snapshot sync exception after committee update', [
                    'tender_id' => $tender->id,
                    'jenis_jawatankuasa' => $jenis,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            $syncMessage = 'STOS backend tidak dikonfigurasi; jemputan mesyuarat sedia ada tidak dikemaskini.';
        }

        $savedChanges = count($affected) - count($failedChanges);
        if (! $affected) {
            $message = 'Tiada perubahan keahlian dikesan.';
        } else {
            $message = 'Perubahan ahli berjaya disimpan untuk ' . $savedChanges . ' ahli; emel berjaya dimasukkan ke giliran untuk ' . $notified . ' ahli.';
            if ($failedChanges) {
                $message .= ' Perubahan bagi ' . count($failedChanges) . ' ahli dibatalkan kerana emel pemakluman gagal dimasukkan ke giliran.';
            }
        }
        if ($syncMessage) {
            $message .= ' ' . $syncMessage;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'notified' => $notified,
            'changes' => $savedChanges,
            'partial' => (bool) ($failedChanges || $syncMessage),
        ]);
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
            'attendance.*.meeting_attendee_id' => ['required', 'integer'],
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
                $this->tryAdvancePenyediaanMesyuarat($tender);

                // STOS renders the invitation (HTML body + PDF memo) and returns the
                // recipients. Actual delivery goes through the shared mail-server queue
                // here — only after STOS confirmed success — so this stays consistent
                // with the rest of the system's email pipeline.
                $this->queueMesyuaratInvitations($response->json('data.recipients') ?? []);
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

    /**
     * Senarai Penyediaan Mesyuarat kekal tersedia sepanjang fasa status mesyuarat,
     * walaupun semua tab jemputan sudah dihantar, supaya pegawai boleh membuat
     * perubahan sehingga tender bergerak ke fasa seterusnya.
     */
    protected function listTendersForMesyuarat(?Request $request = null): \Illuminate\Support\Collection
    {
        return $this->listTendersInMesyuaratPhase($request);
    }

    /**
     * Senarai Kehadiran Mesyuarat: tender yang sekurang-kurangnya satu tab sudah
     * dihantar — kehadiran hanya boleh direkod selepas jemputan mesyuarat keluar.
     */
    protected function listTendersForKehadiran(?Request $request = null): \Illuminate\Support\Collection
    {
        return $this->listTendersInMesyuaratPhase($request, function ($query) {
            $query->whereRaw($this->submittedTabCountSql() . ' >= 1');
        });
    }

    protected function listTendersInMesyuaratPhase(?Request $request, ?callable $applyScope = null): \Illuminate\Support\Collection
    {
        $query = $this->applyOwnAgencyScope(Tender::query())
            ->with('tenderer')
            ->whereIn('status_process_id', self::MESYUARAT_PHASE_STATUSES);

        if ($applyScope !== null) {
            $applyScope($query);
        }

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
                $inputDate = trim((string) $request->input('tarikh'));
                $parsedDate = Carbon::createFromFormat('!d/m/Y', $inputDate);
                if (!$parsedDate || $parsedDate->format('d/m/Y') !== $inputDate) {
                    throw new \InvalidArgumentException('Tarikh tidak sah.');
                }

                $date = $parsedDate->format('Y-m-d');
                $query->whereDate('advertise_start_date', '<=', $date)
                    ->whereDate('advertise_stop_date', '>=', $date);
            } catch (\Exception $e) {
                $query->whereRaw('1 = 0');
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

    /**
     * Bilangan tab jemputan yang SUDAH dihantar untuk sesuatu tender.
     * 'harga' dinormalkan jadi 'eval' (jawatankuasa sama) supaya tak terkira dua kali,
     * dan hanya jenis yang relevan dengan peringkat tender dikira.
     */
    private function submittedTabCountSql(): string
    {
        return "(
            SELECT COUNT(DISTINCT CASE
                        WHEN pm.jenis_jawatankuasa IN ('eval', 'harga') THEN 'eval'
                        ELSE pm.jenis_jawatankuasa
                   END)
            FROM penyediaan_mesyuarat pm
            WHERE pm.tender_id = tenders.id
              AND pm.status = 'Dihantar'
              AND (
                    (COALESCE(tenders.tender_peringkat, 2) = 1 AND pm.jenis_jawatankuasa IN ('open', 'eval', 'harga'))
                 OR (COALESCE(tenders.tender_peringkat, 2) <> 1 AND pm.jenis_jawatankuasa IN ('open', 'tech', 'fin'))
              )
        )";
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

        $tender = $query->firstOrFail();
        $this->assertOwnAgency($tender);

        return $tender;
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

        // Mesyuarat tidak berlaku serentak untuk semua jawatankuasa — tab Pembuka
        // dihantar dahulu, tab lain menyusul kemudian. Jadi cukup satu tab sahaja
        // dihantar untuk tender bergerak ke fasa seterusnya.
        $hasAnySubmitted = collect($visibleJenis)->contains(function (string $jenis) use ($meetingsByJenis, $draftStatuses) {
            $rows = $meetingsByJenis[$jenis] ?? collect();

            return $rows->contains(function (array $row) use ($draftStatuses) {
                $status = trim((string) ($row['status'] ?? ''));

                return $status !== '' && ! in_array($status, $draftStatuses, true);
            });
        });

        if (! $hasAnySubmitted) {
            return;
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

        $tender = is_numeric($identifier)
            ? Tender::query()->where('id', (int) $identifier)->first()
            : Tender::query()->where('uuid', $identifier)->first();

        if ($tender) {
            $this->assertOwnAgency($tender);
        }

        return $tender;
    }
}
