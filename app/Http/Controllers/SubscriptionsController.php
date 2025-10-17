<?php

namespace App\Http\Controllers;

use App\Traits\Helper;
use Illuminate\Http\Request;
use Datatables;
use PDF;
use App\Vendor;
use App\Subscription;

class SubscriptionsController extends Controller
{
	use Helper;
	/**
	* Display a listing of subscriptions
	*
	* @return Response
	*/
	public function index(Request $request, $parent_id) {

		if(!Subscription::canList()) {
			return $this->_access_denied();
		}

		if($request->ajax()) {

			$subscriptions = Subscription::join('transactions', 'transactions.id', '=', 'subscriptions.transaction_id')
				->where('vendor_id', $parent_id);
				$subscriptions = $subscriptions->select([
					'subscriptions.id',
					'subscriptions.start_date',
					'subscriptions.end_date',
					'transactions.created_at',
					'transactions.receipt_number',
					'transactions.amount'
				]);
				return Datatables::of($subscriptions)
					->removeColumn('id')
					->rawColumns(['start_date', 'end_date', 'created_at', 'receipt_number', 'amount'])
					->make();
		}

		$parent = Vendor::findOrFail($parent_id);
		return view('subscriptions.index', compact('parent_id', 'parent'));
	}
	
	/**
	* Show the form for creating a new subscription
	*
	* @return Response
	*/
	public function create($parent_id) {
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!Subscription::canCreate()) {
		return $this->_access_denied();
		}
		$parent = Vendor::findOrFail($parent_id);
		return view('subscriptions.create', compact('parent_id', 'parent'));
	}
	
	/**
	* Store a newly created subscription in storage.
	*
	* @return Response
	*/
	public function store(Request $request, $parent_id) {
		$parent = Vendor::findOrFail($parent_id);
		Subscription::setRules('store');
		if(!Subscription::canCreate()) {
			return $this->_access_denied();
		}

		$subscription            = new Subscription;
		$subscription->vendor_id = $parent_id;
		$subscription_dates      = $parent->getNewExpiryDates();
		
		$transaction = $parent->transactions()->save(new Transaction([
			'organization_unit_id' => 1,
			'receipt_number'       => 'test',
			'from'                 => 'Renewal',
			'status'               => 'SUCCESS',
			'user_id'              => auth()->user()->id,
			'amount'               => 100
		]));
		
		$data['start_date']     = $subscription_dates[0];
		$data['end_date']       = $subscription_dates[1];
		$data['transaction_id'] = $transaction->id;
		$subscription->fill($data);
		if(!$subscription->save()) {
			return $this->_validation_error($subscription);
		}
		$parent->expiry_date = $data['end_date'];
		$parent->save();
		if($request->ajax()) {
			return response()->json($subscription, 201);
		}
		return redirect('SubscriptionsController@index', $parent_id)->with('success', $this->created_message);
	}
	
	/**
	* Display the specified subscription.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show(Request $request, $parent_id, $id) {
		$subscription = Subscription::findOrFail($id);
		if(!$subscription->canShow()) {
			return $this->_access_denied();
		}
		if($request->ajax()) {
			return response()->json($subscription);
		}
		$parent = Vendor::findOrFail($parent_id);

		return view('subscriptions.show', compact('subscription', 'parent_id', 'parent'));
	}
	
	/**
	* Show the form for editing the specified subscription.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit(Request $request, $parent_id, $id) {

		$subscription = Subscription::findOrFail($id);
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!$subscription->canUpdate()) {
			return $this->_access_denied();
		}
		$parent = Vendor::findOrFail($parent_id);
		return view('subscriptions.edit', compact('subscription', 'parent_id', 'parent'));
	}
	
	/**
	* Update the specified subscription in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update(Request $request, $parent_id, $id) {
		$subscription = Subscription::findOrFail($id);
		Subscription::setRules('update');
		$data = Input::all();
		if(!$subscription->canUpdate()) {
			return $this->_access_denied();
		}
		if(!$subscription->update($data)) {
			return $this->_validation_error($subscription);
		}
		if($request->ajax()) {
			return $subscription;
		}
		session()->remove('_old_input');
		return redirect('SubscriptionsController@edit', [$parent_id, $id])->with('success', $this->updated_message);
	}
	
	/**
	* Remove the specified subscription from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy(Request $request, $parent_id, $id) {
		$subscription = Subscription::findOrFail($id);
		if(!$subscription->canDelete()) {
			return $this->_access_denied();
		}
		$subscription->delete();
		if($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('SubscriptionsController@index', $parent_id)->with('success', $this->deleted_message);
	}
	
	public function receipt($parent_id, $id) {
		if(auth()->guest()) return $this->_access_denied();
		
		$vendor			= Vendor::findOrFail($parent_id);
		$subscription	= $vendor->subscriptions()->findOrFail($id);
		
		if(auth()->user()->hasRole('Vendor') && auth()->user()->vendor_id != $vendor->id) return $this->_access_denied();
		
		$receipt = $this->receiptNumGenerator($subscription->transaction->number,date('d-m-Y', strtotime($subscription->transaction->created_at)));
		$type = 'SALINAN';
		if($subscription->transaction->receipt_generated_at == null) {
			$subscription->transaction->receipt_generated_at = date('Y-m-d H:i:s');
			$subscription->transaction->update();
			$type = 'ASAL';
		}
		
		return view('subscriptions.receipt', compact('vendor', 'subscription', 'type', 'receipt'));
		//return PDF::loadView('subscriptions.receipt', compact('vendor', 'subscription', 'type'))->stream();
	}
	
	/**
	* Constructor
	*/
	
	// public function __construct()
	// {
	// parent::__construct();
	// View::share('controller', 'Subscription');
	// }
}
