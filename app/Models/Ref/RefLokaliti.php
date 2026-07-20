<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefLokaliti extends Model
{
    protected $table = 'ref_lokalitis';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
