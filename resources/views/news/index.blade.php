@extends('layouts.default')
@section('content')
   <h1 class="tender-title">
     	Berita

     	@if(App\News::canCreate())<a href="{{ action('NewsController@create') }}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Tambah Berita Baru</a>@endif
   </h1>


   <table class="DT-news table table-bordered table-striped" data-path="/news">
     	<thead class="bg-blue-selangor">
         <tr>
          	<th width="100">Tarikh</th>
          	<th width="150">Agensi</th>
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
		    	columns: [
		            { data: 'created_at', name: 'created_at' },
		            { data: 'organization_unit_id', name: 'organization_unit_id' },
		            { data: 'title', name: 'title' },
		            { data: 'actions', name: 'actions' }
		      ],
		    	stateSave: true,
		    	serverSide: true,
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
			  DT.search('').columns().search('').draw();
		});

	</script>
	
@endsection