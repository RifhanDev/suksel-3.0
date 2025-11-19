<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Vendor;
use App\VendorBlacklist;

class VendorBlacklistsController extends Controller
{
	public function index(Request $request, $vendor_id = null) {

		$vendor = null;

		if( !VendorBlacklist::canList() )
			return $this->_access_denied();
	
		if($vendor_id != null)
			$vendor = Vendor::findOrFail($vendor_id);
		
		if($request->ajax()) {
				$blacklists = VendorBlacklist::with('agency')->with('vendor');
				if(isset($vendor))
					$blacklists = $blacklists->where('vendor_id', $vendor->id);
			
				$blacklists = $blacklists->select([
					'vendor_blacklists.id',
					'vendor_blacklists.vendor_id',
					'vendor_blacklists.organization_unit_id',
					'vendor_blacklists.reason',
					'vendor_blacklists.start',
					'vendor_blacklists.end',
					'vendor_blacklists.status',
					'vendor_blacklists.id as actions'
				]);
			
				$datatable = Datatables::of($blacklists)
									->editColumn('organization_unit_id', function($blacklist) {
										if($blacklist->agency) {
											return $blacklist->agency->name;
										} else {
											return boolean_icon(false);
										}
									})
									->editColumn('start', function($blacklist){
										return $blacklist->start_date;
									})
									->editColumn('end', function($blacklist){
										return $blacklist->end_date;
									})
									->editColumn('status', function($blacklist){
										return VendorBlacklist::$statuses[$blacklist->status];
									})
									->editColumn('actions', function($blacklist) use ($vendor_id) {
										$actions   = ['<div class="btn-group">'];
										if(!isset($vendor_id)) 
											$actions[] = link_to_route('vendors.show', 'Maklumat Syarikat', $blacklist->vendor_id, ['class' => 'btn btn-xs btn-primary']);
										
										$actions[] = $blacklist->canUpdate() ? link_to_route('vendor.blacklists.edit', 'Kemaskini', [$blacklist->vendor_id, $blacklist->id], ['class' => 'btn btn-xs btn-warning'] ) : '';
										
										if($blacklist->file)
											$actions[] = link_to_route('vendor.blacklists.file', 'Lihat Lampiran', [$blacklist->vendor_id, $blacklist->id], ['class' => 'btn btn-xs btn-primary', 'target' => '_blank']);
										
										if($blacklist->canCancel())
											$actions[] = link_to_route('vendor.blacklists.unblacklist', 'Batal', [$blacklist->vendor_id, $blacklist->id], ['class' => 'btn btn-xs btn-danger'] );
										
										$actions[] = '</div>';
										return implode(' ', $actions);
									});
				
				if(isset($vendor)) {
					$datatable = $datatable->removeColumn('vendor_id');
				} else {
					$datatable = $datatable->editColumn('vendor_id', function($blacklist){
						return $blacklist->vendor->name;
					});
				}
			
				if(auth()->user()->ability(['Agency Admin', 'Agency User'], [])) {
					$datatable = $datatable->removeColumn('organization_unit_id');
				}
			
				return $datatable
					->removeColumn('id')
					->rawColumns(['vendor_id','organization_unit_id','reason','start','end','status','actions'])
					->make();
		}
	
		$ajax_url = isset($vendor) ? route('vendor.blacklists.index', [$vendor->id]) : route('blacklists.index');
		return view('blacklists.index', compact('vendor', 'ajax_url'));
	}
	
	public function create($vendor_id) {
		if( !VendorBlacklist::canCreate() || !$vendor_id )
			return $this->_access_denied();
		
		$vendor = Vendor::findOrFail($vendor_id);
		$blacklist = new VendorBlacklist;
		return view('blacklists.create', compact('vendor', 'blacklist'));
	}
	
	public function store(Request $request, $vendor_id) {
		if( !VendorBlacklist::canCreate() || !$vendor_id )
			return $this->_access_denied();
		
		$vendor = Vendor::findOrFail($vendor_id);
		$data   = $request->all();
		
		if(isset($data['start']))   $data['start']  = Carbon::parse($data['start'])->format('Y-m-d');
		if(isset($data['end']))     $data['end']    = Carbon::parse($data['end'])->format('Y-m-d');
		if(!isset($data['organization_unit_id']) || $data['organization_unit_id'] == 0)   $data['organization_unit_id'] = null;
		
		$validator  = Validator::make($data, VendorBlacklist::$rules);
		$validator->setAttributeNames([
			'start' => 'Tarikh Mula',
			'end'   => 'Tarikh Tamat'
		]);
		
		if($validator->fails()) {
		return redirect()->back()
			->withInput()
			->withErrors($validator)
			->with('danger', 'Pengesahan data gagal.');
		}
		
		$blacklist = new VendorBlacklist;
		$blacklist->user()->associate(auth()->user());
		$blacklist->vendor()->associate($vendor);
		$blacklist->fill($data);
		$blacklist->status = 'active';
		$blacklist->save();
		
		return redirect('vendor/'.$vendor->id.'/blacklists')->with('success', 'Rekod dicipta.');
	}
	
