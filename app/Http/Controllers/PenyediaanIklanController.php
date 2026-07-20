<?php

namespace App\Http\Controllers;

use App\Models\PenyediaanIklan;
use App\Models\RefState;
use App\Services\PenyediaanIklanService;
use App\Services\StosBackendClient;
use App\Services\StosTenderChecklistSync;
use App\Support\TenderProcessStatus;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderReviewPresenter;
use App\Tender;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PenyediaanIklanController extends Controller
{
    public function __construct(
        protected StosBackendClient $stos,
        protected PenyediaanIklanService $penyediaanIklanService,
        protected StosTenderChecklistSync $checklistSync
    ) {}

    public function index()
    {
        $tenders = Tender::query()
            ->where('status_process_id', TenderProcessStatus::penyediaanIklanListStatus())
            ->with('tenderer')
            ->orderByDesc('id')
            ->get()
            ->map(function ($tender) {
                return [
                    'id' => $tender->id,
                    'no_tender' => $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id,
                    'tajuk' => $tender->name ?: '-',
                    'ptj' => $tender->tenderer?->name ?? '-',
                    'status_label' => 'Penyediaan Iklan',
                    'show_url' => route('penyediaanIklan.show', $tender->id),
                ];
            });

        return view('newModule.penyediaanIklan.list', compact('tenders'));
    }

    public function show(Tender $tender)
    {
        if ((int) $tender->status_process_id !== TenderProcessStatus::penyediaanIklanListStatus()) {
            /** @var \Illuminate\Http\RedirectResponse $redirect */
            $redirect = redirect()->route('penyediaanIklan.index');
            return $redirect->with('error', 'Tender ini belum selesai pengurusan spesifikasi (status ' . TenderProcessStatus::SPESIFIKASI_KEWANGAN . ').');
        }

        $country_states = RefState::where('display_status', 1)->get();
        $stored = $this->penyediaanIklanService->getForTender($tender);
        $meta = $stored['meta'];
        $penyediaanIklan = $stored['penyediaan_iklan'];

        // Prefer local DB when a record exists. STOS is only a fallback for tenders
        // with no local draft yet — otherwise empty STOS meta would hide saved data.
        if ($this->stos->isConfigured() && $penyediaanIklan === null) {
            try {
                $response = $this->stos->getPenyediaanIklan($tender->id);
                if ($response->successful()) {
                    $body = $response->json();
                    $meta = $body['data']['meta'] ?? $meta;
                    $penyediaanIklan = $body['data']['penyediaan_iklan'] ?? $penyediaanIklan;
                }
            } catch (\Throwable $e) {
                Log::warning('STOS get penyediaan-iklan failed, using local data', [
                    'tender_id' => $tender->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $tender->load(['tenderer', 'codes.code', 'creator', 'officer', 'siteVisits']);
        $this->checklistSync->syncForTender($tender);
        $tenderReview = TenderReviewPresenter::for($tender);
        $tenderDokumen = TenderDokumenPresenter::for($tender);
        $pegawaiPilihan = $this->pegawaiPilihanForTender($tender);

        if (empty($meta['kelulusan'])) {
            $meta['kelulusan'] = PenyediaanIklan::defaultKelulusan();
        }

        return view('newModule.penyediaanIklan.index', compact(
            'tender',
            'country_states',
            'meta',
            'penyediaanIklan',
            'tenderReview',
            'tenderDokumen',
            'pegawaiPilihan'
        ));
    }

    public function simpan(Request $request, Tender $tender)
    {
        return $this->persist($request, $tender, false);
    }

    public function hantar(Request $request, Tender $tender)
    {
        return $this->persist($request, $tender, true);
    }

    protected function persist(Request $request, Tender $tender, bool $submit)
    {
        if ((int) $tender->status_process_id !== TenderProcessStatus::penyediaanIklanListStatus()) {
            return $this->respondError($request, 'Tender belum selesai pengurusan spesifikasi.', 422);
        }

        $payload = $this->buildPayload($request, $tender->id);

        try {
            $this->penyediaanIklanService->save($tender, $payload, $submit);

            if ($this->stos->isConfigured()) {
                try {
                    $response = $submit
                        ? $this->stos->submitPenyediaanIklan($tender->id, $payload)
                        : $this->stos->savePenyediaanIklan($tender->id, $payload);

                    if (! $response->successful()) {
                        Log::warning('STOS penyediaan-iklan sync failed (saved locally)', [
                            'tender_id' => $tender->id,
                            'status' => $response->status(),
                            'message' => $response->json('message'),
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('STOS penyediaan-iklan sync failed (saved locally)', [
                        'tender_id' => $tender->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $message = $submit
                ? 'Penyediaan iklan berjaya dihantar.'
                : 'Penyediaan iklan berjaya disimpan.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => $submit
                        ? route('tenders.index')
                        : route('penyediaanIklan.show', $tender->id) . '?tab=iklan',
                ]);
            }

            $redirectUrl = $submit
                ? route('tenders.index')
                : route('penyediaanIklan.show', $tender->id) . '?tab=iklan';

            /** @var \Illuminate\Http\RedirectResponse $redirect */
            $redirect = redirect()->to($redirectUrl);
            return $redirect->with('success', $message);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?: 'Data tidak sah.';

            return $this->respondError($request, $message, 422);
        } catch (\Throwable $e) {
            Log::error('Penyediaan iklan persist failed', [
                'tender_id' => $tender->id,
                'error' => $e->getMessage(),
            ]);

            return $this->respondError($request, 'Ralat sistem: ' . $e->getMessage(), 500);
        }
    }

    protected function buildPayload(Request $request, int $tenderId): array
    {
        $kelulusan = $this->parseKelulusan($request, $tenderId);

        $existing = PenyediaanIklan::query()->where('tender_id', $tenderId)->first();
        $existingIklan = is_array($existing?->meta) ? ($existing->meta['iklan'] ?? []) : [];

        $taklimat = $existingIklan['taklimat'] ?? [];
        if ($request->filled('taklimat_rows')) {
            $decoded = json_decode($request->input('taklimat_rows'), true);
            if (is_array($decoded)) {
                $taklimat = $decoded;
            }
        }

        $districtRules = [];
        $districtIds = (array) $request->input('district_id_new', []);
        $stateIds = (array) $request->input('state_id_new', []);
        foreach ($districtIds as $idx => $districtId) {
            if ($districtId === '' || $districtId === null) {
                continue;
            }
            $districtRules[] = [
                'district_id' => $districtId,
                'state_id' => $stateIds[$idx] ?? '0',
            ];
        }

        return [
            'kelulusan' => $kelulusan,
            '_sync_dokumen_meja' => $request->has('dokumen_meja'),
            'iklan' => [
                'tarikh_iklan' => $request->input('tarikh_iklan'),
                'masa_iklan' => $request->input('masa_iklan'),
                'tarikh_tutup' => $request->input('tarikh_tutup'),
                'masa_tutup' => $request->input('masa_tutup'),
                'tarikh_jual' => $request->input('tarikh_jual'),
                'tempoh_iklan' => $request->input('tempoh_iklan'),
                'tempoh_sah_laku' => $request->input('tempoh_sah_laku'),
                'sah_laku_tamat' => $request->input('sah_laku_tamat'),
                'kebenaran_khas' => $request->boolean('kebenaran_khas'),
                'syarat_tender' => $request->input('syarat_tender'),
                'taklimat' => $taklimat,
                'syarat' => [
                    'only_selangor' => $request->input('only_selangor'),
                    'only_bumiputera' => $request->boolean('only_bumiputera'),
                    'invitation' => $request->boolean('invitation'),
                    'only_advertise' => $request->boolean('only_advertise'),
                    'district_list_rule' => $districtRules,
                ],
                'dokumen_sokongan' => $request->has('dokumen_meja')
                    ? $this->parseDokumenMejaTerkawal(
                        $request,
                        $tenderId,
                        $existingIklan['dokumen_sokongan'] ?? []
                    )
                    : ($existingIklan['dokumen_sokongan'] ?? []),
            ],
            'pegawai' => $this->buildPegawaiPayload($request),
        ];
    }

    protected function buildPegawaiPayload(Request $request): array
    {
        return $this->penyediaanIklanService->normalizePegawaiMeta([
            'pegawai1' => [
                'nama' => $request->input('pegawai1_nama'),
                'emel' => $request->input('pegawai1_emel'),
                'tel' => $request->input('pegawai1_tel'),
                'jabatan' => $request->input('pegawai1_jabatan'),
            ],
            'pegawai2' => $this->resolvePegawai2FromRequest($request),
        ]);
    }

    protected function resolvePegawai2FromRequest(Request $request): array
    {
        $userId = $request->input('pegawai2_user_id') ?: $request->input('pegawai2_nama');
        $tel = trim((string) $request->input('pegawai2_tel', ''));
        $jabatan = trim((string) $request->input('pegawai2_jabatan', ''));
        $emel = trim((string) $request->input('pegawai2_emel', ''));

        if ($userId !== null && $userId !== '' && ctype_digit((string) $userId)) {
            $user = User::query()->find((int) $userId);
            if ($user) {
                return [
                    'user_id' => (int) $user->id,
                    'nama' => $user->name,
                    'emel' => $emel !== '' ? $emel : ($user->email ?? ''),
                    'tel' => $tel !== '' ? $tel : ($user->tel ?? ''),
                    'jabatan' => $jabatan !== '' ? $jabatan : ($user->department ?? ''),
                ];
            }
        }

        $nama = trim((string) $request->input('pegawai2_nama', ''));
        if ($nama === '' && $emel === '' && $tel === '' && $jabatan === '') {
            return [
                'user_id' => null,
                'nama' => '',
                'emel' => '',
                'tel' => '',
                'jabatan' => '',
            ];
        }

        return [
            'user_id' => null,
            'nama' => $nama,
            'emel' => $emel,
            'tel' => $tel,
            'jabatan' => $jabatan,
        ];
    }

    protected function pegawaiPilihanForTender(Tender $tender): array
    {
        $orgId = $tender->organization_unit_id ?: Auth::user()?->organization_unit_id;

        if (! $orgId) {
            return [];
        }

        return User::query()
            ->where('organization_unit_id', $orgId)
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'tel', 'department'])
            ->map(fn (User $user) => [
                'id' => (int) $user->id,
                'nama' => $user->name,
                'email' => $user->email ?? '',
                'tel' => $user->tel ?? '',
                'jabatan' => $user->department ?? '',
            ])
            ->values()
            ->all();
    }

    protected function parseKelulusan(Request $request, int $tenderId): array
    {
        $fixedLabels = ['Kelulusan Berbelanja', 'Kelulusan Projek ICT'];
        $statuses = (array) $request->input('kelulusan_status', []);
        $catatans = (array) $request->input('kelulusan_catatan', []);
        $jenisDynamic = (array) $request->input('kelulusan_jenis', []);
        $files = (array) $request->file('kelulusan_dokumen', []);

        $rows = [];
        $fileIndex = 0;

        foreach ($fixedLabels as $i => $label) {
            $existingPath = $request->input("kelulusan_existing_dokumen.{$i}");
            $dokumen = $this->resolveKelulusanFile($files[$fileIndex] ?? null, $tenderId, $existingPath);
            if (isset($files[$fileIndex])) {
                $fileIndex++;
            }

            $rows[] = [
                'jenis' => $label,
                'is_fixed' => true,
                'status' => $statuses[$i] ?? null,
                'catatan' => $catatans[$i] ?? null,
                'dokumen' => $dokumen,
            ];
        }

        $dynamicOffset = count($fixedLabels);
        foreach ($jenisDynamic as $j => $jenis) {
            $idx = $dynamicOffset + $j;
            $existingPath = $request->input("kelulusan_existing_dokumen.{$idx}");
            $dokumen = $this->resolveKelulusanFile($files[$fileIndex] ?? null, $tenderId, $existingPath);
            if (isset($files[$fileIndex])) {
                $fileIndex++;
            }

            $rows[] = [
                'jenis' => trim((string) $jenis),
                'is_fixed' => false,
                'status' => $statuses[$idx] ?? null,
                'catatan' => $catatans[$idx] ?? null,
                'dokumen' => $dokumen,
            ];
        }

        return $rows;
    }

    protected function resolveKelulusanFile(?UploadedFile $file, int $tenderId, ?string $existingPath): ?array
    {
        if ($file instanceof UploadedFile) {
            return $this->storeKelulusanUpload($file, $tenderId);
        }

        if ($existingPath) {
            return [
                'path' => $existingPath,
                'original_name' => basename($existingPath),
                'url' => asset($existingPath),
            ];
        }

        return null;
    }

    protected function storeKelulusanUpload(UploadedFile $file, int $tenderId): array
    {
        $dir = public_path("uploads/penyediaan-iklan/{$tenderId}/kelulusan");
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        $relative = "uploads/penyediaan-iklan/{$tenderId}/kelulusan/{$name}";

        return [
            'path' => $relative,
            'original_name' => $file->getClientOriginalName(),
            'url' => asset($relative),
        ];
    }

    protected function parseDokumenMejaTerkawal(Request $request, int $tenderId, array $existingDocs): array
    {
        $rows = (array) $request->input('dokumen_meja', []);
        $existingByPath = collect($existingDocs)->keyBy('path');
        $result = [];

        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $nama = trim((string) ($row['nama'] ?? ''));
            $existingPath = trim((string) ($row['existing_path'] ?? ''));
            $uploadId = $row['upload_id'] ?? null;
            $file = $request->file("dokumen_meja.{$index}.file");

            if ($file instanceof UploadedFile) {
                $stored = $this->storeMejaTerkawalFile($file, $tenderId);
                $stored['nama'] = $nama !== '' ? $nama : $stored['original_name'];
                if ($uploadId) {
                    $stored['upload_id'] = (int) $uploadId;
                }
                $result[] = $stored;
                continue;
            }

            if ($existingPath !== '' && $existingByPath->has($existingPath)) {
                $doc = $existingByPath->get($existingPath);
                $doc['nama'] = $nama !== '' ? $nama : ($doc['nama'] ?? $doc['original_name'] ?? '');
                if ($uploadId) {
                    $doc['upload_id'] = (int) $uploadId;
                }
                $result[] = $doc;
            }
        }

        return array_values($result);
    }

    protected function storeMejaTerkawalFile(UploadedFile $file, int $tenderId): array
    {
        $dir = public_path("uploads/penyediaan-iklan/{$tenderId}/sokongan");
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        $relative = "uploads/penyediaan-iklan/{$tenderId}/sokongan/{$name}";

        return [
            'path' => $relative,
            'original_name' => $file->getClientOriginalName(),
            'url' => asset($relative),
        ];
    }

    protected function respondError(Request $request, string $message, int $status)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return back()->withInput()->with('error', $message);
    }
}
