<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\PenyataBank;
use App\Models\PenyataBankFile;
use App\Models\Tender;
use App\Services\PenyataBankPersistenceService;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PenyataBankController extends Controller
{
    use HandlesTenderFormAccess;

    public function __construct(protected PenyataBankPersistenceService $penyataBankPersistence) {}

    public function index(string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);

        $vendorId = $this->vendorId();
        $apiPath  = 'penyata-banks/' . $tenderUuid;
        $response = $vendorId
            ? $this->api()->get($this->stosUrlWithVendor($apiPath, $vendorId))
            : $this->api()->get($this->url($apiPath));

        $penyataData = null;

        if ($response->successful()) {
            $penyataData = $response->json('data');
        } else {
            Log::warning('PenyataBankController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        $localData = $this->penyataBankPersistence->loadForTender($tender);

        if ($vendorId) {
            $vendorPayload = $this->loadVendorFormPayload($tender, 'penyata_bank');
            $penyataData = $this->penyataBankPersistence->mergeVendorPayload(
                $localData ?? $penyataData,
                $vendorPayload,
                true
            ) ?? $vendorPayload;
        } elseif (empty($penyataData)) {
            $penyataData = $localData;
        }

        $penyataData = $this->resolveSupportingDocuments($tender, $vendorId, $penyataData);

        $isAdminForm = ! $this->isVendorFormMode() && ! $this->isFormViewOnly();

        $aj = (float) ($tender->anggaran_jabatan ?? 0);
        $automatikRowsPurata = $this->calcAutomatikRowsPurata($aj);

        return view('jawatankuasaSpesifikasi.penyata_bank', array_merge([
            'tender' => $tender,
            'penyataData' => $penyataData,
            'automatikRowsPurata' => $automatikRowsPurata,
            'showVendorForm' => $this->isVendorFormMode() || $this->isFormViewOnly(),
            'showPeriodConfig' => $isAdminForm,
            'showScoringConfig' => $isAdminForm,
        ], $this->formViewVars($tender)));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $payload = $request->json()->all() ?: $request->except('_token');
        if ($this->isVendorFormMode()) {
            $vendorId = (int) ($this->vendorId() ?? 0);
            if ($vendorId <= 0) {
                abort(403, 'Akses petender tidak dapat dikenal pasti.');
            }

            $this->validateVendorAccountPayload($payload);

            $payload['vendor_id'] = $vendorId;
            $this->validateVendorAccountFiles($tender, $vendorId, $payload);
        }

        $response = $this->api()->post($this->url('penyata-banks/' . $tenderUuid), $payload);

        if ($this->isVendorFormMode()) {
            DB::transaction(function () use ($tender, $payload) {
                $this->syncVendorAccountFileAssociations($tender, (int) $this->vendorId(), $payload);
                $this->persistVendorFormPayload($tender, 'penyata_bank', $payload);
            });
            $this->trackVendorFormSubmitted($tender, 'penyata_bank', [
                'text' => 'Penyata bank disimpan',
            ]);
            $this->pruneOrphanedVendorFiles($tender, (int) $this->vendorId(), $payload);

            if (! $response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Disimpan secara tempatan.',
                    'warning' => 'Penyegerakan STOS gagal.',
                ]);
            }

            return response()->json($response->json(), $response->status());
        }

        $record = $this->penyataBankPersistence->saveForTender($tender, $payload);

        if ($response->successful()) {
            return response()->json($response->json(), $response->status());
        }

        return response()->json([
            'success' => true,
            'message' => 'Berjaya disimpan.',
            'data' => $this->penyataBankPersistence->loadForTender($tender),
            'warning' => 'Penyegerakan STOS gagal.',
        ]);
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $payload = $request->except('_token');
        if ($this->isVendorFormMode()) {
            $vendorId = (int) ($this->vendorId() ?? 0);
            if ($vendorId <= 0) {
                abort(403, 'Akses petender tidak dapat dikenal pasti.');
            }

            // The completion endpoint can be called without resending the form
            // body. Validate the saved vendor payload in that case so the final
            // submission cannot bypass the required per-account bank statement.
            if (! isset($payload['accounts'])) {
                $savedPayload = $this->loadVendorFormPayload($tender, 'penyata_bank');
                $payload['accounts'] = $savedPayload['accounts'] ?? [];
            }

            $this->validateVendorAccountPayload($payload);
            $payload['vendor_id'] = $vendorId;
            $this->validateVendorAccountFiles($tender, $vendorId, $payload);
        }

        $response = $this->api()->post($this->url('penyata-banks/' . $tenderUuid . '/submit'), $payload);

        if ($this->isVendorFormMode()) {
            $this->pruneOrphanedVendorFiles($tender, (int) $this->vendorId(), $payload);
            if ($response->successful()) {
                $this->trackVendorFormSubmitted($tender, 'penyata_bank', [
                    'text' => 'Penyata bank dihantar',
                ]);
            }
        }

        return response()->json($response->json(), $response->status());
    }

    protected function validateVendorAccountPayload(array $payload): void
    {
        Validator::make($payload, [
            'accounts' => ['required', 'array', 'min:1', 'max:1'],
            'accounts.*' => ['required', 'array'],
            'accounts.*.account_uuid' => ['required', 'uuid', 'distinct'],
            'accounts.*.bank_name' => ['required', 'string', 'max:255'],
            'accounts.*.files' => ['required', 'array', 'min:1'],
            'accounts.*.files.*' => ['required', 'array'],
            'accounts.*.files.*.uuid' => ['required', 'uuid', 'distinct'],
            'accounts.*.files.*.account_uuid' => ['sometimes', 'uuid'],
            'accounts.*.files.*.account_index' => ['sometimes', 'integer', 'min:0'],
        ], [
            'accounts.required' => 'Sila lengkapkan sekurang-kurangnya satu akaun penyata bank.',
            'accounts.max' => 'Borang ini hanya membenarkan satu akaun penyata bank buat masa ini.',
            'accounts.*.bank_name.required' => 'Sila masukkan nama bank bagi setiap akaun.',
            'accounts.*.files.required' => 'Sila muat naik sekurang-kurangnya satu penyata bank bagi setiap akaun.',
            'accounts.*.files.min' => 'Sila muat naik sekurang-kurangnya satu penyata bank bagi setiap akaun.',
            'accounts.*.files.*.uuid.required' => 'Sila tunggu sehingga semua fail penyata bank selesai dimuat naik.',
        ])->validate();
    }

    protected function pruneOrphanedVendorFiles(Tender $tender, int $vendorId, array $payload): void
    {
        if ($vendorId <= 0) {
            return;
        }

        $activeUuids = [];
        foreach ($payload['accounts'] ?? [] as $acc) {
            if (! is_array($acc)) {
                continue;
            }
            foreach ($acc['files'] ?? [] as $f) {
                if (is_array($f)) {
                    if (! empty($f['uuid'])) {
                        $activeUuids[] = (string) $f['uuid'];
                    }
                }
            }
        }

        if (empty($activeUuids)) {
            return;
        }

        $orphans = PenyataBankFile::query()
            ->whereHas('penyataBank', function ($q) use ($tender) {
                $q->where('tender_id', $tender->id);
            })
            ->where('vendor_id', $vendorId)
            ->whereNotIn('uuid', array_values(array_unique($activeUuids)))
            ->get();

        foreach ($orphans as $orphan) {
            if ($orphan->path && \App\Support\AttachmentStorage::exists($orphan->path)) {
                \App\Support\AttachmentStorage::delete($orphan->path);
            }
            $orphan->delete();
        }
    }

    /**
     * Ensure every submitted statement belongs to this tender, vendor, and account.
     */
    protected function validateVendorAccountFiles(Tender $tender, int $vendorId, array $payload): void
    {
        $references = [];
        foreach (array_values($payload['accounts'] ?? []) as $accountIndex => $account) {
            $accountUuid = (string) ($account['account_uuid'] ?? '');
            foreach ($account['files'] ?? [] as $file) {
                $fileUuid = (string) ($file['uuid'] ?? '');
                if ($fileUuid === '' || isset($references[$fileUuid])) {
                    throw ValidationException::withMessages([
                        "accounts.$accountIndex.files" => 'Fail penyata bank tidak sah atau digunakan oleh lebih daripada satu akaun.',
                    ]);
                }

                $fileAccountUuid = (string) ($file['account_uuid'] ?? '');
                if ($fileAccountUuid !== '' && $fileAccountUuid !== $accountUuid) {
                    throw ValidationException::withMessages([
                        "accounts.$accountIndex.files" => 'Fail penyata bank tidak sepadan dengan akaun ini.',
                    ]);
                }

                if (array_key_exists('account_index', $file)
                    && (int) $file['account_index'] !== (int) $accountIndex) {
                    throw ValidationException::withMessages([
                        "accounts.$accountIndex.files" => 'Kedudukan dokumen penyata bank tidak sepadan dengan akaun ini.',
                    ]);
                }

                $references[$fileUuid] = [
                    'account_uuid' => $accountUuid,
                    'account_index' => $accountIndex,
                ];
            }
        }

        $records = PenyataBankFile::query()
            ->whereHas('penyataBank', fn ($query) => $query->where('tender_id', $tender->id))
            ->where('vendor_id', $vendorId)
            ->whereIn('uuid', array_keys($references))
            ->get()
            ->keyBy('uuid');

        foreach ($references as $fileUuid => $association) {
            $record = $records->get($fileUuid);
            if (! $record) {
                throw ValidationException::withMessages([
                    'accounts' => 'Salah satu dokumen penyata bank tidak ditemui untuk petender ini. Sila muat naik semula dokumen tersebut.',
                ]);
            }

            if ($record->account_uuid && $record->account_uuid !== $association['account_uuid']) {
                throw ValidationException::withMessages([
                    'accounts' => 'Salah satu dokumen penyata bank telah dikaitkan dengan akaun lain. Sila muat naik semula dokumen tersebut.',
                ]);
            }

            if (! $record->account_uuid && $record->account_index !== null
                && (int) $record->account_index !== (int) $association['account_index']) {
                throw ValidationException::withMessages([
                    'accounts' => 'Salah satu dokumen penyata bank tidak sepadan dengan kedudukan akaun. Sila muat naik semula dokumen tersebut.',
                ]);
            }
        }
    }

    /**
     * Persist the account association and refresh the ordering after account removal/reordering.
     */
    protected function syncVendorAccountFileAssociations(Tender $tender, int $vendorId, array $payload): void
    {
        foreach (array_values($payload['accounts'] ?? []) as $accountIndex => $account) {
            foreach ($account['files'] ?? [] as $file) {
                PenyataBankFile::query()
                    ->where('uuid', (string) ($file['uuid'] ?? ''))
                    ->where('vendor_id', $vendorId)
                    ->whereHas('penyataBank', fn ($query) => $query->where('tender_id', $tender->id))
                    ->update([
                        'account_uuid' => (string) $account['account_uuid'],
                        'account_index' => $accountIndex,
                    ]);
            }
        }
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'],
            'account_uuid' => ['required', 'uuid'],
            'account_index' => ['required', 'integer', 'min:0'],
        ]);

        $vendorId = $this->vendorId();
        if (! $vendorId) {
            abort(403, 'Akses petender tidak dapat dikenal pasti.');
        }

        $penyata = PenyataBank::firstOrCreate(
            ['tender_id' => $tender->id],
            ['uuid' => (string) Str::uuid(), 'status' => 'draft']
        );

        $file = $request->file('file');
        $storedName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $directory = 'uploads/penyata-bank/' . $tender->id . ($vendorId ? '/' . $vendorId : '');
        $path = \App\Support\AttachmentStorage::storeAs($file, $directory, $storedName);

        $fileRecord = PenyataBankFile::create([
            'uuid'            => (string) Str::uuid(),
            'penyata_bank_id' => $penyata->id,
            'vendor_id'       => $vendorId,
            'account_uuid'    => $request->input('account_uuid'),
            'account_index'   => (int) $request->input('account_index'),
            'file_type'       => 'support',
            'original_name'   => $file->getClientOriginalName(),
            'stored_name'     => $storedName,
            'path'            => $path,
            'mime_type'       => $file->getMimeType(),
            'size'            => $file->getSize(),
            'uploaded_by'     => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dimuat naik ke storan SUKSel 3.0.',
            'file'    => [
                'uuid'          => $fileRecord->uuid,
                'original_name' => $fileRecord->original_name,
                'size'          => $fileRecord->size,
                'url'           => '/tenders/dokumen-files/' . $fileRecord->uuid . '/download',
                'account_uuid'  => $fileRecord->account_uuid,
                'account_index' => $fileRecord->account_index,
                'penyata_bank_file' => true,
            ],
        ]);
    }

    public function deleteFile(string $fileUuid)
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }
        if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management') && ! $user->vendor_id) {
            abort(403);
        }
        $this->ensureFormEditable();

        $fileRecord = PenyataBankFile::where('uuid', $fileUuid)->first();
        if ($fileRecord) {
            $fileRecord->loadMissing('penyataBank');
            if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management')) {
                $vendorId = (int) ($user->vendor_id ?? 0);
                if ($vendorId <= 0 || (int) $fileRecord->vendor_id !== $vendorId) {
                    abort(403, 'Akses tidak dibenarkan.');
                }

                $tender = Tender::findOrFail($fileRecord->penyataBank?->tender_id);
                $this->ensureTenderFormAccess($tender);
            }

            if ($fileRecord->vendor_id && $fileRecord->penyataBank?->tender_id) {
                $this->removePenyataFileFromVendorPayload(
                    (int) $fileRecord->penyataBank->tender_id,
                    (int) $fileRecord->vendor_id,
                    $fileUuid
                );
            }

            if ($fileRecord->path && \App\Support\AttachmentStorage::exists($fileRecord->path)) {
                \App\Support\AttachmentStorage::delete($fileRecord->path);
            }
            $fileRecord->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fail berjaya dipadam dari storan SUKSel 3.0.',
            ]);
        }

        // Backward compatibility fallback for historical files stored on STOS API
        try {
            $response = $this->api()->delete($this->url('penyata-bank-files/' . $fileUuid));

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => true, 'message' => 'Fail dipadam.']);
        }
    }

    private function findTender(string $tenderUuid): Tender
    {
        return Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where(function ($q) use ($tenderUuid) {
                $q->where('tenders.uuid', $tenderUuid);

                // Hanya jatuh balik kepada id apabila nilainya benar-benar
                // angka. tenders.id ialah integer, jadi MySQL memaksa string
                // UUID kepada nombor sebelum membandingkan — UUID seperti
                // '26f653c1-...' menjadi 26 dan memadankan tender id 26 yang
                // tiada kaitan, memuatkan tender yang salah sepenuhnya.
                if (ctype_digit((string) $tenderUuid)) {
                    $q->orWhere('tenders.id', (int) $tenderUuid);
                }
            })
            ->firstOrFail();
    }

    private function api()
    {
        return StosBackendClient::http();
    }

    private function calcAutomatikRowsPurata(float $aj): array
    {
        if ($aj > 50 && $aj <= 500000) {
            $hingga1 = round((0.015 * $aj) - 0.01, 2);
        } elseif ($aj > 500000) {
            $hingga1 = round((0.030 * $aj) - 0.01, 2);
        } else {
            $hingga1 = 0.00;
        }

        $dari2 = round($hingga1 + 0.01, 2);

        return [
            ['dari' => 0.00,   'hingga' => $hingga1, 'skema' => '0'],
            ['dari' => $dari2, 'hingga' => null,      'skema' => '10'],
        ];
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }

    protected function resolveSupportingDocuments(Tender $tender, ?int $vendorId, ?array $penyataData): ?array
    {
        if (! $vendorId || ! $penyataData) {
            return $penyataData;
        }

        $legacyTender = \App\Tender::query()->find($tender->id);
        $payloadService = app(\App\Services\VendorFormPayloadService::class);
        $storedPayload = $legacyTender
            ? $payloadService->get($legacyTender, $vendorId, 'penyata_bank')
            : null;

        $existingFiles = PenyataBankFile::query()
            ->whereHas('penyataBank', fn ($query) => $query->where('tender_id', $tender->id))
            ->where('vendor_id', $vendorId)
            ->get();
        $accounts = is_array($penyataData['accounts'] ?? null) ? array_values($penyataData['accounts']) : [];
        if ($existingFiles->isEmpty() && $accounts === []) {
            return $penyataData;
        }

        $maxUploadedAccountIndex = $existingFiles
            ->pluck('account_index')
            ->filter(fn ($index) => $index !== null)
            ->max();
        while ($maxUploadedAccountIndex !== null && count($accounts) <= (int) $maxUploadedAccountIndex) {
            $accounts[] = [
                'dari_bulan' => $penyataData['dari_bulan'] ?? null,
                'dari_tahun' => $penyataData['dari_tahun'] ?? null,
                'hingga_bulan' => $penyataData['hingga_bulan'] ?? null,
                'hingga_tahun' => $penyataData['hingga_tahun'] ?? null,
                'bank_name' => '',
                'bulans' => [],
                'files' => [],
            ];
        }

        $candidateUuids = collect($accounts)
            ->flatMap(fn ($account) => is_array($account) ? ($account['files'] ?? []) : [])
            ->filter(fn ($file) => is_array($file) && ! empty($file['uuid']))
            ->pluck('uuid')
            ->unique()
            ->values();
        $allReferencedRows = $candidateUuids->isEmpty()
            ? collect()
            : PenyataBankFile::query()->whereIn('uuid', $candidateUuids)->get()->keyBy('uuid');
        $filesByUuid = $existingFiles->keyBy('uuid');
        $payloadNeedsUpdate = false;

        foreach ($accounts as $accountIndex => &$account) {
            if (! is_array($account)) {
                $account = [];
                $payloadNeedsUpdate = true;
            }

            $accountUuid = (string) ($account['account_uuid'] ?? '');
            if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $accountUuid)) {
                $uploadedAccount = $existingFiles->first(fn ($file) =>
                    ! empty($file->account_uuid)
                    && (int) ($file->account_index ?? 0) === (int) $accountIndex
                );
                $accountUuid = $uploadedAccount?->account_uuid ?: (string) Str::uuid();
                $account['account_uuid'] = $accountUuid;
                $payloadNeedsUpdate = true;
            }

            $accountFiles = is_array($account['files'] ?? null) ? $account['files'] : [];
            $resolvedFiles = [];
            $resolvedUuids = [];

            foreach ($accountFiles as $file) {
                if (! is_array($file)) {
                    continue;
                }

                $fileUuid = (string) ($file['uuid'] ?? '');
                $fileName = (string) ($file['original_name'] ?? $file['name'] ?? '');
                $record = $fileUuid !== '' ? $filesByUuid->get($fileUuid) : null;

                if ($fileUuid !== '' && $allReferencedRows->has($fileUuid) && ! $record) {
                    // A local upload from another vendor or tender must never show here.
                    $payloadNeedsUpdate = true;
                    continue;
                }

                // 2. UUID missing: search in PenyataBankFile for this tender & vendor
                $matched = $existingFiles->first(function ($ef) use ($fileName) {
                    return strcasecmp($ef->original_name, $fileName) === 0 || strcasecmp($ef->stored_name, $fileName) === 0;
                });

                // 3. If not in this tender, search other PenyataBankFile records for this vendor
                if (! $matched && $vendorPbFiles->isNotEmpty()) {
                    $matchedCandidate = $vendorPbFiles->first(function ($vf) use ($fileName) {
                        return strcasecmp($vf->original_name, $fileName) === 0 || strcasecmp($vf->stored_name, $fileName) === 0;
                    });

                    if ($matchedCandidate && $matchedCandidate->path && \App\Support\AttachmentStorage::exists($matchedCandidate->path)) {
                        $targetDir = 'uploads/penyata-bank/' . $tender->id . ($vendorId ? '/' . $vendorId : '');
                        $targetPath = $targetDir . '/' . basename($matchedCandidate->path);

                        if (! \App\Support\AttachmentStorage::exists($targetPath)) {
                            \App\Support\AttachmentStorage::copy($matchedCandidate->path, $targetPath);
                        }

                        $matched = PenyataBankFile::firstOrCreate([
                            'penyata_bank_id' => $penyata->id,
                            'vendor_id'       => $vendorId,
                            'original_name'   => $fileName ?: $matchedCandidate->original_name,
                        ], [
                            'uuid'            => (string) Str::uuid(),
                            'file_type'       => 'support',
                            'stored_name'     => basename($targetPath),
                            'path'            => $targetPath,
                            'mime_type'       => $matchedCandidate->mime_type ?: 'application/pdf',
                            'size'            => $file['size'] ?? $matchedCandidate->size ?? 0,
                            'uploaded_by'     => $matchedCandidate->uploaded_by,
                        ]);

                        $existingFiles->push($matched);
                    } elseif ($matchedCandidate) {
                        $matched = $matchedCandidate;
                    }
                }

                // 4. Check TenderVendorDokumenFile
                if (! $matched && $vendorDokumenFiles->isNotEmpty()) {
                    $matched = $vendorDokumenFiles->first(function ($df) use ($fileName) {
                        return strcasecmp($df->original_name, $fileName) === 0;
                    });
                }

                if ($record) {
                    if ($record->account_uuid && $record->account_uuid !== $accountUuid) {
                        $payloadNeedsUpdate = true;
                        continue;
                    }
                    if (! $record->account_uuid && $record->account_index !== null
                        && (int) $record->account_index !== (int) $accountIndex) {
                        $payloadNeedsUpdate = true;
                        continue;
                    }

                    if ($record->account_uuid !== $accountUuid || $record->account_index === null
                        || (int) $record->account_index !== (int) $accountIndex) {
                        $record->forceFill([
                            'account_uuid' => $accountUuid,
                            'account_index' => $accountIndex,
                        ])->save();
                    }
                    $file = array_merge($file, [
                        'uuid' => $record->uuid,
                        'account_uuid' => $accountUuid,
                        'account_index' => $accountIndex,
                        'penyata_bank_file' => true,
                        'name' => $file['name'] ?? $record->original_name,
                        'original_name' => $file['original_name'] ?? $record->original_name,
                        'size' => (int) ($file['size'] ?? $record->size),
                        'url' => '/tenders/dokumen-files/' . $record->uuid . '/download',
                    ]);
                    $filesByUuid->put($record->uuid, $record);
                } else {
                    // Preserve legacy external metadata under its existing account only.
                    $file['account_uuid'] = $accountUuid;
                    $file['account_index'] = $accountIndex;
                }

                if (! empty($file['uuid'])) {
                    $resolvedUuid = (string) $file['uuid'];
                    if (in_array($resolvedUuid, $resolvedUuids, true)) {
                        $payloadNeedsUpdate = true;
                        continue;
                    }
                    $resolvedUuids[] = $resolvedUuid;
                }
                $resolvedFiles[] = $file;
            }

            // Recover uploads that completed before the browser saved the form.
            foreach ($existingFiles as $record) {
                $belongsToAccount = $record->account_uuid
                    ? $record->account_uuid === $accountUuid
                    : (int) ($record->account_index ?? 0) === (int) $accountIndex;
                if (! $belongsToAccount || in_array($record->uuid, $resolvedUuids, true)) {
                    continue;
                }

                if ($record->account_uuid !== $accountUuid || $record->account_index === null
                    || (int) $record->account_index !== (int) $accountIndex) {
                    $record->forceFill([
                        'account_uuid' => $accountUuid,
                        'account_index' => $accountIndex,
                    ])->save();
                }
                $resolvedFiles[] = [
                    'uuid' => $record->uuid,
                    'account_uuid' => $accountUuid,
                    'account_index' => $accountIndex,
                    'penyata_bank_file' => true,
                    'name' => $record->original_name,
                    'original_name' => $record->original_name,
                    'size' => (int) $record->size,
                    'url' => '/tenders/dokumen-files/' . $record->uuid . '/download',
                ];
                $resolvedUuids[] = $record->uuid;
                $payloadNeedsUpdate = true;
            }

            if (($account['account_uuid'] ?? null) !== $accountUuid || ($account['files'] ?? []) !== $resolvedFiles) {
                $payloadNeedsUpdate = true;
            }
            $account['account_uuid'] = $accountUuid;
            $account['files'] = $resolvedFiles;
        }
        unset($account);

        $penyataData['accounts'] = $accounts;
        if ($payloadNeedsUpdate && $legacyTender && is_array($storedPayload)) {
            $storedPayload['accounts'] = $accounts;
            $payloadService->save(
                $legacyTender,
                $vendorId,
                'penyata_bank',
                $storedPayload,
                $storedPayload['status'] ?? 'submitted'
            );
        }

        return $penyataData;
    }

    protected function removePenyataFileFromVendorPayload(int $tenderId, int $vendorId, string $fileUuid): void
    {
        $legacyTender = \App\Tender::query()->find($tenderId);
        if (! $legacyTender) {
            return;
        }

        $payloadService = app(\App\Services\VendorFormPayloadService::class);
        $payload = $payloadService->get($legacyTender, $vendorId, 'penyata_bank');
        if (! is_array($payload)) {
            return;
        }

        $accounts = is_array($payload['accounts'] ?? null) ? $payload['accounts'] : [];
        foreach ($accounts as &$account) {
            if (! is_array($account) || ! is_array($account['files'] ?? null)) {
                continue;
            }
            $account['files'] = array_values(array_filter(
                $account['files'],
                fn ($file) => ! is_array($file) || (string) ($file['uuid'] ?? '') !== $fileUuid
            ));
        }
        unset($account);
        $payload['accounts'] = $accounts;

        foreach (['files', 'dokumen', 'dokumen_sokongan', 'lampiran'] as $fileKey) {
            if (! is_array($payload[$fileKey] ?? null)) {
                continue;
            }
            $payload[$fileKey] = array_values(array_filter(
                $payload[$fileKey],
                fn ($file) => ! is_array($file) || (string) ($file['uuid'] ?? '') !== $fileUuid
            ));
        }

        $payloadService->save(
            $legacyTender,
            $vendorId,
            'penyata_bank',
            $payload,
            $payload['status'] ?? 'submitted'
        );
    }
}
