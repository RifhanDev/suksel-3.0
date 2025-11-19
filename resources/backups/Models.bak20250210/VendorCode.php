<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCode extends Model
{
   protected $table = 'code_vendor';
   public $timestamps = false;

   public static $rule = array(
		'and' => ' <b><i>DAN</i></b> ',
		'or'  => ' <b><i>ATAU</i></b> '
   );

   protected $fillable = [
     	'code_id',
     	'vendor_id',
     	'code_type'
   ];

   public function code() {
		return $this->belongsTo('App\Code');
   }

   public function vendor() {
     	return $this->belongsTo('App\Vendor');
   }

   public function parent() {
     	return $this->belongsTo('App\VendorCode', 'parent_id');
   }

   public function children() {
     	return $this->hasMany('App\VendorCode', 'parent_id');
   }
}
