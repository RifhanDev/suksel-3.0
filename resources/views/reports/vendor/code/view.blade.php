@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Senarai Syarikat Mengikut Kod Bidang
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	  	<a href="{{ action('ReportVendorCodeController@excel', ['mof_codes' => Request::get('mof_codes'), 'cidb_codes' => $cidb_codes, 'cidb_grades' => Request::get('cidb_grades'), 'district_id' => Request::get('district_id')]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	</h4>

	<table class="table table-bordered">
		@if(isset($district) && !is_null($district))
			<tr>
				<th class="col-xs-3">Daerah</th>
				<td>{{ $district }}</td>
			</tr>
		@endif
		<?php $max_count = count($mof_codes); $count = 1; ?>
		@if(count($mof_codes) > 0 && !$mof_empty)
			<tr>
				<th class="col-xs-3">Kod Bidang MOF</th>
				<td>
					@foreach($mof_codes as $code)
						<?php if(!isset($code['codes'])) continue; ?>
						{!! implode( App\VendorCode::$rule[$code['inner_rule']] . '<br>' , App\Code::whereIn('id', $code['codes'])->get()->pluck('label')->toArray()) !!}
						@if( $count != $max_count )<br><br>{!! App\VendorCode::$rule[$code['join_rule']] !!}<br><br>@endif
						<?php $count++; ?>
					@endforeach
				</td>
			</tr>
		@endif
		@if(count($cidb_grades) > 0 && !$grade_empty)
			<tr>
				<th class="col-xs-3">Gred CIDB</th>
				<td>
					<ul>
						@foreach($cidb_grades as $grade)<li>{{ $grade->label }}</li>@endforeach
					</ul>
				</td>
			</tr>
		@endif
		<?php $max_count = count($cidb_codes); $count = 1; ?>
		@if(count($cidb_codes) > 0 && !$cidb_empty)
			<tr>
				<th class="col-xs-3">Bidang Pengkhususan CIDB</th>
				<td>
					@foreach($cidb_codes as $code)
						<?php if(!isset($code['codes'])) continue; ?>
						{!! implode( App\VendorCode::$rule[$code['inner_rule']] . '<br>' , App\Code::whereIn('id', $code['codes'])->get()->pluck('label')->toArray()) !!}
						@if( $count != $max_count )<br><br>{!! App\VendorCode::$rule[$code['join_rule']] !!}<br><br>@endif
						<?php $count++; ?>
					@endforeach
				</td>
			</tr>
		@endif
	</table>

	<table class="table table-bordered datatables">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Bil.</th>
				<th>No. Syarikat</th>
				<th>Nama Syarikat</th>
				<th>Alamat</th>
				<th>Nama Pegawai</th>
				<th>Emel</th>
				<th>No Telefon</th>
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
					<td>{{ $vendor->user ? $vendor->user->name : '<span class="glyphicon glyphicon-remove"></span>' }}</td>
					<td>{{ $vendor->user ? $vendor->user->email : '<span class="glyphicon glyphicon-remove"></span>' }}</td>
					<td>{{ $vendor->tel }}</td>
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

@endsection
@include('reports.footer-scripts')