@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kod Bidang Baru</h3>
            <p class="text-muted small m-0">Sila lengkapkan maklumat kod bidang di bawah.</p>
        </div>
    </div>

    <form action="{{ url('codes') }}" method="POST">
        @csrf

        <div class="content-card">
            <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                <span class="fw-bold text-dark text-uppercase small">Maklumat Kod Bidang</span>
            </div>

            <div class="p-4">
                @include('certificationcodes.form')
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                <a href="{{ asset('codes') }}" class="btn-form btn-form-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </form>
@endsection
