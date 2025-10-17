<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;
use App\Exports\AgencyDaily;
use Illuminate\Support\Facades\Validator;
use App\OrganizationUnit;
use App\TenderVendor;

class ReportAgencyDailyController extends Controller
{
	public function index() {
      $select_ou = OrganizationUnit::orderBy('name')->pluck('name', 'id');
      return view('reports.agency.daily.index', compact('select_ou'));
   }

   public function view(Request $request) {

		$ou     = auth()->user()->hasRole('Admin') ? $request->input('ou', null) : auth()->user()->organization_unit_id;
		$date   = $request->input('date', null);
		$time   = $request->input('time', null);
		$method = $request->input('method', null);
		$type   = $request->input('type', null);
		
		$validator = Validator::make([
			'ou'   => $ou,
			'date' => $date
		],[
			'ou'   => 'required',
			'date' => 'required',
		]);
		$validator->setAttributeNames([
			'ou'   => 'Agensi',
			'date' => 'Tarikh'
		]);
		
		if($validator->fails()) {
			$title = 'Transaksi Harian Agensi';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		$agency                     = OrganizationUnit::find($ou);
		list($purchases, $amount)   = $this->query($ou, $date, $time, $method, $type);
		
		return view('reports.agency.daily.view', compact('agency', 'date', 'time', 'method', 'purchases', 'amount', 'type'));
   }

   protected function excel(Request $request) {
        	$ou     = auth()->user()->hasRole('Admin') ? $request->input('ou', null) : auth()->user()->organization_unit_id;
        	$date   = $request->input('date', null);
        	$time   = $request->input('time', null);
        	$method = $request->input('method', null);

        	$agency                     = OrganizationUnit::find($ou);
        	list($purchases, $amount)   = $this->query($ou, $date, $time, $method);

        	ob_end_clean();
        	ob_start();

        	return Excel::download(new AgencyDaily($agency, $date, $time, $method, $purchases, $amount), 'Transaksi Harian Agensi '.$agency->name.'.xlsx');
   }

   protected function query($ou, $date, $time=null, $method=null) {
        	
        	if($time) {
            $date   = Carbon::parse($date . ' ' . $time);
            $start  = $date->copy()->subDay();
            $end    = $date->copy();
        	} else {
            $date   = Carbon::parse($date);
            $start  = $date->copy()->startOfDay();
            $end    = $date->copy()->endOfDay();
        	}

        	$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
                    ->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
                    ->join('organization_units', 'tenders.organization_unit_id', '=', 'organization_units.id')
                    ->join('vendors', 'vendors.id', '=', 'tender_vendors.vendor_id')
                    ->with('tender', 'vendor', 'transaction', 'transaction.gateway', 'tender.tenderer')
                    ->where('transactions.status', 'success')
                    ->whereBetween('transactions.created_at', [$start, $end])
                    ->where('tenders.organization_unit_id', $ou)
                    ->orderBy('transactions.created_at', 'asc');

        	if($method) $base = $base->where('transactions.method', $method);

        	$purchases  = $base
                    		->get([
		                        'tender_vendors.*',
		                        'vendors.name',
		                        'tenders.name',
		                        'tenders.ref_number',
		                        'tenders.created_at',
		                        'tenders.document_start_date',
		                        'tenders.document_stop_date',
		                        'tenders.advertise_start_date',
		                        'tenders.advertise_stop_date',
		                        'tenders.submission_datetime',
		                        'transactions.created_at',
		                        'transactions.number',
		                        'transactions.vendor_id',
		                        'transactions.gateway_reference',
		                        'transactions.method'
                    		]);
        	$amount     = sprintf('%.2f', $base->sum('tender_vendors.amount'));

        	return [$purchases, $amount];
   }

  //  public function __construct() {
		// parent::__construct();
		// View::share('controller', 'ReportAgencyDaily');
  //  }
}
