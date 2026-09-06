@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Projek Untuk Lantikan Terus</h3>
            <p class="text-muted small m-0">Paparan senarai projek yang layak untuk proses pelantikan/pembelian terus.</p>
        </div>
    </div>

    <!-- FILTER -->
    <div class="card border shadow-sm mb-3 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <input type="text" id="filter_no_tender" class="form-control form-control-sm" placeholder="Cth: QT210000000023741">
                </div>

                <div class="col-12 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
                    <input type="text" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk perolehan...">
                </div>

                <div class="col-6 col-lg-3">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh</label>
                    <input type="text" id="filter_tarikh" class="form-control form-control-sm datepicker" placeholder="dd/mm/yyyy">
                </div>

                <div class="col-6 col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn_reset_filter" class="btn btn-md btn-light border w-100">Reset</button>
                        <button type="button" id="btn_apply_filter" class="btn btn-md btn-selangor fw-medium w-100">Tapis</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="content-card p-0">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size: 1rem;">Senarai Projek</h3>
            </div>
            <a href="{{ route('lantikan.create') }}" class="btn-form btn-form-create">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Cipta Projek
            </a>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table id="tbl-projek" class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="60px">No.</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="200px">Tender / Sebut Harga</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Tajuk Perolehan</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="140px">Tarikh</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" width="140px">Status</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="150px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td class="ps-4"></td>
                                <td class="fw-semibold">{{ $project->no_tender }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->tarikh }}</td>
                                <td class="text-center">
                                    @if ($project->status === 'draft')
                                        <span class="badge-status badge-status-warning">Draf</span>
                                    @else
                                        <span class="badge-status badge-status-success">Telah Dihantar</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    @if ($project->status === 'draft')
                                        <a href="{{ route('lantikan.edit', $project->id) }}"
                                            class="btn btn-sm btn-warning rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Kemaskini
                                        </a>
                                    @else
                                        <span class="text-muted small fst-italic d-inline-flex align-items-center gap-1"
                                            title="Projek telah dihantar dan tidak boleh dikemaskini">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                            Tiada tindakan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            var DT = $('#tbl-projek').DataTable({
                searching: true,
                columnDefs: [
                    { targets: 0, orderable: false, searchable: false },
                    { targets: -1, orderable: false, searchable: false }
                ],
                language: {
                    sEmptyTable:     "Tiada data",
                    sInfo:           "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                    sInfoEmpty:      "Paparan 0 hingga 0 dari 0 rekod",
                    sInfoFiltered:   "(Ditapis dari jumlah _MAX_ rekod)",
                    sLengthMenu:     "Papar _MENU_ rekod",
                    sLoadingRecords: "Diproses...",
                    sProcessing:     "Sedang diproses...",
                    sSearch:         "Carian:",
                    sZeroRecords:    "Tiada padanan rekod yang dijumpai.",
                    oPaginate: {
                        sFirst:    "Pertama",
                        sPrevious: "Sebelum",
                        sNext:     "Kemudian",
                        sLast:     "Akhir"
                    }
                },
                pageLength: 25,
                responsive: true,
                order: []
            });

            // Running number in the No. column — recalculated on every draw
            DT.on('draw.dt', function () {
                DT.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw(false);

            // Apply Filter
            $('#btn_apply_filter').on('click', function () {
                DT.column(1).search($('#filter_no_tender').val());
                DT.column(2).search($('#filter_tajuk').val());
                DT.column(3).search($('#filter_tarikh').val());
                DT.draw();
            });

            // Reset Filter
            $('#btn_reset_filter').on('click', function () {
                $('#filter_no_tender').val('');
                $('#filter_tajuk').val('');
                $('#filter_tarikh').val('');
                DT.columns().search('');
                DT.draw();
            });

        });
    </script>
@endsection
