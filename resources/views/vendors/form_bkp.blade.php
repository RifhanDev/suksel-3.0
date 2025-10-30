@extends('layouts.master')

@section('title')
	@lang('translation.Data_Tables')
@endsection


@section('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />

	<style>
		/* ===== Top info strip (like screenshot #2) ===== */
		.header-band {
			background: #f6eaea;
			/* soft rose */
			border: 1px solid #e7d1d1;
			border-radius: .5rem;
			padding: .75rem 1rem;
		}

		.header-band .item small {
			display: block;
			font-size: .75rem;
			text-transform: uppercase;
			letter-spacing: .03em;
			color: #666;
			font-weight: 700;
			line-height: 1.1;
		}

		.header-band .item .val {
			font-weight: 700;
			margin-top: .15rem;
			white-space: nowrap;
		}

		.header-band .ok {
			color: #19c1a7;
		}


		/* ===== Wizard progress pills (maroon active + thin divider) ===== */
		.progress-nav {
			border-bottom: 4px solid #d9d9d9;
			/* grey line under pills */
		}

		.progress-nav .progress {
			height: 4px;
			background: #e8e8e8;
			margin: 0;
			border-radius: 2px;
		}

		.progress-nav .progress-bar {
			background: #a84545;
			/* maroon */
			transition: width .3s ease;
		}

		.progress-nav .custom-nav {
			gap: 1rem;
			padding-top: .5rem;
		}

		.progress-nav .nav-link {
			border: 0 !important;
			background: transparent !important;
			color: #2b2b2b;
			font-weight: 700;
			padding: .45rem 1rem;
			border-radius: .75rem;
		}

		.progress-nav .nav-link.active {
			background: #a84545 !important;
			color: #fff !important;
			box-shadow: 0 2px 6px rgba(168, 69, 69, .25);
		}
	</style>
@endsection


@section('content')
	<div class="header-band d-flex flex-wrap align-items-center gap-4 mb-3">
		<div class="item">
			<small>No. Sebut Harga / Tender</small>
			<span class="val ok">Belum Dijana</span>
		</div>

		<div class="item flex-grow-1">
			<small>PTJ</small>
			<span class="val">BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN</span>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<form action="{{ isset($tender) ? route('updateCiptaTender', $tender->id) : route('storeCiptaTender') }}"
				method="POST" enctype="multipart/form-data">
				@if (isset($tender))
					@method('PUT')
				@endif
				@csrf
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="d-flex align-items-center justify-content-between mb-3">
								<div>Cipta Tender</div>
								<div class="item ms-auto text-end">
									<small>Status</small>
									<span class="val">Menunggu Penyerahan Sebut Harga / Tender</span>
								</div>
							</div>
						</div>
						<hr>
						<div id="custom-progress-bar" class="progress-nav mb-4 p-2">
							<div class="progress" style="height: 1px;">
								<div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
									aria-valuemax="100"></div>
							</div>
							<ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
								<li class="nav-item" role="presentation">
									<button type="button" id="makluman-umum-tab" class="nav-link rounded-pill active"
										data-progressbar="custom-progress-bar" data-bs-toggle="pill" data-bs-target="#makluman-umum"
										data-title="@lang('translation.application-information')" role="tab" aria-controls="makluman-umum" aria-selected="true">1</button>
								</li>
								<li class="nav-item" role="presentation">
									<button type="button" id="kod-bidang-tab" class="nav-link rounded-pill" data-bs-toggle="pill"
										data-bs-target="#kod-bidang" role="tab" aria-controls="kod-bidang" aria-selected="false">2</button>
								</li>

							</ul>
						</div>
					</div>

					<div class="tab-content" id="application-content">
						<div class="tab-pane fade show active" id="makluman-umum" role="tabpanel" aria-labelledby="makluman-umum-tab">
							<div class="row mt-4 justify-content-center">
								<div class="col-12">
									<h4 class="card-title card-title-grey">MAKLUMAT UMUM</h4>
									<p class="card-title-desc text-primary fst-italic">
										Untuk perkhidmatan yang memerlukan bayaran secara progresif, sila pilih Jenis
										Pemenuhan sebagai Bermasa (Bila Perlu)
									</p>
								</div>
							</div>
							<div class="row d-flex justify-content-center">
								<div class="col-11">
									<div class="row">
										<!-- Kaedah Perolehan -->
										<div class="col-md-12 my-2">
											<div class="row">
												<div class="col-md-2 text-end">
													<label for="" class="">Kaedah Perolehan</label>
												</div>
												<div class="col-md-4 text-end">
													<select class="form-control" name="kaedah_perolehan_id">
														<option value="">- Sila Pilih -</option>
														@foreach ($RefKaedahPerolehan as $kaedah)
															<option value="{{ $kaedah->id }}" @if (old('kaedah_perolehan_id', isset($tender) ? $tender->kaedah_perolehan_id : null) == $kaedah->id) selected @endif>
																{{ $kaedah->name }}
															</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-2 text-end">
													<label for="" class="">Kategori Jenis Perolehan <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-4">
													<select class="form-control" name="kategori_jenis_perolehan_id">
														<option value="">- Sila Pilih -</option>
														@foreach ($RefKategoriJenisPerolehan as $item)
															<option value="{{ $item->id }}" @if (old('kategori_jenis_perolehan_id', isset($tender) ? $tender->kategori_jenis_perolehan_id : null) == $item->id) selected @endif>
																{{ $item->name }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										{{-- Default Form - Cipta Tender --}}
										<div id="ciptaTenderForm">
											<!-- Kategori Jenis Perolehan -->
											<!-- Item Panel -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 d-flex justify-content-end">
														<label for="" class="">Item Panel <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														@foreach ($RefYesNo as $item)
															<div class="form-check form-check-inline">
																<input class="form-check-input" type="radio" name="item_panel" id="item_panel_{{ $item->id }}"
																	value="{{ $item->id }}" @if (old('item_panel', isset($tender) ? $tender->item_panel : null) == $item->id) checked @endif>
																<label class="form-check-label" for="item_panel_{{ $item->id }}">
																	{{ $item->name }}
																</label>
															</div>
														@endforeach
													</div>
												</div>
											</div>
											<!-- Item Panel -->
											<!-- Tajuk Perolehan -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 d-flex justify-content-end">
														<label for="" class="">Tajuk Perolehan <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														<textarea class="form-control" name="name">{{ old('name', isset($tender) ? $tender->name : '') }}</textarea>
													</div>
												</div>
											</div>
											<!-- Tajuk Perolehan -->
											<!-- Disediakan Untuk PTJ -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 d-flex justify-content-end">
														<label for="" class="">Disediakan Untuk PTJ <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														<div class="input-group">
															<select class="form-control" name="organization_unit_id">
																<option value="1"
																	{{ old('organization_unit_id', isset($tender) ? $tender->organization_unit_id : '') == 1 ? 'selected' : '' }}>
																	BAHAGIAN PENTADBIRAN - CAWANNGAN KEWANGAN - KEMENTERIAN KEWANGAN</option>
															</select>
															<span class="input-group-text"><i class="bx bx-search-alt"></i></span>
														</div>
													</div>
												</div>
											</div>
											<!-- Disediakan Untuk PTJ -->
											<!-- No. Rujukan Fail -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 d-flex justify-content-end">
														<label for="no_rujukan_fail" class="">No. Rujukan Fail <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														<input class="form-control" type="text" name="ref_number"
															value="{{ old('ref_number', isset($tender) ? $tender->ref_number : '') }}">
													</div>
												</div>
											</div>
											<!-- No. Rujukan Fail -->
											<!-- Tarikh Dicipta -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 d-flex justify-content-end">
														<label for="" class="">Tarikh Dicipta</label>
													</div>
													<div class="col-md-3">
														<input class="form-control" type="date" name="advertise_start_date"
															value="{{ old('advertise_start_date', isset($tender) ? $tender->advertise_start_date : '') }}">
														<small class="form-text text-muted">Advertise start date</small>
													</div>
												</div>
											</div>
											<!-- Tarikh Dicipta -->
											<!-- Jumlah Harga Indikatif Jangkaan (RM) and required dates -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="price" class="">Jumlah Harga Indikatif Jangkaan (RM) <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-4">
														<input class="form-control" type="number" name="price" step="0.01"
															value="{{ old('price', isset($tender) ? $tender->price : '') }}">
													</div>
													<div class="col-md-2 text-end">
														<label for="anggaran_jabatan" class="">Anggaran Jabatan</label>
													</div>
													<div class="col-md-4">
														<input type="number" name="anggaran_jabatan" class="form-control"
															value="{{ old('anggaran_jabatan', isset($tender) ? $tender->anggaran_jabatan : '') }}">
													</div>
												</div>
											</div>

											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="sumber_peruntukan" class="">Sumber Peruntukan <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														@foreach ($RefSumberPeruntukan as $item)
															<div class="form-check form-check-inline">
																<input class="form-check-input" type="radio" name="sumber_peruntukan"
																	id="sumber_peruntukan_{{ $item->id }}" value="{{ $item->id }}"
																	@if (old('sumber_peruntukan', isset($tender) ? $tender->sumber_peruntukan : null) == $item->id) checked @endif>
																<label class="form-check-label" for="sumber_peruntukan_{{ $item->id }}">
																	{{ $item->name }}
																</label>
															</div>
														@endforeach
													</div>
												</div>
											</div>

											<!-- Advertise / Document / Submission dates (required) -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="advertise_stop_date" class="">Advertise Stop Date <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-3">
														<input class="form-control" type="date" name="advertise_stop_date"
															value="{{ old('advertise_stop_date', isset($tender) ? $tender->advertise_stop_date : '') }}">
													</div>
													<div class="col-md-2 text-end">
														<label for="document_start_date" class="">Document Start Date <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-3">
														<input class="form-control" type="date" name="document_start_date"
															value="{{ old('document_start_date', isset($tender) ? $tender->document_start_date : '') }}">
													</div>
												</div>
											</div>

											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="document_stop_date" class="">Document Stop Date <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-3">
														<input class="form-control" type="date" name="document_stop_date"
															value="{{ old('document_stop_date', isset($tender) ? $tender->document_stop_date : '') }}">
													</div>
													<div class="col-md-2 text-end">
														<label for="submission_datetime" class="">Submission Date <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-3">
														<input class="form-control" type="datetime-local" name="submission_datetime"
															value="{{ old('submission_datetime', isset($tender) && $tender->submission_datetime ? \Carbon\Carbon::parse($tender->submission_datetime)->format('Y-m-d\\TH:i') : '') }}">
													</div>
												</div>
											</div>

											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 d-flex justify-content-end">
														<label for="submission_location_address" class="">Submission Location Address <span
																class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														<input class="form-control" type="text" name="submission_location_address"
															value="{{ old('submission_location_address', isset($tender) ? $tender->submission_location_address : '') }}">
													</div>
												</div>
											</div>

											<!-- Tender rules -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="tender_rules" class="">Tender Rules <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-10">
														<textarea class="form-control" name="tender_rules">{{ old('tender_rules', isset($tender) ? $tender->tender_rules : '') }}</textarea>
													</div>
												</div>
											</div>

											<!-- File uploads -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="files" class="">Upload Files</label>
													</div>
													<div class="col-md-10">
														<input type="file" name="files[]" multiple class="form-control">
														<small class="form-text text-muted">Multiple files allowed. File public flag and metadata are handled
															server-side.</small>
													</div>
												</div>
											</div>

											<!-- Jenis Sebut Harga / Tender -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="" class="required">Jenis Tender/Sebut Harga</label>
													</div>
													<div class="col-md-4">
														<select class="form-control" name="jenis_tender_id">
															<option value="">- Sila Pilih -</option>
															@foreach ($RefTypeOfTender as $item)
																<option value="{{ $item->id }}" @if (old('jenis_tender_id', isset($tender) ? $tender->jenis_tender_id : null) == $item->id) selected @endif>
																	{{ $item->name }}
																</option>
															@endforeach
														</select>
													</div>
													<div class="col-md-2 text-end">
														<label for="" class="">Terbuka Kepada <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-4">
														@foreach ($RefOpenTo as $item)
															<div class="form-check form-check-inline">
																<input class="form-check-input" type="radio" name="open_to_id" id="open_to_id_{{ $item->id }}"
																	value="{{ $item->id }}" @if (old('open_to_id', isset($tender) ? $tender->open_to_id : null) == $item->id) checked @endif>
																<label class="form-check-label" for="open_to_id_{{ $item->id }}">
																	{{ $item->name }}
																</label>
															</div>
														@endforeach
													</div>
												</div>

												<div class="col-md-12 my-2">
													<div class="row">
														<div class="col-md-2 text-end">
															<label for="" class="">Zon / Lokasi</label>
														</div>
														<div class="col-md-2">
															@foreach ($RefYesNo as $item)
																<div class="form-check form-check-inline">
																	<input class="form-check-input" type="radio" name="is_zon_location"
																		id="is_zon_location_{{ $item->id }}" value="{{ $item->id }}"
																		@if (old('is_zon_location', isset($tender) ? $tender->is_zon_location : null) == $item->id) checked @endif>
																	<label class="form-check-label" for="is_zon_location_{{ $item->id }}">
																		{{ $item->name }}
																	</label>
																</div>
															@endforeach
														</div>
														<div class="col-md-4 text-end ">
															<label for="" class="">Taklimat Tender / Lawatan
																Tapak&nbsp;<span class="text-danger">*</span></label>
														</div>
														<div class="col-md-4">
															@foreach ($RefHaveNo as $item)
																<div class="form-check form-check-inline">
																	<input class="form-check-input" type="radio" name="is_taklimat_tender"
																		id="is_taklimat_tender_{{ $item->id }}" value="{{ $item->id }}"
																		@if (old('is_taklimat_tender', isset($tender) ? $tender->is_taklimat_tender : null) == $item->id) checked @endif>
																	<label class="form-check-label" for="is_taklimat_tender_{{ $item->id }}">
																		{{ $item->name }}
																	</label>
																</div>
															@endforeach
														</div>
													</div>
												</div>
												<!-- Lokaliti Penilaian -->
												<div class="col-md-12 my-2">
													<div class="row">
														<div class="col-md-2 text-end">
															<label for="" class="">Lokaliti Liputan</label>
														</div>
														<div class="col-md-4">
															<input type="text" class="form-control" name="lokasi_liputan">
														</div>
													</div>
												</div>
												<!-- Lokaliti Penilaian -->

												<!-- Jenis Kontrak -->
												<div class="col-md-12 my-2">
													<div class="row">
														<div class="col-md-2 text-end">
															<label for="" class="">Jenis Kontrak <span class="text-danger">*</span></label>
														</div>
														<div class="col-md-4">
															<select class="form-control" name="jenis_kontrak_id">
																<option value="">- Sila Pilih -</option>
																@foreach ($RefTypeOfContract as $item)
																	<option value="{{ $item->id }}" @if (old('jenis_kontrak_id', isset($tender) ? $tender->jenis_kontrak_id : null) == $item->id) selected @endif>
																		{{ $item->name }}
																	</option>
																@endforeach
															</select>
														</div>
														<div class="col-md-2 d-flex justify-content-end">
															<label for="" class="">Kategori Perolehan <span class="text-danger">*</span></label>
														</div>
														<div class="col-md-4">
															<select class="form-control" name="jenis_perolehan_id">
																<option value="">- Sila Pilih -</option>
																@foreach ($RefTypeOfPerolehan as $item)
																	<option value="{{ $item->id }}" @if (old('jenis_perolehan_id', isset($tender) ? $tender->jenis_perolehan_id : null) == $item->id) selected @endif>
																		{{ $item->name }}
																	</option>
																@endforeach
															</select>
														</div>

													</div>
												</div>
												<!-- Jenis Kontrak -->

												<!-- No. Kontrak Sedia Ada (Jika Ada) -->
												<!-- Jenis Pemenuhan -->
												<div class="col-md-12 my-2">
													<div class="row">
														<div class="col-md-2 d-flex justify-content-end">
															<label for="" class="">Jenis Pemenuhan <span class="text-danger">*</span></label>
														</div>
														<div class="col-md-4">
															<select class="form-control" name="jenis_pemenuhan_id">
																<option value="">- Sila Pilih -</option>
																@foreach ($RefJenisPemenuhan as $item)
																	<option value="{{ $item->id }}" @if (old('jenis_pemenuhan_id', isset($tender) ? $tender->jenis_pemenuhan_id : null) == $item->id) selected @endif>
																		{{ $item->name }}
																	</option>
																@endforeach
															</select>
														</div>
														<div class="col-md-2 d-flex justify-content-end">
															<label for="" class="">No. Kontrak Sedia Ada
																(Jika Ada)</label>
														</div>
														<div class="col-md-4">
															<input class="form-control" type="text" name="no_kontrak_sedia_ada">
														</div>
													</div>
												</div>
												<!-- Jenis Pemenuhan -->

												<!-- Kelulusan Spesifikasi Daripada Kementerian -->
												<div class="col-md-12 my-2">
													<div class="row">
														<div class="col-md-2 text-end">
															<label for="" class="">Kelulusan Spesifikasi
																Daripada Kementerian
																<span class="text-danger">*</span></label>
														</div>
														<div class="col-md-4">
															@foreach ($RefYesNo as $item)
																<div class="form-check form-check-inline">
																	<input class="form-check-input" type="radio" name="is_kelulusan_spesifikasi"
																		id="is_kelulusan_spesifikasi_{{ $item->id }}" value="{{ $item->id }}"
																		@if (old('is_kelulusan_spesifikasi', isset($tender) ? $tender->is_kelulusan_spesifikasi : null) == $item->id) checked @endif>
																	<label class="form-check-label" for="is_kelulusan_spesifikasi_{{ $item->id }}">
																		{{ $item->name }}
																	</label>
																</div>
															@endforeach
														</div>
														<div class="col-md-2 d-flex justify-content-end">
															<label for="" class="">Tempoh Kontrak /
																Penyiapan (Bulan)</label>
														</div>
														<div class="col-md-4">
															<input class="form-control" type="number" name="tempoh_kontrak">
														</div>
													</div>
												</div>

												<div class="col-md-12 my-2">
													<div class="row">
														<div class="col-md-2 text-end">
															<label for="" class="">Penghantaran Fizikal
																<span class="text-danger">*</span></label>
														</div>
														<div class="col-md-4">
															@foreach ($RefYesNo as $item)
																<div class="form-check form-check-inline">
																	<input class="form-check-input" type="radio" name="is_penghantaran_fizikal"
																		id="is_penghantaran_fizikal_{{ $item->id }}" value="{{ $item->id }}"
																		@if (old('is_penghantaran_fizikal', isset($tender) ? $tender->is_penghantaran_fizikal : null) == $item->id) checked @endif>
																	<label class="form-check-label" for="is_penghantaran_fizikal_{{ $item->id }}">
																		{{ $item->name }}
																	</label>
																</div>
															@endforeach
														</div>
													</div>
												</div>
												<!-- Penghantaran Fizikal -->
											</div>
										</div>
									</div>

									{{-- Form Khusus untuk Kerja --}}
									<div id="kerjaForm" style="display: none;">
										<!-- Tajuk Perolehan -->
										<div class="col-md-12 my-2">
											<div class="row">
												<div class="col-md-2 text-end">
													<label for="" class="">Tajuk Perolehan <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-10">
													<textarea class="form-control" name=""></textarea>
												</div>
											</div>
										</div>
										<!-- Tajuk Perolehan -->
										<!-- Disediakan Untuk PTJ -->
										<div class="col-md-12 my-2">
											<div class="row">
												<div class="col-md-2 text-end">
													<label for="" class="">Disediakan Untuk PTJ <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-10">
													<div class="input-group">
														<select class="form-control" name="">
															<option value="">BAHAGIAN PENTADBIRAN - CAWANNGAN
																KEWANGAN -
																KEMENTERIAN KEWANGAN</option>
														</select>
														<span class="input-group-text"><i class="bx bx-search-alt"></i></span>
													</div>
												</div>
											</div>
										</div>
										<!-- Disediakan Untuk PTJ -->
										<!-- No. Rujukan Fail -->
										<div class="col-md-12 my-2">
											<div class="row">
												<div class="col-md-2 text-end">
													<label for="" class="">No. Rujukan Fail <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-10">
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
										<!-- No. Rujukan Fail -->
										<!-- No. Tender/Sebut Harga -->
										<div class="col-md-12 my-2">
											<div class="row">
												<div class="col-md-2 text-end">
													<label for="" class="">No. Tender/Sebut Harga <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-10">
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
										<!-- No. Tender/Sebut Harga -->
										<!-- Tarikh Dicipta -->
										<div class="col-md-12 my-2">
											<div class="row ">
												<div class="col-md-2 text-end">
													<label for="" class="">Tarikh Dicipta</label>
												</div>
												<div class="col-md-3">
													<input class="form-control" type="date">
												</div>
											</div>
										</div>
										<!-- Tarikh Dicipta -->
										<!-- Harga Indikatif Jabatan (RM) -->
										<div class="col-md-12 my-2">
											<div class="row ">
												<div class="col-md-2 text-end">
													<label class="">Harga Indikatif Jabatan (RM) <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-2">
													<input class="form-control" type="number" name="">
												</div>
												<div class="col-md-4 text-end">
													<label class="required">Sumber Peruntukan (2)</label>
												</div>
												<div class="col-md-4">
													{{-- @foreach ($RefSumberPeruntukan as $item)
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="is_penghantaran_fizikal"
																id="is_penghantaran_fizikal_{{ $item->id }}" value="{{ $item->id }}"
																@if (old('is_penghantaran_fizikal', isset($tender) ? $tender->is_penghantaran_fizikal : null) == $item->id) checked @endif>
															<label class="form-check-label" for="is_penghantaran_fizikal_{{ $item->id }}">
																{{ $item->name }}
															</label>
														</div>
													@endforeach --}}
												</div>
											</div>
										</div>
										<!-- Harga Indikatif Jabatan (RM) -->
										<!-- Anggaran Jabatan -->
										<div class="col-md-12 my-2">
											<div class="row ">
												<div class="col-md-2 text-end">
													<label for="" class="">Anggaran Jabatan (RM) <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-2">
													<input class="form-control" type="number" name="">
												</div>
											</div>
										</div>
										<!-- Anggaran Jabatan -->

										<!-- Jenis Sebut Harga / Tender -->
										<div class="col-md-12 my-2">
											<div class="row">
												<div class="col-md-2 text-end">
													<label for="" class=""> Jenis Tender/Sebut Harga <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-2">
													<select class="form-control" name="">
														<option value="">Konvensional</option>
														<option value="">Reka & Bina</option>
														<option value="">Terhad</option>
													</select>


												</div>
												<div class="col-md-4 text-end">
													<label for="" class="">Terbuka Kepada <span class="text-danger">*</span></label>
												</div>
												<div class="col-md-4">
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" name="terbukaKepada" id="terbukaKepada1">
														<label class="form-check-label" for="terbukaKepada1">
															Bumiputra
														</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" name="terbukaKepada" id="terbukaKepada2" checked>
														<label class="form-check-label" for="terbukaKepada2">
															Semua
														</label>
													</div>
												</div>
											</div>
											<!-- Jenis Sebut Harga / Tender -->

											<!-- Zon / Lokasi -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="" class="">Zon / Lokasi</label>
													</div>
													<div class="col-md-2">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="zonLokasi" id="zonLokasi1">
															<label class="form-check-label" for="zonLokasi1">
																Ya
															</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="zonLokasi" id="zonLokasi2" checked>
															<label class="form-check-label" for="zonLokasi2">
																Tidak
															</label>
														</div>

													</div>
													<div class="col-md-2 text-end">
														<label for="" class="">Taklimat Tender / Lawatan
															Tapak&nbsp;<span class="text-danger">*</span></label>
													</div>
													<div class="col-md-4">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="taklimat" id="ada" checked>
															<label class="form-check-label" for="ada">
																Ada
															</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="taklimat" id="tidak">
															<label class="form-check-label" for="tidak">
																Tidak
															</label>
														</div>
													</div>
												</div>
											</div>
											<!-- Zon / Lokasi -->


											<!-- Lokaliti Penilaian -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="" class="">Lokaliti Liputan</label>
													</div>
													<div class="col-md-2">
														<select class="form-control" name="">
															<option value=""></option>
														</select>
													</div>
													<div class="col-md-4 text-end">
														<label for="" class="">Skop Kerja <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-2">
														<select class="form-control" name="">
															<option value="">Bangunan</option>
															<option value="">Kejuruteraan Awam</option>
															<option value="">Mekanikal & Elektrikal</option>
														</select>
													</div>
												</div>
											</div>
											<!-- Lokaliti Penilaian -->


											<!-- Jenis Pemenuhan -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label class="">Jenis Pemenuhan <span class="text-danger">*</span></label>
													</div>
													<div class="col-md-2">
														<select class="form-control" name="">
															<option value="">Bermasa (Bila Perlu)</option>
														</select>
													</div>
													<div class="col-md-4 text-end">
														<label class="">Tempoh Siap Maksima</label>
													</div>
													<div class="col-md-1">
														<input class="form-control" type="number" name="">
													</div>
													<div class="col-md-1">
														<select class="form-control" name="">
															<option value="bulan">Bulan</option>
															<option value="minggu">Minggu</option>
														</select>
													</div>
												</div>
											</div>

											<!-- Jenis Pemenuhan -->

											<!-- Penghantaran Fizikal -->
											<div class="col-md-12 my-2">
												<div class="row">
													<div class="col-md-2 text-end">
														<label for="" class="">Jawatan
															Spesifikasi&nbsp;<span class="text-danger">*</span></span></label>
													</div>
													<div class="col-md-2">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="jawatanSpek" id="ya">
															<label class="form-check-label" for="ya">
																Ya
															</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="jawatanSpek" id="tidak" checked>
															<label class="form-check-label" for="tidak">
																Tidak
															</label>
														</div>

													</div>
													<div class="col-md-4 text-end ">
														<label for="" class="">Penghantaran
															Fizikal&nbsp;<span class="text-danger">*</span></label>
													</div>
													<div class="col-md-4">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="penghantaranFizikal" id="ya" checked>
															<label class="form-check-label" for="ya">
																Ya
															</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="penghantaranFizikal" id="tidak">
															<label class="form-check-label" for="tidak">
																Tidak
															</label>
														</div>
													</div>
												</div>
											</div>
											<!-- Penghantaran Fizikal -->
										</div>
									</div>

									<div class="row">
										<div class="col-12 d-flex justify-content-between">
											<div class="left"></div>
											<div class="right">
												<button type="submit" class="btn-md-sm btn btn-success ">Simpan</button>
												<button type="button" class="btn btn-primary ms-auto" data-step="step1"
													data-currenttab="application-content" data-nexttab="kod-bidang-tab">
													Seterusnya
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="kod-bidang" role="tabpanel" aria-labelledby="kod-bidang-tab">
									<div class="row mt-4 justify-content-center">
										<div class="col-12">
											<h4 class="card-title card-title-grey">KOD BIDANG</h4>
											<p class="card-title-desc text-primary fst-italic">
												Syarikat Selangor Sahaja atau Syarikat Selangor dan Lain-lain Negeri <span class="text-danger">wajib</span>
												di isi.
											</p>
										</div>
									</div>
									<div class="row d-flex justify-content-center">
										<div class="col-11">
											<div class="row my-2">
												<div class="col-2 text-end">
													<b>Kod Bidang MOF</b>
												</div>
												<div class="col-2">
													<select class="form-control" name="">
														<option value="">Atau</option>
													</select>
												</div>
												<div class="col-6">
													<input class="form-control" type="text" name="">
												</div>
											</div>
											<div class="row my-2">
												<div class="col-2"></div>
												<div class="col-2">
													<button class="btn btn-primary">Tambah</button>
												</div>
											</div>

											<hr>

											<div class="row my-2">
												<div class="col-3"></div>
												<div class="col-8">
													<select name="" class="form-control">
														<option value="">Dan</option>
													</select>
												</div>
											</div>

											<hr>

											<div class="row my-2">
												<div class="col-1"></div>
												<div class="col-2 text-end"><b>Gred CIDB</b></div>
												<div class="col-8">
													<select class="form-control" name="">
														<option value=""></option>
													</select>
												</div>
											</div>
											<div class="row my-2">
												<div class="col-2 text-end">
													<b>Bidang Pengkhususan CIDB</b>
												</div>
												<div class="col-2">
													<select class="form-control" name="">
														<option value="">Atau</option>
													</select>
												</div>
												<div class="col-6">
													<input class="form-control" type="text" name="">
												</div>
											</div>
											<div class="row my-2">
												<div class="col-2"></div>
												<div class="col-2">
													<button class="btn btn-primary">Tambah</button>
												</div>
											</div>

										</div>
									</div>
									<div class="row">
										<div class="col-12 d-flex justify-content-between">
											<div class="left">
												<a href="{{ route('team.create') }}" class="btn-md-sm btn btn-info mx-1">Sebelumnya</a>
											</div>
											<div class="right">
												<a href="{{ route('team.create') }}" class="btn-md-sm btn btn-success mx-1">Simpan</a>
												<a href="{{ route('team.create') }}" class="btn-md-sm btn btn-primary mx-1">Hantar</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Next -> open tab #2
				const nextBtn = document.querySelector('[data-nexttab="kod-bidang-tab"]');
				if (nextBtn) {
					nextBtn.addEventListener('click', function() {
						const nextTabEl = document.getElementById('kod-bidang-tab');
						if (nextTabEl) new bootstrap.Tab(nextTabEl).show();
					});
				}

				// (Optional) Previous button in tab #2
				const prevBtn = document.getElementById('btn-prev-to-makluman');
				if (prevBtn) {
					prevBtn.addEventListener('click', function() {
						const prevTabEl = document.getElementById('makluman-umum-tab');
						if (prevTabEl) new bootstrap.Tab(prevTabEl).show();
					});
				}

				// Progress line reacts to tab changes
				const barWrap = document.getElementById('custom-progress-bar');
				const tabButtons = barWrap ?
					Array.from(barWrap.querySelectorAll('[data-bs-toggle="pill"]')) : [];
				const bar = barWrap ? barWrap.querySelector('.progress-bar') : null;

				function updateProgress(targetBtn) {
					if (!bar || !tabButtons.length) return;
					const idx = tabButtons.indexOf(targetBtn);
					const pct = (idx / Math.max(1, tabButtons.length - 1)) * 100; // 0% on first, 100% on last
					bar.style.width = pct + '%';
					bar.setAttribute('aria-valuenow', pct);
				}

				tabButtons.forEach(btn => {
					btn.addEventListener('shown.bs.tab', (e) => updateProgress(e.target));
				});

				// set initial progress based on which tab is active on load
				const initialActive = tabButtons.find(b => b.classList.contains('active')) || tabButtons[0];
				if (initialActive) updateProgress(initialActive);
			});
		</script>
	@endsection
