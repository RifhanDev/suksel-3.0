<?php

namespace App\Http\Controllers;

use PDF;
use Hash;
use Mail;
use App\Role;
use App\User;
use App\Vendor;
use Datatables;
use App\Approval;
use App\Models\RefState;
use Carbon\Carbon;
use App\VendorHistory;
use App\OrganizationUnit;
use Illuminate\Http\Request;
use App\Models\RejectTemplate;
use Illuminate\Support\Facades\Validator;


class VendorsController extends Controller
{
	public function index(Request $request)
	{

		if (!Vendor::canList()) {
			return $this->_access_denied();
		}

		if ($request->ajax()) {

			if ($state = $request->state) {
				switch ($state) {
					case 'pending_registration':
						$vendors = Vendor::pendingRegistration();
						break;
					case 'approve_new_1':
						$vendors = Vendor::pendingNewApproval1();
						break;
					case 'approve_edit_1':
						$vendors = Vendor::pendingEditApproval1();
						break;
				}
			} else {
				$vendors = Vendor::whereNotNull('vendors.id');
			}

			$vendors = $vendors->join('users', 'vendors.id', '=', 'users.vendor_id');

			if (!auth()->user()->ability(['Admin', 'Registration Assessor'], ['Vendor:approve'])) {
				$vendors = $vendors->whereNotNull('approval_1_id');
			}

			$vendors = $vendors->select([
				'vendors.id',
				'vendors.registration',
				'vendors.name',
				'users.email',
				'vendors.approval_date',
				'vendors.completed',
				'vendors.approval_1_id',
				'vendors.cidb_grade_id',
				'vendors.blacklisted_until',
				'vendors.created_at',
				'vendors.submission_date',
			]);

			$order = [
				'column' => 1,
				'dir'    => 'asc'
			];
			$orders = $request->order;
			if ($orders) {
				$order = $orders[0];
			}
			if ($order['column'] == 4) {
				$vendors = $vendors->orderBy('vendors.submission_date', $order['dir'])
					->orderBy('vendors.created_at', $order['dir']);
			}
			return Datatables::of($vendors)
				->editColumn('completed', function ($vendor) {
					return $vendor->status;
				})
				->addColumn('actions', function ($vendor) {
					$actions   = [];
					$actions[] = $vendor->canShow() ? link_to_action('VendorsController@show', 'Papar', $vendor->id, ['class' => 'btn btn-xs btn-primary']) : '';
					return implode(' ', $actions);
				})
				->editColumn('email', function ($vendor) {

					return $vendor->user ? $vendor->user->email : boolean_icon(false);
				})
				->editColumn('approval_date', function ($vendor) use ($state) {
					if (isset($state)) {
						if ($vendor->submission_date) {
							return Carbon::parse($vendor->submission_date)->format('j M Y');
						}
						return Carbon::parse($vendor->created_at)->format('j M Y');
					} elseif (!empty($vendor->approval_date)) {
						return Carbon::parse($vendor->approval_date)->format('j M Y');
					} else {
						return boolean_icon(false);
					}
				})
				->removeColumn('approval_1_id')
				->removeColumn('cidb_grade_id')
				->removeColumn('blacklisted_until')
				->removeColumn('created_at')
				->removeColumn('submission_date')
				->rawColumns(['registration', 'name', 'email', 'approval_date', 'completed', 'actions'])
				->make(true);
		}

		return view('vendors.index');
	}

