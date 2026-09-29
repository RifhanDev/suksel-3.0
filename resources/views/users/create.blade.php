@extends('layouts.v3.master')

@section('styles')
	<style>
		.card-form-compact {
			background: white;
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
			/* overflow: hidden; Removed to allow dropdowns to overflow */
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
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pendaftaran Pengguna Baru</h3>
			<p class="text-muted small m-0">Sila lengkapkan maklumat di bawah.</p>
		</div>
	</div>

	<form action="{{ url('users') }}" method="POST" id="createUserForm" novalidate>
		@csrf

		<div class="modern-card">

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
							Tiada kata laluan perlu diisi di sini. Selepas disimpan, emel akan dihantar kepada pengguna
							berdaftar dengan pautan untuk mengesahkan emel dan menetapkan kata laluan. Akaun hanya aktif
							selepas Agensi Admin meluluskan permohonan.
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
					<button type="button" class="btn-form btn-form-primary" id="btnCreateUserConfirm">
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
		</div>
	</form>

	@push('modals')
		<div class="modal fade" id="confirmUserEmailModal" tabindex="-1" aria-labelledby="confirmUserEmailModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content p-4">
					<div class="d-flex align-items-start gap-3 mb-3">
						<div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
							style="width: 44px; height: 44px; background: #fef3c7;">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
								stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
								<polyline points="22,6 12,13 2,6"></polyline>
							</svg>
						</div>
						<div class="flex-grow-1">
							<h5 class="fw-bold mb-2" id="confirmUserEmailModalLabel">Sahkan alamat emel</h5>
							<p class="text-muted small mb-3">
								Emel jemputan (pengesahan emel & tetapan kata laluan) akan dihantar ke alamat di bawah.
								Sila pastikan maklumat betul sebelum meneruskan.
							</p>
							<dl class="mb-0 small">
								<dt class="text-muted fw-normal">Nama</dt>
								<dd class="fw-semibold text-dark mb-2" id="confirmUserEmailName">—</dd>
								<dt class="text-muted fw-normal">Alamat emel</dt>
								<dd class="fw-semibold text-dark mb-2" id="confirmUserEmailAddress">—</dd>
								<dt class="text-muted fw-normal d-none" id="confirmUserAgencyLabel">Agensi</dt>
								<dd class="fw-semibold text-dark mb-0 d-none" id="confirmUserAgencyName">—</dd>
							</dl>
						</div>
					</div>
					<div class="d-flex justify-content-end gap-2 pt-2">
						<button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Semak semula</button>
						<button type="button" class="btn-form btn-form-primary" id="btnCreateUserSubmit">
							Ya, hantar emel &amp; simpan
						</button>
					</div>
				</div>
			</div>
		</div>
	@endpush
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

			const form = document.getElementById('createUserForm');
			const confirmModalEl = document.getElementById('confirmUserEmailModal');
			const confirmModal = confirmModalEl ? bootstrap.Modal.getOrCreateInstance(confirmModalEl) : null;

			function getAgencyLabel() {
				const select = document.getElementById('organization_unit_id');
				if (!select) {
					return '';
				}
				if (select.selectize) {
					const value = select.selectize.getValue();
					if (!value) {
						return '';
					}
					const option = select.selectize.options[value];
					return option ? option.text : '';
				}
				const option = select.options[select.selectedIndex];
				return option ? option.text.trim() : '';
			}

			document.getElementById('btnCreateUserConfirm')?.addEventListener('click', function() {
				if (!form.reportValidity()) {
					return;
				}

				const name = (document.getElementById('name')?.value || '').trim();
				const email = (document.getElementById('email')?.value || '').trim();
				const agency = getAgencyLabel();

				document.getElementById('confirmUserEmailName').textContent = name || '—';
				document.getElementById('confirmUserEmailAddress').textContent = email || '—';

				const agencyLabel = document.getElementById('confirmUserAgencyLabel');
				const agencyName = document.getElementById('confirmUserAgencyName');
				if (agency) {
					agencyLabel.classList.remove('d-none');
					agencyName.classList.remove('d-none');
					agencyName.textContent = agency;
				} else {
					agencyLabel.classList.add('d-none');
					agencyName.classList.add('d-none');
				}

				confirmModal?.show();
			});

			document.getElementById('btnCreateUserSubmit')?.addEventListener('click', function() {
				confirmModal?.hide();
				form.submit();
			});
		});
	</script>
@endsection
