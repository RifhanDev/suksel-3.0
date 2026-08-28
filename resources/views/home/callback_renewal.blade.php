@extends('layouts.modernLanding')

@section('styles')
	<style>
		.cbr-wrap {
			max-width: 640px;
			margin: 0 auto;
			padding: 32px 16px 60px;
		}

		.cbr-header {
			margin-bottom: 24px;
		}

		.cbr-header h2 {
			font-size: 1.5rem;
			font-weight: 700;
			color: #1e293b;
			margin: 0;
		}

		.cbr-card {
			background: #fff;
			border-radius: 12px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
			overflow: hidden;
		}

		.cbr-status-bar {
			padding: 16px 24px;
			font-weight: 700;
			font-size: 0.95rem;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.cbr-status-bar.success {
			background: #f0fdf4;
			color: #15803d;
			border-bottom: 1px solid #bbf7d0;
		}

		.cbr-status-bar.fail {
			background: #fff7f7;
			color: #a91830;
			border-bottom: 1px solid #f3c6cc;
		}

		.cbr-info {
			padding: 4px 24px;
		}

		.cbr-info-row {
			display: flex;
			justify-content: space-between;
			gap: 16px;
			padding: 14px 0;
			border-bottom: 1px solid #f3f4f6;
			font-size: 0.9rem;
		}

		.cbr-info-row:last-child {
			border-bottom: none;
		}

		.cbr-info-row dt {
			color: #6b7280;
			font-weight: 500;
		}

		.cbr-info-row dd {
			margin: 0;
			color: #1e293b;
			font-weight: 600;
			text-align: right;
		}

		.cbr-actions {
			padding: 20px 24px;
			display: flex;
			gap: 10px;
			flex-wrap: wrap;
		}

		.cbr-actions a {
			display: inline-flex;
			align-items: center;
			padding: 11px 20px;
			border-radius: 8px;
			font-weight: 700;
			font-size: 0.86rem;
			text-decoration: none;
			transition: background 0.15s, box-shadow 0.15s;
		}

		.cbr-btn-primary {
			background: #c41e3a;
			color: #fff;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
		}

		.cbr-btn-primary:hover {
			background: #a91830;
			color: #fff;
		}

		.cbr-btn-secondary {
			background: #fff;
			color: #374151;
			border: 1px solid #d1d5db;
		}

		.cbr-btn-secondary:hover {
			background: #f9fafb;
			color: #1e293b;
		}
	</style>
@endsection

@section('content')
	<div class="cbr-wrap">
		<div class="cbr-header">
			<h2>Pembaharuan Langganan</h2>
		</div>

		@if ($transaction->status == 'success')
			<div class="cbr-card">
				<div class="cbr-status-bar success">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10" />
						<path d="M9 12l2 2 4-4" />
					</svg>
					Langganan Berjaya
				</div>
				<dl class="cbr-info">
					<div class="cbr-info-row">
						<dt>No Transaksi</dt>
						<dd>{{ $transaction->number }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>No Resit</dt>
						<dd>{{ $receipt != 'old' ? $receipt : $transaction->vendor_id . '-' . $transaction->gateway_reference }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>Kaedah Pembayaran</dt>
						<dd>{{ \App\Gateway::$methods[$transaction->method] }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>No Rujukan Pembayaran</dt>
						<dd>{{ $transaction->gateway_reference }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>Jumlah Pembayaran</dt>
						<dd>RM {{ $transaction->amount }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>Tempoh Langganan</dt>
						<dd>{{ \Carbon\Carbon::parse($subscription->start_date ?? '')->format('d/m/Y') ?? '' }} -
							{{ \Carbon\Carbon::parse($subscription->end_date ?? '')->format('d/m/Y') ?? '' }}</dd>
					</div>
				</dl>
				<div class="cbr-actions">
					{!! link_to_route('vendors.subscriptions.receipt', 'Lihat Resit', [$vendor->id, $subscription->id ?? ''], ['class' => 'cbr-btn-secondary', 'target' => 'new']) !!}
					{!! link_to_route('vendor', 'Selesai', [], ['class' => 'cbr-btn-primary']) !!}
				</div>
			</div>
		@else
			<div class="cbr-card">
				<div class="cbr-status-bar fail">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10" />
						<path d="M12 8v4M12 16h.01" />
					</svg>
					Langganan Tidak Berjaya
				</div>
				<dl class="cbr-info">
					<div class="cbr-info-row">
						<dt>No Transaksi</dt>
						<dd>{{ $transaction->number }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>No Rujukan Pembayaran</dt>
						<dd>{{ $transaction->gateway_reference }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>Jumlah Pembayaran</dt>
						<dd>RM {{ $transaction->amount }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>Kaedah Pembayaran</dt>
						<dd>{{ \App\Gateway::$methods[$transaction->method] }}</dd>
					</div>
					<div class="cbr-info-row">
						<dt>Mesej</dt>
						<dd>{{ $transaction->response_code }}: {{ $transaction->response_message }}</dd>
					</div>
				</dl>
				<div class="cbr-actions">
					{!! link_to_route('renewal', 'Cuba Semula', [], ['class' => 'cbr-btn-primary']) !!}
				</div>
			</div>
		@endif
	</div>
@endsection
