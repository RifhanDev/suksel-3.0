@extends('layouts.default')
@section('content')
	@php
		
	@endphp

	<h2>Status Transaksi {{isset($subtitle2) ? ': ' . $subtitle2 : ''}}</h2>
	<br>
	@include('transactions._snaps_trans_status')

	<h2>Senarai Transaksi {{isset($subtitle) ? ': ' . $subtitle : ''}}</h2>
	<br>
	@include('transactions._snaps')
	<hr>
	<table data-path ="{{action('TransactionsController@index')}}?state={{isset($transaction_type) ? $transaction_type : ''}}&status={{isset($transaction_status) ? $transaction_status : ''}}" class="DT-index table table-striped table-hover table-bordered">
      <thead class="bg-blue-selangor">
            <tr>
					<th>Tarikh &amp; Masa</th>
					<th>Nama Syarikat</th>
					<th>No Transaksi</th>
					<th>No Rujukan Gateway</th>
					<th>No Resit</th>
					<th>Jenis</th>
					<th>Saluran</th>
					<th>Jumlah</th>
					<th>Status</th>
					<th class ="col-lg-1">&nbsp;</th>
            </tr>
		</thead>
		<tbody></tbody>
	</table>
@endsection


@section('scripts')

	{{-- <script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');
		  	var DT = target.DataTable({
		    	ajax: path,
    	    	columns: [
		            { data: 'created_at', name: 'created_at' },
		            { data: 'name', name: 'vendors.name' },
		            { data: 'number', name: 'number' },
		            { data: 'gateway_reference', name: 'gateway_reference' },
		            { data: 'no_resit', name: 'no_resit' },
		            { data: 'type', name: 'type' },
		            { data: 'method', name: 'method' },
		            { data: 'amount', name: 'amount' },
		            { data: 'status', name: 'status' },
		            { data: 'actions', name: 'actions' },
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
		      },
		      oAria: {
		        	sSortAscending: ": diaktifkan kepada susunan lajur menaik",
		        	sSortDescending: ": diaktifkan kepada susunan lajur menurun"
		      }
		   },
		   aaSorting: []
			});
		});
	</script> --}}
	
	<link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet">
	<script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script>

	<script type="text/javascript">
		$(document).ready(function () {
			updateFpxCount();

			// Update count every second
			setInterval(function() {
				updateFpxCount1();
			}, 20000); // Do this every 20 seconds

			
			setInterval(function() {
				updateFpxCount2();
			}, 30000); // Do this every 30 seconds


			// Check payment transaction status every 10 minute
			setInterval(function() {
				updateFpxRequery();
			}, 240000); // Do this every 5 minutes
		});

		function updateFpxCount()
		{
			updatePendingTrans();
			updateSuccessTrans();
			updatePendingAuthorizationTrans();
			updateFailedTrans();
			updateDeclinedTrans();
			updateSubscriptionTrans();
			updatePurchaseTrans();
			updateTotalTrans();
		}

		function updateFpxCount1()
		{
			updatePendingTrans();
			updateSuccessTrans();
			updateFailedTrans();
		}

		function updateFpxCount2()
		{
			updatePendingAuthorizationTrans();
			updateDeclinedTrans();
			updateSubscriptionTrans();
			updatePurchaseTrans();
			updateTotalTrans();
		}

		function updateTotalTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { type: "total"},
				success: function (response) {
					$("#total_trans_count").html(response.total_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateSubscriptionTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { type: "subscribe"},
				success: function (response) {
					$("#subscribe_trans_count").html(response.subscribe_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updatePurchaseTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { type: "purchase"},
				success: function (response) {
					$("#purchase_trans_count").html(response.purchase_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateSuccessTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { status: "success"},
				success: function (response) {
					$("#success_trans_count").html(response.success_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updatePendingTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { status: "pending"},
				success: function (response) {
					$("#pending_trans_count").html(response.pending_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateDeclinedTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { status: "declined"},
				success: function (response) {
					$("#declined_trans_count").html(response.declined_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateFailedTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { status: "failed"},
				success: function (response) {
					$("#failed_trans_count").html(response.failed_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updatePendingAuthorizationTrans()
		{
			$.ajax({
				type: "POST",
				url: "{{ route('updateFpxCount') }}",
				data: { status: "pending_authorization"},
				success: function (response) {
					$("#pending_authorization_trans_count").html(response.pending_authorization_trans_count.toLocaleString('en-US'));
				}
			});
		}

		function updateFpxRequery()
		{
			$.ajax({
				type: "GET",
				async: false,
				url: "{{ route('fpx_queue') }}",
				success: function (response) {
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
			columns: [
				{ data: 'created_at', name: 'created_at' },
				{ data: 'name', name: 'vendors.name' },
				{ data: 'number', name: 'number' },
				{ data: 'gateway_reference', name: 'gateway_reference' },
				{ data: 'no_resit', name: 'no_resit' },
				{ data: 'type', name: 'type' },
				{ data: 'method', name: 'method' },
				{ data: 'amount', name: 'amount' },
				{ data: 'status', name: 'status' },
				{ data: 'actions', name: 'actions' },
			],
			serverSide: true,
			stateSave: true,
			language: {
				"url" : "{{ asset('custom_library/dataTables/lang/ms.json') }}"
			}
		});

	</script>

@endsection


