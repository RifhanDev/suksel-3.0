<table class="table table-bordered">
	<thead class="bg-blue-selangor">
		<tr>
			<th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Tarikh</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Pengguna</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Emel</th>
			<th style="border: 1px solid #000; background: #f3f3f3;">Agensi / Syarikat</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		@forelse($data as $history)
			<tr>
				<td style="border: 1px solid #000;">{{ $count }}</td>
				<td style="border: 1px solid #000;">{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
				<td style="border: 1px solid #000;">{{ $history->user->name }}</td>
				<td style="border: 1px solid #000;">{{ $history->user->email }}</td>
				<td style="border: 1px solid #000;">
					{{ $history->user->agency ? $history->user->agency->name : '' }}
					{{ $history->user->vendor ? $history->user->vendor->name : '' }}
				</td>
			</tr>
			<?php $count++; ?>
		@endforeach
	</tbody>
</table>