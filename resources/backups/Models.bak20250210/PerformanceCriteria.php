<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'scale_1',
        'scale_2',
        'scale_3',
        'scale_4',
        'scale_5',
        'scale_6',
        'review_1',
        'review_2',
        'review_3',
        'review_4',
        'review_5',
        'review_6',

        // FK
        'petender_performance_id'
    ];

    /**
     * Relationships
     */
    public function petenderPerformance()
    {
        return $this -> belongsTo(PetenderPerformance::class, 'petender_performance_id');
    }
}
