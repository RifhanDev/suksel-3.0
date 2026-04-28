@extends('layouts.v3.master')

@section('styles')
<style>
    #tbl-version-histories thead th {
        background-color: #f8fafc;
        color: #6b7280;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
        white-space: nowrap;
        border-bottom: 1px solid #e5e7eb;
    }

    #tbl-version-histories tbody td {
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        padding-top: 0.95rem;
        padding-bottom: 0.95rem;
    }

    #tbl-version-histories tbody tr:hover {
        background-color: #fafafa;
    }

    #tbl-version-histories thead th:last-child,
    #tbl-version-histories tbody td:last-child {
        text-align: center;
    }

    .version-history-actions {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .version-history-actions .btn {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .version-history-actions .icon {
        width: 16px;
        height: 16px;
    }
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

        <div class="content-card p-0 mb-4">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Rekod Versi</h3>
                    </div>
                    <a href="{{ route('version-histories.create') }}" class="btn btn-selangor d-flex align-items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah Versi
                    </a>
                </div>
            </div>
            <div class="content-card-body p-2">
                <div class="table-responsive">
                    <table id="tbl-version-histories" data-path="{{ route('version-histories.index') }}" class="DT-index table table-hover align-middle w-100 mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-4" style="width: 100px;">Versi</th>
                                <th class="py-3" style="width: 130px;">Tarikh</th>
                                <th>Nota</th>
                                <th class="py-3 pe-4" style="width: 160px;">Tindakan</th>
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
