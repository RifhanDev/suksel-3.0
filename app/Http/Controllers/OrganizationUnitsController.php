<?php

namespace App\Http\Controllers;

use App\Models\Tender as ModelsTender;
use App\Models\TenderVendor;
use Illuminate\Http\Request;
use Datatables;
use DB;
use Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\OrganizationType;
use App\OrganizationUnit;
use App\VendorCode;
use App\Tender;
use App\News;


class OrganizationUnitsController extends Controller
{
	/**
	 * Display a listing of organizationunits
	 *
	 * @return Response
	 */
	public function index(Request $request, $type_id = null)
	{
		$type = null;
		$parent = null;

		if ($request->type) {
			$type = OrganizationType::findOrFail($request->type);
		}

		if ($request->parent) {
			$parent = OrganizationUnit::findOrFail($request->parent);
		}

		if ($request->ajax()) {
			$fields = [
				'organization_units.id',
				'organization_units.name',
				'organization_units.address',
				'organization_units.tel',
				'organization_units.type_id'
			];

			$organization_units = OrganizationUnit::select($fields);

			if (isset($type)) {
				$organization_units = $organization_units->where('type_id', $type->id);
				// if ($type->id == 1) {
				// 	$organization_units = $organization_units->whereIn('depth', [0, 1]);
				// } else {
				// 	$organization_units = $organization_units->where('depth', 0);
				// }
			} elseif (isset($parent)) {
				$organization_units = $parent->descendantsAndSelf()->select($fields);
			} else {
				$organization_units = $organization_units->with('type');
			}

			$datatable = Datatables::of($organization_units)
				->addColumn('actions', function ($organization_unit) use ($request) {
					$actions   = [];
					$actions[] = $organization_unit->canShow() ? '<a href="' . route('agencies.show', $organization_unit->id) . '" class="btn btn-xs btn-primary">Lihat Tender</a>' : '';

					if (($organization_unit->type_id > 3) && ($organization_unit->canShow()) && ($organization_unit->children()->count() > 0) && ($organization_unit->id != $request->parent))
						$actions[] = '<a href="' . route('agencies.index', ['parent' => $organization_unit->id]) . '" class="btn btn-xs btn-warning">Lihat Agensi Bawahan</a>';

					$actions[] = $organization_unit->canUpdate() ? '<a href="' . route('agencies.edit', $organization_unit->id) . '" class="btn btn-xs btn-success">Kemaskini</a>' : '';
					return implode('<br>', $actions);
				})
				->removeColumn('id');

			if (isset($type)) {
				$datatable = $datatable->removeColumn('type_id');
				$datatable->rawColumns(['name', 'address', 'tel', 'actions']);
			} else {
				$datatable = $datatable->editColumn('type_id', function ($ou) {
					return isset($ou->type) ? $ou->type->name : '';
				});
				$datatable->rawColumns(['name', 'address', 'tel', 'type_id', 'actions']);
			}

			return $datatable->make(true);
		}

		return view('organizationunits.index', compact('type', 'parent'));
	}

