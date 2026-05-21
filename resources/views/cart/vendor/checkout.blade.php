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
    </style>
@endsection

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Semak & Bayar</h3>
            <p class="text-muted small m-0">Semak senarai tempahan anda sebelum meneruskan pembayaran.</p>
        </div>
        <a href="{{ asset('cart') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
    </div>

    @if (!$fpx && !$ebpg && !$duitnow)
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span style="font-size:0.85rem;font-weight:600;color:#991b1b;">Harap Maaf! Pembayaran tidak dapat dilakukan buat masa ini.</span>
        </div>
    @else

        {{-- Tender List --}}
        <div class="vendor-tender-card">
            <div class="vendor-tender-card-header">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <h6>Senarai Tempahan</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;width:40px;">Bil.</th>
                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Agensi / No / Tajuk</th>
                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Tarikh Jual</th>
                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Tarikh Tutup</th>
                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;text-align:right;">Harga (RM)</th>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted" style="font-size:0.82rem;">Tiada tender dalam senarai tempahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($tenders) > 0)
                        <tfoot style="background:#f8fafc;">
                            <tr style="border-color:#e5e7eb;">
                                <td colspan="4" style="font-size:0.82rem;font-weight:600;color:#6b7280;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Jumlah Tender</td>
                                <td style="font-size:0.82rem;font-weight:700;color:#1f2937;padding:10px 20px;text-align:right;border-color:#e5e7eb;">{{ count($tenders) }}</td>
                            </tr>
                            <tr style="border-color:#e5e7eb;">
                                <td colspan="4" style="font-size:0.88rem;font-weight:700;color:#111827;padding:10px 20px;text-align:right;border-color:#e5e7eb;">Jumlah Bayaran</td>
                                <td style="font-size:0.88rem;font-weight:800;color:#c41e3a;padding:10px 20px;text-align:right;border-color:#e5e7eb;white-space:nowrap;">RM {{ number_format($amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        @if (count($tenders) > 0)

            {{-- Payment Section --}}
            <div class="vendor-tender-card" style="overflow:visible;">
                <div class="vendor-tender-card-header">
                    <div class="header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <h6>Pilihan Pembayaran</h6>
                </div>
                <div style="padding:20px;">

                    {!! Former::open(route('cart.process'))->class('form-inline disabled-submit') !!}
                    <input type="hidden" name="method">

                    @if ($amount > 0.0)
                        <p style="font-size:0.82rem;color:#6b7280;margin-bottom:16px;">Pembelian Dokumen Tender / Sebut Harga boleh dilakukan menggunakan kaedah berikut:</p>
                        <div class="d-flex flex-wrap gap-2">
                            @if ($fpx)
                                <div class="dropdown">
                                    <button type="button" class="btn-form btn-form-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        Internet Banking (FPX)
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item method-ob" href="#" data-value="fpx-1" style="font-size:0.82rem;">Perbankan Peribadi</a></li>
                                        @unless ($fpx->private_key == 'b2c')
                                            <li><a class="dropdown-item method-ob" href="#" data-value="fpx-2" style="font-size:0.82rem;">Perbankan Korporat</a></li>
                                        @endunless
                                        @if (config('app.env') !== 'production')
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item method-ob" href="#" data-value="direct" style="font-size:0.82rem;color:#166534;">[DEV] Bypass Bayaran</a></li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                            @if ($ebpg)
                                <button type="button" class="btn-form btn-form-secondary method-ob" data-value="ebpg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    Kad Kredit
                                </button>
                            @endif
                            @if ($duitnow)
                                <button type="button" class="btn-form btn-form-success method-ob" data-value="duitnow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                    DuitNow
                                </button>
                            @endif
                        </div>
                    @else
                        <p style="font-size:0.82rem;color:#6b7280;margin-bottom:16px;">Tiada bayaran diperlukan untuk senarai tempahan ini.</p>
                        <button type="button" class="btn-form btn-form-success method-ob" data-value="direct">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Teruskan
                        </button>
                    @endif

                    {!! Former::close() !!}
                </div>
            </div>

        @endif

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
