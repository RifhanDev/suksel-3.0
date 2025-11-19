<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetenderPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'type',
        'quantity',
        'cost',
        'acquisition_date',
        'opinion',
        'overall_review',
        'total_score',

        // FK
        'tender_id',
        'vendor_id',
        'appraiser_id'
    ];

    /**
     * Relationships
     */
    public function performanceCriteria()
    {
        return $this -> hasOne(PerformanceCriteria::class, 'petender_performance_id') -> withDefault();
    }

    public function tender()
    {
        return $this -> belongsTo(Tender::class, 'tender_id');
    }

    public function vendor()
    {
        return $this -> belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this -> belongsTo(User::class, 'appraiser_id');
    }
}
