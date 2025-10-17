@extends('layouts.default')
@section('content')

	<h4 class="tender-title">Laporan Sistem Tender Online: Syarikat Mengikut Status</h4>
	
	<table class="table table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Status</th>
				<th class="col-lg-2">Bilangan Syarikat</th>
				<th class="col-lg-2">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Daftar Belum Lulus</th>
				<td>{{ $daftar_belum_lulus }}</td>
				<td>
					<a href="{{ action('ReportVendorStatusController@view', ['view' => 'daftar_belum_lulus']) }}" target="_blank">Senarai</a> | 
					<a href="{{ action('ReportVendorStatusController@csv', ['view' => 'daftar_belum_lulus']) }}" target="_blank">CSV</a>
				</td>
			</tr>
			<tr>
				<th>Lulus Belum Bayar</th>
				<td>{{ $lulus_belum_bayar }}</td>
				<td>
					<a href="{{ action('ReportVendorStatusController@view', ['view' => 'lulus_belum_bayar']) }}" target="_blank">Senarai</a> | 
					<a href="{{ action('ReportVendorStatusController@csv', ['view' => 'lulus_belum_bayar']) }}" target="_blank">CSV</a>
				</td>
			</tr>
			<tr>
				<th>Aktif</th>
				<td>{{ $aktif }}</td>
				<td>
					<a href="{{ action('ReportVendorStatusController@view', ['view' => 'aktif']) }}" target="_blank">Senarai</a> | 
					<a href="{{ action('ReportVendorStatusController@csv', ['view' => 'aktif']) }}" target="_blank">CSV</a>
				</td>
			</tr>
			<tr>
				<th>Tamat Pendaftaran</th>
				<td>{{ $tidak_aktif }}</td>
				<td>
					<a href="{{ action('ReportVendorStatusController@view', ['view' => 'tidak_aktif']) }}" target="_blank">Senarai</a> | 
					<a href="{{ action('ReportVendorStatusController@csv', ['view' => 'tidak_aktif']) }}" target="_blank">CSV</a>
				</td>
			</tr>
			<tr>
				<th>Tamat Tempoh MOF</th>
				<td>{{ $mof_expired }}</td>
				<td>
					<a href="{{ action('ReportVendorStatusController@view', ['view' => 'mof_expired']) }}" target="_blank">Senarai</a> | 
					<a href="{{ action('ReportVendorStatusController@csv', ['view' => 'mof_expired']) }}" target="_blank">CSV</a>
				</td>
			</tr>
			<tr>
				<th>Tamat Tempoh CIDB</th>
				<td>{{ $cidb_expired }}</td>
				<td>
					<a href="{{ action('ReportVendorStatusController@view', ['view' => 'cidb_expired']) }}" target="_blank">Senarai</a> | 
					<a href="{{ action('ReportVendorStatusController@csv', ['view' => 'cidb_expired']) }}" target="_blank">CSV</a>
				</td>
			</tr>
		</tbody>
	</table>

@endsection
