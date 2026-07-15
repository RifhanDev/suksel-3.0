<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KewanganKerjaItem extends Model
{
    protected $fillable = [
        'uuid',
        'kewangan_kerja_header_id',
        'spesifikasi_item_id',
        'standard_item_id',
        'source_type',
        'title',
        'mechanism',
        'vendor_action',
        'score',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(KewanganKerjaHeader::class, 'kewangan_kerja_header_id');
    }
}
