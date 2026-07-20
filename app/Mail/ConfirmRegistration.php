<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class ConfirmRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

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
      return $this->view('auth.emails.confirm')
         ->with('user_name', $this->user->name)
         ->with('confirmation_code', $this->user->confirmation_code);
      // return $this->view('vendors.emails.confirm_emails')->with('vendor', $this->vendor);
   }
}
