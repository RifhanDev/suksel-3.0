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

    protected $description = 'Hantar emel tukar kata laluan kepada pengguna aktif pada hari kata laluan mereka mencapai 6 bulan.';

    public function handle(): int
    {
        // Counted from the last password change (any way it was changed), not from the account review date.
        $expiresOn = Carbon::today()->subMonths(User::PASSWORD_VALID_MONTHS);

        $users = User::active()
            ->where('approved', 1)
            ->whereNotNull('organization_unit_id')
            ->whereDate('password_changed_at', $expiresOn)
            ->whereNotIn('email', ['anonymous', 'tenderadmin@selangor.gov.my'])
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::to(trim($user->email))->send(new PeriodicPasswordReset($user));

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
