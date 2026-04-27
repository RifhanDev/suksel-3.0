<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawatankuasaPerolehanKertasKeputusan extends Model
{
    protected $table = 'jawatankuasa_perolehan_kertas_keputusans';

    protected $fillable = [
        'tender_id',
        'dengan_syarat',
        'syarat_nyatakan',
        'pengesyoran_catatan',
        'justifikasi_pemilihan_pembekal',
        'lampiran_file_nama',
        'lampiran_file_path',
        'keputusan',
        'catatan',
        'submitted_at',
    ];

    protected $casts = [
        'dengan_syarat' => 'boolean',
        'submitted_at' => 'datetime',
    ];
}
