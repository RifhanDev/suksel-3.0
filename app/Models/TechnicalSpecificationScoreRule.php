<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalSpecificationScoreRule extends Model
{
    protected $fillable = [
        'uuid',
        'technical_specification_detail_id',
        'rule_type',
        'from_value',
        'to_value',
        'answer_value',
        'score',
        'sort_order',
    ];

    protected $casts = [
        'from_value' => 'decimal:4',
        'to_value' => 'decimal:4',
        'score' => 'decimal:2',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(TechnicalSpecificationDetail::class, 'technical_specification_detail_id');
    }
}
