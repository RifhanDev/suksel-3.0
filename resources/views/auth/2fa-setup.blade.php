@extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
<style>
    .tf-page {
        display: flex;
        justify-content: center;
    }

    .tf-card {
        width: 100%;
        max-width: 980px;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 40px -10px rgba(196, 30, 58, 0.1);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        position: relative;
    }

    .tf-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .tf-header {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 1.4rem 0 1rem 0;
        margin: 0 2.5rem 1.25rem 2.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .tf-header-icon {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 2px solid var(--sg-yellow);
        border-radius: 50%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        flex-shrink: 0;
    }

    .tf-title {
        font-size: 1.15rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: -0.5px;
        color: var(--sg-red);
        margin-bottom: 0.1rem;
    }

    .tf-subtitle {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0;
    }

    .tf-body { padding: 0 2.5rem 1.75rem 2.5rem; }

    /* Steps and manual key on the left, QR and code on the right. */
    .tf-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .tf-guide { padding-right: 2.5rem; }

    /* Numbered steps: the order matters, so the numbers carry meaning. */
    .tf-steps {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 1rem;
    }

    .tf-steps li {
        display: flex;
        gap: 0.85rem;
        align-items: flex-start;
    }

    .tf-step-num {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--sg-red);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .tf-step-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--sg-black);
        margin-bottom: 0.1rem;
    }

    .tf-step-text {
        font-size: 0.83rem;
        color: #6b7280;
        line-height: 1.45;
    }

    .tf-hint {
        font-size: 0.8rem;
        color: #6b7280;
        margin: 1.25rem 0 0.4rem 0;
    }

    .secret-key {
        font-family: monospace;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-align: center;
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: var(--radius-sm);
        padding: 0.55rem 0.75rem;
        word-break: break-all;
        color: var(--sg-black);
    }

    .tf-action {
        text-align: center;
        padding-left: 2.5rem;
        border-left: 1px solid #f3f4f6;
    }

    .qr-wrapper {
        background: #fff;
        border: 2px solid #f3f4f6;
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        display: inline-block;
        line-height: 0;
        margin-bottom: 1rem;
    }

    .qr-wrapper svg { width: 170px; height: 170px; }

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
        padding: 0.55rem 1rem;
        font-weight: 600;
        color: var(--sg-black);
        background-color: #f9fafb;
        transition: all 0.2s ease;
        text-align: center;
        letter-spacing: 0.5em;
        font-size: 1.35rem;
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
        padding: 0.7rem;
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
        font-size: 0.83rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        line-height: 1.4;
    }

    .custom-alert-warning {
        background-color: #fffbeb;
        color: #92400e;
        border-left: 3px solid #f59e0b;
    }

    .tf-flash { margin-bottom: 1rem; }
    .tf-guide .custom-alert { margin-top: 1.25rem; }

    @media (max-width: 991px) {
        .tf-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        .tf-guide { padding-right: 0; }
        .tf-action { padding-left: 0; border-left: none; }
    }

    @media (max-width: 768px) {
        .tf-header { margin: 0 1.5rem 1.25rem 1.5rem; }
        .tf-body { padding: 0 1.5rem 1.5rem 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="tf-page">
    <div class="tf-card">

        <div class="tf-header">
            <div class="tf-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" /><path d="M12 18h.01" /></svg>
            </div>
            <div>
                <h1 class="tf-title">Sediakan Pengesahan Dua Faktor</h1>
                <p class="tf-subtitle">Perolehan Selangor</p>
            </div>
        </div>

        <div class="tf-body">

            {{-- The staff layout shows every message at the top; the vendor layout skips warnings, so show them here. --}}
            @if (Auth::user()->hasRole('Vendor') && session('warning'))
                <div class="custom-alert custom-alert-warning tf-flash">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 9v4" /><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 16h.01" /></svg>
                    <div>{{ session('warning') }}</div>
                </div>
            @endif

            <div class="tf-grid">

                <div class="tf-guide">
                    <ol class="tf-steps">
                        <li>
                            <span class="tf-step-num">1</span>
                            <div>
                                <div class="tf-step-title">Pasang aplikasi pengesah</div>
                                <div class="tf-step-text">Muat turun <strong>Google Authenticator</strong> atau <strong>Microsoft Authenticator</strong> daripada Play Store atau App Store.</div>
                            </div>
                        </li>
                        <li>
                            <span class="tf-step-num">2</span>
                            <div>
                                <div class="tf-step-title">Imbas kod QR</div>
                                <div class="tf-step-text">Dalam aplikasi, tekan <strong>+</strong> dan imbas kod QR. Jika tidak dapat imbas, masukkan kunci manual di bawah.</div>
                            </div>
                        </li>
                        <li>
                            <span class="tf-step-num">3</span>
                            <div>
                                <div class="tf-step-title">Masukkan kod 6 digit</div>
                                <div class="tf-step-text">Taip kod yang dipaparkan dalam aplikasi. Kod bertukar setiap 30 saat.</div>
                            </div>
                        </li>
                    </ol>

                    <p class="tf-hint">Tidak dapat imbas? Masukkan kunci ini secara manual:</p>
                    <div class="secret-key">{{ $secret }}</div>

                    @if ($hadTwoFactorBefore ?? false)
                        <div class="custom-alert custom-alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 9v4" /><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 16h.01" /></svg>
                            <div>Pernah mengaktifkan 2FA sebelum ini? Padam entri lama bagi akaun ini dalam aplikasi pengesah, kemudian imbas kod QR baharu. Entri baharu bertarikh {{ now()->format('d/m/Y') }}. Kod daripada entri lama tidak akan berfungsi.</div>
                        </div>
                    @endif
                </div>

                <div class="tf-action">
                    <div class="qr-wrapper">{!! $qrCodeSvg !!}</div>

                    <form method="POST" action="{{ route('2fa.confirm') }}" autocomplete="off" id="confirm-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="code">Kod Pengesahan</label>
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
