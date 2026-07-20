<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Excel;
use App\Exports\AgencyTypeYearly;
use App\Exports\AgencyTypeMonthly;
use Illuminate\Support\Facades\Validator;
use App\OrganizationType;
use App\Transaction;

class ReportAgencyTypeController extends Controller
{
	public function index() {
      $select_ou_type = OrganizationType::orderBy('name', 'asc')->pluck('name', 'id');
      $select_type    = ['yearly' => 'Tahunan', 'monthly' => 'Bulanan' ];
      $select_year    = [];

      //$start_year     = Carbon::parse(Transaction::orderBy('created_at')->first()->created_at)->format('Y');
      $start_year		= 2006;
	  $end_year       = date('Y');
      foreach( range($start_year, $end_year) as $year)
         $select_year[$year] = $year;

      return view('reports.agency.type.index', compact('select_ou_type', 'select_type', 'select_year'));
  	}

   public function excel() {
		$ou_type    = $request->input('ou_type', null);
		$type       = $request->input('type', null);
		$year_start = $request->input('year_start', null);
		$year_end   = $request->input('year_end', null);
		
		return $this->{'excel_' . $type}($ou_type, $year_start, $year_end);
   }

   public function view(Request $request) {

		$ou_type    = $request->input('ou_type', null);
		$type       = $request->input('type', null);
		$year_start = $request->input('year_start', null);
		$year_end   = $request->input('year_end', null);
		
		$validator = Validator::make([
			'ou_type'    => $ou_type,
			'type'       => $type,
			'year_start' => $year_start,
			'year_end'   => $year_end
		],[
			'ou_type'    => 'required|exists:organization_types,id',
			'type'       => 'required',
			'year_start' => 'required'
		]);
		$validator->setAttributeNames([
			'ou_type'    => 'Kategori Agensi',
			'type'       => 'Jenis Laporan',
			'year_start' => empty($year_end) ? 'Tahun' : 'Mulai Tahun',
			'year_end'   => 'hingga'
		]);
		
		if($validator->fails()) {
			$title = 'Transaksi Mengikut Kategori Agensi';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		$function = "render_{$type}";
		return $this->{$function}($ou_type, $year_start, $year_end);
   }

   public function query_yearly($ou_type_id, $years) {
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
				WHERE `organization_units`.`type_id` = {$ou_type_id}
				ORDER BY `organization_units`.`name` ASC;";
    
        	return DB::select(implode('', $query));
   }

   public function render_yearly($ou_type_id, $year_start, $year_end) {
        	$years = range($year_start, $year_end);
        	$years = array_unique($years);
        	sort($years);
        	$ou_type = OrganizationType::find($ou_type_id);

        	$data = $this->query_yearly($ou_type_id, $years);

        	return view('reports.agency.all.yearly', compact('ou_type', 'data', 'years', 'year_start', 'year_end'));
   }

   public function excel_yearly($ou_type_id, $year_start, $year_end) {
        	$years = range($year_start, $year_end);
        	sort($years);

        	$data = $this->query_yearly($ou_type_id, $years);

        	return Excel::download(new AgencyTypeYearly($data, $years), 'Trans Kategori Agensi.xlsx');
   }

   public function query_monthly($ou_type_id, $year) {
        	
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
				WHERE `organization_units`.`type_id` = {$ou_type_id}
				ORDER BY `count` DESC, `organization_units`.`name` ASC;";
    
        return DB::select(implode('', $query));
   }

   public function render_monthly($ou_type_id, $year_start, $year_end=null) {
        	$year = $year_start;
        	$data = $this->query_monthly($ou_type_id, $year);
        	$ou_type = OrganizationType::find($ou_type_id);
        	return view('reports.agency.type.monthly', compact('ou_type', 'data', 'year', 'year_start', 'year_end'));
   }

   public function excel_monthly($ou_type_id, $year_start, $year_end) {
        	$year = $year_start;
        	$data = $this->query_monthly($ou_type_id, $year);

        	return Excel::download(new AgencyTypeMonthly($data, $year), 'Trans Kategori Agensi.xlsx');
   }

}
