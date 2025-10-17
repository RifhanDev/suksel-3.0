@extends('layouts.default')
@section('content')
	<h2>Tetapan Pembayaran</h2>
	<hr>
	<table data-path="/gateways" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Agensi</th>
				<th>Saluran</th>
				<th>ID Merchant</th>
				<th>Versi</th>
				<th>Active</th>
				<th>Utama</th>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	
	@if(App\Gateway::canCreate())
		<div class="well">
		<a href="{{ asset('gateways/create') }}" class="btn btn-primary">Masukkan Tetapan Baru</a>
		</div>
	@endif
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script src="{{ asset('js/news.js') }}"></script>

	<script>

		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');

		  	var DT = target.DataTable({
		    	ajax: path,
		    	columns: [
		            { data: 'organization_unit_id', name: 'organization_unit_id' },
		            { data: 'type', name: 'type' },
		            { data: 'merchant_code', name: 'merchant_code' },
		            { data: 'version', name: 'version' },
		            { data: 'active', name: 'active' },
		            { data: 'default', name: 'default' },
		            { data: 'actions', name: 'actions' }
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

	</script>
	
@endsection