<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request as Input;
use Carbon\Carbon;

class CodeRequest extends Model
{
 	static $statuses = [
			'pending'  => 'Belum Diproses',
			'approved' => 'Lulus',
			'rejected' => 'Ditolak'
 	];

 	static $types = [
			'mof'      => 'MOF',
			'cidb'     => 'CIDB',
			'district' => 'Daerah',
			'email'    => 'Alamat Emel'
 	];

 	static $files = [
			'mof'      => ['mof_bumiputera', 'mof'],
			'cidb'     => ['cidb', 'cidb_bumiputera'],
			'district' => ['daerah'],
			'email'    => ['ssm', 'ic', 'auth']
 	];

	static $file_names = [
			'sijil_cidb'            => 'Sijil CIDB & SPKK',
			'sijil_cidb_bumiputera' => 'Sijil Bumiputera PKK',
			'sijil_pkk_bumiputera'  => 'Sijil Bumiputera PKK',
			'sijil_mof'             => 'Sijil MOF',
			'sijil_mof_bumiputera'  => 'Sijil Bumiputera MOF',
			'sijil_bumiputera'      => 'Sijil Bumiputera',
			'sijil_daerah'          => 'Dokumen Perubahan Daerah',
			'sijil_ssm'             => 'Salinan Sijil SSM',
			'sijil_ic'              => 'Salinan Kad Pengenalan Pemilik / Pengarah',
			'sijil_auth'            => 'Surat Kebenaran'
 	];

 	/**
 	* $show_authorize_flag
 	* 0 => all
 	* 1 => show mine only
 	* 2 => if i'm a head of ou, show all under my ou
 	* 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
 	*/
 	static $show_authorize_flag = 0;

 	/**
 	* $update_authorize_flag
 	* 0 => all
 	* 1 => show mine only
 	* 2 => if i'm a head of ou, show all under my ou
 	* 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
 	*/
 	static $update_authorize_flag = 0;

 	/**
 	* $delete_authorize_flag
 	* 0 => all
 	* 1 => show mine only
 	* 2 => if i'm a head of ou, show all under my ou
 	* 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
 	*/
 	static $delete_authorize_flag = 0;

 	/**
 	* Fillable columns
 	*/
 	protected $fillable = [
     	'type',
     	'data',
     	'user_id',
     	'vendor_id',
     	'approval_id',
     	'rejection_reason',
     	'rejection_template_id',
     	'approved_at',
     	'status'
 	];

 	/**
 	* These attributes excluded from the model's JSON form.
 	* @var array
 	*/
 	protected $hidden = [
 		// 'password'
 	];

 	/**
 	* Validation Rules
 	*/
 	private static $_rules = [
     	'store' => [
			'type'      => 'required',
			'data'      => 'required',
			'vendor_id' => 'required',
			'user_id'   => 'required',
     	],
     	'update' => [
			'type'      => 'required',
			'data'      => 'required',
			'vendor_id' => 'required',
			'user_id'   => 'required',
     	]
 	];

 	public static $rules = [];

 	public static function setRules($name) {
     	self::$rules = self::$_rules[$name];
 	}

 	/**
 	* ACL
 	*/

 	public static function canList() {


     	if(auth()->check()) {
         $user = auth()->user();

         if($user->ability(['Admin', 'Registration Assessor'], ['CodeRequest:list'])) {
             	return true;
         } elseif($user->hasRole('Vendor') && $user->vendor_id == request()->vendor) {
             	return true;
         } else {
             	return false;
         }
     	} else {
         return false;
     	}
 	}

