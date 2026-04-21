<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbiddingJadualBidaan extends Model
{
    protected $table = 'ebidding_jadual_bidaans';

    protected $fillable = [
        'tender_id',
        'tarikh_bidaan_mula',
        'masa_bidaan_mula',
        'tarikh_bidaan_tamat',
        'masa_bidaan_tamat',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'tarikh_bidaan_mula' => 'date',
        'tarikh_bidaan_tamat' => 'date',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }
}
