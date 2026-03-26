@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Masukkan Token Agensi Baru</h3>
			<p class="text-muted small m-0">Jana dan daftarkan token API baharu untuk agensi.</p>
		</div>
	</div>

	<form action="{{ route('apitoken.store') }}" method="POST">
		@csrf

		<div class="content-card">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
					<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Token</span>
			</div>

			<div class="p-4">
				<div class="row g-3">
					<!-- Agensi -->
					<div class="col-12">
						<label for="organization_unit_id" class="form-label fw-medium small">
							Agensi <span class="text-danger">*</span>
						</label>
						<select name="organization_unit_id" id="organization_unit_id" class="form-select" required>
							@foreach ($agencies as $agency)
								<option value="{{ $agency->id }}">{{ $agency->name }}</option>
							@endforeach
						</select>
					</div>

					<!-- Token -->
					<div class="col-12">
						<label for="token" class="form-label fw-medium small">
							Token <span class="text-danger">*</span>
						</label>
						<div class="d-flex gap-2">
							<input type="text" name="token" id="token" class="form-control" readonly>
							<button type="button" class="btn btn-sm px-3 flex-shrink-0 generate d-flex align-items-center gap-1" style="background:#1d6f42;color:#fff;border-color:#1d6f42;">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1-4.069 0l-.301-.301l-6.558 6.558a2 2 0 0 1-1.239.578L5.172 21H4a1 1 0 0 1-.993-.883L3 20v-1.172a2 2 0 0 1 .467-1.284l.119-.13L4 17h2v-2h2v-2l2.144-2.144l-.301-.301a2.877 2.877 0 0 1 0-4.069l2.643-2.643a2.877 2.877 0 0 1 4.069 0M15 9h.01"/></svg>
								Jana Token
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
				<a href="{{ asset('apitoken') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
						fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
						stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Batal
				</a>
				<button type="submit" class="btn-form btn-form-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
						fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
						stroke-linejoin="round">
						<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
						<polyline points="17 21 17 13 7 13 7 21"></polyline>
						<polyline points="7 3 7 8 15 8"></polyline>
					</svg>
					Tambah Token
				</button>
			</div>
		</div>

	</form>
@endsection

@section('scripts')
	<script type="text/javascript">
		$('.generate').click(function(e) {
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: "{{ route('apitoken.generate') }}",
				data: {
					'id': $('#organization_unit_id').val()
				},
				dataType: "json",
				success: function(response) {
					$('#token').val(response);
				}
			});
		});
	</script>
@endsection
