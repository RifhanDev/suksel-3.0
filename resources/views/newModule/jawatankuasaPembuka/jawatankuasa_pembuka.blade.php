@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
	<link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
	<link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
	<link href="{{ asset('css/components/stepper.css') }}" rel="stylesheet">

	<style>
		.step-content-item {
			transition: all 0.3s ease-in-out;
		}

		/* =========================
		RUMUSAN RESULT TABLES (Step 3 summary)
		========================= */
		.rumusan-table {
			margin-bottom: 0;
			border-radius: 12px;
			overflow: hidden;
			border-collapse: separate;
			border-spacing: 0;
			border: 1px solid #e5e7eb;
		}

		.rumusan-table thead th {
			background: #dbeafe;
			color: #1e293b;
			font-size: 0.68rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			padding: 12px 16px;
			border-bottom: 0;
		}

		.rumusan-table tbody td {
			padding: 12px 16px;
			border-bottom: 1px solid #f1f5f9;
			font-size: 0.85rem;
			color: #334155;
		}

		.rumusan-table tbody tr:last-child td {
			border-bottom: 1px solid #e5e7eb;
		}

		.rumusan-table tfoot td {
			padding: 12px 16px;
			background: #f8fafc;
			text-align: right;
		}

		.rumusan-total-label {
			font-size: 0.72rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			color: #64748b;
		}

		.rumusan-total-value {
			font-size: 1.15rem;
			font-weight: 800;
			margin-left: 12px;
			vertical-align: -1px;
		}

		/* =========================
		RUMUSAN SECTION HEADING
		========================= */
		.rumusan-icon {
			width: 40px;
			height: 40px;
			border-radius: 9px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.rumusan-heading-title {
			font-size: 0.95rem;
			font-weight: 700;
			letter-spacing: -0.2px;
			color: #1e293b;
			line-height: 1.3;
		}

		.rumusan-heading-sub {
			font-size: 0.78rem;
			color: #64748b;
			margin-top: 1px;
		}

		/* =========================
		PERANAN — Pengerusi-only notice on Langkah 3
		========================= */
		.pengerusi-only-note {
			display: flex;
			align-items: flex-start;
			gap: 9px;
			padding: 11px 14px;
			margin-bottom: 12px;
			background: #eff6ff;
			border: 1px solid #bfdbfe;
			border-radius: 8px;
			font-size: 0.79rem;
			line-height: 1.5;
			color: #1e40af;
		}

		.pengerusi-only-note i {
			flex-shrink: 0;
			margin-top: 1px;
		}

		.declaration-box.is-readonly {
			opacity: 0.6;
		}

		.declaration-box.is-readonly .declaration-header {
			cursor: not-allowed;
		}

		/* =========================
		TEMPAHAN BARIS — who is evaluating which petender right now
		========================= */
		#modalSemakBody tr.row-locked-other {
			background: #f8fafc;
		}

		#modalSemakBody tr.row-locked-other td {
			opacity: 0.55;
		}

		/* Keep the status legible even while the rest of the row is dimmed. */
		#modalSemakBody tr.row-locked-other .semak-lock-note,
		#modalSemakBody tr.row-locked-other .semak-pematuhan-badge,
		#modalSemakBody tr.row-locked-other .semak-catatan-text {
			opacity: 1;
		}

		#modalSemakBody tr.row-locked-mine {
			background: #f0fdf4;
		}

		.semak-pematuhan-badge {
			display: inline-flex;
		}

		.rumusan-harga-value {
			font-weight: 700;
			color: #1e293b;
			font-size: 0.9rem;
			white-space: nowrap;
		}

		.rumusan-info-value {
			color: #334155;
			font-size: 0.85rem;
		}

		.semak-catatan-text {
			font-size: 0.8rem;
			color: #334155;
			line-height: 1.4;
			padding: 0.3rem 0;
		}

		.semak-lock-note {
			margin-top: 5px;
		}

		.lock-note {
			display: inline-flex;
			align-items: center;
			gap: 5px;
			font-size: 0.7rem;
			font-weight: 600;
			line-height: 1.3;
		}

		.lock-note-other { color: #b45309; }
		.lock-note-mine  { color: #15803d; }
		.lock-note-free  { color: #94a3b8; }
		.lock-note-done  { color: #0369a1; }

		.btn-semak-lihat.disabled {
			pointer-events: none;
			opacity: 0.5;
		}

		/* =========================
		AKUAN PENGAKUAN — pre-evaluation modal (distinct from the
		.declaration-* classes used by Pengesahan Akhir in Langkah 3)
		========================= */
		.akuan-eyebrow {
			display: block;
			font-size: 0.62rem;
			font-weight: 700;
			letter-spacing: 1.5px;
			text-transform: uppercase;
			color: #94a3b8;
			margin-bottom: 2px;
		}

		.akuan-meta {
			display: flex;
			flex-wrap: wrap;
			gap: 2rem;
			padding: 12px 16px;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			border-radius: 8px;
		}

		.akuan-meta-label {
			display: block;
			font-size: 0.62rem;
			font-weight: 700;
			letter-spacing: 1px;
			text-transform: uppercase;
			color: #94a3b8;
		}

		.akuan-meta-value {
			display: block;
			font-size: 0.85rem;
			font-weight: 700;
			color: #1e293b;
		}

		.akuan-scroll {
			max-height: 46vh;
			overflow-y: auto;
			padding: 18px 20px;
			border: 1px solid #e2e8f0;
			border-radius: 8px;
			background: #fff;
			font-size: 0.84rem;
			line-height: 1.65;
			color: #334155;
		}

		.akuan-scroll:focus-visible {
			outline: none;
			border-color: #cbd5e1;
			box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.18);
		}

		.akuan-scroll h6 {
			font-size: 0.8rem;
			font-weight: 700;
			color: #0f172a;
			margin: 18px 0 6px;
		}

		.akuan-scroll h6:first-of-type {
			margin-top: 14px;
		}

		.akuan-scroll p {
			margin-bottom: 10px;
		}

		.akuan-closing {
			margin-top: 18px;
			padding-top: 14px;
			border-top: 1px dashed #e2e8f0;
			font-weight: 600;
			color: #0f172a;
		}

		/* Sentinel the scroll observer watches; also guarantees the last line
		   can clear the fade at the bottom of the box. */
		.akuan-end {
			height: 1px;
		}

		.akuan-hint {
			display: flex;
			align-items: center;
			gap: 7px;
			margin-top: 10px;
			font-size: 0.76rem;
			font-weight: 600;
			color: #b45309;
		}

		.akuan-hint.is-complete {
			color: #15803d;
		}

		.akuan-hint svg {
			flex-shrink: 0;
		}

		#btnAkuanSetuju:disabled {
			opacity: 0.5;
			cursor: not-allowed;
		}

		/* =========================
		PENGESAHAN AKHIR — declaration card
		========================= */
		.declaration-box {
			background: #ffffff;
			border: 1px solid #e5e7eb;
			border-left: 3px solid #cbd5e1;
			border-radius: 10px;
			padding: 14px 16px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
			transition: background 0.15s ease, border-color 0.15s ease;
		}

		.declaration-box + .declaration-box {
			margin-top: 10px;
		}

		.declaration-box:has(.declaration-checkbox:checked) {
			background: #f0fdf4;
			border-color: #bbf7d0;
			border-left-color: #16a34a;
		}

		.declaration-header {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 6px;
			cursor: pointer;
		}

		.declaration-header:hover .declaration-title {
			color: var(--sg-red, #c41e3a);
		}

		.declaration-checkbox {
			appearance: none;
			-webkit-appearance: none;
			width: 22px;
			height: 22px;
			flex-shrink: 0;
			margin: 0;
			cursor: pointer;
			border: 2px solid #cbd5e1;
			border-radius: 6px;
			background-color: #fff;
			background-repeat: no-repeat;
			background-position: center;
			background-size: 13px 13px;
			transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
		}

		.declaration-checkbox:hover {
			border-color: #94a3b8;
		}

		.declaration-checkbox:checked {
			background-color: #16a34a;
			border-color: #16a34a;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
		}

		.declaration-checkbox:focus-visible {
			outline: none;
			box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.2);
		}

		.declaration-checkbox.is-invalid {
			border-color: #dc2626;
			box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
		}

		.declaration-title {
			font-weight: 700;
			font-size: 0.85rem;
			color: #1e293b;
			transition: color 0.15s ease;
		}

		.declaration-body {
			font-size: 0.8rem;
			color: #64748b;
			line-height: 1.55;
			margin: 0 0 0 28px;
		}
	</style>
@endsection

@section('content')

{{-- ========================================
     INFO BAR: Tender details
======================================== --}}
<div class="tender-header-card mb-4">
	<div class="tender-page-header">
		<div class="tender-ref-label">
			<span class="tender-type-label">Jawatankuasa Pembuka</span>
			<span class="tender-ref-sep">&middot;</span>
			<span class="tender-ref-no">{{ $tender->no_tender ?: ($tender->ref_number ?: 'Belum Dijana') }}</span>
		</div>
		<h2 class="tender-title-main mb-3">{{ $tender->name ?? '-' }}</h2>

		<div class="d-flex flex-wrap align-items-center gap-3 pb-3">
			<div class="d-flex align-items-center gap-2">
				<span class="text-muted fw-semibold text-uppercase" style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
				<span class="fw-semibold text-dark" style="font-size: 0.88rem;">{{ $tender->tenderer->name ?? '-' }}</span>
			</div>
			<span class="d-none d-sm-block" style="width: 1px; height: 16px; background: #e2e8f0;"></span>
			<div class="d-flex align-items-center gap-2">
				<span class="text-muted fw-semibold text-uppercase" style="font-size: 0.67rem; letter-spacing: 0.5px;">Status</span>
				<span class="badge-status badge-status-warning">Dalam Proses</span>
			</div>
			<span class="d-none d-sm-block" style="width: 1px; height: 16px; background: #e2e8f0;"></span>
			<span class="text-muted" style="font-size: 0.8rem;">{{ count($vendors ?? []) }} petender beli dokumen</span>
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

		{{-- ===================================================
		     STEP 1: Pematuhan Teknikal
		=================================================== --}}
		<div id="step-content-1" class="step-content-item">
			<div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
				<div class="content-card-icon" style="width: 42px; height: 42px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M9 11l3 3L22 4"></path>
						<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
					</svg>
				</div>
				<div>
					<h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Pematuhan Cadangan Teknikal</h4>
					<p class="text-muted mb-0" style="font-size: 0.78rem;">Sila semak dan sahkan pematuhan teknikal bagi setiap pembekal.</p>
				</div>
			</div>

			<div class="guideline-card mb-3">
				<div class="guideline-card-header" style="margin-bottom: 0;">
					<span class="guideline-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="16" x2="12" y2="12"></line>
							<line x1="12" y1="8" x2="12.01" y2="8"></line>
						</svg>
					</span>
					<span class="guideline-item-text mb-0">Klik butang <span class="highlight">Semak</span> untuk meneruskan penilaian pematuhan. Status Pematuhan wajib diisi untuk setiap vendor.</span>
				</div>
			</div>

			<table id="tableTeknikal" class="table table-bordered table-slate">
				<thead>
					<tr>
						<th class="text-center" style="width: 50px;">Bil</th>
						<th>Tajuk / Dokumen</th>
						<th class="text-center">Mekanisma</th>
						<th class="text-center">Status Penilaian</th>
						<th class="text-center" style="width: 100px;">Tindakan</th>
					</tr>
				</thead>
				<tbody>
					@php
						$teknikalItemsFiltered = collect($teknikalItems ?? [])->filter(fn ($item) => strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? '')) !== 'muat turun')->values()->all();
					@endphp
					@forelse ($teknikalItemsFiltered as $i => $item)
						@php
							$uuid   = $item['uuid'] ?? '';
							$status = $semakPayload[$uuid]['status_penilaian'] ?? ['label' => 'Menunggu Penilaian', 'badge' => 'badge-status-neutral'];
						@endphp
						<tr data-item-uuid="{{ $uuid }}">
							<td class="text-center">{{ $i + 1 }}</td>
							<td class="fw-medium">{{ $item['title'] ?? $item['nama'] ?? '-' }}</td>
							<td class="text-center">{{ $item['tindakan'] ?? '-' }}</td>
							<td class="text-center">
								<span class="badge-status {{ $status['badge'] }} status-penilaian-badge">{{ $status['label'] }}</span>
							</td>
							<td class="text-center">
								<button type="button"
									class="btn-form btn-form-success btn-semak"
									data-item-uuid="{{ $uuid }}"
									data-title="{{ $item['title'] ?? $item['nama'] ?? '-' }}">
									{{ $status['label'] === 'Telah Dinilai' ? 'Papar' : 'Semak' }}
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
			<div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
				<div class="content-card-icon" style="width: 42px; height: 42px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M9 11l3 3L22 4"></path>
						<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
					</svg>
				</div>
				<div>
					<h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Peringkat Pematuhan Kewangan</h4>
					<p class="text-muted mb-0" style="font-size: 0.78rem;">Sila semak dan sahkan pematuhan kewangan bagi setiap pembekal.</p>
				</div>
			</div>

			<table id="tableKewangan" class="table table-bordered table-slate">
				<thead>
					<tr>
						<th class="text-center" style="width: 50px;">Bil</th>
						<th>Tajuk / Dokumen</th>
						<th class="text-center">Mekanisma</th>
						<th class="text-center">Status Penilaian</th>
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
							$uuid   = $item['uuid'] ?? '';
							$status = $semakPayload[$uuid]['status_penilaian'] ?? ['label' => 'Menunggu Penilaian', 'badge' => 'badge-status-neutral'];
						@endphp
						<tr data-item-uuid="{{ $uuid }}">
							<td class="text-center">{{ $i + 1 }}</td>
							<td class="fw-medium">{{ $item['title'] ?? $item['nama'] ?? '-' }}</td>
							<td class="text-center">{{ $item['tindakan'] ?? '-' }}</td>
							<td class="text-center">
								<span class="badge-status {{ $status['badge'] }} status-penilaian-badge">{{ $status['label'] }}</span>
							</td>
							<td class="text-center">
								<button type="button"
									class="btn-form btn-form-success btn-semak"
									data-item-uuid="{{ $uuid }}"
									data-title="{{ $item['title'] ?? $item['nama'] ?? '-' }}">
									{{ $status['label'] === 'Telah Dinilai' ? 'Papar' : 'Semak' }}
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
			<div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
				<div class="content-card-icon" style="width: 42px; height: 42px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M9 11l3 3L22 4"></path>
						<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
					</svg>
				</div>
				<div>
					<h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Rumusan</h4>
					<p class="text-muted mb-0" style="font-size: 0.78rem;">Rumusan keseluruhan bagi penilaian jawatankuasa pembuka.</p>
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
				<div class="mb-4">
					<div class="d-flex align-items-center gap-3 mb-3">
						<span class="rumusan-icon" style="background: #dcfce7;">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
								stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
								<polyline points="22 4 12 14.01 9 11.01"></polyline>
							</svg>
						</span>
						<div>
							<div class="rumusan-heading-title">Senarai Pembekal Layak</div>
							<div class="rumusan-heading-sub">Pembekal yang lulus semakan pematuhan teknikal dan kewangan.</div>
						</div>
					</div>
					<table id="tableRumusan" class="table rumusan-table align-middle">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">Bil</th>
								<th>Nama Syarikat</th>
								<th class="text-center" style="width: 200px;">Taraf Bumiputera</th>
								<th class="text-center" style="width: 250px;">Harga Tawaran (RM)</th>
							</tr>
						</thead>
						<tbody id="tableRumusanBody">
							<tr><td colspan="4" class="text-center text-muted" style="padding: 18px 16px;">Memuatkan...</td></tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4">
									<span class="rumusan-total-label">Jumlah Pembekal Layak</span>
									<span class="rumusan-total-value" id="totalLayakText" style="color: #16a34a;">0</span>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>

				{{-- Pengesahan Akhir --}}
				<div class="mb-4">
					<div class="mb-2" style="font-size: 0.68rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #475569;">Pengesahan Akhir</div>

					{{-- Shown to members who may evaluate but not finalise; toggled once the session loads. --}}
					<div class="pengerusi-only-note d-none" id="pengerusiOnlyNote">
						<i class="bi bi-info-circle-fill"></i>
						<span>Pengesahan dan penghantaran akhir hanya boleh dilakukan oleh <strong>Pengerusi Jawatankuasa</strong>. Anda masih boleh menyemak rumusan di halaman ini.</span>
					</div>

					<div class="declaration-box">
						<label for="pengesahan_cutoff" class="declaration-header">
							<input class="declaration-checkbox" type="checkbox" id="pengesahan_cutoff" name="pengesahan_cutoff">
							<span class="declaration-title">Pengesahan Cut-Off</span>
						</label>
						<p class="declaration-body">Saya mengesahkan bahawa petender bagi tender ini perlu diteruskan ke peringkat Cut-Off sebelum sebarang keputusan akhir dibuat. Pengesahan ini diperlukan untuk meneruskan proses penilaian.</p>
					</div>

					<div class="declaration-box">
						<label for="pengesahan_layak" class="declaration-header">
							<input class="declaration-checkbox" type="checkbox" id="pengesahan_layak" name="pengesahan_layak">
							<span class="declaration-title">Pengesahan Kelayakan Petender</span>
						</label>
						<p class="declaration-body">Saya mengesahkan bahawa semua petender telah disemak sepenuhnya dari segi pematuhan teknikal dan kewangan, dan senarai kelayakan di atas adalah tepat serta lengkap.</p>
					</div>
				</div>

				{{-- Senarai Tidak Layak --}}
				<div class="mb-4">
					<div class="d-flex align-items-center gap-3 mb-3">
						<span class="rumusan-icon" style="background: #fee2e2;">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
								stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="15" y1="9" x2="9" y2="15"></line>
								<line x1="9" y1="9" x2="15" y2="15"></line>
							</svg>
						</span>
						<div>
							<div class="rumusan-heading-title">Senarai Pembekal Tidak Layak</div>
							<div class="rumusan-heading-sub">Pembekal yang gagal semakan pematuhan teknikal atau kewangan.</div>
						</div>
					</div>
					<table id="tableTidakLayak" class="table rumusan-table align-middle">
						<thead>
							<tr>
								<th>Nama Syarikat</th>
								<th>Sebab Tidak Layak</th>
							</tr>
						</thead>
						<tbody id="tableTidakLayakBody">
							<tr><td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Tiada pembekal tidak layak.</td></tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2">
									<span class="rumusan-total-label">Jumlah Pembekal Tidak Layak</span>
									<span class="rumusan-total-value" id="totalTidakLayakText" style="color: #dc2626;">0</span>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>

			</div>{{-- /rumusan-content --}}
		</div>

		{{-- Navigation Buttons --}}
		<div class="d-flex justify-content-between mt-5">
			<button type="button" class="btn-form btn-form-secondary" id="prevBtn" onclick="prevStep()" disabled>Kembali</button>
			<button type="button" class="btn-form btn-form-primary" id="nextBtn" onclick="nextStep()">Seterusnya</button>
		</div>
	</div>
