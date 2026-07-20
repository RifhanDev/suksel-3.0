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

        h5{
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
    <h4 class="tender-title">
        LAPORAN TRANSAKSI MENGIKUT KOD AKAUN HASIL
    </h4>
    @if ($type == 'year')
        <h4 class="report-date"> Tahun {{ $year }}</h4>
    @elseif($type == 'month')
        <h4 class="report-date"> Bulan {{ $month }}</h4>
    @elseif($type == 'week')
        <h4 class="report-date"> Minggu {{ $week }}</h4>
    @endif
    <h5>Kod Hasil: 73105</h5>
    <table class="table table-bordered summary-table">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>Kod Hasil</th>
                <th>Tarikh Transaksi</th>
                <th>Masa Transaksi</th>
                <th>Jenis Pembayaran</th>
                <th>Jumlah Kecil</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responses_purchase as $response)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $response->hasil_code }}</td>
                    <td>{{ date('d/m/Y',strtotime($response->created_at)) }}</td>
                    <td>{{ date('h:m:i A',strtotime($response->created_at)) }}</td>
                    <td>{{ $response->method }}</td>
                    <td>{{ $response->amount }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center">
                        Tiada Maklumat Dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <hr>
    <h5>Kod Hasil: 71399</h5>
    <table class="table table-bordered summary-table">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>Kod Hasil</th>
                <th>Tarikh Transaksi</th>
                <th>Masa Transaksi</th>
                <th>Jenis Pembayaran</th>
                <th>Jumlah Kecil</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responses_subscription as $response)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $response->hasil_code }}</td>
                    <td>{{ date('d/m/Y',strtotime($response->created_at)) }}</td>
                    <td>{{ date('h:m:i A',strtotime($response->created_at)) }}</td>
                    <td>{{ $response->method }}</td>
                    <td>{{ $response->amount }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center">
                        Tiada Maklumat Dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@include('reports.footer-scripts')
