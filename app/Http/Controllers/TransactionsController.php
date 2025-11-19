<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateFpxStatus;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Tender;
use App\Models\Gateway;
use App\Models\Fpx;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
	use Helper;

	/**
	 * Display a listing of transactions
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if (!Transaction::canList()) {
			return $this->_access_denied();
		}

		if ($request->ajax()) {

			$state = $request->state ?? "";
			$status = $request->status ?? "";

			if ($state) {
				switch ($state) {
					case 'subscription':
						$transactions = Transaction::where('type', 'subscription')->orderBy('transactions.created_at', 'desc');
						break;
					case 'purchase':
						$transactions = Transaction::where('type', 'purchase')->orderBy('transactions.created_at', 'asc');
						break;
				}
			}
			else {
				$transactions = Transaction::whereNotNull('transactions.id')->orderBy('transactions.created_at', 'desc');
			}

			$transactions->join('vendors', 'vendors.id', '=', 'transactions.vendor_id')->orderBy('transactions.created_at', 'desc');

			if (!auth()->user()->can('Transaction:all')) {
				$transactions->where('transactions.organization_unit_id', auth()->user()->organization_unit_id);
			}		

			$transactions->select([
				'transactions.id',
				'transactions.created_at',
				'vendors.name',
				'transactions.number',
				'transactions.gateway_reference',
				'transactions.type',
				'transactions.method',
				'transactions.amount',
				'transactions.status',
				'transactions.vendor_id'
			]);

			$start 		= $request->start ?? 0;  						// Get starting point for data to be retrieved
			$length 	= $request->length ?? 10;						// Get how much data to be retrieved
			$draw 		= $request->draw ?? 1;							// Get original draw request
			$keyword 	= $request->get('search')["value"] ?? "";
			$keyword 	= Str::lower($keyword);
			$status 	= $request->status ?? "";

			if($status != "")
			{
				$transactions->where('transactions.status', $status );
			}

			$recordsTotal = (clone $transactions)->count() ?? 0;


			$transactions->where( function($q) use($keyword){
				$q->whereRaw(" (case when transactions.status = 'pending' then 'belum diterima' when transactions.status = 'success' then 'berjaya' when transactions.status = 'declined' then 'ditolak' when transactions.status = 'failed' then 'gagal' when transactions.status = 'pending_authorization' then 'dalam proses pengesahan' end ) like ?", '%'.$keyword.'%')
				->orWhereRaw(" (case when transactions.type = 'subscription' then 'langganan' when transactions.type = 'purchase' then 'pembelian dokumen' end ) like ?", '%'.$keyword.'%')
				->orWhereRaw("transactions.vendor_id in (select id from vendors where name like ?)", '%'.$keyword.'%')
				->orWhereRaw("transactions.number like ?", '%'.$keyword.'%')
				->orWhereRaw("transactions.gateway_reference like ?", '%'.$keyword.'%');
			});

			// dd($transactions->toSql());
			
			$recordsFiltered = (clone $transactions)->count() ?? 0;
			// dd( $request->all() );

			$results = $transactions->offset($start)->limit($length)->get();

			$datatable_data = [];
			foreach ($results as $rows) {

				$actions  = [];
				$actions[] = $rows->canShow() ? link_to_route('transactions.show', 'Papar', $rows->id, ['class' => 'btn btn-xs bg-primary']) : '';
				$actions[] = $rows->canUpdate() ? link_to_route('transactions.edit', 'Kemaskini', $rows->id, ['class' => 'btn btn-xs btn-warning']) : '';
				$action_column = '<div class ="btn-group">' . implode(' ', $actions) . '</div>';

				$year = date('d-m-Y', strtotime($rows->created_at));
				$new_receipt = '';
				if ($rows->number != "")
				{
					$receipt = $this->receiptNumGenerator($rows->number,$year);
					$new_receipt = ($receipt!='old') ? $receipt : $rows->vendor_id . '-' . $rows->gateway_reference;
				}
				
				if($rows->status != 'success')
				{
					$new_receipt = '';
				}

				$datatable_data[] = array(
					"created_at" => Carbon::parse($rows->created_at)->format('d/m/Y H:i:s'),
					"name" => $rows->name,
					"number" => $rows->number,
					"gateway_reference" => $rows->gateway_reference,
					"no_resit" => $new_receipt,
					"type" => $rows->type,
					"method" => $rows->method,
					"amount" => $rows->amount,
					"status" => $rows->status,
					"actions" => $action_column,
				);
			}

			$datatable_response = array(
				"draw" => $draw,
				"recordsTotal" => $recordsTotal,
  				"recordsFiltered" => $recordsFiltered,
				"data" => $datatable_data
			);

			return response()->json($datatable_response);
		}

		return view('transactions.index');
	}

	public function updateFpxCount(Request $request)
	{
		$m_transactions = Transaction::whereNotNull('transactions.id')->orderBy('transactions.created_at', 'desc');
		$m_transactions->join('vendors', 'vendors.id', '=', 'transactions.vendor_id')->orderBy('transactions.created_at', 'desc');

		if (!auth()->user()->can('Transaction:all')) {
			$m_transactions->where('transactions.organization_unit_id', auth()->user()->organization_unit_id);
		}

		if($request->type != "")
		{
			$type = $request->type;
			
			switch ($type) {
				case 'subscribe':
					$data["subscribe_trans_count"] = (clone $m_transactions)->where('type', 'subscription')->count();
					break;
				case 'purchase':
					$data["purchase_trans_count"] = (clone $m_transactions)->where('type', 'purchase')->count();
					break;
				case 'total':
					$data["total_trans_count"] = (clone $m_transactions)->count();
					break;
				
				default:
					# code...
					break;
			}
		}

		if($request->status != "")
		{
			$status = $request->status;
			
			switch ($status) {
				case 'success':
					$data["success_trans_count"] = (clone $m_transactions)->where('status', 'success')->count();
					break;
				case 'pending':
					$data["pending_trans_count"] = (clone $m_transactions)->where('status', 'pending')->count();
					break;
				case 'pending_authorization':
					$data["pending_authorization_trans_count"] = (clone $m_transactions)->where('status', 'pending_authorization')->count();
					break;
				case 'failed':
					$data["failed_trans_count"] = (clone $m_transactions)->where('status', 'failed')->count();
					break;
				case 'declined':
					$data["declined_trans_count"] = (clone $m_transactions)->where('status', 'declined')->count();
					break;
				
				default:
					# code...
					break;
			}
		}

		if($request->type == "custom_all")
		{
			$data["subscribe_trans_count"] 	= (clone $m_transactions)->where('type', 'subscription')->count();
			$data["purchase_trans_count"] 	= (clone $m_transactions)->where('type', 'purchase')->count();
			$data["total_trans_count"] 		= (clone $m_transactions)->count();
			$data["success_trans_count"] 	= (clone $m_transactions)->where('status', 'success')->count();
			$data["pending_trans_count"] 	= (clone $m_transactions)->where('status', 'pending')->count();
			$data["failed_trans_count"] 	= (clone $m_transactions)->where('status', 'failed')->count();
			$data["declined_trans_count"] 	= (clone $m_transactions)->where('status', 'declined')->count();
			$data["pending_authorization_trans_count"] = (clone $m_transactions)->where('status', 'pending_authorization')->count();
			
			return response()->json($data);
		}

		return response()->json($data);
	}

	public function subscriptionIndex()
	{
		$data["subtitle"] = "Langganan";
		$data["transaction_type"] = "subscription";

		return view('transactions.index', $data);
	}

	public function purchaseIndex()
	{
		$data["subtitle"] = "Pembelian Dokumen";
		$data["transaction_type"] = "purchase";

		return view('transactions.index', $data);
	}

	public function successTransIndex()
	{
		$data["subtitle2"] = "Berjaya";
		$data["transaction_status"] = "success";

		return view('transactions.index', $data);
	}

	public function pendingTransIndex()
	{
		$data["subtitle2"] = "Belum Diterima";
		$data["transaction_status"] = "pending";

		return view('transactions.index', $data);
	}

	public function declinedTransIndex()
	{
		$data["subtitle2"] = "Ditolak";
		$data["transaction_status"] = "declined";

		return view('transactions.index', $data);
	}

	public function failedTransIndex()
	{
		$data["subtitle2"] = "Gagal";
		$data["transaction_status"] = "failed";

		return view('transactions.index', $data);
	}

	public function pendingAuthTransIndex()
	{
		$data["subtitle2"] = "Dalam Proses Pengesahan";
		$data["transaction_status"] = "pending_authorization";

		return view('transactions.index', $data);
	}

	public function show(Request $request, $id)
	{
		$transaction = Transaction::findOrFail($id);
		if (!$transaction->canShow()) {
			return $this->_access_denied();
		}
		if ($request->ajax()) {
			return response()->json($transaction);
		}

		$fpx = null;
		$fpx_data = null;

		if ($transaction->gateway && $transaction->gateway->type == 'fpx') {
			if ($transaction->type == 'subscription') {
				$description = 'Langganan Tender Selangor';
			}

			if ($transaction->type == 'purchase') {
				$description = 'Beli Dokumen Tender Selangor';
			}

			$fpx = new Fpx([
				'amount'       => $transaction->amount,
				'merchant_id'  => $transaction->gateway->merchant_code,
				'prefix'       => $transaction->gateway->transaction_prefix,
				'order_number' => $transaction->number,
				'description'  => $description,
				'user_email'   => $transaction->user->email,
				'request_type' => 'AE',
			]);
			// $fpx->sign();

			$fpx_data = $request->all();
		}

		$year = date('d-m-Y', strtotime($transaction->created_at));
		$receipt = $this->receiptNumGenerator($transaction->number, $year);

		return view('transactions.show', compact('transaction', 'fpx', 'fpx_data', 'receipt'));
	}

	public function receipt($id)
	{
		$transaction = Transaction::findOrFail($id);
		if (!$transaction->canShow())
			return $this->_access_denied();

		if ($transaction->type == 'subscription') {
			$vendor       = $transaction->vendor;
			$subscription = $transaction->subscription;
			return view('subscriptions.receipt', compact('vendor', 'subscription'));
		}

		if ($transaction->type == 'purchase') {
			return view('tenders.receipt', compact('transaction'));
		}
	}

	public function temp_receipt(Request $request, $id)
	{
		$transaction = Transaction::findOrFail($id);
		$tender = null;

		if (!$transaction->canShow() && $transaction->status != 'success')
			return $this->_access_denied();

		$vendor	= $transaction->vendor;

		if ($transaction->type == 'subscription') {
			return view('transactions.temp_receipt_subscription', compact('vendor', 'transaction'));
		}

		if ($transaction->type == 'purchase' && $request->tender_id) {
			$tender = Tender::findOrFail($request->tender_id);
			return view('transactions.temp_receipt_purchase', compact('vendor', 'transaction', 'tender'));
		}

		return $this->_access_denied();
	}

	public function edit(Request $request, $id)
	{
		$transaction = Transaction::findOrFail($id);
		if ($request->ajax()) {
			return $this->_ajax_denied();
		}
		if (!$transaction->canUpdate()) {
			return $this->_access_denied();
		}
		return view('transactions.edit', compact('transaction'));
	}

	/**
	 * Update the specified transaction in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$transaction = Transaction::findOrFail($id);
		$status = $transaction->status;
		$data = $request->all();

		if (!$transaction->canUpdate())
			return $this->_access_denied();

		if (!$transaction->update($data))
			return $this->_validation_error($transaction);

		if ($request->ajax())
			return $transaction;

		return redirect('transactions/' . $id)->with('success', $this->updated_message);
	}

	public function ebpg_requery(Request $request, $id)
	{
		$transaction = Transaction::findOrFail($id);
		$status = $transaction->status;

		if (!$transaction->canUpdate() || $transaction->status == 'success')
			return $this->_access_denied();

		switch ($request->TXN_STATUS) {
			case 'A':
			case 'C':
			case 'S':
				$transaction->status = 'success';
				break;
			case 'N':
				$transaction->status = 'pending_authorization';
				break;
			default:
				$transaction->status = 'failed';
				break;
		}

		$message = [];
		foreach ($request->all() as $key => $value)
			$message[] = "{$key}: {$value}";

		$transaction->response_code     = $request->RESPONSE_CODE;
		$transaction->response_message  = $request->RESPONSE_DESC;
		$transaction->gateway_reference = $request->TRANSACTION_ID;
		$transaction->gateway_auth      = $request->AUTH_ID;
		$transaction->gateway_response  = $request->TXN_STATUS;
		$transaction->gateway_message   = implode(' | ', $message);

		if (!$transaction->valid_ebpg_signature_2) {
			$transaction->status            = 'failed';
			$transaction->response_message  = 'Invalid transaction signature';
		}

		$transaction->save();

		return redirect('transactions/' . $id)->with('success', $this->updated_message);
	}

	public function fpx_query($id)
	{

		$transaction = Transaction::findOrFail($id);
		if (!$transaction->canShow()) {
			return $this->_access_denied();
		}

		if ($transaction->type == 'subscription') {
			$description = 'Langganan Tender Selangor';
		}

		if ($transaction->type == 'purchase') {
			$description = 'Beli Dokumen Tender Selangor';
		}

		$fpx = new Fpx([
			'amount'       => $transaction->amount,
			'merchant_id'  => $transaction->gateway->merchant_code,
			'prefix'       => $transaction->gateway->transaction_prefix,
			'order_number' => $transaction->number,
			'description'  => $description,
			'user_email'   => $transaction->user->email,
			'request_type' => 'AE'
		]);

		$data = $transaction->gateway_data;

		if (count($data) > 0) {
			$data['fpx_msgType'] = 'AE';
			$fpx->prefill($data);
		}

		$fpx->sign();

		$url    = $transaction->gateway->daemon_url;
		$params = $fpx->request_keys;


		try {

			$client   = new Client(['verify' => false]);
			$response = $client->post($url, ['form_params' => $params, 'debug' => false ]);
		} catch (\Exception $e) {
			return view('transactions.fpx_query', ['error' => 'Gagal untuk berhubung dengan sistem FPX.']);
		}

		$response = (string) $response->getBody();
		$response = explode('&', $response);
		$data     = [];

		if (count($response) < 2) {
			return view('transactions.fpx_query', ['error' => 'Gagal untuk berhubung dengan sistem FPX.']);
		}

		foreach ($response as $resp) {
			$resp           = explode('=', $resp);
			$data[$resp[0]] = $resp[1];
		}

		ksort($data);
		ksort($params);
		$ac_responses 	= $data;
		$ae_requests	= $params;

		return view('transactions.fpx_query', compact('transaction', 'ac_responses', 'ae_requests'));
	}

	public function fpx_requery($id)
	{

		$transaction = Transaction::findOrFail($id);
		if (!$transaction->canShow()) {
			return $this->_access_denied();
		}

		if ($transaction->type == 'subscription') {
			$description = 'Langganan Tender Selangor';
		}

		if ($transaction->type == 'purchase') {
			$description = 'Beli Dokumen Tender Selangor';
		}

		$fpx = new Fpx([
			'amount'       => $transaction->amount,
			'merchant_id'  => $transaction->gateway->merchant_code,
			'prefix'       => $transaction->gateway->transaction_prefix,
			'order_number' => $transaction->number,
			'description'  => $description,
			'user_email'   => $transaction->user->email,
			'request_type' => 'AE'
		]);

		$data = $transaction->gateway_data;

		if (count($data) > 0) {
			$data['fpx_msgType'] = 'AE';
			$fpx->prefill($data);
		}

		$fpx->sign();

		$url    = $transaction->gateway->daemon_url;
		$params = $fpx->request_keys;

		try {
			$client = new Client(['verify' => false]);			
			$response = $client->post($url, ['form_params' => $params, 'debug' => false ]);
		} catch (\Exception $e) {
			return redirect('transactions/' . $id)->with('error', 'Gagal untuk berhubung dengan sistem FPX.');
		}

		$response = $response->getBody()->getContents();
		$response = explode('&', $response);
		$data     = [];

		if (count($response) < 2) {
			return redirect('transactions/' . $id)->with('error', 'Gagal untuk berhubung dengan sistem FPX.');
		}

		foreach ($response as $resp) {
			$resp           = explode('=', $resp);
			$data[$resp[0]] = $resp[1];
		}

		ksort($data);

		switch (true) {
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
		foreach ($data as $key => $value)
			$message[] = "{$key}: {$value}";

		$transaction->response_code     = implode('|', [$data['fpx_debitAuthCode'], $data['fpx_creditAuthCode']]);
		$transaction->gateway_reference = $data['fpx_fpxTxnId'];
		$transaction->gateway_auth      = implode('|', [$data['fpx_debitAuthNo'], $data['fpx_creditAuthNo']]);
		$transaction->gateway_response  = implode(' | ', $message);
		$transaction->save();

		return redirect('transactions/' . $id)->with('success', 'Status FPX dikemaskini.');
	}

	public function queue_fpx_requery()
	{
		$queueLimit = env('FPX_JOBS_LIMIT') ?? 50;

		$get_processed_pending_fpx = Transaction::where('status', 'pending')->where('fpx_job_status', 1)->select('id');
		$total_processed_pending_fpx = $get_processed_pending_fpx->count();

		$get_pending_fpx = Transaction::where('method', 'fpx')->where('status', 'pending')->whereNotNull('gateway_id');

		$pending_transaction_list = [];
		if ($total_processed_pending_fpx > 0)
		{
			$pending_transaction_list = $get_processed_pending_fpx->orderBy('created_at', 'asc')->get();
		}
		else
		{
			$pending_transaction_list = $get_pending_fpx->orderBy('created_at', 'asc')->take($queueLimit)->get();
		}

		echo "Total FPX transaction send to be process. Total => ".count($pending_transaction_list);

		if( count($pending_transaction_list) > 0 )
		{
			foreach ($pending_transaction_list as $transaction) {

				$transaction_id = $transaction->id;

				dispatch(function () use($transaction_id) {
					$update_job = new UpdateFpxStatus($transaction_id);
					$update_job->handle();
				})->delay(now()->addSeconds(5));
			}
		}
	}

	public function api_fpx_requery(Request $request)
	{
		$validator = Validator::make($request->all(), [
            "transaction_id" => "required | integer"
        ]);

		if ($validator->fails())
		{
            return response()->json([
				'error' => $validator->errors()->all()
			]);
        }

		$transaction_id = $request->transaction_id ?? "";
		$transaction = Transaction::findOrFail($transaction_id);

		if ($transaction->type == 'subscription') {
			$description = 'Langganan Tender Selangor';
		}

		if ($transaction->type == 'purchase') {
			$description = 'Beli Dokumen Tender Selangor';
		}

		$fpx = new Fpx([
			'amount'       => $transaction->amount,
			'merchant_id'  => $transaction->gateway->merchant_code,
			'prefix'       => $transaction->gateway->transaction_prefix,
			'order_number' => $transaction->number,
			'description'  => $description,
			'user_email'   => $transaction->user->email,
			'request_type' => 'AE'
		]);

		$data = $transaction->gateway_data;

		if (count($data) > 0) {
			$data['fpx_msgType'] = 'AE';
			$fpx->prefill($data);
		}

		$fpx->sign();

		$url    = $transaction->gateway->daemon_url;
		$params = $fpx->request_keys;

		try {
			$client = new Client(['verify' => false]);
			$response = $client->post($url, ['form_params' => $params, 'debug' => false ]);
		} catch (\Exception $e) {
			return response()->json( array('status' => 'Gagal untuk berhubung dengan sistem FPX.', 'e' => $e->getMessage()) );
		}

		$response_output = $response->getBody()->getContents();
		$response_output2 = explode('&', $response_output);
		$data     = [];

		if (count($response_output2) < 2) {

			$response = array(
				'status' => 'Gagal untuk berhubung dengan sistem FPX.',
				'response' => $response->getBody()->getContents()
			);

			return response()->json($response);
		}

		foreach ($response_output2 as $resp) {
			$resp           = explode('=', $resp);
			$data[$resp[0]] = $resp[1];
		}

		ksort($data);

		switch (true) {
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
		foreach ($data as $key => $value)
		{
			$message[] = "{$key}: {$value}";
		}

		$transaction->response_code     = implode('|', [$data['fpx_debitAuthCode'], $data['fpx_creditAuthCode']]);
		$transaction->gateway_reference = $data['fpx_fpxTxnId'];
		$transaction->gateway_auth      = implode('|', [$data['fpx_debitAuthNo'], $data['fpx_creditAuthNo']]);
		$transaction->gateway_response  = implode(' | ', $message);
		$transaction->fpx_job_status  	= 1;
		$transaction->save();

		return response()->json( array('status' => 'Status FPX dikemaskini.') );
	}
}
