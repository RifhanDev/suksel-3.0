@extends('layouts.default')
@section('content')

    	<h2>
        	@if(isset($vendor))
            {{ $vendor->name }}
        	@else
            Syarikat
        	@endif : Permintaan Kemaskini CIDB / MOF / Alamat SSM
    	</h2>
    	@if(!isset($vendor)) @include('vendors._snaps') @else <hr> @endif

 		<table data-path="{{ $ajax_url }}@if(Request::get('state'))?state={{ Request::get('state') }} @endif" class="DT-index table table-striped table-hover table-bordered">
     		<thead class="bg-blue-selangor">
         	<tr>
             	@if(!isset($vendor))<th>Syarikat</th>@endif
             	<th>Kemaskini</th>
             	<th>Tarikh Permintaan</th>
             	<th>Perkara</th>
             	<th>Status</th>
             	<th width="200px">&nbsp;</th>
         	</tr>
     		</thead>
     		<tbody></tbody>
 		</table>

    	@if(isset($vendor))
    		<div class="well">
        		@if(App\CodeRequest::canCreate())

	        		@if(App\CodeRequest::canCreateFor($vendor->id, 'mof'))
		        		<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'mof']) }}" class="btn btn-primary">Kemaskini MOF</a>
		        	@endif

		        	@if(App\CodeRequest::canCreateFor($vendor->id, 'cidb'))
		        		<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'cidb']) }}" class="btn btn-primary">Kemaskini CIDB</a>
		        	@endif

		        	@if(App\CodeRequest::canCreateFor($vendor->id, 'district'))
		        		<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'district']) }}" class="btn btn-primary">Kemaskini Alamat SSM</a>
		        	@endif

		        	@if(App\CodeRequest::canCreateFor($vendor->id, 'email'))
		        		<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'email']) }}" class="btn btn-primary">Kemaskini Alamat Emel</a>
		        	@endif

	        	@endif

	        	@if($vendor->canShow())
	        		<a href="{{ asset(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors/'.$vendor->id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
	        	@endif
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
	            { data: 'type', name: 'type' },
	            { data: 'created_at', name: 'created_at' },
	            { data: 'approval_1_id', name: 'approval_1_id' },
	            { data: 'status', name: 'status' },
	            { data: 'actions', name: 'actions' },
		      ];
		  	}

		  	else {
		  		
		  		var columns = [
	            { data: 'name', name: 'vendors.name' },
	            { data: 'type', name: 'type' },
	            { data: 'created_at', name: 'created_at' },
	            { data: 'approval_1_id', name: 'approval_1_id' },
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