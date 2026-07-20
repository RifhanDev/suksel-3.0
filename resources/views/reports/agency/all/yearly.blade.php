@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan Transaksi Semua Agensi (Tahunan)
	  	<span class="label label-default pull-right">{{$years[0] }}@if(count($years) > 1) - {{ end($years) }}@endif</span>
	  	<a href="{{ action('ReportAgencyAllController@excel', ['type' => 'yearly', 'year_start' => $year_start, 'year_end' => $year_end]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th rowspan="2" class="col-lg-4">Agensi</th>
				@foreach($years as $year)<th colspan="2" class="col-lg-4">{{ $year }}</th>@endforeach
			</tr>
			<tr>
				@foreach($years as $year)
					<th>Bilangan Transaksi</th>
					<th>Jumlah Transaksi</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@forelse($data as $d)
				<tr>
					<td>{{$d->name}}</td>
					@foreach($years as $year)
						<td><?php $func = "count_{$year}"; echo (int) $d->{$func}; ?></td>
						<td>RM <?php $func = "amount_{$year}"; echo number_format($d->{$func}, 2); ?></td>
					@endforeach
				</tr>
			@empty
				<tr>
					<td colspan="{{ count($years) * 2 + 1 }}"><center>Tiada data</center></td>
				</tr>
			@endforelse
	  	</tbody>
	  	<tfoot>
	    	<tr>
		      <th>Jumlah</th>
		      @foreach($years as $year)
		      <td>{{ array_reduce(array_map(function($d) use($year) { $func = "count_{$year}"; return $d->{$func}; }, $data), function($carry, $d) { $carry += $d; return $carry; }) }}</td>
		      <td>RM {{ number_format(array_reduce(array_map(function($d) use($year) { $func = "amount_{$year}"; return $d->{$func};}, $data), function($carry, $d) { $carry += $d; return $carry; }), 2) }}</td>
		      @endforeach
	    	</tr>
	  	</tfoot>
	</table>
@endsection