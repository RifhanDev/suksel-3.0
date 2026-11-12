@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
	<style>
		/* 10 columns: slightly tighter cells so Tindakan fits without a sideways scroll */
		table.dataTable.DT-index thead th,
		table.dataTable.DT-index tbody td {
			padding-left: 8px;
			padding-right: 8px;
		}

		.DT-index .txn-company {
			max-width: 240px;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
		}

		.DT-index .txn-sub {
			font-size: 0.75rem;
			color: #64748b;
			white-space: nowrap;
		}
	</style>
@endsection

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">
				Senarai Transaksi {{ isset($subtitle) ? ': ' . $subtitle : '' }}
			</h3>
			<p class="text-muted small m-0">Pengurusan transaksi Sistem e-Perolehan Selangor</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
			<div class="d-flex align-items-center gap-2">
				<span class="badge bg-light text-dark border">TARIKH</span>
				<span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
			</div>
		</div>
	</div>

	<!-- Transaction Status Stats -->
	<div class="mb-2">
		<h5 class="fw-bold text-dark mb-3">Status Transaksi {{ isset($subtitle2) ? ': ' . $subtitle2 : '' }}</h5>
	</div>
	@include('transactions._snaps_trans_status')

	<!-- Transaction Type Stats -->
	<div class="mb-2">
		<h5 class="fw-bold text-dark mb-3">Jenis Transaksi</h5>
	</div>
	@include('transactions._snaps')

	<!-- Main Table Card -->
	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-linecap="round" stroke-linejoin="round">
						<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
						<line x1="1" y1="10" x2="23" y2="10"></line>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Transaksi</h3>
			</div>
		</div>

		<!-- Table Body -->
		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table
					data-path="{{ action('TransactionsController@index') }}?state={{ isset($transaction_type) ? $transaction_type : '' }}&status={{ isset($transaction_status) ? $transaction_status : '' }}"
					class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-3">Tarikh</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Syarikat</th>
							<th class="text-uppercase text-muted small fw-bold py-3">No. Transaksi / Rujukan</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Jenis / Saluran</th>
							<th class="text-uppercase text-end text-muted small fw-bold py-3">Jumlah</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3">Status</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4">Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection


@section('scripts')
	<link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet">
	<script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			updateFpxCount();

			// Update count every 20 seconds
			setInterval(function() {
				updateFpxCount1();
			}, 20000);

			// Update count every 30 seconds
			setInterval(function() {
				updateFpxCount2();
			}, 30000);

			// Check payment transaction status every 4 minutes
			setInterval(function() {
				updateFpxRequery();
			}, 240000);
		});

		// Sebelum ini setiap kad statistik menembak permintaannya sendiri - lapan
		// permintaan pada muatan halaman, setiap satu COUNT ke atas jadual transaksi
		// yang sama. Satu permintaan kini memulangkan kesemuanya sekali gus.
		function updateFpxCount() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					type: "custom_all"
				},
				success: function(response) {
					var cards = {
						success_trans_count: response.success_trans_count,
						pending_trans_count: response.pending_trans_count,
						pending_authorization_trans_count: response.pending_authorization_trans_count,
						failed_trans_count: response.failed_trans_count,
						declined_trans_count: response.declined_trans_count,
						subscribe_trans_count: response.subscribe_trans_count,
						purchase_trans_count: response.purchase_trans_count,
						total_trans_count: response.total_trans_count
					};

					Object.keys(cards).forEach(function(id) {
						var value = cards[id];
						if (value === undefined || value === null) return;
						$("#" + id).html(Number(value).toLocaleString("en-US"));
					});
				}
			});
		}

		// Penyegaran berkala menggunakan panggilan tunggal yang sama; tiada sebab
		// untuk memecahkannya kepada subset apabila satu permintaan memulangkan
		// semua kiraan.
		function updateFpxCount1() {
			updateFpxCount();
		}

		function updateFpxCount2() {
			updateFpxCount();
		}

		function updateTotalTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					type: "total"
				},
				success: function(response) {
					$("#total_trans_count").html(response.total_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateSubscriptionTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					type: "subscribe"
				},
				success: function(response) {
					$("#subscribe_trans_count").html(response.subscribe_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updatePurchaseTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					type: "purchase"
				},
				success: function(response) {
					$("#purchase_trans_count").html(response.purchase_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateSuccessTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					status: "success"
				},
				success: function(response) {
					$("#success_trans_count").html(response.success_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updatePendingTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					status: "pending"
				},
				success: function(response) {
					$("#pending_trans_count").html(response.pending_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateDeclinedTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					status: "declined"
				},
				success: function(response) {
					$("#declined_trans_count").html(response.declined_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateFailedTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					status: "failed"
				},
				success: function(response) {
					$("#failed_trans_count").html(response.failed_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updatePendingAuthorizationTrans() {
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: {
					status: "pending_authorization"
				},
				success: function(response) {
					$("#pending_authorization_trans_count").html(response.pending_authorization_trans_count
						.toLocaleString('en-US'));
				}
			});
		}

		function updateFpxRequery() {
			$.ajax({
				type: "GET",
				// async: false menyekat utas UI pelayar sehingga permintaan ini
				// selesai, membekukan halaman pada setiap penyegaran.
				url: "{{ route('fpx_queue') }}",
				success: function(response) {
					// console.log(response);
					// updateFpxCount();
				}
			});
		}
	</script>

	<script type="text/javascript">
		var target = $('.DT-index');
		var path = target.data('path');
		var esc = function(v) { return $('<div>').text(v || '').html(); };

		let table = $('.DT-index').DataTable({
			processing: true,
			serverSide: true,
			ajax: path,
			columns: [{
					data: 'created_at',
					name: 'created_at',
					className: 'text-nowrap ps-3',
					render: function(data) {
						var parts = (data || '').split(' ');
						return '<div class="fw-semibold text-dark">' + esc(parts[0]) + '</div><div class="txn-sub">' + esc(parts[1]) + '</div>';
					}
				},
				{
					data: 'name',
					name: 'vendors.name',
					render: function(data) {
						return '<div class="txn-company fw-semibold text-dark" title="' + esc(data) + '">' + esc(data) + '</div>';
					}
				},
				{
					data: 'number',
					name: 'number',
					render: function(data, type, row) {
						var html = '<div class="fw-semibold text-dark text-nowrap">' + esc(data) + '</div>';
						if (row.gateway_reference) html += '<div class="txn-sub">Gateway: ' + esc(row.gateway_reference) + '</div>';
						if (row.no_resit) html += '<div class="txn-sub">Resit: ' + esc(row.no_resit) + '</div>';
						return html;
					}
				},
				{
					data: 'type',
					name: 'type',
					render: function(data, type, row) {
						return '<div class="text-dark text-nowrap">' + esc(data) + '</div><div class="txn-sub">' + esc(row.method) + '</div>';
					}
				},
				{
					data: 'amount',
					name: 'amount',
					className: 'text-end text-nowrap fw-semibold'
				},
				{
					data: 'status',
					name: 'status',
					className: 'text-center text-nowrap'
				},
				{
					data: 'actions',
					name: 'actions',
					orderable: false,
					searchable: false,
					className: 'text-center'
				},
			],
			stateSave: true,
			autoWidth: false,
			language: {
				"url": "{{ asset('custom_library/dataTables/lang/ms.json') }}"
			},
			dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
			pageLength: 25,
			responsive: true,
			order: [
				[0, 'desc']
			]
		});
	</script>
@endsection
