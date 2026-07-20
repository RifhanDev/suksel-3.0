@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
	  	Senarai Syarikat Mengikut Status
	  	<span class="label label-success pull-right">{{ $status }}</span>
	  	<a href="{{ asset('reports/vendor/status/excel/'.$name) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
	  	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	</h4>

	<table class="table table-bordered datatables">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Bil.</th>
				<th>No. Syarikat</th>
				<th>Nama Syarikat</th>
				<th>{{ $date_label }}</th>
			</tr>
		</thead>
		<tbody>
			<?php $count = 1; ?>
			@forelse($vendors as $vendor)
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
			<?php $count++; ?>
			@empty
				<tr>
					<td colspan="4">Tiada maklumat syarikat.</td>
				</tr>
			@endforelse
		</tbody>
	</table>

@endsection
@include('reports.footer-scripts')