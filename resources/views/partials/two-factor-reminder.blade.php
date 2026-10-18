{{-- 2FA reminder during the grace period; used by both the staff and vendor layouts. --}}
@if (auth()->check() && auth()->user()->requiresTwoFactor() && !auth()->user()->hasTwoFactorEnabled() && !request()->routeIs('2fa.setup', '2fa.confirm', '2fa.manage'))
    @php
        $twoFactorPending = auth()->user()->twoFactorAuth;
    @endphp
    @if ($twoFactorPending && $twoFactorPending->required_since)
        @php
            $twoFactorDeadline = $twoFactorPending->required_since->copy()
                ->addDays(\App\TwoFactorSetting::current()->grace_period_days);
        @endphp
        @if (now()->lessThanOrEqualTo($twoFactorDeadline))
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                Anda perlu mengaktifkan pengesahan dua faktor (2FA) sebelum {{ $twoFactorDeadline->format('d/m/Y, h:i A') }}.
                <a href="{{ route('2fa.setup') }}" class="alert-link">Aktifkan sekarang</a>.
            </div>
        @endif
    @endif
@endif
