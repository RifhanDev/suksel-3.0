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

	<!-- Tabler CSS -->
	<link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.40.0/tabler-icons.min.css" rel="stylesheet">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	@yield('styles')

	<style>
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
			background: #007bff !important;
			border: 2px solid #0056b3 !important;
		}

		/* Hamburger menu fixes */
		.navbar-toggler {
			z-index: 1000 !important;
			position: relative !important;
			cursor: pointer !important;
		}

		.navbar-toggler:focus {
			box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
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
				background: white !important;
				border-top: 1px solid #dee2e6 !important;
				box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
			}
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
