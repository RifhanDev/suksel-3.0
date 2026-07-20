@extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
    <style>
        .page-title-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .title-pipe {
            font-size: 1.5rem;
            color: #cbd5e1;
            font-weight: 300;
            margin: 0 15px;
        }

        .vendor-highlight-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--sg-red);
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(196, 30, 58, 0.1);
        }

        .action-tile-compact {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            /* Smoother physics */
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        .action-tile-compact:hover {
            transform: translateY(-5px);
            /* Clear lift */
            border-color: var(--sg-red);
            /* Elegant full red border */
            box-shadow: 0 12px 24px -6px rgba(196, 30, 58, 0.15);
            /* Soft red shadow */
        }

        .tile-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: #fff1f2;
            color: var(--sg-red);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        /* Icon Hover */
        .action-tile-compact:hover .tile-icon {
            background: var(--sg-red);
            color: white;
            box-shadow: 0 4px 6px rgba(196, 30, 58, 0.2);
        }

        .tile-content h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            transition: color 0.3s;
        }

        .tile-content small {
            margin: 0;
            font-size: 0.75rem;
            color: #64748b;
            display: block;
            line-height: 1.2;
        }

        /* Text turns red on hover */
        .action-tile-compact:hover .tile-content h6 {
            color: var(--sg-red);
        }

        /* Arrow */
        .tile-arrow {
            margin-left: auto;
            color: #cbd5e1;
            transition: transform 0.3s;
        }

        .action-tile-compact:hover .tile-arrow {
            color: var(--sg-red);
            transform: translateX(4px);
        }

        .divider-red {
            height: 1.5px;
            background: linear-gradient(90deg, transparent 0%, var(--sg-red) 20%, var(--sg-red) 80%, transparent 100%);
            opacity: 1;
        }

        .link-slide-underline {
            position: relative;
            text-decoration: none !important;
            transition: color 0.3s ease-in-out;
            padding-bottom: 3px;
        }

        .link-slide-underline:hover {
            color: var(--sg-red) !important;
        }

        .link-slide-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--sg-red);
            transition: width 0.3s ease-in-out;
        }

        .link-slide-underline:hover::after {
            width: 97%;
        }
    </style>
@endsection

@section('content')

    <!-- PAGE HEADER -->
    <div class="mb-4 d-flex align-items-center flex-wrap">
        <h2 class="page-title-text m-0">
            Permintaan Kemaskini
        </h2>

        @if (isset($vendor))
            <span class="title-pipe">|</span>

            <span class="vendor-highlight-text">
                {{ $vendor->name }}
            </span>
        @endif
    </div>

    @if (!isset($vendor))
        <div class="mb-5">
            @include('vendors._snaps')
        </div>
    @endif

    <!-- ACTIONS TOOLBAR -->
    @if (isset($vendor) && App\CodeRequest::canCreate())
        <div class="mb-4">
            {{-- <div class="d-flex align-items-center mb-3">
                <span class="text-uppercase fw-bold text-dark small" style="letter-spacing: 1px; font-size: 0.7rem;">Pilihan Tindakan Baru</span>
                <div class="flex-grow-1 ms-3 divider-red"></div>
            </div> --}}

            <div class="row g-3">

                <!-- MOF Tile -->
                @if (App\CodeRequest::canCreateFor($vendor->id, 'mof'))
                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'mof']) }}"
                            class="action-tile-compact">
                            <div class="tile-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <div class="tile-content">
                                <h6>Kemaskini MOF</h6>
                                <small>Kod Bidang Kewangan</small>
                            </div>
                            <div class="tile-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- CIDB Tile -->
                @if (App\CodeRequest::canCreateFor($vendor->id, 'cidb'))
                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'cidb']) }}"
                            class="action-tile-compact">
                            <div class="tile-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                                    </path>
                                </svg>
                            </div>
                            <div class="tile-content">
                                <h6>Kemaskini CIDB</h6>
                                <small>Gred & Pengkhususan</small>
                            </div>
                            <div class="tile-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Address Tile -->
                @if (App\CodeRequest::canCreateFor($vendor->id, 'district'))
                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'district']) }}"
                            class="action-tile-compact">
                            <div class="tile-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="tile-content">
                                <h6>Kemaskini Alamat</h6>
                                <small>SSM & Operasi</small>
                            </div>
                            <div class="tile-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Email Tile -->
                @if (App\CodeRequest::canCreateFor($vendor->id, 'email'))
                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'email']) }}"
                            class="action-tile-compact">
                            <div class="tile-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div class="tile-content">
                                <h6>Kemaskini Emel</h6>
                                <small>Akaun Login Utama</small>
                            </div>
                            <div class="tile-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    @endif

    <!-- MAIN TABLE CARD -->
    <div class="content-card p-0 mb-3">

        <div class="content-card-header p-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </div>
                <h3 class="m-0 fw-bold text-uppercase" style="font-size: 1rem; color: #64748b;">
                    Senarai Permintaan Kemaskini
                </h3>
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table
                    data-path="{{ $ajax_url }}@if (Request::get('state')) ?state={{ Request::get('state') }} @endif"
                    class="DT-index table table-hover table-vcenter table-mobile-md w-100 mb-0">
                    <thead>
                        <tr>
                            @if (!isset($vendor))
                                <th class="w-15 text-uppercase text-muted small fw-bold py-3 ps-4">
                                    <i class="me-1"></i>Syarikat
                                </th>
                            @endif
                            <th class="w-15 text-uppercase text-muted small fw-bold py-3">
                                <i class="me-1"></i>Kemaskini
                            </th>
                            <th class="w-15 text-uppercase text-muted small fw-bold py-3">
                                <i class="me-1"></i>Tarikh Permintaan
                            </th>
                            <th class="w-15 text-uppercase text-muted small fw-bold py-3">
                                <i class="me-1"></i>Tarikh SSM
                            </th>
                            <th class="w-20 text-uppercase text-muted small fw-bold py-3">
                                <i class="me-1"></i>Perkara
                            </th>
                            <th class="w-10 text-uppercase text-muted small fw-bold py-3">
                                <i class="me-1"></i>Status
                            </th>
                            <th class="w-10 text-uppercase text-muted small fw-bold py-3 pe-4">
                                <i class="me-1"></i>Tindakan
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @if (isset($vendor) && $vendor->canShow())
        <div class="d-flex justify-content-start pb-5">
            <a href="{{ asset(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors/' . $vendor->id) }}"
                class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 link-slide-underline">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold">Kembali ke Maklumat Syarikat</span>
            </a>
        </div>
    @endif

@endsection

@section('scripts')
    <script type="text/javascript">
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');

            if (path.includes('/vendor')) {
                var columns = [{
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'ssm_expiry',
                        name: 'ssm_expiry'
                    },
                    {
                        data: 'approval_1_id',
                        name: 'approval_1_id'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ];
            } else {
                var columns = [{
                        data: 'name',
                        name: 'vendors.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'ssm_expiry',
                        name: 'ssm_expiry'
                    },
                    {
                        data: 'approval_1_id',
                        name: 'approval_1_id'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ];
            }

            var DT = target.DataTable({
                ajax: path,
                columns: columns,
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
