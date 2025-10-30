<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefTypeOfPemenuhan extends Model
{
    protected $table = 'ref_type_of_pemenuhans';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
