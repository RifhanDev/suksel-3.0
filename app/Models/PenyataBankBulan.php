<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyataBankBulan extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'penyata_bank_id',
        'bulan',
        'tahun',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function penyataBank()
    {
        return $this->belongsTo(PenyataBank::class);
    }
}
