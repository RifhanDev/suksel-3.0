<?php

namespace App\Traits;

use App\Models\ApiRequest;
use App\Models\ApiToken;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;

trait ApiHelper
{
    public function tokenValidator($token)
    {
        $isValid = ApiToken::where('token', $token)->exists();

        return $isValid;
    }

    public function recordApiRequest($token,$api_type,$parameter) {
        $org = ApiToken::where('token', $token)->first();
        $arr = [
            'organization_unit_id' => $org->organization_unit_id,
            'token' => $token,
            'api_type' => $api_type,
            'parameter' => json_encode($parameter)
        ];

        return ApiRequest::create($arr);
    }

    public function isExists($collection)
    {
        if($collection == null){
            return false;
        }
        return true;
    }
}
