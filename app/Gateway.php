<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
	public static $methods = [
		'direct' => 'Langsung',
		'fpx'    => 'FPX',
		'ebpg'   => 'eBPG',
		'migs'   => 'MIGS',
		'm2u'    => 'm2u',
		'cimb'   => 'CIMB Clicks *Legacy',
		'sentry' => 'SENTRY',
		'lock'   => 'Gateway Lock',
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
		'type',
		'merchant_code',
		'private_key',
		'transaction_prefix',
		'endpoint_url',
		'daemon_url',
		'version',
		'active',
		'default',
		'organization_unit_id'
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
			'type'                 => 'required',
		],
		'update' => [
			'organization_unit_id' => 'required',
			'type'                 => 'required',
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
		return (auth()->check() && auth()->user()->ability(['Admin'], []));
	}
	
	public static function canCreate() {
		return (auth()->check() && auth()->user()->ability(['Admin'], []));
	}
	
	public function canShow() {
		return (auth()->check() && auth()->user()->ability(['Admin'], []));
	}
	
	public function canUpdate() {
		return (auth()->check() && auth()->user()->ability(['Admin'], []));
	}
	
	public function canDelete() {
		return (auth()->check() && auth()->user()->ability(['Admin'], []));
	}
	
	/**
	* Relationships
	*/
	
	public function agency() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}
}
