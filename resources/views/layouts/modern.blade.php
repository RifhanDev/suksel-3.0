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

	<!-- Bootstrap 5 + Modern Styles -->
	<link href="{{ asset('css/modern.css') }}" rel="stylesheet">
	<!-- Tabler Icons -->
	<link href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.40.0/tabler-icons.min.css" rel="stylesheet">
	<!-- Legacy application.css for forms/tables styling -->
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/modern-actions.css') }}" rel="stylesheet">
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
			--sidebar-active: #cf5858;

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
		.sidebar-vertical {
			width: 280px;
			background: var(--sidebar-bg);
			border-right: 1px solid #333;
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			z-index: 1000;
			display: flex;
			flex-direction: column;
			transition: transform 0.3s ease-in-out;
		}

		.sidebar-header {
			padding: var(--space-6);
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
			flex-shrink: 0;
		}

		.sidebar-brand {
			display: flex;
			align-items: center;
			justify-content: center;
			text-decoration: none;
		}

		.sidebar-brand-image {
			max-width: 68px;
			height: auto;
		}

		.sidebar-body {
			flex: 1;
			overflow-y: auto;
			overflow-x: hidden;
			padding: var(--space-4) var(--space-6);
		}

		.sidebar-nav {
			display: flex;
			flex-direction: column;
			gap: var(--space-1);
		}

		.sidebar-nav .nav-item {
			width: 100%;
		}

		.sidebar-nav .nav-link {
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
			width: 100%;
		}

		.sidebar-nav .nav-link:hover {
			background: var(--sidebar-hover);
			color: var(--sidebar-text);
			transform: translateX(4px);
		}

		.sidebar-nav .nav-link.active {
			background: var(--sidebar-active);
			color: white;
		}

		/* Keep dropdown toggle text white when clicked/focused/active */
		.sidebar-nav .nav-link:focus,
		.sidebar-nav .nav-link:active,
		.sidebar-nav .dropdown.show .nav-link {
			color: white !important;
			background: var(--sidebar-hover);
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
			font-size: 1.5rem;
			text-align: left;
			flex: 1;
			min-width: 0;
			overflow: visible;
			white-space: normal;
			word-wrap: break-word;
			line-height: 1.4;
			padding-right: 8px;
		}

		/* Dropdown toggle icon spacing */
		.nav-link.dropdown-toggle::after {
			margin-left: auto;
			flex-shrink: 0;
			margin-right: 0;
		}

		/* Bootstrap 5 Dropdown Styles for Sidebar */
		.sidebar-nav .dropdown-menu {
			background: #34495e;
			border: 1px solid rgba(255, 255, 255, 0.1);
			padding: 0.5rem 0;
		}

		.sidebar-nav .dropdown-item {
			color: #ffffff;
			padding: 0.5rem 1rem;
			white-space: normal;
		}

		.sidebar-nav .dropdown-item:hover,
		.sidebar-nav .dropdown-item:focus {
			background: #2c3e50;
			color: #ffffff;
		}

		/* Top navbar specific styles */
		.navbar.navbar-expand-md .navbar-nav {
			flex-direction: row;
			align-items: center;
		}

		.navbar.navbar-expand-md .navbar-nav .nav-item {
			width: auto;
		}

		.navbar.navbar-expand-md .navbar-nav .nav-link {
			justify-content: center;
			padding: var(--space-2) var(--space-3);
		}

		/* Ensure buttons are properly positioned */
		.navbar .navbar-nav.ms-auto {
			margin-left: auto !important;
		}

		.navbar .navbar-nav .nav-item .btn {
			margin-left: var(--space-2);
		}

		/* Sidebar logout button */
		.sidebar-vertical .nav-item.mt-auto {
			margin-top: auto !important;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
			padding-top: var(--space-3);
		}

		.sidebar-vertical .nav-item.mt-auto .nav-link {
			color: #ff6b6b !important;
			font-weight: 600;
		}

		.sidebar-vertical .nav-item.mt-auto .nav-link:hover {
			background-color: rgba(255, 107, 107, 0.1) !important;
			color: #ff5252 !important;
		}

		.sidebar-vertical .nav-item.mt-auto .nav-link .nav-link-icon {
			display: inline-flex !important;
			align-items: center;
			justify-content: center;
			width: 2rem;
			height: 2rem;
		}

		.sidebar-vertical .nav-item.mt-auto .nav-link .nav-link-icon i {
			color: #ff6b6b !important;
			font-size: 1.5rem !important;
			display: inline-block !important;
			font-weight: bold !important;
		}

		.sidebar-vertical .nav-item.mt-auto .nav-link .nav-link-icon .icon-logout-fallback {
			color: #ff6b6b !important;
			stroke: #ff6b6b !important;
			display: inline-block !important;
			vertical-align: middle;
		}

		/* Top navbar user dropdown visibility */
		.page-wrapper .navbar .navbar-nav {
			display: flex !important;
			flex-direction: row !important;
			align-items: center !important;
		}

		.page-wrapper .navbar .nav-item.dropdown {
			display: block !important;
		}

		.page-wrapper .navbar .nav-link {
			display: flex !important;
			padding: 0.5rem 1rem !important;
		}

		.page-wrapper .navbar .avatar {
			display: inline-block !important;
		}

		/* User dropdown toggle styling */
		/* Navbar dropdown override for Bootstrap 5 */
		.navbar .dropdown-menu {
			background: #ffffff;
			border: 1px solid rgba(0, 0, 0, 0.15);
		}

		.navbar .dropdown-item {
			padding: 0.5rem 1rem;
			color: #212529;
		}

		.navbar .dropdown-item:hover,
		.navbar .dropdown-item:focus {
			background: #f8f9fa;
			color: #212529;
		}

		/* Modal accessibility fixes */
		.modal {
			z-index: 1055 !important;
		}

		.modal-backdrop {
			z-index: 1050 !important;
		}

		.modal[aria-hidden="false"] {
			pointer-events: auto !important;
		}

		.modal[aria-hidden="true"] {
			pointer-events: none !important;
		}

		/* Ensure modal becomes visible when shown */
		.modal.show {
			opacity: 1 !important;
			display: block !important;
			visibility: visible !important;
		}

		.modal.show .modal-dialog {
			transform: none !important;
			opacity: 1 !important;
		}

		.modal.show .modal-content {
			opacity: 1 !important;
			visibility: visible !important;
		}

		/* Modal backdrop */
		.modal-backdrop.show {
			opacity: 0.5 !important;
			display: block !important;
		}

		/* Page Wrapper */
		.page-wrapper {
			flex: 1;
			margin-left: 280px;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
			width: calc(100% - 280px);
			transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
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
			padding: var(--space-6) var(--space-4);
			width: 100%;
		}

		/* Container Constraints - Better width management */
		.page-body .container-xl {
			max-width: 100%;
			width: 100%;
			margin: 0 auto;
			padding-left: var(--space-6);
			padding-right: var(--space-6);
		}

		/* For forms, use better constraints */
		.page-body .container-xl.form-container {
			max-width: 1200px;
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

		/* Bootstrap 3 Nav Pills & Stacked - for vendor form tabs */
		.nav-pills > li {
			float: left;
		}

		.nav-pills > li > a {
			border-radius: 4px;
		}

		.nav-pills > li + li {
			margin-left: 2px;
		}

		.nav-pills > li.active > a,
		.nav-pills > li.active > a:hover,
		.nav-pills > li.active > a:focus {
			color: #fff;
			background-color: var(--primary);
		}

		.nav-stacked > li {
			float: none;
		}

		.nav-stacked > li + li {
			margin-top: 2px;
			margin-left: 0;
		}

		.nav > li > a {
			position: relative;
			display: block;
			padding: 10px 15px;
		}

		.nav > li > a:hover,
		.nav > li > a:focus {
			text-decoration: none;
			background-color: #eee;
		}

		.nav > li.disabled > a {
			color: #777;
		}

		.nav > li.disabled > a:hover,
		.nav > li.disabled > a:focus {
			color: #777;
			text-decoration: none;
			cursor: not-allowed;
			background-color: transparent;
		}

		/* Tab panes */
		.tab-content > .tab-pane {
			display: none;
		}

		.tab-content > .active {
			display: block;
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
		/* Tablets and below */
		@media (max-width: 1024px) {
			.sidebar-vertical {
				transform: translateX(-100%);
			}

			.sidebar-vertical.show {
				transform: translateX(0);
				box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
			}

			.page-wrapper {
				margin-left: 0;
				width: 100%;
			}

			.page-body {
				padding: var(--space-4) var(--space-3);
			}

			.page-body .container-xl {
				padding-left: var(--space-4);
				padding-right: var(--space-4);
			}
		}

		/* Mobile phones */
		@media (max-width: 768px) {
			.page-body {
				padding: var(--space-3) var(--space-2);
			}

			.page-body .container-xl {
				padding-left: var(--space-3);
				padding-right: var(--space-3);
			}

			.page-title {
				font-size: 1.5rem;
			}

			.card-body {
				padding: var(--space-4);
			}
		}

		/* Small mobile phones */
		@media (max-width: 480px) {
			.sidebar-vertical {
				width: 260px;
			}

			.page-body {
				padding: var(--space-2);
			}

			.page-body .container-xl {
				padding-left: var(--space-2);
				padding-right: var(--space-2);
			}

			.page-title {
				font-size: 1.25rem;
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
		@include('layouts._side')

		<!-- Main Content -->
		<div class="page-wrapper">
			<!-- Top Navigation -->
			@include('layouts._topbar')

			<!-- Page Content -->
			<div class="page-body">
				<div id="container" class="container-xl">
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
						<button type="button" class="btn-close" onclick="hideLoginModal()" aria-label="Close"></button>
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
			</ul>
		</div>
	@endif

	@include('layouts._footer')
	@include('layouts._popupModal')

	<!-- Modern JS bundle (jQuery + Bootstrap 5) -->
	<script src="{{ asset('js/modern.js') }}"></script>
	<!-- Legacy application.js for vendor forms and old scripts -->
	<script src="{{ asset('js/application.js') }}"></script>

	<script>
		// Debug and fix modal functionality
		document.addEventListener('DOMContentLoaded', function() {
			console.log('DOM loaded, initializing modals...');

			// Check if Bootstrap (v3) is available via jQuery
			if (typeof $.fn.modal === 'undefined') {
				console.error('Bootstrap 3 is not loaded!');
				return;
			}

			console.log('Bootstrap 3 is available via jQuery');

			// Bootstrap 3 modal initialization is automatic via data-toggle
			// Just ensure all modals are properly initialized
			$('.modal').each(function() {
				$(this).modal({
					show: false
				});
				console.log('Modal initialized (Bootstrap 3):', this.id);
			});

			// Handle data-bs-target (Bootstrap 5 syntax) and convert to data-toggle (Bootstrap 3)
			$('[data-bs-toggle="modal"]').each(function() {
				$(this).attr('data-toggle', 'modal');
				const target = $(this).attr('data-bs-target');
				if (target) {
					$(this).attr('data-target', target);
				}
			});

			console.log('Bootstrap 3 modals ready');
		});

		// Fallback method - simple modal without Bootstrap
		function showLoginModal() {
			console.log('Fallback: Showing login modal');
			const modal = document.getElementById('loginModal');
			if (modal) {
				modal.style.display = 'block';
				modal.classList.add('show');
				modal.setAttribute('aria-hidden', 'false');
				modal.setAttribute('aria-modal', 'true');
				document.body.classList.add('modal-open');

				// Create backdrop
				const existingBackdrop = document.getElementById('modal-backdrop');
				if (existingBackdrop) {
					existingBackdrop.remove();
				}

				const backdrop = document.createElement('div');
				backdrop.className = 'modal-backdrop fade show';
				backdrop.id = 'modal-backdrop';
				backdrop.onclick = hideLoginModal;
				document.body.appendChild(backdrop);

				// Focus on first input
				const firstInput = modal.querySelector('input[type="email"]');
				if (firstInput) {
					setTimeout(() => firstInput.focus(), 100);
				}
			}
		}

		function hideLoginModal() {
			console.log('Fallback: Hiding login modal');
			const modal = document.getElementById('loginModal');
			if (modal) {
				modal.style.display = 'none';
				modal.classList.remove('show');
				modal.setAttribute('aria-hidden', 'true');
				modal.removeAttribute('aria-modal');
				document.body.classList.remove('modal-open');

				const backdrop = document.getElementById('modal-backdrop');
				if (backdrop) {
					backdrop.remove();
				}
			}
		}

		// Add global functions for easy access
		window.showLoginModal = showLoginModal;
		window.hideLoginModal = hideLoginModal;
		window.openLoginModal = showLoginModal; // Alias for easier access

		// Direct button handler
		document.addEventListener('DOMContentLoaded', function() {
			const loginBtn = document.getElementById('loginButton');
			if (loginBtn) {
				loginBtn.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					console.log('Direct button click handler triggered');
					openLoginModal();
				});
				console.log('Login button direct handler attached');
			}

			// Convert Bootstrap 5 dropdown syntax to Bootstrap 3
			$('[data-bs-toggle="dropdown"]').each(function() {
				$(this).attr('data-toggle', 'dropdown');
				console.log('Dropdown converted to Bootstrap 3:', this);
			});
		});

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

		// CSRF Token Setup for AJAX Requests
		$.ajaxSetup({
			headers: {
				'X-CSRF-Token': $('meta[name=_token]').attr('content')
			}
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

	@yield('scripts')
</body>

</html>
