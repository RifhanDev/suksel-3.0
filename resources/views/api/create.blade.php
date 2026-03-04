@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
@endsection

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Masukkan Token Agensi Baru</h3>
			<p class="text-muted small m-0">Sistem Tender Online Selangor</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
			<div class="d-flex align-items-center gap-2">
				<span class="badge bg-light text-dark border">TARIKH</span>
				<span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
			</div>
		</div>
	</div>

	<form action="{{ route('apitoken.store') }}" method="POST">
		@csrf

		<!-- Form Card -->
		<div class="stats-card mb-4">
			<div class="stats-card-header p-4 border-bottom">
				<div class="d-flex align-items-center gap-3">
					<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z"></path></svg>
					</div>
					<h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">Maklumat API Token</h3>
				</div>
			</div>
			<div class="card-body p-4">
				<div class="row">
					<!-- Agensi Field -->
					<div class="col-md-12 mb-3">
						<label for="organization_unit_id" class="form-label fw-semibold">
							Agensi <span class="text-danger">*</span>
						</label>
						<select name="organization_unit_id" id="organization_unit_id" class="form-control form-select @error('organization_unit_id') is-invalid @enderror" required>
							<option value="">-- Pilih Agensi --</option>
							@foreach ($agencies as $agency)
								<option value="{{ $agency->id }}" {{ old('organization_unit_id') == $agency->id ? 'selected' : '' }}>{{ $agency->name }}</option>
							@endforeach
						</select>
						@error('organization_unit_id')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>

					<!-- Token Field -->
					<div class="col-md-12 mb-3">
						<label for="token" class="form-label fw-semibold">
							Token <span class="text-danger">*</span>
						</label>
						<div class="input-group">
							<input type="text" name="token" id="token" class="form-control @error('token') is-invalid @enderror" readonly placeholder="Klik Jana untuk menjana token" required>
							<button type="button" class="btn btn-selangor generate d-flex align-items-center gap-2">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
								Jana
							</button>
						</div>
						@error('token')
							<div class="invalid-feedback d-block">{{ $message }}</div>
						@enderror
					</div>
				</div>
			</div>
		</div>

		<!-- Action Buttons -->
		<div class="stats-card">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
					<a href="{{ asset('apitoken') }}" class="btn btn-secondary d-flex align-items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
						Kembali ke Senarai
					</a>
					<button type="submit" class="btn btn-success d-flex align-items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
						Tambah Token
					</button>
				</div>
			</div>
		</div>
	</form>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$('.generate').on('click', function(e) {
				e.preventDefault();
				var orgId = $('#organization_unit_id').val();
				if (!orgId) {
					alert('Sila pilih agensi terlebih dahulu.');
					return;
				}
				$.ajax({
					type: "POST",
					url: "{{ route('apitoken.generate') }}",
					data: {
						'_token': '{{ csrf_token() }}',
						'id': orgId
					},
					dataType: "json",
					success: function(response) {
						$('#token').val(response);
					},
					error: function() {
						alert('Ralat semasa menjana token. Sila cuba lagi.');
					}
				});
			});
		});
	</script>
@endsection
