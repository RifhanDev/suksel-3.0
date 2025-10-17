@extends('layouts.default')
@section('content')
   <h2 class="tender-title">
        	Senarai Agensi @if(isset($type)) : {{ $type->name }}@endif @if(isset($parent)) : {{ $parent->name }} @endif

        	<div class="btn-group pull-right">
            @if(App\OrganizationUnit::canCreate())<a href="{{ asset('agencies/create') }}" class="btn btn-danger btn-sm">Masukkan Agensi Baru</a>@endif
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
             	Pilihan Kategori <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
             	@foreach(App\OrganizationType::all() as $ou_type)
             	<li><a href="{{route('agencies.index', ['type' => $ou_type->id])}}">{{$ou_type->name}}</a></li>
             	@endforeach
            </ul>
        	</div>
   </h2>

   <div class="clearfix"></div>

   <table data-path="/agencies<?php if(isset($type)) : ?>?type=<?php echo $type->id; ?><?php endif; ?><?php if(isset($parent)) : ?>?parent=<?php echo $parent->id; ?><?php endif; ?>" class="DT-index table table-striped table-hover table-bordered">
     	<thead class="bg-blue-selangor">
         <tr>
          	<th>Nama</th>
          	<th>Alamat</th>
          	<th>No. Telefon</th>
          	@if(!isset($type))<th>Kategori</th>@endif
          	<th width="200px">&nbsp;</th>
         </tr>
     	</thead>
     	<tbody></tbody>
   </table>
@endsection

@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script>
		$('.DT-index').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');

		  	if (path.includes('/agencies?type')) {
			  	var columns = [
		         { data: 'name', name: 'name' },
		         { data: 'address', name: 'address' },
		         { data: 'tel', name: 'tel' },
		         { data: 'actions', name: 'actions' }
		      ];
		  	}

		  	else if (path.includes('/agencies?parent')) {
			  	var columns = [
		         { data: 'name', name: 'name' },
		         { data: 'address', name: 'address' },
		         { data: 'tel', name: 'tel' },
		         { data: 'type_id', name: 'type_id' },
		         { data: 'actions', name: 'actions' }
		      ];
		  	}

		  	else {
		  		
		  		var columns = [
		         { data: 'name', name: 'name' },
		         { data: 'address', name: 'address' },
		         { data: 'tel', name: 'tel' },
		         { data: 'type_id', name: 'type_id' },
		         { data: 'actions', name: 'actions' }
		      ];
		  	}

		  	var DT = target.DataTable({
		    	ajax: path,
		    	columns: columns,
		    	serverSide: true,
		    	// stateSave: true,
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