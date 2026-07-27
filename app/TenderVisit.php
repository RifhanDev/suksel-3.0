<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenderVisit extends Model
{
	protected $table = 'tender_visits';
	
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
		'tender_id',
		'datetime',
		'address',
		'required',
		'meetpoint'
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
			'tender_id' => 'required',
			'datetime'  => 'required',
			'address'   => 'required',
		],
		'update' => [
		'tender_id' => 'required',
		'datetime'  => 'required',
		'address'   => 'required',
	]
	];
	
	public static $rules = [];
	
	public static function setRules($name) {
		self::$rules = self::$_rules[$name];
	}
	
	public function tender() {
	return $this->belongsTo('App\Tender');
	}
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	public function visitors() {
		return $this->hasMany('App\TenderVisitor', 'visit_id');
	}

	public function representatives()
	{
		return $this->hasMany(\App\Models\TenderVisitRepresentative::class, 'visit_id');
	}

	/**
	 * Vendors may register representatives on or before the site visit date.
	 */
	public function canSubmitRepresentatives(): bool
	{
		if (empty($this->datetime)) {
			return false;
		}

		return \Carbon\Carbon::today()->lte(\Carbon\Carbon::parse($this->datetime)->startOfDay());
	}
	
	public static function boot() {
		parent::boot();

		self::created(function () {
			static::flushSiteVisitCache();
		});

		self::updated(function () {
			static::flushSiteVisitCache();
		});

		self::deleted(function () {
			static::flushSiteVisitCache();
		});
	}

	protected static function flushSiteVisitCache(): void
	{
		try {
			cache()->tags('TenderSiteVisit')->flush();
		} catch (\Throwable) {
			// File/database cache drivers do not support tags.
		}
	}
}
