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

		.mp-hint {
			background: #eff6ff;
			border: 1px solid #bae6fd;
			color: #0369a1;
			border-radius: 8px;
			padding: 8px 12px;
			font-size: 0.85rem;
		}

		.mp-item-row {
			cursor: pointer;
		}

		.mp-item-row.mp-item-active {
			background-color: #e7f1ff !important;
		}
	</style>
@endsection

@section('content')
	@php
		$refNo = optional($tender)->no_tender ?: optional($tender)->ref_number ?: '-';
		$tajukTender = optional($tender)->name ?: '-';
		$ptj = optional(optional($tender)->tenderer)->name ?: '-';
		$tarikhSerahan = !empty(optional($tender)->submission_datetime)
		    ? \Carbon\Carbon::parse($tender->submission_datetime)
		    : null;
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
								<th style="width:110px;">Bil. Mesyuarat <span class="text-danger">*</span></th>
								<th style="width:130px;">Tarikh Mesyuarat <span class="text-danger">*</span></th>
								<th style="width:100px;">Masa <span class="text-danger">*</span></th>
								<th style="width:160px;">Tempat <span class="text-danger">*</span></th>
								<th style="width:120px;">No. Kod Kertas <span class="text-danger">*</span></th>
								<th style="min-width:240px;">Agenda <span class="text-danger">*</span></th>
								<th style="width:130px;">Status <span class="text-danger">*</span></th>
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
				<h6 class="fw-bold text-dark mb-1">Seksyen Laporan</h6>
				<p class="text-muted small mb-3">Senarai lampiran yang dimuat naik dalam modul Perakuan Jabatan. Di sini anda hanya
					boleh <strong>papar</strong> (tiada muat naik / padam).</p>

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
									<th width="140">Tindakan</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($taklimatAttachments as $attachment)
									<tr>
										<td>{{ $attachment['kandungan'] }}</td>
										<td>{{ $attachment['file_name'] }}</td>
										<td class="text-center">
											@if (!empty($attachment['papar_url']) && $attachment['papar_url'] !== '#')
												<a href="{{ $attachment['papar_url'] }}" class="btn btn-sm btn-outline-primary" target="_blank"
													rel="noopener noreferrer">Papar</a>
											@else
												<span class="text-muted small">—</span>
											@endif
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
				<div id="mp-alert" class="alert d-none py-2 px-3 mb-3"></div>

				@if (!$tender)
					<div class="alert alert-warning mb-0">Sila pilih tender untuk mengisi borang ini.</div>
				@elseif (($pemilihanItems ?? collect())->isEmpty())
					<div class="alert alert-info mb-0">Tiada item untuk dipaparkan. Sila muat semula halaman.</div>
				@else
					<h6 class="fw-bold text-dark mb-3">Memuktamadkan Pemilihan Pembekal</h6>

					<div class="section-title-bar mb-2">KEPUTUSAN PIHAK BERKUASA MELULUS</div>
					<div class="row g-3 mb-4">
						<div class="col-md-6">
							<label class="form-label small fw-semibold">Keputusan Mesyuarat <span class="text-danger">*</span></label>
							<select id="mp_keputusan_mesyuarat" class="form-select form-select-sm">
								<option value="">-- Sila Pilih --</option>
								@foreach ($pemilihanOpts['keputusan_mesyuarat'] as $opt)
									<option value="{{ $opt }}" @selected(($pemilihanHeader['keputusan_mesyuarat'] ?? '') === $opt)>{{ $opt }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label small fw-semibold">Kaedah Memuktamadkan Pembekal <span
									class="text-danger">*</span></label>
							<select id="mp_kaedah_memuktamadkan" class="form-select form-select-sm">
								<option value="">-- Sila Pilih --</option>
								@foreach ($pemilihanOpts['kaedah_memuktamadkan_pembekal'] as $opt)
									<option value="{{ $opt }}" @selected(($pemilihanHeader['kaedah_memuktamadkan_pembekal'] ?? '') === $opt)>{{ $opt }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label small fw-semibold">Pemilihan Berdasarkan <span class="text-danger">*</span></label>
							@php $mpPemilihanBerdasarkan = ($pemilihanHeader['pemilihan_berdasarkan'] ?? ''); @endphp
							<div class="d-flex align-items-center flex-wrap gap-3 pt-1">
								<div class="form-check mb-0">
									<input class="form-check-input" type="radio" name="mp_pemilihan_berdasarkan"
										id="mp_pemilihan_berdasarkan_item" value="1 item"
										{{ $mpPemilihanBerdasarkan === '1 item' ? 'checked' : '' }}>
									<label class="form-check-label small" for="mp_pemilihan_berdasarkan_item">1 item</label>
								</div>
								<div class="form-check mb-0">
									<input class="form-check-input" type="radio" name="mp_pemilihan_berdasarkan"
										id="mp_pemilihan_berdasarkan_pakej" value="Pakej"
										{{ $mpPemilihanBerdasarkan === 'Pakej' ? 'checked' : '' }}>
									<label class="form-check-label small" for="mp_pemilihan_berdasarkan_pakej">Pakej</label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label small fw-semibold">LOI/LOA Disediakan Oleh <span class="text-danger">*</span></label>
							@php $mpLoiLoaOleh = ($pemilihanHeader['loi_loa_disediakan_oleh'] ?? ''); @endphp
							<div class="d-flex flex-column gap-2 pt-1">
								<div class="form-check mb-0">
									<input class="form-check-input" type="radio" name="mp_loi_loa_oleh" id="mp_loi_loa_urusetia"
										value="Urusetia atau Setiausaha Sebut Harga"
										{{ $mpLoiLoaOleh === 'Urusetia atau Setiausaha Sebut Harga' ? 'checked' : '' }}>
									<label class="form-check-label small" for="mp_loi_loa_urusetia">Urusetia atau Setiausaha Sebut Harga</label>
								</div>
								<div class="form-check mb-0">
									<input class="form-check-input" type="radio" name="mp_loi_loa_oleh" id="mp_loi_loa_lembaga"
										value="Lembaga Perolehan" {{ $mpLoiLoaOleh === 'Lembaga Perolehan' ? 'checked' : '' }}>
									<label class="form-check-label small" for="mp_loi_loa_lembaga">Lembaga Perolehan</label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label small fw-semibold">Bil. Mesyuarat <span class="text-danger">*</span></label>
							<input type="text" id="mp_bil_mesyuarat" class="form-control form-control-sm"
								value="{{ $pemilihanHeader['bil_mesyuarat'] ?? '' }}">
						</div>
						<div class="col-md-6">
							<label class="form-label small fw-semibold">No. Kod <span class="text-danger">*</span></label>
							<input type="text" id="mp_no_kod" class="form-control form-control-sm"
								value="{{ $pemilihanHeader['no_kod'] ?? '' }}">
						</div>
					</div>

					<div class="section-title-bar mb-2">SENARAI ITEM</div>
					<div class="mp-hint mb-2">Sila klik pada item untuk melihat senarai pembekal.</div>
					<div class="table-responsive mb-2">
						<table class="table table-bordered table-sm align-middle mb-0">
							<thead class="text-white text-center" style="background-color:#2d3e84;">
								<tr>
									<th style="width:36px;"></th>
									<th>Item</th>
									<th style="min-width:110px;">Jenis Item</th>
									<th style="min-width:110px;">Unit Ukuran</th>
									<th style="min-width:110px;">Jenis Harga</th>
									<th style="min-width:100px;">Dibatalkan</th>
									<th style="min-width:110px;">Pembekal Dipilih</th>
									<th style="min-width:90px;">Kuantiti</th>
								</tr>
							</thead>
							<tbody id="mp-items-body"></tbody>
						</table>
					</div>
					<div class="d-flex justify-content-end mb-4">
						<button type="button" class="btn btn-kt-teal btn-sm" id="mp_btn_terpakai_semua">Terpakai untuk semua</button>
					</div>

					<div class="section-title-bar mb-2">SENARAI PEMBEKAL</div>
					<div class="mp-hint mb-2">Semua pembekal yang melepasi Markah Lulus Keseluruhan (teknikal dan kewangan) akan
						dijemput
						untuk menyertai bidaan.</div>
					<div class="table-responsive mb-3">
						<table class="table table-bordered table-sm align-middle mb-0">
							<thead class="text-white text-center" style="background-color:#2d3e84;">
								<tr>
									<th>Bil</th>
									<th>Status Bumiputra</th>
									<th>Harga Tawaran (RM)</th>
									<th>Jumlah Skor</th>
									<th>Kedudukan Penilaian Teknikal Kewangan</th>
									<th>Status Pendaftaran MOF</th>
									<th colspan="3">Maklumat Tambahan</th>
									<th>Keputusan Urusetia</th>
									<th style="min-width:180px;">Catatan Urusetia</th>
									<th id="mp-th-selection" class="d-none">Pemilihan</th>
									<th id="mp-th-catatan-zon" class="d-none" style="min-width:180px;">Catatan Mengikut Zon</th>
								</tr>
								<tr>
									<th colspan="6"></th>
									<th class="small">Prestasi Pembekal</th>
									<th class="small">Lembaga Pengarah</th>
									<th class="small">CIDB</th>
									<th id="mp-th-empty-right" colspan="2"></th>
								</tr>
							</thead>
							<tbody id="mp-pembekal-body"></tbody>
						</table>
					</div>

					<div class="form-check mb-4" id="mp_sahkan_wrap">
						<input class="form-check-input" type="checkbox" id="mp_sahkan_layak" value="1"
							{{ !empty($pemilihanHeader['sahkan_layak_bidaan']) ? 'checked' : '' }}>
						<label class="form-check-label small" for="mp_sahkan_layak">Saya mengesahkan petender diatas layak untuk
							menyertai
							Bidaan.</label>
					</div>

					<div class="d-flex justify-content-end gap-2">
						<button type="button" class="btn btn-kt-teal" id="mp_btn_simpan">Simpan</button>
						<button type="button" class="btn btn-selangor" id="mp_btn_hantar">Hantar</button>
					</div>
				@endif
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
					<textarea id="kk_syarat_nyatakan" class="form-control" rows="2" style="max-width:420px;">{{ old('syarat_nyatakan', optional($kertasKeputusan)->syarat_nyatakan) }}</textarea>
				</div>

				<div class="section-title-bar mb-2">PENGESYORAN</div>
				<div class="mb-3">
					<label class="form-label mb-1">Catatan</label>
					<textarea id="kk_pengesyoran_catatan" class="form-control" rows="2" style="max-width:420px;"
					 placeholder="Pengesyoran Urusetia Perolehan adalah berdasarkan keputusan Jawatankuasa Penilaian...">{{ old('pengesyoran_catatan', optional($kertasKeputusan)->pengesyoran_catatan) }}</textarea>
				</div>

				<div class="section-title-bar mb-2">JUSTIFIKASI</div>
				<div class="mb-3" style="max-width:520px;">
					<label class="form-label mb-1">Justifikasi Pemilihan Pembekal <span class="text-danger">*</span></label>
					<select id="kk_justifikasi" class="form-select">
						<option value="">-- Sila Pilih --</option>
						@php
							$kkJustifikasi = old(
							    'justifikasi_pemilihan_pembekal',
							    optional($kertasKeputusan)->justifikasi_pemilihan_pembekal,
							);
							$kkJustifikasiOptions = [
							    'Harga dalam lingkungan harga indikatif jabatan',
							    'Spesifikasi teknikal memenuhi keperluan',
							    'Tempoh penghantaran menepati keperluan projek',
							    'Prestasi dan rekod pembekal memuaskan',
							];
						@endphp
						@foreach ($kkJustifikasiOptions as $option)
							<option value="{{ $option }}" {{ $kkJustifikasi === $option ? 'selected' : '' }}>{{ $option }}
							</option>
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
							<a id="kk_existing_download" href="{{ asset(optional($kertasKeputusan)->lampiran_file_path) }}"
								class="small text-primary" target="_blank">
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
					<textarea id="kk_catatan" class="form-control" rows="2" style="max-width:420px;">{{ old('catatan', optional($kertasKeputusan)->catatan) }}</textarea>
				</div>

				<div class="d-flex justify-content-end gap-2 mt-4">
					<button type="button" class="btn btn-kt-teal" id="kk_btn_simpan">Simpan</button>
					<button type="button" class="btn btn-selangor" id="kk_btn_hantar">Hantar</button>
				</div>
			</div>
		</div>

	</div>

	@if ($tender && ($pemilihanVendors ?? collect())->isNotEmpty())
		@foreach ($pemilihanVendors as $vendor)
			@include('components.vendor-cidb-meta', [
				'vendor' => $vendor,
				'trigger' => 'none',
			])
		@endforeach
	@endif
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
			const pemilihanSimpanUrl = "{{ route('jawatankuasa.perolehan.pemilihan_pembekal.simpan') }}";
			const pemilihanHantarUrl = "{{ route('jawatankuasa.perolehan.pemilihan_pembekal.hantar') }}";
			const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content');
			const $tbody = $('#mesyuarat-body');
			const $alert = $('#mesyuarat-alert');

			function showAlert(message, type) {
				$alert.removeClass('d-none alert-success alert-danger').addClass(type === 'success' ? 'alert-success' :
					'alert-danger').text(message);
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
					'<td><input type="text" class="form-control form-control-sm bil_mesyuarat" value="' + escapeHtml(
						row.bil_mesyuarat || '') + '"></td>' +
					'<td><input type="date" class="form-control form-control-sm tarikh_mesyuarat" value="' +
					escapeHtml(row.tarikh_mesyuarat || '') + '"></td>' +
					'<td><input type="time" class="form-control form-control-sm masa" value="' +
					escapeHtml(row.masa || '') + '"></td>' +
					'<td><input type="text" class="form-control form-control-sm tempat" value="' + escapeHtml(row
						.tempat || '') + '"></td>' +
					'<td><input type="text" class="form-control form-control-sm no_kod_kertas" value="' + escapeHtml(
						row.no_kod_kertas || '') + '"></td>' +
					'<td><input type="text" class="form-control form-control-sm tajuk_agenda" value="' + escapeHtml(row
						.tajuk_agenda || '') + '"></td>' +
					'<td><select class="form-select form-select-sm status"><option value="Belum Selesai"' + (status ===
						'Belum Selesai' ? ' selected' : '') + '>Belum Selesai</option><option value="Selesai"' + (
						status === 'Selesai' ? ' selected' : '') + '>Selesai</option></select></td>' +
					'<td><input type="text" class="form-control form-control-sm catatan" value="' + escapeHtml(row
						.catatan || '') + '"></td>' +
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
						masa: $tr.find('.masa').val(),
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
					if (!row.bil_mesyuarat || !row.tarikh_mesyuarat || !row.masa || !row.tajuk_agenda || !row.tempat || !row
						.no_kod_kertas || !row.status) {
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

			@if ($tender && ($pemilihanItems ?? collect())->isNotEmpty())
				let pemilihanState = {
					header: @json($pemilihanHeader ?? []),
					items: @json($pemilihanItems ?? [])
				};
				let mpSelectedItemIndex = 0;
				const $mpAlert = $('#mp-alert');

				function mpShowAlert(message, type) {
					if (!$mpAlert.length) {
						return;
					}
					$mpAlert.removeClass('d-none alert-success alert-danger')
						.addClass(type === 'success' ? 'alert-success' : 'alert-danger')
						.text(message || '');
				}

				function mpFlushSuppliersToState() {
					const item = pemilihanState.items[mpSelectedItemIndex];
					if (!item || !item.petenders) {
						return;
					}
					$('#mp-pembekal-body tr').each(function() {
						const i = parseInt($(this).data('pet-idx'), 10);
						const p = item.petenders[i];
						if (!p) {
							return;
						}
						p.tindakan_disiplin = ($(this).find('.mp-pet-disiplin').val() || '').toString();
						p.selected_for_selection = $(this).find('.mp-pet-selection').is(':checked');
						p.catatan_mengikut_zon = ($(this).find('.mp-pet-catatan-zon').val() || '').toString();
					});
				}

				function mpFlushItemsToState() {
					$('#mp-items-body tr.mp-item-row').each(function() {
						const idx = parseInt($(this).data('idx'), 10);
						const it = pemilihanState.items[idx];
						if (!it) {
							return;
						}
						it.dibatalkan = ($(this).find('.mp-item-dib').val() || 'Tidak').toString();
						it.pembekal_dipilih = parseInt($(this).find('.mp-item-pembekal').val(), 10) || 0;
						it.kuantiti = ($(this).find('.mp-item-kuantiti').val() || '0').toString();
					});
				}

				function mpReadHeader() {
					return {
						keputusan_mesyuarat: ($('#mp_keputusan_mesyuarat').val() || '').toString(),
						kaedah_memuktamadkan_pembekal: ($('#mp_kaedah_memuktamadkan').val() || '').toString(),
						pemilihan_berdasarkan: ($('input[name="mp_pemilihan_berdasarkan"]:checked').val() || '')
							.toString(),
						loi_loa_disediakan_oleh: ($('input[name="mp_loi_loa_oleh"]:checked').val() || '').toString(),
						bil_mesyuarat: ($('#mp_bil_mesyuarat').val() || '').toString(),
						no_kod: ($('#mp_no_kod').val() || '').toString(),
						sahkan_layak_bidaan: $('#mp_sahkan_layak').is(':checked'),
					};
				}

				function mpBuildPayload() {
					mpFlushSuppliersToState();
					mpFlushItemsToState();
					pemilihanState.header = mpReadHeader();
					return {
						tender: tenderParam,
						header: pemilihanState.header,
						items: pemilihanState.items
					};
				}

				function mpFormatMoney(n) {
					const x = Number(n);
					if (Number.isNaN(x)) {
						return '0.00';
					}
					return x.toLocaleString('en-MY', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					});
				}

				function mpRenderItems() {
					const $b = $('#mp-items-body');
					$b.empty();
					pemilihanState.items.forEach(function(it, idx) {
						const dib = it.dibatalkan === 'Ya' ? 'Ya' : 'Tidak';
						const $tr = $('<tr class="mp-item-row"></tr>').attr('data-idx', idx);
						if (idx === mpSelectedItemIndex) {
							$tr.addClass('mp-item-active');
						}
						$tr.append(
							'<td class="text-center"><input type="checkbox" class="form-check-input mp-item-check"></td>'
						);
						$tr.append('<td><span class="small">' + escapeHtml(it.perihal_item || '') +
							'</span></td>');
						$tr.append('<td class="small">' + escapeHtml(it.jenis_item || '') + '</td>');
						$tr.append('<td class="small">' + escapeHtml(it.unit_ukuran || '') + '</td>');
						$tr.append('<td class="small">' + escapeHtml(it.jenis_harga || '') + '</td>');
						const dibSel = '<select class="form-select form-select-sm mp-item-dib">' +
							'<option value="Tidak"' + (dib === 'Tidak' ? ' selected' : '') +
							'>Tidak</option>' +
							'<option value="Ya"' + (dib === 'Ya' ? ' selected' : '') + '>Ya</option></select>';
						$tr.append('<td>' + dibSel + '</td>');
						$tr.append(
							'<td><input type="number" min="0" class="form-control form-control-sm mp-item-pembekal" value="' +
							escapeHtml(String(it.pembekal_dipilih ?? 0)) + '"></td>');
						$tr.append(
							'<td><input type="number" min="0" step="0.0001" class="form-control form-control-sm mp-item-kuantiti" value="' +
							escapeHtml(String(it.kuantiti ?? '')) + '"></td>');
						$b.append($tr);
					});
				}

				function mpCidbCell(p) {
					if (!p.vendor_id || !p.cidb_has_meta) {
						return '<td class="text-center"><span class="text-muted small">—</span></td>';
					}

					return '<td class="text-center">' +
						'<button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 d-inline-flex align-items-center justify-content-center" ' +
						'data-bs-toggle="modal" data-bs-target="#cidbMetaModal-' + escapeHtml(String(p.vendor_id)) +
						'" title="Maklumat CIDB">' +
						'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
						'<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>' +
						'</button></td>';
				}

				function mpRenderSuppliers() {
					const item = pemilihanState.items[mpSelectedItemIndex];
					const $b = $('#mp-pembekal-body');
					$b.empty();
					if (!item || !item.petenders) {
						return;
					}
					const kaedah = ($('#mp_kaedah_memuktamadkan').val() || '').toString();
					const showSelection = kaedah === 'Pemilihan Terus' || kaedah ===
						'Pemilihan Lebih Daripada Satu Syarikat';
					const showCatatanZon = kaedah === 'Pemilihan Lebih Daripada Satu Syarikat';
					item.petenders.forEach(function(p, i) {
						const lp = p.lembaga_pengarah_papar_url ?
							'<a href="' + escapeHtml(p.lembaga_pengarah_papar_url) +
							'" class="btn btn-sm btn-outline-primary py-0 px-2" target="_blank" rel="noopener noreferrer" title="Papar">' +
							'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>' :
							'<span class="text-muted small">—</span>';
						const selectionCell = showSelection ?
							'<td class="text-center"><input type="checkbox" class="form-check-input mp-pet-selection"' +
							(p
								.selected_for_selection ? ' checked' : '') + '></td>' : '';
						const catatanZonCell = showCatatanZon ?
							'<td><textarea class="form-control form-control-sm mp-pet-catatan-zon" rows="2">' +
							escapeHtml(p.catatan_mengikut_zon || '') + '</textarea></td>' : '';
						const row = '<tr data-pet-idx="' + i + '">' +
							'<td class="text-center">' + escapeHtml(p.bil_label || '') + '</td>' +
							'<td class="text-center">' + escapeHtml(p.status_bumiputra || '') + '</td>' +
							'<td class="text-end">' + escapeHtml(mpFormatMoney(p.harga_tawaran)) + '</td>' +
							'<td class="text-end">' + escapeHtml(String(p.jumlah_skor ?? '')) + '</td>' +
							'<td class="text-center">' + escapeHtml(String(p.kedudukan_penilaian ?? '')) +
							'</td>' +
							'<td class="text-center small">' + escapeHtml(p.status_mof || '') + '</td>' +
							'<td><textarea class="form-control form-control-sm mp-pet-disiplin" rows="2">' +
							escapeHtml(p
								.tindakan_disiplin || '') + '</textarea></td>' +
							'<td class="text-center">' + lp + '</td>' +
							mpCidbCell(p) +
							'<td class="small">' + escapeHtml(p.keputusan_urusetia || '') + '</td>' +
							'<td class="small">' + escapeHtml(p.catatan_urusetia || '') + '</td>' +
							selectionCell +
							catatanZonCell +
							'</tr>';
						$b.append(row);
					});
				}

				function mpSyncSupplierTableColumns() {
					const kaedah = ($('#mp_kaedah_memuktamadkan').val() || '').toString();
					const showSelection = kaedah === 'Pemilihan Terus' || kaedah ===
						'Pemilihan Lebih Daripada Satu Syarikat';
					const showCatatanZon = kaedah === 'Pemilihan Lebih Daripada Satu Syarikat';
					$('#mp-th-selection').toggleClass('d-none', !showSelection);
					$('#mp-th-catatan-zon').toggleClass('d-none', !showCatatanZon);
					const rightColspan = 2 + (showSelection ? 1 : 0) + (showCatatanZon ? 1 : 0);
					$('#mp-th-empty-right').attr('colspan', rightColspan);
				}

				$('#mp-items-body').on('click', '.mp-item-row', function(e) {
					if ($(e.target).closest('input,select,textarea,button,a,label').length) {
						return;
					}
					mpFlushSuppliersToState();
					mpSelectedItemIndex = parseInt($(this).data('idx'), 10);
					$('#mp-items-body tr.mp-item-row').removeClass('mp-item-active');
					$(this).addClass('mp-item-active');
					mpRenderSuppliers();
				});

				$('#mp_btn_terpakai_semua').on('click', function() {
					mpFlushItemsToState();
					let src = 0;
					$('#mp-items-body tr.mp-item-row').each(function(i) {
						if ($(this).find('.mp-item-check').is(':checked')) {
							src = i;
							return false;
						}
					});
					const base = pemilihanState.items[src];
					if (!base) {
						return;
					}
					pemilihanState.items.forEach(function(it) {
						it.dibatalkan = base.dibatalkan;
						it.pembekal_dipilih = base.pembekal_dipilih;
						it.kuantiti = base.kuantiti;
					});
					mpRenderItems();
					mpRenderSuppliers();
					mpShowAlert('Nilai daripada baris sumber telah diterapkan kepada semua item.', 'success');
				});

				function mpPostJson(url, successMessage) {
					const body = mpBuildPayload();
					$.ajax({
						url: url,
						method: 'POST',
						headers: {
							'X-CSRF-TOKEN': csrfToken || '',
							'Accept': 'application/json',
							'Content-Type': 'application/json'
						},
						data: JSON.stringify(body),
						success: function(resp) {
							mpShowAlert(resp.message || successMessage, 'success');
						},
						error: function(xhr) {
							let message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
							if (xhr?.responseJSON?.errors) {
								message = Object.values(xhr.responseJSON.errors).flat().join(' ');
							}
							mpShowAlert(message, 'error');
						}
					});
				}

				$('#mp_btn_simpan').on('click', function() {
					mpPostJson(pemilihanSimpanUrl, 'Data berjaya disimpan.');
				});
				$('#mp_btn_hantar').on('click', function() {
					mpPostJson(pemilihanHantarUrl, 'Data berjaya dihantar.');
				});

				$('#mp_kaedah_memuktamadkan').on('change', function() {
					mpFlushSuppliersToState();
					mpSyncSupplierTableColumns();
					mpRenderSuppliers();
					mpSyncSahkanBidaanVisibility();
				});

				function mpSyncSahkanBidaanVisibility() {
					const kaedah = ($('#mp_kaedah_memuktamadkan').val() || '').toString();
					const isBidaan = kaedah === 'Bidaan';
					$('#mp_sahkan_wrap').toggleClass('d-none', !isBidaan);
					if (!isBidaan) {
						$('#mp_sahkan_layak').prop('checked', false);
					}
				}

				mpSyncSupplierTableColumns();
				mpRenderItems();
				mpRenderSuppliers();
				mpSyncSahkanBidaanVisibility();
			@endif
		});
	</script>
@endsection
