@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        #tbl-spesifikasi { border: 1px solid #e2e8f0; }
        #tbl-spesifikasi th, #tbl-spesifikasi td { border-right: 1px solid #e2e8f0 !important; }
        #tbl-spesifikasi th:last-child, #tbl-spesifikasi td:last-child { border-right: none !important; }

        /* Keep dokumen cell from growing too wide */
        .dokumen-chip-cell { min-width: 160px; max-width: 220px; }
    </style>
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Spesifikasi Tender</h3>
            <p class="text-muted small m-0">Isi maklumat spesifikasi bagi tender / sebutharga ini.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size:0.67rem;letter-spacing:0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height:1.45;font-size:1rem;">
                    PROJEK MENAIKTARAF JALAN PELABUHAN UTARA DARI KLANG CONTAINER TERMINAL
                    <span class="fw-normal text-muted fst-italic" style="font-size:0.85rem;">(Kerja)</span>
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size:0.67rem;letter-spacing:0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">SUKSEL/PERT/2026/001</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size:0.67rem;letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">100-007</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background:#fef9c3;color:#854d0e;font-size:0.8rem;border:1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle"
                            style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form id="form-spesifikasi-tender" action="#" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- ===================== SECTION: PENYEDIAAN SPESIFIKASI TENDER ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width:38px;height:38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size:1rem;">Penyediaan Spesifikasi Tender</h3>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Senarai spesifikasi bagi tender ini</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Table toolbar -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <!-- Left: BQ upload -->
                <div class="d-flex align-items-center gap-2">
                    <label class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 mb-0"
                        style="cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Muat Naik Dokumen BQ / Spesifikasi
                        <input type="file" id="input-bq" name="bq_spesifikasi_file" accept=".xlsx,.xls,.pdf,.doc,.docx" hidden>
                    </label>
                    <span id="bq-filename" class="small text-muted fst-italic d-none"></span>
                </div>
                <!-- Right: Tambah -->
                <button type="button" id="btn-tambah-spesifikasi"
                    class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Spesifikasi
                </button>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="tbl-spesifikasi" class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center py-3" style="width:50px;">Bil</th>
                            <th class="py-3" style="min-width:280px;">Spesifikasi</th>
                            <th class="text-center py-3" style="width:120px;">Ya / Tidak</th>
                            <th class="py-3" style="min-width:220px;">Catatan</th>
                            <th class="py-3 dokumen-chip-cell">Dokumen</th>
                            <th class="text-center py-3" style="width:60px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-spesifikasi-body">
                        <tr id="tbl-spesifikasi-empty">
                            <td colspan="6" class="text-center text-muted py-4 small">
                                Tiada spesifikasi. Klik <strong>Tambah Spesifikasi</strong> untuk menambah baris.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="{{ url()->previous() }}" class="btn-form btn-form-secondary">
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
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
            <button type="submit" class="btn-form btn-form-success">
                Hantar
            </button>
        </div>
    </div>

    </form>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    var rowCount = 0;

    // ── Delete SVG ────────────────────────────────────────────────────────────
    var DELETE_BTN =
        '<button type="button" class="btn-hapus-spesifikasi d-inline-flex align-items-center justify-content-center p-0" ' +
        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
            'stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                '<polyline points="3 6 5 6 21 6"></polyline>' +
                '<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>' +
                '<path d="M10 11v6"></path><path d="M14 11v6"></path>' +
                '<path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>' +
            '</svg>' +
        '</button>';

    // ── Build row ─────────────────────────────────────────────────────────────
    function buildRow(bil) {
        var uploadId  = 'upload-row-' + bil;
        var chipId    = 'chips-row-' + bil;
        var inputId   = 'input-row-' + bil;

        var $tr = $(
            '<tr class="spesifikasi-row">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td style="vertical-align:top;padding-top:10px;">' +
                    '<textarea name="spesifikasi[]" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Masukkan spesifikasi..." style="resize:vertical;min-height:60px;"></textarea>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<select name="ya_tidak[]" class="form-select form-select-sm">' +
                        '<option value="">—</option>' +
                        '<option value="ya">Ya</option>' +
                        '<option value="tidak">Tidak</option>' +
                    '</select>' +
                '</td>' +
                '<td style="vertical-align:top;padding-top:10px;">' +
                    '<textarea name="catatan[]" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Catatan tambahan..." style="resize:vertical;min-height:60px;"></textarea>' +
                '</td>' +
                '<td class="dokumen-chip-cell text-center" style="vertical-align:middle;">' +
                    '<div class="d-flex flex-column align-items-center gap-1">' +
                    '<label class="d-inline-flex align-items-center gap-1 btn btn-sm btn-outline-primary px-2 py-1 mb-0" ' +
                    'style="cursor:pointer;font-size:0.75rem;" for="' + inputId + '">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>' +
                            '<polyline points="17 8 12 3 7 8"></polyline>' +
                            '<line x1="12" y1="3" x2="12" y2="15"></line>' +
                        '</svg>' +
                        'Muat Naik' +
                        '<input type="file" id="' + inputId + '" name="dokumen_row[' + bil + '][]" multiple hidden ' +
                        'accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">' +
                    '</label>' +
                    '<div class="spesifikasi-chips" id="' + chipId + '" style="display:flex;flex-wrap:wrap;gap:4px;justify-content:center;"></div>' +
                    '</div>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:middle;">' + DELETE_BTN + '</td>' +
            '</tr>'
        );

        // Wire up file input for this row
        $tr.find('#' + inputId).on('change', function () {
            var files = this.files;
            if (!files || files.length === 0) return;
            var $chips = $('#' + chipId);
            $.each(files, function (i, file) {
                var ext  = file.name.split('.').pop().toLowerCase();
                var url  = URL.createObjectURL(file);
                var $chip = $(
                    '<div style="display:inline-flex;align-items:center;gap:4px;background:#f1f5f9;border:1px solid #e2e8f0;' +
                    'border-radius:6px;padding:2px 6px 2px 4px;font-size:0.7rem;">' +
                        '<span style="background:#64748b;color:#fff;border-radius:3px;padding:1px 4px;font-size:0.6rem;font-weight:700;text-transform:uppercase;">' + ext + '</span>' +
                        '<a href="' + url + '" target="_blank" style="color:#334155;font-weight:600;max-width:90px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;" title="' + file.name + '">' + file.name + '</a>' +
                        '<button type="button" class="chip-remove" style="background:none;border:none;padding:0;color:#94a3b8;cursor:pointer;line-height:1;" data-url="' + url + '">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                        '</button>' +
                    '</div>'
                );
                $chip.find('.chip-remove').on('click', function () {
                    URL.revokeObjectURL($(this).data('url'));
                    $chip.remove();
                });
                $chips.append($chip);
            });
            $(this).val('');
        });

        return $tr;
    }

    // ── Re-number ─────────────────────────────────────────────────────────────
    function reNumber() {
        $('#tbl-spesifikasi-body .spesifikasi-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    function syncEmpty() {
        if ($('#tbl-spesifikasi-body .spesifikasi-row').length > 0) {
            $('#tbl-spesifikasi-empty').remove();
        } else if ($('#tbl-spesifikasi-empty').length === 0) {
            $('#tbl-spesifikasi-body').append(
                '<tr id="tbl-spesifikasi-empty"><td colspan="6" class="text-center text-muted py-4 small">' +
                'Tiada spesifikasi. Klik <strong>Tambah Spesifikasi</strong> untuk menambah baris.</td></tr>'
            );
        }
    }

    // ── Tambah Spesifikasi ────────────────────────────────────────────────────
    $('#btn-tambah-spesifikasi').on('click', function () {
        rowCount++;
        $('#tbl-spesifikasi-empty').remove();
        $('#tbl-spesifikasi-body').append(buildRow(rowCount));
    });

    // ── Hapus row ─────────────────────────────────────────────────────────────
    $('#tbl-spesifikasi-body').on('click', '.btn-hapus-spesifikasi', function () {
        $(this).closest('tr').remove();
        reNumber();
        syncEmpty();
    });

    // ── Muat Naik BQ — show filename ─────────────────────────────────────────
    $('#input-bq').on('change', function () {
        if (this.files && this.files[0]) {
            $('#bq-filename').text(this.files[0].name).removeClass('d-none');
        }
    });

});
</script>
@endsection
