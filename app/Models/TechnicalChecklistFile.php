<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalChecklistFile extends Model
{
    protected $fillable = [
        'uuid',
        'technical_checklist_header_id',
        'technical_checklist_item_id',
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
        return $this->belongsTo(TechnicalChecklistHeader::class, 'technical_checklist_header_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(TechnicalChecklistItem::class, 'technical_checklist_item_id');
    }
}
