<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">No. Syarikat</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Nama Syarikat</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Alamat</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Nama Pegawai</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Emel</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Telefon</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Mendaftar</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Tamat Langganan</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Status</th>
	</tr>
	
	<?php $count = 1; ?>
	@foreach($vendors as $vendor)
		<tr>
			<td style="border: 1px solid #000;">{{ $count }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->registration }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->name }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->address }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->user ? $vendor->user->name : '' }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->user ? $vendor->user->email : '' }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->tel }}</td>
			<td style="border: 1px solid #000;">{{ Carbon\Carbon::parse($vendor->created_at)->format('d/m/Y') }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->expiry_date ? Carbon\Carbon::parse($vendor->expiry_date)->format('d/m/Y') : '' }}</td>
			<td style="border: 1px solid #000;">{{ $vendor->status }}</td>
		</tr>
		<?php $count++; ?>
	@endforeach
</html>
