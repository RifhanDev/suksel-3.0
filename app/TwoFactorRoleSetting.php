<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorRoleSetting extends Model
{
   protected $table = 'two_factor_role_settings';

   protected $fillable = [
      'role_id',
      'required',
      'updated_by',
   ];

   protected function casts(): array
   {
      return [
         'required' => 'boolean',
      ];
   }

   public function role()
   {
      return $this->belongsTo('App\Role', 'role_id');
   }

   /**
    * Role ids that currently require 2FA. Absence of a row means "not required",
    * so only rows explicitly flagged are returned.
    */
   public static function requiredRoleIds(): array
   {
      return self::where('required', true)->pluck('role_id')->all();
   }
}
