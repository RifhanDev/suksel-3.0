<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderPengalamanKerjaDokumen extends Model
{
    protected $fillable = [
        'uuid',
        'tender_uuid',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
