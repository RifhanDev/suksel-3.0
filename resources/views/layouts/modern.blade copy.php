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
		.navbar-vertical .nav-item.mt-auto {
			margin-top: auto !important;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
			padding-top: var(--space-3);
		}

		.navbar-vertical .nav-item.mt-auto .nav-link {
			color: #ff6b6b !important;
			font-weight: 600;
		}

		.navbar-vertical .nav-item.mt-auto .nav-link:hover {
			background-color: rgba(255, 107, 107, 0.1) !important;
			color: #ff5252 !important;
		}

		.navbar-vertical .nav-item.mt-auto .nav-link .nav-link-icon {
			display: inline-flex !important;
			align-items: center;
			justify-content: center;
			width: 2rem;
			height: 2rem;
		}

		.navbar-vertical .nav-item.mt-auto .nav-link .nav-link-icon i {
			color: #ff6b6b !important;
			font-size: 1.5rem !important;
			display: inline-block !important;
			font-weight: bold !important;
		}

		.navbar-vertical .nav-item.mt-auto .nav-link .nav-link-icon .icon-logout-fallback {
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
		.user-dropdown-toggle {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			border-radius: 10px !important;
			padding: 0.75rem 1.25rem !important;
			transition: all 0.3s ease !important;
			border: 2px solid rgba(255, 255, 255, 0.2) !important;
			box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
		}

		.user-dropdown-toggle:hover {
			background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
			transform: translateY(-2px) !important;
			box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6) !important;
		}

		.user-dropdown-toggle .fw-bold {
			color: #ffffff !important;
			font-size: 1rem !important;
			font-weight: 600 !important;
		}

		.user-dropdown-toggle .text-muted {
			color: rgba(255, 255, 255, 0.8) !important;
			font-size: 0.85rem !important;
		}

		.user-dropdown-toggle .ti-chevron-down {
			color: #ffffff !important;
			font-size: 1.2rem !important;
		}

		.user-dropdown-toggle .avatar {
			border: 2px solid #ffffff !important;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
		}

		/* Ultra Modern Dropdown Menu Styling */
		.navbar .dropdown-menu,
		.dropdown-menu {
			background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
			border: 1px solid rgba(255, 255, 255, 0.1);
			border-radius: 16px;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
				0 0 1px rgba(255, 255, 255, 0.1) inset;
			padding: 0.75rem;
			min-width: 280px;
			margin-top: 0.75rem;
			z-index: 1060 !important;
			backdrop-filter: blur(10px);
			animation: dropdownSlideIn 0.3s ease-out;
		}

		@keyframes dropdownSlideIn {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.dropdown-menu.show {
			display: block !important;
			opacity: 1 !important;
			visibility: visible !important;
		}

		.navbar .dropdown-menu .dropdown-item,
		.dropdown-menu .dropdown-item {
			color: rgb(255, 255, 255);
			padding: 0.85rem 1.25rem;
			font-size: 0.95rem;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			border-radius: 10px;
			margin: 0.25rem 0;
			position: relative;
			overflow: hidden;
		}

		.navbar .dropdown-menu .dropdown-item:hover,
		.dropdown-menu .dropdown-item:hover {
			background: linear-gradient(90deg, rgba(96, 165, 250, 0.2) 0%, rgba(139, 92, 246, 0.2) 100%);
			color: #ffffff;
			transform: translateX(5px);
			box-shadow: 0 4px 12px rgba(96, 165, 250, 0.2);
		}

		.navbar .dropdown-menu .dropdown-item i,
		.dropdown-menu .dropdown-item i {
			color: #60a5fa;
			font-size: 1.2em;
			width: 24px;
			text-align: center;
			transition: all 0.3s ease;
		}

		.navbar .dropdown-menu .dropdown-item:hover i,
		.dropdown-menu .dropdown-item:hover i {
			color: #93c5fd;
			transform: scale(1.1);
		}

		.navbar .dropdown-menu::before,
		.dropdown-menu::before {
			content: '';
			position: absolute;
			top: -8px;
			right: 30px;
			width: 16px;
			height: 16px;
			background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
			transform: rotate(45deg);
			border-left: 1px solid rgba(255, 255, 255, 0.1);
			border-top: 1px solid rgba(255, 255, 255, 0.1);
		}

		/* Dropdown item icons specific styling */
		.dropdown-item i.ti-user {
			color: #60a5fa !important;
		}

		.dropdown-item i.ti-logout {
			color: #f87171 !important;
		}

		.dropdown-item i.ti-book {
			color: #34d399 !important;
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
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-home">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M5 12l-2 0l9 -9l9 9l-2 0" />
									<path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
									<path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
								</svg>
							</span>
							<span class="nav-link-title">Utama</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ action('HomeController@prices') }}" class="nav-link {{ request()->is('prices*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
									class="icon icon-tabler icons-tabler-filled icon-tabler-files">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path
										d="M11 2l3 .001v5.999a1 1 0 0 0 .883 .993l.117 .007h6v6a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-7a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3m-3 6h-1a1 1 0 0 0 -1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1 -1v-1h-4a3 3 0 0 1 -3 -3zm12.415 -1h-4.415v-4.415z" />
								</svg>
							</span>
							<span class="nav-link-title">Carta Tender</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ action('HomeController@results') }}"
							class="nav-link {{ request()->is('results*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-device-ipad-check">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v8" />
									<path d="M9 18h2" />
									<path d="M15 19l2 2l4 -4" />
								</svg>
							</span>
							<span class="nav-link-title">Penender Berjaya</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ route('circulars.public') }}" class="nav-link {{ request()->is('circulars*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-circles-relation">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M9.183 6.117a6 6 0 1 0 4.511 3.986" />
									<path d="M14.813 17.883a6 6 0 1 0 -4.496 -3.954" />
								</svg>
							</span>
							<span class="nav-link-title">Pekeliling</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ route('aduan.create') }}" class="nav-link {{ request()->is('aduan*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-mail-spark">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path
										d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" />
									<path d="M11.5 19h-6.5a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5" />
									<path d="M3 7l9 6l9 -6" />
								</svg>
							</span>
							<span class="nav-link-title">Aduan</span>
						</a>
					</div>
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
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

					@if (!empty($user))
						<!-- Logout Button in Sidebar -->
						<div class="nav-item mt-auto">
							<a href="{{ route('logout') }}" class="nav-link text-danger">
								<span class="nav-link-icon">
									<i class="ti ti-logout"></i>
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-logout-fallback" width="24" height="24"
										viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
										stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
										<path d="M9 12h12l-3 -3"></path>
										<path d="M18 15l3 -3"></path>
									</svg>
								</span>
								<span class="nav-link-title">Logout</span>
							</a>
						</div>
					@endif
				</div>
			</div>
		</aside>

		<!-- Main Content -->
		<div class="page-wrapper">
			<!-- Top Navigation -->
			<div class="navbar navbar-expand-md navbar-light d-print-none">
				<div class="container-xl">
					<h1 class="navbar-brand navbar-brand-autodark">
						Welcome {{ data_get($user, 'name') }} !
					</h1>
					<button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
						aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="navbar-nav flex-row order-md-last ms-auto">
						@if (!empty($user))
							<!-- USER DROPDOWN - SUPER VISIBLE -->
							<div class="dropdown">
								<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
									style="background: linear-gradient(135deg, #8e9acf 0%, #8b62b4 100%) !important; color: white !important; font-size: 16px !important; padding: 10px 20px !important; margin-left: 15px !important; border: 2px solid rgba(255,255,255,0.3) !important; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important; display: inline-flex !important; align-items: center !important; gap: 10px !important;">
									<span class="avatar avatar-sm"
										style="background-image: url({{ asset('images/user-avatar.png') }}); width: 28px; height: 28px; border: 2px solid white; flex-shrink: 0;"></span>
									<div style="text-align: left;">
										<div style="font-weight: 600; line-height: 1.2;">{{ $user->name }}</div>
										<div style="font-size: 0.75rem; opacity: 0.9; line-height: 1.2;">{{ $user->email }}</div>
									</div>
									<i class="ti ti-chevron-down" style="margin-left: 5px;"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end">
									<li><a href="{{ asset('profile') }}" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-user">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M12 2a5 5 0 1 1 -5 5l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
												<path d="M14 14a5 5 0 0 1 5 5v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1a5 5 0 0 1 5 -5h4z" />
											</svg>
											<i class="ti ti-user me-2"></i> Profil Saya
										</a></li>
									<li><a href="{{ route('logout') }}" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-logout">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
												<path d="M9 12h12l-3 -3"></path>
												<path d="M18 15l3 -3"></path>
											</svg>
											<i class="ti ti-logout me-2"></i> Daftar Keluar
										</a></li>
									<li><a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-files">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M15 3v4a1 1 0 0 0 1 1h4" />
												<path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
												<path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
											</svg>
											<i class="ti ti-book me-2"></i> Panduan Pengguna
										</a></li>
								</ul>
							</div>
						@else
							<div class="nav-item">
								<a href="{{ route('registration') }}" class="btn btn-outline-primary me-2">Daftar Akaun</a>
								<button type="button" class="btn btn-primary" id="loginButton" onclick="openLoginModal()">
									Daftar Masuk
								</button>
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
		// Debug and fix modal functionality
		document.addEventListener('DOMContentLoaded', function() {
			console.log('DOM loaded, initializing modals...');

			// Check if Bootstrap is available
			if (typeof bootstrap === 'undefined') {
				console.error('Bootstrap is not loaded!');
				return;
			}

			console.log('Bootstrap is available:', bootstrap);

			// Find all modals
			const modals = document.querySelectorAll('.modal');
			console.log('Found modals:', modals.length);

			modals.forEach((modal, index) => {
				console.log(`Initializing modal ${index}:`, modal.id);

				try {
					const modalInstance = new bootstrap.Modal(modal, {
						backdrop: true,
						keyboard: true,
						focus: true
					});

					// Fix aria-hidden attribute when modal is shown
					modal.addEventListener('show.bs.modal', function() {
						console.log('Modal showing:', this.id);
						this.setAttribute('aria-hidden', 'false');
						this.style.pointerEvents = 'auto';
						this.style.display = 'block';
					});

					// Reset aria-hidden when modal is hidden
					modal.addEventListener('hidden.bs.modal', function() {
						console.log('Modal hidden:', this.id);
						this.setAttribute('aria-hidden', 'true');
						this.style.pointerEvents = 'none';
					});

					console.log('Modal instance created:', modalInstance);
				} catch (error) {
					console.error('Error creating modal instance:', error);
				}
			});

			// Find and fix login button
			const loginButton = document.querySelector('[data-bs-target="#loginModal"]');
			console.log('Login button found:', loginButton);

			if (loginButton) {
				// Remove any existing event listeners
				loginButton.replaceWith(loginButton.cloneNode(true));
				const newLoginButton = document.querySelector('[data-bs-target="#loginModal"]');

				newLoginButton.addEventListener('click', function(e) {
					console.log('Login button clicked!');
					const modal = document.getElementById('loginModal');
					console.log('Modal element:', modal);

					let opened = false;
					if (modal && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
						try {
							const modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
							modalInstance.show();
							opened = true;
							console.log('Modal show() called');
						} catch (err) {
							console.error('Bootstrap show failed:', err);
						}
					}

					// If Bootstrap failed, try direct fallback
					if (!opened && modal) {
						try {
							modal.style.display = 'block';
							modal.classList.add('show');
							modal.setAttribute('aria-hidden', 'false');
							modal.setAttribute('aria-modal', 'true');
							document.body.classList.add('modal-open');
							const existing = document.getElementById('modal-backdrop');
							if (!existing) {
								const backdrop = document.createElement('div');
								backdrop.className = 'modal-backdrop fade show';
								backdrop.id = 'modal-backdrop';
								document.body.appendChild(backdrop);
							}
							opened = true;
							console.log('Modal shown via direct manipulation');
						} catch (directError) {
							console.error('Direct manipulation failed:', directError);
						}
					}

					// Only prevent default navigation if we managed to open the modal
					if (opened) {
						e.preventDefault();
						e.stopPropagation();
					} else {
						console.warn('Modal could not be opened, falling back to href');
					}
				});

				console.log('Login button event listener added');
			} else {
				console.error('Login button not found!');
			}
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

			// Initialize all dropdowns manually
			const dropdownTriggers = document.querySelectorAll('[data-bs-toggle="dropdown"]');
			console.log('Found dropdown triggers:', dropdownTriggers.length);

			dropdownTriggers.forEach((trigger, index) => {
				console.log('Initializing dropdown', index);

				if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
					try {
						new bootstrap.Dropdown(trigger);
						console.log('Dropdown initialized successfully:', index);
					} catch (error) {
						console.error('Error initializing dropdown:', error);
					}
				}

				// Add manual click handler as fallback
				trigger.addEventListener('click', function(e) {
					e.preventDefault();
					console.log('Dropdown clicked');

					const menu = this.nextElementSibling;
					if (menu && menu.classList.contains('dropdown-menu')) {
						const isShown = menu.classList.contains('show');

						// Close all other dropdowns
						document.querySelectorAll('.dropdown-menu.show').forEach(m => {
							m.classList.remove('show');
						});

						if (!isShown) {
							menu.classList.add('show');
							console.log('Dropdown opened manually');
						}
					}
				});
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
	</script>

	@yield('scripts')
</body>

</html>
