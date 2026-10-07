<?php

namespace App\Models;

use App\Tender;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderIklanDokumen extends Model
{
    protected $fillable = [
        'tender_id',
        'name',
        'original_name',
        'path',
        'mime',
        'size',
        'uploaded_by',
        'sort_order',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
