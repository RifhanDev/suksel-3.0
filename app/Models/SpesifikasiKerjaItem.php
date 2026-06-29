<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpesifikasiKerjaItem extends Model
{
    protected $fillable = [
        'uuid',
        'spesifikasi_kerja_header_id',
        'spesifikasi',
        'ya_tidak',
        'catatan',
        'sort_order',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(SpesifikasiKerjaHeader::class, 'spesifikasi_kerja_header_id');
    }
}
