@extends('layouts.modernLanding')

@section('styles')
<style>
    .selangor-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px -10px rgba(196, 30, 58, 0.15);
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
        height: 6px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .card-header-custom {
        padding: 3rem 2rem 1.5rem 2rem;
        text-align: center;
    }

    .card-body-custom {
        padding: 0 2.5rem 2.5rem 2.5rem;
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

    /* --- INPUTS --- */
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-weight: 600;
        color: var(--sg-black);
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--sg-red);
        box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.2); 
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
        width: 100%;
    }

    .btn-selangor:hover {
        background-color: #a01830;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(196, 30, 58, 0.4);
        color: #fff;
    }

    /* Yellow Bottom Border on Button */
    .btn-selangor::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--sg-yellow);
    }

    /* --- ALERTS --- */
    .custom-alert {
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
    }
    .custom-alert-danger {
        background-color: #fef2f2;
        color: #991b1b;
        border-left: 4px solid var(--sg-red);
    }

    /* --- LINKS --- */
    .auth-links {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px dashed #e5e7eb;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        align-items: center;
    }
    
    .link-item {
        text-decoration: none;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .link-item:hover {
        color: var(--sg-red);
    }

    .link-register {
        color: var(--sg-red);
        font-weight: 700;
        background: #fff1f2;
        padding: 0.4rem 1rem;
        border-radius: 50px;
    }
    
    .link-register:hover {
        background: var(--sg-red);
        color: white;
    }

    .forgot-pass-link {
        color: #6b7280;
        font-size: 0.85rem;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .forgot-pass-link:hover {
        color: var(--sg-red);
        text-decoration: underline;
    }

    .register-callout {
        background-color: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        margin-top: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .register-callout::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: var(--sg-yellow);
    }

    .btn-register-ghost {
        display: inline-block;
        margin-top: 0.5rem;
        background: white;
        border: 1px solid #e5e7eb;
        color: var(--sg-red);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        text-decoration: none;
    }

    .btn-register-ghost:hover {
        border-color: var(--sg-red);
        background: #fff1f2;
        transform: translateY(-1px);
		text-decoration: none;
		color: var(--sg-red)
    }

    .manual-link {
        font-size: 0.75rem;
        color: #9ca3af;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        margin-top: 1rem;
        transition: color 0.2s;
    }
    .manual-link:hover {
        color: var(--sg-black);
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center pt-5 pb-5">

    <div class="col-lg-5 col-md-7">        
        <div class="selangor-card">
            <!-- HEADER -->
            <div class="card-header-custom">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #fff; border: 2px solid var(--sg-yellow); border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-login-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M3 12h13l-3 -3" /><path d="M13 15l3 -3" /></svg>
                </div>
                <h1 class="h3 fw-bold mb-1 text-uppercase" style="letter-spacing: 1px; color: var(--sg-red);">Daftar Masuk</h1>
                <p class="text-muted small fw-semibold">Sistem Tender Online Selangor (STOS 3.0)</p>
            </div>

            <div class="card-body-custom">
                
                <!-- ERROR HANDLING -->
                @if ($errors->any())
                    <div class="custom-alert custom-alert-danger">
                        <i class="ti ti-alert-circle fs-4 mt-1"></i>
                        <div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="custom-alert custom-alert-danger">
                        <i class="ti ti-alert-circle fs-4 mt-1"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->has('login'))
                    <div class="custom-alert custom-alert-danger">
                        <i class="ti ti-alert-circle fs-4 mt-1"></i>
                        <div>{{ $errors->first('login') }}</div>
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ action('AuthController@doLogin') }}" autocomplete="off">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Alamat Emel</label>
                        <div class="input-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                placeholder="nama@syarikat.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Kata Laluan</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="••••••••" required autocomplete="current-password">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn-selangor">
                            DAFTAR MASUK
                        </button>
                    </div>
                </form>

                <!-- FORGOT PASSWORD -->
                <div class="text-center mt-3">
                    <a href="{{ action('AuthController@forgotPassword') }}" class="forgot-pass-link">
                        <i class="ti ti-lock-question"></i> Lupa Kata Laluan?
                    </a>
                </div>

                <!-- REGISTER BOX -->
                <div class="register-callout">
                    <div class="small fw-bold text-dark">Syarikat Belum Berdaftar?</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Sertai kami untuk peluang tender.</div>
                    
                    <a href="{{ route('registration') }}" class="btn-register-ghost">
                        Daftar Akaun Baru
                    </a>
                </div>

                <!-- MANUAL -->
                <a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="manual-link">
                    <i class="ti ti-book"></i> Panduan Pengguna
                </a>

            </div> <!-- End Card Body -->
        </div> <!-- End Card -->
    </div>
</div>
@endsection