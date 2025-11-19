<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;

class OrganizationUnit extends Node
{
   // Add your validation rules here
   static $rules = [
        	'store' => [
            'name'      => 'required',
            'type_id'   => 'numeric|required',
            'parent_id' => 'numeric'
        	],
        	'update' => [
            'name'      => 'required',
            'type_id'   => 'numeric|required',
            'parent_id' => 'numeric|nullable'
        	]
   ];

   // Don't forget to fill this array
   protected $fillable = [
        	'name',
        	'address',
        	'tel',
        	'email',
        	'user_id',
        	'type_id',
        	'confirmation_agency'
   ];

	public static function canList() {
		return true;
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin'], ['OrganizationUnit:create']));
	}
	
	public function canShow()
	{
		return true;
	}
	
	public function canUpdate()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['OrganizationUnit:edit']));
	}
	
	public function canDelete()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['OrganizationUnit:delete']));
	}
	
	/**
	* Relationships
	*/
	
	public function type() {
		return $this->belongsTo('App\OrganizationType', 'type_id');
	}
	
	public function user() {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}
	
	public function users() {
		return $this->hasMany('App\User', 'organization_unit_id', 'id');
	}
	
	public function tenders() {
		return $this->hasMany('App\Tender', 'organization_unit_id', 'id');
	}
	
	public function news() {
		return $this->hasMany('App\News', 'organization_unit_id', 'id');
	}
	
	public function blacklists() {
		return $this->hasMany('App\VendorBlacklsit', 'organization_unit_id', 'id');
	}
	
	public function gateways() {
		return $this->hasMany('App\Gateway');
	}
	
	public function activeGateway() {
		return $this->hasMany('App\Gateway')->whereIn('type', ['fpx', 'ebpg', 'migs'])->whereActive(1)->whereDefault(0);
	}
	
	public function scopeVendorList($q) {
		return $q->where('confirmation_agency', 1);
	}
	
	public function getGmapAddressAttribute() {
		return str_replace(' ', '+', str_replace("\n", " ", $this->address));
	}
	
	public function getGmapUrlAttribute() {
		return "https://www.google.com.my/maps?q=" . $this->gmap_address;
	}
	
	public function getGmapSrcAttribute() {
		return "//maps.googleapis.com/maps/api/staticmap?center=" . urlencode($this->gmap_address) . "&zoom=14&scale=2&size=450x250&maptype=roadmap&sensor=false&format=png&visual_refresh=true&markers=size:mid|color:red";
	}
	
	public function getIsGatewayLockedAttribute() {
		return $this->gateways()->whereType('lock')->whereActive(1)->count() > 0;
	}
	
	public static function boot() {
		parent::boot();
		
		self::created(function () {
			cache()->tags('OrganizationUnit')->flush();
		});
		
		self::updated(function () {
			cache()->tags('OrganizationUnit')->flush();
		});
		
		self::deleted(function () {
			cache()->tags('OrganizationUnit')->flush();
		});
	}
}
