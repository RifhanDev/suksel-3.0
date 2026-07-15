<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalSpecificationItem extends Model
{
    protected $fillable = [
        'uuid',
        'technical_specification_document_id',
        'title',
        'quantity',
        'unit',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function details(): HasMany
    {
        return $this->hasMany(TechnicalSpecificationDetail::class, 'technical_specification_item_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
