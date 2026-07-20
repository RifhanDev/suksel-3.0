<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyataBankScoringItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'penyata_bank_id',
        'dari',
        'hingga',
        'skema',
        'sort_order',
    ];

    protected $casts = [
        'dari'   => 'decimal:2',
        'hingga' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function penyataBank()
    {
        return $this->belongsTo(PenyataBank::class);
    }
}
