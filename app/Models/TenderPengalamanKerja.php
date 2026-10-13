<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderPengalamanKerja extends Model
{
    protected $fillable = [
        'uuid',
        'tender_uuid',
        'vendor_id',
        'tajuk',
        'pic',
        'telefon_pic',
        'wang_kos_prima',
        'wang_peruntukan_semasa',
        'nilai_kerja',
        'sort_order',
    ];

    protected $casts = [
        'wang_kos_prima'        => 'decimal:2',
        'wang_peruntukan_semasa' => 'decimal:2',
        'nilai_kerja'            => 'decimal:2',
        'sort_order'             => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
