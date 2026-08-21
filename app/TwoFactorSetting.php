<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorSetting extends Model
{
   protected $table = 'two_factor_settings';

   protected $fillable = [
      'grace_period_days',
      'recovery_codes_count',
      'max_failed_attempts',
      'lockout_minutes',
      'remember_device_days',
      'updated_by',
   ];

   protected function casts(): array
   {
      return [
         'grace_period_days'    => 'integer',
         'recovery_codes_count' => 'integer',
         'max_failed_attempts'  => 'integer',
         'lockout_minutes'      => 'integer',
         'remember_device_days' => 'integer',
      ];
   }

   /**
    * Singleton accessor. The migration seeds row id=1; firstOrCreate keeps this
    * safe if the row is ever removed by hand.
    */
   public static function current(): self
   {
      return self::firstOrCreate(
         ['id' => 1],
         [
            'grace_period_days'    => 7,
            'recovery_codes_count' => 8,
            'max_failed_attempts'  => 5,
            'lockout_minutes'      => 5,
            'remember_device_days' => 30,
         ]
      );
   }
}
