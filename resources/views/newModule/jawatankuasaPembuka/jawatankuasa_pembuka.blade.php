@extends('layouts.v3.master')

@section('content')
<style>
	/* ================================
			TENDER LIST HEADER
================================ */
	.modal-table-header {
		background-color: #1e293b;
		color: white;
	}

	.modal-table-header th {
		font-weight: 600;
		text-transform: uppercase;
		font-size: 0.75rem;
		letter-spacing: 0.5px;
		padding: 1rem 0.75rem !important;
	}

	.status-badge.status-success {
		background-color: #f0fdf4;
		color: #166534;
		border: 1px solid #bbf7d0;
	}

	.card-header-v3 {
		background: #1e293b;
		color: #fff;
		text-align: center;
		vertical-align: middle;
	}

	.table-header {
		background: #1e293b;
		color: #fff;
		text-align: center;
		vertical-align: middle;
	}

	.table thead th {
		font-weight: 600;
		text-transform: uppercase;
		font-size: 0.75rem;
		letter-spacing: 0.025em;
		border-top: none;
	}

	.table tbody td {
		vertical-align: middle;
		color: #475569;
		font-size: 0.85rem;
	}

	/* Status Badge Styles */
	.status-badge {
		padding: 0.35em 0.65em;
		font-size: 0.75em;
		font-weight: 600;
		border-radius: 50rem;
	}

	.status-pending {
		background-color: #fef3c7;
		color: #92400e;
	}

	.status-completed {
		background-color: #dcfce7;
		color: #166534;
	}

	/* DataTables Customization */
	.dataTables_wrapper .dataTables_length, 
	.dataTables_wrapper .dataTables_filter {
		margin-bottom: 1rem;
		font-size: 0.8rem;
	}

	.dataTables_wrapper .dataTables_info, 
	.dataTables_wrapper .dataTables_paginate {
		margin-top: 1rem;
		font-size: 0.8rem;
	}

	/* ================================
			STEPPER DESIGN (WORKFLOW)
================================ */
	:root {
		--sg-red: #C81E1E;
		--sg-red-dark: #A4161A;
		--topbar-border: #E5E7EB;
		--topbar-text: #374151;
	}

	.progress-wrapper {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		position: relative;
	}

	/* Each step */
	.progress-step {
		flex: 1;
		text-align: center;
		position: relative;
		cursor: pointer;
	}

	/* Connector line */
	.progress-step:not(:last-child)::after {
		content: '';
		position: absolute;
		top: 18px;
		/* center of 36px circle */
		left: 50%;
		width: 100%;
		height: 3px;
		background: var(--topbar-border);
		z-index: 0;
	}

	/* Active & completed line */
	.progress-step.active:not(:last-child)::after,
	.progress-step.done:not(:last-child)::after {
		background: var(--sg-red);
	}

	/* Reset future steps line */
	.progress-step.active~.progress-step:not(:last-child)::after {
		background: var(--topbar-border);
	}

	/* Step circle */
	.step-number {
		width: 36px;
		height: 36px;
		border-radius: 50%;
		background: var(--topbar-border);
		color: var(--topbar-text);
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto;
		font-weight: 600;
		position: relative;
		z-index: 2;
	}

	/* Active & done circle */
	.progress-step.active .step-number,
	.progress-step.done .step-number {
		background: var(--sg-red);
		color: #fff;
	}

	/* Label */
	.step-label {
		margin-top: 8px;
		font-size: 13px;
		color: var(--topbar-text);
		font-weight: 500;
	}

	/* Active & done label */
	.progress-step.active .step-label,
	.progress-step.done .step-label {
		color: var(--sg-red-dark);
		font-weight: 600;
	}

	.step-content-item {
		transition: all 0.3s ease-in-out;
	}

	.btn-success {
		background-color: #10b981 !important;
		border-color: #10b981 !important;
		color: #fff !important;
	}

	.btn-success:hover {
		background-color: #059669 !important;
		border-color: #059669 !important;
	}

	/* ================================
			GENERAL UI
================================ */
	.section-title {
		font-weight: bold;
		margin: 10px 0 10px;
	}

</style>

