<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="_token" content="{{ csrf_token() }}">
	<title>Sistem Perolehan Selangor</title>

	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
	<link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

	{{-- <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet"> --}}
	{{-- <link href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.40.0/tabler-icons.min.css" rel="stylesheet"> --}}
	{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

	<!-- Modern CSS (includes Bootstrap 5) -->
	<link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.5/datatables.min.css" rel="stylesheet" integrity="sha384-Lv8lYSJkh1Hc5kB9lk2YbbGdchMRCuAcwUOYWZ3Q/YIfKNVW+6W+V57wxKNv1D8l" crossorigin="anonymous">
	{{-- <link href="{{ asset('css/modern-actions.css') }}" rel="stylesheet"> --}}

	@yield('styles')

	<style>
		/* ============================================
		   CSS CUSTOM PROPERTIES (Selangor Theme)
		   ============================================ */
		:root {
			/* Brand Colors */
			--sg-red: #c41e3a;
			--sg-red-dark: #a01830;
			--sg-red-darker: #8b1428;
			--sg-yellow: #ffcc00;
			--sg-black: #1f1f1f;
			--sg-bg: #f3f4f6;

			/* UI Colors */
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

			/* Typography */
			--font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			--font-display: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

			/* Spacing */
			--sp-xs: 0.375rem;
			--sp-sm: 0.5rem;
			--sp-md: 0.75rem;
			--sp-lg: 1rem;
			--sp-xl: 1.5rem;
			--sp-2xl: 2rem;

			/* Border Radius - Standardized */
			--radius-sm: 4px;
			--radius-md: 6px;
			--radius-lg: 8px;

			/* Navbar Height */
			--navbar-height: 84px;
		}

		/* ============================================
		   BASE STYLES
		   ============================================ */
		* {
			box-sizing: border-box;
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
		}

		/* Page Layout */
		.page {
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		.page-wrapper {
			flex: 1;
			padding: var(--sp-2xl) var(--sp-xl);
		}

		.page-wrapper .container-fluid {
			padding-left: 1.5rem;
			padding-right: 1.5rem;
		}

		/* Avatar fix for chat widgets */
		.desktop-closed-message-avatar {
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid white;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		/* ============================================
		   NAVBAR STRUCTURE
		   ============================================ */
		.navbar-top {
			background: var(--topbar-bg);
			border-bottom: 1px solid var(--topbar-border);
			box-shadow: var(--topbar-shadow);
			padding: 0;
			height: var(--navbar-height);
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

		/* Brand Logo */
		.navbar-brand-logo {
			display: flex;
			align-items: center;
			text-decoration: none;
			gap: 0.875rem;
			transition: filter 0.2s ease;
			background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
			height: 100%;
			clip-path: polygon(0 0, 100% 0, 92% 100%, 0% 100%);
			padding-left: 1.25rem;
			padding-right: 3rem;
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
			animation: brandSlideIn 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    		will-change: transform, opacity; /* Optimizes performance */
		}

		.navbar-brand-logo img {
			max-width: 90px;
			height: 75px;
			display: block;
		}

		.brand-text-stack {
			display: flex;
			flex-direction: column;
			justify-content: center;
			line-height: 1.3;
			margin-top: 2px;
		}

		.brand-line-top {
			font-family: var(--font-display);
			font-weight: 600;
			font-size: 0.85rem;
			color: rgba(255, 255, 255, 0.95);
			letter-spacing: 1px;
			text-transform: uppercase;
			display: block;
			opacity: 0;
			animation: brandSlideIn 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) 0.1s forwards;
			will-change: transform, opacity;
		}

		.brand-line-btm {
			font-family: var(--font-display);
			font-weight: 800;
			font-size: 1.4rem;
			color: var(--sg-yellow);
			letter-spacing: 1px;
			text-transform: uppercase;
			display: block;
			opacity: 0;
			animation: brandSlideIn 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) 0.2s forwards;
			will-change: transform, opacity;
		}

		/* ============================================
		   NAVBAR MENU ITEMS
		   ============================================ */
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
			gap: 0.5rem;
			padding: 0.65rem 0.75rem;
			margin: 0 0.2rem;
			color: #4b5563;
			text-decoration: none;
			font-weight: 600;
			font-size: 0.75rem;
			position: relative;
			transition: color 0.2s ease, background 0.2s ease;
			white-space: nowrap;
		}

		.nav-link-icon {
			width: 20px;
			height: 20px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.nav-link-icon svg {
			width: 20px;
			height: 20px;
		}

		/* Nav Link Hover */
		.navbar-nav-horizontal .nav-link:hover {
			color: var(--sg-red);
			background: transparent;
		}

		/* Nav Link Active */
		.navbar-nav-horizontal .nav-link.active {
			color: var(--sg-red);
			font-weight: 700;
		}

		/* Nav Link Underline */
		.navbar-nav-horizontal .nav-link::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 0;
			height: 3px;
			background: var(--sg-red);
			transition: width 0.3s ease;
		}

		.navbar-nav-horizontal .nav-link:hover::after,
		.navbar-nav-horizontal .nav-link.active::after {
			width: 85%;
		}

		/* Dropdown Nav Items - Hide default underline */
		.navbar-nav-horizontal .nav-item.dropdown .nav-link::after {
			display: none;
		}

		/* Dropdown Underline (uses ::before) */
		.navbar-nav-horizontal .nav-item.dropdown .nav-link::before {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 0;
			height: 3px;
			background: var(--sg-red);
			transition: width 0.3s ease, opacity 0.3s ease;
			opacity: 0;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link:hover::before {
			width: 85%;
			opacity: 1;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link.show::before,
		.navbar-nav-horizontal .nav-item.dropdown .nav-link.active.show::before {
			width: 0;
			opacity: 0;
		}

		.navbar-nav-horizontal .nav-item.dropdown .nav-link.show,
		.navbar-nav-horizontal .nav-item.dropdown .nav-link.active {
			color: var(--sg-red);
			font-weight: 600;
		}

		/* Dropdown Arrow */
		.navbar-nav-horizontal .nav-item.dropdown .dropdown-toggle::after {
			display: inline-block;
			content: "";
			width: 6px;
			height: 6px;
			position: static;
			background: transparent;
			border-right: 2px solid #9ca3af;
			border-bottom: 2px solid #9ca3af;
			border-top: 0;
			border-left: 0;
			margin-left: 8px;
			vertical-align: 2px;
			transform: rotate(45deg);
			transition: transform 0.2s ease;
		}

		.navbar-nav-horizontal .nav-item.dropdown .dropdown-toggle.show::after {
			transform: rotate(225deg);
			border-color: var(--sg-red);
		}

		/* Dropdown Menu */
		.navbar-nav-horizontal .dropdown-menu {
			position: absolute;
			top: 107%;
			left: 0;
			margin-top: 18px;
			padding: 0.5rem 0;
			border: 1px solid #e5e7eb;
			border-top: 3px solid var(--sg-red);
			box-shadow: 0 15px 30px rgba(0,0,0,0.15);
			background: white;
			display: none;
			z-index: 1050;
			min-width: 220px;
			border-radius: 0 0 var(--radius-sm) var(--radius-sm);
		}

		.dropdown-menu::before,
		.dropdown-menu::after {
			display: none;
			content: none;
		}

		@keyframes simpleSlide {
			from { opacity: 0; transform: translateY(10px); }
			to   { opacity: 1; transform: translateY(0); }
		}

		.navbar-nav-horizontal .dropdown-menu.show {
			display: block;
			animation: simpleSlide 0.4s ease-out forwards;
		}

		/* Dropdown Items */
		.dropdown-item {
			padding: 0.7rem 1.2rem;
			font-size: 0.8rem;
			font-weight: 500;
			color: var(--nav-text);
			margin: 0;
			transition: all 0.2s ease;
		}

		.dropdown-item:hover {
			background-color: var(--nav-hover-bg);
			color: var(--sg-red);
			padding-left: 1.5rem;
		}

		.dropdown-item svg {
			color: var(--sg-red);
			width: 20px;
			margin-right: 8px;
			transition: transform 0.2s;
		}

		.dropdown-item:hover svg {
			transform: scale(1.1);
		}

		/* ============================================
		   NAVBAR ACTIONS
		   ============================================ */
		.navbar-actions {
			display: flex;
			align-items: center;
			gap: 1rem;
			margin-left: auto;
		}

		/* Action Links */
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

		.action-link-item:hover {
			color: var(--sg-red);
			text-decoration: underline;
		}

		.action-link-item svg {
			width: 14px;
			height: 14px;
		}

		/* Action Buttons */
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
			border-radius: var(--radius-sm);
		}

		.btn-auth-outline {
			border: 1px solid #d1d5db;
			background: white;
			color: var(--text-primary);
		}

		.btn-auth-outline:hover {
			border-color: var(--sg-red);
			color: var(--sg-red);
			background: #fff1f2;
		}

		.btn-auth-solid {
			background: var(--sg-red);
			color: white;
			border: 1px solid var(--sg-red);
			box-shadow: 0 2px 4px rgba(196, 30, 58, 0.15);
		}

		.btn-auth-solid:hover {
			background: var(--sg-red-dark);
			transform: translateY(-1px);
			box-shadow: 0 4px 8px rgba(196, 30, 58, 0.25);
			color: white;
		}

		/* ============================================
		   USER PROFILE DROPDOWN
		   ============================================ */
		.user-nav-item {
			position: relative;
			list-style: none;
		}

		.user-chip-toggle {
			display: inline-flex;
			flex-direction: row;
			align-items: center;
			flex-wrap: nowrap;
			white-space: nowrap;
			gap: 0.75rem;
			padding: 0.25rem 0.5rem 0.25rem 0.25rem;
			transition: all 0.2s ease;
			cursor: pointer;
			text-decoration: none;
			color: var(--nav-text);
			border: 1px solid transparent;
			background: transparent;
			border-radius: var(--radius-sm);
		}

		.user-chip-toggle:hover,
		.user-chip-toggle.show {
			background-color: #f3f4f6;
			border-color: #e5e7eb;
			text-decoration: none;
			color: var(--nav-text);
		}

		.user-chip-toggle .avatar {
			width: 34px;
			height: 34px;
			object-fit: cover;
			border: 2px solid white;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			flex-shrink: 0;
			border-radius: 50%;
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
			display: inline-block;
			content: "";
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
			flex-shrink: 0;
		}

		.user-chip-toggle.show::after {
			transform: rotate(225deg);
			border-color: var(--sg-red);
		}

		/* User Dropdown Menu */
		.user-dropdown-menu {
			position: absolute;
			top: 107%;
			right: 0;
			left: auto;
			margin-top: 12px;
			width: 260px;
			padding: 0;
			border: 1px solid #e5e7eb;
			border-top: 3px solid var(--sg-red);
			box-shadow: 0 15px 30px rgba(0,0,0,0.15);
			background: white;
			display: none;
			z-index: 1050;
			border-radius: 0 0 var(--radius-sm) var(--radius-sm);
		}

		.user-dropdown-menu.show {
			display: block;
			animation: simpleSlide 0.4s ease-out forwards;
		}

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

		.user-dropdown-menu .dropdown-item {
			padding: 0.75rem 1.25rem;
			font-size: 0.8rem;
			color: var(--nav-text);
			border-left: 3px solid transparent;
			transition: all 0.2s;
		}

		.user-dropdown-menu .dropdown-item:hover {
			background-color: #fef2f2;
			color: var(--sg-red);
			border-left-color: var(--sg-red);
			padding-left: 1.5rem;
		}

		.user-dropdown-menu .dropdown-item svg {
			margin-right: 10px;
			width: 20px;
			height: 20px;
			color: #9ca3af;
			transition: color 0.2s;
		}

		.user-dropdown-menu .dropdown-item:hover svg {
			color: var(--sg-red);
		}

		/* Logout Item */
		.item-logout {
			color: #dc2626;
		}

		.item-logout:hover {
			background-color: #fef2f2;
			color: #b91c1c;
		}

		.item-logout svg {
			color: #dc2626;
		}

		.dropdown-divider {
			margin: 0;
			border-color: var(--border-color);
		}

		/* ============================================
		   HAMBURGER MENU (Mobile)
		   ============================================ */
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
			border-radius: var(--radius-sm);
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
			border-radius: 2px;
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

		/* Mobile Sidebar */
		.mobile-sidebar {
			position: fixed;
			top: var(--navbar-height);
			left: -100%;
			width: 320px;
			height: calc(100vh - var(--navbar-height));
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
			top: var(--navbar-height);
			left: 0;
			width: 100%;
			height: calc(100vh - var(--navbar-height));
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

		/* ============================================
		   RESPONSIVE BREAKPOINTS
		   ============================================ */

		/* Extra Large Screens (1600px+) */
		@media (min-width: 1600px) {
			.navbar-top .container-fluid {
				padding-left: 0;
				padding-right: 3rem;
				max-width: 1920px;
				margin: 0 auto;
			}

			.page-wrapper .container-fluid {
				margin: 0 auto;
				padding-left: 1.5rem;
				padding-right: 3rem;
			}
		}

		/* Large Desktops (1201px - 1599px) */
		@media (min-width: 1201px) and (max-width: 1599px) {
			.navbar-top .container-fluid {
				padding-left: 0;
				padding-right: 1.5rem;
			}

			.navbar-nav-horizontal .nav-link {
				padding: 0.65rem 0.6rem;
				margin: 0 0.15rem;
				font-size: 0.72rem;
				gap: 0.4rem;
			}

			.nav-link-icon svg {
				width: 19px;
				height: 19px;
			}
		}

		/* Show Hamburger Menu (below 1500px) */
		@media (max-width: 1499px) {
			.navbar-nav-horizontal {
				display: none;
			}

			.hamburger-btn {
				display: flex;
			}

			.navbar-top .container-fluid {
				padding-left: 0;
				padding-right: 1.5rem;
			}
		}

		/* Laptops (max 1200px) */
		@media (max-width: 1200px) {
			.action-links {
				display: none;
			}

			.navbar-actions {
				gap: 0.5rem;
			}
		}

		/* Tablets (max 992px) */
		@media (max-width: 992px) {
			.navbar-brand-logo {
				clip-path: none;
				background: transparent;
				padding-right: 0;
				margin-left: 0;
			}

			.brand-line-top {
				color: var(--sg-red);
			}

			.brand-line-btm {
				color: var(--sg-red-dark);
			}

			.logo-bg-box {
				border-color: transparent;
				animation: brandSlideIn 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    			will-change: transform, opacity; /* Optimizes performance */
			}

			.navbar-top .container-fluid {
				gap: var(--sp-lg);
			}

			.navbar-actions .btn {
				padding: 0.5rem 0.75rem;
				font-size: 0.75rem;
			}
		}

		/* Mobile (max 768px) */
		@media (max-width: 768px) {
			:root {
				--navbar-height: auto;
			}

			.navbar-top {
				padding: 0.5rem 0;
				height: auto;
			}

			.navbar-top .container-fluid {
				padding-left: 1rem;
				padding-right: 1rem;
			}

			.brand-line-top {
				font-size: 0.65rem;
			}

			.brand-line-btm {
				font-size: 1rem;
			}

			.navbar-brand-logo img {
				max-width: 44px;
				max-height: 44px;
			}

			.logo-bg-box {
				width: 50px;
				height: 50px;
			}

			.navbar-actions {
				gap: 0.375rem;
			}

			.navbar-actions .btn-auth {
				padding: 0.5rem;
				font-size: 0.7rem;
				gap: 0;
			}

			.navbar-actions .btn-auth span {
				display: none;
			}

			.page-wrapper {
				padding: var(--sp-lg);
			}

			/* Adjust mobile sidebar for auto height navbar */
			.mobile-sidebar {
				top: 0;
				height: 100vh;
				padding-top: 70px;
			}

			.mobile-overlay {
				top: 0;
				height: 100vh;
			}
		}

		/* Small Mobile (max 480px) */
		@media (max-width: 480px) {
			.logo-bg-box {
				width: 44px;
				height: 44px;
			}

			.navbar-brand-logo img {
				max-width: 38px;
				max-height: 38px;
			}

			.page-wrapper {
				padding: 0.75rem;
			}

			.page-wrapper .container-fluid {
				padding-left: 0.5rem;
				padding-right: 0.5rem;
			}
		}

		/* ============================================
		   FOOTER STYLES
		   ============================================ */
		.footer {
			background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
			color: #e5e7eb;
		}

		.agency-info {
			padding: 0.5rem 0;
		}

		.agency-title {
			color: var(--sg-yellow);
			font-family: var(--font-display);
			font-size: 0.95rem;
			font-weight: 700;
			margin-bottom: 1rem;
			padding-bottom: 0.5rem;
			border-bottom: 2px solid var(--sg-red);
			display: inline-block;
		}

		.agency-subtitle {
			color: #fff;
			font-size: 0.85rem;
			font-weight: 600;
			margin-bottom: 0.25rem;
		}

		.agency-info p {
			font-size: 0.8rem;
			line-height: 1.6;
			color: #d1d5db;
		}

		.agency-info ul li {
			color: #9ca3af;
			line-height: 1.8;
		}

		.footer-list li {
			display: flex;
			align-items: flex-start;
			gap: 0.5rem;
		}

		.footer-list li svg {
			flex-shrink: 0;
			margin-top: 0.45rem;
			opacity: 0.6;
		}

		.footer-link {
			color: var(--sg-yellow);
			text-decoration: none;
			transition: color 0.2s ease;
		}

		.footer-link:hover {
			color: #fff;
			text-decoration: underline;
		}

		.payment-info p {
			color: #9ca3af;
		}

		.payment-icons {
			min-height: 40px;
		}

		.payment-icons i {
			font-size: 2rem;
			color: #fff;
		}

		.system-info {
			padding-top: 1rem;
			border-top: 1px solid #374151;
		}

		.system-info p {
			color: #9ca3af;
		}

		/* Footer Bottom Bar */
		.footer-bottom {
			background: var(--sg-red-darker);
			color: rgba(255, 255, 255, 0.8);
			font-size: 0.75rem;
		}

		.footer-bottom-link {
			color: var(--sg-yellow);
			text-decoration: none;
			transition: color 0.2s ease;
		}

		.footer-bottom-link:hover {
			color: #fff;
			text-decoration: underline;
		}

		/* Footer Responsive */
		@media (max-width: 992px) {
			.agency-info {
				text-align: center;
			}

			.agency-title {
				display: block;
				text-align: center;
			}

			.agency-info ul {
				text-align: left;
				display: inline-block;
			}
		}

		@media (max-width: 768px) {
			.footer {
				padding: 2rem 0;
			}

			.agency-title {
				font-size: 0.9rem;
			}

			.agency-info p,
			.agency-info ul li {
				font-size: 0.75rem;
			}

			.footer-bottom {
				font-size: 0.7rem;
			}
		}

		/* ============================================
		ANIMATION
		============================================ */

		/* Brand Animation (Slide In) */
		@keyframes brandSlideIn {
			0% {
				opacity: 0;
				transform: translateX(-20px); /* Start 20px to the left */
			}
			100% {
				opacity: 1;
				transform: translateX(0); /* End at original position */
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
						<img src="{{ asset('images/Jata_Negeri_Selangor_2025.png') }}" alt="Selangor">
					</div>
					<div class="brand-text-stack">
						<span class="brand-line-top">Perolehan</span>
						<span class="brand-line-btm">SELANGOR</span>
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
						<a href="{{ action('HomeController@prices') }}" class="nav-link {{ request()->is('prices*') ? 'active' : '' }}" title="Senarai Tender">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-files"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 2l3 .001v5.999a1 1 0 0 0 .883 .993l.117 .007h6v6a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-7a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3m-3 6h-1a1 1 0 0 0 -1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1 -1v-1h-4a3 3 0 0 1 -3 -3zm12.415 -1h-4.415v-4.415z" /></svg>
							</span>
							<span>Senarai Tender</span>
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
						<a href="{{ route('aduan.create') }}" class="nav-link {{ request()->is('aduan*') ? 'active' : '' }}" title="Aduan">
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
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
								Cara Mendaftar
							</a>
						</div>

						<!-- Group 2: Primary Actions -->
						<div class="action-buttons">
							<a href="{{ asset('register') }}" class="btn-auth btn-auth-outline" title="Daftar akaun vendor baharu">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -3.85" /></svg>
								<span class="d-none d-sm-inline">Daftar</span>
							</a>
							<a href="{{ route('login') }}" class="btn-auth btn-auth-solid text-decoration-none" title="Log masuk ke sistem">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M3 12h13l-3 -3" /><path d="M13 15l3 -3" /></svg>
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
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a5 5 0 1 1 -5 5l.005 -.217a5 5 0 0 1 4.995 -4.783z" /><path d="M14 14a5 5 0 0 1 5 5v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1a5 5 0 0 1 5 -5h4z" /></svg>
									Profil Saya
								</a>
								<a class="dropdown-item" href="#">
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a5 5 0 0 1 5 5v3a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3v-3a5 5 0 0 1 5 -5m0 12a2 2 0 0 0 -1.995 1.85l-.005 .15a2 2 0 1 0 2 -2m0 -10a3 3 0 0 0 -3 3v3h6v-3a3 3 0 0 0 -3 -3" /></svg>
									Tukar Kata Laluan
								</a>

								<div class="dropdown-divider"></div>

								<!-- Logout -->
								<a class="dropdown-item item-logout" href="{{ route('logout') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a1 1 0 0 1 1 1v15a1 1 0 0 1 -1 1h-5v2a1 1 0 0 1 -1.351 .936l-8 -3a1 1 0 0 1 -.649 -.936v-15a1 1 0 0 1 .212 -.616l.068 -.079l.078 -.072l.066 -.05l.092 -.058l.065 -.033l.1 -.04l.099 -.028l.046 -.01l.108 -.013l.066 -.001zm-5.649 3.064a1 1 0 0 1 .649 .936v11h4v-13h-7.486z" /></svg>
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
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" /></svg>
						<span>Utama</span>
					</a>
				</div>

				<!-- Senarai Tender -->
				<div class="mobile-nav-item">
					<a href="{{ action('HomeController@prices') }}" class="mobile-nav-link {{ request()->is('prices*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11 2l3 .001v5.999a1 1 0 0 0 .883 .993l.117 .007h6v6a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-7a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3m-3 6h-1a1 1 0 0 0 -1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1 -1v-1h-4a3 3 0 0 1 -3 -3zm12.415 -1h-4.415v-4.415z" /></svg>
						<span>Senarai Tender</span>
					</a>
				</div>

				<!-- Penender Berjaya -->
				<div class="mobile-nav-item">
					<a href="{{ action('HomeController@results') }}" class="mobile-nav-link {{ request()->is('results*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2.17a3 3 0 1 1 0 5.659v.171a6.002 6.002 0 0 1 -5 5.917v2.083h3a1 1 0 0 1 .117 1.993l-.117 .007h-8a1 1 0 0 1 -.117 -1.993l.117 -.007h3v-2.083a6.002 6.002 0 0 1 -4.996 -5.692l-.004 -.225v-.171a3 3 0 0 1 -3.996 -2.653l-.003 -.176l.005 -.176a3 3 0 0 1 3.995 -2.654l-.001 -2.17a1 1 0 0 1 1 -1h10zm-12 5a1 1 0 1 0 0 2a1 1 0 0 0 0 -2zm14 0a1 1 0 1 0 0 2a1 1 0 0 0 0 -2z" /></svg>
						<span>Penender Berjaya</span>
					</a>
				</div>

				<!-- Pekeliling -->
				<div class="mobile-nav-item">
					<a href="{{ route('circulars.public') }}" class="mobile-nav-link {{ request()->is('circulars*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.987 10.83h-.01a1 1 0 0 0 -.117 1.993l.127 .007a1 1 0 0 0 0 -2m5.99 0h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m-5.99 -4h-.01a1 1 0 0 0 -.117 1.993l.127 .007a1 1 0 0 0 0 -2m5.99 0h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m-1 -9a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" /></svg>
						<span>Pekeliling</span>
					</a>
				</div>

				<!-- Aduan -->
				<div class="mobile-nav-item">
					<a href="{{ route('aduan.create') }}" class="mobile-nav-link {{ request()->is('aduan*') ? 'active' : '' }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-6 10a1 1 0 0 0 -1 1v.01a1 1 0 0 0 2 0v-.01a1 1 0 0 0 -1 -1m0 -6a1 1 0 0 0 -1 1v3a1 1 0 0 0 2 0v-3a1 1 0 0 0 -1 -1" /></svg>
						<span>Aduan</span>
					</a>
				</div>

				<!-- Pertanyaan Dropdown -->
				<div class="mobile-nav-item">
					<button class="mobile-dropdown-toggle" data-dropdown="pertanyaan">
						<div class="mobile-nav-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M14 3.072a8 8 0 0 1 2.32 11.834l5.387 5.387a1 1 0 0 1 -1.414 1.414l-5.388 -5.387a8 8 0 0 1 -12.905 -6.32l.005 -.285a8 8 0 0 1 11.995 -6.643m-4 8.928a1 1 0 0 0 -.993 .883l-.007 .127a1 1 0 0 0 1.993 .117l.007 -.127a1 1 0 0 0 -1 -1m-1.9 -5.123a1 1 0 0 0 1.433 1.389l.088 -.09a.5 .5 0 1 1 .379 .824a1 1 0 0 0 -.002 2a2.5 2.5 0 1 0 -1.9 -4.123" /></svg>
							<span>Pertanyaan</span>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dropdown-arrow"><path d="M6 9l6 6l6 -6" /></svg>
					</button>
					<div class="mobile-dropdown-content" id="dropdown-pertanyaan">
						<a href="{{ action('HelpsController@index') }}" class="mobile-dropdown-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M14.757 16.172l3.571 3.571a10.004 10.004 0 0 1 -12.656 0l3.57 -3.571a5 5 0 0 0 2.758 .828c1.02 0 1.967 -.305 2.757 -.828m-10.5 -10.5l3.571 3.57a5 5 0 0 0 -.828 2.758c0 1.02 .305 1.967 .828 2.757l-3.57 3.572a10 10 0 0 1 -2.258 -6.329l.005 -.324a10 10 0 0 1 2.252 -6.005m17.743 6.329c0 2.343 -.82 4.57 -2.257 6.328l-3.571 -3.57a5 5 0 0 0 .828 -2.758c0 -1.02 -.305 -1.967 -.828 -2.757l3.571 -3.57a10 10 0 0 1 2.257 6.327m-5 -8.66q .707 .41 1.33 .918l-3.573 3.57a5 5 0 0 0 -2.757 -.828c-1.02 0 -1.967 .305 -2.757 .828l-3.573 -3.57a10 10 0 0 1 11.33 -.918" /></svg>
							Bantuan
						</a>
						<a href="{{ route('manuals.show', 'pendaftaran') }}" class="mobile-dropdown-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 2a3 3 0 0 1 2.995 2.824l.005 .176v14a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005h14zm-6.99 13l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" /></svg>
							Panduan Pengguna
						</a>
					</div>
				</div>

				<!-- Direktori Agensi Dropdown -->
				<div class="mobile-nav-item">
					<button class="mobile-dropdown-toggle" data-dropdown="agensi">
						<div class="mobile-nav-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10 22a1 1 0 0 1 0 -2h1v-2.001l-5 .001a1 1 0 0 1 -.707 -.293l-2 -2a1 1 0 0 1 0 -1.414l2 -2a1 1 0 0 1 .707 -.293l5 -.001v-1.999h-3a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h3v-1a1 1 0 0 1 2 0v1h6a1 1 0 0 1 .707 .293l2 2a1 1 0 0 1 0 1.414l-2 2a1 1 0 0 1 -.707 .293h-6v1.999l1 .001a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1l-1 -.001v2.001h1a1 1 0 0 1 0 2z" /></svg>
							<span>Direktori Agensi</span>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dropdown-arrow"><path d="M6 9l6 6l6 -6" /></svg>
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
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2a3 3 0 0 1 2.995 2.824l.005 .176v16a1 1 0 0 1 -.883 .993l-.117 .007h-5a1 1 0 0 1 -.993 -.883l-.007 -.117v-3h-2v3a1 1 0 0 1 -.883 .993l-.117 .007h-5a1 1 0 0 1 -.993 -.883l-.007 -.117v-16a3 3 0 0 1 2.824 -2.995l.176 -.005h10zm-6 6h-1a1 1 0 1 0 0 2h1a1 1 0 0 0 0 -2zm4 0h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0 -2z" /></svg>
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

				<!-- ALERTS -->
				@if(session('notice'))
					<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
						{{ session('notice') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				@endif

				@if(session('error'))
					<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
						{{ session('error') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				@endif

				@yield('content')
			</div>
		</div>
	</div>

	@include('layouts._footer')


	<!-- Old Scripts (disabled - conflicts with Bootstrap 5) -->
	{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
	{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
	{{-- <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script> --}}

	<!-- Modern JS (includes jQuery and Bootstrap 5) -->
	<script src="{{ asset('js/modern.js') }}"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.3.5/datatables.min.js" integrity="sha384-qH0inyYSCOpaLgM/WSarLVnq0ULwworkGFzUI+E6bpx0DUCIsJePT0TRDnLnkcU1" crossorigin="anonymous"></script>

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
