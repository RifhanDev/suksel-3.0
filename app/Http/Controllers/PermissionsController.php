<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Former;
use App\Permission;

class PermissionsController extends Controller
{
	/**
	* Display a listing of permissions
	*
	* @return Response
	*/
	public function index(Request $request) {
		if (!Permission::canList()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			$permissions = Permission::select(['id', 'group_name', 'name', 'display_name']);
			return Datatables::of($permissions)
					->addColumn('actions', function($data){
						$actions   = [];
						$actions[] = $data->canUpdate() ? link_to_route('permissions.edit', 'Kemaskini', $data->id, ['class' => 'btn btn-sm btn-default'] ) : '';
						$actions[] = $data->canDelete() ? Former::open(url('permissions/'.$data->id))->class('form-inline') 
						. Former::hidden('_method', 'DELETE')
						. '<button type="button" class="btn btn-sm btn-danger confirm-delete">Padam</button>'
						. Former::close() : '';
						return implode(' ', $actions);
					})
					->removeColumn('id')
					->rawColumns(['group_name', 'name', 'display_name', 'actions'])
					->make();
		}
		return view('permissions.index');
	}
	
	/**
	* Show the form for creating a new permission
	*
	* @return Response
	*/
	public function create(Request $request) {
		if ($request->ajax()) {
			return _ajax_denied();
		}
		if (!Permission::canCreate()) {
			return $this->_access_denied();
		}
		return view('permissions.create');
	}

	/**
	* Store a newly created permission in storage.
	*
	* @return Response
	*/
	public function store(Request $request) {
		if (!Permission::canCreate()) {
			return _access_denied();
		}
		Permission::setRules('store');
		$permission = new Permission;
		$permission->fill($request->all());
		if (!$permission->save()) {
			return $this->_validation_error($permission);
		}
		if ($request->ajax()) {
			return response()->json($permission, 201);
		}
	return redirect('permissions')->with('success', $this->created_message);
	}

	/**
	* Display the specified permission.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show(Request $request, $id) {
		$permission = Permission::findOrFail($id);
		if (!$permission->canShow()) {
			return _access_denied();
		}
		if ($request->ajax()) {
			return $permission;
		}

		return view('permissions.show', compact('permission'));
	}

	/**
	* Show the form for editing the specified permission.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit(Request $request, $id) {
		$permission = Permission::find($id);
		if ($request->ajax()) {
			return _ajax_denied();
		}
		if (!$permission->canUpdate()) {
			return _access_denied();
		}
		return view('permissions.edit', compact('permission'));
	}

	/**
	* Update the specified permission in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update(Request $request, $id) {
	$permission = Permission::findOrFail($id);
		Permission::setRules('update');
		if (!$permission->canUpdate()) {
			return _access_denied();
		}
		$permission->fill($request->all());
		if (!$permission->updateUniques()) {
			return $this->_validation_error($permission);
		}
		$permission->save();
		if ($request->ajax()) {
			return $permission;
		}
		session()->remove('_old_input');
		return redirect('permissions')->with('success', $this->updated_message);
	}

	/**
	* Remove the specified permission from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy(Request $request, $id) {

		$permission = Permission::findOrFail($id);
		if (!$permission->canDelete()) {
			return _access_denied();
		}
		if (!$permission->delete()) {
			return $this->_delete_error();
		}
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('permissions')->with('success', $this->deleted_message);
	}
	
	public function __construct() {
		// parent::__construct();
		// view()->share('controller', 'PermissionsController');
	}
}
