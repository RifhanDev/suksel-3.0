@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php $user = Auth::user(); @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Profil Saya</h3>
            <p class="text-muted small m-0">Maklumat akaun dan tetapan pengguna.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h3 class="content-card-title">Maklumat Pengguna</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Nama</label>
                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Alamat Emel</label>
                    <div class="fw-semibold text-dark">{{ $user->email }}</div>
                </div>

                @if ($user->roles->count() > 0)
                    <div class="col-md-6">
                        <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Peranan</label>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach ($user->roles as $role)
                                <span class="badge-status badge-status-info">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->hasRole('Vendor'))
                    <div class="col-md-6">
                        <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Nama Syarikat</label>
                        <div class="fw-semibold text-dark">{{ $user->vendor->name }}</div>
                    </div>
                @endif

                @if ($user->agency)
                    <div class="col-md-6">
                        <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Agensi</label>
                        <div class="fw-semibold text-dark">{{ $user->agency->name }}</div>
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Tarikh Didaftarkan</label>
                    <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($user->created_at)->format('j M Y') }}</div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end px-4 py-3 border-top bg-light rounded-bottom">
            <a href="{{ asset('profile/change_password') }}" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Kemaskini Kata Laluan
            </a>
        </div>

    </div>

@endsection
