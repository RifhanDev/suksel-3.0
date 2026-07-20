<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderPrestasiKerjaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_prestasi_kerja_id',
        'nama',
        'no_kontrak',
        'harga',
        'tarikh_tapak',
        'tempoh',
        'tarikh_siap',
        'tarikh_penilaian',
        'luputan',
        'kemajuan_sebenar',
        'kemajuan_jadual',
        'sort_order',
    ];

    protected $casts = [
        'harga'            => 'decimal:2',
        'kemajuan_sebenar' => 'decimal:2',
        'kemajuan_jadual'  => 'decimal:2',
    ];

    public function parent()
    {
        return $this->belongsTo(TenderPrestasiKerja::class, 'tender_prestasi_kerja_id');
    }
}
