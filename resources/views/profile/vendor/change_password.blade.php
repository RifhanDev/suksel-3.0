@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        .vendor-tender-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .vendor-tender-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .vendor-tender-card-header h6 {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .vendor-tender-card-header .header-icon {
            width: 28px; height: 28px;
            background: rgba(196,30,58,0.08);
            color: #c41e3a;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
    </style>
@endsection

@section('content')
    @php($forcePasswordChange = $forcePasswordChange ?? false)

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">
                {{ $forcePasswordChange ? 'Tetapkan Kata Laluan Anda' : 'Kemaskini Kata Laluan' }}
            </h3>
            <p class="text-muted small m-0">
                {{ $forcePasswordChange ? 'Sila tetapkan kata laluan baharu sebelum meneruskan penggunaan sistem.' : 'Kemaskini kata laluan akaun anda.' }}
            </p>
        </div>
    </div>

    {!! Former::open($forcePasswordChange ? url('profile/force_password_change') : url('profile/change_password'))->autocomplete('off') !!}
    {!! Former::hidden('_method', 'PUT') !!}

    <div class="vendor-tender-card">

        <div class="vendor-tender-card-header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h6>{{ $forcePasswordChange ? 'Tetapkan Kata Laluan' : 'Maklumat Kata Laluan' }}</h6>
        </div>

        <div class="p-4">

            @if ($forcePasswordChange)
                <div class="d-flex align-items-start gap-2 mb-4 p-3 rounded-2"
                    style="background:#fffbeb; border:1px solid #fde68a; font-size:0.82rem; color:#92400e; line-height:1.6;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <span>Sila tetapkan kata laluan baharu sebelum meneruskan penggunaan sistem.</span>
                </div>
            @endif

            <div class="d-flex align-items-start gap-2 mb-4 p-3 rounded-2"
                style="background:#eff6ff; border:1px solid #bae6fd; font-size:0.82rem; color:#0369a1; line-height:1.6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
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

        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="background:#f8fafc;">
            @if (!$forcePasswordChange)
                <a href="{{ asset('profile') }}" class="btn-form btn-form-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Profil Saya
                </a>
            @else
                <div></div>
            @endif
            <button type="submit" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan
            </button>
        </div>

    </div>

    {!! Former::close() !!}

@endsection

@section('scripts')
@endsection
