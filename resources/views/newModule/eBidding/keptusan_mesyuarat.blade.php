@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">

	<style>
		/* =====================
															SECTION BAR
															===================== */
		.section-title-bar {
			background: #f3f5f8;
			border: 1px solid #e9edf3;
			border-radius: 10px;
			padding: 14px 14px;
			font-weight: 800;
			text-transform: uppercase;
			margin-top: 14px;
			margin-bottom: 12px;
		}


		/* =====================
															TABLE STYLE
															===================== */
		.table thead th {
			text-align: center;
			vertical-align: middle;
		}

		.thead-red {
			background: #A4161A !important;
			color: #fff !important;
		}

		.table tbody td {
			vertical-align: middle;
		}

		/* =====================
															RED TABLE HEADER
															===================== */
		.table thead th {
			background-color: #B11217 !important;
			color: #ffffff !important;
			text-align: left;
			vertical-align: middle;
			font-weight: 600;
			border-color: #B11217 !important;
		}

		/* checkbox column alignment */
		.table thead th:first-child {
			text-align: center;
			width: 40px;
		}
	</style>
@endsection

@section('content')
	@php
		$visibleTabs = $visibleTabs ?? ['penyediaan', 'taklimat', 'pemilihan', 'pengesyoran', 'jadual-bidaan', 'keputusan'];
		// Default buka tab Pemilihan Pembekal dahulu.
		$tabOrder = ['pengesyoran', 'penyediaan', 'taklimat', 'pemilihan', 'jadual-bidaan', 'keputusan'];
		$activeTab =
		    collect($tabOrder)->first(function ($tabKey) use ($visibleTabs) {
		        return in_array($tabKey, $visibleTabs, true);
		    }) ?? 'pengesyoran';
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
		<span class="text-muted small">E-Bidding</span>
		<span class="text-muted small">/</span>
		<span class="text-muted small fw-semibold text-dark">Keputusan Mesyuarat</span>
	</div>

	<div id="pageDetail">

		{{-- HEADER --}}
		<div class="tender-header-card mb-4">

			<div class="tender-page-header">

				{{-- Ref label row --}}
				<div class="tender-ref-label">
					<span class="tender-type-label">Sebut Harga / Tender</span>
					<span class="tender-ref-sep">·</span>
					<span class="tender-ref-no" id="dNo">QT210000000023741</span>
				</div>

				{{-- Title --}}
				<h2 class="tender-title-main mb-3">KERJA-KERJA MENAIK TARAF SUNGAI BATU DAN KAWASAN SEKITAR, SELANGOR DARUL
					EHSAN</h2>

				{{-- Detail fields grid --}}
				<div class="row g-3 pb-3">

					<div class="col-12 col-sm-6 col-lg-3">
						<div class="d-flex flex-column gap-1">
							<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
							<span class="fw-semibold text-dark" style="font-size:0.88rem;" id="dPtj">JABATAN PENGAIRAN
								DAN SALIRAN</span>
						</div>
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						<div class="d-flex flex-column gap-1">
							<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Tempoh Sah
								Laku Tawaran</span>
							<span class="fw-semibold text-dark" style="font-size:0.88rem;" id="dTempoh">90 Hari</span>
						</div>
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						<div class="d-flex flex-column gap-1">
							<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Sah Laku
								Tawaran Tamat</span>
							<span class="fw-semibold text-dark" style="font-size:0.88rem;" id="dTamat">17/01/2027</span>
						</div>
					</div>

					<div class="col-12 col-sm-6 col-lg-3">
						<div class="d-flex flex-column gap-1">
							<span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
							<div>
								<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-semibold"
									style="background:#fef3c7; color:#b45309; font-size:0.78rem;" id="dStatus">
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

			{{-- Tab Navigation --}}
			<div class="tender-top-tabs">
				<ul class="nav nav-tabs" id="mainTabs" role="tablist">
					@if (in_array('penyediaan', $visibleTabs, true))
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $activeTab === 'penyediaan' ? 'active' : '' }}" id="tab-penyediaan-btn"
								data-bs-toggle="tab" data-bs-target="#tab-penyediaan" type="button" role="tab">
								Penyediaan Mesyuarat
							</button>
						</li>
					@endif

					@if (in_array('pengesyoran', $visibleTabs, true))
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $activeTab === 'pengesyoran' ? 'active' : '' }}" id="tab-pengesyoran-btn"
								data-bs-toggle="tab" data-bs-target="#tab-pengesyoran" type="button" role="tab">
								Pemilihan Pembekal
							</button>
						</li>
					@endif

					@if (in_array('taklimat', $visibleTabs, true))
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $activeTab === 'taklimat' ? 'active' : '' }}" id="tab-taklimat-btn"
								data-bs-toggle="tab" data-bs-target="#tab-taklimat" type="button" role="tab">
								{{ !empty($isRestrictedEbidding) ? 'Paparan Kertas Taklimat' : 'Paparan Kertas Taklimat' }}
							</button>
						</li>
					@endif

					@if (in_array('pemilihan', $visibleTabs, true))
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $activeTab === 'pemilihan' ? 'active' : '' }}" id="tab-pemilihan-btn"
								data-bs-toggle="tab" data-bs-target="#tab-pemilihan" type="button" role="tab">
								Memuktamadkan Pemilihan Pembekal
							</button>
						</li>
					@endif

					@if (in_array('jadual-bidaan', $visibleTabs, true))
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $activeTab === 'jadual-bidaan' ? 'active' : '' }}" id="tab-jadual-bidaan-btn"
								data-bs-toggle="tab" data-bs-target="#tab-jadual-bidaan" type="button" role="tab">
								Penyediaan Jadual Bidaan
							</button>
						</li>
					@endif

					@if (in_array('keputusan', $visibleTabs, true))
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $activeTab === 'keputusan' ? 'active' : '' }}" id="tab-keputusan-btn"
								data-bs-toggle="tab" data-bs-target="#tab-keputusan" type="button" role="tab">
								Kertas Keputusan
							</button>
						</li>
					@endif

				</ul>
			</div>

		</div>

		{{-- TAB CONTENT --}}
		<div class="tab-content" id="mainTabContent">

			{{-- ============ TAB 1: Penyediaan Mesyuarat ============ --}}
			@if (in_array('penyediaan', $visibleTabs, true))
				<div class="tab-pane fade {{ $activeTab === 'penyediaan' ? 'show active' : '' }}" id="tab-penyediaan"
					role="tabpanel">
					<div class="content-card p-4">
						<div class="section-title-bar">PERINCIAN MESYUARAT</div>

						<div class="table-responsive">
							<table class="table table-bordered align-middle mb-2">
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
								<tbody>
									<tr>
										<td class="text-center">
											<input type="checkbox" class="form-check-input">
										</td>
										<td><input type="text" class="form-control form-control-sm" placeholder=""></td>
										<td><input type="date" class="form-control form-control-sm" value="2021-10-18">
										</td>
										<td><input type="text" class="form-control form-control-sm" placeholder=""></td>
										<td><input type="text" class="form-control form-control-sm" value="Bilik Mesyuarat Tingkat 3"></td>
										<td><input type="text" class="form-control form-control-sm" placeholder=""></td>
										<td>
											<select class="form-select form-select-sm">
												<option selected>Belum Selesai</option>
												<option>Selesai</option>
											</select>
										</td>
										<td><input type="text" class="form-control form-control-sm" placeholder=""></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="d-flex justify-content-end gap-2 mb-3">
							<button type="button" class="btn btn-success">Tambah</button>
							<button type="button" class="btn btn-warning text-white">Hapus</button>
						</div>

						<div class="d-flex justify-content-end gap-3 mt-3">
							<button type="button" class="btn btn-outline-secondary">Simpan</button>
							<button type="button" class="btn btn-selangor">Hantar</button>
						</div>
					</div>
				</div>
			@endif

			{{-- ============ TAB 2: Paparan Kertas Taklimat ============ --}}
			@if (in_array('taklimat', $visibleTabs, true))
				<div class="tab-pane fade {{ $activeTab === 'taklimat' ? 'show active' : '' }}" id="tab-taklimat"
					role="tabpanel">
					<div class="content-card p-4">
						<div id="eb-taklimat-alert" class="alert d-none py-2 px-3 mb-3"></div>
						<h6 class="fw-bold mb-2">SEKSYEN LAPORAN</h6>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="text-white text-center" style="background-color:#2d3e84;">
									<tr>
										<th style="width:38px;"></th>
										<th>Kandungan</th>
										<th width="220">Tindakan</th>
									</tr>
								</thead>
								<tbody id="eb-taklimat-body"></tbody>
							</table>
						</div>

						<div class="d-flex justify-content-end gap-2 mb-3">
							<button type="button" class="btn btn-kt-teal" id="eb_taklimat_download_all">Muat Turun Semua</button>
							<button type="button" class="btn btn-selangor" id="eb_taklimat_add_row">Tambah</button>
							<button type="button" class="btn btn-warning text-white" id="eb_taklimat_delete_rows">Hapus</button>
						</div>

						<div class="section-grey">Catatan</div>
						<div class="row g-3 align-items-start">
							<div class="col-md-6">
								<textarea class="form-control" rows="4" id="eb_taklimat_catatan">{{ old('catatan', optional($ebTaklimat)->catatan) }}</textarea>
							</div>
							<div class="col-md-6 d-flex justify-content-end align-items-end">
								<div class="d-flex gap-2">
									<button class="btn btn-outline-secondary px-4" type="button" id="eb_taklimat_simpan">Simpan</button>
									<button class="btn btn-selangor px-4" type="button" id="eb_taklimat_hantar">Hantar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif

			{{-- ============ TAB 3: Memuktamadkan Pemilihan Pembekal ============ --}}
			@if (in_array('pemilihan', $visibleTabs, true))
				<div class="tab-pane fade {{ $activeTab === 'pemilihan' ? 'show active' : '' }}" id="tab-pemilihan"
					role="tabpanel">
					<div class="content-card p-4">
						<h6 class="fw-bold">KEPUTUSAN PIHAK BERKUASA MELULUS</h6>
						<div class="row mb-3">
							<div class="col-md-4">
								<label class="form-label">Keputusan Mesyuarat</label>
								<select class="form-select">
									<option selected>Pemilihan Pembekal</option>
									<option>Penilaian Semula</option>
									<option>Iklan Semula</option>
									<option>Kemukakan kepada Pihak Berkuasa Yang Lebih Tinggi</option>
									<option>Batal</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label">Kaedah Memuktamadkan Pembekal</label>
								<select class="form-select">
									<option selected>Bidaan</option>
									<option>Pemilihan Terus</option>
									<option>Pemilihan Lebih Daripada Satu Syarikat</option>
								</select>
							</div>
						</div>

						<h6 class="fw-bold">SENARAI ITEM</h6>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="text-white text-center" style="background-color:#2d3e84;">
									<tr>
										<th></th>
										<th>Item</th>
										<th>Jenis Item</th>
										<th>Unit Ukuran</th>
										<th>Jenis Harga</th>
										<th>Dibatalkan</th>
										<th>Pembekal Dipilih</th>
										<th>Kuantiti</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-center"><input type="checkbox"></td>
										<td>Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
										<td>Perkhidmatan</td>
										<td>Activity Unit</td>
										<td>Biasa Standard</td>
										<td>
											<select class="form-select">
												<option selected>Tidak</option>
												<option>Ya</option>
											</select>
										</td>
										<td>2</td>
										<td>1</td>
									</tr>
								</tbody>
							</table>
							<button class="btn btn-success">Terapkan untuk semua</button>
						</div>

						<h6 class="fw-bold mt-4">SENARAI PEMBEKAL</h6>
						<div class="table-responsive">
							<table class="table table-bordered text-center">
								<thead class="text-white" style="background-color:#2d3e84;">
									<tr>
										<th>Bil</th>
										<th>Status Bumiputra</th>
										<th>Harga Tawaran (RM)</th>
										<th>Jumlah Skor</th>
										<th>Kedudukan Penilaian Teknikal Kewangan</th>
										<th>Status Pendaftaran MOF</th>
										<th colspan="2">Maklumat Tambahan</th>
										<th>Keputusan oleh Urusetia</th>
										<th>Catatan Urusetia</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>2/2</td>
										<td>Ya</td>
										<td>360,000.00</td>
										<td>96.43</td>
										<td>1</td>
										<td>Aktif</td>
										<td>Prestasi Pembekal</td>
										<td><button class="btn btn-light"><i class="bi bi-file-earmark"></i></button></td>
										<td>Disyorkan</td>
										<td></td>
									</tr>
									<tr>
										<td>1/2</td>
										<td>Tidak</td>
										<td>330,000.00</td>
										<td>94.53</td>
										<td>2</td>
										<td>Aktif</td>
										<td></td>
										<td><button class="btn btn-light"><i class="bi bi-file-earmark"></i></button></td>
										<td>Disyorkan</td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="form-check mb-3">
							<input class="form-check-input" type="checkbox" id="pengakuan">
							<label class="form-check-label" for="pengakuan">Saya mengesahkan petender diatas layak untuk
								menyertai Bidaan.</label>
						</div>

						<div class="d-flex justify-content-end gap-2">
							<button class="btn btn-success">Simpan</button>
							<button class="btn btn-selangor">Hantar</button>
						</div>
					</div>
				</div>
			@endif

			{{-- ============ TAB: Pemilihan Pembekal ============ --}}
			@if (in_array('pengesyoran', $visibleTabs, true))
				<div class="tab-pane fade {{ $activeTab === 'pengesyoran' ? 'show active' : '' }}" id="tab-pengesyoran"
					role="tabpanel">
					<div class="content-card p-4">
						<div id="eb-pengesyoran-alert" class="alert d-none py-2 px-3 mb-3"></div>

						<div class="section-grey">Senarai Item</div>
						<div class="text-primary small mb-2" style="font-style:italic;">
							Sila klik pada item untuk melihat senarai pembekal
						</div>

						<div class="table-responsive mb-3">
							<table class="table table-bordered table-blue align-middle">
								<thead class="text-center">
									<tr>
										<th style="width:36px;"></th>
										<th>Item</th>
										<th style="width:140px;">Jenis Item</th>
										<th style="width:120px;">Unit Ukuran</th>
										<th style="width:120px;">Jenis Harga</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($agencyPemilihanItems ?? collect() as $idx => $it)
										<tr class="{{ $idx === 0 ? 'table-active' : '' }}">
											<td class="text-center"><input type="checkbox" {{ $idx === 0 ? 'checked' : '' }} disabled></td>
											<td>{{ $it['item'] }}</td>
											<td class="text-center">{{ $it['jenis_item'] }}</td>
											<td class="text-center">{{ $it['unit_ukuran'] }}</td>
											<td class="text-center">{{ $it['jenis_harga'] }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<div class="section-grey">Senarai Pembekal</div>
						<div class="table-responsive mb-3">
							<table class="table table-bordered table-blue text-center align-middle">
								<thead>
									<tr>
										<th style="width:60px;">Bil</th>
										<th style="width:110px;">Status Bumiputra</th>
										<th style="width:120px;">Harga Tawaran (RM)</th>
										<th style="width:90px;">Jumlah Skor</th>
										<th style="width:130px;">Kedudukan Penilaian Teknikal</th>
										<th style="width:150px;">Status Pendaftaran MOF</th>
										<th colspan="2" style="width:220px;">Maklumat Tambahan</th>
										<th style="width:180px;">Kaedah Memuktamadkan Pembekal oleh SULP</th>
										<th style="width:120px;">Harga Bidaan (RM)</th>
									</tr>
									<tr>
										<th colspan="6"></th>
										<th>Prestasi Pembekal</th>
										<th>Lembaga Pengarah</th>
										<th colspan="2"></th>
									</tr>
								</thead>
								<tbody>
									@php
										$firstItem = collect($agencyPemilihanItems ?? [])->first();
										$petenderRows = $firstItem['petenders'] ?? [];
									@endphp
									@foreach ($petenderRows as $row)
										<tr>
											<td>{{ $row['bil_label'] }}</td>
											<td>{{ $row['status_bumiputra'] }}</td>
											<td>{{ number_format((float) $row['harga_tawaran'], 2) }}</td>
											<td>{{ $row['jumlah_skor'] }}</td>
											<td>{{ $row['kedudukan_penilaian'] }}</td>
											<td>{{ $row['status_mof'] }}</td>
											<td>{{ $row['tindakan_disiplin'] }}</td>
											<td>
												@if (!empty($row['lembaga_pengarah_url']))
													<a href="{{ $row['lembaga_pengarah_url'] }}" target="_blank" rel="noopener noreferrer"
														class="btn btn-light btn-sm"><i class="bi bi-file-earmark"></i></a>
												@endif
											</td>
											<td>{{ $row['kaedah_sulp'] }}</td>
											<td>{{ number_format((float) $row['harga_bidaan'], 2) }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<div class="section-grey">Catatan</div>
						<div class="row g-3 align-items-start">
							<div class="col-md-6">
								<textarea class="form-control" rows="4" id="eb_pengesyoran_catatan">{{ old('catatan', optional($pengesyoran)->catatan) }}</textarea>
								{{-- <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="sahBidaan">
                                <label class="form-check-label" for="sahBidaan">Saya mengesahkan Bidaan</label>
                            </div> --}}
							</div>
							<div class="col-md-6 d-flex justify-content-end align-items-end">
								<div class="d-flex gap-2">
									<button class="btn btn-outline-secondary px-4" type="button" id="eb_btn_pengesyoran_simpan">Simpan</button>
									<button class="btn btn-selangor px-4" type="button" id="eb_btn_pengesyoran_hantar">Hantar</button>
								</div>
							</div>
						</div>

					</div>
				</div>
			@endif

			{{-- ============ TAB: Penyediaan Jadual Bidaan ============ --}}
			@if (in_array('jadual-bidaan', $visibleTabs, true))
				<div class="tab-pane fade {{ $activeTab === 'jadual-bidaan' ? 'show active' : '' }}" id="tab-jadual-bidaan"
					role="tabpanel">
					<div class="content-card p-4">
						<div id="eb-jadual-alert" class="alert d-none py-2 px-3 mb-3"></div>
						<h6 class="fw-bold">Penyediaan Jadual Bidaan</h6>

						<div class="row mb-3">
							<div class="col-md-3">
								<label class="form-label">Tarikh Bidaan Mula<span class="text-danger">*</span></label>
								<input type="date" class="form-control" id="eb_jadual_tarikh_mula"
									value="{{ old('tarikh_bidaan_mula', optional($jadualBidaan)->tarikh_bidaan_mula ? optional($jadualBidaan->tarikh_bidaan_mula)->format('Y-m-d') : '') }}">
							</div>
							<div class="col-md-3">
								<label class="form-label">Masa Bidaan Mula<span class="text-danger">*</span></label>
								<input type="time" class="form-control" id="eb_jadual_masa_mula"
									value="{{ old('masa_bidaan_mula', optional($jadualBidaan)->masa_bidaan_mula) }}">
							</div>
							<div class="col-md-3">
								<label class="form-label">Tarikh Bidaan Tamat<span class="text-danger">*</span></label>
								<input type="date" class="form-control" id="eb_jadual_tarikh_tamat"
									value="{{ old('tarikh_bidaan_tamat', optional($jadualBidaan)->tarikh_bidaan_tamat ? optional($jadualBidaan->tarikh_bidaan_tamat)->format('Y-m-d') : '') }}">
							</div>
							<div class="col-md-3">
								<label class="form-label">Masa Bidaan Tamat<span class="text-danger">*</span></label>
								<input type="time" class="form-control" id="eb_jadual_masa_tamat"
									value="{{ old('masa_bidaan_tamat', optional($jadualBidaan)->masa_bidaan_tamat) }}">
							</div>
						</div>

						<div class="d-flex justify-content-end gap-2">
							<button class="btn btn-outline-secondary" type="button" id="eb_jadual_simpan">Simpan</button>
							<button class="btn btn-selangor" type="button" id="eb_jadual_mula">Mula Bidaan</button>
						</div>
					</div>
				</div>
			@endif

			{{-- ============ TAB 4: Kertas Keputusan ============ --}}
			@if (in_array('keputusan', $visibleTabs, true))
				<div class="tab-pane fade {{ $activeTab === 'keputusan' ? 'show active' : '' }}" id="tab-keputusan"
					role="tabpanel">
					<div class="content-card p-4">
						<div class="section-title-bar">KERTAS KEPUTUSAN</div>
						<div class="section-header text-uppercase">Syarat-Syarat</div>
						<div class="py-3">
							<div class="row g-3 align-items-center">
								<div class="col-md-3">
									<label class="form-label mb-0">Dengan Syarat</label>
								</div>
								<div class="col-md-9">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="syarat" id="syaratYa" value="Ya">
										<label class="form-check-label" for="syaratYa">Ya</label>
									</div>
									<div class="form-check form-check-inline ms-3">
										<input class="form-check-input" type="radio" name="syarat" id="syaratTidak" value="Tidak" checked>
										<label class="form-check-label" for="syaratTidak">Tidak</label>
									</div>
								</div>

								<div class="col-md-3">
									<label class="form-label">Jika Ya, sila nyatakan</label>
								</div>
								<div class="col-md-9">
									<textarea class="form-control" rows="3"></textarea>
								</div>
							</div>
						</div>

						{{-- PENGESYORAN --}}
						<div class="section-header text-uppercase mt-3">Pengesyoran</div>
						<div class="py-3">
							<div class="row g-3">
								<div class="col-md-3">
									<label class="form-label mb-0">Catatan</label>
								</div>
								<div class="col-md-9">
									<textarea class="form-control" rows="3"
									 placeholder="Pengesyoran Urusetia Perolehan adalah berdasarkan keputusan Jawatankuasa Penilaian...."></textarea>
								</div>
							</div>
						</div>

						{{-- JUSTIFIKASI --}}
						<div class="section-header text-uppercase mt-3">Justifikasi</div>
						<div class="py-3">
							<div class="row g-3 align-items-center">
								<div class="col-md-3">
									<label class="form-label mb-0">Justifikasi Pemilihan Pembekal <span class="text-danger">*</span></label>
								</div>
								<div class="col-md-9">
									<select class="form-select">
										<option selected>Harga dalam lingkungan harga indikatif jabatan</option>
										<option>Prestasi kerja terdahulu</option>
										<option>Kepakaran teknikal</option>
									</select>
								</div>
							</div>
						</div>

						{{-- KERTAS KEPUTUSAN (OPTIONAL) --}}
						<div class="section-header text-uppercase mt-3">Kertas Keputusan (Optional)</div>
						<div class="py-4">
							<div class="row justify-content-start align-items-center g-3">
								<div class="col-md-3">
									<div class="text-center">
										<div class="fw-semibold mb-1">Lampiran</div>
										<small class="text-info d-block" style="line-height:1.2">
											(Memuat naik kertas keputusan yang telah<br>ditanda tangan oleh kesemua ahli
											PBM)
										</small>
									</div>
								</div>
								<div class="col-md-9">
									<div class="d-flex align-items-center gap-3">
										<label class="btn btn-outline-success d-inline-flex align-items-center px-3">
											<i class="bi bi-cloud-arrow-up me-2"></i> Muat Naik
											<input type="file" class="d-none">
										</label>
									</div>
								</div>
							</div>
						</div>

						{{-- KEPUTUSAN --}}
						<div class="section-header text-uppercase mt-3">Keputusan</div>
						<div class="py-3">
							<div class="row g-3 align-items-center">
								<div class="col-md-3">
									<label class="form-label mb-0">Keputusan</label>
								</div>
								<div class="col-md-9">
									<select class="form-select">
										<option selected>Keputusan</option>
										<option>Lulus</option>
										<option>Tawaran Semula</option>
										<option>Batal</option>
										<option>Tangguh</option>
									</select>
								</div>
							</div>
						</div>

						{{-- CATATAN --}}
						<div class="section-header text-uppercase mt-3">Catatan</div>
						<div class="py-3">
							<div class="row g-3">
								<div class="col-md-3">
									<label class="form-label mb-0">Catatan</label>
								</div>
								<div class="col-md-9">
									<textarea class="form-control" rows="3"></textarea>
								</div>
							</div>
						</div>

						{{-- ACTIONS --}}
						<div class="d-flex justify-content-end gap-3 mt-2">
							<button type="button" class="btn btn-outline-secondary">Simpan</button>
							<button type="button" class="btn btn-selangor">Hantar</button>
						</div>
					</div>
				</div>
			@endif

		</div>{{-- end tab-content --}}

	</div>{{-- end pageDetail --}}
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			const isAgencyReadOnly = @json(((int) ($currentStage ?? 1)) === 2);
			const tenderId = @json(optional($tender)->id);
			const taklimatSaveUrl = @json(optional($tender)->id ? route('eBidding.kertasTaklimat.simpan', ['id' => $tender->id]) : '');
			const taklimatSubmitUrl = @json(optional($tender)->id ? route('eBidding.kertasTaklimat.hantar', ['id' => $tender->id]) : '');
			const jadualSaveUrl = @json(optional($tender)->id ? route('eBidding.jadualBidaan.simpan', ['id' => $tender->id]) : '');
			const jadualMulaUrl = @json(optional($tender)->id ? route('eBidding.jadualBidaan.mula', ['id' => $tender->id]) : '');
			const ebiddingIndexUrl = @json(route('eBidding.index'));
			let taklimatRows = @json($ebTaklimatRows ?? []);
			let deletedTaklimatItemIds = [];
			let deletedTaklimatFileIds = [];

			const saveUrl = @json(optional($tender)->id ? route('eBidding.pengesyoran.simpan', ['id' => $tender->id]) : '');
			const submitUrl = @json(optional($tender)->id ? route('eBidding.pengesyoran.hantar', ['id' => $tender->id]) : '');
			const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content');
			const $alert = $('#eb-pengesyoran-alert');
			const $taklimatAlert = $('#eb-taklimat-alert');
			const $jadualAlert = $('#eb-jadual-alert');

			function esc(text) {
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

			function showTaklimatAlert(message, type) {
				if (!$taklimatAlert.length) return;
				$taklimatAlert.removeClass('d-none alert-success alert-danger')
					.addClass(type === 'success' ? 'alert-success' : 'alert-danger')
					.text(message || '');
			}

			function showJadualAlert(message, type) {
				if (!$jadualAlert.length) return;
				$jadualAlert.removeClass('d-none alert-success alert-danger')
					.addClass(type === 'success' ? 'alert-success' : 'alert-danger')
					.text(message || '');
			}

			function renderTaklimatRows() {
				const $body = $('#eb-taklimat-body');
				if (!$body.length) return;
				$body.empty();

				taklimatRows.forEach(function(row, idx) {
					let fileLinks = '';
					(row.files || []).forEach(function(file) {
						if (file && file.url) {
							fileLinks += '<div><a href="' + esc(file.url) +
								'" class="text-primary eb-file-link" target="_blank" rel="noopener noreferrer">Muat Turun</a>';
							if (file.can_delete && file.source === 'ebidding') {
								fileLinks +=
									' <a href="#" class="text-danger small eb-file-delete" data-row-idx="' +
									idx + '" data-file-id="' + file.id + '">Padam</a>';
							}
							fileLinks += '</div>';
						}
					});
					if (!fileLinks) {
						fileLinks = '<span class="text-muted small">Tiada fail</span>';
					}

					const uploadHtml = row.can_upload ?
						('<div class="mt-1"><label class="btn btn-sm btn-outline-primary mb-0">Muat Naik<input type="file" class="d-none eb-upload-input" data-row-idx="' +
							idx +
							'" multiple></label><span class="small text-muted ms-2 eb-upload-name" id="eb_upload_name_' +
							idx + '"></span></div>') :
						'';
					const deleteCheck = row.can_delete ?
						'<input type="checkbox" class="form-check-input eb-row-check" data-row-idx="' + idx +
						'">' : '';
					const kandunganHtml = row.can_delete ?
						('<input type="text" class="form-control form-control-sm eb-kandungan-input" data-row-idx="' +
							idx + '" value="' + esc(row.kandungan || '') + '" placeholder="Nama lampiran">') :
						esc(row.kandungan || '');

					const line = '<tr>' +
						'<td class="text-center">' + deleteCheck + '</td>' +
						'<td>' + kandunganHtml + '</td>' +
						'<td class="text-center">' + fileLinks + uploadHtml + '</td>' +
						'</tr>';
					$body.append(line);
				});
			}

			function syncTaklimatRowNamesFromUi() {
				$('.eb-kandungan-input').each(function() {
					const idx = parseInt($(this).data('row-idx'), 10);
					if (Number.isNaN(idx) || !taklimatRows[idx]) return;
					taklimatRows[idx].kandungan = ($(this).val() || '').toString().trim();
				});
			}

			function buildTaklimatPayload() {
				syncTaklimatRowNamesFromUi();
				const fd = new FormData();
				fd.append('_token', csrfToken || '');
				fd.append('catatan', ($('#eb_taklimat_catatan').val() || '').toString().trim());
				deletedTaklimatItemIds.forEach(function(id) {
					fd.append('deleted_item_ids[]', id);
				});
				deletedTaklimatFileIds.forEach(function(id) {
					fd.append('deleted_file_ids[]', id);
				});

				taklimatRows.forEach(function(row, idx) {
					fd.append('rows[' + idx + '][id]', row.id || '');
					fd.append('rows[' + idx + '][kandungan]', row.kandungan || '');
					const input = document.querySelector('.eb-upload-input[data-row-idx="' + idx + '"]');
					if (input && input.files && input.files.length) {
						Array.from(input.files).forEach(function(file) {
							fd.append('rows[' + idx + '][files][]', file);
						});
					}
				});
				return fd;
			}

			function postTaklimat(url, successMessage) {
				if (!url) {
					showTaklimatAlert('URL tindakan tidak dijumpai.', 'error');
					return;
				}
				$.ajax({
					url: url,
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrfToken || ''
					},
					data: buildTaklimatPayload(),
					processData: false,
					contentType: false,
					success: function(resp) {
						showTaklimatAlert(resp.message || successMessage, 'success');
						deletedTaklimatItemIds = [];
						deletedTaklimatFileIds = [];
					},
					error: function(xhr) {
						let message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
						if (xhr?.responseJSON?.errors) {
							message = Object.values(xhr.responseJSON.errors).flat().join(' ');
						}
						showTaklimatAlert(message, 'error');
					}
				});
			}

			$('#eb_taklimat_add_row').on('click', function() {
				taklimatRows.push({
					id: null,
					slot_key: null,
					kandungan: 'Dokumen Tambahan',
					can_upload: true,
					can_delete: true,
					files: []
				});
				renderTaklimatRows();
			});

			$('#eb_taklimat_delete_rows').on('click', function() {
				const selectedIdxs = [];
				$('#eb-taklimat-body .eb-row-check:checked').each(function() {
					selectedIdxs.push(parseInt($(this).data('row-idx'), 10));
				});
				selectedIdxs.sort((a, b) => b - a).forEach(function(idx) {
					const row = taklimatRows[idx];
					if (!row) return;
					if (row.id) {
						deletedTaklimatItemIds.push(row.id);
					}
					(row.files || []).forEach(function(f) {
						if (f && f.can_delete && f.id) deletedTaklimatFileIds.push(f.id);
					});
					taklimatRows.splice(idx, 1);
				});
				renderTaklimatRows();
			});

			$(document).on('change', '.eb-upload-input', function() {
				const idx = parseInt($(this).data('row-idx'), 10);
				const names = this.files ? Array.from(this.files).map(f => f.name).join(', ') : '';
				$('#eb_upload_name_' + idx).text(names ? ('Dipilih: ' + names) : '');
			});

			$(document).on('input', '.eb-kandungan-input', function() {
				const idx = parseInt($(this).data('row-idx'), 10);
				if (Number.isNaN(idx) || !taklimatRows[idx]) return;
				taklimatRows[idx].kandungan = ($(this).val() || '').toString();
			});

			$(document).on('click', '.eb-file-delete', function(e) {
				e.preventDefault();
				const rowIdx = parseInt($(this).data('row-idx'), 10);
				const fileId = parseInt($(this).data('file-id'), 10);
				if (!Number.isNaN(fileId)) {
					deletedTaklimatFileIds.push(fileId);
				}
				const row = taklimatRows[rowIdx];
				if (row) {
					row.files = (row.files || []).filter(f => parseInt(f.id, 10) !== fileId);
				}
				renderTaklimatRows();
			});

			$('#eb_taklimat_download_all').on('click', function() {
				let count = 0;
				$('#eb-taklimat-body .eb-file-link').each(function() {
					const href = $(this).attr('href');
					if (href) {
						window.open(href, '_blank', 'noopener');
						count++;
					}
				});
				if (!count) {
					showTaklimatAlert('Tiada fail untuk dimuat turun.', 'error');
				}
			});

			$('#eb_taklimat_simpan').on('click', function() {
				postTaklimat(taklimatSaveUrl, 'Kertas taklimat berjaya disimpan.');
			});

			$('#eb_taklimat_hantar').on('click', function() {
				postTaklimat(taklimatSubmitUrl, 'Kertas taklimat berjaya dihantar.');
			});

			function postJadual(url, successMessage, redirectAfter) {
				$.ajax({
					url: url,
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrfToken || '',
						'Accept': 'application/json'
					},
					data: {
						tarikh_bidaan_mula: ($('#eb_jadual_tarikh_mula').val() || '').toString(),
						masa_bidaan_mula: ($('#eb_jadual_masa_mula').val() || '').toString(),
						tarikh_bidaan_tamat: ($('#eb_jadual_tarikh_tamat').val() || '').toString(),
						masa_bidaan_tamat: ($('#eb_jadual_masa_tamat').val() || '').toString(),
					},
					success: function(resp) {
						showJadualAlert(resp.message || successMessage, 'success');
						if (redirectAfter) {
							setTimeout(function() {
								window.location.href = ebiddingIndexUrl;
							}, 900);
						}
					},
					error: function(xhr) {
						let message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
						if (xhr?.responseJSON?.errors) {
							message = Object.values(xhr.responseJSON.errors).flat().join(' ');
						}
						showJadualAlert(message, 'error');
					}
				});
			}

			$('#eb_jadual_simpan').on('click', function() {
				postJadual(jadualSaveUrl, 'Jadual bidaan berjaya disimpan.', false);
			});

			$('#eb_jadual_mula').on('click', function() {
				postJadual(jadualMulaUrl, 'Bidaan berjaya dimulakan.', true);
			});

			if (isAgencyReadOnly) {
				$('#mainTabContent input, #mainTabContent select, #mainTabContent textarea').prop('disabled', true);
				$('#mainTabContent button').prop('disabled', true).addClass('disabled');
			}

			renderTaklimatRows();

			function showAlert(message, type) {
				$alert.removeClass('d-none alert-success alert-danger')
					.addClass(type === 'success' ? 'alert-success' : 'alert-danger')
					.text(message || '');
			}

			function submitPengesyoran(url, successMessage) {
				if (!tenderId || !url) {
					showAlert('Tender tidak ditemui.', 'error');
					return;
				}

				$.ajax({
					url: url,
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrfToken || '',
						'Accept': 'application/json'
					},
					data: {
						catatan: ($('#eb_pengesyoran_catatan').val() || '').toString().trim()
					},
					success: function(resp) {
						showAlert(resp.message || successMessage, 'success');
					},
					error: function(xhr) {
						let message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
						if (xhr?.responseJSON?.errors) {
							message = Object.values(xhr.responseJSON.errors).flat().join(' ');
						}
						showAlert(message, 'error');
					}
				});
			}

			$('#eb_btn_pengesyoran_simpan').on('click', function() {
				submitPengesyoran(saveUrl, 'Pemilihan Pembekal berjaya disimpan.');
			});

			$('#eb_btn_pengesyoran_hantar').on('click', function() {
				submitPengesyoran(submitUrl, 'Pemilihan Pembekal berjaya dihantar.');
			});
		});
	</script>
@endsection
