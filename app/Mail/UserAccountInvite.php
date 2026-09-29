<?php

namespace App\Mail;

use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountInvite extends Mailable
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

        return $this->view('auth.emails.user-invite')
            ->with('user', $this->user)
            ->with('token', $this->token)
            ->subject('Pengesahan Emel & Tetapan Kata Laluan | Sistem Tender Online Selangor');
    }
}
