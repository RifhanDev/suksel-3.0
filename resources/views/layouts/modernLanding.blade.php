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

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

	<!-- Tabler CSS -->
	<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.40.0/tabler-icons.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/modern-actions.css') }}" rel="stylesheet">
	@yield('styles')
	    
	<style>
		:root {
			--primary: #c41e3a;
			--primary-rgb: 196, 30, 58;
			--primary-light: #fef2f2;
			--primary-dark: #8b1428;
			--primary-darker: #5a0d1a;
			--sg-red: #c41e3a;
            --sg-yellow: #ffcc00;
            --sg-black: #1f1f1f;
            --sg-bg: #f3f4f6;

			--topbar-bg: #ffffff;
			--topbar-border: #d1d5db;
			--topbar-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
			--nav-text: #1f2937;
			--nav-text-muted: #6b7280;
			--nav-hover-bg: #f0f1f3;
			--nav-active-bg: #fef2f2;
			--border-color: #e5e7eb;
			--text-primary: #111827;
			--text-secondary: #6b7280;
			--btn-success: #059669;

			/* Typography */
			--font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			--font-display: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

			--sp-xs: 0.375rem;
			--sp-sm: 0.5rem;
			--sp-md: 0.75rem;
			--sp-lg: 1rem;
			--sp-xl: 1.5rem;
			--sp-2xl: 2rem;
		}

		* {
			box-sizing: border-box;
            border-radius: 0 !important;
		}

		.desktop-closed-message-avatar {
			border-radius: 50% !important;
			object-fit: cover !important;
			border: 2px solid white !important;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
		}

		html, body {
			margin: 0;
			padding: 0;
		}

		body {
            font-family: var(--font-sans);
            background-color: var(--sg-bg);
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
            color: var(--sg-black);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

		.page {
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		/* --- NAVBAR STRUCTURE --- */
		.navbar-top {
			background: var(--topbar-bg);
			border-bottom: 1px solid var(--topbar-border);
			box-shadow: var(--topbar-shadow);
			padding: 0; 
			height: 84px;
			position: sticky;
			top: 0;
			z-index: 1000;
			transition: all 0.3s ease;
		}

		.navbar-top .container-fluid {
			display: flex;
			justify-content: space-between;
			align-items: center;
			height: 100%;
			width: 100%;
			padding-left: 0; 
			padding-right: 1.5rem; 
		}

		.navbar-brand-logo {
			display: flex;
			align-items: center;
			text-decoration: none;
			margin: 0;
			flex-shrink: 0;
			gap: 0.875rem;
			transition: transform 0.2s ease;
			background: linear-gradient(135deg, var(--sg-red) 0%, #a01830 100%);
			height: 100%;
			clip-path: polygon(0 0, 100% 0, 92% 100%, 0% 100%);
			padding-left: 1.25rem;
			padding-right: 3rem;
			margin-left: 0;
		}

		.navbar-brand-logo:hover {
			text-decoration: none;
			filter: brightness(1.05);
		}

		.logo-bg-box {
			background: white;
			width: 75px;
			height: 75px;
			display: flex;
			align-items: center;
			justify-content: center;
			border: 2px solid var(--sg-yellow);
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}

		.navbar-brand-logo img {
			max-width: 85px;
			height: auto;
			display: block;
		}

		.navbar-brand-text {
			font-family: var(--font-display);
			font-weight: 800;
			font-size: 1.3rem;
			color: #ffffff;
			margin: 0;
			line-height: 1;
			letter-spacing: -0.3px;
			text-transform: uppercase;
			text-decoration: none;
			text-shadow: 0 1px 2px rgba(0,0,0,0.2);
		}

		.navbar-brand-text-sub {
			display: block;
			font-size: 0.65rem;
			font-weight: 700;
			color: var(--sg-yellow);
			margin-top: 0.25rem;
			letter-spacing: 0.5px;
		}

		/* --- MENU ITEMS --- */
		.navbar-nav-horizontal {
			display: flex;
			align-items: center;
			gap: 0.15rem;
			margin: 0;
			padding: 0;
			list-style: none;
			flex: 1;
			justify-content: center;
			height: 100%;
		}

		.navbar-nav-horizontal .nav-link {
			display: inline-flex;
			align-items: center;
			gap: 0.4rem;
			padding: 0.75rem 0.25rem;
			margin: 0 0.25rem;
			color: #4b5563;
			text-decoration: none;
			font-weight: 600;
			font-size: 0.75rem;
			position: relative;
			transition: color 0.2s ease, background 0.2s ease;
			white-space: nowrap;
		}

		.nav-link-icon {
			margin-bottom: 3px;
		}

		/* Hover State */
		.navbar-nav-horizontal .nav-link:hover {
			color: var(--primary);
			background: transparent;
		}

		/* Active State */
		.navbar-nav-horizontal .nav-link.active {
			color: var(--primary);
			font-weight: 700;
		}

		/* Standard Underline (::after) */
		.navbar-nav-horizontal .nav-link::after {
			content: '';
			position: absolute;
			bottom: 0px;
			left: 50%;
			transform: translateX(-50%);
			width: 0;
			height: 3px;
			background: var(--primary);
			transition: all 0.3s ease;
			opacity: 1 !important;
		}

		.navbar-nav-horizontal .nav-link:hover::after,
		.navbar-nav-horizontal .nav-link.active::after {
			width: 85%;
			opacity: 1;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link::after {
			display: none !important;
		}

		/* Underline for Dropdown (::before) */
		.navbar-nav-horizontal .nav-item.dropdown .nav-link::before {
			content: '';
			position: absolute;
			bottom: 0px;
			left: 50%;
			transform: translateX(-50%);
			width: 0;
			height: 3px;
			background: var(--sg-red);
			transition: all 0.3s ease;
			opacity: 0;
		}

		/* Show on Hover */
		.navbar-nav-horizontal .nav-item.dropdown .nav-link:hover::before {
			width: 85%;
			opacity: 1;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link.show::before,
		.navbar-nav-horizontal .nav-item.dropdown .nav-link.active.show::before {
			width: 0 !important;
			opacity: 0 !important;
			transition: none;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link.show,
		.navbar-nav-horizontal .nav-item.dropdown .nav-link.active {
			color: var(--sg-red);
			font-weight: 600;
		}

		/* Fix dropdown spacing - prevent shift when opening */
		.navbar-nav-horizontal .nav-item.dropdown .nav-link {
			display: inline-flex !important;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link .nav-link-icon {
			flex-shrink: 0;
			width: 24px;
			display: inline-flex;
			justify-content: center;
		}

		/* Arrow Styling */
		.navbar-nav-horizontal .nav-item.dropdown .dropdown-toggle::after {
			display: inline-block !important;
			content: "" !important;
			width: 6px; 
			height: 6px;
			position: static !important;
			background: transparent !important;
			border-right: 2px solid #9ca3af; 
			border-bottom: 2px solid #9ca3af;
			border-top: 0;
			border-left: 0;
			margin-left: 8px;
			vertical-align: 2px;
			transform: rotate(45deg); 
			transition: transform 0.2s ease;
			opacity: 1 !important;
		}

		.navbar-nav-horizontal .nav-item.dropdown .dropdown-toggle.show::after {
			transform: rotate(225deg) !important;
			border-color: var(--sg-red) !important;
		}

		.navbar-nav-horizontal .dropdown-menu {
			position: absolute !important;
			top: 100% !important;
			left: 0 !important;
			margin-top: 18px !important; /* Snap to link bottom */
			padding: 0.5rem 0;
			border: 1px solid #e5e7eb;
			border-top: 3px solid var(--sg-red) !important; 
			box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
			background: white;
			display: none;
			z-index: 1050;
			min-width: 220px;
		}

		.dropdown-menu::before, 
		.dropdown-menu::after {
			display: none !important;
			content: none !important;
		}

		@keyframes simpleSlide {
			from { opacity: 0; transform: translateY(10px); }
			to   { opacity: 1; transform: translateY(0); }
		}

		.navbar-nav-horizontal .dropdown-menu.show {
			display: block;
			animation: simpleSlide 0.4s ease-out forwards;
		}

		.dropdown-item {
			padding: 0.7rem 1.2rem !important;
			font-size: 0.8rem !important;
			font-weight: 500;
			color: var(--nav-text) !important;
			margin: 0 !important;
		}

		.dropdown-item:hover {
			background-color: var(--nav-hover-bg) !important;
			color: var(--sg-red) !important;
			padding-left: 1.5rem !important;
		}

		.dropdown-item i {
			color: var(--sg-red);
			width: 20px;
			text-align: center;
			margin-right: 8px;
			transition: transform 0.2s;
		}
		
		.dropdown-item:hover i { transform: scale(1.1); }

		/* --- ACTIONS --- */
		.navbar-actions {
			display: flex;
			align-items: center;
			gap: 1rem;
			margin-left: auto;
		}

		/* Utility Links */
		.action-links {
			display: flex;
			flex-direction: column; 
			align-items: flex-end;
			justify-content: center;
			gap: 2px;
			text-align: right;
			padding-right: 1rem;
			border-right: 1px solid var(--border-color); 
			height: 38px;
		}

		.action-link-item {
			font-size: 0.6rem;
			font-weight: 500;
			color: var(--text-secondary);
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: 4px;
			transition: color 0.2s ease;
		}

		.action-link-item:hover { color: var(--primary); text-decoration: underline; }
		.action-link-item i { font-size: 0.8rem; }

		/* Buttons */
		.action-buttons {
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.btn-auth {
			padding: 0.5rem 1rem;
			font-size: 0.8rem;
			font-weight: 600;
			display: inline-flex;
			align-items: center;
			gap: 0.4rem;
			transition: all 0.2s ease;
			text-decoration: none;
			height: 38px;
		}

		.btn-auth-outline {
			border: 1px solid #d1d5db;
			background: white;
			color: var(--text-primary);
		}
		.btn-auth-outline:hover {
			border-color: var(--primary);
			color: var(--primary);
			background: #fff1f2;
		}

		.btn-auth-solid {
			background: var(--primary);
			color: white;
			border: 1px solid var(--primary);
			box-shadow: 0 2px 4px rgba(196, 30, 58, 0.15);
		}
		.btn-auth-solid:hover {
			background: var(--primary-dark);
			transform: translateY(-1px);
			box-shadow: 0 4px 8px rgba(196, 30, 58, 0.25);
			color: white;
		}

		/* --- USER PROFILE --- */
		.user-nav-item {
			position: relative;
			list-style: none;
		}

		.user-chip-toggle {
			display: inline-flex !important;
			flex-direction: row !important; 
			align-items: center;
			flex-wrap: nowrap !important;
			white-space: nowrap;
			gap: 0.75rem;
			padding: 0.25rem 0.5rem 0.25rem 0.25rem;
			transition: all 0.2s ease;
			cursor: pointer;
			text-decoration: none;
			color: var(--nav-text);
			border: 1px solid transparent;
			background: transparent;
		}

		a.user-chip-toggle:hover,
		a.user-chip-toggle:focus,
		a.user-chip-toggle.show {
			text-decoration: none !important;
		}

		.user-chip-toggle:hover, 
		.user-chip-toggle.show {
			background-color: #f3f4f6;
			border-color: #e5e7eb;
			text-decoration: none;
			color: var(--nav-text);
			text-decoration: none;
		}

		.user-chip-toggle .avatar {
			width: 34px;
			height: 34px;
			object-fit: cover;
			border: 2px solid white;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			flex-shrink: 0; /* Prevent avatar from shrinking */
		}

		.user-info {
			display: flex;
			flex-direction: column;
			line-height: 1.1;
			margin-right: 0.25rem;
			text-align: left;
		}

		.user-name {
			font-size: 0.8rem;
			font-weight: 700;
			color: var(--sg-black);
		}

		.user-role {
			font-size: 0.6rem;
			color: #6b7280;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		/* User Toggle Arrow */
		.user-chip-toggle::after {
			display: inline-block !important;
			content: "" !important;
			width: 6px; 
			height: 6px;
			border-right: 2px solid #9ca3af; 
			border-bottom: 2px solid #9ca3af;
			border-top: 0;
			border-left: 0;
			margin-left: 4px;
			margin-right: 8px;
			vertical-align: 2px;
			transform: rotate(45deg); 
			transition: transform 0.2s ease;
			opacity: 1 !important;
			flex-shrink: 0; /* Stop arrow from causing wrap issues */
		}

		.user-chip-toggle.show::after {
			transform: rotate(225deg);
			border-color: var(--sg-red);
		}

		/* --- USER DROPDOWN MENU --- */
		.user-dropdown-menu {
			position: absolute !important;
			top: 100% !important;
			right: 0 !important;
			left: auto !important;
			margin-top: 12px !important;
			width: 260px;
			padding: 0;
			border: 1px solid #e5e7eb;
			border-top: 3px solid var(--sg-red) !important;
			box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
			background: white;
			display: none;
			z-index: 1050;
		}

		.user-dropdown-menu.show {
			display: block;
			animation: simpleSlide 0.4s ease-out forwards;
		}

		/* User Header Dropdown */
		.dropdown-user-header {
			padding: 1.25rem;
			background-color: #f9fafb;
			border-bottom: 1px solid #e5e7eb;
			text-align: left;
			text-decoration: none;
		}

		.dropdown-user-header h6 {
			margin: 0;
			font-weight: 700;
			color: var(--sg-black);
			font-size: 0.9rem;
		}

		.dropdown-user-header span {
			display: block;
			font-size: 0.75rem;
			color: #6b7280;
			margin-top: 2px;
		}

		/* User Menu Items */
		.user-dropdown-menu .dropdown-item {
			padding: 0.75rem 1.25rem;
			font-size: 0.8rem;
			color: var(--nav-text);
			border-left: 3px solid transparent;
			transition: all 0.2s;
		}

		.user-dropdown-menu .dropdown-item:hover {
			background-color: #fef2f2 !important;
			color: var(--sg-red) !important;
			border-left-color: var(--sg-red);
			padding-left: 1.5rem;
		}

		.user-dropdown-menu .dropdown-item i {
			margin-right: 10px;
			font-size: 1.1rem;
			color: #9ca3af;
			transition: color 0.2s;
		}

		.user-dropdown-menu .dropdown-item:hover i { color: var(--sg-red); }

		/* Logout */
		.item-logout { color: #dc2626 !important; }
		.item-logout:hover {
			background-color: #fef2f2 !important;
			color: #b91c1c !important;
		}
		.item-logout i { color: #dc2626 !important; }

		.dropdown-divider { margin: 0; border-color: var(--border-color); }

		/* --- HAMBURGER MENU --- */
		.hamburger-btn {
			display: none;
			flex-direction: column;
			justify-content: space-around;
			width: 32px;
			height: 32px;
			background: transparent;
			border: none;
			cursor: pointer;
			padding: 4px;
			z-index: 1060;
			transition: all 0.3s ease;
		}

		.hamburger-btn:hover {
			background: #f3f4f6;
		}

		.hamburger-btn span {
			width: 100%;
			height: 3px;
			background: var(--sg-red);
			transition: all 0.3s ease;
			transform-origin: center;
		}

		.hamburger-btn.active span:nth-child(1) {
			transform: translateY(8px) rotate(45deg);
		}

		.hamburger-btn.active span:nth-child(2) {
			opacity: 0;
		}

		.hamburger-btn.active span:nth-child(3) {
			transform: translateY(-8px) rotate(-45deg);
		}

		/* Mobile Sidebar Menu */
		.mobile-sidebar {
			position: fixed;
			top: 84px;
			left: -100%;
			width: 320px;
			height: calc(100vh - 84px);
			background: white;
			box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
			z-index: 1050;
			transition: left 0.3s ease;
			overflow-y: auto;
			padding: 1.5rem 0;
		}

		.mobile-sidebar.active {
			left: 0;
		}

		.mobile-overlay {
			position: fixed;
			top: 84px;
			left: 0;
			width: 100%;
			height: calc(100vh - 84px);
			background: rgba(0, 0, 0, 0.5);
			z-index: 1040;
			display: none;
			transition: opacity 0.3s ease;
		}

		.mobile-overlay.active {
			display: block;
		}

		.mobile-nav-item {
			border-bottom: 1px solid #f3f4f6;
		}

		.mobile-nav-link {
			display: flex;
			align-items: center;
			gap: 1rem;
			padding: 1rem 1.5rem;
			color: var(--nav-text);
			text-decoration: none;
			font-weight: 600;
			font-size: 0.9rem;
			transition: all 0.2s ease;
		}

		.mobile-nav-link:hover,
		.mobile-nav-link.active {
			background: #fef2f2;
			color: var(--sg-red);
			border-left: 4px solid var(--sg-red);
			padding-left: 1.375rem;
		}

		.mobile-nav-link svg {
			width: 22px;
			height: 22px;
			flex-shrink: 0;
		}

		.mobile-dropdown-toggle {
			display: flex;
			align-items: center;
			justify-content: space-between;
			width: 100%;
			padding: 1rem 1.5rem;
			color: var(--nav-text);
			text-decoration: none;
			font-weight: 600;
			font-size: 0.9rem;
			background: transparent;
			border: none;
			text-align: left;
			cursor: pointer;
			transition: all 0.2s ease;
		}

		.mobile-dropdown-toggle:hover {
			background: #fef2f2;
			color: var(--sg-red);
		}

		.mobile-dropdown-toggle .mobile-nav-icon {
			display: flex;
			align-items: center;
			gap: 1rem;
		}

		.mobile-dropdown-toggle .dropdown-arrow {
			transition: transform 0.3s ease;
		}

		.mobile-dropdown-toggle.active .dropdown-arrow {
			transform: rotate(180deg);
		}

		.mobile-dropdown-content {
			max-height: 0;
			overflow: hidden;
			background: #f9fafb;
			transition: max-height 0.3s ease;
		}

		.mobile-dropdown-content.active {
			max-height: 500px;
		}

		.mobile-dropdown-item {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			padding: 0.875rem 1.5rem 0.875rem 3.5rem;
			color: #4b5563;
			text-decoration: none;
			font-size: 0.85rem;
			font-weight: 500;
			transition: all 0.2s ease;
		}

		.mobile-dropdown-item:hover {
			background: #fff;
			color: var(--sg-red);
			padding-left: 3.75rem;
		}

		.mobile-dropdown-item svg {
			width: 18px;
			height: 18px;
		}

		.page-wrapper {
			flex: 1;
			padding: var(--sp-2xl) var(--sp-xl);
		}

		.page-wrapper .container-fluid {
			padding-left: 1.5rem;
			padding-right: 1.5rem;
		}

		/* Responsive Design */
		/* Large Screens */
		@media (min-width: 1600px) {
			.navbar-top .container-fluid {
				padding-left: 0;
				padding-right: 3rem;
				max-width: 1920px;
				margin: 0 auto;
			}

			.page-wrapper .container-fluid {
				max-width: 1920px;
				margin: 0 auto;
				padding-left: 1.5rem;
				padding-right: 3rem;
			}
		}

		/* Medium-Large Desktop */
		@media (min-width: 1201px) and (max-width: 1599px) {
			.navbar-top .container-fluid {
				padding-left: 0;
				padding-right: 1.5rem;
			}
		}

		/* Show Hamburger Menu below 1500px */
		@media (max-width: 1499px) {
			.navbar-nav-horizontal {
				display: none !important;
			}

			.hamburger-btn {
				display: flex;
			}

			.navbar-top .container-fluid {
				padding-left: 0;
				padding-right: 1.5rem;
			}
		}

		/* 2. Laptops */
		@media (max-width: 1200px) {
			.action-links { display: none; }
			.navbar-actions { gap: 0.5rem; }
		}

		/* 3. Tablets */
		@media (max-width: 992px) {
			
			.navbar-brand-logo {
				clip-path: none;
				background: transparent;
				padding-right: 0;
				margin-left: 0; 
			}
			
			.navbar-brand-text { color: var(--primary-darker); }
			.navbar-brand-text-sub { color: var(--primary); }
			.logo-bg-box { border-color: transparent; }
			
			.navbar-top .container-fluid { gap: var(--sp-lg); }
			.navbar-actions .btn { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
		}

		/* 4. Mobile */
		@media (max-width: 768px) {
			.navbar-top {
				padding: 0.5rem 0; /* Vertical padding on mobile wrapper */
				height: auto;
			}

			/* Padding on mobile */
			.navbar-top .container-fluid {
				padding-left: 1rem;
				padding-right: 1rem;
			}

			.navbar-brand-text { font-size: 1.1rem; }
			.navbar-brand-text-sub { display: none; }
			.navbar-brand-logo img { max-width: 44px; }

			.navbar-actions { gap: 0.375rem; }
			.navbar-actions .btn { padding: 0.5rem; font-size: 0.7rem; gap: 0; }
			.navbar-actions .btn span { display: none; }

			.page-wrapper { padding: var(--sp-lg); }

			/* Adjust mobile sidebar for auto height navbar */
			.mobile-sidebar {
				top: 0;
				height: 100vh;
				padding-top: calc(var(--navbar-height, 84px));
			}

			.mobile-overlay {
				top: 0;
				height: 100vh;
			}
		}
	</style>
</head>

<body>
	<div class="page">
		<nav class="navbar-top">
			<div class="container-fluid">
				<a href="/" class="navbar-brand-logo" title="Sistem Tender Online Selangor">
					<div class="logo-bg-box">
						<img src="{{ asset('images/02_selangor.png') }}" alt="Selangor">
					</div>
					<div>
						<h1 class="navbar-brand-text">STOS 3.0</h1>
						<span class="navbar-brand-text-sub">Sistem Tender Online Selangor</span>
					</div>
				</a>

				<ul class="navbar-nav-horizontal">
					<li class="nav-item">
						<a href="{{ action('HomeController@index') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}" title="Halaman Utama">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" /></svg>
							</span>
							<span>Utama</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ action('HomeController@prices') }}" class="nav-link {{ request()->is('prices*') ? 'active' : '' }}" title="Carta Tender">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-files"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 2l3 .001v5.999a1 1 0 0 0 .883 .993l.117 .007h6v6a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-7a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3m-3 6h-1a1 1 0 0 0 -1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1 -1v-1h-4a3 3 0 0 1 -3 -3zm12.415 -1h-4.415v-4.415z" /></svg>
							</span>
							<span>Carta Tender</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ action('HomeController@results') }}" class="nav-link {{ request()->is('results*') ? 'active' : '' }}" title="Penender Berjaya">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trophy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2.17a3 3 0 1 1 0 5.659v.171a6.002 6.002 0 0 1 -5 5.917v2.083h3a1 1 0 0 1 .117 1.993l-.117 .007h-8a1 1 0 0 1 -.117 -1.993l.117 -.007h3v-2.083a6.002 6.002 0 0 1 -4.996 -5.692l-.004 -.225v-.171a3 3 0 0 1 -3.996 -2.653l-.003 -.176l.005 -.176a3 3 0 0 1 3.995 -2.654l-.001 -2.17a1 1 0 0 1 1 -1h10zm-12 5a1 1 0 1 0 0 2a1 1 0 0 0 0 -2zm14 0a1 1 0 1 0 0 2a1 1 0 0 0 0 -2z" /></svg>
							</span>
							<span>Penender Berjaya</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('circulars.public') }}" class="nav-link {{ request()->is('circulars*') ? 'active' : '' }}" title="Pekeliling">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-clipboard-list"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.987 10.83h-.01a1 1 0 0 0 -.117 1.993l.127 .007a1 1 0 0 0 0 -2m5.99 0h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m-5.99 -4h-.01a1 1 0 0 0 -.117 1.993l.127 .007a1 1 0 0 0 0 -2m5.99 0h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m-1 -9a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" /></svg>
							</span>
							<span>Pekeliling</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('aduan.create') }}" class="nav-link {{ request()->is('aduan*') ? 'active' : '' }}" title="Pekeliling">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-message-report"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-6 10a1 1 0 0 0 -1 1v.01a1 1 0 0 0 2 0v-.01a1 1 0 0 0 -1 -1m0 -6a1 1 0 0 0 -1 1v3a1 1 0 0 0 2 0v-3a1 1 0 0 0 -1 -1" /></svg>
							</span>
							<span>Aduan</span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a href="#navbar-help" 
						class="nav-link dropdown-toggle {{ request()->is('manuals*') ? 'active' : '' }}" 
						id="navbarDropdownPertanyaan"
						role="button" 
						data-bs-toggle="dropdown" 
						data-bs-auto-close="outside" 
						data-bs-display="static" 
						aria-expanded="false" 
						title="Pertanyaan">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-zoom-question"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3.072a8 8 0 0 1 2.32 11.834l5.387 5.387a1 1 0 0 1 -1.414 1.414l-5.388 -5.387a8 8 0 0 1 -12.905 -6.32l.005 -.285a8 8 0 0 1 11.995 -6.643m-4 8.928a1 1 0 0 0 -.993 .883l-.007 .127a1 1 0 0 0 1.993 .117l.007 -.127a1 1 0 0 0 -1 -1m-1.9 -5.123a1 1 0 0 0 1.433 1.389l.088 -.09a.5 .5 0 1 1 .379 .824a1 1 0 0 0 -.002 2a2.5 2.5 0 1 0 -1.9 -4.123" /></svg>
							</span>
							<span>Pertanyaan</span>
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownPertanyaan">
							<a class="dropdown-item" href="{{ action('HelpsController@index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-lifebuoy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14.757 16.172l3.571 3.571a10.004 10.004 0 0 1 -12.656 0l3.57 -3.571a5 5 0 0 0 2.758 .828c1.02 0 1.967 -.305 2.757 -.828m-10.5 -10.5l3.571 3.57a5 5 0 0 0 -.828 2.758c0 1.02 .305 1.967 .828 2.757l-3.57 3.572a10 10 0 0 1 -2.258 -6.329l.005 -.324a10 10 0 0 1 2.252 -6.005m17.743 6.329c0 2.343 -.82 4.57 -2.257 6.328l-3.571 -3.57a5 5 0 0 0 .828 -2.758c0 -1.02 -.305 -1.967 -.828 -2.757l3.571 -3.57a10 10 0 0 1 2.257 6.327m-5 -8.66q .707 .41 1.33 .918l-3.573 3.57a5 5 0 0 0 -2.757 -.828c-1.02 0 -1.967 .305 -2.757 .828l-3.573 -3.57a10 10 0 0 1 11.33 -.918" /></svg>
								Bantuan
							</a>
							<a class="dropdown-item" href="{{ route('manuals.show', 'pendaftaran') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-alert-square"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 2a3 3 0 0 1 2.995 2.824l.005 .176v14a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h14zm-6.99 13l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" /></svg>
								Panduan Pengguna
							</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a href="#navbar-agencies"
						class="nav-link dropdown-toggle {{ request()->is('agencies*') ? 'active' : '' }}" 
						id="navbarDropdownAgensi"
						role="button" 
						data-bs-toggle="dropdown" 
						data-bs-auto-close="outside" 
						data-bs-display="static" 
						aria-expanded="false" 
						title="Agensi">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-directions"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 22a1 1 0 0 1 0 -2h1v-2.001l-5 .001a1 1 0 0 1 -.707 -.293l-2 -2a1 1 0 0 1 0 -1.414l2 -2a1 1 0 0 1 .707 -.293l5 -.001v-1.999h-3a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h3v-1a1 1 0 0 1 2 0v1h6a1 1 0 0 1 .707 .293l2 2a1 1 0 0 1 0 1.414l-2 2a1 1 0 0 1 -.707 .293h-6v1.999l1 .001a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1l-1 -.001v2.001h1a1 1 0 0 1 0 2z" /></svg>
							</span>
							<span>Direktori Agensi</span>
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownAgensi">
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
					</li>
				</ul>

				<!-- HAMBURGER MENU BUTTON -->
				<button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle navigation">
					<span></span>
					<span></span>
					<span></span>
				</button>

				<!-- ACTIONS -->
				<div class="navbar-actions">
					@if (!Auth::check())
						<!-- Group 1: Utilities (Help) -->
						<div class="action-links d-none d-xl-flex">
							<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="action-link-item" title="Panduan pendaftaran vendor">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-help-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
								Cara Mendaftar
							</a>
							{{-- <a href="#" class="action-link-item" title="Set semula kata laluan">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock-question"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 21h-8a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2h10c.265 0 .518 .052 .75 .145" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /><path d="M19 22v.01" /><path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
								Lupa Kata Laluan?
							</a> --}}
						</div>

						<!-- Group 2: Primary Actions -->
						<div class="action-buttons">
							<a href="{{ asset('register') }}" class="btn-auth btn-auth-outline" title="Daftar akaun vendor baharu">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -3.85" /></svg>
								<span class="d-none d-sm-inline">Daftar</span>
							</a>
							<a href="{{ route('login') }}" class="btn-auth btn-auth-solid text-decoration-none" title="Log masuk ke sistem">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-login-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M3 12h13l-3 -3" /><path d="M13 15l3 -3" /></svg>
								<span class="d-none d-sm-inline">Masuk</span>
							</a>
						</div>
					@else
						<!-- USER PROFILE MENU -->
						<div class="nav-item dropdown user-nav-item">
							<a href="#" 
							   class="user-chip-toggle dropdown-toggle" 
							   data-bs-toggle="dropdown" 
							   data-bs-auto-close="outside"
							   data-bs-display="static"
							   aria-expanded="false">
								
								<img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=c41e3a&color=fff" alt="{{ Auth::user()->name }}" class="avatar">
								
								<div class="user-info d-none d-xl-flex">
									<span class="user-name mt-2">{{ Str::limit(Auth::user()->name, 15) }}</span>
									<span class="user-role">Pengguna</span>
								</div>
							</a>

							<div class="dropdown-menu user-dropdown-menu dropdown-menu-end">
								<!-- Header Section -->
								<div class="dropdown-user-header">
									<h6>{{ Auth::user()->name }}</h6>
									<span>{{ Auth::user()->email }}</span>
								</div>

								<!-- Menu Items -->
								<a class="dropdown-item" href="#">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2a5 5 0 1 1 -5 5l.005 -.217a5 5 0 0 1 4.995 -4.783z" /><path d="M14 14a5 5 0 0 1 5 5v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1a5 5 0 0 1 5 -5h4z" /></svg>
									Profil Saya
								</a>
								<a class="dropdown-item" href="#">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-lock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2a5 5 0 0 1 5 5v3a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3v-3a5 5 0 0 1 5 -5m0 12a2 2 0 0 0 -1.995 1.85l-.005 .15a2 2 0 1 0 2 -2m0 -10a3 3 0 0 0 -3 3v3h6v-3a3 3 0 0 0 -3 -3" /></svg>
									Tukar Kata Laluan
								</a>
								
								<div class="dropdown-divider"></div>
								
								<!-- Logout -->
								<a class="dropdown-item item-logout" href="{{ route('logout') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-dual-screen"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 3a1 1 0 0 1 1 1v15a1 1 0 0 1 -1 1h-5v2a1 1 0 0 1 -1.351 .936l-8 -3a1 1 0 0 1 -.649 -.936v-15a1 1 0 0 1 .212 -.616l.068 -.079l.078 -.072l.066 -.05l.092 -.058l.065 -.033l.1 -.04l.099 -.028l.046 -.01l.108 -.013l.066 -.001zm-5.649 3.064a1 1 0 0 1 .649 .936v11h4v-13h-7.486z" /></svg>
								   	Log Keluar
								</a>
							</div>
						</div>
					@endif
				</div>
			</div>
		</nav>

		<!-- MOBILE SIDEBAR MENU -->
		<div class="mobile-overlay" id="mobileOverlay"></div>
		<div class="mobile-sidebar" id="mobileSidebar">
			<nav>
				<!-- Utama -->
				<div class="mobile-nav-item">
					<a href="{{ action('HomeController@index') }}" class="mobile-nav-link {{ request()->is('/') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" /></svg>
						<span>Utama</span>
					</a>
				</div>

				<!-- Carta Tender -->
				<div class="mobile-nav-item">
					<a href="{{ action('HomeController@prices') }}" class="mobile-nav-link {{ request()->is('prices*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-files"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 2l3 .001v5.999a1 1 0 0 0 .883 .993l.117 .007h6v6a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-7a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3m-3 6h-1a1 1 0 0 0 -1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1 -1v-1h-4a3 3 0 0 1 -3 -3zm12.415 -1h-4.415v-4.415z" /></svg>
						<span>Carta Tender</span>
					</a>
				</div>

				<!-- Penender Berjaya -->
				<div class="mobile-nav-item">
					<a href="{{ action('HomeController@results') }}" class="mobile-nav-link {{ request()->is('results*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trophy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2.17a3 3 0 1 1 0 5.659v.171a6.002 6.002 0 0 1 -5 5.917v2.083h3a1 1 0 0 1 .117 1.993l-.117 .007h-8a1 1 0 0 1 -.117 -1.993l.117 -.007h3v-2.083a6.002 6.002 0 0 1 -4.996 -5.692l-.004 -.225v-.171a3 3 0 0 1 -3.996 -2.653l-.003 -.176l.005 -.176a3 3 0 0 1 3.995 -2.654l-.001 -2.17a1 1 0 0 1 1 -1h10zm-12 5a1 1 0 1 0 0 2a1 1 0 0 0 0 -2zm14 0a1 1 0 1 0 0 2a1 1 0 0 0 0 -2z" /></svg>
						<span>Penender Berjaya</span>
					</a>
				</div>

				<!-- Pekeliling -->
				<div class="mobile-nav-item">
					<a href="{{ route('circulars.public') }}" class="mobile-nav-link {{ request()->is('circulars*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-clipboard-list"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.987 10.83h-.01a1 1 0 0 0 -.117 1.993l.127 .007a1 1 0 0 0 0 -2m5.99 0h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m-5.99 -4h-.01a1 1 0 0 0 -.117 1.993l.127 .007a1 1 0 0 0 0 -2m5.99 0h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m-1 -9a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" /></svg>
						<span>Pekeliling</span>
					</a>
				</div>

				<!-- Aduan -->
				<div class="mobile-nav-item">
					<a href="{{ route('aduan.create') }}" class="mobile-nav-link {{ request()->is('aduan*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-message-report"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-6 10a1 1 0 0 0 -1 1v.01a1 1 0 0 0 2 0v-.01a1 1 0 0 0 -1 -1m0 -6a1 1 0 0 0 -1 1v3a1 1 0 0 0 2 0v-3a1 1 0 0 0 -1 -1" /></svg>
						<span>Aduan</span>
					</a>
				</div>

				<!-- Pertanyaan Dropdown -->
				<div class="mobile-nav-item">
					<button class="mobile-dropdown-toggle" data-dropdown="pertanyaan">
						<div class="mobile-nav-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-zoom-question"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3.072a8 8 0 0 1 2.32 11.834l5.387 5.387a1 1 0 0 1 -1.414 1.414l-5.388 -5.387a8 8 0 0 1 -12.905 -6.32l.005 -.285a8 8 0 0 1 11.995 -6.643m-4 8.928a1 1 0 0 0 -.993 .883l-.007 .127a1 1 0 0 0 1.993 .117l.007 -.127a1 1 0 0 0 -1 -1m-1.9 -5.123a1 1 0 0 0 1.433 1.389l.088 -.09a.5 .5 0 1 1 .379 .824a1 1 0 0 0 -.002 2a2.5 2.5 0 1 0 -1.9 -4.123" /></svg>
							<span>Pertanyaan</span>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dropdown-arrow"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
					</button>
					<div class="mobile-dropdown-content" id="dropdown-pertanyaan">
						<a href="{{ action('HelpsController@index') }}" class="mobile-dropdown-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-lifebuoy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14.757 16.172l3.571 3.571a10.004 10.004 0 0 1 -12.656 0l3.57 -3.571a5 5 0 0 0 2.758 .828c1.02 0 1.967 -.305 2.757 -.828m-10.5 -10.5l3.571 3.57a5 5 0 0 0 -.828 2.758c0 1.02 .305 1.967 .828 2.757l-3.57 3.572a10 10 0 0 1 -2.258 -6.329l.005 -.324a10 10 0 0 1 2.252 -6.005m17.743 6.329c0 2.343 -.82 4.57 -2.257 6.328l-3.571 -3.57a5 5 0 0 0 .828 -2.758c0 -1.02 -.305 -1.967 -.828 -2.757l3.571 -3.57a10 10 0 0 1 2.257 6.327m-5 -8.66q .707 .41 1.33 .918l-3.573 3.57a5 5 0 0 0 -2.757 -.828c-1.02 0 -1.967 .305 -2.757 .828l-3.573 -3.57a10 10 0 0 1 11.33 -.918" /></svg>
							Bantuan
						</a>
						<a href="{{ route('manuals.show', 'pendaftaran') }}" class="mobile-dropdown-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-alert-square"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 2a3 3 0 0 1 2.995 2.824l.005 .176v14a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h14zm-6.99 13l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" /></svg>
							Panduan Pengguna
						</a>
					</div>
				</div>

				<!-- Direktori Agensi Dropdown -->
				<div class="mobile-nav-item">
					<button class="mobile-dropdown-toggle" data-dropdown="agensi">
						<div class="mobile-nav-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-directions"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 22a1 1 0 0 1 0 -2h1v-2.001l-5 .001a1 1 0 0 1 -.707 -.293l-2 -2a1 1 0 0 1 0 -1.414l2 -2a1 1 0 0 1 .707 -.293l5 -.001v-1.999h-3a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h3v-1a1 1 0 0 1 2 0v1h6a1 1 0 0 1 .707 .293l2 2a1 1 0 0 1 0 1.414l-2 2a1 1 0 0 1 -.707 .293h-6v1.999l1 .001a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1l-1 -.001v2.001h1a1 1 0 0 1 0 2z" /></svg>
							<span>Direktori Agensi</span>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dropdown-arrow"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
					</button>
					<div class="mobile-dropdown-content" id="dropdown-agensi">
						@php
							try {
								$__orgTypes = App\OrganizationType::orderBy('sort_no', 'asc')->get();
							} catch (\Throwable $e) {
								$__orgTypes = collect();
							}
						@endphp
						@foreach ($__orgTypes as $type)
							<a href="{{ action('OrganizationUnitsController@index', ['type' => $type->id]) }}" class="mobile-dropdown-item">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-building"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 2a3 3 0 0 1 2.995 2.824l.005 .176v16a1 1 0 0 1 -.883 .993l-.117 .007h-5a1 1 0 0 1 -.993 -.883l-.007 -.117v-3h-2v3a1 1 0 0 1 -.883 .993l-.117 .007h-5a1 1 0 0 1 -.993 -.883l-.007 -.117v-16a3 3 0 0 1 2.824 -2.995l.176 -.005h10zm-6 6h-1a1 1 0 1 0 0 2h1a1 1 0 0 0 0 -2zm4 0h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0 -2z" /></svg>
								{{ $type->name }}
							</a>
						@endforeach
					</div>
				</div>
			</nav>
		</div>

		<!-- Page Content -->
		<div class="page-wrapper">
			<div class="container-fluid">
				@yield('content')
			</div>
		</div>
	</div>

	@include('layouts._footer')


	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Tabler JS -->
	{{-- kena check balik script tabler ni --}}
	{{-- <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script> --}}

	{{-- <script src="{{ asset('js/application.js') }}"></script> --}}

	<!-- Hamburger Menu Script -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const hamburgerBtn = document.getElementById('hamburgerBtn');
			const mobileSidebar = document.getElementById('mobileSidebar');
			const mobileOverlay = document.getElementById('mobileOverlay');
			const dropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');

			// Toggle mobile menu
			function toggleMobileMenu() {
				hamburgerBtn.classList.toggle('active');
				mobileSidebar.classList.toggle('active');
				mobileOverlay.classList.toggle('active');

				// Prevent body scroll when menu is open
				if (mobileSidebar.classList.contains('active')) {
					document.body.style.overflow = 'hidden';
				} else {
					document.body.style.overflow = '';
				}
			}

			// Close mobile menu
			function closeMobileMenu() {
				hamburgerBtn.classList.remove('active');
				mobileSidebar.classList.remove('active');
				mobileOverlay.classList.remove('active');
				document.body.style.overflow = '';
			}

			// Hamburger button click
			if (hamburgerBtn) {
				hamburgerBtn.addEventListener('click', toggleMobileMenu);
			}

			// Overlay click
			if (mobileOverlay) {
				mobileOverlay.addEventListener('click', closeMobileMenu);
			}

			// Dropdown toggles in mobile menu
			dropdownToggles.forEach(toggle => {
				toggle.addEventListener('click', function() {
					const dropdownId = this.getAttribute('data-dropdown');
					const dropdownContent = document.getElementById('dropdown-' + dropdownId);

					// Toggle active class
					this.classList.toggle('active');
					dropdownContent.classList.toggle('active');
				});
			});

			// Close menu when clicking on a link (not dropdown)
			const mobileNavLinks = document.querySelectorAll('.mobile-nav-link, .mobile-dropdown-item');
			mobileNavLinks.forEach(link => {
				link.addEventListener('click', function() {
					closeMobileMenu();
				});
			});

			// Close menu on window resize if screen becomes large
			window.addEventListener('resize', function() {
				if (window.innerWidth >= 1500) {
					closeMobileMenu();
				}
			});
		});
	</script>

	@yield('scripts')
</body>

</html>