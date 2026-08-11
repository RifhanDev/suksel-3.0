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

	/* Compliance evaluation badge states */
	.badge-belum   { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
	.badge-ada     { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
	.badge-tiada   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

{{-- ========================================
     INFO BAR: Tender details
======================================== --}}
<div class="card border shadow-sm mb-2 rounded-3">
	<div class="card-body p-3">
		<div class="row g-2 align-items-end">
			<div class="col-4 col-lg-4">
				<label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
				<h6 class="text-primary mb-0">{{ $tender->no_tender ?: ($tender->ref_number ?: '-') }}</h6>
				<small class="text-muted">{{ \Illuminate\Support\Str::limit($tender->name ?? '-', 80) }}</small>
			</div>
			<div class="col-4 col-lg-4">
				<label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
				<h6 class="text-primary mb-0">{{ $tender->tenderer->name ?? '-' }}</h6>
			</div>
			<div class="col-4 col-lg-4">
				<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
				<span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase" style="font-size: 0.8rem;">
					Dalam Proses
				</span>
				<div class="small text-muted mt-1">{{ count($vendors ?? []) }} petender beli dokumen</div>
			</div>
		</div>
	</div>
</div>

{{-- ========================================
     STEPPER CARD
======================================== --}}
<div class="card border shadow-sm rounded-3 mt-3">
	<div class="card-body p-4">

		{{-- Stepper Navigation --}}
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

		{{-- ===================================================
		     STEP 1: Pematuhan Teknikal
		=================================================== --}}
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

			<style>
				@keyframes alertPopBuzz {
					0%   { transform: scale(0.15); opacity: 0; }
					50%  { transform: scale(1.05); opacity: 1; }
					60%  { transform: scale(1) rotate(0deg); }
					67%  { transform: scale(1) rotate(2deg) translateX(1px); }
					74%  { transform: scale(1) rotate(-2deg) translateX(-1px); }
					81%  { transform: scale(1) rotate(1.5deg) translateX(0.5px); }
					88%  { transform: scale(1) rotate(-1deg) translateX(-0.5px); }
					94%  { transform: scale(1) rotate(0.5deg); }
					100% { transform: scale(1) rotate(0deg) translateX(0); opacity: 1; }
				}
			</style>
			<div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af; animation: alertPopBuzz 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;">
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
					<circle cx="12" cy="12" r="10"></circle>
					<line x1="12" y1="16" x2="12" y2="12"></line>
					<line x1="12" y1="8" x2="12.01" y2="8"></line>
				</svg>
				<div>
					<span class="small fw-medium text-info-emphasis"><strong>Informasi:</strong></span>
					<p class="mb-0 small">Klik butang <strong>Semak</strong> untuk meneruskan penilaian pematuhan. Status Pematuhan wajib diisi untuk setiap vendor.</p>
				</div>
			</div>

			<table id="tableTeknikal" class="table table-hover table-striped border shadow-sm rounded-3">
				<thead class="table-header">
					<tr>
						<th class="text-center" style="width: 50px;">Bil</th>
						<th>Tajuk / Dokumen</th>
						<th class="text-center">Mekanisma</th>
						<th class="text-center">Status Penyerahan</th>
						<th class="text-center" style="width: 100px;">Tindakan</th>
					</tr>
				</thead>
				<tbody>
					@php
						$teknikalItemsFiltered = collect($teknikalItems ?? [])->filter(fn ($item) => strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? '')) !== 'muat turun')->values()->all();
					@endphp
					@forelse ($teknikalItemsFiltered as $i => $item)
						@php
							$uuid        = $item['uuid'] ?? '';
							$payload     = $semakPayload[$uuid] ?? null;
							$submitted   = (int) ($payload['submitted_count'] ?? 0);
							$totalVendors = (int) ($payload['vendor_count'] ?? count($vendors ?? []));
						@endphp
						<tr>
							<td class="text-center">{{ $i + 1 }}</td>
							<td class="fw-medium">{{ $item['title'] ?? $item['nama'] ?? '-' }}</td>
							<td class="text-center">
								<span class="badge bg-light text-dark border">{{ $item['tindakan'] ?? '-' }}</span>
							</td>
							<td class="text-center">
								@if ($totalVendors === 0)
									<span class="status-badge status-pending"><i class="bi bi-people me-1"></i>Tiada petender</span>
								@elseif ($submitted >= $totalVendors && $totalVendors > 0)
									<span class="status-badge status-completed"><i class="bi bi-check-circle me-1"></i>{{ $submitted }} / {{ $totalVendors }} dihantar</span>
								@elseif ($submitted > 0)
									<span class="status-badge status-pending"><i class="bi bi-hourglass-split me-1"></i>{{ $submitted }} / {{ $totalVendors }} dihantar</span>
								@else
									<span class="status-badge status-pending"><i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan</span>
								@endif
							</td>
							<td class="text-center">
								<button type="button"
									class="btn btn-danger btn-sm rounded-pill px-3 btn-semak"
									data-item-uuid="{{ $uuid }}"
									data-title="{{ $item['title'] ?? $item['nama'] ?? '-' }}">
									Semak
								</button>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center text-muted py-4">Tiada dokumen teknikal untuk tender ini.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- ===================================================
		     STEP 2: Pematuhan Kewangan
		=================================================== --}}
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

			<table id="tableKewangan" class="table table-hover table-striped border shadow-sm rounded-3">
				<thead class="table-header">
					<tr>
						<th class="text-center" style="width: 50px;">Bil</th>
						<th>Tajuk / Dokumen</th>
						<th class="text-center">Mekanisma</th>
						<th class="text-center">Status Penyerahan</th>
						<th class="text-center" style="width: 100px;">Tindakan</th>
					</tr>
				</thead>
				<tbody>
					@php
						$kewanganItemsFiltered = collect($kewanganItems ?? [])->filter(function ($item) {
							$tindakan   = strtolower(trim($item['tindakan'] ?? ''));
							$mekanisma  = strtolower(trim($item['mechanism'] ?? $item['mekanisma'] ?? ''));
							$sourceType = strtolower(trim($item['source_type'] ?? ''));

							if ($tindakan === 'muat turun' || $mekanisma === 'muat turun') {
								return false;
							}

							if ($tindakan === 'spesifikasi' || $mekanisma === 'spesifikasi' || in_array($sourceType, ['specification', 'specification_document'], true)) {
								return false;
							}

							return true;
						})->values()->all();
					@endphp
					@forelse ($kewanganItemsFiltered as $i => $item)
						@php
							$uuid         = $item['uuid'] ?? '';
							$payload      = $semakPayload[$uuid] ?? null;
							$submitted    = (int) ($payload['submitted_count'] ?? 0);
							$totalVendors = (int) ($payload['vendor_count'] ?? count($vendors ?? []));
						@endphp
						<tr>
							<td class="text-center">{{ $i + 1 }}</td>
							<td class="fw-medium">{{ $item['title'] ?? $item['nama'] ?? '-' }}</td>
							<td class="text-center">
								<span class="badge bg-light text-dark border">{{ $item['tindakan'] ?? '-' }}</span>
							</td>
							<td class="text-center">
								@if ($totalVendors === 0)
									<span class="status-badge status-pending"><i class="bi bi-people me-1"></i>Tiada petender</span>
								@elseif ($submitted >= $totalVendors && $totalVendors > 0)
									<span class="status-badge status-completed"><i class="bi bi-check-circle me-1"></i>{{ $submitted }} / {{ $totalVendors }} dihantar</span>
								@elseif ($submitted > 0)
									<span class="status-badge status-pending"><i class="bi bi-hourglass-split me-1"></i>{{ $submitted }} / {{ $totalVendors }} dihantar</span>
								@else
									<span class="status-badge status-pending"><i class="bi bi-clock-history me-1"></i>Menunggu Penyerahan</span>
								@endif
							</td>
							<td class="text-center">
								<button type="button"
									class="btn btn-danger btn-sm rounded-pill px-3 btn-semak"
									data-item-uuid="{{ $uuid }}"
									data-title="{{ $item['title'] ?? $item['nama'] ?? '-' }}">
									Semak
								</button>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center text-muted py-4">Tiada dokumen kewangan untuk tender ini.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- ===================================================
		     STEP 3: Rumusan
		=================================================== --}}
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

			{{-- Loading indicator --}}
			<div id="rumusan-loading" class="text-center py-5">
				<div class="spinner-border text-primary" role="status"></div>
				<p class="text-muted mt-2 small">Mengira kelayakan petender...</p>
			</div>

			{{-- Rumusan content (rendered after AJAX load) --}}
			<div id="rumusan-content" class="d-none">

				{{-- Senarai Layak --}}
				<div class="mb-2 mt-2">
					<h6 class="fw-bold text-dark mb-0"><i class="bi bi-check-circle text-success me-2"></i>Senarai Pembekal Layak</h6>
					<div class="small text-muted mt-1" id="totalLayakText">0 pembekal layak</div>
				</div>
				<table id="tableRumusan" class="table table-hover border shadow-sm rounded-3 mb-4">
					<thead class="table-header">
						<tr>
							<th class="text-center" style="width: 80px;">Bil</th>
							<th>Nama Syarikat</th>
							<th class="text-center" style="width: 200px;">Taraf Bumiputera</th>
							<th class="text-center" style="width: 250px;">Harga Tawaran (RM)</th>
						</tr>
					</thead>
					<tbody id="tableRumusanBody">
						<tr><td colspan="4" class="text-center text-muted py-3">Memuatkan...</td></tr>
					</tbody>
				</table>

				{{-- Pengesahan Akhir --}}
				<div class="card bg-light border-0 shadow-none mt-3 rounded-3">
					<div class="card-body p-3">
						<h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check me-2 text-primary"></i>Pengesahan Akhir</h6>
						<div class="form-check mb-2">
							<input class="form-check-input" type="checkbox" id="pengesahan_cutoff" name="pengesahan_cutoff">
							<label class="form-check-label small fw-medium" for="pengesahan_cutoff">
								Saya mengesahkan petender perlu melalui proses <span class="text-danger fw-bold">Cut-Off</span>
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="pengesahan_layak" name="pengesahan_layak">
							<label class="form-check-label small fw-medium" for="pengesahan_layak">
								Saya mengesahkan semua petender disemak dan <span class="text-success fw-bold">layak dinilai</span>
							</label>
						</div>
					</div>
				</div>

				{{-- Senarai Tidak Layak --}}
				<!-- <div class="d-flex align-items-center mb-3 mt-5">
					<div class="bg-danger-subtle p-2 rounded-2 me-3">
						<i class="bi bi-exclamation-triangle text-danger fs-5"></i>
					</div>
					<div>
						<h5 class="fw-bold mb-0">Senarai Pembekal Tidak Layak</h5>
						<div class="small text-muted mt-1" id="totalTidakLayakText">0 pembekal tidak layak</div>
					</div>
				</div> -->
				<div class="mb-2 mt-4">
					<h6 class="fw-bold text-dark mb-0"><i class="bi bi-exclamation-circle text-danger me-2"></i>Senarai Pembekal Tidak Layak</h6>
					<div class="small text-muted mt-1" id="totalTidakLayakText">0 pembekal layak</div>
				</div>

				<table id="tableTidakLayak" class="table table-hover border shadow-sm rounded-3">
					<thead class="table-header bg-danger text-white">
						<tr>
							<th>Nama Syarikat</th>
							<th>Sebab Tidak Layak</th>
						</tr>
					</thead>
					<tbody id="tableTidakLayakBody">
						<tr><td colspan="2" class="text-center text-muted py-3">Tiada pembekal tidak layak.</td></tr>
					</tbody>
				</table>

			</div>{{-- /rumusan-content --}}
		</div>

		{{-- Navigation Buttons --}}
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

