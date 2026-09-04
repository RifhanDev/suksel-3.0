<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerakuanJabatanPengesyoranPembekal extends Model
{
    protected $table = 'perakuan_jabatan_pengesyoran_pembekals';

    protected $fillable = [
        'tender_id',
        'catatan',
        'sahkan_petender_layak',
        'submitted_at',
    ];

    protected $casts = [
        'sahkan_petender_layak' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PerakuanJabatanPengesyoranPembekalItem::class, 'pengesyoran_pembekal_id');
    }
}
