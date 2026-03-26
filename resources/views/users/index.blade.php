@extends('layouts.v3.master')

@section('content')
    <?php $user = Auth::user(); ?>
    <link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <!-- Title -->
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Pengguna</h3>
            <p class="text-muted small m-0">Pengurusan dan tetapan akaun pengguna sistem.</p>
        </div>
    </div>

    @if ($user->ability(['Admin', 'Agency Admin'], ['User:approve']))
        <div class="row g-4 mb-4">
            <div class="col-sm-4">
                <div class="stats-card h-100">
                    <div class="stats-card-header">
                        <h6 class="stats-card-title text-nowrap">Pendaftaran Belum Diluluskan</h6>
                        <div class="stats-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="stats-card-body">
                        <h2 class="stats-card-value">{{ number_format(App\User::pendingApprovalCount(), 0) }}</h2>
                    </div>
                    <div class="stats-card-footer">
                        <a href="{{ asset('users/pending-approval') }}" class="stats-card-link">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="stats-card h-100">
                    <div class="stats-card-header">
                        <h6 class="stats-card-title text-nowrap">Pengguna Aktif</h6>
                        <div class="stats-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <polyline points="17 11 19 13 23 9" />
                            </svg>
                        </div>
                    </div>
                    <div class="stats-card-body">
                        <h2 class="stats-card-value">{{ number_format(App\User::activeCount(), 0) }}</h2>
                    </div>
                    <div class="stats-card-footer">
                        <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Akaun Aktif
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="stats-card h-100">
                    <div class="stats-card-header">
                        <h6 class="stats-card-title text-nowrap">Pengguna Tidak Aktif</h6>
                        <div class="stats-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <line x1="18" y1="8" x2="23" y2="13" />
                                <line x1="23" y1="8" x2="18" y2="13" />
                            </svg>
                        </div>
                    </div>
                    <div class="stats-card-body">
                        <h2 class="stats-card-value">{{ number_format(App\User::inactiveCount(), 0) }}</h2>
                    </div>
                    <div class="stats-card-footer">
                        <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            Akaun Tidak Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('users._review_snaps')

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
                <h3 class="content-card-title">Senarai Pengguna</h3>
            </div>
            <a href="{{ asset('users/create') }}" class="btn-form btn-form-create">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Masukan Pengguna Baru
            </a>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table data-path="{{ route('users.index') }}" class="DT-users table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="w-15 text-uppercase text-muted small fw-bold py-3 ps-4">Nama</th>
                            <th class="w-20 text-uppercase text-muted small fw-bold py-3">Alamat Emel</th>
                            <th class="w-15 text-uppercase text-muted small fw-bold py-3">Peranan</th>
                            <th class="w-25 text-uppercase text-muted small fw-bold py-3">Agensi / Syarikat</th>
                            <th class="w-5 text-uppercase text-muted small fw-bold py-3">Status</th>
                            <th class="w-5 text-uppercase text-muted small fw-bold py-3">Reviewed</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4"
                                style="width: 300px; min-width: 300px;">Tindakan</th>
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
        $('.DT-users').each(function() {
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
                        data: 'roles_column',
                        name: 'roles_column'
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
                        data: 'arr',
                        name: 'arr'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
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
