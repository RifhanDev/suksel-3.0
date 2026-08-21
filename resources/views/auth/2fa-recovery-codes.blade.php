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

    .card-header-custom { padding: 2.5rem 2rem 1rem 2rem; text-align: center; }
    .card-body-custom { padding: 0.5rem 2.5rem 2.5rem 2.5rem; }

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

    .btn-outline-selangor {
        background: #fff;
        color: var(--sg-red);
        font-weight: 700;
        padding: 0.7rem;
        border-radius: var(--radius-sm);
        border: 2px solid var(--sg-red);
        width: 100%;
        transition: all 0.2s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-selangor:hover { background: #fef2f2; color: var(--sg-red); }

    .custom-alert-warning {
        background-color: #fffbeb;
        color: #92400e;
        border: none;
        border-left: 3px solid #f59e0b;
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        line-height: 1.45;
    }

    .code-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.6rem;
        margin-bottom: 1.5rem;
    }

    .code-chip {
        font-family: monospace;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-align: center;
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: var(--radius-sm);
        padding: 0.6rem 0.4rem;
        color: var(--sg-black);
    }

    @media (max-width: 768px) {
        .card-header-custom { padding: 2rem 1.5rem 0.5rem 1.5rem; }
        .card-body-custom { padding: 0.5rem 1.5rem 2rem 1.5rem; }
        .code-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-lg-5 col-md-7 col-sm-11">
        <div class="selangor-card">

            <div class="card-header-custom">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #fff; border: 2px solid var(--sg-yellow); border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M9 12l2 2l4 -4" /></svg>
                </div>

                <h1 class="h4 fw-bold mb-1 text-uppercase" style="letter-spacing: -0.5px; color:var(--sg-red)">Kod Pemulihan</h1>
                <p class="text-muted small fw-semibold mb-0">Simpan kod ini di tempat selamat</p>
            </div>

            <div class="card-body-custom">

                <div class="custom-alert-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 9v4" /><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 16h.01" /></svg>
                    <div>
                        Kod ini <strong>hanya dipaparkan sekali sahaja</strong>. Salin dan simpan sekarang &mdash; ia diperlukan jika anda kehilangan peranti pengesah. Setiap kod hanya boleh digunakan sekali.
                    </div>
                </div>

                <div class="code-grid" id="code-grid">
                    @foreach ($codes as $code)
                        <div class="code-chip">{{ $code }}</div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <button type="button" class="btn-outline-selangor" id="copy-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="8" width="12" height="12" rx="2" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>
                        <span id="copy-text">Salin Semua Kod</span>
                    </button>
                </div>

                <a href="{{ route('2fa.manage') }}" class="btn-selangor text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10" /></svg>
                    Saya Telah Menyimpannya
                </a>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('copy-btn').addEventListener('click', function() {
        const codes = Array.from(document.querySelectorAll('#code-grid .code-chip'))
            .map(el => el.textContent.trim())
            .join('\n');

        navigator.clipboard.writeText(codes).then(function() {
            const label = document.getElementById('copy-text');
            label.textContent = 'Disalin!';
            setTimeout(() => label.textContent = 'Salin Semua Kod', 2000);
        });
    });
});
</script>
@endsection
