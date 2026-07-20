<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefHaveNo extends Model
{
    protected $table = 'ref_have_nos';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
