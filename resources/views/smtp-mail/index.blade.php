@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Email SMTP</h3>
			<p class="text-muted small m-0">Pengurusan tetapan konfigurasi pelayan email SMTP.</p>
		</div>
		@if (App\Models\SmtpMails::canCreate())
			<div>
				<a href="{{ route('mail-manager.smtp-setting.create') }}" class="btn-form btn-form-create d-flex align-items-center gap-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="12" y1="5" x2="12" y2="19"></line>
						<line x1="5" y1="12" x2="19" y2="12"></line>
					</svg>
					Tambah Email SMTP
				</a>
			</div>
		@endif
	</div>

	<!-- Table -->
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
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Email SMTP</h3>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table class="DT-index table table-hover align-middle mb-0 w-100" data-path="{{ route('mail-manager.smtp-setting.index') }}">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width: 50px;">No.</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Server</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Port</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Username</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Limit Email Sehari</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width: 220px;">Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	{{-- <link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet"> --}}
	{{-- <script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script> --}}
	<script type="text/javascript">
		var target = $('.DT-index');
		var path = target.data('path');

		let table = $('.DT-index').DataTable({
			processing: true,
			serverSide: true,
			ajax: path,
			columns: [{
					data: 'id',
					name: 'id'
				},
				{
					data: 'mail_server',
					name: 'mail_server'
				},
				{
					data: 'mail_port',
					name: 'mail_port'
				},
				{
					data: 'mail_username',
					name: 'mail_username'
				},
				{
					data: 'mail_message_ratelimit',
					name: 'mail_message_ratelimit'
				},
				{
					data: 'actions',
					name: 'actions'
				},
			],
			stateSave: true,
			language: {
				// "url": "{{ asset('custom_library/dataTables/lang/ms.json') }}"
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
				},
				oAria: {
					sSortAscending: ": diaktifkan kepada susunan lajur menaik",
					sSortDescending: ": diaktifkan kepada susunan lajur menurun"
				}
			},
			autoWidth: false,
			columnDefs: [
				{
					searchable: false,
					orderable: false,
					targets: 0,
					width: '50px',
				},
				{
					searchable: false,
					orderable: false,
					targets: 5,
					width: '220px',
				},
			],
			order: [
				[1, 'asc']
			],
		});

		table.on('order.dt search.dt', function() {
			let i = 1;
			table.cells(null, 0, {
				search: 'applied',
				order: 'applied'
			}).every(function(cell) {
				this.data(i++);
			});
		}).draw();
	</script>
@endsection
