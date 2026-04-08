@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
	<style>
		.section-title-bar {
			background: #f1f3f5;
			border-top: 1px solid #d9d9d9;
			border-bottom: 1px solid #d9d9d9;
			padding: 8px 10px;
			font-size: 13px;
			font-weight: 700;
			color: #202020;
		}

		.btn-kt-teal {
			background-color: #0f766e;
			border-color: #0f766e;
			color: #fff;
		}

		.btn-kt-teal:hover {
			background-color: #0d9488;
			border-color: #0d9488;
			color: #fff;
		}
	</style>
@endsection

@section('content')
	@php
		$refNo = optional($tender)->no_tender ?: optional($tender)->ref_number ?: '-';
		$tajukTender = optional($tender)->name ?: '-';
		$ptj = optional(optional($tender)->tenderer)->name ?: '-';
		$tarikhSerahan = !empty(optional($tender)->submission_datetime) ? \Carbon\Carbon::parse($tender->submission_datetime) : null;
		$tempohSahLaku = $tarikhSerahan ? '90 Hari' : '-';
		$sahLakuTamat = $tarikhSerahan ? $tarikhSerahan->copy()->addDays(90)->format('d/m/Y') : '-';
	@endphp

	<!-- BREADCRUMB -->
	<div class="d-flex align-items-center gap-2 mb-3">
		<a href="{{ url()->previous() }}" class="text-muted small d-flex align-items-center gap-1" style="text-decoration:none;">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="15 18 9 12 15 6"></polyline>
			</svg>
			Kembali
		</a>
		<span class="text-muted small">/</span>
		<span class="text-muted small">Jawatankuasa Perolehan</span>
		<span class="text-muted small">/</span>
		<span class="text-muted small fw-semibold text-dark">Keputusan Mesyuarat</span>
	</div>

	<!-- HEADER -->
	<div class="tender-header-card mb-4">

		<!-- Info Section -->
		<div class="tender-page-header">

			<!-- Ref label row -->
			<div class="tender-ref-label">
				<span class="tender-type-label">Sebut Harga / Tender</span>
				<span class="tender-ref-sep">·</span>
				<span class="tender-ref-no">{{ $refNo }}</span>
			</div>

			<!-- Tender title -->
			<h2 class="tender-title-main mb-3">{{ $tajukTender }}</h2>

			<!-- Detail fields grid -->
			<div class="row g-3 pb-3">

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="d-flex flex-column gap-1">
						<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
						<span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $ptj }}</span>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="d-flex flex-column gap-1">
						<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Tempoh Sah
							Laku Tawaran</span>
						<span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $tempohSahLaku }}</span>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="d-flex flex-column gap-1">
						<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Sah Laku
							Tawaran Tamat</span>
						<span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $sahLakuTamat }}</span>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="d-flex flex-column gap-1">
						<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
						<div>
							<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-semibold"
								style="background:#fef3c7; color:#b45309; font-size:0.78rem;">
								<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="10"></circle>
									<polyline points="12 6 12 12 16 14"></polyline>
								</svg>
								Penyediaan Pemilihan Akhir Pembekal
							</span>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Tab Navigation -->
		<div class="tender-top-tabs">
			<ul class="nav nav-tabs" role="tablist">

				<li class="nav-item" role="presentation">
					<a class="nav-link active" href="#tab-penyediaan-mesyuarat" data-bs-toggle="tab" role="tab"
						aria-controls="tab-penyediaan-mesyuarat" aria-selected="true">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
							<line x1="16" y1="2" x2="16" y2="6"></line>
							<line x1="8" y1="2" x2="8" y2="6"></line>
							<line x1="3" y1="10" x2="21" y2="10"></line>
						</svg>
						Penyediaan Mesyuarat
					</a>
				</li>

				<li class="nav-item" role="presentation">
					<a class="nav-link" href="#tab-kertas-taklimat" data-bs-toggle="tab" role="tab"
						aria-controls="tab-kertas-taklimat" aria-selected="false">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
							<line x1="16" y1="13" x2="8" y2="13"></line>
							<line x1="16" y1="17" x2="8" y2="17"></line>
						</svg>
						Paparan Kertas Taklimat
					</a>
				</li>

				<li class="nav-item" role="presentation">
					<a class="nav-link" href="#tab-muktamad-pembekal" data-bs-toggle="tab" role="tab"
						aria-controls="tab-muktamad-pembekal" aria-selected="false">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="9" cy="7" r="4"></circle>
							<polyline points="23 11 17 17 14 14"></polyline>
						</svg>
						Memuktamadkan Pemilihan Pembekal
					</a>
				</li>

				<li class="nav-item" role="presentation">
					<a class="nav-link" href="#tab-kertas-keputusan" data-bs-toggle="tab" role="tab"
						aria-controls="tab-kertas-keputusan" aria-selected="false">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="9 11 12 14 22 4"></polyline>
							<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
						</svg>
						Kertas Keputusan
					</a>
				</li>

			</ul>
		</div>

	</div>

	<!-- TAB CONTENT -->
	<div class="tab-content">

		<!-- Tab 1: Penyediaan Mesyuarat -->
		<div class="tab-pane active" id="tab-penyediaan-mesyuarat" role="tabpanel">
			<div class="content-card p-4">
				<div class="section-title-bar mb-3">PERINCIAN MESYUARAT</div>
				<div id="mesyuarat-alert" class="alert d-none py-2 px-3 mb-3"></div>

				<div class="table-responsive">
					<table class="table table-bordered align-middle mb-2" id="tbl-penyediaan-mesyuarat">
						<thead class="text-white text-center" style="background-color:#2d3e84;">
							<tr>
								<th style="width:42px;"></th>
								<th>Bil Mesyuarat <span class="text-danger">*</span></th>
								<th>Tarikh Mesyuarat <span class="text-danger">*</span></th>
								<th>Tajuk Agenda <span class="text-danger">*</span></th>
								<th>Tempat <span class="text-danger">*</span></th>
								<th>No. Kod Kertas <span class="text-danger">*</span></th>
								<th>Status <span class="text-danger">*</span></th>
								<th>Catatan</th>
							</tr>
						</thead>
						<tbody id="mesyuarat-body"></tbody>
					</table>
				</div>

				<div class="d-flex justify-content-end gap-2 mb-3">
					<button type="button" id="btn-tambah-mesyuarat" class="btn btn-success">Tambah</button>
					<button type="button" id="btn-hapus-mesyuarat" class="btn btn-warning text-white">Hapus</button>
				</div>

				<div class="d-flex justify-content-end gap-3 mt-3">
					<button type="button" id="btn-simpan-mesyuarat" class="btn btn-outline-secondary">Simpan</button>
					<button type="button" id="btn-hantar-mesyuarat" class="btn btn-selangor">Hantar</button>
				</div>
			</div>
		</div>

		<!-- Tab 2: Paparan Kertas Taklimat -->
		<div class="tab-pane" id="tab-kertas-taklimat" role="tabpanel">
			<div class="content-card p-4">
				<h6 class="fw-bold text-dark mb-1">Paparan Kertas Taklimat</h6>
				<p class="text-muted small mb-3">Senarai lampiran yang dihantar dari modul Perakuan Jabatan (muat turun sahaja).</p>

				@if (($taklimatAttachments ?? collect())->isEmpty())
					<div class="alert d-flex align-items-center gap-2 mb-0"
						style="background:#eff6ff; border:1px solid #bae6fd; color:#0369a1; border-radius:10px;">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="8" x2="12" y2="12"></line>
							<line x1="12" y1="16" x2="12.01" y2="16"></line>
						</svg>
						Tiada lampiran ditemui daripada Paparan Kertas Taklimat.
					</div>
				@else
					<div class="table-responsive">
						<table class="table table-bordered align-middle mb-0">
							<thead class="text-white text-center" style="background-color:#2d3e84;">
								<tr>
									<th>Kandungan</th>
									<th>Nama Fail</th>
									<th width="160">Tindakan</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($taklimatAttachments as $attachment)
									<tr>
										<td>{{ $attachment['kandungan'] }}</td>
										<td>{{ $attachment['file_name'] }}</td>
										<td class="text-center">
											<a href="{{ $attachment['download_url'] }}" class="btn btn-sm btn-outline-primary">
												Muat Turun
											</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		</div>

		<!-- Tab 3: Memuktamadkan Pemilihan Pembekal -->
		<div class="tab-pane" id="tab-muktamad-pembekal" role="tabpanel">
			<div class="content-card p-4">
				<h6 class="fw-bold text-dark mb-1">Memuktamadkan Pemilihan Pembekal</h6>
				<p class="text-muted small mb-3">Muktamadkan senarai pembekal yang dipilih untuk perolehan ini.</p>
				<div class="alert d-flex align-items-center gap-2 mb-0"
					style="background:#eff6ff; border:1px solid #bae6fd; color:#0369a1; border-radius:10px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10"></circle>
						<line x1="12" y1="8" x2="12" y2="12"></line>
						<line x1="12" y1="16" x2="12.01" y2="16"></line>
					</svg>
					Bahagian ini belum tersedia. Sila hubungi pentadbir sistem untuk maklumat lanjut.
				</div>
			</div>
		</div>

		<!-- Tab 4: Kertas Keputusan -->
		<div class="tab-pane" id="tab-kertas-keputusan" role="tabpanel">
			<div class="content-card p-4">
				<div id="kertas-keputusan-alert" class="alert d-none py-2 px-3 mb-3"></div>

				<div class="section-title-bar mb-2">SYARAT-SYARAT</div>
				<div class="mb-3">
					<div class="d-flex align-items-center gap-4 mb-2">
						<label class="fw-semibold text-dark mb-0">Dengan Syarat</label>
						<div class="form-check form-check-inline mb-0">
							<input class="form-check-input" type="radio" name="kk_dengan_syarat" id="kk_syarat_ya" value="1"
								{{ optional($kertasKeputusan)->dengan_syarat === true ? 'checked' : '' }}>
							<label class="form-check-label" for="kk_syarat_ya">Ya</label>
						</div>
						<div class="form-check form-check-inline mb-0">
							<input class="form-check-input" type="radio" name="kk_dengan_syarat" id="kk_syarat_tidak" value="0"
								{{ optional($kertasKeputusan)->dengan_syarat === false ? 'checked' : '' }}>
							<label class="form-check-label" for="kk_syarat_tidak">Tidak</label>
						</div>
					</div>
					<label class="form-label mb-1">Jika Ya, sila nyatakan</label>
					<textarea id="kk_syarat_nyatakan" class="form-control" rows="2"
						style="max-width:420px;">{{ old('syarat_nyatakan', optional($kertasKeputusan)->syarat_nyatakan) }}</textarea>
				</div>

				<div class="section-title-bar mb-2">PENGESYORAN</div>
				<div class="mb-3">
					<label class="form-label mb-1">Catatan</label>
					<textarea id="kk_pengesyoran_catatan" class="form-control" rows="2"
						style="max-width:420px;"
						placeholder="Pengesyoran Urusetia Perolehan adalah berdasarkan keputusan Jawatankuasa Penilaian...">{{ old('pengesyoran_catatan', optional($kertasKeputusan)->pengesyoran_catatan) }}</textarea>
				</div>

				<div class="section-title-bar mb-2">JUSTIFIKASI</div>
				<div class="mb-3" style="max-width:520px;">
					<label class="form-label mb-1">Justifikasi Pemilihan Pembekal <span class="text-danger">*</span></label>
					<select id="kk_justifikasi" class="form-select">
						<option value="">-- Sila Pilih --</option>
						@php
							$kkJustifikasi = old('justifikasi_pemilihan_pembekal', optional($kertasKeputusan)->justifikasi_pemilihan_pembekal);
							$kkJustifikasiOptions = [
							    'Harga dalam lingkungan harga indikatif jabatan',
							    'Spesifikasi teknikal memenuhi keperluan',
							    'Tempoh penghantaran menepati keperluan projek',
							    'Prestasi dan rekod pembekal memuaskan',
							];
						@endphp
						@foreach ($kkJustifikasiOptions as $option)
							<option value="{{ $option }}" {{ $kkJustifikasi === $option ? 'selected' : '' }}>{{ $option }}</option>
						@endforeach
					</select>
				</div>

				<div class="section-title-bar mb-2">KERTAS KEPUTUSAN (OPTIONAL)</div>
				<div class="mb-3">
					<div class="d-flex align-items-center gap-3 flex-wrap">
						<label class="form-label mb-0">Lampiran</label>
						<button type="button" id="kk_btn_upload" class="btn btn-sm btn-kt-teal">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
								<polyline points="17 8 12 3 7 8"></polyline>
								<line x1="12" y1="3" x2="12" y2="15"></line>
							</svg>
						</button>
						<input type="file" id="kk_lampiran" class="d-none">
						@if (!empty(optional($kertasKeputusan)->lampiran_file_path))
							<a id="kk_existing_download" href="{{ asset(optional($kertasKeputusan)->lampiran_file_path) }}" class="small text-primary"
								target="_blank">
								(Memuat naik kertas keputusan yang telah ditandatangani oleh Ketua Unit PBM)
							</a>
							<div class="form-check ms-2 mb-0">
								<input class="form-check-input" type="checkbox" id="kk_buang_lampiran" value="1">
								<label class="form-check-label small" for="kk_buang_lampiran">Buang lampiran</label>
							</div>
						@else
							<span id="kk_existing_download" class="small text-muted">Tiada lampiran</span>
						@endif
						<span id="kk_new_file_name" class="small text-success"></span>
					</div>
				</div>

				<div class="section-title-bar mb-2">KEPUTUSAN</div>
				<div class="mb-3" style="max-width:260px;">
					<select id="kk_keputusan" class="form-select">
						<option value="">Keputusan</option>
						@php $kkKeputusan = old('keputusan', optional($kertasKeputusan)->keputusan); @endphp
						<option value="Lulus" {{ $kkKeputusan === 'Lulus' ? 'selected' : '' }}>Lulus</option>
						<option value="Tawaran Semula" {{ $kkKeputusan === 'Tawaran Semula' ? 'selected' : '' }}>Tawaran Semula</option>
						<option value="Batal" {{ $kkKeputusan === 'Batal' ? 'selected' : '' }}>Batal</option>
						<option value="Tangguh" {{ $kkKeputusan === 'Tangguh' ? 'selected' : '' }}>Tangguh</option>
					</select>
				</div>

				<div class="section-title-bar mb-2">CATATAN</div>
				<div class="mb-3">
					<label class="form-label mb-1">Catatan</label>
					<textarea id="kk_catatan" class="form-control" rows="2"
						style="max-width:420px;">{{ old('catatan', optional($kertasKeputusan)->catatan) }}</textarea>
				</div>

				<div class="d-flex justify-content-end gap-2 mt-4">
					<button type="button" class="btn btn-kt-teal" id="kk_btn_simpan">Simpan</button>
					<button type="button" class="btn btn-selangor" id="kk_btn_hantar">Hantar</button>
				</div>
			</div>
		</div>

	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			const tenderParam = "{{ request('tender', optional($tender)->uuid) }}";
			const rowsFromBackend = @json($meetings ?? []);
			const saveUrl = "{{ route('jawatankuasa.perolehan.mesyuarat.simpan') }}";
			const submitUrl = "{{ route('jawatankuasa.perolehan.mesyuarat.hantar') }}";
			const kertasKeputusanSaveUrl = "{{ route('jawatankuasa.perolehan.kertas_keputusan.simpan') }}";
			const kertasKeputusanSubmitUrl = "{{ route('jawatankuasa.perolehan.kertas_keputusan.hantar') }}";
			const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content');
			const $tbody = $('#mesyuarat-body');
			const $alert = $('#mesyuarat-alert');

			function showAlert(message, type) {
				$alert.removeClass('d-none alert-success alert-danger').addClass(type === 'success' ? 'alert-success' : 'alert-danger').text(message);
			}

			function escapeHtml(text) {
				return String(text ?? '').replace(/[&<>"']/g, function(m) {
					return ({
						'&': '&amp;',
						'<': '&lt;',
						'>': '&gt;',
						'"': '&quot;',
						"'": '&#039;'
					})[m];
				});
			}

			function rowTemplate(row = {}) {
				const status = row.status === 'Selesai' ? 'Selesai' : 'Belum Selesai';
				return '<tr>' +
					'<td class="text-center"><input type="checkbox" class="form-check-input row-check"></td>' +
					'<td><input type="text" class="form-control form-control-sm bil_mesyuarat" value="' + escapeHtml(row.bil_mesyuarat || '') + '"></td>' +
					'<td><input type="date" class="form-control form-control-sm tarikh_mesyuarat" value="' + escapeHtml(row.tarikh_mesyuarat || '') + '"></td>' +
					'<td><input type="text" class="form-control form-control-sm tajuk_agenda" value="' + escapeHtml(row.tajuk_agenda || '') + '"></td>' +
					'<td><input type="text" class="form-control form-control-sm tempat" value="' + escapeHtml(row.tempat || '') + '"></td>' +
					'<td><input type="text" class="form-control form-control-sm no_kod_kertas" value="' + escapeHtml(row.no_kod_kertas || '') + '"></td>' +
					'<td><select class="form-select form-select-sm status"><option value="Belum Selesai"' + (status === 'Belum Selesai' ? ' selected' : '') + '>Belum Selesai</option><option value="Selesai"' + (status === 'Selesai' ? ' selected' : '') + '>Selesai</option></select></td>' +
					'<td><input type="text" class="form-control form-control-sm catatan" value="' + escapeHtml(row.catatan || '') + '"></td>' +
					'</tr>';
			}

			function ensureRows() {
				if ($tbody.find('tr').length === 0) {
					$tbody.append(rowTemplate());
				}
			}

			function getRowsPayload() {
				const rows = [];
				$tbody.find('tr').each(function() {
					const $tr = $(this);
					rows.push({
						bil_mesyuarat: $tr.find('.bil_mesyuarat').val().trim(),
						tarikh_mesyuarat: $tr.find('.tarikh_mesyuarat').val(),
						tajuk_agenda: $tr.find('.tajuk_agenda').val().trim(),
						tempat: $tr.find('.tempat').val().trim(),
						no_kod_kertas: $tr.find('.no_kod_kertas').val().trim(),
						status: $tr.find('.status').val(),
						catatan: $tr.find('.catatan').val().trim()
					});
				});
				return rows;
			}

			function validateRows(rows) {
				if (!tenderParam) {
					return 'Tender tidak ditemui pada URL.';
				}

				if (!rows.length) {
					return 'Sila tambah sekurang-kurangnya satu perincian mesyuarat.';
				}

				for (let i = 0; i < rows.length; i++) {
					const row = rows[i];
					if (!row.bil_mesyuarat || !row.tarikh_mesyuarat || !row.tajuk_agenda || !row.tempat || !row.no_kod_kertas || !row.status) {
						return 'Sila lengkapkan medan wajib pada baris ' + (i + 1) + '.';
					}
				}

				return null;
			}

			function persistRows(url, successMessage) {
				const rows = getRowsPayload();
				const error = validateRows(rows);

				if (error) {
					showAlert(error, 'error');
					return;
				}

				$.ajax({
					url: url,
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrfToken
					},
					data: {
						tender: tenderParam,
						rows: rows
					},
					success: function(resp) {
						showAlert(resp.message || successMessage, 'success');
					},
					error: function(xhr) {
						const message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
						showAlert(message, 'error');
					}
				});
			}

			if (rowsFromBackend.length > 0) {
				rowsFromBackend.forEach(function(row) {
					$tbody.append(rowTemplate(row));
				});
			} else {
				ensureRows();
			}

			$('#btn-tambah-mesyuarat').on('click', function() {
				$tbody.append(rowTemplate());
			});

			$('#btn-hapus-mesyuarat').on('click', function() {
				$tbody.find('input.row-check:checked').closest('tr').remove();
				ensureRows();
			});

			$('#btn-simpan-mesyuarat').on('click', function() {
				persistRows(saveUrl, 'Perincian mesyuarat berjaya disimpan.');
			});

			$('#btn-hantar-mesyuarat').on('click', function() {
				persistRows(submitUrl, 'Perincian mesyuarat berjaya dihantar.');
			});

			function showKertasKeputusanAlert(message, type) {
				$('#kertas-keputusan-alert')
					.removeClass('d-none alert-success alert-danger')
					.addClass(type === 'success' ? 'alert-success' : 'alert-danger')
					.text(message);
			}

			$('#kk_btn_upload').on('click', function() {
				$('#kk_lampiran').trigger('click');
			});

			$('#kk_lampiran').on('change', function() {
				const fileName = this.files && this.files.length ? this.files[0].name : '';
				$('#kk_new_file_name').text(fileName ? ('Fail dipilih: ' + fileName) : '');
			});

			function buildKertasKeputusanPayload() {
				const fd = new FormData();
				fd.append('_token', csrfToken || '');
				fd.append('tender', tenderParam);

				const syarat = $('input[name="kk_dengan_syarat"]:checked').val();
				if (typeof syarat !== 'undefined') {
					fd.append('dengan_syarat', syarat);
				}
				fd.append('syarat_nyatakan', ($('#kk_syarat_nyatakan').val() || '').toString().trim());
				fd.append('pengesyoran_catatan', ($('#kk_pengesyoran_catatan').val() || '').toString().trim());
				fd.append('justifikasi_pemilihan_pembekal', ($('#kk_justifikasi').val() || '').toString().trim());
				fd.append('keputusan', ($('#kk_keputusan').val() || '').toString().trim());
				fd.append('catatan', ($('#kk_catatan').val() || '').toString().trim());
				fd.append('buang_lampiran', $('#kk_buang_lampiran').is(':checked') ? '1' : '0');

				const fileInput = document.getElementById('kk_lampiran');
				if (fileInput && fileInput.files && fileInput.files.length) {
					fd.append('lampiran', fileInput.files[0]);
				}

				return fd;
			}

			function submitKertasKeputusan(url, successMessage) {
				$.ajax({
					url: url,
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrfToken || ''
					},
					data: buildKertasKeputusanPayload(),
					processData: false,
					contentType: false,
					success: function(resp) {
						showKertasKeputusanAlert(resp.message || successMessage, 'success');
					},
					error: function(xhr) {
						let message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
						if (xhr?.responseJSON?.errors) {
							message = Object.values(xhr.responseJSON.errors).flat().join(' ');
						}
						showKertasKeputusanAlert(message, 'error');
					}
				});
			}

			$('#kk_btn_simpan').on('click', function() {
				submitKertasKeputusan(kertasKeputusanSaveUrl, 'Kertas keputusan berjaya disimpan.');
			});

			$('#kk_btn_hantar').on('click', function() {
				submitKertasKeputusan(kertasKeputusanSubmitUrl, 'Kertas keputusan berjaya dihantar.');
			});
		});
	</script>
@endsection
