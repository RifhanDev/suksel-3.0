<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialChecklistItem extends Model
{
    protected $fillable = [
        'uuid',
        'financial_checklist_header_id',
        'source_type',
        'technical_item_id',
        'standard_item_id',
        'title',
        'mechanism',
        'vendor_action',
        'score',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(FinancialChecklistHeader::class, 'financial_checklist_header_id');
    }
}
