<?php

namespace App\Http\Controllers;

use App\Services\StosBackendClient;
use Illuminate\Http\Request;

class StandardChecklistItemController extends Controller
{
    public function index(Request $request)
    {
        $response = StosBackendClient::http()->get(StosBackendClient::apiUrl('standard-checklist-items'), $request->only([
            'category',
            'type',
            'is_active',
            'limit',
        ]));

        return response()->json($response->json(), $response->status());
    }
}
