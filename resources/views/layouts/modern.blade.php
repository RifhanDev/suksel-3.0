<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="_token" content="{{ csrf_token() }}">
	<title>Sistem Tender Online Selangor</title>

	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
	<link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

	<!-- Google Fonts - Modern Typography -->
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet">

	<!-- Tabler CSS -->
	<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.40.0/tabler-icons.min.css" rel="stylesheet">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	@yield('styles')

	<style>
		/* ========================================
		MODERN SIDEBAR LAYOUT 2024
		======================================== */
		:root {
			/* Selangor Brand Colors */
			--primary: #dc2626;
			--primary-rgb: 220, 38, 38;
			--primary-light: #fef2f2;
			--primary-dark: #991b1b;

			/* Modern Color Palette */
			--sidebar-bg: #2c3e50;
			--sidebar-text: #ffffff;
			--sidebar-text-muted: #bdc3c7;
			--sidebar-hover: #34495e;
			--sidebar-active: #dc2626;

			--content-bg: #f8fafc;
			--card-bg: #ffffff;
			--border-color: #e2e8f0;
			--text-primary: #1e293b;
			--text-muted: #64748b;

			/* Typography */
			--font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			--font-display: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

			/* Spacing */
			--space-1: 0.25rem;
			--space-2: 0.5rem;
			--space-3: 0.75rem;
			--space-4: 1rem;
			--space-6: 1.5rem;
			--space-8: 2rem;
			--space-12: 3rem;

			/* Border Radius */
			--radius-sm: 0.375rem;
			--radius-md: 0.5rem;
			--radius-lg: 0.75rem;
			--radius-xl: 1rem;

			/* Shadows */
			--shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
			--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
			--shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
			--shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);

			/* Transitions */
			--transition: all 0.2s ease-in-out;
		}

		/* Global Styles */
		* {
			box-sizing: border-box;
		}

		body {
			font-family: var(--font-sans);
			background: var(--content-bg);
			color: var(--text-primary);
			line-height: 1.6;
		}

		/* Page Layout */
		.page {
			display: flex;
			min-height: 100vh;
		}

		/* Sidebar Styles */
		.navbar-vertical {
			width: 280px;
			background: var(--sidebar-bg);
			border-right: 1px solid #333;
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			z-index: 1000;
			overflow-y: auto;
			transition: var(--transition);
		}

		.navbar-vertical .container-fluid {
			padding: var(--space-6);
		}

		.navbar-brand {
			margin-bottom: var(--space-8);
			padding-bottom: var(--space-6);
			border-bottom: 1px solid #ffffffd6;
		}

		.navbar-brand img {
			max-width: 68px;
			height: auto;
		}

		.navbar-nav {
			flex-direction: column;
			gap: var(--space-1);
		}

		.navbar-nav .nav-item {
			width: 100%;
		}

		.navbar-nav .nav-link {
			display: flex;
			align-items: center;
			justify-content: flex-start;
			padding: var(--space-3) var(--space-4);
			color: var(--sidebar-text-muted);
			text-decoration: none;
			border-radius: var(--radius-md);
			transition: var(--transition);
			font-weight: 500;
			text-align: left;
		}

		.navbar-nav .nav-link:hover {
			background: var(--sidebar-hover);
			color: var(--sidebar-text);
			transform: translateX(4px);
		}

		.navbar-nav .nav-link.active {
			background: var(--sidebar-active);
			color: white;
		}

		.nav-link-icon {
			width: 20px;
			height: 20px;
			margin-right: var(--space-3);
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.nav-link-title {
			font-size: 0.95rem;
			text-align: left;
			flex: 1;
		}

		/* Dropdown Styles */
		.navbar-nav .dropdown-menu {
			background: #34495e;
			border: 1px solid #4a5f7a;
			border-radius: var(--radius-md);
			box-shadow: var(--shadow-lg);
			margin-top: var(--space-2);
		}

		.navbar-nav .dropdown-item {
			color: var(--sidebar-text-muted);
			padding: var(--space-2) var(--space-4);
			transition: var(--transition);
		}

		.navbar-nav .dropdown-item:hover {
			background: var(--sidebar-hover);
			color: var(--sidebar-text);
		}

		/* Page Wrapper */
		.page-wrapper {
			flex: 1;
			margin-left: 280px;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		/* Top Navigation */
		.page-wrapper .navbar {
			background: var(--card-bg);
			border-bottom: 1px solid var(--border-color);
			box-shadow: var(--shadow-sm);
			padding: var(--space-4) var(--space-6);
		}

		.page-wrapper .navbar .container-xl {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		/* Page Body */
		.page-body {
			flex: 1;
			padding: var(--space-8) var(--space-6);
		}

		/* Cards */
		.card {
			background: var(--card-bg);
			border: 1px solid var(--border-color);
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-sm);
			transition: var(--transition);
		}

		.card:hover {
			box-shadow: var(--shadow-md);
		}

		.card-header {
			background: var(--card-bg);
			border-bottom: 1px solid var(--border-color);
			padding: var(--space-6);
		}

		.card-body {
			padding: var(--space-6);
		}

		/* Page Header */
		.page-header {
			margin-bottom: var(--space-8);
		}

		.page-pretitle {
			font-size: 0.875rem;
			font-weight: 500;
			color: var(--text-muted);
			text-transform: uppercase;
			letter-spacing: 0.05em;
			margin-bottom: var(--space-2);
		}

		.page-title {
			font-family: var(--font-display);
			font-size: 2rem;
			font-weight: 700;
			color: var(--text-primary);
			margin: 0;
		}

		/* Tabs */
		.nav-tabs {
			border-bottom: 1px solid var(--border-color);
		}

		.nav-tabs .nav-link {
			border: none;
			border-radius: var(--radius-md) var(--radius-md) 0 0;
			margin-right: var(--space-2);
			font-weight: 500;
			color: var(--text-muted);
			transition: var(--transition);
		}

		.nav-tabs .nav-link:hover {
			background: var(--primary-light);
			color: var(--primary);
		}

		.nav-tabs .nav-link.active {
			background: var(--primary);
			color: white;
		}

		/* Tables */
		.table {
			background: var(--card-bg);
			border-radius: var(--radius-lg);
			overflow: hidden;
		}

		.table th {
			background: var(--primary-light);
			border: none;
			font-weight: 600;
			color: var(--text-primary);
			padding: var(--space-4);
		}

		.table td {
			border: none;
			border-bottom: 1px solid var(--border-color);
			padding: var(--space-4);
		}

		.table tbody tr:hover {
			background: #f8fafc;
		}

		/* Buttons */
		.btn {
			border-radius: var(--radius-md);
			font-weight: 500;
			transition: var(--transition);
		}

		.btn-primary {
			background: var(--primary);
			border-color: var(--primary);
		}

		.btn-primary:hover {
			background: var(--primary-dark);
			border-color: var(--primary-dark);
			transform: translateY(-1px);
		}

		.btn-outline-primary {
			color: var(--primary);
			border-color: var(--primary);
		}

		.btn-outline-primary:hover {
			background: var(--primary);
			border-color: var(--primary);
		}

		/* Forms */
		.form-control {
			border: 1px solid var(--border-color);
			border-radius: var(--radius-md);
			padding: var(--space-3) var(--space-4);
			transition: var(--transition);
		}

		.form-control:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
		}

		/* DataTable Enhancements */
		.dataTables_wrapper .dataTables_length,
		.dataTables_wrapper .dataTables_filter {
			margin-bottom: var(--space-4);
		}

		.dataTables_wrapper .dataTables_length select,
		.dataTables_wrapper .dataTables_filter input {
			border: 1px solid var(--border-color);
			border-radius: var(--radius-md);
			padding: var(--space-2) var(--space-3);
		}

		/* Responsive Design */
		@media (max-width: 1024px) {
			.navbar-vertical {
				transform: translateX(-100%);
			}

			.navbar-vertical.show {
				transform: translateX(0);
			}

			.page-wrapper {
				margin-left: 0;
			}
		}

		/* Mobile Sidebar Toggle */
		.navbar-toggler {
			display: none;
		}

		@media (max-width: 1024px) {
			.navbar-toggler {
				display: block;
			}
		}

		/* Animations */
		@keyframes slideIn {
			from {
				opacity: 0;
				transform: translateY(20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.animate-slideIn {
			animation: slideIn 0.3s ease-out;
		}

		/* Hide Laravel Debug Bar */
		#phpdebugbar,
		.phpdebugbar,
		[class*="phpdebugbar"],
		[class*="debugbar"] {
			display: none !important;
			visibility: hidden !important;
			opacity: 0 !important;
			height: 0 !important;
			width: 0 !important;
			overflow: hidden !important;
		}

		/* Hide any debug elements */
		[class*="debug"],
		[class*="Debug"],
		[class*="DEBUG"] {
			display: none !important;
		}
	</style>
</head>

<body>
	<div class="page">
		<!-- Sidebar -->
		<aside class="navbar navbar-vertical navbar-expand-lg navbar-light" id="sidebar">
			<div class="container-fluid">
				<button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
					aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<h1 class="navbar-brand navbar-brand-autodark">
					<a href="/">
						<img src="{{ asset('images/02_selangor.png') }}" alt="Sistem Tender Online Selangor" class="navbar-brand-image">
					</a>
				</h1>
				<div class="navbar-nav collapse navbar-collapse" id="sidebar-menu">
					<div class="nav-item">
						<a href="{{ action('HomeController@index') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<i class="ti ti-home"></i>
							</span>
							<span class="nav-link-title">Utama</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ action('HomeController@prices') }}" class="nav-link {{ request()->is('prices*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<i class="ti ti-chart-line"></i>
							</span>
							<span class="nav-link-title">Carta Tender</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ action('HomeController@results') }}"
							class="nav-link {{ request()->is('results*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<i class="ti ti-trophy"></i>
							</span>
							<span class="nav-link-title">Penender Berjaya</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ route('circulars.public') }}" class="nav-link {{ request()->is('circulars*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<i class="ti ti-file-text"></i>
							</span>
							<span class="nav-link-title">Pekeliling</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ route('aduan.create') }}" class="nav-link {{ request()->is('aduan*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<i class="ti ti-message-circle"></i>
							</span>
							<span class="nav-link-title">Aduan</span>
						</a>
					</div>
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon">
								<i class="ti ti-help-circle"></i>
							</span>
							<span class="nav-link-title">Pertanyaan</span>
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{{ action('HelpsController@index') }}">Bantuan</a>
							<a class="dropdown-item" href="{{ route('manuals.show', 'pendaftaran') }}">Panduan Pengguna</a>
						</div>
					</div>
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#navbar-agencies" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon">
								<i class="ti ti-building"></i>
							</span>
							<span class="nav-link-title">Direktori Agensi</span>
						</a>
						<div class="dropdown-menu">
							@php
								try {
								    $__orgTypes = App\OrganizationType::orderBy('sort_no', 'asc')->get();
								} catch (\Throwable $e) {
								    $__orgTypes = collect();
								}
							@endphp
							@foreach ($__orgTypes as $type)
								<a class="dropdown-item"
									href="{{ action('OrganizationUnitsController@index', ['type' => $type->id]) }}">{{ $type->name }}</a>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</aside>

		<!-- Main Content -->
		<div class="page-wrapper">
			<!-- Top Navigation -->
			<div class="navbar navbar-expand-md navbar-light d-print-none">
				<h1 class="navbar-brand navbar-brand-autodark">
					Welcome {{ data_get($user, 'name') }} !
				</h1>
				<div class="container-xl">
					<button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
						aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="navbar-nav flex-row order-md-last">
						@if (!empty($user) && $user->hasRole('Vendor'))
							<div class="nav-item dropdown">
								<a href="{{ asset('cart') }}" class="nav-link d-flex lh-1 text-reset p-0">
									<span class="avatar avatar-sm" style="background-image: url({{ asset('images/cart-icon.png') }})"></span>
									<div class="d-none d-xl-block ps-2">
										<div>Senarai Tempahan</div>
										<div class="mt-1 small text-muted">{{ App\Cart::count() }} item</div>
									</div>
								</a>
							</div>
						@endif

						@if (!empty($user))
							<div class="nav-item dropdown">
								<a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
									<span class="avatar avatar-sm" style="background-image: url({{ asset('images/user-avatar.png') }})"></span>
									<div class="d-none d-xl-block ps-2">
										<div>{{ $user->vendor ? $user->vendor->name : $user->name }}</div>
										<div class="mt-1 small text-muted">{{ $user->email }}</div>
									</div>
								</a>
								<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
									@if ($user && $user->hasRole('Vendor') && $user->vendor)
										<a href="/dashboard" class="dropdown-item">
											<i class="ti ti-dashboard me-2"></i> Akaun Saya
										</a>
										<a href="{{ action('VendorsController@certificate', $user->vendor->id) }}" target="_blank"
											class="dropdown-item">
											<i class="ti ti-certificate me-2"></i> Papar Sijil Pengesahan
										</a>
										<a
											href="{{ action('ReportVendorSummaryController@index', ['year' => date('Y'), 'vendor_id' => $user->vendor->id]) }}"
											class="dropdown-item">
											<i class="ti ti-chart-pie me-2"></i> Laporan Transaksi Syarikat
										</a>
									@endif
									<a href="{{ asset('profile') }}" class="dropdown-item">
										<i class="ti ti-user me-2"></i> Profil Saya
									</a>
									@if ($user && $user->hasRole('Vendor') && Auth::user()->vendor->registration_paid)
										<a href="{{ asset('vendor/' . Auth::user()->vendor_id . '/requests') }}" class="dropdown-item">
											<i class="ti ti-heart me-2"></i> Permintaan Kemaskini
										</a>
									@endif
									@if (Session::has('original_user_id'))
										<a href="{{ route('release_user') }}" class="dropdown-item">
											<i class="ti ti-key me-2"></i> Kembali ke Pengguna Asal
										</a>
									@endif
									<div class="dropdown-divider"></div>
									<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="dropdown-item">
										<i class="ti ti-book me-2"></i> Panduan Pengguna
									</a>
									<a href="{{ asset('auth/logout') }}" class="dropdown-item">
										<i class="ti ti-logout me-2"></i> Daftar Keluar
									</a>
								</div>
							</div>
						@else
							<div class="nav-item">
								<a href="{{ route('registration') }}" class="btn btn-outline-primary me-2">Daftar Akaun</a>
								<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Daftar
									Masuk</button>
							</div>
						@endif
					</div>
				</div>
			</div>

			<!-- Page Content -->
			<div class="page-body">
				<div class="container-xl">
					@include('layouts._notification')
					@yield('content')
				</div>
			</div>
		</div>
	</div>

	<!-- Login Modal -->
	@if (empty($user))
		<div class="modal modal-blur fade" id="loginModal" tabindex="-1" role="dialog"
			aria-labelledby="loginModalLabel">
			<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="loginModalLabel">Daftar Masuk</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="POST" action="{{ action('AuthController@doLogin') }}">
						@csrf
						<div class="modal-body">
							<div class="mb-3">
								<label class="form-label">Alamat Emel</label>
								<input type="email" class="form-control" name="email" placeholder="Alamat Emel"
									value="{{ old('email') }}" required autocomplete="email">
								@error('email')
									<div class="text-danger small">{{ $message }}</div>
								@enderror
							</div>
							<div class="mb-3">
								<label class="form-label">Kata Laluan</label>
								<input type="password" class="form-control" name="password" placeholder="Kata Laluan" required
									autocomplete="current-password">
								@error('password')
									<div class="text-danger small">{{ $message }}</div>
								@enderror
							</div>
							@if ($errors->has('login'))
								<div class="alert alert-danger">
									{{ $errors->first('login') }}
								</div>
							@endif
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
							<button type="submit" class="btn btn-primary">Daftar Masuk</button>
						</div>
					</form>
					<div class="modal-body pt-0">
						<div class="text-center">
							<a href="{{ action('AuthController@forgotPassword') }}" class="text-muted">Lupa Kata Laluan?</a> &bullet;
							<a href="{{ route('registration') }}" class="text-muted">Daftar Akaun!</a> &bullet;
							<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="text-muted">Cara Mendaftar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

	@include('layouts._footer')
	@include('layouts._popupModal')

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Bootstrap 5 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Tabler JS -->
	<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
	<script src="{{ asset('js/application.js') }}"></script>

	<script>
		// Sidebar toggle for mobile
		document.addEventListener('DOMContentLoaded', function() {
			const sidebarToggle = document.querySelector('.navbar-toggler');
			const sidebar = document.getElementById('sidebar');

			if (sidebarToggle && sidebar) {
				sidebarToggle.addEventListener('click', function() {
					sidebar.classList.toggle('show');
				});
			}
		});
	</script>

	@yield('scripts')
</body>

</html>
