<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderTeknikalBorangEvaluation extends Model
{
    protected $table = 'tender_teknikal_borang_evaluations';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'checklist_item_uuid',
        'skor_manual',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'skor_manual' => 'decimal:2',
    ];

    public function tender()
    {
        return $this->belongsTo(\App\Tender::class);
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Vendor::class);
    }
}
