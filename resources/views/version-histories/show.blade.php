@extends('layouts.v3.master')

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
            <div class="mb-3 mb-lg-0">
                <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Paparan Rekod Versi</h3>
                <p class="text-muted small m-0">Sistem Tender Online Selangor</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <a href="{{ route('version-histories.edit', $versionHistory->id) }}" class="btn btn-info d-flex align-items-center gap-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Kemaskini
                </a>
                <div class="bg-white px-3 py-2 rounded-2 shadow-sm border d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border">TARIKH</span>
                    <span class="small text-muted fw-bold">{{ $versionHistory->released_at ? $versionHistory->released_at->format('d/m/Y') : $versionHistory->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="stats-card mb-4">
            <div class="stats-card-header p-4 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    <h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">Versi {{ $versionHistory->version }}</h3>
                </div>
            </div>
            <div class="card-body p-4">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Versi</dt>
                    <dd class="col-sm-9"><span class="badge bg-primary fs-6">{{ $versionHistory->version }}</span></dd>
                    <dt class="col-sm-3 text-muted">Tarikh</dt>
                    <dd class="col-sm-9">{{ $versionHistory->formatted_date }}</dd>
                    <dt class="col-sm-3 text-muted">Nota</dt>
                    <dd class="col-sm-9">
                        @if (count($versionHistory->notes_lines) > 0)
                            <ol class="mb-0 ps-3">
                                @foreach ($versionHistory->notes_lines as $line)
                                    <li>{!! nl2br(e($line)) !!}</li>
                                @endforeach
                            </ol>
                        @else
                            <span class="text-muted">{{ $versionHistory->notes ?: '—' }}</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="stats-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('version-histories.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali ke Senarai
                    </a>
                    <form action="{{ route('version-histories.destroy', $versionHistory->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger d-flex align-items-center gap-2 confirm-delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Padam
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        @include('layouts._register')
        @include('layouts._news')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/news.js') }}"></script>
@endsection
