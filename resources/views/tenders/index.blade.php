@extends('layouts.v3.master')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('styles')
    <style>
        .page-title-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .table-modern thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tbody tr:hover {
            background-color: #fff9f9;
        }

        .badge-date {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }

        /* ── Modal Pilih Peringkat ── */
        .peringkat-option {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            background: #fff;
            transition: border-color 0.15s, background 0.15s;
        }

        .peringkat-option:hover {
            border-color: #fca5a5;
            background: #fff5f5;
        }

        .peringkat-option.selected {
            border-color: #c41e3a;
            background: #fef2f2;
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.08);
        }

        .peringkat-option-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            flex-shrink: 0;
            transition: background 0.15s, color 0.15s;
        }

        .peringkat-option.selected .peringkat-option-icon {
            background: #fee2e2;
            color: #c41e3a;
        }

        .peringkat-option-check {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: transparent;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }

        .peringkat-option.selected .peringkat-option-check {
            background: #c41e3a;
            border-color: #c41e3a;
            color: #fff;
        }
    </style>
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <!-- Title -->
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender / Sebutharga</h3>
            <p class="text-muted small m-0">Paparan maklumat tender terkini di bawah Sistem e-Perolehan Selangor.</p>
        </div>
    </div>

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">

            <div class="row g-2 align-items-end">

                <div class="col-12 col-lg-2">
					<label for="filter_no_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <input type="text" id="filter_no_tender" class="form-control form-control-sm" placeholder="Cth: JPS/01">
                </div>

                <div class="col-12 col-lg-4">
					<label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
					<input type="text" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk projek...">
                </div>

                <div class="col-6 col-lg-2">
					<label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <select id="filter_status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="belum_disiarkan">Belum Disiarkan</option>
                    </select>
                </div>

                <div class="col-6 col-lg-2">
					<label for="filter_tarikh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh</label>
					<input type="text" id="filter_tarikh" class="form-control form-control-lg datepicker" placeholder="dd/mm/yyyy">
                </div>

                <div class="col-12 col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn_reset_filter" class="btn btn-md btn-light border w-100">
                            Reset
                        </button>
                        <button type="button" id="btn_apply_filter" class="btn btn-md btn-selangor fw-medium w-100">
                            Tapis
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="content-card mb-4">
        <div class="content-card-header">
            <h3 class="content-card-title d-flex align-items-center gap-2 mb-0">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                Maklumat Tender
            </h3>

            {{-- @if (App\Tender::canCreate())
            <a href="{{ asset('tenders/create') }}" class="btn btn-selangor d-flex align-items-center gap-2 shadow-sm">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Tender / Sebutharga
            </a>
            @endif --}}
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table data-path="/tender" class="DT-index table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th>Maklumat Tender</th>
                            <th width="150px">Tarikh Jual</th>
                            <th width="150px">Tarikh Tutup</th>
                            <th width="150px">Harga (RM)</th>
                            @if (Auth::check() && !Auth::user()->hasRole('Vendor'))
                                <th width="150px">Status</th>
                                <th width="150px">Tindakan</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    <div class="modal fade" id="modalPilihPeringkat" tabindex="-1" aria-labelledby="modalPilihPeringkatLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalPilihPeringkatLabel" style="font-size:1.05rem;">
                            Pilih Kaedah Penilaian
                        </h5>
                        <p class="text-muted mb-0" style="font-size:0.8rem;">
                            Pilih kaedah penilaian sebelum melantik jawatankuasa.
                        </p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="d-flex flex-column gap-3" id="peringkatOptionGroup">

                        <label class="peringkat-option selected" data-value="1">
                            <input type="radio" name="modal_kaedah" value="1" class="d-none" checked>
                            <div class="peringkat-option-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2z"/><path d="M17 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2"/><path d="M14 14V6l-2 2"/></g></svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size:0.88rem;">1 Peringkat</div>
                                <div class="text-muted" style="font-size:0.75rem;">Penilaian teknikal &amp; kewangan serentak</div>
                            </div>
                            <div class="peringkat-option-check">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        </label>

                        <label class="peringkat-option" data-value="2">
                            <input type="radio" name="modal_kaedah" value="2" class="d-none">
                            <div class="peringkat-option-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2z"/><path d="M17 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2"/><path d="M12 8a2 2 0 1 1 4 0c0 .591-.417 1.318-.816 1.858L12 14.001h4"/></g></svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size:0.88rem;">2 Peringkat</div>
                                <div class="text-muted" style="font-size:0.75rem;">Penilaian teknikal dahulu, kemudian kewangan</div>
                            </div>
                            <div class="peringkat-option-check">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        </label>

                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btnTeruskanLantik">
                        Teruskan
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>
@endpush

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            // ── DataTable ──────────────────────────────────────────────────
            $('.DT-index').each(function () {
                var target = $(this);
                var path = target.data('path');

                var columns = [
                    { data: 'name', name: 'name' },
                    { data: 'document_start_date', name: 'document_start_date' },
                    { data: 'submission_datetime', name: 'submission_datetime' },
                    { data: 'price', name: 'price' }
                ];

                @if (Auth::check() && !Auth::user()->hasRole('Vendor'))
                    columns.push({ data: 'approver_id', name: 'approver_id' });
                    columns.push({
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) { return data || ''; }
                    });
                @endif

                var DT = target.DataTable({
                    ajax: path,
                    columns: columns,
                    serverSide: true,
                    stateSave: true,
                    columnDefs: [
                        { className: 'text-start', targets: [3] }
                    ],
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
                    pageLength: 25,
                    responsive: true,
                    order: [[1, 'desc']]
                });

                window.tendersDT = DT;
            });

            // ── Datepicker ─────────────────────────────────────────────────
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true
            });

            // ── Filter ─────────────────────────────────────────────────────
            $('#btn_apply_filter').on('click', function () {
                var noTender = $('#filter_no_tender').val();
                var tajuk    = $('#filter_tajuk').val();
                var status   = $('#filter_status').val();
                var tarikh   = $('#filter_tarikh').val();

                if (window.tendersDT) {
                    window.tendersDT.ajax.url(
                        '/tender?no_tender=' + noTender +
                        '&tajuk=' + tajuk +
                        '&status=' + status +
                        '&tarikh=' + tarikh
                    ).load();
                }
            });

            $('#btn_reset_filter').on('click', function () {
                $('#filter_no_tender, #filter_tajuk, #filter_tarikh').val('');
                $('#filter_status').val('');
                if (window.tendersDT) {
                    window.tendersDT.ajax.url('/tender').load();
                }
            });

            // ── Modal Pilih Peringkat ──────────────────────────────────────
            var _lantikUrl   = '';
            var _tenderUuid  = '';

            // Capture trigger data when the Lantik modal opens
            $(document).on('click', '.btn-pilih-peringkat', function () {
                _lantikUrl  = $(this).data('lantik-url');
                _tenderUuid = $(this).data('tender-uuid');
                $('#modalPilihPeringkat').data('tender-uuid', _tenderUuid);

                // Always reset to 1 Peringkat as default
                $('#peringkatOptionGroup .peringkat-option').removeClass('selected');
                $('#peringkatOptionGroup .peringkat-option[data-value="1"]').addClass('selected');
                $('#peringkatOptionGroup input[value="1"]').prop('checked', true);
            });

            // Option card selection
            $(document).on('click', '.peringkat-option', function () {
                $('#peringkatOptionGroup .peringkat-option').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            // Teruskan — show Swal confirmation, then redirect
            $('#btnTeruskanLantik').on('click', function () {
                var peringkat  = $('#peringkatOptionGroup input[name="modal_kaedah"]:checked').val();
                var tenderUuid = $('#modalPilihPeringkat').data('tender-uuid');
                var label      = peringkat === '1' ? '1 Peringkat' : '2 Peringkat';

                // Dismiss the Bootstrap modal, then show Swal once hidden
                var bsModalEl = document.getElementById('modalPilihPeringkat');
                var bsModal   = bootstrap.Modal.getOrCreateInstance(bsModalEl);

                $(bsModalEl).one('hidden.bs.modal', function () {
                    Swal.fire({
                        title: 'Sahkan Pilihan Peringkat',
                        html: 'Anda pasti ingin memilih <strong>' + label + '</strong>?<br><small class="text-muted">Pilihan ini tidak boleh diubah selepas disimpan.</small>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#c41e3a',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Teruskan',
                        cancelButtonText: 'Batal',
                        reverseButtons: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            if (peringkat === '1') {
                                window.location.href = '{{ route("pelantikanJawatankuasaSatuPeringkat") }}?tender=' + tenderUuid;
                            } else {
                                window.location.href = _lantikUrl;
                            }
                        } else {
                            // User cancelled — re-open the selection modal
                            bsModal.show();
                        }
                    });
                });

                bsModal.hide();
            });

        });
    </script>
@endsection
