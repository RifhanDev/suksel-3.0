@extends('layouts.modernLanding')

@section('styles')
<style>
    .search-gateway-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* Top Gradient Line */
    .search-gateway-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .gateway-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        border-bottom: 1px dashed #e5e7eb;
    }

    .gateway-body {
        padding: 1.5rem;
        flex: 1;
    }

    /* =========================================
        TIMELINE
       ========================================= */
    .logic-timeline {
        position: relative;
        padding-left: 1.5rem;
        border-left: 2px solid #f3f4f6;
        margin-left: 0.4rem;
    }

    .logic-step {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .logic-step:last-child {
        margin-bottom: 0;
    }

    /* Dots */
    .logic-step::before {
        content: "";
        position: absolute;
        left: -1.9rem;
        top: 2px;
        width: 0.9rem;
        height: 0.9rem;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--sg-red);
        z-index: 2;
        box-shadow: 0 0 0 3px white;
    }
    
    .logic-step.secondary::before {
        border-color: var(--sg-yellow);
    }

    .timeline-label {
        font-weight: 800;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: block;
        line-height: 1;
    }

    /* =========================================
        FORM ELEMENTS
       ========================================= */
    .form-group {
        margin-bottom: 0.75rem;
    }

    .form-label {
        font-weight: 700;
        font-size: 0.75rem;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .form-control {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.8rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--sg-black);
        width: 100%;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--sg-red);
        background: white;
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        outline: none;
    }

    .form-text {
        font-size: 0.7rem;
        color: #9ca3af;
        margin-top: 0.2rem;
        line-height: 1.2;
    }

    /* OR Divider */
    .or-divider {
        display: flex;
        align-items: center;
        color: #9ca3af;
        font-size: 0.65rem;
        font-weight: 700;
        margin: 0.75rem 0;
        text-transform: uppercase;
    }
    .or-divider::before, .or-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #f3f4f6;
        margin: 0 8px;
    }

    /* Search Button */
    .btn-search {
        background: var(--sg-black);
        color: white;
        width: 100%;
        padding: 0.75rem;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: var(--radius-sm);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        transition: all 0.2s;
        margin-top: 1rem;
    }
    
    .btn-search:hover {
        background: #111827;
        transform: translateY(-1px);
    }

    /* =========================================
       RIGHT CARD
       ========================================= */
    .register-promo-card {
        background: linear-gradient(135deg, var(--sg-red) 0%, #9f1239 100%);
        border-radius: var(--radius-lg);
        color: white;
        padding: 2rem 1.5rem;
        height: 100%; 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    /* Subtle Pattern */
    .register-promo-card::after {
        content: "";
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 16px 16px;
        opacity: 0.3;
    }

    .promo-content {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    .promo-icon {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto; 
        color: var(--sg-yellow);
    }

    .btn-register-white {
        background: white;
        color: var(--sg-red);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        transition: all 0.2s;
        border: 2px solid white;
        display: inline-block;
        width: 100%;
        text-decoration: none;
    }

    .btn-register-white:hover {
        background: transparent;
        color: white;
        transform: translateY(-1px);
    }

    @media (max-width: 992px) {
        .gateway-header, .gateway-body { padding: 1.25rem; }
        .logic-timeline { padding-left: 1.25rem; }
        .register-promo-card { padding: 2rem 1.25rem; min-height: auto; }
    }
</style>
@endsection

@section('content')
<div class="row pt-4 pb-5 g-3 align-items-stretch justify-content-center">
    
    <!-- LEFT: SEARCH GATEWAY -->
    <div class="col-lg-6 col-xl-5">
        <div class="search-gateway-card">
            
            <div class="gateway-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </div>
                    <div>
                        <h1 class="h6 fw-bold mb-0 text-dark text-uppercase" style="letter-spacing: -0.2px;">Semakan Syarikat</h1>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Sila isi maklumat di bawah.</p>
                    </div>
                </div>
            </div>

            <div class="gateway-body">
                <form action="{{ action('HomeController@doCompanySearch') }}" method="POST">
                    @csrf

                    <div class="logic-timeline">
                        
                        <!-- SSM -->
                        <div class="logic-step">
                            <span class="timeline-label text-danger">WAJIB ISI</span>
                            <div class="mb-0">
                                <label for="company_no" class="form-label">No. Pendaftaran SSM</label>
                                <input type="text" class="form-control" id="company_no" name="company_no" required>
                                <div class="form-text">No syarikat atau perniagaan SSM</div>
                            </div>
                        </div>

                        <div class="logic-step secondary">
                            <span class="timeline-label text-warning" style="color: #d97706 !important;">DAN SALAH SATU DARI:</span>
                            
                            <!-- Option A: MOF -->
                            <div class="form-group">
                                <label for="mof_no" class="form-label">No. Rujukan MOF</label>
                                <input type="text" class="form-control" id="mof_no" name="mof_no">
                            </div>
                            
                            <div class="or-divider">Atau</div>

                            <!-- Option B: CIDB -->
                            <div class="form-group">
                                <label for="cidb_no" class="form-label">No. Pendaftaran CIDB</label>
                                <input type="text" class="form-control" id="cidb_no" name="cidb_no">
                            </div>

                            <div class="or-divider">Atau</div>

                            <!-- Option C: Name -->
                            <div class="form-group">
                                <label for="company_name" class="form-label">Nama Syarikat / Perniagaan</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                            </div>
                        </div>

                    </div>

                    <div class="ps-3 ms-2">
                        <button type="submit" class="btn-search">
                            Semak Rekod
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT: REGISTER -->
    <div class="col-lg-4 col-xl-3">
        <div class="register-promo-card">
            <div class="promo-content">
                <div class="promo-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                
                <h2 class="h5 fw-bold mb-2">Belum Berdaftar?</h2>
                
                <p class="mb-3 text-white-50 px-2" style="font-size: 0.8rem; line-height: 1.4;">
                    Akses penuh peluang perolehan Kerajaan Negeri Selangor.
                </p>

                <a href="{{ asset('register') }}" class="btn-register-white">
                    Daftar Vendor
                </a>

                <div class="mt-3 text-white-50 small opacity-75" style="font-size: 0.7rem;">
                    Pendaftaran Percuma & Pantas
                </div>
            </div>
        </div>
    </div>

</div>
@endsection