<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
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
		'start_date',
		'end_date',
		'transaction_id',
		'vendor_id',
		'renewal'
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
			'start_date'     => 'required',
			'end_date'       => 'required',
			'transaction_id' => 'required',
		
		],
		'update' => [
			'start_date'     => 'required',
			'end_date'       => 'required',
			'transaction_id' => 'required',
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
		return true;
	}
	
	public static function canCreate() {
		return true;
	}
	
	public function canShow() {
		return false;
	}
	
	public function canUpdate() {
		return false;
	}
	
	public function canDelete() {
		return false;
	}
	
	/**
	* Relationships
	*/
	
	public function transaction() {
		return $this->belongsTo('App\Transaction');
	}
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	
	/**
	* Decorators
	*/
	
	public function getNameAttribute($value) {
		return $value;
	}
	

	public static function getLastSubscription($vendor_id) {
		$subscription = self::where('vendor_id', $vendor_id)->where('renewal', 0)->latest('end_date')->first();
		if (!is_null($subscription)) {
			return Carbon::parse($subscription->end_date)->format('d/m/Y');
		}
		else return null;
		
	}
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
		
		self::created(function($model){
		$vendor                     = $model->vendor;
		$vendor->registration_paid  = 1;
		$vendor->expiry_date        = $model->end_date;
		$vendor->save();
		});
		
		self::created(function(){
		   cache()->tags('Subscription')->flush();
		});
		
		self::updated(function(){
		   cache()->tags('Subscription')->flush();
		});
		
		self::deleted(function(){
		   cache()->tags('Subscription')->flush();
		});
	}
}
