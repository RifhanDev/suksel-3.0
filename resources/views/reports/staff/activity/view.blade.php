@extends('layouts.report')
@section('styles')
    <style>
        h4 {
            text-align: center;
        }

        .report-date {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .tender-title {
            margin-bottom: 50px;
            font-weight: bolder
        }

        .summary-table {
            margin-bottom: 30px;
        }

        table.summary-table {
            width: 100%;
            table-layout: fixed;
        }

        table.summary-table th {
            text-align: center;
            height: 50px;

        }

        .table td {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
    <h4 class="tender-title">
        LAPORAN AKTIVITI PENGGUNA SISTEM
    </h4>
    @if ($type == 'year')
        <h4 class="report-date"> Tahun {{ $year }}</h4>
    @elseif($type == 'month')
        <h4 class="report-date"> Bulan {{ $month }}</h4>
    @endif
    <table class="table table-bordered">
        <thead class="bg-blue-selangor">
            <tr>
				<th rowspan="2">Bil.</th>
				<th rowspan="2">Nama</th>
				<th colspan="{{ count($tender_activities) }}">Aktiviti Tender</th>
				<th colspan="{{ count($vendor_activities) }}">Aktiviti Syarikat</th>
				<th rowspan="2">Permintaan Perubahan</th>
				<th rowspan="2">Jumlah</th>
			</tr>
			<tr>
				@foreach($tender_activities as $activity)<th>{{ App\TenderHistory::$types[$activity] }}</th>@endforeach
				@foreach($vendor_activities as $activity)<th>{{ App\VendorHistory::$types[$activity] }}</th>@endforeach
			</tr>
        </thead>
        <tbody>
	    	@forelse($data[1] as $user => $number)
		    	<tr>
			      <td>{{ $loop->index }}</td>
			      <td>{{ $user }}</td>
			      @foreach($tender_activities as $activity)<td>{{ $number[$activity] }}</td>@endforeach
			      @foreach($vendor_activities as $activity)<td>{{ $number[$activity] }}</td>@endforeach
			      <td>{{ $number['change-request'] }}</td>
			      <td>{{ $number['total'] }}</td>
		    	</tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center">
                        Tiada Maklumat Dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@include('reports.footer-scripts')
