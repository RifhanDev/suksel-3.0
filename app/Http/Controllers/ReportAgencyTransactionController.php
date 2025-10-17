<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Excel;
use App\Exports\AgencyTransaction;
use App\TenderVendor;
use App\OrganizationUnit;
use App\Transaction;

class ReportAgencyTransactionController extends Controller
{
	public function index() {
		$select_ou      = OrganizationUnit::orderBy('name')->pluck('name', 'id');
		$select_year    = [];
		//$start_year     = Carbon::parse(Transaction::orderBy('created_at')->first()->created_at)->format('Y');
		$start_year		= 2006;
		$end_year       = date('Y');
		foreach( range($start_year, $end_year) as $year)
			$select_year[$year] = $year;
		
		$select_month   = [];
		foreach(range(1,12) as $month)
			$select_month[$month] = date('F', mktime(null, null, null, sprintf('%02d', $month), 1));
		
		return view('reports.agency.transaction.index', compact('select_ou', 'select_year', 'select_month'));
   }

   public function excel(Request $request) {

			$year   = $request->input('year', null);
			$month  = $request->input('month', null);
			$ou     = auth()->user()->hasRole('Admin') ? $request->input('ou', null) : auth()->user()->organization_unit_id;
			
			$start  = Carbon::createFromDate($year, $month, 1)->startOfMonth();
			$end    = Carbon::createFromDate($year, $month, 1)->endOfMonth();
			$agency = OrganizationUnit::find($ou);

        	$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
                    ->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
                    ->join('organization_units', 'tenders.organization_unit_id', '=', 'organization_units.id')
                    ->join('vendors', 'vendors.id', '=', 'tender_vendors.vendor_id')
                    ->with('tender', 'vendor', 'transaction', 'transaction.gateway', 'tender.tenderer')
                    ->where('transactions.status', 'success')
                    ->whereBetween('transactions.created_at', [$start, $end])
                    ->where('tenders.organization_unit_id', $ou)
                    ->orderBy('tenders.created_at', 'asc');

        	$tenders    = $base
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
                        ])->groupBy('ref_number');

            $export = new AgencyTransaction($tenders);

        	return Excel::download($export, 'Transaksi Mengikut Tender.xlsx');
    }

   public function view(Request $request) {

		$year  = $request->input('year', null);
		$month = $request->input('month', null);
		$ou    = auth()->user()->hasRole('Admin') ? $request->input('ou', null) : auth()->user()->organization_unit_id;

		$validator = Validator::make([
			'ou'    => $ou,
			'year'  => $year,
			'month' => $month
		],[
			'ou'    => 'required|exists:organization_units,id',
			'year'  => 'required',
			'month' => 'required'
		]);
		$validator->setAttributeNames([
			'ou'    => 'Agensi',
			'year'  => 'Tahun',
			'month' => 'Bulan'
		]);
		
		if($validator->fails()) {
			$title = 'Transaksi Agensi Mengikut Tender';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}

			$start  = Carbon::createFromDate($year, $month, 1)->startOfMonth();
			$end    = Carbon::createFromDate($year, $month, 1)->endOfMonth();
			$agency = OrganizationUnit::find($ou);

        	$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
                    	->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
                    	->join('organization_units', 'tenders.organization_unit_id', '=', 'organization_units.id')
                    	->join('vendors', 'vendors.id', '=', 'tender_vendors.vendor_id')
                    	->with('tender', 'vendor', 'transaction', 'transaction.gateway', 'tender.tenderer')
                    	->where('transactions.status', 'success')
                    	->whereBetween('transactions.created_at', [$start, $end])
                    	->where('tenders.organization_unit_id', $ou)
                    	->orderBy('tenders.created_at', 'asc');

        	$tenders = $base
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
							])->groupBy('ref_number');
        	$amount = sprintf('%.2f', $base->sum('tender_vendors.amount'));

        	return view('reports.agency.transaction.view', compact('start', 'end', 'tenders', 'year', 'month', 'agency', 'amount'));
   }

   public function receipts(Request $request) {

		$year  = $request->input('year', null);
		$month = $request->input('month', null);
		$ou    = auth()->user()->hasRole('Admin') ? $request->input('ou', null) : auth()->user()->organization_unit_id;
		$page  = $request->input('page', null);
		$limit = $request->input('limit', null);

     	$validator = Validator::make([
			'ou'    => $ou,
			'year'  => $year,
			'month' => $month
     	],[
			'ou'    => 'required|exists:organization_units,id',
			'year'  => 'required',
			'month' => 'required'
     	]);
     	$validator->setAttributeNames([
			'ou'    => 'Agensi',
			'year'  => 'Tahun',
			'month' => 'Bulan'
     	]);

     	if($validator->fails()) {
         $title = 'Transaksi Agensi Mengikut Tender';
         $error = 'Sila pastikan pilihan medan adalah betul.';
         return view('reports.error', compact('title', 'error'));
     	}

     	$start  = Carbon::createFromDate($year, $month, 1)->startOfMonth();
     	$end    = Carbon::createFromDate($year, $month, 1)->endOfMonth();
     	$agency = OrganizationUnit::find($ou);

     	$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
                 	->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
                 	->join('organization_units', 'tenders.organization_unit_id', '=', 'organization_units.id')
                 	->join('vendors', 'vendors.id', '=', 'tender_vendors.vendor_id')
                 	->with('tender', 'vendor', 'transaction', 'transaction.gateway', 'tender.tenderer')
                 	->where('transactions.status', 'success')
                 	->whereBetween('transactions.created_at', [$start, $end])
                 	->where('tenders.organization_unit_id', $ou)
                 	->orderBy('tenders.created_at', 'asc')
                 	->orderBy('transactions.created_at', 'asc');

      if($page && $limit) {
         $base = $base->take($limit);

         if($page > 1) {
            $base = $base->skip($page * $limit);
         }
      }

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
      return view('reports.agency.transaction.receipts', compact('purchases'));
  	}

}