 	public static function canCreate() {
     	if(auth()->check()) {
         $user = auth()->user();

         if($user->ability(['Admin', 'Registration Assessor'], ['CodeRequest:create'])) {
            return true;
         } elseif($user->hasRole('Vendor') && $user->vendor_id == request()->vendor) {
            return self::whereVendorId($user->vendor_id)->whereStatus('pending')->whereType('mof')->count() == 0
               || self::whereVendorId($user->vendor_id)->whereStatus('pending')->whereType('cidb')->count() == 0
               || self::whereVendorId($user->vendor_id)->whereStatus('pending')->whereType('district')->count() == 0
               || self::whereVendorId($user->vendor_id)->whereStatus('pending')->whereType('email')->count() == 0;
         } else {
             return false;
         }
     	} else {
         return false;
     	}
 	}

 	public static function canCreateFor($vendor_id, $type) {
     	return CodeRequest::whereVendorId($vendor_id)->whereStatus('pending')->whereType($type)->count() == 0;
 	}

 	public function canShow() {
     if(auth()->check()) {
         $user = auth()->user();

         if($user->ability(['Admin', 'Registration Assessor'], ['CodeRequest:show'])) {
             	return true;
         } elseif($user->hasRole('Vendor') && $user->vendor_id == request()->vendor) {
             	return true;
         } else {
             	return false;
         }
     	} else {
         return false;
     	}
 	}

	public function canUpdate() {
		return false;
	}
	
	public function canDelete() {
		if(auth()->check()) {
			$user = auth()->user();
		
			if($user->ability(['Admin', 'Registration Assessor'], [])) {
				return true;
			} elseif($user->hasRole('Vendor') && $user->vendor_id == request()->vendor && $this->status == 'pending') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}    
	}
	
