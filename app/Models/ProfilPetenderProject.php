<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPetenderProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'profil_petender_id',
        'nama',
        'agensi',
        'nilai_projek',
        'sort_order',
    ];

    protected $casts = [
        'nilai_projek' => 'decimal:2',
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
