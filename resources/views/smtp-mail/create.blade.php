@extends('layouts.v3.master')
@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Tambah Email SMTP</h3>
			<p class="text-muted small m-0">Sila lengkapkan maklumat konfigurasi pelayan email SMTP.</p>
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

	<form action="{{ route('mail-manager.smtp-setting.store') }}" method="POST">
		@csrf

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
							value="{{ old('mail_server') }}">
						@error('mail_server')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Port -->
					<div class="col-md-6">
						<label for="mail_port" class="form-label fw-medium small">Mail Port <span class="text-danger">*</span></label>
						<input class="form-control" required id="mail_port" name="mail_port" type="number" max="65535"
							value="{{ old('mail_port') }}">
						@error('mail_port')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Encryption -->
					<div class="col-md-6">
						<label for="mail_crypto" class="form-label fw-medium small">Mail Encryption</label>
						<select name="mail_crypto" id="mail_crypto" class="form-select">
							<option value="0" {{ (old('mail_crypto') ?? 0) == 0 ? 'selected' : '' }}>NONE</option>
							<option value="1" {{ (old('mail_crypto') ?? 0) == 1 ? 'selected' : '' }}>TLS</option>
							<option value="2" {{ (old('mail_crypto') ?? 0) == 2 ? 'selected' : '' }}>SSL</option>
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
							value="{{ old('mail_username') }}">
						@error('mail_username')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Mail Password -->
					<div class="col-12">
						<label for="mail_password" class="form-label fw-medium small">Mail Password</label>
						<input class="form-control" id="mail_password" name="mail_password" type="password"
							value="{{ old('mail_password') }}">
						@error('mail_password')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Daily Messages Limit -->
					<div class="col-12">
						<label for="mail_message_ratelimit" class="form-label fw-medium small">Daily Messages Limit <span
								class="text-danger">*</span></label>
						<input class="form-control" required id="mail_message_ratelimit" name="mail_message_ratelimit" type="number"
							value="{{ old('mail_message_ratelimit') }}">
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
					<button type="button" class="btn-form btn-form-secondary" onclick="openModalTestMail()">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path
								d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.43 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.34 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.38a16 16 0 0 0 5.66 5.66l1.19-1.19a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.17 15z">
							</path>
						</svg>
						Uji Tetapan SMTP Email
					</button>
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
@endsection

@push('modals')
	<div class="modal fade" id="myPopup" tabindex="-1" aria-labelledby="myPopupLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="myPopupForm" action="" method="">
				@csrf
				<div class="modal-content border-0 shadow-sm">
					<div id="modal_header" class="modal-header bg-light border-bottom px-4 py-3">
						<h5 id="modal_title" class="modal-title fw-bold text-dark small text-uppercase m-0"></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div id="modal_body" class="modal-body p-4"></div>
					<div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-end gap-2">
						<button id="button_cancel" type="button" class="btn-form btn-form-secondary"
							data-bs-dismiss="modal">Tutup</button>
						<button id="button_confirm" type="button" class="btn-form btn-form-primary">Teruskan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endpush

@section('scripts')
	<script>
		function openModalTestMail() {
			$("#modal_title").html("<b>Pengujiaan tetapan SMTP mail</b>");
			$("#button_cancel").html("TUTUP");
			$("#button_confirm").html("TERUSKAN");
			$('#button_confirm').attr('type', "submit");
			$('#myPopupForm').attr('action', '{{ env('MAIL_SAMPLER') }}');
			$('#myPopupForm').attr('method', "POST");

			$("#modal_body").empty();
			$("#modal_body").append(
				"Sila Masukkan Destinasi Email yang ingin dihantar : <input class='form-control' type='email' id='target_email' name='target_email' value='' />"
			);
			$("#modal_body").append("<input type='hidden' id='mail_host' name='mail_host' value='" + $("#mail_server").val() +
				"' />");
			$("#modal_body").append("<input type='hidden' id='mail_port' name='mail_port' value='" + $("#mail_port").val() +
				"' />");
			$("#modal_body").append("<input type='hidden' id='mail_crypto' name='mail_crypto' value='" + $(
				"#mail_crypto option:selected").text() + "' />");
			$("#modal_body").append("<input type='hidden' id='mail_username' name='mail_username' value='" + $(
				"#mail_username").val() + "' />");
			$("#modal_body").append("<input type='hidden' id='mail_password' name='mail_password' value='" + $(
				"#mail_password").val() + "' />");

			$('#myPopup').modal('show');
		}
	</script>
@endsection
