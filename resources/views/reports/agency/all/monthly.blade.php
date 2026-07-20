@extends('layouts.report')
@section('content')
	
	<h4 class="tender-title">
	  	Laporan Transaksi Semua Agensi (Bulanan)
	  	<span class="label label-default pull-right">{{$year}}</span>
	   <a href="{{ action('ReportAgencyAllController@excel', ['type' => 'monthly', 'year_start' => $year_start, 'year_end' => $year_end]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
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
	  	@if(!empty($data))
	  		<tbody>
		  	
		  		@foreach($data as $d)
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
				      <td>RM {{ number_format($d->amount_mar, 2) }}</td>
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
		    	@endforeach
	  		</tbody>
	  
		  	<tfoot>
		    	<tr>
			      <th rowspan="2">Jumlah</th>

			      <td>{{ array_reduce(array_map(function($d) { return $d->count_jan; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_feb; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_mar; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_apr; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_may; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_jun; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_jul; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_aug; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_sep; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_oct; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_nov; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <td>{{ array_reduce(array_map(function($d) { return $d->count_dec; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
			      <th>{{ array_reduce(array_map(function($d) { return $d->count; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</th>
		    	</tr>

		    	<tr>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_jan; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_feb; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_mar; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_apr; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_may; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_jun; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_jul; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_aug; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_sep; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_oct; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_nov; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <td>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount_dec; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
			      <th>RM {{ number_format(array_reduce(array_map(function($d) { return $d->amount; }, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</th>
		    	</tr>
		  	</tfoot>
	  	@else
	  		<tbody>
	    		<tr>
	     	 		<td colspan="14"><center>Tiada data</center></td>
	    		</tr>
	  		</tbody>
	  	@endif
	</table>

@endsection