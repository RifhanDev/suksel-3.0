@extends('layouts.default')
@section('content')

    	<h2>
        @if(isset($vendor))
            {{ $vendor->name }}
        @else
            Syarikat
        @endif : Senarai Hitam
    	</h2>
    	<table data-path="{{ $ajax_url }}" class="DT-index table table-striped table-hover table-bordered">
        	<thead class="bg-blue-selangor">
            <tr>
					@if(!isset($vendor))<th>Syarikat</th>@endif
					<th>Agensi</th>
					<th>Sebab</th>
					<th>Tarikh Mula</th>
					<th>Tarikh Tamat</th>
					<th>Status</th>
					<th width ="200px">&nbsp;</th>
         	</tr>
        	</thead>
        	<tbody></tbody>
    	</table>

    	@if(isset($vendor))
	    	<div class="well">
				@if(App\VendorBlacklist::canCreate())
					<a href="{{ route('vendor.blacklists.create', $vendor->id) }}" class="btn btn-primary">Masukkan Senarai Hitam Baru</a>
				@endif
				
				@if($vendor->canShow())
					<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
				@endif
				<div class="clearfix"></div>
	    	</div>
    	@endif
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');

		  	if (path.includes('/vendor')) {
			  	var columns = [
	            { data: 'organization_unit_id', name: 'organization_unit_id' },
	            { data: 'reason', name: 'reason' },
	            { data: 'start', name: 'start' },
	            { data: 'end', name: 'end' },
	            { data: 'status', name: 'status' },
	            { data: 'actions', name: 'actions' },
		      ];
		  	}

		  	else {
		  		
		  		var columns = [
		            { data: 'vendor_id', name: 'vendor_id' },
		            { data: 'organization_unit_id', name: 'organization_unit_id' },
		            { data: 'reason', name: 'reason' },
		            { data: 'start', name: 'start' },
		            { data: 'end', name: 'end' },
		            { data: 'status', name: 'status' },
		            { data: 'actions', name: 'actions' },
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