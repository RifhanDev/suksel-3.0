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
use App\Models\MailQueue;
use App\Models\TenderEligible;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Helper;

    public $unique_id = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($unique_id, public ?int $mailQueueId = null)
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
        Log::info('[SendEmailJob] Dispatching email to mail server.', ['mail_queue_id' => $this->mailQueueId]);

        try {
            ob_start();
            $this->trigger_mail_server($this->unique_id);
            ob_end_clean();

            $this->syncEligibilityStatusFromMailQueue();
        } catch (\Exception $e) {
            ob_end_clean();
            Log::error('[SendEmailJob] Job threw an exception.', [
                'mail_queue_id' => $this->mailQueueId,
                'exception' => get_class($e),
            ]);
            throw $e;
        }
    }

    /**
     * Update Senarai Layak only after the mail server records an SMTP result.
     * A queued (N) message is not reported as sent.
     */
    protected function syncEligibilityStatusFromMailQueue(): void
    {
        if (!$this->mailQueueId) {
            return;
        }

        $mailQueue = MailQueue::query()->find($this->mailQueueId);
        if (!$mailQueue) {
            return;
        }

        $payload = json_decode((string) $mailQueue->payload, true);
        $eligibleId = data_get($payload, 'tracking.tender_eligible_id');
        if (!$eligibleId) {
            return;
        }

        $eligible = TenderEligible::query()->find($eligibleId);
        if (!$eligible) {
            return;
        }

        if ($mailQueue->status === 'S') {
            $eligible->update([
                'email' => 2,
                'sent_at' => $mailQueue->email_send_at ?: now(),
            ]);
        } elseif (in_array($mailQueue->status, ['T', 'F'], true)) {
            $eligible->update([
                'email' => 4,
                'sent_at' => null,
            ]);
        }
    }
}
