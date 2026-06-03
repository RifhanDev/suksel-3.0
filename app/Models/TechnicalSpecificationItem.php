<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
