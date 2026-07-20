@extends('layouts.v3.master')

@section('styles')
	<style>
		/* Selangor Brand Theme Accordion Styling */
		.help-accordion .accordion-item {
			border: 1px solid #e2e8f0;
			border-radius: 12px !important;
			margin-bottom: 1rem;
			overflow: hidden;
			background: #ffffff;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.help-accordion .accordion-item:hover {
			box-shadow: 0 8px 24px rgba(196, 30, 58, 0.04);
			border-color: rgba(196, 30, 58, 0.15);
		}

		.help-accordion .accordion-button {
			background: #ffffff;
			font-family: 'Poppins', sans-serif;
			font-size: 0.95rem;
			font-weight: 600;
			color: #1e293b;
			padding: 1.25rem 1.5rem;
			border: none;
			box-shadow: none;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 12px;
			position: relative;
		}

		.help-accordion .accordion-button::before {
			content: '';
			position: absolute;
			left: 0;
			top: 0;
			bottom: 0;
			width: 0;
			background-color: var(--sg-red, #c41e3a);
			transition: all 0.3s ease;
		}

		.help-accordion .accordion-button:not(.collapsed) {
			background: rgba(196, 30, 58, 0.02);
			color: var(--sg-red, #c41e3a);
			box-shadow: none;
		}

		.help-accordion .accordion-button:not(.collapsed)::before {
			width: 4px;
		}

		.help-accordion .accordion-button:focus {
			box-shadow: none;
			outline: none;
			border-color: transparent;
		}

		.help-accordion .accordion-icon-wrapper {
			width: 28px;
			height: 28px;
			border-radius: 8px;
			background: rgba(196, 30, 58, 0.05);
			color: var(--sg-red, #c41e3a);
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.3s ease;
			flex-shrink: 0;
		}

		.help-accordion .accordion-button:hover .accordion-icon-wrapper {
			background: var(--sg-red, #c41e3a);
			color: #ffffff;
			transform: scale(1.05);
		}

		.help-accordion .accordion-body {
			background: #ffffff;
			padding: 1.5rem;
			color: #475569;
			line-height: 1.7;
			font-size: 0.9rem;
			border-top: 1px solid #f1f5f9;
		}

		.help-action-bar {
			display: flex;
			gap: 8px;
			padding-top: 1rem;
			margin-top: 1rem;
			border-top: 1px dashed #e2e8f0;
		}
	</style>
@endsection

@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Soalan Lazim</h3>
			<p class="text-muted small m-0">Pengurusan bagi senarai soalan lazim sistem.</p>
		</div>
	</div>

	<div class="content-card">
		<div class="content-card-header">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
						stroke-linecap="round" stroke-linejoin="round">
						<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
						<line x1="7" y1="7" x2="7.01" y2="7" />
					</svg>
				</div>
				<h3 class="content-card-title">Senarai Soalan Lazim (Bantuan Untuk Kontraktor)</h3>
			</div>

			<div class="d-flex align-items-center gap-2">
				<a href="{{ asset('helps') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Kembali
				</a>
				<button type="button" class="btn-form btn-form-create" data-bs-toggle="modal" data-bs-target="#tambahSoalanModal">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="12" y1="5" x2="12" y2="19"></line>
						<line x1="5" y1="12" x2="19" y2="12"></line>
					</svg>
					Tambah Soalan Lazim
				</button>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="p-3">
				<div class="accordion help-accordion" id="dummy-accordion">
					<div class="accordion-item">
						<h2 class="accordion-header" id="dummy-title-1">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#dummy-collapse-1" aria-expanded="true" aria-controls="dummy-collapse-1">
								<div class="accordion-icon-wrapper">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"></circle>
										<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
										<line x1="12" y1="17" x2="12.01" y2="17"></line>
									</svg>
								</div>
								Bagaimana cara mendaftar akaun sebagai Kontraktor?
							</button>
						</h2>
						<div id="dummy-collapse-1" class="accordion-collapse collapse show" aria-labelledby="dummy-title-1" data-bs-parent="#dummy-accordion">
							<div class="accordion-body">
								Pendaftaran sebagai Kontraktor boleh dilakukan dengan klik butang <strong>"Daftar"</strong> di halaman log masuk utama. Lengkapkan borang pendaftaran syarikat dengan mengisi maklumat profil SSM, alamat, dan butiran perhubungan pemilik syarikat. Seterusnya, muat naik sijil wajib seperti CIDB dan sijil pengesahan bidang lain yang berkaitan untuk disemak oleh pihak urusetia.
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<h2 class="accordion-header" id="dummy-title-2">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dummy-collapse-2" aria-expanded="false" aria-controls="dummy-collapse-2">
								<div class="accordion-icon-wrapper">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"></circle>
										<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
										<line x1="12" y1="17" x2="12.01" y2="17"></line>
									</svg>
								</div>
								Apakah dokumen sokongan yang wajib dimuat naik semasa pembaharuan sijil?
							</button>
						</h2>
						<div id="dummy-collapse-2" class="accordion-collapse collapse" aria-labelledby="dummy-title-2" data-bs-parent="#dummy-accordion">
							<div class="accordion-body">
								Untuk tujuan pembaharuan profil, anda dikehendaki mengemukakan dokumen sokongan yang disahkan termasuk: 
								<ul>
									<li>Salinan Sijil Pendaftaran CIDB (bagi kontraktor kerja)</li>
									<li>Penyata bank rasmi syarikat bagi 3 bulan terkini</li>
									<li>Sijil Perakuan Pendaftaran Syarikat SSM (Borang 9 / Profil Syarikat Terkini)</li>
									<li>Sijil Akuan Pendaftaran Syarikat Bumiputera (jika berkenaan)</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<h2 class="accordion-header" id="dummy-title-3">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dummy-collapse-3" aria-expanded="false" aria-controls="dummy-collapse-3">
								<div class="accordion-icon-wrapper">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"></circle>
										<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
										<line x1="12" y1="17" x2="12.01" y2="17"></line>
									</svg>
								</div>
								Berapakah yuran langganan tahunan sistem dan saluran pembayaran yang disokong?
							</button>
						</h2>
						<div id="dummy-collapse-3" class="accordion-collapse collapse" aria-labelledby="dummy-title-3" data-bs-parent="#dummy-accordion">
							<div class="accordion-body">
								Yuran langganan akaun Kontraktor adalah sebanyak <strong>RM50.00</strong> setahun. Pembayaran boleh dibuat secara selamat terus di dalam sistem melalui gerbang pembayaran FPX (perbankan internet) atau kad kredit/debit. Setelah transaksi berjaya, resit rasmi sistem akan dikeluarkan serta-merta untuk rujukan anda.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Tambah Soalan Lazim Modal -->
	<div class="modal fade" id="tambahSoalanModal" tabindex="-1" aria-labelledby="tambahSoalanModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
				<div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
					<h5 class="modal-title fw-bold text-dark" id="tambahSoalanModalLabel" style="font-family: 'Poppins', sans-serif;">Tambah Soalan Lazim Baharu</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" style="padding: 24px;">
					<form id="tambahSoalanForm">
						<!-- Kategori Input -->
						<div class="mb-4">
							<label for="faqCategory" class="form-label fw-semibold text-secondary small">Kategori <span class="text-danger">*</span></label>
							<input type="text" class="form-control py-2.5 px-3" id="faqCategory" value="Bantuan Untuk Kontraktor" readonly style="border-radius: 8px; border-color: #cbd5e1; font-size: 0.9rem; background-color: #f8fafc;">
						</div>
						<!-- Soalan Input -->
						<div class="mb-4">
							<label for="faqQuestion" class="form-label fw-semibold text-secondary small">Soalan <span class="text-danger">*</span></label>
							<input type="text" class="form-control py-2.5 px-3" id="faqQuestion" placeholder="Masukkan soalan..." required style="border-radius: 8px; border-color: #cbd5e1; font-size: 0.9rem;">
						</div>
						<!-- Jawapan Textarea -->
						<div class="mb-4">
							<label for="faqAnswer" class="form-label fw-semibold text-secondary small">Jawapan <span class="text-danger">*</span></label>
							<textarea class="form-control px-3 py-2.5" id="faqAnswer" rows="5" placeholder="Masukkan jawapan lengkap..." required style="border-radius: 8px; border-color: #cbd5e1; font-size: 0.9rem; resize: vertical;"></textarea>
						</div>
						
						<!-- Footer Buttons -->
						<div class="d-flex justify-content-end gap-2 pt-3" style="border-top: 1px solid #f1f5f9;">
							<button type="button" class="btn-form btn-form-secondary m-0" data-bs-dismiss="modal">Batal</button>
							<button type="submit" class="btn-form btn-form-primary m-0" style="background: #3b82f6; border-color: #3b82f6;">Hantar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		$('#tambahSoalanForm').on('submit', function(e) {
			e.preventDefault();
			// Purely dummy - nothing happens
		});
	});
</script>
@endsection
