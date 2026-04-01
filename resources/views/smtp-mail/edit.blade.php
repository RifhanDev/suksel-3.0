@extends('layouts.v3.master')
@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Email SMTP</h3>
			<p class="text-muted small m-0">Kemaskini maklumat konfigurasi pelayan email SMTP.</p>
		</div>
	</div>

	@if ($errors->any())
		<div class="alert alert-danger mb-3">
			<ul class="mb-0">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form id="saveForm" name="saveForm"
		action="{{ route('mail-manager.smtp-setting.update', ['smtp_setting' => $data->enc_id]) }}" method="POST">
		@csrf
		@method('PUT')

		<div class="content-card">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="2" y="4" width="20" height="16" rx="2"></rect>
					<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat SMTP</span>
			</div>

			<div class="p-4">
				<div class="row g-3">
					<!-- Mail Server -->
					<div class="col-12">
						<label for="mail_server" class="form-label fw-medium small">Mail Server <span class="text-danger">*</span></label>
						<input class="form-control" required id="mail_server" name="mail_server" type="text"
							value="{{ old('mail_server') ?? $data->mail_server }}">
						@error('mail_server')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Port -->
					<div class="col-md-6">
						<label for="mail_port" class="form-label fw-medium small">Mail Port <span class="text-danger">*</span></label>
						<input class="form-control" required id="mail_port" name="mail_port" type="number" max="65535"
							value="{{ old('mail_port') ?? $data->mail_port }}">
						@error('mail_port')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Encryption -->
					<div class="col-md-6">
						<label for="mail_crypto" class="form-label fw-medium small">Mail Encryption</label>
						<select name="mail_crypto" id="mail_crypto" class="form-select">
							<option value="0" {{ (old('mail_crypto') ?? $data->mail_crypto) == 0 ? 'selected' : '' }}>NONE</option>
							<option value="1" {{ (old('mail_crypto') ?? $data->mail_crypto) == 1 ? 'selected' : '' }}>TLS</option>
							<option value="2" {{ (old('mail_crypto') ?? $data->mail_crypto) == 2 ? 'selected' : '' }}>SSL</option>
						</select>
						@error('mail_crypto')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Username -->
					<div class="col-12">
						<label for="mail_username" class="form-label fw-medium small">Mail Username <span
								class="text-danger">*</span></label>
						<input class="form-control" required id="mail_username" name="mail_username" type="text"
							value="{{ old('mail_username') ?? $data->mail_username }}">
						@error('mail_username')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Password -->
					<div class="col-12">
						<label for="mail_password" class="form-label fw-medium small">Mail Password</label>
						<input class="form-control" id="mail_password" name="mail_password" type="password"
							value="{{ old('mail_password') ?? '********' }}">
						@error('mail_password')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Daily Messages Limit -->
					<div class="col-12">
						<label for="mail_message_ratelimit" class="form-label fw-medium small">Daily Messages Limit <span
								class="text-danger">*</span></label>
						<input class="form-control" required id="mail_message_ratelimit" name="mail_message_ratelimit" type="number"
							value="{{ old('mail_message_ratelimit') ?? $data->mail_message_ratelimit }}">
						@error('mail_message_ratelimit')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>
				</div>
			</div>

			<div
				class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-2 p-4 border-top bg-light">
				<a href="{{ route('mail-manager.smtp-setting.index') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Batal
				</a>
				<div class="d-flex flex-column flex-sm-row gap-2">
					<button type="button" class="btn-form btn-form-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="3 6 5 6 21 6"></polyline>
							<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
							<path d="M10 11v6"></path>
							<path d="M14 11v6"></path>
							<path d="M9 6V4h6v2"></path>
						</svg>
						Padam
					</button>
					<button type="submit" form="saveForm" class="btn-form btn-form-primary">
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
		</div>
	</form>

	<!-- Delete Modal -->
	<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fw-bold" id="deleteModalLabel">Pengesahan Padam</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="mb-0">Adakah anda pasti untuk memadam rekod ini?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Tidak</button>
					<form action="{{ route('mail-manager.smtp-setting.destroy', ['smtp_setting' => $data->enc_id]) }}" method="POST"
						class="d-inline m-0">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn-form btn-form-danger">Ya, Padam</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
