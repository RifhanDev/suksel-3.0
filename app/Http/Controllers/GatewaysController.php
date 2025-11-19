<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Former;
use App\Gateway;

class GatewaysController extends Controller
{
	public function index(Request $request) {
		if(!Gateway::canList())
			return $this->_access_denied();

		if($request->ajax()) {
			$gateways = Gateway::with('agency');

			$gateways = $gateways->select([
					'id',
					'organization_unit_id',
					'type',
					'merchant_code',
					'version',
					'active',
					'default'
			]);

			return Datatables::of($gateways)
				->editColumn('organization_unit_id', function($gateway){
					return $gateway->agency->name;
				})
				->editColumn('version', function($g){
					return $g->type == 'fpx' ? $g->version : 'N/A';
				})
				->editColumn('type', function($g){
					return Gateway::$methods[$g->type];
				})
				->editColumn('active', function($g){
					return boolean_icon($g->active);
				})
				->editColumn('default', function($g){
					return boolean_icon($g->default);
				})
				->addColumn('actions', function($gateway){
	                    	$actions   = [];
	                    	$actions[] = $gateway->canUpdate() ? link_to_route('gateways.edit', 'Kemaskini', $gateway->id, ['class' => 'btn btn-xs btn-primary'] ) : '';
	                    	$actions[] = $gateway->canDelete() ? Former::open(url('gateways/'.$gateway->id))->class('form-inline') 
	                        . Former::hidden('_method', 'DELETE')
	                        . '<button type="button" class="btn btn-xs btn-danger confirm-delete">Padam</button>'
	                        . Former::close() : '';
	                    	return implode(' ', $actions);
	                	})
				->removeColumn('id')
				->rawColumns(['organization_unit_id', 'type', 'merchant_code', 'version', 'active', 'default', 'actions'])
				->make();
		}

		return view('gateways.index');
	}

	public function create() {
		if(!Gateway::canCreate())
			$this->_access_denied();
		$gateway = new Gateway;
		return view('gateways.create', compact('gateway'));
	}

	public function store(Request $request) {
		if(!Gateway::canCreate())
			$this->_access_denied();

		$data = $request->all();
		if(!isset($data['active'])) $data['active'] = 0;
		if(!isset($data['default'])) $data['default'] = 0;

		Gateway::setRules('store');

		$gateway = new Gateway($data);
		if(!$gateway->save())
			return $this->_validation_error($gateway);

		return redirect('gateways')->with('success', $this->created_message);
	}

	public function edit($id) {
		$gateway = Gateway::findOrFail($id);
		if(!$gateway->canUpdate())
			$this->_access_denied();
		return view('gateways.edit', compact('gateway'));
	}

	public function update(Request $request, $id) {
		$gateway = Gateway::findOrFail($id);
		if(!$gateway->canUpdate())
			$this->_access_denied();

		$data = $request->all();
		if(!isset($data['active'])) $data['active'] = 0;
		if(!isset($data['default'])) $data['default'] = 0;

		Gateway::setRules('update');
		if(!$gateway->update($data))
			return $this->_validation_error($gateway);

		return redirect('gateways')->with('success', $this->updated_message);
	}

   public function destroy($id) {
   	$gateway = Gateway::findOrFail($id);

   	if(!$gateway->canDelete())
   		return $this->_access_denied();

   	$gateway->delete();

   	return redirect('gateways')->with('success', $this->deleted_message);
   }

	public function __construct() {
		// parent::__construct();
		// view()->share('controller', 'Gateway');
	}
}
