<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Comment extends Model
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
		'user_id',
		'organization_unit_id',
		'name',
		'company_name',
		'email',
		'subject',
		'body',
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
			'email'                => 'email|required',
			'body'                 => 'required',
		
		],
		'update' => [
			'organization_unit_id' => 'required',
			'email'                => 'email|required',
			'body'                 => 'required',
		],
			'contact' => [
			'organization_unit_id' => 'required',
			'name'                 => 'required',
			'email'                => 'email|required',
			'body'                 => 'required'
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
		return (auth()->user() && auth()->user()->ability(['Admin', 'Comment Admin'], ['Comment:list']));
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Comment Admin'], ['Comment:create']));
	}
	
	public function canShow() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Comment Admin'], ['Comment:show']))
			return true;
		return false;
	}
	
	public function canUpdate() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Comment Admin'], ['Comment:edit']))
			return true;
		return false;
	}
	
	public function canDelete() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Comment Admin'], ['Comment:delete']))
			return true;
		return false;
	}
	
	public function agency() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id', 'id');
	}
	
	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public static function boot() {
		parent::boot();
		
		self::created(function(){
			Cache::tags('Comment')->flush();
		});
		
		self::updated(function(){
			Cache::tags('Comment')->flush();
		});
		
		self::deleted(function(){
			Cache::tags('Comment')->flush();
		});
	}
}
