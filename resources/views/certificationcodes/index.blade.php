@extends('layouts.v3.master')
@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">
                Senarai Kod Bidang
                @if (App\Code::typeExists(Request::get('type')))
                    : {{ App\Code::$type[Request::get('type')] }}
                @endif
            </h3>
            <p class="text-muted small m-0">Pengurusan dan tetapan senarai kod bidang.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                </div>
                <h3 class="content-card-title">Senarai Kod Bidang</h3>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <a href="#" class="btn-form btn-form-secondary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Kategori Kod Bidang
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach (App\Code::$type as $type => $name)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('codes.index', ['type' => $type]) }}">{{ $name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if (App\Code::canCreate())
                    <a href="{{ asset('codes/create') }}" class="btn-form btn-form-create">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Kod Baru
                    </a>
                @endif
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table data-path="/codes{{ Request::get('type') ? '?type=' . Request::get('type') : '' }}"
                    class="DT-index table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Kod</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Nama</th>
                            @if (!Request::get('type'))
                                <th class="text-uppercase text-muted small fw-bold py-3">Agensi / Jenis</th>
                            @endif
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4"
                                style="width: 200px; min-width: 200px;">Tindakan</th>
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
        var url = window.location.href;

        if (url.includes('?type')) {
            var columns = [{
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
            ];
        } else {
            var columns = [{
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
            ];
        }

        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
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
