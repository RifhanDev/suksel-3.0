<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbiddingKertasTaklimatItem extends Model
{
    protected $table = 'ebidding_kertas_taklimat_items';

    protected $fillable = [
        'kertas_taklimat_id',
        'slot_key',
        'kandungan',
        'sort_order',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(EbiddingKertasTaklimat::class, 'kertas_taklimat_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(EbiddingKertasTaklimatItemFile::class, 'item_id');
    }
}
