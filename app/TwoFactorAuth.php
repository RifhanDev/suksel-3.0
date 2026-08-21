<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorAuth extends Model
{
   protected $table = 'two_factor_auths';

   protected $fillable = [
      'user_id',
      'secret',
      'confirmed_at',
      'required_since',
      'failed_attempts',
      'locked_until',
      'remember_token',
      'remember_expires_at',
   ];

   protected $hidden = ['secret', 'remember_token'];

   protected function casts(): array
   {
      return [
         'secret'              => 'encrypted',
         'confirmed_at'        => 'datetime',
         'required_since'      => 'datetime',
         'locked_until'        => 'datetime',
         'remember_expires_at' => 'datetime',
         'failed_attempts'     => 'integer',
      ];
   }

   public function user()
   {
      return $this->belongsTo('App\User', 'user_id');
   }

   /**
    * Enrolment is only complete once the user has proven they can generate a
    * valid code from the secret.
    */
   public function isConfirmed(): bool
   {
      return !is_null($this->confirmed_at);
   }

   public function isLocked(): bool
   {
      return $this->locked_until && $this->locked_until->isFuture();
   }
}
