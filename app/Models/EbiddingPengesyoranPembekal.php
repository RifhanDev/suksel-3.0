<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbiddingPengesyoranPembekal extends Model
{
    protected $table = 'ebidding_pengesyoran_pembekals';

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
}
