<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalChecklistFile extends Model
{
    protected $fillable = [
        'uuid',
        'technical_checklist_header_id',
        'technical_checklist_item_id',
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
}
