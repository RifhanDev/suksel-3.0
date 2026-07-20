@if($user->ability(['Admin', 'Registration Assessor'], []))
   <h4 class="tender-title">
    	Statistik Status Pengguna Mengikut Agensi
    	<span class="label label-success pull-right">{{$inputAgency == 'all' ? 'Semua' : $agencies->first()->name}}</span>
    	{{--<a href="{{ action('ReportUserActiveController@excel', ['agency' => $agency->id]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>--}}
    	{{-- <a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a> --}}
   </h4>
    
	<table class="table table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Bil.</th>
				<th>Agensi</th>
				<th>Alamat Agensi</th>
				{{-- <th>No Telefon</th> --}}
				<th>Kategori</th>
				<th># Pengguna Aktif</th>
				<th># Pengguna Tidak Aktif</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php $count = 1; ?>
			@forelse($agencies as $agency)
				<tr>
					<td>{{ $count }}</td>
					<td>{{ $agency ? $agency->name : 'No Agency' }}</td>
					<td>{{ $agency ? $agency->address : 'No address' }}</td>
					{{-- <td>{{ $agency ? $agency->tel : 'No Phone'}}</td> --}}
					<td>{{ $agency ? $agency->type->name : 'Tiada Kategori' }}</td>
					<td>{{number_format($agency->users()->active()->count(), 0)}}</td>
					<td>{{number_format($agency->users()->notActive()->count(), 0)}}</td>
					<td>{{number_format($agency->users()->count(), 0)}}</td>
				</tr>
				<?php $count++; ?>
			@empty
				<tr>
					<td colspan="7">Tiada maklumat pengguna.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
@endif