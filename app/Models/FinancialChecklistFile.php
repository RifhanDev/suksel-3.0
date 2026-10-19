<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialChecklistFile extends Model
{
    protected $fillable = [
        'uuid',
        'financial_checklist_header_id',
        'financial_checklist_item_id',
        'file_type',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(FinancialChecklistHeader::class, 'financial_checklist_header_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(FinancialChecklistItem::class, 'financial_checklist_item_id');
    }
}
