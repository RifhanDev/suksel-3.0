<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Remark;

class RemarksController extends Controller
{
	public function index(Request $request, $parent_id)
	{
		if (!Remark::canList()) {
			return $this->_access_denied();
		}
		$parent = Vendor::findOrFail($parent_id);
		if ($request->ajax()) {
			return $parent->remarks()->with('user')->orderBy('created_at', -1)->get();
		}
		return view('remarks.index', compact('parent_id', 'parent'));
	}

	/**
	 * Store a newly created remark in storage.
	 *
	 * @return Response
	 */
	public function store($parent_id)
	{
		$data = Input::all();
		Remark::setRules('store');
		if (!Remark::canCreate()) {
			return $this->_access_denied();
		}
		$remark = new Remark;
		$remark->vendor_id = $parent_id;
		$remark->user_id = Auth::user()->id;
		$remark->fill($data);
		if (!$remark->save()) {
			return $this->_validation_error($remark);
		}
		if ($request->ajax()) {
			$remark->user;
			return response()->json($remark, 201);
		}
		// return Redirect::action('RemarksController@index', $parent_id)->with('success', $this->created_message);
		return redirect('vendor/' . $parent_id . '/remarks')->with('success', $this->created_message);
	}

	/**
	 * Update the specified remark in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($parent_id, $id)
	{
		$remark = Remark::findOrFail($id);
		Remark::setRules('update');
		$data = Input::all();
		if (!$remark->canUpdate()) {
			return $this->_access_denied();
		}
		if (!$remark->update($data)) {
			return $this->_validation_error($remark);
		}
		if ($request->ajax()) {
			$remark->user;
			return $remark;
		}
		session()->remove('_old_input');
		// return Redirect::action('RemarksController@edit', [$parent_id, $id])->with('success', $this->updated_message);
		return redirect('vendor/' . $parent_id . '/remarks/' . $id . '/edit')->with('success', $this->updated_message);
	}

	/**
	 * Remove the specified remark from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($parent_id, $id)
	{
		$remark = Remark::findOrFail($id);
		if (!$remark->canDelete()) {
			return $this->_access_denied();
		}
		$remark->delete();
		if ($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return Redirect::action('RemarksController@index', $parent_id)->with('success', $this->deleted_message);
		return redirect('vendor/' . $parent_id . '/remarks')->with('success', $this->deleted_message);
	}

	/**
	 * Constructor
	 */

	public function __construct()
	{
		// parent::__construct();
		// View::share('controller', 'Remark');
	}
}
