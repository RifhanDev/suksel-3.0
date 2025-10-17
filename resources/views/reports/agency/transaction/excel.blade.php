<table>
	<thead>
		<tr>
			<th style="border: 1px solid #000; background: #f3f3f3;">Maklumat Tender</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Tarikh dan Masa</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Syarikat</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">No. Transaski</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">No. Resit</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Kaedah Pembayaran</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Harga Dokumen</th>
		</tr>
	</thead>
	<tbody>
		@foreach($tenders as $ref_number => $transactions)
			<?php $count = 0; $sum = 0; ?>
			@foreach(array_sort($transactions, function($txn){ return $txn->transaction->created_at; }) as $txn)
				<tr>
					@if($count == 0)
						<td style="border: 1px solid #000;" rowspan="{{ count($transactions) }}">
							{{ $txn->tender->ref_number }} - {{ $txn->tender->name }} ({{ count($transactions) }})
						</td>
					@endif
					<td style="border: 1px solid #000;">{{ Carbon\Carbon::parse($txn->transaction->created_at)->format('d/m/Y H:i:s') }}</td>
					<td style="border: 1px solid #000;">{{ $txn->vendor ? $txn->vendor->name : '' }}</td>
					<td style="border: 1px solid #000;">{{ $txn->transaction->number }}</td>
					<td style="border: 1px solid #000;">{{ $txn->transaction->receipt_number }}</td>
					<td style="border: 1px solid #000;">{{ App\Gateway::$methods[$txn->transaction->method] }}</td>
					<td style="border: 1px solid #000;" class="text-right">{{ number_format($txn->amount, 2) }}</td>
				</tr>
				<?php $count++; $sum += $txn->amount; ?>
			@endforeach
			<tr>
				<th style="border: 1px solid #000; background: #f3f3f3;" colspan="6" class="text-right">Jumlah</th>
				<th style="border: 1px solid #000; background: #f3f3f3;" class="text-right">
					{{ number_format($sum, 2) }}
				</th>
			</tr>
		@endforeach
	</tbody>
</table>		