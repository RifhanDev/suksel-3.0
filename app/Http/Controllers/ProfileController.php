<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use App\User;
use App\UserHistory;
use Carbon\Carbon;

class ProfileController extends Controller
{
	public $change_password_invalid_message = 'Kata Laluan yang salah.';
	public $change_password_message         = 'Kata Laluan berjaya diubah.';

	public function show()
	{
		if (auth()->user()->hasRole('Vendor')) {
			return view('profile.vendor.show');
		}
		return view('profile.show');
	}

	public function changePassword()
	{
		if (auth()->user()->hasRole('Vendor')) {
			return view('profile.vendor.change_password', ['forcePasswordChange' => false]);
		}
		return view('profile.change_password', ['forcePasswordChange' => false]);
	}

	public function forceChangePassword()
	{
		if (!$this->requiresForcedPasswordChange(auth()->user())) {
			return redirect('profile/change_password');
		}

		if (auth()->user()->hasRole('Vendor')) {
			return view('profile.vendor.change_password', ['forcePasswordChange' => true]);
		}

		return view('profile.change_password', ['forcePasswordChange' => true]);
	}

	public function doChangePassword(Request $request)
	{
		$user = auth()->user();
		$data = $request->all();

		if (!Hash::check($data['old_password'], $user->password)) {

			if (!$data['old_password'] !== md5($user->password)) {

				if ($request->ajax()) {
					return response()->json($this->change_password_invalid_message, 400);
				}
				return redirect()->back()
					->withErrors($user->validationErrors)
					->withInput()
					->with('danger', $this->change_password_invalid_message);
			}
		}
		$validator = Validator::make($data, User::$_rules['changePassword']);
		if ($validator->fails()) {
			return $this->_validation_error($validator);
		}

		$this->storeUpdatedPassword($user, $data['password']);

		if ($request->ajax()) {
			return response()->json($this->change_password_message);
		}
		return redirect('profile')->with('success', $this->change_password_message);
	}

	public function doForceChangePassword(Request $request)
	{
		$user = auth()->user();
		$data = $request->all();

		if (!$this->requiresForcedPasswordChange($user)) {
			return $this->_access_denied();
		}

		$validator = Validator::make($data, User::$_rules['changePassword']);
		if ($validator->fails()) {
			return $this->_validation_error($validator);
		}

		$this->storeUpdatedPassword($user, $data['password']);

		if ($request->ajax()) {
			return response()->json($this->change_password_message);
		}

		if ($user->hasRole('Vendor') && is_null($user->vendor)) {
			auth()->logout();
			return redirect('/auth/login')->with('error', 'Akaun anda mempunyai masalah.<br>Sila berhubung dengan Bahagian Teknologi Maklumat di <u>tenderadmin@selangor.gov.my</u> dan nyatakan alamat emel <b>(' . $user->email . '</b>) yang digunakan.');
		}

		return $this->redirectAfterPasswordChange($user)->with('success', $this->change_password_message);
	}

	protected function storeUpdatedPassword(User $user, $password)
	{
		$user->password = Hash::make($password);
		$user->password_changed_at = now();
		$user->save();

		UserHistory::log($user->id, 'password-update');
	}

	protected function redirectAfterPasswordChange(User $user)
	{
		if ($user->hasRole('Vendor')) {
			if (!$user->vendor->completed) {
				return redirect('register/company');
			}

			if (!$user->vendor->registration_paid) {
				return redirect('register/payment');
			}

			return redirect('dashboard');
		}

		if ($user->can('Vendor:list')) {
			return redirect('vendors');
		}

		if ($user->hasRole('Admin')) {
			return redirect()->route('dashboard.hq');
		}

		return redirect()->route('dashboard', ['id' => $user->organization_unit_id]);
	}

	protected function requiresForcedPasswordChange(User $user)
	{
		if (is_null($user->password_changed_at)) {
			return true;
		}

		return Carbon::parse($user->password_changed_at)->diffInMonths(Carbon::now()) >= 6;
	}

	public function releaseUser()
	{

		$currentUserId = auth()->user()->id;
		$originalUserId = session()->pull('original_user_id');

		$user = User::find($originalUserId);

		if (!$user || !$user->hasRole('Admin')) {
			return redirect()->back()->with('error', 'Not authorized.');
		}

		auth()->login($user);

		if (!$user || !$user->hasRole('Admin')) {
			return redirect()->back()->with('error', 'Not authorized.');
		}

		auth()->login($user);

		if ($user->can('Vendor:list'))
			return redirect('vendors');
		else
			return redirect('agency/' . $user->organization_unit_id);
		// return redirect('users/'.$currentUserId.'/edit');

	}
}
