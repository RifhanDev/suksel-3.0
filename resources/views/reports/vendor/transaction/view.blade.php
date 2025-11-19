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
        LAPORAN TRANSAKSI
    </h4>

    <h4 class="report-date">
        @if ($type === 'year')
            Tahun {{ $year }}
        @elseif($type === 'month')
            Bulan {{ $month }}
        @elseif($type === 'week')
            Minggu {{ $week }}
        @endif
    </h4>

    <h4 class="report-date"> Agensi: {{ $agencyName }}</h4>

    <table class="table table-bordered summary-table table-condensed">
        <thead class="bg-blue-selangor">
            <tr>
                <th rowspan="2">Bil.</th>
                <th rowspan="2">Nama Syarikat</th>
                <th rowspan="2">Kod Hasil</th>
                <th rowspan="2">No Transaksi</th>
                <th rowspan="2">No Rujukan Gateway</th>
                <th rowspan="2">No Resit</th>
                <th rowspan="2">Tarikh Resit</th>
                <th colspan="6">Item</th>
                <th rowspan="2">Jumlah Keseluruhan</th>
            </tr>
            <tr>
                <th>Bil.</th>
                <th>No. Rujukan</th>
                <th>Mod Pembayaran</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Jumlah Kecil</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responses as $index => $response)
                @if ($response->details->isEmpty())
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $response->name }}</td>
                        <td rowspan="1">{{ $response->hasil_code }}</td>
                        <td>{{ $response->number }}</td>
                        <td>{{ $response->gateway_reference }}</td>
                        <td></td>
                        <td>{{ $response->created_at }}</td>
                        <td colspan="6">Tiada</td>
                        <td>0.00</td>
                    </tr>
                @else
                    @foreach ($response->details as $detail)
                        @if ($loop->first)
                            <tr>
                                <td rowspan="{{ $response->details->count() }}">{{ $index + 1 }}</td>
                                <td rowspan="{{ $response->details->count() }}">{{ strtoupper($response->name) }}</td>
                                <td rowspan="{{ $response->details->count() }}">{{ $response->hasil_code }}</td>
                                <td rowspan="{{ $response->details->count() }}">{{ $response->number }}</td>
                                <td rowspan="{{ $response->details->count() }}">{{ $response->gateway_reference }}</td>
                                <td rowspan="{{ $response->details->count() }}">
                                    {{ $response->receipt !== 'old' ? $response->receipt : $response->vendor_id . '-' . $response->gateway_reference }}
                                </td>
                                <td rowspan="{{ $response->details->count() }}">{{ $response->created_at }}</td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->ref_number }}</td>
                                <td>{{ $detail->method }}</td>
                                <td>{{ $detail->fpx_buyerBankId }}</td>
                                <td>
                                    @if ($detail->status === 'success')
                                        Berjaya
                                    @elseif ($detail->status === 'failed')
                                        Gagal
                                    @elseif ($detail->status === 'pending')
                                        Menunggu
                                    @endif
                                </td>
                                <td>{{ number_format($detail->amount, 2) }}</td>
                                <td rowspan="{{ $response->details->count() }}">
                                    {{ number_format($response->details->sum('amount'), 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->ref_number }}</td>
                                <td>{{ $detail->method }}</td>
                                <td>{{ $detail->fpx_buyerBankId }}</td>
                                <td>
                                    @if ($detail->status === 'success')
                                        Berjaya
                                    @elseif ($detail->status === 'failed')
                                        Gagal
                                    @elseif ($detail->status === 'pending')
                                        Menunggu
                                    @endif
                                </td>
                                <td>{{ number_format($detail->amount, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @empty
                <tr>
                    <td colspan="13" style="text-align: center">
                        Tiada Maklumat Dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tbody>
            <tr>
                <td colspan="10" style="text-align: right;"><strong>Jumlah Mengikut Status</strong></td>
                <td><strong>B2C</strong></td>
                <td><strong>B2B</strong></td>
                <td><strong>Jumlah Bil.</strong></td>
                <td><strong>Jumlah (RM)</strong></td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: right;">Berjaya</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'success')->where('fpx_buyerBankId', 'B2C')->count() }}</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'success')->where('fpx_buyerBankId', 'B2B')->count() }}</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'success')->count() }}</td>
                <td>{{ number_format($responses->pluck('details')->flatten()->where('status', 'success')->sum('amount'),2) }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: right;">Gagal</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'failed')->where('fpx_buyerBankId', 'B2C')->count() }}</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'failed')->where('fpx_buyerBankId', 'B2B')->count() }}</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'failed')->count() }}</td>
                <td>{{ number_format($responses->pluck('details')->flatten()->where('status', 'failed')->sum('amount'),2) }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: right;">Menunggu</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'pending')->where('fpx_buyerBankId', 'B2C')->count() }}</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'pending')->where('fpx_buyerBankId', 'B2B')->count() }}</td>
                <td>{{ $responses->pluck('details')->flatten()->where('status', 'pending')->count() }}</td>
                <td>{{ number_format($responses->pluck('details')->flatten()->where('status', 'pending')->sum('amount'),2) }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: right;"><strong>Jumlah Keseluruhan</strong></td>
                <td><strong>{{ $responses->pluck('details')->flatten()->whereIn('status', ['success', 'failed', 'pending'])->where('fpx_buyerBankId', 'B2C')->count() }}</strong></td>
                <td><strong>{{ $responses->pluck('details')->flatten()->whereIn('status', ['success', 'failed', 'pending'])->where('fpx_buyerBankId', 'B2B')->count() }}</strong></td>
                <td><strong>{{ $responses->pluck('details')->flatten()->whereIn('status', ['success', 'failed', 'pending'])->count() }}</strong></td>
                <td>
                    <strong>{{ number_format($responses->pluck('details')->flatten()->whereIn('status', ['success', 'failed', 'pending'])->sum('amount'),2) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
@endsection

@include('reports.footer-scripts')
