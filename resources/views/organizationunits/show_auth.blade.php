@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">{{ $organizationunit->name }}</h3>
            <p class="text-muted small m-0">Direktori Agensi &mdash; Tender &amp; Sebut Harga</p>
        </div>
        <div class="d-flex flex-nowrap gap-2">
            @if (Auth::user()->hasRole('Admin'))
                <a href="{{ route('agencies.edit', $organizationunit->id) }}" class="btn btn-sm btn-warning rounded-8 px-3 text-nowrap d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"/>
                        <path d="M13.5 6.5l4 4"/>
                    </svg>
                    Kemaskini Agensi
                </a>
            @endif
            @if (Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
                <a href="{{ route('ciptaTender') }}" class="btn btn-sm btn-primary rounded-8 px-3 text-nowrap d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Tender / Sebut Harga
                </a>
            @endif
        </div>
    </div>

    @if (App\Tender::canShowUpdate($organizationunit->id))
        <!-- Stats cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="?state=1" class="text-decoration-none">
                    <div class="content-card h-100 {{ request('state') == 1 ? 'border-warning' : '' }}">
                        <div class="content-card-body p-4 text-center">
                            <div class="fw-bold mb-1" style="font-size:2rem; color:#f59e0b;">{{ $count_1 }}</div>
                            <div class="text-muted small">Tender / Sebut Harga Belum Disiarkan</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="?state=2" class="text-decoration-none">
                    <div class="content-card h-100 {{ request('state') == 2 ? 'border-warning' : '' }}">
                        <div class="content-card-body p-4 text-center">
                            <div class="fw-bold mb-1" style="font-size:2rem; color:#f59e0b;">{{ $count_2 }}</div>
                            <div class="text-muted small">Belum Diumumkan Carta Tender</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="?state=3" class="text-decoration-none">
                    <div class="content-card h-100 {{ request('state') == 3 ? 'border-warning' : '' }}">
                        <div class="content-card-body p-4 text-center">
                            <div class="fw-bold mb-1" style="font-size:2rem; color:#f59e0b;">{{ $count_3 }}</div>
                            <div class="text-muted small">Belum Diumumkan Penender Berjaya</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <h3 class="content-card-title">Senarai Tender &amp; Sebut Harga</h3>
            </div>

            <!-- Filter tabs -->
            <div class="d-flex gap-2">
                <a href="{{ route('agencies.show', $organizationunit->id) }}"
                    class="btn-form {{ !request('type') ? 'btn-form-primary' : 'btn-form-secondary' }}">Semua</a>
                <a href="{{ route('agencies.show', $organizationunit->id) }}?type=tenders"
                    class="btn-form {{ request('type') == 'tenders' ? 'btn-form-primary' : 'btn-form-secondary' }}">Tender</a>
                <a href="{{ route('agencies.show', $organizationunit->id) }}?type=quotations"
                    class="btn-form {{ request('type') == 'quotations' ? 'btn-form-primary' : 'btn-form-secondary' }}">Sebut Harga</a>
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table class="DT-show table table-hover align-middle mb-0 w-100" data-path="{{ $path }}">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4">No / Tajuk</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Kod Bidang</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3">Tarikh Jual</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3">Tarikh Tutup</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3">Harga Dokumen</th>
                            @if (App\Tender::canShowUpdate($organizationunit->id))
                                <th class="text-uppercase text-center text-muted small fw-bold py-3">Jadual</th>
                                <th class="text-uppercase text-center text-muted small fw-bold py-3">Status</th>
                                <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width:160px;">Tindakan</th>
                            @endif
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
    var canUpdate = {!! json_encode(App\Tender::canShowUpdate($organizationunit->id)) !!};

    $('.DT-show').each(function () {
        var target = $(this);
        var path   = target.data('path');

        var columns = [
            { data: 'name',                 name: 'name' },
            { data: 'codes',                name: 'codes' },
            { data: 'document_start_date',  name: 'document_start_date' },
            { data: 'submission_datetime',  name: 'submission_datetime' },
            { data: 'price',                name: 'price' },
        ];

        if (canUpdate) {
            columns.push({ data: 'report',  name: 'report' });
            columns.push({ data: 'status',  name: 'status' });
            columns.push({ data: 'actions', name: 'actions', orderable: false, searchable: false });
        }

        target.DataTable({
            ajax: path,
            columns: columns,
            serverSide: true,
            stateSave: false,
            language: {
                sEmptyTable:    "Tiada data",
                sInfo:          "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                sInfoEmpty:     "Paparan 0 hingga 0 dari 0 rekod",
                sInfoFiltered:  "(Ditapis dari jumlah _MAX_ rekod)",
                sInfoThousands: ",",
                sLengthMenu:    "Papar _MENU_ rekod",
                sLoadingRecords:"Diproses...",
                sProcessing:    "Sedang diproses...",
                sSearch:        "Carian:",
                sZeroRecords:   "Tiada padanan rekod yang dijumpai.",
                oPaginate: {
                    sFirst: "Pertama", sPrevious: "Sebelum", sNext: "Kemudian", sLast: "Akhir"
                }
            },
            aaSorting: [],
            pageLength: 25,
        });
    });
</script>
@endsection
