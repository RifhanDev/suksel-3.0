@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Tambah Rekod Versi</h3>
            <p class="text-muted small m-0">Sistem Tender Online Selangor</p>
        </div>
    </div>

    {!! Former::open(route('version-histories.store')) !!}
    {!! Former::populate($versionHistory) !!}

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <h3 class="content-card-title">Maklumat Versi</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Versi <span class="text-danger">*</span></label>
                    <input type="text" name="version" class="form-control" value="{{ old('version') }}" placeholder="Contoh: 1.0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Tarikh <span class="text-danger">*</span></label>
                    <input type="text" name="released_at" class="form-control datepicker" value="{{ old('released_at') }}" placeholder="Pilih tarikh..." required readonly>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium small">Nota <span class="text-muted fw-normal">(satu baris per item)</span></label>
                    {!! Former::textarea('notes')->label(false)->placeholder("Masukkan setiap perubahan pada baris berasingan.\nContoh:\nLive\nItem perubahan 1\nItem perubahan 2")->rows(10)->class('form-control') !!}
                    <div class="text-muted small mt-1">Setiap baris akan dipaparkan sebagai item dalam senarai bernombor.</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light rounded-bottom">
            <a href="{{ route('version-histories.index') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Senarai
            </a>
            <button type="submit" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Simpan
            </button>
        </div>
    </div>

    {!! Former::close() !!}
@endsection

@section('scripts')
<script>
    $('.datepicker').datepicker({
        format: 'd M yyyy',
        autoclose: true,
        todayHighlight: true,
    });
</script>
@endsection
