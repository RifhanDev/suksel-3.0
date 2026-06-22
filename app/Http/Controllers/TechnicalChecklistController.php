<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TechnicalChecklistController extends Controller
{
    public function index(string $tenderUuid)
    {
        $this->ensureSpecificationAccess();
        
        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $apiUrl = $this->url('technical-checklists/' . $tenderUuid);
        $response = $this->api()->get($apiUrl);

        $checklistData = null;

        if ($response->successful()) {
            $checklistData = $response->json('data');
        } else {
            Log::warning('TechnicalChecklistController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'tender_id'   => $tender->id,
                'api_url'     => $apiUrl,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        $standardApiUrl = $this->url('standard-checklist-items?category=technical');
        $standardResponse = $this->api()->get($standardApiUrl);

        $standardItems = [];

        if ($standardResponse->successful()) {
            $standardItems = $standardResponse->json('data') ?? [];
        } else {
            Log::warning('TechnicalChecklistController@index: Failed to fetch standard checklist items', [
                'api_url' => $standardApiUrl,
                'status'  => $standardResponse->status(),
                'body'    => $standardResponse->body(),
            ]);
        }

        return view('TechnicalChecklist.senarai_teknikal', compact('tender', 'checklistData', 'standardItems'));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $response = $this->api()->post($this->url('technical-checklists/' . $tenderUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $response = $this->api()->post($this->url('technical-checklists/' . $tenderUuid . '/submit'), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

        $response = $this->api()->attach(
            'file',
            $request->file('file')->get(),
            $request->file('file')->getClientOriginalName()
        )->post($this->url('technical-checklists/' . $tenderUuid . '/files'), $request->except(['_token', 'file']));

        return response()->json($response->json(), $response->status());
    }

    public function deleteFile(string $fileUuid)
    {
        $this->ensureSpecificationAccess();

        $response = $this->api()->delete($this->url('technical-checklist-files/' . $fileUuid));

        return response()->json($response->json(), $response->status());
    }

    private function ensureSpecificationAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function api()
    {
        return Http::withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ]);
    }

    private function url(string $path): string
    {
        return config('services.stos_backend.url') . '/api/' . $path;
    }
}
