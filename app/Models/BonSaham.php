<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonSaham extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_id',
        'jumlah_keseluruhan',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'jumlah_keseluruhan' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function accounts()
    {
        return $this->hasMany(BonSahamAccount::class);
    }
}
