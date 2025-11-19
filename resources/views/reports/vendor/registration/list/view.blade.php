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
        LAPORAN PENDAFTARAN SYARIKAT
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
                <th>No Pendaftaran</th>
                <th>Nama Syarikat</th>
                <th>Alamat Emel</th>
                <th>Tindakan</th>
                <th>Tarikh Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responses as $response)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $response->registration }}</td>
                    <td>{{ $response->name }}</td>
                    <td>{{ $response->email }}</td>
                    <td>
                        @if ($response->action == 'reject')
                            Ditolak
                        @elseif($response->action == 'approve')
                            Lulus
                        @endif
                    </td>
                    <td>{{ $response->created_at }}</td>
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
