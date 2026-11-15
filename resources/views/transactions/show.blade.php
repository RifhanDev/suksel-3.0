@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
	<style>
		.txn-field {
			padding: 14px 24px;
			border-bottom: 1px solid #f1f5f9;
		}

		.txn-label {
			font-size: 0.72rem;
			font-weight: 600;
			color: #64748b;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			margin-bottom: 4px;
		}

		.txn-value {
			font-size: 0.9rem;
			font-weight: 600;
			color: #1e293b;
			word-break: break-word;
		}
	</style>
@endsection

@section('content')
	@php
		$statusBadge = ['success' => 'success', 'pending' => 'warning', 'pending_authorization' => 'info', 'failed' => 'danger', 'declined' => 'danger'][$transaction->status] ?? 'neutral';
		$isFpx = $transaction->gateway && $transaction->gateway->type == 'fpx';
		$isEbpg = $transaction->gateway && $transaction->gateway->type == 'ebpg';
	@endphp

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<div class="d-flex align-items-center flex-wrap gap-2">
				<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Maklumat Transaksi</h3>
				<span class="badge-status badge-status-{{ $statusBadge }}">{{ App\Transaction::$statuses[$transaction->status] ?? $transaction->status }}</span>
			</div>
			<p class="text-muted small m-0 mt-1">No. Transaksi {{ $transaction->number }}</p>
		</div>
		<a href="{{ asset('transactions') }}" class="btn-form btn-form-secondary">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
				stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="19" y1="12" x2="5" y2="12"></line>
				<polyline points="12 19 5 12 12 5"></polyline>
			</svg>
			Kembali ke Senarai
		</a>
	</div>

	<!-- MAKLUMAT TRANSAKSI -->
	<div class="modern-card mb-4">
		<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)"
				stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
				<line x1="1" y1="10" x2="23" y2="10"></line>
			</svg>
			<span class="fw-bold text-dark text-uppercase small">Maklumat Transaksi</span>
		</div>

		<div class="row g-0">
			<div class="col-md-6 txn-field">
				<div class="txn-label">Nama Syarikat</div>
				<div class="txn-value">{{ $transaction->vendor->name }}</div>
			</div>
			<div class="col-md-6 txn-field">
				<div class="txn-label">Agensi Pembayaran</div>
				<div class="txn-value">{{ $transaction->agency->name }}</div>
			</div>
			<div class="col-md-6 txn-field">
				<div class="txn-label">Tarikh Transaksi</div>
				<div class="txn-value">{{ Carbon\Carbon::parse($transaction->created_at)->format('j M Y H:i:s') }}</div>
			</div>
			<div class="col-md-6 txn-field">
				<div class="txn-label">No Transaksi</div>
				<div class="txn-value">{{ $transaction->number }}</div>
			</div>

			@if ($transaction->gateway_reference)
				<div class="col-md-6 txn-field">
					<div class="txn-label">No Resit</div>
					<div class="txn-value">{{ $receipt != 'old' ? $receipt : $transaction->vendor_id . '-' . $transaction->gateway_reference }}</div>
				</div>
			@endif

			<div class="col-md-6 txn-field">
				<div class="txn-label">Jenis Pembayaran</div>
				<div class="txn-value">{{ App\Transaction::$types[$transaction->type] }}</div>
			</div>
			<div class="col-md-6 txn-field">
				<div class="txn-label">Jumlah</div>
				<div class="txn-value">RM {{ number_format($transaction->amount, 2, '.', ',') }}</div>
			</div>
			<div class="col-md-6 txn-field">
				<div class="txn-label">Status</div>
				<div class="txn-value">
					<span class="badge-status badge-status-{{ $statusBadge }}">{{ App\Transaction::$statuses[$transaction->status] }}</span>
				</div>
			</div>

			@if ($transaction->ebpg_signature)
				<div class="col-md-6 txn-field">
					<div class="txn-label">eBPG Signature</div>
					<div class="txn-value">
						{!! wordwrap($transaction->ebpg_signature, 40, "<br>\n", true) !!}
						@if ($transaction->valid_ebpg_signature)
							<span class="badge-status badge-status-success ms-1">Sah</span>
						@endif
					</div>
				</div>
			@endif

			@if ($transaction->ebpg_signature_2)
				<div class="col-md-6 txn-field">
					<div class="txn-label">eBPG Signature 2</div>
					<div class="txn-value">
						{!! wordwrap($transaction->ebpg_signature_2, 40, "<br>\n", true) !!}
						@if ($transaction->valid_ebpg_signature_2)
							<span class="badge-status badge-status-success ms-1">Sah</span>
						@endif
					</div>
				</div>
			@endif

			<div class="col-md-6 txn-field">
				<div class="txn-label">No Rujukan Pembayaran</div>
				<div class="txn-value">{!! $transaction->gateway_reference ? e($transaction->gateway_reference) : '<span class="text-muted fw-normal">-</span>' !!}</div>
			</div>
			<div class="col-md-6 txn-field">
				<div class="txn-label">No Kebenaran Pembayaran</div>
				<div class="txn-value">{!! $transaction->gateway_auth ? e($transaction->gateway_auth) : '<span class="text-muted fw-normal">-</span>' !!}</div>
			</div>
			<div class="col-12 txn-field border-bottom-0">
				<div class="txn-label">Mesej Sistem</div>
				<div class="txn-value">
					@if ($transaction->response_message)
						{!! $transaction->response_message !!}
					@else
						<span class="text-muted fw-normal">-</span>
					@endif
				</div>
			</div>
		</div>
	</div>

	@if ($transaction->type == 'subscription')
		<!-- MAKLUMAT LANGGANAN -->
		<div class="modern-card mb-4">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)"
					stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
					<line x1="16" y1="2" x2="16" y2="6"></line>
					<line x1="8" y1="2" x2="8" y2="6"></line>
					<line x1="3" y1="10" x2="21" y2="10"></line>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Langganan</span>
			</div>

			<div class="row g-0">
				@if ($transaction->subscription)
					<div class="col-md-6 txn-field border-bottom-0">
						<div class="txn-label">Tempoh Langganan</div>
						<div class="txn-value">{{ \Carbon\Carbon::parse($transaction->subscription->start_date)->format('d/m/Y') }} -
							{{ \Carbon\Carbon::parse($transaction->subscription->end_date)->format('d/m/Y') }}</div>
					</div>
					<div class="col-md-6 txn-field border-bottom-0">
						<div class="txn-label">Resit Pembayaran</div>
						<div class="txn-value">
							{{ link_to_route('vendors.subscriptions.receipt', 'Resit', [$transaction->vendor_id, $transaction->subscription->id], ['target' => 'new', 'class' => 'btn btn-sm btn-warning rounded-8 px-3']) }}
						</div>
					</div>
				@elseif($transaction->cached_subscription)
					<div class="col-md-6 txn-field border-bottom-0">
						<div class="txn-label">Tempoh Langganan</div>
						<div class="txn-value">{{ \Carbon\Carbon::parse($transaction->cached_subscription->start_date)->format('d/m/Y') }} -
							{{ \Carbon\Carbon::parse($transaction->cached_subscription->end_date)->format('d/m/Y') }}</div>
					</div>
					@if ($transaction->status == 'success')
						<div class="col-md-6 txn-field border-bottom-0">
							<div class="txn-label">Resit Pembayaran</div>
							<div class="txn-value">
								{{ link_to_route('transactions.temp_receipt', 'Resit', $transaction->id, ['target' => 'new', 'class' => 'btn btn-sm btn-warning rounded-8 px-3']) }}
							</div>
						</div>
					@endif
				@else
					<div class="col-12 txn-field border-bottom-0">
						<div class="txn-label">Tempoh Langganan</div>
						<div class="txn-value text-muted fw-normal">Tiada Maklumat Langganan</div>
					</div>
				@endif
			</div>
		</div>
	@endif

	@if ($transaction->type == 'purchase')
		<!-- MAKLUMAT DOKUMEN -->
		<div class="modern-card mb-4">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)"
					stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
					<polyline points="14 2 14 8 20 8"></polyline>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Dokumen</span>
			</div>

			<div class="p-4">
				<div class="table-responsive">
					<table class="table table-bordered table-slate align-middle mb-0">
						<thead>
							<tr>
								<th>Tender / Sebut Harga</th>
								<th class="text-end" style="width: 140px;">Jumlah</th>
								<th class="text-center" style="width: 240px;">Tindakan</th>
							</tr>
						</thead>
						<tbody>
							@forelse($transaction->purchases as $purchase)
								<tr>
									<td>
										<div class="fw-bold text-dark">{{ $purchase->tender->tenderer->name }}</div>
										<a href="{{ asset('tenders/' . $purchase->tender_id) }}" class="text-decoration-none">
											<div class="small text-muted">{{ $purchase->tender->ref_number }}</div>
											{{ $purchase->tender->name }}
										</a>
									</td>
									<td class="text-end fw-semibold">RM {{ number_format((float) $purchase->amount, 2) }}</td>
									<td>
										<div class="d-flex gap-2 justify-content-center flex-nowrap">
											<a href="{{ asset('tenders/' . $purchase->tender_id . '/receipt/' . $purchase->id) }}" target="_blank"
												class="btn btn-sm btn-warning rounded-8 px-3 text-nowrap">Resit</a>
											<a href="{{ asset('tenders/' . $purchase->tender_id . '/document/' . $purchase->id) }}" target="_blank"
												class="btn btn-sm btn-primary rounded-8 px-3 text-nowrap">No. Siri Dokumen</a>
										</div>
									</td>
								</tr>
							@empty
								@foreach ($transaction->cached_purchases as $tender)
									<tr>
										<td>
											<div class="fw-bold text-dark">{{ $tender->tenderer->name }}</div>
											<a href="{{ route('tenders.show', $tender->id) }}" class="text-decoration-none">
												<div class="small text-muted">{{ $tender->ref_number }}</div>
												{{ $tender->name }}
											</a>
										</td>
										<td class="text-end fw-semibold">RM {{ number_format((float) $tender->price, 2) }}</td>
										<td>
											<div class="d-flex gap-2 justify-content-center flex-nowrap">
												@if ($transaction->status == 'success')
													<a href="{{ route('transactions.temp_receipt', [$transaction->id, 'tender_id' => $tender->id]) }}"
														target="_blank" class="btn btn-sm btn-warning rounded-8 px-3 text-nowrap">Resit</a>
												@else
													<span class="text-muted small">-</span>
												@endif
											</div>
										</td>
									</tr>
								@endforeach
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	@endif

	@if ($transaction->canUpdate() || $isFpx || $isEbpg)
		<!-- TINDAKAN -->
		<div class="modern-card mb-4">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
					stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
					<path d="M12 20h9"></path>
					<path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
				</svg>
				<span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Tindakan Transaksi</span>
			</div>
			<div class="p-4 bg-white rounded-bottom">
				<p class="text-muted small mb-4">Sila pilih tindakan yang ingin dilakukan terhadap transaksi ini:</p>
				<div class="d-flex flex-wrap gap-3">
					@if ($transaction->canUpdate())
						<a href="{{ asset('transactions/' . $transaction->id . '/edit') }}" class="btn-action btn-action-blue">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
								<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
							</svg>
							Kemaskini
						</a>
					@endif

					@if ($isEbpg)
						<form method="post" id="transaction_query" class="d-inline" action="{{ $transaction->gateway->daemon_url }}">
							<input type="hidden" name="MERCHANT_ACC_NO" value="{{ $transaction->gateway->merchant_code }}">
							<input type="hidden" name="MERCHANT_TRANID"
								value="{{ $transaction->gateway->transaction_prefix }}{{ $transaction->number }}">
							<input type="hidden" name="AMOUNT" value="{{ $transaction->amount }}">
							<input type="hidden" name="TRANSACTION_TYPE" value="1">
							<input type="hidden" name="TXN_SIGNATURE" value="<?php echo hash('sha512', "{$transaction->gateway->private_key}{$transaction->gateway->merchant_code}{$transaction->gateway->transaction_prefix}{$transaction->number}{$transaction->amount}"); ?>">
							<input type="hidden" name="RESPONSE_TYPE" value="PLAIN">
							<button type="submit" class="btn-action btn-action-amber">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="11" cy="11" r="8"></circle>
									<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
								</svg>
								Data eBPG
							</button>
						</form>

						@if ($transaction->canUpdate())
							<form method="post" id="ebpg_requery" class="d-inline" action="{{ $transaction->gateway->daemon_url }}">
								<input type="hidden" name="MERCHANT_ACC_NO" value="{{ $transaction->gateway->merchant_code }}">
								<input type="hidden" name="MERCHANT_TRANID"
									value="{{ $transaction->gateway->transaction_prefix }}{{ $transaction->number }}">
								<input type="hidden" name="AMOUNT" value="{{ $transaction->amount }}">
								<input type="hidden" name="TRANSACTION_TYPE" value="1">
								<input type="hidden" name="TXN_SIGNATURE" value="<?php echo hash('sha512', "{$transaction->gateway->private_key}{$transaction->gateway->merchant_code}{$transaction->gateway->transaction_prefix}{$transaction->number}{$transaction->amount}"); ?>">
								<input type="hidden" name="RESPONSE_TYPE" value="HTTP">
								<input type="hidden" name="RETURN_URL" value="{{ route('transactions.ebpg_requery', $transaction->id) }}">
								<button type="submit" class="btn-action btn-action-red">
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<polyline points="23 4 23 10 17 10"></polyline>
										<path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
									</svg>
									Kemaskini Data eBPG
								</button>
							</form>
						@endif
					@endif

					@if ($isFpx)
						<a href="{{ route('transactions.fpx_query', $transaction->id) }}" class="btn-action btn-action-amber txn-data-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="11" cy="11" r="8"></circle>
								<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							</svg>
							Data FPX
						</a>

						@if ($transaction->canUpdate())
							<a href="{{ route('transactions.fpx_requery', $transaction->id) }}" class="btn-action btn-action-red">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="23 4 23 10 17 10"></polyline>
									<path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
								</svg>
								Kemaskini Data FPX
							</a>
						@endif
					@endif
				</div>
			</div>
		</div>
	@endif
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$(document).on('submit', 'form#transaction_query', function() {
				window.open('about:blank', 'Popup_Window',
					'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=500,left = 50,top = 50'
				);
				this.target = 'Popup_Window';
			});

			$('.txn-data-link').click(function(evt) {
				var w = window.open(this.href, 'Popup_Window',
					'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=500,left = 50,top = 50'
				);
				evt.preventDefault();
				return false;
			});
		});
	</script>
@endsection
