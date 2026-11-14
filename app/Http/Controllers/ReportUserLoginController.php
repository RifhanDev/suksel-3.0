<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Exports\UserLogin;
use App\User;
use App\UserHistory;

use App\Http\Controllers\Concerns\AuthorizesReportAccess;

class ReportUserLoginController extends Controller
{
	use AuthorizesReportAccess;

	public function index() {
		if ($denied = $this->denyUnlessReport('Report:view:user_login')) {
			return $denied;
		}

     	$select_users = User::whereHas('roles', function($query) {
         return $query->whereNotIn('name', ['Vendor']);
     	})->get();

     return view('reports.user.login.index', compact('select_users'));
   }

   public function view(Request $request) {
		if ($denied = $this->denyUnlessReport('Report:view:user_login')) {
			return $denied;
		}

        
     	$user_id    = $request->input('user_id');
     	$user       = User::findOrFail($user_id);

     	return view('reports.user.login.view', [
         'data'  => $this->query($user_id),
         'user'  => $user
     	]);
   }

   public function excel(Request $request) {
		if ($denied = $this->denyUnlessReport('Report:view:user_login')) {
			return $denied;
		}


     	$user_id    = $request->input('user_id');
     	$user       = User::findOrFail($user_id);
     	$data       = $this->query($user_id);

     	return Excel::download(new UserLogin($data, $user), 'Aktiviti Login.xlsx');
   }

   public function query($user_id) {
     	return UserHistory::with('user', 'user.agency')->where3pId($user_id)->leftJoin('users', 'users.id', '=', 'user_histories.user_id')->whereAction('sign-in')->whereNotNull('users.id')->orderBy('user_histories.created_at', 'desc')->select(['user_histories.*'])->get();
   }

}
