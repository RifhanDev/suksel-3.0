<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Former;
use Datatables;
use App\Role;

class RolesController extends Controller
{
	/**
	* Display a listing of roles
	*
	* @return Response
	*/
	public function index(Request $request) {
		if (!Role::canList()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			$roles = Role::with('perms')
				->select(['roles.id', 'roles.name']);
			return Datatables::of($roles)
				->addColumn('actions', function($role){
					$actions   = [];
					$actions[] = $role->canUpdate() ? link_to_route('roles.edit', 'Kemaskini', $role->id, ['class' => 'btn btn-sm btn-default'] ) : '';
					$actions[] = $role->canDelete() ? Former::open(url('roles/'.$role->id))->class('form-inline') 
					. Former::hidden('_method', 'DELETE')
					. '<button type="button" class="btn btn-sm btn-danger confirm-delete">Padam</button>'
					. Former::close() : '';
					return implode(' ', $actions);
				})
				->addColumn('user_count', function($role){
					return $role->users()->count();
				})
				->addColumn('permissions', function($role){
					return '<ul>' . implode('', array_map(function($name){ return '<li>' . $name . '</li>'; }, $role->perms->pluck('name')->toArray())) . '</ul>';
				})
				->removeColumn('id')
				->rawColumns(['name', 'permissions', 'user_count', 'actions'])
				->make();
		}

		return view('roles.index');
	}
	
	/**
	* Show the form for creating a new role
	*
	* @return Response
	*/
	public function create(Request $request) {
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!Role::canCreate()) {
			return $this->_access_denied();
		}
		return view('roles.create');
	}
	
	/**
	* Store a newly created role in storage.
	*
	* @return Response
	*/
	public function store(Request $request) {
		$data = $request->all();
		if (!Role::canCreate()) {
			return $this->_access_denied();
		}
		Role::setRules('store');
		$role = new Role;
		$role->fill($data);
		if (!$role->save()) {
			return $this->_validation_error($role);
		}
		$data['perms'] = isset($data['perms'])?$data['perms']:[];
		$role->perms()->sync($data['perms']);
		if ($request->ajax()) {
			return response()->json($role, 201);
		}
		return redirect('roles')->with('success', $this->created_message);
	}
	
	/**
	* Display the specified role.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show(Request $request, $id) {
		$role = Role::findOrFail($id);
		if (!$role->canShow()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			return $role;
		}
		return view('roles.show', compact('role'));
	}
	
	/**
	* Show the form for editing the specified role.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit(Request $request, $id) {
		$role = Role::find($id);
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$role->canUpdate()) {
			return $this->_access_denied();
		}
		return view('roles.edit', compact('role'));
	}
	
	/**
	* Update the specified role in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update(Request $request, $id) {
		$role = Role::findOrFail($id);
		$data = $request->all();
		Role::setRules('update');
		if (!$role->canUpdate()) {
			return $this->_ajax_denied();
		}
		$role->fill($data);
		if (!$role->updateUniques()) {
			return $this->_validation_error($role);
		}
		$data['perms'] = isset($data['perms'])?$data['perms']:[];
		$role->perms()->sync($data['perms']);
		$role->touch();
		if ($request->ajax()) {
			return $role;
		}
		session()->remove('_old_input');
		return redirect('roles')->with('success', $this->updated_message);
	}
	
	/**
	* Remove the specified role from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy(Request $request, $id) {
		$role = Role::findOrFail($id);
		if (!$role->canDelete()) {
			return $this->_access_denied();
		}
		if (!$role->delete()) {
			return $this->_delete_error();
		}
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('roles')->with('success', $this->deleted_message);
	}
	
	public function __construct() {
		// parent::__construct();
		// view()->share('controller', 'RolesController');
	}
}
