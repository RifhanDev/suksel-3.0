<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tender;
use App\Gateway;
use App\Transaction;
use App\TenderVendor;

class CartController extends Controller
{
	public function index() {
		$items = session('cart_items');
		$amount = 0;
		if(is_null($items)) {
			$tenders = [];
		} else {
			$tenders = Tender::whereIn('id', $items)->get();
			$amount  = Tender::whereIn('id', $items)->sum('price');
		}
		
		if(session('cart_ou')) {
			$fpx  = Gateway::whereType('fpx')->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
			$ebpg = Gateway::whereType('ebpg')->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
		} else {
			$fpx  = Gateway::whereType('fpx')->whereDefault(1)->whereActive(1)->first();
			$ebpg = Gateway::whereType('ebpg')->whereDefault(1)->whereActive(1)->first();
		}
		
		return view('cart.index', compact('tenders', 'amount' , 'fpx', 'ebpg'));
	}
	
	public function clear() {
		session()->forget('cart_items');
		session()->forget('cart_ou');
		return redirect('cart')->with('succces', 'Senarai Tempahan telah dipadamkan.');
	}
	
	public function delete($id) {
		$items = session('cart_items');
	
		if(is_null($items)) {
			return redirect('cart')->with('succces', 'Tiada tender dalam senarai tempahan.');
		} else {
			$key = array_search((int) $id , $items);
			unset($items[$key]);
			session()->put('cart_items', $items);
			if(count($items) == 0 ) session()->forget('cart_ou');
		}
	
		return redirect('cart')->with('succces', 'Senarai Tempahan telah dikemaskini.');
	}
	
	public function checkout() {
		$items = session('cart_items');
		
		if(is_null($items)) {
		return redirect('cart')->with('error', 'Tiada tender dalam senarai tempahan.');
		} else {
			$tenders = Tender::whereIn('id', $items)->get();
			$amount  = Tender::whereIn('id', $items)->sum('price');
		}
		
		if(session('cart_ou')) {
			$fpx  = Gateway::whereType('fpx')->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
			$ebpg = Gateway::whereType('ebpg')->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
		} else {
			$fpx  = Gateway::whereType('fpx')->whereDefault(1)->whereActive(1)->first();
			$ebpg = Gateway::whereType('ebpg')->whereDefault(1)->whereActive(1)->first();
		}
		
		$items  = array_diff($items, TenderVendor::whereVendorId(auth()->user()->vendor_id)->whereParticipate(1)->pluck('tender_id')->toArray());
		session()->put('cart_items', $items);
		
		return view('cart.checkout', compact('tenders', 'amount' , 'fpx', 'ebpg'));
	}
	
	public function process(Request $request) {
		$items = session('cart_items');
	
		if(is_null($items)) {
			return redirect('cart')->with('error', 'Tiada tender dalam senarai tempahan.');
		} else {
			$items  = array_diff($items, TenderVendor::whereVendorId(auth()->user()->vendor_id)->whereParticipate(1)->pluck('tender_id')->toArray());
			session()->put('cart_items', $items);
			
			$tenders = Tender::whereIn('id', $items)->get();
			$amount  = Tender::whereIn('id', $items)->sum('price');
		}
	
		$user   = auth()->user();
		$vendor = $user->vendor;
		$method = $request->method;
		$type   = in_array($method, ['fpx-1', 'fpx-2']) ? 'fpx' : $method;
	
		if($amount > 0.00) {
			if(!in_array($method, ['fpx-1', 'fpx-2', 'ebpg'])) {
				return redirect()->back()->with('error', 'Sila pilih saluran pembayaran yang sah.');
			}
		
			if($method == 'fpx-1') {
				if((float) $amount > 30000.00) {
					return redirect()->back()->with('error', 'Had transaksi maksimum bagi Perbankan Peribadi adalah RM 30,000.00');
				}
				
				if((float) $amount < 1.00) {
					return redirect()->back()->with('error', 'Had transaksi minimum bagi Perbankan Peribadi adalah RM 1.00');
				}
			}
		
			if($method == 'fpx-2') {
				if((float) $amount > 1000000.00) {
					return redirect()->back()->with('error', 'Had transaksi maksimum bagi Perbankan Korporat adalah RM 1,000,000.00');
				}
			
				if((float) $amount < 2.00) {
					return redirect()->back()->with('error', 'Had transaksi minimum bagi Perbankan Korporat adalah RM 2.00');
				}
			}
			
			if(session('cart_ou')) {
				$gateway = Gateway::whereType($type)->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
			}
		
			if(empty($gateway)) $gateway = Gateway::whereType($type)->whereDefault(1)->whereActive(1)->first();
		} else {
		$method = 'direct';
		}
	
		$transaction = $vendor->transactions()->save(new Transaction([
			'type'                  => 'purchase',
			'method'                => $type,
			'status'                => 'pending',
			'user_id'               => $user->id,
			'organization_unit_id'  => isset($gateway) ? $gateway->organization_unit_id : config('app.global_cart_ou'),
			'amount'                => $amount,
			'ip'                    => $request->ip,
			'gateway_id'            => isset($gateway) ? $gateway->id : null,
			'cached_data'           => serialize($tenders->pluck('id'))
		]));
	
		session()->put('txn_id', $transaction->id);
		session()->put('txn_type', 'purchase');
	
		if($type == 'direct') {
			$transaction->status = 'success';
			$transaction->gateway_reference = str_random(10);
			$transaction->save();
			// $redirect = redirect( action('CartController@callback') );
			$redirect = redirect( route('cart.receipt', $transaction->id) );
		} else {
			session()->put('fpx_type', $method);
			if ($type == 'fpx' && $gateway->version == '7.0') {
				$redirect = redirect( route($type . ".bank-list") );
			} else {
				$redirect = redirect( route($type . ".connect") );
			}
		}
	
		return $redirect;
	}
	
	public function callback($transaction_id) {
		// $transaction = Transaction::findOrFail(session('txn_id', request()->input('transaction_id')));
		$transaction = Transaction::findOrFail($transaction_id);
		$vendor      = $transaction->vendor;
		
		$items       = unserialize($transaction->cached_data); 
		$tenders     = Tender::whereIn('id', $items)->get();

		$fpx = null;
		$ebpg = null;
		
		if($transaction->status != 'failed') {
			session()->forget('cart_items');
			session()->forget('cart_ou');
			session()->forget('cart_txn_items');
			session()->forget('txn_id');
			session()->forget('txn_type');
		} else {
			if(session('cart_ou')) {
				$fpx    = Gateway::whereType('fpx')->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
				$ebpg   = Gateway::whereType('ebpg')->where('organization_unit_id', session('cart_ou'))->whereActive(1)->first();
			} else {
				$fpx    = Gateway::whereType('fpx')->whereDefault(1)->whereActive(1)->first();
				$ebpg   = Gateway::whereType('ebpg')->whereDefault(1)->whereActive(1)->first();
			}
		}
	
		if($transaction->user_id != auth()->user()->id)
			return $this->_access_denied();
	
		$amount = $transaction->amount;
		return view('cart.callback', compact('transaction', 'tenders', 'amount', 'fpx', 'ebpg'));
	}
	
	public function receipt($id) {
	
		$transaction = Transaction::findOrFail($id);
	
		if(auth()->user()->hasRole('Vendor') && $transaction->user_id != auth()->user()->id)
			return $this->_access_denied();
	
		return view('tenders.receipt', compact('transaction'));
	}
	
}

