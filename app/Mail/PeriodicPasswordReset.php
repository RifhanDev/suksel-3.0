<?php

namespace App\Mail;

use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeriodicPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $token;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $this->token = md5(uniqid(mt_rand(), true));

        DB::insert(
            'insert into password_reminders (email, token, created_at) values (?, ?, ?)',
            [$this->user->email, $this->token, now()]
        );

        return $this->subject('Tukar Kata Laluan (6 Bulan) | Sistem Tender Online Selangor')
            ->view('auth.emails.periodic-password-reset')
            ->with('user', $this->user)
            ->with('token', $this->token);
    }
}
