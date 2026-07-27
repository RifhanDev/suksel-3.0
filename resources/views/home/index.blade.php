@extends('layouts.modernLanding')

@section('styles')
    <style>
        .hero-card {
            background: white;
            border-radius: var(--radius-sm);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            z-index: 1;
        }

        #landing-carousel {
            position: relative;
            width: 100%;
            margin: 0;
            padding: 0;
            height: 100%;
            flex: 1;
            display: flex;
            flex-direction: column;
            z-index: 1;
        }

        .carousel-inner {
            position: relative;
            width: 100%;
            overflow: hidden;
            height: 100%;
            flex: 1;
        }

        .carousel-item {
            position: relative;
            display: none;
            float: left;
            width: 100%;
            margin-right: -100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transition: transform .6s ease-in-out;
            height: 100%;
        }

        .carousel-item.active,
        .carousel-item-next,
        .carousel-item-prev {
            display: block;
        }

        .carousel-item img {
            display: block;
            width: 100%;
            height: 100%;
            max-width: 100%;
            border-radius: 0;
            object-fit: contain;
            object-position: center;
        }

        #landing-carousel .carousel-inner {
            overflow: hidden;
            height: auto;
        }

        .carousel-control-prev, 
        .carousel-control-next {
            width: 5%;
            height: 100%;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.6;
            z-index: 10;
            background: none;
            border: none;
        }

        .carousel-control-prev:hover, 
        .carousel-control-next:hover {
            opacity: 1;
            background: rgba(0,0,0,0.1);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1) grayscale(100); 
            width: 2rem;
            height: 2rem;
        }

        #landing-carousel .carousel-indicators {
            position: absolute;
            right: 0;
            bottom: 10px;
            left: 0;
            z-index: 15;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            margin: 0;
            list-style: none;
            width: 100%;
        }

        .carousel-indicators [data-bs-target] {
            box-sizing: content-box;
            flex: 0 1 auto;
            width: 10px;
            height: 10px;
            padding: 0;
            margin-right: 6px;
            margin-left: 6px;
            text-indent: -999px;
            cursor: pointer;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #999;
            opacity: .5;
            transition: opacity .6s ease;
            border-radius: var(--radius-sm);
        }

        .carousel-indicators .active {
            opacity: 1;
            background-color: var(--sg-red);
            border-color: var(--sg-red);
        }

        .news-card-wrapper {
            background: white;
            border-radius: var(--radius-sm);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .news-card-header {
            background: #fff;
            padding: 1rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .news-card-wrapper .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .news-item-sidebar {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            gap: 0.75rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .news-item-sidebar:hover { background: #fef2f2; }
        .news-date-small {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            min-width: 44px; height: 44px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            line-height: 1;
            flex-shrink: 0;
        }
        .news-date-small .day { font-weight: 800; color: var(--sg-red); font-size: 0.9rem; }
        .news-date-small .month { font-size: 0.55rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin-top: 2px; }
        .news-item-sidebar:hover .news-date-small { background: var(--sg-red); border-color: var(--sg-red); }
        .news-item-sidebar:hover .news-date-small .day,
        .news-item-sidebar:hover .news-date-small .month { color: white; }

        .news-title-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 3px solid var(--sg-red);
            padding-left: 0.75rem;
            min-height: 38px;
        }

        .news-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .news-subtitle {
            font-size: 0.6rem;
            color: #6b7280;
            margin-top: 2px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .list-group-flush { margin: 0; padding: 0; width: 100%; }

        .news-item {
            text-decoration: none;
            display: flex;
            width: 100%;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s ease;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .news-item:hover {
            background-color: #fef2f2;
        }

        .news-item:hover .news-date-box {
            background: var(--sg-red);
            border-color: var(--sg-red);
        }

        .news-item:hover .news-date-box .news-day,
        .news-item:hover .news-date-box .news-month {
            color: white;
        }

        .news-date-box {
            min-width: 48px;
            height: 48px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-sm);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .news-day {
            font-size: 1rem;
            font-weight: 800;
            color: var(--sg-red);
            line-height: 1;
            transition: color 0.2s ease;
        }

        .news-month {
            font-size: 0.55rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1;
            margin-top: 2px;
            transition: color 0.2s ease;
        }

        .news-info {
            flex: 1;
            min-width: 0;
        }

        .news-item-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }

        .news-item:hover .news-item-title {
            color: var(--sg-red);
        }

        .news-item-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .news-tag {
            font-size: 0.6rem;
            font-weight: 600;
            color: var(--sg-red);
            background: #fef2f2;
            padding: 0.15rem 0.4rem;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .news-time {
            font-size: 0.65rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        #announcements-ticker, #home-news-ticker {
            height: 100%;
            flex: 1;
            overflow: hidden;
            width: 100%;
        }

        .news-card-wrapper .p-3 {
            flex-shrink: 0;
        }

        .btn-see-all {
            color: #374151;
            background: white;
            border: 2px solid #e5e7eb;
            font-weight: 700;
            font-size: 0.8rem;
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-block;
        }

        .btn-see-all:hover {
            background-color: var(--sg-red);
            border-color: var(--sg-red);
            color: white;
            box-shadow: 0 8px 15px rgba(196, 30, 58, 0.25);
            transform: translateY(-2px);
        }

        /* ==========================================================================
           VENDOR REGISTER CARD
           ========================================================================== */
        .cta-promo-card {
            background: linear-gradient(100deg, #ffffff 50%, #fff1f2 100%);
            border: 1px solid #ffe4e6;
            border-radius: var(--radius-md);
            padding: 1.5rem 2rem; /* Reduced padding for compact look */
            position: relative;
            box-shadow: 0 4px 15px rgba(196, 30, 58, 0.05);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        /* Decorative Pattern */
        .cta-promo-card::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 150px;
            background-image: repeating-linear-gradient(45deg, rgba(196, 30, 58, 0.03) 0, rgba(196, 30, 58, 0.03) 2px, transparent 0, transparent 50%);
            z-index: 0;
            pointer-events: none;
        }

        .promo-content {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            position: relative;
            z-index: 2;
        }

        .promo-icon-badge {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--sg-red) 0%, #b91c1c 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(196, 30, 58, 0.2);
            flex-shrink: 0;
        }

        .promo-text h4 {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.15rem;
            color: #111827;
            margin: 0 0 0.15rem 0;
        }

        .promo-text p {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0;
        }
        
        .promo-tag {
            background: #fff;
            color: var(--sg-red);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #ffe4e6;
            margin-bottom: 4px;
            display: inline-block;
        }

        .promo-actions {
            display: flex;
            gap: 0.75rem;
            position: relative;
            z-index: 2;
        }

        .btn-promo-main {
            background: var(--sg-red);
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(196, 30, 58, 0.2);
            border: 1px solid var(--sg-red);
        }

        .btn-promo-main:hover {
            background: #9f1239;
            color: white;
            border-color: #9f1239;
        }

        .btn-promo-sub {
            background: white;
            color: #374151;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .btn-promo-sub:hover {
            border-color: #d1d5db;
            background: #f9fafb;
            color: #111827;
        }

        /* ==========================================================================
           TABLE HEADER
           ========================================================================== */
        .tender-card {
            background: white;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: none;
            overflow: hidden;
        }

        .tender-header {
            background: #fff;
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .header-title-group {
            display: flex;
            flex-direction: column;
            justify-content: center; 
            border-left: 4px solid var(--sg-red);
            padding-left: 1rem;
            min-height: 42px;
        }

        .header-title {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 2px 0 0 0;
            font-weight: 400;
            line-height: 1.2;
        }

        /* Tabs */
        .nav-modern-tabs {
            display: inline-flex;
            background: #f3f4f6;
            padding: 4px;
            border-radius: var(--radius-sm);
            list-style: none;
            margin: 0;
        }

        .nav-modern-tabs .nav-item { margin: 0; }

        .nav-modern-tabs .nav-link {
            border: none;
            padding: 0.5rem 1.25rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            background: transparent;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .nav-modern-tabs .nav-link:hover { color: #1f2937; }

        .nav-modern-tabs .nav-link.active {
            background: white;
            color: var(--sg-red);
            font-weight: 700;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Table */
        .table-modern thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }
        .table-modern tbody td {
            padding: 0.9rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }
        .table-modern tbody tr:hover { background-color: #fef2f2; }

        /* Responsive */
        @media (max-width: 992px) {
            .cta-promo-card {
                flex-direction: column;
                padding: 1.5rem;
                text-align: center;
                gap: 1.25rem;
            }
            .promo-content { flex-direction: column; }
            .promo-actions { width: 100%; justify-content: center; }
            
            .tender-header { flex-direction: column; align-items: flex-start; }
            .header-title-group { width: 100%; border-left: 4px solid var(--sg-red); }
            .nav-modern-tabs { width: 100%; justify-content: space-between; }
            .nav-modern-tabs .nav-link { flex: 1; text-align: center; }
        }
    </style>
@endsection

@section('content')

	<div class="row gy-4 mb-4" style="align-items:stretch;">
		<!-- LEFT: CAROUSEL -->
		<div class="col-lg-9">
			<div class="hero-card">
				<div id="landing-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
					<div class="carousel-indicators">
						@foreach (range(0, count($banners) - 1) as $c)
							<button type="button" data-bs-target="#landing-carousel" data-bs-slide-to="{{ $c }}"
								@if ($c == 0) class="active" @endif aria-current="true"
								aria-label="Slide {{ $c + 1 }}"></button>
						@endforeach
					</div>

					<div class="carousel-inner">
						<?php $index = 1; ?>
						@foreach ($banners as $banner)
							@if (\Illuminate\Support\Facades\Schema::hasTable('uploads') && $banner->file)
								<div class="carousel-item @if ($index == 1) active @endif">
									@if ($banner->link)
										<a href="{{ $banner->link }}" title="{{ $banner->title }}" class="d-block">
									@endif
									<img src="{{ $banner->file->url . '/' . $banner->file->name }}" alt="{{ $banner->title }}">
                                    @if ($banner->link)
										</a>
									@endif
								</div>
								<?php $index++; ?>
							@endif
						@endforeach
					</div>

					<button class="carousel-control-prev" type="button" data-bs-target="#landing-carousel" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#landing-carousel" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button>
				</div>
			</div>
		</div>

		<!-- RIGHT: NEWS -->
		<div class="col-lg-3">
			<div class="news-card-wrapper">

				<div class="news-card-header">
					<div class="news-title-group">
						<h5 class="news-title">Berita Terkini</h5>
                        <p class="news-subtitle">Pengumuman & Makluman</p>
					</div>
				</div>
				
				<div class="card-body p-0 flex-grow-1" style="overflow:hidden;">
					<div id="home-news-ticker" style="overflow:hidden;">
						<div class="general-item-list m-0 p-0">
							@forelse ($global_news as $news)
								@php
									$newsDate = \Carbon\Carbon::parse($news->published_at ?: $news->created_at);
								@endphp
								<a href="{{ asset('news/' . $news->id) }}" class="news-item-sidebar">
									<div class="news-date-small flex-shrink-0">
										<span class="day">{{ $newsDate->format('d') }}</span>
										<span class="month">{{ $newsDate->format('M') }}</span>
									</div>
									<div class="news-info">
										<div class="text-dark fw-bold small mb-1" style="line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
											{{ $news->title }}
										</div>
										<div class="d-flex align-items-center gap-1 mt-1">
											<span class="news-tag">Berita</span>
											<span class="text-muted" style="font-size:0.65rem;">{{ $newsDate->format('Y') }}</span>
										</div>
									</div>
								</a>
							@empty
								<div class="p-3 text-center text-muted small">Tiada berita terkini.</div>
							@endforelse
						</div>
					</div>
				</div>

				<div class="p-3 bg-light border-top text-center">
					<a href="/news" class="btn btn-see-all btn-sm w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
						Lihat Semua Berita
					</a>
				</div>
			</div>
		</div>
	</div>

    <!-- VENDOR REGISTER CARD -->
	@if (!Auth::check())
		<div class="row mb-4">
			<div class="col-12">
				<div class="cta-promo-card">
					
                    <div class="promo-content">
                        <div class="promo-icon-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                        </div>
                        <div class="promo-text">
                            <span class="promo-tag">Akses Vendor</span>
                            <h4>Daftar Akaun Vendor</h4>
                            <p>Dapatkan akses penuh dokumen tender & sebut harga.</p>
                        </div>
                    </div>

                    <div class="promo-actions">
                        <a href="{{ asset('company_search') }}" class="btn-promo-sub">
                            Semak Status
                        </a>
                        <a href="{{ asset('register') }}" class="btn-promo-main">
                            Daftar Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l14 0" /><path d="M15 16l4 -4" /><path d="M15 8l4 4" /></svg>
                        </a>
                    </div>

				</div>
			</div>
		</div>
	@endif

	<!-- TABLE -->
	<div class="row mb-3">
		<div class="col-12">
			<div class="tender-card">
				<div class="tender-header">
					
                    <div class="header-title-group">
						<h3 class="header-title">
							Senarai Tender & Sebut Harga
						</h3>
						<p class="header-subtitle">Paparan 20 item terkini yang sedang aktif</p>
					</div>
                    
                    <!-- Tabs -->
                    <ul class="nav nav-modern-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if (!Request::get('type')) active @endif" href="{{ action('HomeController@index') }}">
                                Semua
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Request::get('type') == 'tenders') active @endif"
                                href="{{ action('HomeController@index', ['type' => 'tenders']) }}">
                                Tender
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Request::get('type') == 'quotations') active @endif"
                                href="{{ action('HomeController@index', ['type' => 'quotations']) }}">
                                Sebut Harga
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Request::get('type') == 'pembelian_terus') active @endif"
                                href="{{ action('HomeController@index', ['type' => 'pembelian_terus']) }}">
                                Pembelian Terus
                            </a>
                        </li>
                    </ul>
				</div>

				<div class="card-body p-0">
					<div class="table-responsive p-2">
						<table class="DT2 table table-modern table-hover w-100 mb-0" data-path="{{ $path }}">
							<thead>
								<tr>
									<th class="w-30">No / Tajuk Dokumen</th>
									<th class="w-15">Kod Bidang</th>
									<th class="w-15 text-center">Tarikh Jual</th>
									<th class="w-15 text-center">Tarikh Tutup</th>
									<th class="w-15 text-end">Harga (RM)</th>
								</tr>
							</thead>
							<tbody>
                                <!-- Ajax loads here -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
	<script src="{{ asset('js/easy-ticker.js') }}"></script>
	<script type="text/javascript">

		$('#home-news-ticker').easyTicker({
			direction: 'up',
			easing: 'swing',
			speed: 'slow',
			interval: 3000,
			height: 'auto',
			visible: 5,
			mousePause: 1
		});

		$('.DT2').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'codes',
						name: 'codes'
					},
					{
						data: 'document_start_date',
						name: 'document_start_date'
					},
					{
						data: 'submission_datetime',
						name: 'submission_datetime'
					},
					{
						data: 'price',
						name: 'price'
					}
				],
				stateSave: true,
				language: {
					sEmptyTable: "Tiada data",
					sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
					sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
					sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Papar _MENU_ rekod",
					sLoadingRecords: "Diproses...",
					sProcessing: "Sedang diproses...",
					sSearch: "Carian:",
					sZeroRecords: "Tiada padanan rekod yang dijumpai.",
					oPaginate: {
						sFirst: "Pertama",
						sPrevious: "Sebelum",
						sNext: "Kemudian",
						sLast: "Akhir"
					},
					oAria: {
						sSortAscending: ": diaktifkan kepada susunan lajur menaik",
						sSortDescending: ": diaktifkan kepada susunan lajur menurun"
					}
				},
				aaSorting: []
			});
		});

	</script>

    <!-- Botman Chatbot Widget -->
	<script>
		@php $chat_id = Str::random(8); @endphp

		var botmanWidget = {
			title: 'Lela (Bot)',
			introMessage: 'Hi, saya Lela. Saya di sini untuk membantu anda dan menjawab persoalan anda.',
			mainColor: '#c32508',
			aboutText: '',
			bubbleBackground: '#c32508',
			headerTextColor: '#fff',
			desktopHeight: 500,
			desktopWidth: 400,
			bubbleAvatarUrl: '{{ asset('images/chatbot.png') }}',
			placeholderText: 'Hantar Pesanan..',
			frameEndpoint: "{{ route('chat_widget',['chat_id' => $chat_id]) }}",
			userId: "{{ $chat_id }}"
		};

		window.addEventListener("message", (event) => {

			// console.log(event);

			if (event.data != "")
			{
				let data = event.data;

				if(data.status == 200)
				{
					let messages = data.messages;

					messages.forEach(row => {

						if (row.text == "DataACK")
						{
							sender_response_detail = row.additionalParameters;

							if (sender_response_detail.sender == "user_chat")
							{
								if (sender_response_detail.type == "image_only")
								{
									botmanChatWidget.say('<img src="' + sender_response_detail.response + '" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only")
								{
									botmanChatWidget.say(sender_response_detail.response);
								}
							}

							if (sender_response_detail.sender == "bot")
							{
								if (sender_response_detail.type == "image_only")
								{
									botmanChatWidget.sayAsBot('<img src="' + sender_response_detail.response + '" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only")
								{
									botmanChatWidget.sayAsBot(sender_response_detail.response);
								}
							}
						}
					});
					// botmanChatWidget.sayAsBot('TQ. <img src="https://botman.io/img/logo.png" alt="botsaywhat" width="20" height="20">');
				}
			}
		});
	</script>
	{{-- <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script> --}}
	<script src='{{ asset('packages/botman/build/js/widget.js') }}'></script>
@endsection