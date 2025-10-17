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
        <thead>
            <tr>
                <th>CIDB : {{ number_format($responses->where('type', 'cidb')->count(), 0) }} Syarikat</th>
                <th>MOF : {{ number_format($responses->where('type', 'mof')->count(), 0) }} Syarikat</th>
                <th>Alamat Emel : {{ number_format($responses->where('type', 'email')->count(), 0) }} Syarikat</th>
                <th>Alamat SSM : {{ number_format($responses->where('type', 'district')->count(), 0) }} Syarikat</th>
            </tr>
        </thead>
    </table>
    <table class="table table-bordered">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>Nama Syarikat</th>
                <th>Jenis Kemaskini</th>
                <th>Tarikh Permohonan</th>
                <th>Tarikh Diluluskan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responses as $response)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $response->name }}</td>
                    <td>
                        @if ($response->type == 'district')
                            Alamat SSM
                        @elseif($response->type == 'email')
                            Alamat Emel
                        @else
                            {{ strtoupper($response->type) }}
                        @endif
                    </td>
                    <td>{{ $response->created_at }}</td>
                    <td>{{ $response->updated_at }}</td>
                    <td>{{ $response->status == 'approved' ? 'LULUS' : 'DITOLAK' }}</td>
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
