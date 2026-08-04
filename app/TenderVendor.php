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
        	'kod_pembekal',
        	'amount',
        	'label',
        	'price',
        	'exception',
        	'participate',
        	'briefing',
        	'winner',
        	'submitted',
        	'transaction_id',
        	'vendor_id',
        	'tender_id',
        	// Vendor elimination (generic, reusable across all process stages)
        	'cancel_fg',
        	'eliminated_process_id',
        	'eliminated_reason',
        	'eliminated_at',
        	// Jawatankuasa Pembuka rumusan fields
        	'is_bumiputera',
        	'harga_tawaran',
   ];

   protected $casts = [
       'cancel_fg'            => 'integer',
       'is_bumiputera'        => 'integer',
       'eliminated_at'        => 'datetime',
       'harga_tawaran'        => 'decimal:2',
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

   public static function syncKodPembekal(int $tenderId): void
   {
       	app(\App\Services\KodPembekalService::class)->syncForTender($tenderId);
   }

   // ─────────────────────────────────────────────────────────────────
   // Scopes
   // ─────────────────────────────────────────────────────────────────

   /**
    * Only vendors who have NOT been eliminated from the procurement process.
    */
   public function scopeActive($query)
   {
       return $query->where('cancel_fg', 0);
   }

   /**
    * Only vendors who have been eliminated from the procurement process.
    */
   public function scopeEliminated($query)
   {
       return $query->where('cancel_fg', 1);
   }

   // ─────────────────────────────────────────────────────────────────
   // Helpers
   // ─────────────────────────────────────────────────────────────────

   /**
    * Mark this vendor participation as eliminated at a given process stage.
    *
    * @param  int     $processId   The status_process_id at which elimination occurred.
    * @param  string  $reason      Human-readable reason string.
    */
   public function eliminate(int $processId, string $reason): void
   {
       $this->update([
           'cancel_fg'            => 1,
           'eliminated_process_id' => $processId,
           'eliminated_reason'    => $reason,
           'eliminated_at'        => now(),
       ]);
   }

   /**
    * Returns true if this vendor has been eliminated.
    */
   public function isEliminated(): bool
   {
       return (int) ($this->cancel_fg ?? 0) === 1;
   }
}
