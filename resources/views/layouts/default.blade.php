<!DOCTYPE html>
<html>

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
		ULTRA MODERN DESIGN SYSTEM 2024
		======================================== */
		:root {
			/* Modern Color Palette - Sophisticated & Contemporary */
			--primary-50: #fef2f2;
			--primary-100: #fee2e2;
			--primary-200: #fecaca;
			--primary-300: #fca5a5;
			--primary-400: #f87171;
			--primary-500: #ef4444;
			--primary-600: #dc2626;
			--primary-700: #b91c1c;
			--primary-800: #991b1b;
			--primary-900: #7f1d1d;
			--primary-950: #450a0a;

			--accent-50: #fffbeb;
			--accent-100: #fef3c7;
			--accent-200: #fde68a;
			--accent-300: #fcd34d;
			--accent-400: #fbbf24;
			--accent-500: #f59e0b;
			--accent-600: #d97706;
			--accent-700: #b45309;
			--accent-800: #92400e;
			--accent-900: #78350f;
			--accent-950: #451a03;

			--neutral-0: #ffffff;
			--neutral-50: #fafafa;
			--neutral-100: #f5f5f5;
			--neutral-200: #e5e5e5;
			--neutral-300: #d4d4d4;
			--neutral-400: #a3a3a3;
			--neutral-500: #737373;
			--neutral-600: #525252;
			--neutral-700: #404040;
			--neutral-800: #262626;
			--neutral-900: #171717;
			--neutral-950: #0a0a0a;

			/* Modern Typography System */
			--font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
			--font-display: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;

			/* Typography Scale */
			--text-xs: 0.75rem;
			--text-sm: 0.875rem;
			--text-base: 1rem;
			--text-lg: 1.125rem;
			--text-xl: 1.25rem;
			--text-2xl: 1.5rem;
			--text-3xl: 1.875rem;
			--text-4xl: 2.25rem;
			--text-5xl: 3rem;
			--text-6xl: 3.75rem;

			/* Spacing Scale */
			--space-0: 0;
			--space-1: 0.25rem;
			--space-2: 0.5rem;
			--space-3: 0.75rem;
			--space-4: 1rem;
			--space-5: 1.25rem;
			--space-6: 1.5rem;
			--space-8: 2rem;
			--space-10: 2.5rem;
			--space-12: 3rem;
			--space-16: 4rem;
			--space-20: 5rem;
			--space-24: 6rem;
			--space-32: 8rem;

			/* Modern Shadow System */
			--shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
			--shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
			--shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
			--shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
			--shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
			--shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
			--shadow-inner: inset 0 2px 4px 0 rgb(0 0 0 / 0.05);

			/* Modern Border Radius */
			--radius-none: 0;
			--radius-sm: 0.125rem;
			--radius-md: 0.375rem;
			--radius-lg: 0.5rem;
			--radius-xl: 0.75rem;
			--radius-2xl: 1rem;
			--radius-3xl: 1.5rem;
			--radius-full: 9999px;

			/* Modern Transitions */
			--transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
			--transition-normal: 300ms cubic-bezier(0.4, 0, 0.2, 1);
			--transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);

			/* Modern Z-Index Scale */
			--z-dropdown: 1000;
			--z-sticky: 1020;
			--z-fixed: 1030;
			--z-modal-backdrop: 1040;
			--z-modal: 1050;
			--z-popover: 1060;
			--z-tooltip: 1070;
		}

		/* ========================================
		ULTRA MODERN GLOBAL STYLES 2024
		======================================== */
		*,
		*::before,
		*::after {
			box-sizing: border-box;
		}

		html {
			scroll-behavior: smooth;
		}

		body {
			font-family: var(--font-sans);
			font-size: var(--text-base);
			line-height: 1.6;
			color: var(--neutral-800);
			background: linear-gradient(135deg, var(--neutral-50) 0%, var(--neutral-100) 50%, var(--neutral-0) 100%);
			min-height: 100vh;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
		}

		/* ========================================
		ULTRA MODERN NAVBAR 2024
		======================================== */
		.navbar {
			background: rgba(255, 255, 255, 0.95) !important;
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			border-bottom: 1px solid var(--neutral-200);
			box-shadow: var(--shadow-sm);
			padding: var(--space-4) 0;
			position: sticky;
			top: 0;
			z-index: var(--z-sticky);
		}

		.navbar-brand {
			font-family: var(--font-display);
			font-weight: 700;
			font-size: var(--text-xl);
			color: var(--primary-600) !important;
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: var(--space-3);
		}

		.navbar-brand:hover {
			color: var(--primary-700) !important;
			transform: translateY(-1px);
			transition: all var(--transition-fast);
		}

		.navbar-brand img {
			height: 40px;
			width: auto;
			transition: all var(--transition-fast);
		}

		.navbar-brand:hover img {
			transform: scale(1.05);
		}

		.navbar-nav .nav-link {
			color: var(--neutral-700) !important;
			font-weight: 500;
			font-size: var(--text-sm);
			padding: var(--space-2) var(--space-4) !important;
			border-radius: var(--radius-lg);
			transition: all var(--transition-fast);
			position: relative;
		}

		.navbar-nav .nav-link:hover {
			background: var(--primary-50);
			color: var(--primary-600) !important;
			transform: translateY(-1px);
		}

		.navbar-nav .nav-link.active {
			background: var(--primary-600);
			color: var(--neutral-0) !important;
			font-weight: 600;
			box-shadow: var(--shadow-sm);
		}

		/* Modern Navbar Toggle */
		.navbar-toggler {
			border: 1px solid var(--neutral-300);
			border-radius: var(--radius-lg);
			padding: var(--space-2);
			transition: all var(--transition-fast);
			background: var(--neutral-0);
		}

		.navbar-toggler:hover {
			background: var(--neutral-50);
			border-color: var(--primary-300);
			transform: scale(1.05);
		}

		.navbar-toggler:focus {
			box-shadow: 0 0 0 3px var(--primary-100) !important;
			border-color: var(--primary-400);
		}

		/* ========================================
		ULTRA MODERN SIDEBAR 2024
		======================================== */
		.left-sidebar {
			background: var(--neutral-0) !important;
			border-right: 1px solid var(--neutral-200);
			box-shadow: var(--shadow-sm);
			position: sticky;
			top: 80px;
			height: calc(100vh - 80px);
			overflow-y: auto;
		}

		.left-sidebar .nav-link {
			color: var(--neutral-600) !important;
			padding: var(--space-3) var(--space-4) !important;
			margin: var(--space-1) var(--space-2);
			border-radius: var(--radius-lg);
			font-weight: 500;
			font-size: var(--text-sm);
			transition: all var(--transition-fast);
			position: relative;
			display: flex;
			align-items: center;
			gap: var(--space-3);
		}

		.left-sidebar .nav-link:hover {
			background: var(--primary-50);
			color: var(--primary-600) !important;
			transform: translateX(4px);
			box-shadow: var(--shadow-sm);
		}

		.left-sidebar .nav-link.active {
			background: var(--primary-600);
			color: var(--neutral-0) !important;
			font-weight: 600;
			box-shadow: var(--shadow-md);
		}

		.left-sidebar .nav-link::before {
			content: '';
			position: absolute;
			left: 0;
			top: 50%;
			transform: translateY(-50%);
			height: 20px;
			width: 3px;
			background: var(--primary-600);
			border-radius: var(--radius-full);
			opacity: 0;
			transition: all var(--transition-fast);
		}

		.left-sidebar .nav-link:hover::before,
		.left-sidebar .nav-link.active::before {
			opacity: 1;
		}

		/* ========================================
		ULTRA MODERN BUTTONS 2024
		======================================== */
		.btn {
			font-family: var(--font-sans);
			font-weight: 500;
			font-size: var(--text-sm);
			line-height: 1.5;
			border-radius: var(--radius-lg);
			padding: var(--space-2) var(--space-4);
			transition: all var(--transition-fast);
			border: 1px solid transparent;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: var(--space-2);
			text-decoration: none;
		}

		.btn-primary {
			background: var(--primary-600);
			color: var(--neutral-0);
			border-color: var(--primary-600);
			box-shadow: var(--shadow-sm);
		}

		.btn-primary:hover {
			background: var(--primary-700);
			border-color: var(--primary-700);
			transform: translateY(-1px);
			box-shadow: var(--shadow-md);
		}

		.btn-primary:active {
			transform: translateY(0);
			box-shadow: var(--shadow-sm);
		}

		.btn-secondary {
			background: var(--neutral-100);
			color: var(--neutral-700);
			border-color: var(--neutral-300);
		}

		.btn-secondary:hover {
			background: var(--neutral-200);
			border-color: var(--neutral-400);
			transform: translateY(-1px);
			box-shadow: var(--shadow-sm);
		}

		.btn-outline-primary {
			background: transparent;
			color: var(--primary-600);
			border-color: var(--primary-300);
		}

		.btn-outline-primary:hover {
			background: var(--primary-50);
			border-color: var(--primary-400);
			transform: translateY(-1px);
		}

		.btn-lg {
			padding: var(--space-3) var(--space-6);
			font-size: var(--text-base);
		}

		.btn-sm {
			padding: var(--space-1) var(--space-3);
			font-size: var(--text-xs);
		}

		/* ========================================
		ULTRA MODERN CARDS 2024
		======================================== */
		.card {
			background: var(--neutral-0);
			border: 1px solid var(--neutral-200);
			border-radius: var(--radius-2xl);
			box-shadow: var(--shadow-sm);
			overflow: hidden;
			transition: all var(--transition-normal);
		}

		.card:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
			border-color: var(--neutral-300);
		}

		.card-header {
			background: var(--neutral-50);
			border-bottom: 1px solid var(--neutral-200);
			padding: var(--space-6);
			font-weight: 600;
			font-size: var(--text-lg);
			color: var(--neutral-800);
		}

		.card-body {
			padding: var(--space-6);
		}

		.card-footer {
			background: var(--neutral-50);
			border-top: 1px solid var(--neutral-200);
			padding: var(--space-4) var(--space-6);
		}

		/* ========================================
		ULTRA MODERN FORMS 2024
		======================================== */
		.form-control {
			background: var(--neutral-0);
			border: 1px solid var(--neutral-300);
			border-radius: var(--radius-lg);
			padding: var(--space-3) var(--space-4);
			font-size: var(--text-sm);
			line-height: 1.5;
			transition: all var(--transition-fast);
			color: var(--neutral-800);
		}

		.form-control:focus {
			border-color: var(--primary-400);
			box-shadow: 0 0 0 3px var(--primary-100);
			outline: none;
		}

		.form-control::placeholder {
			color: var(--neutral-400);
		}

		.form-label {
			font-weight: 500;
			font-size: var(--text-sm);
			color: var(--neutral-700);
			margin-bottom: var(--space-2);
			display: block;
		}

		.form-group {
			margin-bottom: var(--space-4);
		}

		.invalid-feedback {
			color: var(--primary-600);
			font-size: var(--text-xs);
			margin-top: var(--space-1);
		}

		.is-invalid {
			border-color: var(--primary-500);
		}

		/* ========================================
		ULTRA MODERN MODALS 2024
		======================================== */
		.modal-content {
			background: var(--neutral-0);
			border: 1px solid var(--neutral-200);
			border-radius: var(--radius-3xl);
			box-shadow: var(--shadow-2xl);
			overflow: hidden;
		}

		.modal-header {
			background: var(--neutral-50);
			border-bottom: 1px solid var(--neutral-200);
			padding: var(--space-6);
		}

		.modal-title {
			font-family: var(--font-display);
			font-weight: 600;
			font-size: var(--text-xl);
			color: var(--neutral-800);
		}

		.modal-body {
			padding: var(--space-6);
		}

		.modal-footer {
			background: var(--neutral-50);
			border-top: 1px solid var(--neutral-200);
			padding: var(--space-4) var(--space-6);
		}

		.btn-close {
			background: none;
			border: none;
			font-size: var(--text-lg);
			color: var(--neutral-400);
			transition: color var(--transition-fast);
		}

		.btn-close:hover {
			color: var(--neutral-600);
		}

		/* ========================================
		ULTRA MODERN TABLES 2024
		======================================== */
		.table {
			background: var(--neutral-0);
			border-radius: var(--radius-2xl);
			overflow: hidden;
			box-shadow: var(--shadow-sm);
			border: 1px solid var(--neutral-200);
		}

		.table thead th {
			background: var(--neutral-50);
			color: var(--neutral-700);
			border: none;
			font-weight: 600;
			font-size: var(--text-sm);
			padding: var(--space-4);
			text-align: left;
		}

		.table tbody tr {
			transition: all var(--transition-fast);
			border-bottom: 1px solid var(--neutral-100);
		}

		.table tbody tr:hover {
			background: var(--neutral-50);
		}

		.table tbody tr:last-child {
			border-bottom: none;
		}

		.table tbody td {
			padding: var(--space-4);
			font-size: var(--text-sm);
			color: var(--neutral-700);
		}

		/* ========================================
		MODERN UTILITY CLASSES 2024
		======================================== */
		.text-primary {
			color: var(--primary-600) !important;
		}

		.text-secondary {
			color: var(--neutral-600) !important;
		}

		.text-muted {
			color: var(--neutral-400) !important;
		}

		.text-success {
			color: #059669 !important;
		}

		.text-warning {
			color: var(--accent-600) !important;
		}

		.text-danger {
			color: var(--primary-600) !important;
		}

		.bg-primary {
			background-color: var(--primary-600) !important;
		}

		.bg-secondary {
			background-color: var(--neutral-100) !important;
		}

		.bg-light {
			background-color: var(--neutral-50) !important;
		}

		.border {
			border: 1px solid var(--neutral-200) !important;
		}

		.border-primary {
			border-color: var(--primary-300) !important;
		}

		.border-light {
			border-color: var(--neutral-200) !important;
		}

		.rounded {
			border-radius: var(--radius-lg) !important;
		}

		.rounded-lg {
			border-radius: var(--radius-xl) !important;
		}

		.rounded-xl {
			border-radius: var(--radius-2xl) !important;
		}

		.shadow {
			box-shadow: var(--shadow-sm) !important;
		}

		.shadow-lg {
			box-shadow: var(--shadow-lg) !important;
		}

		.shadow-xl {
			box-shadow: var(--shadow-xl) !important;
		}

		/* ========================================
		MODERN ANIMATIONS 2024
		======================================== */
		@keyframes fadeIn {
			from {
				opacity: 0;
			}

			to {
				opacity: 1;
			}
		}

		@keyframes slideInUp {
			from {
				opacity: 0;
				transform: translateY(20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes slideInRight {
			from {
				opacity: 0;
				transform: translateX(-20px);
			}

			to {
				opacity: 1;
				transform: translateX(0);
			}
		}

		.animate-fade-in {
			animation: fadeIn 0.5s ease-out;
		}

		.animate-slide-up {
			animation: slideInUp 0.5s ease-out;
		}

		.animate-slide-right {
			animation: slideInRight 0.5s ease-out;
		}

		/* ========================================
		DATATABLE FIXES 2024
		======================================== */
		.dataTables_wrapper {
			overflow-x: auto;
		}

		.dataTables_length,
		.dataTables_filter {
			margin-bottom: var(--space-4);
		}

		.dataTables_length {
			float: left;
			margin-right: var(--space-4);
		}

		.dataTables_filter {
			float: right;
			text-align: right;
		}

		.dataTables_length label,
		.dataTables_filter label {
			display: flex;
			align-items: center;
			gap: var(--space-2);
			margin: 0;
			font-weight: 500;
			color: var(--neutral-700);
		}

		.dataTables_length select,
		.dataTables_filter input {
			border: 1px solid var(--neutral-300);
			border-radius: var(--radius-lg);
			padding: var(--space-2) var(--space-3);
			font-size: var(--text-sm);
			background: var(--neutral-0);
			transition: all var(--transition-fast);
		}

		.dataTables_length select:focus,
		.dataTables_filter input:focus {
			border-color: var(--primary-400);
			box-shadow: 0 0 0 3px var(--primary-100);
			outline: none;
		}

		.dataTables_info {
			clear: both;
			padding-top: var(--space-4);
			font-size: var(--text-sm);
			color: var(--neutral-600);
		}

		.dataTables_paginate {
			text-align: right;
			margin-top: var(--space-4);
		}

		.dataTables_paginate .paginate_button {
			display: inline-block;
			padding: var(--space-2) var(--space-3);
			margin: 0 var(--space-1);
			border: 1px solid var(--neutral-300);
			border-radius: var(--radius-lg);
			background: var(--neutral-0);
			color: var(--neutral-700);
			text-decoration: none;
			transition: all var(--transition-fast);
		}

		.dataTables_paginate .paginate_button:hover {
			background: var(--primary-50);
			border-color: var(--primary-300);
			color: var(--primary-600);
		}

		.dataTables_paginate .paginate_button.current {
			background: var(--primary-600);
			border-color: var(--primary-600);
			color: var(--neutral-0);
		}

		.dataTables_paginate .paginate_button.disabled {
			opacity: 0.5;
			cursor: not-allowed;
		}

		/* Fix for overlapping DataTable controls */
		.dataTables_wrapper::after {
			content: "";
			display: table;
			clear: both;
		}

		/* ========================================
		TABLE HEADER OVERLAP FIXES 2024
		======================================== */
		.table thead th {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			min-width: 80px;
			padding: var(--space-3) var(--space-2);
			position: relative;
		}

		.table thead th:first-child {
			min-width: 60px;
		}

		.table thead th:nth-child(2) {
			min-width: 120px;
		}

		/* Specific fixes for vendor table headers */
		.table thead th:nth-child(n+6) {
			min-width: 100px;
			text-align: center;
		}

		/* Target specific columns that commonly overlap */
		.table thead th:nth-last-child(-n+4) {
			min-width: 100px;
			text-align: center;
		}

		/* Ensure table is responsive */
		.table {
			table-layout: auto;
			width: 100%;
		}

		/* Fix for specific table columns that might overlap */
		.table th[colspan] {
			text-align: center;
		}

		/* Ensure proper spacing in table headers */
		.bg-blue-selangor th {
			padding: var(--space-3) var(--space-2) !important;
			font-size: var(--text-sm);
			font-weight: 600;
			line-height: 1.4;
		}

		/* Specific fix for overlapping table headers */
		.table.table-bordered thead th {
			position: relative;
			z-index: 1;
			background: var(--primary-600) !important;
			color: var(--neutral-0) !important;
			border: 1px solid var(--primary-700) !important;
		}

		/* Ensure table cells don't overlap */
		.table td,
		.table th {
			vertical-align: middle;
			word-wrap: break-word;
			word-break: break-word;
		}

		/* Fix for table with many columns */
		.table-responsive {
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}

		/* Ensure minimum widths for critical columns */
		.table thead th:nth-child(1) {
			min-width: 50px;
		}

		/* Bil. */
		.table thead th:nth-child(2) {
			min-width: 150px;
		}

		/* Nama Syarikat */
		.table thead th:nth-child(3) {
			min-width: 100px;
		}

		/* Beli Dokumen */
		.table thead th:nth-child(4) {
			min-width: 100px;
		}

		/* Taklimat */
		.table thead th:nth-child(5) {
			min-width: 80px;
		}

		/* LT */
		.table thead th:nth-child(6) {
			min-width: 100px;
		}

		/* Label */
		.table thead th:nth-child(7) {
			min-width: 100px;
		}

		/* Harga */
		.table thead th:nth-child(8) {
			min-width: 100px;
		}

		/* Berjaya */
		.table thead th:nth-child(9) {
			min-width: 120px;
		}

		/* Gred/Prestasi */
		.table thead th:nth-child(10) {
			min-width: 80px;
		}

		/* Padam */

		/* Force table to respect column widths */
		.table {
			table-layout: fixed;
			width: 100%;
		}

		/* Override any conflicting styles */
		.table.table-bordered {
			border-collapse: separate !important;
			border-spacing: 0 !important;
		}

		.table.table-bordered th,
		.table.table-bordered td {
			border: 1px solid var(--neutral-300) !important;
			padding: var(--space-2) !important;
		}

		/* Ensure headers don't overlap */
		.table thead th {
			position: relative;
			z-index: 2;
			background: var(--primary-600) !important;
			color: var(--neutral-0) !important;
			text-align: center;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		/* ========================================
		TABLER CUSTOMIZATIONS 2024
		======================================== */
		/* Selangor Brand Colors for Tabler */
		:root {
			--tblr-primary: #dc2626;
			--tblr-primary-rgb: 220, 38, 38;
			--tblr-primary-fg: #ffffff;
			--tblr-primary-fg-rgb: 255, 255, 255;
		}

		/* Custom page header styling */
		.page-header {
			margin-bottom: 2rem;
		}

		.page-pretitle {
			font-size: 0.875rem;
			font-weight: 500;
			color: var(--tblr-text-muted);
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		.page-title {
			font-size: 1.75rem;
			font-weight: 700;
			color: var(--tblr-text-default);
			margin: 0;
		}

		/* Enhanced card styling */
		.card {
			border: 1px solid var(--tblr-border-color);
			box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
		}

		.card-header {
			background: var(--tblr-bg-surface);
			border-bottom: 1px solid var(--tblr-border-color);
		}

		/* Professional tab styling */
		.nav-tabs .nav-link {
			border: none;
			border-radius: 0.375rem;
			margin-right: 0.25rem;
			font-weight: 500;
			transition: all 0.15s ease-in-out;
		}

		.nav-tabs .nav-link:hover {
			background: var(--tblr-bg-surface-secondary);
			color: var(--tblr-primary);
		}

		.nav-tabs .nav-link.active {
			background: var(--tblr-primary);
			color: var(--tblr-primary-fg);
			border-color: var(--tblr-primary);
		}

		/* Professional table styling */
		.table th {
			font-weight: 600;
			color: var(--tblr-text-default);
			border-bottom: 2px solid var(--tblr-border-color);
		}

		.table td {
			border-bottom: 1px solid var(--tblr-border-color);
		}

		/* DataTable enhancements */
		.dataTables_wrapper .dataTables_length,
		.dataTables_wrapper .dataTables_filter {
			margin-bottom: 1rem;
		}

		.dataTables_wrapper .dataTables_length select,
		.dataTables_wrapper .dataTables_filter input {
			border: 1px solid var(--tblr-border-color);
			border-radius: 0.375rem;
			padding: 0.375rem 0.75rem;
		}

		/* ========================================
		RESPONSIVE DESIGN 2024
		======================================== */
		@media (max-width: 768px) {
			:root {
				--space-4: 0.75rem;
				--space-6: 1rem;
				--space-8: 1.5rem;
			}

			.card-body {
				padding: var(--space-4);
			}

			.modal-body {
				padding: var(--space-4);
			}

			.dataTables_length,
			.dataTables_filter {
				float: none;
				text-align: left;
				margin-bottom: var(--space-3);
			}

			.dataTables_length label,
			.dataTables_filter label {
				flex-direction: column;
				align-items: flex-start;
				gap: var(--space-1);
			}

			.dataTables_paginate {
				text-align: center;
			}

			/* Mobile tab adjustments */
			.modern-tabs {
				flex-direction: column;
				gap: var(--space-1);
			}

			.modern-tab {
				width: 100%;
				justify-content: flex-start;
				padding: var(--space-4);
			}

			.page-title {
				font-size: var(--text-xl);
				margin-bottom: var(--space-3);
			}
		}

		/* ========================================
		LEGACY STYLES (MAINTAINED FOR COMPATIBILITY)
		======================================== */
		i.fa.fa-caret-down {
			float: right;
		}

		#botmanWidgetRoot>div {
			bottom: 20px !important;
			overflow: visible !important;
		}

		.desktop-closed-message-avatar {
			border-radius: 50% !important;
			height: 80px !important;
			width: 80px !important;
		}

		.desktop-closed-message-avatar img {
			border-radius: 999px !important;
		}

		/* Ensure modal inputs are focusable */
		#loginModal .modal-dialog {
			pointer-events: auto !important;
			z-index: 10001 !important;
		}

		#loginModal .modal-content {
			pointer-events: auto !important;
		}

		#loginModal input,
		#loginModal button {
			pointer-events: auto !important;
			z-index: 10002 !important;
		}

		#loginModal button[type="submit"] {
			pointer-events: auto !important;
			z-index: 10003 !important;
			cursor: pointer !important;
			position: relative !important;
			background: linear-gradient(135deg, var(--selangor-red) 0%, var(--selangor-red-dark) 100%) !important;
			border: none !important;
		}

		/* Hamburger menu fixes */
		.navbar-toggler {
			z-index: 1000 !important;
			position: relative !important;
			cursor: pointer !important;
		}

		.navbar-toggler:focus {
			box-shadow: 0 0 0 0.25rem rgba(251, 191, 36, 0.25) !important;
		}

		#navbar-menu {
			z-index: 999 !important;
		}

		/* Ensure navbar collapse works on mobile */
		@media (max-width: 767.98px) {
			.navbar-collapse {
				position: absolute !important;
				top: 100% !important;
				left: 0 !important;
				right: 0 !important;
				background: var(--selangor-white) !important;
				border-top: 1px solid var(--selangor-gray-200) !important;
				box-shadow: var(--shadow-lg) !important;
				border-radius: 0 0 var(--radius-xl) var(--radius-xl);
			}
		}

		/* ========================================
		RESPONSIVE DESIGN
		======================================== */
		@media (max-width: 768px) {
			:root {
				--space-md: 0.75rem;
				--space-lg: 1rem;
				--space-xl: 1.5rem;
			}
		}

		/* ========================================
		ANIMATIONS
		======================================== */
		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.fade-in-up {
			animation: fadeInUp 0.6s ease-out;
		}

		/* ========================================
		GLASSMORPHISM EFFECTS
		======================================== */
		.glass {
			background: rgba(255, 255, 255, 0.1);
			backdrop-filter: blur(20px);
			border: 1px solid rgba(255, 255, 255, 0.2);
		}
	</style>

	<script>
		(function() {
			var STORAGE_KEY = 'suksel.sidebar.collapsed';
			var toggle = document.getElementById('sidebarToggle');
			if (!toggle) return;

			function setCollapsed(collapsed) {
				if (collapsed) {
					document.body.classList.add('left-collapsed');
					toggle.setAttribute('aria-pressed', 'true');
				} else {
					document.body.classList.remove('left-collapsed');
					toggle.setAttribute('aria-pressed', 'false');
				}
				localStorage.setItem(STORAGE_KEY, collapsed);
			}

			function isCollapsed() {
				return localStorage.getItem(STORAGE_KEY) === 'true';
			}

			function init() {
				var collapsed = isCollapsed();
				setCollapsed(collapsed);
				toggle.addEventListener('click', function() {
					setCollapsed(!isCollapsed());
				});
			}

			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', init);
			} else {
				init();
			}
		})();
	</script>
