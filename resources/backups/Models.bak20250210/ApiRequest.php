<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_unit_id',
        'token',
        'api_type',
        'parameter',
    ];

    protected $casts = [
        'parameter' => 'object'
    ];
}
