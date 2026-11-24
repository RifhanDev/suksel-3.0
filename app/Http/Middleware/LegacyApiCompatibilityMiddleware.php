<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiToken;
use App\Support\ApiCredentialContext;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Authenticate the approved legacy API routes using either Sanctum or an
 * active STOS API key, without treating a machine client as a human user.
 */
class LegacyApiCompatibilityMiddleware
{
    public function handle($request, Closure $next, string $ability, string $legacySource = 'bearer')
    {
        if (!in_array($legacySource, ['bearer', 'body'], true)) {
            return $this->unauthorized('Invalid API authentication configuration.');
        }

        $bearerToken = $request->bearerToken();
        $bodyToken = $request->input('token');
        $authorizationHeader = $request->header('Authorization');

        if (($bearerToken && $bodyToken) || ($authorizationHeader && !$bearerToken)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Provide exactly one supported API credential.',
                'data' => [],
            ], 400);
        }

        // Sanctum itself parses and validates the bearer token. A valid Sanctum
        // identity with insufficient ability is rejected, never downgraded to
        // legacy-key authentication.
        $sanctumPrincipal = $bearerToken ? Auth::guard('sanctum')->user() : null;
        if ($sanctumPrincipal !== null) {
            if (!$sanctumPrincipal instanceof ApiClient || !$sanctumPrincipal->isActive()) {
                return $this->unauthorized();
            }

            $accessToken = $sanctumPrincipal->currentAccessToken();
            if (!$accessToken || !$accessToken->can($ability)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'The API client is not authorized for this endpoint.',
                    'data' => [],
                ], 403);
            }

            if (
                in_array($ability, ['tender:read', 'transaction:read', 'vendor:read'], true)
                && empty($sanctumPrincipal->organization_unit_id)
            ) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'The API client must be assigned to an agency before using this endpoint.',
                    'data' => [],
                ], 403);
            }

            $request->setUserResolver(function () use ($sanctumPrincipal) {
                return $sanctumPrincipal;
            });
            Auth::shouldUse('sanctum');

            $request->attributes->set('api_credential', new ApiCredentialContext(
                'sanctum',
                (int) $accessToken->getKey(),
                (int) ($sanctumPrincipal->organization_unit_id ?? 0),
                (int) $sanctumPrincipal->getKey()
            ));

            return $next($request);
        }

        $legacyToken = $legacySource === 'body' ? $bodyToken : $bearerToken;
        if (!is_string($legacyToken) || trim($legacyToken) === '') {
            return $this->unauthorized();
        }

        $legacyCredential = ApiToken::query()
            ->where('token', $legacyToken)
            ->first(['id', 'token', 'organization_unit_id', 'status']);

        if (!$this->legacyCredentialIsValid($legacyCredential, $legacyToken)) {
            return $this->unauthorized();
        }

        $request->attributes->set('api_credential', new ApiCredentialContext(
            'legacy',
            (int) $legacyCredential->id,
            (int) $legacyCredential->organization_unit_id
        ));

        return $next($request);
    }

    protected function unauthorized(string $message = 'A valid API credential is required.')
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'data' => [],
        ], 401);
    }

    protected function legacyCredentialIsValid(?ApiToken $credential, string $providedToken): bool
    {
        return $credential !== null
            && (int) $credential->status === 1
            && hash_equals((string) $credential->token, $providedToken);
    }
}
