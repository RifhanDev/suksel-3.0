<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyataBankFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'penyata_bank_id',
        'file_type',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
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
