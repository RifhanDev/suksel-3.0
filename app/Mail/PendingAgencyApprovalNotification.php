<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingAgencyApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public User $admin
    ) {}

    public function build()
    {
        return $this->subject('Permohonan Kelulusan Akaun Pengguna Baharu – '.$this->user->name)
            ->view('users.emails.pending-approval-notify-agency-admin');
    }
}
