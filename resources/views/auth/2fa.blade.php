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

    /* Top Gradient Accent */
    .selangor-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    /* =========================================
        CARD HEADER
        ========================================= */
    .card-header-custom {
        padding: 2.5rem 2rem 1rem 2rem;
        text-align: center;
    }

    .card-body-custom {
        padding: 0.5rem 2.5rem 2.5rem 2.5rem;
    }

    /* =========================================
       INPUT
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
        text-align: center;
        letter-spacing: 0.5em;
        font-size: 1.5rem;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-weight: 400;
        letter-spacing: 0.3em;
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
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-selangor:hover {
        background-color: var(--sg-red-dark);
        border-color: var(--sg-red-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 15px rgba(196, 30, 58, 0.25);
    }

    /* =========================================
       ALERTS
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

    .custom-alert-info {
        background-color: #eff6ff;
        color: #1e40af;
        border-left: 3px solid #3b82f6;
    }

    /* Links */
    .back-link {
        color: var(--sg-red);
        font-size: 0.8rem;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .back-link:hover {
        color: var(--sg-black);
        text-decoration: none;
    }

    .info-text {
        font-size: 0.85rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .card-header-custom { padding: 2rem 1.5rem 0.5rem 1.5rem; }
        .card-body-custom { padding: 0.5rem 1.5rem 2rem 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">

    <div class="col-lg-4 col-md-6 col-sm-10">        
        <div class="selangor-card">
            
            <!-- HEADER -->
            <div class="card-header-custom">
                <!-- Icon Circle -->
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #fff; border: 2px solid var(--sg-yellow); border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding-left: 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /><path d="M9 12l2 2 4-4" /></svg>
                </div>
                
                <h1 class="h4 fw-bold mb-1 text-uppercase" style="letter-spacing: -0.5px; color:var(--sg-red)">Pengesahan Dua Faktor</h1>
                <p class="text-muted small fw-semibold mb-0">Sistem e-Perolehan Selangor</p>
            </div>

            <div class="card-body-custom">
                
                <!-- ERROR HANDLING -->
                @if ($errors->any() || session('error'))
                    <div class="custom-alert custom-alert-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16h.01" /></svg>
                        <div>
                            @if ($errors->any())
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ session('error') }}
                            @endif
                        </div>
                    </div>
                @endif

                <!-- DUMMY CODE (for testing) -->
                <div class="mb-3 p-3 rounded text-center" style="background: #fef3c7; border: 1px dashed #d97706;">
                    <span class="small text-uppercase fw-bold text-muted d-block mb-1">Kod dummy (uji sahaja)</span>
                    <code class="fs-4 fw-bold" style="color: #b45309;">1234</code>
                </div>

                <!-- INFO MESSAGE -->
                <div class="info-text">
                    <p class="mb-2">Sila masukkan kod pengesahan 4 digit.</p>
                    <p class="mb-0">Gunakan kod dummy di atas untuk ujian.</p>
                </div>

                <!-- FORM -->
                <form method="POST" action="{{ action('AuthController@verify2FA') }}" autocomplete="off" id="2fa-form">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Kod Pengesahan</label>
                        <input type="text" 
                               class="form-control @error('code') is-invalid @enderror" 
                               name="code"
                               id="code"
                               placeholder="1234" 
                               value="{{ old('code') }}" 
                               required 
                               autocomplete="off" 
                               autofocus
                               maxlength="4"
                               pattern="[0-9]{4}"
                               inputmode="numeric">
                        @error('code')
                            <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-2 mb-3">
                        <button type="submit" class="btn-selangor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4" /><path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3" /><path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3" /><path d="M12 3c0 1-1 3-3 3s-3-2-3-3 1-3 3-3 3 2 3 3" /><path d="M12 21c0-1 1-3 3-3s3 2 3 3-1 3-3 3-3-2-3-3" /></svg>
                            Sahkan
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ action('AuthController@login') }}" class="back-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5" /><path d="M12 19l-7-7 7-7" /></svg>
                            Kembali ke Log Masuk
                        </a>
                    </div>
                </form>

            </div> <!-- End Card Body -->
        </div> <!-- End Card -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    
    // Only allow numbers
    codeInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Auto-submit when 4 digits are entered
    codeInput.addEventListener('input', function(e) {
        if (this.value.length === 4) {
            setTimeout(() => {
                document.getElementById('2fa-form').submit();
            }, 300);
        }
    });

    // Focus on input when page loads
    codeInput.focus();
});
</script>
@endsection

