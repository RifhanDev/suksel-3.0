@extends('layouts.modernLanding')

@section('styles')
	<link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
	<link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
	<style>
		.vendor-tender-card {
			background: #fff;
			border-radius: 12px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
			overflow: hidden;
			margin-bottom: 1.25rem;
		}

		.vendor-tender-card-header {
			background: #f8fafc;
			border-bottom: 1px solid #e5e7eb;
			padding: 14px 20px;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.vendor-tender-card-header h6 {
			margin: 0;
			font-size: 0.82rem;
			font-weight: 700;
			color: #111827;
			text-transform: uppercase;
			letter-spacing: 0.3px;
		}

		.vendor-tender-card-header .header-icon {
			width: 28px;
			height: 28px;
			background: rgba(196, 30, 58, 0.08);
			color: #c41e3a;
			border-radius: 7px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.info-table {
			width: 100%;
		}

		.info-table tr {
			border-bottom: 1px solid #f1f5f9;
		}

		.info-table tr:last-child {
			border-bottom: none;
		}

		.info-table th {
			padding: 10px 20px;
			font-size: 0.75rem;
			font-weight: 600;
			color: #6b7280;
			width: 35%;
			vertical-align: top;
		}

		.info-table td {
			padding: 10px 20px;
			font-size: 0.82rem;
			color: #1f2937;
			font-weight: 500;
		}

		.tender-code-divider {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			margin: 0 0 1.25rem;
		}

		.tender-code-divider::before,
		.tender-code-divider::after {
			content: '';
			flex: 1;
			height: 1px;
			background: #e5e7eb;
		}

		.tender-code-divider span {
			flex: none;
			padding: 0.25rem 0.85rem;
			background: #f8fafc;
			border: 1px solid #e5e7eb;
			border-radius: 999px;
			color: #6b7280;
			font-size: 0.7rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.8px;
		}

		.vendor-side-nav .nav-link {
			color: #475569;
			font-size: 0.82rem;
			font-weight: 500;
			border-radius: 8px;
			padding: 9px 12px;
			display: flex;
			align-items: center;
			gap: 8px;
			transition: background 0.15s, color 0.15s;
			margin-bottom: 2px;
		}

		.vendor-side-nav .nav-link:hover {
			background: #f1f5f9;
			color: #1e293b;
		}

		.vendor-side-nav .nav-link.active {
			background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);
			color: #fff;
		}

		.vendor-side-nav .nav-link svg {
			flex-shrink: 0;
			opacity: 0.65;
		}

		.vendor-side-nav .nav-link.active svg {
			opacity: 1;
		}
	</style>
@endsection

@section('content')

	{{-- Breadcrumb + Header --}}
	<div class="mb-4">
		<div class="d-flex align-items-center gap-2 mb-1">
			<a href="{{ url('/') }}" class="text-muted small text-decoration-none">Laman Utama</a>
			<span class="text-muted small">/</span>
			<span class="text-muted small">{{ App\Tender::$types[$tender->type] ?? 'Tender' }}</span>
		</div>
		<h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">{{ $tender->name }}</h3>
		<div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
			<span class="text-muted small">{{ optional($tender->tenderer)->name }}</span>
			@if ($tender->ref_number)
				<span class="text-muted small">·</span>
				<span class="fw-semibold small text-dark">{{ $tender->ref_number }}</span>
			@endif
		</div>
	</div>

	@include('tenders._notification')

	<div class="row g-4 align-items-start">

		{{-- LEFT: Side nav --}}
		<div class="col-lg-3">
			<div class="bg-white rounded-3 border shadow-sm p-2">
				<nav class="nav flex-column vendor-side-nav" id="vendorTabs" role="tablist">

					<a class="nav-link active" href="#vt-main" data-bs-toggle="pill" role="tab">
						<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10" />
							<line x1="12" y1="8" x2="12" y2="12" />
							<line x1="12" y1="16" x2="12.01" y2="16" />
						</svg>
						Maklumat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}
					</a>

					<a class="nav-link" href="#vt-syarat" data-bs-toggle="pill" role="tab">
						<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
							<polyline points="14 2 14 8 20 8" />
							<line x1="16" y1="13" x2="8" y2="13" />
							<line x1="16" y1="17" x2="8" y2="17" />
						</svg>
						Syarat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}
					</a>

					@if (count($tender->siteVisits) > 0)
						<a class="nav-link" href="#vt-lawatan" data-bs-toggle="pill" role="tab">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
								<circle cx="12" cy="10" r="3" />
							</svg>
							Lawatan Tapak
						</a>
					@endif

					@if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
						<a class="nav-link" href="#vt-kod" data-bs-toggle="pill" role="tab">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="16 18 22 12 16 6" />
								<polyline points="8 6 2 12 8 18" />
							</svg>
							Kod Bidang
						</a>
					@endif

					@if ($mejaTerkawal->hasDocuments())
						<a class="nav-link" href="#vt-doc1" data-bs-toggle="pill" role="tab">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
								<polyline points="13 2 13 9 20 9" />
							</svg>
							{{ \App\Support\TenderMejaTerkawalPresenter::TAB_LABEL }}
							<span class="badge bg-primary ms-auto" style="font-size:0.6rem;">{{ $mejaTerkawal->count() }}</span>
						</a>
					@endif

					<a class="nav-link" href="#vt-news" data-bs-toggle="pill" role="tab">
						<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path
								d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1" />
						</svg>
						Makluman / Ralat
						@if ($tender->news()->count() > 0)
							<span class="badge bg-warning ms-auto" style="font-size:0.6rem;">{{ $tender->news()->count() }}</span>
						@endif
					</a>

					<a class="nav-link" href="#vt-officer" data-bs-toggle="pill" role="tab">
						<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
							<circle cx="12" cy="7" r="4" />
						</svg>
						Pegawai Bertanggungjawab
					</a>

				</nav>
			</div>
		</div>

		{{-- RIGHT: Tab content --}}
		<div class="col-lg-9">
			<div class="tab-content">

				{{-- TAB: Maklumat --}}
				<div class="tab-pane fade show active" id="vt-main" role="tabpanel">
					<div class="vendor-tender-card">
						<div class="vendor-tender-card-header">
							<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
									stroke-linejoin="round">
									<circle cx="12" cy="12" r="10" />
									<line x1="12" y1="8" x2="12" y2="12" />
									<line x1="12" y1="16" x2="12.01" y2="16" />
								</svg></div>
							<h6>Maklumat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</h6>
						</div>
						<table class="info-table">
							<tr>
								<th>Petender</th>
								<td>{{ optional($tender->tenderer)->name ?? '-' }}</td>
							</tr>
							<tr>
								<th>No. {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</th>
								<td>{{ $tender->ref_number ?? '-' }}</td>
							</tr>
							<tr>
								<th>Tarikh Iklan</th>
								<td>{{ $tender->tarikh_iklan_display }}</td>
							</tr>
							<tr>
								<th>Tarikh Jual</th>
								<td>{{ $tender->tarikh_jual_display }}</td>
							</tr>
							<tr>
								<th>Tarikh Tutup</th>
								<td>{{ $tender->tarikh_tutup_display }}</td>
							</tr>
							<tr>
								<th>Masa Tutup</th>
								<td>{{ $tender->masa_tutup_display }}</td>
							</tr>
							@if ($tender->submission_location_address)
								<tr>
									<th>Tempat Hantar</th>
									<td>{!! nl2br(e($tender->submission_location_address)) !!}</td>
								</tr>
							@endif
							@if ($tender->hasBriefing())
								<tr>
									<th>Tarikh &amp; Masa Taklimat</th>
									<td>{{ \Carbon\Carbon::parse($tender->briefing_datetime)->format('j M Y H:i') }}</td>
								</tr>
								<tr>
									<th>Alamat Taklimat</th>
									<td>
										{!! nl2br(e($tender->briefing_address)) !!}
										@if ($tender->briefing_required)
											<div class="mt-1 text-danger small fw-semibold">&#10003; Kehadiran taklimat adalah diwajibkan</div>
										@endif
									</td>
								</tr>
							@endif
							<tr>
								<th>Kebenaran Khas</th>
								<td>
									@if ($tender->allow_exception)
										<span class="badge bg-success">Ya</span>
									@else
										<span class="badge bg-danger">Tidak</span>
									@endif
								</td>
							</tr>
							@if ($tender->only_bumiputera)
								<tr>
									<th>Syarikat Bumiputera Sahaja</th>
									<td><span class="badge bg-success">Ya</span></td>
								</tr>
							@endif
							@if ($tender->only_selangor == 2)
								<tr>
									<th>Syarikat Negeri</th>
									<td><span class="badge bg-info">{{ strtoupper($tender->getNegeriList()) }} SAHAJA</span></td>
								</tr>
							@elseif ($tender->only_selangor == 3)
								<tr>
									<th>Syarikat Negeri</th>
									<td><span class="badge bg-info">SELURUH MALAYSIA</span></td>
								</tr>
							@endif
							@if ($tender->district_id != null && $tender->district_id > 0)
								<tr>
									<th>Syarikat Dibawah Daerah Sahaja</th>
									<td><span class="badge bg-info">{{ strtoupper(App\Vendor::$districts[$tender->district_id]) }} SAHAJA</span>
									</td>
								</tr>
							@elseif($tender->district_id == null && $tender->getDaerahListExist() === true && $tender->only_selangor != 3)
								<tr>
									<th>Syarikat Dibawah Daerah Sahaja</th>
									<td><span class="badge bg-info">{{ strtoupper($tender->getDaerahList()) }} SAHAJA</span></td>
								</tr>
							@elseif($tender->district_id == null && $tender->district_list_rule === '[]' && $tender->only_selangor == 1)
								<tr>
									<th>Syarikat Dibawah Daerah Sahaja</th>
									<td><span class="badge bg-info">SELURUH SELANGOR</span></td>
								</tr>
							@endif
							<tr>
								<th>Harga Dokumen</th>
								<td><strong>RM {{ number_format($tender->price, 2) }}</strong></td>
							</tr>
						</table>
					</div>
				</div>

				{{-- TAB: Syarat --}}
				<div class="tab-pane fade" id="vt-syarat" role="tabpanel">
					<div class="vendor-tender-card">
						<div class="vendor-tender-card-header">
							<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
									stroke-linejoin="round">
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
									<polyline points="14 2 14 8 20 8" />
								</svg></div>
							<h6>Syarat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</h6>
						</div>
						<div class="p-4" style="font-size:0.85rem; line-height:1.7;">
							@if ($tender->tender_rules)
								{!! $tender->tender_rules !!}
							@else
								<p class="text-muted mb-0">Tiada syarat ditetapkan.</p>
							@endif
						</div>
					</div>
				</div>

				{{-- TAB: Lawatan Tapak --}}
				@if (count($tender->siteVisits) > 0)
					<div class="tab-pane fade" id="vt-lawatan" role="tabpanel">
						<div class="vendor-tender-card">
							<div class="vendor-tender-card-header">
								<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
										stroke-linejoin="round">
										<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
										<circle cx="12" cy="10" r="3" />
									</svg></div>
								<h6>Lawatan Tapak</h6>
							</div>
							<div class="table-responsive">
								<table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
									<thead style="background:#f8fafc;">
										<tr>
											<th class="py-3 ps-4"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Bil.</th>
											<th class="py-3"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tempat Berkumpul
											</th>
											<th class="py-3"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Alamat</th>
											<th class="py-3"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tarikh &amp; Waktu
											</th>
											<th class="py-3 pe-4"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Wajib Hadir</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($tender->siteVisits->sortBy('id') as $i => $visit)
											<tr style="border-color:#e5e7eb;">
												<td class="ps-4">{{ $i + 1 }}</td>
												<td>{!! nl2br(e($visit->meetpoint)) !!}</td>
												<td>{!! nl2br(e($visit->address)) !!}</td>
												<td>{{ \Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') }}</td>
												<td class="pe-4">
													@if ($visit->required)
														<span class="badge bg-success">Ya</span>
													@else
														<span class="badge bg-danger">Tidak</span>
													@endif
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				@endif

				{{-- TAB: Kod Bidang --}}
				@if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
					<div class="tab-pane fade" id="vt-kod" role="tabpanel">
						@if (count($tender->mof_codes) > 0)
							<div class="vendor-tender-card">
								<div class="vendor-tender-card-header">
									<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round">
											<polyline points="16 18 22 12 16 6" />
											<polyline points="8 6 2 12 8 18" />
										</svg></div>
									<h6>Kod Bidang MOF</h6>
								</div>
								<table class="info-table">
									@php $max_count = count($tender->mof_code_groups); @endphp
									<tr>
										<th>Kod Bidang MOF</th>
										<td>
											@foreach ($tender->mof_code_groups as $order => $data)
												{!! implode(
												    '<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>',
												    tender_vendor_codes($data['codes'], null),
												) !!}
												@if ($order != $max_count)
													<br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
												@endif
											@endforeach
										</td>
									</tr>
								</table>
							</div>
							@if (count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
								<div class="tender-code-divider">
									<span>{{ strtoupper((string) ($tender->mof_cidb_rule ?: 'or')) === 'AND' ? 'DAN' : 'ATAU' }}</span>
								</div>
							@endif
						@endif
						@if (count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
							<div class="vendor-tender-card">
								<div class="vendor-tender-card-header">
									<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round">
											<polyline points="16 18 22 12 16 6" />
											<polyline points="8 6 2 12 8 18" />
										</svg></div>
									<h6>Kod Bidang CIDB</h6>
								</div>
								<table class="info-table">
									@if (count($tender->cidb_grades) > 0)
										<tr>
											<th>Gred CIDB</th>
											<td>
												<ul class="mb-0 ps-3">
													@foreach ($tender->cidb_grades as $code)
														<li>{!! tender_cidb_grade($code->code, null) !!}</li>
													@endforeach
												</ul>
											</td>
										</tr>
									@endif
									@if (count($tender->cidb_codes) > 0)
										@php $max_count = count($tender->cidb_code_groups); @endphp
										<tr>
											<th>Pengkhususan CIDB</th>
											<td>
												@foreach ($tender->cidb_code_groups as $order => $data)
													{!! implode(
													    '<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>',
													    tender_vendor_codes($data['codes'], null),
													) !!}
													@if ($order != $max_count)
														<br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
													@endif
												@endforeach
											</td>
										</tr>
									@endif
								</table>
							</div>
						@endif
					</div>
				@endif

				{{-- TAB: Dokumen Meja Terkawal --}}
				@if ($mejaTerkawal->hasDocuments())
					<div class="tab-pane fade" id="vt-doc1" role="tabpanel">
						<div class="vendor-tender-card">
							<div class="vendor-tender-card-header">
								<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
										stroke-linejoin="round">
										<path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
										<polyline points="13 2 13 9 20 9" />
									</svg></div>
								<h6>{{ \App\Support\TenderMejaTerkawalPresenter::TAB_LABEL }}</h6>
							</div>
							@include('tenders._meja_terkawal_table', ['mejaTerkawal' => $mejaTerkawal])
						</div>
					</div>
				@endif

				{{-- TAB: Makluman / Ralat --}}
				<div class="tab-pane fade" id="vt-news" role="tabpanel">
					<div class="vendor-tender-card">
						<div class="vendor-tender-card-header">
							<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
									stroke-linejoin="round">
									<path
										d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1" />
								</svg></div>
							<h6>Makluman / Ralat</h6>
						</div>
						@php $list_ralat_news = $tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get(); @endphp
						@if ($list_ralat_news->count() > 0)
							<div class="table-responsive">
								<table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
									<thead style="background:#f8fafc;">
										<tr>
											<th class="py-3 ps-4"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:130px;">
												Tarikh</th>
											<th class="py-3"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tajuk</th>
											<th class="py-3 pe-4 text-center"
												style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:120px;">
												Tindakan</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($list_ralat_news as $news)
											<tr style="border-color:#e5e7eb;">
												<td class="ps-4">{{ \Carbon\Carbon::parse($news->published_at)->format('j M Y') }}</td>
												<td>{{ $news->title }}</td>
												<td class="pe-4 text-center"><a href="{{ route('news.show', $news->id) }}"
														class="btn btn-sm btn-primary px-3">Selanjutnya</a></td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@else
							<div class="p-4 text-muted small">Tiada makluman / ralat.</div>
						@endif
					</div>
				</div>

				{{-- TAB: Pegawai Bertanggungjawab --}}
				<div class="tab-pane fade" id="vt-officer" role="tabpanel">
					<div class="vendor-tender-card">
						<div class="vendor-tender-card-header">
							<div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
									stroke-linejoin="round">
									<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
									<circle cx="12" cy="7" r="4" />
								</svg></div>
							<h6>Pegawai Bertanggungjawab</h6>
						</div>
						@include('tenders._pegawai_bertanggungjawab_table', [
							'tableClass' => 'info-table',
						])
					</div>
				</div>

			</div>{{-- /.tab-content --}}

			<div class="mt-3">
				<a href="{{ url('/') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12" />
						<polyline points="12 19 5 12 12 5" />
					</svg>
					Kembali ke Laman Utama
				</a>
			</div>

		</div>{{-- /.col-lg-9 --}}

	</div>{{-- /.row --}}

@endsection
