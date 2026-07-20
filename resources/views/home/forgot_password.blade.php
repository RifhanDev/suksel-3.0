@extends('layouts.modernLanding')

@section('styles')
<style>
    .selangor-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 40px -10px rgba(196, 30, 58, 0.1);
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

    /* Header */
    .card-header-custom {
        padding: 2.5rem 2rem 1rem 2rem;
        text-align: center;
    }

    /* Body */
    .card-body-custom {
        padding: 0.5rem 2.5rem 2.5rem 2.5rem;
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
        margin-bottom: 0.35rem;
    }

    .form-control {
        border: 2px solid #f3f4f6;
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: var(--sg-black);
        background-color: #f9fafb;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--sg-red);
        box-shadow: 0 0 0 4px rgba(196, 30, 58, 0.1); 
        color: var(--sg-black);
    }

    /* =========================================
       BUTTONS
       ========================================= */
    .btn-selangor {
        background-color: var(--sg-red);
        color: white;
        font-weight: 700;
        padding: 0.85rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--sg-red);
        width: 100%;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px rgba(196, 30, 58, 0.2);
    }

    .btn-selangor:hover {
        background-color: var(--sg-red-dark);
        border-color: var(--sg-red-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 15px rgba(196, 30, 58, 0.25);
    }

    /* =========================================
       ALERTS & NOTES
       ========================================= */
    .custom-alert {
        border-radius: var(--radius-sm);
        border: none;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        line-height: 1.4;
    }
    
    .custom-alert-danger {
        background-color: #fef2f2;
        color: #991b1b;
        border-left: 3px solid var(--sg-red);
    }

    .custom-alert-success {
        background-color: #ecfdf5;
        color: #065f46;
        border-left: 3px solid #10b981;
    }

    .security-note {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px dashed #e5e7eb;
        font-size: 0.75rem;
        color: #6b7280;
        text-align: center;
    }

    .security-note strong {
        color: var(--sg-red);
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        margin-top: 1rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: var(--sg-black);
    }

    @media (max-width: 768px) {
        .card-header-custom { padding: 2rem 1.5rem 0.5rem 1.5rem; }
        .card-body-custom { padding: 0.5rem 1.5rem 2rem 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    
    <div class="col-lg-4 col-md-6 col-sm-10">
        <div class="selangor-card">
            
            <!-- Header -->
            <div class="card-header-custom">
                <!-- Icon Circle -->
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #fff; border: 2px solid var(--sg-yellow); border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 11v-4a4 4 0 1 1 8 0v4" /><path d="M3 11m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M12 16l0 2" /></svg>
                </div>

                <h1 class="h4 fw-bold mb-1 text-uppercase" style="letter-spacing: -0.5px; color: var(--sg-red);">Lupa Kata Laluan</h1>
                <p class="text-muted small fw-semibold mb-0">Sistem e-Perolehan Selangor</p>
            </div>

            <!-- Body -->
            <div class="card-body-custom">

                <!-- STATUS -->
                @if (session('status'))
                    <div class="custom-alert custom-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M5 12l5 5l10 -10" /></svg>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="custom-alert custom-alert-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16h.01" /></svg>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ action('AuthController@doForgotPassword') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">{{ trans('auth.register.email') }}</label>
                        <input type="email" 
                               class="form-control" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               placeholder="nama@syarikat.com" 
                               required 
                               autofocus>
                    </div>

                    <button type="submit" class="btn-selangor">
                        Seterusnya
                    </button>

                </form>

                <!-- Security Note -->
                <div class="security-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                    <div>
                        <strong>Nota Keselamatan:</strong><br> 
                        Sila tukar kata laluan anda setiap 90 hari.
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
                        Kembali ke Log Masuk
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/news.js') }}"></script>
@endsection