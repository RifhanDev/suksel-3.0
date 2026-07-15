@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php($forcePasswordChange = $forcePasswordChange ?? false)

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">
                {{ $forcePasswordChange ? 'Tetapkan Kata Laluan Anda' : 'Kemaskini Kata Laluan' }}
            </h3>
            <p class="text-muted small m-0">
                {{ $forcePasswordChange ? 'Sila tetapkan kata laluan baharu sebelum meneruskan penggunaan sistem.' : 'Kemaskini kata laluan akaun anda.' }}
            </p>
        </div>
    </div>

    {!! Former::open($forcePasswordChange ? url('profile/force_password_change') : url('profile/change_password'))->autocomplete('off') !!}
    {!! Former::hidden('_method', 'PUT') !!}

    <div class="content-card">

        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Maklumat Kata Laluan</h3>
            </div>
        </div>

        <div class="content-card-body p-4">

            @if ($forcePasswordChange)
                <div class="d-flex align-items-start gap-2 mb-4 p-3 rounded-2"
                    style="background:#fffbeb; border:1px solid #fde68a; font-size:0.82rem; color:#92400e; line-height:1.6;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span>Sila tetapkan kata laluan baharu sebelum meneruskan penggunaan sistem.</span>
                </div>
            @endif

            <div class="d-flex align-items-start gap-2 mb-4 p-3 rounded-2"
                style="background:#eff6ff; border:1px solid #bae6fd; font-size:0.82rem; color:#0369a1; line-height:1.6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Kata laluan mestilah sekurang-kurangnya <strong>8 aksara</strong> dan mengandungi satu simbol, nombor, huruf besar dan huruf kecil. Sila tukar kata laluan setiap 6 bulan.</span>
            </div>

            <div class="row g-3">
                @if (!$forcePasswordChange)
                    <div class="col-12">
                        <label class="form-label fw-medium small">Kata Laluan Asal <span class="text-danger">*</span></label>
                        <input type="password" name="old_password" class="form-control" autocomplete="new-password" required>
                        {!! $errors->first('old_password', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>
                @endif

                <div class="col-12">
                    <label class="form-label fw-medium small">Kata Laluan Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" autocomplete="new-password" required>
                    {!! $errors->first('password', '<div class="text-danger small mt-1">:message</div>') !!}
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium small">Sahkan Kata Laluan Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                    {!! $errors->first('password_confirmation', '<div class="text-danger small mt-1">:message</div>') !!}
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light rounded-bottom">
            @if (!$forcePasswordChange)
                <a href="{{ asset('profile') }}" class="btn-form btn-form-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Profil Saya
                </a>
            @else
                <div></div>
            @endif
            <button type="submit" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
        </div>

    </div>

    {!! Former::close() !!}

@endsection

@section('scripts')
@endsection