</div>

{{-- ========================================
     MODAL: Akuan Pengakuan — shown once per member per tender before evaluating.
     Blocking (static backdrop); the agree button unlocks only after the text is
     scrolled to the end.
======================================== --}}
<div class="modal fade" id="modalAkuan" tabindex="-1" aria-labelledby="modalAkuanLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content border-0 shadow-lg rounded-3">
			<div class="modal-header px-4 pt-4 border-0">
				<div class="d-flex align-items-center gap-3">
					<div class="content-card-icon flex-shrink-0" style="width: 42px; height: 42px;">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						</svg>
					</div>
					<div>
						<span class="akuan-eyebrow">Sebelum Penilaian Bermula</span>
						<h5 class="modal-title fw-bold text-dark mb-0" id="modalAkuanLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Akuan Pengakuan Ahli Jawatankuasa Pembuka</h5>
					</div>
				</div>
			</div>

			<div class="modal-body px-4 pb-0">
				<div class="akuan-meta mb-3">
					<div>
						<span class="akuan-meta-label">Tender</span>
						<span class="akuan-meta-value">{{ $tender->no_tender ?: ($tender->ref_number ?: '-') }}</span>
					</div>
					<div>
						<span class="akuan-meta-label">Peranan Anda</span>
						<span class="akuan-meta-value" id="akuanPeranan">&mdash;</span>
					</div>
				</div>

				<div class="akuan-scroll" id="akuanScroll" tabindex="0">
					<p>Saya, sebagai ahli Jawatankuasa Pembuka yang dilantik bagi tender/sebut harga di atas, dengan ini mengaku dan berjanji seperti berikut:</p>

					<h6>1. Kerahsiaan Maklumat</h6>
					<p>Saya akan merahsiakan segala maklumat, dokumen, harga tawaran, dan apa-apa butiran petender yang saya perolehi sepanjang proses pembukaan dan penilaian ini. Saya tidak akan mendedahkan maklumat tersebut kepada mana-mana pihak yang tidak berkenaan, sama ada secara lisan, bertulis, elektronik atau apa-apa cara lain, semasa mahupun selepas proses ini selesai.</p>

					<h6>2. Percanggahan Kepentingan</h6>
					<p>Saya mengesahkan bahawa saya tidak mempunyai apa-apa kepentingan peribadi, keluarga, kewangan atau perniagaan dengan mana-mana petender yang terlibat dalam tender/sebut harga ini. Sekiranya wujud apa-apa percanggahan kepentingan, sama ada sedia ada atau yang timbul kemudian, saya akan segera memaklumkan kepada Pengerusi Jawatankuasa dan menarik diri daripada penilaian berkaitan.</p>

					<h6>3. Ketelusan dan Kesaksamaan</h6>
					<p>Saya akan menjalankan penilaian dengan adil, telus dan saksama berdasarkan semata-mata kepada dokumen yang dikemukakan oleh petender serta kriteria penilaian yang telah ditetapkan. Saya tidak akan memihak kepada mana-mana petender atas apa-apa sebab selain daripada merit tawaran mereka.</p>

					<h6>4. Larangan Menerima Sebarang Bentuk Suapan</h6>
					<p>Saya tidak akan meminta, menerima atau bersetuju untuk menerima apa-apa bentuk suapan, hadiah, keraian, komisen atau apa-apa manfaat daripada mana-mana petender atau wakil mereka. Saya faham bahawa perbuatan sedemikian adalah satu kesalahan di bawah Akta Suruhanjaya Pencegahan Rasuah Malaysia 2009.</p>

					<h6>5. Integriti Rekod Penilaian</h6>
					<p>Saya mengaku bahawa setiap penilaian yang saya rekodkan dalam sistem ini adalah hasil pertimbangan saya sendiri terhadap dokumen yang telah saya semak. Saya tidak akan merekodkan sebarang keputusan penilaian bagi dokumen yang tidak saya semak, dan tidak akan membenarkan mana-mana pihak lain merekodkan penilaian bagi pihak saya.</p>

					<h6>6. Penggunaan Akaun Sendiri</h6>
					<p>Saya bertanggungjawab sepenuhnya ke atas akaun pengguna saya. Saya tidak akan berkongsi kata laluan atau membenarkan mana-mana individu lain mengakses sistem ini menggunakan akaun saya. Saya faham bahawa segala tindakan yang direkodkan melalui akaun saya akan dianggap sebagai tindakan saya sendiri.</p>

					<h6>7. Rekod Aktiviti</h6>
					<p>Saya memahami dan bersetuju bahawa setiap tindakan saya dalam proses penilaian ini &mdash; termasuk masa akuan ini diterima, dokumen yang saya buka, penilaian yang saya rekodkan, dan penghantaran akhir &mdash; akan direkodkan secara automatik oleh sistem untuk tujuan audit dan boleh dirujuk pada bila-bila masa oleh pihak berkuasa yang berkenaan.</p>

					<h6>8. Tanggungjawab</h6>
					<p>Saya faham bahawa keputusan Jawatankuasa Pembuka memberi kesan langsung kepada kelayakan petender untuk meneruskan ke peringkat penilaian seterusnya. Saya menerima tanggungjawab tersebut dengan penuh amanah.</p>

					<p class="akuan-closing">Saya mengaku bahawa segala maklumat dan pengakuan di atas adalah benar. Saya faham bahawa sekiranya saya melanggar mana-mana pengakuan ini, tindakan tatatertib dan/atau tindakan undang-undang boleh diambil terhadap saya.</p>

					<div class="akuan-end" id="akuanEnd" aria-hidden="true"></div>
				</div>

				<div class="akuan-hint" id="akuanHint">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 5v14"></path><path d="m19 12-7 7-7-7"></path>
					</svg>
					<span>Sila tatal sehingga ke penghujung teks untuk meneruskan.</span>
				</div>
			</div>

			<div class="modal-footer px-4 pb-4 pt-3 border-0">
				<button type="button" class="btn-form btn-form-secondary" id="btnAkuanTolak">Keluar</button>
				<button type="button" class="btn-form btn-form-success" id="btnAkuanSetuju" disabled>Saya Faham dan Bersetuju</button>
			</div>
		</div>
	</div>
