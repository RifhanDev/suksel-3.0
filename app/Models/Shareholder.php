<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
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
		'vendor_id',
		'name',
		'identity',
		'nationality',
		'bumiputera_status',
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
		'name'              => 'required',
		'identity'          => 'required',
		'nationality'       => 'required',
		'bumiputera_status' => 'required',
		
		],
		'update' => [
		'name'              => 'required',
		'identity'          => 'required',
		'nationality'       => 'required',
		'bumiputera_status' => 'required',
		
		]
	];
	
	public static $rules = [];
	
	public static function setRules($name) {
	self::$rules = self::$_rules[$name];
	}
	
	/**
	* ACL
	*/
	
	public static function canList($vendor_id) {
	return (auth()->user() && (auth()->user()->ability(['Admin', 'Shareholder Admin', 'User'], ['Shareholder:list']) || auth()->user()->vendor_id == $vendor_id));
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Shareholder Admin'], ['Shareholder:create']));
	}
	
	public function canShow() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Shareholder Admin'], ['Shareholder:show']))
			return true;
		return false;
	}
	
	public function canUpdate()  {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Shareholder Admin'], ['Shareholder:edit']))
			return true;
		return false;
	}
	
	public function canDelete() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Shareholder Admin'], ['Shareholder:delete']))
			return true;
		return false;
	}
	
	/**
	* Relationships
	*/
	
	
	/**
	* Decorators
	*/
	
	public function getNameAttribute($value) {
		return $value;
	}
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
	}
	}
