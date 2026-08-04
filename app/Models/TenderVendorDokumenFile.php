<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TenderVendorDokumenFile extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'vendor_id',
        'checklist_item_uuid',
        'section',
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

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function url(): string
    {
        return route('tenderDokumen.download', $this->uuid);
    }

    public function absolutePath(): ?string
    {
        $path = ltrim((string) $this->path, '/');

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->path($path);
        }

        $publicPath = public_path($path);
        if (is_file($publicPath)) {
            return $publicPath;
        }

        return null;
    }
}
