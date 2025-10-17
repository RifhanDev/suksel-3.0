<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpxBank extends Model
{

 	protected $fillable = [
		'code',
		'name',
		'display_name',
		'type',
		'status'
 	];

    /*
     * Scopes
     */

	public function scopeActive($q)	{
		return $q->whereStatus('active');
	}

	public function scopeType($q, $type)	{
		return $q->whereType($type);
	}

	public static function canList()	{
		return true;
	}

	public static function canCreate() {
		return (Auth::user() && Auth::user()->ability(['Admin'], ['FpxBank:create']));
	}

	public function canShow()	{
		return true;
	}

	public function canUpdate() {
		return (Auth::user() && Auth::user()->ability(['Admin'], ['FpxBank:edit']));
	}

	public function canDelete() {
		return (Auth::user() && Auth::user()->ability(['Admin'], ['FpxBank:delete']));
	}

	/**
	* Relationships
	*/


	/*
	* Boot
	*/
	public static function boot() {
		parent::boot();
	}
}
