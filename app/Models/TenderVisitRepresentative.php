<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderVisitRepresentative extends Model
{
    protected $table = 'tender_visit_representatives';

    protected $fillable = [
        'visit_id',
        'vendor_id',
        'ic_no',
        'name',
        'attended',
    ];

    public function visit()
    {
        return $this->belongsTo(\App\TenderVisit::class, 'visit_id');
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Vendor::class, 'vendor_id');
    }
}

