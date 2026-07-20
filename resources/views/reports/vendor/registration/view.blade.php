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
        LAPORAN PERMOHONAN KEMASKINI MAKLUMAT SYARIKAT
    </h4>
    @if ($type == 'year')
        <h4 class="report-date"> Tahun {{ $year }}</h4>
    @elseif($type == 'month')
        <h4 class="report-date"> Bulan {{ $month }}</h4>
    @elseif($type == 'week')
        <h4 class="report-date"> Minggu {{ $week }}</h4>
    @endif

    <table class="table table-bordered summary-table">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>Status</th>
                <th>Bilangan Syarikat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Pendaftaran Belum Selesai</td>
                <td>{{ number_format($pendingCount, 0) }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Pendaftaran Belum Diluluskan</td>
                <td>{{ number_format($pendingApprovalCount, 0) }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Permintaan Kemaskini</td>
                <td>{{ number_format($pendingRequest, 0) }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Syarikat Aktif</td>
                <td>{{ number_format($active, 0) }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Syarikat Tidak Aktif</td>
                <td>{{ number_format($unactive, 0) }}</td>
            </tr>
            <tr>
                <td colspan="2"><b>JUMLAH KESELURUHAN</b></td>
                <td>{{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>
@endsection

@include('reports.footer-scripts')
