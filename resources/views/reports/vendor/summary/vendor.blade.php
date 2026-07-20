@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        .vendor-tender-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .vendor-tender-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .vendor-tender-card-header h6 {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .vendor-tender-card-header .header-icon {
            width: 28px; height: 28px;
            background: rgba(196,30,58,0.08);
            color: #c41e3a;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .report-table thead th {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            background: #f8fafc;
            border-color: #e5e7eb;
            padding: 10px 16px;
            text-align: center;
        }
        .report-table tbody td {
            font-size: 0.82rem;
            color: #374151;
            border-color: #e5e7eb;
            padding: 11px 16px;
            vertical-align: middle;
        }
        .report-table tfoot td {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
            border-color: #e5e7eb;
            padding: 11px 16px;
            background: #f8fafc;
        }
        @media print {
            .no-print { display: none !important; }
            .vendor-tender-card { box-shadow: none; border: 1px solid #e5e7eb; }
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 no-print" style="flex-wrap:wrap;gap:12px;">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Laporan Transaksi Syarikat</h3>
            <p class="text-muted small m-0">Ringkasan transaksi pembelian dokumen tender.</p>
        </div>
        <div style="display:flex;flex-direction:row;gap:8px;flex-shrink:0;">
            <button onclick="window.print()" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak
            </button>
            <a href="{{ asset('dashboard') }}" class="btn-form btn-form-secondary no-print">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Year selector --}}
    <div class="vendor-tender-card mb-4 no-print">
        <div class="vendor-tender-card-header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h6>Pilih Tahun Laporan</h6>
        </div>
        <div class="p-4">
            <div class="d-flex align-items-center gap-3">
                <label class="fw-medium small text-muted" style="white-space:nowrap;">Tahun Laporan:</label>
                <input class="form-control" id="year_summary" type="text" name="year_summary"
                    value="{{ $year }}" autocomplete="off" style="max-width:120px;">
            </div>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="vendor-tender-card h-100">
                <div class="p-4 text-center">
                    <div style="font-size:2rem;font-weight:800;color:#c41e3a;line-height:1;">{{ number_format($total_transaction, 0) }}</div>
                    <div class="text-muted mt-2" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Bilangan Transaksi Keseluruhan</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vendor-tender-card h-100">
                <div class="p-4 text-center">
                    <div style="font-size:2rem;font-weight:800;color:#c41e3a;line-height:1;">RM {{ number_format($total_sum->total ?? 0, 2) }}</div>
                    <div class="text-muted mt-2" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Nilai Transaksi Keseluruhan</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vendor-tender-card h-100">
                <div class="p-4 text-center">
                    <div style="font-size:2rem;font-weight:800;color:#c41e3a;line-height:1;">{{ number_format($total_transaction_yearly, 0) }}</div>
                    <div class="text-muted mt-2" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Bilangan Transaksi Tahun {{ $year }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction list --}}
    <div class="vendor-tender-card">
        <div class="vendor-tender-card-header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <h6>Senarai Transaksi Tahun {{ $year }}</h6>
        </div>
        <div class="table-responsive">
            <table class="report-table table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">Bil.</th>
                        <th class="text-start">Tajuk</th>
                        <th>Tarikh Pembelian</th>
                        <th>Harga Dokumen (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lists as $list)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->index + 1 }}</td>
                            <td>{{ $list->type == 'purchase' ? strtoupper($list->purchases[0]->tender->name) : 'LANGGANAN SISTEM TENDER ONLINE SELANGOR' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($list->created_at)->format('j/m/Y h:iA') }}</td>
                            <td class="text-center">{{ $list->amount }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.85rem;">Tiada transaksi dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Jumlah Nilai Transaksi Tahun {{ $year }}</td>
                        <td class="text-center" style="color:#c41e3a;">RM {{ number_format($lists->sum('amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var year = {{ $year }};
        const vendor_id = {{ $vendor_id }};
    </script>
    <script src="{{ asset('js/report-vendor.js') }}"></script>
    <script>
        $("#year_summary").change(function() {
            var url = "{{ route('vendor.report.vendor.summary', ['year' => ':year', 'vendor_id' => ':vendor_id']) }}";
            url = url.replace(':year', $(this).val());
            url = url.replace(':vendor_id', vendor_id);
            if (url) {
                window.location.href = url;
            }
            return false;
        });
    </script>
@endsection
