@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Paparan Rekod Versi</h3>
            <p class="text-muted small m-0">Sistem Tender Online Selangor</p>
        </div>
        <a href="{{ route('version-histories.edit', $versionHistory->id) }}" class="btn-form btn-form-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Kemaskini
        </a>
    </div>

    <div class="content-card mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <h3 class="content-card-title">Versi {{ $versionHistory->version }}</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Versi</label>
                    <div class="fw-semibold text-dark">
                        <span class="badge bg-primary fs-6">{{ $versionHistory->version }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Tarikh</label>
                    <div class="fw-semibold text-dark">{{ $versionHistory->formatted_date }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium small text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Nota</label>
                    @if (count($versionHistory->notes_lines) > 0)
                        <ol class="mb-0 ps-3">
                            @foreach ($versionHistory->notes_lines as $line)
                                <li>{!! nl2br(e($line)) !!}</li>
                            @endforeach
                        </ol>
                    @else
                        <span class="text-muted">{{ $versionHistory->notes ?: '—' }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light rounded-bottom">
            <a href="{{ route('version-histories.index') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Senarai
            </a>
            <form action="{{ route('version-histories.destroy', $versionHistory->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-form btn-form-danger confirm-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Padam
                </button>
            </form>
        </div>
    </div>
@endsection
