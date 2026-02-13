<html>

<head>
	<title>BotMan Widget</title>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	<link href="{{ asset('packages/fontawesome/css/font-awesome.css') }}" type="text/css" rel="stylesheet" media="screen">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css"
		href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="static/css/chat.min.css"> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


	{{-- Modern Chatbot Styles --}}
	<style>
		/* Modern Typography */
		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
			background: #ffffff;
		}

		/* Modern Chat Messages */
		.chat ol li {
			border-radius: 18px !important;
			padding: 12px 16px !important;
			margin: 8px 0 !important;
			font-size: 14px !important;
			line-height: 1.5 !important;
			word-wrap: break-word !important;
			animation: message-fade-in 0.3s ease-out !important;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
			transition: all 0.2s ease !important;
		}

		@keyframes message-fade-in {
			from {
				opacity: 0;
				transform: translateY(10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* Bot Messages */
		.chat ol li.from-bot {
			background: #f3f4f6 !important;
			color: #1f2937 !important;
			border-bottom-left-radius: 4px !important;
			margin-right: 20% !important;
		}

		/* User Messages */
		.chat ol li.from-user {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
			color: #ffffff !important;
			border-bottom-right-radius: 4px !important;
			margin-left: 20% !important;
			text-align: right !important;
		}

		.chat ol li:hover {
			transform: translateY(-2px) !important;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
		}

		/* Modern Input Area */
		#userText,
		input[type="text"],
		textarea {
			border-radius: 12px !important;
			border: 2px solid #e5e7eb !important;
			padding: 12px 16px !important;
			font-size: 14px !important;
			transition: all 0.2s ease !important;
			background: #ffffff !important;
			font-family: 'Inter', sans-serif !important;
		}

		#userText:focus,
		input[type="text"]:focus,
		textarea:focus {
			outline: none !important;
			border-color: #dc2626 !important;
			box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
		}

		/* Modern Send Button */
		button[type="submit"],
		.btn-send,
		#send {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
			border-radius: 12px !important;
			border: none !important;
			padding: 12px 20px !important;
			transition: all 0.2s ease !important;
			box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3) !important;
			color: #ffffff !important;
			font-weight: 500 !important;
			cursor: pointer !important;
		}

		button[type="submit"]:hover,
		.btn-send:hover,
		#send:hover {
			transform: translateY(-2px) !important;
			box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4) !important;
		}

		button[type="submit"]:active,
		.btn-send:active,
		#send:active {
			transform: translateY(0) !important;
		}

		/* Modern Scrollbar */
		.chat,
		#messageArea,
		[class*="message"] {
			scrollbar-width: thin !important;
			scrollbar-color: #d1d5db #f3f4f6 !important;
		}

		.chat::-webkit-scrollbar,
		#messageArea::-webkit-scrollbar,
		[class*="message"]::-webkit-scrollbar {
			width: 6px !important;
		}

		.chat::-webkit-scrollbar-track,
		#messageArea::-webkit-scrollbar-track,
		[class*="message"]::-webkit-scrollbar-track {
			background: #f3f4f6 !important;
			border-radius: 10px !important;
		}

		.chat::-webkit-scrollbar-thumb,
		#messageArea::-webkit-scrollbar-thumb,
		[class*="message"]::-webkit-scrollbar-thumb {
			background: #d1d5db !important;
			border-radius: 10px !important;
		}

		.chat::-webkit-scrollbar-thumb:hover,
		#messageArea::-webkit-scrollbar-thumb:hover,
		[class*="message"]::-webkit-scrollbar-thumb:hover {
			background: #9ca3af !important;
		}

		/* Attachment Button */
		.div-attachments-container {
			width: 100%;
			display: inline-block;
			position: fixed;
			bottom: 70px;
		}

		.div-attachments {
			margin-top: 10px;
			position: relative;
		}

		div.btn {
			white-space: normal !important;
			word-break: break-word !important;
			overflow-wrap: break-word !important;
		}

		.circle-button {
			width: 44px;
			height: 44px;
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			border-radius: 50% !important;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
			position: relative;
			z-index: 1;
			opacity: 1;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
		}

		.circle-button:hover {
			transform: scale(1.1);
			box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
		}

		.circle-button-icon {
			color: #ffffff;
			font-size: 20px;
			transition: transform 0.2s;
		}

		.circle-button:hover .circle-button-icon {
			transform: scale(1.15);
		}

		.options {
			position: absolute;
			bottom: calc(100% + 10px);
			left: 50%;
			transform: translateX(-50%);
			background: #ffffff;
			color: #1f2937;
			padding: 8px 12px;
			border-radius: 8px;
			opacity: 0;
			pointer-events: none;
			transition: all 0.2s ease;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			font-size: 12px;
			white-space: nowrap;
		}

		.active .options {
			opacity: 1;
			pointer-events: auto;
		}

		/* Typing Indicator */
		.typing-indicator {
			display: flex;
			gap: 4px;
			padding: 12px 16px;
		}

		.typing-dot {
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background: #9ca3af;
			animation: typing-bounce 1.4s infinite ease-in-out;
		}

		.typing-dot:nth-child(1) {
			animation-delay: -0.32s;
		}

		.typing-dot:nth-child(2) {
			animation-delay: -0.16s;
		}

		@keyframes typing-bounce {

			0%,
			80%,
			100% {
				transform: scale(0.8);
				opacity: 0.5;
			}

			40% {
				transform: scale(1);
				opacity: 1;
			}
		}
	</style>
</head>

