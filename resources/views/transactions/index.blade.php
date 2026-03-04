@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
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
	<div class="stats-card p-0">
		<div class="stats-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="stats-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-linecap="round" stroke-linejoin="round">
						<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
						<line x1="1" y1="10" x2="23" y2="10"></line>
					</svg>
				</div>
				<h3 class="stats-card-title" style="font-size: 1rem;">Senarai Transaksi</h3>
			</div>
		</div>

		<!-- Table Body -->
		<div class="stats-card-body p-2">
			<div class="table-responsive">
				<table
					data-path="{{ action('TransactionsController@index') }}?state={{ isset($transaction_type) ? $transaction_type : '' }}&status={{ isset($transaction_status) ? $transaction_status : '' }}"
					class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4">Tarikh & Masa</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Nama Syarikat</th>
							<th class="text-uppercase text-muted small fw-bold py-3">No Transaksi</th>
							<th class="text-uppercase text-muted small fw-bold py-3">No Rujukan Gateway</th>
							<th class="text-uppercase text-muted small fw-bold py-3">No Resit</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Jenis</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Saluran</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Jumlah</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Status</th>
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

		function updateFpxCount() {
			updatePendingTrans();
			updateSuccessTrans();
			updatePendingAuthorizationTrans();
			updateFailedTrans();
			updateDeclinedTrans();
			updateSubscriptionTrans();
			updatePurchaseTrans();
			updateTotalTrans();
		}

		function updateFpxCount1() {
			updatePendingTrans();
			updateSuccessTrans();
			updateFailedTrans();
		}

		function updateFpxCount2() {
			updatePendingAuthorizationTrans();
			updateDeclinedTrans();
			updateSubscriptionTrans();
			updatePurchaseTrans();
			updateTotalTrans();
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
				async: false,
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

		let table = $('.DT-index').DataTable({
			processing: true,
			serverSide: true,
			ajax: path,
			columns: [{
					data: 'created_at',
					name: 'created_at'
				},
				{
					data: 'name',
					name: 'vendors.name'
				},
				{
					data: 'number',
					name: 'number'
				},
				{
					data: 'gateway_reference',
					name: 'gateway_reference'
				},
				{
					data: 'no_resit',
					name: 'no_resit'
				},
				{
					data: 'type',
					name: 'type'
				},
				{
					data: 'method',
					name: 'method'
				},
				{
					data: 'amount',
					name: 'amount'
				},
				{
					data: 'status',
					name: 'status'
				},
				{
					data: 'actions',
					name: 'actions'
				},
			],
			stateSave: true,
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
