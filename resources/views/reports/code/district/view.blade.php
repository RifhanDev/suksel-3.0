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
        LAPORAN JUMLAH BERKAITAN KOD BIDANG
    </h4>
    <h4 class="report-date"> Pada Tarikh Terkini</h4>
    <h4 class="report-date"> Jenis :
        @if ($type == 'active')
            Syarikat Aktif
        @elseif($type == 'update')
            Pendaftaran
        @elseif($type == 'register')
            Kemaskini
        @endif
    </h4>
    <table class="table table-bordered summary-table">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>Jenis Kod Bidang</th>
                <th>Daerah Petaling</th>
                <th>Daerah Klang</th>
                <th>Daerah Gombak</th>
                <th>Daerah Sepang</th>
                <th>Daerah Hulu Langat</th>
                <th>Daerah Kuala Langat</th>
                <th>Daerah Hulu Selangor</th>
                <th>Daerah Kuala Selangor</th>
                <th>Daerah Sabak Bernam</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responses as $response)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $response->{'Jenis Kod Bidang'} }}</td>
                    <td>{{ $response->{'Petaling'} }}</td>
                    <td>{{ $response->{'Klang'} }}</td>
                    <td>{{ $response->{'Gombak'} }}</td>
                    <td>{{ $response->{'Sepang'} }}</td>
                    <td>{{ $response->{'Hulu Langat'} }}</td>
                    <td>{{ $response->{'Kuala Langat'} }}</td>
                    <td>{{ $response->{'Hulu Selangor'} }}</td>
                    <td>{{ $response->{'Kuala Selangor'} }}</td>
                    <td>{{ $response->{'Sabak Bernam'} }}</td>
                    <td>{{ $response->{'Petaling'} + $response->{'Klang'} + $response->{'Gombak'} + $response->{'Sepang'} + $response->{'Hulu Langat'} + $response->{'Kuala Langat'} + $response->{'Hulu Selangor'} + $response->{'Kuala Selangor'} + $response->{'Sabak Bernam'} }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center">
                        Tiada Maklumat Dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@include('reports.footer-scripts')
