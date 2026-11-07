<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TenderKerjaDalamTanganDokumen extends Model
{
    protected $fillable = [
        'uuid',
        'tender_uuid',
        'vendor_id',
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
        if (\App\Support\AttachmentStorage::isRemote()) {
            return route('attachments.stream', ['path' => ltrim((string) $this->path, '/')]);
        }

        return Storage::disk('public')->url($this->path);
    }
}
