<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
	/**
	* $show_authorize_flag
	* 0                         => all
	* 1                         => show mine only
	* 2                         => if i'm a head of ou, show all under my ou
	* 3                         => if i'm a head of ou, show all under my ou and other entries under his ou's children
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
		'user_id',
	
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
			'user_id' => 'required',
		],
		'update' => [
			'user_id' => 'required',
	
		]
	];
	
	public static $rules = [];
	
	public static function setRules($name) {
	self::$rules = self::$_rules[$name];
	}
	
	/**
	* ACL
	*/
	
	public static function canList()  {
	return (auth()->user() && auth()->user()->ability(['Admin', 'Approval Admin'], ['Approval:list']));
	}
	
	public static function canCreate()  {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Approval Admin'], ['Approval:create']));
	}
	
	public function canShow() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Approval Admin'], ['Approval:show']))
		return true;
		return false;
	}
	
	public function canUpdate() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Approval Admin'], ['Approval:edit']))
			return true;
		return false;
	}
	
	public function canDelete() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Approval Admin'], ['Approval:delete']))
			return true;
		return false;
	}
	
	/**
	* Relationships
	*/
	
	// public function status()
	// {
	//     return $this->hasOne('Status');
	// }
	
	
	/**
	* Decorators
	*/
	
	public function getNameAttribute($value) {
		return $value;
	}
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
		
		self::saving(function($approval){
			$approval->user_id = auth()->user()->id;
			return $approval;
		});
	
	// self::created(function(){
	//     Cache::tags('Approval')->flush();
	// });
	
	// self::updated(function(){
	//     Cache::tags('Approval')->flush();
	// });
	
	// self::deleted(function(){
	//     Cache::tags('Approval')->flush();
	// });
	}
}
