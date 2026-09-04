@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* ── Table grid borders ─────────────────────────────────────── */
        #tbl-kdt {
            border: 1px solid #e2e8f0;
        }
        #tbl-kdt th,
        #tbl-kdt td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-kdt th:last-child,
        #tbl-kdt td:last-child {
            border-right: none !important;
        }
    </style>
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kerja Dalam Tangan</h3>
            <p class="text-muted small m-0">Isi maklumat kerja yang sedang dilaksanakan oleh petender.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">

            <!-- Tajuk Tender -->
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    MEMBEKAL RANGSUM PUKAL (AIR MINERAL) UNTUK BANGUNAN KERAJAAN
                    <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">(Bekalan Perkhidmatan)</span>
                </h5>
            </div>

            <!-- Metadata: No. Tender · PTJ · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">SUKSEL/PERT/2026/001</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">100-007</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle"
                            style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>

        </div>
    </div>

    <form id="form-kerja-dalam-tangan" action="{{ route('jawatankuasa.simpanKerjaDalamTangan') }}" method="POST" enctype="multipart/form-data">
    @csrf

        <!-- ===================== SECTION: KERJA DALAM TANGAN ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Kerja Dalam Tangan</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                    </div>
                </div>
            </div>

            <div class="content-card-body p-4">

                <!-- Table toolbar -->
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" id="btn-tambah-row"
                        class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="tbl-kdt" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:50px;">Bil</th>
                                <th class="py-3" style="min-width:220px;">Senarai Kerja Dalam Tangan</th>
                                <th class="py-3" style="min-width:160px;">PIC</th>
                                <th class="py-3" style="width:150px;">No. Telefon PIC</th>
                                <th class="text-end py-3" style="width:160px;">Nilai Kerja (RM)</th>
                                <th class="text-center py-3" style="width:60px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-kdt-body">
                            <!-- initial row rendered by JS below -->
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid #e2e8f0;">
                                <th colspan="4" class="text-end text-muted" style="font-size:0.75rem;">JUMLAH NILAI KERJA</th>
                                <th class="text-end" id="total-nilai-kdt" style="font-size:0.875rem; color:#1e293b;">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

        <!-- ===================== SECTION: DOKUMEN SOKONGAN ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Dokumen Sokongan / Rujukan</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Muat naik surat kontrak, pesanan kerja atau dokumen berkaitan kerja dalam tangan</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Upload Zone -->
                <label class="upload-zone w-100" id="upload-zone-kdt" for="input-dokumen-kdt">
                    <div class="upload-zone-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 16 12 12 8 16"></polyline>
                            <line x1="12" y1="12" x2="12" y2="21"></line>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                        </svg>
                    </div>
                    <span class="upload-zone-label">Klik atau seret fail ke sini untuk muat naik</span>
                    <span class="upload-zone-sub">PDF, Word, Excel, Imej, ZIP — saiz maksimum 10 MB setiap fail</span>
                    <input type="file" id="input-dokumen-kdt" name="dokumen_kdt[]" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.rar">
                </label>

                <!-- Uploaded file chips -->
                <div class="file-chip-list" id="file-chip-list-kdt"></div>

            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="{{ request()->route('tenderUuid') ? route('senaraiTeknikal', request()->route('tenderUuid')) : url()->previous() }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="d-flex gap-2">
                <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                <button type="submit" class="btn-form btn-form-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

@section('scripts')
<script src="{{ asset('js/components/file-upload.js') }}"></script>
<script>
$(document).ready(function () {

    // ── Row template ─────────────────────────────────────────────────
    function buildRow(bil) {
        return $('<tr class="kdt-row">' +
            '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
            '<td><input type="text" name="kdt_tajuk[]" class="form-control form-control-sm" placeholder="Nama / tajuk kerja..."></td>' +
            '<td><input type="text" name="kdt_pic[]" class="form-control form-control-sm" placeholder="Nama PIC..."></td>' +
            '<td><input type="text" name="kdt_telefon[]" class="form-control form-control-sm" placeholder="Cth: 012-3456789"></td>' +
            '<td><input type="text" name="kdt_nilai[]" class="form-control form-control-sm text-end nilai-kerja" placeholder="0.00"></td>' +
            '<td class="text-center">' +
                '<button type="button" class="btn btn-sm btn-hapus-row d-inline-flex align-items-center justify-content-center p-0" ' +
                    'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" ' +
                    'title="Buang baris">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                '</button>' +
            '</td>' +
        '</tr>');
    }

    // Seed first row
    $('#tbl-kdt-body').append(buildRow(1));

    // ── Re-number rows ───────────────────────────────────────────────
    function reNumber() {
        $('#tbl-kdt-body .kdt-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    // ── Update total ─────────────────────────────────────────────────
    function updateTotal() {
        var total = 0;
        $('#tbl-kdt-body .nilai-kerja').each(function () {
            total += parseFloat($(this).val().replace(/,/g, '')) || 0;
        });
        $('#total-nilai-kdt').text(total.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // ── Add row ──────────────────────────────────────────────────────
    $('#btn-tambah-row').on('click', function () {
        var bil = $('#tbl-kdt-body .kdt-row').length + 1;
        $('#tbl-kdt-body').append(buildRow(bil));
    });

    // ── Delete row ───────────────────────────────────────────────────
    $('#tbl-kdt-body').on('click', '.btn-hapus-row', function () {
        if ($('#tbl-kdt-body .kdt-row').length <= 1) return;
        $(this).closest('tr').remove();
        reNumber();
        updateTotal();
    });

    // ── Nilai kerja: numeric only + format on blur ───────────────────
    $('#tbl-kdt-body').on('input', '.nilai-kerja', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
        updateTotal();
    });
    $('#tbl-kdt-body').on('blur', '.nilai-kerja', function () {
        var v = parseFloat($(this).val().replace(/,/g, '')) || 0;
        $(this).val(v.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        updateTotal();
    });

    // ── File Upload Zone ─────────────────────────────────────────────
    if (typeof FileUpload !== 'undefined' && typeof FileUpload.init === 'function') {
        FileUpload.init({
            zoneId     : 'upload-zone-kdt',
            inputId    : 'input-dokumen-kdt',
            chipListId : 'file-chip-list-kdt'
        });
    }
});
</script>
@endsection

