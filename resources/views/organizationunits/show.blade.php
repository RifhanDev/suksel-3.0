
@extends('layouts.default')
@section('content')

	<h2 class="tender-title">
		{{ $organizationunit->name }}
		
		@if(Auth::check())
			<div class="btn-group pull-right">
				@if(Auth()->user()->hasRole('Admin'))
					<a href="{{ asset('agencies/'.$organizationunit->id.'/edit') }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Kemaskini Agensi</a>
				@endif
				@if(Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
					<a href="{{ asset('tenders/create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Tender / Sebut Harga</a>
				@endif
			</div>
		@endif
	</h2>

	@if(Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
		<div class="row">
			<div class="col-lg-4">
				<div class="alert @if(Request::get('state') == 1) alert-success @else alert-warning @endif">
					<a href="" class="btn btn-sm @if(Request::get('state') == 1) btn-success @else btn-warning @endif">{{$count_1}}</a> tender / sebut harga belum disiarkan. 
				</div>
			</div>
			
			<div class="col-lg-4">
				<div class="alert @if(Request::get('state') == 2) alert-success @else alert-warning @endif">
					<a href="" class="btn btn-sm @if(Request::get('state') == 2) btn-success @else btn-warning @endif">{{$count_2}}</a> tender / sebut harga belum di umumkan carta tender. 
				</div>
			</div>
			
			<div class="col-lg-4">
				<div class="alert @if(Request::get('state') == 3) alert-success @else alert-warning @endif">
					<a href="" class="btn btn-sm @if(Request::get('state') == 3) btn-success @else btn-warning @endif">{{$count_3}}</a> tender / sebut harga belum diumumkan penender berjaya. 
				</div>
			</div>
		</div>
	@endif

	<ul class="nav nav-tabs">
		<li class="active"><a href="{{action('OrganizationUnitsController@show', $organizationunit->id)}}">Tender &amp; Sebut Harga</a></li>
		<li><a href="{{action('OrganizationUnitsController@prices', $organizationunit->id)}}">Carta Tender</a></li>
		<li><a href="{{action('OrganizationUnitsController@results', $organizationunit->id)}}">Penender Berjaya</a></li>
		<li class="pull-right"><a href="{{action('OrganizationUnitsController@news', $organizationunit->id)}}">Berita</a></li>
	</ul>

	<div class="row">
		<div class="col-md-2">
			<ul class="nav nav-pills nav-stacked">
				<li @if(!Request::get('type')) class="active" @endif><a href="{{action('OrganizationUnitsController@show', $organizationunit->id)}}" role="tab">Semua</a></li>
				<li @if(Request::get('type') == 'tenders') class="active" @endif><a href="{{action('OrganizationUnitsController@show', [$organizationunit->id, 'type' => 'tenders'])}}">Tender</a></li>
				<li @if(Request::get('type') == 'quotations') class="active" @endif><a href="{{action('OrganizationUnitsController@show', [$organizationunit->id, 'type' => 'quotations'])}}">Sebut Harga</a></li>
			</ul>
		</div>
		
		<div class="col-md-10">
			<table class="DT-show table table-hover table-compact" data-path="{{ $path }}">
				<thead class="bg-blue-selangor">
					<tr>
						<th>No / Tajuk</th>
						<th>Kod Bidang</th>
						<th>Tarikh Jual</th>
						<th>Tarikh Tutup</th>
						<th>Harga Dokumen</th>
						@if(Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
							<th>Status</th>
						@endif
						@if(Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
							<th>Jadual</th>
							<th>&nbsp;</th>
						@endif
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script>

        var canUpdate = {!! json_encode(Auth::check() && App\Tender::canShowUpdate($organizationunit->id)) !!};

		$('.DT-show').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');

		  	if (canUpdate) {
			  	var columns = [
		            { data: 'name', name: 'name' },
		            { data: 'codes', name: 'codes' },
		            { data: 'document_start_date', name: 'document_start_date' },
		            { data: 'submission_datetime', name: 'submission_datetime' },
		            { data: 'price', name: 'price' },
		            { data: 'actions', name: 'actions' },
		            { data: 'report', name: 'report' },
		      	];
		  	}
		  	else {
		  		var columns = [
		            { data: 'name', name: 'name' },
		            { data: 'codes', name: 'codes' },
		            { data: 'document_start_date', name: 'document_start_date' },
		            { data: 'submission_datetime', name: 'submission_datetime' },
		            { data: 'price', name: 'price' },
		      	];
		  	}

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
