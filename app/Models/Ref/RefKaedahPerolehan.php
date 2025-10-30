<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefKaedahPerolehan extends Model
{
    protected $table = 'ref_kaedah_perolehans';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
