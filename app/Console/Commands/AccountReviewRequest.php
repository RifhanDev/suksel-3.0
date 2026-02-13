<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;
use App\User;
use App\Traits\Helper;

class AccountReviewRequest extends Command
{
    use Helper;
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

        // Hantar emel Account Review Request kepada SEMUA pengguna aktif
        // (kecuali emel anonymous / tenderadmin) tanpa mengira tarikh arr_sent_at.
        // Tarikh arr_sent_at akan dikemas kini ke tarikh semasa selepas emel dihantar.
        $users = User::active()
            ->whereNotNull('organization_unit_id')
            ->whereNotIn('email', ['anonymous', 'tenderadmin@selangor.gov.my'])
            ->get();

        foreach ($users as $user) {
            // Hantar emel menggunakan view sedia ada
            Mail::send(
                'users.emails.account-review-request',
                ['user' => $user],
                function ($message) use ($user) {
                    $message->to(trim($user->email));
                    $message->subject('Permintaan Semakan Akaun Pengguna Oleh Sistem Tender');
                }
            );

            $user->arr_sent_at = Carbon::now();
            $user->arr = 0;
            $user->save();
        }
    }
}
