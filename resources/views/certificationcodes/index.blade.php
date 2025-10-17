@extends('layouts.default')
@section('content')

	<h2 class="pull-left">
		Senarai Kod Bidang @if(App\Code::typeExists(Request::get('type'))): {{ App\Code::$type[Request::get('type')] }}@endif
	</h2>
	
	<div class="dropdown pull-right">
		<a href="#" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Kategori Kod Bidang <span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-right" role="menu">
		@foreach(App\Code::$type as $type => $name)
			<li><a href="{{ route('codes.index', ['type' => $type]) }}">{{ $name }}</a></li>
		@endforeach
		</ul>
	</div>
	<div class="clearfix"></div>
	<hr>
	<table data-path="/codes<?php if(Request::get('type')) : ?>?type={{Request::get('type')}}<?php endif; ?>" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Kod</th>
				<th>Nama</th>
				<?php if(!Request::get('type')) : ?><th>Agensi / Jenis</th><?php endif; ?>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<div class="well">
		<a href="{{ asset('codes/create') }}" class="btn btn-default">Masukkan Kod Bidang Baru</a>
	</div>

@endsection
@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">

		var url = window.location.href;

		if (url.includes('?type')) {
			var columns = [
    	    			{ data: 'code', name: 'code' },
		            { data: 'name', name: 'name' },
		            { data: 'actions', name: 'actions' },
			];
		}
		else {
			var columns = [
    	    			{ data: 'code', name: 'code' },
		            { data: 'name', name: 'name' },
		            { data: 'type', name: 'type' },
		            { data: 'actions', name: 'actions' },
			];
		}

		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');
		  	var DT = target.DataTable({
		    	ajax: path,
    	    	columns: columns,
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