@extends('layouts.default')
@section('content')
	<h2>Kategori Agensi</h2>
	<hr>
	<table data-path="/organizationtypes" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Nama</th>
				<th>Susunan</th>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<br>
	@include('organizationtypes.actions-footer', ['is_list' => true])
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');
		  	var DT = target.DataTable({
		    	ajax: path,
    	    	columns: [
		            { data: 'name', name: 'name' },
		            { data: 'sort_no', name: 'sort_no' },
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
	</script>
	
@endsection