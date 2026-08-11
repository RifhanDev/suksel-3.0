<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpesifikasiKerjaItem extends Model
{
    protected $fillable = [
        'uuid',
        'spesifikasi_kerja_header_id',
        'parent_id',
        'nama_item',
        'spesifikasi',
        'unit',
        'kuantiti',
        'ya_tidak',
        'catatan',
        'kadar',
        'sort_order',
    ];

    protected $casts = [
        'kuantiti' => 'float',
        'kadar' => 'float',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(SpesifikasiKerjaHeader::class, 'spesifikasi_kerja_header_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function isParentItem(): bool
    {
        return $this->parent_id === null;
    }

    public function jumlah(): float
    {
        return round(((float) $this->kuantiti) * ((float) $this->kadar), 2);
    }
}
