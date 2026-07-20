@extends('layouts.v3.master')

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Direktori Agensi</h3>
            <p class="text-muted small m-0">
                {{ isset($type) ? $type->name : 'Semua Agensi' }}
            </p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21l18 0"/><path d="M9 8l1 0"/><path d="M9 12l1 0"/>
                        <path d="M9 16l1 0"/><path d="M14 8l1 0"/><path d="M14 12l1 0"/>
                        <path d="M14 16l1 0"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                    </svg>
                </div>
                <h3 class="content-card-title">Senarai Agensi</h3>
            </div>

            @if (App\OrganizationUnit::canCreate())
                <a href="{{ route('agencies.create') }}" class="btn-form btn-form-create">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Masukkan Agensi Baru
                </a>
            @endif
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table data-path="{{ url()->current() }}"
                    class="DT-index table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width:30%;">Nama Agensi</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Alamat</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" style="width:120px;">Tel</th>
                            @if (!isset($type))
                                <th class="text-uppercase text-muted small fw-bold py-3" style="width:150px;">Jenis</th>
                            @endif
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width:150px; min-width:150px;">Tindakan</th>
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
    var hasType = {{ isset($type) ? 'true' : 'false' }};

    $('.DT-index').each(function () {
        var target = $(this);
        var path   = target.data('path');

        var columns = [
            { data: 'name',    name: 'name' },
            { data: 'address', name: 'address' },
            { data: 'tel',     name: 'tel' },
        ];

        if (!hasType) {
            columns.push({ data: 'type_id', name: 'type_id' });
        }

        columns.push({ data: 'actions', name: 'actions', orderable: false, searchable: false });

        target.DataTable({
            ajax: path,
            columns: columns,
            serverSide: true,
            stateSave: true,
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
