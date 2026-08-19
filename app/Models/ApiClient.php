<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ApiClient extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'organization_unit_id',
        'status',
        'plain_token',
    ];

    protected $hidden = [
        'plain_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'plain_token' => 'encrypted',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class, 'organization_unit_id');
    }

    public function isActive(): bool
    {
        return (bool) $this->status;
    }
}
