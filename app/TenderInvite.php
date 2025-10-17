<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenderInvite extends Model
{
   protected $table = 'tender_invites';
   public $timestamps = false;

   protected $fillable = [
        	'vendor_id',
        	'tender_id'
   ];

   public function vendor() {
        	return $this->belongsTo('App\Vendor');
   }

   public function tender() {
        	return $this->belongsTo('App\Tender');
   }
}
