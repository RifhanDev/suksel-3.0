<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StosWebhookController extends Controller
{
    /**
     * Receive callbacks from STOS-EPENILAIAN-WEB after processing completes.
     */
    public function handle(Request $request)
    {
        $event = $request->input('event');
        $payload = $request->input('payload', []);

        Log::info('STOS webhook received', [
            'event' => $event,
            'payload' => $payload,
            'source' => $request->input('source'),
        ]);

        // Hook domain handlers here, e.g. update tender status, notify users, etc.
        switch ($event) {
            case 'tender.created':
                break;
            case 'penyediaan_iklan.saved':
            case 'penyediaan_iklan.submitted':
                break;
            case 'penyediaan_mesyuarat.saved':
            case 'penyediaan_mesyuarat.submitted':
            case 'kehadiran_mesyuarat.saved':
                break;
            default:
                if (is_string($event) && str_starts_with($event, 'process.')) {
                    break;
                }
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook received.',
            'event' => $event,
        ]);
    }
}
