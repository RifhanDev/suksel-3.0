@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Pekeliling</h3>
            <p class="text-muted small m-0">Pengurusan dan tetapan pekeliling laman awam.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <h3 class="content-card-title">Maklumat Pekeliling</h3>
            </div>

            <div class="d-flex gap-2">
                @if (App\Models\Circular::canCreate())
                    <a href="{{ asset('circulars/sort') }}" class="btn-form btn-form-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        Kemaskini Susunan
                    </a>
                    <a href="{{ asset('circulars/create') }}" class="btn-form btn-form-create">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Masukkan Pekeliling Baru
                    </a>
                @endif
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table data-path="/circulars" class="DT-index table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Tajuk</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3">Siar</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3">Susunan</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3">Tarikh Muat Naik</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4"
                                style="width: 220px; min-width: 220px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PDF Viewer Modal -->
    <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content h-100 border-0 shadow-lg rounded-3">
                <div class="modal-body p-0 bg-light">
                    <iframe id="pdfIframe" src="" width="100%" height="100%"
                        style="border:none; min-height: 85vh;"></iframe>
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
                order: [
                    [2, 'asc']
                ],
                ajax: path,
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'published',
                        name: 'published',
                        class: 'text-center'
                    },
                    {
                        data: 'position',
                        name: 'position',
                        class: 'text-center'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        class: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
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
                aaSorting: []
            });
        });

        // PDF Viewer Modal
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                var target = e.target.closest('.view-pdf-btn');

                if (target) {
                    e.preventDefault();
                    var url = target.getAttribute('data-url') || target.getAttribute('href');

                    if (url) {
                        var iframe = document.getElementById('pdfIframe');
                        var modalEl = document.getElementById('pdfViewerModal');

                        if (iframe && modalEl) {
                            iframe.src = url;
                            var myModal = new bootstrap.Modal(modalEl);
                            myModal.show();
                        }
                    }
                }
            });

            var pdfModal = document.getElementById('pdfViewerModal');
            if (pdfModal) {
                pdfModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('pdfIframe').src = '';
                });
            }
        });
    </script>
@endsection
