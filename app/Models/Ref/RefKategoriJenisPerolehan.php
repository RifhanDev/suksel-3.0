<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefKategoriJenisPerolehan extends Model
{
    protected $table = 'ref_kategori_jenis_perolehans';

    protected $casts = [
        'active' => 'bool'
    ];

    protected $guarded = [];
}
