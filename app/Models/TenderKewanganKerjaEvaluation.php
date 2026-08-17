<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderKewanganKerjaEvaluation extends Model
{
    protected $table = 'tender_kewangan_kerja_evaluations';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'borang_code',
        'status_pematuhan',
        'payload',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status_pematuhan' => 'integer',
        'payload'          => 'array',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
