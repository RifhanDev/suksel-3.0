@extends('layouts.v3.master')

@section('styles')
	<style>
		.card-form-compact {
			background: white;
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
			overflow: hidden;
		}

		.card-form-header {
			padding: 15px 20px;
			border-bottom: 1px solid #f1f5f9;
			background: #fff;
		}

		.card-form-body {
			padding: 20px;
		}
	</style>
@endsection

@section('content')

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<!-- Title -->
		<div class="mb-3 mb-lg-0">
			<div class="d-flex align-items-center flex-wrap gap-2">
				<h3 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="letter-spacing: -0.5px;">
					Kemaskini Pengguna
				</h3>
				@if ($currentUser->confirmed == 1)
					<span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border bg-white shadow-sm mt-1 mt-sm-0"
						style="font-size: 0.75rem;">
						<span class="bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
						<span class="fw-bold text-dark">{{ strtoupper($currentUser->status()) }}</span>
					</span>
				@else
					<span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border bg-white shadow-sm mt-1 mt-sm-0"
						style="font-size: 0.75rem;">
						<span class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></span>
						<span class="fw-bold text-dark">{{ strtoupper($currentUser->status()) }}</span>
					</span>
				@endif
			</div>
			<p class="text-muted small m-0 mt-1">Sila kemaskini maklumat pengguna di bawah.</p>
		</div>
	</div>

	<form action="{{ url('users/' . $currentUser->id) }}" method="POST">
		@csrf
		@method('PUT')

		<div class="modern-card mb-4">

			<div id="step1-content">
				<!-- Header -->
				<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
						stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
						<polyline points="14 2 14 8 20 8"></polyline>
						<line x1="16" y1="13" x2="8" y2="13"></line>
						<line x1="16" y1="17" x2="8" y2="17">
							</polyline>
							<polyline points="10 9 9 9 8 9"></polyline>
					</svg>
					<span class="fw-bold text-dark text-uppercase small">Maklumat Pengguna</span>
				</div>

				<div class="p-4">
					<!-- Alert -->
					<div class="alert-selangor mb-4">
						<div class="alert-selangor-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
								</path>
								<line x1="12" y1="9" x2="12" y2="13"></line>
								<line x1="12" y1="17" x2="12.01" y2="17"></line>
							</svg>
						</div>
						<div class="small lh-sm">
							<strong>Perhatian</strong>
							Sila pastikan rekod yang dikemaskini adalah tepat.
						</div>
					</div>

					@include('users.form')
				</div>

				<!-- FOOTER ACTIONS -->
				<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light rounded-bottom">
					<a href="{{ asset('users') }}" class="btn-form btn-form-secondary">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="19" y1="12" x2="5" y2="12"></line>
							<polyline points="12 19 5 12 12 5"></polyline>
						</svg>
						Batal
					</a>
					<button type="submit" class="btn-form btn-form-primary">
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
		</div>
	</form>

	<!-- SECOND CARD: TINDAKAN PENGGUNA -->
	<div class="modern-card mb-4">
		<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
				<path d="M12 20h9"></path>
				<path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
			</svg>
			<span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Tindakan Pengguna</span>
		</div>
		<div class="p-4 bg-white rounded-bottom">
			<p class="text-muted small mb-4">Sila pilih tindakan yang ingin dilakukan terhadap pengguna ini:</p>
			<div class="d-flex flex-wrap gap-3">
				@if ($currentUser->canLogin())
					<a href="{{ asset('users/' . $currentUser->id . '/login') }}" class="btn-action btn-action-red">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
							<polyline points="10 17 15 12 10 7"></polyline>
							<line x1="15" y1="12" x2="3" y2="12"></line>
						</svg>
						Login Sebagai
					</a>
				@endif

				@if (Auth::user()->hasRole('Admin') && !$currentUser->confirmed)
					<a href="{{ action('UsersController@resendConfirmation', $currentUser->id) }}" class="btn-action btn-action-blue">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
							<polyline points="22,6 12,13 2,6"></polyline>
						</svg>
						Hantar Emel Pengesahan
					</a>
				@endif

				@if ($currentUser->canSetPassword())
					<a href="{{ action('UsersController@getSetPassword', $currentUser->id) }}" class="btn-action btn-action-slate">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
							<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
						</svg>
						Tukar Kata Laluan
					</a>
				@endif

				@if ($currentUser->canSetConfirmation())
					<form action="{{ action('UsersController@putSetConfirmation', $currentUser->id) }}" method="POST"
						class="d-inline">
						@csrf
						@method('PUT')
						<input type="hidden" name="confirmed" value="{{ !$currentUser->confirmed }}">
						<button type="submit" class="btn-action btn-action-amber">
							@if ($currentUser->confirmed)
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M16 21v-2a4 4 0 0 0-4-4H5c-2.2 0-4 1.8-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="18" y1="8" x2="23" y2="13"></line>
									<line x1="23" y1="8" x2="18" y2="13"></line>
								</svg>
								Nyahaktif Pengguna
							@else
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M16 21v-2a4 4 0 0 0-4-4H5c-2.2 0-4 1.8-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<polyline points="17 11 19 13 23 9"></polyline>
								</svg>
								Aktifkan Pengguna
							@endif
						</button>
					</form>
				@endif

				@if ($currentUser->canDelete())
					<form action="{{ route('users.destroy', $currentUser->id) }}" method="POST" class="d-inline">
						@csrf
						@method('DELETE')
						<button type="button" class="btn-action btn-action-danger confirm-delete">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="3 6 5 6 21 6"></polyline>
								<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
								</path>
								<line x1="10" y1="11" x2="10" y2="17"></line>
								<line x1="14" y1="11" x2="14" y2="17"></line>
							</svg>
							Padam Rekod
						</button>
					</form>
				@endif
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$('#roles').selectize({
				plugins: ['remove_button'],
			});

			if ($('#organization_unit_id').length) {
				$('#organization_unit_id').selectize();
			}
		});
	</script>
@endsection
