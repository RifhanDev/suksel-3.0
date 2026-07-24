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
        $path = ltrim((string) $this->path, '/');

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            $url = Storage::disk('public')->url($path);
            $parsedPath = parse_url($url, PHP_URL_PATH);
            return $parsedPath ?: '/storage/' . $path;
        }

        $assetUrl = asset($path);
        $parsedPath = parse_url($assetUrl, PHP_URL_PATH);
        return $parsedPath ?: '/' . $path;
    }
}
