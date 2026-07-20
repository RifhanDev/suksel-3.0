<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderVendorFormPayload extends Model
{
    protected $fillable = [
        'uuid',
        'tender_id',
        'vendor_id',
        'form_key',
        'payload',
        'status',
        'submitted_at',
        'updated_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'submitted_at' => 'datetime',
    ];
}
