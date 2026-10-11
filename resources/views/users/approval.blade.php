@extends('layouts.v3.master')

@section('content')
	@php
		$currentUser = $currentUser ?: Auth::user();
		$predefinedRemarks = $predefinedRemarks ?? [];
		$selectedRoles = old('roles', $currentUser->roles->pluck('id')->toArray());
		if (empty($selectedRoles) && $currentUser->role_applied) {
			$selectedRoles = [(int) $currentUser->role_applied];
		}
	@endphp

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<div class="d-flex align-items-center flex-wrap gap-2">
				<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Sahkan Pengguna</h3>
				@if ($currentUser->confirmed == 1)
					<span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border bg-white shadow-sm"
						style="font-size: 0.75rem;">
						<span class="bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
						<span class="fw-bold text-dark">{{ strtoupper($currentUser->status()) }}</span>
					</span>
				@else
					<span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border bg-white shadow-sm"
						style="font-size: 0.75rem;">
						<span class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></span>
						<span class="fw-bold text-dark">{{ strtoupper($currentUser->status()) }}</span>
					</span>
				@endif
			</div>
			<p class="text-muted small m-0 mt-1">Semak maklumat pengguna dan luluskan atau tolak permohonan akaun.</p>
		</div>
	</div>

	<form action="{{ route('users.store-approval', $currentUser->id) }}" method="POST" id="approvalUserForm">
		@csrf
		@method('PUT')

		<div class="modern-card mb-4">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M16 21v-2a4 4 0 0 0-4-4H5a2 2 0 0 0-4 4v2"></path>
					<circle cx="8.5" cy="7" r="4"></circle>
					<polyline points="17 11 19 13 23 9"></polyline>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat &amp; Kelulusan</span>
			</div>

			<div class="p-4">
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
						Jika diluluskan, akaun akan diaktifkan (ARR) dan emel status akan dihantar kepada pengguna.
						Jika ditolak, akaun akan dipadam dan emel penolakan akan dihantar.
					</div>
				</div>

				@include('users.approval-form')

				<div class="row g-3 mt-1">
					<div class="col-12">
						<label class="form-label fw-medium small">Kelulusan <span class="text-danger">*</span></label>
						<div class="d-flex flex-wrap gap-4">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="approved" id="approved_pass" value="1"
									{{ old('approved') === '1' ? 'checked' : '' }} required>
								<label class="form-check-label fw-medium" for="approved_pass">Lulus</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="approved" id="approved_reject" value="0"
									{{ old('approved') === '0' ? 'checked' : '' }} required>
								<label class="form-check-label fw-medium" for="approved_reject">Tolak</label>
							</div>
						</div>
						{!! $errors->first('approved', '<div class="text-danger small mt-1">:message</div>') !!}
					</div>

					<div class="col-12" id="remarkTxt" style="display: none;">
						<label for="remark_txt" class="form-label fw-medium small">Catatan</label>
						<textarea class="form-control" id="remark_txt" name="remark_txt" rows="4"
							placeholder="Masukkan catatan kelulusan (pilihan)">{{ old('remark_txt') }}</textarea>
					</div>

					<div class="col-12" id="remarkDropdown" style="display: none;">
						<label for="remark_dropdown" class="form-label fw-medium small">Catatan Penolakan</label>
						<select class="form-select" id="remark_dropdown" name="remark_dropdown">
							<option value="">Pilih Catatan</option>
							@foreach ($predefinedRemarks as $remark => $label)
								<option value="{{ $remark }}" {{ old('remark_dropdown') == $remark ? 'selected' : '' }}>
									{{ $label }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light rounded-bottom">
				<a href="{{ url('users/pending-approval') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Kembali
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
	</form>

	<!-- TINDAKAN PENGGUNA -->
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
			<p class="text-muted small mb-4">Tindakan tambahan terhadap pengguna ini:</p>
			<div class="d-flex flex-wrap gap-3">
				@if (Auth::user()->hasRole('Admin') && !$currentUser->confirmed)
					<a href="{{ url('users/' . $currentUser->id . '/resend_confirmation') }}" class="btn-action btn-action-blue">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
							<polyline points="22,6 12,13 2,6"></polyline>
						</svg>
						Hantar Emel Pengesahan
					</a>
				@endif

				@if ($currentUser->canSetPassword())
					<a href="{{ url('users/' . $currentUser->id . '/reset_password') }}" class="btn-action btn-action-slate">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
							<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
						</svg>
						Tukar Kata Laluan
					</a>
				@endif

				@if ($currentUser->canSetConfirmation())
					<form action="{{ url('users/' . $currentUser->id . '/confirm') }}" method="POST" class="d-inline">
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
								<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
							</svg>
							Padam Rekod
						</button>
					</form>
				@endif

				<a href="{{ url('users') }}" class="btn-action btn-action-slate">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="8" y1="6" x2="21" y2="6"></line>
						<line x1="8" y1="12" x2="21" y2="12"></line>
						<line x1="8" y1="18" x2="21" y2="18"></line>
						<line x1="3" y1="6" x2="3.01" y2="6"></line>
						<line x1="3" y1="12" x2="3.01" y2="12"></line>
						<line x1="3" y1="18" x2="3.01" y2="18"></line>
					</svg>
					Senarai Pengguna
				</a>
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

			function showRemark(value) {
				if (value === '1') {
					$('#remarkTxt').show();
					$('#remarkDropdown').hide();
				} else if (value === '0') {
					$('#remarkDropdown').show();
					$('#remarkTxt').hide();
				} else {
					$('#remarkTxt').hide();
					$('#remarkDropdown').hide();
				}
			}

			var $approved = $('input[name="approved"]');
			showRemark($approved.filter(':checked').val());
			$approved.on('change', function() {
				showRemark($(this).val());
			});
		});
	</script>
@endsection
