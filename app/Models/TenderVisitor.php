<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderVisitor extends Model
{
    protected $table = 'tender_visitors';

    /**
    * Fillable columns
    */
    protected $fillable = [
        'vendor_id',
        'visit_id',
    ];

    public function visit()
    {
        return $this->belongsTo('App\TenderVist');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public static function hasVisit($visit_id, $vendor_id)
    {
        return self::where('visit_id', $visit_id)->where('vendor_id', $vendor_id)->count() == 1;
    }
}
