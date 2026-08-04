<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderTeknikalKerjaEvaluation extends Model
{
    protected $table = 'tender_teknikal_kerja_evaluations';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'status',
        'created_by',
        'updated_by',
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
