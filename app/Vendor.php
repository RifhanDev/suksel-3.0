<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Libraries\UploadableTrait;
use App\Models\PetenderPerformance;
use \Venturecraft\Revisionable\RevisionableTrait;
use App\Upload;
use App\VendorCode;

class Vendor extends Model
{
	use UploadableTrait;
	use \Venturecraft\Revisionable\RevisionableTrait;
	
	static $show_authorize_flag   = 0;
	static $update_authorize_flag = 0;
	static $delete_authorize_flag = 0;
	
	/**
	* Fillable columns
	*/
	protected $fillable = [
		'registration',
		'name',
		'organization_type',
		'address',
		'tel',
		'fax',
		'website',
		'incorporation_date',
		'authorized_capital',
		'paidup_capital',
		'authorized_capital_currency',
		'paidup_capital_currency',
		'gst_no',
		'tax_no',
		'bumi_percentage',
		'nonbumi_percentage',
		'foreigner_percentage',
		'blacklisted_until',
		'blacklist_reason',
		'organization_unit_id',
		'mof_ref_no',
		'mof_start_date',
		'mof_end_date',
		'mof_bumi',
		'cidb_ref_no',
		'cidb_start_date',
		'cidb_end_date',
		'cidb_bumi',
		'cidb_grade_id',
		'cidb_grade_b_id',
		'cidb_grade_ce_id',
		'cidb_grade_me_id',
		'ssm_expiry',
		'submission_date',
		'district_id',
		'officer_name',
		'officer_designation',
		'officer_email',
		'certificate_generated_at',
		'officer_tel',
		'state_id',
		'rejection_template_id'
	];

	protected $casts = [ 'ssm_expiry'=>'date'];
	
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
	public static $_rules = [
		'store' => [
			'name'                 => 'required',
			'registration'         => 'required',
			'organization_type'    => 'required',
			'address'              => 'required',
			'tel'                  => 'required',
			'incorporation_date'   => 'required',
			'bumi_percentage'      => 'required',
			'nonbumi_percentage'   => 'required',
			'foreigner_percentage' => 'required'
		],
		'update' => [
			'name'                 => 'required',
			'organization_type'    => 'required',
			'address'              => 'required',
			'tel'                  => 'required',
			'incorporation_date'   => 'required',
			'bumi_percentage'      => 'required',
			'nonbumi_percentage'   => 'required',
			'foreigner_percentage' => 'required',
		],
		'blacklist' => [
			'blacklisted_until' => 'required',
			'blacklist_reason'  => 'required'
		],
		'mof' => [
			'mof_ref_no'   => 'required',
			'mof_end_date' => 'required',
			'mof_codes'    => 'required',
		],
		'cidb' => [
			'cidb_ref_no'     => 'required',
			'cidb_start_date' => 'required',
			'cidb_end_date'   => 'required'
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
		return (auth()->user() && auth()->user()->ability(['Admin', 'Registration Assessor', 'Agency Admin', 'Agency User'], ['Vendor:list']));
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin'], ['Vendor:create']));
	}
	
	public function canShow() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin', 'Registration Assessor'], ['Vendor:show', 'Vendor:approve', 'Vendor:reject']))
			return true;
		return false;
	}
	
	public function canUpdate() {
		$vendor = false;
		
		if(auth()->user()->vendor) {
			$vendor = auth()->user()->vendor_id == $this->id;
		}
	
		return auth()->user()->can('Vendor:edit') || $vendor;
	}
	
	public function canUpdate2() {
		return auth()->user()->can('Vendor:override');
	}
	
