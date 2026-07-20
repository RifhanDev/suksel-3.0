@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  Senarai Pengguna Agensi
	  <span class="label label-success pull-right">{{$agency->name}}</span>
	  <a href="{{ action('ReportUserAgencyController@excel', ['agency' => $agency->id, 'roles' => $roles]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  <a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
	  	<thead class="bg-blue-selangor">
	    	<tr>
		      <th>Bil.</th>
		      <th>Nama</th>
		      <th>Emel</th>
		      <th>Peranan</th>
	    	</tr>
	  	</thead>
	  	<tbody>
		  	<?php $count = 1; ?>
		  	@forelse($users as $user)
		    	<tr>
			      <td>{{ $count }}</td>
			      <td>{{ $user->name }}</td>
			      <td>{{ $user->email }}</td>
			      <td>
			        <ul>
			          	@foreach($user->roles as $role)<li>{{ $role->name }}</li>@endforeach
			        </ul>
			      </td>
		    	</tr>
		    	<?php $count++; ?>
	  		@empty
	    		<tr>
	      		<td colspan="3">Tiada maklumat pengguna.</td>
	    		</tr>
	  		@endforelse
	  	</tbody>
	</table>

@endsection

@include('reports.footer-scripts')