@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Tetapan Pembayaran</h3>
			<p class="text-muted small m-0">Sistem Tender Online Selangor</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
			<div class="d-flex align-items-center gap-2">
				<span class="badge bg-light text-dark border">TARIKH</span>
				<span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
			</div>
		</div>
	</div>

	{!! Former::open(url('gateways/' . $gateway->id)) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	<div class="stats-card mb-4">
		<div class="stats-card-header p-4 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
				</div>
				<h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">Maklumat Gateway</h3>
			</div>
		</div>
		<div class="card-body p-4">
			@include('gateways.form')
		</div>
	</div>
	<div class="stats-card">
		<div class="card-body p-4">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
				@if (App\Gateway::canList())
					<a href="{{ asset('gateways') }}" class="btn btn-secondary d-flex align-items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
						Kembali ke Senarai
					</a>
				@endif
				<div class="d-flex gap-2 flex-wrap">
					@if ($gateway->canDelete())
						<form action="{{ url('gateways/' . $gateway->id) }}" method="POST" class="d-inline">
							@csrf
							@method('DELETE')
							<button type="button" class="btn btn-danger d-flex align-items-center gap-2 confirm-delete">Padam</button>
						</form>
					@endif
					<button type="submit" class="btn btn-selangor d-flex align-items-center gap-2">Simpan</button>
				</div>
			</div>
		</div>
	</div>
	{!! Former::close() !!}
@endsection
