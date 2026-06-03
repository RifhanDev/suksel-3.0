<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalChecklistHeader extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'max_score',
        'passing_score',
        'passing_percentage',
        'status',
        'submitted_at',
        'submitted_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'max_score'          => 'decimal:2',
        'passing_score'      => 'decimal:2',
        'passing_percentage' => 'decimal:2',
        'submitted_at'       => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
