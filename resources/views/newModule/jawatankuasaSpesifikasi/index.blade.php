@extends('layouts.v3.master')
@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender / Sebutharga</h3>
            <p class="text-muted small m-0">Paparan maklumat tender terkini di bawah Sistem e-Perolehan Selangor.</p>
        </div>
    </div>

    <!-- FILTER -->
    <div class="card border shadow-sm mb-3 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-lg-2">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <input type="text" id="filter_no_tender" class="form-control form-control-sm" placeholder="Cth: QT210000000023741">
                </div>

                <div class="col-12 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
                    <input type="text" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk projek...">
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <select id="filter_status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Belum Disiarkan">Belum Disiarkan</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh</label>
                    <input type="text" id="filter_tarikh" class="form-control form-control-sm datepicker" placeholder="dd/mm/yyyy">
                </div>

                <div class="col-12 col-lg-2">
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
        <div class="content-card-header p-4 pb-3 border-bottom">
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
                <table id="tbl-spesifikasi" class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="180px">No. Tender / Sebut Harga</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Tajuk Perolehan</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="130px">Tarikh Jual</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="130px">Tarikh Tutup</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" width="140px">Status</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="120px">Tindakan</th>
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
        $(document).ready(function () {

            // ── DUMMY DATA (replace with API/controller data later) ──────────────
            const urlTeknikal = "{{ route('senaraiTeknikal') }}";
            const urlKewangan = "{{ route('senaraiKewangan') }}";
            const urlKewanganKerja = "{{ route('senaraiKewanganKerja') }}";
            const urlPenyediaanTender = "{{ route('penyediaanSpekTender') }}";

            const dummyData = [
                {
                    no_tender:    'QT210000000023741',
                    tajuk:        'MEMBEKAL RANGSUM PUKAL (AIR MINERAL) UNTUK BANGUNAN KERAJAAN <i>(Bekalan Perkhidmatan)</i>',
                    tarikh_jual:  '03/01/2026',
                    tarikh_tutup: '01/05/2026',
                    status:       '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2 fw-semibold" style="background:#fef9c3;color:#854d0e;font-size:0.72rem;border:1px solid #fde68a;"><span class="rounded-circle" style="width:6px;height:6px;background:#ca8a04;flex-shrink:0;display:inline-block;"></span>Dalam Proses</span>',
                    tindakan:     `<div class="d-flex gap-1 justify-content-center">
                                       <a href="${urlTeknikal}" class="btn btn-sm btn-warning text-white">Teknikal</a>
                                       <a href="${urlKewangan}" class="btn btn-sm btn-success">Kewangan</a>
                                   </div>`
                },
                {
                    no_tender:    'QT210000000031582',
                    tajuk:        'PROJEK MENAIKTARAF JALAN PELABUHAN UTARA DARI KLANG CONTAINER TERMINAL <i>(Kerja)</i>',
                    tarikh_jual:  '27/02/2026',
                    tarikh_tutup: '31/07/2026',
                    status:       '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2 fw-semibold" style="background:#f0fdf4;color:#166534;font-size:0.72rem;border:1px solid #bbf7d0;"><span class="rounded-circle" style="width:6px;height:6px;background:#16a34a;flex-shrink:0;display:inline-block;"></span>Telah Dihantar</span>',
                    tindakan:     `<div class="d-flex gap-1 justify-content-center">
                                       <a href="${urlPenyediaanTender}" class="btn btn-sm btn-info text-white">Spesifikasi</a>
                                       <a href="${urlKewanganKerja}" class="btn btn-sm btn-success">Kewangan</a>
                                   </div>`
                }
            ];
            // ─────────────────────────────────────────────────────────────────────

            var DT = $('#tbl-spesifikasi').DataTable({
                data: dummyData,
                columns: [
                    { data: 'no_tender',    className: 'ps-4 fw-semibold' },
                    { data: 'tajuk' },
                    { data: 'tarikh_jual',  className: 'text-center' },
                    { data: 'tarikh_tutup', className: 'text-center' },
                    { data: 'status',       orderable: false, searchable: false, className: 'text-center' },
                    { data: 'tindakan',     orderable: false, searchable: false, className: 'text-center pe-4' }
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

            // Apply Filter
            $('#btn_apply_filter').on('click', function () {
                var noTender = $('#filter_no_tender').val();
                var tajuk    = $('#filter_tajuk').val();
                var status   = $('#filter_status').val();
                DT.search(noTender || tajuk || status).draw();
            });

            // Reset Filter
            $('#btn_reset_filter').on('click', function () {
                $('#filter_no_tender').val('');
                $('#filter_tajuk').val('');
                $('#filter_status').val('');
                $('#filter_tarikh').val('');
                DT.search('').draw();
            });

        });
    </script>
@endsection
