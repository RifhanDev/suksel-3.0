<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\Tender;
use App\Services\StosBackendClient;
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
=======
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
>>>>>>> ef2addc99f96ee697754b9781a138906b76e3efe
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
}
