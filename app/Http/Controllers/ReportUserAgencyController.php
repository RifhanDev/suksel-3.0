<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Excel;
use App\Exports\UserAgency;
use App\Role;
use App\User;
use App\OrganizationUnit;

class ReportUserAgencyController extends Controller
{
	public function index() {
		$select_roles    = Role::where('name', '!=', 'Vendor')->orderBy('name', 'asc')->pluck('name', 'id');
		$select_agencies = OrganizationUnit::orderBy('name', 'asc')->pluck('name', 'id');
		return view('reports.user.agency.index', compact('select_roles', 'select_agencies'));
   }


   public function view(Request $request) {

		$roles  = $request->input('roles[]', []);
		$agency = auth()->user()->hasRole('Admin') ? $request->input('agency', null) : auth()->user()->organization_unit_id;
		
		$validator = Validator::make([
			'agency'    => $agency
		],[
			'agency'    => 'required|exists:organization_units,id'
		]);
		$validator->setAttributeNames([
			'agency'    => 'Agensi'
		]);
		
		if($validator->fails()) {
			$title = 'Senarai Pengguna Agensi';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		$users  = User::where('organization_unit_id', $agency);
		if(count($roles) > 0 ) $users = $users->whereHas('roles', function($q) use($roles) { $q->whereIn('name', $roles); });
		$users = $users->get();
		$agency = OrganizationUnit::find($agency);
		return view('reports.user.agency.view', compact('users', 'agency', 'roles'));
   }

   public function excel(Request $request) {
     	$roles  = $request->input('roles[]', []);
     	$agency = auth()->user()->hasRole('Admin') ? $request->input('agency', null) : auth()->user()->organization_unit_id;

     	$users  = User::where('organization_unit_id', $agency);
     	if(count($roles) > 0 ) $users = $users->whereHas('roles', function($q) use($roles) { $q->whereIn('name', $roles); });
     	$users = $users->get();
     	$agency = OrganizationUnit::find($agency);

     	return Excel::download(new UserAgency($users, $agency), 'Senarai Pengguna Agensi.xlsx');
   }

}
