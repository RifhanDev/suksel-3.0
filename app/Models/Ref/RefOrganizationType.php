<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefOrganizationType extends Model
{
    protected $table = 'ref_organization_types';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
