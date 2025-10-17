@extends('layouts.default')
@section('content')
	<div class="row">
		<div class="col-lg-9">
			<h1 class="tender-title">
				Senarai Kategori Soalan Lazim
			
				@if(Auth::user() && Auth::user()->hasRole('Admin'))
					<div class="btn-group pull-right">
						<a href="{{ asset('helps') }}" class="btn btn-sm btn-warning"><i class="fa fa-tags"></i> Soalan Lazim</a>
						<a href="{{ asset('helpcategories/create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Kategori</a>
					</div>
				@endif
			</h1>
			
			<table data-path="/helpcategories" class="DT-index table table-striped table-hover table-bordered">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th class="col-lg-3">Jumlah Soalan</th>
						<th width="200px">&nbsp;</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		
		<div class="col-lg-3">
			@include('layouts._register')
			
			@include('layouts._news')
		</div>
	</div>
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
		            { data: 'name', name: 'name' },
		            { data: 'count', name: 'count' },
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