	public function edit($vendor_id, $blacklist_id) {

		if( !$vendor_id )
		return $this->_access_denied();
		
		$vendor    = Vendor::findOrFail($vendor_id);
		$blacklist = $vendor->blacklists()->findOrFail($blacklist_id);

		
		if(! $blacklist->canUpdate())
			return $this->_access_denied();
		
		return view('blacklists.edit', compact('vendor', 'blacklist'));
	}
	
	public function update(Request $request, $vendor_id, $blacklist_id) {
		if( !$vendor_id )
		return $this->_access_denied();
		
		$vendor     = Vendor::findOrFail($vendor_id);
		$blacklist  = $vendor->blacklists()->findOrFail($blacklist_id);
		
		if(! $blacklist->canUpdate())
			return $this->_access_denied();
		
		$data = $request->all();
		
		if(isset($data['start']))   $data['start']  = Carbon::parse($data['start'])->format('Y-m-d');
		if(isset($data['end']))     $data['end']    = Carbon::parse($data['end'])->format('Y-m-d');
		if(!isset($data['organization_unit_id']) || $data['organization_unit_id'] == 0)   $data['organization_unit_id'] = null;
		
		$validator  = Validator::make($data, VendorBlacklist::$rules);
		$validator->setAttributeNames([
			'start' => 'Tarikh Mula',
			'end'   => 'Tarikh Tamat'
		]);
		
		if($validator->fails()) {
			return redirect()->back()
				->withInput()
				->withErrors($validator)
				->with('danger', 'Pengesahan data gagal.');
		}
		
		$blacklist->fill($data);
		$blacklist->save();
		
		return redirect('vendor/'.$vendor->id.'/blacklists')->with('success', 'Rekod dicipta.');
	}
	
	public function show($vendor_id, $request_id) {

		if( $vendor_id )
		$vendor = Vendor::findOrFail($vendor_id);
		
		$blacklist = VendorBlacklist::findOrFail(Route::input('blacklists'));
		
		if( !$blacklist->canShow() )
		$this->_access_denied();
		
		return view('blacklists.show', compact('vendor', 'blacklist'));
	}
	
	public function unblacklist($vendor_id, $blacklist_id) {
		if( $vendor_id )
		$vendor = Vendor::findOrFail($vendor_id);
		
		$blacklist = VendorBlacklist::findOrFail($blacklist_id);
		
		if( !$blacklist->canCancel() )
		return $this->_access_denied();
		
		return view('blacklists.unblacklist', compact('vendor', 'blacklist'));
	}
	
	public function cancel($vendor_id, $blacklist_id) {
		if( $vendor_id )
		$vendor = Vendor::findOrFail($vendor_id);
		
		$blacklist = VendorBlacklist::findOrFail($blacklist_id);
		
		if( !$blacklist->canCancel() )
			return $this->_access_denied();
		
		$data = request()->all();
		
		$blacklist->status = 'cancelled';
		$blacklist->fill($data);
		$blacklist->save();
		
		if(isset($vendor)) {
			$redirect = redirect('vendor/'.$vendor->id.'/blacklists');
		} else {
			$redirect = redirect('blacklists');
		}
		
		return $redirect->with('success', 'Senarai Hitam telah dibatalkan.');
	}
	
	public function file($vendor_id, $blacklist_id) {
		if( $vendor_id )
		$vendor = Vendor::findOrFail($vendor_id);
		
		$blacklist = VendorBlacklist::findOrFail($blacklist_id);
		
		if( !$blacklist->canShow() || !$blacklist->file )
		$this->_access_denied();
		
		header("Content-type: {$blacklist->file->type}");
		readfile($blacklist->file->getPath());        
	}
	
	/**
	* Constructor
	*/
	
	// public function __construct()
	// {
	// parent::__construct();
	// View::share('controller', 'VendorBlacklist');
	// }
}
