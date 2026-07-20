@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Paparan Berita</h3>
            <p class="text-muted small m-0">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}</p>
        </div>
        <a href="{{ asset('news') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Arkib Berita
        </a>
        @auth
            <a href="{{ asset('dashboard') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Dashboard
            </a>
        @else
            <a href="{{ url('/') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Laman Utama
            </a>
        @endauth
    </div>

    <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,0.06);overflow:hidden;">

        {{-- News header --}}
        <div style="padding:20px;border-bottom:1px solid #f3f4f6;">
            <div class="d-flex align-items-start gap-3">
                <div style="width:38px;height:38px;background:rgba(196,30,58,0.08);color:#c41e3a;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1-4 0v-13a1 1 0 0 0-1-1h-10a1 1 0 0 0-1 1v12a3 3 0 0 0 3 3h11"/><path d="M8 8l4 0"/><path d="M8 12l4 0"/><path d="M8 16l4 0"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <h3 class="fw-bold m-0 mb-2" style="font-size:1.1rem;color:#1e293b;line-height:1.4;">{{ $news->title }}</h3>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        @if ($news->agency)
                            <span style="font-size:0.75rem;color:#6b7280;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                <a href="{{ asset('agencies/' . $news->agency->id) }}" style="color:#c41e3a;text-decoration:none;font-weight:600;">{{ $news->agency->name }}</a>
                            </span>
                        @endif
                        @if ($news->tender)
                            <span style="font-size:0.75rem;color:#6b7280;display:flex;align-items:center;gap:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                untuk <a href="{{ asset('tenders/' . $news->tender->id) }}" style="color:#c41e3a;text-decoration:none;font-weight:600;">{{ $news->tender->name }}</a>
                            </span>
                        @endif
                        <span style="font-size:0.75rem;color:#9ca3af;display:flex;align-items:center;gap:5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- News body --}}
        <div style="padding:24px 24px;line-height:1.85;color:#374151;font-size:0.92rem;">
            {!! $news->notification !!}
        </div>

    </div>

@endsection
