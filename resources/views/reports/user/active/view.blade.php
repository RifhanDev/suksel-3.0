@extends('layouts.report')
@section('content')

	<?php $user = Auth::user(); ?>

	@include('reports.user.active._snaps')
	<br><br>
	<h4 class="tender-title">
	  	Senarai Status Pengguna Mengikut Agensi
	  	<span class="label label-success pull-right">{{$inputAgency == 'all' ? 'Semua' : $agencies->first()->name}}</span>
	  	<a href="{{ action('ReportUserActiveController@excel', ['agency' => $agencies->first()->id]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Bil.</th>
				<th>Nama</th>
				<th>Agensi</th>
				<th>Tarikh Daftar</th>
				<th>Emel</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php $count = 1; ?>
			@forelse($users as $user)
				<tr>
					<td>{{ $count }}</td>
					<td>{{ $user->name }}</td>
					<td>{{ $user->agency ? $user->agency->name : 'No Agency' }}</td>
					<td>{{ $user->created_at}}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->status() }}</td>
				</tr>
				<?php $count++; ?>
			@empty
				<tr>
					<td colspan="3">Tiada maklumat pengguna.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
@endsection

@include('reports.footer-scripts')