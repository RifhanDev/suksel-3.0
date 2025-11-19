<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;
use App\Exports\Revenue;
use App\Transaction;
use App\TenderVendor;
use App\Subscription;

class ReportRevenueController extends Controller
{
	public function index() {
		$select_year = [];
		//$start_year  = Carbon::parse(Transaction::orderBy('created_at')->first()->created_at)->format('Y');
		$start_year		= 2006;
		$end_year    = date('Y');
		foreach( range($start_year, $end_year) as $year)
			$select_year[$year] = $year;
		
		return view('reports.revenue.index', compact('select_year'));
	}
	
	public function data($years, $fields) {
	$data = [];
	
	foreach($years as $year) {
		$data[$year] = [];
		$start  = sprintf('%04d-01-01 00:00:00', $year);
      $end    = sprintf('%04d-12-31 23:59:59', $year);
		
		if(in_array('tender', $fields)) {
			$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
			->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
			->where('tenders.type', 'tender')
			->where('transactions.status', 'success')
			->where('tender_vendors.participate', 1)
			->whereBetween('transactions.created_at', [$start, $end]);
			
			$count  = $base->count();
			$value  = $base->sum('tender_vendors.amount');
			
			$data[$year]['tender'] = [
				'count' => $count,
				'value' => $value
			];
		}
	
		if(in_array('quotation', $fields)) {
			$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
				->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
				->where('tenders.type', 'quotation')
				->where('transactions.status', 'success')
				->where('tender_vendors.participate', 1)
				->whereBetween('transactions.created_at', [$start, $end]);
			
			$count  = $base->count();
			$value  = $base->sum('tender_vendors.amount');
			
			$data[$year]['quotation'] = [
				'count' => $count,
				'value' => $value
			];
		}
	
		if(in_array('transaction', $fields)) {
			$base = TenderVendor::join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
				->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
				->where('transactions.status', 'success')
				->where('tender_vendors.participate', 1)
				->whereBetween('transactions.created_at', [$start, $end]);
			
			$count  = $base->count();
			$value  = $base->sum('tender_vendors.amount');
			
			$data[$year]['transaction'] = [
				'count' => $count,
				'value' => $value
			];
		}
	
		if(in_array('registration', $fields)) {
			$base  = Subscription::whereRenewal(0)->whereBetween('start_date', [$start, $end]);
			$count = $base->count();
			
			$data[$year]['registration'] = [
				'count' => $count
			];
		}
	
		if(in_array('renewal', $fields)) {
			$base  = Subscription::whereRenewal(1)->whereBetween('start_date', [$start, $end]);
			$count = $base->count();
			
			$data[$year]['renewal'] = [
				'count' => $count
			];
			}
		}
	
		return $data;
	}
	
	public function excel(Request $request) {

		$fields = $request->input('fields', []);
		$years  = $request->input('years', []);
		$data   = $this->data($years, $fields);

		return Excel::download(new Revenue($data, $fields, $years), 'Hasil Transaski Tahunan.xlsx');

	}
	
	public function view(Request $request) {

		$fields     = [];
		$year_start = $request->input('year_start', null);
		$year_end   = $request->input('year_end', null);
		
		$years = range($year_start, $year_end);
		$years = array_unique($years);
		sort($years);
		
		if($request->input('tender'))        $fields[] = 'tender';
		if($request->input('quotation'))     $fields[] = 'quotation';
		if($request->input('transaction'))   $fields[] = 'transaction';
		if($request->input('registration'))  $fields[] = 'registration';
		if($request->input('renewal'))       $fields[] = 'renewal';
		
		$data = $this->data($years, $fields);
		
		return view('reports.revenue.view', compact('data', 'fields', 'years'));
	}
	
}
