<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpecificationPricingController extends Controller
{
    public function index(string $spesifikasiUuid)
    {
        $this->ensureAccess();

        $apiUrl   = $this->url('specification-pricings/' . $spesifikasiUuid);
        $response = $this->api()->get($apiUrl);

        $pricingData = null;

        if ($response->successful()) {
            $pricingData = $response->json('data');
        } else {
            Log::warning('SpecificationPricingController@index: API request failed', [
                'spesifikasi_uuid' => $spesifikasiUuid,
                'status'           => $response->status(),
                'body'             => $response->body(),
            ]);
        }

        return view('jawatankuasaSpesifikasi.spesifikasi_kewangan', compact('spesifikasiUuid', 'pricingData'));
    }

    public function store(Request $request, string $spesifikasiUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('specification-pricings/' . $spesifikasiUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $spesifikasiUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('specification-pricings/' . $spesifikasiUuid . '/submit'), $request->except('_token'));

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
