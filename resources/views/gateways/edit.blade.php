@extends('layouts.v3.master')

@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Tetapan Pembayaran</h3>
			<p class="text-muted small m-0">Kemaskini konfigurasi saluran pembayaran di bawah.</p>
		</div>
	</div>

	{{-- Main edit form --}}
	<form action="{{ url('gateways/' . $gateway->id) }}" method="POST">
		@csrf
		@method('PUT')

		<div class="content-card">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
					<line x1="1" y1="10" x2="23" y2="10"></line>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Tetapan Pembayaran</span>
			</div>

			<div class="p-4">
				@include('gateways.form')
			</div>

			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
				<div class="d-flex gap-2">
					@if (App\Gateway::canList())
						<a href="{{ asset('gateways') }}" class="btn-form btn-form-secondary">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<line x1="19" y1="12" x2="5" y2="12"></line>
								<polyline points="12 19 5 12 12 5"></polyline>
							</svg>
							Batal
						</a>
					@endif
				</div>

				<button type="submit" class="btn-form btn-form-primary confirm">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
						<polyline points="17 21 17 13 7 13 7 21"></polyline>
						<polyline points="7 3 7 8 15 8"></polyline>
					</svg>
					Simpan
				</button>
			</div>
		</div>
	</form>

	{{-- Delete form — separate from main form (valid HTML, sibling not nested) --}}
	@if ($gateway->canDelete())
		<div class="mt-3 d-flex justify-content-end">
			<form action="{{ url('gateways/' . $gateway->id) }}" method="POST" class="m-0">
				@csrf
				@method('DELETE')
				<button type="button" class="btn-form btn-form-danger confirm-delete">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="3 6 5 6 21 6"></polyline>
						<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
						<path d="M10 11v6"></path>
						<path d="M14 11v6"></path>
						<path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
					</svg>
					Padam Tetapan
				</button>
			</form>
		</div>
	@endif
@endsection
