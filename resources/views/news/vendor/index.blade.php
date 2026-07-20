@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">Berita Terkini</h3>
            <p class="text-muted small m-0">Senarai berita dan makluman terkini daripada agensi.</p>
        </div>
        @auth
            <a href="{{ asset('dashboard') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Dashboard
            </a>
        @else
            <a href="{{ url('/') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Laman Utama
            </a>
        @endauth
    </div>

    <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,0.06);overflow:hidden;">

        <div style="display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid #f3f4f6;">
            <div style="width:34px;height:34px;background:rgba(196,30,58,0.08);color:#c41e3a;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1-4 0v-13a1 1 0 0 0-1-1h-10a1 1 0 0 0-1 1v12a3 3 0 0 0 3 3h11"/><path d="M8 8l4 0"/><path d="M8 12l4 0"/><path d="M8 16l4 0"/></svg>
            </div>
            <div>
                <div class="fw-bold text-dark" style="font-size:0.88rem;">Arkib Berita</div>
                <div class="text-muted" style="font-size:0.72rem;">{{ $newsList->total() }} berita dijumpai</div>
            </div>
        </div>

        @forelse ($newsList as $news)
            <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6;transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div style="flex:1;min-width:0;">
                        <a href="{{ asset('news/' . $news->id) }}" style="font-size:0.9rem;font-weight:600;color:#111827;text-decoration:none;display:block;margin-bottom:5px;line-height:1.4;"
                            onmouseover="this.style.color='#c41e3a'" onmouseout="this.style.color='#111827'">
                            {{ $news->title }}
                        </a>
                        <div style="font-size:0.78rem;color:#6b7280;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ strip_tags($news->notification) }}
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
                            <span style="font-size:0.7rem;color:#9ca3af;display:flex;align-items:center;gap:4px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                {{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}
                            </span>
                            @if ($news->agency)
                                <span style="font-size:0.7rem;color:#9ca3af;display:flex;align-items:center;gap:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    {{ $news->agency->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ asset('news/' . $news->id) }}" style="flex-shrink:0;display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;font-weight:600;color:#0369a1;text-decoration:none;padding:5px 12px;background:#f0f9ff;border-radius:6px;border:1px solid #bae6fd;white-space:nowrap;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Selanjutnya
                    </a>
                </div>
            </div>
        @empty
            <div class="d-flex align-items-center gap-3 m-3 p-4 rounded-2" style="background:#f0f9ff;border:1px solid #bae6fd;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span style="font-size:0.82rem;color:#0c4a6e;font-weight:600;">Tiada berita pada masa ini.</span>
            </div>
        @endforelse

        @if ($newsList->hasPages())
            <div style="padding:14px 20px;border-top:1px solid #f3f4f6;background:#fafafa;">
                {{ $newsList->links() }}
            </div>
        @endif

    </div>

@endsection