	public function emails(Request $request)
	{
		if (!Vendor::canList()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			$vendors = Vendor::leftJoin('users', 'users.vendor_id', '=', 'vendors.id')->whereNotNull('users.email_verify_at')->orderBy('vendors.name', 'asc');
			$vendors = $vendors->select([
				'vendors.id',
				'vendors.registration',
				'vendors.name',
				'users.email',
			]);
			return Datatables::of($vendors)
				->editColumn('completed', function ($vendor) {
					return $vendor->status;
				})
				->addColumn('actions', function ($vendor) {
					$actions   = [];
					$actions[] = $vendor->canShow()   ? link_to_route('VendorsController@show', 'Papar', $vendor->id, ['class' => 'btn btn-xs btn-primary']) : '';
					return implode(' ', $actions);
				})
				->editColumn('email', function ($vendor) {
					return $vendor->user ? $vendor->user->email : boolean_icon(false);
				})
				->addColumn('email_verify_at', function ($vendor) {
					if (!empty($vendor->email_verify_at)) {
						return Carbon::parse($vendor->email_verify_at)->format('j M Y');
					} else {
						return boolean_icon(false);
					}
				})
				->rawColumns(['registration', 'name', 'email', 'email_verify_at', 'actions'])
				->make();
		}

		return view('vendors.emails');
	}

	public function pendingRegistrationIndex()
	{
		return view('vendors.index', [
			'subtitle'        => 'Pendaftaran Belum Selesai',
			'approval_status' => 'pending_registration'
		]);
	}

	public function approvalNew1Index()
	{
		return view('vendors.index', [
			'subtitle'        => 'Pendaftaran Belum Diluluskan',
			'approval_status' => 'approve_new_1'
		]);
	}

	public function approvalEdit1Index()
	{
		return view('vendors.index', [
			'subtitle'        => 'Permintaan Kemaskini',
			'approval_status' => 'approve_edit_1'
		]);
	}

