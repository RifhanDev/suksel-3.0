<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderVendorDokumenResponse extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'vendor_id',
        'checklist_item_uuid',
        'section',
        'response_type',
        'payload',
        'status',
        'updated_by',
    ];

    protected $casts = [
        'payload' => 'array',
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
