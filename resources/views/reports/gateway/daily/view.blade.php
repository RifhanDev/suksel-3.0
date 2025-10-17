@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Laporan Harian Gateway: {{ $gateway->agency->name }} - {{ App\Gateway::$methods[$gateway->type] }} ({{ $gateway->merchant_code }}) 
	  	<span class="label label-default pull-right">{{ Carbon\Carbon::parse($date)->format('d/m/Y') . (isset($time) ? ' ' . $time : '') }}</span>
	  	<span class="label label-success pull-right">MYR {{ $amount }}</span>
	  	<a href="{{ action('ReportGatewayDailyController@excel', ['gateway_id' => $gateway->id, 'date' => $date, 'time' => $time]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered">
    	<thead class="bg-blue-selangor">
        	<tr>
            <th>Bil.</th>
            <th class="col-xs-1">Tarikh &amp; Waktu</th>
            <th class="col-xs-2">Maklumat Transaksi</th>
            <th class="col-xs-2">Syarikat</th>
            <th class="col-xs-3" colspan="2">Maklumat Tender/Langganan</th>
            @if($gateway->default)<th class="col-xs-1">Agensi</th>@endif
            <th class="col-xs-1">Kod Hasil</th>
            <th class="col-xs-1 text-right">Jumlah (RM)</th>
            <th class="col-xs-1 text-right">Jumlah Besar (RM)</th>
        	</tr>
    	</thead>
    	<tbody>
        	<?php $count = 1; ?>
        	@foreach($transactions as $txn)
	        	@if($txn->type == 'subscription')
		        	<tr>
		            <td>{{ $count }}</td>
		            <td>
							{{ Carbon\Carbon::parse($txn->created_at)->format('d/m/Y') }}<br>
							{{ Carbon\Carbon::parse($txn->created_at)->format('H:i:s') }}
		            </td>
		            <td>
							No. Transaksi<br><b>{{ $txn->number }}</b><br><br>
							No. Resit<br><b>{{ $txn->receipt_number }}</b><br><br>
							No. Rujukan Pembayaran<br><b>{{ $gateway->type == 'ebpg' ? $txn->gateway_auth : $txn->gateway_reference }}</b>
		            </td>
		            <td>
							@if(isset($txn->vendor))
								<b>{{ $txn->vendor->name }}<br>{{ $txn->vendor->registration }})</b>
								<br><small>{!! nl2br($txn->vendor->address) !!}</small>
							@else
								{!! boolean_icon(false) !!}
							@endif
		            </td>
		            <td colspan="2">
							Tarikh Mula Langganan: {{ Carbon\Carbon::parse($txn->subscription->start_date)->format('d/m/Y') }}<br>
							Tarikh Tamat Langganan: {{ Carbon\Carbon::parse($txn->subscription->end_date)->format('d/m/Y') }}
		            </td>
		            @if($gateway->default)<td>{{ $txn->agency->short_name }}</td>@endif
		            <td>
		               @if($txn->type == 'purchase') 73105
		               @else 71399
		               @endif
		            </td>
		            <td class="text-right">{{ number_format($txn->amount, 2) }}</td>
		            <td class="text-right">{{ number_format($txn->amount, 2) }}</td>
		        	</tr>
	        	@else
		        	<?php $index = 1; ?>
		        	@foreach($txn->purchases as $purchase)
			        	<tr>
			        	@if($index == 1)
			            <td @if(count($txn->purchases) > 1) rowspan="{{ count($txn->purchases) }}" @endif >{{ $count }}</td>
			            <td @if(count($txn->purchases) > 1) rowspan="{{ count($txn->purchases) }}" @endif >
								{{ Carbon\Carbon::parse($txn->created_at)->format('d/m/Y') }}<br>
								{{ Carbon\Carbon::parse($txn->created_at)->format('H:i:s') }}
			            </td>
			            <td @if(count($txn->purchases) > 1) rowspan="{{ count($txn->purchases) }}" @endif >
								No. Transaksi<br><b>{{ $txn->number }}</b><br><br>
								No. Resit<br><b>{{ $txn->receipt_number }}</b><br><br>
								No. Rujukan Pembayaran<br><b>{{ $gateway->type == 'ebpg' ? $txn->gateway_auth : $txn->gateway_reference }}</b>
			            </td>
			            <td @if(count($txn->purchases) > 1) rowspan="{{ count($txn->purchases) }}" @endif >
			               @if(isset($txn->vendor))
			                  <b>{{ $txn->vendor->name }}<br>{{ $txn->vendor->registration }}</b>
			                  <br><small>{!! nl2br($txn->vendor->address) !!}</small>
			               @else
			                  {!! boolean_icon(false) !!}
			               @endif
			            </td>
			        	@endif
	        	   	<td width="40">{{ $index }}</td>
	            	<td>
	                	<strong>{{ $purchase->tender->ref_number }}</strong><br>
	                	<small>{{ $purchase->tender->name }}</small>
	            	</td>
	            	@if($gateway->default)<td>{{ $purchase->tender->tenderer->short_name }}</td>@endif
	            	<td>
	                	@if($txn->type == 'purchase') 73105
	                  @else 71399
	                	@endif
	            	</td>
	            	<td class="text-right">{{ number_format($purchase->amount, 2) }}</td>
			        	@if($index == 1)
			            <td class="text-right" @if(count($txn->purchases) > 1) rowspan="{{ count($txn->purchases) }}" @endif >{{ number_format($txn->amount, 2) }}</td>
			        	@endif
	        			</tr>
	        		<?php $index++; ?>
	        		@endforeach
        		@endif
        		<?php $count++; ?>
       	@endforeach
    	</tbody>
    	<tfoot>
        	<tr>
            <th class="text-right" colspan="{{ $gateway->default ? 8 : 7 }}">Jumlah Keseluruhan</th>
            <th class="text-right">{{ number_format($amount, 2) }}</th>
        	</tr>
    	</tfoot>
	</table>

@endsection
