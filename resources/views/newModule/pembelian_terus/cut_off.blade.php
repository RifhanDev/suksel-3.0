@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* --- PROJECT SUMMARY --- */
        .project-summary {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid var(--sg-red);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .project-summary-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 22px;
            flex: 1;
            min-width: 240px;
        }

        .project-summary-item .ps-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(196, 30, 58, 0.08);
            color: var(--sg-red);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-summary-item .ps-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .project-summary-item .ps-label {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .project-summary-item .ps-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.35;
        }

        .project-summary .ps-divider {
            width: 1px;
            background: #f1f5f9;
            align-self: stretch;
            margin: 12px 0;
        }

        @media (max-width: 767px) {
            .project-summary .ps-divider { display: none; }
            .project-summary-item {
                flex: 1 1 100%;
                border-bottom: 1px solid #f1f5f9;
            }
            .project-summary-item:last-child { border-bottom: none; }
        }

        /* --- CUT-OFF TABLE --- */
        .cutoff-table thead th {
            background: #f8fafc;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6b7280;
            border-color: #e5e7eb;
        }
    </style>
@endsection

@section('content')
    @php $p = $project; @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Cut Off Projek</h3>
            <p class="text-muted small m-0">Penentuan cut-off dan pemilihan pembekal.</p>
        </div>
    </div>

    <!-- PROJECT SUMMARY -->
    <div class="project-summary mb-4">
        <div class="project-summary-item">
            <div class="ps-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div class="ps-text">
                <span class="ps-label">No. Sebut Harga / Tender</span>
                <span class="ps-value">{{ $p->no_tender }}</span>
            </div>
        </div>
        <div class="ps-divider"></div>
        <div class="project-summary-item" style="flex: 2;">
            <div class="ps-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </div>
            <div class="ps-text">
                <span class="ps-label">Tajuk Perolehan</span>
                <span class="ps-value">{{ $p->name }}</span>
            </div>
        </div>
    </div>

    <form id="cutOffForm" action="{{ route('pembelianTerus.storeCutoff', $p->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modern-card">

            <!-- ======================= PENENTUAN CUT-OFF ======================= -->
            <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <span class="fw-bold text-dark text-uppercase small">Penentuan Cut-Off</span>
            </div>

            <div class="p-4">
                <div class="table-responsive">
                    <table id="tbl-cutoff" class="table cutoff-table align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="py-3 ps-4 fw-bold" width="60px">Bil.</th>
                                <th class="py-3 fw-bold">Nama Pembekal</th>
                                <th class="py-3 fw-bold text-center" width="200px">Jumlah Harga Tawaran (RM)</th>
                                <th class="py-3 fw-bold text-center" width="180px">Dokumen BQ</th>
                                <th class="py-3 pe-4 fw-bold text-center" width="80px">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        Pilih
                                        <input type="checkbox" class="form-check-input mt-0" id="check-all-supplier">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $index => $supplier)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td class="text-center fw-semibold">{{ number_format($supplier->harga_tawaran, 2) }}</td>
                                    <td class="text-center">
                                        <a href="#" download
                                            class="fw-semibold text-decoration-none d-inline-flex align-items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            {{ $supplier->bq_filename }}
                                        </a>
                                    </td>
                                    <td class="text-center pe-4">
                                        <input type="checkbox" class="form-check-input supplier-check"
                                            name="offer_ids[]" value="{{ $supplier->id }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ======================= MUAT NAIK DOKUMEN ======================= -->
            <div class="bg-light px-4 py-3 border-top border-bottom d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <span class="fw-bold text-dark text-uppercase small">Muat Naik Dokumen</span>
            </div>

            <div class="p-4">
                <div class="row g-4">
                    <!-- JPICT -->
                    <div class="col-md-6">
                        <label class="form-label">JPICT</label>
                        <label class="upload-zone w-100" id="upload-zone-jpict" for="input-jpict">
                            <div class="upload-zone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="16 16 12 12 8 16"></polyline>
                                    <line x1="12" y1="12" x2="12" y2="21"></line>
                                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                </svg>
                            </div>
                            <span class="upload-zone-label">Sila muat naik fail di sini</span>
                            <span class="upload-zone-sub">PDF, Word, Excel — satu fail sahaja</span>
                            <input type="file" id="input-jpict" name="jpict" hidden
                                accept=".pdf,.doc,.docx,.xls,.xlsx">
                        </label>
                        <div class="file-chip-list" id="file-chip-list-jpict"></div>
                    </div>

                    <!-- Minit Bebas -->
                    <div class="col-md-6">
                        <label class="form-label">Minit Bebas</label>
                        <label class="upload-zone w-100" id="upload-zone-minit" for="input-minit">
                            <div class="upload-zone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="16 16 12 12 8 16"></polyline>
                                    <line x1="12" y1="12" x2="12" y2="21"></line>
                                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                </svg>
                            </div>
                            <span class="upload-zone-label">Sila muat naik fail di sini</span>
                            <span class="upload-zone-sub">PDF, Word, Excel — satu fail sahaja</span>
                            <input type="file" id="input-minit" name="minit_bebas" hidden
                                accept=".pdf,.doc,.docx,.xls,.xlsx">
                        </label>
                        <div class="file-chip-list" id="file-chip-list-minit"></div>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="bg-light p-4 border-top d-flex justify-content-end gap-2">
                <button type="button" class="btn-form btn-form-success" id="btn-laporan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Laporan
                </button>
                <button type="submit" class="btn-form btn-form-primary" id="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Selesai
                </button>
            </div>

        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // --- SORTABLE TABLE (Nama Pembekal & Jumlah Harga Tawaran only) ---
            $('#tbl-cutoff').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                columnDefs: [
                    { targets: [0, 3, 4], orderable: false } // Bil, Dokumen BQ, Pilih
                ],
                order: [],
                language: {
                    sEmptyTable: "Tiada data"
                }
            });

            // --- SELECT ALL SUPPLIERS ---
            $('#check-all-supplier').on('change', function() {
                $('.supplier-check').prop('checked', $(this).prop('checked'));
            });

            $(document).on('change', '.supplier-check', function() {
                var total   = $('.supplier-check').length;
                var checked = $('.supplier-check:checked').length;
                $('#check-all-supplier').prop('checked', total === checked);
            });

            // --- SINGLE-FILE UPLOAD (reusable) ---
            function initSingleUpload(opts) {
                var $zone = $('#' + opts.zoneId);
                var input = document.getElementById(opts.inputId);
                var $list = $('#' + opts.listId);

                function formatBytes(b) {
                    if (b < 1024) return b + ' B';
                    if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
                    return (b / 1048576).toFixed(1) + ' MB';
                }

                function render(file) {
                    var ext      = file.name.split('.').pop().toLowerCase();
                    var safeName = $('<span>').text(file.name).html();
                    $list.empty();
                    var $chip = $(
                        '<div class="file-chip">' +
                            '<span class="file-chip-ext ext-' + ext + '">' + ext + '</span>' +
                            '<div class="file-chip-body">' +
                                '<span class="file-chip-name" title="' + safeName + '">' + safeName + '</span>' +
                                '<span class="file-chip-size">' + formatBytes(file.size) + '</span>' +
                            '</div>' +
                            '<button type="button" class="file-chip-remove" title="Buang fail">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                            '</button>' +
                        '</div>'
                    );
                    $chip.find('.file-chip-remove').on('click', function() {
                        input.value = '';
                        $chip.remove();
                    });
                    $list.append($chip);
                }

                $(input).on('change', function() {
                    if (this.files && this.files[0]) render(this.files[0]);
                });

                var zoneEl = $zone[0];
                zoneEl.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    $zone.addClass('dragover');
                });
                zoneEl.addEventListener('dragleave', function() {
                    $zone.removeClass('dragover');
                });
                zoneEl.addEventListener('drop', function(e) {
                    e.preventDefault();
                    $zone.removeClass('dragover');
                    var files = e.dataTransfer.files;
                    if (!files || !files.length) return;
                    var dt = new DataTransfer();
                    dt.items.add(files[0]);
                    input.files = dt.files;
                    render(files[0]);
                });
            }

            initSingleUpload({ zoneId: 'upload-zone-jpict', inputId: 'input-jpict', listId: 'file-chip-list-jpict' });
            initSingleUpload({ zoneId: 'upload-zone-minit', inputId: 'input-minit', listId: 'file-chip-list-minit' });

        });
    </script>
@endsection
