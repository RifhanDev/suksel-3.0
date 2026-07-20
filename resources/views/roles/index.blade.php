@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Peranan</h3>
            <p class="text-muted small m-0">Pengurusan peranan dan kebenaran pengguna sistem.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Maklumat Peranan</h3>
            </div>

            @if (App\Role::canCreate())
                <a href="{{ route('roles.create') }}" class="btn-form btn-form-create">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Peranan Baru
                </a>
            @endif
        </div>

        <div class="content-card-body p-3">
            <div class="table-responsive">
                <table data-path="/roles" class="DT-index table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Nama Peranan</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Kebenaran Ditetapkan</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" style="width: 130px;">Jumlah Pengguna</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4"
                                style="width: 180px; min-width: 180px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');

            var DT = target.DataTable({
                order: [
                    [0, 'asc']
                ],
                ajax: path,
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_count',
                        name: 'user_count',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                serverSide: true,
                stateSave: true,
                pageLength: 25,
                language: {
                    sEmptyTable: "Tiada data",
                    sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                    sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
                    sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
                    sInfoPostFix: "",
                    sInfoThousands: ",",
                    sLengthMenu: "Papar _MENU_ rekod",
                    sLoadingRecords: "Diproses...",
                    sProcessing: "Sedang diproses...",
                    sSearch: "Carian:",
                    sZeroRecords: "Tiada padanan rekod yang dijumpai.",
                    oPaginate: {
                        sFirst: "Pertama",
                        sPrevious: "Sebelum",
                        sNext: "Kemudian",
                        sLast: "Akhir"
                    },
                    oAria: {
                        sSortAscending: ": diaktifkan kepada susunan lajur menaik",
                        sSortDescending: ": diaktifkan kepada susunan lajur menurun"
                    }
                },
                aaSorting: []
            });
        });
    </script>
@endsection