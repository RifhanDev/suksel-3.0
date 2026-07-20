@extends('layouts.modernLanding')

@section('styles')
	<style>
		.page-header-modern {
			position: relative;
			background: white;
			border-radius: var(--radius-lg);
			padding: 2.5rem 2.5rem;
			margin-bottom: 2rem;
			overflow: hidden;
			border: 1px solid rgba(0, 0, 0, 0.05);
			box-shadow: 0 10px 30px -10px rgba(196, 30, 58, 0.08);
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.page-header-modern::before {
			content: '';
			position: absolute;
			top: 0; right: 0; bottom: 0; left: 0;
			background-image:
				radial-gradient(at 90% 10%, rgba(196, 30, 58, 0.08) 0px, transparent 50%),
				radial-gradient(at 10% 90%, rgba(255, 204, 0, 0.08) 0px, transparent 50%);
			z-index: 0;
		}

		.page-header-modern::after {
			content: '';
			position: absolute;
			top: -30px; right: -30px;
			width: 150px; height: 150px;
			background: linear-gradient(135deg, var(--sg-red) 0%, transparent 80%);
			border-radius: 50%;
			opacity: 0.1;
			z-index: 0;
		}

		.header-content { position: relative; z-index: 2; }

		.header-pretitle {
			font-size: 0.75rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1px;
			color: var(--sg-red);
			margin-bottom: 0.5rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.header-pretitle::before {
			content: '';
			display: block;
			width: 20px; height: 2px;
			background: var(--sg-yellow);
		}

		.header-title {
			font-family: var(--font-display);
			font-weight: 800;
			font-size: 1.75rem;
			color: #111827;
			margin: 0;
			line-height: 1.1;
		}

		.header-icon-box {
			position: relative;
			z-index: 2;
			width: 70px; height: 70px;
			display: flex;
			align-items: center;
			justify-content: center;
			background: rgba(255,255,255,0.6);
			backdrop-filter: blur(10px);
			border: 1px solid rgba(255,255,255,0.8);
			border-radius: 20px;
			box-shadow: 0 15px 35px rgba(0,0,0,0.1);
			color: var(--sg-red);
			transform: rotate(-5deg);
			transition: transform 0.3s ease;
		}

		.page-header-modern:hover .header-icon-box { transform: rotate(0deg) scale(1.05); }

		.header-actions {
			position: relative;
			z-index: 2;
			display: flex;
			gap: 0.5rem;
		}

		.search-card {
			background: white;
			padding: 0.5rem;
			border-radius: var(--radius-md);
			box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
			border: 1px solid #e5e7eb;
			margin-bottom: 2rem;
			display: flex;
			align-items: center;
		}

		.search-input {
			border: none;
			padding: 0.8rem 1rem;
			font-size: 0.95rem;
			width: 100%;
			outline: none;
			background: transparent;
		}

		.search-btn {
			background: var(--sg-black);
			color: white;
			border: none;
			padding: 0.6rem 1.5rem;
			border-radius: var(--radius-sm);
			font-weight: 600;
			transition: all 0.2s;
		}

		.search-btn:hover { background: #000; transform: translateY(-1px); }

		.help-card {
			display: block;
			background: white;
			border: 1px solid #f3f4f6;
			border-radius: var(--radius-lg);
			padding: 1.5rem;
			height: 100%;
			transition: all 0.2s ease;
			text-decoration: none;
			color: inherit;
			position: relative;
			overflow: hidden;
			box-shadow: 0 2px 5px rgba(0,0,0,0.02);
		}

		.help-card:hover {
			transform: translateY(-3px);
			box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
			border-color: #e5e7eb;
			color: inherit;
		}

		.help-card::after {
			content: '';
			position: absolute;
			bottom: 0; left: 0;
			width: 100%; height: 3px;
			background: var(--sg-red);
			transform: scaleX(0);
			transform-origin: left;
			transition: transform 0.3s ease;
		}

		.help-card:hover::after { transform: scaleX(1); }

		.help-title {
			font-weight: 700;
			font-size: 1.1rem;
			color: #1f2937;
			margin-bottom: 0.5rem;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.help-count {
			background: #fef2f2;
			color: var(--sg-red);
			font-size: 0.7rem;
			font-weight: 700;
			padding: 2px 8px;
			border-radius: 50px;
		}

		.help-desc {
			font-size: 0.85rem;
			color: #6b7280;
			line-height: 1.5;
			margin: 0;
		}

		.empty-state-modern {
			text-align: center;
			padding: 4rem 2rem;
			background: white;
			border-radius: var(--radius-lg);
			border: 1px dashed #e5e7eb;
		}

		.sidebar-widget {
			background: white;
			border-radius: var(--radius-lg);
			box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05);
			border: 1px solid #f3f4f6;
			overflow: hidden;
			margin-bottom: 1.5rem;
		}

		.sidebar-header {
			padding: 1rem 1.25rem;
			border-bottom: 1px solid #f3f4f6;
			display: flex;
			align-items: center;
		}

		.sidebar-title {
			font-size: 0.95rem;
			font-weight: 800;
			color: #111827;
			margin: 0;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.news-item-sidebar {
			padding: 1rem;
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
			border-radius: var(--radius-sm);
			min-width: 44px; height: 44px;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			line-height: 1;
		}

		.news-date-small .day { font-weight: 800; color: var(--sg-red); font-size: 0.9rem; }
		.news-date-small .month { font-size: 0.55rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin-top: 2px; }

		.news-item-sidebar:hover .news-date-small { background: var(--sg-red); border-color: var(--sg-red); }
		.news-item-sidebar:hover .news-date-small .day,
		.news-item-sidebar:hover .news-date-small .month { color: white; }

		@media (max-width: 992px) {
			.page-header-modern { padding: 1.5rem; flex-direction: column; align-items: flex-start; gap: 1rem; }
			.header-icon-box { display: none; }
			.header-actions { width: 100%; margin-top: 0.5rem; }
		}
	</style>
@endsection

@section('content')
	<div class="row g-4">

		<!-- LEFT -->
		<div class="col-lg-9">

			<!-- Header -->
			<div class="page-header-modern">
				<div class="header-content">
					<div class="header-pretitle">Pusat Bantuanaaa</div>
					<h2 class="header-title">Soalan Lazim & Panduan</h2>
				</div>

				@if (Auth::user() && Auth::user()->hasRole('Admin'))
					<div class="header-actions">
						<a href="{{ asset('helpcategories') }}"
							class="btn btn-warning btn-sm fw-bold d-inline-flex align-items-center gap-1">
							<i class="ti ti-tags"></i> Kategori
						</a>
						<a href="{{ asset('helpcategories') }}"
							class="btn btn-primary btn-sm fw-bold d-inline-flex align-items-center gap-1">
							<i class="ti ti-plus"></i> Tambah Topik
						</a>
					</div>
				@else
					<div class="header-icon-box d-none d-md-flex">
						<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
							<path d="M12 17l0 .01" />
							<path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4" />
						</svg>
					</div>
				@endif
			</div>

			<!-- Search Bar -->
			<form action="{{ action('HelpsController@search') }}" method="GET">
				@csrf
				<div class="search-card">
					<div class="ps-3 text-muted">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
							<path d="M21 21l-6 -6" />
						</svg>
					</div>
					<input type="text" name="q" class="search-input" placeholder="Carian topik bantuan atau soalan lazim..."
						value="{{ request('q') }}">
					<button type="submit" class="search-btn">Cari</button>
				</div>
			</form>

			<!-- Categories Grid -->
			@if (count($categories) == 0)
				<div class="empty-state-modern">
					<div class="text-muted mb-3">
						<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
							stroke="#e5e7eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
							<path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
							<path d="M3 6v13" /><path d="M12 6v13" /><path d="M21 6v13" />
						</svg>
					</div>
					<h5 class="fw-bold text-dark">Tiada Topik Bantuan</h5>
					<p class="text-muted small">Belum ada kategori soalan lazim yang didaftarkan pada masa ini.</p>
				</div>
			@else
				<div class="row g-4">
					@foreach ($categories as $category)
						<div class="col-md-6">
							<a href="{{ asset('helps/' . $category->id) }}" class="help-card">
								<div class="help-title">
									{{ $category->name }}
									<span class="help-count">{{ $category->helps->count() }}</span>
								</div>
								<p class="help-desc">
									{{ $category->description ?? 'Klik untuk melihat senarai soalan lazim bagi kategori ini.' }}
								</p>
							</a>
						</div>
					@endforeach
				</div>
			@endif

		</div>

		<!-- RIGHT -->
		<div class="col-lg-3">
			<div class="d-flex flex-column gap-3">
				@include('layouts._register')
				@include('layouts._news')
			</div>
		</div>

	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/news.js') }}"></script>
	<script src="{{ asset('js/easy-ticker.js') }}"></script>

	<script>
		$(document).ready(function() {
			$('#announcements-ticker').easyTicker({
				direction: 'up',
				easing: 'swing',
				speed: 'slow',
				interval: 3000,
				height: 'auto',
				visible: 5,
				mousePause: 1
			});
		});
	</script>
@endsection
