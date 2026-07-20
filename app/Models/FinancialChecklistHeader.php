<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialChecklistHeader extends Model
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

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FinancialChecklistItem::class)->orderBy('sort_order');
    }
}
