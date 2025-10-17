<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VendorBlacklist extends Model
{
   protected $table = 'vendor_blacklists';

   protected $fillable = [
		'reason',
		'start',
		'end',
		'vendor_id',
		'organization_unit_id',
		'status',
		'cancel_reason'
   ];

   public static $rules = array(
		'reason'               => 'required',
		'start'                => 'required|date|before:end',
		'end'                  => 'required|date|after:start',
		'organization_unit_id' => 'exists:organization_units,id',
		'vendor_id'            => 'exists:vendors,id'
   );

   public static $statuses = array(
		'active'    => 'Aktif',
		'cancelled' => 'Dibatalkan'
   );

   public static function canList() {
     	if(auth()->check() && auth()->user()->can('Blacklist:index')) {
         return true;
     	} else {
         return false;
     	}
   }

   public function canShow() {
     	return true;   
   }

   public static function canCreate() {
		if(auth()->check() && auth()->user()->can('Blacklist:create')) {
			return true;
		} else {
			return false;
		}
   }

	public function canUpdate() {
		if(auth()->check() && auth()->user()->can('Blacklist:update')) {
			return true;
		} else {
			return false;
		}
	}

   public function canDelete() {
		if(auth()->check() && auth()->user()->can('Blacklist:delete')) {
			return true;
		} else {
			return false;
		}
   }

	public function canCancel() {
		if(auth()->check() && auth()->user()->can('Blacklist:cancel') && $this->status == 'active') {
			return true;
		} else {
			return false;
		}
	}

	public function getStartDateAttribute() {
		if(!empty($this->start)) {
			return Carbon::parse($this->start)->format('j M Y');
		} else {
			return null;
		}
	}
	
	public function getEndDateAttribute() {
		if(!empty($this->end)) {
			return Carbon::parse($this->end)->format('j M Y');
		} else {
			return null;
		}
	}
	
	public function agency() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id', 'id');
	}
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public function file() {
		return $this->morphOne('App\Upload', 'uploadable');
	}
	
	public static function boot() {
		parent::boot();
	
		self::saved(function($blacklist){
			Upload::setRules('store');
			$hash = md5($blacklist->vendor->registration);
			
			$file = request()->file('file');
	
			if($file && $file->isValid()) {
				$name                      = 'blacklist_' . $blacklist->vendor->registration . '_' . $blacklist->id . '.pdf';
				Upload::where('name', $name)->delete();
	
				$upload                    = [];
				$upload['path']            = public_path() . '/uploads/' . $hash . '/';
				$upload['url']             = url('uploads/' . $hash);
				$upload['name']            = $name;
				$upload['size']            = $file->getSize();
				$upload['type']            = $file->getMimeType();
				$upload['public']          = isset($datum['public']);
				$upload['label']           = 'Lampiran Senarai Hitam';
				
				$upload['cr_approved']     = 0;
				$upload['uploadable_type'] = 'App\VendorBlacklist';
				$upload['uploadable_id']   = $blacklist->id;
	
				$file->move($upload['path'], $upload['name']);
	
				$new_upload                = new Upload;
				$new_upload->fill($upload);
				$new_upload->save();
			}
		});
	}
}
