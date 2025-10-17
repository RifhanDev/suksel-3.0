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
		$(document).ready(function() {
			// Ensure Bootstrap modals work properly
			var loginModal = document.getElementById('loginModal');
			if (loginModal) {
				// Add event listener for modal show
				loginModal.addEventListener('show.bs.modal', function(event) {
					console.log('Login modal is opening');
				});

				// Add event listener for modal shown
				loginModal.addEventListener('shown.bs.modal', function(event) {
					console.log('Login modal is now visible');
					// Focus on email input
					var emailInput = loginModal.querySelector('input[name="email"]');
					if (emailInput) {
						emailInput.focus();
					}
				});
			}

			// Handle login button clicks using jQuery
			$('[data-bs-target="#loginModal"]').on('click', function(e) {
				e.preventDefault();
				var modal = new bootstrap.Modal(document.getElementById('loginModal'));
				modal.show();
			});
		});
	</script>

</body>

</html>
