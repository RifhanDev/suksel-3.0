<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{

	/**
	* Fillable columns
	*/
	protected $fillable = [
		'name',
		'user_id',
		'uploadable_type',
		'uploadable_id',
		'token',
		'type',
		'size',
		'url',
		'path',
		'public',
		'label',
	];
	
	public function getPath() {
		return $this->path . $this->name;
	}
	
	public function getUrl() {
		return $this->url . '/' . $this->name;
	}
	
	public function getPublicUrl() {
		return base_url() . $this->token . $this->name;
	}
	
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
	static $_rules = [
		'store' => [
			'uploadable_type' => 'required',
			'name'            => 'required',
			'type'            => 'required',
			'size'            => 'required',
			'url'             => 'required',
			'path'            => 'required',
		]
	];
	
	static $rules = [];
	
	public static function setRules($name) {
		self::$rules = self::$_rules[$name];
	}
	
	/**
	* ACL
	*/
	
	public static function canCreate() {
		return true;
	}
	
	public function canDelete() {
		$user = auth()->user();
		if ($user && $user->hasRole('Admin', 'Upload Admin')) {
			return true;
		}
		
		if ($user && $this->user_id === $user->id) {
			return true;
		}
		return true;
	}
	
	/**
	* Relationships
	*/
	
	public function uploadable() {
		return $this->morphTo();
	}
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
	parent::boot();
	
	self::creating(function ($data) {
		if(auth()->user())
			$data->user_id = auth()->user()->id;
		else
			$data->user_id = 1;
		});
		
		self::created(function () {
			cache()->tags('Upload')->flush();
		});
		
		self::updated(function () {
			cache()->tags('Upload')->flush();
		});
		
		self::deleted(function () {
			cache()->tags('Upload')->flush();
		});
		
		self::deleting(function ($data) {
			File::deleteDirectory($data->path);
			return true;
		});
	}
	
	/**
	* Decorators
	*/
	
	public function getSizeAttribute($bytes) {
		$decimals = 2;
		$size     = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$factor   = floor((strlen($bytes)-1)/3);
		return sprintf("%.{$decimals}f", $bytes/pow(1024, $factor)).' '.$size[$factor];
	}
	
	public static function updateAdvanceUploads($id) {
		$token = Input::get('_token');
		if($token) {
			foreach (self::where('token', $token)->get() as $upload) {
				$upload->uploadable_id = $id;
				$upload->save();
			}
		}
	}
	
	public function previewUrl() {
	
	}
	
	public function fileUrl() {
	
	}
}
