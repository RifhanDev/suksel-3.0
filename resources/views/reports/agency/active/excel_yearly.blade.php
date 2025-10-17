<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;" rowspan="2" class="col-lg-4">Agensi</th>
		<th style="border: 1px solid #000; background: #f3f3f3;" colspan="2" class="col-lg-4">{{ $year - 1}}</th>
		<th style="border: 1px solid #000; background: #f3f3f3;" colspan="2" class="col-lg-4">{{ $year}}</th>
	</tr>
	<tr>
		<td style="border: 1px solid #000;">&nbsp;</td>
		<th style="border: 1px solid #000; background: #f3f3f3;">Bilangan Transaksi</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Transaksi</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Bilangan Transaksi</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Transaksi</th>
	</tr>
	
	@foreach($data as $d)
		<tr>
			<td style="border: 1px solid #000;">{{$d->name}}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_prev }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_prev) }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount) }}</td>
		</tr>
	@endforeach
</html>
