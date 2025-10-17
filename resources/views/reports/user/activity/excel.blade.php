<table>
	<thead>
		<tr>
			<th style="border: 1px solid #000; background: #f3f3f3;" rowspan="2">Bil.</th>
			<th style="border: 1px solid #000; background: #f3f3f3;" rowspan="2">Nama</th>
			<th style="border: 1px solid #000; background: #f3f3f3;" colspan="{{ count($tender_activities) }}">Aktiviti Tender</th>
			<th style="border: 1px solid #000; background: #f3f3f3;" colspan="{{ count($vendor_activities) }}">Aktiviti Syarikat</th>
			<th style="border: 1px solid #000; background: #f3f3f3;" rowspan="2">Permintaan Perubahan</th>
			<th style="border: 1px solid #000; background: #f3f3f3;" rowspan="2">Jumlah</th>
		</tr>
		<tr>
			@foreach($tender_activities as $activity)
				<th style="border: 1px solid #000; background: #f3f3f3;">
					{{ App\TenderHistory::$types[$activity] }}
				</th>
			@endforeach
			@foreach($vendor_activities as $activity)
				<th style="border: 1px solid #000; background: #f3f3f3;">
					{{ App\VendorHistory::$types[$activity] }}
				</th>
			@endforeach
		</tr>
	</thead>

	<tbody>
	   <?php $count = 1; ?>
	   @foreach($data[1] as $user => $number)
	    	<tr>
	        	<td style="border: 1px solid #000;">{{ $count }}</td>
	        	<td style="border: 1px solid #000;">{{ $user }}</td>
	        	@foreach($tender_activities as $activity)
	        		<td style="border: 1px solid #000;">
	        			{{ $number[$activity] }}
	        		</td>
	        	@endforeach
	        	@foreach($vendor_activities as $activity)
	        		<td style="border: 1px solid #000;">
	        			{{ $number[$activity] }}
	        		</td>
	        	@endforeach
	        	<td style="border: 1px solid #000;">{{ $number['change-request'] }}</td>
	        	<td style="border: 1px solid #000;">{{ $number['total'] }}</td>
	    	</tr>
	    	<?php $count++; ?>
	   @endforeach
	</tbody>

</table>
