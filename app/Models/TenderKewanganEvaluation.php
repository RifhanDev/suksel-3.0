<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderKewanganEvaluation extends Model
{
    protected $table = 'tender_kewangan_evaluations';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'checklist_item_uuid',
        'status_pematuhan',
        'catatan',
        'skor',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status_pematuhan' => 'integer',
        'skor'             => 'float',
    ];

    public function tender()
    {
        return $this->belongsTo(\App\Tender::class);
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Vendor::class);
    }

    public function isFailed(): bool
    {
        return (int) $this->status_pematuhan === 0;
    }

    public function isPassed(): bool
    {
        return (int) $this->status_pematuhan === 1;
    }
}
