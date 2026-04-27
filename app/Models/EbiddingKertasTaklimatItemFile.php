<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbiddingKertasTaklimatItemFile extends Model
{
    protected $table = 'ebidding_kertas_taklimat_item_files';

    protected $fillable = [
        'item_id',
        'file_path',
        'file_original_name',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(EbiddingKertasTaklimatItem::class, 'item_id');
    }
}
