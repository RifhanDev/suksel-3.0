<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Transaction extends Model
{
	public static $types = [
		'subscription' => 'Langganan',
		'purchase'     => 'Pembelian Dokumen'
	];
	
	public static $statuses = [
		'pending'               => 'Belum Diterima',
		'success'               => 'Berjaya',
		'declined'              => 'Ditolak',
		'failed'                => 'Gagal',
		'pending_authorization' => 'Dalam Proses Pengesahan'
	];
	
	/**
	* $show_authorize_flag
	* 0 => all
	* 1 => show mine only
	* 2 => if i'm a head of ou, show all under my ou
	* 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
	*/
	static $show_authorize_flag = 0;
	
	/**
	* $update_authorize_flag
	* 0 => all
	* 1 => show mine only
	* 2 => if i'm a head of ou, show all under my ou
	* 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
	*/
	static $update_authorize_flag = 0;
	
	/**
	* $delete_authorize_flag
	* 0 => all
	* 1 => show mine only
	* 2 => if i'm a head of ou, show all under my ou
	* 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
	*/
	static $delete_authorize_flag = 0;
	
	/**
	* Fillable columns
	*/
	protected $fillable = [
		'number',
		'type',
		'method',
		'amount',
		'claimed',
		'gateway_message',
		'gateway_response',
		'gateway_reference',
		'gateway_auth',
		'response_code',
		'response_message',
		'status',
		'organization_unit_id',
		'user_id',
		'vendor_id',
		'gateway_id',
		'cached_data',
		'receipt_generated_at',
	];
	
	/**
	* These attributes excluded from the model's JSON form.
	* @var array
	*/
	protected $hidden = [
		// 'password'
	];
	
	/**
	* Validation Rules
	*/
	private static $_rules = [
		'store' => [
			'organization_unit_id' => 'required',
			'vendor_id'            => 'vendor_id',
			'number'               => 'required',
			'type'                 => 'required',
			'method'               => 'required',
			'amount'               => 'required',
		],
		'update' => [
			'organization_unit_id' => 'required',
			'vendor_id'            => 'vendor_id',
			'number'               => 'required',
			'type'                 => 'required',
			'method'               => 'required',
			'amount'               => 'required',
		]
	];
	
	public static $rules = [];
	
	public static function setRules($name) {
		self::$rules = self::$_rules[$name];
	}
	
	/**
	* ACL
	*/
	
	public static function canList() {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['Transaction:list']));
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], []));
	}
	
	public function canShow() {
		$user = auth()->user();
		if (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin'], ['Transaction:show'])) {
			return true;
		}
		return false;
	}
	
	public function canUpdate() {
		$user = auth()->user();
		
		if ($this->status == 'success') {
			return false;
		}
	
		if ($user->can('Transaction:edit')) {
			return true;
		}
	
		if (
				$user->can('Transaction:edit:organization_unit_id') &&
				$this->gateway &&
				$this->gateway->organization_unit_id == $user->organization_unit_id
			) {
			return true;
		}
	
		return false;
	}
	
	public function canDelete() {
		return false;
	}
	
	/**
	* Relationships
	*/
	
	public function agency() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}
	
	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	public function subscription() {
		return $this->hasOne('App\Subscription');
	}
	
	public function getCachedSubscriptionAttribute() {
		$sub = unserialize($this->cached_data);
		if (! empty($sub)) {
			return (object)$sub;
		} else {
			return null;
		}
	}
	
	public function purchases() {
		return $this->hasMany('App\TenderVendor');
	}
	
	public function getCachedPurchasesAttribute() {
		$items = unserialize($this->cached_data);
		return Tender::whereIn('id', $items)->get();
	}
	
	public function gateway() {
		return $this->belongsTo('App\Gateway');
	}
	
	public function getReceiptNumberAttribute() {
		return isset($this->gateway_reference) ? sprintf('%s-%s', $this->vendor_id, $this->gateway_reference) : '';
	}
	
	public function getBankNameAttribute() {
		if (! empty($this->gateway_response)) {
			$data = explode(' | ', $this->gateway_response);
			$params = [];
			foreach ($data as $d) {
			$key_val = explode(': ', $d);
			$params[$key_val[0]] = $key_val[1];
			}
			
			return isset($params['fpx_buyerBankBranch']) ? $params['fpx_buyerBankBranch'] : null;
		} else {
			return null;
		}
	}
	
	public function getSellerTxnTimeAttribute()
	{

		if(!empty($this->gateway_response)) {
			$data   = explode(' | ', $this->gateway_response);
			$params = [];
			foreach($data as $d) {
				$key_val = explode(': ', $d);
				$params[$key_val[0]] = $key_val[1];
			}
		
			if (isset($params['fpx_sellerTxnTime'])) {
				$string = $params['fpx_sellerTxnTime'];
				
				$year = substr($string, 0, 4);
				$month = substr($string, 4, 2);
				$day = substr($string, 6, 2);
				$hour = substr($string, 8, 2);
				$min = substr($string, 10, 2);
				$sec = substr($string, 12, 2);
			
				return $day . '/' . $month . '/' . $year . ' ' . $hour . ':' . $min . ':' . $sec;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	public function generateTenderVendor() {
	
		if ($this->type == 'purchase' && $this->status == 'success') {
			$items = unserialize($this->cached_data);
			$tenders = Tender::whereIn('id', $items)->get();
		
			foreach ($tenders as $tender) {
				$purchase = $tender->participants()->where('vendor_id', $this->vendor_id)->first();
				
				if ($purchase) {
					if ($purchase->participate == 0) {
					$purchase->participate = 1;
					$purchase->ref_number = TenderVendor::generateNumber($tender->id);
					$purchase->amount = $tender->price;
					$purchase->transaction_id = $this->id;
					$purchase->save();
					}
				} else {
					$tender->participants()->save(new TenderVendor([
					'ref_number' => TenderVendor::generateNumber($tender->id),
					'transaction_id' => $this->id,
					'participate' => 1,
					'amount' => $tender->price,
					'vendor_id' => $this->vendor_id
					]));
				}
			}
		}
	}
	
	public function generateSubscription() {
		if ($this->type == 'subscription' && $this->status == 'success' && empty($this->subscription)) {
			$vendor     = $this->vendor;
			$start_date = Carbon::parse($this->cached_subscription->start_date);
			$end_date   = Carbon::parse($this->cached_subscription->end_date);

			$subscription = $vendor->subscriptions()->save(new Subscription([
				'transaction_id' => $this->id,
				'start_date'     => $start_date->format('Y-m-d'),
				'end_date'       => $end_date->format('Y-m-d'),
				'renewal'        => $vendor->registration_paid
			]));
		}
	}
	
	public function getGatewayDataAttribute() {
		$data = [];
		if (! empty($this->gateway_message)) {
				$datum = explode('|', $this->gateway_message);
			if (count($datum) < 2) {
				return $data;
			}
			
			foreach ($datum as $d) {
				$keyval = explode(':', $d);
			
				if (count($keyval) < 2) {
					continue;
				}
				$data[trim($keyval[0])] = trim($keyval[1]);
			}
		}
	
		return $data;
	}
	
	public function getGatewayMessagesAttribute() {
		$data = [];
		
		if (! empty($this->gateway_message)) {
			$raw_data = explode('|', $this->gateway_message);
			foreach ($raw_data as $raw_datum) {
				$data[trim(explode(':', $raw_datum)[0])] = trim(explode(':', $raw_datum)[1]);
			}
		}
		
		return $data;
	}
	
	public function getEbpgSignatureAttribute() {
		if ($this->method != 'ebpg') {
			return null;
		}
	
		$signature_string = [
			$this->gateway->private_key,
			$this->gateway->merchant_code,
			$this->gateway->transaction_prefix . $this->number,
			$this->amount,
			$this->gateway_reference
		];
	
		return hash('sha512', implode('', $signature_string));
	}
	
	public function getEbpgSignature2Attribute() {
		if ($this->method != 'ebpg') {
			return null;
		}
	
		$signature_string = [
		strtoupper($this->gateway->private_key),
		$this->gateway->merchant_code,
		strtoupper($this->gateway->transaction_prefix . $this->number),
		$this->amount,
		$this->gateway_reference,
		strtoupper($this->gateway_response),
		strtoupper($this->response_code),
		];
	
		return strtoupper(hash('sha512', implode('', $signature_string)));
	}
	
	public function spellOut() {
		$items = explode(".", $this->amount);
		$cent = (new \NumberFormatter("ms", \NumberFormatter::SPELLOUT))->format($items[1]);
		return strtoupper((new \NumberFormatter("ms", \NumberFormatter::SPELLOUT))->format($items[0]). " Ringgit Dan " . $cent . " Sen");
	}
	
	public function getValidEbpgSignatureAttribute(){
		if ($this->method != 'ebpg') {
			return true;
		}
	
		if (! array_key_exists('TXN_SIGNATURE', $this->gateway_messages)) {
			return true;
		}
	
		return $this->gateway_messages['TXN_SIGNATURE'] == $this->ebpg_signature;
	}
	
	public function getValidEbpgSignature2Attribute() {
		if ($this->method != 'ebpg') {
			return true;
		}
	
		if (! array_key_exists('TXN_SIGNATURE2', $this->gateway_messages)) {
			return true;
		}
	
		return $this->gateway_messages['TXN_SIGNATURE2'] == $this->ebpg_signature2;
	}
	
	public static function transactionSubscription() {
		return self::where('type', 'subscription');
	}
	
	public static function transactionSubscriptionCount() {
		return self::transactionSubscription()
		->count();
	}
	
	public static function transactionSubscriptionYearlyCount() {
		return self::transactionSubscription()
		->where(DB::raw('YEAR(created_at)'), date('Y'))
		->count();
	}
	
	public static function transactionSubscriptionValue() {
		return self::transactionSubscription()
		->where('status', 'success')
		->where(DB::raw('YEAR(created_at)'), date('Y'))
		->sum('amount');
	}
	
	public static function transactionPurchase() {
		return self::where('type', 'purchase');
	}
	
	public static function transactionPurchaseCount() {
		return self::transactionPurchase()
		->count();
	}
	
	public static function transactionPurchaseYearlyCount() {
		return self::transactionPurchase()
		->where(DB::raw('YEAR(created_at)'), date('Y'))
		->count();
	}
	
	public static function transactionPurchaseValue() {
		return self::transactionPurchase()
		->where('status', 'success')
		->where(DB::raw('YEAR(created_at)'), date('Y'))
		->sum('amount');
	}
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
		
		self::created(function () {
			cache()->tags('Transaction')->flush();
		});
		
		self::updated(function ($txn) {
			$txn->generateTenderVendor();
			$txn->generateSubscription();
		});
		
		self::deleted(function () {
			cache()->tags('Transaction')->flush();
		});
		
		self::creating(function ($model) {
		//
		});
	}
}
