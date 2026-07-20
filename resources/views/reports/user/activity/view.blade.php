@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan Aktiviti Staff ({{ $date_start }} - {{ $date_end }})
	  	<a href="{{ action('ReportUserActivityController@excel', ['users' => $data[0]->pluck('id'), 'date_start' => $date_start, 'date_end' => $date_end]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<h5>Aktiviti Tender</h5>

	<table class="table table-bordered">
	  	<thead class="bg-blue-selangor">
			<tr>
				<th rowspan="2">Bil.</th>
				<th rowspan="2">Nama</th>
				<th colspan="{{ count($tender_activities) }}">Aktiviti Tender</th>
				<th colspan="{{ count($vendor_activities) }}">Aktiviti Syarikat</th>
				<th rowspan="2">Permintaan Perubahan</th>
				<th rowspan="2">Jumlah</th>
			</tr>
			<tr>
				@foreach($tender_activities as $activity)<th>{{ App\TenderHistory::$types[$activity] }}</th>@endforeach
				@foreach($vendor_activities as $activity)<th>{{ App\VendorHistory::$types[$activity] }}</th>@endforeach
			</tr>
		</thead>
	  	<tbody>
	    	<?php $count = 1; ?>
	    	@foreach($data[1] as $user => $number)
		    	<tr>
			      <td>{{ $count }}</td>
			      <td>{{ $user }}</td>
			      @foreach($tender_activities as $activity)<td>{{ $number[$activity] }}</td>@endforeach
			      @foreach($vendor_activities as $activity)<td>{{ $number[$activity] }}</td>@endforeach
			      <td>{{ $number['change-request'] }}</td>
			      <td>{{ $number['total'] }}</td>
		    	</tr>
		    	<?php $count++; ?>
	    	@endforeach
	  	</tbody>
	</table>

@endsection