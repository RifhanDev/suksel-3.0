<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Former;
use Datatables;
use App\Code;

class CertificationCodesController extends Controller
{
	public function index(Request $request) {
		if(!Code::canList()) {
			return $this->_access_denied();
		}

		if($request->ajax()) {

			if($request->xdt) {
				$type = $request->type;
				$codes = Code::whereNotNull('codes.created_at');
				if($type) $codes = $codes->where('type', $type);
				return $codes->orderBy('code', 'asc')->get();
			}

			$users_under_me = auth()->user()->getAuthorizedUserids(Code::$show_authorize_flag);
			if(empty($users_under_me)) {
				$certificationcodes = Code::orderBy('code')->whereNotNull('codes.created_at');	
			} else {
				$certificationcodes = Code::orderBy('code')->whereIn('codes.user_id', $users_under_me);	
			}

			if($request->type && Code::typeExists($request->type))
				$certificationcodes = $certificationcodes->whereType($request->type);

			$certificationcodes = $certificationcodes->select([
				'codes.id',
				'codes.code',
				'codes.name',
				'codes.type'
			]);
			$datatable = Datatables::of($certificationcodes)
								->editColumn('type', function($code){
									return Code::$type[$code->type];
								})
								->addColumn('actions', function($certificationcode){
									$actions   = [];
									$actions[] = '<div class="btn-group">';
									$actions[] = $certificationcode->canUpdate() ? link_to_action('CertificationCodesController@edit', 'Kemaskini', $certificationcode->id, ['class' => 'btn btn-xs btn-primary'] ) : '';
									$actions[] = $certificationcode->canDelete() ? Former::open(action('CertificationCodesController@destroy', $certificationcode->id))->class('form-inline') 
									. Former::hidden('_method', 'DELETE')
									. '<button type="button" class="btn btn-xs btn-danger confirm-delete">Delete</button>'
									. Former::close() : '';
									$actions[] = '</div>';
									return implode(' ', $actions);
                			});

      if($request->type && Code::typeExists($request->type))
      	$datatable->removeColumn('type');

      return $datatable->removeColumn('id')->rawColumns(['code', 'name', 'type', 'actions'])->make();
		}

		return view('certificationcodes.index');
	}

	public function certifications()
	{
		return Code::get()->map(function($code){
			return [
				'value' => $code->id,
				'content' => $code->name,
			];
		});
	}

	/**
	 * Show the form for creating a new certificationcode
	 *
	 * @return Response
	 */
	public function create(Request $request) {
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!Code::canCreate()) {
			return $this->_access_denied();
		}
		return view('certificationcodes.create');
	}

	/**
	 * Store a newly created certificationcode in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {

		$data = $request->all();
		Code::setRules('store');
		if(!Code::canCreate()) {
			return $this->_access_denied();
		}
		$certificationcode = new Code;
		$certificationcode->fill($data);
		if(!$certificationcode->save()) {
			return $this->_validation_error($certificationcode);
		}
		if($request->ajax()) {
			return response()->json($certificationcode, 201);
		}
		return redirect('codes')->with('success', $this->created_message);
	}

	/**
	 * Display the specified certificationcode.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id) {

		$certificationcode = Code::findOrFail($id);
		if(!$certificationcode->canShow()) {
			return $this->_access_denied();
		}
		if($request->ajax()) {
			return response()->json($certificationcode);
		}
		return view('certificationcodes.show', compact('certificationcode'));
	}

	/**
	 * Show the form for editing the specified certificationcode.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id) {
		$certificationcode = Code::findOrFail($id);
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!$certificationcode->canUpdate()) {
			return _access_denied();
		}
		return view('certificationcodes.edit', compact('certificationcode'));
	}

	/**
	 * Update the specified certificationcode in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$certificationcode = Code::findOrFail($id);
		Code::setRules('update');
		$data = $request->all();
		if(!$certificationcode->canUpdate()) {
			return $this->_access_denied();
		}
		if(!$certificationcode->update($data)) {
			return $this->_validation_error($certificationcode);
		}
		if($request->ajax()) {
			return $certificationcode;
		}
		session()->forget('_old_input');
		return redirect('codes/'.$id)->with('success', $this->updated_message);
	}

	/**
	 * Remove the specified certificationcode from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id) {
		$certificationcode = Code::findOrFail($id);
		if(!$certificationcode->canDelete()) {
			return $this->_access_denied();
		}
		$certificationcode->delete();
		if($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('codes')->with('success', $this->deleted_message);
	}

	/**
	 * Custom Methods. Dont forget to add these to routes: Route::get('example/name', 'ExampleController@getName');
	 */
	
	// public function getName()
	// {
	// }

	/**
	 * Constructor
	 */

	public function __construct() {
		// parent::__construct();
		// view()->share('controller', 'Certificationcode');
	}
}
