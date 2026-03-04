@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
            <div class="mb-3 mb-lg-0">
                <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Rekod Versi</h3>
                <p class="text-muted small m-0">Sistem Tender Online Selangor</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <a href="{{ route('version-histories.show', $versionHistory->id) }}" class="btn btn-outline-info d-flex align-items-center gap-2">Lihat</a>
                <div class="bg-white px-3 py-2 rounded-2 shadow-sm border d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border">TARIKH</span>
                    <span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {!! Former::open(route('version-histories.update', $versionHistory->id)) !!}
        {!! Former::populate($versionHistory) !!}
        {!! Former::hidden('_method', 'PUT') !!}
        <div class="stats-card mb-4">
            <div class="stats-card-header p-4 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    <h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">Maklumat Versi</h3>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Versi <span class="text-danger">*</span></label>
                        {!! Former::text('version')->label(false)->placeholder('Contoh: 1.0')->required()->class('form-control') !!}
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tarikh <span class="text-danger">*</span></label>
                        <input type="date" name="released_at" class="form-control" value="{{ old('released_at', $versionHistory->released_at ? $versionHistory->released_at->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Nota (satu baris per item)</label>
                        {!! Former::textarea('notes')->label(false)->placeholder('Satu baris per item')->rows(10)->class('form-control') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('version-histories.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali ke Senarai
                    </a>
                    <button type="submit" class="btn btn-selangor d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        {!! Former::close() !!}
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