<div class="card border shadow-sm mb-2 rounded-3">
	<div class="card-body p-3">
		<div class="row g-2 align-items-end">
			<div class="col-4 col-lg-4">
				<label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
				<h6 class="text-primary">SUKSEL/PERT/2026/001</h6>
				<!-- <input type="text" id="" class="form-control form-control-sm" placeholder=""> -->
			</div>
			<div class="col-4 col-lg-4">
				<label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
				<h6 class="text-primary">100-007</h6>
			</div>
			<div class="col-4 col-lg-4">
				<label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
				<span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase heartbeat" style="font-size: 0.8rem;">
					Dalam Proses
				</span>
			</div>
		</div>
	</div>
</div>

<div class="card border shadow-sm rounded-3 mt-3">
	<div class="card-body p-4">
		<!-- Stepper Navigation -->
		<div class="progress-wrapper mb-4">
			<div class="progress-step active" id="step-nav-1" onclick="goToStep(1)">
				<div class="step-number">1</div>
				<div class="step-label text-uppercase">Peringkat Pematuhan Teknikal</div>
			</div>
			<div class="progress-step" id="step-nav-2" onclick="goToStep(2)">
				<div class="step-number">2</div>
				<div class="step-label text-uppercase">Peringkat Pematuhan Kewangan</div>
			</div>
			<div class="progress-step" id="step-nav-3" onclick="goToStep(3)">
				<div class="step-number">3</div>
				<div class="step-label text-uppercase">Rumusan</div>
			</div>
		</div>

		<hr class="my-4 opacity-25">

		<!-- Step Content Sections -->
		<div id="step-content-1" class="step-content-item">
			<div class="d-flex align-items-center mb-4">
				<div class="bg-primary-subtle p-2 rounded-2 me-3">
					<i class="bi bi-file-earmark-check text-primary fs-4"></i>
				</div>
				<div>
					<h5 class="fw-bold mb-0">Pematuhan Cadangan Teknikal</h5>
					<p class="text-secondary small mb-0">Sila semak dan sahkan pematuhan teknikal bagi setiap pembekal.</p>
				</div>
			</div>
			
			<div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
				<i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
				<div>
					<span class="small fw-medium text-info-emphasis">Informasi:</span>
					<p class="mb-0 small">Klik butang Semak untuk meneruskan penilaian pematuhan</p>
				</div>
			</div>

			<table id="tableTeknikal" class="table table-hover table-striped border shadow-sm rounded-3">
				<thead class="table-header">
					<tr>
						<th class="text-center" style="width: 50px;">Bil</th>
						<th>Tajuk / Dokumen</th>
						<th class="text-center">Mekanisma</th>
						<th class="text-center">Status Penilaian</th>
						<th class="text-center" style="width: 100px;">Tindakan</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">1</td>
						<td class="fw-medium">Penilaian Forensik Sistem XXXX</td>
						<td class="text-center"><span class="badge bg-light text-dark border">Spesifikasi</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Penilaian Forensik Sistem XXXX">
								Semak
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-center">2</td>
						<td class="fw-medium">Surat Pengesahan Prinsipal</td>
						<td class="text-center"><span class="badge bg-light text-dark border">Petender Muat Naik</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Surat Pengesahan Prinsipal">
								Semak
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-center">3</td>
						<td class="fw-medium">Senarai Kakitangan Teknikal</td>
						<td class="text-center"><span class="badge bg-light text-dark border">Petender Muat Naik</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Senarai Kakitangan Teknikal">
								Semak
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="step-content-2" class="step-content-item d-none">
			<div class="d-flex align-items-center mb-4">
				<div class="bg-primary-subtle p-2 rounded-2 me-3">
					<i class="bi bi-cash-coin text-primary fs-4"></i>
				</div>
				<div>
					<h5 class="fw-bold mb-0">Peringkat Pematuhan Kewangan</h5>
					<p class="text-secondary small mb-0">Sila semak dan sahkan pematuhan kewangan bagi setiap pembekal.</p>
				</div>
			</div>

			<!-- <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
				<i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
				<div>
					<span class="small fw-medium text-info-emphasis">Informasi:</span>
					<p class="mb-0 small">Kandungan bagi peringkat pematuhan kewangan akan dipaparkan di sini.</p>
				</div>
			</div> -->

			<table id="tableKewangan" class="table table-hover table-striped border shadow-sm rounded-3">
				<thead class="table-header">
					<tr>
						<th class="text-center" style="width: 50px;">Bil</th>
						<th>Tajuk / Dokumen</th>
						<th class="text-center">Mekanisma</th>
						<th class="text-center">Status Penilaian</th>
						<th class="text-center" style="width: 100px;">Tindakan</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">1</td>
						<td class="fw-medium">Maklumat Profil Petender</td>
						<td class="text-center"><span class="badge bg-light text-dark border">Borang Atas Talian</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Maklumat Profil Petender">
								Semak
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-center">2</td>
						<td class="fw-medium">Penyata Bank (3 Bulan)</td>
						<td class="text-center"><span class="badge bg-light text-dark border">Borang Atas Talian</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Penyata Bank (3 Bulan)">
								Semak
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-center">3</td>
						<td class="fw-medium">Sijil Pendaftaran MOF</td>
						<td class="text-center"><span class="badge bg-light text-dark border">Petender Muat Naik</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Sijil Pendaftaran MOF">
								Semak
							</button>
						</td>
					</tr>
					<tr>
						<td class="text-center">4</td>
						<td class="fw-medium">Surat Akuan Pembida</td>
						<td class="text-center"><span class="badge bg-light text-dark border">PTJ Muat Naik</span></td>
						<td class="text-center">
							<span class="status-badge status-pending">
								<i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan
							</span>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-semak" data-title="Surat Akuan Pembida">
								Semak
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="step-content-3" class="step-content-item d-none">
			<div class="d-flex align-items-center mb-4">
				<div class="bg-primary-subtle p-2 rounded-2 me-3">
					<i class="bi bi-clipboard-data text-primary fs-4"></i>
				</div>
				<div>
					<h5 class="fw-bold mb-0">Rumusan</h5>
					<p class="text-secondary small mb-0">Rumusan keseluruhan bagi penilaian jawatankuasa pembuka.</p>
				</div>
			</div>

			<!-- <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
				<i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
				<div>
					<span class="small fw-medium text-info-emphasis">Informasi:</span>
					<p class="mb-0 small">Kandungan rumusan akan dipaparkan di sini.</p>
				</div>
			</div> -->

			<table id="tableRumusan" class="table table-hover border shadow-sm rounded-3">
				<thead class="table-header">
					<tr>
						<th class="text-center" style="width: 80px;">Bil</th>
						<th>Nama Syarikat</th>
						<th class="text-center" style="width: 200px;">Taraf Bumiputera</th>
						<th class="text-center" style="width: 250px;">Harga Tawaran (RM)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">1 / 2</td>
						<td class="fw-bold text-primary">Syarikat A</td>
						<td class="text-center">
							<select class="form-select form-select-sm shadow-none">
								<option selected>Ya</option>
								<option>Tidak</option>
							</select>
						</td>
						<td>
							<div class="input-group input-group-sm">
								<span class="input-group-text bg-light">RM</span>
								<input type="text" class="form-control text-end fw-bold" placeholder="0.00">
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-center">2 / 2</td>
						<td class="fw-bold text-primary">Syarikat B</td>
						<td class="text-center">
							<select class="form-select form-select-sm shadow-none">
								<option>Ya</option>
								<option selected>Tidak</option>
							</select>
						</td>
						<td>
							<div class="input-group input-group-sm">
								<span class="input-group-text bg-light">RM</span>
								<input type="text" class="form-control text-end fw-bold" placeholder="0.00">
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="card bg-light border-0 shadow-none mt-4 rounded-3">
				<div class="card-body p-3">
					<h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check me-2 text-primary"></i>Pengesahan Akhir</h6>
					<div class="form-check mb-2">
						<input class="form-check-input" type="radio" name="rumusan" id="rumusan1">
						<label class="form-check-label small fw-medium" for="rumusan1">
							Saya mengesahkan petender perlu melalui proses <span class="text-danger fw-bold">Cut-Off</span>
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="rumusan" id="rumusan2">
						<label class="form-check-label small fw-medium" for="rumusan2">
							Saya mengesahkan semua petender disemak dan <span class="text-success fw-bold">layak dinilai</span>
						</label>
					</div>
				</div>
			</div>

			<div class="d-flex align-items-center mb-3 mt-5">
				<div class="bg-danger-subtle p-2 rounded-2 me-3">
					<i class="bi bi-exclamation-triangle text-danger fs-5"></i>
				</div>
				<h5 class="fw-bold mb-0">Senarai Pembekal Tidak Layak</h5>
			</div>

			<table id="tableTidakLayak" class="table table-hover border shadow-sm rounded-3">
				<thead class="table-header bg-danger text-white">
					<tr>
						<th>Nama Syarikat</th>
						<th>Catatan</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="fw-bold text-danger">Syarikat C</td>
						<td>Gagal mengemukakan Penyata Bank bagi bulan Mac 2026.</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Navigation Buttons -->
		<div class="d-flex justify-content-between mt-5 pt-3 border-top">
			<button type="button" class="btn btn-light px-4 fw-bold" id="prevBtn" onclick="prevStep()" disabled>
				<i class="bi bi-arrow-left me-2"></i>Kembali
			</button>
			<button type="button" class="btn btn-danger px-4 fw-bold" id="nextBtn" onclick="nextStep()">
				Seterusnya<i class="bi bi-arrow-right ms-2"></i>
			</button>
		</div>
	</div>
