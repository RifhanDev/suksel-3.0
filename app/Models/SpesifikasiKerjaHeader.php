<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpesifikasiKerjaHeader extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'status',
        'submitted_at',
        'submitted_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SpesifikasiKerjaItem::class)->orderBy('sort_order');
    }
}
