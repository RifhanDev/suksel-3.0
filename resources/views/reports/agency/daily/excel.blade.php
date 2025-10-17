<html>
   <tr>
		<th style ="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">No. Tender</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">Tajuk Tender</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">Nama Syarikat</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">No Syarikat</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">Tarikh &amp; Waktu</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">No Transaksi</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">No Resit</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">Kaedah Pembayaran</th>
		<th style ="border: 1px solid #000; background: #f3f3f3;">Harga Dokumen (RM)</th>
   </tr>

   <?php $count = 1; ?>
   @foreach($purchases as $txn)
		<tr>
			<td style="border: 1px solid #000;">{{ $count }}</td>
			<td style="border: 1px solid #000;">{{ $txn->tender->ref_number }}</td>
			<td style="border: 1px solid #000;">{{ $txn->tender->name }}</td>
			<td style="border: 1px solid #000;">@if(isset($txn->vendor)){{ $txn->vendor->name }}@endif</td>
			<td style="border: 1px solid #000;">@if(isset($txn->vendor)){{ $txn->vendor->registration }}@endif</td>
			<td style="border: 1px solid #000;">{{ Carbon\Carbon::parse($txn->transaction->created_at)->format('d/m/Y H:i:s') }}</td>
			<td style="border: 1px solid #000;">{{ $txn->transaction->number }}</td>
			<td style="border: 1px solid #000;">{{ $txn->transaction->receipt_number }}</td>
			<td style="border: 1px solid #000;">{{ App\Gateway::$methods[$txn->transaction->method] }}</td>
			<td style="border: 1px solid #000;">{{ number_format($txn->amount, 2) }}</td>
		</tr>
   <?php $count++; ?>
   @endforeach

   <tr>
		<th style ="text-align: right;" colspan="9">Jumlah Keseluruhan</th>
		<th style ="border: 1px solid #000;"><b>{{ number_format($amount, 2) }}</b></th>
   </tr>
</html>
