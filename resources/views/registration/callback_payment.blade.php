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
        .info-table { width: 100%; }
        .info-table tr { border-bottom: 1px solid #f1f5f9; }
        .info-table tr:last-child { border-bottom: none; }
        .info-table th {
            padding: 10px 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            width: 40%;
            vertical-align: top;
        }
        .info-table td {
            padding: 10px 20px;
            font-size: 0.82rem;
            color: #1f2937;
            vertical-align: top;
        }
        .step-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .step-wrapper::before {
            content: '';
            position: absolute;
            top: 16px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: 0;
        }
        .step-item {
            position: relative;
            z-index: 1;
            background: #f3f4f6;
            padding: 0 10px;
            text-align: center;
        }
        .step-number {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #cbd5e1;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 8px;
            border: 4px solid #f3f4f6;
        }
        .step-item.completed .step-number { background: #10b981; }
        .step-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .step-item.completed .step-label { color: #10b981; }
    </style>
@endsection

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Pendaftaran Vendor</h3>
            <p class="text-muted small m-0">{{ $transaction->status == 'success' ? 'Pendaftaran anda telah berjaya diselesaikan.' : 'Pembayaran tidak berjaya.' }}</p>
        </div>
    </div>

    {{-- Step Progress --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-12">
            <div class="step-wrapper">
                <div class="step-item completed">
                    <div class="step-number">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="step-label">E-mel</div>
                </div>
                <div class="step-item completed">
                    <div class="step-number">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="step-label">Maklumat</div>
                </div>
                <div class="step-item completed">
                    <div class="step-number">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="step-label">Pembayaran</div>
                </div>
            </div>
        </div>
    </div>

    @if ($transaction->status == 'success')

        {{-- Success Banner --}}
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#f0fdf4;border:1px solid #86efac;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="20 6 9 17 4 12"/></svg>
            <div>
                <div style="font-size:0.92rem;font-weight:700;color:#166534;">Langganan Berjaya!</div>
                <div style="font-size:0.8rem;color:#16a34a;">Akaun vendor anda telah diaktifkan. Anda kini boleh menggunakan sistem.</div>
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="vendor-tender-card">
            <div class="vendor-tender-card-header">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <h6>Maklumat Transaksi</h6>
            </div>
            <table class="info-table">
                <tr>
                    <th>No Transaksi</th>
                    <td>{{ $transaction->number }}</td>
                </tr>
                <tr>
                    <th>No Resit</th>
                    <td>{{ $transaction->vendor_id }}-{{ $transaction->gateway_reference }}</td>
                </tr>
                <tr>
                    <th>Kaedah Pembayaran</th>
                    <td>{{ App\Gateway::$methods[$transaction->method] }}</td>
                </tr>
                <tr>
                    <th>No Rujukan Pembayaran</th>
                    <td>{{ $transaction->gateway_reference }}</td>
                </tr>
                <tr>
                    <th>Jumlah Pembayaran</th>
                    <td><strong>RM {{ number_format($transaction->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <th>Tempoh Langganan</th>
                    <td>
                        {{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}
                        &rarr;
                        {{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('vendors.subscriptions.receipt', [$vendor->id, $subscription->id]) }}" target="_blank" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Lihat Resit
            </a>
            <a href="{{ route('vendor') }}" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Selesai
            </a>
        </div>

    @else

        {{-- Failed Banner --}}
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>
                <div style="font-size:0.92rem;font-weight:700;color:#991b1b;">Langganan Tidak Berjaya</div>
                <div style="font-size:0.8rem;color:#b91c1c;">Sila cuba semula atau hubungi pihak pentadbir jika masalah berterusan.</div>
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="vendor-tender-card">
            <div class="vendor-tender-card-header">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <h6>Maklumat Transaksi</h6>
            </div>
            <table class="info-table">
                <tr>
                    <th>No Transaksi</th>
                    <td>{{ $transaction->number }}</td>
                </tr>
                <tr>
                    <th>No Rujukan Pembayaran</th>
                    <td>{{ $transaction->gateway_reference }}</td>
                </tr>
                <tr>
                    <th>Jumlah Pembayaran</th>
                    <td><strong>RM {{ number_format($transaction->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <th>Kaedah Pembayaran</th>
                    <td>{{ App\Gateway::$methods[$transaction->method] }}</td>
                </tr>
                <tr>
                    <th>Mesej</th>
                    <td class="text-danger">{{ $transaction->response_code }}: {{ $transaction->response_message }}</td>
                </tr>
            </table>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('payment_registration') }}" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.45"/></svg>
                Cuba Semula
            </a>
        </div>

    @endif

@endsection
