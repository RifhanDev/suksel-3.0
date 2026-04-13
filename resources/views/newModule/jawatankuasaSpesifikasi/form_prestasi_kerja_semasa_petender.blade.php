@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        #tbl-prestasi { border: 1px solid #e2e8f0; }
        #tbl-prestasi th, #tbl-prestasi td { border-right: 1px solid #e2e8f0 !important; }
        #tbl-prestasi th:last-child, #tbl-prestasi td:last-child { border-right: none !important; }

        /* ── Expand detail row ────────────────────────────────────── */
        .detail-row td {
            background: #f8fafc !important;
            border-top: none !important;
            padding: 0 !important;
        }
        .detail-inner {
            padding: 14px 20px 16px;
            border-top: 1px dashed #e2e8f0;
        }
        .detail-field-label {
            font-size: 0.67rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #94a3b8;
            margin-bottom: 3px;
        }
        .detail-field-value {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1e293b;
        }
        /* Chevron rotation */
        .btn-expand-row .chevron {
            transition: transform 0.2s ease;
        }
        .btn-expand-row.expanded .chevron {
            transform: rotate(180deg);
        }
    </style>
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Prestasi Kerja Semasa Petender</h3>
            <p class="text-muted small m-0">Isi maklumat prestasi kerja semasa yang sedang dilaksanakan oleh petender.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height:1.45;font-size:1rem;">
                    PROJEK MENAIKTARAF JALAN PELABUHAN UTARA DARI KLANG CONTAINER TERMINAL
                    <span class="fw-normal text-muted fst-italic" style="font-size:0.85rem;">(Kerja)</span>
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">SUKSEL/PERT/2026/001</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">100-007</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background:#fef9c3;color:#854d0e;font-size:0.8rem;border:1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form id="form-prestasi" action="{{ route('jawatankuasa.hantarPrestasiKerjaSemasa') }}" method="POST" enctype="multipart/form-data">
    @csrf

        <!-- ===================== SECTION 1: PRESTASI KERJA SEMASA ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width:38px;height:38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size:1rem;">Prestasi Kerja Semasa</h3>
                        <p class="text-muted mb-0" style="font-size:0.78rem;">Senarai kerja semasa yang sedang dilaksanakan</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Info note -->
                <div class="rounded-2 px-3 py-2 mb-4 d-inline-flex align-items-center gap-2"
                    style="background:#eff6ff;border:1px solid #bfdbfe;font-size:0.78rem;color:#1e40af;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Perlu diisi oleh Petender — klik <strong class="ms-1">↓</strong> pada baris untuk lihat butiran penuh
                </div>

                <!-- Tambah button -->
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" id="btn-tambah-prestasi"
                        class="btn btn-sm btn-success d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#modalKerja">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="tbl-prestasi" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:44px;"></th>
                                <th class="text-center py-3" style="width:44px;">Bil</th>
                                <th class="py-3">Nama Ringkas Kerja Semasa</th>
                                <th class="py-3" style="width:150px;">No. Kontrak</th>
                                <th class="text-end py-3" style="width:150px;">Harga Kontrak (RM)</th>
                                <th class="text-center py-3" style="width:130px;">Tarikh Tapak</th>
                                <th class="text-center py-3" style="width:110px;">Tempoh (P)</th>
                                <th class="text-center py-3" style="width:90px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-prestasi-body">
                            <tr id="tbl-prestasi-empty">
                                <td colspan="8" class="text-center text-muted py-4 small">
                                    Tiada rekod. Klik <strong>Tambah</strong> untuk menambah kerja semasa.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- ===================== SECTION 2: DOKUMEN SOKONGAN ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width:38px;height:38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size:1rem;">Dokumen Sokongan</h3>
                        <p class="text-muted mb-0" style="font-size:0.78rem;">Muat naik dokumen berkaitan prestasi kerja semasa</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">
                <label class="upload-zone w-100" id="upload-zone-prestasi" for="input-dokumen-prestasi">
                    <div class="upload-zone-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 16 12 12 8 16"></polyline>
                            <line x1="12" y1="12" x2="12" y2="21"></line>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                        </svg>
                    </div>
                    <span class="upload-zone-label">Klik atau seret fail ke sini untuk muat naik</span>
                    <span class="upload-zone-sub">PDF, Word, Excel, Imej — saiz maksimum 10 MB setiap fail</span>
                    <input type="file" id="input-dokumen-prestasi" name="dokumen_prestasi[]" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                </label>
                <div class="file-chip-list" id="file-chip-list-prestasi"></div>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="{{ route('senaraiTeknikal') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="d-flex gap-2">
                <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                <button type="submit" class="btn-form btn-form-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>

    </form>

