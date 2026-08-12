<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SpesifikasiKerjaFile extends Model
{
    protected $fillable = [
        'uuid',
        'spesifikasi_kerja_header_id',
        'spesifikasi_kerja_item_id',
        'file_type',
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

    public function header(): BelongsTo
    {
        return $this->belongsTo(SpesifikasiKerjaHeader::class, 'spesifikasi_kerja_header_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(SpesifikasiKerjaItem::class, 'spesifikasi_kerja_item_id');
    }

    public function publicUrl(): string
    {
        $path = ltrim(str_replace('\\', '/', (string) $this->path), '/');
        if ($path === '') {
            return '';
        }

        $base = rtrim((string) config('services.stos_backend.url'), '/');
        if ($base !== '') {
            return $base . '/storage/' . $path;
        }

        return Storage::disk('public')->url($path);
    }
}
