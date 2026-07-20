@extends('layouts.v3.master')

@section('styles')
<style>
    .card {
        overflow: hidden; /* Ensures the sidebar background doesn't leak */
    }

    .info-sidebar {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Geometric "Slate" */
    .info-sidebar::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: var(--sg-yellow);
        opacity: 0.15;
        border-radius: 30px;
        transform: rotate(45deg);
        pointer-events: none;
    }

    /* Subtle shape for depth */
    .info-sidebar::before {
        content: '';
        position: absolute;
        top: -30px;
        left: -30px;
        width: 100px;
        height: 100px;
        background: white;
        opacity: 0.05;
        border-radius: 50%;
        pointer-events: none;
    }

    /* Text Adjustments for Dark Background */
    .info-sidebar h5, 
    .info-sidebar .fw-bold {
        color: white !important;
    }
    
    .info-sidebar .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .icon-box-sidebar {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        width: 50px; 
        height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1rem;
        backdrop-filter: blur(5px);
    }

    .link-slide-underline {
        position: relative;
        text-decoration: none !important;
        transition: color 0.3s ease-in-out;
        padding-bottom: 3px;
    }
    .link-slide-underline:hover { color: var(--sg-red) !important; }
    .link-slide-underline::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: var(--sg-red);
        transition: width 0.3s ease-in-out;
    }
    .link-slide-underline:hover::after { width: 90%; }
</style>
@endsection

@section('content')

<!-- PAGE HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Pengurusan Vendor</div>
        <h2 class="fw-bold text-dark m-0">
            Kemaskini Akaun
        </h2>
    </div>
</div>

<form action="{{ action('VendorsController@updateEmail', $vendor->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- SPLIT LAYOUT CARD -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden card-accent-top mb-5">
        <div class="row g-0">
            
            <!-- LEFT PANEL: IDENTITY (Read-Only) -->
            <div class="col-lg-4 info-sidebar">
                <div class="p-5 h-100 d-flex flex-column justify-content-center position-relative z-1">
                    
                    <div class="mb-5">
                        <div class="icon-box-sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <h5 class="fw-bold mb-2">Maklumat Semasa</h5>
                        <p class="text-muted small mb-0">Butiran ini diambil dari pendaftaran asal dan tidak boleh diubah di sini.</p>
                    </div>

                    <div class="mb-4">
                        <label class="small text-uppercase text-muted fw-bold mb-1" style="letter-spacing: 1px;">Nama Syarikat</label>
                        <div class="fw-bold fs-5">{{ $vendor->name }}</div>
                    </div>

                    <div>
                        <label class="small text-uppercase text-muted fw-bold mb-1" style="letter-spacing: 1px;">Nama Pegawai</label>
                        <div class="fw-bold fs-5">{{ $vendor->user->name }}</div>
                    </div>

                </div>
            </div>

            <!-- RIGHT PANEL: EDIT FORM -->
            <div class="col-lg-8 bg-white">
                <div class="p-5">
                    
                    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <h6 class="fw-bold text-dark mb-0">Butiran Untuk Dikemaskini</h6>
                    </div>

                    <div class="row g-4">
                        <!-- Email Input -->
                        <div class="col-12">
                            <label for="email" class="form-label fw-bold text-dark">Alamat Emel Baru <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $vendor->user->email) }}">
                            
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">Emel ini akan digunakan untuk log masuk sistem.</div>
                        </div>

                        <!-- Registration Input -->
                        <div class="col-12">
                            <label for="registration" class="form-label fw-bold text-dark">No. Pendaftaran Baru <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control form-control-lg fs-6 @error('registration') is-invalid @enderror" 
                                   id="registration" 
                                   name="registration" 
                                   value="{{ old('registration', $vendor->registration) }}">
                            
                            @error('registration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">Pastikan nombor pendaftaran syarikat adalah tepat.</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ACTION BAR -->
    <div class="w-100 bg-white border border-top-0 shadow-lg rounded-3 p-3 z-3">
        <div class="d-flex justify-content-between align-items-center">
            
            <!-- LEFT: BACK BUTTON -->
            <div>
                @if ($vendor->canShow())
                    <a href="{{ asset('vendors/' . $vendor->id) }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 ps-0 link-slide-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        <span class="fw-bold">Kembali</span>
                    </a>
                @endif
            </div>

            <!-- RIGHT: SUBMIT BUTTON -->
            <div>
                <button type="submit" class="btn btn-selangor d-flex align-items-center gap-2 fw-medium px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </div>

</form>

@endsection