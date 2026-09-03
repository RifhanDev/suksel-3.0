<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderKakitanganTeknikalDokumen extends Model
{
    protected $table = 'tender_kakitangan_teknikal_dokumens';

    protected $fillable = [
        'uuid',
        'tender_uuid',
        'vendor_id',
        'kakitangan_uuid',
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

    public function getUrlAttribute(): string
    {
        return route('kakitanganTeknikal.dokumen.download', $this->uuid);
    }
}
