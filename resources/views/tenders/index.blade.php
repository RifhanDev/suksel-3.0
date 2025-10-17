@extends('layouts.default')
@section('content')
    	<h2 class="tender-title">
        	Senarai Tender / Sebutharga
        	@if(App\Tender::canCreate())<a href="{{ asset('tenders/create') }}" class="btn btn-sm blue pull-right"><i class="fa fa-plus"></i> Tambah Tender / Sebutharga</a>@endif
    	</h2>
    	<table data-path="/tenders" class="DT-index table table-striped table-hover table-bordered">
        	<thead class="bg-blue-selangor">
            <tr>
                	<th>Maklumat Tender</th>
                	<th class="col-lg-2">Tarikh Jual</th>
                	<th class="col-lg-2">Tarikh Tutup</th>
                	<th class="col-lg-2">Harga Dokumen (RM)</th>
                	@if(Auth::check() && !Auth::user()->hasRole('Vendor'))<th>Status</th>@endif
            </tr>
        	</thead>
        	<tbody></tbody>
    	</table>
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
		            { data: 'document_start_date', name: 'document_start_date' },
		            { data: 'submission_datetime', name: 'submission_datetime' },
		            { data: 'price', name: 'price' },
		            { data: 'approver_id', name: 'approver_id' },
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