<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Hash;
use Mail;
use App\User;
use App\UserHistory;
use App\Mail\ConfirmRegistration;
use Carbon\Carbon;
use Crypt;

class UsersController extends Controller
{
	public $set_password_message            = 'Kata Laluan disimpan.';
	public $set_confirmation_message        = 'Pengguna diaktifkan.';
	public $change_password_invalid_message = 'Kata Lalauan lama tidak sah.';
	public $change_password_message         = 'Kata Lalauan berjaya ditukar.';
	public $activation_message              = 'Pengguna telah diaktifkan.';
	public $deactivation_message            = 'Pengguna telah dinyahaktifkan.';

	/**
	 * Display a listing of users
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if (!User::canList()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			$users = User::with('roles', 'agency', 'vendor')->has('roles', '=', 0)->orWhereHas('roles', function ($q) {
				$q->where('name', '!=', 'Vendor');
			});

			if (!auth()->user()->hasRole('Admin')) {
				$users = $users->where('organization_unit_id', auth()->user()->organization_unit_id);
			}

			$users = $users->select([
				'users.id',
				'users.name',
				'users.email',
				'users.organization_unit_id',
				'users.confirmed',
				'users.arr'
			]);

			return Datatables::of($users)
				->editColumn('first_name', function ($user) {
					return $user->name;
				})
				->addColumn('roles_column', function ($user) {
					return '<ul>' . implode('', array_map(function ($name) {
						return '<li>' . $name . '</li>';
					}, $user->roles->pluck('name')->toArray())) . '</ul>';
				})
				->editColumn('organization_unit_id', function ($user) {
					if ($user->agency) {
						return $user->agency->name;
					}
				})
				->editColumn('confirmed', function ($user) {
					return $user->status();
				})
				->editColumn('arr', function ($user) {
					if ($user->arr == 1) {
						return 'Telah Disemak';
					} elseif ($user->arr == 0) {
						return 'Belum Disemak';
					} else {
						return 'Tiada';
					}
				})
				->addColumn('actions', function ($data) {
					$actions = [];
					$actions[] = $data->canShow() ? link_to_route('users.edit', 'Kemaskini', $data->id, ['class' => 'btn btn-xs btn-primary']) : '';
					$actions[] = $data->canShow() ? link_to_route('users.histories', 'Aktiviti Pengguna', $data->id, ['class' => 'btn btn-xs btn-success']) : '';
					$actions[] = $data->canLogin() ? link_to_route('users.login', 'Login Sebagai', $data->id, ['class' => 'btn btn-xs btn-danger']) : '';

					return '<div class="btn-group">' . implode(' ', $actions) . '</div>';
				})
				->removeColumn('id')
				->removeColumn('last_name')
				->rawColumns(['name', 'email', 'roles_column', 'organization_unit_id', 'confirmed', 'arr', 'actions'])
				->make();
		}

		return view('users.index');
	}

	/**
	 * Show the form for creating a new user
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!User::canCreate()) {
			return $this->_access_denied();
		}

		return view('users.create');
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		User::setRules('store');
		$data = $request->all();
		if (!User::canCreate()) {
			return $this->_access_denied();
		}

		$data['name']      = $data['name'];
		$data['username']  = $data['email'];
		$data['confirmed'] = 1;
		$data['roles']     = isset($data['roles']) ? $data['roles'] : [];

		if (isset($data['organization_unit_id']) && empty($data['organization_unit_id'])) {
			$data['organization_unit_id'] = null;
		}

		if (!auth()->user()->hasRole('Admin')) {
			$data['organization_unit_id'] = auth()->user()->organization_unit_id;
		}

		$user = new User;
		$user->fill($data);
		$user->password = Hash::make($request->password);
		if (!$user->save()) {
			return $this->_validation_error($user);
		}
		$user->roles()->sync($data['roles']);
		if ($request->ajax()) {
			return response()->json($user, 201);
		}

		return redirect('users')->with('success', $this->created_message);
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function show($id)
	{
		$currentUser = User::findOrFail($id);
		if (!$currentUser->canShow()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			return $currentUser;
		}

		return view('users.show', compact('currentUser'));
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$currentUser = User::find($id);
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$currentUser->canUpdate()) {
			return $this->_access_denied();
		}

		return view('users.edit', compact('currentUser'));
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

		$email = User::where('id', '!=', $id)->where('email', $request->email)->first();

		if ($email) {
			return redirect('users')->with('error', $this->update_error_message);
		} else {

			$user = User::findOrFail($id);
			$data = $request->all();

			if (!auth()->user()->hasRole('Admin')) {
				$data['organization_unit_id'] = auth()->user()->organization_unit_id;
			}
			if (isset($data['organization_unit_id']) && empty($data['organization_unit_id'])) {
				$data['organization_unit_id'] = null;
			}

			if (!$user->canUpdate()) {
				return $this->_access_denied();
			}
			$user->fill($data);
			$user->save();
			$data['roles'] = isset($data['roles']) ? $data['roles'] : [];
			$user->roles()->sync($data['roles']);
			if ($request->ajax()) {
				return $user;
			}
			session()->forget('_old_input');
			UserHistory::log($user->id, 'edit');

			return redirect('users')->with('success', $this->updated_message);
		}
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$user = User::findOrFail($id);

		if (!$user->canDelete()) {
			return $this->_access_denied();
		}
		if (!$user->delete()) {
			return $this->_delete_error();
		}
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}

		return redirect('users')->with('success', $this->deleted_message);
	}

	public function getSetPassword(Request $request, $id)
	{
		$currentUser = User::findOrFail($id);
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$currentUser->canSetPassword()) {
			return $this->_access_denied();
		}

		return view('users.set-password', compact('currentUser'));
	}

	public function putSetPassword(Request $request, $id)
	{
		$user = User::findOrFail($id);
		$data = $request->all();
		if (!$user->canSetPassword()) {
			return $this->_ajax_denied();
		}

		$validator = Validator::make($request->all(), User::$_rules['changePassword']);

		if ($validator->fails()) {
			$error_msg = trans('auth.alerts.wrong_password_reset');
			return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'Pengesahan kata laluan gagal');
		} else {

			$user->password = Hash::make($request->password);
			$user->save();

			if ($user->hasRole('Vendor')) {
				$redirect = redirect('vendors/' . $user->vendor_id);
			} else {
				$redirect = redirect('users/' . $user->id . '/edit');
			}

			UserHistory::log($user->id, 'password-update', auth()->user()->id);

			return $redirect->with('success', $this->set_password_message);
		}
	}

	public function putSetConfirmation(Request $request, $id = null)
	{
		$user = User::findOrFail($id);
		$data = $request->all();
		if (!$user->canSetConfirmation()) {
			return $this->_access_denied();
		}
		User::setRules('setConfirmation');
		if (!$user->update($data)) {
			return $this->_validation_error($user);
		}
		UserHistory::log($user->id, $user->confirmed ? 'activate' : 'deactivate', auth()->user()->id);
		if ($request->ajax()) {
			return response()->json($user->confirmed ? $this->activation_message : $this->deactivation_message);
		}
		$redirect = redirect('users/' . $user->id . '/edit');

		if ($user->confirmed) {
			return $redirect->with('success', $this->activation_message);
		} else {
			return $redirect->with('error', $this->deactivation_message);
		}
	}

	public function allEmails()
	{
		return User::whereNotNull('vendor_id')->pluck('email');
	}

	public function doLogin(Request $request, $id = null)
	{

		$third_party_id = auth()->user()->id;
		session()->put('original_user_id', $third_party_id);

		// dd(session('original_user_id'));
		if (isset($id)) {
			$user              = User::find($id);
			$not_found_message = 'Pengguna yang tidak wujud.';
		} else {
			$email = $request->email;
			if (!$email) {
				return redirect('users')->with('error', 'Sila masukkan alamat emel.');
			}
			$user              = User::where('email', $email)->first();
			$not_found_message = "Pengguna dengan alamat emel {$email} tidak dijumpai.";
		}

		if (!$user) {
			return redirect('users')->with('error', $not_found_message);
		}

		if ($user->email == auth()->user()->email) {
			return redirect('users')->with('error', "Anda sudah mendaftar masuk.");
		}

		auth()->login($user);

		if ($user->hasRole('Vendor') && $user->vendor) {
			// $redirect = redirect('dashboard');

			if (!$user->vendor->completed)
				return redirect('register/company');
			elseif (!$user->vendor->registration_paid)
				return redirect('register/payment');
			else
				return redirect('dashboard');
		} elseif ($user->ability(['Admin', 'Registration Assessor', 'Admin UPEN'], [])) {
			$redirect = redirect('vendors');
		} else {
			$redirect = redirect('agencies/' . $user->organization_unit_id);
		}

		UserHistory::log($user->id, 'sign-in', $third_party_id);

		return $redirect->with('notice', "Anda sudah mendaftar masuk sebagai {$user->email}.");
	}

	public function histories(Request $request, $id)
	{
		if (!User::canList()) {
			return $this->_access_denied();
		}

		$view_user = User::findOrFail($id);

		if ($request->ajax()) {

			$histories = UserHistory::with('third_party')
				->whereUserId($id)
				->where('created_at', '!=', '0000-00-00 00:00:00')
				->orderBy('created_at', 'desc');

			$histories = $histories->select([
				'user_histories.id',
				'user_histories.created_at',
				'user_histories.action',
				'user_histories.3p_id',
			]);

			return Datatables::of($histories)
				->editColumn('action', function ($history) {
					return UserHistory::$types[$history->action];
				})
				->editColumn('created_at', function ($history) {
					return $history->created_at->format('d/m/Y H:i:s');
				})
				->editColumn('3p_id', function ($history) {
					return $history->third_party ? $history->third_party->name : '<span class ="glyphicon glyphicon-remove"></span>';
				})
				->removeColumn('id')
				->rawColumns(['created_at', 'action', '3p_id'])
				->make();
		}

		return view('users.histories', compact('view_user'));
	}

	public function resendConfirmation($id)
	{
		$user = User::findOrFail($id);

		if ($user->confirmed) {
			$this->_access_denied();
		}

		// yg ni mmg dh comment dri asal
		// Mail::send(config('confide::email_account_confirmation'), ['user' => $user],
		// function ($message) use ($user) {
		// 	$message->to(trim($user->email))
		// 	->subject(trans('auth.email.account_confirmation.subject'));
		// });

		Mail::to($user)->send(new ConfirmRegistration($user)); // yg ni yg commented baru 24/11/2022

		if ($user->hasRole('Vendor')) {
			$redirect = redirect('vendors/' . $user->vendor_id);
		} else {
			$redirect = redirect('users/' . $user->id . '/edit');
		}

		UserHistory::log($user->id, 'resend-confirmation', auth()->user()->id);

		return $redirect->with('notice', "Emel pengesahan telah dihantar semula.");
	}

	public function pendingApproval(Request $request)
	{
		if (!auth()->user()->canApprove()) {
			return $this->_access_denied();
		}

		if ($request->ajax()) {
			$users = User::with('agency')->pendingApproval();

			if (!auth()->user()->hasRole('Admin')) {
				$users = $users->where('organization_unit_id', auth()->user()->organization_unit_id);
			}

			$users = $users->select([
				'users.id',
				'users.name',
				'users.email',
				'users.organization_unit_id',
				'users.confirmed'
			]);

			return Datatables::of($users)
				->editColumn('first_name', function ($user) {
					return $user->name;
				})
				->editColumn('organization_unit_id', function ($user) {
					if ($user->agency) {
						return $user->agency->name;
					}
				})
				->editColumn('confirmed', function ($user) {
					return $user->status();
				})
				->addColumn('actions', function ($data) {
					$actions = [];
					$actions[] = $data->canShow() ? link_to_route('users.approval', 'Sahkan', $data->id, ['class' => 'btn btn-xs btn-primary']) : '';

					return '<div class="btn-group">' . implode(' ', $actions) . '</div>';
				})
				->removeColumn('id')
				->removeColumn('last_name')
				->rawColumns(['name', 'email', 'organization_unit_id', 'confirmed', 'actions'])
				->make();
		}

		return view('users.pending-approval');
	}

	public function approval(Request $request, $id)
	{
		$currentUser = User::find($id);

		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!auth()->user()->canApprove()) {
			return $this->_access_denied();
		}

		return view('users.approval', compact('currentUser'));
	}

	public function storeApproval(Request $request, $id)
	{
		$user = User::findOrFail($id);
		$data = $request->all();

		if (!auth()->user()->hasRole('Admin')) {
			$data['organization_unit_id'] = auth()->user()->organization_unit_id;
		}

		if (isset($data['organization_unit_id']) && empty($data['organization_unit_id'])) {
			$data['organization_unit_id'] = null;
		}

		$data['remark'] = $data['approved'] == 1 ? $data['remark_txt'] : $data['remark_dropdown'];
		$data['approver_id'] = auth()->id();

		if (!auth()->user()->canApprove()) {
			return $this->_access_denied();
		}

		User::setRules('storeApproval');
		$user->fill($data);

		if (!$user->updateUniques()) {
			return $this->_validation_error($user);
		}
		$user->save();

		$data['roles'] = isset($data['roles']) ? $data['roles'] : [];
		$user->roles()->sync($data['roles']);

		if ($request->ajax()) {
			return $user;
		}

		if ($user->approved == 1) {
			// Mail::send('users.emails.application-approved', ['user' => $user], function ($message) use ($user) {
			// 	$message->to($user->email);
			// 	$message->subject('Status Permohonan Akaun Agensi');
			// });

			$to			= trim($user->email);
			$subject 	= 'Status Permohonan Akaun Agensi';
			$send_status = $this->sendMail("html", $to, $subject, "", "users.emails.application-approved", ['user' => $user]);
			
		} else {
			// Mail::send('users.emails.application-rejected', ['user' => $user], function ($message) use ($user) {
			// 	$message->to($user->email);
			// 	$message->subject('Status Permohonan Akaun Agensi');
			// });

			$to			= trim($user->email);
			$subject 	= 'Status Permohonan Akaun Agensi';
			$send_status = $this->sendMail("html", $to, $subject, "", "users.emails.application-rejected", ['user' => $user]);
			
			$user->delete();
		}

		session()->remove('_old_input');
		UserHistory::log($user->id, 'approval');

		return redirect('users/pending-approval')->with('success', $this->updated_message);
	}

	public function accountReview($id)
	{

		// echo 'id url - ' . $id . '<br>'	;
		// echo 'id url - ' . Crypt::decrypt($id) . '<br>'	;
		// //echo 'id auth - ' . auth()->id();
		// return;

		try {
			$user = User::find(Crypt::decrypt($id));
			if ($user) {
				$user->arr = 1;
				$user->confirmed = 1; // menukarkan semula status user kepada aktif setelah email arr dihantar dan sekiranya lebih dari 3 bulan, akaun akan disekat
				$user->save(); 
				return redirect()->to('/')->with('success', 'Akaun telah disemak dan anda boleh log masuk semula pada sistem.');
			} else {
				return redirect()->to('/')->with('error', 'Akaun tidak padan untuk dikemaskini.');
			}
		} catch (exception $e) {
			return redirect()->to('/')->with('error', 'Akaun tidak padan untuk dikemaskini.');
		}

		// if ($id == auth()->id()) {
		// 	$user      = User::find(auth()->id());
		// 	$user->arr = 1;
		// 	$user->save();

		// 	return redirect()->to('/')->with('notice', 'Akaun telah berjaya dikemaskini.');
		// 	} else {
		// 		return redirect()->to('/')->with('error', 'Akaun tidak padan untuk dikemaskini.');
		// }
	}


	// public function __construct()
	// {
	//     parent::__construct();
	//     View::share('controller', 'UsersController');
	// }

	public function sendArr()
	{
		$today = Carbon::today();
		$users = User::active()
			->where(function ($query) use ($today) {
				$query->whereNull('arr_sent_at')
					->orWhere('arr_sent_at', '<', $today->subMonths(3)); // Semakan tarikh Arr Send At melebihi 3 bulan e-mail akan dihantar
			})
			->whereNotNull('organization_unit_id')
			->whereNotIn('email', ['anonymous', 'tenderadmin@selangor.gov.my'])
			//->where('email', 'hafiz@selangor.gov.my') // nak test single email user terlibat
			->get();

		foreach ($users as $user) {
			// Mail::send('users.emails.account-review-request', ['user' => $user], function ($message) use ($user) {
			//     $message->to($user->email);
			//     $message->subject('Permintaan Semakan Akaun Oleh Sistem Tender');
			// });

			$to			= trim($user->email);
			$subject 	= 'Permintaan Semakan Akaun Oleh Sistem Tender';
			$send_status = $this->sendMail("html", $to, $subject, "", "users.emails.account-review-request", ['user' => $user]);

			$user->arr_sent_at = Carbon::now();
			$user->arr = 0; // Jika user lebih dari 3 bulan akan bertukar tidak disemak
			$user->confirmed = 0; // Jika user lebih 3 bulan akan berstatus tidak aktif
			$user->save();
		}

		echo '... Dah update SendARR selesai';
	}

	public function getUserByAgencies(Request $request)
	{
		$search = $request->search;
		$org_id = $request->id;

		if ($search == '') {
			$users = User::orderby('name', 'asc')->select('id', 'name')->where('organization_unit_id', $org_id)->get();
		} else {
			$users = User::orderby('name', 'asc')->select('id', 'name')->where('name', 'like', '%' . $search . '%')->where('organization_unit_id', $org_id)->get();
		}

		$response = array();
		$response[] = array(
			"id" => '',
			"text" => '-- Pilih Pegawai --'
		);
		foreach ($users as $user) {
			$response[] = array(
				"id" => $user->id,
				"text" => $user->name
			);
		}
		return response()->json($response);
	}

	public function getUserById(Request $request)
	{
		$id = $request->id;

		$users = User::orderby('name', 'asc')->select('tel', 'department', 'email')->where('id', $id)->get();

		$response = array();
		foreach ($users as $user) {
			$response[] = array(
				"tel" => $user->tel,
				"department" => $user->department,
				"email" => $user->email,
			);
		}
		return response()->json($response);
	}
}
