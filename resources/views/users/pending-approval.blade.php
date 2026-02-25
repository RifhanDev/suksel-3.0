@extends('layouts.v3.master')

@section('content')
    <link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <!-- Title -->
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Pengguna Belum Diluluskan</h3>
            <p class="text-muted small m-0">Pengurusan permohonan akaun pengguna sistem.</p>
        </div>
    </div>

    <div class="stats-card p-0">
        <div class="stats-card-header p-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="stats-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3 class="m-0 fw-bold text-uppercase" style="font-size: 1rem; color: #64748b;">Permohonan Pengguna</h3>
            </div>
            <a href="{{ asset('users/create') }}" class="btn btn-form-create">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Masukan Pengguna Baru
            </a>
        </div>

        <div class="stats-card-body p-2">
            <div class="table-responsive">
                <table data-path="{{ route('users.pending-approval') }}"
                    class="DT-index table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="w-20 text-uppercase text-muted small fw-bold py-3 ps-4">Nama</th>
                            <th class="w-20 text-uppercase text-muted small fw-bold py-3">Alamat Emel</th>
                            <th class="w-25 text-uppercase text-muted small fw-bold py-3">Agensi / Syarikat</th>
                            <th class="w-15 text-uppercase text-muted small fw-bold py-3">Status</th>
                            <th class="w-20 text-uppercase text-muted small fw-bold py-3 pe-4">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');

            var DT = target.DataTable({
                ajax: path,
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'organization_unit_id',
                        name: 'organization_unit_id'
                    },
                    {
                        data: 'confirmed',
                        name: 'confirmed'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
                serverSide: true,
                stateSave: true,
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
