@extends('layouts.modernLanding')

@section('styles')
<style>
    .selangor-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 5px 25px -5px rgba(196, 30, 58, 0.08);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        position: relative;
    }

    .selangor-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .card-header-custom {
        color: var(--sg-red);
        padding: 2rem 2rem 0.5rem 2rem;
        text-align: center;
    }

    .card-body-custom {
        padding: 0.5rem 2rem 2rem 2rem;
    }

    /* =========================================
        FORM ELEMENTS
       ========================================= */
    .form-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #374151;
        margin-bottom: 0.3rem;
    }

    .form-control {
        border: 1px solid #e5e7eb;
        border-radius: var(--radius-sm);
        padding: 0.6rem 0.9rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--sg-black);
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-weight: 400;
        font-size: 0.85rem;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--sg-red);
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1); 
        color: var(--sg-black);
    }
    
    .text-uppercase-input {
        text-transform: uppercase;
    }

    /* =========================================
        SECTION DIVIDERS
       ========================================= */
    .form-section-title {
        display: flex;
        align-items: center;
        margin-top: 1.25rem;
        margin-bottom: 1rem;
    }

    .form-section-title span {
        background-color: var(--sg-yellow);
        color: #111827;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 4px;
        margin-right: 10px;
    }

    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #f3f4f6;
    }

    /* =========================================
       BUTTONS
       ========================================= */
    .btn-selangor {
        background-color: var(--sg-red);
        color: white;
        font-weight: 700;
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--sg-red);
        width: 100%;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .btn-selangor:hover {
        background-color: var(--sg-red-dark);
        border-color: var(--sg-red-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-check-link {
        color: #4b5563;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        transition: all 0.2s;
    }

    .btn-check-link:hover {
        color: white;
        background-color: var(--sg-red);
    }

    /* =========================================
       NOTES & ALERTS
       ========================================= */
    .note-box {
        background-color: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        line-height: 1.4;
    }
    
    .invalid-feedback {
        font-size: 0.7rem;
        font-weight: 600;
        color: #dc2626;
        margin-top: 0.2rem;
    }

    @media (max-width: 768px) {
        .card-header-custom { padding: 2rem 1.5rem 0.5rem 1.5rem; }
        .card-body-custom { padding: 0.5rem 1.5rem 2rem 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-lg-7 col-md-9">
        
        <div class="selangor-card">
            
            <!-- Header -->
            <div class="card-header-custom">
                <h1 class="h4 fw-bold mb-1 text-uppercase" style="letter-spacing: -0.5px;">
                    Pendaftaran Syarikat
                </h1>
                {{-- TEMP-HIDE (revert to restore): <p class="text-muted small fw-semibold mb-0" style="font-size: 0.75rem;">Sistem Perolehan Selangor</p> --}}
            </div>

            <!-- Body -->
            <div class="card-body-custom">
                
                <form method="POST" action="{{ url('register') }}" autocomplete="off" class="needs-validation">
                    @csrf

                    <!-- SECTION : ORGANIZATION -->
                    <div class="form-section-title mt-2">
                        <span>Maklumat Organisasi</span>
                    </div>

                    <div class="row g-2">
                        <!-- Company No -->
                        <div class="col-md-4">
                            <label for="company_no" class="form-label">No. Pendaftaran (SSM) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control text-uppercase-input @error('company_no') is-invalid @enderror" 
                                   id="company_no" 
                                   name="company_no" 
                                   value="{{ old('company_no') }}" 
                                   placeholder="202401..." 
                                   required>
                            @error('company_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Company Name -->
                        <div class="col-md-8">
                            <label for="company_name" class="form-label">Nama Syarikat <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control text-uppercase-input @error('company_name') is-invalid @enderror" 
                                   id="company_name" 
                                   name="company_name" 
                                   value="{{ old('company_name') }}" 
                                   placeholder="CONTOH: BINA JAYA SDN BHD" 
                                   required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION : ACCESS & SECURITY -->
                    <div class="form-section-title">
                        <span>Akses & Keselamatan</span>
                    </div>

                    <div class="row g-2">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Pegawai <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control text-uppercase-input @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Nama Penuh" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Alamat Emel <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="emel@syarikat.com" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Kata Laluan <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Sahkan Laluan <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                    </div>

                    <!-- Important Note -->
                    <div class="note-box mt-3 mb-3">
                        <div class="d-flex gap-2 align-items-start">
                            <div class="flex-shrink-0 pt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                            </div>
                            <div>
                                <strong>Nota Penting:</strong> Pastikan maklumat tepat seperti sijil SSM. No. SSM tidak boleh diubah selepas pendaftaran.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn-selangor">
                            Sahkan & Daftar Akaun
                        </button>
                    </div>

                </form>
            </div>
            
            <!-- Footer Links -->
            <div class="py-2 px-4 text-center border-top bg-light">
                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-1">
                    <span class="text-muted small fw-semibold" style="font-size: 0.75rem;">Sudah mempunyai akaun?</span>
                    <a href="{{ action('HomeController@companySearch') }}" class="btn-check-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
						Semak Status Pendaftaran
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection