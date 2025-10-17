<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderCode extends Model
{
   protected $table = 'code_tender';
   public $timestamps = false;

   protected $fillable = [
        	'code_id',
        	'tender_id',
        	'code_type',
        	'inner_rule',
        	'join_rule',
        	'order'
   ];

   public function code() {
        	return $this->belongsTo('App\Code');
   }

   public function tender() {
        	return $this->belongsTo('App\Tender');
   }
}
