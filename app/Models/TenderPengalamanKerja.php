<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderPengalamanKerja extends Model
{
    protected $fillable = [
        'uuid',
        'tender_uuid',
        'tajuk',
        'pic',
        'telefon_pic',
        'nilai_kerja',
        'sort_order',
    ];

    protected $casts = [
        'nilai_kerja' => 'decimal:2',
        'sort_order'  => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
