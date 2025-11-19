<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefTypeOfPerolehan extends Model
{
    protected $table = 'ref_type_of_perolehans';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
