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

        .table.summary-table td {
            text-align: center;
        }

        .noborder td,
        .noborder th {
            border: none !important;
        }
    </style>
@endsection
@section('content')
    <a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
    <h4 class="tender-title">
        JADUAL {{ $label }}
    </h4>
    <h4 class="report-date">{{ strtoupper($organization->name) }}</h4>
    <table class="table noborder table-condensed">
        <tr style="border-color: transparent !important;">
            <th style="width: 20%">TAJUK {{ $label }}</th>
            <th>: {{ $tender->name }}</th>
        </tr>
        <tr>
            <th>NO. {{ $label }}</th>
            <th>: {{ $tender->ref_number }}</th>
        </tr>
        <tr>
            <th>TARIKH BUKA {{ $label }}</th>
            <th>: {{ date('d/m/Y', strtotime($tender->advertise_start_date)) }}</th>
        </tr>
    </table>
    <table class="table table-bordered summary-table">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>Nama Syarikat</th>
                <th>Taraf</th>
                <th>Label</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchasers as $purchaser)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $purchaser->vendor->name }}</td>
                    <td>{{ $purchaser->vendor->getBumiputeraCompanyAttribute() ? 'Bumiputera' : 'Bukan Bumiputera' }}</td>
                    <td>{{ $purchaser->label }}/{{ $purchasers->count() }}</td>
                    <td>{{ $purchaser->price }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center">
                        Tiada Petender Dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-5 mt-5">
        <table class="table noborder table-condensed">
            <tr>
                <td colspan="2">
                    ......................................................................................
                </td>
                <td colspan="2">
                    ......................................................................................
                </td>
            </tr>
            <tr>
                <th style="width: 10%">
                    NAMA
                </th>
                <td style="width: 60%">
                    : {{ $tender->creator->name }}
                </td>
                <th style="width: 10%">
                    NAMA
                </th>
                <td style="width: 40%">
                    : {{ $tender->officer->name ?? ''}}
                </td>
            </tr>
            <tr>
                <th>
                    JAWATAN
                </th>
                <td>
                    : 
                </td>
                <th>
                    JAWATAN
                </th>
                <td>
                    : 
                </td>
            </tr>
            <tr>
                <th>
                    TARIKH
                </th>
                <td>
                    : 
                </td>
                <th>
                    TARIKH
                </th>
                <td>
                    : 
                </td>
            </tr>
        </table>
    </div>
@endsection

@include('reports.footer-scripts')
