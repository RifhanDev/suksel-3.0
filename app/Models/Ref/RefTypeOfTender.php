<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefTypeOfTender extends Model
{
    protected $table = 'ref_type_of_tenders';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
