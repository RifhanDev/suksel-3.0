<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Tender extends Model
{

	use \Venturecraft\Revisionable\RevisionableTrait;
	
	static $types = [
		'tender'      => 'Tender',
		'quotation'   => 'Sebut Harga'
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
		'name',
		'ref_number',
		'creator_id',
		'officer_id',
		'organization_unit_id',
		'price',
		'allow_exception',
		'advertise_start_date',
		'advertise_stop_date',
		'document_start_date',
		'document_stop_date',
		'submission_datetime',
		'submission_location_address',
		'tender_rules',
		'publish_prices',
		'publish_shortlists',
		'publish_winner',
		'briefing_datetime',
		'briefing_address',
		'briefing_latlng',
		'briefing_required',
		'invitation',
		'district_id',
		'only_selangor',
		'only_bumiputera',
		'type',
		'only_advertise',
		'mof_cidb_rule',
		'district_list_rule'
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
		'store'                       => [
			'name'                        => 'required',
			'ref_number'                  => 'required',
			'creator_id'                  => 'required',
			'organization_unit_id'        => 'required',
			'price'                       => 'required',
			'advertise_start_date'        => 'required',
			'advertise_stop_date'         => 'required',
			'document_start_date'         => 'required',
			'document_stop_date'          => 'required',
			'submission_datetime'         => 'required',
			'submission_location_address' => 'required',
			'tender_rules'                => 'required',
		],
		'update'                      => [
			'name'                        => 'required',
			'ref_number'                  => 'required',
			'creator_id'                  => 'required',
			'organization_unit_id'        => 'required',
			'price'                       => 'required',
			'advertise_start_date'        => 'required',
			'advertise_stop_date'         => 'required',
			'document_start_date'         => 'required',
			'document_stop_date'          => 'required',
			'submission_datetime'         => 'required',
			'submission_location_address' => 'required',
			'tender_rules'                => 'required'
		]
	];
	
	public static $rules = [];
	
	public static function setRules($name) {
		self::$rules = self::$_rules[$name];
	}
	
	/**
	* ACL
	*/

	public function canException()
	{
		if ($this->allow_exception) {
			return true;
		}
		return false;
	}
	
	public static function canList() {
		return true;
	}
	
	public static function canCreate() {
		return (auth()->user() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['Tender:create']));
	}
	
	public function canShow() {
		if(auth()->check()) $user = auth()->user();
		if($this->invitation) {
			if(auth()->check()) {
				if($user->hasRole('Admin')) {
					return true;
				} elseif($user->ability(['Agency Admin', 'Agency User'], ['Tender:edit']) && $this->organization_unit_id == $user->organization_unit_id) {
					return true;
				} elseif($user->hasRole('Vendor') && $this->invites()->where('vendor_id', $user->vendor_id)->first()) {
					return true;
				} else {
					return false;
				}
				} else {
					return false;
			}
		} elseif(empty($this->approver_id)) {
			if(auth()->check() && $user->hasRole('Admin')) {
				return true;
			} elseif(auth()->check() && $user->ability(['Agency Admin', 'Agency User'], ['Tender:edit']) && $this->organization_unit_id == $user->organization_unit_id) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	public function canUpdate()  {
		if(auth()->check()) {
			$user = auth()->user();
			
			if($user->hasRole('Admin')) {
				return true;
			} elseif($user->ability(['Agency Admin', 'Agency User'], ['Tender:edit']) && $this->organization_unit_id == $user->organization_unit_id) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function canAllowEdit() {
		if(auth()->check()) {
			$user = auth()->user();
			
			if($this->approver_id > 0) {
				return false;
			} if($user->hasRole('Admin')) {
				return true;
			} elseif($user->ability(['Agency Admin', 'Agency User'], ['Tender:edit']) && $this->organization_unit_id == $user->organization_unit_id) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}        
	}
	
	public static function canShowUpdate($organization_unit_id) {
		if(auth()->check()) {
			$user = auth()->user();
			
			if($user->hasRole('Admin')) {
				return true;
			} elseif($user->ability(['Agency Admin', 'Agency User'], ['Tender:edit']) && $organization_unit_id === $user->organization_unit_id) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function canShowTabs() {
		if($this->publish_prices > 0) {
			return true;
		} else {
			if(auth()->check() ) {
				$user = auth()->user();
				
				if($user->can('Tender:vendors:all')) { 
					return true;
				} elseif($user->can('Tender:vendors') && $this->organization_unit_id == $user->organization_unit_id) {
					return true;
				} else {
					return false;
				}
			}
		}    
	}
	
	public function canShowFiles($vendor_id) {
		if(auth()->check()) {
		$user = auth()->user();
		
			if($user->hasRole('Admin')) {
				return true;
			} elseif($user->ability(['Agency Admin', 'Agency User'], []) && $this->organization_unit_id == $user->organization_unit_id) {
				return true;
			} elseif($user->hasRole('Vendor') && $this->hasParticipate($vendor_id)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function onlyShowPrices() {
		if(auth()->check() && auth()->user()->hasRole('Vendor')) {
			return true;
		} elseif(!auth()->check()) {
			return true;
		} elseif(auth()->user()->ability(['Agency Admin', 'Agency User'], []) && $this->organization_unit_id != auth()->user()->organization_unit_id) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canDelete() {
		if(auth()->check() && auth()->user()->hasRole('Admin')) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canCancel() {
		if(auth()->check()) {
			if(auth()->user()->hasRole('Admin')) {
			return true;
			} elseif(auth()->user()->ability(['Agency Admin'], ['Tender:cancel'])) {
			return true;
			}
		} else {
			return false;
		}        
	}
	
	public function canApprove() {
		if(auth()->check()) {
				if(auth()->user()->hasRole('Admin')) {
					return true;
				}
			} else {
				return false;
			}
	}
	
	public function canPurchase() {
		if(auth()->check() && auth()->user()->hasRole('Vendor')) {
			if($this->manual_payment) {
				return false;
			} elseif($this->hasParticipate(auth()->user()->vendor_id)) {
				return false;
			} elseif(in_array($this->id, session('cart_items', []))) {
				return false;
			} elseif(!$this->canParticipate(auth()->user()->vendor_id)) {
				return false;
			} else {
				return true;
			}
		
		} else {
			return false;
		}
	}
	
	/**
	* Relationships
	*/

	public function creator()
	{
		return $this->belongsTo('App\User','creator_id','id');
	}

	public function officer()
	{
		return $this->belongsTo('App\User','officer_id','id');
	}
	
	public function tenderer() {
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}
	
	public function news() {
		return $this->hasMany('App\News');
	}
	
	public function exceptions() {
		return $this->hasMany('App\Models\ExceptionTender');
	}
	
	public function siteVisits() {
		return $this->hasMany('App\TenderVisit');
	}
	
	public function codes() {
		return $this->hasMany('App\TenderCode');
	}
	
	public function files() {
		return $this->morphMany('App\Upload', 'uploadable');
	}
	
	public function invites() {
		return $this->hasMany('App\TenderInvite');
	}
	
	public function histories() {
		return $this->hasMany('App\TenderHistory');
	}
	
	public function eligibles() {
		return $this->hasMany('App\TenderEligible');
	}

	public function petenderPerformances()
	{
		return $this -> hasMany(PetenderPerformance::class, 'tender_id');
	}

	public function participants() 
	{
		return $this -> hasMany(TenderVendor::class, 'tender_id');
	}
	
	/**
	* Decorators
	*/
	
	public function getAdvertiseDateRangeAttribute() {
		if(isset($this->advertise_start_date) && isset($this->advertise_stop_date)) {
			return implode(' - ', [
				Carbon::parse($this->advertise_start_date)->format('d M Y'),
				Carbon::parse($this->advertise_stop_date)->format('d M Y')
			]);
		}
		else {
			return null;
		}
	}
	
	public function getDocumentDateRangeAttribute() {
		if(isset($this->document_start_date) && isset($this->document_stop_date)) {
			return implode(' - ', [
				Carbon::parse($this->document_start_date)->format('d M Y'),
				Carbon::parse($this->document_stop_date)->format('d M Y')
			]);
		}
		else {
			return null;
		}
	}
	
	public function getMofCodesAttribute() {
		return $this->codes()->with('code')->orderBy('order', 'asc')->where('code_type', 'mof')->get();
	}
	
	public function getMofCodeGroupsAttribute() {
		$data = $this->mof_codes;
		$groups = array();
		
		foreach($data as $d) {
			$order = $d['order'];
			if(isset($groups[$order])) {
				$groups[$order]['codes'][$d['code_id']] =  $d->code->label2;
			} else {
				$groups[$order] = array(
					'inner_rule' => $d['inner_rule'],
					'join_rule' => $d['join_rule'],
					'codes' => array(
						$d['code_id'] => $d->code->label2
					)
				);
			}
		}
	
		return $groups;
	}
	
	public function getMofCodeGroupsByCodeAttribute() {
		$data = $this->mof_codes;
		$groups = array();
		
		foreach($data as $d) {
			$order = $d['order'];
			if(isset($groups[$order])) {
				$groups[$order]['codes'][$d['code_id']] =  $d->code->code;
			} else {
				$groups[$order] = array(
					'inner_rule' => $d['inner_rule'],
					'join_rule' => $d['join_rule'],
					'codes' => array(
						$d['code_id'] => $d->code->code
					)
				);
			}
		}
		
		return $groups;
	}
	
	
	public function getCidbCodesAttribute() {
		return $this->codes()->with('code')->orderBy('order', 'asc')->where('code_type', 'cidb')->get();
	}
	
	public function getCidbCodeGroupsAttribute() {
		$data = $this->cidb_codes;
		$groups = array();
		
		foreach($data as $d) {
			$order = $d['order'];
			if(isset($groups[$order])) {
				$groups[$order]['codes'][$d['code_id']] =  $d->code->label2;
			} else {
				$groups[$order] = array(
					'inner_rule' => $d['inner_rule'],
					'join_rule' => $d['join_rule'],
					'codes' => array(
						$d['code_id'] => $d->code->label2
					)
				);
			}
		}
		
		return $groups;
	}
	
	public function getCidbCodeGroupsByCodeAttribute() {
		$data = $this->cidb_codes;
		$groups = array();
		
		foreach($data as $d) {
			$order = $d['order'];
			if(isset($groups[$order])) {
				$groups[$order]['codes'][$d['code_id']] =  $d->code->code;
			} else {
				$groups[$order] = array(
					'inner_rule' => $d['inner_rule'],
					'join_rule' => $d['join_rule'],
					'codes' => array(
						$d['code_id'] => $d->code->code
					)
				);
			}
		}
		
		return $groups;
	}
	
	public function getCidbGradesAttribute() {
	return $this->codes()->where('code_type', 'cidb-g')->get();
	}
	
	public function getTableFilesAttribute() {
	return $this->files()->where('public', 1)->orderBy('name', 'asc')->get();
	}
	
	public function getTenderFilesAttribute() {
	return $this->files()->where('public', 0)->orderBy('name', 'asc')->get();
	}
	
	public function hasParticipate($id) {
		$participate = $this->participants()->where('vendor_id', $id)->first();
		if($participate) {
			return $participate->participate;
		} else {
			return false;
		}
	}

	public function hasOfficer()
	{
		return isset($this->officer_id);
	}
	
	public function isBlacklisted($vendor_id) {
		$vendor = Vendor::find($vendor_id);
		
		if(!$vendor) return false;
		
		$that = $this;
		
		$blacklists = $vendor->blacklists()
			->where('start', '<=', date('Y-m-d'))
			->where('end', '>=', date('Y-m-d'))
			->where('status', 'active')
			->where(function($q) use($that) {
				$q->orWhere('organization_unit_id', $that->organization_unit_id)->orWhere('organization_unit_id', 'IS NULL');
			})
			->count();
		
		return $blacklists > 0;
	}
	
	public function canParticipate($vendor_id) {

		$vendor = Vendor::find($vendor_id);
		$purchase = TenderVendor::where('vendor_id', $vendor_id)->where('tender_id', $this->id)->first();
		if($this->tenderer->is_gateway_locked) return false;
		if(!$vendor) return false;
		if(!$this->validDocumentDate()) return false;
		if($purchase && $purchase->exception == 1) return true;
	
		$participate = true;
		
		if(!$vendor->canParticipateInTenders()) {
			$participate = false;
		}
	
		$mof_participate = true;
		// when mof codes == 0 then the mof_participate will always true
		if(count($this->mof_codes) > 0) {
			$mof_participate = $vendor->mofValid();
			$mof_participate = $mof_participate && $this->matchCodes($vendor->id, 'mof');
			
			if($this->only_bumiputera) {
				$mof_participate = $mof_participate && $vendor->mof_bumi;
			}
		}
	
		$cidb_participate = true;
		if(count($this->cidb_grades) > 0) {
			$cidb_participate = $cidb_participate && $vendor->cidbValid();
			$cidb_participate = $cidb_participate && $this->matchCidbGrade($vendor->id);
			$cidb_participate = $cidb_participate && $this->matchCidbCodesInverse($vendor->id);
		
			if($this->only_bumiputera) {
				$cidb_participate = $cidb_participate && $vendor->cidb_bumi;
			}
		}
	
		if($this->mof_cidb_rule == 'and') {
			$participate = $participate && ($mof_participate && $cidb_participate);
		} else {
			$participate = $participate && ($mof_participate || $cidb_participate);
		}

		if($this->only_selangor == 1) {
			$participate = $participate && !empty($vendor->district_id);
		}
		
		if($this->district_id != null && $this->district_id > 0 ){
			$participate = $participate && ( $this->district_id == $vendor->district_id );
		}

		// dev 22/6/2023 [START]

		//change by zayid 9/6/2023
		$district_list_rule = json_decode($this->district_list_rule);

		if ($district_list_rule === []) {
			$district_list_rule = false;
		}

		if ($district_list_rule == null) {
			$district_list_rule = false;
		}
		//ended here - zayid


		$tender_open_for_state_id 		= [];
		$tender_open_for_state_desc		= [];
		$tender_open_for_district_id 	= [];
		$tender_open_for_district_desc 	= [];

		if( $district_list_rule !== false)
		{
			$current_vendor_state_id 	= auth()->user()->vendor->state_id ?? "0";
			$current_vendor_district_id = auth()->user()->vendor->district_id ?? "0";

			foreach($district_list_rule as $row_rules)
			{
				if ($row_rules->district_id == 0 && $row_rules->state_id != 0)
				{
					$tender_open_for_state_id[]		= $row_rules->state_id;
					$tender_open_for_state_desc[]	= \App\Models\RefState::find($row_rules->state_id )->description ?? "";
				}
				elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0)
				{
					$tender_open_for_district_id[]		= $row_rules->district_id;
					$tender_open_for_district_desc[]	= \App\Models\Vendor::$districts[$row_rules->district_id] ?? "";
				}
			}
		}

		if ($district_list_rule !== false)
		{
			if(!in_array($current_vendor_state_id, $tender_open_for_state_id) && $current_vendor_district_id == 0 && $this->getNegeriListExist())
			{
				$allowed = false;
				$participate = $participate && $allowed;
			}
			elseif(!in_array($current_vendor_district_id, $tender_open_for_district_id) && $current_vendor_state_id == 0 && $this->getDaerahListExist())
			{
				$allowed = false;
				$participate = $participate && $allowed;
			}
			else
			{
				$allowed = true;
				$participate = $participate && $allowed;
			}
		}

		if(auth()->user()->vendor->district_id == null && auth()->user()->vendor->state_id == null)
		{
			$participate = false;
		}

		// dev 22/6/2023 [END]
	
		if($this->briefing_required) {
			if($purchase) {
				$participate = $participate && $purchase->briefing;
			} else {
				$participate = false;
			}
		}
	
		$participate = $participate && $this->attendVisits($vendor->id);
		$participate = !$this->isBlacklisted($vendor_id) && $participate;
		$participate = !$this->only_advertise && $participate;
	
		return $participate;
	}
	
	public function attendVisits($vendor_id) {
		$participate = true;
		if(count($this->siteVisits) > 0) {
			foreach($this->siteVisits as $visit) {
				if($visit->required) {
				$participate = $participate && TenderVisitor::hasVisit($visit->id, $vendor_id);
				}
			}
		}
		return $participate;
	}
	
	public function attendBriefing($vendor_id) {
		if(is_null($this->briefing_required) || $this->briefing_required == 0) {
			return true;
		}
		$purchase = $this->participants()->where('vendor_id', $vendor_id)->first();
		return isset($purchase) && $purchase->briefing;
	}
	
	public function validDocumentDate() {
		$valid = true;
		$valid = $valid && (strtotime($this->document_start_date) <= time());
		$valid = $valid && (time() < Carbon::parse($this->document_stop_date)->addDay()->timestamp);
		return $valid;
	}
	
	public function nearSubmission()
	{
		$submission_date = Carbon::parse($this->submission_datetime);
		if($submission_date->timestamp > time()) {
			$minus_a_day = $submission_date->subDay();
			return time() > $minus_a_day->timestamp;
		} else {
			return false;
		}        
	}
	
	public function nearDocumentStop() {
		$submission_date = Carbon::parse($this->document_stop_date);
		if($submission_date->subDay()->timestamp > time()) {
			return $submission_date->addDay()->timestamp < time();
		} else {
			return false;
		}
	}
	
	public function getCodes($type) {
		if(count($this->{$type . '_codes'}) == 0) return [];
	
		$codes = $this->{$type . '_code_groups'};
	
		$vendor_ids = [];
	
		foreach($codes as $order => $data) {
			$vendors = Vendor::withCodes(array_keys($data['codes']), $type, $data['inner_rule']);
			
			if($order == 1) {
				$vendor_ids = $vendors;
			} else {
				if($codes[$order-1]['join_rule'] == 'and') {

					if(!is_array($vendor_ids))
                    	$vendor_ids = $vendor_ids->toArray();
                	if(!is_array($vendors))
                    	$vendors = $vendors->toArray();

					$vendor_ids = array_intersect($vendor_ids, $vendors);
				}
				if($codes[$order-1]['join_rule'] == 'or') {

					if(!is_array($vendor_ids))
                    	$vendor_ids = $vendor_ids->toArray();
                	if(!is_array($vendors))
                    	$vendors = $vendors->toArray();

					$vendor_ids = array_merge($vendor_ids, $vendors);
				}
			}
		}
	
		return $vendor_ids;
	}
	
	public function matchCodes($vendor_id, $type) {
		if(count($this->{$type . '_codes'}) == 0) 
			return true;
		
		//return in_array($vendor_id, (array) $this->getCodes($type));
		return in_array($vendor_id, json_decode(json_encode($this->getCodes($type)), true)); // Mirul 2019 Nov 26
	}
	
	
	public function matchCidbGrade($vendor_id)
	{
		$grades = $this->cidb_grades->pluck('code_id');
		return VendorCode::whereCodeType('cidb-g')->whereVendorId($vendor_id)->whereIn('code_id', $grades)->count() > 0;
	}
	
	public function getMatchCidbGrades($vendor_id)
	{
		$grades = $this->cidb_grades->pluck('code_id');
		return VendorCode::whereCodeType('cidb-g')->whereVendorId($vendor_id)->whereIn('code_id', $grades)->get();
	}
	
	public function matchCidbCodes($vendor_id)
	{
		$matched = false;
		$match_cidb_grades = $this->getMatchCidbGrades($vendor_id);
		
		if(count($match_cidb_grades) > 0 && count($this->cidb_codes)) {
			$code_groups = $this->cidb_code_groups;
			foreach($match_cidb_grades as $vendor_code) {
				$grade_matched = false;
		
				foreach($this->cidb_code_groups as $order => $group) {
					$code_matched = false;
					$vendor_code_count = $vendor_code->children()->whereIn('code_id', array_keys($group['codes']))->count();
					$group_code_count = count(array_keys($group['codes']));
					$code_matched = $group['inner_rule'] == 'and' ? $vendor_code_count == $group_code_count : $vendor_code_count > 0;
				
					if($order > 1) {
						$join_rule = $this->cidb_code_groups[$order-1]['join_rule'];
						$grade_matched = $join_rule == 'and' ? $grade_matched && $code_matched : $grade_matched || $code_matched;
					} else {
						$grade_matched = $code_matched;
					}
				}
		
			$matched = $grade_matched || $matched;
			}
		}
	
		return $matched;
	}
	
	public function matchCidbCodesInverse($vendor_id) {
		$vendor = Vendor::find($vendor_id);
		$grades = $this->cidb_grades->pluck('code_id');
		
		if(empty($vendor)) {
			return false;
		}
		
		if(empty($grades)) {
			return true;
		}
		
		// Get parent cidb-g based on $grades in table code_vendor
		$parent_cidbg = $vendor->vendorCodes()->where('code_type', 'cidb-g')->whereIn('code_id', $grades->toArray() )->pluck('id');
		
	
		$group_matched = false;

		foreach($this->cidb_code_groups as $order => $group) {
			$inner_matched = false;
			
			// $codes = $vendor->vendorCodes()->whereCodeType('cidb')->whereIn('code_id', array_keys($group['codes']))->get();
			$codes = $vendor->vendorCodes()->where('code_type','cidb')->whereIn('parent_id', $parent_cidbg->toArray() )->whereIn('code_id', array_keys($group['codes']))->get();
			
			$inner_matched = $inner_matched || ( $group['inner_rule'] == 'and' ? count($codes) == count(array_keys($group['codes'])) : count($codes) > 0 );

		
			foreach($codes as $code) {
				if (!is_array($grades)){
					$grades = $grades->toArray();
				}
				$inner_matched = $inner_matched && in_array($code->parent->code_id, $grades);
			}
		
			if($order > 1) {
				$join_rule = $this->cidb_code_groups[$order-1]['join_rule'];
				$group_matched = ($join_rule == 'and' ? $group_matched && $inner_matched : $group_matched || $inner_matched);
			} else {
				$group_matched = $inner_matched;
			}
		}
	
		return $group_matched;
	}
	
	public function canShowPrices() {
		return time() > strtotime($this->submission_datetime);
	}
	
	public function canShowWinner() {
		return $this->publish_prices;
	}
	
	public function hasBriefing() {
		return isset($this->briefing_datetime) && isset($this->briefing_address);
	}
	
	public function hasException() {
		return $this->allow_exception == 1 ;
	}
	
	public function updateTender($audit = true) {

		$data = request();
		$uploads = request()->file();
	
		if(isset($data['site_visits'])) {
		
			foreach($data['site_visits'] as $visit) {
				if(isset($visit['id']) && !empty($visit['id'])) {
					$site_visit = $this->siteVisits()->find($visit['id']);
				
					if($site_visit) {
						$site_visit->datetime  = Carbon::parse($visit['date']);
						$site_visit->address   = $visit['address'];
						$site_visit->meetpoint = $visit['meetpoint'];
						$site_visit->required  = isset($visit['required']);
						$site_visit->save();
					}
			
				} else {
					$test = $this->siteVisits()->save(new TenderVisit([
						'datetime'  => Carbon::parse($visit['date']),
						'address'   => $visit['address'],
						'meetpoint' => $visit['meetpoint'],
						'required'  => isset($visit['required'])
					]));
				
				}
			}
		}
	
		if(isset($data['deleted_site_visits'])) {
			$this->siteVisits()->whereIn('id', $data['deleted_site_visits'])->delete();
		}
	
		$this->codes()->delete();
		
		if(isset($data['mof_codes'])) {
			foreach($data['mof_codes'] as $order => $d) {
				if(!isset($d['codes'])) break;
				$inner_rule = $d['inner_rule'];
				$join_rule = $d['join_rule'];
				
				foreach($d['codes'] as $code) {
					$this->codes()->save(new TenderCode([
						'code_id'    => $code,
						'code_type'  => 'mof',
						'inner_rule' => $inner_rule,
						'join_rule'  => $join_rule,
						'order'      => $order + 1
					]));
				}
			}
		}
		
		if(isset($data['cidb_codes'])) {
			foreach($data['cidb_codes'] as $order => $d) {
				if(!isset($d['codes'])) break;
				$inner_rule = $d['inner_rule'];
				$join_rule = $d['join_rule'];
			
				foreach($d['codes'] as $code) {
					$this->codes()->save(new TenderCode([
						'code_id'    => $code,
						'code_type'  => 'cidb',
						'inner_rule' => $inner_rule,
						'join_rule'  => $join_rule,
						'order'      => $order + 1
					]));
				}
			}
		}
		
		if(isset($data['cidb_grade'])) {
			$this->codes()->where('code_type', 'cidb-g')->delete();
			foreach($data['cidb_grade'] as $grade) {
				$this->codes()->save(new TenderCode([
					'code_id'    => $grade,
					'code_type'  => 'cidb-g',
					'inner_rule' => '',
					'join_rule'  => ''
				]));
			}
		}
		
		if(count($uploads) > 0) {
			Upload::setRules('store');
			foreach( $data['files'] as $index => $datum) {
				$hash = str_random(40);
				// $file = $uploads[$index]['file'];
				$file = $datum['file'];
				if(is_null($file)) continue;
				
				$upload = [];
				$upload['path']   = public_path() . '/uploads/' . $hash . '/';
				$upload['name']   = $file->getClientOriginalName();
				$upload['size']   = $file->getSize();
				$upload['type']   = $file->getMimeType();
				$upload['public'] = isset($datum['public']);
				$upload['label']  = $datum['name'];
				$upload['url']    = request()->root() . '/uploads/' . $hash . '/' . $upload['name'];
				
				$upload['uploadable_type'] = 'App\Tender';
				$upload['uploadable_id'] = $this->id;
				
				$file->move($upload['path'], $upload['name']);
				
				if($upload['public']) {
					if($upload['type'] == 'application/pdf') {
						if(!is_dir($upload['path'])) {
							mkdir($upload['path']);
						}
						
						try {
							$watermark = new \PDFWatermark(public_path() . '/images/etender-dokumen-watermark.png');
							$watermarker = new \PDFWatermarker($upload['path'] . $upload['name'], $upload['path'] . $upload['name'], $watermark);
							$watermarker->setWatermarkPosition('topright');
							$watermarker->watermarkPdf();                             
						} catch (Exception $e) {
							$message = $upload['name'] . ' tidak berjaya di label sebagai "Dokumen Meja Terkawal". (' . $e->getMessage() . ')';
							session()->flash('upload_errors', array_merge((array) session()->get('upload_errors'), array($message)));
							continue;
						}
					}
				}
				
				$new_upload = new Upload;
				$new_upload->fill($upload);
				$new_upload->save();
			}
		}
		
		if(isset($data['deleted_files'])) {
			$this->files()->whereIn('id', $data['deleted_files'])->delete();
		}
	
		$this->district_id = request('district_id', null);
		if($this->district_id <= 0) $this->district_id = null;
		
		if($audit) TenderHistory::log($this->id, 'edit');
	}
	
	public function getStatusAttribute() {
		if(!$this->approver_id) {
			return 'Belum Disiarkan';
		} elseif($this->approver_id) {
			if($this->publish_prices && $this->publish_winner) {
				return 'Selesai';
			} elseif(!$this->publish_prices) {
				return 'Belum Umum Carta Tender';
			} elseif(!$this->publish_winner) {
				return 'Belum Umum Penender Berjaya';
			} else {
				return 'Disiarkan';
			}
		}
	}

	public function getNegeriListExist() {
		
		$district_list_rule = json_decode($this->district_list_rule);

		if ($district_list_rule === []) {
			$district_list_rule = false;
		}
		
		if ($district_list_rule == null) {
			$district_list_rule = false;
		}
		//ended here - zayid

		$tender_open_for_state_id 		= [];
		$tender_open_for_state_desc		= [];
		$tender_open_for_district_id 	= [];
		$tender_open_for_district_desc 	= [];

		if( $district_list_rule !== false)
		{
			$current_vendor_state_id 	= auth()->user()->vendor->state_id ?? "0";
			$current_vendor_district_id = auth()->user()->vendor->district_id ?? "0";

			foreach($district_list_rule as $row_rules)
			{
				if ($row_rules->district_id == 0 && $row_rules->state_id != 0)
				{
					$tender_open_for_state_id[]		= $row_rules->state_id;
					$tender_open_for_state_desc[]	= \App\Models\RefState::find($row_rules->state_id )->description ?? "";
				}
				elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0)
				{
					$tender_open_for_district_id[]		= $row_rules->district_id;
					$tender_open_for_district_desc[]	= \App\Models\Vendor::$districts[$row_rules->district_id] ?? "";
				}
			}
		}

		$response = false;

		if ($district_list_rule !== false)
		{
			if($this->only_selangor == 2 && count($tender_open_for_state_desc) > 0)
			{
				$response = true;
			}
			elseif($this->only_selangor == 1 || ($this->only_selangor == 2 && count($tender_open_for_state_desc) == 0) )
			{
				$response = true;
			}
			elseif(count($tender_open_for_state_desc) > 0)
			{
				$response = true;
			}
		}

		return $response;
	}

	public function getNegeriList() {
		
		$district_list_rule = json_decode($this->district_list_rule);

		if ($district_list_rule === []) {
			$district_list_rule = false;
		}
		
		if ($district_list_rule == null) {
			$district_list_rule = false;
		}
		//ended here - zayid

		$tender_open_for_state_id 		= [];
		$tender_open_for_state_desc		= [];
		$tender_open_for_district_id 	= [];
		$tender_open_for_district_desc 	= [];

		if( $district_list_rule !== false)
		{
			foreach($district_list_rule as $row_rules)
			{
				if ($row_rules->district_id == 0 && $row_rules->state_id != 0)
				{
					$tender_open_for_state_id[]		= $row_rules->state_id;
					$tender_open_for_state_desc[]	= \App\Models\RefState::find($row_rules->state_id )->description ?? "";
				}
				elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0)
				{
					$tender_open_for_district_id[]		= $row_rules->district_id;
					$tender_open_for_district_desc[]	= \App\Models\Vendor::$districts[$row_rules->district_id] ?? "";
				}
			}
		}

		$response = "";

		if ($district_list_rule !== false)
		{
			if($this->only_selangor == 2 && count($tender_open_for_state_desc) > 0)
			{
				$response = "Selangor, ".implode(", ", $tender_open_for_state_desc);
			}
			elseif($this->only_selangor == 1 || ($this->only_selangor == 2 && count($tender_open_for_state_desc) == 0) )
			{
				$response = "Selangor";
			}
			elseif(count($tender_open_for_state_desc) > 0)
			{
				$response = implode(", ", $tender_open_for_state_desc);
			}
		}

		return $response;
	}

	public function getDaerahListExist() {
		
		$district_list_rule = json_decode($this->district_list_rule);

		if ($district_list_rule === []) {
			$district_list_rule = false;
		}
		
		if ($district_list_rule == null) {
			$district_list_rule = false;
		}
		//ended here - zayid

		$tender_open_for_state_id 		= [];
		$tender_open_for_state_desc		= [];
		$tender_open_for_district_id 	= [];
		$tender_open_for_district_desc 	= [];

		if( $district_list_rule !== false)
		{
			foreach($district_list_rule as $row_rules)
			{
				if ($row_rules->district_id == 0 && $row_rules->state_id != 0)
				{
					$tender_open_for_state_id[]		= $row_rules->state_id;
					$tender_open_for_state_desc[]	= \App\Models\RefState::find($row_rules->state_id )->description ?? "";
				}
				elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0)
				{
					$tender_open_for_district_id[]		= $row_rules->district_id;
					$tender_open_for_district_desc[]	= \App\Models\Vendor::$districts[$row_rules->district_id] ?? "";
				}
			}
		}

		$response = false;

		if ($district_list_rule !== false && !empty($tender_open_for_district_id) )
		{
			$response = true;
		}

		return $response;
	}

	public function getDaerahList() {
		
		$district_list_rule = json_decode($this->district_list_rule);

		if ($district_list_rule === []) {
			$district_list_rule = false;
		}
		
		if ($district_list_rule == null) {
			$district_list_rule = false;
		}
		//ended here - zayid

		$tender_open_for_state_id 		= [];
		$tender_open_for_state_desc		= [];
		$tender_open_for_district_id 	= [];
		$tender_open_for_district_desc 	= [];

		if( $district_list_rule !== false)
		{
			foreach($district_list_rule as $row_rules)
			{
				if ($row_rules->district_id == 0 && $row_rules->state_id != 0)
				{
					$tender_open_for_state_id[]		= $row_rules->state_id;
					$tender_open_for_state_desc[]	= \App\Models\RefState::find($row_rules->state_id )->description ?? "";
				}
				elseif ($row_rules->state_id == 0 && $row_rules->district_id != 0)
				{
					$tender_open_for_district_id[]		= $row_rules->district_id;
					$tender_open_for_district_desc[]	= \App\Models\Vendor::$districts[$row_rules->district_id] ?? "";
				}
			}
		}

		$response = "";

		if ($district_list_rule !== false)
		{
			$response =  implode(", ", $tender_open_for_district_desc);
		}
		
		return $response;
	}
	
	/**
	* Dashboard
	*/
	
	public static function tenderCount() {
		return self::where('type', 'tender')
		->whereNotNull('approver_id')
		->where(DB::raw('YEAR(created_at)'), date('Y'))
		->count();
	}
	
	public static function quotationCount() {
		return self::where('type', 'quotation')
		->whereNotNull('approver_id')
		->where(DB::raw('YEAR(created_at)'), date('Y'))
		->count();
	}
	
	public function codeErrors($vendor_id) {
		$error = false;
	
		if(count($this->mof_codes) > 0) {
			if(!$this->matchCodes($vendor_id, 'mof')) {
				$error = $error || true;
			}
		}
	
		if(count($this->cidb_codes) > 0) {
			$error = $error || !$this->matchCidbGrade($vendor_id) || !$this->matchCidbCodesInverse($vendor_id);
		}
	
		return $error;
	}
	
	public function scopeForPublic($q) {
		return $q->where(function($query){
			$query->whereNull('invitation')->orWhere('invitation', 0);
		});
	}
	
	public function scopeAdvertised($q) {
		return $q->where('advertise_start_date','<=', date('Y-m-d'));
	}
	
	public function scopePublished($q) {
		return $q->whereNotNull('approver_id');
	}
	
	public function scopePublishedPrices($q) {
		return $q->where('publish_prices', '>', 0);
	}
	
	public function scopePublishedWinner($q) {
		return $q->where('publish_winner', '>', 0);
	}
	
	public function saveAudit($action =null) {
		$this->histories()->save(new TenderHistory([
			'action' => $action,
			'user_id' => auth()->check() ? auth()->user()->id : null,
			'changed_data' => serialize(!empty($this->audit_data) ? $this->audit_data : null )
		]));
	}
	
	
	/**
	* Boot Method
	*/
	
	public static function boot() {
		parent::boot();
	
		self::created(function($tender){
			TenderHistory::log($tender->id, 'create');
			cache()->tags('Tender')->flush();
		});
	
		self::updated(function(){
			cache()->tags('Tender')->flush();
		});
	
		self::deleted(function(){
			cache()->tags('Tender')->flush();
		});
	
		static::saving(function ($model) {


			
			$model->preSave();
			if($model->tenderer->is_gateway_locked) {
				$model->only_advertise = $model->tenderer->is_gateway_locked;
			}
		});

		static::saved(function ($model) {
			$model->postSave();
		});
			static::deleted(function ($model) {
			$model->preSave();
			$model->postDelete();
		});
	}
}
