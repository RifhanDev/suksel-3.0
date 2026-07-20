<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonSahamAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'bon_saham_id',
        'bank_institusi',
        'jumlah_deposit',
    ];

    protected $casts = [
        'jumlah_deposit' => 'decimal:2',
    ];

    public function bonSaham()
    {
        return $this->belongsTo(BonSaham::class);
    }
}
