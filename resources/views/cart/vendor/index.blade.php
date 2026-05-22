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
        .cart-table thead th {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            background: #f8fafc;
            border-color: #e5e7eb;
            padding: 10px 16px;
        }
        .cart-table tbody td {
            font-size: 0.82rem;
            color: #374151;
            border-color: #e5e7eb;
            padding: 12px 16px;
            vertical-align: middle;
        }
        .cart-table tbody tr:hover { background: #fafafa; }
        .cart-table tfoot td {
            font-size: 0.82rem;
            color: #374151;
            border-color: #e5e7eb;
            padding: 10px 16px;
            background: #f8fafc;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Senarai Tempahan</h3>
            <p class="text-muted small m-0">Semak dan teruskan pembayaran dokumen tender.</p>
        </div>
        <a href="{{ asset('dashboard') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    @if (!$fpx && !$ebpg && !$duitnow)
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span style="font-size:0.85rem;font-weight:600;color:#991b1b;">Harap Maaf! Pembayaran tidak dapat dilakukan buat masa ini.</span>
        </div>
    @else
        <div class="vendor-tender-card">
            <div class="vendor-tender-card-header">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2a1 1 0 0 1 .993.883L7 3v1.068l13.071.935a1 1 0 0 1 .929 1.024l-.01.114l-1 7a1 1 0 0 1-.877.853L19 14H8v2h9a3 3 0 1 1-2.83 2h-4.34A3 3 0 1 1 7.172 16H7V4H5a1 1 0 0 1 0-2h1zm1 3.086V12h10.697l.802-5.611z"/></svg>
                </div>
                <h6>Senarai Tender / Sebut Harga</h6>
                <span class="ms-auto" style="font-size:0.72rem;color:#6b7280;">{{ count($tenders) }} item</span>
            </div>

            <div class="table-responsive">
                <table class="cart-table table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Petender</th>
                            <th>No / Tajuk</th>
                            <th>Tarikh Jual</th>
                            <th>Tarikh Tutup</th>
                            <th class="text-end">Harga (RM)</th>
                            <th class="text-center" style="width:80px;">Padam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenders as $tender)
                            <tr>
                                <td>
                                    <a href="{{ asset('agencies/' . $tender->organization_unit_id) }}" style="font-size:0.8rem;color:#6b7280;text-decoration:none;">
                                        {{ $tender->tenderer->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ asset('tenders/' . $tender->id) }}" class="fw-semibold d-block" style="font-size:0.85rem;color:#111827;text-decoration:none;">{{ $tender->ref_number }}</a>
                                    <span style="font-size:0.75rem;color:#6b7280;">{{ $tender->name }}</span>
                                </td>
                                <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($tender->document_start_date)->format('j M Y') }}</td>
                                <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}</td>
                                <td class="text-end fw-semibold">{{ sprintf('%.2f', $tender->price) }}</td>
                                <td class="text-center">
                                    <a href="{{ asset('cart/delete/' . $tender->id) }}"
                                        style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:600;color:#991b1b;text-decoration:none;padding:4px 10px;background:#fef2f2;border-radius:5px;border:1px solid #fecaca;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Padam
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted" style="font-size:0.85rem;">
                                    Tiada tender dalam senarai tempahan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($tenders) > 0)
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-semibold">Jumlah Tender</td>
                                <td colspan="2" class="fw-semibold">{{ count($tenders) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold" style="color:#111827;">Jumlah Bayaran</td>
                                <td colspan="2" class="fw-bold" style="color:#c41e3a;">RM {{ sprintf('%.2f', $amount) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            @if (count($tenders) > 0)
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="background:#f8fafc;">
                    <a href="{{ asset('cart/clear') }}" class="btn-form btn-form-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Batal Semua Tempahan
                    </a>
                    <a href="{{ asset('cart/checkout') }}" class="btn-form btn-form-success">
                        Teruskan Pembayaran
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            @endif

        </div>
    @endif

@endsection
