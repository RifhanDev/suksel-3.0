<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalSpecificationDetail extends Model
{
    protected $fillable = [
        'uuid',
        'technical_specification_item_id',
        'description',
        'response_type',
        'score_mode',
        'max_score',
        'sort_order',
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(TechnicalSpecificationItem::class, 'technical_specification_item_id');
    }
}
