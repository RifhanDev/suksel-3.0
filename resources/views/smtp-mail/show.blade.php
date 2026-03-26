@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Maklumat Email SMTP</h3>
			<p class="text-muted small m-0">Paparan butiran konfigurasi pelayan email SMTP.</p>
		</div>
	</div>

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
					<label for="mail_server" class="form-label fw-medium small">Mail Server</label>
					<input class="form-control" readonly id="mail_server" name="mail_server" type="text"
						value="{{ $data->mail_server }}">
				</div>

				<!-- Mail Port -->
				<div class="col-md-6">
					<label for="mail_port" class="form-label fw-medium small">Mail Port</label>
					<input class="form-control" readonly id="mail_port" name="mail_port" type="number" max="65535"
						value="{{ $data->mail_port }}">
				</div>

				<!-- Mail Encryption -->
				<div class="col-md-6">
					<label for="mail_crypto" class="form-label fw-medium small">Mail Encryption</label>
					<select name="mail_crypto" id="mail_crypto" class="form-select" disabled>
						<option value="0" {{ ($data->mail_crypto ?? 0) == 0 ? 'selected' : '' }}>NONE</option>
						<option value="1" {{ ($data->mail_crypto ?? 0) == 1 ? 'selected' : '' }}>TLS</option>
						<option value="2" {{ ($data->mail_crypto ?? 0) == 2 ? 'selected' : '' }}>SSL</option>
					</select>
				</div>

				<!-- Mail Username -->
				<div class="col-12">
					<label for="mail_username" class="form-label fw-medium small">Mail Username</label>
					<input class="form-control" readonly id="mail_username" name="mail_username" type="text"
						value="{{ $data->mail_username }}">
				</div>

				<!-- Mail Password -->
				<div class="col-12">
					<label for="mail_password" class="form-label fw-medium small">Mail Password</label>
					<input class="form-control" readonly id="mail_password" name="mail_password" type="password"
						value="********">
				</div>

				<!-- Daily Messages Limit -->
				<div class="col-12">
					<label for="mail_message_ratelimit" class="form-label fw-medium small">Daily Messages Limit</label>
					<input class="form-control" readonly id="mail_message_ratelimit" name="mail_message_ratelimit" type="number"
						value="{{ $data->mail_message_ratelimit }}">
				</div>
			</div>
		</div>

		<div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-2 p-4 border-top bg-light">
			<a href="{{ route('mail-manager.smtp-setting.index') }}" class="btn-form btn-form-secondary">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
					fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="19" y1="12" x2="5" y2="12"></line>
					<polyline points="12 19 5 12 12 5"></polyline>
				</svg>
				Batal
			</a>
			<a href="{{ route('mail-manager.smtp-setting.edit', ['smtp_setting' => $data->enc_id]) }}"
				class="btn-form btn-form-primary">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
					fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
					<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
				</svg>
				Kemaskini
			</a>
		</div>
	</div>
@endsection
