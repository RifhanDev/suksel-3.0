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
        height: 6px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .card-header-custom {
        padding: 2.5rem 2rem 1rem 2rem;
        text-align: center;
    }

    .card-body-custom {
        padding: 0.5rem 2.5rem 2.5rem 2.5rem;
    }

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
        text-align: center;
        letter-spacing: 0.5em;
        font-size: 1.5rem;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--sg-red);
        box-shadow: 0 0 0 4px rgba(196, 30, 58, 0.1);
        color: var(--sg-black);
    }

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
    }

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

    .custom-alert-warning {
        background-color: #fffbeb;
        color: #92400e;
        border-left: 3px solid #f59e0b;
    }

    .qr-wrapper {
        background: #fff;
        border: 2px solid #f3f4f6;
        border-radius: var(--radius-sm);
        padding: 1rem;
        display: inline-block;
        line-height: 0;
    }

    .qr-wrapper svg { width: 200px; height: 200px; }

    .secret-key {
        font-family: monospace;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: var(--radius-sm);
        padding: 0.6rem 0.75rem;
        word-break: break-all;
        color: var(--sg-black);
    }

    .step-list {
        font-size: 0.85rem;
        color: #4b5563;
        padding-left: 1.1rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .card-header-custom { padding: 2rem 1.5rem 0.5rem 1.5rem; }
        .card-body-custom { padding: 0.5rem 1.5rem 2rem 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-lg-5 col-md-7 col-sm-11">
        <div class="selangor-card">

            <div class="card-header-custom">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #fff; border: 2px solid var(--sg-yellow); border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" /><path d="M12 18h.01" /></svg>
                </div>

                <h1 class="h4 fw-bold mb-1 text-uppercase" style="letter-spacing: -0.5px; color:var(--sg-red)">Sediakan Pengesahan Dua Faktor</h1>
                <p class="text-muted small fw-semibold mb-0">Sistem e-Perolehan Selangor</p>
            </div>

            <div class="card-body-custom">

                @if (session('error'))
                    <div class="custom-alert custom-alert-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><circle cx="12" cy="12" r="9" /><path d="M12 9v4" /><path d="M12 16h.01" /></svg>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="custom-alert custom-alert-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 9v4" /><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 16h.01" /></svg>
                        <div>{{ session('warning') }}</div>
                    </div>
                @endif

                <ol class="step-list mb-3">
                    <li>Pasang aplikasi <strong>Google Authenticator</strong> atau <strong>Microsoft Authenticator</strong>.</li>
                    <li>Imbas kod QR di bawah, atau masukkan kunci secara manual.</li>
                    <li>Masukkan kod 6 digit yang dipaparkan untuk mengesahkan.</li>
                </ol>

                <div class="text-center mb-3">
                    <div class="qr-wrapper">{!! $qrCodeSvg !!}</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kunci Manual</label>
                    <div class="secret-key text-center">{{ $secret }}</div>
                </div>

                <form method="POST" action="{{ route('2fa.confirm') }}" autocomplete="off" id="confirm-form">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Kod Pengesahan</label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               name="code"
                               id="code"
                               placeholder="000000"
                               required
                               autocomplete="off"
                               autofocus
                               maxlength="6"
                               pattern="[0-9]{6}"
                               inputmode="numeric">
                        @error('code')
                            <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-selangor">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10" /></svg>
                        Sahkan &amp; Aktifkan
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');

    codeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');

        if (this.value.length === 6) {
            setTimeout(() => document.getElementById('confirm-form').submit(), 300);
        }
    });

    codeInput.focus();
});
</script>
@endsection
