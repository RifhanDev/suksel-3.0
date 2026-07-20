@extends('layouts.report')
@section('content')

	<h4 class="tender-title">
		Laporan Transaksi Agensi: {{ $agency->name }} ({{ $agency->short_name }})
		<span class="label label-default pull-right">{{ date('M Y', strtotime($year . '-' . $month . '-01')) }}</span>
		<span class="label label-success pull-right">MYR {{ $amount }}</span>
		<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
		<a href="{{ action('ReportAgencyTransactionController@excel', ['year' => $year, 'month' => $month, 'ou' => $agency->id]) }}" target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-excel-o"></i> Excel</a>
		<a href="{{ action('ReportAgencyTransactionController@receipts', ['year' => $year, 'month' => $month, 'ou' => $agency->id]) }}"  target="_blank" class="pull-right print hidden-print"><i class="fa fa-file-archive-o"></i> Resit</a>
	</h4>

	<table class="table table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th class="col-xs-3">Maklumat Tender</th>
				<th class="col-xs-2">Tarikh &amp; Masa</th>
				<th class="col-xs-3">Syarikat</th>
				<th>No Transaksi</th>
				<th>No Resit</th>
				<th>Kaedah Pembayaran</th>
				<th class="col-xs-2 text-right">Harga Dokumen (RM)</th>
			</tr>
		</thead>
		<tbody>
			@foreach($tenders as $ref_number => $transactions)
				<?php $count = 0; $sum = 0; ?>
				@foreach(array_sort($transactions, function($txn){ return $txn->transaction->created_at; }) as $txn)
					<tr>
						@if($count == 0)
						<td rowspan="{{ count($transactions) }}">
							<small><strong>{{ $txn->tender->ref_number }}</strong></small><br>
							{{ $txn->tender->name }}<br><br>
							Tarikh Iklan: {{ Carbon\Carbon::parse($txn->tender->advertise_start_date)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($txn->tender->advertise_stop_date)->format('d/m/Y') }}<br>
							Tarikh Jual: {{ Carbon\Carbon::parse($txn->tender->document_start_date)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($txn->tender->document_stop_date)->format('d/m/Y') }}<br>
							Tarikh Tutup: {{ Carbon\Carbon::parse($txn->tender->submission_datetime)->format('d/m/Y') }}<br>
							Jumlah Transaksi: {{ count($transactions) }}
						</td>
						@endif
						<td>{{ Carbon\Carbon::parse($txn->transaction->created_at)->format('d/m/Y H:i:s') }}</td>
						<td>@if(isset($txn->vendor)) {{ $txn->vendor->name }} @else {{ boolean_icon(false) }} @endif</td>
						<td>{{ $txn->transaction->number }}</td>
						<td>{{ $txn->transaction->receipt_number }}</td>
						<td>{{ App\Gateway::$methods[$txn->transaction->method] }}</td>
						<td class="text-right">{{ number_format($txn->amount, 2) }}</td>
					</tr>
					<?php $count++; $sum += $txn->amount; ?>
				@endforeach

				<tr>
					<th colspan="6" class="text-right">Jumlah</th>
					<th class="text-right">
						{{ number_format($sum, 2) }}
					</th>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th class="text-right" colspan="6">Jumlah Keseluruhan</th>
				<th class="text-right">{{ number_format($amount, 2) }}</th>
			</tr>
		</tfoot>
	</table>

@endsection
