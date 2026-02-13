<!DOCTYPE html>
<html lang="ms">
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

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">

	<!-- Selectize CSS -->
	<link href="{{ asset('packages/selectize/dist/css/selectize.default.css') }}" rel="stylesheet">

	<!-- Bootstrap Datepicker CSS -->
	<link href="{{ asset('packages/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" /> --}}
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.5/datatables.min.css" rel="stylesheet" integrity="sha384-Lv8lYSJkh1Hc5kB9lk2YbbGdchMRCuAcwUOYWZ3Q/YIfKNVW+6W+V57wxKNv1D8l" crossorigin="anonymous">

	@yield('styles')
    @stack('styles')

	<style>
		:root {
			/* ===== SELANGOR BRAND COLORS ===== */
			--sg-red: #c41e3a;
			--sg-red-dark: #8b1428;
			--sg-red-deep: #5a0d1a;
            --sg-red-soft: rgba(196, 30, 58, 0.05);
			--sg-yellow: #ffcc00;
			--sg-black: #1f1f1f;
			--sg-bg: #f8f9fa;

            /* ===== SUCCESS COLORS ===== */
            --success-color: #10b981;

			/* ===== SIDEBAR VARIABLES ===== */
			--sidebar-width: 290px;
            --sidebar-width-mobile: 260px;
			--sidebar-bg: linear-gradient(160deg, #c41e3a 0%, #8b1428 100%);
			--sidebar-text: rgba(255, 255, 255, 0.8);
			--sidebar-text-hover: #ffffff;
			--sidebar-hover-bg: rgba(255, 255, 255, 0.1);
            
            /* Active State (White Card Style) */
            --sidebar-active-bg: #ffffff;
			--sidebar-active-text: #c41e3a;
            --sidebar-active-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);

			--sidebar-submenu-bg: rgba(0, 0, 0, 0.15);
            --sidebar-submenu-text: rgba(255, 255, 255, 0.7);

            /* ===== TOPBAR VARIABLES ===== */
            --topbar-height: 100px;
            --topbar-bg: #ffffff;
            --topbar-border: #e5e7eb;
            --topbar-text: #374151;

			/* ===== LAYOUT & SCROLLBAR ===== */
            --scrollbar-thumb: rgba(255, 255, 255, 0.2);
            --scrollbar-track: transparent;
            --transition-speed: 0.3s;

            /* ===== Input Fields ===== */
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --label-color: #64748b;
            --text-dark: #334155;
		}

		* { box-sizing: border-box; }

		html, body {
			margin: 0;
			padding: 0;
			height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            overflow-x: hidden;
            font-size: 0.9rem;
		}

		/* ===== PAGE LAYOUT ===== */
		.page-container {
			display: flex;
			min-height: 100vh;
            position: relative;
		}

		/* ===== SIDEBAR STYLES ===== */
		.sidebar {
			width: var(--sidebar-width);
			background: var(--sidebar-bg);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			z-index: 1040; 
			transition: transform var(--transition-speed) ease-in-out;
			display: flex;
			flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.1);
		}

        /* Scrollbar */
        .sidebar-scroll-area {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
            padding-bottom: 2rem;
            position: relative; /* Context for z-index */
        }
        .sidebar-scroll-area::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll-area::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll-area::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 10px; }
        .sidebar-scroll-area::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.4); }

		/* --- SIDEBAR HEADER (With Floating Box) --- */
		.sidebar-header {
			height: var(--topbar-height);
			display: flex;
			align-items: center;
            justify-content: center;
            padding: 0 1.5rem;
            position: relative;
            background: rgba(0,0,0,0.05);
		}

        /* --- GRADIENT LINE --- */
        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.5) 50%, transparent 100%);
            box-shadow: 0 1px 3px rgba(255,255,255,0.2);
            z-index: 2;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            width: 100%;
        }

		.sidebar-logo-container {
            background: white;
            border-radius: 12px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
		}

		.sidebar-logo { width: 57px; height: 70px; }
        
        .sidebar-brand-text { display: flex; flex-direction: column; }

        .brand-title {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .brand-title-top {
            font-weight: 500;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.85);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .brand-title-bottom {
            font-weight: 800;
            font-size: 1.35rem;
            color: #fff;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .brand-subtitle {
            font-size: 0.60rem;
            color: var(--sg-yellow);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
            font-weight: 600;
        }

		/* Navigation List */
		.sidebar-nav {
			list-style: none;
			padding: 1.5rem 1rem;
			margin: 0;
		}

		.nav-item { margin-bottom: 6px; }

		/* Section Header */
        .nav-section-header {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #ffcc00;
            /* margin: 30px 0 10px 0; */
            letter-spacing: 1px;
            padding-left: 1rem;
            display: flex;
            align-items: center;
            position: relative;
        }

		 /* Line after the text */
        .nav-section-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin-left: 10px;
            margin-right: 10px;
        }

		/* --- MAIN LINKS --- */
		.sidebar-link {
			display: flex;
			align-items: center;
			padding: 0.9rem 1.2rem;
			color: var(--sidebar-text);
			text-decoration: none;
			border-radius: 12px;
			transition: all 0.2s ease;
			font-weight: 500;
			font-size: 0.9rem;
            position: relative;
            border: 1px solid transparent;
		}

		.sidebar-link:hover {
			background: var(--sidebar-hover-bg);
			color: var(--sidebar-text-hover);
            padding-left: 1.4rem;
		}

        /* Active State */
		.sidebar-link[aria-expanded="true"], 
        .sidebar-link.active {
			background: var(--sidebar-active-bg);
			color: var(--sidebar-active-text);
			font-weight: 700;
            box-shadow: var(--sidebar-active-shadow);
		}
        
        .sidebar-link[aria-expanded="true"]::before, 
        .sidebar-link.active::before { content: none; }

		.nav-icon {
			width: 20px;
			height: 20px;
			margin-right: 12px;
            opacity: 0.8;
			stroke-width: 1.5;
            transition: all 0.2s;
            flex-shrink: 0;
		}
        
        .sidebar-link[aria-expanded="true"] .nav-icon,
        .sidebar-link.active .nav-icon {
            opacity: 1;
            stroke: var(--sg-red);
            transform: scale(1.1);
        }

		.nav-text { flex: 1; }

		.nav-arrow {
			width: 16px;
			height: 16px;
			transition: transform 0.3s ease;
            opacity: 0.6;
		}

		.sidebar-link[aria-expanded="true"] .nav-arrow {
			transform: rotate(90deg);
            opacity: 1;
            color: var(--sg-red);
		}

		/* Submenu Container */
		.sidebar-submenu {
			list-style: none;
			padding: 0.5rem 0;
            margin: 5px 0 0.5rem 0;
            background: var(--sidebar-submenu-bg);
            border-radius: 12px;
		}

        .sidebar-submenu li { margin: 0; }

        /* Submenu Item */
		.sidebar-submenu .submenu-item {
			padding: 0.7rem 1rem 0.7rem 1rem; 
			color: var(--sidebar-submenu-text);
			font-size: 0.85rem;
			text-decoration: none;
			display: flex;
            align-items: flex-start;
			transition: all 0.2s ease;
            white-space: normal;
            word-break: break-word;
            line-height: 1.4;
            position: relative;
            border-radius: 8px;
            margin: 0 0.5rem; 
		}

        .submenu-section-header {
            padding: 0.75rem 1rem 0.25rem 1.5rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            color: var(--sg-yellow);
            display: block;
            opacity: 0.9;
        }

        .submenu-icon {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.3);
            margin-top: 7px; 
            margin-right: 10px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .sidebar-submenu .submenu-item:hover {
			color: white;
            background-color: rgba(255,255,255,0.08);
		}

        .sidebar-submenu .submenu-item:hover .submenu-icon {
            background-color: var(--sg-yellow);
            box-shadow: 0 0 5px var(--sg-yellow);
            transform: scale(1.2);
        }

		/* ===== MAIN WRAPPER ===== */
		.main-wrapper {
			margin-left: var(--sidebar-width);
			flex: 1;
			display: flex;
			flex-direction: column;
            width: calc(100% - var(--sidebar-width));
            transition: margin-left var(--transition-speed) ease-in-out;
		}

        /* ===== TOPBAR ===== */
        .topbar {
            height: var(--topbar-height);
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
        }

        /* Left Area */
        .topbar-left { 
            display: flex; 
            align-items: center; 
            gap: 1.5rem; 
        }
        
        .mobile-toggle {
            display: none;
            background: transparent;
            border: none;
            padding: 0.5rem;
            color: var(--topbar-text);
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .mobile-toggle:hover {
            color: var(--sg-red);
        }

        .page-title-group {
            display: flex;
            flex-direction: column;
        }

        .page-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: #111827;
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.2px;
        }

        .current-date {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 2px;
        }
        
        .current-date svg {
            color: var(--sg-red);
            width: 14px;
            height: 14px;
        }

        /* Right Area */
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* User Profile */
        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--topbar-text);
            padding: 0.4rem 0.6rem 0.4rem 1rem;
            border-radius: 50px;
            background-color: transparent;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .user-menu-btn:hover, 
        .user-menu-btn[aria-expanded="true"] { 
            background-color: #f9fafb;
            border-color: #e5e7eb;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
            text-align: right;
        }

        .user-name { 
            font-size: 0.85rem; 
            font-weight: 600; 
            color: #374151; 
			margin-bottom: 3px;
        }
        
        .user-role { 
            font-size: 0.65rem; 
            color: #9ca3af; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--sg-red), #9f1239);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Dropdown */
        .custom-dropdown-menu {
            border: 1px solid #f3f4f6;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.5rem;
            min-width: 240px;
            margin-top: 10px !important;
        }

        .dropdown-header-custom {
            padding: 0.6rem 1rem;
            margin-bottom: 0.5rem;
            color: #9ca3af;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dropdown-item {
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            color: #4b5563;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #fef2f2;
            color: var(--sg-red);
        }

        .dropdown-item svg {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            color: #9ca3af;
            transition: color 0.2s;
        }
        
        .dropdown-item:hover svg { color: var(--sg-red); }

        .dropdown-divider { margin: 0.5rem 0; border-color: #f3f4f6; }

        /* Guest Buttons */
        .btn-guest {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }
        .btn-guest-outline { border: 1px solid #d1d5db; color: #374151; background: white; }
        .btn-guest-outline:hover { border-color: var(--sg-red); color: var(--sg-red); background: #fff; }
        .btn-guest-solid { background: var(--sg-red); color: white; border: 1px solid var(--sg-red); margin-left: 10px; }
        .btn-guest-solid:hover { background: #a01830; border-color: #a01830; }

        /* Content */
        .content-wrapper { padding: 2rem; }

		/* Responsive */
		@media (max-width: 991px) {
			.sidebar { transform: translateX(-100%); width: 260px; }
			.sidebar.show { transform: translateX(0); }
			.main-wrapper { margin-left: 0; width: 100%; }
            .mobile-toggle { display: flex; align-items: center; justify-content: center; }
            .user-info { display: none; } 
            .user-menu-btn { padding: 0; border: none; background: transparent; }
            .topbar { padding: 0 1rem; height: 64px; }
            .page-title { font-size: 1rem; }
            .current-date { display: none; }
            .content-wrapper { padding: 1rem; }
		}

        /* CUSTOM: Bawah ni nanti asingkan masuk dlm separate css file.  */

        /* ===== BUTTON ===== */
        .btn-selangor {
            background-color: var(--sg-red) !important;
            border-color: var(--sg-red) !important;
            color: white !important;
        }

        .btn-selangor:hover {
            background-color: var(--sg-red-dark) !important;
            border-color: var(--sg-red-dark) !important;
        }

		/* ===== FORM VALIDATION ===== */
		.has-error .control-label,
		.has-error .help-block,
		.has-error .checkbox,
		.has-error .checkbox-inline,
		.has-error .radio,
		.has-error .radio-inline,
		.has-error.checkbox label,
		.has-error.checkbox-inline label,
		.has-error.radio label,
		.has-error.radio-inline label {
			color: #a94442;
		}

		.has-error .form-control {
			border-color: #a94442;
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		}

		.has-error .form-control:focus {
			border-color: #843534;
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #ce8483;
		}

		.has-error .input-group-addon {
			color: #a94442;
			background-color: #f2dede;
			border-color: #a94442;
		}

		.help-block.error {
			color: #a94442;
			font-size: 0.875rem;
			margin-top: 0.25rem;
		}

		.has-error .selectize-input {
			border-color: #a94442;
		}

		.has-error .selectize-input:focus {
			border-color: #843534;
		}
	</style>
</head>

<body>
	<div class="page-container">
		
        @include('layouts.v3.sidebar')

		<!-- Main Content Wrapper -->
		<div class="main-wrapper">
			
            @include('layouts.v3.topbar')

			<div class="content-wrapper">
				<div class="content-container">
					@include('layouts._notification')
					@yield('content')
				</div>
			</div>
		</div>
	</div>

    @stack('modals')
 
	<!-- Scripts -->
	<script src="{{ asset('js/modern.js') }}"></script>
	<!-- Bootbox 6.x -->
    <script src="{{ asset('packages/bootbox/bootbox.js') }}"></script>
	<!-- Selectize.js -->
	<script src="{{ asset('packages/selectize/dist/js/standalone/selectize.min.js') }}"></script>
	<!-- sheepIt (Dynamic Form Repeater) -->
	<script src="{{ asset('js/sheepit.js') }}"></script>
	<!-- Bootstrap Datepicker -->
	<script src="{{ asset('packages/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script> --}}
    <script src="https://cdn.datatables.net/v/dt/dt-2.3.5/datatables.min.js" integrity="sha384-qH0inyYSCOpaLgM/WSarLVnq0ULwworkGFzUI+E6bpx0DUCIsJePT0TRDnLnkcU1" crossorigin="anonymous"></script>

	<script>
		$(document).ready(function() {
            // Mobile Menu Toggle
			$('.mobile-menu-toggle').on('click', function() {
				$('.sidebar').toggleClass('show');
			});
			$(document).on('click', function(e) {
				if ($(window).width() <= 991 && !$(e.target).closest('.sidebar, .mobile-menu-toggle').length) {
					$('.sidebar').removeClass('show');
				}
			});
			$.ajaxSetup({ headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') } });
		});
	</script>
    <script>
        // Sidebar Auto Scroll When Opening Long Submenu
        $(document).ready(function() {
            $('.sidebar .collapse').on('show.bs.collapse', function () {
                var $container = $('.sidebar-scroll-area');
                var $collapse = $(this);
                var $parentLi = $collapse.closest('.nav-item');
                var $innerList = $collapse.find('.sidebar-submenu');

                var $clone = $innerList.clone().css({
                    'display': 'block',
                    'visibility': 'hidden',
                    'position': 'absolute',
                    'top': '-9999px',
                    'left': '-9999px'
                }).appendTo($parentLi);
                
                var submenuHeight = $clone.outerHeight();
                $clone.remove();

                var containerHeight = $container.height();
                var containerScroll = $container.scrollTop();
                
                var headerRect = $parentLi[0].getBoundingClientRect();
                var containerRect = $container[0].getBoundingClientRect();
                
                var relativeTop = headerRect.top - containerRect.top;
                var headerHeight = $parentLi.find('.sidebar-link').outerHeight();

                var totalExpectedHeight = headerHeight + submenuHeight;
                var expectedBottomEdge = relativeTop + totalExpectedHeight;

                if (expectedBottomEdge > containerHeight) {
                    var scrollAmount = expectedBottomEdge - containerHeight + 20;

                    $container.animate({
                        scrollTop: containerScroll + scrollAmount
                    }, 300);
                }
            });
        });
    </script>
	<!-- Botman Chatbot Widget -->
	<script>
		@php $chat_id = Str::random(8); @endphp

		var botmanWidget = {
			title: 'Lela (Bot)',
			introMessage: 'Hi, saya Lela. Saya di sini untuk membantu anda dan menjawab persoalan anda.',
			mainColor: '#c41e3a',
			aboutText: '',
			bubbleBackground: '#c41e3a',
			headerTextColor: '#fff',
			desktopHeight: 500,
			desktopWidth: 400,
			bubbleAvatarUrl: '{{ asset('images/chatbot.png') }}',
			placeholderText: 'Hantar Pesanan..',
			frameEndpoint: "{{ route('chat_widget',['chat_id' => $chat_id]) }}",
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
									botmanChatWidget.say('<img src="' + sender_response_detail.response + '" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only") {
									botmanChatWidget.say(sender_response_detail.response);
								}
							}

							if (sender_response_detail.sender == "bot") {
								if (sender_response_detail.type == "image_only") {
									botmanChatWidget.sayAsBot('<img src="' + sender_response_detail.response + '" alt="attach" width="120" height="120">');
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

	@yield('scripts')
	@stack('scripts')
</body>
</html>