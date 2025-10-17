<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;" rowspan="2" class="col-lg-4">Agensi</th>
		@foreach($years as $year)
			<th style="border: 1px solid #000; background: #f3f3f3;" colspan="2">{{ $year }}</th>
		@endforeach
	</tr>
	<tr>
		<td style="border: 1px solid #000;">&nbsp;</td>
		@foreach($years as $year)
			<th style="border: 1px solid #000; background: #f3f3f3;">Bilangan Transaksi</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Transaksi</th>
		@endforeach
	</tr>
		
	@foreach($data as $d)
		<tr>
			<td style="border: 1px solid #000;">{{$d->name}}</td>
			@foreach($years as $year)
				<td style="border: 1px solid #000;"><?php $func = "count_{$year}"; echo (int) $d->{$func}; ?></td>
				<td style="border: 1px solid #000;">RM <?php $func = "amount_{$year}"; echo sprintf('%.2f', $d->{$func}); ?></td>
			@endforeach
		</tr>
	@endforeach
</html>