{{-- ========================================
     MODAL: Semak Pematuhan
======================================== --}}
<div class="modal fade" id="modalSemak" tabindex="-1" aria-labelledby="modalSemakLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content border-0 shadow-lg rounded-3">
			<div class="modal-header px-4 pt-4 border-0">
				<div class="d-flex align-items-center rounded-3 mt-3">
					<div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background: #dbeafe;">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
							<line x1="16" y1="13" x2="8" y2="13"></line>
							<line x1="16" y1="17" x2="8" y2="17"></line>
						</svg>
					</div>
					<div class="flex-shrink-0">
						<span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Tajuk / Dokumen</span>
						<h6 id="modalDocTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">-</h6>
					</div>
					<div class="mx-3 align-self-stretch" style="width: 1px; background: #d1d5db;"></div>
					<span class="text-secondary" style="font-size: 0.78rem;">Senarai dokumen yang perlu dikemukakan oleh petender.</span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="table-responsive rounded-3" style="border: 1px solid #e5e7eb;">
					<table class="table align-middle mb-0" style="font-size: 0.85rem;">
						<thead>
							<tr>
								<th class="text-center text-uppercase fw-bold py-2" style="width: 120px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9ff; color: #3f3f3fff;">Kod Pembekal</th>
								<th class="text-center text-uppercase fw-bold py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9ff; color: #3f3f3fff;">Dokumen</th>
								<th class="text-center text-uppercase fw-bold py-2" style="width: 160px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9ff; color: #3f3f3fff;">Status Penyerahan</th>
								<th class="text-center text-uppercase fw-bold py-2" style="width: 180px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9ff; color: #3f3f3fff;">Status Pematuhan</th>
								<th class="text-center text-uppercase fw-bold py-2" style="width: 220px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9ff; color: #3f3f3fff;">Catatan</th>
							</tr>
						</thead>
						<tbody id="modalSemakBody">
							<tr>
								<td colspan="5" class="text-center text-muted py-4">Pilih dokumen untuk semakan.</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mt-3" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
						<circle cx="12" cy="12" r="10"></circle>
						<line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
						<line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
					</svg>
					Sila pastikan maklumat pematuhan adalah tepat sebelum disimpan. <strong class="ms-1">Catatan wajib diisi jika Status Pematuhan = Tiada.</strong>
				</div>
			</div>
			<div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
				<button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-sm btn-success px-4 fw-bold" id="btnSavePematuhan">
					<i class="bi bi-save me-2"></i>Simpan Penilaian
				</button>
			</div>
		</div>
	</div>
