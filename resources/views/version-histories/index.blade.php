@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Urus Sejarah Perubahan Sistem</h3>
            <p class="text-muted small m-0">Pengurusan rekod versi dan nota pelepasan.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <h3 class="content-card-title">Senarai Rekod Versi</h3>
            </div>
            <div class="d-flex align-items-center gap-2 flex-nowrap">
                <a href="{{ url('version-histories') }}" class="btn-form btn-form-secondary text-nowrap" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    Lihat Halaman Awam
                </a>
                <a href="{{ route('version-histories.create') }}" class="btn-form btn-form-create text-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Versi
                </a>
            </div>
        </div>
        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table id="tbl-version-histories" data-path="{{ route('version-histories.index') }}" class="DT-index table table-hover align-middle w-100 mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width:100px;">Versi</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" style="width:140px;">Tarikh Lepas</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Nota Perubahan</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width:130px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
<script>
    $(function() {
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
            target.DataTable({
                ajax: path,
                columns: [
                    { data: 'version',     name: 'version', className: 'text-center' },
                    { data: 'released_at', name: 'released_at' },
                    { data: 'notes',       name: 'notes' },
                    { data: 'actions',     name: 'actions', orderable: false, searchable: false }
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
