@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Lihat Templat Penolakan</h3>
            <p class="text-muted small m-0">{{ $template->title }}</p>
        </div>
    </div>

    <div class="content-card">
        <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span class="fw-bold text-dark text-uppercase small">Maklumat Templat Penolakan</span>
        </div>

        <div class="p-4">
            @include('reject-template.form')
        </div>

        <div class="p-4 border-top bg-light">
            @include('reject-template.actions-footer')
        </div>
    </div>
@endsection
