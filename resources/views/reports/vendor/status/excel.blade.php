<table>
	<thead>
		<tr>
			<th>Bil.</th>
			<th>No. Syarikat</th>
			<th>Nama Syarikat</th>
			<th>{{ $date_label }}</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		@foreach($vendors as $vendor)
			<tr>
				<td>{{ $count }}</td>
				<td>{{ strtoupper($vendor->registration) }}</td>
				<td>{{ strtoupper($vendor->name) }}</td>
				<td>
					@switch($status)
 						@case('Daftar Belum Lulus')
				        	{{ Carbon\Carbon::parse($vendor->created_at)->format('d/m/Y') }}
				        	@break

 						@case('Lulus Belum Bayar')
     						{{ Carbon\Carbon::parse($vendor->approval_date)->format('d/m/Y') }}
     						@break

     					@case('Aktif')
     						{{ \App\Subscription::getLastSubscription($vendor->id) }}
     						@break

     					@case('Tidak Aktif')
     						{{ \App\Subscription::getLastSubscription($vendor->id) }}
     						@break

     					@case('Tamat Tempoh MOF')
     						{{ Carbon\Carbon::parse($vendor->mof_end_date)->format('d/m/Y') }}
     						@break

				    	@default
				     		{{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('d/m/Y') }}
				       	@break
					@endswitch
				</td>
			</tr>
		@endforeach
		<?php $count++; ?>
	</tbody>
</table>		