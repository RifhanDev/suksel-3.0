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
        'wang_kos_prima',
        'wang_peruntukan_semasa',
        'tarikh_tapak',
        'tempoh',
        'tarikh_siap',
        'tarikh_penilaian',
        'luputan',
        'kemajuan_sebenar',
        'kemajuan_jadual',
        'sort_order',
        'jenis',
    ];

    protected $casts = [
        'harga'                  => 'decimal:2',
        'wang_kos_prima'         => 'decimal:2',
        'wang_peruntukan_semasa' => 'decimal:2',
        'kemajuan_sebenar'       => 'decimal:2',
        'kemajuan_jadual'        => 'decimal:2',
        'jenis'                  => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(TenderPrestasiKerja::class, 'tender_prestasi_kerja_id');
    }
}
