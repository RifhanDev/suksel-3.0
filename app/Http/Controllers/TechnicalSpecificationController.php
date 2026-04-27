<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TechnicalSpecificationController extends Controller
{
    public function store(Request $request)
    {
        $response = $this->api()->post($this->url('technical-specifications'), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function show(string $uuid)
    {
        $response = $this->api()->get($this->url('technical-specifications/' . $uuid));

        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, string $uuid)
    {
        $response = $this->api()->put($this->url('technical-specifications/' . $uuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function complete(string $uuid)
    {
        $response = $this->api()->post($this->url('technical-specifications/' . $uuid . '/complete'));

        return response()->json($response->json(), $response->status());
    }

    public function destroy(string $uuid)
    {
        $response = $this->api()->delete($this->url('technical-specifications/' . $uuid));

        return response()->json($response->json(), $response->status());
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
