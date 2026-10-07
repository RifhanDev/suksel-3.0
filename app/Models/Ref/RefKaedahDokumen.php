<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefKaedahDokumen extends Model
{
    protected $table = 'ref_kaedah_dokumens';

    protected $guarded = [];

    protected $casts = [
        'active' => 'bool',
        'skips_to_penyediaan_iklan' => 'bool',
    ];
}
