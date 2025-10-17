<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use PDF;

class TenderVendor extends Model
{
   protected $table = 'tender_vendors';

   protected $fillable = [
        	'ref_number',
        	'amount',
        	'label',
        	'price',
        	'participate',
        	'briefing',
        	'winner',
        	'submitted',
        	'transaction_id',
        	'vendor_id',
        	'tender_id'
   ];

   public function canViewReceipt() {
        	if(auth()->check()) {
            $user = auth()->user();

            if($user->ability(['Admin', 'Agency Admin', 'Agency User'], [])) {
                	return true;
            } elseif($user->hasRole('Vendor') && $user->vendor_id == $this->vendor_id) {
                	return true;
            } else {
                	return false;
            }

        	} else {
            return false;
        	}
   }

   public function vendor() {
        	return $this->belongsTo('App\Vendor');
   }

   public function tender() {
        	return $this->belongsTo('App\Tender');
   }

   public function transaction() {
        	return $this->belongsTo('App\Transaction');
   }

   public function getAmountAttribute($amount) {
       	
       	if(Carbon::parse($this->transaction->created_at)->timestamp > Carbon::parse('2015-06-08')->timestamp) {
            return $amount;
        	} else {
            return $this->tender->price;
        	}
   }

   public function spellOut() {
        	
        	$items = explode(".", $this->amount);
        	$cent = (new \NumberFormatter("ms", \NumberFormatter::SPELLOUT))->format($items[1]);
        	return strtoupper((new \NumberFormatter("ms", \NumberFormatter::SPELLOUT))->format($items[0]). " Ringgit Dan " . $cent . " Sen");
   }

   public static function generateNumber($tender_id) {
        	
        	$tender = Tender::find($tender_id);
        	if(!$tender) return null;

        	$count = self::where('tender_id', $tender_id)->where('participate', 1)->count();
        	$new_count = $count + 1;

        	return "{$tender->ref_number} ONLINE {$new_count}";
   }
}
