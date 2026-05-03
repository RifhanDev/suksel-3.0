<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfilPetenderController extends Controller
{
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $apiUrl   = $this->url('profil-petenders/' . $tenderUuid);
        $response = $this->api()->get($apiUrl);

        $profilData = null;

        if ($response->successful()) {
            $profilData = $response->json('data');
        } else {
            Log::warning('ProfilPetenderController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        return view('jawatankuasaSpesifikasi.profil_petender', compact('tender', 'profilData'));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('profil-petenders/' . $tenderUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('profil-petenders/' . $tenderUuid . '/submit'), $request->except('_token'));

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
