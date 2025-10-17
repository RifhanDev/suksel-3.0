@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan Harian Agensi: {{ $agency->name }} ({{ $agency->short_name }})
	  	<span class="label label-default pull-right">
	  		{{ Carbon\Carbon::parse($date)->format('d/m/Y') . (isset($time) ? ' ' . $time : '') }}</span>
	  	<span class="label label-success pull-right">MYR {{ $amount }}</span>
	  	@if($method)
	  		<span class="label label-danger pull-right">{{ App\Gateway::$methods[$method] }}</span>
	  	@endif
	  	<a href="{{ action('ReportAgencyDailyController@excel', ['ou' => $agency->id, 'date' => $date, 'time' => $time, 'method' => $method]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
	   <thead class="bg-blue-selangor">
        	<tr>
            <th>Bil.</th>
            <th class="col-xs-3">Maklumat Tender</th>
            <th class="col-xs-2">Syarikat</th>
            <th class="col-xs-2">Tarikh &amp; Waktu</th>
            <th>No Transaksi</th>
            <th>Kod Transaksi</th>
            <th>No Resit</th>
            <th>Kaedah Pembayaran</th>
            <th class="col-xs-2 text-right">Harga Dokumen (RM)</th>
        	</tr>
	   </thead>
    	<tbody>
        	<?php $count = 1; ?>
        	@foreach($purchases as $txn)
        		<tr>
            <td>{{ $count }}</td>
            <td>
               <strong>{{ $txn->tender->ref_number }}</strong><br>
               <small>{{ $txn->tender->name }}</small>
            </td>
            <td>@if(isset($txn->vendor)) <b>{{ $txn->vendor->name }} ({{ $txn->vendor->registration }})</b><br><small>{{ nl2br($txn->vendor->address) }}</small> @else {{ boolean_icon(false) }} @endif</td>
            <td>{{ Carbon\Carbon::parse($txn->transaction->created_at)->format('d/m/Y H:i:s') }}</td>
            <td>{{ $txn->transaction->number }}</td>
            <td>
               @if($txn->transaction->type == 'purchase') 
                  73105
                 	@else 71399
               @endif
            </td>
            <td>{{ $txn->transaction->receipt_number }}</td>
            <td>{{ App\Gateway::$methods[$txn->transaction->method] }}</td>
            <td class="text-right">{{ number_format($txn->amount, 2) }}</td>
        		</tr>
        	<?php $count++; ?>
        	@endforeach
    	</tbody>
    	<tfoot>
        	<tr>
            <th class="text-right" colspan="8">Jumlah Keseluruhan</th>
            <th class="text-right">{{ number_format($amount, 2) }}</th>
        	</tr>
    	</tfoot>
	</table>

@endsection
@include('reports.footer-scripts')

