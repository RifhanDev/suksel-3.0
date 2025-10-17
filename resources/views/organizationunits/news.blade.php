@extends('layouts.default')
@section('content')
	<h2 class="tender-title">
		{{ $organizationunit->name }}
	
		@if(Auth::check())
			<div class="btn-group pull-right">
				@if(Auth::user()->hasRole('Admin'))
					<a href="{{ asset('agencies/'.$organizationunit->id.'/edit') }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Kemaskini Agensi</a>
				@endif
				@if(Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
					<a href="{{ asset('tenders/create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Tender / Sebut Harga</a>
				@endif
			</div>
		@endif
	</h2>
	
	<ul class="nav nav-tabs">
		<li><a href="{{ action('OrganizationUnitsController@show', $organizationunit->id) }}">Tender &amp; Sebut Harga</a></li>
		<li><a href="{{ action('OrganizationUnitsController@prices', $organizationunit->id) }}">Carta Tender</a></li>
		<li><a href="{{ action('OrganizationUnitsController@results', $organizationunit->id) }}">Penender Berjaya</a></li>
		<li class="pull-right active"><a href="{{action('OrganizationUnitsController@news', $organizationunit->id) }}">Berita</a></li>
	</ul>
	
	<table class="DT-news table table-bordered table-striped" data-path="/agencies/{{ $organizationunit->id }}/news">
		<thead class="bg-blue-selangor">
			<tr>
				<th width="100">Tarikh</th>
				<th>Berita</th>
				<th width="100">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-news').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');
		  	var DT = target.DataTable({
		    	ajax: path,
		    	columns : [
		         { data: 'created_at', name: 'created_at' },
		         { data: 'title', name: 'title' },
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
