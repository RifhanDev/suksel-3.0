{{-- @extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master') --}}
@extends(!Auth::check() || Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')
    {{-- 
     PAGE-PAGE AGENCY NI TINGGAL SEKEJAP, SEBAB KENA CHECK BTUL2 DULU (CHALLENGING) 
     NK BEZAKAN PAGE UTK LANDING DGN USER YG DH LOGGED IN
    --}}

@section('styles')
    <style>
        /* =========================================
           MODERN PAGE HEADER
          ========================================= */
        .page-header-modern {
            position: relative;
            background: white;
            border-radius: var(--radius-lg);
            padding: 2.5rem 2.5rem;
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px -10px rgba(196, 30, 58, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header-modern::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image:
                radial-gradient(at 90% 10%, rgba(196, 30, 58, 0.08) 0px, transparent 50%),
                radial-gradient(at 10% 90%, rgba(255, 204, 0, 0.08) 0px, transparent 50%);
            z-index: 0;
        }

        /* Decorative Circle */
        .page-header-modern::after {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, var(--sg-red) 0%, transparent 80%);
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-pretitle {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sg-red);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-pretitle::before {
            content: '';
            display: block;
            width: 20px;
            height: 2px;
            background: var(--sg-yellow);
        }

        .header-title {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 2rem;
            color: #111827;
            margin: 0;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .header-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-top: 0.5rem;
            max-width: 600px;
        }

        /* Floating 3D Icon */
        .header-icon-box {
            position: relative;
            z-index: 2;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            color: var(--sg-red);
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }

        .page-header-modern:hover .header-icon-box {
            transform: rotate(0deg) scale(1.05);
        }

        @media (max-width: 768px) {
            .page-header-modern {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.5rem;
                gap: 1.5rem;
            }

            .header-icon-box {
                display: none;
            }

            /* Hide icon on mobile */
        }

        /* =========================================
           TENDER CARD & TABLE
          ========================================= */
        .tender-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: visible;
        }

        .tender-toolbar {
            padding: 1.25rem 2rem;
            border-bottom: 1px solid #f3f4f6;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Header Title Group inside Toolbar */
        .header-title-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 4px solid var(--sg-red);
            padding-left: 1rem;
            min-height: 42px;
        }

        .toolbar-title {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .toolbar-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 2px 0 0 0;
            font-weight: 400;
            line-height: 1.2;
        }

        /* Toolbar Actions */
        .toolbar-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Table */
        .table-modern thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .table-modern tbody td {
            padding: 0.9rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }

        .table-modern tbody tr:hover {
            background-color: #fef2f2;
        }

        .table-modern th svg,
        .table-modern td svg {
            vertical-align: text-bottom;
            margin-right: 0.35rem;
        }
    </style>
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <!-- MAIN CONTENT -->
        <div class="col-12">

            <div class="page-header-modern">
                <div class="header-content">
                    <div class="header-pretitle">Sistem Tender Online</div>
                    <h2 class="header-title">
                        Senarai Agensi
                        @if (isset($type))
                            : {{ $type->name }}
                        @endif
                        @if (isset($parent))
                            : {{ $parent->name }}
                        @endif
                    </h2>
                    <p class="header-subtitle">
                        Senarai badan berkanun, PBT, anak syarikat dan agensi kerajaan dibawah Kerajaan Negeri Selangor.
                    </p>
                </div>

                <div class="header-icon-box d-none d-md-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21l18 0" />
                        <path d="M9 8l1 0" />
                        <path d="M9 12l1 0" />
                        <path d="M9 16l1 0" />
                        <path d="M14 8l1 0" />
                        <path d="M14 12l1 0" />
                        <path d="M14 16l1 0" />
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                    </svg>
                </div>
            </div>

            <div class="tender-card">

                <div class="tender-toolbar">
                    <div class="header-title-group">
                        <h3 class="toolbar-title">Direktori Agensi</h3>
                    </div>

                    <div class="toolbar-actions">
                        <div class="dropdown d-inline-block">
                            <button type="button"
                                class="btn-auth btn-auth-outline dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 6l16 0" /><path d="M4 12l10 0" /><path d="M4 18l6 0" />
                                </svg>
                                Pilihan Kategori
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item" href="{{ route('agencies.index') }}">Semua Kategori</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @foreach (App\OrganizationType::all() as $ou_type)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('agencies.index', ['type' => $ou_type->id]) }}">
                                            {{ $ou_type->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if (App\OrganizationUnit::canCreate())
                            <a href="{{ asset('agencies/create') }}" class="btn-auth btn-auth-solid">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                                </svg>
                                Masukkan Agensi Baru
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table
                            data-path="/agencies<?php if(isset($type)) : ?>?type=<?php echo $type->id; ?><?php endif; ?><?php if(isset($parent)) : ?>?parent=<?php echo $parent->id; ?><?php endif; ?>"
                            class="DT-index table table-modern table-mobile-md w-100 mb-0">
                            <thead>
                                <tr>
                                    <th class="w-25">
                                        Nama
                                    </th>
                                    <th>
                                        Alamat
                                    </th>
                                    <th class="w-15">
                                        No. Telefon
                                    </th>
                                    @if (!isset($type))
                                        <th class="w-20">
                                            Kategori
                                        </th>
                                    @endif
                                    <th class="w-20 text-center">
                                        Tindakan
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');

            if (path.includes('/agencies?type')) {
                var columns = [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'tel',
                        name: 'tel'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ];
            } else if (path.includes('/agencies?parent')) {
                var columns = [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'tel',
                        name: 'tel'
                    },
                    {
                        data: 'type_id',
                        name: 'type_id'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ];
            } else {

                var columns = [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'tel',
                        name: 'tel'
                    },
                    {
                        data: 'type_id',
                        name: 'type_id'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ];
            }

            var DT = target.DataTable({
                ajax: path,
                columns: columns,
                serverSide: true,
                // stateSave: true,
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
                        sFirst: "<<",
                        sPrevious: "<",
                        sNext: ">",
                        sLast: ">>"
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
