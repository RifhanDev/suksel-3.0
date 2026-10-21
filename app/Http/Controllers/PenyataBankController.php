<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\PenyataBank;
use App\Models\PenyataBankFile;
use App\Models\Tender;
use App\Services\PenyataBankPersistenceService;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $payload['vendor_id'] = $this->vendorId();
        }

        $response = $this->api()->post($this->url('penyata-banks/' . $tenderUuid), $payload);

        if ($this->isVendorFormMode()) {
            $this->persistVendorFormPayload($tender, 'penyata_bank', $payload);
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
            $payload['vendor_id'] = $this->vendorId();
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

    protected function pruneOrphanedVendorFiles(Tender $tender, int $vendorId, array $payload): void
    {
        if ($vendorId <= 0) {
            return;
        }

        $activeUuids = [];
        $activeNames = [];
        foreach ($payload['accounts'] ?? [] as $acc) {
            if (! is_array($acc)) {
                continue;
            }
            foreach ($acc['files'] ?? [] as $f) {
                if (is_array($f)) {
                    if (! empty($f['uuid'])) {
                        $activeUuids[] = (string) $f['uuid'];
                    }
                    $name = $f['original_name'] ?? $f['name'] ?? null;
                    if ($name) {
                        $activeNames[] = (string) $name;
                    }
                }
            }
        }

        if (empty($activeUuids) && empty($activeNames)) {
            return;
        }

        $orphans = PenyataBankFile::query()
            ->whereHas('penyataBank', function ($q) use ($tender) {
                $q->where('tender_id', $tender->id);
            })
            ->where('vendor_id', $vendorId)
            ->whereNotIn('uuid', $activeUuids)
            ->when(! empty($activeNames), function ($q) use ($activeNames) {
                $q->whereNotIn('original_name', $activeNames);
            })
            ->where('created_at', '<', now()->subMinutes(15))
            ->get();

        foreach ($orphans as $orphan) {
            if ($orphan->path && Storage::disk('public')->exists($orphan->path)) {
                Storage::disk('public')->delete($orphan->path);
            }
            $orphan->delete();
        }
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'],
        ]);

        $vendorId = $this->isVendorFormMode() ? $this->vendorId() : ($request->input('vendor_id') ?: null);

        $penyata = PenyataBank::firstOrCreate(
            ['tender_id' => $tender->id],
            ['uuid' => (string) Str::uuid(), 'status' => 'draft']
        );

        $file = $request->file('file');
        $storedName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $directory = 'uploads/penyata-bank/' . $tender->id . ($vendorId ? '/' . $vendorId : '');
        $path = $file->storeAs($directory, $storedName, 'public');

        $fileRecord = PenyataBankFile::create([
            'uuid'            => (string) Str::uuid(),
            'penyata_bank_id' => $penyata->id,
            'vendor_id'       => $vendorId,
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
            if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management')) {
                $vendorId = (int) ($user->vendor_id ?? 0);
                if ($vendorId <= 0 || ($fileRecord->vendor_id && (int) $fileRecord->vendor_id !== $vendorId)) {
                    abort(403, 'Akses tidak dibenarkan.');
                }
            }

            if ($fileRecord->path && Storage::disk('public')->exists($fileRecord->path)) {
                Storage::disk('public')->delete($fileRecord->path);
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
        if (! $penyataData || empty($penyataData['accounts']) || ! is_array($penyataData['accounts'])) {
            return $penyataData;
        }

        $penyata = PenyataBank::firstOrCreate(
            ['tender_id' => $tender->id],
            ['uuid' => (string) Str::uuid(), 'status' => 'draft']
        );

        $existingFiles = PenyataBankFile::where('penyata_bank_id', $penyata->id)
            ->when($vendorId, fn ($q) => $q->where('vendor_id', $vendorId))
            ->get();

        $vendorPbFiles = $vendorId
            ? PenyataBankFile::where('vendor_id', $vendorId)->get()
            : collect();

        $vendorDokumenFiles = $vendorId
            ? \App\Models\TenderVendorDokumenFile::where('tender_id', $tender->id)->where('vendor_id', $vendorId)->get()
            : collect();

        $payloadNeedsUpdate = false;

        foreach ($penyataData['accounts'] as $accIdx => &$account) {
            if (! isset($account['files']) || ! is_array($account['files'])) {
                $account['files'] = [];
            }

            foreach ($account['files'] as &$file) {
                if (! is_array($file)) {
                    continue;
                }

                $fileName = (string) ($file['original_name'] ?? $file['name'] ?? '');
                $fileUuid = (string) ($file['uuid'] ?? '');
                $fileUrl  = (string) ($file['url'] ?? '');

                // 1. If uuid is present, ensure valid root-relative download URL
                if ($fileUuid !== '') {
                    $normalizedUrl = '/tenders/dokumen-files/' . $fileUuid . '/download';
                    if ($fileUrl !== $normalizedUrl) {
                        $file['url'] = $normalizedUrl;
                        $payloadNeedsUpdate = true;
                    }
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

                    if ($matchedCandidate && $matchedCandidate->path && Storage::disk('public')->exists($matchedCandidate->path)) {
                        $targetDir = 'uploads/penyata-bank/' . $tender->id . ($vendorId ? '/' . $vendorId : '');
                        $targetPath = $targetDir . '/' . basename($matchedCandidate->path);

                        if (! Storage::disk('public')->exists($targetPath)) {
                            Storage::disk('public')->copy($matchedCandidate->path, $targetPath);
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

                // 5. If match found, assign UUID, URL, size and names
                if ($matched) {
                    $file['uuid'] = $matched->uuid;
                    $file['url'] = '/tenders/dokumen-files/' . $matched->uuid . '/download';
                    if (empty($file['size']) && ! empty($matched->size)) {
                        $file['size'] = (int) $matched->size;
                    }
                    if (empty($file['name'])) {
                        $file['name'] = $matched->original_name;
                    }
                    if (empty($file['original_name'])) {
                        $file['original_name'] = $matched->original_name;
                    }
                    $payloadNeedsUpdate = true;
                }
            }
            unset($file);

            // Include any uploaded files from existingFiles missing in the accounts files list
            if ($accIdx === 0 && $existingFiles->isNotEmpty()) {
                $existingAccountUuids = array_filter(array_column($account['files'], 'uuid'));
                $existingAccountNames = array_filter(array_map('strtolower', array_column($account['files'], 'name')));

                foreach ($existingFiles as $ef) {
                    if (! in_array($ef->uuid, $existingAccountUuids, true) && ! in_array(strtolower($ef->original_name), $existingAccountNames, true)) {
                        $account['files'][] = [
                            'uuid'          => $ef->uuid,
                            'name'          => $ef->original_name,
                            'original_name' => $ef->original_name,
                            'size'          => (int) $ef->size,
                            'url'           => '/tenders/dokumen-files/' . $ef->uuid . '/download',
                        ];
                        $payloadNeedsUpdate = true;
                    }
                }
            }
        }
        unset($account);

        // Update database payload if anything was enriched
        if ($payloadNeedsUpdate && $vendorId) {
            $legacyTender = \App\Tender::find($tender->id);
            if ($legacyTender) {
                $payloadService = app(\App\Services\VendorFormPayloadService::class);
                $storedPayload = $payloadService->get($legacyTender, $vendorId, 'penyata_bank');
                if (is_array($storedPayload)) {
                    $storedPayload['accounts'] = $penyataData['accounts'];
                    $payloadService->save(
                        $legacyTender,
                        $vendorId,
                        'penyata_bank',
                        $storedPayload,
                        $storedPayload['status'] ?? 'submitted'
                    );
                }
            }
        }

        return $penyataData;
    }
}
