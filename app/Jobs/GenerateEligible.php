<?php

namespace App\Jobs;

use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateEligible implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Helper;

    /** Allow long runs when many MOF/CIDB vendors match. */
    public int $timeout = 600;

    public int $tries = 1;

    protected $tender_id;

    protected $email;

    public function __construct($tender_id, $email = 1)
    {
        $this->tender_id = $tender_id;
        $this->email = $email;
    }

    public function handle(): void
    {
        Log::info('[GenerateEligible] Starting eligible generation.', [
            'tender_id' => $this->tender_id,
        ]);

        $this->generateEligible($this->tender_id, (int) $this->email);

        Log::info('[GenerateEligible] Completed eligible generation.', [
            'tender_id' => $this->tender_id,
        ]);
    }
}
