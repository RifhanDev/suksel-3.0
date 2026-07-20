@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan Login Sebagai : {{$user->name}}
	  	<a href="{{ action('ReportUserLoginController@excel', ['user_id' => $user->id ]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
	  	<thead class="bg-blue-selangor">
	    	<tr>
		      <th>Bil.</th>
		      <th>Tarikh</th>
		      <th>Pengguna</th>
		      <th>Emel</th>
		      <th>Agensi / Syarikat</th>
	    	</tr>
	  	</thead>
	  	<tbody>
	  		<?php $count = 1; ?>
	  		@forelse($data as $history)
		    	<tr>
			      <td>{{ $count }}</td>
			      <td>{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
			      <td>{{ $history->user->name }}</td>
			      <td>{{ $history->user->email }}</td>
			      <td>
			        {{ $history->user->agency ? $history->user->agency->name : '' }}
			        {{ $history->user->vendor ? $history->user->vendor->name : '' }}
			      </td>
		    	</tr>
		    	<?php $count++; ?>
	  		@empty
		    	<tr>
		      	<td colspan="5">Tiada maklumat pengguna.</td>
		    	</tr>
	  		@endforelse
	  	</tbody>
	</table>

@endsection

@include('reports.footer-scripts')
