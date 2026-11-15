<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderPrestasiKerjaDokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_prestasi_kerja_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TenderPrestasiKerja::class, 'tender_prestasi_kerja_id');
    }

    public function prestasiKerja(): BelongsTo
    {
        return $this->belongsTo(TenderPrestasiKerja::class, 'tender_prestasi_kerja_id');
    }
}
