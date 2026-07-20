<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefSumberPeruntukan extends Model
{
    protected $table = 'ref_sumber_peruntukans';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
