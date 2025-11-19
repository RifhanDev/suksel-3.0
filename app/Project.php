<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
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
	* ACL
	*/
	
	public static function canList($vendor_id) {
		return (auth()->user() && (auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User', 'Vendor'], ['Vendor:show']) || auth()->user()->vendor_id == $vendor_id));
	}
	
	public static function canCreate()  {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User', 'Vendor'], ['Vendor:edit']));
	}
	
	public function canShow() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User', 'Vendor'], ['Vendor:show']))
			return true;
		return false;
	}
	
	public function canUpdate() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User', 'Vendor'], ['Vendor:edit']))
			return true;
		return false;
	}
	
	public function canDelete() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User', 'Vendor'], ['Vendor:edit']))
			return true;
		return false;
	}
	
	/**
	* Fillable columns
	*/
	protected $fillable = [
		'vendor_id',
		'name',
		'customer',
		'period',
		'value',
		'done'
	];
	
	/**
	* Validation Rules
	*/
	private static $_rules = [
		'store' => [
		'name'     => 'required',
		'customer' => 'required',
		'period'   => 'required',
		'value'    => 'required',
		
		],
		'update' => [
		'name'     => 'required',
		'customer' => 'required',
		'period'   => 'required',
		'value'    => 'required',
		]
	];
	
	public static $rules = [];
	
	public static function setRules($name) {
		self::$rules = self::$_rules[$name];
	}
	
	/**
	* Relationships
	*/
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
}
