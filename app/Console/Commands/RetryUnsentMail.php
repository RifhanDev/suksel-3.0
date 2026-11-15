<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob;
use App\Models\MailQueue;
use App\Traits\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Re-sends mail_queues rows still in status N — emails that never reached the
 * mail server.
 *
 * Each email gets exactly one send attempt when it is created (SendEmailJob
 * dispatched ->afterResponse()). If that attempt cannot reach the mail server,
 * nothing else ever retries it, so the row stays "Belum dihantar" for good.
 * This command is that retry, run on a schedule from routes/console.php.
 *
 * Only status N is touched. Once the mail server has handled a row it is S
 * (sent) or T (SMTP attempted and failed), and resending those is not this
 * command's job.
 */
class RetryUnsentMail extends Command
{
    use Helper;

    protected $signature = 'mail:retry-unsent
        {--dry-run : Senaraikan rekod yang akan dihantar semula tanpa menghantarnya}';

    protected $description = 'Hantar semula emel mail_queues berstatus N (belum sampai ke mail server)';

    public function handle(): int
    {
        $minAge = (int) config('schedule.mail_retry.min_age_minutes');
        $window = (int) config('schedule.mail_retry.window_hours');
        $batch  = (int) config('schedule.mail_retry.batch_size');

        $pending = MailQueue::query()
            ->where('status', 'N')
            ->where('created_at', '<=', now()->subMinutes($minAge))
            ->where('created_at', '>=', now()->subHours($window));

        $total = (clone $pending)->count();
        $rows  = $pending->orderBy('id')->limit($batch)->get(['id', 'created_at']);

        if ($rows->isEmpty()) {
            $this->info('Tiada emel tertunggak untuk dihantar semula.');

            return self::SUCCESS;
        }

        $this->info("Emel tertunggak: {$total}. Larian ini memproses {$rows->count()} (had {$batch}).");

        if ($this->option('dry-run')) {
            $this->table(
                ['id', 'created_at'],
                $rows->map(fn ($row) => [$row->id, $row->created_at])->all()
            );
            $this->comment('Dry run: tiada emel dihantar.');

            return self::SUCCESS;
        }

        foreach ($rows as $row) {
            dispatch_sync(new SendEmailJob($this->encryptString((string) $row->id)));
        }

        // The mail server writes the outcome back to the same row, so this shows
        // whether each one actually got through rather than just that we tried.
        $outcome = MailQueue::whereIn('id', $rows->pluck('id'))
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $sent    = (int) ($outcome['S'] ?? 0);
        $failed  = (int) ($outcome['T'] ?? 0);
        $unsent  = (int) ($outcome['N'] ?? 0);

        $this->info("Selesai. Dihantar: {$sent}, gagal SMTP: {$failed}, masih belum sampai ke mail server: {$unsent}.");

        Log::info('[mail:retry-unsent] Retried unsent emails.', [
            'pending_total' => $total,
            'processed'     => $rows->count(),
            'sent'          => $sent,
            'smtp_failed'   => $failed,
            'still_unsent'  => $unsent,
        ]);

        return self::SUCCESS;
    }
}
