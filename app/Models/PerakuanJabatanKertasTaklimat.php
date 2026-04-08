<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerakuanJabatanKertasTaklimat extends Model
{
    protected $table = 'perakuan_jabatan_kertas_taklimats';

    protected $fillable = [
        'tender_id',
        'catatan',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PerakuanJabatanKertasTaklimatItem::class, 'kertas_taklimat_id')->orderBy('sort_order');
    }
}