	public function canProcess() {
		if(auth()->check()) {
			$user = auth()->user();
		
			if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve', 'Vendor:reject']) && $this->status == 'pending') {
				return true;
			} else {
				return false;
			}
			} else {
				return false;
		}
	}
	
	public static function availableTypes($vendor_id) {
		
		$vendor = Vendor::find($vendor_id);
		
		if($vendor) {
			$types = [];
			
			if( $vendor->codeChanges()->whereStatus('pending')->whereType('mof')->count() == 0)      $types['mof']      = 'MOF';
			if( $vendor->codeChanges()->whereStatus('pending')->whereType('cidb')->count() == 0)     $types['cidb']     = 'CIDB';
			if( $vendor->codeChanges()->whereStatus('pending')->whereType('district')->count() == 0) $types['district'] = 'Daerah';
			if( $vendor->codeChanges()->whereStatus('pending')->whereType('email')->count() == 0)    $types['email']    = 'Alamat Emel';
			
			return $types;
		} else {
			return [];
		}
	}
	
	public static function pending() {
		$query = self::whereStatus('pending');
		return $query;
	}
	
	public static function pendingCount() {
		return self::pending()->count();
	}
	
	public function getDataAttribute($value) {
		return unserialize($value);
	}
	
	public function getMofDateRangeAttribute() {
		$start_date = isset($this->data['mof_start_date']) ? $this->data['mof_start_date'] : $this->vendor->mof_start_date;
		$start_date = Carbon::parse($start_date)->format('j M Y');
		
		$end_date = isset($this->data['mof_end_date']) ? $this->data['mof_end_date'] : $this->vendor->mof_end_date;
		$end_date = Carbon::parse($end_date)->format('j M Y');
		
		return implode(' - ', [$start_date, $end_date]);
	}
	
	public function getMofCodesAttribute() {
		if(isset($this->data['mof_codes']) && count($this->data['mof_codes']) > 0) {
			return Code::whereIn('id', $this->data['mof_codes'])->orderBy('code', 'asc')->get();
		} else {
			return null;
		}
	}
	
	public function getCidbDateRangeAttribute() {
		$start_date = isset($this->data['cidb_start_date']) ? $this->data['cidb_start_date'] : $this->vendor->cidb_start_date;
		$start_date = Carbon::parse($start_date)->format('j M Y');
		
		$end_date = isset($this->data['cidb_end_date']) ? $this->data['cidb_end_date'] : $this->vendor->cidb_end_date;
		$end_date = Carbon::parse($end_date)->format('j M Y');
		
		return implode(' - ', [$start_date, $end_date]);
	}
	
	public function getCidbCodesAttribute() {
		if(isset($this->data['cidb_codes']) && count($this->data['cidb_codes']) > 0) {
			return Code::whereIn('id', $this->data['cidb_codes'])->orderBy('code', 'asc')->get();
		} else {
			return null;
		}
	}
	
	public function getCidbGradeAttribute() {
		if(isset($this->data['cidb_grade_id'])) {
			return Code::where('id', $this->data['cidb_grade_id'])->first();
		} else {
			return null;
		}
	}
	
	/**
	* Relationships
	*/
	
	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public function vendor() {
		return $this->belongsTo('App\Vendor');
	}
	
	public function files() {
		return $this->morphMany('App\Upload', 'uploadable');
	}
	
	public function processData($data) {
	
		if(isset($data['type']) && in_array($data['type'], ['mof', 'cidb', 'district', 'email'])) {
			$this->type     = $data['type'];
			$this->status   = 'pending';
		
			if($this->type == 'mof') {
				$this->processMof($data);
			}
		
			if($this->type == 'cidb') {
				$this->processCidb($data);
			}
			
			if($this->type == 'district') {
				$this->processDistrict($data);
			}
			
			if($this->type == 'email'){ 
				$this->processEmail($data);
			}
		}
	}
	
	public function processEmail($data) {
		unset($data['sijil_ssm']);
		unset($data['sijil_ic']);
		unset($data['sijil_auth']);
		$this->data = serialize($data);
	}
	
	public function processDistrict($data) {
		unset($data['sijil_daerah']);
		$this->data = serialize($data);
	}
	
	public function processMof($data) {
		$data['mof_bumi'] = !!Input::get('mof_bumi', false);
	
		if(isset($data['mof_start_date'])) {
			$data['mof_start_date'] = Carbon::parse($data['mof_start_date'])->format('Y-m-d');
		}
		
		if(isset($data['mof_end_date'])) {
			$data['mof_end_date'] = Carbon::parse($data['mof_end_date'])->format('Y-m-d');
		}
	
		unset($data['sijil_mof']);
		unset($data['sijil_mof_bumiputera']);
		
		$this->data = serialize($data);
	}
	
	public function processCidb($data) {
		$data['cidb_bumi'] = !!Input::get('cidb_bumi', false);    
		
		if(isset($data['cidb_start_date'])) {
			$data['cidb_start_date'] = Carbon::parse($data['cidb_start_date'])->format('Y-m-d');
		}
		
		if(isset($data['cidb_end_date'])) {
			$data['cidb_end_date'] = Carbon::parse($data['cidb_end_date'])->format('Y-m-d');
		}
		
		unset($data['sijil_cidb']);
		unset($data['sijil_cidb_bumiputera']);
		
		$this->data = serialize($data);
	}
	
	public function updateData() {

		if($this->type == 'mof') {
			if(isset($this->data['mof_ref_no']))
				$this->vendor->mof_ref_no = $this->data['mof_ref_no'];
			
			if(isset($this->data['bumiputera_company']))
				$this->vendor->mof_bumi = !!$this->data['bumiputera_company'];
			
			if(isset($this->data['mof_bumi']))
				$this->vendor->mof_bumi = !!$this->data['mof_bumi'];
			
			if(isset($this->data['mof_start_date']))
				$this->vendor->mof_start_date = $this->data['mof_start_date'];
			
			if(isset($this->data['mof_end_date']))
				$this->vendor->mof_end_date = $this->data['mof_end_date'];
			
			if(isset($this->data['mof_codes'])) {
				$new_mof_codes  = $this->data['mof_codes'];
				$old_mof_codes  = $this->vendor->vendorCodes()->where('code_type', 'mof')->pluck('code_id');
				
				if(!is_array($old_mof_codes))
					$old_mof_codes = $old_mof_codes->toArray();

				if(!is_array($new_mof_codes))
					$new_mof_codes = $new_mof_codes->toArray();

				$keep_mof_codes = array_intersect($old_mof_codes, $new_mof_codes);
				$del_mof_codes  = array_diff($old_mof_codes, $keep_mof_codes);
				$save_mof_codes = array_diff($new_mof_codes, $keep_mof_codes);
				
				$this->vendor->vendorCodes()->whereIn('code_id', $del_mof_codes)->delete();
				
				foreach($save_mof_codes as $code) {
					$this->vendor->vendorCodes()->save(new VendorCode([
					'code_id' => $code,
					'code_type' => 'mof'
					]));
				}
			}
		}
	
		if($this->type == 'cidb') {
			if(isset($this->data['cidb_ref_no']))
				$this->vendor->cidb_ref_no = $this->data['cidb_ref_no'];
			
			if(isset($this->data['cidb_bumi']))
				$this->vendor->cidb_bumi = !!$this->data['cidb_bumi'];
			
			if(isset($this->data['cidb_start_date']))
				$this->vendor->cidb_start_date = $this->data['cidb_start_date'];
			
			if(isset($this->data['cidb_end_date']))
				$this->vendor->cidb_end_date = $this->data['cidb_end_date'];
			
			if(isset($this->data['deleted_cidb_group']))
				$this->vendor->deleteCidbCodes($this->data['deleted_cidb_group']);
			
			if(isset($this->data['cidb_group']))
				$this->vendor->processCidbCodes($this->data['cidb_group']);
		}
	
		if($this->type == 'district') {

			if($this->data['district_id'] <= 0)
			{
				$this->vendor->district_id = null;
				$this->vendor->state_id = $this->data['state_id'] ?? "";
			} else
			{
				$this->vendor->state_id = null;
				$this->vendor->district_id = $this->data['district_id'];
			}

			if( isset($this->data['address']) && $this->data['address'] != "" )
			{
				$this->vendor->address = $this->data['address'];
			}
		}
	
		if($this->type == 'email') {
			$user = $this->vendor->user;
			$user->unconfirmed_email_token = str_random(24);
			$user->unconfirmed_email = $this->data['email'];
			$user->username = $this->data['email'];
			$user->email = $this->data['email'];
			$user->save();
		}
	
		foreach($this->files as $file) {
			$new_file = $file->replicate();
			$new_file->cr_approved = 1;
			$new_file->uploadable_type = 'App\Vendor';
			$new_file->uploadable_id = $this->vendor_id;
			
			$this->vendor->uploads()->where('name', $new_file->name)->delete();
			$new_file->save();
		}
	
		$this->vendor->save();
	}
	
	public static function boot() {
		parent::boot();
		
		self::saved(function($request){
			Upload::setRules('store');
			$hash = md5($request->vendor->registration);
			
			foreach(self::$files[$request->type] as $name) {
				$file = Input::file('sijil_' . $name);
				
				if($file && $file->isValid()) {
					$upload = [];
					$upload['path']     = public_path() . '/uploads/' . $hash . '/';
					$upload['url']      = url('uploads/' . $hash);
					$upload['name']     = 'cr_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $request->vendor->registration) . '_' . $name . '.pdf';
					$upload['size']     = $file->getSize();
					$upload['type']     = $file->getMimeType();
					$upload['public']   = isset($datum['public']);
					$upload['label']    = self::$file_names['sijil_' . $name];
					
					$upload['cr_approved']      = 0;
					$upload['uploadable_type']  = 'App\CodeRequest';
					$upload['uploadable_id']    = $request->id;
					
					$file->move($upload['path'], $upload['name']);
					
					$new_upload = new Upload;
					$new_upload->fill($upload);
					$new_upload->save();
				}
			}
		});
	}
}
