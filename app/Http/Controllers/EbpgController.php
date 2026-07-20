<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Ebpg;
use Log;

class EbpgController extends Controller
{
	public function connect() {
		$transaction = Transaction::with('gateway')->find(session('txn_id'));
		
		if( empty($transaction) || empty($transaction->gateway) || $transaction->gateway->type != 'ebpg' || $transaction->method != $transaction->gateway->type )
		return $this->_access_denied();
		
		if($transaction->type == 'subscription') {
			$description = 'Pendaftaran Sistem Tender Online Selangor';
		}
		
		if($transaction->type == 'purchase') {
			$description = 'Pembelian Dokumen Sistem Tender Online Selangor';
		}
		
		$ebpg = new Ebpg([
			'amount'       => $transaction->amount,
			'merchant_id'  => $transaction->gateway->merchant_code,
			'prefix'       => $transaction->gateway->transaction_prefix,
			'order_number' => $transaction->number,
			'hash_key'     => $transaction->gateway->private_key,
			'return_url'   => url()->route('ebpg.respond'),
			'description'  => $description
		]);
		$ebpg->sign();
		
		return view('payment.ebpg.connect', compact('ebpg', 'transaction'));
	}
	
	public function respond(Request $request) {

        // Log::channel('ebpg-respond')->info($request->all());

		$transaction = Transaction::with('gateway')->find(session('txn_id'));

        if( empty($transaction) || empty($transaction->gateway) || $transaction->gateway->type != 'ebpg' || $transaction->method != $transaction->gateway->type )
            return $this->_access_denied();

        if( !$request->get('TXN_STATUS') )
            return $this->_access_denied();

        switch ($request->TXN_STATUS) {
            case 'A':
            case 'C':
            case 'S':
                $transaction->status        = 'success';
                break;
            case 'N':
                $transaction->status        = 'pending_authorization';
                break;
            default:
                $transaction->status        = 'failed';
                break;
        }

        $message = [];
        foreach($request->all() as $key => $value)
            $message[] = "{$key}: {$value}";

        $transaction->response_code     = $request->RESPONSE_CODE;
        $transaction->response_message  = $request->RESPONSE_DESC;
        $transaction->gateway_reference = $request->TRANSACTION_ID;
        $transaction->gateway_auth      = $request->AUTH_ID;
        $transaction->gateway_response  = $request->TXN_STATUS;
        $transaction->gateway_message   = implode(' | ', $message);

        if(!$transaction->valid_ebpg_signature_2)
        {
            $transaction->status            = 'failed';
            $transaction->response_message  = 'Invalid transaction signature';
        }

        $transaction->save();

        if($transaction->type == 'purchase') {
            $redirect = redirect('cart/callback/'.$transaction->id);
        }

        if($transaction->type == 'subscription' && session('txn_type') == 'registration') {
            $redirect = redirect('register/payment_callback/'.$transaction->id);
        }

        if($transaction->type == 'subscription' && session('txn_type') == 'renewal') {
            $redirect = redirect('renewal_callback/'.$transaction->id);
        }

        return $redirect;
	}
	
}
