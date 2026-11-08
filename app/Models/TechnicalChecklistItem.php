<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalChecklistItem extends Model
{
    protected $fillable = [
        'uuid',
        'technical_checklist_header_id',
        'source_type',
        'title',
        'mechanism',
        'vendor_action',
        'penghantaran_fizikal',
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
        'penghantaran_fizikal' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(TechnicalChecklistHeader::class, 'technical_checklist_header_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(TechnicalChecklistFile::class);
    }

    public function specificationDocument(): BelongsTo
    {
        return $this->belongsTo(TechnicalSpecificationDocument::class, 'specification_document_id');
    }
}
