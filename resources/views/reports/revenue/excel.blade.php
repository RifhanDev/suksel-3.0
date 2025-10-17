<html>
   <tr>
		<th style="border: 1px solid #000; background: #f3f3f3;">Tahun</th>
		
		@if(in_array('tender', $fields))
			<th style="border: 1px solid #000; background: #f3f3f3;">Bilangan Transaksi Tender</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Transaksi Tender (RM)</th>
		@endif
		
		@if(in_array('quotation', $fields))
			<th style="border: 1px solid #000; background: #f3f3f3;">Bilangan Transaksi Sebutharga</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Transaksi Sebutharga (RM)</th>
		@endif
		
		@if(in_array('transaction', $fields))
			<th style="border: 1px solid #000; background: #f3f3f3;">Bilangan Transaksi</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Transaksi (RM)</th>
		@endif
		
		@if(in_array('registration', $fields))
			<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Langganan Syarikat Baru</th>
		@endif
		
		@if(in_array('renewal', $fields))
			<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Pembaharuan Langganan Syarikat</th>
		@endif
   </tr>

   @foreach($data as $year => $d)
    	<tr>
			<td style="border: 1px solid #000;"><strong>{{ $year }}</strong></td>
			
			@if(in_array('tender', $fields))
				<td style="border: 1px solid #000;">{{ $d['tender']['count'] }}</td>
				<td style="border: 1px solid #000;">{{ sprintf('%.2f', $d['tender']['value']) }}</td>
			@endif
			
			@if(in_array('quotation', $fields))
				<td style="border: 1px solid #000;">{{ $d['quotation']['count'] }}</td>
				<td style="border: 1px solid #000;">{{ sprintf('%.2f', $d['quotation']['value']) }}</td>
			@endif
			
			@if(in_array('transaction', $fields))
				<td style="border: 1px solid #000;">{{ $d['transaction']['count'] }}</td>
				<td style="border: 1px solid #000;">{{ sprintf('%.2f', $d['transaction']['value']) }}</td>
			@endif
			
			@if(in_array('registration', $fields))
				<td style="border: 1px solid #000;">{{ $d['registration']['count'] }}</td>
			@endif
			
			@if(in_array('renewal', $fields))
				<td style="border: 1px solid #000;">{{ $d['renewal']['count'] }}</td>
			@endif
		</tr>
   @endforeach
</html>
