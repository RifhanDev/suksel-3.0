@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
@endsection

@php
	$refNo = $tender->no_tender ?: $tender->ref_number ?: '-';
	$ptj = optional($tender->tenderer)->name ?: '-';
	$tarikhSerahan = !empty($tender->submission_datetime) ? \Carbon\Carbon::parse($tender->submission_datetime) : null;
	$sahLakuTamat = $tarikhSerahan ? $tarikhSerahan->copy()->addDays(90)->format('d/m/Y') : '-';
	$pjMode = $pjMode ?? 'normal';
	$tabsReadOnly = $tabsReadOnly ?? false;
	$showJadualTab = in_array($pjMode, ['jadual', 'laporan'], true);
	$showSemakanBidaan = $pjMode === 'laporan';
	$statusBadge = match ($pjMode) {
	    'jadual' => 'Penyediaan Jadual Bidaan',
	    'laporan' => 'Semakan Laporan Bidaan',
	    default => 'Penyediaan Pemilihan Akhir Pembekal',
	};
@endphp

@section('content')
	<!-- BREADCRUMB -->
	<div class="d-flex align-items-center gap-2 mb-3">
		<a href="{{ route('perakuanjabatan.index') }}" class="text-muted small d-flex align-items-center gap-1" style="text-decoration:none;">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="15 18 9 12 15 6"></polyline>
			</svg>
			Kembali
		</a>
		<span class="text-muted small">/</span>
		<span class="text-muted small">Perakuan Jabatan</span>
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
			<h2 class="tender-title-main mb-3">{{ $tender->name }}</h2>

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
						<span class="fw-semibold text-dark" style="font-size:0.88rem;">90 Hari</span>
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
								{{ $statusBadge }}
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
					<a class="nav-link {{ $pjMode === 'normal' ? 'active' : '' }}" href="#tab-kertas-taklimat" data-bs-toggle="tab" role="tab"
						aria-controls="tab-kertas-taklimat" aria-selected="{{ $pjMode === 'normal' ? 'true' : 'false' }}">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
							<line x1="16" y1="13" x2="8" y2="13"></line>
							<line x1="16" y1="17" x2="8" y2="17"></line>
						</svg>
						Penyediaan Kertas Taklimat
					</a>
				</li>

				<li class="nav-item" role="presentation">
					<a class="nav-link" href="#tab-pengesyoran-pembekal" data-bs-toggle="tab" role="tab"
						aria-controls="tab-pengesyoran-pembekal" aria-selected="false">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
							fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="9" cy="7" r="4"></circle>
							<polyline points="23 11 17 17 14 14"></polyline>
						</svg>
						Pengesyoran Pembekal
					</a>
				</li>

				@if ($showJadualTab)
					<li class="nav-item" role="presentation">
						<a class="nav-link {{ $pjMode === 'jadual' ? 'active' : '' }}" href="#tab-jadual-bidaan" data-bs-toggle="tab" role="tab"
							aria-controls="tab-jadual-bidaan" aria-selected="{{ $pjMode === 'jadual' ? 'true' : 'false' }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
								<line x1="16" y1="2" x2="16" y2="6"></line>
								<line x1="8" y1="2" x2="8" y2="6"></line>
								<line x1="3" y1="10" x2="21" y2="10"></line>
							</svg>
							Penyediaan Jadual Bidaan
						</a>
					</li>
				@endif

				@if ($showSemakanBidaan)
					<li class="nav-item" role="presentation">
						<a class="nav-link active" href="#tab-semakan-bidaan" data-bs-toggle="tab" role="tab"
							aria-controls="tab-semakan-bidaan" aria-selected="true">
							<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="15" height="15" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
								<polyline points="14 2 14 8 20 8"></polyline>
								<line x1="12" y1="18" x2="12" y2="12"></line>
								<line x1="9" y1="15" x2="15" y2="15"></line>
							</svg>
							Laporan &amp; Pengesahan Bidaan
						</a>
					</li>
				@endif
			</ul>
		</div>

	</div>

	<!-- TAB CONTENT -->
	<div class="tab-content">

		@include('newModule.perakuanJabatan.kertas_taklimat')

		@include('newModule.perakuanJabatan.pengesyoran_pembekal')

		@if ($showJadualTab)
			@include('newModule.perakuanJabatan.jadual_bidaan')
		@endif

		@if ($showSemakanBidaan)
			@include('newModule.perakuanJabatan.semakan_bidaan')
		@endif
	</div>
@endsection

@section('scripts')
@endsection
