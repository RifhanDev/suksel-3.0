@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pengesahan Dua Faktor</h3>
            <p class="text-muted small m-0">Keselamatan tambahan untuk akaun anda.</p>
        </div>
    </div>

    {{-- success/error/info flashes are already rendered globally by layouts._notification --}}

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
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-success">AKTIF</span>
                    <span class="text-muted small">
                        Didaftarkan pada {{ $twoFactor->confirmed_at->format('d/m/Y H:i') }}
                    </span>
                </div>

                <p class="text-muted small mb-4">
                    Baki kod pemulihan yang belum digunakan: <strong>{{ $remainingCodes }}</strong>.
                    @if ($remainingCodes <= 2)
                        <span class="text-danger fw-semibold">Sila jana kod baharu.</span>
                    @endif
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('2fa.regenerate') }}"
                          onsubmit="return confirm('Jana kod pemulihan baharu? Semua kod lama akan dibatalkan.');">
                        @csrf
                        <button type="submit" class="btn-form btn-form-create">Jana Kod Pemulihan Baharu</button>
                    </form>

                    @if (!$isRequired)
                        <form method="POST" action="{{ route('2fa.disable') }}"
                              onsubmit="return confirm('Matikan pengesahan dua faktor untuk akaun anda?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Matikan 2FA</button>
                        </form>
                    @else
                        <span class="text-muted small align-self-center">
                            Peranan anda memerlukan 2FA &mdash; ia tidak boleh dimatikan.
                        </span>
                    @endif
                </div>
            @else
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-secondary">TIDAK AKTIF</span>
                    @if ($isRequired)
                        <span class="badge bg-warning text-dark">DIWAJIBKAN</span>
                    @endif
                </div>

                <p class="text-muted small mb-4">
                    Lindungi akaun anda dengan kod sekali guna dari aplikasi pengesah pada telefon anda.
                </p>

                <a href="{{ route('2fa.setup') }}" class="btn-form btn-form-create">Sediakan Sekarang</a>
            @endif
        </div>
    </div>
@endsection
