<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderVendorOnlineFormStatus extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'vendor_id',
        'form_key',
        'status',
        'summary',
        'submitted_at',
        'updated_by',
    ];

    protected $casts = [
        'summary' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }
}
