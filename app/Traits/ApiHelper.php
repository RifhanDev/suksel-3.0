<?php

namespace App\Traits;

use App\Models\ApiRequest;
use App\Models\ApiToken;
use App\Support\ApiCredentialContext;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;

trait ApiHelper
{
    public function tokenValidator($token)
    {
        $credential = ApiToken::where('token', $token)->first(['id', 'token', 'status']);

        return $credential
            && (int) $credential->status === 1
            && hash_equals((string) $credential->token, (string) $token);
    }

    public function recordApiRequest($token, $api_type, $parameter)
    {
        $org = ApiToken::where('token', $token)->first(['id', 'organization_unit_id']);
        $arr = [
            'organization_unit_id' => $org->organization_unit_id ?? null,
            // Keep an audit identifier, never a reusable credential.
            'token' => $org ? 'legacy:' . $org->id : 'legacy:unknown',
            'api_type' => $api_type,
            'parameter' => json_encode(['fields' => array_keys((array) $parameter)])
        ];

        return ApiRequest::create($arr);
    }

    public function recordApiRequestForCredential(Request $request, string $apiType, array $parameters = [])
    {
        $credential = $request->attributes->get('api_credential');
        if (!$credential instanceof ApiCredentialContext) {
            return null;
        }

        return ApiRequest::create([
            'organization_unit_id' => $credential->organizationUnitId ?: null,
            'token' => $credential->auditIdentifier(),
            'api_type' => $apiType,
            'parameter' => json_encode(['fields' => array_keys($parameters)]),
        ]);
    }

    public function isExists($collection)
    {
        if($collection == null){
            return false;
        }
        return true;
    }
}
