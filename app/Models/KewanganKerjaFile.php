<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KewanganKerjaFile extends Model
{
    protected $fillable = [
        'uuid',
        'kewangan_kerja_header_id',
        'kewangan_kerja_item_id',
        'file_type',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(KewanganKerjaHeader::class, 'kewangan_kerja_header_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(KewanganKerjaItem::class, 'kewangan_kerja_item_id');
    }
}
