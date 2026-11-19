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
use Illuminate\Support\Facades\Cache;
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
			try {
				return $this->indexDatatable($request);
			} catch (\Throwable $e) {
				Log::error('Transactions DataTable failed', [
					'error' => $e->getMessage(),
					'file' => $e->getFile(),
					'line' => $e->getLine(),
				]);

				return response()->json([
					'draw' => (int) $request->get('draw', 0),
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => [],
					'error' => 'Gagal memuatkan senarai transaksi. Sila cuba lagi.',
				]);
			}
		}

		return view('transactions.index');
	}

	/**
	 * Server-side DataTables for /transactions.
	 * Avoid COUNT(*)+JOIN on the full 1.4M-row table on every page load.
	 */
	protected function indexDatatable(Request $request)
	{
		$state = (string) ($request->state ?? '');
		$status = (string) ($request->status ?? '');
		$start = max(0, (int) ($request->start ?? 0));
		$length = max(1, min(100, (int) ($request->length ?? 25)));
		$draw = (int) ($request->draw ?? 1);
		$keyword = Str::lower(trim((string) ($request->input('search.value') ?? '')));

		$base = Transaction::query();

		if ($state === 'subscription') {
			$base->where('transactions.type', 'subscription');
		} elseif ($state === 'purchase') {
			$base->where('transactions.type', 'purchase');
		}

		if ($status !== '') {
			$base->where('transactions.status', $status);
		}

		if (! auth()->user()->can('Transaction:all')) {
			$base->where('transactions.organization_unit_id', auth()->user()->organization_unit_id);
		}

		$counts = $this->transactionSummaryCounts();

		// Prefer cached totals — a live COUNT(*) on this table routinely stalls the page.
		if ($keyword === '') {
			if ($status !== '') {
				$map = [
					'success' => 'success_trans_count',
					'pending' => 'pending_trans_count',
					'failed' => 'failed_trans_count',
					'declined' => 'declined_trans_count',
					'pending_authorization' => 'pending_authorization_trans_count',
				];
				$recordsTotal = (int) ($counts[$map[$status] ?? ''] ?? 0);
			} elseif ($state === 'subscription') {
				$recordsTotal = (int) ($counts['subscribe_trans_count'] ?? 0);
			} elseif ($state === 'purchase') {
				$recordsTotal = (int) ($counts['purchase_trans_count'] ?? 0);
			} else {
				$recordsTotal = (int) ($counts['total_trans_count'] ?? 0);
			}
			$recordsFiltered = $recordsTotal;
		} else {
			$searchQuery = (clone $base)->leftJoin('vendors', 'vendors.id', '=', 'transactions.vendor_id')
				->where(function ($q) use ($keyword) {
					$q->where('transactions.number', 'like', '%' . $keyword . '%')
						->orWhere('transactions.gateway_reference', 'like', '%' . $keyword . '%')
						->orWhere('vendors.name', 'like', '%' . $keyword . '%')
						->orWhere('vendors.registration', 'like', '%' . $keyword . '%')
						->orWhereRaw(
							"(case when transactions.status = 'pending' then 'belum diterima' when transactions.status = 'success' then 'berjaya' when transactions.status = 'declined' then 'ditolak' when transactions.status = 'failed' then 'gagal' when transactions.status = 'pending_authorization' then 'dalam proses pengesahan' end) like ?",
							['%' . $keyword . '%']
						)
						->orWhereRaw(
							"(case when transactions.type = 'subscription' then 'langganan' when transactions.type = 'purchase' then 'pembelian dokumen' end) like ?",
							['%' . $keyword . '%']
						);
				});

			$recordsFiltered = (int) (clone $searchQuery)->count(DB::raw('DISTINCT transactions.id'));
			$recordsTotal = (int) ($counts['total_trans_count'] ?? $recordsFiltered);
			$base = $searchQuery;
		}

		$sortColumns = [
			'created_at' => 'transactions.created_at',
			'vendors.name' => 'vendors.name',
			'number' => 'transactions.number',
			'type' => 'transactions.type',
			'amount' => 'transactions.amount',
			'status' => 'transactions.status',
		];
		$sortName = (string) $request->input('columns.' . $request->input('order.0.column') . '.name');
		$sortCol = $sortColumns[$sortName] ?? 'transactions.created_at';
		$sortDir = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';

		// Id-first page: ORDER BY + LIMIT on transactions only, then hydrate vendor names.
		$idQuery = (clone $base)->select('transactions.id')->reorder($sortCol, $sortDir);
		if ($keyword !== '' && $sortCol === 'vendors.name') {
			// join already present for search
		} elseif ($sortCol === 'vendors.name') {
			$idQuery->leftJoin('vendors', 'vendors.id', '=', 'transactions.vendor_id');
		}

		$ids = $idQuery->offset($start)->limit($length)->pluck('transactions.id')->all();

		$results = collect();
		if ($ids !== []) {
			$results = Transaction::query()
				->leftJoin('vendors', 'vendors.id', '=', 'transactions.vendor_id')
				->whereIn('transactions.id', $ids)
				->select([
					'transactions.id',
					'transactions.created_at',
					'vendors.name',
					'transactions.number',
					'transactions.gateway_reference',
					'transactions.type',
					'transactions.method',
					'transactions.amount',
					'transactions.status',
					'transactions.vendor_id',
				])
				->orderByRaw('FIELD(transactions.id,' . implode(',', array_map('intval', $ids)) . ')')
				->get();
		}

		$datatable_data = [];
		$statusBadges = ['success' => 'success', 'pending' => 'warning', 'pending_authorization' => 'info', 'failed' => 'danger', 'declined' => 'danger'];
		$canShow = auth()->user()->ability(['Admin', 'Agency Admin'], ['Transaction:show']);
		$canEditPerm = auth()->user()->can('Transaction:edit');

		foreach ($results as $rows) {
			$actions = [];
			if ($canShow) {
				$actions[] = link_to_route('transactions.show', 'Papar', $rows->id, ['class' => 'btn btn-sm btn-primary rounded-8 px-3 text-nowrap']);
			}
			if ($rows->status !== 'success' && $canEditPerm) {
				$actions[] = link_to_route('transactions.edit', 'Kemaskini', $rows->id, ['class' => 'btn btn-sm btn-warning rounded-8 px-3 text-nowrap']);
			}
			$action_column = '<div class="d-flex gap-2 justify-content-center flex-nowrap">' . implode('', $actions) . '</div>';

			$new_receipt = '';
			if ($rows->status === 'success' && $rows->number != '') {
				$year = date('d-m-Y', strtotime($rows->created_at));
				$receipt = $this->receiptNumGenerator($rows->number, $year);
				$new_receipt = ($receipt != 'old') ? $receipt : $rows->vendor_id . '-' . $rows->gateway_reference;
			}

			$datatable_data[] = [
				'created_at' => Carbon::parse($rows->created_at)->format('d/m/Y H:i:s'),
				'name' => $rows->name,
				'number' => $rows->number,
				'gateway_reference' => $rows->gateway_reference,
				'no_resit' => $new_receipt,
				'type' => Transaction::$types[$rows->type] ?? $rows->type,
				'method' => strtoupper((string) $rows->method),
				'amount' => 'RM ' . number_format((float) $rows->amount, 2),
				'status' => '<span class="badge-status badge-status-' . ($statusBadges[$rows->status] ?? 'neutral') . '">' . e(Transaction::$statuses[$rows->status] ?? $rows->status) . '</span>',
				'actions' => $action_column,
			];
		}

		return response()->json([
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $datatable_data,
		]);
	}

	public function updateFpxCount(Request $request)
	{
		$data = [];

		try {
			if ($request->type === 'custom_all') {
				return response()->json($this->transactionSummaryCounts());
			}

			$counts = $this->transactionSummaryCounts();

			if ($request->type != '') {
				switch ($request->type) {
					case 'subscribe':
						$data['subscribe_trans_count'] = $counts['subscribe_trans_count'];
						break;
					case 'purchase':
						$data['purchase_trans_count'] = $counts['purchase_trans_count'];
						break;
					case 'total':
						$data['total_trans_count'] = $counts['total_trans_count'];
						break;
				}
			}

			if ($request->status != '') {
				switch ($request->status) {
					case 'success':
						$data['success_trans_count'] = $counts['success_trans_count'];
						break;
					case 'pending':
						$data['pending_trans_count'] = $counts['pending_trans_count'];
						break;
					case 'pending_authorization':
						$data['pending_authorization_trans_count'] = $counts['pending_authorization_trans_count'];
						break;
					case 'failed':
						$data['failed_trans_count'] = $counts['failed_trans_count'];
						break;
					case 'declined':
						$data['declined_trans_count'] = $counts['declined_trans_count'];
						break;
				}
			}
		} catch (\Throwable $e) {
			Log::error('Transactions summary counts failed', ['error' => $e->getMessage()]);
		}

		return response()->json($data);
	}

	/**
	 * Cached GROUP BY counts for cards + DataTables totals.
	 *
	 * @return array<string, int>
	 */
	protected function transactionSummaryCounts(): array
	{
		$scopeKey = auth()->user()->can('Transaction:all')
			? 'all'
			: 'ou:' . auth()->user()->organization_unit_id;

		return Cache::remember('transactions:counts:' . $scopeKey, 300, function () {
			$m_transactions = Transaction::query();

			if (! auth()->user()->can('Transaction:all')) {
				$m_transactions->where('transactions.organization_unit_id', auth()->user()->organization_unit_id);
			}

			$byStatus = (clone $m_transactions)
				->select('transactions.status', DB::raw('COUNT(*) as aggregate'))
				->groupBy('transactions.status')
				->pluck('aggregate', 'status');

			$byType = (clone $m_transactions)
				->select('transactions.type', DB::raw('COUNT(*) as aggregate'))
				->groupBy('transactions.type')
				->pluck('aggregate', 'type');

			return [
				'subscribe_trans_count' => (int) ($byType['subscription'] ?? 0),
				'purchase_trans_count' => (int) ($byType['purchase'] ?? 0),
				'total_trans_count' => (int) $byStatus->sum(),
				'success_trans_count' => (int) ($byStatus['success'] ?? 0),
				'pending_trans_count' => (int) ($byStatus['pending'] ?? 0),
				'failed_trans_count' => (int) ($byStatus['failed'] ?? 0),
				'declined_trans_count' => (int) ($byStatus['declined'] ?? 0),
				'pending_authorization_trans_count' => (int) ($byStatus['pending_authorization'] ?? 0),
			];
		});
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

	/**
	 * Verify an FPX AE (status enquiry) response and log the outcome.
	 *
	 * Returns true when the caller should refuse to act on the payload. While
	 * services.fpx.verify_signature is false this only logs and always returns false,
	 * so the field set can be confirmed against real traffic before enforcing —
	 * see config/services.php for the rollout steps.
	 */
	private function rejectOnBadFpxSignature(array $data, $transaction): bool
	{
		$result = \App\Models\Fpx::verifyResponseSignature($data);

		if ($result['valid']) {
			return false;
		}

		\Log::channel('fpx-respond')->warning('FPX requery signature verification FAILED', [
			'enforced'      => (bool) config('services.fpx.verify_signature'),
			'reason'        => $result['reason'],
			'transaction'   => $transaction->number ?? null,
			'source_string' => $result['source_string'],
			'received_sum'  => $data['fpx_checkSum'] ?? null,
			'payload_keys'  => array_keys($data),
		]);

		return (bool) config('services.fpx.verify_signature');
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

			$client   = new Client(['verify' => config('services.fpx.verify_tls', true)]);
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

		// Diagnostic screen only — this method never changes transaction status, so a
		// failed signature is surfaced to the admin rather than blocking the view.
		$signature_check = \App\Models\Fpx::verifyResponseSignature($data);

		return view('transactions.fpx_query', compact('transaction', 'ac_responses', 'ae_requests', 'signature_check'));
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
			$client = new Client(['verify' => config('services.fpx.verify_tls', true)]);
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

		if ($this->rejectOnBadFpxSignature($data, $transaction)) {
			return redirect('transactions/' . $id)->with('error', 'Respons FPX gagal pengesahan tandatangan. Status transaksi tidak diubah.');
		}

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

	/**
	 * Dipanggil setiap minit oleh cron melalui api/fpx_queue.
	 *
	 * Kunci diperlukan kerana cron menembak mengikut jam, bukan mengikut sama ada
	 * larian sebelumnya sudah tamat. Apabila satu larian mengambil masa lebih
	 * seminit, larian bertindih dan setiap satu mengulangi pertanyaan yang sama
	 * pada jadual sejuta baris. Tujuh salinan serentak pernah diperhatikan di
	 * staging, cukup untuk menepukan pangkalan data sehingga halaman lain 504.
	 *
	 * Larian pertama memegang kunci; yang lain keluar serta-merta. TTL 5 minit
	 * memastikan larian yang tersembam tidak menahan kunci selama-lamanya.
	 */
	public function queue_fpx_requery()
	{
		$lock = Cache::lock('fpx-queue-requery', 300);

		if (! $lock->get()) {
			echo 'FPX requery already running, skipped.';

			return;
		}

		try {
			$this->dispatch_fpx_requery_jobs();
		} finally {
			$lock->release();
		}
	}

	private function dispatch_fpx_requery_jobs()
	{
		$queueLimit = config('services.fpx.jobs_limit');

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
			$client = new Client(['verify' => config('services.fpx.verify_tls', true)]);
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

		if ($this->rejectOnBadFpxSignature($data, $transaction)) {
			return response()->json(['status' => 'Respons FPX gagal pengesahan tandatangan. Status transaksi tidak diubah.']);
		}

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
