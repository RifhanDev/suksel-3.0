<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
   public $timestamps = false;

   protected $fillable = [
      
      'action',
      'user_id',
      '3p_id'
   ];

   protected $dates 	 = ['created_at', 'updated_at'];

   public static function boot() {

      parent::boot();
     	static::creating( function ($model) {
         $model->setCreatedAt($model->freshTimestamp());
     	});
   }

   public function user() {
      return $this->belongsTo('App\User', 'user_id');
   }

   public function third_party() {
      return $this->belongsTo('App\User', '3p_id');
   }

   public static function log($user_id, $action, $third_party_id=null) {

     	if(array_key_exists($action, self::$types)) {

         $history = new self([
             	'action' => $action,
             	'user_id' => $user_id,
             	'3p_id' => $third_party_id
         ]);

         try {
             	return $history->save();   
         } catch (\Throwable $e) {
             	// Never block auth/profile flows on audit trail failures
             	// (e.g. legacy FK pointing at users1).
             	\Log::warning('UserHistory::log failed', [
             		'user_id' => $user_id,
             		'action' => $action,
             		'error' => $e->getMessage(),
             	]);

             	return false;
         }
     	}
   }



   public function getLabelAttribute() {
	  	if(array_key_exists($this->action, self::$types)) {
	      return self::$types[$this->action];
	  	} else {
	      boolean_icon(null);
	  	}
   }

   static $types = [
		'create'          => 'Didaftar Ke Dalam Sistem',
		'edit'            => 'Kemaskini Maklumat',
		'delete'          => 'Hapus Dari Sistem',
		'sign-in'         => 'Daftar Masuk',
		'sign-out'        => 'Daftar Keluar',
		'password-forget' => 'Permintaan Lupa Kata Laluan',
		'password-reset'  => 'Tukar Kata Laluan',
		'password-update' => 'Kemaskini Kata Laluan',
		'activate'        => 'Aktifkan Pengguna',
		'deactivate'      => 'Nyahaktif Pengguna'
   ];
}
