<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Tarikh &amp; Waktu</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">No. Transaksi</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">No. Resit</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">No. Rujukan Pembayaran</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Nama Syarikat</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">No. Syarikat</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Maklumat Tender/Langganan</th>
		@if($gateway->default)<th style="border: 1px solid #000; background: #f3f3f3;">Agensi</th>@endif
		<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah (RM)</th>
	</tr>

   <?php $count = 1; ?>
   @foreach($transactions as $txn)
    	@if($txn->type == 'subscription')
	    	<tr>
				<td style="border: 1px solid #000;">{{ $count }}</td>
				<td style="border: 1px solid #000;">{{ Carbon\Carbon::parse($txn->created_at)->format('d/m/Y H:i:s') }}</td>
				<td style="border: 1px solid #000;">{{ $txn->number }}</td>
				<td style="border: 1px solid #000;">{{ $txn->receipt_number }}</td>
				<td style="border: 1px solid #000;">{{ $txn->method == 'ebpg' ? $txn->gateway_auth : $txn->gateway_reference }}</td>
				<td style="border: 1px solid #000;">@if(isset($txn->vendor)){{ $txn->vendor->name }} ({{ $txn->vendor->registration }})@endif</td>
				<td style="border: 1px solid #000;">
				{{ Carbon\Carbon::parse($txn->subscription->start_date)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($txn->subscription->end_date)->format('d/m/Y') }}
				</td>
				<td style="border: 1px solid #000;">{{ $txn->agency->name }}</td>
				<td style="border: 1px solid #000;">{{ number_format($txn->amount, 2) }}</td>
	    	</tr>
    	@else
    		<?php $index = 1; ?>
    		@foreach($txn->purchases as $purchase)
    			<tr>
					<td style ="border: 1px solid #000;">{{ $index == 1 ? $count : '' }}</td>
					<td style ="border: 1px solid #000;">{{ Carbon\Carbon::parse($txn->created_at)->format('d/m/Y H:i:s') }}</td>
					<td style ="border: 1px solid #000;">{{ $txn->number }}</td>
					<td style ="border: 1px solid #000;">{{ $txn->receipt_number }}</td>
					<td style ="border: 1px solid #000;">{{ $txn->method == 'ebpg' ? $txn->gateway_auth : $txn->gateway_reference }}</td>
					<td style ="border: 1px solid #000;">@if(isset($txn->vendor)){{ $txn->vendor->name }}@endif</td>
					<td style ="border: 1px solid #000;">@if(isset($txn->vendor)){{ $txn->vendor->registration }}@endif</td>
					<td style ="border: 1px solid #000;">{{ $purchase->tender->ref_number }} - {{ $purchase->tender->name }}</td>
					<td style ="border: 1px solid #000;">{{ $purchase->tender->tenderer->name }}</td>
					<td style ="border: 1px solid #000;">{{ number_format($purchase->amount, 2) }}</td>
    			</tr>
    		<?php $index++; ?>
    		@endforeach
    	@endif
    	<?php $count++; ?>
   @endforeach

   <tr>
     	<th style="text-align: right;" colspan="{{ $gateway->default ? '8' : '7' }}">Jumlah Keseluruhan</th>
     	<th style="border: 1px solid #000;"><b>{{ number_format($amount, 2) }}</b></th>
   </tr>
</html>
