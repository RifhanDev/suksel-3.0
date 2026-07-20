<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\FpxBank;
use App\Models\Fpx;
use App\Gateway;
use Log;

class FpxController extends Controller
{
	public function bankList() {
		$transaction = Transaction::with('gateway')->find(session('txn_id'));
		$type = session('fpx_type', null);
		
		$fpx = new Fpx([
			'request_type' => 'BE',
			'msg_token'    => $type == 'fpx-2' ? '02' : '01',
			'merchant_id'  => $transaction->gateway->merchant_code,
			'version'      => $transaction->gateway->version,
		]);
		
		$banks = $fpx->bankList();
		ksort ($banks);

		// if($_SERVER['REMOTE_ADDR'] == '202.185.133.20') {
		// 	var_dump($banks);
		// 	echo '<pre>'; print_r($banks); echo '</pre>';
		// }
		
		foreach($banks as $id => $status) {
			$statusCode = ' (Offline)';
			if($status == 'A') {
				$statusCode = '';
			}

			$modelFB = FpxBank::whereCode($id)->first();

			if(!empty($modelFB)) {
				$banks[$id] = $modelFB->display_name . ' ' . $statusCode;
			}
		}
		
		return view('payment.fpx.bank-list', compact('banks', 'fpx'));
	}
	
	public function connect(Request $request) {
		$transaction = Transaction::with('gateway')->find(session('txn_id'));
		
		if( empty($transaction) || empty($transaction->gateway) || $transaction->gateway->type != 'fpx' || $transaction->method != $transaction->gateway->type )
		return $this->_access_denied();
		
		if($transaction->type == 'subscription') {
			$description = 'Langganan Tender Selangor';
		}
		
		if($transaction->type == 'purchase') {
			$description = 'Beli Dokumen Tender Selangor';
		}
	
		$type = session('fpx_type', null);
		
		$fpx = new Fpx([
			'amount'       => $transaction->amount,
			'merchant_id'  => $transaction->gateway->merchant_code,
			'prefix'       => $transaction->gateway->transaction_prefix,
			'order_number' => $transaction->number,
			'description'  => $description,
			'user_email'   => $transaction->user->email,
			'msg_token'    => $type == 'fpx-2' ? '02' : '01',
			'bank_code'    => $request->input('bank_code', ''),
			'version'      => $transaction->gateway->version
		]);
		$fpx->sign();
	
		$messages = [];
		
		foreach($fpx->request_keys as $key => $value) {
			$messages[] = implode(': ', [$key, $value]);
		}
	
		$transaction->gateway_message = implode(' | ', $messages);
		$transaction->save();
	
		return view('payment.fpx.connect', compact('fpx', 'transaction'));
	}
	