	/**
	 * Show the form for creating a new vendor
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!Vendor::canCreate()) {
			return $this->_access_denied();
		}


		$country_states = RefState::where('display_status', 1)->get();
		return view('vendors.create', compact('country_states'));
	}

	/**
	 * Store a newly created vendor in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		$data = $request->all();
		Vendor::setRules('store');
		if (!Vendor::canCreate()) {
			return $this->_access_denied();
		}
		$validator = Validator::make(
			$data,
			[
				// 'ssm'        => 'required|max:5120|mimes:pdf',
				// 'bumiputera' => 'max:5120|mimes:pdf',
				// 'mof'        => 'max:5120|mimes:pdf',
				// 'cidb'       => 'max:5120|mimes:pdf',

				'mof'             => ['nullable', 'mimes:pdf', 'max:5120'],
				'cidb'            => ['nullable', 'mimes:pdf', 'max:5120'],
				'ssm'             => ['required', 'mimes:pdf', 'max:5120'],
				'mof_bumiputera'  => ['nullable', 'mimes:pdf', 'max:5120'],
				'cidb_bumiputera' => ['nullable', 'mimes:pdf', 'max:5120'],
			]
		);
		if ($validator->fails()) {
			return redirect()->back()
				->withInput()
				->with('danger', 'Fail Dimuat Naik Tidak Betul.')
				->withErrors($validator);
		}

		$user_data = [
			'name'                  => $data['officer_name'],
			'email'                 => $data['email'],
			'username'              => $data['email'],
			'password'              => Hash::make($data['password']),
			'password_confirmation' => $data['password_confirmation'],
			'confirmed'             => 1
		];
		User::setRules('store');
		$user = new User;
		$user->fill($user_data);

		if (!$user->save())
			return $this->_validation_error($user);

		$user->roles()->sync([Role::where('name', 'Vendor')->first()->id]);


		// convert ssm_expiry
		$ssm_expiry_input = $data["ssm_expiry"]; // Your string representation of a date
		$ssm_expiry_input_format = 'd/m/Y'; // Your custom date format
		$new_ssm_expiry = Carbon::createFromFormat($ssm_expiry_input_format, $ssm_expiry_input);
		$data["ssm_expiry"] = $new_ssm_expiry->format('Y-m-d');

		if ($data["district_id"] > 0) {
			$data["state_id"] = null;
		} else {
			if ($data["state_id"] > 0) {
				$data["district_id"] = null;
			}
		}

		$vendor = new Vendor;
		$vendor->organization_unit_id = config('app.global_ou');
		$vendor->fill($data);

		if (!$vendor->save())
			return $this->_validation_error($vendor);

		// $user->vendor()->associate($user);
		$user->vendor_id = $vendor->id;
		$user->save();

		if ($request->ajax()) {
			return response()->json($vendor, 201);
		}
		return redirect('vendors')->with('success', $this->created_message);
	}

	/**
	 * Display the specified vendor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		$vendor = Vendor::findOrFail($id);

		if (empty($vendor->token) && !is_null($vendor->approval_1_id)) {
			$vendor->token = str_random_unique(8);
			$vendor->save();
		}

		if (!$vendor->canShow()) {
			return $this->_access_denied();
		}

		if ($request->ajax()) {
			return response()->json($vendor);
		}

		$transactions = $vendor->transactions()->where('type', 'subscription')->where('status', 'success')->get();

		foreach ($transactions as $transaction) {
			$transaction->subscription_id = 'TIADA';
			$transaction->start_date = 'TIADA'; // Default value
			$transaction->end_date = 'TIADA';   // Default value

			if ($transaction->subscription) {

				$transaction->subscription_id = $transaction->subscription->id;

				if ($transaction->subscription->start_date) {
					$transaction->start_date = Carbon::parse($transaction->subscription->start_date)->format('d/m/Y');
				}

				if ($transaction->subscription->end_date) {
					$transaction->end_date = Carbon::parse($transaction->subscription->end_date)->format('d/m/Y');
				}
			}
			// $transaction->start_date = Carbon::parse($transaction->subscription->start_date)->format('d/m/Y') ?? 'TIADA';
			// $transaction->end_date = Carbon::parse($transaction->subscription->end_date)->format('d/m/Y') ?? 'TIADA';

			$year = date('d-m-Y', strtotime($transaction->created_at));
			$transaction->receipt = $this->receiptNumGenerator($transaction->number, $year);
		}

		$templates = RejectTemplate::where('applicable_0', 1)->get([
			'id', 'title', 'content'
		]);

		return view('vendors.show', compact('vendor', 'transactions', 'templates'));
	}

	public function certificate($id)
	{
		$vendor = Vendor::findOrFail($id);

		if (!$vendor->canCertificate()) {
			return $this->_access_denied();
		}

		$type = 'SALINAN';
		if ($vendor->certificate_generated_at == null) {
			$vendor->certificate_generated_at = date('Y-m-d H:i:s');
			$vendor->update();
			$type = 'ASAL';
		}
		return view('vendors.certificate', compact('vendor', 'type'));
		//return PDF::loadView('vendors.certificate', compact('vendor', 'type'))->stream();
	}

	/**
	 * Show the form for editing the specified vendor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$vendor = Vendor::findOrFail($id);
		$country_states = RefState::where('display_status', 1)->get();

		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$vendor->canUpdate()) {
			return $this->_access_denied();
		}

		// alamat dan daerah negeri tidak boleh diubah secara terus as requested by en iskandar. (6/4/2023)
		$disable_create_flaq = 0;  // 1: Block Editing, 0: Allow Editing

		return view('vendors.edit', compact('vendor', 'country_states', 'disable_create_flaq'));
	}

	/**
	 * Show the form for editing the specified vendor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function blacklist(Request $request, $id)
	{
		$vendor = Vendor::findOrFail($id);
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$vendor->canBlacklist()) {
			return $this->_access_denied();
		}
		return view('vendors.blacklist', compact('vendor'));
	}

	public function cancelBlacklist($id)
	{
		$vendor = Vendor::findOrFail($id);

		if (empty($vendor->blacklisted_until))
			return $this->_access_denied();

		$vendor->blacklisted_until = null;
		$vendor->blacklist_reason = null;
		$vendor->save();
		return redirect('vendors.show', $vendor->id)->with('success', 'Syarikat telah dibuang dari senarai hitam.');
	}

	/**
	 * Update the specified vendor in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function doBlacklist(Request $request, $id)
	{

		$vendor = Vendor::findOrFail($id);
		Vendor::setRules('blacklist');
		$data = $request->all();
		$parts = explode('/', $data['blacklisted_until']);
		$date  = date('Y-m-d', strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0] . ' 00:00:00'));
		$data['blacklisted_until']  = $date;

		if (!$vendor->canBlacklist()) {
			return $this->_access_denied();
		}

		if (!$vendor->update($data)) {
			return $this->_validation_error($vendor);
		}

		if ($request->ajax()) {
			return $vendor;
		}

		session()->forget('_old_input');
		return redirect('vendors/' . $id)->with('success', 'Vendor blacklist details updated');
	}

	/**
	 * Update the specified vendor in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

		$vendor = Vendor::findOrFail($id);

		$validator = Validator::make($request->all(), [
			'mof'             => ['nullable', 'mimes:pdf', 'max:5120'],
			'cidb'            => ['nullable', 'mimes:pdf', 'max:5120'],
			'ssm'             => ['nullable', 'mimes:pdf', 'max:5120'],
			'mof_bumiputera'  => ['nullable', 'mimes:pdf', 'max:5120'],
			'cidb_bumiputera' => ['nullable', 'mimes:pdf', 'max:5120'],
		]);

		if ($validator->fails())
			return redirect()->back()
				->withErrors($validator, 'pdf_upload')
				->with('danger', 'Fail Dimuat Naik Tidak Betul.');

		Vendor::setRules('update');
		$data = $request->all();
		$data['paidup_capital']     = str_replace(',', '', $data['paidup_capital']);
		$data['authorized_capital'] = str_replace(',', '', $data['authorized_capital']);


		// convert ssm_expiry
		$ssm_expiry_input = $data["ssm_expiry"]; // Your string representation of a date
		$ssm_expiry_input_format = 'd/m/Y'; // Your custom date format
		$new_ssm_expiry = Carbon::createFromFormat($ssm_expiry_input_format, $ssm_expiry_input);
		$data["ssm_expiry"] = $new_ssm_expiry->format('Y-m-d');

		if (isset($data["district_id"])) {
			if ($data["district_id"] > 0) {
				$data["state_id"] = null;
			} else {
				if ($data["state_id"] > 0) {
					$data["district_id"] = null;
				}
			}
		}


		if (!$vendor->canUpdate()) {
			return $this->_access_denied();
		}
		if (!$vendor->update($data)) {
			return $this->_validation_error($vendor);
		}
		if ($request->ajax()) {
			return $vendor;
		}
		session()->forget('_old_input');

		if (auth()->user()->vendor_id == $id) {
			$redirect = redirect('vendor');
		} else {
			$redirect = redirect('vendors/' . $id);
		}

		VendorHistory::log('edit', $vendor->id, auth()->user()->id);

		return $redirect->with('success', $this->updated_message);
	}

	/**
	 * Remove the specified vendor from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $reques, $id)
	{
		$vendor = Vendor::findOrFail($id);
		if (!$vendor->canDelete()) {
			return $this->_access_denied();
		}
		$vendor->delete();
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('VendorsController@index')->with('success', $this->deleted_message);
	}

	public function approve($vendor_id)
	{
		if (!auth()->user()->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
			return $this->_access_denied();

		$vendor = Vendor::findOrFail($vendor_id);
		$approval = new Approval;
		$approval->user_id = auth()->user()->id;
		$approval->save();
		VendorHistory::log('approve', $vendor->id, auth()->user()->id);

		$vendor->approval_1_id = $approval->id;
		$vendor->approval_date = date('Y-m-d');
		// Mail::send('vendors.emails.application-approved', ['vendor' => $vendor], function($message) use ($vendor) {
		// 	$message->to(trim($vendor->registeredBy->email));
		// 	$message->subject('Sistem Tender Online Selangor: Pendaftaran Syarikat Diluluskan');
		// });

		$to			= trim($vendor->registeredBy->email);
		$subject 	= 'Sistem Tender Online Selangor: Pendaftaran Syarikat Diluluskan';
		$send_status = $this->sendMail("html", $to, $subject, "", "vendors.emails.application-approved", ['vendor' => $vendor]);

		$vendor->rejection_reason = null;
		$vendor->rejection_template_id = null;
		$vendor->token = str_random_unique(8);
		$vendor->save();
		return redirect('vendors/' . $vendor_id)->with('success', 'Syarikat Diluluskan.');
	}

	public function reject(Request $request, $vendor_id)
	{
		if (!auth()->user()->ability(['Admin', 'Registration Assessor'], ['Vendor:reject']))
			return $this->_access_denied();

		$template = null;
		$rejection_reason = null;
		if ($request->template != null) {
			$template = json_encode($request->template);
		}
		if ($request->reason != '') {
			$rejection_reason = $request->reason;
		}
		$vendor = Vendor::findOrFail($vendor_id);
		$vendor->completed = 0;
		$vendor->approval_1_id = null;
		$vendor->rejection_reason = $rejection_reason;
		$vendor->rejection_template_id = $template;
		$vendor->save();
		VendorHistory::log('reject', $vendor->id, auth()->user()->id, $vendor->rejection_reason, $vendor->rejection_template_id);

		//mail commented for dev purposed - by zayid 5 nov 22
		Mail::send('vendors.emails.application-rejected', ['vendor' => $vendor], function ($message) use ($vendor) {
			$message->to(trim($vendor->registeredBy->email));
			$message->subject('Sistem Tender Online Selangor: Pendaftaran Syarikat Ditolak.');
		});
		session()->flash('info', 'Syarikat Ditolak.');
		return 'true';
	}

	public function select(Request $request)
	{
		if (!auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
			return $this->_ajax_denied();

		$query = $request->get('q');

		if (empty($query)) return response()->json(['error' => 'Please enter your search query']);

		$vendors = Vendor::canParticipate()
			->join('users', 'vendors.id', '=', 'users.vendor_id')
			->where('vendors.name', 'LIKE', "%{$query}%")
			->orWhere('vendors.registration', 'LIKE', "%{$query}%")
			->select('vendors.id', 'vendors.name', 'vendors.registration', 'vendors.expiry_date', 'users.email')->get();

		return response()->json($vendors);
	}

	public function editEmail(Request $request, $id)
	{
		$vendor = Vendor::findOrFail($id);
		if (!$vendor->canUpdate2() || $request->ajax())
			return $this->_access_denied();
		return view('vendors.edit_email', compact('vendor'));
	}

	public function updateEmail(Request $request, $id)
	{
		$vendor = Vendor::findOrFail($id);
		$data = $request->all();

		if (!$vendor->canUpdate2())
			return $this->_access_denied();

		$validator = Validator::make(
			$data,
			[
				'registration'  => 'required|unique:vendors,registration,' . $vendor->id,
				'email'         => 'required|unique:users,email,' . $vendor->user->id
			]
		);

		if ($validator->fails()) {
			return redirect()->back()->withInput()->withErrors($validator);
		}

		$saved = false;

		if ($request->registration != $vendor->registration) $saved = $saved || true;
		if ($request->registration != $vendor->user->email) $saved = $saved || true;

		$vendor->registration = $request->input('registration', $vendor->registration);
		$vendor->save();

		$vendor->user->email = $vendor->user->username = $request->input('email', $vendor->user->email);
		$vendor->user->save();

		if ($request->ajax())
			return $vendor;

		if ($saved)
			VendorHistory::log('edit-2', $id, auth()->user()->id);

		return redirect('vendors/' . $id);
	}

	public function histories($id)
	{
		$vendor = Vendor::findOrFail($id);
		$templates = RejectTemplate::get(['id', 'title', 'content']);
		return view('vendors.histories', compact('vendor', 'templates'));
	}

	/**
	 * Constructor
	 */

	public function __construct()
	{
		// parent::__construct();
		// View::share('controller', 'Vendor');
	}
}
