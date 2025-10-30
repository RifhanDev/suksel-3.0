<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefTypeOfContract extends Model
{
    protected $table = 'ref_type_of_contracts';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
