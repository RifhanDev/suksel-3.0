<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
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
		'designation',
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
			'name'        => 'required',
			'identity'    => 'required',
			'nationality' => 'required',
			'designation' => 'required',
		
		],
		'update' => [
			'name'        => 'required',
			'identity'    => 'required',
			'nationality' => 'required',
			'designation' => 'required',
		
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
		return (auth()->user() && (auth()->user()->ability(['Admin', 'Director Admin', 'User'], ['Director:list']) || auth()->user()->vendor_id == $vendor_id));
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Director Admin'], ['Director:create']));
	}
	
	public function canShow() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Director Admin'], ['Director:show']))
			return true;
		return false;
	}
	
	public function canUpdate() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Director Admin'], ['Director:edit']))
			return true;
		return false;
	}
	
	public function canDelete() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Director Admin'], ['Director:delete']))
			return true;
		return false;
	}
	
	/**
	* Relationships
	*/
	
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
