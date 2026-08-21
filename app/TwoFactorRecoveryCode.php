<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorRecoveryCode extends Model
{
   protected $table = 'two_factor_recovery_codes';

   public $timestamps = false;

   protected $fillable = [
      'user_id',
      'code_hash',
      'used_at',
      'created_at',
   ];

   protected $hidden = ['code_hash'];

   protected function casts(): array
   {
      return [
         'used_at'    => 'datetime',
         'created_at' => 'datetime',
      ];
   }

   public function user()
   {
      return $this->belongsTo('App\User', 'user_id');
   }

   public function scopeUnused($query)
   {
      return $query->whereNull('used_at');
   }
}