	/**
	 * Show the form for creating a new organizationunit
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		if ($request->ajax()) {
			return _ajax_denied();
		}
		if (!OrganizationUnit::canCreate()) {
			return $this->_access_denied();
		}
		return view('organizationunits.create');
	}

	/**
	 * Store a newly created organizationunit in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($data = $request->all(), OrganizationUnit::$rules['store']);
		if (!OrganizationUnit::canCreate()) {
			return $this->_access_denied();
		}
		if ($validator->fails()) {
			return $this->_validation_error($validator->messages());
		}
		$organizationunit = OrganizationUnit::create($data);
		if (!isset($organizationunit->id)) {
			return $this->_create_error();
		}
		if (isset($data['parent_id'])) {
			$parent = OrganizationUnit::find($data['parent_id']);
			if ($parent) {
				$organizationunit->makeChildOf($parent);
				$parent->touch();
			}
		}
		if ($request->ajax()) {
			return response()->json($organizationunit->toJson(), 201);
		}
		return redirect('agencies')->with('success', $this->created_message);
	}

	/**
	 * Display the specified organizationunit.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		$organizationunit = OrganizationUnit::findOrFail($id);
		$tenders          = Tender::where('organization_unit_id', $organizationunit->id)->orderBy('created_at', 'desc')->orderBy('name', 'asc');

		if (!auth()->check() || auth()->user()->hasRole('Vendor') || !Tender::canShowUpdate($organizationunit->id)) {
			$tenders = $tenders->where(function ($query) {
				$query->advertised()->forPublic()->published();
			});
		}

		switch ($request->type) {
			case 'tenders':
				$tenders = $tenders->whereType('tender');
				$path    = url('agencies', $organizationunit->id) . '?type=tenders';
				break;
			case 'quotations':
				$tenders = $tenders->whereType('quotation');
				$path    = url('agencies', $organizationunit->id) . '?type=quotations';
				break;
			default:
				$tenders = $tenders;
				$path    = url('agencies', $organizationunit->id);
				break;
		}

		switch ($request->state) {
			case 1:
				$tenders = $tenders->whereNull('approver_id');
				$path    = url('agencies', $organizationunit->id) . '?state=1';
				break;
			case 2:
				// Temporarily commented out to fix column issue
				// $tenders = $tenders->whereNotNull('approver_id')->where('publish_prices', 0)->where('publish_winner', 0);
				$tenders = $tenders->where('publish_prices', 0)->where('publish_winner', 0);
				$path    = url('agencies', $organizationunit->id) . '?state=2';
				break;
			case 3:
				// Temporarily commented out to fix column issue
				// $tenders = $tenders->whereNotNull('approver_id')->where('publish_prices', '>', 0)->where('publish_winner', 0);
				$tenders = $tenders->where('publish_prices', '>', 0)->where('publish_winner', 0);
				$path    = url('agencies', $organizationunit->id) . '?state=3';
				break;
		}

		if ($request->ajax()) {
			$tenders = $tenders->select([
				'id',
				'name',
				'document_start_date',
				'submission_datetime',
				'price',
				'organization_unit_id',
				'publish_prices',
				'approver_id',
				'publish_winner',
				'briefing_required',
				'briefing_address',
				'briefing_datetime',
				'ref_number',
				'created_at'
			]);

			$datatable = Datatables::of($tenders)

				->editColumn('name', function ($tender) {
					$string   = [];
					$string[] = '<small><strong>' . $tender->ref_number . '</strong></small>';
					$string[] = link_to_route('tenders.show', $tender->name, $tender->id, ['class' => 'table-tender-title']);

					if ($tender->briefing_required) {
						$string[] = '';
						$string[] = '<span class="glyphicon glyphicon-bullhorn"></span> <b><u><small>Kehadiran Taklimat Diwajibkan</small></u></b>';
						$string[] = Carbon::parse($tender->briefing_datetime)->format('j M Y H:i');
						$string[] = nl2br($tender->briefing_address);
					}

					if (count($tender->siteVisits) > 0) {
						$string[] = '';
						$string[] = '<span class="glyphicon glyphicon-road"></span> <b><u><small>Lawatan Tapak</small></u></b>';

						foreach ($tender->siteVisits->sortBy('id') as $visit) {
							$string[] = '';
							$string[] = Carbon::parse($visit->datetime)->format('j M Y H:i');
							$string[] = nl2br($visit->address);

							if ($visit->required) {
								$string[] = '<small><span class="glyphicon glyphicon-ok"></span> Wajib Hadir</small>';
							}
						}
					}

					return implode('<br>', $string);
				})
				->editColumn('document_start_date', function ($tender) {
					return Carbon::parse($tender->document_start_date)->format('j M Y');
				})
				->editColumn('submission_datetime', function ($tender) {
					return Carbon::parse($tender->submission_datetime)->format('j M Y');
				})
				->editColumn('price', function ($tender) {
					return sprintf('RM %.2f', $tender->price);
				})
				->addColumn('status', function ($tender) {
					return $tender->status;
				})
				->addColumn('codes', function ($tender) {
					$string = '';

					if (count($tender->mof_codes) > 0) {
						$max_count = count($tender->mof_code_groups);
						$string    .= '<strong><u>MOF</u></strong><br>';

						foreach ($tender->mof_code_groups_by_code as $order => $data) {
							$code_count = count($data['codes']);
							$i          = 1;
							$string     .= '<small>';

							foreach ($data['codes'] as $id => $code) {
								$string .= $code;
								if ($i   != $code_count)
									$string .= VendorCode::$rule[$data['inner_rule']];
								$i++;
							}

							$string .= '</small>';

							if ($order !=  $max_count)
								$string .= '<br>' . VendorCode::$rule[$data['join_rule']] . '<br>';
						}

						$string .= '<br><br>';
					}

					if (count($tender->cidb_grades) > 0) {
						$string .= '<strong><u>Gred CIDB</u></strong><br><small>';

						foreach ($tender->cidb_grades as $code)
							$string .= $code->code->code . '&nbsp;&nbsp;';

						$string .= '</small><br><br>';
					}

					if (count($tender->cidb_codes) > 0) {
						$max_count = count($tender->cidb_code_groups);
						$string    .= '<strong><u>CIDB</u></strong><br>';

						foreach ($tender->cidb_code_groups_by_code as $order => $data) {
							$code_count = count($data['codes']);
							$i          = 1;
							$string     .= '<small>';

							foreach ($data['codes'] as $id => $code) {
								$string .= $code;
								if ($i   != $code_count)
									$string .= VendorCode::$rule[$data['inner_rule']];
								$i++;
							}

							$string .= '</small>';

							if ($order !=  $max_count)
								$string .= '<br>' . VendorCode::$rule[$data['join_rule']] . '<br>';
						}

						$string .= '<br><br>';
					}

					return $string;
				});

			if (Tender::canShowUpdate($organizationunit->id)) {
				$datatable = $datatable->addColumn('actions', function ($tender) {
					$str   = [];
					$str[] = '<div class="btn-group btn-group-vertical">';
					if (empty($tender->approver_id)) $str[] = link_to_route('tenders.edit', 'Kemaskini', $tender->id, ['class' => 'btn btn-xs btn-primary']);
					if ($tender->canCancel() && $tender->approver_id > 0)
						$str[] = link_to_action('TendersController@cancel', 'Batal Siar', $tender->id, ['class' => 'btn btn-xs btn-danger']);
					if ($tender->canUpdate() && empty($tender->approver_id))
						$str[] = link_to_action('TendersController@publish', 'Siar', $tender->id, ['class' => 'btn btn-xs btn-warning']);
					return implode('', $str);
				});

				$datatable = $datatable->addColumn('report', function ($tender) use ($id) {
					$str   = [];
					$str[] = '<div class="btn-group btn-group-vertical">';
					if ($tender->canCancel() && $tender->approver_id > 0)
						$str[] = link_to_action('OrganizationUnitsController@report', 'Lihat', [$id, $tender->id], ['class' => 'btn btn-xs btn-primary']);

					return implode('', $str);
				});
			} else {
				$datatable = $datatable->removeColumn('actions');
			}

			return $datatable
				->filterColumn('name', function ($query, $keyword) {
					$sql = "CONCAT(name,'-',ref_number)  like ?";
					$query->whereRaw($sql, ["%{$keyword}%"]);
				})
				->removeColumn('organization_unit_id')
				->removeColumn('approver_id')
				->removeColumn('publish_prices')
				->removeColumn('publish_winner')
				->removeColumn('id')
				->removeColumn('briefing_required')
				->removeColumn('briefing_datetime')
				->removeColumn('briefing_address')
				->rawColumns(['name', 'codes', 'document_start_date', 'submission_datetime', 'price', 'actions', 'report'])
				->make();
		}

		$count_1     = $organizationunit->tenders()->whereNull('approver_id')->count();
		// Temporarily commented out to fix column issue
		// $count_2     = $organizationunit->tenders()->whereNotNull('approver_id')->where('publish_prices', 0)->where('publish_winner', 0)->count();
		// $count_3     = $organizationunit->tenders()->whereNotNull('approver_id')->where('publish_prices', '>', 0)->where('publish_winner', 0)->count();
		$count_2     = $organizationunit->tenders()->where('publish_prices', 0)->where('publish_winner', 0)->count();
		$count_3     = $organizationunit->tenders()->where('publish_prices', '>', 0)->where('publish_winner', 0)->count();
		$global_news = $organizationunit->news()->where('publish', 1)->orderBy('published_at', 'desc')->take(10)->get();
		view()->share('global_ou', $organizationunit);
		return view('organizationunits.show', compact('organizationunit', 'tenders', 'count_1', 'count_2', 'count_3', 'global_news', 'path'));
	}

	public function report($organization_unit_id, $tender_id)
	{
		if (!Tender::canShowUpdate($organization_unit_id))
			return $this->_access_denied();

		$purchasers = TenderVendor::where('tender_id', $tender_id)->orderBy('created_at', 'asc')->with('vendor')->get();
		$tender = Tender::with(['creator', 'officer'])->findOrFail($tender_id);
		$organization = OrganizationUnit::findOrFail($organization_unit_id);

		if ($tender->type == 'tender') {
			$label = 'TENDER';
		} else if ($tender->type == 'quotation') {
			$label = 'SEBUT HARGA';
		}

		return view('organizationunits.tender.report', compact('tender', 'organization', 'label', 'purchasers'));
	}

	public function prices(Request $request, $id)
	{

		$organizationunit = OrganizationUnit::findOrFail($id);
		$base = $organizationunit->tenders()->orderBy('submission_datetime', 'desc')->published();
		if (!auth()->check() || auth()->user()->hasRole('Vendor') || !Tender::canShowUpdate($organizationunit->id)) {
			$base = $base->forPublic()->publishedPrices();
		}

		switch ($request->type) {
			case 'tenders':
				$tenders = $base->whereType('tender')->get();
				break;
			case 'quotations':
				$tenders = $base->whereType('quotation')->get();
				break;
			default:
				$tenders = $base->get();
				break;
		}
		$global_news = $organizationunit->news()->where('publish', 1)->orderBy('published_at', 'desc')->take(10)->get();

		view()->share('global_ou', $organizationunit);

		return view('organizationunits.prices', compact('organizationunit', 'tenders', 'global_news'));
	}

	public function news(Request $request, $id)
	{

		$news = null;
		$organizationunit = OrganizationUnit::findOrFail($id);

		if ($request->ajax()) {
			$news = News::where('organization_unit_id', $organizationunit->id)->where('publish', 1)->orderBy('published_at', 'desc');
			$news = $news->select([
				'id',
				'created_at',
				'title',
				'notification',
			]);

			$datatable = Datatables::of($news)
				->editColumn('created_at', function ($news) {
					return Carbon::parse($news->published_at)->format('j M Y');
				})
				->editColumn('title', function ($news) {
					$string = '';
					$string .= '<h4>' . $news->title . '</h4>';
					$string .= '<p>' . Str::words($news->notification, 20) . '</p>';
					return $string;
				})
				->addColumn('actions', function ($news) {
					return link_to_route('news.show', 'Selanjutnya', $news->id, ['class' => 'btn btn-xs btn-primary']);
				})
				->removeColumn('id')
				->removeColumn('notification')
				->rawColumns(['created_at', 'title', 'actions'])
				->make(true);

			return $datatable;
		}
		return view('organizationunits.news', compact('organizationunit', 'news'));
	}

	public function results(Request $request, $id)
	{

		$organizationunit = OrganizationUnit::findOrFail($id);
		$base = $organizationunit->tenders()->orderBy('submission_datetime', 'desc')->published()->publishedPrices();
		if (!auth()->check() || auth()->user()->hasRole('Vendor') || !Tender::canShowUpdate($organizationunit->id)) {
			$base = $base->forPublic()->publishedWinner();
		}

		switch ($request->type) {
			case 'tenders':
				$tenders = $base->whereType('tender')->get();
				break;
			case 'quotations':
				$tenders = $base->whereType('quotation')->get();
				break;
			default:
				$tenders = $base->get();
				break;
		}

		$global_news = $organizationunit->news()->where('publish', 1)->orderBy('published_at', 'desc')->take(10)->get();
		view()->share('global_ou', $organizationunit);
		return view('organizationunits.results', compact('organizationunit', 'tenders', 'global_news'));
	}
	/**
	 * Show the form for editing the specified organizationunit.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{

		$organizationunit = OrganizationUnit::find($id);
		if ($request->ajax()) {
			return _ajax_denied();
		}
		if (!$organizationunit->canUpdate()) {
			return $this->_access_denied();
		}
		view()->share('global_ou', $organizationunit);
		return view('organizationunits.edit', compact('organizationunit'));
	}

	/**
	 * Update the specified organizationunit in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

		$organizationunit = OrganizationUnit::findOrFail($id);
		if (!$organizationunit->canUpdate()) {
			return $this->_access_denied();
		}
		$data = $request->all();
		if (!isset($data['confirmation_agency'])) $data['confirmation_agency'] = 0;
		$validator = Validator::make($data, OrganizationUnit::$rules['update']);
		if ($validator->fails()) {
			return $this->_validation_error($validator->messages());
		}
		if (!$organizationunit->update($data)) {
			return $this->_update_error();
		}
		if ((int) $organizationunit->parent_id !== (int) $data['parent_id']) {
			if (!empty($data['parent_id'])) {
				$organizationunit->makeChildOf($data['parent_id']);
				OrganizationUnit::find($data['parent_id'])->touch();
			} else {
				OrganizationUnit::find($organizationunit->parent_id)->touch();
				$organizationunit->makeRoot();
			}
		}
		if ($request->ajax()) {
			return $organizationunit;
		}

		session()->forget('_old_input');
		return redirect('agencies/' . $organizationunit->id)->with('success', $this->updated_message);
	}

	/**
	 * Remove the specified organizationunit from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$organizationunit = OrganizationUnit::findOrFail($id);
		if (!$organizationunit->canDelete()) {
			return $this->_access_denied();
		}
		if (!$organizationunit->delete()) {
			return $this->_delete_error();
		}
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('agencies.index')->with('success', $this->deleted_message);
	}

	// public function __construct()
	// {
	//     parent::__construct();
	//     view()->share('controller', 'OrganizationUnitsController');
	// }
}
