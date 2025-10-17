
@extends('layouts.default')

@section('content')
	<div class="row">
    	<div class="col-lg-9">
        	<h4><i class="fa fa-bar-chart-o"></i> Carta Tender</h4>
        	<ul class="nav nav-tabs nav-justified" role="tablist">
            <li @if(!Request::get('type')) class="active" @endif><a href="{{ asset('prices') }}">Semua</a></li>
            <li @if(Request::get('type') == 'tenders') class="active" @endif><a href="{{ action('HomeController@prices', ['type' => 'tenders']) }}">Tender</a></li>
            <li @if(Request::get('type') == 'quotations') class="active" @endif><a href="{{ action('HomeController@prices', ['type' => 'quotations']) }}">Sebut Harga</a></li>
        	</ul>

        	<table class="DT-index table table-hover table-compact" data-path="{{ $path }}">
            <thead class="bg-blue-selangor">
                	<tr>
                    	<th class="col-lg-2">Tarikh Tutup</th>
                    	<th class="col-lg-3">Petender</th>
                    	<th>No / Tajuk</th>
                	</tr>
            </thead>
            <tbody>
            </tbody>
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

	<script type="text/javascript">
		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');
		  	var DT = target.DataTable({
		    	ajax: path,
			  	columns: [
		            { data: 'submission_datetime', name: 'submission_datetime' },
		            { data: 'organization_unit_id', name: 'organization_unit_id' },
		            { data: 'name', name: 'name' },
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
