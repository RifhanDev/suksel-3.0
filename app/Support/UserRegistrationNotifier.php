<?php

namespace App\Support;

use App\Mail\PendingAgencyApprovalNotification;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserRegistrationNotifier
{
    public function notifyAgencyAdmins(User $user): void
    {
        if (! $user->organization_unit_id) {
            return;
        }

        $recipients = User::query()
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($user) {
                $query->where(function ($agencyQuery) use ($user) {
                    $agencyQuery->where('organization_unit_id', $user->organization_unit_id)
                        ->whereHas('roles', function ($roleQuery) {
                            $roleQuery->where('name', 'Agency Admin');
                        });
                })->orWhereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'Admin');
                });
            })
            ->get()
            ->unique('id');

        if ($recipients->isEmpty()) {
            Log::warning('[UserRegistrationNotifier] No Agency Admin or Admin recipients found.', [
                'user_id' => $user->id,
                'organization_unit_id' => $user->organization_unit_id,
            ]);

            return;
        }

        foreach ($recipients as $admin) {
            try {
                Mail::to(trim($admin->email))->send(new PendingAgencyApprovalNotification($user, $admin));
                Log::info('[UserRegistrationNotifier] Pending approval email sent.', [
                    'new_user_id' => $user->id,
                    'recipient_id' => $admin->id,
                    'recipient_email' => $admin->email,
                ]);
            } catch (\Throwable $e) {
                Log::error('[UserRegistrationNotifier] Failed to send pending approval email.', [
                    'new_user_id' => $user->id,
                    'recipient_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
