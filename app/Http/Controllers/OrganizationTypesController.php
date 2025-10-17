<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Former;
use App\OrganizationType;
use App\Code;

class OrganizationTypesController extends Controller
{

	public function index(Request $request) {
		if(!OrganizationType::canList())
			return $this->_access_denied();

		if($request->ajax()) {
			$types = OrganizationType::whereNotNull('organization_types.created_at');
			$types = $types->select([
				'organization_types.id',
				'organization_types.name',
				'organization_types.sort_no',
			])
			->orderBy('sort_no');
			return Datatables::of($types)
                	->addColumn('actions', function($certificationcode){
                    	$actions   = [];
                    	$actions[] = $certificationcode->canUpdate() ? link_to_route('organizationtypes.edit', 'Kemaskini', $certificationcode->id, ['class' => 'btn btn-xs btn-default'] ) : '';
                    	$actions[] = $certificationcode->canDelete() ? Former::open(url('organizationtypes/'.$certificationcode->id))->class('form-inline') 
                        . Former::hidden('_method', 'DELETE')
                        . '<button type="button" class="btn btn-xs btn-danger confirm-delete">Padam</button>'
                        . Former::close() : '';
                    	return implode(' ', $actions);
                	})
				->removeColumn('id')
				->rawColumns(['name', 'actions'])
				->make();
			return Datatables::of($types)->make();
		}
		return view('organizationtypes.index');
	}

	public function create(Request $request) {
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!OrganizationType::canCreate()) {
			return $this->_access_denied();
		}

		$process = 'create';
		return view('organizationtypes.create', compact('process'));
	}


	public function store(Request $request) {
		$data = $request->all();
		OrganizationType::setRules('store');
		if(!OrganizationType::canCreate()) {
			return $this->_access_denied();
		}
		$type = new OrganizationType;
		$type->fill($data);
		if(!$type->save()) {
			return $this->_validation_error($certificationcode);
		}
		if($request->ajax()) {
			return response()->json()($type, 201);
		}
		return redirect('organizationtypes')->with('success', $this->created_message);
	}


	public function show(Request $request, $id) {
		$type = OrganizationType::findOrFail($id);
		if(!$type->canShow()) {
			return $this->_access_denied();
		}
		if($request->ajax()) {
			return response()->json()($type);
		}

		return view('organizationtypes.show', compact('type'));
	}


	public function edit(Request $request, $id) {
		$process = "update";
		$type = OrganizationType::findOrFail($id);
		$list_organization_type = OrganizationType::orderBy('sort_no')->get();
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!$type->canUpdate()) {
			return _access_denied();
		}
		return view('organizationtypes.edit', compact('type', 'list_organization_type', 'process'));
	}


	public function update(Request $request, $id) {
		$type = OrganizationType::findOrFail($id);
		Code::setRules('update');
		$data = $request->all();
		if(!$type->canUpdate())	{
			return $this->_access_denied();
		}
		if(!$type->update($data)) {
			return $this->_validation_error($type);
		}
		if($request->ajax()) {
			return response()->json()($type);
		}
		session()->remove('_old_input');
		return redirect('organizationtypes')->with('success', $this->updated_message);
	}


	public function destroy(Request $request, $id) {
		$type = OrganizationType::findOrFail($id);
		if(!$type->canDelete()) {
			return $this->_access_denied();
		}
		$type->delete();
		if($request->ajax()) {
			return response()->json()($this->deleted_message);
		}
		return redirect('organizationtypes')->with('success', $this->deleted_message);
	}

	public function __construct() {
		// parent::__construct();
		// view()->share('controller', 'Certificationcode');
	}

	public function customSave(Request $request)
	{
		$id = $request->org_type_id ?? 0;
		$type = OrganizationType::findOrFail($id);
		Code::setRules('update');


		$data = $request->all();

		if(!$type->canUpdate())	{
			return $this->_access_denied();
		}
		if(!$type->update($data)) {
			return $this->_validation_error($type);
		}


		$positon = 0;
		foreach ($request->order as $id) {
            $positon++;
            $organizationType = OrganizationType::findOrFail($id);
            $organizationType->sort_no = $positon;
            $organizationType->save();
        }

		session()->remove('_old_input');

		$response = array(
			"status" => "success",
			"message" => $this->updated_message,
			"redirect" => route("organizationtypes.index")
		);

		return response()->json($response);
		// return redirect('organizationtypes')->with('success', $this->updated_message);
	}

}
