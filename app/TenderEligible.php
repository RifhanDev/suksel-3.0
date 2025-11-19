<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenderEligible extends Model
{
	protected $table = 'tender_eligibles';
	
	protected $fillable = [
		'tender_id',
		'vendor_id',
		'sent_at',
		'email'
	];
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	public function tender() {
		return $this->belongsTo('App\Tender');
	}
	
	public function sendEmail() {
		Mail::queue('tenders.emails.eligible', ['tender_id' => $tender->id, 'vendor_id' => $vendor->id], function($message) use($vendor, $tender) {
			$message->to(trim($vendor->user->email));
			$message->subject('Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name);   
		});
	}
}
