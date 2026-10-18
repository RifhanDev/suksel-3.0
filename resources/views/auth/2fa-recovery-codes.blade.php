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

    /* Guidance on the left, codes and actions on the right. */
    .tf-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .tf-guide { padding-right: 2.5rem; }

    .tf-action {
        padding-left: 2.5rem;
        border-left: 1px solid #f3f4f6;
    }

    .custom-alert-warning {
        background-color: #fffbeb;
        color: #92400e;
        border: none;
        border-left: 3px solid #f59e0b;
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        line-height: 1.45;
        margin-bottom: 1.5rem;
    }

    .tf-tips {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 1rem;
    }

    .tf-tips li {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        font-size: 0.85rem;
        color: #4b5563;
        line-height: 1.5;
    }

    .tf-tips svg { flex-shrink: 0; margin-top: 2px; color: var(--sg-red); }
    .tf-tips strong { display: block; color: var(--sg-black); font-size: 0.95rem; }

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
        margin-bottom: 0.75rem;
    }

    .btn-outline-selangor:hover { background: #fef2f2; color: var(--sg-red); }

    @media (max-width: 991px) {
        .tf-grid { grid-template-columns: 1fr; gap: 1.75rem; }
        .tf-guide { padding-right: 0; }
        .tf-action { padding-left: 0; border-left: none; }
    }

    @media (max-width: 768px) {
        .tf-header { margin: 0 1.5rem 1.5rem 1.5rem; }
        .tf-body { padding: 0 1.5rem 2rem 1.5rem; }
        .code-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="tf-page">
    <div class="tf-card">

        <div class="tf-header">
            <div class="tf-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M9 12l2 2l4 -4" /></svg>
            </div>
            <div>
                <h1 class="tf-title">Kod Pemulihan</h1>
                <p class="tf-subtitle">Simpan kod ini di tempat selamat</p>
            </div>
        </div>

        <div class="tf-body">
            <div class="tf-grid">

                <div class="tf-guide">
                    <div class="custom-alert-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1"><path d="M12 9v4" /><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 16h.01" /></svg>
                        <div>Kod ini <strong>hanya dipaparkan sekali sahaja</strong>. Salin dan simpan sekarang sebelum meninggalkan halaman ini.</div>
                    </div>

                    <ul class="tf-tips">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" /><path d="M12 18h.01" /></svg>
                            <div><strong>Bila digunakan</strong>Jika telefon hilang, ditukar, atau entri dalam aplikasi pengesah terpadam.</div>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3" /><circle cx="12" cy="12" r="9" /></svg>
                            <div><strong>Sekali guna</strong>Setiap kod hanya boleh digunakan sekali, semasa log masuk atau semasa Hantar penilaian.</div>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2" /><circle cx="12" cy="16" r="1" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                            <div><strong>Simpan dengan selamat</strong>Jangan kongsi kod ini dengan sesiapa. Kod baharu boleh dijana di halaman 2FA anda.</div>
                        </li>
                    </ul>
                </div>

                <div class="tf-action">
                    <div class="code-grid" id="code-grid">
                        @foreach ($codes as $code)
                            <div class="code-chip">{{ $code }}</div>
                        @endforeach
                    </div>

                    <button type="button" class="btn-outline-selangor" id="copy-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="8" width="12" height="12" rx="2" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>
                        <span id="copy-text">Salin Semua Kod</span>
                    </button>

                    <a href="{{ route('2fa.manage') }}" class="btn-selangor text-decoration-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10" /></svg>
                        Saya Telah Menyimpannya
                    </a>
                </div>

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
