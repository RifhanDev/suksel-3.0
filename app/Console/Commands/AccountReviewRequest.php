<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;
use App\User;

class AccountReviewRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:account-review-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Account Review Request to all user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();
        $users = User::active()
            ->where(function ($query) use ($today) {
                $query->whereNull('arr_sent_at')
                    ->orWhere('arr_sent_at', '<', $today->subMonths(5));
            })
            ->whereNotNull('organization_unit_id')
            ->whereNotIn('email', ['anonymous', 'tenderadmin@selangor.gov.my'])
            ->get();

        foreach ($users as $user) {
            // Mail::send('users.emails.account-review-request', ['user' => $user], function ($message) use ($user) {
            //     $message->to($user->email);
            //     $message->subject('Permintaan Semakan Akaun Oleh Sistem Tender');
            // });

            $to			= trim($user->email);
            $subject 	= 'Permintaan Semakan Akaun Pengguna Oleh Sistem Tender';
            $send_status = $this->sendMail("html", $to, $subject, "", "users.emails.account-review-request", ['user' => $user]);


            $user->arr_sent_at = Carbon::now();
            $user->arr = 0;
            $user->save();
        }
    }
}
