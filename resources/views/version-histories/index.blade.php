@extends('layouts.v3.master')

@section('styles')
<style>
    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        position: relative;
    }
    .stats-card::before {
        content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
        background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
    }
    .stats-card-header {
        padding: 20px 16px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .stats-card-title {
        margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;
    }
    .table-modern thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .table-modern tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr:hover { background-color: #fafafa; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
            <div class="mb-3 mb-lg-0">
                <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Urus Sejarah Perubahan Sistem</h3>
                <p class="text-muted small m-0">Pengurusan rekod versi dan nota pelepasan.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <a href="{{ url('version-histories') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    Lihat Halaman Awam
                </a>
                <div class="bg-white px-3 py-2 rounded-2 shadow-sm border d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border">TARIKH</span>
                    <span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 align-middle"> <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="stats-card mb-4">
            <div class="stats-card-header">
                <h3 class="stats-card-title">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    Senarai Rekod Versi
                </h3>
                <a href="{{ route('version-histories.create') }}" class="btn btn-selangor d-flex align-items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Versi
                </a>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table data-path="{{ route('version-histories.index') }}" class="DT-index table table-modern w-100 mb-0">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Versi</th>
                                <th style="width: 130px;">Tarikh</th>
                                <th>Nota</th>
                                <th style="width: 120px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
<script src="{{ asset('js/datatables.js') }}"></script>
<script src="{{ asset('js/news.js') }}"></script>
<script>
    $(function() {
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
            target.DataTable({
                ajax: path,
                columns: [
                    { data: 'version', name: 'version' },
                    { data: 'released_at', name: 'released_at' },
                    { data: 'notes', name: 'notes' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                serverSide: true,
                stateSave: true,
                drawCallback: function() { $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip(); },
                language: {
                    sEmptyTable: "Tiada data",
                    sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                    sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
                    sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
                    sLengthMenu: "Papar _MENU_ rekod",
                    sLoadingRecords: "Diproses...", sProcessing: "Sedang diproses...",
                    sSearch: "Carian:", sZeroRecords: "Tiada padanan rekod yang dijumpai.",
                    oPaginate: { sFirst: "Pertama", sPrevious: "Sebelum", sNext: "Kemudian", sLast: "Akhir" }
                },
                order: [[1, 'desc']], pageLength: 25, responsive: true
            });
        });
    });
</script>
@endsection
