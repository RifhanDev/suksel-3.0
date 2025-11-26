@extends('layouts.modernLanding')

@section('styles')
<style>
    .selangor-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px -10px rgba(196, 30, 58, 0.15); /* Red tinted shadow */
        border: none;
        overflow: hidden;
        position: relative;
    }

    .selangor-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .card-header-custom {
        padding: 2.5rem 2.5rem 1rem 2.5rem;
        text-align: center;
    }

    .card-body-custom {
        padding: 1rem 2.5rem 2.5rem 2.5rem;
    }

    /* --- TYPOGRAPHY --- */
    .form-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--sg-black);
        margin-bottom: 0.5rem;
    }

    /* --- INPUT STYLING --- */
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-weight: 600;
        color: var(--sg-black);
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-weight: 400;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--sg-red);
        box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.2); 
    }

    /* --- SECTION DIVIDERS --- */
    .form-section-title {
        display: flex;
        align-items: center;
        margin-top: 1.5rem;
        margin-bottom: 1.2rem;
    }

    .form-section-title span {
        background-color: var(--sg-yellow); /* Yellow Highlight */
        color: var(--sg-black);
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 4px 12px;
        border-radius: 4px;
        margin-right: 10px;
    }

    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    /* --- BUTTONS --- */
    .btn-selangor {
        background-color: var(--sg-red);
        color: white;
        font-weight: 700;
        padding: 0.9rem;
        border-radius: 8px;
        border: none;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3);
    }

    .btn-selangor:hover {
        background-color: #a01830;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(196, 30, 58, 0.4);
        color: #fff;
    }

    .btn-selangor::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--sg-yellow);
    }

    .btn-check-status {
        /* background-color: #fff;
        border: 2px solid var(--sg-yellow);
        color: var(--sg-black);
        font-weight: 700;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.2s; */
		color: var(--sg-black);
		transition: all 0.2s;
    }

    .btn-check-status:hover {
        /* background-color: var(--sg-yellow); */
        color: var(--sg-yellow);
    }
    
    .crest-icon {
        width: 70px; 
        height: 70px; 
        background: #fff;
        border: 2px solid var(--sg-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .card-header-custom { padding: 2rem 1.5rem 0 1.5rem; }
        .card-body-custom { padding: 1rem 1.5rem 2rem 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center pt-5 pb-5">
    <div class="col-lg-9 col-md-9">
        
        <div class="selangor-card">    
            <div class="card-header-custom">
                {{-- <div class="crest-icon">
                    <!-- Red Star/Emblem Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#c41e3a" class="icon icon-tabler icons-tabler-filled icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                </div> --}}
                <h1 class="h3 fw-bold mb-1 text-uppercase" style="letter-spacing: 1px; color: var(--sg-red);">Pendaftaran Syarikat</h1>
                <p class="text-muted small fw-semibold">Sistem Tender Online Selangor (STOS 3.0)</p>
            </div>

            <div class="card-body-custom">
                {!! Former::open(url('register'))->addClass('form-uppercase')->autocomplete('false') !!}

                <!-- Section 1: Yellow Tag -->
                <div class="form-section-title">
                    <span>Maklumat Organisasi</span>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        {!! Former::text('company_no')->label('No. Pendaftaran (SSM)')->required()->addClass('form-control')->placeholder('Contoh: 202401009999') !!}
                    </div>
                    <div class="col-12">
                        {!! Former::text('company_name')->label('Nama Syarikat')->required()->addClass('form-control')->placeholder('Contoh: BINA JAYA SDN BHD') !!}
                    </div>
                </div>

                <!-- Section 2: Yellow Tag -->
                <div class="form-section-title">
                    <span>Akses & Keselamatan</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        {!! Former::text('name')->label('Nama Pegawai Pendaftar')->required()->addClass('form-control')->placeholder('Nama Penuh') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Former::email('email')->label('Alamat Emel Rasmi')->required()->addClass('form-control x-uppercase')->placeholder('email@syarikat.com') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Former::password('password')->label('Kata Laluan')->required()->addClass('form-control x-uppercase')->placeholder('••••••••') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Former::password('password_confirmation')->label('Sahkan Kata Laluan')->required()->addClass('form-control x-uppercase')->placeholder('••••••••') !!}
                    </div>
                </div>

                <!-- Footer Info Area -->
                <div class="mt-4 p-3 rounded" style="background-color: #fff9db; border: 1px solid #ffe066;">
                    <div class="d-flex gap-3 align-items-center">
                        <i class="ti ti-info-circle text-dark fs-2"></i>
                        <div class="small lh-sm text-dark">
                            <strong>Nota Penting:</strong> Sila pastikan maklumat di atas adalah tepat seperti dalam sijil SSM. Maklumat ini tidak boleh diubah selepas pendaftaran.
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="d-grid mt-4 mb-2">
                    <button type="submit" class="btn-selangor btn-lg">
                        Sahkan Alamat Emel
                    </button>
                </div>

                {!! Former::close() !!}
            </div>
            
            <div class="py-3 px-4 text-center border-top bg-light">
                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-1">
                    <span class="text-muted small fw-semibold">Sudah mempunyai akaun atau rekod?</span>
                    <a href="{{ action('HomeController@companySearch') }}" class="btn-check-status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-zoom"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
						Semak Status
                    </a>
                </div>
            </div>

        </div>
		<!-- End Card -->
    </div>
</div>
@endsection