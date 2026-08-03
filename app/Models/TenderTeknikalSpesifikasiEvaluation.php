<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderTeknikalSpesifikasiEvaluation extends Model
{
    protected $table = 'tender_teknikal_spesifikasi_evaluations';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'checklist_item_uuid',
        'specification_detail_uuid',
        'input_value',
        'skor_automatik',
        'skor_manual',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'skor_automatik' => 'decimal:2',
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
