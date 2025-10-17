@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan Hasil Transaksi Tahunan
	  	<span class="label label-default pull-right">{{$years[0] }}@if(count($years) > 1) - {{ end($years) }}@endif</span>
	  	<a href="{{ action('ReportRevenueController@excel', ['years' => $years, 'fields' => $fields]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
	  	<thead class="bg-blue-selangor">
	    	<tr>
	    		<th class="col-lg-2">Tahun</th>

	    		@if(in_array('tender', $fields))
	    			<th>Bilangan Transaksi Tender</th>
	    			<th>Jumlah Transaksi Tender (RM)</th>
	    		@endif

	    		@if(in_array('quotation', $fields))
	    			<th>Bilangan Transaksi Sebutharga</th>
	    			<th>Jumlah Transaksi Sebutharga (RM)</th>
	    		@endif

	    		@if(in_array('transaction', $fields))
	    			<th>Bilangan Transaksi</th>
	    			<th>Jumlah Transaksi (RM)</th>
	    		@endif

	    		@if(in_array('registration', $fields))
	    			<th>Jumlah Langganan Syarikat Baru</th>
	    		@endif

	    		@if(in_array('renewal', $fields))
	    			<th>Jumlah Pembaharuan Langganan Syarikat</th>
	    		@endif
	    	</tr>
	  	</thead>
	  	<tbody>
		  	@foreach($data as $year => $d)
		  		<tr>
		  			<td><strong>{{ $year }}</strong></td>

		  			@if(in_array('tender', $fields))
			  			<td>{{ $d['tender']['count'] }}</td>
			        	{{-- <td>{{ sprintf('%.2f', $d['tender']['value']) }}</td> --}}
			  			<td>{{ number_format($d['tender']['value'], 2) }}</td>
		    		@endif

		    		@if(in_array('quotation', $fields))
			  			<td>{{ $d['quotation']['count'] }}</td>
			  			<td>{{ number_format($d['quotation']['value'], 2) }}</td>
		    		@endif

		    		@if(in_array('transaction', $fields))
			  			<td>{{ $d['transaction']['count'] }}</td>
			  			<td>{{ number_format($d['transaction']['value'], 2) }}</td>
		    		@endif

		    		@if(in_array('registration', $fields))
		    			<td>{{ $d['registration']['count'] }}</td>
		    		@endif

		    		@if(in_array('renewal', $fields))
		    			<td>{{ $d['renewal']['count'] }}</td>
		    		@endif
		  		</tr>
		  	@endforeach
	  	</tbody>
	</table>

@endsection
@include('reports.footer-scripts')
