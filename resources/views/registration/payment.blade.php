@extends('layouts.modernLanding')

@section('styles')
<style>
    .stats-card, .price-card, .payment-option-btn, .rounded-12 {
        border-radius: 12px !important;
    }

    /* Step Indicator */
    .step-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2.5rem;
        position: relative;
        max-width: 600px;
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
        transition: all 0.3s ease;
    }
    .step-item.active .step-number {
        background: var(--sg-red);
        box-shadow: 0 0 0 4px rgba(196, 30, 58, 0.1);
    }
    .step-item.completed .step-number {
        background: #10b981;
    }
    .step-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .step-item.active .step-label {
        color: var(--sg-red);
    }
    .step-item.completed .step-label {
        color: #10b981;
    }

    /* Pricing Card */
    .price-card {
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .price-header {
        background: linear-gradient(145deg, var(--sg-red), var(--sg-red-dark));
        padding: 2.5rem 2rem;
        text-align: center;
        color: white;
    }
    .price-amount {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 0;
        letter-spacing: -2px;
        line-height: 1;
    }
    .price-currency {
        font-size: 1.25rem;
        vertical-align: super;
        margin-right: 5px;
        font-weight: 600;
    }
    .price-body {
        padding: 2rem;
        text-align: center;
    }

    /* Payment Button */
    .payment-option-btn {
        width: 100%;
        padding: 1.1rem 1.25rem;
        margin-bottom: 1rem;
        border: 2px solid #f1f5f9;
        background: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
        text-align: left;
        cursor: pointer;
    }
    .payment-option-btn:hover {
        border-color: var(--sg-red);
        background: var(--sg-red-soft);
        transform: translateY(-2px);
    }
    .payment-icon-box {
        width: 44px;
        height: 44px;
        background: #f8fafc;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--sg-red);
        flex-shrink: 0;
    }
    .payment-option-btn:hover .payment-icon-box {
        background: white;
    }

    /* Dropdown */
    .dropdown-menu-payment {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 8px;
        width: 100%;
    }
    .dropdown-item-payment {
        padding: 12px 15px;
        border-radius: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dropdown-item-payment:hover {
        background-color: var(--sg-red-soft);
        color: var(--sg-red);
    }
</style>
@endsection

@section('content')

<!-- HEADER -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
    <div>
        <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pendaftaran Vendor</h3>
        <p class="text-muted small m-0">Sila selesaikan pembayaran langganan untuk pengaktifan sistem.</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border mt-3 mt-lg-0">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-danger border border-danger border-opacity-25">PROSES</span>
            <span class="small text-muted fw-bold">PEMBAYARAN</span>
        </div>
    </div>
</div>

<!-- STEP PROGRESS -->
<div class="row justify-content-center mb-4">
    <div class="col-md-12">
        <div class="step-wrapper">
            <div class="step-item completed">
                <div class="step-number">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-label">E-mel</div>
            </div>
            <div class="step-item completed">
                <div class="step-number">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-label">Maklumat</div>
            </div>
            <div class="step-item active">
                <div class="step-number">3</div>
                <div class="step-label">Pembayaran</div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">

    <div class="col-lg-5 col-xl-4 mb-4">
        <div class="price-card">
            <div class="price-header">
                <div class="small text-uppercase fw-bold opacity-75 mb-2" style="letter-spacing: 1px;">Yuran Langganan</div>
                <h1 class="price-amount"><span class="price-currency">RM</span>100</h1>
            </div>
            <div class="price-body">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div class="bg-warning bg-opacity-10 text-warning fw-bold px-3 py-1 rounded-pill small fw-800">
                        AKSES 1 TAHUN
                    </div>
                </div>
                
                <div class="p-3 bg-light rounded-12 mb-2">
                    <div class="small text-muted mb-1">Tempoh Langganan Berkuatkuasa:</div>
                    <div class="fw-bold text-dark d-flex align-items-center justify-content-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }} 
                        <span class="text-muted fw-normal">→</span> 
                        {{ \Carbon\Carbon::now()->addYear()->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-5">
        <div class="stats-card p-4 h-100">
            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                Pilih Kaedah Pembayaran
            </h5>

            @if ($fpx || $ebpg || $duitnow)
                {!! Former::open(action('RegistrationController@storePayment'))->class('disabled-submit') !!}
                <input type="hidden" name="method">
                
                <div class="payment-list">
                    {{-- CREDIT CARD --}}
                    @if ($ebpg)
                        <button type="button" name="method" id="method-cc" value="ebpg" class="payment-option-btn text-dark shadow-none" onclick="$('input[name=method]').val('ebpg'); this.form.submit();">
                            <div class="d-flex align-items-center gap-3">
                                <div class="payment-icon-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                </div>
                                <div>
                                    <div class="fw-bold">Kad Kredit / Debit</div>
                                    <div class="small text-muted">Visa, Mastercard, AMEX</div>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    @endif

                    {{-- FPX --}}
                    @if ($fpx)
                        <div class="dropdown mb-3">
                            <button class="payment-option-btn text-dark shadow-none w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-icon-box text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M3 10h18"></path><path d="M5 10V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3"></path><path d="M7 21v-4"></path><path d="M11 21v-4"></path><path d="M15 21v-4"></path><path d="M19 21v-4"></path></svg>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Internet Banking (FPX)</div>
                                        <div class="small text-muted">Maybank, CIMB, Bank Islam, etc.</div>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-payment shadow border-0">
                                <li>
                                    <a class="dropdown-item dropdown-item-payment method-ob" href="#" data-value="fpx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        Perbankan Peribadi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item dropdown-item-payment method-ob" href="#" data-value="fpx-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="7" y1="21" x2="17" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                        Perbankan Korporat
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    {{-- DUITNOW --}}
                    @if ($duitnow)
                        <button type="button" id="method-duitnow" class="payment-option-btn text-dark shadow-none" onclick="$('input[name=method]').val('duitnow'); this.form.submit();">
                            <div class="d-flex align-items-center gap-3">
                                <div class="payment-icon-box text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                                </div>
                                <div>
                                    <div class="fw-bold">DuitNow QR / Transfer</div>
                                    <div class="small text-muted">Pembayaran Pantas & Mudah</div>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    @endif
                </div>
                {!! Former::close() !!}
            @else
                <div class="alert alert-danger border-0 rounded-12 d-flex align-items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div>
                        <div class="fw-bold">Harap Maaf!</div>
                        <div class="small">Pembayaran tidak dapat dilakukan pada masa ini kerana masalah teknikal.</div>
                    </div>
                </div>
            @endif

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex align-items-center gap-2 text-muted small justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Transaksi anda dilindungi dengan enkripsi SSL 256-bit
                </div>
            </div>

            @if (!app()->isProduction())
            <div class="mt-3 pt-3 border-top text-center">
                <p class="text-muted small mb-2">
                    <span class="badge bg-warning text-dark">DEV</span>
                    Persekitaran pembangunan sahaja
                </p>
                <a href="{{ route('payment_bypass') }}"
                   class="btn btn-sm btn-outline-secondary w-100"
                   onclick="return confirm('Bypass pembayaran? Akaun akan terus diaktifkan.')">
                    Pintas Pembayaran (Bypass)
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
            // FPX Dropdown selection logic
            $('.method-ob').click(function(e) {
                e.preventDefault();
                var method = $(this).data('value');
                $('input[name=method]').val(method);
                $(this).closest('form').submit();
            });

            // Ensure Credit Card direct hidden value update
            $("#method-cc").click(function() {
                $('input[name="method"]').val('ebpg');
            });
        });
	</script>
@endsection