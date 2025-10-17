@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Senarai Syarikat Mengikut Daerah
	  	<span class="label label-success pull-right">{{ $district == 'all' ? 'Semua' : App\Vendor::$districts[$district] }}</span>
	  	<a href="{{ action('ReportVendorDistrictController@excel', ['district' => $district]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Bil.</th>
				<th>No. Syarikat</th>
				<th>Nama Syarikat</th>
				<th>Alamat</th>
				<th>Nama Pegawai</th>
				<th>Emel</th>
				<th>Telefon</th>
				<th>Tarikh Mendaftar</th>
				<th>Tarikh Tamat Langganan</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php $count = 1; ?>
			@forelse($vendors as $vendor)
				<tr>
					<td>{{ $count }}</td>
					<td>{{ $vendor->registration }}</td>
					<td>{{ $vendor->name }}</td>
					<td>{{ $vendor->address }}</td>
					<td>{!! $vendor->user ? $vendor->user->name : '<span class="glyphicon glyphicon-remove"></span>' !!}</td>
					<td>{!! $vendor->user ? $vendor->user->email : '<span class="glyphicon glyphicon-remove"></span>' !!}</td>
					<td>{{ $vendor->tel }}</td>
					<td>{{ Carbon\Carbon::parse($vendor->created_at)->format('d/m/Y') }}</td>
					<td>
						{!! $vendor->expiry_date ? Carbon\Carbon::parse($vendor->expiry_date)->format('d/m/Y') : '<span class="glyphicon glyphicon-remove"></span>' !!}
					</td>
					<td>{{ $vendor->status }}</td>
				</tr>
				<?php $count++; ?>
			@empty
				<tr>
					<td colspan="7">Tiada maklumat syarikat.</td>
				</tr>
			@endforelse
		</tbody>
	</table>

	<?php echo $vendors->appends(array('district' => $district))->links(); ?>

@endsection
@include('reports.footer-scripts')