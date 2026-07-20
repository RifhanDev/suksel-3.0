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
        .info-table { width: 100%; }
        .info-table tr { border-bottom: 1px solid #f1f5f9; }
        .info-table tr:last-child { border-bottom: none; }
        .info-table th {
            padding: 10px 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            width: 35%;
            vertical-align: top;
        }
        .info-table td {
            padding: 10px 20px;
            font-size: 0.82rem;
            color: #1f2937;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    @php $user = Auth::user(); @endphp

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Profil Saya</h3>
            <p class="text-muted small m-0">Maklumat akaun dan tetapan pengguna.</p>
        </div>
        <a href="{{ asset('dashboard') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="vendor-tender-card">
        <div class="vendor-tender-card-header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h6>Maklumat Akaun</h6>
        </div>
        <table class="info-table">
            <tr>
                <th>Nama</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Alamat Emel</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Nama Syarikat</th>
                <td>{{ $user->vendor->name }}</td>
            </tr>
            <tr>
                <th>Tarikh Didaftarkan</th>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('j M Y') }}</td>
            </tr>
        </table>
        <div class="d-flex justify-content-end align-items-center px-4 py-3 border-top" style="background:#f8fafc;">
            <a href="{{ asset('profile/change_password') }}" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Kemaskini Kata Laluan
            </a>
        </div>
    </div>

@endsection
