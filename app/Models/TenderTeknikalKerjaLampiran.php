<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TenderTeknikalKerjaLampiran extends Model
{
    protected $table = 'tender_teknikal_kerja_lampirans';

    protected $fillable = [
        'uuid',
        'tender_id',
        'display_name',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
        'updated_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender()
    {
        return $this->belongsTo(\App\Tender::class);
    }

    public function url(): string
    {
        return route('penilaianTeknikalKerja.lampiran.download', $this->uuid);
    }

    public function absolutePath(): ?string
    {
        if (Storage::disk('public')->exists($this->path)) {
            return Storage::disk('public')->path($this->path);
        }

        return null;
    }
}