</div>

<!-- Modal Semak Pematuhan -->
<div class="modal fade" id="modalSemak" tabindex="-1" aria-labelledby="modalSemakLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
			<div class="modal-header px-4 pt-4 border-0">
				<h5 class="modal-title fw-bold" id="modalSemakLabel">
					<i class="bi bi-file-earmark-check-fill me-2"></i>Penilaian Pematuhan
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="mb-4 bg-light p-3 rounded-3 border-start border-primary border-4">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk / Dokumen</label>
					<h5 id="modalDocTitle" class="text-dark fw-bold mb-0">-</h5>
				</div>

				<div class="row g-4">
					<div class="col-12">
						<div class="table-responsive border rounded-3">
							<table class="table table-hover align-middle mb-0">
								<thead class="modal-table-header">
									<tr class="text-center text-white">
										<th style="width: 120px;">Kod Pembekal</th>
										<th class="text-start">Dokumen</th>
										<th style="width: 180px;">Status Penyerahan</th>
										<th style="width: 200px;">Status Pematuhan</th>
										<th style="width: 250px;">Catatan</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-center fw-bold">1</td>
										<td>
											<div class="d-flex align-items-center">
												<div class="bg-primary-subtle p-2 rounded-2 me-3">
													<i class="bi bi-file-earmark-pdf-fill text-primary fs-5"></i>
												</div>
												<span class="modal-doc-filename fw-medium text-dark small"></span>
											</div>
										</td>
										<td class="text-center">
											<span class="status-badge status-success py-1 px-3">
												Hantar
											</span>
										</td>
										<td>
											<select class="form-select shadow-none border-2 small">
												<option value="" selected>Ada / Tiada</option>
												<option value="1">Ada</option>
												<option value="0">Tiada</option>
											</select>
										</td>
										<td>
											<textarea class="form-control shadow-none border-2 small" rows="1" placeholder="Masukkan catatan..."></textarea>
										</td>
									</tr>
									<tr>
										<td class="text-center fw-bold">2</td>
										<td>
											<div class="d-flex align-items-center">
												<div class="bg-primary-subtle p-2 rounded-2 me-3">
													<i class="bi bi-file-earmark-pdf-fill text-primary fs-5"></i>
												</div>
												<span class="modal-doc-filename fw-medium text-dark small"></span>
											</div>
										</td>
										<td class="text-center">
											<span class="status-badge status-success py-1 px-3">
												Hantar
											</span>
										</td>
										<td>
											<select class="form-select shadow-none border-2 small">
												<option value="" selected>Ada / Tiada</option>
												<option value="1">Ada</option>
												<option value="0">Tiada</option>
											</select>
										</td>
										<td>
											<textarea class="form-control shadow-none border-2 small" rows="1" placeholder="Masukkan catatan..."></textarea>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="mt-4 pt-3 border-top">
					<div class="alert alert-warning border-0 small mb-0">
						<i class="bi bi-exclamation-circle-fill me-2"></i>
						Sila pastikan maklumat pematuhan adalah tepat sebelum disimpan.
					</div>
				</div>
			</div>
			<div class="modal-footer bg-light border-0 px-4 py-3">
				<button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-success px-4 fw-bold" id="btnSavePematuhan">
					<i class="bi bi-save me-2"></i>Simpan
				</button>
			</div>
		</div>
	</div>
