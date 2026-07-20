<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
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
        	'question',
        	'answer',
        	'category_id'
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
            'question'      => 'required',
            'answer'        => 'required',
            'category_id'   => 'required',
        	],
        	'update' => [
            'question'      => 'required',
            'answer'        => 'required',
            'category_id'   => 'required',
        	]
   ];

   public static $rules = [];

   public static function setRules($name) {
        	self::$rules = self::$_rules[$name];
   }

   /**
   * Relationships
   */

   public function category() {
        	return $this->belongsTo('App\HelpCategory', 'category_id');
   }

   public function user() {
      	return $this->belongsTo('App\User');
   }
}
