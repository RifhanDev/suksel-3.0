<?php

namespace App\Http\Controllers;

use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StandardChecklistItemController extends Controller
{
    public function index(Request $request)
    {
        try {
            $apiUrl   = StosBackendClient::apiUrl('standard-checklist-items');
            $response = StosBackendClient::http()->get($apiUrl, $request->only([
                'category',
                'type',
                'is_active',
                'limit',
            ]));

            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }

            Log::warning('StandardChecklistItemController: STOS API returned error status, falling back to local DB', [
                'status' => $response->status(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('StandardChecklistItemController: STOS API unreachable, falling back to local DB', [
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback to local database standard_checklist_items table
        $query = DB::table('standard_checklist_items')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($request->filled('category')) {
            $query->where('category', (string) $request->input('category'));
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        $items = $query->get()->map(function ($item) {
            return [
                'uuid'                  => $item->uuid,
                'category'              => $item->category,
                'type'                  => $item->type,
                'title'                 => $item->title,
                'mechanism_default'     => $item->mechanism_default,
                'vendor_action_default' => $item->vendor_action_default,
                'action_url'            => $item->action_url,
                'is_active'             => (bool) $item->is_active,
                'sort_order'            => (int) $item->sort_order,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $items->values()->all(),
        ]);
    }
}
