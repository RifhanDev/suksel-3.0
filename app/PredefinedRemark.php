<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PredefinedRemark extends Model
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
		'type',
		'remark'
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
			'type' => 'required',
			'remark' => 'required',
		],
		'update' => [
			'type' => 'required',
			'remark' => 'required',
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
      return (auth()->user() && auth()->user()->hasRole('Admin'));
   }

	public static function canCreate() {
		return (auth()->user() && auth()->user()->hasRole('Admin'));
	}
	
	public function canShow() {
		return (auth()->user() && auth()->user()->hasRole('Admin'));
	}
	
	public function canUpdate() {
		return (auth()->user() && auth()->user()->hasRole('Admin'));
	}
	
	public function canDelete() {
		return (auth()->user() && auth()->user()->hasRole('Admin'));
	}

	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
		
		self::created(function () {
		//
		});
		
		self::updated(function () {
		//
		});
		
		self::deleted(function () {
		//
		});
	}
}
