<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefYesNo extends Model
{
    protected $table = 'ref_yes_nos';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
