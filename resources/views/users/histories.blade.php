@extends('layouts.default')
@section('content')

	<h2>Aktiviti Pengguna: {{ $view_user->name }}</h2>
	<table class="table table-bordered table-striped">
		<tr>
			<th class="col-xs-3">Nama</th>
			<td>{{ $view_user-> name }}</td>
		</tr>
		@if($view_user->agency)
			<tr>
				<th>Agensi</th>
				<td>{{ $view_user->agency->name }}</td>
			</tr>
		@endif
		@if($view_user->vendor)
			<tr>
				<th>Syarikat</th>
				<td>{{ $view_user->vendor->name }}</td>
			</tr>
		@endif
		<tr>
			<th>Alamat Emel</th>
			<td>{{ $view_user->email }}</td>
		</tr>
		<tr>
			<th>Peranan</th>
			<td>
				<ul>
					@foreach($view_user->roles as $role)<li>{{ $role->name }}</li>@endforeach
				</ul>
			</td>
		</tr>
	</table>
	
	<table data-path="{{ asset('users/'.$view_user->id.'/histories') }}" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Tarikh</th>
				<th>Aktiviti</th>
				<th>Pengguna Ketiga</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<br>
	<div class="well">
		@if($view_user->vendor)
			<a href="{{ asset('vendors/'.$view_user->vendor_id)}}" class="btn btn-default">Maklumat Syarikat</a>
		@else
			<a href="{{ asset('view_users')}}" class="btn btn-default">Senarai Pengguna</a>
		@endif
	</div>

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
		            { data: 'created_at', name: 'created_at' },
		            { data: 'action', name: 'action' },
		            { data: '3p_id', name: '3p_id' },
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
