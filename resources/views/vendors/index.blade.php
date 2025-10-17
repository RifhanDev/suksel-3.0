@extends('layouts.default')
@section('content')
    	<h2>Syarikat {{isset($subtitle) ? ': ' . $subtitle : ''}}</h2>
    	<br>
    	@include('vendors._snaps')
    	<table data-path="{{action('VendorsController@index')}}{{isset($approval_status) ? '?state=' . $approval_status : ''}}" class="DT-index table table-striped table-hover table-bordered">
        	<thead class="bg-blue-selangor">
            <tr>
             	<th>Bil.</th>
             	<th>No. Pendaftaran</th>
             	<th>Nama</th>
             	<th>Alamat Emel</th>
             	<th>@if(isset($approval_status)) Tarikh Didaftarkan @else Tarikh Diluluskan @endif</th>
             	<th>Status</th>
             	<th width="80px">&nbsp;</th>
            </tr>
        	</thead>
        	<tbody></tbody>
    	</table>
    	<br>
    	@include('vendors.actions-footer', ['is_list' => true])
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
	            { data: 'id', name: null },
	            { data: 'registration', name: 'vendors.registration' },
	            { data: 'name', name: 'name' },
	            { data: 'email', name: 'users.email' },
	            { data: 'approval_date', name: 'approval_date' },
	            { data: 'completed', name: 'completed' },
	            { data: 'actions', name: 'actions', orderable: false, searchable: false },
	      	],
		      // processing: true,
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
			aaSorting: [],
			// columnDefs: [{
			// "searchable": false,
			// "orderable": false,
			// "targets": 0
			// }, {
			// "targets": 1,
			// "name" : "vendors.registration"
			// }],
			// "order": [[1, 'asc']],
			fnDrawCallback: function(oSettings) {
				start = oSettings.oAjaxData.start + 1;
				DT.column(0).nodes().to$().each(function(index){
				$(this).text(start+index);
				});
			}
			});
		});
	</script>
	
@endsection