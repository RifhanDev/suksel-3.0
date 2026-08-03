@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender</h3>
            <p class="text-muted small m-0">Paparan senarai tender untuk proses penilaian teknikal.</p>
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

                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
                    <input type="text" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk perolehan...">
                </div>

                <div class="col-12 col-lg-2">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <select id="filter_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="Belum Dinilai">Belum Dinilai</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Hantar</label>
                    <input type="text" id="filter_tarikh" class="form-control form-control-lg datepicker" placeholder="dd/mm/yyyy">
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
                <h3 class="content-card-title" style="font-size: 1rem;">Senarai Tender</h3>
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table id="tbl-teknikal" class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="60px">No.</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="200px">No. Tender / Sebut Harga</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Tajuk Perolehan</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="130px">Tarikh Hantar</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="150px">Tarikh Dicipta</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" width="140px">Status</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="120px">Tindakan</th>
                        </tr>
                    </thead>
                    {{-- DataTables serverSide --}}
                    <tbody></tbody>

                    {{-- <tbody>
                        @forelse($tenders ?? [] as $item)
                        <tr class="teknikal-row-link" data-href="{{ $item['show_url'] }}">
                            <td><a href="{{ $item['show_url'] }}" class="text-decoration-none"><span class="tender-number">{{ $item['no_tender'] }}</span></a></td>
                            <td><span class="fw-medium">{{ $item['tajuk'] }}</span></td>
                            <td><span class="text-muted small">{{ $item['tarikh'] }}</span></td>
                            <td><span class="text-muted small">{{ $item['status_label'] }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tiada tender pada peringkat ini.</td>
                        </tr>
                        @endforelse
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            const params = new URLSearchParams(window.location.search);
            if (params.get('toast') && params.get('message')) {
                showToast(params.get('toast'), params.get('message'));
                params.delete('toast');
                params.delete('message');
                const cleanQuery = params.toString();
                window.history.replaceState(null, '', window.location.pathname + (cleanQuery ? '?' + cleanQuery : ''));
            }

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            var DT = $('#tbl-teknikal').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('penilaianTeknikal') }}",
                    data: function (d) {
                        d.filter_no_tender = $('#filter_no_tender').val();
                        d.filter_tajuk = $('#filter_tajuk').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tarikh = $('#filter_tarikh').val();
                    }
                },
                columns: [
                    { data: null, orderable: false, searchable: false, className: 'ps-4', defaultContent: '' },
                    { data: 'no_tender', name: 'no_tender', className: 'fw-semibold' },
                    { data: 'name', name: 'name' },
                    { data: 'submission_datetime', name: 'submission_datetime' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status_label', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'tindakan', orderable: false, searchable: false, className: 'text-center pe-4' }
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

            // Running number in the No. column — offset by the current page's starting row
            DT.on('draw.dt', function () {
                var start = DT.page.info().start;
                DT.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = start + i + 1;
                });
            });

            // Apply Filter — values are read straight from the inputs by ajax.data above,
            // so applying a filter just means asking the server for a fresh page of results
            $('#btn_apply_filter').on('click', function () {
                DT.ajax.reload();
            });

            // Reset Filter
            $('#btn_reset_filter').on('click', function () {
                $('#filter_no_tender, #filter_tajuk, #filter_tarikh').val('');
                $('#filter_status').val('');
                DT.ajax.reload();
            });
        });
    </script>
@endsection
