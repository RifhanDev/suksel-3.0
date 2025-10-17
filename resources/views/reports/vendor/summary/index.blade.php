@extends('layouts.default')

@section('styles')
    <style>
        @media print {
            .default-dashboard {
                page-break-after: always;
            }

            .chart-dashboard {
                page-break-after: always;
            }

            .panel {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <a onclick="window.print()" class="pull-right print hidden-print" target="_new"><i class="fa fa-print"></i>
                Cetak</a>
        </div>
    </div>
    <form id="tender_summary" class="form-inline">
        <h3 class="tender-title">Laporan Transaksi Syarikat :
            <input class="form-control" id="year_summary" type="text" name="year_summary"
                style="width:10%;font-weight: bold;" autocomplete="off">
        </h3>
    </form>
    <div class="default-dashboard">
        <div class="col-sm-4">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h2>{{ number_format($total_transaction, 0) }}</h2>
                </div>
                <div class="panel-heading">
                    <span style="color:white">
                        Bilangan Transaksi Keseluruhan
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h2>{{ 'RM' . number_format($total_sum->total ?? 0, 2) }}</h2>
                </div>
                <div class="panel-heading">
                    <span style="color:white">
                        Nilai Transaksi Keseluruhan
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h2>{{ number_format($total_transaction_yearly, 0) }}</h2>
                </div>
                <div class="panel-heading">
                    <span style="color:white">
                        Bilangan Transaksi Mengikut Tahun Semasa
                    </span>
                </div>
            </div>
        </div>
    </div>

    <h4>SENARAI TRANSAKSI TAHUN {{ $year }}</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align: center">BIL.</th>
                <th style="text-align: center">TAJUK</th>
                <th style="text-align: center">TARIKH PEMBELIAN</th>
                <th style="text-align: center">HARGA DOKUMEN TENDER (RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lists as $list)
                <tr>
                    <td style="text-align: center">{{ $loop->index + 1 }}</td>
                    <td>{{ $list->type == 'purchase' ? strtoupper($list->purchases[0]->tender->name) : 'LANGGANAN SISTEM TENDER ONLINE SELANGOR' }}
                    </td>
                    <td style="text-align: center">{{ Carbon::parse($list->created_at)->format('j/m/Y h:iA') }}</td>
                    <td style="text-align: center">{{ $list->amount }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center"> TIADA TRANSAKSI DIJUMPAI.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="3" style="text-align: right;"><b>JUMLAH NILAI TRANSAKSI TAHUN {{ $year }}</b></td>
                <td style="text-align: center">
                    RM {{ number_format($lists->sum('amount'), 2) }}
                </td>
            </tr>
        </tbody>
    </table>
@endsection
@section('scripts')
    <script>
        var year = {{ $year }};
        const vendor_id = {{ $vendor_id }};
    </script>
    <script src="{{ asset('js/report-vendor.js') }}"></script>
    <script>
        $("#year_summary").change(function() {
          var url = "{{ route('report.vendor.summary', ['year' => ':year','vendor_id' => ':vendor_id']) }}"; // get selected value
          url = url.replace(':year',$(this).val());
          url = url.replace(':vendor_id',vendor_id);
          if (url) { // require a URL
              window.location.href  = url;
          }
          return false;
        });
    </script>
@endsection
