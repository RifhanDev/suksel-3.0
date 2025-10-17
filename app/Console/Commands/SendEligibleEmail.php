<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TenderEligible;
use App\Vendor;
use App\Tender;
use Mail;
use Carbon\Carbon;

class SendEligibleEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:eligible-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tender eligible email';

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
        $eligibles = TenderEligible::with('tender', 'vendor')->whereNull('sent_at')
			->where('email', 1)
			->whereHas('vendor', function($q){
				$q->whereRaw("blacklisted_until < current_date");
			})
			->where('created_at', '>', '2023-04-1')
			// ->orderBy('tender_id', 'desc', 'id')
			->limit(100)
			->get();

		$eligibles = $eligibles->sortBy([
			['tender_id','desc'],
			['id','asc'],
		]);

        foreach($eligibles as $eligible)
        {
            $vendor = $eligible->vendor;
            $tender = $eligible->tender;

            if($tender && $vendor && $vendor->user && !$vendor->user->isEmailBlacklist() && $vendor->canParticipateInTenders())
            {
				
				// Check Email
				if (filter_var(trim($vendor->user->email), FILTER_VALIDATE_EMAIL)) {
				
					// Mail::send('tenders.emails.eligible', ['tender_id' => $tender->id, 'vendor_id' => $vendor->id], function($message) use($vendor, $tender) {
					// 	$message->to(trim($vendor->user->email));
					// 	$message->subject('Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name);   
					// });

                    $to			= trim($vendor->user->email);
                    $subject 	= 'Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name;
                    $send_status = $this->sendMail("html", $to, $subject, "", "tenders.emails.eligible", ['tender_id' => $tender->id, 'vendor_id' => $vendor->id]);


					$eligible->update([
						'sent_at' => Carbon::now()
					]);
				} else {
					
				}
            }
            else
            {
                $eligible->update([
                    'email' => false
                ]);
            }
        }
    }
}