</div>

{{-- ========================================
     MODAL: Prebiu Dokumen/Borang
======================================== --}}
<div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90%; height: 90vh;">
		<div class="modal-content h-100 border-0 shadow-lg rounded-3">
			<div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					<div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background-color: #e0f2fe;">
						<i class="bi bi-file-earmark-pdf text-primary fs-5" id="previewIcon"></i>
					</div>
					<div>
						<span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Prebiu Dokumen</span>
						<h6 id="modalPreviewTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">-</h6>
					</div>
				</div>
				<div class="d-flex align-items-center gap-2">
					<a id="btnNewTabPreview" href="#" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
						<i class="bi bi-box-arrow-up-right"></i> <span class="d-none d-sm-inline">Buka di Tab Baru</span>
					</a>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
			<div class="modal-body p-0 bg-light position-relative d-flex align-items-center justify-content-center" style="height: calc(100% - 75px); overflow: hidden;">
				<div id="previewSpinner" class="spinner-border text-primary position-absolute" role="status" style="z-index: 10; width: 3rem; height: 3rem;">
					<span class="visually-hidden">Memuatkan...</span>
				</div>
				<iframe id="previewIframe" src="" class="w-100 h-100 border-0 d-none" style="background: white;"></iframe>
				<div id="previewImageWrapper" class="w-100 h-100 d-none overflow-auto p-3 text-center">
					<img id="previewImage" src="" class="img-fluid rounded shadow-sm" style="max-height: 100%; object-fit: contain;" />
				</div>
				<div id="previewFallback" class="text-center p-4 d-none">
					<div class="bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
						<i class="bi bi-file-earmark-zip fs-1"></i>
					</div>
					<h5 class="fw-bold text-dark">Prebiu tidak disokong</h5>
					<p class="text-muted small mx-auto" style="max-width: 400px;">Format fail ini tidak menyokong paparan terus. Sila klik butang di bawah untuk memuat turun.</p>
					<a id="btnFallbackDownload" href="#" target="_blank" class="btn btn-primary px-4 fw-bold mt-2">
						<i class="bi bi-download me-2"></i>Muat Turun Fail
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	// ─────────────────────────────────────────────────────────────────
	// CONSTANTS (from Blade)
	// ─────────────────────────────────────────────────────────────────
	const TENDER_IDENTIFIER = '{{ $tender->uuid }}';
	const SEMAK_PAYLOAD     = @json($semakPayload ?? []);
	const SIMPAN_URL        = '{{ route('jawatankuasaPembuka.simpanPematuhan') }}';
	const RUMUSAN_URL       = '{{ route('jawatankuasaPembuka.rumusanData') }}';
	const HANTAR_URL        = '{{ route('jawatankuasaPembuka.hantar') }}';
	const CSRF_TOKEN        = '{{ csrf_token() }}';

	// ─────────────────────────────────────────────────────────────────
	// STEPPER STATE
	// ─────────────────────────────────────────────────────────────────
	let currentStep  = 1;
	const totalSteps = 3;
	let dataTables   = {};

	// Track the UUID that is currently open in the modal
	let activeItemUuid = null;

	// ─────────────────────────────────────────────────────────────────
	// STEPPER UI
	// ─────────────────────────────────────────────────────────────────
	function updateStepperUI() {
		for (let i = 1; i <= totalSteps; i++) {
			const stepNav     = document.getElementById(`step-nav-${i}`);
			const stepContent = document.getElementById(`step-content-${i}`);

			if (i < currentStep) {
				stepNav.classList.add('done');
				stepNav.classList.remove('active');
				stepContent.classList.add('d-none');
			} else if (i === currentStep) {
				stepNav.classList.add('active');
				stepNav.classList.remove('done');
				stepContent.classList.remove('d-none');

				setTimeout(() => {
					if (i === 1 && dataTables.teknikal) dataTables.teknikal.columns.adjust().draw();
					if (i === 2 && dataTables.kewangan) dataTables.kewangan.columns.adjust().draw();
					if (i === 3) {
						loadRumusanData();
					}
				}, 100);
			} else {
				stepNav.classList.remove('active', 'done');
				stepContent.classList.add('d-none');
			}
		}

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

	function nextStep() {
		if (currentStep < totalSteps) {
			currentStep++;
			updateStepperUI();
		} else {
			submitSelesai();
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

	// ─────────────────────────────────────────────────────────────────
	// MODAL SEMAK – Render rows
	// ─────────────────────────────────────────────────────────────────
	function escapeHtml(value) {
		return String(value || '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function renderSemakRows(item) {
		const $body = $('#modalSemakBody');
		$body.empty();

		const vendors = item?.vendors || [];
		if (!vendors.length) {
			$body.append('<tr><td colspan="5" class="text-center text-muted py-4">Tiada petender yang membeli dokumen.</td></tr>');
			return;
		}

		vendors.forEach(function (vendor) {
			const statusClass = vendor.status === 'submitted' ? 'status-success' : 'status-pending';

			// Document/File column
			let docHtml = '<div class="small text-muted">' + escapeHtml(vendor.summary || '-') + '</div>';
			if (Array.isArray(vendor.files) && vendor.files.length) {
				docHtml = vendor.files.map(function (file) {
					return '<a href="' + escapeHtml(file.url) + '" data-name="' + escapeHtml(file.name) + '" class="d-block small text-primary btn-preview-file">' +
						'<i class="bi bi-file-earmark-arrow-down me-1"></i>' + escapeHtml(file.name) +
						'</a>';
				}).join('');
			} else if (vendor.form_url) {
				const isSpec = (item?.action === 'view_specification');
				const label  = isSpec ? 'Buka spesifikasi' : 'Buka borang';
				const icon   = isSpec ? 'bi bi-file-earmark-text' : 'bi bi-window';
				docHtml += '<div class="mt-1"><a href="' + escapeHtml(vendor.form_url) + '" data-name="' + (isSpec ? 'Spesifikasi ' : 'Borang ') + escapeHtml(vendor.name) + '" class="small btn-preview-file"><i class="' + icon + ' me-1"></i>' + label + '</a></div>';
			}

			// Status Pematuhan – pre-fill from saved evaluation
			const savedStatus  = vendor.status_pematuhan; // null = belum semak, 1 = Ada, 0 = Tiada
			const savedCatatan = vendor.catatan || '';

			const selectHtml =
				'<select class="form-select shadow-none border-2 small semak-pematuhan" data-vendor-id="' + vendor.vendor_id + '">' +
					'<option value="" ' + (savedStatus === null || savedStatus === undefined ? 'selected' : '') + ' disabled>-- Pilih --</option>' +
					'<option value="1" ' + (savedStatus === 1 ? 'selected' : '') + '>Ada</option>' +
					'<option value="0" ' + (savedStatus === 0 ? 'selected' : '') + '>Tiada</option>' +
				'</select>';

			const catatanHtml =
				'<textarea class="form-control shadow-none border-2 small semak-catatan" rows="2" placeholder="Catatan...">' +
				escapeHtml(savedCatatan) + '</textarea>';

			const kodDisplay = vendor.kod
				? escapeHtml(vendor.kod)
				: '<span class="fst-italic small text-muted">Kod Pembekal Belum Dijana</span>';

			$body.append(
				'<tr>' +
					'<td class="text-center fw-bold" style="background-color: #efeff0ff; color: #3f3f3fff;" data-vendor-id="' + vendor.vendor_id + '">' + kodDisplay + '</td>' +
					'<td>' +
						'<div class="fw-semibold text-dark small mb-1">' + escapeHtml(vendor.name) + '</div>' +
						docHtml +
					'</td>' +
					'<td class="text-center"><span class="status-badge ' + statusClass + ' py-1 px-3">' + escapeHtml(vendor.status_label) + '</span></td>' +
					'<td>' + selectHtml + '</td>' +
					'<td>' + catatanHtml + '</td>' +
				'</tr>'
			);
		});
	}

	// ─────────────────────────────────────────────────────────────────
	// MODAL SEMAK – Open
	// ─────────────────────────────────────────────────────────────────
	$(document).on('click', '.btn-semak', function () {
		const title    = $(this).data('title');
		activeItemUuid = String($(this).data('item-uuid') || '');
		const item     = SEMAK_PAYLOAD[activeItemUuid] || null;

		$('#modalDocTitle').text(title || item?.title || '-');
		renderSemakRows(item);

		const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalSemak'));
		modal.show();
	});

	// ─────────────────────────────────────────────────────────────────
	// MODAL SEMAK – Save Pematuhan (AJAX)
	// ─────────────────────────────────────────────────────────────────
	$('#btnSavePematuhan').on('click', function () {
		if (!activeItemUuid) {
			Swal.fire('Ralat', 'UUID item tidak diketahui.', 'error');
			return;
		}

		// Collect all rows
		const rows = [];
		let hasError = false;

		$('#modalSemakBody tr').each(function () {
			const $select  = $(this).find('.semak-pematuhan');
			const $catatan = $(this).find('.semak-catatan');
			if (!$select.length) return; // header / empty row

			const vendorId        = $select.data('vendor-id');
			const statusPematuhan = $select.val();
			const catatan         = $catatan.val().trim();

			if (statusPematuhan === '' || statusPematuhan === null) {
				hasError = true;
				$select.addClass('is-invalid');
				return;
			}
			$select.removeClass('is-invalid');

			if (statusPematuhan === '0' && !catatan) {
				hasError = true;
				$catatan.addClass('is-invalid');
				return;
			}
			$catatan.removeClass('is-invalid');

			rows.push({ vendor_id: vendorId, status_pematuhan: statusPematuhan, catatan });
		});

		if (hasError) {
			Swal.fire('Ralat Validasi', 'Sila lengkapkan semua Status Pematuhan. Catatan wajib diisi jika Status Pematuhan = Tiada.', 'warning');
			return;
		}

		Swal.fire({
			title: 'Simpan Penilaian?',
			text: 'Maklumat pematuhan akan direkodkan ke dalam sistem.',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#1e293b',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Simpan',
			cancelButtonText: 'Batal'
		}).then(async (result) => {
			if (!result.isConfirmed) return;

			// Save each vendor row sequentially
			let saved = 0;
			let failed = [];

			for (const row of rows) {
				try {
					const resp = await $.ajax({
						url: SIMPAN_URL,
						method: 'POST',
						data: {
							_token:               CSRF_TOKEN,
							tender:               TENDER_IDENTIFIER,
							vendor_id:            row.vendor_id,
							checklist_item_uuid:  activeItemUuid,
							status_pematuhan:     row.status_pematuhan,
							catatan:              row.catatan,
						}
					});
					saved++;

					// Update SEMAK_PAYLOAD in memory so the UI stays consistent
					if (SEMAK_PAYLOAD[activeItemUuid]) {
						const vendorRow = SEMAK_PAYLOAD[activeItemUuid].vendors.find(v => v.vendor_id == row.vendor_id);
						if (vendorRow) {
							vendorRow.status_pematuhan = parseInt(row.status_pematuhan);
							vendorRow.catatan          = row.catatan;
						}
					}
				} catch (err) {
					failed.push(row.vendor_id);
				}
			}

			const modal = bootstrap.Modal.getInstance(document.getElementById('modalSemak'));
			if (modal) modal.hide();

			if (failed.length === 0) {
				Swal.fire({ title: 'Berjaya!', text: 'Penilaian pematuhan telah disimpan.', icon: 'success', confirmButtonColor: '#1e293b' });
			} else {
				Swal.fire({ title: 'Separa Berjaya', text: `${saved} disimpan, ${failed.length} gagal.`, icon: 'warning', confirmButtonColor: '#1e293b' });
			}
		});
	});

	// ─────────────────────────────────────────────────────────────────
	// RUMUSAN – Load via AJAX
	// ─────────────────────────────────────────────────────────────────
	let rumusanData = null;

	function loadRumusanData() {
		$('#rumusan-loading').removeClass('d-none');
		$('#rumusan-content').addClass('d-none');

		$.get(RUMUSAN_URL, { tender: TENDER_IDENTIFIER })
			.done(function (data) {
				rumusanData = data;
				renderRumusan(data);
				$('#rumusan-loading').addClass('d-none');
				$('#rumusan-content').removeClass('d-none');
			})
			.fail(function () {
				$('#rumusan-loading').addClass('d-none');
				$('#rumusan-content').removeClass('d-none');
				Swal.fire('Ralat', 'Gagal memuatkan data rumusan. Sila muat semula halaman.', 'error');
			});
	}

	function renderRumusan(data) {
		const layak     = data.layak      || [];
		const tidakLayak = data.tidak_layak || [];

		$('#totalLayakText').text(`${layak.length} pembekal layak`);
		$('#totalTidakLayakText').text(`${tidakLayak.length} pembekal tidak layak`);

		// ── Senarai Layak Table ────────────────────────────────────
		const $rumusanBody = $('#tableRumusanBody');
		$rumusanBody.empty();

		if (layak.length === 0) {
			$rumusanBody.append('<tr><td colspan="4" class="text-center text-muted py-3">Tiada petender layak.</td></tr>');
		} else {
			layak.forEach(function (v, idx) {
				const bumiSelected1 = v.is_bumiputera == 1 ? 'selected' : '';
				const bumiSelected0 = v.is_bumiputera == 0 ? 'selected' : '';
				const harga         = v.harga_tawaran != null ? v.harga_tawaran : '';

				$rumusanBody.append(
					`<tr>
						<td class="text-center">${idx + 1} / ${layak.length}</td>
						<td class="fw-bold text-primary">${escapeHtml(v.name)}</td>
						<td class="text-center">
							<select class="form-select form-select-sm shadow-none rumusan-bumiputera" data-vendor-id="${v.vendor_id}">
								<option value="" ${!bumiSelected1 && !bumiSelected0 ? 'selected' : ''}>-- Pilih --</option>
								<option value="1" ${bumiSelected1}>Ya</option>
								<option value="0" ${bumiSelected0}>Tidak</option>
							</select>
						</td>
						<td>
							<div class="input-group input-group-sm">
								<span class="input-group-text bg-light">RM</span>
								<input type="number" step="0.01" min="0" class="form-control text-end fw-bold rumusan-harga" data-vendor-id="${v.vendor_id}" value="${escapeHtml(String(harga))}" placeholder="0.00">
							</div>
						</td>
					</tr>`
				);
			});
		}

		// ── Senarai Tidak Layak Table ──────────────────────────────
		const $tidakLayakBody = $('#tableTidakLayakBody');
		$tidakLayakBody.empty();

		if (tidakLayak.length === 0) {
			$tidakLayakBody.append('<tr><td colspan="2" class="text-center text-muted py-3">Tiada pembekal tidak layak.</td></tr>');
		} else {
			tidakLayak.forEach(function (v) {
				const reasonsHtml = (v.reasons || []).map(r => `<li class="mb-1">${escapeHtml(r)}</li>`).join('');
				$tidakLayakBody.append(
					`<tr>
						<td class="fw-bold text-danger">${escapeHtml(v.name)}</td>
						<td><ul class="mb-0 small ps-3">${reasonsHtml}</ul></td>
					</tr>`
				);
			});
		}
	}

	// ─────────────────────────────────────────────────────────────────
	// SELESAI – Submit final evaluation
	// ─────────────────────────────────────────────────────────────────
	function submitSelesai() {
		const $cutoff = $('#pengesahan_cutoff');
		const $layak  = $('#pengesahan_layak');

		const cutoffChecked = $cutoff.is(':checked');
		const layakChecked  = $layak.is(':checked');

		$cutoff.toggleClass('is-invalid', !cutoffChecked);
		$layak.toggleClass('is-invalid', !layakChecked);

		if (!cutoffChecked || !layakChecked) {
			if (typeof Swal !== 'undefined') {
				Swal.fire({
					title: 'Pengesahan Diperlukan',
					text: 'Sila tandakan kedua-dua pengesahan sebelum meneruskan.',
					icon: 'warning',
					confirmButtonText: 'Kembali Semula',
					confirmButtonColor: '#df9657ff'
				});
			} else {
				alert('Pengesahan Diperlukan: Sila tandakan kedua-dua pengesahan sebelum meneruskan.');
			}
			return;
		}

		// Collect rumusan data
		const rumusanRows = [];
		$('#tableRumusanBody tr').each(function () {
			const $bumiSelect = $(this).find('.rumusan-bumiputera');
			const $harga      = $(this).find('.rumusan-harga');
			if (!$bumiSelect.length) return;

			rumusanRows.push({
				vendor_id:     $bumiSelect.data('vendor-id'),
				is_bumiputera: $bumiSelect.val(),
				harga_tawaran: $harga.val(),
			});
		});

		Swal.fire({
			title: 'Selesaikan Penilaian?',
			html: 'Tender akan diteruskan ke proses <strong>Cut-Off</strong>.',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#10b981',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Selesai',
			cancelButtonText: 'Batal'
		}).then(function (result) {
			if (!result.isConfirmed) return;

			$.ajax({
				url:    HANTAR_URL,
				method: 'POST',
				data: {
					_token:  CSRF_TOKEN,
					tender:  TENDER_IDENTIFIER,
					pilihan: 'cut_off',
					rumusan: rumusanRows,
				}
			}).done(function (resp) {
				Swal.fire({
					title: 'Berjaya!',
					text:  resp.message || 'Penilaian jawatankuasa pembuka telah selesai direkodkan.',
					icon:  'success',
					confirmButtonText: 'OK',
					confirmButtonColor: '#1e293b'
				}).then(function () {
					window.location.href = '{{ route('indexJawatankuasaPembuka') }}';
				});
			}).fail(function (xhr) {
				const msg = xhr.responseJSON?.message || 'Ralat tidak diketahui. Sila cuba semula.';
				const details = xhr.responseJSON?.details || '';
				Swal.fire('Gagal', msg + (details ? '\n\n' + details : ''), 'error');
			});
		});
	}

	// ─────────────────────────────────────────────────────────────────
	// FILE PREVIEW MODAL
	// ─────────────────────────────────────────────────────────────────
	$(document).on('click', '.btn-preview-file', function (e) {
		e.preventDefault();
		let url  = $(this).attr('href') || '';
		const name = $(this).data('name') || 'Fail';

		if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
			try {
				const parsed = new URL(url);
				url = parsed.pathname + parsed.search + parsed.hash;
			} catch (err) {}
		}

		$('#modalPreviewTitle').text(name);
		$('#btnNewTabPreview').attr('href', url);
		$('#btnFallbackDownload').attr('href', url);

		$('#previewSpinner').removeClass('d-none');
		$('#previewIframe').addClass('d-none').attr('src', '');
		$('#previewImageWrapper').addClass('d-none');
		$('#previewImage').attr('src', '');
		$('#previewFallback').addClass('d-none');

		const urlPath  = url.split(/[#?]/)[0];
		const extension = urlPath.split('.').pop().trim().toLowerCase();
		const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
		const filenamePortion = urlPath.substring(urlPath.lastIndexOf('/') + 1);
		const isDownloadRoute = /\/download\/?$/.test(urlPath) || urlPath.includes('/dokumen-files/') || urlPath.includes('/checklist-files/') || urlPath.includes('/stos-form-files/');
		const isProbablyPage  = !isDownloadRoute && (!filenamePortion.includes('.') || filenamePortion.endsWith('.html') || url.includes('form') || url.includes('borang'));

		const $icon = $('#previewIcon');
		$icon.removeClass();
		if (imageExtensions.includes(extension)) {
			$icon.addClass('bi bi-file-earmark-image text-primary fs-5');
			$('#previewImage').attr('src', url);
			$('#previewImageWrapper').removeClass('d-none');
		} else if (extension === 'pdf' || isDownloadRoute) {
			$icon.addClass('bi bi-file-earmark-pdf text-danger fs-5');
			$('#previewIframe').attr('src', url).removeClass('d-none');
		} else if (isProbablyPage) {
			$icon.addClass('bi bi-window text-success fs-5');
			$('#previewIframe').attr('src', url).removeClass('d-none');
		} else {
			$icon.addClass('bi bi-file-earmark-zip text-warning fs-5');
			$('#previewSpinner').addClass('d-none');
			$('#previewFallback').removeClass('d-none');
		}

		bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPreview')).show();
	});

	$('#previewIframe').on('load', function () { $('#previewSpinner').addClass('d-none'); });
	$('#previewImage').on('load',  function () { $('#previewSpinner').addClass('d-none'); });

	// ─────────────────────────────────────────────────────────────────
	// INIT
	// ─────────────────────────────────────────────────────────────────
	$(document).ready(function () {
		const dtOptions = {
			paging:      true,
			searching:   false,
			ordering:    false,
			info:        true,
			lengthChange: false,
			responsive:  true,
			language: {
				info:     'Memaparkan _START_ hingga _END_ daripada _TOTAL_ rekod',
				paginate: { first: 'Pertama', last: 'Terakhir', next: 'Seterusnya', previous: 'Sebelumnya' }
			}
		};

		dataTables.teknikal = $('#tableTeknikal').DataTable(dtOptions);
		dataTables.kewangan = $('#tableKewangan').DataTable(dtOptions);

		$('#pengesahan_cutoff, #pengesahan_layak').on('change', function () {
			if ($(this).is(':checked')) {
				$(this).removeClass('is-invalid');
			}
		});

		updateStepperUI();
	});
</script>
@endpush
@endsection
