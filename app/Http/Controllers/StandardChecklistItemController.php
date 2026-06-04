<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StandardChecklistItemController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ])->get(config('services.stos_backend.url') . '/api/standard-checklist-items', $request->only([
            'category',
            'type',
            'is_active',
            'limit',
        ]));

        return response()->json($response->json(), $response->status());
    }
}
