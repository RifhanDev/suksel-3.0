<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalChecklistItem extends Model
{
    protected $fillable = [
        'uuid',
        'technical_checklist_header_id',
        'source_type',
        'title',
        'mechanism',
        'vendor_action',
        'score',
        'status',
        'sort_order',
        'standard_item_id',
        'specification_document_id',
        'standard_item_id',
        'online_form_key',
        'online_form_route',
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
        return $this->belongsTo(TechnicalChecklistHeader::class, 'technical_checklist_header_id');
    }
}
