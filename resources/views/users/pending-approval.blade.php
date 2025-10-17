@extends('layouts.default')
@section('content')
	<h2>Senarai Pengguna Belum Diluluskan</h2>
	<br>
	<table data-path="{{ route('users.pending-approval') }}" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Nama</th>
				<th>Alamat Emel</th>
				<th>Agensi / Syarikat</th>
				<th>Status</th>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<br>
	<div class="well">
		<a href="{{ asset('users/create')}}" class="btn btn-default">Masukan Pengguna Baru</a>
	</div>
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script>

		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');

		  	var DT = target.DataTable({
		    	ajax: path,
		    	columns: [
		            { data: 'name', name: 'name' },
		            { data: 'email', name: 'email' },
		            { data: 'organization_unit_id', name: 'organization_unit_id' },
		            { data: 'confirmed', name: 'confirmed' },
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