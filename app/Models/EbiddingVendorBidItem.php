<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbiddingVendorBidItem extends Model
{
    protected $table = 'ebidding_vendor_bid_items';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'pemilihan_item_id',
        'bid_price',
        'submitted_at',
    ];

    protected $casts = [
        'bid_price' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    public function pemilihanItem(): BelongsTo
    {
        return $this->belongsTo(JawatankuasaPerolehanPemilihanItem::class, 'pemilihan_item_id');
    }
}