	public function canDelete() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin'], ['Vendor:delete']))
			return true;
		return false;
	}
	
	public function canBlacklist() {
		$user = auth()->user();
		if(auth()->user() && auth()->user()->ability(['Admin'], ['Vendor:blacklist']))
			return true;
		return false;
	}
	
	public function canMakeChanges() {
		if(!$this->approval_1_id)
			return false;
		return true;
	}
	
	public function canApprove() {
		return auth()->user()->can('Vendor:approve') && $this->completed && !$this->approval_1_id;
	}
	
	public function canConfirm() {
		return auth()->user()->can('Vendor:edit') && !$this->user->confirmed;
	}
	
	public function canParticipateInTenders() {
		return $this->approval_1_id
		&& $this->completed
		&& $this->registration_paid
		&& $this->valid()
		&& !$this->isBlacklisted();
	}
	
	public function scopeCanParticipate($q) {
		return $q->whereNotNull('approval_1_id')
			->where('completed', 1)
			->where('blacklisted_until', '<', date('Y-m-d'))
			->has('activeSubscription');
	}
	
	public function canCertificate() {
		$user = auth()->user();
		
		if($user->vendor_id == $this->id) {
			return !is_null($this->approval_1_id);
		}
		else {
			return $user->can('Vendor:certificate') && !is_null($this->approval_1_id);
		}
	}
	
	public function isBlacklisted() {
		return time() < Carbon::parse($this->blacklisted_until)->addDay()->timestamp;
	}
	
	public static function pendingRegistration() {
		return self::where('completed', 0);
	}
	
	public static function pendingRegistrationCount() {
		return self::pendingRegistration()->count();
	}
	
	public static function pendingNewApproval1() {
		return self::where('completed', 1)->whereNull('approval_1_id')->where('vendors.created_at', '>=', '2015-03-01 00:00:00');
	}
	
	public static function pendingNewApproval1Count() {
		return self::pendingNewApproval1()->count();
	}
	
	public static function pendingEditApproval1() {
		return self::where('completed', 1)
			->whereNotNull('approval_1_id')
			->whereHas('pendingChangeRequests', function($changeRequests){
				$changeRequests->whereNull('approval_1_id');
				$changeRequests->where('status', 'New');
			});
	}
	
	public static function pendingEditApproval1Count() {
		return self::pendingEditApproval1()->count();
	}
	
	public static function orgType($value) {
		self::where('organization_type', 'LIKE', "{$value}%");
	}
	
	public function getNewExpiryDates() {
		$expiry = $this->expiry_date;
		if(time() < strtotime($expiry)) {
			$start_date = strtotime($expiry);
		} else {
			$start_date = now();
		}
		return [date('Y-m-d', $start_date), date('Y-m-d', strtotime('+1 year', $start_date))];
	}
	
	public function valid() {
		return Carbon::parse($this->expiry_date)->addDay()->timestamp > time();
	}
	
	/**
	* Relationships
	*/
	
	public function remarks() {
		return $this->hasMany('App\Remark');
	}
	
	public function vendorCodes() {
		return $this->hasMany('App\VendorCode');
	}
	
	public function cidbGrades() {
		return $this->hasMany('App\VendorCode')->whereCodeType('cidb-g');
	}
	
	public function participations() {
		return $this->hasMany('App\TenderVendor');
	}
	
	public function blacklists() {
		return $this->hasMany('App\VendorBlacklist');
	}
	
	public function shareholders() {
		return $this->hasMany('App\Shareholder');
	}
	
	public function directors() {
		return $this->hasMany('App\Director');
	}
	
	public function contacts() {
		return $this->hasMany('App\Contact');
	}
	
	public function awards() {
		return $this->hasMany('App\Award');
	}
	
	public function products() {
		return $this->hasMany('App\Product');
	}
	
	public function assets() {
		return $this->hasMany('App\VendorAsset', 'vendor_id', 'id');
	}
	
	public function projects() {
		return $this->hasMany('App\Project');
	}
	
	public function transactions() {
		return $this->hasMany('App\Transaction');
	}
	
	public function subscriptions() {
		return $this->hasMany('App\Subscription');
	}
	
	public function agency() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}
	
	public function user() {
		return $this->hasOne('App\User', 'vendor_id');
	}
	
	public function registeredBy() {
		return $this->hasOne('App\User', 'vendor_id');
	}
	
	public function codeChanges() {
		return $this->hasMany('App\CodeRequest');
	}
	
	public function histories() {
		return $this->hasMany('App\VendorHistory', 'vendor_id');
	}

	public function petenderPerformances()
	{
		return $this -> hasMany(PetenderPerformance::class, 'vendor_id');
	}

	public function winningParticipations()
	{
		return $this -> participations() -> where('winner', 1);
	}
	
	/**
	* Decorators
	*/
	
	public static function hasRegistered($registration) {
		return self::where('registration', $registration)->first() ? true : false;
	}
	
	public function getIncorporationDateAttribute($value) {
		return date('d/m/Y', strtotime($value . ' 00:00:00'));
	}
	
	public function setIncorporationDateAttribute($value) {
		$parts = explode('/', $value);
		$date  = date('Y-m-d', strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0] . ' 00:00:00'));
		$this->attributes['incorporation_date'] = $date;
	}
	
	public function getMofDateRangeAttribute() {
		if(!empty($this->mof_start_date) && !empty($this->mof_end_date)) {
			return implode(' - ', [
				Carbon::parse($this->mof_start_date)->format('d M Y'),
				Carbon::parse($this->mof_end_date)->format('d M Y')
			]);
		} else {
			return null;
		}
	}
	
	public function getCidbDateRangeAttribute() {
		if(!empty($this->cidb_start_date) && !empty($this->cidb_end_date)) {
			return implode(' - ', [
				Carbon::parse($this->cidb_start_date)->format('d M Y'),
				Carbon::parse($this->cidb_end_date)->format('d M Y')
			]);
		} else {
			return null;
		}
	}
	
	public function getStatusAttribute() {
		if($this->isBlacklisted()) {
			return 'Disenarai Hitam';
		} elseif($this->canMakeChanges()) {
		if($this->activeSubscription) {
			return 'Aktif';
		} else {
			return 'Tiada Langganan Aktif';
		}
		} elseif(!$this->completed) {
			return 'Belum Selesai';
		} elseif(!$this->approval_1_id) {
			return 'Belum Diluluskan';
		}
		return null;
	}
	
	public function getBumiputeraCompanyAttribute() {
		return $this->mof_bumi || $this->cidb_bumi;
	}
	
	public function activeSubscription() {
		return $this->hasOne('App\Subscription')->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'));
	}
	
	public static function activeSubscriptionCount() {
		return self::has('activeSubscription')->count();
	}
	
	public static function nonActiveSubscriptionCount() {
		return self::has('activeSubscription', '<', 1)->count();
	}
	
	public static function getStatus($id) {
		$self = self::find($id);
		
		if($self) {
			return $self->status;
		} else {
			return null;
		}
	}
	
	public function processShareholders($data) {
		foreach ($data['id'] as $index => $value) {
			$name              = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$identity          = isset($data['identity'][$index]) ? $data['identity'][$index] : '';
			$nationality       = isset($data['nationality'][$index]) ? $data['nationality'][$index] : '';
			$bumiputera_status = isset($data['bumiputera_status'][$index]) ? $data['bumiputera_status'][$index] : '';
			if($value === 'false') {
				$this->shareholders()->save(new Shareholder([
					'name'              => $name,
					'identity'          => $identity,
					'nationality'       => $nationality,
					'bumiputera_status' => $bumiputera_status,
				]));
			} else {
				$shareholder                    = $this->shareholders()->find($data['id'][$index]);
				$shareholder->name              = $name;
				$shareholder->identity          = $identity;
				$shareholder->nationality       = $nationality;
				$shareholder->bumiputera_status = $bumiputera_status;
				$shareholder->save();
			}
		}
	}
	
	public function processDirectors($data) {
		foreach ($data['id'] as $index => $value) {
			$name           = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$identity       = isset($data['identity'][$index]) ? $data['identity'][$index] : '';
			$nationality    = isset($data['nationality'][$index]) ? $data['nationality'][$index] : '';
			$designation    = isset($data['designation'][$index]) ? $data['designation'][$index] : '';
			if($value === 'false') {
				$this->directors()->save(new Director([
					'name'              => $name,
					'identity'          => $identity,
					'nationality'       => $nationality,
					'designation'       => $designation,
				]));
			} else {
				$director = $this->directors()->find($data['id'][$index]);
				$director->name = $name;
				$director->identity = $identity;
				$director->nationality = $nationality;
				$director->designation = $designation;
				$director->save();
			}
		}
	}
	
	public function processContacts($data) {
		foreach ($data['id'] as $index => $value) {
			$name           = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$designation    = isset($data['designation'][$index]) ? $data['designation'][$index] : '';
			$nationality    = isset($data['nationality'][$index]) ? $data['nationality'][$index] : '';
			$status         = isset($data['status'][$index]) ? $data['status'][$index] : '';
			if($value === 'false') {
				$this->contacts()->save(new Contact([
					'name'           => $name,
					'designation'    => $designation,
					'nationality'    => $nationality,
					'status'         => $status,
				]));
			} else {
				$contact              = $this->contacts()->find($data['id'][$index]);
				$contact->name        = $name;
				$contact->designation = $designation;
				$contact->nationality = $nationality;
				$contact->status      = $status;
				$contact->save();
			}
		}
	}
	
	public function processAwards($data) {
		foreach ($data['id'] as $index => $value) {
			$name        = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$description = isset($data['description'][$index]) ? $data['description'][$index] : '';
			$by          = isset($data['by'][$index]) ? $data['by'][$index] : '';
			if($value === 'false') {
				$this->awards()->save(new Award([
					'name'           => $name,
					'description'    => $description,
					'by'    => $by,
				]));
			} else {
				$award              = $this->awards()->find($data['id'][$index]);
				$award->name        = $name;
				$award->description = $description;
				$award->by          = $by;
				$award->save();
			}
		}
	}
	
	public function processAssets($data) {
		foreach ($data['id'] as $index => $value) {
			$name = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$val  = isset($data['value'][$index]) ? $data['value'][$index] : '';
			if($value === 'false') {
				$this->assets()->save(new VendorAsset([
					'name'   => $name,
					'value'  => str_replace(',', '', $val)
				]));
			} else {
				$asset        = $this->assets()->find($data['id'][$index]);
				$asset->name  = $name;
				$asset->value = $val;
				$asset->save();
			}
		}
	}
	
	public function processProjects($data) {
		foreach ($data['id'] as $index => $value) {
			$name       = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$customer   = isset($data['customer'][$index]) ? $data['customer'][$index] : '';
			$period     = isset($data['period'][$index]) ? $data['period'][$index] : '';
			$done       = isset($data['done'][$index]) && $data['done'][$index] == 'true' ? true : '';
			$val        = isset($data['value'][$index]) ? $data['value'][$index] : '';
			if($value === 'false') {
				$this->projects()->save(new Project([
					'name'       => $name,
					'customer'   => $customer,
					'period'     => $period,
					'done'       => $done,
					'value'      => str_replace(',', '', $val)
				]));
			} else {
				$project           = $this->projects()->find($data['id'][$index]);
				$project->name     = $name;
				$project->customer = $customer;
				$project->period   = $period;
				$project->value    = $val;
				$project->done     = $done;
				$project->save();
			}
		}
	}
	
	public function processProducts($data) {
		foreach ($data['id'] as $index => $value) {
			$name               = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$description        = isset($data['description'][$index]) ? $data['description'][$index] : '';
			$implementations    = isset($data['implementations'][$index]) ? $data['implementations'][$index] : '';
			$by    = isset($data['by'][$index]) ? $data['by'][$index] : '';
			if($value === 'false') {
				$this->products()->save(new Product([
					'name'               => $name,
					'description'        => $description,
					'implementations'    => $implementations,
				]));
			} else {
				$product = $this->products()->find($data['id'][$index]);
				$product->name = $name;
				$product->description = $description;
				$product->implementations = $implementations;
				$product->save();
			}
		}
	}
	
	public function processAccounts($data) {
		foreach ($data['id'] as $index => $value) {
			$name           = isset($data['name'][$index]) ? $data['name'][$index] : '';
			$account_number = isset($data['account_number'][$index]) ? $data['account_number'][$index] : '';
			$bank_name      = isset($data['bank_name'][$index]) ? $data['bank_name'][$index] : '';
			$bank_address   = isset($data['bank_address'][$index]) ? $data['bank_address'][$index] : '';
			$swift_code     = isset($data['swift_code'][$index]) ? $data['swift_code'][$index] : '';
			if($value === 'false') {
				$this->accounts()->save(new Account([
					'name'           => $name,
					'account_number' => $account_number,
					'bank_name'      => $bank_name,
					'bank_address'   => $bank_address,
					'swift_code'     => $swift_code,
				]));
			} else {
				$account                 = $this->accounts()->find($data['id'][$index]);
				$account->name           = $name;
				$account->account_number = $account_number;
				$account->bank_name      = $bank_name;
				$account->bank_address   = $bank_address;
				$account->swift_code     = $swift_code;
				$account->save();
			}
		}
	}
	
	public function createUploads($file, $name, $label, $cr_approved = true) {
		$hash                    = md5($this->registration);
		$data                    = [];
		$data['label']           = $label || $name;
		$data['path']            = public_path().'/uploads/'.$hash.'/';
		$data['url']             = url('uploads/'.$hash);
		$data['name']            = $name;
		$data['type']            = $file->getMimeType();
		$data['size']            = $file->getSize();
		$data['uploadable_type'] = 'App\Vendor';
		$data['uploadable_id']   = $this->id;
		$data['cr_approved']     = $cr_approved;
		Upload::where('name', $name)->delete();
		$file->move($data['path'], $data['name']);
		$upload = new Upload;

		$upload->fill($data);
		$upload->save();
	}
	
	public function processVendorUploads() {
		Upload::setRules('store');
		foreach ([
			'mof'             => 'Sijil MOF',
			'cidb'            => 'Sijil CIDB & SPKK',
			'ssm'             => 'Sijil SSM',
			'mof_bumiputera'  => 'Sijil Bumiputera MOF',
			'cidb_bumiputera' => 'Sijil Bumiputera PKK'
		] as $file_name => $label) {
			$file = request()->file($file_name);
			if($file) {
				$this->createUploads($file, preg_replace('/[^a-zA-Z0-9]+/', '_', $this->registration) . '_' . $file_name  . '.pdf', $label);
			}
		}
		$files = request()->file('other_files');
		if($files) {
			$files = array_filter($files);
			foreach ($files as $file) {
				$file_name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
				$this->createUploads($file, $file_name);
			}
		}
	}
	
	public function processMofCodes($datum) {


		$old_mof_codes  = $this->vendorCodes()->where('code_type', 'mof')->pluck('code_id');
		$new_mof_codes  = $datum;


		if(!is_array($old_mof_codes))
			$old_mof_codes = $old_mof_codes->toArray();

		if(!is_array($new_mof_codes))
			$new_mof_codes = $new_mof_codes->toArray();

		$keep_mof_codes = array_intersect($old_mof_codes, $new_mof_codes);
		$del_mof_codes  = array_diff($old_mof_codes, $keep_mof_codes);
		$save_mof_codes = array_diff($new_mof_codes, $keep_mof_codes);
		$this->vendorCodes()->whereIn('code_id', $del_mof_codes)->delete();
		
		foreach($save_mof_codes as $code) {
			$this->vendorCodes()->save(new VendorCode([
				'code_id'   => $code,
				'code_type' => 'mof'
			]));
		}
	}
	
	public function processCidbCodes($datum) {

		foreach($datum as $key => $data) {

			$vc = null;

			if(!isset($data['code_id']) || empty($data['code_id']) || !isset($data['codes'])) continue;
			
			if(isset($data['id'])) {
				$vc = VendorCode::find($data['id']);
			}
			
			if(!isset($vc)) {
				$vc = new VendorCode;
				$vc->code_type  = 'cidb-g';
				$vc->vendor_id  = $this->id;
			}
			
			if($vc->code_id != $data['code_id']) $vc->code_id = $data['code_id'];
			$vc->save();
			
			$old_children   = $vc->children->pluck('code_id')->toArray();
			$keep_children  = array_intersect($old_children, $data['codes']);
			$del_children   = array_diff($old_children, $keep_children);
			$save_children  = array_diff($data['codes'], $keep_children);
			
			VendorCode::whereVendorId($this->id)->whereIn('code_id', $del_children)->delete();
			
			foreach($save_children as $code_id) {
				$code = new VendorCode;
				$code->parent_id    = $vc->id;
				$code->code_id      = $code_id;
				$code->code_type    = 'cidb';
				$code->vendor_id    = $this->id;
				$code->save();
			}
			
			// ensure unique codes is inserted.
			$code_ids           = VendorCode::whereParentId($vc->id)->pluck('code_id', 'id')->toArray();
			$unique_code_ids    = array_unique($code_ids);
			$del_code_ids       = array_diff_assoc($code_ids, $unique_code_ids);
			VendorCode::whereIn('id', array_keys($del_code_ids))->delete();
		}
	}
	
	public function deleteCidbCodes($datum) {
		foreach($datum as $id) {
			$vc = VendorCode::find($id);
			if($vc) $vc->delete();
		}
	}
	
	public function getMofCodesAttribute() {
		return $this->vendorCodes()->where('code_type', 'mof')->with(['code' => function($q){
			return $q->orderBy('code', 'asc');
		}])->get();
	}
	
	public function getCidbCodesAttribute() {
		return $this->vendorCodes()->where('code_type', 'cidb')->with(['code' => function($q){
			return $q->orderBy('code', 'asc');
		}])->get();
	}
	
	public function getRequireRenewalAttribute() {
		return $this->registration_paid && time() > Carbon::parse($this->expiry_date)->subDays(90)->timestamp;
	}
	
	public function getExpiryDateAttribute() {
		if($this->subscriptions()->count() > 0) {
			$last_sub = $this->subscriptions()->orderBy('end_date', 'desc')->first();
			return $last_sub->end_date;
		}
		else {
			return null;
		}
	}
	
	public function getRegistrationPaidAttribute() {
		return $this->subscriptions()->count() > 0;
	}
	
	public function getExpiredAttribute() {
		if($this->subscriptions()->count() > 0) {
			return Carbon::parse($this->expiry_date) < Carbon::now();
		}
		else {
			return true;
		}
	}
	
	public function getOfficerNameAttribute() {
		return isset($this->user) ? $this->user->name : '';
	}
	
	public function mofValid() {
		return time() < strtotime($this->mof_end_date);
	}
	
	public function cidbValid() {
		return time() < strtotime($this->cidb_end_date);
	}
	
	public function allValid() {
		return $this->mofValid() && $this->cidbValid();
	}
	
	public static function withCodes($codes, $code_type, $operator) {
	if($operator  == 'and') {
		$vendor_ids = [];
		
		foreach($codes as $code) {
			$vendors = VendorCode::whereCodeType($code_type)->where('code_id', $code)->groupBy('vendor_id')->pluck('vendor_id')->toArray();
			
			if(count($vendor_ids) == 0 ) {
				$vendor_ids = $vendors;
			} else {
				$vendor_ids = array_intersect($vendor_ids, $vendors);
			}
		}
		
		return $vendor_ids;
	}
	
		if($operator == 'or') {
			return VendorCode::whereCodeType($code_type)->whereIn('code_id', $codes)->groupBy('vendor_id')->pluck('vendor_id');
		}
	}
	
	public function hasFile($name) {
		$files = [
			$this->registration . '_' . $name  . '.pdf',
			'cr_' . $this->registration . '_' . $name . '.pdf'
		];
		
		return $this->uploads()->whereIn('name', $files)->count() > 0;
	}
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
	
		self::saving(function($vendor) {
			$vendor->authorized_capital = $vendor->authorized_capital ? $vendor->authorized_capital : '0.00';
			$vendor->paidup_capital = $vendor->paidup_capital ? $vendor->paidup_capital : '0.00';
			
			$data = request()->except('_method', '_token');
		
			if(count($data) > 0) {
				if(isset($data['district_id']) && $data['district_id'] <= 0) $vendor->district_id = null;
			
					if(isset($data['mof_ref_no'])) {
						$vendor->mof_ref_no = $data['mof_ref_no'];
					}
			
					if(isset($data['cidb_ref_no'])) {
						$vendor->cidb_ref_no = $data['cidb_ref_no'];
					}
			
					if(isset($data['mof_start_date'])) {
						if(!empty($data['mof_start_date'])) {
							$vendor->mof_start_date = Carbon::parse($data['mof_start_date'])->format('Y-m-d');
						} else {
							$vendor->mof_start_date = null;
						}
					}
			
					if(isset($data['mof_end_date'])) {
						if(!empty($data['mof_end_date'])) {
							$vendor->mof_end_date = Carbon::parse($data['mof_end_date'])->format('Y-m-d');
						} else {
							$vendor->mof_end_date = null;
						}
					}
			
				if(isset($data['cidb_start_date'])) {
					if(!empty($data['cidb_start_date'])) {
						$vendor->cidb_start_date = Carbon::parse($data['cidb_start_date'])->format('Y-m-d');
					} else {
						$vendor->cidb_start_date = null;
					}
				}
				
				if(isset($data['cidb_end_date'])) {
					if(!empty($data['cidb_end_date'])) {
						$vendor->cidb_end_date = Carbon::parse($data['cidb_end_date'])->format('Y-m-d');
					} else {
						$vendor->cidb_end_date = null;
					}
				}
			
				if(!isset($vendor->approval_1_id) || auth()->user()->hasRole('Admin')) {
					if(isset($data['mof_bumi'])) {
						$vendor->mof_bumi   = !!$data['mof_bumi'];
					}
					
					if(isset($data['cidb_bumi'])) {
						$vendor->cidb_bumi  = !!$data['cidb_bumi'];
					}
				}
			}
		});
	
		self::saved(function($vendor){
		
			$data = request();
			
			if(isset($data['shareholder']))
				$vendor->processShareholders($data['shareholder']);
			
			if(isset($data['director']))
				$vendor->processDirectors($data['director']);
			
			if(isset($data['contact']))
				$vendor->processContacts($data['contact']);
			
			if(isset($data['account']))
				$vendor->processAccounts($data['account']);
			
			if(isset($data['award']))
				$vendor->processAwards($data['award']);
			
			if(isset($data['asset']))
				$vendor->processAssets($data['asset']);
			
			if(isset($data['project']))
				$vendor->processProjects($data['project']);
			
			if(isset($data['product']))
				$vendor->processProducts($data['product']);
			
			if(isset($data['mof_codes']))

				$vendor->processMofCodes($data['mof_codes']);
			
			if(isset($data['cidb_group']))
				$vendor->processCidbCodes($data['cidb_group']);
			
			if(isset($data['deleted_cidb_group']))
				$vendor->deleteCidbCodes($data['deleted_cidb_group']);
		});
	
		self::updated(function($vendor){
			$data = request()->all();
			if(isset($data['deleted']['shareholder']))
				Shareholder::destroy($data['deleted']['shareholder']);
			if(isset($data['deleted']['director']))
				Director::destroy($data['deleted']['director']);
			if(isset($data['deleted']['contact']))
				Contact::destroy($data['deleted']['contact']);
			if(isset($data['deleted']['account']))
				Account::destroy($data['deleted']['account']);
			if(isset($data['deleted']['award']))
				Award::destroy($data['deleted']['award']);
			if(isset($data['deleted']['asset']))
				VendorAsset::destroy($data['deleted']['asset']);
			if(isset($data['deleted']['project']))
				Project::destroy($data['deleted']['project']);
			if(isset($data['deleted']['product']))
				Project::destroy($data['deleted']['product']);
			
			if(empty($vendor->cidb_ref_no)) {
				$vendor->vendorCodes()->where('code_type', 'cidb')->delete();
			}
			
			if(empty($vendor->mof_ref_no))
				$vendor->vendorCodes()->where('code_type', 'mof')->delete();
		});
	
		self::saving(function($model){
			$path = public_path() . '/uploads/' . md5($model->registration);
			if(!is_dir($path))
				mkdir($path);
			$model->processVendorUploads();
		});
		
		self::updating(function($vendor){
			$data = request()->all();
			if(isset($data['officer_name']) && $vendor->user) {
				$vendor->user->name = $data['officer_name'];
			}
			if(isset($data['email']) && auth()->user()->hasRole('Admin')) {
				$vendor->user->email = $vendor->user->username = trim($data['email']);
			}
			$vendor->user->save();
		});
	
		static::saving(function ($model) {
			$model->preSave();
		});
		static::saved(function ($model) {
			$model->postSave();
		});
		static::deleted(function ($model) {
			$model->preSave();
			$model->postDelete();
		});
	}
	
	public static $shareholderTypes = [
		'Bumiputera'     => 'Bumiputera',
		'Non Bumiputera' => 'Bukan Bumiputera',
		'Foreigner'      => 'Warga Asing'
	];
	
	public static $directorDesignations = [
		'PENGARAH'                             => 'PENGARAH',
		'PENGARAH EKSEKUTIF'                   => 'PENGARAH EKSEKUTIF',
		'PENGARAH BUKAN EKSEKUTIF'             => 'PENGARAH BUKAN EKSEKUTIF',
		'PENGARAH BUKAN EKSEKUTIF BUKAN BEBAS' => 'PENGARAH BUKAN EKSEKUTIF BUKAN BEBAS',
		'PENGARAH BUKAN EKSEKUTIF BEBAS'       => 'PENGARAH BUKAN EKSEKUTIF BEBAS',
	];
	
	public static $organizationTypes = [
		'ROB: PERSEORANGAN'                 => 'ROB: PERSEORANGAN',
		'ROB: PERKONGSIAN'                  => 'ROB: PERKONGSIAN',
		'ROC: BERHAD'                       => 'ROC: BERHAD',
		'ROC: SENDIRIAN BERHAD'             => 'ROC: SENDIRIAN BERHAD',
		'ROC: PERKONGSIAN LIABILITI TERHAD' => 'ROC: PERKONGSIAN LIABILITI TERHAD',
		'ROS: KOPERASI'                     => 'ROS: KOPERASI',
		'ROS: PERTUBUHAN'                   => 'ROS: PERTUBUHAN',
		'ROS: PERSATUAN'                    => 'ROS: PERSATUAN'
	];
	
	public static $nationalities = [
		'MALAYSIA'                                     => 'MALAYSIA',
		'AFGHANISTAN'                                  => 'AFGHANISTAN',
		'ALBANIA'                                      => 'ALBANIA',
		'ALGERIA'                                      => 'ALGERIA',
		'AMERICAN SAMOA'                               => 'AMERICAN SAMOA',
		'ANDORRA'                                      => 'ANDORRA',
		'ANGOLA'                                       => 'ANGOLA',
		'ANGUILLA'                                     => 'ANGUILLA',
		'ANTARCTICA'                                   => 'ANTARCTICA',
		'ANTIGUA AND BARBUDA'                          => 'ANTIGUA AND BARBUDA',
		'ARGENTINA'                                    => 'ARGENTINA',
		'ARMENIA'                                      => 'ARMENIA',
		'ARUBA'                                        => 'ARUBA',
		'AUSTRALIA'                                    => 'AUSTRALIA',
		'AUSTRIA'                                      => 'AUSTRIA',
		'AZERBAIJAN'                                   => 'AZERBAIJAN',
		'BAHAMAS'                                      => 'BAHAMAS',
		'BAHRAIN'                                      => 'BAHRAIN',
		'BANGLADESH'                                   => 'BANGLADESH',
		'BARBADOS'                                     => 'BARBADOS',
		'BELARUS'                                      => 'BELARUS',
		'BELGIUM'                                      => 'BELGIUM',
		'BELIZE'                                       => 'BELIZE',
		'BENIN'                                        => 'BENIN',
		'BERMUDA'                                      => 'BERMUDA',
		'BHUTAN'                                       => 'BHUTAN',
		'BOLIVIA'                                      => 'BOLIVIA',
		'BOSNIA AND HERZEGOWINA'                       => 'BOSNIA AND HERZEGOWINA',
		'BOTSWANA'                                     => 'BOTSWANA',
		'BOUVET ISLAND'                                => 'BOUVET ISLAND',
		'BRAZIL'                                       => 'BRAZIL',
		'BRITISH INDIAN OCEAN TERRITORY'               => 'BRITISH INDIAN OCEAN TERRITORY',
		'BRUNEI'                                       => 'BRUNEI',
		'BULGARIA'                                     => 'BULGARIA',
		'BURKINA FASO'                                 => 'BURKINA FASO',
		'BURUNDI'                                      => 'BURUNDI',
		'CAMBODIA'                                     => 'CAMBODIA',
		'CAMEROON'                                     => 'CAMEROON',
		'CANADA'                                       => 'CANADA',
		'CAPE VERDE'                                   => 'CAPE VERDE',
		'CAYMAN ISLANDS'                               => 'CAYMAN ISLANDS',
		'CENTRAL AFRICAN REPUBLIC'                     => 'CENTRAL AFRICAN REPUBLIC',
		'CHAD'                                         => 'CHAD',
		'CHILE'                                        => 'CHILE',
		'CHINA'                                        => 'CHINA',
		'CHRISTMAS ISLAND'                             => 'CHRISTMAS ISLAND',
		'COCOS (KEELING) ISLANDS'                      => 'COCOS (KEELING) ISLANDS',
		'COLOMBIA'                                     => 'COLOMBIA',
		'COMOROS'                                      => 'COMOROS',
		'CONGO'                                        => 'CONGO',
		'CONGO, THE DEMOCRATIC REPUBLIC OF THE'        => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
		'COOK ISLANDS'                                 => 'COOK ISLANDS',
		'COSTA RICA'                                   => 'COSTA RICA',
		'COTE D\'IVOIRE'                               => 'COTE D\'IVOIRE',
		'CROATIA (HRVATSKA)'                           => 'CROATIA (HRVATSKA)',
		'CUBA'                                         => 'CUBA',
		'CYPRUS'                                       => 'CYPRUS',
		'CZECH REPUBLIC'                               => 'CZECH REPUBLIC',
		'DENMARK'                                      => 'DENMARK',
		'DJIBOUTI'                                     => 'DJIBOUTI',
		'DOMINICA'                                     => 'DOMINICA',
		'DOMINICAN REPUBLIC'                           => 'DOMINICAN REPUBLIC',
		'EAST TIMOR'                                   => 'EAST TIMOR',
		'ECUADOR'                                      => 'ECUADOR',
		'EGYPT'                                        => 'EGYPT',
		'EL SALVADOR'                                  => 'EL SALVADOR',
		'EQUATORIAL GUINEA'                            => 'EQUATORIAL GUINEA',
		'ERITREA'                                      => 'ERITREA',
		'ESTONIA'                                      => 'ESTONIA',
		'ETHIOPIA'                                     => 'ETHIOPIA',
		'FALKLAND ISLANDS (MALVINAS)'                  => 'FALKLAND ISLANDS (MALVINAS)',
		'FAROE ISLANDS'                                => 'FAROE ISLANDS',
		'FIJI'                                         => 'FIJI',
		'FINLAND'                                      => 'FINLAND',
		'FRANCE'                                       => 'FRANCE',
		'FRANCE METROPOLITAN'                          => 'FRANCE METROPOLITAN',
		'FRENCH GUIANA'                                => 'FRENCH GUIANA',
		'FRENCH POLYNESIA'                             => 'FRENCH POLYNESIA',
		'FRENCH SOUTHERN TERRITORIES'                  => 'FRENCH SOUTHERN TERRITORIES',
		'GABON'                                        => 'GABON',
		'GAMBIA'                                       => 'GAMBIA',
		'GEORGIA'                                      => 'GEORGIA',
		'GERMANY'                                      => 'GERMANY',
		'GHANA'                                        => 'GHANA',
		'GIBRALTAR'                                    => 'GIBRALTAR',
		'GREECE'                                       => 'GREECE',
		'GREENLAND'                                    => 'GREENLAND',
		'GRENADA'                                      => 'GRENADA',
		'GUADELOUPE'                                   => 'GUADELOUPE',
		'GUAM'                                         => 'GUAM',
		'GUATEMALA'                                    => 'GUATEMALA',
		'GUINEA'                                       => 'GUINEA',
		'GUINEA-BISSAU'                                => 'GUINEA-BISSAU',
		'GUYANA'                                       => 'GUYANA',
		'HAITI'                                        => 'HAITI',
		'HEARD AND MC DONALD ISLANDS'                  => 'HEARD AND MC DONALD ISLANDS',
		'HONDURAS'                                     => 'HONDURAS',
		'HONG KONG'                                    => 'HONG KONG',
		'HUNGARY'                                      => 'HUNGARY',
		'ICELAND'                                      => 'ICELAND',
		'INDIA'                                        => 'INDIA',
		'INDONESIA'                                    => 'INDONESIA',
		'IRAN'                                         => 'IRAN',
		'IRAQ'                                         => 'IRAQ',
		'IRELAND'                                      => 'IRELAND',
		'ISRAEL'                                       => 'ISRAEL',
		'ITALY'                                        => 'ITALY',
		'JAMAICA'                                      => 'JAMAICA',
		'JAPAN'                                        => 'JAPAN',
		'JORDAN'                                       => 'JORDAN',
		'KAZAKHSTAN'                                   => 'KAZAKHSTAN',
		'KENYA'                                        => 'KENYA',
		'KIRIBATI'                                     => 'KIRIBATI',
		'KUWAIT'                                       => 'KUWAIT',
		'KYRGYZSTAN'                                   => 'KYRGYZSTAN',
		'LAO'                                          => 'LAO',
		'LATVIA'                                       => 'LATVIA',
		'LEBANON'                                      => 'LEBANON',
		'LESOTHO'                                      => 'LESOTHO',
		'LIBERIA'                                      => 'LIBERIA',
		'LIBYA'                                        => 'LIBYA',
		'LIECHTENSTEIN'                                => 'LIECHTENSTEIN',
		'LITHUANIA'                                    => 'LITHUANIA',
		'LUXEMBOURG'                                   => 'LUXEMBOURG',
		'MACAU'                                        => 'MACAU',
		'MACEDONIA'                                    => 'MACEDONIA',
		'MADAGASCAR'                                   => 'MADAGASCAR',
		'MALAWI'                                       => 'MALAWI',
		'MALDIVES'                                     => 'MALDIVES',
		'MALI'                                         => 'MALI',
		'MALTA'                                        => 'MALTA',
		'MARSHALL ISLANDS'                             => 'MARSHALL ISLANDS',
		'MARTINIQUE'                                   => 'MARTINIQUE',
		'MAURITANIA'                                   => 'MAURITANIA',
		'MAURITIUS'                                    => 'MAURITIUS',
		'MAYOTTE'                                      => 'MAYOTTE',
		'MEXICO'                                       => 'MEXICO',
		'MICRONESIA'                                   => 'MICRONESIA',
		'MOLDOVA'                                      => 'MOLDOVA',
		'MONACO'                                       => 'MONACO',
		'MONGOLIA'                                     => 'MONGOLIA',
		'MONTSERRAT'                                   => 'MONTSERRAT',
		'MOROCCO'                                      => 'MOROCCO',
		'MOZAMBIQUE'                                   => 'MOZAMBIQUE',
		'MYANMAR'                                      => 'MYANMAR',
		'NAMIBIA'                                      => 'NAMIBIA',
		'NAURU'                                        => 'NAURU',
		'NEPAL'                                        => 'NEPAL',
		'NETHERLANDS'                                  => 'NETHERLANDS',
		'NETHERLANDS ANTILLES'                         => 'NETHERLANDS ANTILLES',
		'NEW CALEDONIA'                                => 'NEW CALEDONIA',
		'NEW ZEALAND'                                  => 'NEW ZEALAND',
		'NICARAGUA'                                    => 'NICARAGUA',
		'NIGER'                                        => 'NIGER',
		'NIGERIA'                                      => 'NIGERIA',
		'NIUE'                                         => 'NIUE',
		'NORFOLK ISLAND'                               => 'NORFOLK ISLAND',
		'NORTH KOREA'                                  => 'NORTH KOREA',
		'NORTHERN MARIANA ISLANDS'                     => 'NORTHERN MARIANA ISLANDS',
		'NORWAY'                                       => 'NORWAY',
		'OMAN'                                         => 'OMAN',
		'PAKISTAN'                                     => 'PAKISTAN',
		'PALAU'                                        => 'PALAU',
		'PANAMA'                                       => 'PANAMA',
		'PAPUA NEW GUINEA'                             => 'PAPUA NEW GUINEA',
		'PARAGUAY'                                     => 'PARAGUAY',
		'PERU'                                         => 'PERU',
		'PHILIPPINES'                                  => 'PHILIPPINES',
		'PITCAIRN'                                     => 'PITCAIRN',
		'POLAND'                                       => 'POLAND',
		'PORTUGAL'                                     => 'PORTUGAL',
		'PUERTO RICO'                                  => 'PUERTO RICO',
		'QATAR'                                        => 'QATAR',
		'REUNION'                                      => 'REUNION',
		'ROMANIA'                                      => 'ROMANIA',
		'RUSSIAN FEDERATION'                           => 'RUSSIAN FEDERATION',
		'RWANDA'                                       => 'RWANDA',
		'SAINT KITTS AND NEVIS'                        => 'SAINT KITTS AND NEVIS',
		'SAINT LUCIA'                                  => 'SAINT LUCIA',
		'SAINT VINCENT AND THE GRENADINES'             => 'SAINT VINCENT AND THE GRENADINES',
		'SAMOA'                                        => 'SAMOA',
		'SAN MARINO'                                   => 'SAN MARINO',
		'SAO TOME AND PRINCIPE'                        => 'SAO TOME AND PRINCIPE',
		'SAUDI ARABIA'                                 => 'SAUDI ARABIA',
		'SENEGAL'                                      => 'SENEGAL',
		'SEYCHELLES'                                   => 'SEYCHELLES',
		'SIERRA LEONE'                                 => 'SIERRA LEONE',
		'SINGAPORE'                                    => 'SINGAPORE',
		'SLOVAKIA'                                     => 'SLOVAKIA',
		'SLOVENIA'                                     => 'SLOVENIA',
		'SOLOMON ISLANDS'                              => 'SOLOMON ISLANDS',
		'SOMALIA'                                      => 'SOMALIA',
		'SOUTH AFRICA'                                 => 'SOUTH AFRICA',
		'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
		'SOUTH KOREA'                                  => 'SOUTH KOREA',
		'SPAIN'                                        => 'SPAIN',
		'SRI LANKA'                                    => 'SRI LANKA',
		'ST. HELENA'                                   => 'ST. HELENA',
		'ST. PIERRE AND MIQUELON'                      => 'ST. PIERRE AND MIQUELON',
		'SUDAN'                                        => 'SUDAN',
		'SURINAME'                                     => 'SURINAME',
		'SVALBARD AND JAN MAYEN ISLANDS'               => 'SVALBARD AND JAN MAYEN ISLANDS',
		'SWAZILAND'                                    => 'SWAZILAND',
		'SWEDEN'                                       => 'SWEDEN',
		'SWITZERLAND'                                  => 'SWITZERLAND',
		'SYRIAN ARAB REPUBLIC'                         => 'SYRIAN ARAB REPUBLIC',
		'TAIWAN'                                       => 'TAIWAN',
		'TAJIKISTAN'                                   => 'TAJIKISTAN',
		'TANZANIA, UNITED REPUBLIC OF'                 => 'TANZANIA, UNITED REPUBLIC OF',
		'THAILAND'                                     => 'THAILAND',
		'TOGO'                                         => 'TOGO',
		'TOKELAU'                                      => 'TOKELAU',
		'TONGA'                                        => 'TONGA',
		'TRINIDAD AND TOBAGO'                          => 'TRINIDAD AND TOBAGO',
		'TUNISIA'                                      => 'TUNISIA',
		'TURKEY'                                       => 'TURKEY',
		'TURKMENISTAN'                                 => 'TURKMENISTAN',
		'TURKS AND CAICOS ISLANDS'                     => 'TURKS AND CAICOS ISLANDS',
		'TUVALU'                                       => 'TUVALU',
		'UGANDA'                                       => 'UGANDA',
		'UKRAINE'                                      => 'UKRAINE',
		'UNITED ARAB EMIRATES'                         => 'UNITED ARAB EMIRATES',
		'UNITED KINGDOM'                               => 'UNITED KINGDOM',
		'UNITED STATES'                                => 'UNITED STATES',
		'UNITED STATES MINOR OUTLYING ISLANDS'         => 'UNITED STATES MINOR OUTLYING ISLANDS',
		'URUGUAY'                                      => 'URUGUAY',
		'UZBEKISTAN'                                   => 'UZBEKISTAN',
		'VANUATU'                                      => 'VANUATU',
		'VATICAN CITY'                                 => 'VATICAN CITY',
		'VENEZUELA'                                    => 'VENEZUELA',
		'VIETNAM'                                      => 'VIETNAM',
		'VIRGIN ISLANDS (BRITISH)'                     => 'VIRGIN ISLANDS (BRITISH)',
		'VIRGIN ISLANDS (U.S.)'                        => 'VIRGIN ISLANDS (U.S.)',
		'WALLIS AND FUTUNA ISLANDS'                    => 'WALLIS AND FUTUNA ISLANDS',
		'WESTERN SAHARA'                               => 'WESTERN SAHARA',
		'YEMEN'                                        => 'YEMEN',
		'YUGOSLAVIA'                                   => 'YUGOSLAVIA',
		'ZAMBIA'                                       => 'ZAMBIA',
		'ZIMBABWE'                                     => 'ZIMBABWE'
	];
	
	public static $currencies = [
		'MYR' => 'MYR',
		'ALL' => 'ALL',
		'AFN' => 'AFN',
		'ARS' => 'ARS',
		'AWG' => 'AWG',
		'AUD' => 'AUD',
		'AZN' => 'AZN',
		'BSD' => 'BSD',
		'BBD' => 'BBD',
		'BYR' => 'BYR',
		'BZD' => 'BZD',
		'BMD' => 'BMD',
		'BOB' => 'BOB',
		'BAM' => 'BAM',
		'BWP' => 'BWP',
		'BGN' => 'BGN',
		'BRL' => 'BRL',
		'BND' => 'BND',
		'KHR' => 'KHR',
		'CAD' => 'CAD',
		'KYD' => 'KYD',
		'CLP' => 'CLP',
		'CNY' => 'CNY',
		'COP' => 'COP',
		'CRC' => 'CRC',
		'HRK' => 'HRK',
		'CUP' => 'CUP',
		'CZK' => 'CZK',
		'DKK' => 'DKK',
		'DOP' => 'DOP',
		'XCD' => 'XCD',
		'EGP' => 'EGP',
		'SVC' => 'SVC',
		'EEK' => 'EEK',
		'EUR' => 'EUR',
		'FKP' => 'FKP',
		'FJD' => 'FJD',
		'GHC' => 'GHC',
		'GIP' => 'GIP',
		'GTQ' => 'GTQ',
		'GGP' => 'GGP',
		'GYD' => 'GYD',
		'HNL' => 'HNL',
		'HKD' => 'HKD',
		'HUF' => 'HUF',
		'ISK' => 'ISK',
		'INR' => 'INR',
		'IDR' => 'IDR',
		'IRR' => 'IRR',
		'IMP' => 'IMP',
		'ILS' => 'ILS',
		'JMD' => 'JMD',
		'JPY' => 'JPY',
		'JEP' => 'JEP',
		'KZT' => 'KZT',
		'KPW' => 'KPW',
		'KRW' => 'KRW',
		'KGS' => 'KGS',
		'LAK' => 'LAK',
		'LVL' => 'LVL',
		'LBP' => 'LBP',
		'LRD' => 'LRD',
		'LTL' => 'LTL',
		'MKD' => 'MKD',
		'MUR' => 'MUR',
		'MXN' => 'MXN',
		'MNT' => 'MNT',
		'MZN' => 'MZN',
		'NAD' => 'NAD',
		'NPR' => 'NPR',
		'ANG' => 'ANG',
		'NZD' => 'NZD',
		'NIO' => 'NIO',
		'NGN' => 'NGN',
		'KPW' => 'KPW',
		'NOK' => 'NOK',
		'OMR' => 'OMR',
		'PKR' => 'PKR',
		'PAB' => 'PAB',
		'PYG' => 'PYG',
		'PEN' => 'PEN',
		'PHP' => 'PHP',
		'PLN' => 'PLN',
		'QAR' => 'QAR',
		'RON' => 'RON',
		'RUB' => 'RUB',
		'SHP' => 'SHP',
		'SAR' => 'SAR',
		'RSD' => 'RSD',
		'SCR' => 'SCR',
		'SGD' => 'SGD',
		'SBD' => 'SBD',
		'SOS' => 'SOS',
		'ZAR' => 'ZAR',
		'KRW' => 'KRW',
		'LKR' => 'LKR',
		'SEK' => 'SEK',
		'CHF' => 'CHF',
		'CHF' => 'CHF',
		'CHF' => 'CHF',
		'SRD' => 'SRD',
		'SYP' => 'SYP',
		'TWD' => 'TWD',
		'THB' => 'THB',
		'TTD' => 'TTD',
		'TRY' => 'TRY',
		'TRL' => 'TRL',
		'TVD' => 'TVD',
		'UAH' => 'UAH',
		'GBP' => 'GBP',
		'USD' => 'USD',
		'UYU' => 'UYU',
		'UZS' => 'UZS',
		'VEF' => 'VEF',
		'VND' => 'VND',
		'YER' => 'YER',
		'ZWD' => 'ZWD'
	];
	
	public static $districts = [
		1 => 'Sabak Bernam',
		2 => 'Hulu Selangor',
		3 => 'Kuala Selangor',
		4 => 'Gombak',
		5 => 'Petaling',
		6 => 'Klang',
		7 => 'Kuala Langat',
		8 => 'Hulu Langat',
		9 => 'Sepang',
		'0' => 'Luar Negeri Selangor',
	];

	public static $states = [
		1 => 'JOHOR',
		2 => 'KEDAH',
		3 => 'KELANTAN',
		4 => 'MELAKA',
		5 => 'NEGERI SEMBILAN',
		6 => 'PAHANG',
		7 => 'PULAU PINANG',
		8 => 'PERAK',
		9 => 'PERLIS',
		10 => 'SABAH',
		11 => 'SARAWAK',
		12 => 'SELANGOR',
		13 => 'TERENGGANU',
		14 => 'KUALA LUMPUR',
		15 => 'LABUAN',
		16 => 'PUTRAJAYA',
	];
}