@endsection

@push('modals')
<!-- ===================== MODAL: TAMBAH / KEMASKINI KERJA ===================== -->
<div class="modal fade" id="modalKerja" tabindex="-1" aria-labelledby="modalKerjaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalKerjaLabel">Tambah Kerja Semasa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Nama Ringkas Kerja Semasa <span class="text-danger">*</span></label>
                        <input type="text" id="mk_nama" class="form-control form-control-sm" placeholder="Nama ringkas kerja...">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">No. Kontrak Kerja Semasa</label>
                        <input type="text" id="mk_no_kontrak" class="form-control form-control-sm" placeholder="No. kontrak...">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Harga Kontrak (RM)</label>
                        <input type="text" id="mk_harga" class="form-control form-control-sm text-end" placeholder="0.00">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Tarikh Pemilikan Tapak</label>
                        <input type="text" id="mk_tarikh_tapak" class="form-control form-control-sm mk-date" placeholder="dd/mm/yyyy" readonly>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Tempoh Kontrak (Hari) (P)</label>
                        <input type="number" id="mk_tempoh" class="form-control form-control-sm" placeholder="0" min="0">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Tarikh Siap Kontrak <span class="fw-normal text-muted" style="font-size:0.7rem;">(termasuk EOT diluluskan)</span></label>
                        <input type="text" id="mk_tarikh_siap" class="form-control form-control-sm mk-date" placeholder="dd/mm/yyyy" readonly>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Tarikh Penilaian Kemajuan</label>
                        <input type="text" id="mk_tarikh_penilaian" class="form-control form-control-sm mk-date" placeholder="dd/mm/yyyy" readonly>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Luputan Tarikh Siap Kontrak (Hari) (D)</label>
                        <input type="text" id="mk_luputan" class="form-control form-control-sm mk-date" placeholder="dd/mm/yyyy" readonly>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Peratus Kemajuan Sebenar Dicapai (A)(%)</label>
                        <input type="number" id="mk_kemajuan_sebenar" class="form-control form-control-sm" placeholder="0" min="0" max="100" step="0.01">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Peratus Kemajuan Mengikut Jadual (S)(%)</label>
                        <input type="number" id="mk_kemajuan_jadual" class="form-control form-control-sm" placeholder="0" min="0" max="100" step="0.01">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-form btn-form-primary" id="btn-modal-simpan">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endpush

