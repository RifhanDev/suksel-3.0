<!DOCTYPE html>
<html lang="ms">

<head>
	<title>BotMan Widget</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link href="{{ asset('packages/botman/build/assets/css/chat.min.css') }}" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="{{ asset('packages/fontawesome/css/font-awesome.css') }}" type="text/css" rel="stylesheet" media="screen">

	<style>
		html,
		body {
			height: 100%;
			overflow: hidden;
		}

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			background: #ffffff;
			margin: 0;
			display: flex;
			flex-direction: column;
		}

		#fileApp {
			position: absolute;
			width: 0;
			height: 0;
			overflow: hidden;
		}

		#botmanChatRoot {
			flex: 1 1 auto;
			display: flex;
			flex-direction: column;
			min-height: 0;
			width: 100%;
		}

		.chat ol li {
			border-radius: 18px;
			padding: 12px 16px;
			margin: 8px 0;
			font-size: 14px;
			line-height: 1.5;
			word-wrap: break-word;
		}

		.chat ol li.from-bot {
			background: #f3f4f6;
			color: #1f2937;
		}

		.chat ol li.from-user {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			color: #ffffff;
		}

		/* Syor / menu / confirmation buttons (must stay above fixed paperclip bar) */
		.chat .msg .btn,
		.chat .msg a.btn,
		.chat .msg div.btn {
			display: block;
			width: 100%;
			max-width: 100%;
			margin: 6px 0;
			padding: 10px 14px;
			text-align: center;
			background: #fff;
			border: 2px solid #c41e3a;
			color: #c41e3a;
			border-radius: 8px;
			cursor: pointer;
			font-size: 14px;
			font-weight: 600;
			white-space: normal;
			word-break: break-word;
			position: relative;
			z-index: 20;
			pointer-events: auto;
			user-select: none;
			-webkit-tap-highlight-color: transparent;
		}

		.chat .msg .btn:hover,
		.chat .msg a.btn:hover {
			background: #c41e3a;
			color: #fff;
			text-decoration: none;
		}

		#messageArea {
			flex: 1 1 auto;
			min-height: 0;
			overflow-y: auto;
			padding-bottom: 24px;
			-webkit-overflow-scrolling: touch;
		}

		/* Override BotMan fixed input bar — it overlapped action buttons at the bottom. */
		input.textarea#userText,
		#userText.textarea {
			position: relative !important;
			bottom: auto !important;
			left: auto !important;
			right: auto !important;
			width: 100% !important;
			flex-shrink: 0;
			z-index: 1;
			box-sizing: border-box;
		}

		#userText,
		input[type="text"],
		textarea {
			border-radius: 12px;
			border: 2px solid #e5e7eb;
			padding: 12px 16px;
			font-size: 14px;
		}

		#userText:focus,
		input[type="text"]:focus,
		textarea:focus {
			outline: none;
			border-color: #dc2626;
			box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
		}

		button[type="submit"],
		.btn-send,
		#send {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			border-radius: 12px;
			border: none;
			color: #fff;
		}

		/* Paperclip only — do not use a full-width hit area (it blocked Ya/Tidak clicks). */
		.div-attachments-container {
			position: fixed;
			bottom: 88px;
			right: 12px;
			left: auto;
			width: auto;
			height: auto;
			pointer-events: none;
			z-index: 15;
		}

		.div-attachments-container .div-attachments,
		.div-attachments-container .pull-right {
			width: auto;
			pointer-events: none;
		}

		.div-attachments-container .circle-button {
			pointer-events: auto;
		}

		.circle-button {
			width: 44px;
			height: 44px;
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			border-radius: 50%;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
			box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
		}

		.circle-button-icon {
			color: #ffffff;
			font-size: 20px;
		}

		.options {
			position: absolute;
			bottom: calc(100% + 10px);
			left: 50%;
			transform: translateX(-50%);
			background: #ffffff;
			padding: 8px 12px;
			border-radius: 8px;
			opacity: 0;
			pointer-events: none;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			font-size: 12px;
			white-space: nowrap;
		}

		.circle-button.active .options {
			opacity: 1;
			pointer-events: auto;
		}

		.chat .msg {
			position: relative;
			z-index: 2;
		}
	</style>
</head>

