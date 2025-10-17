<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
	use \Venturecraft\Revisionable\RevisionableTrait;
	
   static $type = [
		'mof'    => 'MOF',
		'cidb-g' => 'CIDB (Gred)',
		'cidb-c' => 'CIDB',
		'pkk'    => 'PKK',
		null     => '',
		''       => '',
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
        	'code',
        	'name',
        	'type'
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
            'code' => 'required|unique:codes',
            'name' => 'required',
            'type' => 'required',
        	],
        	'update' => [
            'code' => 'required',
            'name' => 'required',
            'type' => 'required',
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
        	return (auth()->user() && auth()->user()->ability(['Admin', 'CertificationCode Admin'], ['CertificationCode:list']));
   }

   public static function canCreate() {
        	return (auth()->user() && auth()->user()->ability(['Admin', 'CertificationCode Admin'], ['CertificationCode:create']));
   }

   public function canShow() {
        
        	$user = auth()->user();
        	if(auth()->user() && auth()->user()->ability(['Admin', 'CertificationCode Admin'], ['CertificationCode:show']))
            return true;
        	return false;
   }

   public function canUpdate() {
        
        	$user = auth()->user();
        	if(auth()->user() && auth()->user()->ability(['Admin', 'CertificationCode Admin'], ['CertificationCode:edit']))
            return true;
        	return false;
   }

   public function canDelete() {
        
        	$user = auth()->user();
        	if(auth()->user() && auth()->user()->ability(['Admin', 'CertificationCode Admin'], ['CertificationCode:delete']))
            return true;
        	return false;
   }

   public static function typeExists($type) {
        	return in_array($type, array_keys(self::$type));
   }

   public function getLabelAttribute() {
        	return sprintf("(%s) %s", $this->code, $this->name);
   }

   public function getLabel2Attribute() {
        	return sprintf("<b>%s</b> %s", $this->code, $this->name);
   }

   public static function boot() {
        	
        	parent::boot();

        	self::created(function(){
            cache()->tags('Code')->flush();
        	});

        	self::updated(function(){
            cache()->tags('Code')->flush();
        	});

        	self::deleted(function(){
            cache()->tags('Code')->flush();
        	});

        	static::saving(function ($model) {
            $model->preSave();
        	});
        
        	static::saved(function ($model) {
            $model->postSave();
        	});

        	static::deleted(function ($model) {
            $model->preSave();
            $model->postDelete();
        	});
   }
}
