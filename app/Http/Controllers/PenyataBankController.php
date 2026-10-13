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

        if ($response->successful() && $this->isVendorFormMode()) {
            $this->trackVendorFormSubmitted($tender, 'penyata_bank', [
                'text' => 'Penyata bank dihantar',
            ]);
        }

        return response()->json($response->json(), $response->status());
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
                'url'           => url('/tenders/dokumen-files/' . $fileRecord->uuid . '/download'),
            ],
        ]);
    }

    public function deleteFile(string $fileUuid)
    {
        $user = auth()->user();
        if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management') && ! $user->vendor_id) {
            abort(403);
        }
        $this->ensureFormEditable();

        $fileRecord = PenyataBankFile::where('uuid', $fileUuid)->first();
        if ($fileRecord) {
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
}