<body>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
		integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
		integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
		integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
	</script>

	<script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js'></script>

	{{-- Configure CSRF Token for BotMan Requests --}}
	<script>
		// Set up CSRF token for all AJAX requests
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// Intercept XMLHttpRequest to add CSRF token
		(function() {
			var originalOpen = XMLHttpRequest.prototype.open;
			var originalSend = XMLHttpRequest.prototype.send;
			var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

			XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
				this._url = url;
				return originalOpen.apply(this, arguments);
			};

			XMLHttpRequest.prototype.send = function(data) {
				if (this._url && this._url.includes('botman') && csrfToken) {
					this.setRequestHeader('X-CSRF-TOKEN', csrfToken);
				}
				return originalSend.apply(this, arguments);
			};
		})();
	</script>

	{{-- <script src="static/js/jquery-1.10.2.min.js"></script>
	<script src="static/bootstrap-4.1.3/js/bootstrap.min.js"></script> --}}

	<div id="fileApp"></div>
	<div class="div-attachments-container">
		<div class="div-attachments">
			<div class="pull-right">
				<div style="padding-right: 10px;">
					<div class="circle-button" id="circle-button">
						<div class="circle-button-icon" id="circle-button-icon">
							<i class="fa fa-paperclip"></i>
						</div>
						<div class="options" id="open-folder">
							Tambah Lampiran
						</div>
					</div>
					<span id="view-file-name" style="display:none"></span>
				</div>
			</div>


		</div>
	</div>

	{{-- <script src='static/js/bot_attachment.js?v=1'></script> --}}
	{{-- bot_attachment.js  --}}
	<script>
		$(document).ready(function() {
			$("#circle-button").on("click", function() {
				$(this).toggleClass("active");
				var icon = $(this).find(".circle-button-icon i");
				icon.toggleClass("fa-paperclip fa-times");
			});


			document.getElementById('fileApp').innerHTML =
				'<div> <input style="display:none" type="file" id="fileInput" /> </div> ';

			const fileInput = document.querySelector("#fileInput");
			var file_type;
			var files;

			$("#open-folder").on("click", function(e) {
				file_type = "image";
				fileInput.click();
			});

			// $("#view-audio").on("click", function(e){
			//     file_type = "audio";
			//     fileInput.click();
			// });

			// $("#send").on("click", function(e){
			//     if(($("#view-file-name").text() == "") || (files == null)) return;
			//     sendFile(files[0], file_type);
			// });

			$("#fileInput").on("change", function(e) {
				console.log("File here");
				files = e.target.files;
				console.log(files);
				if (files.length > 0) {
					$("#view-file-name").text(files[0]["name"]);
					sendFile(files[0], file_type)
				}

			});

			function sendFile(file, filetype) {
				var form = new FormData();
				form.append("driver", "web");
				form.append("attachment", filetype);
				form.append("interactive", 0);
				form.append("file", file);
				form.append("userId", '{{ $chat_id ?? 'EMpty Id' }}');

				// Get CSRF token - try iframe first, then current page
				var csrfToken = '';
				try {
					// Try to get from parent window (main page)
					if (window.parent && window.parent.document) {
						var parentToken = window.parent.document.querySelector('meta[name="csrf-token"]');
						if (parentToken) {
							csrfToken = parentToken.getAttribute('content');
						}
					}
					// Fallback to current page
					if (!csrfToken) {
						csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
					}
				} catch (e) {
					// If can't access parent, use current page token
					csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
				}

				// Add CSRF token to form data (required for multipart/form-data)
				form.append("_token", csrfToken);

				var settings = {
					"url": "{{ url('botman') }}",
					headers: {
						'X-CSRF-TOKEN': csrfToken,
						'X-Requested-With': 'XMLHttpRequest'
					},
					"method": "POST",
					"timeout": 0,
					"processData": false,
					"mimeType": "multipart/form-data",
					"contentType": false,
					"data": form
				};

				$.ajax(settings)
					.done(function(response) {
						files = null;
						$("#fileInput").val(null);
						$("#view-file-name").text("");

						try {
							response = JSON.parse(response);
							window.parent.postMessage(response, '*');
						} catch (e) {
							console.error('Error parsing response:', e);
							window.parent.postMessage({
								status: 200,
								messages: [{
									text: 'File uploaded successfully'
								}]
							}, '*');
						}
					})
					.fail(function(xhr, status, error) {
						console.error('File upload failed:', status, error);
						console.error('Response:', xhr.responseText);
						console.error('CSRF Token used:', csrfToken ? 'Token found' : 'Token missing');
						if (xhr.status === 419) {
							// CSRF token issue - try to get fresh token and show helpful message
							console.error('CSRF token expired or invalid');
							alert('Sesi telah tamat. Sila muat semula halaman dan cuba lagi.');
						} else {
							alert('Ralat semasa memuat naik fail. Sila cuba lagi. (Status: ' + xhr.status + ')');
						}
					});
			}
		});
	</script>


	{{-- <script src="static/js/chat_changes.js?v=1"></script> --}}
	{{-- chat_changes.js --}}
	<script>
		window.addEventListener('load', function() {
			var messageArea = document.getElementById("messageArea");
			var userText = document.getElementById("userText");
			var chatOl = document.getElementsByClassName("chat")[0];
			var messageAreaHeight = messageArea.clientHeight;
			chatHeight = chatOl.clientHeight;
			// messageArea.style.height = (messageAreaHeight - 20) + "px";
			// chatOl.style.height = (chatHeight - 20) + "px";
			userText.setAttribute("autocomplete", "off");
			userText.style.width = "100%";
			messageArea.style.overflow = "auto";
			// userText.style.position = "absolute";
			// userText.style.bottom = "40px";
		});
	</script>
</body>

</html>

<!doctype html>
<html>

<head>
	<title>BotMan Widget</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css"
		href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">
</head>

<body>

</body>

</html>
