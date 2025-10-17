<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use App\User;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
   public function __construct(User $user) {
      $this->user = $user;
   }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build() {

    	   $token = md5( uniqid(mt_rand(), true) );

        	DB::insert('insert into password_reminders (email, token, created_at) values (?, ?, ?)', [$this->user->email, $token, now()]);

         return $this->view('auth.emails.passwordreset')->with('user', $this->user)->with('token', $token);
    }
}
