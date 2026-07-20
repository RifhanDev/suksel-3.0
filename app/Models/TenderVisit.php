<?php

namespace App\Models;

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
	
	public static function boot() {
		parent::boot();
		
		self::created(function(){
			cache()->tags('TenderSiteVisit')->flush();
		});
		
			self::updated(function(){
		cache()->tags('TenderSiteVisit')->flush();
		});
		
		self::deleted(function(){
			cache()->tags('TenderSiteVisit')->flush();
		});
	}
}
