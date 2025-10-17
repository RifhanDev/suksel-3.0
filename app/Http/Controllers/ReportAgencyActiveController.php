<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Excel;
use App\Exports\AgencyActiveYearly;
use App\Exports\AgencyActiveMonthly;
use Illuminate\Support\Facades\Validator;
use App\Transaction;

class ReportAgencyActiveController extends Controller
{
	public function index() {
		$select_type = ['yearly' => 'Tahunan', 'monthly' => 'Bulanan' ];
		$select_year = [];
		
		//$start_year = Carbon::parse(Transaction::orderBy('created_at')->first()->created_at)->format('Y');
		$start_year		= 2006;
		$end_year   = date('Y');
		foreach( range($start_year, $end_year) as $year)
			$select_year[$year] = $year;
		
		return view('reports.agency.active.index', compact('select_type', 'select_year'));
	}
	
	public function view(Request $request) {

		$type   = $request->input('type', null);
		$year   = $request->input('year', null);
		
		$validator = Validator::make([
			'type' => $type,
			'year' => $year,
		],[
			'type' => 'required',
			'year' => 'required'
		]);
		$validator->setAttributeNames([
			'type' => 'Jenis Laporan',
			'year' => 'Tahun'
		]);
		
		if($validator->fails()) {
			$title = '10 Agensi Aktif';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		$function = "render_{$type}";
		return $this->{$function}($year);
	}
	
	public function excel(Request $request) {
		$type   = $request->input('type', null);
		$year   = $request->input('year', null);
		
		return $this->{"excel_" . $type}($year);
	}
	
	public function query_yearly($year) {
		$year_prev = $year - 1;
		$query = "
			SELECT `id`, `name`,
			(SELECT COUNT(*)
			FROM `tender_vendors`
			INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
			INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
			WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
			AND `transactions`.`status` = 'success'
			AND `tender_vendors`.`created_at` BETWEEN '{$year_prev}-01-01 00:00:00' AND '{$year_prev}-12-31 23:59:59'
			AND `tender_vendors`.`participate` = 1
			AND `tender_vendors`.`amount` IS NOT NULL
			AND `tender_vendors`.`amount` NOT LIKE ''
			AND `tender_vendors`.`transaction_id` IS NOT NULL) as count_prev,
			(SELECT SUM(`tender_vendors`.`amount`)
			FROM `tender_vendors`
			INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
			INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
			WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
			AND `transactions`.`status` = 'success'
			AND `tender_vendors`.`created_at` BETWEEN '{$year_prev}-01-01 00:00:00' AND '{$year_prev}-12-31 23:59:59'
			AND `tender_vendors`.`participate` = 1
			AND `tender_vendors`.`amount` IS NOT NULL
			AND `tender_vendors`.`amount` NOT LIKE ''
			AND `tender_vendors`.`transaction_id` IS NOT NULL) as amount_prev,
			(SELECT COUNT(*)
			FROM `tender_vendors`
			INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
			INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
			WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
			AND `transactions`.`status` = 'success'
			AND `tender_vendors`.`created_at` BETWEEN '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:59'
			AND `tender_vendors`.`participate` = 1
			AND `tender_vendors`.`amount` IS NOT NULL
			AND `tender_vendors`.`amount` NOT LIKE ''
			AND `tender_vendors`.`transaction_id` IS NOT NULL) as count,
			(SELECT SUM(`tender_vendors`.`amount`)
			FROM `tender_vendors`
			INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
			INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
			WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
			AND `transactions`.`status` = 'success'
			AND `tender_vendors`.`created_at` BETWEEN '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:59'
			AND `tender_vendors`.`participate` = 1
			AND `tender_vendors`.`amount` IS NOT NULL
			AND `tender_vendors`.`amount` NOT LIKE ''
			AND `tender_vendors`.`transaction_id` IS NOT NULL) as amount
			
			FROM `organization_units`
			ORDER BY `count` DESC
			LIMIT 0,10;";
			
		return DB::select($query);
	}
	
	public function render_yearly($year) {
		$data = $this->query_yearly($year);
		return view('reports.agency.active.yearly', compact('data', 'year'));
	}
	
	public function excel_yearly($year) {
		$data = $this->query_yearly($year);

		return Excel::download(new AgencyActiveYearly($data, $year), 'Laporan 10 Agensi Aktif (Tahunan).xlsx');
	}
	
	public function query_monthly($year) {
		$query = [];
		$query [] = "SELECT `id`, `short_name`,";
	
		foreach(range(1,12) as $m) {
			$start  = Carbon::createFromDate($year, $m, 1)->startOfMonth();
			$end    = Carbon::createFromDate($year, $m, 1)->endOfMonth();
			$month  = strtolower(date('M', strtotime($start)));
			
			$query [] = "
				(SELECT COUNT(*)
				FROM `tender_vendors`
				INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
				INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
				WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
				AND `transactions`.`status` = 'success'
				AND `tender_vendors`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
				AND `tender_vendors`.`participate` = 1
				AND `tender_vendors`.`amount` IS NOT NULL
				AND `tender_vendors`.`amount` NOT LIKE ''
				AND `tender_vendors`.`transaction_id` IS NOT NULL) as count_{$month},
				(SELECT SUM(`tender_vendors`.`amount`)
				FROM `tender_vendors`
				INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
				INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
				WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
				AND `transactions`.`status` = 'success'
				AND `tender_vendors`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
				AND `tender_vendors`.`participate` = 1
				AND `tender_vendors`.`amount` IS NOT NULL
				AND `tender_vendors`.`amount` NOT LIKE ''
				AND `tender_vendors`.`transaction_id` IS NOT NULL) as amount_{$month},";
	
		}
	
		$start  = Carbon::createFromDate($year, 1, 1)->startOfYear();
		$end    = Carbon::createFromDate($year, 1, 1)->endOfYear();
	
		$query [] = "
			(SELECT COUNT(*)
			FROM `tender_vendors`
			INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
			INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
			WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
			AND `transactions`.`status` = 'success'
			AND `tender_vendors`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
			AND `tender_vendors`.`participate` = 1
			AND `tender_vendors`.`amount` IS NOT NULL
			AND `tender_vendors`.`amount` NOT LIKE ''
			AND `tender_vendors`.`transaction_id` IS NOT NULL) as count,
			(SELECT SUM(`tender_vendors`.`amount`)
			FROM `tender_vendors`
			INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
			INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
			WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
			AND `transactions`.`status` = 'success'
			AND `transactions`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
			AND `tender_vendors`.`participate` = 1
			AND `tender_vendors`.`amount` IS NOT NULL
			AND `tender_vendors`.`amount` NOT LIKE ''
			AND `tender_vendors`.`transaction_id` IS NOT NULL) as amount
			
			FROM `organization_units`
			ORDER BY `count` DESC
			LIMIT 0,10;";
		
		return DB::select(implode('', $query));
	}
	
	public function render_monthly($year) {
		$data = $this->query_monthly($year);
		dd($data);
		return view('reports.agency.active.monthly', compact('data', 'year'));
	}
	
	public function excel_monthly($year) {
		$data = $this->query_monthly($year);
		return Excel::download(new AgencyActiveMonthly($data, $year), 'Laporan 10 Agensi Aktif (Bulanan).xlsx');
	}
	
}
