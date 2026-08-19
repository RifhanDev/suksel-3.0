@extends('layouts.v3.master')

@section('styles')
	<style>
		.content-container > .alert-success { display: none !important; }
	</style>
@endsection

@section('content')
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai API Token</h3>
			<p class="text-muted small m-0">Setiap klien mempunyai token Sanctum sendiri. Token boleh dilihat dan disalin dari senarai ini.</p>
		</div>
		<div>
			<a href="{{ route('apitoken.create') }}" class="btn-form btn-form-create d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
				Klien &amp; Token Baharu
			</a>
		</div>
	</div>

	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-linecap="round" stroke-linejoin="round">
						<line x1="8" y1="6" x2="21" y2="6"></line>
						<line x1="8" y1="12" x2="21" y2="12"></line>
						<line x1="8" y1="18" x2="21" y2="18"></line>
						<line x1="3" y1="6" x2="3.01" y2="6"></line>
						<line x1="3" y1="12" x2="3.01" y2="12"></line>
						<line x1="3" y1="18" x2="3.01" y2="18"></line>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Klien API (Sanctum)</h3>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table data-path="{{ route('apitoken.index') }}" class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4">Klien</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Agensi</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Token</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3">Status</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Tarikh Dijana</th>
							<th class="text-uppercase text-muted small fw-bold py-3 pe-4">Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		var FLASH_SUCCESS = @json(session('success'));
		var FLASH_TOKEN = @json(session('plain_text_token'));
		var FLASH_CLIENT = @json(session('plain_text_client'));

		function copyText(text) {
			text = String(text || '');
			if (!text) {
				return Promise.reject(new Error('Tiada token'));
			}

			if (navigator.clipboard && window.isSecureContext) {
				return navigator.clipboard.writeText(text);
			}

			return new Promise(function (resolve, reject) {
				var area = document.createElement('textarea');
				area.value = text;
				area.setAttribute('readonly', '');
				area.style.position = 'fixed';
				area.style.top = '0';
				area.style.left = '0';
				area.style.opacity = '0';
				document.body.appendChild(area);
				area.focus();
				area.select();
				area.setSelectionRange(0, area.value.length);

				try {
					var ok = document.execCommand('copy');
					document.body.removeChild(area);
					ok ? resolve() : reject(new Error('Salin gagal'));
				} catch (err) {
					document.body.removeChild(area);
					reject(err);
				}
			});
		}

		function showTokenSwal(title, clientName, token) {
			Swal.fire({
				title: title,
				icon: 'success',
				html: '<p class="mb-2">Token untuk <strong>' + $('<div>').text(clientName || '').html() + '</strong></p>'
					+ '<code class="d-block p-2 bg-light border rounded text-start" style="word-break:break-all;font-size:0.8rem;">'
					+ $('<div>').text(token).html()
					+ '</code>',
				confirmButtonColor: '#1e293b',
				confirmButtonText: 'Salin Token',
				showCancelButton: true,
				cancelButtonText: 'Tutup',
				cancelButtonColor: '#6c757d'
			}).then(function (result) {
				if (result.isConfirmed) {
					copyText(token).then(function () {
						Swal.fire({
							title: 'Disalin',
							text: 'Token telah disalin ke papan keratan.',
							icon: 'success',
							confirmButtonColor: '#1e293b',
							timer: 1600,
							showConfirmButton: false
						});
					}).catch(function () {
						Swal.fire({
							title: 'Tidak dapat salin',
							text: 'Sila pilih token secara manual dan salin.',
							icon: 'error',
							confirmButtonColor: '#1e293b'
						});
					});
				}
			});
		}

		if (FLASH_TOKEN) {
			showTokenSwal('Token Berjaya Dijana', FLASH_CLIENT, FLASH_TOKEN);
		} else if (FLASH_SUCCESS) {
			Swal.fire({
				title: 'Berjaya',
				text: FLASH_SUCCESS,
				icon: 'success',
				confirmButtonColor: '#1e293b'
			});
		}

		$(document).on('click', '.js-swal-confirm', function () {
			var btn = $(this);
			var form = btn.closest('form');
			Swal.fire({
				title: btn.data('title') || 'Sahkan tindakan?',
				text: btn.data('text') || '',
				icon: btn.data('icon') || 'question',
				showCancelButton: true,
				confirmButtonColor: '#1e293b',
				cancelButtonColor: '#6c757d',
				confirmButtonText: btn.data('confirm') || 'Ya, Teruskan',
				cancelButtonText: 'Batal'
			}).then(function (result) {
				if (result.isConfirmed) {
					form.trigger('submit');
				}
			});
		});

		$(document).on('click', '.btn-copy-token', function () {
			var btn = $(this);
			var text = btn.closest('div').find('.api-token-value').text()
				|| btn.attr('data-token')
				|| '';
			copyText($.trim(text)).then(function () {
				btn.text('Disalin');
				setTimeout(function () { btn.text('Salin'); }, 1500);
			}).catch(function () {
				Swal.fire({
					title: 'Tidak dapat salin',
					text: 'Sila pilih token secara manual dan salin.',
					icon: 'error',
					confirmButtonColor: '#1e293b'
				});
			});
		});

		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			target.DataTable({
				ajax: path,
				columns: [
					{ data: 'name', name: 'name' },
					{ data: 'agency_name', name: 'agency_name', orderable: false },
					{ data: 'token_status', name: 'token_status', orderable: false },
					{ data: 'status', name: 'status' },
					{ data: 'created_at', name: 'created_at' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: false }
				],
				serverSide: true,
				stateSave: true,
				language: {
					sEmptyTable: "Tiada data",
					sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
					sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
					sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Papar _MENU_ rekod",
					sLoadingRecords: "Diproses...",
					sProcessing: "Sedang diproses...",
					sSearch: "Carian:",
					sZeroRecords: "Tiada padanan rekod yang dijumpai.",
					oPaginate: {
						sFirst: "Pertama",
						sPrevious: "Sebelum",
						sNext: "Kemudian",
						sLast: "Akhir"
					}
				},
				aaSorting: []
			});
		});
	</script>
@endsection
