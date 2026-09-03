<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesTenderProcessAfterChecklistSubmit;
use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\PenyediaanIklanNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SenaraiKewanganKerjaController extends Controller
{
    use UpdatesTenderProcessAfterChecklistSubmit;
    /**
     * Display the Senarai Kewangan Kerja page with existing checklist data.
     */
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        // Load checklist data from backend
        $apiUrl   = $this->url('kewangan-kerja/' . $tenderUuid);
        $response = $this->api()->get($apiUrl);

        $checklistData = null;

        if ($response->successful()) {
            $checklistData = $response->json('data');
        } else {
            Log::warning('SenaraiKewanganKerjaController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'api_url'     => $apiUrl,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        // Collect standard_item_uuids currently in use in tbl-kewangan
        $usedStandardUuids = array_values(array_filter(
            array_map(fn ($item) => $item['standard_item_uuid'] ?? null, $checklistData['items'] ?? [])
        ));

        // Load standard items for the "Senarai Semak Standard" modal (excluding mandatory 'borang_atas_talian' and already used items)
        $standardItems = [];
        try {
            $standardResponse = $this->api()->get($this->url('standard-checklist-items?category=kewangan_kerja'));

            if ($standardResponse->successful()) {
                $standardItems = $standardResponse->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            Log::warning('SenaraiKewanganKerjaController@index: STOS API request failed, using local DB fallback', [
                'error' => $e->getMessage(),
            ]);
        }

        if (empty($standardItems)) {
            $standardItems = \Illuminate\Support\Facades\DB::table('standard_checklist_items')
                ->where('category', 'kewangan_kerja')
                ->where('type', '!=', 'borang_atas_talian')
                ->when(!empty($usedStandardUuids), fn ($q) => $q->whereNotIn('uuid', $usedStandardUuids))
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        } else {
            $standardItems = array_values(array_filter(
                $standardItems,
                fn ($item) => ($item['type'] ?? '') !== 'borang_atas_talian'
                    && !in_array($item['uuid'] ?? '', $usedStandardUuids, true)
            ));
        }

        return view(
            'newModule.jawatankuasaSpesifikasi.senarai_kewangan_kerja',
            [
                'tender' => $tender,
                'checklistData' => $checklistData,
                'standardItems' => $standardItems,
                'afterSpecificationUrl' => PenyediaanIklanNavigation::afterSpecificationUrl($tender),
            ]
        );
    }

    /**
     * Save draft — proxied to STOS-EPENILAIAN-WEB.
     */
    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('kewangan-kerja/' . $tenderUuid),
            $request->except('_token')
        );

        return response()->json($response->json(), $response->status());
    }

    /**
     * Submit checklist — proxied to STOS-EPENILAIAN-WEB.
     */
    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post(
            $this->url('kewangan-kerja/' . $tenderUuid . '/submit'),
            $request->except('_token')
        );

        if ($response->successful()) {
            $this->refreshTenderProcessAfterChecklistSubmit($tenderUuid);
        }

        return response()->json($response->json(), $response->status());
    }

    /**
     * Upload a file — proxied to STOS-EPENILAIAN-WEB.
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
                $this->url('kewangan-kerja/' . $tenderUuid . '/files'),
                $request->except(['_token', 'file'])
            );

        return response()->json($response->json(), $response->status());
    }

    /**
     * Delete a file — proxied to STOS-EPENILAIAN-WEB.
     */
    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->delete(
            $this->url('kewangan-kerja-files/' . $fileUuid)
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
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }
}
