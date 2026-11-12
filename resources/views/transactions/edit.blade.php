@extends('layouts.v3.master')
@section('content')
	@php
		$statusBadge = ['success' => 'success', 'pending' => 'warning', 'pending_authorization' => 'info', 'failed' => 'danger', 'declined' => 'danger'][$transaction->status] ?? 'neutral';
		$canEditStatus = in_array($transaction->status, ['pending', 'pending-authorization']);
	@endphp

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<div class="d-flex align-items-center flex-wrap gap-2">
				<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Transaksi</h3>
				<span class="badge-status badge-status-{{ $statusBadge }}">{{ App\Transaction::$statuses[$transaction->status] ?? $transaction->status }}</span>
			</div>
			<p class="text-muted small m-0 mt-1">No. Transaksi {{ $transaction->number }}</p>
		</div>
	</div>

	<form action="{{ url('transactions/' . $transaction->id) }}" method="POST">
		@csrf
		@method('PUT')

		<div class="modern-card mb-4">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)"
					stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
					<line x1="1" y1="10" x2="23" y2="10"></line>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Transaksi</span>
			</div>

			<div class="p-4">
				@if ($canEditStatus)
					<div class="alert-selangor mb-4">
						<div class="alert-selangor-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
								<line x1="12" y1="9" x2="12" y2="13"></line>
								<line x1="12" y1="17" x2="12.01" y2="17"></line>
							</svg>
						</div>
						<div class="small lh-sm">
							<strong>Perhatian</strong>
							Kemaskini status secara manual hanya selepas pembayaran disahkan dengan bank / FPX.
						</div>
					</div>
				@endif

				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label fw-medium small">Nama Syarikat</label>
						<input type="text" class="form-control" value="{{ $transaction->vendor->name }}" disabled>
					</div>
					<div class="col-md-6">
						<label class="form-label fw-medium small">Agensi Pembayaran</label>
						<input type="text" class="form-control" value="{{ $transaction->agency->name ?? $transaction->organization_unit_id }}" disabled>
					</div>
					<div class="col-md-6">
						<label class="form-label fw-medium small">No. Transaksi</label>
						<input type="text" class="form-control" value="{{ $transaction->number }}" disabled>
					</div>
					@if ($transaction->gateway_reference)
						<div class="col-md-6">
							<label class="form-label fw-medium small">No. Resit</label>
							<input type="text" class="form-control" value="{{ $transaction->receipt_number }}" disabled>
						</div>
					@endif
					<div class="col-md-6">
						<label class="form-label fw-medium small">Jenis Pembayaran</label>
						<input type="text" class="form-control" value="{{ App\Transaction::$types[$transaction->type] }}" disabled>
					</div>
					<div class="col-md-6">
						<label class="form-label fw-medium small">Jumlah (RM)</label>
						<input type="text" class="form-control" value="{{ number_format((float) $transaction->amount, 2) }}" disabled>
					</div>

					<div class="col-md-6">
						<label for="status" class="form-label fw-medium small">Status</label>
						@if ($canEditStatus)
							<select class="form-select" id="status" name="status">
								@foreach (App\Transaction::$statuses as $value => $label)
									<option value="{{ $value }}" {{ old('status', $transaction->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
								@endforeach
							</select>
						@else
							<input type="text" class="form-control" value="{{ App\Transaction::$statuses[$transaction->status] }}" disabled>
						@endif
						{!! $errors->first('status', '<div class="text-danger small mt-1">:message</div>') !!}
					</div>

					@if ($canEditStatus)
						<div class="col-md-6">
							<label for="gateway_reference" class="form-label fw-medium small">No Rujukan Pembayaran</label>
							<input type="text" class="form-control" id="gateway_reference" name="gateway_reference"
								value="{{ old('gateway_reference', $transaction->gateway_reference) }}">
							{!! $errors->first('gateway_reference', '<div class="text-danger small mt-1">:message</div>') !!}
						</div>
						<div class="col-md-6">
							<label for="gateway_auth" class="form-label fw-medium small">No Kebenaran Pembayaran</label>
							<input type="text" class="form-control" id="gateway_auth" name="gateway_auth"
								value="{{ old('gateway_auth', $transaction->gateway_auth) }}">
							{!! $errors->first('gateway_auth', '<div class="text-danger small mt-1">:message</div>') !!}
						</div>
					@endif
				</div>
			</div>

			<!-- FOOTER ACTIONS -->
			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light rounded-bottom">
				<a href="{{ asset('transactions') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Senarai Transaksi
				</a>
				<button type="submit" class="btn-form btn-form-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
						<polyline points="17 21 17 13 7 13 7 21"></polyline>
						<polyline points="7 3 7 8 15 8"></polyline>
					</svg>
					Kemaskini
				</button>
			</div>
		</div>
	</form>
@endsection

@section('scripts')
	<script src="{{ asset('js/application.js') }}"></script>
@endsection