<body>
	<div id="fileApp"></div>
	<div class="div-attachments-container">
		<div class="div-attachments">
			<div class="pull-right">
				<div style="padding-right: 10px;">
					<div class="circle-button" id="circle-button">
						<div class="circle-button-icon">
							<i class="fa fa-paperclip"></i>
						</div>
						<div class="options" id="open-folder">Tambah Lampiran</div>
					</div>
					<span id="view-file-name" style="display:none"></span>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" crossorigin="anonymous"></script>

	{{-- Config from parent iframe ?conf= (merged by chat.js) — ensure chatServer is absolute --}}
	<script>
		(function() {
			var params = new URLSearchParams(window.location.search);
			var conf = {};
			try {
				conf = JSON.parse(decodeURIComponent(params.get('conf') || '{}'));
			} catch (e) {
				conf = {};
			}
			window.botmanWidget = Object.assign({
				chatServer: @json(url('botman')),
				userId: @json($chat_id ?? ''),
				introMessage: 'Hai, saya Lela — pembantu SUKSEL. Saya boleh bantu dengan panduan, status permohonan, aduan, dan soalan lazim (FAQ). Menu pilihan akan dipaparkan sebentar lagi.',
				placeholderText: 'Taip soalan anda atau taip "menu"',
				mainColor: '#c41e3a',
			}, conf, {
				chatServer: conf.chatServer || @json(url('botman')),
				userId: conf.userId || @json($chat_id ?? ''),
			});
		})();
	</script>

	<script src="{{ asset('packages/botman/build/js/chat.js') }}"></script>

	<script>
		$(document).ready(function() {
			$("#circle-button").on("click", function() {
				$(this).toggleClass("active");
				var icon = $(this).find(".circle-button-icon i");
				icon.toggleClass("fa-paperclip fa-times");
			});

			document.getElementById('fileApp').innerHTML =
				'<div><input style="display:none" type="file" id="fileInput" accept=".jpeg,.jpg,.png" /></div>';

			const fileInput = document.querySelector("#fileInput");
			var file_type;
			var files;

			$("#open-folder").on("click", function() {
				file_type = "image";
				fileInput.click();
			});

			$("#fileInput").on("change", function(e) {
				files = e.target.files;
				if (files.length > 0) {
					$("#view-file-name").text(files[0]["name"]);
					sendFile(files[0], file_type);
				}
			});

			function sendFile(file, filetype) {
				var form = new FormData();
				form.append("driver", "web");
				form.append("attachment", filetype);
				form.append("interactive", 0);
				form.append("file", file);
				form.append("userId", @json($chat_id ?? ''));

				var csrfToken = $('meta[name="csrf-token"]').attr('content') || @json(csrf_token());
				form.append("_token", csrfToken);

				$.ajax({
					url: @json(url('botman')),
					method: "POST",
					headers: {
						'X-CSRF-TOKEN': csrfToken,
						'X-Requested-With': 'XMLHttpRequest'
					},
					processData: false,
					contentType: false,
					data: form
				}).done(function(response) {
					files = null;
					$("#fileInput").val(null);
					$("#view-file-name").text("");
					try {
						response = typeof response === 'string' ? JSON.parse(response) : response;
						window.parent.postMessage(response, '*');
					} catch (e) {
						window.parent.postMessage({
							status: 200,
							messages: [{ text: 'File uploaded successfully' }]
						}, '*');
					}
				}).fail(function(xhr) {
					if (xhr.status === 419) {
						alert('Sesi telah tamat. Sila muat semula halaman dan cuba lagi.');
					} else {
						alert('Ralat semasa memuat naik fail. Sila cuba lagi.');
					}
				});
			}
		});

		window.addEventListener('load', function() {
			var conf = window.botmanWidget || {};
			var userText = document.getElementById("userText");
			var messageArea = document.getElementById("messageArea");

			if (userText) {
				userText.setAttribute("autocomplete", "off");
			}

			function scrollChatToBottom() {
				if (messageArea) {
					messageArea.scrollTop = messageArea.scrollHeight;
				}
			}

			function setupChatLayout() {
				messageArea = document.getElementById("messageArea");
				if (!messageArea || !messageArea.parentElement) {
					return false;
				}

				document.body.style.display = 'flex';
				document.body.style.flexDirection = 'column';

				var botmanRoot = document.getElementById('botmanChatRoot');
				if (botmanRoot) {
					botmanRoot.style.flex = '1 1 auto';
					botmanRoot.style.display = 'flex';
					botmanRoot.style.flexDirection = 'column';
					botmanRoot.style.minHeight = '0';
					botmanRoot.style.width = '100%';
				}

				var chatRoot = messageArea.parentElement;
				chatRoot.style.flex = '1 1 auto';
				chatRoot.style.display = 'flex';
				chatRoot.style.flexDirection = 'column';
				chatRoot.style.minHeight = '0';
				chatRoot.style.overflow = 'hidden';
				chatRoot.style.width = '100%';

				scrollChatToBottom();
				return true;
			}

			var labelToValue = {
				'Ya': '1',
				'Tidak': '0',
				'Ya, ada lampiran': '1',
				'Tidak, terus hantar': '0',
				'Panduan pengguna': '1',
				'Panduan (daftar & log masuk)': '1',
				'Semak status permohonan': '2',
				'Hantar aduan': '3',
				'Soalan lazim (FAQ)': '4',
				'Panduan': '1',
				'Semak Status': '2',
				'Aduan': '3'
			};

			function findChatList() {
				return document.querySelector('#messageArea ol.chat, #messageArea .chat, ol.chat');
			}

			function appendBotmanMessages(payload) {
				var messages = (payload && payload.messages) || [];
				var ol = findChatList();
				if (!ol || !messages.length) {
					return false;
				}

				messages.forEach(function(m) {
					if (!m || m.type === 'typing' || m.type === 'typing_indicator') {
						return;
					}
					var li = document.createElement('li');
					li.className = 'chatbot from-bot';
					var inner = document.createElement('div');
					inner.className = 'msg';
					var textWrap = document.createElement('div');
					textWrap.innerHTML = m.text || '';
					inner.appendChild(textWrap);

					if (m.actions && m.actions.length) {
						var actionWrap = document.createElement('div');
						m.actions.forEach(function(action) {
							var btn = document.createElement('div');
							btn.className = 'btn btn-botman-action';
							btn.textContent = action.text || '';
							if (action.value != null) {
								btn.setAttribute('data-value', String(action.value));
							}
							actionWrap.appendChild(btn);
						});
						inner.appendChild(actionWrap);
					}

					li.appendChild(inner);
					ol.appendChild(li);
				});

				scrollChatToBottom();
				return true;
			}

			/** Same code path as typing "hi" — BotMan widget listens for this postMessage. */
			function triggerWelcomeMenuViaWidget() {
				window.postMessage({
					method: 'whisper',
					params: ['__welcome__']
				}, '*');
			}

			function parseBotmanResponseBody(text) {
				try {
					return JSON.parse(text);
				} catch (e) {
					var start = text.indexOf('{"status"');
					if (start === -1) {
						start = text.indexOf('{');
					}
					var end = text.lastIndexOf('}');
					if (start >= 0 && end > start) {
						return JSON.parse(text.slice(start, end + 1));
					}
					throw e;
				}
			}

			function showBotmanReplyMessages(data) {
				var messages = (data && data.messages) || [];

				messages.forEach(function(m) {
					if (!m || m.type === 'typing_indicator') {
						return;
					}
					if (m.type === 'text' && m.text) {
						window.postMessage({
							method: 'sayAsBot',
							params: [m.text]
						}, '*');
					} else if (m.type === 'actions') {
						appendBotmanMessages({
							messages: [m]
						});
					}
				});

				return messages.length > 0;
			}

			function postInteractiveReply(value, sourceBtn) {
				if (!value || !conf.chatServer) {
					return;
				}

				var form = new FormData();
				form.append('driver', 'web');
				form.append('userId', conf.userId || '');
				form.append('message', value);
				form.append('value', value);
				form.append('interactive', '1');

				if (sourceBtn) {
					sourceBtn.style.opacity = '0.55';
					sourceBtn.style.pointerEvents = 'none';
				}

				fetch(conf.chatServer, {
					method: 'POST',
					body: form,
					credentials: 'same-origin',
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				}).then(function(res) {
					return res.text().then(function(text) {
						var data = parseBotmanResponseBody(text);
						if (!res.ok) {
							throw new Error('HTTP ' + res.status);
						}
						return data;
					});
				}).then(function(data) {
					if (sourceBtn) {
						var actionBlock = sourceBtn.parentElement;
						if (actionBlock) {
							actionBlock.style.display = 'none';
						}
					}
					showBotmanReplyMessages(data);
					try {
						window.parent.postMessage(data, '*');
					} catch (e) {}
				}).catch(function() {
					if (sourceBtn) {
						sourceBtn.style.opacity = '';
						sourceBtn.style.pointerEvents = '';
					}
					alert('Gagal menghantar pilihan. Sila muat semula chat dan cuba lagi.');
				});
			}

			function resolveButtonValue(btn) {
				if (btn.getAttribute('data-value')) {
					return btn.getAttribute('data-value');
				}
				var label = (btn.textContent || '').trim();
				return labelToValue[label] || '';
			}

			document.body.addEventListener('click', function(ev) {
				var btn = ev.target.closest('.chat .msg div.btn');
				if (!btn) {
					return;
				}
				var value = resolveButtonValue(btn);
				if (!value) {
					return;
				}
				ev.preventDefault();
				ev.stopPropagation();
				postInteractiveReply(value, btn);
			}, true);

			var welcomeMenuSent = false;

			function trySendWelcomeMenu() {
				if (welcomeMenuSent) {
					return true;
				}
				if (!setupChatLayout() || !findChatList()) {
					return false;
				}
				welcomeMenuSent = true;
				setTimeout(triggerWelcomeMenuViaWidget, 350);
				return true;
			}

			if (!trySendWelcomeMenu()) {
				var welcomeTimer = setInterval(function() {
					if (trySendWelcomeMenu()) {
						clearInterval(welcomeTimer);
					}
				}, 150);
				setTimeout(function() {
					clearInterval(welcomeTimer);
					if (!welcomeMenuSent) {
						trySendWelcomeMenu();
					}
				}, 8000);
			}

			if (window.MutationObserver) {
				new MutationObserver(function() {
					scrollChatToBottom();
				}).observe(document.body, {
					childList: true,
					subtree: true
				});
			}
		});
	</script>
</body>

</html>
