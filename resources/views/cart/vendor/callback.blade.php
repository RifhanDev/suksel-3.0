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
        @media print {
            .no-print { display: none !important; }
        }
    </style>
@endsection

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 no-print">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Resit Pembelian</h3>
            <p class="text-muted small m-0">Butiran transaksi pembelian dokumen tender.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak
            </button>
            <a href="{{ route('dashboard') }}" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Akaun Saya
            </a>
        </div>
    </div>

    {{-- Status Banner --}}
    @if ($transaction->status == 'success')
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#f0fdf4;border:1px solid #86efac;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="20 6 9 17 4 12"/></svg>
            <span style="font-size:0.88rem;font-weight:600;color:#166534;">Pembelian Dokumen Anda Berjaya!</span>
        </div>
    @elseif ($transaction->status == 'failed')
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>
                <div style="font-size:0.88rem;font-weight:600;color:#991b1b;">Pembayaran Anda Gagal</div>
                <div style="font-size:0.78rem;color:#b91c1c;">({{ $transaction->response_code }}) {{ $transaction->response_message }}</div>
            </div>
        </div>
    @elseif ($transaction->status == 'pending_authorization')
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#eff6ff;border:1px solid #bae6fd;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span style="font-size:0.88rem;font-weight:600;color:#0369a1;">Pembayaran Anda Dalam Proses Pengesahan</span>
        </div>
    @endif

    {{-- Tender List --}}
    <div class="vendor-tender-card">
        <div class="vendor-tender-card-header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h6>Senarai Dokumen Dibeli</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;width:40px;">Bil.</th>
                        <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Agensi / No / Tajuk</th>
                        <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Tarikh Jual</th>
                        <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Tarikh Serahan</th>
                        <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;text-align:right;white-space:nowrap;">Harga (RM)</th>
                        @if ($transaction->status == 'success')
                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;text-align:center;">Dokumen</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenders as $tender)
                        <tr style="border-color:#f1f5f9;">
                            <td style="font-size:0.78rem;color:#9ca3af;padding:12px 20px;border-color:#f1f5f9;">{{ $loop->index + 1 }}.</td>
                            <td style="padding:12px 20px;border-color:#f1f5f9;">
                                <div style="font-size:0.72rem;color:#6b7280;margin-bottom:2px;">{{ $tender->tenderer->name }}</div>
                                <div style="font-size:0.72rem;font-weight:600;color:#6b7280;margin-bottom:3px;">{{ $tender->ref_number }}</div>
                                <a href="{{ asset('tenders/' . $tender->id) }}" style="font-size:0.85rem;font-weight:600;color:#1f2937;text-decoration:none;">{{ $tender->name }}</a>
                            </td>
                            <td style="font-size:0.82rem;color:#374151;padding:12px 20px;border-color:#f1f5f9;">
                                {{ \Carbon\Carbon::parse($tender->document_start_date)->format('j M Y') }}
                            </td>
                            <td style="font-size:0.82rem;color:#374151;padding:12px 20px;border-color:#f1f5f9;">
                                {{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}
                            </td>
                            <td style="font-size:0.88rem;font-weight:700;color:#1f2937;padding:12px 20px;border-color:#f1f5f9;text-align:right;white-space:nowrap;">
                                RM {{ number_format($tender->price, 2) }}
                            </td>
                            @if ($transaction->status == 'success')
                                @php $participant = $tender->participants()->whereVendorId(Auth::user()->vendor_id)->first(); @endphp
                                <td style="padding:12px 20px;border-color:#f1f5f9;text-align:center;">
                                    @if ($participant)
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <a href="{{ asset('tenders/' . $tender->id . '/receipt/' . $participant->id) }}" target="_blank"
                                                style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:600;color:#0369a1;padding:4px 10px;background:#f0f9ff;border-radius:5px;border:1px solid #bae6fd;text-decoration:none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                Resit
                                            </a>
                                            <a href="{{ asset('tenders/' . $tender->id . '/document/' . $participant->id) }}" target="_blank"
                                                style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:600;color:#d97706;padding:4px 10px;background:#fffbeb;border-radius:5px;border:1px solid #fde68a;text-decoration:none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                No. Siri
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $transaction->status == 'success' ? 6 : 5 }}" class="text-center py-4 text-muted" style="font-size:0.82rem;">Tiada rekod.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot style="background:#f8fafc;">
                    <tr style="border-color:#e5e7eb;">
                        <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Jumlah Tender</td>
                        <td style="font-size:0.82rem;font-weight:700;color:#1f2937;padding:10px 20px;text-align:right;border-color:#e5e7eb;white-space:nowrap;">{{ count($tenders) }}</td>
                    </tr>
                    <tr style="border-color:#e5e7eb;">
                        <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.88rem;font-weight:700;color:#111827;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Jumlah Bayaran</td>
                        <td style="font-size:0.88rem;font-weight:800;color:#c41e3a;padding:10px 20px;text-align:right;border-color:#e5e7eb;white-space:nowrap;">RM {{ number_format($amount, 2) }}</td>
                    </tr>
                    <tr style="border-color:#e5e7eb;">
                        <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Tarikh &amp; Masa Bayaran</td>
                        <td style="font-size:0.82rem;color:#374151;padding:10px 20px;text-align:right;border-color:#e5e7eb;white-space:nowrap;">{{ $transaction->sellerTxnTime }}</td>
                    </tr>
                    <tr style="border-color:#e5e7eb;">
                        <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">No Transaksi</td>
                        <td style="font-size:0.82rem;color:#374151;padding:10px 20px;text-align:right;border-color:#e5e7eb;">{{ $transaction->number }}</td>
                    </tr>
                    @if ($transaction->status == 'success')
                        <tr style="border-color:#e5e7eb;">
                            <td colspan="5" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">No Resit</td>
                            <td style="font-size:0.82rem;color:#374151;padding:10px 20px;text-align:right;border-color:#e5e7eb;">{{ $transaction->vendor_id }}-{{ $transaction->gateway_reference }}</td>
                        </tr>
                    @endif
                    <tr style="border-color:#e5e7eb;">
                        <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Kaedah Pembayaran</td>
                        <td style="font-size:0.82rem;color:#374151;padding:10px 20px;text-align:right;border-color:#e5e7eb;">{{ App\Gateway::$methods[$transaction->method] }}</td>
                    </tr>
                    <tr style="border-color:#e5e7eb;">
                        <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">No Rujukan Pembayaran</td>
                        <td style="font-size:0.82rem;color:#374151;padding:10px 20px;text-align:right;border-color:#e5e7eb;">{{ $transaction->gateway_reference }}</td>
                    </tr>
                    @if ($transaction->method == 'fpx' && $transaction->bank_name)
                        <tr style="border-color:#e5e7eb;">
                            <td colspan="{{ $transaction->status == 'success' ? 5 : 4 }}" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Bank Pembayaran</td>
                            <td style="font-size:0.82rem;color:#374151;padding:10px 20px;text-align:right;border-color:#e5e7eb;">{{ $transaction->bank_name }}</td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Retry payment if failed --}}
    @if ($transaction->status == 'failed')
        <div class="vendor-tender-card" style="overflow:visible;">
            <div class="vendor-tender-card-header">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <h6>Cuba Semula Pembayaran</h6>
            </div>
            <div style="padding:20px;">
                {!! Former::open(route('cart.process'))->class('form-inline') !!}
                <input type="hidden" name="method">
                <div class="d-flex flex-wrap gap-2">
                    @if ($amount > 0.0)
                        @if ($fpx)
                            <div class="dropdown">
                                <button type="button" class="btn-form btn-form-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    Internet Banking (FPX)
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item method-ob" href="#" data-value="fpx-1" style="font-size:0.82rem;">Perbankan Peribadi</a></li>
                                    <li><a class="dropdown-item method-ob" href="#" data-value="fpx-2" style="font-size:0.82rem;">Perbankan Korporat</a></li>
                                    @if (config('app.env') !== 'production')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item method-ob" href="#" data-value="direct" style="font-size:0.82rem;color:#166534;">[DEV] Bypass Bayaran</a></li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        @if ($ebpg)
                            <button type="button" class="btn-form btn-form-secondary method-ob" data-value="ebpg">Kad Kredit</button>
                        @endif
                        @if ($duitnow)
                            <button type="button" class="btn-form btn-form-success method-ob" data-value="duitnow">DuitNow</button>
                        @endif
                    @else
                        <button type="button" class="btn-form btn-form-primary method-ob" data-value="direct">Teruskan</button>
                    @endif
                </div>
                {!! Former::close() !!}
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script type="text/javascript">
        $('.method-ob').click(function() {
            var method = $(this).data('value');
            $('input[name=method]').val(method);
            $(this).closest('form').submit();
        });
    </script>
@endsection
