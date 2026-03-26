@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('tenders._menu')

            {{-- Notifications & Alerts --}}
            @include('tenders._notification')

            {{-- Header & Tabs Card --}}
            <div class="tender-header-card d-print-none mb-4">
                {{-- Page Header --}}
                <div class="tender-page-header">
                    <div class="tender-ref-label">
                        <span class="tender-type-label">{{ App\Tender::$types[$tender->type] }}</span>
                        <span class="tender-ref-sep">·</span>
                        <span class="tender-ref-no">{{ $tender->ref_number }}</span>
                    </div>
                    <h2 class="tender-title-main">{{ $tender->name }}</h2>
                </div>

                @if (Auth::user() && $tender->canShowTabs())
                    <div class="tender-top-tabs mt-4">
                        <ul class="nav nav-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="{{ asset('tender/' . $tender->id) }}" class="nav-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </g>
                                    </svg>
                                    Maklumat Tender
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="nav-link active">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                    </svg>
                                    Maklumat Syarikat
                                </a>
                            </li>
                            @if (Auth::check() &&
                                    $tender->canException() &&
                                    auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
                                <li class="nav-item">
                                    <a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}" class="nav-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20"
                                            viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2">
                                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
                                                <path d="M11 12h1v4h1" />
                                            </g>
                                        </svg>
                                        Maklumat Kebenaran Khas
                                        <span class="badge bg-danger ms-1">{{ $tender->exceptions()->where('status', 0)->count() }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Admin Sub-Navigation --}}
            @if (Auth::user()->hasRole('Admin'))
                <div class="tender-tab-card mb-4">
                    <div class="card-body" style="padding: 0.75rem 1rem;">
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ asset('tenders/' . $tender->id . '/eligibles') }}"
                                class="tender-menu-btn @if (!isset($purchases)) tender-menu-btn-primary @else tender-menu-btn-ghost @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Senarai Layak
                            </a>
                            <a href="{{ asset('tenders/' . $tender->id . '/vendors') }}"
                                class="tender-menu-btn @if (isset($purchases)) tender-menu-btn-primary @else tender-menu-btn-ghost @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1" />
                                    <circle cx="20" cy="21" r="1" />
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                </svg>
                                Pembelian Dokumen
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Senarai Layak Table --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <h3 class="content-card-title">Senarai Layak</h3>
                    </div>
                </div>
                <div class="content-card-body p-2">
                    <div class="table-responsive">
                        <table data-path="{{ route('tenders.eligibles', $tender->id) }}"
                            class="DT-index table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width: 5%;">#</th>
                                    <th class="text-uppercase text-muted small fw-bold py-3">No. Pendaftaran</th>
                                    <th class="text-uppercase text-muted small fw-bold py-3">Nama Syarikat</th>
                                    <th class="text-uppercase text-muted small fw-bold py-3">Alamat Emel</th>
                                    <th class="text-uppercase text-muted small fw-bold py-3">Tarikh Janaan</th>
                                    <th class="text-uppercase text-muted small fw-bold py-3 pe-4">Tarikh Email</th>
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
    <script type="text/javascript">
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
            var DT = target.DataTable({
                ajax: path,
                columns: [{
                        data: null
                    },
                    {
                        data: 'vendor_registration',
                        name: 'vendor.registration'
                    },
                    {
                        data: 'vendor_name',
                        name: 'vendor.name'
                    },
                    {
                        data: 'user_email',
                        name: 'users.email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'sent_at',
                        name: 'sent_at'
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
                aaSorting: [],
                "order": [
                    [1, 'asc']
                ],
                fnDrawCallback: function(oSettings) {
                    start = oSettings.oAjaxData.start + 1;
                    DT.column(0).nodes().to$().each(function(index) {
                        $(this).text(start + index);
                    });
                }
            });
        });
    </script>
@endsection
