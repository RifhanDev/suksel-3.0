<?php

namespace App\Http\Controllers;

use App\Approval;
use App\Gateway;
use App\Jobs\GenerateEligible;
use App\Models\ExceptionTender;
use App\Models\RefState;
use App\Models\RejectTemplate;
use App\Models\Upload;
use App\Tender;
use App\TenderEligible;
use App\TenderHistory;
use App\TenderInvite;
use App\TenderVendor;
use App\TenderVisit;
use App\TenderVisitor;
use App\Traits\Helper;
use App\Transaction;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use DB;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Mail;
use PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TendersController extends Controller
{
	use Helper;
	/**
	 * Display a listing of tenders
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		if ($request->ajax()) {

			$tenders = Tender::orderBy('submission_datetime', 'desc');

			if (auth()->check() && !auth()->user()->ability(['Admin', 'Front Desk'], []) && auth()->user()->organization_unit_id)
				$tenders = $tenders->where('organization_unit_id', auth()->user()->organization_unit_id);

			if (!auth()->check() || auth()->user()->hasRole('Vendor'))
				$tenders = $tenders->forPublic()->published()->advertised();

			$tenders = $tenders->select([
				'tenders.id',
				'tenders.name',
				'tenders.ref_number',
				'tenders.organization_unit_id',
				'tenders.document_start_date',
				'tenders.submission_datetime',
				'tenders.price',
				'tenders.approver_id',
				'tenders.invitation',
				'tenders.publish_prices',
				'tenders.publish_winner',
				'tenders.briefing_required',
				'tenders.briefing_datetime',
				'tenders.briefing_address',
			]);
			$tenders = $tenders->orderBy('created_at', 'desc');
			$datatable = Datatables::of($tenders)
				->filterColumn('name', function ($query, $keyword) {
					$sql = "CONCAT(name,'-',ref_number)  like ?";
					$query->whereRaw($sql, ["%{$keyword}%"]);
				})
				->editColumn('name', function ($tender) {
					$string   = [];
					$string[] = (auth()->check() && !auth()->user()->hasRole('Vendor') && $tender->invitation ? '<i class="fa fa-lock"></i> ' : '') . "<strong><u>" . $tender->tenderer->name . "</u></strong>";
					$string[] = "<small>" . $tender->ref_number . "</small>";
					$string[] = "<a class=\"table-tender-title\" href=" . action('TendersController@show', $tender->id) . ">" . $tender->name . "</a>";;

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
							$string[] = \Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i');
							$string[] = nl2br($visit->address);

							if ($visit->required) {
								$string[] = '<small><span class="glyphicon glyphicon-ok"></span> Wajib Hadir</small>';
							}
						}
					}

					return implode('<br>', $string);
				})
				->editColumn('document_start_date', function ($tender) {
					return \Carbon\Carbon::parse($tender->document_start_date)->format('j M Y');
				})
				->editColumn('submission_datetime', function ($tender) {
					return \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y');
				})
				->editColumn('approver_id', function ($tender) {
					return $tender->status;
				})
				->removeColumn('id')
				->removeColumn('ref_number')
				->removeColumn('organization_unit_id')
				->removeColumn('invitation')
				->removeColumn('publish_winner')
				->removeColumn('briefing_required')
				->removeColumn('briefing_datetime')
				->removeColumn('briefing_address')
				->removeColumn('publish_prices');

			if (!auth()->check() || auth()->user()->hasRole('Vendor')) $datatable = $datatable->removeColumn('approver_id');

			return $datatable
				->rawColumns(['name', 'document_start_date', 'submission_datetime', 'price', 'approver_id'])
				->make();
		}

		return view('tenders.index');
	}

	/**
	 * Show the form for creating a new tender
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		if ($request->ajax()) {
			return $this->_ajax_denied($request);
		}
		if (!Tender::canCreate()) {
			return $this->_access_denied();
		}


		$country_states = RefState::where('display_status', 1)->get();

		return view('tenders.create', compact('country_states'));
	}

	/**
	 * Store a newly created tender in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		if (!Tender::canCreate()) {
			return $this->_access_denied();
		}

		$data = $request->all();
		$user = auth()->user();

		$data['advertise_start_date'] = Carbon::parse($data['advertise_start_date'])->format('Y-m-d');
		$data['advertise_stop_date']  = Carbon::parse($data['advertise_stop_date'])->format('Y-m-d');
		$data['document_start_date']  = Carbon::parse($data['document_start_date'])->format('Y-m-d');
		$data['document_stop_date']   = Carbon::parse($data['document_stop_date'])->format('Y-m-d');
		$data['submission_datetime']  = Carbon::parse($data['submission_datetime'])->format('Y-m-d 12:00:00');
		$data['creator_id']           = $user->id;
		if (!array_key_exists('allow_exception', $data))
			$data['allow_exception']  = 0;

		if (isset($data['briefing_datetime']) && !empty($data['briefing_datetime'])) {
			$data['briefing_datetime'] = Carbon::parse($data['briefing_datetime'])->format('Y-m-d H:i:s');
		} else {
			$data['briefing_datetime'] = null;
			$data['briefing_address']  = null;
		}

		if (isset($data['organization_unit_id']) && auth()->user()->hasRole('Admin')) {
			$data['organization_unit_id'] = $data['organization_unit_id'];
		} else {
			$data['organization_unit_id'] = $user->organizationunit->id;
		}

		if (!isset($data['district_id']) || $data['district_id'] == 0) $data['district_id'] = null;
		if (isset($data['district_id']) && empty($data['district_id'])) unset($data['district_id']);

		$district_list = $request->district_id_new ?? [];
		$state_list = $request->state_id_new ?? [];
		$district_list_rule = [];


		if (count($district_list) > 0) {
			foreach ($district_list as $idx => $input_district_id) {
				$state_id = isset($state_list[$idx]) ? $state_list[$idx] : "0";

				$district_list_rule[] = array(
					"district_id" => $input_district_id,
					"state_id" => $state_id,
				);
			}
		}

		if ($data["only_selangor"] != 3) {
			$data["district_list_rule"] = json_encode($district_list_rule);
		} else {
			$data["district_list_rule"] = json_encode(array());
		}


		Tender::setRules('store');
		$tender = new Tender;
		$tender->fill($data);

		if (!$tender->save()) {
			return $this->_validation_error($tender);
		}

		/* update creator information */
		$user_update = User::find($user->id);
		$user_update->tel = $data['default_tel'];
		$user_update->department = $data['default_department'];
		$user_update->save();

		if ($data['officer_id'] != '') {
			/* update officer information */
			$user_update = User::find($data['officer_id']);
			$user_update->tel = $data['tel'];
			$user_update->department = $data['department'];
			$user_update->save();
		}

		$tender->updateTender(false);

		if ($request->ajax()) {
			return response()->json($tender, 201);
		}

		return redirect('tenders/' . $tender->id)->with('success', $this->created_message);
	}

	/**
	 * Display the specified tender.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		$tender = Tender::with('codes')
			->with('siteVisits', 'creator', 'officer')
			->findOrFail($id);

		$organizationunit   = $tender->tenderer;
		$invites            = $tender->invites()->has('vendor')->get();
		$histories          = $tender->histories()->orderBy('created_at', 'desc')->get();
		$tender_winner = null;
		$tender_vendors	= $tender->participants;

		foreach ($tender_vendors as $tender_vendor) {
			if ($tender_vendor->winner == 1) {
				$tender_winner = $tender_vendor;
			}
		}

		$exception = null;

		if (auth()->check()) {
			$exception          = $tender->exceptions()->with('files')->where('vendor_id', auth()->user()->vendor_id)->orderBy('created_at', 'desc')->first();
		}

		$templates 			= RejectTemplate::where('applicable_2', 1)->get(['id', 'title', 'content']);

		if (!$tender->canShow()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			return response()->json($tender);
		}

		view()->share('global_ou', $tender->tenderer);
		return view('tenders.show', compact('tender', 'organizationunit', 'invites', 'histories', 'exception', 'templates', 'tender_winner'));
	}

	/**
	 * Show the form for editing the specified tender.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$tender = Tender::with('creator', 'officer')->findOrFail($id);
		$visits = TenderVisit::where('tender_id', $tender->id)->get()->toArray();
		$country_states = RefState::where('display_status', 1)->get();

		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$tender->canUpdate()) {
			return $this->_access_denied();
		}
		return view('tenders.edit', compact('tender', 'visits', 'country_states'));
	}

	/**
	 * Update the specified tender in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

		$tender = Tender::findOrFail($id);
		Tender::setRules('update');
		$data = $request->all();

		$data['advertise_start_date'] = Carbon::parse($data['advertise_start_date'])->format('Y-m-d');
		$data['advertise_stop_date']  = Carbon::parse($data['advertise_stop_date'])->format('Y-m-d');
		$data['document_start_date']  = Carbon::parse($data['document_start_date'])->format('Y-m-d');
		$data['document_stop_date']   = Carbon::parse($data['document_stop_date'])->format('Y-m-d');
		$data['submission_datetime']  = Carbon::parse($data['submission_datetime'])->format('Y-m-d 12:00:00');

		if (isset($data['briefing_datetime']) && !empty($data['briefing_datetime'])) {
			$data['briefing_datetime'] = Carbon::parse($data['briefing_datetime'])->format('Y-m-d H:i:s');
		} else {
			$data['briefing_datetime'] = null;
			$data['briefing_address']  = null;
		}

		if (isset($data['district_id']) && $data['district_id'] == 0) $data['district_id'] = null;
		if (!isset($data['only_bumiputera'])) $data['only_bumiputera'] = 0;
		if (!isset($data['only_selangor'])) $data['only_selangor'] = 0;
		if (!isset($data['invitation'])) $data['invitation'] = 0;
		if (!isset($data['briefing_required'])) $data['briefing_required'] = null;
		if (!isset($data['allow_exception'])) $data['allow_exception'] = 0;
		if (!isset($data['only_advertise'])) $data['only_advertise'] = 0;

		$district_list = $request->district_id_new ?? [];
		$state_list = $request->state_id_new ?? [];
		$district_list_rule = [];


		if (count($district_list) > 0) {
			foreach ($district_list as $idx => $input_district_id) {
				$state_id = isset($state_list[$idx]) ? $state_list[$idx] : "0";

				$district_list_rule[] = array(
					"district_id" => $input_district_id,
					"state_id" => $state_id,
				);
			}
		}

		if ($data["only_selangor"] != 3) {
			$data["district_list_rule"] = json_encode($district_list_rule);
		} else {
			$data["district_list_rule"] = json_encode(array());
		}

		if (!$tender->canUpdate()) {
			return $this->_access_denied();
		}

		if (!$tender->update($data)) {
			return $this->_validation_error($tender);
		}

		/* update creator information */
		$user_update = User::find(($data['default_creator_id']));
		$user_update->tel = $data['default_tel'];
		$user_update->department = $data['default_department'];
		$user_update->save();

		if ($data['officer_id'] != '') {
			/* update officer information */
			$user_update = User::find($data['officer_id']);
			$user_update->tel = $data['tel'];
			$user_update->department = $data['department'];
			$user_update->save();
		}

		$tender->updateTender();
		if ($request->ajax()) {
			return $tender;
		}
		session()->forget('_old_input');
		return redirect('tenders/' . $id)->with('success', $this->updated_message);
	}

	/**
	 * Remove the specified tender from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$tender = Tender::findOrFail($id);
		if (!$tender->canDelete()) {
			return $this->_access_denied();
		}
		$tender->delete();
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		TenderHistory::log($id, 'delete');
		return redirect('tenders')->with('success', $this->deleted_message);
	}

	public function buy($id)
	{

		$tender = Tender::findOrFail($id);

		if (!$tender->canShow() || !auth()->user()->hasRole('Vendor') || !$tender->canParticipate(auth()->user()->vendor_id) || $tender->hasParticipate(auth()->user()->vendor_id))
			return $this->_access_denied();

		if (!empty(session('cart_ou')) && session('cart_ou') != $tender->organization_unit_id) {
			return redirect('tenders/' . $tender->id)->with('error', 'Hanya tender dari agensi yang sama boleh di beli!');
		}

		if ($tender->hasParticipate(auth()->user()->vendor_id)) {
			return redirect('tenders/', $tender->id)->with('error', 'Tender ini sudah dibeli!');
		}

		if ($tender->organization_unit_id != config('app.global_cart_ou')) {
			$fpx = Gateway::whereType('fpx')->where('organization_unit_id', $tender->organization_unit_id)->whereActive(1)->first();
			$ebpg = Gateway::whereType('ebpg')->where('organization_unit_id', $tender->organization_unit_id)->whereActive(1)->first();

			if ($fpx || $ebpg) {
				if (!empty(session('cart_items'))) {
					//$tender_ous = array_unique(Tender::whereIn('id', session('cart_items'))->pluck('organization_unit_id'));
					$tender_ous = array_unique(json_decode(json_encode(Tender::whereIn('id', session('cart_items'))->pluck('organization_unit_id')), true));

					if (!in_array($tender->organization_unit_id, $tender_ous)) {
						return redirect('tenders/' . $tender->id)->with('error', 'Hanya tender dari agensi yang sama boleh di beli!');
					}
				}

				if (empty(session('cart_ou', null))) session()->put('cart_ou', $tender->organization_unit_id);
			}
		}

		session()->push('cart_items', $tender->id);

		$prices = Tender::whereIn('id', session('cart_items'))->sum('price');


		return redirect('tenders/' . $tender->id)->with('success', 'Tender telah ditambah dalam senarai tempahan.');
	}

	public function callbackBuy(Request $request, $id)
	{

		$user   = auth()->user();
		$tender = $user->vendor;
		$method = $request->method;
		$tender = Tender::findOrFail($id);

		$txn    = $request->txn;
		$status = $request->status;

		$transaction = Transaction::find($txn);
		$subscription = null;

		if (!$transaction || $transaction->user_id != $user->id || $transaction->status != 'pending')
			return $this->_access_denied();

		if ($status == 'success') {
			$transaction->status            = 'success';
			$transaction->gateway_reference = str_random(10);
			$transaction->gateway_auth      = str_random(6);
			$transaction->save();

			$participant = $tender->participants()->where('transaction_id', $txn)->first();

			if (!$subscription) {
				$subscription = $tender->participants()->save(new TenderVendor([
					'vendor_id'      => $tender->id,
					'transaction_id' => $transaction->id
				]));

				$tender->registration_paid = 1;
				$tender->expiry_date       = $subscription->end_date;
				$tender->save();
			}
		} else {
			$transaction->status            = 'failed';
			$transaction->gateway_reference = str_random(10);
			$transaction->save();
		}
		return view('registration.callback_payment', compact('transaction', 'subscription', 'vendor'));
	}

	public function select()
	{
		if (!auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
			return $this->_ajax_denied();

		$query = request()->input('q');

		if (empty($query)) {
			return response()->json(['error' => 'Sila masukkan kata carian...'], 403);
		}

		$tenders = Tender::select('id', 'name', 'ref_number');

		if (!auth()->user()->hasRole('Vendor')) {
			$user = auth()->user();

			if (!$user->hasRole('Admin') && $user->agency) {
				$ou_ids  = auth()->user()->agency->descendantsAndSelf()->pluck('id');
				$tenders = $tenders->where('organization_unit_id', auth()->user()->organization_unit_id);
			}
		}

		$tenders = $tenders->where('name', 'LIKE', "%{$query}%")->orWhere('ref_number', 'LIKE', "%{$query}%");
		$tenders = $tenders->get();
		return response()->json($tenders);
	}

	public function file($tender_id, $id)
	{
		$tender = Tender::findOrFail($tender_id);
		$file   = $tender->files()->whereId($id)->first();

		if (!$file)
			return $this->_access_denied();

		if ($file->public == 0) {
			if (!auth()->check())
				return $this->_access_denied();
			if (auth()->user()->hasRole('Vendor') && !Tender::hasParticipate(auth()->user()->vendor_id))
				return $this->_access_denied();
		}

		return Response::download($file->path, $file->name);
	}

	public function receipt($tender_id, $id)
	{
		$tender   = Tender::findOrFail($tender_id);
		$purchase = $tender->participants()->with('transaction')->findOrFail($id);

		if (!$purchase->canViewReceipt())
			return $this->_access_denied();

		$year = date('d-m-Y', strtotime($purchase->transaction->created_at));
		$receipt = $this->receiptNumGenerator($purchase->transaction->number, $year);
		$type = 'SALINAN';
		if ($purchase->transaction->receipt_generated_at == null) {
			$purchase->transaction->receipt_generated_at = date('Y-m-d H:i:s');
			$purchase->transaction->update();
			$type = 'ASAL';
		}
		return view('tenders.receipt', compact('tender', 'purchase', 'type', 'receipt'));
		//return PDF::loadView('tenders.receipt', compact('tender', 'purchase', 'type'))->stream();
	}

	public function document($tender_id, $id)
	{
		$tender   = Tender::findOrFail($tender_id);
		$purchase = TenderVendor::findOrFail($id);
		$year = date('d-m-Y', strtotime($purchase->transaction->created_at));
		$receipt = $this->receiptNumGenerator($purchase->transaction->number, $year);

		if (auth()->user()->hasRole('Vendor') && $purchase->vendor_id != auth()->user()->vendor_id)
			return $this->_access_denied();

		return view('tenders.document', compact('purchase', 'receipt'));
	}

	public function vendors($id)
	{

		$tender    = Tender::with('siteVisits')->findOrFail($id);
		$purchases = $tender->participants()->has('vendor')->get();

		// Check for winner 
		$count_winner = 0;

		foreach ($purchases as $purchase) {
			if ($purchase->winner == 1) {
				$count_winner++;
			}
		}

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		view()->share('global_ou', $tender->tenderer);
		if ($tender->onlyShowPrices()) {
			$prices = $tender->participants()->where('label', 'NOT LIKE', '')->orderBy(DB::raw('label*1'), 'asc')->orderBy('label', 'asc')->get();
			$winner = $tender->participants()->where('winner', 1)->first();
			return view('tenders.prices', compact('tender', 'prices', 'winner'));
		}

		return view('tenders.vendors', compact('tender', 'purchases', 'count_winner'));
	}

	public function eligibles(Request $request, $id)
	{
		$tender = Tender::with('siteVisits')->findOrFail($id);

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		if ($request->ajax()) {
			$eligibles = TenderEligible::with('vendor')->whereTenderId($tender->id)
				->select(
					'tender_eligibles.id',
					'vendors.registration',
					'vendors.registration as vendor_registration',
					'vendors.name as vendor_name',
					'vendors.name',
					'users.email as user_email',
					'users.email',
					'tender_eligibles.created_at',
					'tender_eligibles.sent_at',
					'tender_eligibles.vendor_id'
				)
				->leftJoin('vendors', 'tender_eligibles.vendor_id', '=', 'vendors.id')
				->leftJoin('users', 'users.vendor_id', '=', 'vendors.id');

			$datatable = Datatables::of($eligibles)
				->editColumn('vendor_registration', function ($eligible) {
					// return link_to_route('vendors.show', $eligible->vendor_registration, [$eligible->vendor_id]) . '<br><small>' . $eligible->vendor->status . '</small>';
					return '<a href="' . route('vendors.show', $eligible->vendor_id) . '">' . $eligible->vendor_registration . '</a><br><small>' . $eligible->vendor->status . '</small>';
				})
				->editColumn('vendor_name', function ($eligible) {
					return link_to_route('vendors.show', $eligible->vendor_name, $eligible->vendor_id);
				})
				->editColumn('user_email', function ($eligible) {
					return $eligible->user_email;
				})
				->editColumn('created_at', function ($eligible) {
					return \Carbon\Carbon::parse($eligible->created_at)->format('d/m/Y H:i:s');
				})
				->editColumn('sent_at', function ($eligible) {
					return $eligible->sent_at ? \Carbon\Carbon::parse($eligible->sent_at)->format('d/m/Y H:i:s') : boolean_icon(false);
				});

			return $datatable->rawColumns(['vendor_registration', 'vendor_name', 'user_email', 'created_at', 'sent_at'])->make();
		}

		return view('tenders.eligibles', compact('tender'));
	}

	public function printVendors($id)
	{
		$tender    = Tender::with('siteVisits')->findOrFail($id);
		$purchases = $tender->participants()->has('vendor')->get();

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		return view('tenders.print_vendors', compact('tender', 'purchases'));
	}

	public function updateVendors(Request $request, $id)
	{

		$tender     = Tender::with('siteVisits')->findOrFail($id);
		$purchases  = $tender->participants;
		$tender_ids = $purchases->pluck('vendor_id')->toArray();

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		$data = $request->all();

		$tender->participants()->update(['winner' => 0]);

		foreach ($tender->participants as $purchase) {

			if (isset($data['briefing']) && isset($data['briefing'][$purchase->id])) {
				$purchase->briefing = 1;
			} else {
				$purchase->briefing = 0;
			}

			if (isset($data['participate']) && isset($data['participate'][$purchase->id]) && !$purchase->participate) {
				$purchase->participate = 1;
				$purchase->ref_number  = TenderVendor::generateNumber($tender->id);
				$purchase->amount      = $tender->price;
			}

			if (isset($data['winner']) && $data['winner'] == $purchase->id) {
				$purchase->winner           = 1;
				$purchase->project_timeline = $data['project_timeline'];
			}

			if (isset($data['price']) && $data['price'][$purchase->id]) $purchase->price = $data['price'][$purchase->id];
			if (isset($data['label']) && $data['label'][$purchase->id]) $purchase->label = $data['label'][$purchase->id];

			if (isset($data['delete'])) {
				$tender->participants()->whereParticipate(0)->whereIn('id', $data['delete'])->delete();
			}

			$purchase->save();
		}

		foreach ($tender->siteVisits as $visit) {
			$visit->visitors()->delete();
		}

		if (isset($data['visits'])) {
			foreach ($data['visits'] as $visit_id => $tender_ids) {
				foreach ($tender_ids as $id) {
					$visit = new TenderVisitor;
					$visit->visit_id  = $visit_id;
					$visit->vendor_id = $id;
					$visit->save();
				}
			}
		}


		if (!empty($data['vendor_ids'])) {
			$ids     = explode(',', $data['vendor_ids']);
			$new_ids = array_diff($ids, $tender_ids);

			foreach ($new_ids as $id) {
				$tender->participants()->save(new TenderVendor([
					'vendor_id' => $id
				]));
			}
		}

		TenderHistory::log($tender->id, 'update-vendors');

		return redirect('tenders/' . $tender->id . '/vendors')->with('success', 'Maklumat Syarikat dikemaskini.');
	}

	public function exception(Request $request, $id)
	{
		$tender = Tender::with('siteVisits')->findOrFail($id);

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		$data   = $request->all();

		$vendor = Vendor::find($data['exception_id']);

		if (empty($vendor) || !$vendor->canParticipate()) {
			return redirect('tenders/' . $tender->id . '/vendors')->with('error', 'Syarikat dipilih tidak layak untuk diberi kebenaran khas.');
		}

		$participate = $tender->participants()->whereVendorId($vendor->id)->first();

		if ($participate && $participate->exception == 1) {
			return redirect('tenders/' . $tender->id . '/vendors')->with('error', 'Syarikat dipilih telah diberi kebenaran khas.');
		}

		if (!$participate)
			$participate = new TenderVendor(['vendor_id' => $vendor->id, 'tender_id' => $tender->id]);

		$participate->exception = 1;
		$participate->save();

		TenderHistory::log($tender->id, 'update-vendors');

		return redirect('tenders/' . $tender->id . '/vendors')->with('success', 'Maklumat Kebenaran Khas disimpan.');
	}

	public function updateInvites(Request $request, $id)
	{
		$tender     = Tender::with('invites')->findOrFail($id);
		$invites    = $tender->invites;
		$tender_ids = $invites->pluck('vendor_id')->toArray();

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		$data = $request->all();

		if (!empty($data['invite_ids'])) {
			$ids     = explode(',', $data['invite_ids']);
			$new_ids = array_diff($ids, $tender_ids);

			foreach ($new_ids as $vendor_id) {
				$tender->invites()->save(new TenderInvite([
					'vendor_id' => $vendor_id
				]));
			}
		}

		if (isset($data['deleted_invites'])) {
			foreach ($data['deleted_invites'] as $index => $id) {
				$participate = $tender->participants()->where('vendor_id', $id)->first();

				if (!$participate) {
					$tender->invites()->where('vendor_id', $id)->delete();
				}
			}
		}

		TenderHistory::log($tender->id, 'update-invites');

		return redirect('tenders/' . $tender->id . '#tf-invites')->with('success', 'Maklumat Jemputan dikemaskini.');
	}

	public function publish(Request $request, $id)
	{
		$tender = Tender::findOrFail($id);

		if (!$tender->canUpdate())
			$this->_access_denied();
		if (!empty($tender->approver_id))
			return redirect('tenders/' . $tender->id)->with('error', 'Tender / Sebut Harga ini telah disiarkan.');

		$approval          = new Approval;
		$approval->user_id = auth()->user()->id;
		$approval->save();

		$tender->approver_id = $approval->id;
		$tender->save();

		$tender_ids = [];

		if (($tender->invitation == 1) && (!$request->get('email'))) {

			foreach ($tender->invites as $invite) {
				$vendor = $invite->vendor;

				/*
					Mail::send('tenders.emails.invite', ['tender' => $tender, 'vendor' => $vendor], function($message) use($vendor) {
					$message->to(trim($vendor->user->email));
					$message->subject('Sistem Tender Online Selangor: Jemputan Tender');
					});
				*/

				$to			= trim($vendor->user->email);
				$subject 	= 'Sistem Tender Online Selangor: Jemputan Tender';
				$send_status = $this->sendMail("html", $to, $subject, "", "tenders.emails.invite", ['tender' => $tender, 'vendor' => $vendor]);
			}
		} else {
			$this->generateEligible($tender->id);
		}

		TenderHistory::log($tender->id, 'publish');

		return redirect('tenders/' . $tender->id)->with('success', 'Tender / Sebut Harga berjaya disiarkan.');
	}

	public function cancel($id)
	{
		$tender = Tender::findOrFail($id);

		if (!$tender->canUpdate())
			$this->_access_denied();
		if (empty($tender->approver_id))
			return redirect('tenders/' . $tender->id)->with('error', 'Tender / Sebut Harga belum disiarkan.');

		$tender->approver_id = null;
		$tender->save();

		TenderHistory::log($tender->id, 'unpublish');

		return redirect('tenders/' . $tender->id)->with('success', 'Tender / Sebut Harga telah di batal siar.');
	}

	public function publishPrices($id)
	{
		$tender = Tender::findOrFail($id);

		if (!$tender->canShowPrices())
			$this->_access_denied();

		if (!$tender->publish_prices) {
			$approval          = new Approval;
			$approval->user_id = auth()->user()->id;
			$approval->save();

			$tender->publish_prices = $approval->id;
			TenderHistory::log($tender->id, 'publish-prices');
		} else {
			$tender->publish_prices = 0;
			TenderHistory::log($tender->id, 'unpublish-prices');
		}

		$tender->save();

		return redirect('tenders/' . $tender->id)->with('success', 'Carta Tender telah dikemaskini.');
	}

	public function publishWinner($id)
	{
		$tender = Tender::findOrFail($id);

		if (!$tender->canShowPrices())
			$this->_access_denied();

		$winner = $tender->participants()->where('winner', 1)->first();

		if (!$winner)
			return redirect('tenders/' . $tender->id . '/vendors')->with('error', 'Sila pilih Penender Berjaya dahulu.');

		if (!$tender->publish_winner) {
			$approval          = new Approval;
			$approval->user_id = auth()->user()->id;
			$approval->save();

			$tender->publish_winner = $approval->id;
			TenderHistory::log($tender->id, 'publish-winner');
		} else {
			$tender->publish_winner = 0;
			TenderHistory::log($tender->id, 'unpublish-winner');
		}

		$tender->save();

		return redirect('tenders/' . $tender->id . '/vendors')->with('success', 'Penender Berjaya telah diumumkan.');
	}

	public function vendor($tender_id, $id)
	{
		$tender = Tender::findOrFail($tender_id);

		if (!$tender->canUpdate())
			return $this->_access_denied();

		$participate = $tender->participants()->where('vendor_id', $id)->first();
		$invite      = $tender->invites()->where('vendor_id', $id)->first();

		if (!($participate || $invite))
			return $this->_access_denied();

		$vendor = isset($participate) ? $participate->vendor : $invite->vendor;

		$transactions = $vendor->transactions()->where('type', 'subscription')->where('status', 'success')->get();

		foreach ($transactions as $transaction) {
			$transaction->subscription_id = $transaction->subscription->id;
			$transaction->start_date = Carbon::parse($transaction->subscription->start_date)->format('d/m/Y');
			$transaction->end_date = Carbon::parse($transaction->subscription->end_date)->format('d/m/Y');
		}

		return view('tenders.vendor', compact('tender', 'vendor', 'transactions'));
	}

	public function template($tender_id)
	{
		$tender = Tender::findOrFail($tender_id);
		if (!$tender->canUpdate())
			return $this->_access_denied();

		$response = new StreamedResponse(function () use ($tender) {
			$handle   = fopen('php://output', 'w');

			$headers = [
				'No. Syarikat',
				'Nama Syarikat'
			];
			$content = [
				'123456',
				'ACME Corp'
			];

			if ($tender->hasBriefing()) {
				$headers[] = 'Taklimat';
				$content[] = '1';
			}

			foreach ($tender->siteVisits()->orderBy('id', 'asc')->get() as $visit) {
				$headers[] = 'LT' . $visit->id;
				$content[] = '1';
			}

			fputcsv($handle, $headers);
			fputcsv($handle, $content);

			fclose($handle);
		}, 200, [
			'Content-Type' => 'text/csv',
			'Content-Disposition' => 'attachment; filename="template-' . $tender->id . '.csv"',
		]);

		return $response;
	}

	public function bulkUpdate(Request $request, $tender_id)
	{
		$tender = Tender::findOrFail($tender_id);
		if (!$tender->canUpdate())
			return $this->_access_denied();

		if (!$request->file('file'))
			return redirect()->back()->with('error', 'Sila muat naik fail data Maklumat Syarikat');

		$file   = $request->file('file');
		$handle = fopen($file->getRealPath(), 'r+');
		$csv    = [];
		$data   = [];

		while (!feof($handle)) {
			$extracted_csv_data = fgetcsv($handle);
			if ($extracted_csv_data !== false) {
				$csv[] = $extracted_csv_data;
			}
		}

		fclose($handle);

		$headers = array_shift($csv);
		foreach ($csv as $c) {
			$current = [];
			foreach ($headers as $index => $header) {
				$current[$header] = $c[$index];
			}
			$data[] = $current;
		}

		$visit_ids = $tender->siteVisits()->pluck('id');
		$vendors   = [];
		$notfound  = [];

		foreach ($data as $d) {
			$vendor = Vendor::whereRegistration($d['No. Syarikat'])->first();
			if (empty($vendor)) {
				$notfound[$d['No. Syarikat']] = $d['Nama Syarikat'];
				continue;
			}

			$tv = TenderVendor::whereVendorId($vendor->id)->whereTenderId($tender->id)->first();

			if (empty($tv)) {
				$tv = new TenderVendor(['vendor_id' => $vendor->id, 'tender_id' => $tender->id]);
			}

			if (isset($d['Taklimat'])) {
				$tv->briefing = $d['Taklimat'];
			} else {
				$tv->briefing = 0;
			}

			if (isset($d['Harga'])) {
				$tv->price = $d['Harga'];
			}

			if (isset($d['Label'])) {
				$tv->label = $d['Label'];
			}

			$tv->save();

			foreach ($headers as $header) {
				if (in_array($header, ['No. Syarikat', 'Nama Syarikat', 'Taklimat', 'Harga', 'Label']))
					continue;

				$visit_id = (int) str_replace('LT', '', $header);
				if (!in_array($visit_id, $visit_ids))
					continue;

				$tv1 = TenderVisitor::whereVisitId($visit_id)->whereVendorId($vendor->id)->first();

				if (empty($tv1)) {
					$visit            = new TenderVisitor;
					$visit->visit_id  = $visit_id;
					$visit->vendor_id = $vendor->id;
					$visit->save();
				}
			}
		}

		return redirect()->back()->with('success', 'Maklumat Syarikat telah dikemasini.')->with('bulk_errors', $notfound);
	}

	/**
	 * Constructor
	 */

	public function __construct()
	{
		// parent::__construct();
		// view()->share('controller', 'Tender');
		// 	Config::set('former::TwitterBootstrap3.labelWidths', [
		// 		'large' => 2,
		// 		'small' => 2,
		// 	]);
		// 	Config::set('former::TwitterBootstrap3.viewports', [
		// 		'large'  => 'lg',
		// 		'medium' => 'md',
		// 		'small'  => 'sm',
		// 		'mini'   => 'xs',
		// 	]);
		// Asset::push('js', 'tender-vue');
		// Asset::push('css', 'form');
	}

	public function sendEligible()
	{

		$eligibles = TenderEligible::with('tender', 'vendor')->whereNull('sent_at')
			->where('email', 1)
			->whereHas('vendor', function ($q) {
				$q->whereRaw("blacklisted_until < current_date");
			})
			->whereHas('tender', function ($q) {
				$q->whereRaw("submission_datetime > current_date");
			})
			->where('created_at', '>', '2023-04-1')
			// ->orderBy('tender_id', 'desc', 'id')
			->limit(500)
			->get();

		$eligibles = $eligibles->sortBy([
			['tender_id', 'desc'],
			['id', 'asc'],
		]);

		// dd($eligibles);

		foreach ($eligibles as $eligible) {
			$vendor = $eligible->vendor;
			$tender = $eligible->tender;



			if ($tender && $vendor && $vendor->user && !$vendor->user->isEmailBlacklist() && $vendor->canParticipateInTenders()) {

				// Check Email
				if (filter_var(trim($vendor->user->email), FILTER_VALIDATE_EMAIL)) {

					//DD($eligible,$vendor->user->email);
					//var_dump($eligible,$vendor->user->email);

					// Mail::send('tenders.emails.eligible', ['tender_id' => $tender->id, 'vendor_id' => $vendor->id], function($message) use($vendor, $tender) {
					// 	$message->to(trim($vendor->user->email));
					// 	$message->subject('Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name);   
					// });

					$to			= trim($vendor->user->email);
					$subject 	= 'Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name;
					$send_status = $this->sendMail("html", $to, $subject, "", "tenders.emails.eligible", ['tender_id' => $tender->id, 'vendor_id' => $vendor->id]);


					$eligible->update([
						'sent_at' => Carbon::now(),
						'email' => '2'
					]);
				} else {
				}
			} else {
				$eligible->update([
					'email' => false
				]);
			}
		}

		return view('home.version-histories');
	}

	public function storeException(Request $request)
	{
		$user = auth()->user();
		$request['vendor_id'] = $user->vendor_id;
		$request['user_id'] = $user->id;

		$exception = ExceptionTender::where('vendor_id', $user->vendor_id)->where('tender_id', $request['tender_id'])->first();

		if (!$exception) {
			$exception = new ExceptionTender();
		} else {
			$request['rejection_reason'] = null;
			$request['rejection_template_id'] = null;
			$request['status'] = 0;
		}

		$data = $request->except('exception_letter');
		$exception->fill($data);

		if (!$exception->save())
			return $this->_validation_error($exception);

		return redirect('tenders/' . $request->tender_id)->with('success', $this->created_message);
	}


	public function exceptions($id)
	{

		if (!ExceptionTender::canList()) {
			return $this->_access_denied();
		}

		$tender    = Tender::with('siteVisits')->findOrFail($id);
		$exceptions = $tender->exceptions()->with('files')->orderBy('status', 'asc')->get();
		$templates = RejectTemplate::where('applicable_2', 1)->get(['id', 'title', 'content']);

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		view()->share('global_ou', $tender->tenderer);

		return view('tenders.exceptions', compact('tender', 'exceptions', 'templates'));
	}

	public function approve_exception($id)
	{
		if (!ExceptionTender::canApprove())
			return $this->_access_denied();


		$exception = ExceptionTender::findOrFail($id);
		$tender = Tender::with('siteVisits')->findOrFail($exception->tender_id);

		if (!$tender->canShowTabs())
			return $this->_access_denied();

		$vendor = Vendor::find($exception->vendor->id);

		if (empty($vendor) || !$vendor->canParticipate()) {
			return redirect('tenders/' . $tender->id . '/exceptions')->with('error', 'Syarikat dipilih tidak layak untuk diberi kebenaran khas.');
		}

		$participate = $tender->participants()->whereVendorId($vendor->id)->first();

		if ($participate && $participate->exception == 1) {
			return redirect('tenders/' . $tender->id . '/exceptions')->with('error', 'Syarikat dipilih telah diberi kebenaran khas.');
		}

		if (!$participate)
			$participate = new TenderVendor(['vendor_id' => $vendor->id, 'tender_id' => $tender->id]);

		$participate->exception = 1;
		$participate->save();

		TenderHistory::log($tender->id, 'exception');

		$exception->status = 1;
		$exception->rejection_reason = null;
		$exception->rejection_template_id = null;
		$exception->save();
		return redirect('tenders/' . $tender->id . '/exceptions')->with('success', 'Permohonan kebenaran khas diterima');
	}

	public function reject_exception(Request $request, $id, $exception_id)
	{
		if (!ExceptionTender::canApprove())
			return $this->_access_denied();

		$template = null;
		$rejection_reason = null;
		if ($request->template != null) {
			$template = json_encode($request->template);
		}
		if ($request->reason != '') {
			$rejection_reason = $request->reason;
		}
		$exception = ExceptionTender::findOrFail($exception_id);
		$exception->status = 2;
		$exception->rejection_reason = $rejection_reason;
		$exception->rejection_template_id = $template;
		$exception->save();

		session()->flash('info', 'Permohonan Ditolak.');
		return 'true';
	}

	public function regen_tender_eligible(Request $request)
	{
		$tender_id = $request->post('tender_id') ?? -1;
		$public_key = $request->post('pk') ?? "-1";

		$special_id = Hash::make("8d8a32d3115554817987bc460a0c9bbf");

		if (!Hash::check($public_key, $special_id)) {
			return response()->json(["status" => "Access Denied"], 500);
		}


		if ($tender_id < 1) {
			return response()->json(["status" => "Invalid Tender ID"], 500);
		}


		$tender = Tender::findOrFail($tender_id);

		if ($tender->approver_id < 1) {
			return response()->json(["status" => "Tender is not published yet"], 500);
		}

		$this->generateEligible($tender->id);

		// TenderHistory::log($tender->id, 'publish');

		return response()->json(["status" => "Tender Eligible regenerated successfully"]);
	}
}
