<?php

namespace App;

use App\Support\TenderProcessStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
       	
       	if(!$this->transaction || !$this->transaction->created_at) {
            return $amount;
        	}

       	if(Carbon::parse($this->transaction->created_at)->timestamp > Carbon::parse('2015-06-08')->timestamp) {
            return $amount;
        	} else {
            return optional($this->tender)->price ?? $amount;
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

   /**
    * Companies the technical committee may evaluate: chosen on a submitted
    * cut-off, and not failed at the opening stage. A company eliminated
    * during technical evaluation stays on the list so that decision remains visible.
    */
   public function scopeForTechnicalEvaluation($query, int $tenderId)
   {
       $table = $query->getModel()->getTable();
       $refs = static::submittedCutOffVendorRefs($tenderId);

       $query->where("{$table}.tender_id", $tenderId)
           ->where("{$table}.participate", 1)
           ->where(function ($eligible) use ($table) {
               $eligible->where(function ($active) use ($table) {
                   $active->where("{$table}.cancel_fg", 0)
                       ->where(function ($opening) use ($table) {
                           $opening->whereNull("{$table}.eliminated_process_id")
                               ->orWhere("{$table}.eliminated_process_id", '!=', TenderProcessStatus::PENILAIAN_PEMBUKA);
                       });
               })->orWhere("{$table}.eliminated_process_id", TenderProcessStatus::PENILAIAN_TEKNIKAL);
           });

       if ($refs === []) {
           return $query->whereRaw('0 = 1');
       }

       $numericIds = array_values(array_filter($refs, fn ($ref) => ctype_digit($ref)));

       return $query->where(function ($match) use ($table, $refs, $numericIds) {
           $match->whereIn("{$table}.kod_pembekal", $refs);

           if ($numericIds !== []) {
               $match->orWhereIn("{$table}.vendor_id", array_map('intval', $numericIds));
           }

           $match->orWhereHas('vendor', function ($vendor) use ($refs) {
               $vendor->whereIn('registration', $refs);
           });
       });
   }

   /**
    * Supplier references ticked and submitted on the cut-off form
    * (kod pembekal, registration, or vendor id).
    *
    * @return list<string>
    */
   public static function submittedCutOffVendorRefs(int $tenderId): array
   {
       $raw = DB::table('cut_off_selections')
           ->where('tender_id', $tenderId)
           ->where('status', 'submitted')
           ->value('selected_refs');

       $refs = is_array($raw) ? $raw : json_decode((string) $raw, true);
       if (! is_array($refs)) {
           return [];
       }

       return array_values(array_unique(array_filter(array_map(
           fn ($ref) => trim((string) $ref),
           $refs
       ), fn ($ref) => $ref !== '' && strcasecmp($ref, 'AJ') !== 0)));
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
