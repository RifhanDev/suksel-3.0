<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\SpesifikasiKerjaFile;
use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\StosStoredFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SpesifikasiTenderKerjaController extends Controller
{
    use UpdatesTenderProcessAfterChecklistSubmit;
    /**
     * Display the Penyediaan Spesifikasi Tender page with existing data.
     */
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $apiUrl   = $this->url('spesifikasi-kerja/' . $tenderUuid);
        $response = $this->api()->get($apiUrl);

        $checklistData = null;

        if ($response->successful()) {
            $checklistData = $response->json('data');
        } else {
            Log::warning('SpesifikasiTenderKerjaController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'api_url'     => $apiUrl,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        return view(
            'newModule.jawatankuasaSpesifikasi.form_penyediaan_spesifikasi_tender',
            compact('tender', 'checklistData')
        );
    }

    /**
     * Save (draft) the specification items — proxied to backend API.
     */
    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('spesifikasi-kerja/' . $tenderUuid),
            $request->except('_token')
        );

        return response()->json($response->json(), $response->status());
    }

    /**
     * Submit the specification — proxied to backend API.
     */
    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('spesifikasi-kerja/' . $tenderUuid . '/submit'),
            $request->except('_token')
        );

        if ($response->successful()) {
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
        }

        return response()->json($response->json(), $response->status());
    }

    /**
     * Upload a per-item supporting document — proxied to backend API.
     */
    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()
            ->attach(
                'file',
                $request->file('file')->get(),
                $request->file('file')->getClientOriginalName()
            )
            ->post(
                $this->url('spesifikasi-kerja/' . $tenderUuid . '/files'),
                $request->except(['_token', 'file'])
            );

        return response()->json($response->json(), $response->status());
    }

    /**
     * Open an uploaded specification file through this app.
     * The API returns a storage URL on 127.0.0.1, which the browser cannot open.
     */
    public function downloadFile(string $tenderUuid, string $fileUuid)
    {
        $this->ensureAccess();

        $tender = Tender::query()->where('uuid', $tenderUuid)->firstOrFail();

        $file = SpesifikasiKerjaFile::query()
            ->where('uuid', $fileUuid)
            ->whereHas('header', fn ($query) => $query->where('tender_id', $tender->id))
            ->first();

        if (! $file) {
            $file = $this->findRemoteSpecificationFile($tenderUuid, $fileUuid);
        }

        if (! $file) {
            abort(404, 'Fail tidak dijumpai.');
        }

        $name = is_array($file) ? ($file['original_name'] ?? 'Dokumen') : $file->original_name;
        $path = is_array($file) ? ($file['path'] ?? null) : $file->path;
        $mime = is_array($file) ? ($file['mime_type'] ?? null) : $file->mime_type;

        return StosStoredFile::response([
            'original_name' => $name ?: 'Dokumen',
            'path' => $path,
            'mime_type' => $mime,
            'download_api' => 'spesifikasi-kerja-files/' . $fileUuid . '/download',
        ]);
    }

    /**
     * Delete an uploaded file — proxied to backend API.
     */
    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->delete(
            $this->url('spesifikasi-kerja-files/' . $fileUuid)
        );

        return response()->json($response->json(), $response->status());
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function api()
    {
        return Http::withoutVerifying()->timeout(30)->withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ]);
    }

    private function url(string $path): string
    {
        return config('services.stos_backend.url') . '/api/' . $path;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findRemoteSpecificationFile(string $tenderUuid, string $fileUuid): ?array
    {
        $response = $this->api()->get($this->url('spesifikasi-kerja/' . $tenderUuid));
        if (! $response->successful()) {
            return null;
        }

        $data = $response->json('data') ?? [];
        $files = $data['files'] ?? [];

        foreach ($data['items'] ?? [] as $item) {
            if (! is_array($item)) {
                continue;
            }

            foreach ($item['files'] ?? [] as $file) {
                $files[] = $file;
            }

            foreach ($item['specs'] ?? [] as $spec) {
                if (! is_array($spec)) {
                    continue;
                }

                foreach ($spec['files'] ?? [] as $file) {
                    $files[] = $file;
                }
            }
        }

        foreach ($files as $file) {
            if (is_array($file) && ($file['uuid'] ?? '') === $fileUuid) {
                return $file;
            }
        }

        return null;
    }
}
