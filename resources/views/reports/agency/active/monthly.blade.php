@extends('layouts.report')
@section('content')
	
	<h4 class="tender-title">
	  	Laporan 10 Agensi Aktif (Bulanan)
	  	<span class="label label-default pull-right">{{$year}}</span>
	  	<a href="{{ action('ReportAgencyActiveController@excel', ['year' => $year, 'type' => 'monthly']) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	 	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered table-striped small-font">
	  	<thead class="bg-blue-selangor">
	    	<tr>
		      <th>Agensi</th>
		      	@foreach(range(1,12) as $m)<th>{{ date('M', strtotime(sprintf('%4d-%02d-01', $year, $m))) }}</th>@endforeach
		      <th>Jumlah</th>
	    	</tr>
	  	</thead>
	  	<tbody>
			@forelse($data as $d)
			<tr>
				<td rowspan="2">{{$d->short_name}}</td>
			
				<td>{{ (int) $d->count_jan }}</td>
				<td>{{ (int) $d->count_feb }}</td>
				<td>{{ (int) $d->count_mar }}</td>
				<td>{{ (int) $d->count_apr }}</td>
				<td>{{ (int) $d->count_may }}</td>
				<td>{{ (int) $d->count_jun }}</td>
				<td>{{ (int) $d->count_jul }}</td>
				<td>{{ (int) $d->count_aug }}</td>
				<td>{{ (int) $d->count_sep }}</td>
				<td>{{ (int) $d->count_oct }}</td>
				<td>{{ (int) $d->count_nov }}</td>
				<td>{{ (int) $d->count_dec }}</td>
				<td>{{ (int) $d->count }}</td>
			</tr>
			
			<tr>
				<td>RM {{ number_format($d->amount_jan, 2) }}</td>
				<td>RM {{ number_format($d->amount_feb, 2) }}</td>
				<td>RM {{ number_format($d->amount_mar, 2 ) }}</td>
				<td>RM {{ number_format($d->amount_apr, 2) }}</td>
				<td>RM {{ number_format($d->amount_may, 2) }}</td>
				<td>RM {{ number_format($d->amount_jun, 2) }}</td>
				<td>RM {{ number_format($d->amount_jul, 2) }}</td>
				<td>RM {{ number_format($d->amount_aug, 2) }}</td>
				<td>RM {{ number_format($d->amount_sep, 2) }}</td>
				<td>RM {{ number_format($d->amount_oct, 2) }}</td>
				<td>RM {{ number_format($d->amount_nov, 2) }}</td>
				<td>RM {{ number_format($d->amount_dec, 2) }}</td>
				<td>RM {{ number_format($d->amount, 2) }}</td>
			</tr>
			
			@empty
				<tr>
					<td colspan="14"><center>Tiada data</center></td>
				</tr>
			@endforelse
	  	</tbody>
	</table>
@endsection