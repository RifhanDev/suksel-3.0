<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Excel;
use App\Exports\AgencyAllYearly;
use App\Exports\AgencyAllMonthly;
use Illuminate\Support\Facades\Validator;
use App\Transaction;

class ReportAgencyAllController extends Controller
{
	public function index() {
		$select_type    = ['yearly' => 'Tahunan', 'monthly' => 'Bulanan' ];
		$select_year    = [];
		
		//$start_year     = Carbon::parse(Transaction::orderBy('created_at')->first()->created_at)->format('Y');
		$start_year		= 2006;
		$end_year       = date('Y');
		
		foreach( range($start_year, $end_year) as $year)
			$select_year[$year] = $year;
		
		return view('reports.agency.all.index', compact('select_type', 'select_year'));
	}

   public function view(Request $request) {

		$type       = $request->input('type', null);
		$year_start = $request->input('year_start', null);
		$year_end   = $request->input('year_end', null);
		
		$validator = Validator::make([
			'type'       => $type,
			'year_start' => $year_start,
			'year_end'   => $year_end
		],[
			'type'       => 'required',
			'year_start' => 'required'
		]);
		$validator->setAttributeNames([
			'type'       => 'Jenis Laporan',
			'year_start' => empty($year_end) ? 'Tahun' : 'Mulai Tahun',
			'year_end'   => 'hingga'
		]);
		
		if($validator->fails()) {
			$title = 'Transaksi Semua Agensi';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		$function = "render_{$type}";
		return $this->{$function}($year_start, $year_end);
   }

   public function excel(Request $request) {
        	$type       = $request->input('type', null);
        	$year_start = $request->input('year_start', null);
        	$year_end   = $request->input('year_end', null);

        	return $this->{'excel_' . $type}($year_start, $year_end);
   }

   public function render_yearly($year_start, $year_end) {
        	$years = range($year_start, $year_end);
        	sort($years);

        	$data = $this->query_yearly($years);
        	return view('reports.agency.all.yearly', compact('data', 'years', 'year_start', 'year_end'));
   }

   public function excel_yearly($year_start, $year_end) {
        	$years = range($year_start, $year_end);
        	sort($years);

        	$data = $this->query_yearly($years);

        	return Excel::download(new AgencyAllYearly($data, $years), 'Transaksi Semua Agensi.xlsx');
   }

   public function query_yearly($years) {
        	$query = [];
        	$query[] = "SELECT `id`,";

        	foreach($years as $year) {
            $start  = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $end    = Carbon::createFromDate($year, 1, 1)->endOfYear();

            $query[] = "
					(SELECT COUNT(*)
					    	FROM `tender_vendors`
					    	INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
					    	INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
					    	WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
					    	AND `transactions`.`status` = 'success'
					    	AND `transactions`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
					    	AND `tender_vendors`.`participate` = 1
					    	AND `tender_vendors`.`amount` IS NOT NULL
					    	AND `tender_vendors`.`amount` NOT LIKE ''
					    	AND `tender_vendors`.`transaction_id` IS NOT NULL) as count_{$year},
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
					    	AND `tender_vendors`.`transaction_id` IS NOT NULL) as amount_{$year},";
        	}

        	$query[] = "
					`name`
					FROM `organization_units`
					ORDER BY `organization_units`.`name` ASC;";
    
        	return DB::select(implode('', $query));
   }

 	public function render_monthly($year_start, $year_end=null) {
        $year = $year_start;
        $data = $this->query_monthly($year);
        return view('reports.agency.all.monthly', compact('data', 'year', 'year_start', 'year_end'));
   }

   public function excel_monthly($year_start, $year_end) {
        	$year = $year_start;
        	$data = $this->query_monthly($year);

        	return Excel::download(new AgencyAllMonthly($data, $year), 'Transaksi Semua Agensi.xlsx');
   }

   public function query_monthly($year) {
        	$query = [];
        	$query[] = "SELECT `id`, `short_name`,";

        	foreach(range(1,12) as $m) {
            $start  = Carbon::createFromDate($year, $m, 1)->startOfMonth();
            $end    = Carbon::createFromDate($year, $m, 1)->endOfMonth();
            $month  = strtolower(date('M', strtotime($start)));

            $query[] = "
				(SELECT COUNT(*)
					FROM `tender_vendors`
					INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
					INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
					WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
					AND `transactions`.`status` = 'success'
					AND `transactions`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
					AND `tender_vendors`.`participate`                             = 1
					AND `tender_vendors`.`amount` IS NOT NULL
					AND `tender_vendors`.`amount` NOT LIKE ''
					AND `tender_vendors`.`transaction_id` IS NOT NULL) as count_{$month},
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
					AND `tender_vendors`.`transaction_id` IS NOT NULL) as amount_{$month},";
		   }

		        	$start  = Carbon::createFromDate($year, 1, 1)->startOfYear();
		        	$end    = Carbon::createFromDate($year, 1, 1)->endOfYear();

		        	$query[] = "
				(SELECT COUNT(*)
					FROM `tender_vendors`
					INNER JOIN `tenders` ON `tender_vendors`.`tender_id` = `tenders`.`id`
					INNER JOIN `transactions` ON `tender_vendors`.`transaction_id` = `transactions`.`id`
					WHERE `tenders`.`organization_unit_id` = `organization_units`.`id`
					AND `transactions`.`status` = 'success'
					AND `transactions`.`created_at` BETWEEN '{$start->format('Y-m-d H:i:s')}' AND '{$end->format('Y-m-d H:i:s')}'
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
				ORDER BY `organization_units`.`name` ASC;";
    
        return DB::select(implode('', $query));
   }


}
