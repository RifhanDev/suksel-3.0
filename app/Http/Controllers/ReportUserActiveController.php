<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Excel;
use App\Exports\UserActive;
use App\User;
use App\OrganizationUnit;

class ReportUserActiveController extends Controller
{
	public function index() {
      $select_agencies = ['all' => 'Semua'] + OrganizationUnit::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
      return view('reports.user.active.index', compact('select_agencies'));
   }

   public function view(Request $request) {

        	$inputAgency   = $request->input('agency', []);
        	$agencyOpts = array_merge(['all'] + OrganizationUnit::pluck('id')->toArray());
        
        	$validator = Validator::make([
            'agency'  => $inputAgency
        	],[
            'agency'  => 'required|in:' . implode(',', $agencyOpts)
        	]);
        	$validator->setAttributeNames([
            'agency'    => 'Agensi'
        	]);
            
        	if($validator->fails()) {
            $title = 'Senarai Pengguna Agensi';
            $error = 'Sila pastikan pilihan medan adalah betul.';
            return view('reports.error', compact('title', 'error'));
        	}
        
        	if($inputAgency != 'all') {
            $users  = User::where('organization_unit_id', $inputAgency);
            $agencies = OrganizationUnit::where('id', $inputAgency)->get();
        	} else {
            $users = User::with('agency')->whereNotNull('organization_unit_id');
            $agencies = OrganizationUnit::all();

        	}

        	$users = $users->get();
    
        	return view('reports.user.active.view', compact('users', 'inputAgency', 'agencies'));
   }

   public function excel(Request $request) {

		$agency = auth()->user()->hasRole('Admin') ? $request->input('agency', null) : auth()->user()->organization_unit_id;
		
		$users  = User::where('organization_unit_id', $agency);
		$users = $users->get();
		$agency = OrganizationUnit::find($agency);

      return Excel::download(new UserActive($users, $agency), 'Senarai Pengguna Agensi.xlsx');
   }

}
