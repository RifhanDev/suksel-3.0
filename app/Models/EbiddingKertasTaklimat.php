<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbiddingKertasTaklimat extends Model
{
    protected $table = 'ebidding_kertas_taklimats';

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
        return $this->hasMany(EbiddingKertasTaklimatItem::class, 'kertas_taklimat_id')->orderBy('sort_order');
    }
}
