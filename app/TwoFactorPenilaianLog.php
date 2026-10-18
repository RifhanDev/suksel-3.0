<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFactorPenilaianLog extends Model
{
   protected $table = 'two_factor_penilaian_logs';

   public $timestamps = false;

   protected $fillable = [
      'user_id',
      'tender_id',
      'peringkat',
      'kaedah',
      'ip_address',
      'user_agent',
      'created_at',
   ];

   protected function casts(): array
   {
      return [
         'created_at' => 'datetime',
      ];
   }

   public function user()
   {
      return $this->belongsTo('App\User', 'user_id');
   }

   public function tender()
   {
      return $this->belongsTo('App\Tender', 'tender_id');
   }
}