</div>



@push('scripts')
<script>
	let currentStep = 1;
	const totalSteps = 3;
	let dataTables = {};

	function updateStepperUI() {
		// Update Nav Steps
		for (let i = 1; i <= totalSteps; i++) {
			const stepNav = document.getElementById(`step-nav-${i}`);
			const stepContent = document.getElementById(`step-content-${i}`);
			
			if (i < currentStep) {
				stepNav.classList.add('done');
				stepNav.classList.remove('active');
				stepContent.classList.add('d-none');
			} else if (i === currentStep) {
				stepNav.classList.add('active');
				stepNav.classList.remove('done');
				stepContent.classList.remove('d-none');
				
				// Adjust DataTables columns when visible
				setTimeout(() => {
					if (i === 1 && dataTables.teknikal) dataTables.teknikal.columns.adjust().draw();
					if (i === 2 && dataTables.kewangan) dataTables.kewangan.columns.adjust().draw();
					if (i === 3) {
						if (dataTables.rumusan) dataTables.rumusan.columns.adjust().draw();
						if (dataTables.tidakLayak) dataTables.tidakLayak.columns.adjust().draw();
					}
				}, 100);
			} else {
				stepNav.classList.remove('active', 'done');
				stepContent.classList.add('d-none');
			}
		}

		// Update Buttons
		const prevBtn = document.getElementById('prevBtn');
		const nextBtn = document.getElementById('nextBtn');

		if (prevBtn) prevBtn.disabled = (currentStep === 1);
		
		if (nextBtn) {
			if (currentStep === totalSteps) {
				nextBtn.innerHTML = 'Selesai<i class="bi bi-check-all ms-2"></i>';
				nextBtn.classList.replace('btn-danger', 'btn-success');
			} else {
				nextBtn.innerHTML = 'Seterusnya<i class="bi bi-arrow-right ms-2"></i>';
				nextBtn.classList.replace('btn-success', 'btn-danger');
			}
		}
	}

	$(document).ready(function() {
		// Initialize DataTables
		const dtOptions = {
			paging: true,
			searching: false,
			ordering: false,
			info: true,
			lengthChange:false,
			responsive: true,
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Carian...",
				lengthMenu: "Papar _MENU_ rekod",
				info: "Memaparkan _START_ hingga _END_ daripada _TOTAL_ rekod",
				paginate: {
					first: "Pertama",
					last: "Terakhir",
					next: "Seterusnya",
					previous: "Sebelumnya"
				}
			}
		};

		dataTables.teknikal = $('#tableTeknikal').DataTable(dtOptions);
		dataTables.kewangan = $('#tableKewangan').DataTable(dtOptions);
		
		dataTables.rumusan = $('#tableRumusan').DataTable({
			...dtOptions,
			searching: false,
			paging: false,
			info: false
		});

		dataTables.tidakLayak = $('#tableTidakLayak').DataTable({
			...dtOptions,
			searching: false,
			paging: false,
			info: false
		});

		// Handle Modal Semak
		$(document).on('click', '.btn-semak', function() {
			const title = $(this).data('title');
			$('#modalDocTitle').text(title);
			$('.modal-doc-filename').text(title + '.pdf');
			
			// Use Bootstrap 5 JS API for better compatibility
			const modalEl = document.getElementById('modalSemak');
			const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
			modal.show();
		});

		// Handle Save Pematuhan
		$('#btnSavePematuhan').on('click', function() {
			Swal.fire({
				title: 'Simpan Penilaian?',
				text: "Maklumat pematuhan akan direkodkan ke dalam sistem.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#1e293b',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					const modalEl = document.getElementById('modalSemak');
					const modal = bootstrap.Modal.getInstance(modalEl);
					if (modal) modal.hide();

					Swal.fire({
						title: 'Berjaya!',
						text: 'Penilaian telah berjaya disimpan.',
						icon: 'success',
						confirmButtonColor: '#1e293b'
					});
				}
			});
		});

		updateStepperUI();
	});

	function nextStep() {
		if (currentStep < totalSteps) {
			currentStep++;
			updateStepperUI();
		} else {
			// Handle completion
			Swal.fire({
				title: 'Berjaya!',
				text: 'Penilaian jawatankuasa pembuka telah selesai direkodkan.',
				icon: 'success',
				confirmButtonText: 'OK',
				confirmButtonColor: '#1e293b'
			});
		}
	}

	function prevStep() {
		if (currentStep > 1) {
			currentStep--;
			updateStepperUI();
		}
	}

	function goToStep(step) {
		if (step >= 1 && step <= totalSteps) {
			currentStep = step;
			updateStepperUI();
		}
	}
</script>
@endpush
@endsection
