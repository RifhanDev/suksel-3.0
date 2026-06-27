<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Services\StosBackendClient;
use App\Support\PenyediaanIklanNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinancialChecklistController extends Controller
{
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $apiUrl  = $this->url('financial-checklists/' . $tenderUuid);
        $response = $this->api()->get($apiUrl);

        $checklistData = null;

        if ($response->successful()) {
            $checklistData = $response->json('data');
        } else {
            Log::warning('FinancialChecklistController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'api_url'     => $apiUrl,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        $standardApiUrl  = $this->url('standard-checklist-items?category=financial');
        $standardResponse = $this->api()->get($standardApiUrl);

        $standardItems = [];

        if ($standardResponse->successful()) {
            $standardItems = $standardResponse->json('data') ?? [];
        } else {
            Log::warning('FinancialChecklistController@index: Failed to fetch financial standard items', [
                'api_url' => $standardApiUrl,
                'status'  => $standardResponse->status(),
                'body'    => $standardResponse->body(),
            ]);
        }

        return view('jawatankuasaSpesifikasi.senarai_kewangan_bekalan', [
            'tender' => $tender,
            'checklistData' => $checklistData,
            'standardItems' => $standardItems,
            'afterSpecificationUrl' => PenyediaanIklanNavigation::afterSpecificationUrl($tender),
        ]);
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('financial-checklists/' . $tenderUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('financial-checklists/' . $tenderUuid . '/submit'), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->attach(
            'file',
            $request->file('file')->get(),
            $request->file('file')->getClientOriginalName()
        )->post($this->url('financial-checklists/' . $tenderUuid . '/files'), $request->except(['_token', 'file']));

        return response()->json($response->json(), $response->status());
    }

    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->delete($this->url('financial-checklist-files/' . $fileUuid));

        return response()->json($response->json(), $response->status());
    }

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