	public function respond(Request $request) {

		$returning           = false;
		$fpx_sellerExOrderNo = $request->fpx_sellerExOrderNo;
		$merchant_code       = implode('|', [$request->fpx_sellerExId, $request->fpx_sellerId]);
		$gateway             = Gateway::whereType('fpx')->whereMerchantCode($merchant_code)->whereActive(1)->first();
	
		if(empty($fpx_sellerExOrderNo) || empty($merchant_code) || empty($gateway)) return $this->_access_denied();
	
		if(session('txn_id')) {
			$transaction = Transaction::with('gateway')->find(session('txn_id'));
		}
	
		$transaction_number = str_replace($gateway->transaction_prefix, '', $fpx_sellerExOrderNo);
	
		if(isset($transaction) && $transaction->number == $transaction_number) {
			$returning = true;
		} else {
			$transaction = Transaction::with('gateway')->whereNumber($transaction_number)->first();
		}
	
		$data = $request->except('_method', '_token');
	
		switch(true) {
			case $data['fpx_debitAuthCode'] == '00' && $data['fpx_creditAuthCode'] == '00':
				$transaction->status            = 'success';
				$transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'SUCCESSFUL');
				break;
			case $data['fpx_debitAuthCode'] == '99':
				$transaction->status            = 'pending_authorization';
				$transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'PENDING FOR AUTHORIZER TO APPROVE');
				break;
			default:
				$transaction->status            = 'failed';
				$transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'UNSUCCESSFUL');
				break;
		}
	
		$message = [];
		foreach($data as $key => $value)
			$message[] = "{$key}: {$value}";
	
		$transaction->response_code     = implode('|', [$data['fpx_debitAuthCode'], $data['fpx_creditAuthCode']]);
		$transaction->gateway_reference = $data['fpx_fpxTxnId'];
		$transaction->gateway_auth      = implode('|', [$data['fpx_debitAuthNo'], $data['fpx_creditAuthNo']]);
		$transaction->gateway_response  = implode(' | ', $message);
		$transaction->save();
	
		if($returning) {
			if($transaction->type == 'purchase') {
				$redirect = redirect( action('CartController@callback', ['transaction_id' => $transaction->id]) );
			}
	
			if($transaction->type == 'subscription' && session('txn_type') == 'registration') {
				$redirect = redirect( action('RegistrationController@callbackPayment', ['transaction_id' => $transaction->id]) );
			}
	
			if($transaction->type == 'subscription' && session('txn_type') == 'renewal') {
				$redirect = redirect( action('HomeController@callbackRenewal', ['transaction_id' => $transaction->id]) );
			}
			} else {
				if(auth()->check() && auth()->user()->hasRole('Admin')) {
					$redirect = redirect( action('TransactionsController@show', $transaction->id) );
			} else {
			$redirect = redirect('/');
			}
	
		$redirect = $redirect->with('notice', 'Transaksi telah dikemaskini.');
		}
		
		return $redirect;
	}
	
	public function listen(Request $request) {

		// Log::channel('fpx-listen')->info($request->all());

		$fpx_sellerExOrderNo = $request->fpx_sellerExOrderNo;
		$merchant_code       = implode('|', [$request->fpx_sellerExId, $request->fpx_sellerId]);
		$gateway             = Gateway::whereType('fpx')->whereMerchantCode($merchant_code)->whereActive(1)->first();
	
		if(empty($fpx_sellerExOrderNo) || empty($merchant_code) || empty($gateway)) return $this->_access_denied();
	
		$transaction_number     = str_replace($gateway->transaction_prefix, '', $fpx_sellerExOrderNo);
		$transaction            = Transaction::with('gateway')->whereNumber($transaction_number)->first();
	
		if(empty($transaction_number) || empty($transaction)) return $this->_access_denied();
	
		$data = $request->except('_method', '_token');
	
		if($transaction->status == 'success') {
				echo 'OK';
			return;
		}
	
		switch(true) {
			case $data['fpx_debitAuthCode'] == '00' && $data['fpx_creditAuthCode'] == '00':
			$transaction->status            = 'success';
			$transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'SUCCESSFUL');
			break;
			case $data['fpx_debitAuthCode'] == '99':
			$transaction->status            = 'pending_authorization';
			$transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'PENDING FOR AUTHORIZER TO APPROVE');
			break;
			default:
			$transaction->status            = 'failed';
			$transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'UNSUCCESSFUL');
			break;
		}
	
		$message = [];
		foreach($data as $key => $value)
			$message[] = "{$key}: {$value}";
	
		$transaction->response_code     = implode('|', [$data['fpx_debitAuthCode'], $data['fpx_creditAuthCode']]);
		$transaction->gateway_reference = $data['fpx_fpxTxnId'];
		$transaction->gateway_auth      = implode('|', [$data['fpx_debitAuthNo'], $data['fpx_creditAuthNo']]);
		$transaction->gateway_response  = implode(' | ', $message);
		$transaction->save();
	
		echo 'OK';
	}
	
	public function __construct() {
		// parent::__construct();
	}
	
	private function verifyFpxSignature($data, $exchange_id) {
		$checksum           = $data['fpx_checkSum'];
		unset($data['fpx_checkSum']);
		ksort($data);
		$string             = implode('|', array_values($data));
		$certificate_path   = base_path() . "/fpx/FPX.cer";
		$file               = fopen($certifcate, 'r');
		$certificate        = fread($certifcate_path, 8192);
		fclose($file);
		$certificate_data   = openssl_pkey_get_public($certificate);
		$signature_data     = hex2bin($signature);
		return openssl_verify($checksum, $string, $certificate_data);
	}
}
