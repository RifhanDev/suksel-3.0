<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'technical_checklist_item_id',
        'anggaran_jabatan',
        'jumlah_harga',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'anggaran_jabatan' => 'decimal:2',
        'jumlah_harga'     => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function technicalChecklistItem()
    {
        return $this->belongsTo(TechnicalChecklistItem::class, 'technical_checklist_item_id');
    }

    public function items()
    {
        return $this->hasMany(SpecificationPricingItem::class)->orderBy('sort_order');
    }
}