@section('scripts')
<script src="{{ asset('js/components/file-upload.js') }}"></script>
<script>
$(document).ready(function () {

    var entries = [];
    var editIdx = null;

    // ── Datepickers in modal ──────────────────────────────────────────────────
    $('.mk-date').datepicker({ format: 'd M yyyy', autoclose: true, todayHighlight: true, todayBtn: 'linked' });

    // ── Amount helpers ────────────────────────────────────────────────────────
    function fmtAmt(n) { return (parseFloat(n)||0).toLocaleString('en-MY',{minimumFractionDigits:2,maximumFractionDigits:2}); }
    function parseAmt(s){ return parseFloat(String(s).replace(/,/g,''))||0; }

    $('#mk_harga').on('focus', function(){ $(this).val($(this).val().replace(/,/g,'')||''); })
                  .on('blur',  function(){ if($(this).val()) $(this).val(fmtAmt($(this).val())); })
                  .on('input', function(){ $(this).val($(this).val().replace(/[^\d.]/g,'')); });

    // ── Open modal fresh ──────────────────────────────────────────────────────
    $('#btn-tambah-prestasi').on('click', function () {
        editIdx = null;
        $('#modalKerjaLabel').text('Tambah Kerja Semasa');
        clearModal();
    });

    function clearModal() {
        $('#mk_nama,#mk_no_kontrak,#mk_harga,#mk_tarikh_tapak,#mk_tarikh_siap,#mk_tarikh_penilaian,#mk_luputan').val('');
        $('#mk_tempoh,#mk_kemajuan_sebenar,#mk_kemajuan_jadual').val('');
        $('#mk_nama').removeClass('is-invalid');
    }

    // ── Simpan ────────────────────────────────────────────────────────────────
    $('#btn-modal-simpan').on('click', function () {
        var entry = {
            nama:             $('#mk_nama').val().trim(),
            no_kontrak:       $('#mk_no_kontrak').val().trim(),
            harga:            parseAmt($('#mk_harga').val()),
            tarikh_tapak:     $('#mk_tarikh_tapak').val(),
            tempoh:           $('#mk_tempoh').val(),
            tarikh_siap:      $('#mk_tarikh_siap').val(),
            tarikh_penilaian: $('#mk_tarikh_penilaian').val(),
            luputan:          $('#mk_luputan').val(),
            kemajuan_sebenar: $('#mk_kemajuan_sebenar').val(),
            kemajuan_jadual:  $('#mk_kemajuan_jadual').val(),
        };
        if (!entry.nama) { $('#mk_nama').addClass('is-invalid').focus(); return; }
        $('#mk_nama').removeClass('is-invalid');

        editIdx !== null ? (entries[editIdx] = entry) : entries.push(entry);
        renderTable();
        bootstrap.Modal.getInstance(document.getElementById('modalKerja')).hide();
    });

    // ── Render ────────────────────────────────────────────────────────────────
    function detailHtml(e) {
        function field(label, val) {
            return '<div class="col-6 col-md-4 col-lg-2">' +
                       '<div class="detail-field-label">' + label + '</div>' +
                       '<div class="detail-field-value">' + (val || '—') + '</div>' +
                   '</div>';
        }
        return '<td colspan="8" class="p-0">' +
                   '<div class="detail-inner">' +
                       '<div class="row g-3 justify-content-center">' +
                           field('Tarikh Siap Kontrak', e.tarikh_siap) +
                           field('Tarikh Penilaian', e.tarikh_penilaian) +
                           field('Luputan (Hari)', e.luputan) +
                           field('Kemajuan Sebenar (A)%', e.kemajuan_sebenar ? e.kemajuan_sebenar + '%' : '—') +
                           field('Kemajuan Jadual (S)%', e.kemajuan_jadual ? e.kemajuan_jadual + '%' : '—') +
                       '</div>' +
                   '</div>' +
               '</td>';
    }

    function renderTable() {
        var $body = $('#tbl-prestasi-body');
        $body.empty();
        if (entries.length === 0) {
            $body.append('<tr id="tbl-prestasi-empty"><td colspan="8" class="text-center text-muted py-4 small">Tiada rekod. Klik <strong>Tambah</strong> untuk menambah kerja semasa.</td></tr>');
            return;
        }
        $.each(entries, function (i, e) {
            var rowId = 'detail-' + i;
            // Summary row
            var $main = $(
                '<tr class="prestasi-main-row" data-idx="' + i + '">' +
                    '<td class="text-center">' +
                        '<button type="button" class="btn-expand-row d-inline-flex align-items-center justify-content-center" ' +
                        'style="width:24px;height:24px;border:1px solid #e2e8f0;border-radius:4px;background:#fff;color:#64748b;padding:0;" ' +
                        'data-target="#' + rowId + '">' +
                            '<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>' +
                        '</button>' +
                    '</td>' +
                    '<td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">' + (i+1) + '</td>' +
                    '<td class="fw-semibold" style="font-size:0.85rem;">' + $('<span>').text(e.nama).html() + '</td>' +
                    '<td class="text-muted small">' + (e.no_kontrak||'—') + '</td>' +
                    '<td class="text-end fw-semibold">' + (e.harga ? fmtAmt(e.harga) : '—') + '</td>' +
                    '<td class="text-center small text-muted">' + (e.tarikh_tapak||'—') + '</td>' +
                    '<td class="text-center small text-muted">' + (e.tempoh ? e.tempoh+' hari' : '—') + '</td>' +
                    '<td class="text-center">' +
                        '<div class="d-flex justify-content-center gap-1">' +
                            '<button type="button" class="btn-edit-kerja d-inline-flex align-items-center justify-content-center" ' +
                            'style="width:28px;height:28px;border-radius:6px;background:#fef3c7;color:#d97706;border:none;" title="Kemaskini" data-idx="' + i + '">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                            '</button>' +
                            '<button type="button" class="btn-hapus-kerja d-inline-flex align-items-center justify-content-center" ' +
                            'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang" data-idx="' + i + '">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                            '</button>' +
                        '</div>' +
                    '</td>' +
                '</tr>'
            );
            // Detail row (hidden by default)
            var $detail = $('<tr class="detail-row d-none" id="' + rowId + '">').append(detailHtml(e));
            $body.append($main).append($detail);
        });
    }

    // ── Expand / collapse detail row ──────────────────────────────────────────
    $('#tbl-prestasi-body').on('click', '.btn-expand-row', function () {
        var $btn    = $(this);
        var $detail = $($btn.data('target'));
        $btn.toggleClass('expanded');
        $detail.toggleClass('d-none');
    });

    // ── Edit ──────────────────────────────────────────────────────────────────
    $('#tbl-prestasi-body').on('click', '.btn-edit-kerja', function () {
        var idx = parseInt($(this).data('idx'));
        editIdx  = idx;
        var e    = entries[idx];
        $('#modalKerjaLabel').text('Kemaskini Kerja ' + (idx+1));
        $('#mk_nama').val(e.nama);
        $('#mk_no_kontrak').val(e.no_kontrak);
        $('#mk_harga').val(e.harga ? fmtAmt(e.harga) : '');
        $('#mk_tarikh_tapak').val(e.tarikh_tapak);
        $('#mk_tempoh').val(e.tempoh);
        $('#mk_tarikh_siap').val(e.tarikh_siap);
        $('#mk_tarikh_penilaian').val(e.tarikh_penilaian);
        $('#mk_luputan').val(e.luputan);
        $('#mk_kemajuan_sebenar').val(e.kemajuan_sebenar);
        $('#mk_kemajuan_jadual').val(e.kemajuan_jadual);
        new bootstrap.Modal(document.getElementById('modalKerja')).show();
    });

    // ── Hapus ─────────────────────────────────────────────────────────────────
    $('#tbl-prestasi-body').on('click', '.btn-hapus-kerja', function () {
        entries.splice(parseInt($(this).data('idx')), 1);
        renderTable();
    });

    // ── Inject hidden inputs on submit ────────────────────────────────────────
    $('#form-prestasi').on('submit', function () {
        $(this).find('.prestasi-hidden').remove();
        var $f = $(this);
        $.each(entries, function (i, e) {
            $.each(e, function (key, val) {
                $('<input type="hidden">').attr('name', key+'[]').val(val).addClass('prestasi-hidden').appendTo($f);
            });
        });
    });

    // ── File upload ───────────────────────────────────────────────────────────
    FileUpload.init({
        zoneId     : 'upload-zone-prestasi',
        inputId    : 'input-dokumen-prestasi',
        chipListId : 'file-chip-list-prestasi'
    });

});
</script>
@endsection