</head>

<body class="layout-boxed">
	<div class="page">
		@include('layouts._navbar')
		@include('layouts._side')

		<div class="page-wrapper">
			@if (isset($pageTitle))
				<div class="page-header d-print-none">
					<div class="container-xl">
						<div class="row g-2 align-items-center">
							<div class="col">
								<h2 class="page-title">
									{{ $pageTitle }}
									@if (isset($pageSubtitle))
										<small class="text-muted">{{ $pageSubtitle }}</small>
									@endif
								</h2>
							</div>
						</div>
					</div>
				</div>
			@endif

			<div class="page-body">
				<div class="container-xl">
					@include('layouts._notification')
					@yield('content')
				</div>
			</div>
		</div>
	</div>

	@include('layouts._footer')
	@include('layouts._popupModal')

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Bootstrap 5 JS (load first to ensure it's available) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Tabler JS -->
	<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
	<script src="{{ asset('js/application.js') }}"></script>
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
			frameEndpoint: "{{ route('chat_widget', ['chat_id' => $chat_id]) }}",
			userId: "{{ $chat_id }}"
		};

		window.addEventListener("message", (event) => {
			if (event.data != "") {
				let data = event.data;

				if (data.status == 200) {
					let messages = data.messages;

					messages.forEach(row => {
						if (row.text == "DataACK") {
							sender_response_detail = row.additionalParameters;

							if (sender_response_detail.sender == "user_chat") {
								if (sender_response_detail.type == "image_only") {
									botmanChatWidget.say('<img src="' + sender_response_detail.response +
										'" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only") {
									botmanChatWidget.say(sender_response_detail.response);
								}
							}

							if (sender_response_detail.sender == "bot") {
								if (sender_response_detail.type == "image_only") {
									botmanChatWidget.sayAsBot('<img src="' + sender_response_detail.response +
										'" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only") {
									botmanChatWidget.sayAsBot(sender_response_detail.response);
								}
							}
						}
					});
				}
			}
		});
	</script>
	<script src='{{ asset('packages/botman/build/js/widget.js') }}'></script>
	<script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-Token': $('meta[name=_token]').attr('content')
			}
		});
	</script>

	@yield('scripts')

	<!-- Login Modal JavaScript -->
	<script>
		// Simple, direct approach - force show modal
		$(document).ready(function() {
			// Check if modal exists
			if ($('#loginModal').length === 0) {
				return;
			}

			// Simple click handler that forces modal to show
			$('[data-bs-target="#loginModal"]').on('click', function(e) {
				e.preventDefault();

				// Get modal element
				var modal = $('#loginModal');

				// Remove any conflicting attributes
				modal.removeAttr('aria-hidden');
				modal.removeClass('fade');

				// Show the modal directly
				modal.css({
					'display': 'block',
					'z-index': '9999',
					'position': 'fixed',
					'top': '0',
					'left': '0',
					'width': '100%',
					'height': '100%',
					'background-color': 'rgba(0,0,0,0.5)',
					'pointer-events': 'auto'
				});

				// Make sure modal dialog is clickable
				modal.find('.modal-dialog').css({
					'pointer-events': 'auto',
					'position': 'relative',
					'z-index': '10000'
				});

				// Add backdrop
				if ($('.modal-backdrop').length === 0) {
					$('body').append('<div class="modal-backdrop fade show" style="z-index: 9998;"></div>');
				}

				// Focus on email input
				setTimeout(function() {
					var emailInput = modal.find('input[name="email"]');
					emailInput.focus();
				}, 200);
			});

			// Close modal when clicking backdrop or close button
			$('#loginModal').on('click', function(e) {
				if (e.target === this) {
					closeModal();
				}
			});

			// Prevent modal content clicks from closing modal
			$('#loginModal .modal-dialog').on('click', function(e) {
				e.stopPropagation();
			});

			$('#loginModal .btn-close, #loginModal [data-bs-dismiss="modal"]').on('click', function(e) {
				e.preventDefault();
				closeModal();
			});

			// Handle form submission
			$('#loginModal form').on('submit', function(e) {
				// Let the form submit normally - don't prevent default
			});

			// Make sure submit button is clickable
			$('#loginModal button[type="submit"]').on('click', function(e) {
				// Add visual feedback
				$(this).css('background', '#28a745').text('Submitting...');
				// Let the form submit normally
			});

			// Close modal function
			function closeModal() {
				$('#loginModal').css('display', 'none');
				$('.modal-backdrop').remove();
			}

			// Show modal if there are login errors
			@if ($errors->has('email') || $errors->has('password') || $errors->has('login'))
				$('[data-bs-target="#loginModal"]').click();
			@endif
		});
	</script>

	<!-- Hamburger Menu Fix -->
	<script>
		$(document).ready(function() {
			// Ensure Bootstrap collapse is properly initialized
			if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
				// Initialize all collapse elements
				var collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
				var collapseList = collapseElementList.map(function(collapseEl) {
					return new bootstrap.Collapse(collapseEl, {
						toggle: false
					});
				});

				// Add click handler for hamburger menu as backup
				$('.navbar-toggler').on('click', function(e) {
					e.preventDefault();
					var target = $(this).attr('data-bs-target');
					if (target) {
						$(target).collapse('toggle');
					}
				});

				console.log('Hamburger menu initialized successfully');
			} else {
				console.error('Bootstrap not available for hamburger menu');
			}
		});
	</script>

</body>

</html>
