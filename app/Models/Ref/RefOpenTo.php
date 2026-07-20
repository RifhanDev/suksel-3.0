<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefOpenTo extends Model
{
    protected $table = 'ref_open_tos';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