</div>

{{-- ========================================
     MODAL: Semak Pematuhan
======================================== --}}
<div class="modal fade" id="modalSemak" tabindex="-1" aria-labelledby="modalSemakLabel" aria-hidden="true"
	data-pengalaman-kerja-url-template="{{ route('tenderDokumen.pengalamanKerjaReview', ['tender' => $tender->id]) }}"
	data-kerja-dalam-tangan-url-template="{{ route('tenderDokumen.kerjaDalamTanganReview', ['tender' => $tender->id]) }}">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content border-0 shadow-lg rounded-3">
			<div class="modal-header px-4 pt-4 border-0">
				<div class="d-flex align-items-center gap-3">
					<div class="content-card-icon" style="width: 42px; height: 42px;">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
						</svg>
					</div>
					<div>
						<h5 id="modalDocTitle" class="modal-title fw-bold text-dark mb-0" style="font-size: 1.05rem; letter-spacing: -0.2px;">-</h5>
						<p class="text-muted mb-0" style="font-size: 0.78rem;">Senarai dokumen yang perlu dikemukakan oleh petender.</p>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="guideline-card mb-3">
					<div class="guideline-card-header" style="margin-bottom: 0;">
						<span class="guideline-card-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="12" y1="8" x2="12" y2="12"></line>
								<line x1="12" y1="16" x2="12.01" y2="16"></line>
							</svg>
						</span>
						<span class="guideline-item-text mb-0">Sila pastikan maklumat pematuhan adalah tepat sebelum disimpan. <span class="highlight">Catatan wajib diisi jika Status Pematuhan = Tiada.</span></span>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-slate align-middle mb-0">
						<thead>
							<tr>
								<th class="text-center" style="width: 120px;">Kod Pembekal</th>
								<th class="text-center">Dokumen</th>
								<th class="text-center" style="width: 160px;">Status Penyerahan</th>
								<th class="text-center" style="width: 180px;">Status Pematuhan</th>
								<th class="text-center" style="width: 220px;">Catatan</th>
							</tr>
						</thead>
						<tbody id="modalSemakBody">
							<tr>
								<td colspan="5" class="text-center text-muted py-4">Pilih dokumen untuk semakan.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
				<button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn-form btn-form-success" id="btnSavePematuhan">Simpan Penilaian</button>
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
				<div class="d-flex align-items-center gap-3">
					<div class="content-card-icon flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
						<i class="bi bi-file-earmark-pdf fs-5" id="previewIcon"></i>
					</div>
					<div>
						<span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #94a3b8;">Prebiu Dokumen</span>
						<h5 id="modalPreviewTitle" class="fw-bold text-dark mb-0" style="font-size: 1.05rem; letter-spacing: -0.2px;">-</h5>
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

	// Live evaluation session (akuan, tempahan baris, log) — shared endpoints,
	// 'open' selects Jawatankuasa Pembuka.
	const SESI_SESSION_URL     = '{{ route('penilaianSesi.session', ['jenis' => 'open']) }}';
	const SESI_DECLARATION_URL = '{{ route('penilaianSesi.declaration', ['jenis' => 'open']) }}';
	const SESI_LOCK_URL        = '{{ route('penilaianSesi.lock', ['jenis' => 'open']) }}';
	const SESI_UNLOCK_URL      = '{{ route('penilaianSesi.lock.release', ['jenis' => 'open']) }}';
	const SESI_COMPLETE_URL    = '{{ route('penilaianSesi.rows.complete', ['jenis' => 'open']) }}';
	const SESI_LOCKS_URL       = '{{ route('penilaianSesi.locks', ['jenis' => 'open']) }}';
	const SESI_LOG_URL         = '{{ route('penilaianSesi.log', ['jenis' => 'open']) }}';
	const SESI_EVALUASI_URL    = '{{ route('jawatankuasaPembuka.penilaianSemasa') }}';
	const SESI_STATUS_URL      = '{{ route('jawatankuasaPembuka.statusPenilaian') }}';
	const SESI_BUMIPUTERA_URL  = '{{ route('jawatankuasaPembuka.tarafBumiputera') }}';

	// vendor_id -> { is_bumiputera, label }, from the vendor profile
	let BUMIPUTERA_STATUSES = {};

	// Latest saved evaluation per vendor for the open item, keyed by vendor_id.
	let ROW_EVALUATIONS = {};

	// Populated from the session endpoint on load.
	let SESI = {
		user_id: null,
		peranan: null,
		peranan_label: null,
		is_committee_member: false,
		is_admin: false,
		can_submit: false,
		has_declared: false,
		locks: []
	};

	// ─────────────────────────────────────────────────────────────────
	// STEPPER STATE
	// ─────────────────────────────────────────────────────────────────
	let currentStep  = 1;
	const totalSteps = 3;

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
				nextBtn.innerHTML = 'Selesai';
				nextBtn.classList.replace('btn-form-primary', 'btn-form-success');
			} else {
				nextBtn.innerHTML = 'Seterusnya';
				nextBtn.classList.replace('btn-form-success', 'btn-form-primary');
			}
		}

		startOuterStatusPolling();
	}

	// A step is "done" once every document in its table has been fully evaluated
	// (or there was nothing to evaluate at all — Tiada Petender).
	function isStepComplete(step) {
		const tableId = step === 1 ? '#tableTeknikal' : (step === 2 ? '#tableKewangan' : null);
		if (!tableId) return true;

		return Array.from(document.querySelectorAll(tableId + ' .status-penilaian-badge'))
			.every(function (badge) {
				const label = badge.textContent.trim();
				return label === 'Telah Dinilai' || label === 'Tiada Petender';
			});
	}

	function stepsComplete(fromStep, toStep) {
		for (let s = fromStep; s < toStep && s <= 2; s++) {
			if (!isStepComplete(s)) return false;
		}
		return true;
	}

	// Blocks advancing until every step being skipped is fully evaluated.
	// Re-checks with the server only when the local badges say incomplete, in case
	// another evaluator finished a document since the last refresh.
	function guardStepAdvance(fromStep, toStep, onAllowed) {
		if (toStep <= fromStep || stepsComplete(fromStep, toStep)) {
			onAllowed();
			return;
		}

		refreshOuterStatuses().always(function () {
			if (stepsComplete(fromStep, toStep)) {
				onAllowed();
				return;
			}

			Swal.fire({
				title: 'Penilaian Belum Selesai',
				text: 'Sila lengkapkan penilaian pematuhan bagi semua dokumen pada peringkat ini sebelum meneruskan.',
				icon: 'warning',
				confirmButtonText: 'Kembali Semula',
				confirmButtonColor: '#df9657ff'
			});
		});
	}

	function nextStep() {
		if (currentStep < totalSteps) {
			guardStepAdvance(currentStep, currentStep + 1, function () {
				const from = currentStep;
				currentStep++;
				updateStepperUI();
				logSesi('step_advanced', { metadata: { from: from, to: currentStep } });
			});
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
			guardStepAdvance(currentStep, step, function () {
				const from = currentStep;
				currentStep = step;
				updateStepperUI();
				if (step > from) {
					logSesi('step_advanced', { metadata: { from: from, to: step } });
				}
			});
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

	// ─────────────────────────────────────────────────────────────────
	// TEMPAHAN BARIS (row reservation)
	// ─────────────────────────────────────────────────────────────────

	// Admins are not committee members and stay unrestricted.
	function lockingActive() {
		return SESI.is_committee_member === true;
	}

	function findLock(itemUuid, vendorId) {
		return (SESI.locks || []).find(function (l) {
			return l.checklist_item_uuid === itemUuid && Number(l.vendor_id) === Number(vendorId);
		}) || null;
	}

	/** 'free' | 'mine' | 'other' */
	function lockState(itemUuid, vendorId) {
		if (!lockingActive()) return 'mine'; // admin: never gated
		const lock = findLock(itemUuid, vendorId);
		if (!lock) return 'free';
		return Number(lock.user_id) === Number(SESI.user_id) ? 'mine' : 'other';
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
			const statusClass = vendor.status === 'submitted' ? 'badge-status-success' : 'badge-status-warning';

			// data-url instead of href so clicks run through the reservation check.
			const LIHAT_LINK_CLASS = 'text-primary text-decoration-none d-inline-flex align-items-center gap-1 btn-semak-lihat';

			// Nothing submitted: still claimable so Tiada can be recorded.
			let docHtml = '<span class="small text-muted d-block">' + escapeHtml(vendor.summary || '-') + '</span>' +
				'<a href="#" data-vendor-id="' + vendor.vendor_id + '" class="' + LIHAT_LINK_CLASS + '">' +
				'<i class="bi bi-pencil-square"></i>Mula Menilai</a>';

			if (Array.isArray(vendor.files) && vendor.files.length) {
				docHtml = '<div class="d-flex flex-wrap gap-2">' + vendor.files.map(function (file, idx) {
					const label = vendor.files.length > 1 ? 'Lihat ' + (idx + 1) : 'Lihat';
					return '<a href="#" data-url="' + escapeHtml(file.url) + '" data-name="' + escapeHtml(file.name) + '" ' +
						'data-vendor-id="' + vendor.vendor_id + '" class="' + LIHAT_LINK_CLASS + '">' +
						'<i class="bi bi-file-earmark-pdf-fill"></i>' + label + '</a>';
				}).join('') + '</div>';
			} else if (vendor.form_url) {
				const isSpec = (item?.action === 'view_specification');
				const label  = isSpec ? 'Lihat Spesifikasi' : 'Lihat Borang';
				// use clean shared partial for spec preview
				let formUrl = vendor.form_url;
				if (isSpec) {
					formUrl += (formUrl.indexOf('?') === -1 ? '?' : '&') + 'summary=dokumentasi';
				} else if (vendor.form_key === 'pengalaman_kerja' || vendor.form_key === 'kerja_dalam_tangan') {
					// use clean shared review page instead of the vendor's own live form
					const modalEl = document.getElementById('modalSemak');
					const template = vendor.form_key === 'pengalaman_kerja'
						? modalEl.dataset.pengalamanKerjaUrlTemplate
						: modalEl.dataset.kerjaDalamTanganUrlTemplate;
					formUrl = template + '?vendor_id=' + vendor.vendor_id + '&modal=1&mode=view';
				} else {
					// Force this row's vendor_id so every "Lihat Borang" opens the correct petender.
					if (/([?&])vendor_id=\d+/.test(formUrl)) {
						formUrl = formUrl.replace(/([?&])vendor_id=\d+/, '$1vendor_id=' + vendor.vendor_id);
					} else {
						formUrl += (formUrl.indexOf('?') === -1 ? '?' : '&') + 'vendor_id=' + vendor.vendor_id;
					}
					if (formUrl.indexOf('modal=1') === -1) {
						formUrl += (formUrl.indexOf('?') === -1 ? '?' : '&') + 'modal=1';
					}
					if (formUrl.indexOf('mode=view') === -1) {
						formUrl += (formUrl.indexOf('?') === -1 ? '?' : '&') + 'mode=view';
					}
				}
				docHtml = '<a href="#" data-url="' + escapeHtml(formUrl) + '" data-name="' + (isSpec ? 'Spesifikasi ' : 'Borang ') + escapeHtml(vendor.kod || vendor.name) + '" data-vendor-id="' + vendor.vendor_id + '" class="' + LIHAT_LINK_CLASS + '"><i class="bi bi-file-earmark-pdf-fill"></i>' + label + '</a>';
			}

			// Status Pematuhan – pre-fill from saved evaluation. Rendered disabled;
			// applyLockStates() enables it only for the member holding this row.
			const savedStatus  = vendor.status_pematuhan; // null = belum semak, 1 = Ada, 0 = Tiada
			const savedCatatan = vendor.catatan || '';

			const selectHtml =
				'<select class="form-select shadow-none border-2 small semak-pematuhan" data-vendor-id="' + vendor.vendor_id + '" disabled>' +
					'<option value="" ' + (savedStatus === null || savedStatus === undefined ? 'selected' : '') + ' disabled>-- Pilih --</option>' +
					'<option value="1" ' + (savedStatus === 1 ? 'selected' : '') + '>Ada</option>' +
					'<option value="0" ' + (savedStatus === 0 ? 'selected' : '') + '>Tiada</option>' +
				'</select>';

			const catatanHtml =
				'<textarea class="form-control shadow-none border-2 small semak-catatan" rows="2" placeholder="Catatan..." disabled>' +
				escapeHtml(savedCatatan) + '</textarea>';

			const pematuhanBadgeHtml = '<span class="badge-status semak-pematuhan-badge d-none"></span>';
			const catatanTextHtml = '<div class="semak-catatan-text d-none"></div>';

			const kodDisplay = vendor.kod
				? escapeHtml(vendor.kod)
				: '<span class="fst-italic small text-muted">Kod Pembekal Belum Dijana</span>';

			$body.append(
				'<tr data-vendor-id="' + vendor.vendor_id + '">' +
					'<td class="text-center fw-bold bg-light" data-vendor-id="' + vendor.vendor_id + '">' + kodDisplay + '</td>' +
					'<td>' + docHtml + '<div class="semak-lock-note"></div></td>' +
					'<td class="text-center"><span class="badge-status ' + statusClass + '">' + escapeHtml(vendor.status_label) + '</span></td>' +
					'<td class="text-center">' + selectHtml + pematuhanBadgeHtml + '</td>' +
					'<td>' + catatanHtml + catatanTextHtml + '</td>' +
				'</tr>'
			);
		});

		applyLockStates();
	}

	// Repaints lock state only — never rebuilds rows, so in-progress typing survives a poll.
	function applyLockStates() {
		const itemUuid = activeItemUuid;
		let heldByMe = 0;

		$('#modalSemakBody tr[data-vendor-id]').each(function () {
			const $row     = $(this);
			const vendorId = $row.data('vendor-id');
			const state    = lockState(itemUuid, vendorId);
			const lock     = findLock(itemUuid, vendorId);

			if (state === 'mine' && lockingActive() && lock) heldByMe++;

			const $select      = $row.find('.semak-pematuhan');
			const $catatan     = $row.find('.semak-catatan');
			const $badge       = $row.find('.semak-pematuhan-badge');
			const $catatanText = $row.find('.semak-catatan-text');
			const $links       = $row.find('.btn-semak-lihat');
			const $note        = $row.find('.semak-lock-note');

			const evaluation  = ROW_EVALUATIONS[vendorId];
			const isEvaluated = evaluation && evaluation.status_pematuhan !== null;

			$row.removeClass('row-locked-other row-locked-mine');

			if (state === 'mine') {
				$select.prop('disabled', false).removeClass('d-none');
				$catatan.prop('disabled', false).removeClass('d-none');
				$badge.addClass('d-none');
				$catatanText.addClass('d-none');
				$links.removeClass('disabled').attr('aria-disabled', 'false');
				if (lockingActive() && lock) {
					$row.addClass('row-locked-mine');
					$note.html('<span class="lock-note lock-note-mine"><i class="bi bi-unlock-fill"></i>Sedang dinilai oleh anda</span>');
				} else {
					$note.empty();
				}
			} else if (state === 'other') {
				$select.addClass('d-none');
				$catatan.addClass('d-none');
				$badge.removeClass('d-none')
					.attr('class', 'badge-status semak-pematuhan-badge badge-status-warning')
					.text('Sedang Dinilai');
				$catatanText.removeClass('d-none').text('—').addClass('text-muted');
				$links.addClass('disabled').attr('aria-disabled', 'true');
				$row.addClass('row-locked-other');
				$note.html('<span class="lock-note lock-note-other"><i class="bi bi-lock-fill"></i>Sedang dinilai oleh ' +
					escapeHtml(lock ? lock.user_name : 'pengguna lain') + '</span>');
			} else if (isEvaluated) {
				// Recorded result — badge, not a disabled form control.
				$select.addClass('d-none');
				$catatan.addClass('d-none');
				$badge.removeClass('d-none')
					.attr('class', 'badge-status semak-pematuhan-badge ' +
						(evaluation.status_pematuhan === 1 ? 'badge-status-success' : 'badge-status-danger'))
					.text(evaluation.status_pematuhan === 1 ? 'Ada' : 'Tiada');
				$catatanText.removeClass('d-none')
					.text(evaluation.catatan || '—')
					.toggleClass('text-muted', !evaluation.catatan);
				$links.removeClass('disabled').attr('aria-disabled', 'false');
				if (evaluation.evaluator_name) {
					$note.html('<span class="lock-note lock-note-done"><i class="bi bi-check-circle-fill"></i>Disemak oleh ' +
						escapeHtml(evaluation.evaluator_name) + '</span>');
				} else {
					$note.empty();
				}
			} else {
				// free, not yet evaluated — must claim the row before judging it
				$select.prop('disabled', true).removeClass('d-none');
				$catatan.prop('disabled', true).removeClass('d-none');
				$badge.addClass('d-none');
				$catatanText.addClass('d-none');
				$links.removeClass('disabled').attr('aria-disabled', 'false');

				const hasDoc = $links.filter('[data-url]').length > 0;
				$note.html('<span class="lock-note lock-note-free"><i class="bi bi-eye"></i>' +
					(hasDoc ? 'Buka dokumen untuk menilai' : 'Tiada dokumen — klik Mula Menilai') + '</span>');
			}
		});

		// Nothing to save unless this user is holding at least one row.
		$('#btnSavePematuhan').prop('disabled', lockingActive() && heldByMe === 0);
	}

	/**
	 * "Lihat" inside the semak modal. Opening a document reserves that row, so the
	 * click is gated:
	 *   - already mine  → reopen straight away (resume path, no second prompt)
	 *   - someone else  → blocked
	 *   - free          → confirm, reserve, then open
	 */
	$(document).on('click', '.btn-semak-lihat', function (e) {
		e.preventDefault();

		const $link    = $(this);
		const url      = $link.data('url');
		const name     = $link.data('name') || 'Fail';
		const vendorId = $link.data('vendor-id');
		const itemUuid = activeItemUuid;

		if (!lockingActive()) {
			if (url) openPreview(url, name);
			return;
		}

		const state = lockState(itemUuid, vendorId);

		if (state === 'mine') {
			if (url) openPreview(url, name);
			return;
		}

		if (state === 'other') {
			const lock = findLock(itemUuid, vendorId);
			Swal.fire({
				icon: 'info',
				title: 'Sedang Dinilai',
				text: 'Pembekal ini sedang dinilai oleh ' + (lock ? lock.user_name : 'ahli lain') + '. Sila pilih petender lain atau tunggu sehingga selesai.',
				confirmButtonColor: '#1e293b'
			});
			return;
		}

		// Re-opening a row a colleague already recorded is an update, not a fresh
		// evaluation — say so plainly, and name who did it.
		const existing = ROW_EVALUATIONS[vendorId];
		const alreadyEvaluated = existing && existing.status_pematuhan !== null;

		const prompt = alreadyEvaluated
			? {
				title: 'Petender Ini Telah Disemak',
				html: 'Dokumen petender ini telah disemak' +
					(existing.evaluator_name ? ' oleh <strong>' + escapeHtml(existing.evaluator_name) + '</strong>' : '') +
					' dengan status <strong>' + (existing.status_pematuhan === 1 ? 'Ada' : 'Tiada') + '</strong>.' +
					'<br><br>Adakah anda mahu membuka semula dokumen ini untuk <strong>mengemas kini</strong> penilaian tersebut?',
				confirmText: 'Ya, Kemas Kini'
			}
			: {
				title: url ? 'Nilai Dokumen Petender Ini?' : 'Nilai Pembekal Ini?',
				html: (url ? '' : 'Pembekal ini tidak mengemukakan sebarang dokumen. ') +
					'Anda akan mula menilai pembekal ini. Ahli jawatankuasa lain tidak akan dapat menilai pembekal yang sama sehingga anda selesai.',
				confirmText: 'Ya, Mula Menilai'
			};

		Swal.fire({
			title: prompt.title,
			html: prompt.html,
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: prompt.confirmText,
			cancelButtonText: 'Batal',
			confirmButtonColor: '#1e293b',
			cancelButtonColor: '#94a3b8'
		}).then(function (result) {
			if (!result.isConfirmed) return;

			$.post(SESI_LOCK_URL, {
				_token: CSRF_TOKEN,
				tender: TENDER_IDENTIFIER,
				checklist_item_uuid: itemUuid,
				vendor_id: vendorId,
				item_title: SEMAK_PAYLOAD[itemUuid]?.title || ''
			}).done(function (res) {
				SESI.locks = (SESI.locks || []).filter(function (l) {
					return !(l.checklist_item_uuid === itemUuid && Number(l.vendor_id) === Number(vendorId));
				});
				SESI.locks.push({
					checklist_item_uuid: itemUuid,
					vendor_id: Number(vendorId),
					user_id: Number(SESI.user_id),
					user_name: 'Anda'
				});
				applyLockStates();
				if (url) openPreview(url, name);
			}).fail(function (xhr) {
				// 409 = another member won the race in the moment between render and click.
				if (xhr.status === 409) {
					const holder = xhr.responseJSON?.data?.held_by_name || 'ahli lain';
					Swal.fire({
						icon: 'warning',
						title: 'Pembekal Ini Baru Sahaja Diambil',
						text: 'Dokumen ini baru sahaja diambil oleh ' + holder + '. Sila pilih petender lain.',
						confirmButtonColor: '#1e293b'
					});
					refreshLocks();
					return;
				}
				// 502/504 = the evaluation backend is unreachable, not an evaluation problem.
				if (xhr.status === 0 || xhr.status >= 502) {
					Swal.fire({
						icon: 'error',
						title: 'Sistem Penilaian Tidak Dapat Dihubungi',
						text: 'Sambungan ke pelayan penilaian terputus. Sila cuba sebentar lagi atau maklumkan kepada pentadbir sistem.',
						confirmButtonColor: '#1e293b'
					});
					return;
				}
				Swal.fire('Ralat', xhr.responseJSON?.message || 'Gagal memulakan penilaian pembekal ini.', 'error');
			});
		});
	});

	// ── Polling: keeps every member's view of the reservations current ──
	let lockPollTimer = null;
	let lockPollInFlight = false;

	function refreshLocks() {
		if (!activeItemUuid) return $.Deferred().resolve().promise();

		// Skip if the previous tick is still open, so a slow backend cannot stack requests.
		if (lockPollInFlight) return $.Deferred().resolve().promise();
		lockPollInFlight = true;

		// Fetched together: finishing a row changes both at once.
		return $.when(
			$.get(SESI_LOCKS_URL, { tender: TENDER_IDENTIFIER, checklist_item_uuid: activeItemUuid }),
			$.get(SESI_EVALUASI_URL, { tender: TENDER_IDENTIFIER, checklist_item_uuid: activeItemUuid })
		).always(function () {
			lockPollInFlight = false;
		}).done(function (lockRes, evalRes) {
			const incoming = lockRes[0]?.data?.locks || [];
			// Replace only this item's locks; other items' reservations stay cached.
			SESI.locks = (SESI.locks || [])
				.filter(function (l) { return l.checklist_item_uuid !== activeItemUuid; })
				.concat(incoming);

			ROW_EVALUATIONS = {};
			(evalRes[0]?.data?.evaluations || []).forEach(function (row) {
				ROW_EVALUATIONS[row.vendor_id] = row;
			});

			applyEvaluationValues();
			applyLockStates();
		});
	}

	// Pulls in other members' saved results. Skips rows held by this user (may be mid-edit).
	function applyEvaluationValues() {
		$('#modalSemakBody tr[data-vendor-id]').each(function () {
			const $row     = $(this);
			const vendorId = $row.data('vendor-id');

			if (lockState(activeItemUuid, vendorId) === 'mine') return;

			const evaluation = ROW_EVALUATIONS[vendorId];
			if (!evaluation || evaluation.status_pematuhan === null) return;

			$row.find('.semak-pematuhan').val(String(evaluation.status_pematuhan));
			$row.find('.semak-catatan').val(evaluation.catatan || '');

			// Keep the in-memory payload in step so reopening the modal is consistent.
			const vendorRow = SEMAK_PAYLOAD[activeItemUuid]?.vendors.find(v => v.vendor_id == vendorId);
			if (vendorRow) {
				vendorRow.status_pematuhan = evaluation.status_pematuhan;
				vendorRow.catatan = evaluation.catatan || '';
			}
		});
	}

	// ── Outer step tables: keep Status Penilaian current across evaluators ──
	let outerStatusTimer = null;

	// Document uuids rendered in the step tables.
	function outerItemUuids() {
		return $('#tableTeknikal tr[data-item-uuid], #tableKewangan tr[data-item-uuid]')
			.map(function () { return $(this).data('item-uuid'); })
			.get()
			.filter(Boolean);
	}

	let outerStatusInFlight = false;

	function refreshOuterStatuses() {
		const items = outerItemUuids();
		if (!items.length || outerStatusInFlight) return $.Deferred().resolve().promise();
		outerStatusInFlight = true;

		return $.get(SESI_STATUS_URL, { tender: TENDER_IDENTIFIER, items: items })
			.always(function () {
				outerStatusInFlight = false;
			})
			.done(function (res) {
				const statuses = res.data?.statuses || {};
				Object.keys(statuses).forEach(function (uuid) {
					updateStatusPenilaianBadge(uuid, statuses[uuid]);
				});
			});
	}

	// Steps 1-2 only; step 3 has no document list to refresh.
	function startOuterStatusPolling() {
		stopOuterStatusPolling();
		if (currentStep > 2) return;
		outerStatusTimer = setInterval(refreshOuterStatuses, 20000);
	}

	function stopOuterStatusPolling() {
		if (outerStatusTimer) {
			clearInterval(outerStatusTimer);
			outerStatusTimer = null;
		}
	}

	function startLockPolling() {
		stopLockPolling();
		if (!lockingActive()) return;
		lockPollTimer = setInterval(refreshLocks, 5000);
	}

	function stopLockPolling() {
		if (lockPollTimer) {
			clearInterval(lockPollTimer);
			lockPollTimer = null;
		}
	}

	// ─────────────────────────────────────────────────────────────────
	// MODAL SEMAK – Open
	// ─────────────────────────────────────────────────────────────────
	$(document).on('click', '.btn-semak', function () {
		const title    = $(this).data('title');
		activeItemUuid = String($(this).data('item-uuid') || '');
		const item     = SEMAK_PAYLOAD[activeItemUuid] || null;

		// Seed from page data so the badge is right on first paint, before the poll lands.
		ROW_EVALUATIONS = {};
		(item?.vendors || []).forEach(function (vendor) {
			ROW_EVALUATIONS[vendor.vendor_id] = {
				vendor_id: vendor.vendor_id,
				status_pematuhan: vendor.status_pematuhan,
				catatan: vendor.catatan || '',
				evaluator_name: null
			};
		});

		$('#modalDocTitle').text(title || item?.title || '-');
		renderSemakRows(item);

		const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalSemak'));
		modal.show();

		// Pull the current reservations for this document, then keep them fresh while open.
		refreshLocks();
		startLockPolling();
	});

	document.getElementById('modalSemak').addEventListener('hidden.bs.modal', function () {
		stopLockPolling();
		refreshOuterStatuses();
	});

	// ─────────────────────────────────────────────────────────────────
	// MODAL SEMAK – Save Pematuhan (AJAX)
	// ─────────────────────────────────────────────────────────────────
	// Live-patch the outer table's badge + Semak/Papar button label after a save,
	// using the server-computed Status Penilaian — no page reload needed.
	function updateStatusPenilaianBadge(itemUuid, itemStatus) {
		const row = document.querySelector('tr[data-item-uuid="' + itemUuid + '"]');
		const badge = row ? row.querySelector('.status-penilaian-badge') : null;
		if (!badge) return;
		badge.className = 'badge-status ' + itemStatus.badge + ' status-penilaian-badge';
		badge.textContent = itemStatus.label;
		const actionBtn = row.querySelector('.btn-semak');
		if (actionBtn) actionBtn.textContent = (itemStatus.label === 'Telah Dinilai') ? 'Papar' : 'Semak';
	}

	$('#btnSavePematuhan').on('click', function () {
		if (!activeItemUuid) {
			Swal.fire('Ralat', 'UUID item tidak diketahui.', 'error');
			return;
		}

		// Collect only the rows the officer actually filled in — evaluating one
		// vendor at a time (leaving others blank) is allowed.
		const rows = [];
		let hasError = false;

		$('#modalSemakBody tr').each(function () {
			const $select  = $(this).find('.semak-pematuhan');
			const $catatan = $(this).find('.semak-catatan');
			if (!$select.length) return; // header / empty row

			const vendorId        = $select.data('vendor-id');
			const statusPematuhan = $select.val();

			// Only rows reserved to this member — others' values are still in the DOM.
			if (lockingActive() && lockState(activeItemUuid, vendorId) !== 'mine') {
				return;
			}

			if (statusPematuhan === '' || statusPematuhan === null) {
				$select.removeClass('is-invalid');
				return; // not filled in yet — skip, not an error
			}
			$select.removeClass('is-invalid');

			const catatan = $catatan.val().trim();
			if (statusPematuhan === '0' && !catatan) {
				hasError = true;
				$catatan.addClass('is-invalid');
				return;
			}
			$catatan.removeClass('is-invalid');

			rows.push({ vendor_id: vendorId, status_pematuhan: statusPematuhan, catatan });
		});

		if (hasError) {
			Swal.fire('Ralat Validasi', 'Catatan wajib diisi jika Status Pematuhan = Tiada.', 'warning');
			return;
		}

		if (!rows.length) {
			const holdsRow = $('#modalSemakBody tr[data-vendor-id]').toArray().some(function (tr) {
				return lockState(activeItemUuid, $(tr).data('vendor-id')) === 'mine';
			});

			Swal.fire({
				icon: 'info',
				title: holdsRow ? 'Status Pematuhan Belum Dipilih' : 'Tiada Penilaian Untuk Disimpan',
				text: holdsRow
					? 'Sila pilih status pematuhan bagi pembekal yang sedang anda nilai.'
					: 'Semua pembekal bagi dokumen ini telah dinilai. Klik "Lihat" pada pembekal untuk mengemas kini penilaian.'
			});
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
		}).then(function (result) {
			if (!result.isConfirmed) return;

			$.ajax({
				url: SIMPAN_URL,
				method: 'POST',
				data: {
					_token:              CSRF_TOKEN,
					tender:              TENDER_IDENTIFIER,
					checklist_item_uuid: activeItemUuid,
					rows:                rows,
				}
			}).done(function (resp) {
				// Update SEMAK_PAYLOAD in memory so the UI stays consistent
				rows.forEach(function (row) {
					const vendorRow = SEMAK_PAYLOAD[activeItemUuid]?.vendors.find(v => v.vendor_id == row.vendor_id);
					if (vendorRow) {
						vendorRow.status_pematuhan = parseInt(row.status_pematuhan);
						vendorRow.catatan          = row.catatan;
					}
				});

				// Sent as one request: a call per row would fan out across the shared worker pool.
				if (lockingActive()) {
					const savedUuid = activeItemUuid;
					$.post(SESI_COMPLETE_URL, {
						_token: CSRF_TOKEN,
						tender: TENDER_IDENTIFIER,
						checklist_item_uuid: savedUuid,
						item_title: SEMAK_PAYLOAD[savedUuid]?.title || '',
						rows: rows.map(function (row) {
							return { vendor_id: row.vendor_id, status_pematuhan: row.status_pematuhan };
						})
					});
					SESI.locks = (SESI.locks || []).filter(function (l) {
						return !(l.checklist_item_uuid === savedUuid &&
							rows.some(function (r) { return Number(r.vendor_id) === Number(l.vendor_id); }));
					});
				}

				const modal = bootstrap.Modal.getInstance(document.getElementById('modalSemak'));
				if (modal) modal.hide();

				Swal.fire({ title: 'Berjaya!', text: resp.message || 'Penilaian pematuhan telah disimpan.', icon: 'success', confirmButtonColor: '#1e293b' });

				if (resp.item_status) {
					updateStatusPenilaianBadge(activeItemUuid, resp.item_status);
				}
			}).fail(function (xhr) {
				Swal.fire('Ralat', xhr.responseJSON?.message || 'Gagal menyimpan penilaian.', 'error');
			});
		});
	});

	// ─────────────────────────────────────────────────────────────────
	// RUMUSAN – Load via AJAX
	// ─────────────────────────────────────────────────────────────────
	let rumusanData = null;

	function loadRumusanData() {
		$('#rumusan-loading').removeClass('d-none');
		$('#rumusan-content').addClass('d-none');

		$.when(
			$.get(RUMUSAN_URL, { tender: TENDER_IDENTIFIER }),
			$.get(SESI_BUMIPUTERA_URL, { tender: TENDER_IDENTIFIER })
		)
			.done(function (rumusanRes, bumiRes) {
				const data = rumusanRes[0];
				BUMIPUTERA_STATUSES = bumiRes[0]?.data?.bumiputera || {};
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

		$('#totalLayakText').text(layak.length);
		$('#totalTidakLayakText').text(tidakLayak.length);

		// ── Senarai Layak Table ────────────────────────────────────
		const $rumusanBody = $('#tableRumusanBody');
		$rumusanBody.empty();

		if (layak.length === 0) {
			$rumusanBody.append('<tr><td colspan="4" class="text-center text-muted py-3">Tiada petender layak.</td></tr>');
		} else {
			layak.forEach(function (v, idx) {
				// Both values are derived, not officer input.
				const bumi    = BUMIPUTERA_STATUSES[v.vendor_id];
				const isBumi  = bumi ? bumi.is_bumiputera === 1 : null;
				const harga   = v.harga_tawaran != null ? v.harga_tawaran : null;

				const bumiHtml = bumi
					? `<span class="rumusan-info-value">${escapeHtml(bumi.label)}</span>`
					: '<span class="text-muted small">Tiada maklumat</span>';

				const hargaHtml = harga !== null && harga !== ''
					? `<span class="rumusan-harga-value">RM ${Number(harga).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>`
					: '<span class="text-muted small">&mdash;</span>';

				$rumusanBody.append(
					`<tr>
						<td class="text-center">${idx + 1} / ${layak.length}</td>
						<td class="fw-bold text-primary">${escapeHtml(v.name)}</td>
						<td class="text-center">
							${bumiHtml}
							<input type="hidden" class="rumusan-bumiputera" data-vendor-id="${v.vendor_id}" value="${isBumi === null ? '' : (isBumi ? 1 : 0)}">
						</td>
						<td class="text-end">
							${hargaHtml}
							<input type="hidden" class="rumusan-harga" data-vendor-id="${v.vendor_id}" value="${escapeHtml(String(harga ?? ''))}">
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
		if (!SESI.can_submit) {
			Swal.fire({
				icon: 'info',
				title: 'Hanya Pengerusi Boleh Menghantar',
				text: 'Penghantaran keputusan akhir hanya boleh dilakukan oleh Pengerusi Jawatankuasa.',
				confirmButtonColor: '#1e293b'
			});
			return;
		}

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
				// Logged before navigating away so the entry is not lost to the redirect.
				logSesi('submitted', { metadata: { vendor_count: rumusanRows.length } })
					.always(function () {
						Swal.fire({
							title: 'Berjaya!',
							text:  resp.message || 'Penilaian jawatankuasa pembuka telah selesai direkodkan.',
							icon:  'success',
							confirmButtonText: 'OK',
							confirmButtonColor: '#1e293b'
						}).then(function () {
							window.location.href = '{{ route('indexJawatankuasaPembuka') }}';
						});
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
		openPreview($(this).attr('href') || '', $(this).data('name') || 'Fail');
	});

	/** Opens the document viewer. Split out so the lock-gated links in the semak
	 *  modal can call it once the row has been reserved. */
	function openPreview(rawUrl, name) {
		let url = rawUrl || '';

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
		$('#previewIframe').addClass('d-none').attr('src', 'about:blank');
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
		} else if (extension === 'pdf' || isDownloadRoute) {
			$icon.addClass('bi bi-file-earmark-pdf text-danger fs-5');
			$('#previewIframe').attr('src', url);
		} else if (isProbablyPage) {
			$icon.addClass('bi bi-window text-success fs-5');
			// Bust cache so switching petender always reloads the correct vendor form.
			const bust = (url.indexOf('?') === -1 ? '?' : '&') + '_ts=' + Date.now();
			$('#previewIframe').attr('src', url + bust);
		} else {
			$icon.addClass('bi bi-file-earmark-zip text-warning fs-5');
			$('#previewSpinner').addClass('d-none');
			$('#previewFallback').removeClass('d-none');
		}

		bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPreview')).show();
	}

	$('#previewIframe').on('load', function () {
		if ($(this).attr('src') === 'about:blank') return;
		$('#previewSpinner').addClass('d-none');
		$(this).removeClass('d-none');
	});
	$('#previewImage').on('load', function () {
		$('#previewSpinner').addClass('d-none');
		$('#previewImageWrapper').removeClass('d-none');
	});

	// ─────────────────────────────────────────────────────────────────
	// SESI PENILAIAN — akuan pengakuan
	// ─────────────────────────────────────────────────────────────────

	/** Fire-and-forget audit entry; never blocks the evaluator. */
	function logSesi(action, extra) {
		return $.post(SESI_LOG_URL, Object.assign({
			_token: CSRF_TOKEN,
			tender: TENDER_IDENTIFIER,
			action: action
		}, extra || {}));
	}

	function loadSesi() {
		return $.get(SESI_SESSION_URL, { tender: TENDER_IDENTIFIER })
			.done(function (res) {
				SESI = Object.assign(SESI, res.data || {});
			});
	}

	/**
	 * Enables the agree button once the text has been read to the end.
	 * Uses IntersectionObserver against a sentinel at the bottom of the text, so it
	 * stays correct at any zoom level or viewport height where a scrollTop maths
	 * check would drift. Falls back to a scroll listener where unsupported.
	 */
	function wireAkuanScrollGate() {
		const box    = document.getElementById('akuanScroll');
		const end    = document.getElementById('akuanEnd');
		const $btn   = $('#btnAkuanSetuju');
		const $hint  = $('#akuanHint');

		if (!box || !end) return;

		function unlock() {
			$btn.prop('disabled', false);
			$hint.addClass('is-complete')
				.find('span').text('Terima kasih. Anda kini boleh menerima akuan ini.');
		}

		// Text short enough to need no scrolling at all — nothing to gate on.
		if (box.scrollHeight <= box.clientHeight + 2) {
			unlock();
			return;
		}

		if ('IntersectionObserver' in window) {
			const observer = new IntersectionObserver(function (entries) {
				if (entries.some(function (e) { return e.isIntersecting; })) {
					unlock();
					observer.disconnect();
				}
			}, { root: box, threshold: 1.0 });
			observer.observe(end);
			return;
		}

		box.addEventListener('scroll', function onScroll() {
			if (box.scrollTop + box.clientHeight >= box.scrollHeight - 8) {
				unlock();
				box.removeEventListener('scroll', onScroll);
			}
		});
	}

	function showAkuanModal() {
		$('#akuanPeranan').text(SESI.peranan_label || 'Ahli Jawatankuasa');

		const modal = new bootstrap.Modal(document.getElementById('modalAkuan'), {
			backdrop: 'static',
			keyboard: false
		});

		// Gate is wired after the modal is visible so the scroll box has real dimensions.
		document.getElementById('modalAkuan').addEventListener('shown.bs.modal', function () {
			wireAkuanScrollGate();
			document.getElementById('akuanScroll').focus();
		}, { once: true });

		modal.show();
	}

	// Pengerusi-only finalisation. Route middleware enforces the same rule server-side.
	function applyPerananRestrictions() {
		if (SESI.can_submit) return;

		$('#pengerusiOnlyNote').removeClass('d-none');
		$('#pengesahan_cutoff, #pengesahan_layak')
			.prop('checked', false)
			.prop('disabled', true)
			.closest('.declaration-box').addClass('is-readonly');
	}

	// Resume on the furthest step reached, using the same rule guardStepAdvance() enforces.
	function resumeLatestStep() {
		let target = 1;
		for (let s = 1; s <= 2; s++) {
			if (isStepComplete(s)) target = s + 1;
			else break;
		}

		if (target !== currentStep) {
			currentStep = target;
			updateStepperUI();
		}
	}

	function initSesiPenilaian() {
		loadSesi()
			.done(function () {
				applyPerananRestrictions();
				resumeLatestStep();

				// Admins overseeing the process are not committee members and have no
				// akuan to give; members must accept once per tender before evaluating.
				if (!SESI.is_committee_member || SESI.has_declared) {
					return;
				}
				showAkuanModal();
			})
			.fail(function () {
				showToast('error', 'Gagal memuatkan sesi penilaian. Sila muat semula halaman.');
			});
	}

	$('#btnAkuanSetuju').on('click', function () {
		const $btn = $(this);
		$btn.prop('disabled', true).text('Merekod...');

		$.post(SESI_DECLARATION_URL, { _token: CSRF_TOKEN, tender: TENDER_IDENTIFIER })
			.done(function (res) {
				SESI.has_declared = true;
				bootstrap.Modal.getInstance(document.getElementById('modalAkuan')).hide();
				showToast('success', res.message || 'Akuan telah direkodkan.');
			})
			.fail(function (xhr) {
				$btn.prop('disabled', false).text('Saya Faham dan Bersetuju');
				showToast('error', xhr.responseJSON?.message || 'Gagal merekod akuan.');
			});
	});

	// Declining means leaving — evaluation cannot proceed without the akuan.
	$('#btnAkuanTolak').on('click', function () {
		window.location.href = '{{ route('indexJawatankuasaPembuka') }}';
	});

	// ─────────────────────────────────────────────────────────────────
	// INIT
	// ─────────────────────────────────────────────────────────────────
	$(document).ready(function () {
		$('#pengesahan_cutoff, #pengesahan_layak').on('change', function () {
			if ($(this).is(':checked')) {
				$(this).removeClass('is-invalid');
			}
		});

		updateStepperUI();
		initSesiPenilaian();
	});
</script>
@endpush
@endsection
