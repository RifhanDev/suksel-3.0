<?php

namespace App\Models;

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
        	return $this->belongsTo(Vendor::class);
   }

   public function tender() {
        	return $this->belongsTo(Tender::class);
   }
}
