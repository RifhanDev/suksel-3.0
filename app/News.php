<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
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
     	'title',
     	'notification',
     	'organization_unit_id',
     	'tender_id',
     	'published_at',
     	'publish'
   ];

 	protected $hidden = [
 	];

    /**
    * Validation Rules
    */
 	private static $_rules = [
     	'store' => [
			'organization_unit_id' => 'required',
			'title'                => 'required',
			'notification'         => 'required',
     	],
     'update' => [
			'organization_unit_id' => 'required',
			'title'                => 'required',
			'notification'         => 'required',
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
		return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['']));
	}
	
	public static function canCreate()  {
		if(auth()->check()) {
		if(auth()->user()->hasRole('Vendor')) {
			return false;
		} else {
			return true;
		}
		} else {
			return false;
		}
	}
	
	public function canShow() {
		if(auth()->check()) {
			if(auth()->user()->hasRole('Admin')) {
				return true;
			} elseif(auth()->user()->ability(['Agency Admin', 'Agency User'], []) && auth()->user()->organization_unit_id == $this->organization_unit_id) {
				return true;
			} else {
				return $this->publish;
			}
		} else {
			return $this->publish;
		}
	}
	
	public function canUpdate() {
		if(auth()->check()) {
			if(auth()->user()->hasRole('Admin')) {
				return true;
			} elseif(auth()->user()->ability(['Agency Admin', 'Agency User'], []) && auth()->user()->organization_unit_id == $this->organization_unit_id) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function canDelete()  {
		if(auth()->check()) {
			if(auth()->user()->hasRole('Admin')) {
				return true;
			} elseif(auth()->user()->ability(['Agency Admin', 'Agency User'], ['']) && auth()->user()->organization_unit_id == $this->organization_unit_id) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function agency() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}
	
	public function tender() {
		return $this->belongsTo('App\Tender');
	}

   public function scopeFeatured($q) {
      return $q->where('featured', 1);
   }

   public static function boot() {
		parent::boot();
		
		self::created(function(){
			cache()->tags('Notification')->flush();
		});
		
		self::updated(function(){
			cache()->tags('Notification')->flush();
		});
		
		self::deleted(function(){
			cache()->tags('Notification')->flush();
		});
   }
}
