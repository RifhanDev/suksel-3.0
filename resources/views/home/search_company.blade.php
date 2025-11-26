@extends('layouts.modernLanding')

@section('styles')
<style>
    .search-gateway-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
        border: 1px solid #f3f4f6;
        height: 100%; /* Match height of right card */
        position: relative;
        overflow: hidden;
    }

    .search-gateway-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(to right, var(--sg-red) 60%, var(--sg-yellow) 40%);
    }

    .gateway-header {
        padding: 2rem 2rem 1rem 2rem;
        border-bottom: 1px dashed #e5e7eb;
    }

    .gateway-body {
        padding: 2rem;
    }

    .logic-timeline {
        position: relative;
        padding-left: 2rem;
        border-left: 2px solid #e5e7eb; /* The vertical line */
        margin-left: 1rem;
    }

    .logic-step {
        position: relative;
        margin-bottom: 2rem;
    }

    /* The Dots on the timeline */
    .logic-step::before {
        content: "";
        position: absolute;
        left: -2.6rem;
        top: 0.1rem;
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--sg-red);
        z-index: 2;
    }
    
    .logic-step.secondary::before {
        border-color: var(--sg-yellow); /* Yellow dot for secondary options */
    }

    /* 3. Input Styling */
    .form-group {
        margin-bottom: 0; /* Let logic-step handle spacing */
    }

    .control-label {
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #1f2937;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .form-control {
        background-color: #f9fafb;
        border: 2px solid #e5e7eb;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--sg-red);
        background: white;
        box-shadow: 0 0 0 4px rgba(196, 30, 58, 0.1);
        outline: none;
    }

    /* Help Text Styling */
    .help-block, .form-text {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.4rem;
        line-height: 1.3;
        font-style: italic;
    }

    /* 4. The Registration Promo Card */
    .register-promo-card {
        background: linear-gradient(135deg, var(--sg-red) 0%, #8b1428 100%);
        border-radius: 16px;
        color: white;
        padding: 2rem 1.5rem;
        height: auto; 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -5px rgba(196, 30, 58, 0.4);
    }
    
    .promo-icon {
        width: 64px;
        height: 64px;
        background: rgba(255,255,255,0.1);
        border: 2px solid var(--sg-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto; 
        color: var(--sg-yellow);
    }

    /* Background Pattern for Register Card */
    .register-promo-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.3;
    }

    .promo-content {
        position: relative;
        z-index: 2;
    }

    /* Buttons */
    .btn-search {
        background: var(--sg-black);
        color: white;
        width: 100%;
        padding: 1rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        transition: all 0.2s;
    }
    
    .btn-search:hover {
        background: #000;
        transform: translateY(-2px);
    }

    .btn-register-white {
        background: white;
        color: var(--sg-red);
        font-weight: 800;
        padding: 1rem 2rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.2s;
        border: 2px solid white;
    }

    .btn-register-white:hover {
        background: transparent;
        color: white;
        transform: translateY(-2px);
    }

    .or-divider {
        display: flex;
        align-items: center;
        color: #9ca3af;
        font-size: 0.7rem;
        font-weight: 700;
        margin: 1.5rem 0;
        text-transform: uppercase;
    }
    .or-divider::before, .or-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e5e7eb;
        margin: 0 10px;
    }

    /* Section Label within timeline */
    .timeline-label {
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        display: block;
    }

</style>
@endsection

@section('content')
<div class="row pt-5 pb-5 g-4">
    
    <!-- LEFT: SEARCH GATEWAY -->
    <div class="col-lg-7">
        <div class="search-gateway-card">
            <div class="gateway-header">
                <h1 class="h3 fw-bold mb-1 text-dark">
                    <i class="ti ti-search text-danger me-2"></i>Semakan Syarikat
                </h1>
                <p class="text-muted small mb-0 ms-4 ps-2">Sila masukan maklumat ke dalam medan yang disediakan di bawah.</p>
            </div>

            <div class="gateway-body">
                {!! Former::vertical_open(action('HomeController@doCompanySearch')) !!}

                <div class="logic-timeline">
                    
                    <!-- STEP 1: SSM (Mandatory) -->
                    <div class="logic-step">
                        <span class="timeline-label text-danger">Wajib Isi</span>
                        <div class="mb-2">
                            {!! Former::text('company_no')
                                ->label('No. SSM')
                                ->help('No syarikat atau perniagaan yang didaftarkan oleh SSM')
                                ->required()
                                ->class('form-control') !!}
                        </div>
                    </div>

                    <!-- STEP 2: Logic Connector -->
                    <div class="logic-step secondary" style="margin-bottom: 1.5rem;">
                        <span class="timeline-label text-warning" style="color: #d97706 !important;">Dan Salah Satu Dari:</span>
                        
                        <!-- Option A: MOF -->
                        <div>
                            {!! Former::text('mof_no')
                                ->label('No. Rujukan Pendaftaran MOF')
                                ->help('No Rujukan Pendaftaran yang tertera di atas Sijil Akuan Pendaftaran Syarikat Kementerian Kewangan Malaysia')
                                ->class('form-control') !!}
                        </div>
                        
                        <div class="or-divider">Atau</div>

                        <!-- Option B: CIDB -->
                        <div>
                            {!! Former::text('cidb_no')
                                ->label('No. Pendaftaran CIDB')
                                ->help('No Pendaftaran yang tertera di atas Perakuan Pendaftaran CIDB')
                                ->class('form-control') !!}
                        </div>

                        <div class="or-divider">Atau</div>

                        <!-- Option C: Name -->
                        <div>
                            {!! Former::text('company_name')
                                ->label('Nama Syarikat/Perniagaan')
                                ->help('Nama syarikat atau perniagaan yang didaftarkan oleh SSM')
                                ->class('form-control') !!}
                        </div>
                    </div>

                </div>

                <!-- Submit Button Area (Outside timeline) -->
                <div class="ps-4 ms-2 mt-4">
                    <button type="submit" class="btn-search">
                        Semak Rekod
                    </button>
                </div>

                {!! Former::close() !!}
            </div>
        </div>
    </div>

    <!-- RIGHT: REGISTER -->
    <div class="col-lg-5">
        <div class="register-promo-card">
            <div class="promo-content">
                <div class="promo-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21h9" />
                        <path d="M9 8h1" />
                        <path d="M9 12h1" />
                        <path d="M9 16h1" />
                        <path d="M14 21v-12.5a2.5 2.5 0 0 1 5 0v12.5" />
                        <path d="M15 11h1" />
                        <path d="M15 15h1" />
                        <path d="M3 21v-14a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v2" />
                        <path d="M16 6h6" />
                        <path d="M19 3v6" />
                    </svg>
                </div>
                
                <h2 class="h4 fw-bold mb-2">Syarikat Belum Berdaftar?</h2>
                
                <p class="mb-3 text-white-50 px-2 small">
                    Sertai STOS 3.0 untuk akses penuh peluang perolehan.
                </p>

                <a href="{{ asset('register') }}" class="btn-register-white text-decoration-none d-inline-block mb-2 w-100">
                    Daftar Vendor Baru
                </a>

                <div class="mt-2 text-white-50 small" style="font-size: 0.75rem;">
                    <i class="ti ti-check me-1"></i> Daftar Sebagai Vendor Baru Sekarang
                </div>
            </div>
        </div>
    </div>

</div>
@endsection