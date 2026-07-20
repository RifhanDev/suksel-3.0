<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
	protected $fillable = [
		'token',
		'ip',
		'user_agent',
		'referer'
	];
	
	public function setToken() {
		$set = false;
		while ($set == false) {
			$token = str_random('24');
			if (self::whereToken($token)->count() == 0) $set = true;
		}
		$this->token = $token;
	}
	
	public static function getCount() {
		return self::orderBy('id', 'desc')->limit(1)->first()->id;
	}
	
	public static function boot() {
		parent::boot();
		
		self::saving(function ($visit) {
			$visit->setToken();
		});
	}
	
	public function setUpdatedAtAttribute($value) {
		return null;
	}
}
