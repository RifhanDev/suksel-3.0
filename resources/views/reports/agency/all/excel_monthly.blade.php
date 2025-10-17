<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;">Agensi</th>
		@foreach(range(1,12) as $m)
			<th style="border: 1px solid #000; background: #f3f3f3;">{{ date('M', strtotime(sprintf('%4d-%02d-01', $year, $m))) }}</th>
		@endforeach
		<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah</th>
	</tr>

	<?php $count = 1; ?>
	@foreach($data as $d)
		<tr>
			<td rowspan="2">{{$d->short_name}}</td>
			
			<td style="border: 1px solid #000;">{{ (int) $d->count_jan }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_feb }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_mar }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_apr }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_may }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_jun }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_jul }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_aug }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_sep }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_oct }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_nov }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count_dec }}</td>
			<td style="border: 1px solid #000;">{{ (int) $d->count }}</td>
		</tr>
		
		<tr>
			<td style="border: 1px solid #000;">&nbsp;</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_jan) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_feb) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_mar ) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_apr) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_may) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_jun) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_jul) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_aug) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_sep) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_oct) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_nov) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount_dec) }}</td>
			<td style="border: 1px solid #000;">RM {{ sprintf('%.2f', $d->amount) }}</td>
		</tr>
	@endforeach
</html>
