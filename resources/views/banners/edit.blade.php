@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Banner</h3>
            <p class="text-muted small m-0">Kemaskini maklumat banner di bawah.</p>
        </div>
    </div>

    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="content-card">
            <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="fw-bold text-dark text-uppercase small">Maklumat Banner</span>
            </div>

            <div class="p-4">
                @include('banners.form')
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                <div class="d-flex gap-2">
                    <a href="{{ asset('banners') }}" class="btn-form btn-form-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Batal
                    </a>
                    @if ($banner->file)
                        <a href="{{ $banner->file->url }}/{{ $banner->file->name }}" target="_blank"
                            class="btn-form btn-form-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                <path d="M21 12c-2.4 4-5.4 6-9 6c-3.6 0-6.6-2-9-6c2.4-4 5.4-6 9-6c3.6 0 6.6 2 9 6" />
                            </svg>
                            Lihat Banner
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Kemaskini Banner
                </button>
            </div>
        </div>
    </form>
@endsection
