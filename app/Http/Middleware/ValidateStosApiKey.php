<?php

namespace App\Http\Middleware;

use Closure;

class ValidateStosApiKey
{
    public function handle($request, Closure $next)
    {
        $configuredKey = config('services.stos_backend.inbound_api_key');

        if (empty($configuredKey)) {
            return response()->json([
                'success' => false,
                'message' => 'STOS inbound API key is not configured.',
            ], 503);
        }

        $providedKey = $request->header('X-API-Key');

        if (! is_string($providedKey) || ! hash_equals($configuredKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
