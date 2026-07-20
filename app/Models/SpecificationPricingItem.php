<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationPricingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'specification_pricing_id',
        'spec_item_id',
        'title',
        'kuantiti',
        'uom',
        'harga',
        'sort_order',
    ];

    protected $casts = [
        'kuantiti' => 'decimal:3',
        'harga'    => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function pricing()
    {
        return $this->belongsTo(SpecificationPricing::class, 'specification_pricing_id');
    }
}
