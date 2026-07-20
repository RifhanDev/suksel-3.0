<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPetenderScoringItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'profil_petender_id',
        'jenis_skor',
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

    public function profilPetender()
    {
        return $this->belongsTo(ProfilPetender::class);
    }
}
