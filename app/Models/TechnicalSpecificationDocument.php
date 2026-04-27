<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalSpecificationDocument extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'title',
        'item_type',
        'specification_type',
        'goods_type',
        'weighting_type',
        'physical_submission',
        'status',
        'total_score',
        'completed_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'physical_submission' => 'boolean',
        'total_score'         => 'decimal:2',
        'completed_at'        => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
