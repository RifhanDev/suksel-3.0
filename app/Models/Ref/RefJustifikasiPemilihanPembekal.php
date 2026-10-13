<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefJustifikasiPemilihanPembekal extends Model
{
    protected $table = 'ref_justifikasi_pemilihan_pembekals';

    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
