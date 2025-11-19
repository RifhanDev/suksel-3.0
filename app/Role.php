<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
	/**
	 * Validation Rules
	 */
	static $_rules = [
		'store' => [
			'name' => 'required|unique:roles,name'
		],
		'update' => [
			'name' => 'required|unique:roles,name'
		]
	];

	static $rules = [];

	public static function setRules($name)
	{
		self::$rules = self::$_rules[$name];
	}

	// Don't forget to fill this array
	protected $fillable = [
		'name',
	];

	// Relationship (by zayid 10 nov 2022)

	public function perms()
	{
		return $this->belongsToMany('App\Permission', 'permission_role');
	}

	public static function canList()
	{
		return (auth()->user() && auth()->user()->ability(['Admin', 'Role Admin'], ['Role:list']));
	}

	public static function canCreate()
	{
		return (auth()->user() && auth()->user()->ability(['Admin', 'Role Admin'], ['Role:create']));
	}

	public function canShow()
	{
		return (auth()->user() && auth()->user()->ability(['Admin', 'Role Admin'], ['Role:show']));
	}

	public function canUpdate()
	{
		return (auth()->user() && auth()->user()->ability(['Admin', 'Role Admin'], ['Role:edit']));
	}

	public function canDelete()
	{
		return (auth()->user() && auth()->user()->ability(['Admin', 'Role Admin'], ['Role:delete']));
	}

	public function scopeAvailableRoles($q)
	{
		if (auth()->user()->hasRole('Agency Admin')) {
			return $q->whereIn('name', ['Agency User', 'Agency Finance']);
		} else {
			return $q;
		}
	}

	public static function boot()
	{
		parent::boot();

		self::created(function () {
			cache()->tags('Role')->flush();
		});

		self::updated(function () {
			cache()->tags('Role')->flush();
		});

		self::deleted(function () {
			cache()->tags('Role')->flush();
		});
	}

	public function updateUniques()
	{
		return true;
	}
}
