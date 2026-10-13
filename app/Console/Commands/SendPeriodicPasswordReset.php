<?php

namespace App\Console\Commands;

use App\Mail\PeriodicPasswordReset;
use App\User;
use App\UserHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPeriodicPasswordReset extends Command
{
    protected $signature = 'send:periodic-password-reset';

    protected $description = 'Hantar emel tukar kata laluan kepada pengguna aktif 6 bulan selepas arr_sent_at.';

    public function handle(): int
    {
        $cutoff = Carbon::today()->subMonths(User::ARR_REVIEW_INTERVAL_MONTHS);

        $users = User::active()
            ->where('approved', 1)
            ->whereNotNull('organization_unit_id')
            ->whereNotNull('arr_sent_at')
            ->where('arr_sent_at', '<=', $cutoff)
            ->whereNotIn('email', ['anonymous', 'tenderadmin@selangor.gov.my'])
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::to(trim($user->email))->send(new PeriodicPasswordReset($user));

                $user->arr_sent_at = Carbon::now();
                $user->save();

                UserHistory::log($user->id, 'password-reset-sent');
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                Log::error('[SendPeriodicPasswordReset] Failed to send password reset email.', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Periodic password reset: {$sent} sent, {$failed} failed, {$users->count()} due.");

        return self::SUCCESS;
    }
}
