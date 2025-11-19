<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Zizaco\Entrust\EntrustPermission;
use Shanmuga\LaravelEntrust\Models\EntrustPermission;

class Permission extends EntrustPermission
{

   /**
    * Validation Rules
   */
   static $_rules = [
        	'store' => [
            'name'         => 'required|unique:permissions,name',
            'display_name' => 'required',
            'group_name'
        	],
        	'update' => [
            'name'         => 'required|unique:permissions,name',
            'display_name' => 'required',
            'group_name'
        	]
   ];

   static $rules = [];

   public static function setRules($name) {
        	self::$rules = self::$_rules[$name];
   }

   // Don't forget to fill this array
   protected $fillable = [
        	'name',
        	'display_name',
        	'group_name',
        	'description'
   ];

   public static function canList() {
        	return (auth()->user() && auth()->user()->ability(['Admin', 'Permission Admin'], ['Permission:list']));
   }

   public static function canCreate() {
        	return (auth()->user() && auth()->user()->ability(['Admin', 'Permission Admin'], ['Permission:create']));
   }

   public function canShow() {
        	return (auth()->user() && auth()->user()->ability(['Admin', 'Permission Admin'], ['Permission:show']));
   }

   public function canUpdate() {
        	return (auth()->user() && auth()->user()->ability(['Admin', 'Permission Admin'], ['Permission:edit']));
   }

   public function canDelete() {
        	return (auth()->user() && auth()->user()->ability(['Admin', 'Permission Admin'], ['Permission:delete']));
   }

   public static function boot() {
        
        	parent::boot();

        	self::created(function () {
            cache()->tags('Permission')->flush();
        	});

        	self::updated(function () {
            cache()->tags('Permission')->flush();
        	});

        	self::deleted(function () {
            cache()->tags('Permission')->flush();
        	});
   }

   public function updateUniques() {
		return true;
	}
}
