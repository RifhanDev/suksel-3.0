<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use App\User;
use App\UserHistory;

class ProfileController extends Controller
{
   public $change_password_invalid_message = 'Kata Laluan yang salah.';
   public $change_password_message         = 'Kata Laluan berjaya diubah.';

   public function show() {

      return view('profile.show');
   }

   public function changePassword() {
      	return view('profile.change_password');
   }

   public function doChangePassword(Request $request) {
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

        	$user->password = Hash::make($data['password']);
        	$user->save();

        	if ($request->ajax()) {
            return response()->json($this->change_password_message);
        	}
        	UserHistory::log($user->id, 'password-update');
        	return redirect('profile')->with('success', $this->change_password_message);
   }

   public function releaseUser() {

        	$currentUserId = auth()->user()->id;
        	$originalUserId = session()->pull('original_user_id');

        	$user = User::find($originalUserId);

        	if(!$user || !$user->hasRole('Admin')) {
            return redirect()->back()->with('error', 'Not authorized.');
        	}

        	auth()->login($user);

          if($user->can('Vendor:list'))
            return redirect('vendors');
          else
            return redirect('agencies/'.$user->organization_unit_id);
        	// return redirect('users/'.$currentUserId.'/edit');

   }

}
