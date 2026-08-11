<?php

namespace App\Models;

use App\Tender;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TenderKewanganProgress extends Model
{
    protected $table = 'tender_kewangan_progress';

    protected $fillable = [
        'tender_id',
        'current_step',
        'step1_confirmed_at',
        'step1_confirmed_by',
        'step2_confirmed_at',
        'step2_confirmed_by',
        'step3_confirmed_at',
        'step3_confirmed_by',
    ];

    protected $casts = [
        'current_step'       => 'integer',
        'step1_confirmed_at' => 'datetime',
        'step2_confirmed_at' => 'datetime',
        'step3_confirmed_at' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function step1ConfirmedBy()
    {
        return $this->belongsTo(User::class, 'step1_confirmed_by');
    }

    public function step2ConfirmedBy()
    {
        return $this->belongsTo(User::class, 'step2_confirmed_by');
    }

    public function step3ConfirmedBy()
    {
        return $this->belongsTo(User::class, 'step3_confirmed_by');
    }

    public function isStep1Confirmed(): bool
    {
        return $this->step1_confirmed_at !== null;
    }

    public function isStep2Confirmed(): bool
    {
        return $this->isStep1Confirmed() && $this->step2_confirmed_at !== null;
    }

    public function isStep3Confirmed(): bool
    {
        return $this->isStep2Confirmed() && $this->step3_confirmed_at !== null;
    }

    public function isStepUnlocked(int $stepIndex): bool
    {
        return match ((int) $stepIndex) {
            1 => true,
            2 => $this->isStep1Confirmed(),
            3 => $this->isStep2Confirmed(),
            4 => $this->isStep3Confirmed(),
            default => false,
        };
    }
}
