<?php

namespace App\Http\Controllers;

use App\Exports\AgencyTransaction;
use App\Http\Controllers\Concerns\AuthorizesReportAccess;
use App\OrganizationUnit;
use App\TenderVendor;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportAgencyTransactionController extends Controller
{
	use AuthorizesReportAccess;

	public function index()
	{
		if ($denied = $this->denyUnlessReport('Report:view:agency_tender', 'Report:view:agency_tender:organization_unit_id')) {
			return $denied;
		}

		$select_ou = OrganizationUnit::orderBy('name')->pluck('name', 'id');
		$select_year = [];
		$start_year = 2006;
		$end_year = date('Y');
		foreach (range($start_year, $end_year) as $year) {
			$select_year[$year] = $year;
		}

		$select_month = [];
		foreach (range(1, 12) as $month) {
			$select_month[$month] = date('F', mktime(null, null, null, sprintf('%02d', $month), 1));
		}

		return view('reports.agency.transaction.index', compact('select_ou', 'select_year', 'select_month'));
	}

	public function excel(Request $request)
	{
		if ($denied = $this->denyUnlessReport('Report:view:agency_tender', 'Report:view:agency_tender:organization_unit_id')) {
			return $denied;
		}

		[$year, $month, $ou] = $this->resolveFilters($request);
		if ($year === null) {
			$title = 'Transaksi Agensi Mengikut Tender';
			$error = 'Sila pastikan pilihan medan adalah betul.';

			return view('reports.error', compact('title', 'error'));
		}

		[$start, $end] = $this->monthBounds($year, $month);
		$tenders = $this->loadGroupedTransactions($start, $end, $ou);

		return Excel::download(new AgencyTransaction($tenders), 'Transaksi Mengikut Tender.xlsx');
	}

	public function view(Request $request)
	{
		if ($denied = $this->denyUnlessReport('Report:view:agency_tender', 'Report:view:agency_tender:organization_unit_id')) {
			return $denied;
		}

		[$year, $month, $ou] = $this->resolveFilters($request);
		if ($year === null) {
			$title = 'Transaksi Agensi Mengikut Tender';
			$error = 'Sila pastikan pilihan medan adalah betul.';

			return view('reports.error', compact('title', 'error'));
		}

		[$start, $end] = $this->monthBounds($year, $month);
		$agency = OrganizationUnit::find($ou);
		$rows = $this->transactionQuery($start, $end, $ou)->get();
		$amount = sprintf('%.2f', $rows->sum(fn ($row) => (float) $row->getRawOriginal('amount')));
		$tenders = $rows->groupBy(function ($row) {
			return $row->tender->ref_number ?? ('tender-' . $row->tender_id);
		});

		return view('reports.agency.transaction.view', compact('start', 'end', 'tenders', 'year', 'month', 'agency', 'amount'));
	}

	public function receipts(Request $request)
	{
		if ($denied = $this->denyUnlessReport('Report:view:agency_tender', 'Report:view:agency_tender:organization_unit_id')) {
			return $denied;
		}

		[$year, $month, $ou] = $this->resolveFilters($request);
		if ($year === null) {
			$title = 'Transaksi Agensi Mengikut Tender';
			$error = 'Sila pastikan pilihan medan adalah betul.';

			return view('reports.error', compact('title', 'error'));
		}

		[$start, $end] = $this->monthBounds($year, $month);
		$query = $this->transactionQuery($start, $end, $ou)
			->orderBy('transactions.created_at', 'asc');

		$page = $request->input('page');
		$limit = $request->input('limit');
		if ($page && $limit) {
			$query->take((int) $limit);
			if ((int) $page > 1) {
				$query->skip(((int) $page) * (int) $limit);
			}
		}

		$purchases = $query->get();

		return view('reports.agency.transaction.receipts', compact('purchases'));
	}

	/**
	 * @return array{0:?int,1:?int,2:?int}
	 */
	protected function resolveFilters(Request $request): array
	{
		$year = $request->input('year');
		$month = $request->input('month');
		$ou = auth()->user()->hasRole('Admin')
			? $request->input('ou')
			: auth()->user()->organization_unit_id;

		$validator = Validator::make([
			'ou' => $ou,
			'year' => $year,
			'month' => $month,
		], [
			'ou' => 'required|exists:organization_units,id',
			'year' => 'required',
			'month' => 'required',
		]);
		$validator->setAttributeNames([
			'ou' => 'Agensi',
			'year' => 'Tahun',
			'month' => 'Bulan',
		]);

		if ($validator->fails()) {
			return [null, null, null];
		}

		return [(int) $year, (int) $month, (int) $ou];
	}

	/**
	 * @return array{0:Carbon,1:Carbon}
	 */
	protected function monthBounds(int $year, int $month): array
	{
		$start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
		$end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

		return [$start, $end];
	}

	/**
	 * Lean query: filter via joins, hydrate only tender_vendors + needed relations.
	 * Avoids the old pattern of joining every table AND eager-loading them again,
	 * then running a second full sum() scan (that combination 504s on busy months).
	 */
	protected function transactionQuery(Carbon $start, Carbon $end, int $ou)
	{
		return TenderVendor::query()
			->select('tender_vendors.*')
			->join('tenders', 'tenders.id', '=', 'tender_vendors.tender_id')
			->join('transactions', 'transactions.id', '=', 'tender_vendors.transaction_id')
			->where('transactions.status', 'success')
			->whereBetween('transactions.created_at', [$start, $end])
			->where('tenders.organization_unit_id', $ou)
			->orderBy('tenders.created_at', 'asc')
			->orderBy('tender_vendors.id', 'asc')
			->with([
				'tender:id,ref_number,name,advertise_start_date,advertise_stop_date,document_start_date,document_stop_date,submission_datetime,organization_unit_id,price',
				'vendor:id,name',
				'transaction:id,created_at,number,receipt_number,method,gateway_reference,vendor_id,status',
			]);
	}

	protected function loadGroupedTransactions(Carbon $start, Carbon $end, int $ou)
	{
		return $this->transactionQuery($start, $end, $ou)
			->get()
			->groupBy(function ($row) {
				return $row->tender->ref_number ?? ('tender-' . $row->tender_id);
			});
	}
}
