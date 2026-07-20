@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan 10 Agensi Aktif (Tahunan)
	  	<span class="label label-default pull-right">{{$year}}</span>
	  	<a href="{{ action('ReportAgencyActiveController@excel', ['year' => $year, 'type' => 'yearly']) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th rowspan="2" class="col-lg-4">Agensi</th>
				<th colspan="2" class="col-lg-4">{{ $year - 1}}</th>
				<th colspan="2" class="col-lg-4">{{ $year}}</th>
			</tr>
			<tr>
				<th>Bilangan Transaksi</th>
				<th>Jumlah Transaksi</th>
				<th>Bilangan Transaksi</th>
				<th>Jumlah Transaksi</th>
			</tr>
		</thead>
		<tbody>
			@forelse($data as $d)
				<tr>
					<td>{{$d->name}}</td>
					<td>{{ (int) $d->count_prev }}</td>
					<td>RM {{ number_format($d->amount_prev, 2) }}</td>
					<td>{{ (int) $d->count }}</td>
					<td>RM {{ number_format($d->amount, 2) }}</td>
				</tr>
			@empty
				<tr>
					<td colspan="5"><center>Tiada data</center></td>
				</tr>
			@endforelse
		</tbody>
	</table>

@endsection