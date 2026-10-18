@extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
    {{-- The vendor layout does not load the component styles that the staff layout has. --}}
    @if (Auth::user()->hasRole('Vendor'))
        <link href="{{ asset('css/components/content-card.css') }}" rel="stylesheet">
        <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
        <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
        <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
        <link href="{{ asset('css/components/modal-confirm.css') }}" rel="stylesheet">
    @endif
@endsection

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pengesahan Dua Faktor</h3>
            <p class="text-muted small m-0">Keselamatan tambahan untuk akaun anda.</p>
        </div>
    </div>

    {{-- The staff layout shows every flash; the vendor layout only shows notice/error, so show the rest here. --}}
    @if (Auth::user()->hasRole('Vendor'))
        @foreach (['success', 'info', 'warning'] as $flashType)
            @if (session($flashType))
                <div class="alert alert-{{ $flashType }} alert-dismissible fade show mb-4" role="alert">
                    {{ session($flashType) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Status Akaun</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            @if ($twoFactor && $twoFactor->confirmed_at)
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="small fw-bold text-uppercase text-muted mb-2">Status</div>
                        <span class="badge-status badge-status-success">Diaktifkan</span>
                    </div>
                    <div class="col-md-4">
                        <div class="small fw-bold text-uppercase text-muted mb-2">Tarikh Diaktifkan</div>
                        <div class="fw-semibold text-dark">{{ $twoFactor->confirmed_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small fw-bold text-uppercase text-muted mb-2">Kod Pemulihan</div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="fw-semibold text-dark">{{ $remainingCodes }} belum digunakan</span>
                            @if ($remainingCodes <= 2)
                                <span class="badge-status badge-status-warning">Sila jana kod baharu</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-top pt-4 mt-4">
                    <span class="text-muted small">
                        @if ($isRequired)
                            Peranan anda mewajibkan 2FA, jadi ia tidak boleh dimatikan.
                        @endif
                    </span>

                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        @if (!$isRequired)
                            <form method="POST" action="{{ route('2fa.disable') }}"
                                  data-confirm="Matikan pengesahan dua faktor untuk akaun anda?"
                                  data-confirm-title="Matikan 2FA" data-confirm-text="Ya, Matikan" data-confirm-icon="danger">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-form btn-form-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.69 14a6.9 6.9 0 0 0 .31-2V5l-8-3-3.16 1.18"></path><path d="M4.73 4.73 4 5v7c0 6 8 10 8 10a20.29 20.29 0 0 0 5.62-4.38"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    Matikan 2FA
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('2fa.regenerate') }}"
                              data-confirm="Semua kod pemulihan lama akan dibatalkan."
                              data-confirm-title="Jana Kod Pemulihan Baharu" data-confirm-text="Ya, Jana">
                            @csrf
                            <button type="submit" class="btn-form btn-form-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                Jana Kod Pemulihan Baharu
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @if ($isRequired)
                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div class="small lh-sm">
                            <strong>Wajib untuk peranan anda</strong>
                            @if ($deadline && now()->greaterThan($deadline))
                                Tempoh untuk mengaktifkan telah tamat pada {{ $deadline->format('d/m/Y, h:i A') }}. Anda tidak boleh menggunakan sistem sehingga 2FA diaktifkan.
                            @elseif ($deadline)
                                Sila aktifkan sebelum {{ $deadline->format('d/m/Y, h:i A') }}. Selepas tarikh ini, anda tidak boleh menggunakan sistem sehingga 2FA diaktifkan.
                            @else
                                Peranan anda mewajibkan pengesahan dua faktor. Sila aktifkan sekarang.
                            @endif
                        </div>
                    </div>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge-status badge-status-warning">Belum Diaktifkan</span>
                </div>

                <p class="text-muted small mb-0">
                    Lindungi akaun anda dengan kod sekali guna dari aplikasi pengesah pada telefon anda.
                </p>

                <div class="d-flex justify-content-end border-top pt-4 mt-4">
                    <a href="{{ route('2fa.setup') }}" class="btn-form btn-form-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
                        Aktifkan Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- The staff layout already includes the confirm popup; the vendor layout does not. --}}
    @if (Auth::user()->hasRole('Vendor'))
        @include('components.confirm-dialog-modal')
    @endif
@endsection

@section('scripts')
@if (Auth::user()->hasRole('Vendor'))
    <script src="{{ asset('js/confirm-modal.js') }}"></script>
@endif
<script>
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            showConfirmModal({
                title: form.dataset.confirmTitle,
                message: form.dataset.confirm,
                confirmText: form.dataset.confirmText,
                icon: form.dataset.confirmIcon || 'warning'
            }).then(function (ok) {
                if (ok) form.submit();
            });
        });
    });
</script>
@endsection
