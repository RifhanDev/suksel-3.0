<?php

namespace App\Jobs;

use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Helper;

    public $unique_id = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($unique_id)
    {
        $this->unique_id = $unique_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[SendEmailJob] Dispatching email to mail server.', ['unique_id' => $this->unique_id]);

        try {
            ob_start();
            $this->trigger_mail_server($this->unique_id);
            ob_end_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            Log::error('[SendEmailJob] Job threw an exception.', [
                'unique_id' => $this->unique_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
