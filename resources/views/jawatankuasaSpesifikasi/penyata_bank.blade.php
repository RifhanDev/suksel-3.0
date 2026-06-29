@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
<style>
        /* ── Table grid borders ───────────────────────────────────── */
        #tbl-purata-penyata {
            border: 1px solid #e2e8f0;
        }
        #tbl-purata-penyata th,
        #tbl-purata-penyata td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-purata-penyata th:last-child,
        #tbl-purata-penyata td:last-child {
            border-right: none !important;
        }

        /* Loading overlay */
        #loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }
        #loading-overlay.active { display: flex; }
        .loading-box {
            background: #fff;
            border-radius: 12px;
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .loading-spinner {
            width: 28px; height: 28px;
            border: 3px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text { font-size: 0.9rem; font-weight: 600; color: #1e293b; }
        .loading-check { display: none; width: 28px; height: 28px; flex-shrink: 0; color: #22c55e; }
        #loading-overlay.success .loading-spinner { display: none; }
        #loading-overlay.success .loading-check  { display: block; }
        #loading-overlay.success .loading-text   { color: #16a34a; }

        .borang-title-bar {
            background: #e2e8f0;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 10px 16px;
            border-radius: 6px 6px 0 0;
        }
        .penyata-akaun-block {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            background: #fff;
        }
        .penyata-akaun-block .akaun-heading {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
        }
        .btn-hapus-akaun {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #fee2e2;
            color: #ef4444;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        #tbl-purata-penyata thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 0.78rem;
            border-color: #1e3a5f !important;
        }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $kembaliUrl = $returnUrl ?? (
            (isset($tender->kategori_perolehan_name) && strtolower($tender->kategori_perolehan_name) === 'kerja')
                ? url('/senarai-kewangan-kerja/' . $tender->uuid)
                : url('/senarai-kewangan-bekalan/' . $tender->uuid)
        );
        $showVendorForm = $showVendorForm ?? false;
        $showScoringConfig = $showScoringConfig ?? true;
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyata Bank</h3>
            <p class="text-muted small m-0">Maklumat penyata bank terkini petender bagi tender / sebutharga ini.</p>
        </div>
    </div>

    <!-- TENDER INFO CARD -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">

            <!-- Tajuk Tender -->
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    {{ $tender->name ?? 'Tiada Tajuk' }}
                </h5>
            </div>

            <!-- Metadata: No. Tender · PTJ · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $tender->no_tender ?? $tender->ref_number ?? '-' }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ optional($tender->tenderer)->name ?? '-' }}</span>
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

    <!-- Loading overlay -->
    <div id="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <svg class="loading-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span class="loading-text" id="loading-text">Menyimpan...</span>
        </div>
    </div>

    <form id="form-penyata-bank" action="{{ route('penyataBank.store', $tender->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($modalEmbed ?? false)
        <input type="hidden" name="modal" value="1">
    @endif
    @if (! empty($returnUrl))
        <input type="hidden" name="return" value="{{ $returnUrl }}">
    @endif

    @if ($showVendorForm)
        @include('jawatankuasaSpesifikasi.partials.penyata_bank_vendor')
    @endif

    @if ($showScoringConfig)
    <!-- ===================== PURATA PENYATA BANK (SKEMA) ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Purata Penyata Bank</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Penetapan skema pemarkahan</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Jenis Skor -->
            <div class="row mb-3">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small">Jenis Skor <span class="text-danger">*</span></label>
                    <select name="jenis_skor_purata" class="form-select form-select-sm jenis-skor-select" data-target="#panel-purata-penyata">
                        <option value="">— Sila pilih —</option>
                        <option value="manual">Manual</option>
                        <option value="automatik" disabled>Automatik</option>
                    </select>
                </div>
            </div>

            <!-- Manual panel -->
            <div id="panel-purata-penyata" class="d-none">
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-purata">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="tbl-purata-penyata" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:55px;">Bil</th>
                                <th class="py-3">Dari</th>
                                <th class="py-3">Hingga</th>
                                <th class="py-3" style="width:140px;">Skema</th>
                                <th class="text-center py-3" style="width:60px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-purata-body">
                            <!-- seeded by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ===================== ACTION BUTTONS ===================== -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        @include('tenders.forms._vendor_form_kembali', ['kembaliUrl' => $kembaliUrl])
        @unless ($viewOnly ?? false)
        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Laporan
            </button>
            <button type="button" id="btn-simpan" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
        </div>
        @endunless
    </div>

    </form>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    var STORE_URL        = '{{ route("penyataBank.store", $tender->uuid) }}';
    var UPLOAD_URL       = '{{ route("penyataBank.uploadFile", $tender->uuid) }}';
    var DELETE_FILE_BASE = '{{ url("/penyata-bank-fail") }}';
    var CSRF_TOKEN       = '{{ csrf_token() }}';
    var penyataData      = @json($penyataData ?? null);
    var SHOW_VENDOR      = @json($showVendorForm ?? false);
    var SHOW_SCORING     = @json($showScoringConfig ?? true);
    var VIEW_ONLY        = @json($viewOnly ?? false);
    var VENDOR_MODE      = @json($vendorFormMode ?? false);
    var REDIRECT_URL     = @json($returnUrl ?? $kembaliUrl);

    var BULAN_MS = ['', 'Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'];
    var akaunCounter = 0;

    function parseRm(raw) {
        if (raw == null || String(raw).trim() === '') return 0;
        var s = String(raw).replace(/,/g, '').replace(/^\s*RM\s*/i, '').trim();
        var n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function formatRm(n) {
        if (isNaN(n) || !isFinite(n)) return '';
        return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function bulanSelepas(dy, dm, n) {
        var d = new Date(dy, dm - 1 + n, 1);
        return { y: d.getFullYear(), m: d.getMonth() + 1 };
    }

    function bulanLabel(m) {
        return BULAN_MS[m] || String(m);
    }

    // ── Multi-account vendor form ─────────────────────────────────────────────
    function buildMonthOptions(selected) {
        var html = '<option value="">Pilih Bulan</option>';
        for (var m = 1; m <= 12; m++) {
            html += '<option value="' + m + '"' + (selected === m ? ' selected' : '') + '>' + bulanLabel(m) + '</option>';
        }
        return html;
    }

    function buildYearOptions(selected) {
        var html = '<option value="">Pilih Tahun</option>';
        var nowY = new Date().getFullYear();
        for (var y = nowY - 10; y <= nowY; y++) {
            html += '<option value="' + y + '"' + (selected === y ? ' selected' : '') + '>' + y + '</option>';
        }
        return html;
    }

    function buildAkaunBlock(index, data) {
        data = data || {};
        var canDelete = index > 0;
        var $block = $(
            '<div class="penyata-akaun-block" data-akaun-index="' + index + '">' +
                '<div class="d-flex justify-content-between align-items-center mb-3">' +
                    '<span class="akaun-heading">Akaun ' + (index + 1) + '</span>' +
                    (canDelete && !VIEW_ONLY
                        ? '<button type="button" class="btn-hapus-akaun" title="Buang akaun">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                            '<polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>' +
                            '<path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                          '</button>'
                        : '') +
                '</div>' +
                '<p class="text-muted small mb-3">Sila pilih bulan pertama penyata bank yang perlu dikemukakan oleh petender</p>' +
                '<div class="row g-3 mb-3">' +
                    '<div class="col-12 col-md-6">' +
                        '<label class="form-label fw-semibold small">Dari (Bulan) <span class="text-danger">*</span></label>' +
                        '<div class="row g-2">' +
                            '<div class="col-6"><select class="form-select form-select-sm akaun-dari-bulan"' + (VIEW_ONLY ? ' disabled' : '') + '>' + buildMonthOptions(data.dari_bulan || null) + '</select></div>' +
                            '<div class="col-6"><select class="form-select form-select-sm akaun-dari-tahun"' + (VIEW_ONLY ? ' disabled' : '') + '>' + buildYearOptions(data.dari_tahun || null) + '</select></div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                        '<label class="form-label fw-semibold small">Hingga (Bulan)</label>' +
                        '<div class="row g-2">' +
                            '<div class="col-6"><input type="text" class="form-control form-control-sm bg-light akaun-hingga-bulan" readonly tabindex="-1"></div>' +
                            '<div class="col-6"><input type="text" class="form-control form-control-sm bg-light akaun-hingga-tahun" readonly tabindex="-1"></div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<p class="borang-subtitle mb-3">Perlu diisi oleh Petender</p>' +
                '<div class="row g-4">' +
                    '<div class="col-12 col-md-6">' +
                        '<div class="akaun-bulan-rows"></div>' +
                        '<div class="mt-3 pt-3 border-top">' +
                            '<div class="d-flex align-items-center gap-3 mb-2">' +
                                '<label class="fw-semibold text-muted mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:200px;">Jumlah Keseluruhan Penyata Bank (RM)</label>' +
                                '<input type="text" class="form-control form-control-sm bg-light text-end fw-semibold akaun-jumlah" readonly tabindex="-1">' +
                            '</div>' +
                            '<div class="d-flex align-items-center gap-3">' +
                                '<label class="fw-semibold text-muted mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:200px;">Purata Penyata Bank (RM)</label>' +
                                '<input type="text" class="form-control form-control-sm bg-light text-end fw-semibold akaun-purata" readonly tabindex="-1">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                        '<label class="form-label fw-semibold small mb-2">Dokumen Sokongan <span class="fw-normal text-muted">(untuk rujukan Pemilik Projek)</span></label>' +
                        (VIEW_ONLY
                            ? '<div class="file-chip-list akaun-file-list"></div>'
                            : '<label class="upload-zone w-100 akaun-upload-zone">' +
                                '<div class="upload-zone-icon">' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' +
                                    '<polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line>' +
                                    '<path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg>' +
                                '</div>' +
                                '<span class="upload-zone-label">Klik disini untuk memuat naik</span>' +
                                '<input type="file" class="akaun-file-input" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">' +
                              '</label>' +
                              '<div class="file-chip-list akaun-file-list"></div>') +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        $('#penyata-akaun-container').append($block);
        updateAkaunHingga($block);
        if (data.bulans && data.bulans.length) {
            setTimeout(function () {
                data.bulans.forEach(function (b) {
                    var $inp = $block.find('.penyata-bank-bulan-input[data-bulan="' + b.bulan + '"][data-tahun="' + b.tahun + '"]');
                    if ($inp.length && b.jumlah > 0) $inp.val(formatRm(b.jumlah));
                });
                updateAkaunTotals($block);
            }, 50);
        }
        if (data.jumlah_keseluruhan) $block.find('.akaun-jumlah').val(formatRm(data.jumlah_keseluruhan));
        if (data.purata) $block.find('.akaun-purata').val(formatRm(data.purata));
        if (data.files && data.files.length) {
            data.files.forEach(function (f) {
                $block.find('.akaun-file-list').append(buildFileChip(f.uuid, f.original_name, f.size, $block));
            });
        }
        return $block;
    }

    function updateAkaunHingga($block) {
        var dm = parseInt($block.find('.akaun-dari-bulan').val(), 10);
        var dy = parseInt($block.find('.akaun-dari-tahun').val(), 10);
        if (!dm || !dy) {
            $block.find('.akaun-hingga-bulan').val('');
            $block.find('.akaun-hingga-tahun').val('');
            rebuildAkaunBulanRows($block);
            return;
        }
        var h = bulanSelepas(dy, dm, 2);
        $block.find('.akaun-hingga-bulan').val(bulanLabel(h.m));
        $block.find('.akaun-hingga-tahun').val(h.y);
        rebuildAkaunBulanRows($block, dm, dy, h.m, h.y);
    }

    function rebuildAkaunBulanRows($block, dm, dy, hm, hy) {
        if (dm == null) dm = parseInt($block.find('.akaun-dari-bulan').val(), 10);
        if (dy == null) dy = parseInt($block.find('.akaun-dari-tahun').val(), 10);
        if (hm == null) {
            var hTxt = $block.find('.akaun-hingga-bulan').val();
            hm = BULAN_MS.indexOf(hTxt);
            if (hm < 0) hm = parseInt(hTxt, 10);
        }
        if (hy == null) hy = parseInt($block.find('.akaun-hingga-tahun').val(), 10);

        var $wrap = $block.find('.akaun-bulan-rows');
        var existing = {};
        $wrap.find('.penyata-bank-bulan-input').each(function () {
            existing[$(this).data('bulan') + '-' + $(this).data('tahun')] = $(this).val();
        });
        $wrap.empty();

        if (!dm || !dy || !hm || !hy) {
            updateAkaunTotals($block);
            return;
        }

        var start = new Date(dy, dm - 1, 1);
        var end   = new Date(hy, hm - 1, 1);
        if (start > end) { updateAkaunTotals($block); return; }

        var cur = new Date(start.getTime());
        while (cur <= end) {
            var m = cur.getMonth() + 1;
            var y = cur.getFullYear();
            var key = m + '-' + y;
            var val = existing[key] || '';
            $wrap.append(
                '<div class="d-flex align-items-center gap-3 mb-2 penyata-bank-bulan-item">' +
                    '<label class="fw-semibold text-dark mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:200px;">Penyata Bank Bulan ' + bulanLabel(m) + ' (RM)</label>' +
                    '<input type="text" class="form-control form-control-sm text-end amount-input penyata-bank-bulan-input" value="' + val + '" data-bulan="' + m + '" data-tahun="' + y + '" placeholder="0.00"' + (VIEW_ONLY ? ' readonly' : '') + '>' +
                '</div>'
            );
            cur.setMonth(cur.getMonth() + 1);
        }
        updateAkaunTotals($block);
    }

    function updateAkaunTotals($block) {
        var $inputs = $block.find('.penyata-bank-bulan-input');
        var sum = 0;
        $inputs.each(function () { sum += parseRm($(this).val()); });
        var avg = $inputs.length > 0 ? sum / $inputs.length : 0;
        $block.find('.akaun-jumlah').val(formatRm(sum));
        $block.find('.akaun-purata').val(formatRm(avg));
        updateGrandTotal();
    }

    function updateGrandTotal() {
        var grand = 0;
        $('#penyata-akaun-container .penyata-akaun-block').each(function () {
            grand += parseRm($(this).find('.akaun-jumlah').val());
        });
        $('#penyata-grand-total').val(formatRm(grand));
    }

    function renumberAkaun() {
        $('#penyata-akaun-container .penyata-akaun-block').each(function (i) {
            $(this).attr('data-akaun-index', i);
            $(this).find('.akaun-heading').text('Akaun ' + (i + 1));
            $(this).find('.btn-hapus-akaun').toggle(i > 0 && !VIEW_ONLY);
        });
        akaunCounter = $('#penyata-akaun-container .penyata-akaun-block').length;
        updateGrandTotal();
    }

    function initVendorForm() {
        var accounts = [];
        if (penyataData) {
            if (penyataData.accounts && penyataData.accounts.length) {
                accounts = penyataData.accounts;
            } else if (penyataData.dari_bulan || (penyataData.bulans && penyataData.bulans.length)) {
                accounts = [{
                    dari_bulan: penyataData.dari_bulan,
                    dari_tahun: penyataData.dari_tahun,
                    hingga_bulan: penyataData.hingga_bulan,
                    hingga_tahun: penyataData.hingga_tahun,
                    bulans: penyataData.bulans || [],
                    jumlah_keseluruhan: penyataData.jumlah_keseluruhan,
                    purata: penyataData.purata,
                    files: penyataData.files || [],
                }];
            }
        }
        if (!accounts.length) accounts = [{}, {}];
        accounts.forEach(function (acc, i) { buildAkaunBlock(i, acc); });
        akaunCounter = accounts.length;
        updateGrandTotal();
    }

    if (SHOW_VENDOR) {
        initVendorForm();

        $('#btn-tambah-akaun').on('click', function () {
            buildAkaunBlock(akaunCounter, {});
            akaunCounter++;
            renumberAkaun();
        });

        $('#penyata-akaun-container').on('click', '.btn-hapus-akaun', function () {
            if ($('#penyata-akaun-container .penyata-akaun-block').length <= 1) return;
            $(this).closest('.penyata-akaun-block').remove();
            renumberAkaun();
        });

        $('#penyata-akaun-container').on('change', '.akaun-dari-bulan, .akaun-dari-tahun', function () {
            updateAkaunHingga($(this).closest('.penyata-akaun-block'));
        });

        $('#penyata-akaun-container').on('input change', '.penyata-bank-bulan-input', function () {
            updateAkaunTotals($(this).closest('.penyata-akaun-block'));
        });

        // File upload per account
        function formatBytes(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function buildFileChip(fileUuid, fileName, fileSize, $block) {
            var ext = fileName.split('.').pop().toLowerCase();
            var safeName = $('<span>').text(fileName).html();
            var $chip = $(
                '<div class="file-chip" data-file-uuid="' + (fileUuid || '') + '">' +
                    '<span class="file-chip-ext ext-' + ext + '">' + ext + '</span>' +
                    '<div class="file-chip-body">' +
                        '<span class="file-chip-name" title="' + safeName + '">' + safeName + '</span>' +
                        '<span class="file-chip-size">' + (fileSize ? formatBytes(fileSize) : '—') + '</span>' +
                    '</div>' +
                    (VIEW_ONLY ? '' :
                        '<button type="button" class="file-chip-remove" title="Buang fail">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' +
                            '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                        '</button>') +
                '</div>'
            );
            if (!VIEW_ONLY) {
                $chip.find('.file-chip-remove').on('click', function () {
                    var uuid = $chip.data('file-uuid');
                    if (uuid) {
                        $.ajax({ url: DELETE_FILE_BASE + '/' + uuid, method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
                         .always(function () { $chip.remove(); });
                    } else {
                        $chip.remove();
                    }
                });
            }
            return $chip;
        }

        function uploadFileForAkaun(file, $block) {
            var akaunIndex = $block.data('akaun-index');
            var $chip = buildFileChip(null, file.name, file.size, $block);
            $chip.find('.file-chip-size').text('Memuat naik...');
            $block.find('.akaun-file-list').append($chip);

            var fd = new FormData();
            fd.append('file', file);
            fd.append('account_index', akaunIndex);
            $.ajax({
                url: UPLOAD_URL, method: 'POST', data: fd,
                processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            }).done(function (res) {
                if (res && res.success) {
                    $chip.attr('data-file-uuid', res.file.uuid).data('file-uuid', res.file.uuid);
                    $chip.find('.file-chip-size').text(formatBytes(res.file.size || file.size));
                } else {
                    $chip.remove();
                    alert('Fail tidak berjaya dimuat naik.');
                }
            }).fail(function () {
                $chip.remove();
                alert('Ralat semasa muat naik fail. Sila cuba lagi.');
            });
        }

        $('#penyata-akaun-container').on('change', '.akaun-file-input', function () {
            var $block = $(this).closest('.penyata-akaun-block');
            if (!this.files || !this.files.length) return;
            var arr = [];
            for (var i = 0; i < this.files.length; i++) arr.push(this.files[i]);
            $(this).val('');
            arr.forEach(function (f) { uploadFileForAkaun(f, $block); });
        });
    }

    // ── Purata scoring table (PTJ) ────────────────────────────────────────────
    var DELETE_ROW_BTN =
        '<button type="button" class="btn btn-sm btn-hapus-row d-inline-flex align-items-center justify-content-center p-0" ' +
        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang baris">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
        '</button>';

    function buildPurataRow(bil) {
        return $('<tr class="purata-row">' +
            '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
            '<td><input type="text" name="purata_dari[]" class="form-control form-control-sm amount-input" placeholder="0.00"></td>' +
            '<td><input type="text" name="purata_hingga[]" class="form-control form-control-sm amount-input" placeholder="0.00"></td>' +
            '<td><input type="text" name="purata_skema[]" class="form-control form-control-sm" placeholder="0"></td>' +
            '<td class="text-center">' + DELETE_ROW_BTN + '</td>' +
        '</tr>');
    }

    function reNumberPurata() {
        $('#tbl-purata-body .purata-row').each(function (i) { $(this).find('.row-bil').text(i + 1); });
    }

    if (SHOW_SCORING) {
        $('.btn-tambah-purata').on('click', function () {
            var bil     = $('#tbl-purata-body .purata-row').length + 1;
            var $newRow = buildPurataRow(bil);
            var $last   = $('#tbl-purata-body tr.purata-row').last();
            if ($last.length) {
                var lastH = parseRm($last.find('td:eq(2) input').val());
                if (lastH > 0) $newRow.find('td:eq(1) input').val(formatRm(lastH + 1));
            }
            $('#tbl-purata-body').append($newRow);
        });

        $('#tbl-purata-body').on('click', '.btn-hapus-row', function () {
            if ($('#tbl-purata-body .purata-row').length <= 1) return;
            $(this).closest('tr').remove();
            reNumberPurata();
        });

        $('#tbl-purata-body').on('blur', 'tr.purata-row .amount-input', function () {
            var $input = $(this), $td = $input.closest('td'), $row = $td.closest('tr'), col = $td.index();
            if (col === 1) {
                var $prev = $row.prev('tr.purata-row');
                if ($prev.length) {
                    var prevH = parseRm($prev.find('td:eq(2) input').val());
                    var dari  = parseRm($input.val());
                    $input.toggleClass('is-invalid', prevH > 0 && dari > 0 && dari <= prevH);
                }
            } else if (col === 2) {
                var $next = $row.next('tr.purata-row');
                if ($next.length) {
                    var h = parseRm($input.val()), $nd = $next.find('td:eq(1) input'), nd = parseRm($nd.val());
                    $nd.toggleClass('is-invalid', h > 0 && nd > 0 && nd <= h);
                }
                var d = parseRm($row.find('td:eq(1) input').val()), hi = parseRm($input.val());
                $input.toggleClass('is-invalid', d > 0 && hi > 0 && hi < d);
            }
        });

        $('.jenis-skor-select').on('change', function () {
            var $panel = $($(this).data('target'));
            $(this).val() === 'manual' ? $panel.removeClass('d-none') : $panel.addClass('d-none');
        });

        if (penyataData && penyataData.jenis_skor_purata) {
            $('[name="jenis_skor_purata"]').val(penyataData.jenis_skor_purata).trigger('change');
            if (penyataData.jenis_skor_purata === 'manual' && penyataData.scoring_items && penyataData.scoring_items.length) {
                $('#tbl-purata-body').empty();
                penyataData.scoring_items.forEach(function (s, i) {
                    var $row = buildPurataRow(i + 1);
                    $row.find('[name="purata_dari[]"]').val(s.dari > 0 ? formatRm(s.dari) : '');
                    $row.find('[name="purata_hingga[]"]').val(s.hingga != null ? formatRm(s.hingga) : '');
                    $row.find('[name="purata_skema[]"]').val(s.skema || '');
                    $('#tbl-purata-body').append($row);
                });
            }
        }

        if (!$('#tbl-purata-body .purata-row').length) {
            $('#tbl-purata-body').append(buildPurataRow(1));
        }
    }

    // ── Amount input formatting ───────────────────────────────────────────────
    $(document).on('focus',  '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        $(this).val(parseFloat(raw) === 0 ? '' : raw);
    });
    $(document).on('blur',   '.amount-input', function () {
        var val = $(this).val();
        if (val !== '') $(this).val(formatRm(parseRm(val)));
    });
    $(document).on('input',  '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    // ── Loading overlay ───────────────────────────────────────────────────────
    function blockUI(msg) {
        $('#loading-overlay').removeClass('success');
        $('#loading-text').text(msg || 'Menyimpan...');
        $('#loading-overlay').addClass('active');
    }
    function unblockUI() {
        $('#loading-overlay').removeClass('active success');
    }

    function hinggaNumeric($block) {
        var hTxt = $block.find('.akaun-hingga-bulan').val();
        var hm = BULAN_MS.indexOf(hTxt);
        if (hm < 0) hm = parseInt(hTxt, 10);
        return {
            bulan: hm,
            tahun: parseInt($block.find('.akaun-hingga-tahun').val(), 10) || null,
        };
    }

    function buildPayload() {
        var payload = {};

        if (SHOW_VENDOR) {
            var accounts = [];
            $('#penyata-akaun-container .penyata-akaun-block').each(function () {
                var $block = $(this);
                var hingga = hinggaNumeric($block);
                var bulans = [];
                $block.find('.penyata-bank-bulan-input').each(function () {
                    bulans.push({
                        bulan:  parseInt($(this).data('bulan'), 10),
                        tahun:  parseInt($(this).data('tahun'), 10),
                        jumlah: parseRm($(this).val()),
                    });
                });
                accounts.push({
                    dari_bulan:         parseInt($block.find('.akaun-dari-bulan').val(), 10) || null,
                    dari_tahun:         parseInt($block.find('.akaun-dari-tahun').val(), 10) || null,
                    hingga_bulan:       hingga.bulan,
                    hingga_tahun:       hingga.tahun,
                    bulans:             bulans,
                    jumlah_keseluruhan: parseRm($block.find('.akaun-jumlah').val()),
                    purata:             parseRm($block.find('.akaun-purata').val()),
                });
            });
            payload.accounts = accounts;
            payload.jumlah_keseluruhan_grand = parseRm($('#penyata-grand-total').val());

            if (accounts.length) {
                var first = accounts[0];
                payload.dari_bulan = first.dari_bulan;
                payload.dari_tahun = first.dari_tahun;
                payload.hingga_bulan = first.hingga_bulan;
                payload.hingga_tahun = first.hingga_tahun;
                payload.bulans = first.bulans;
                payload.jumlah_keseluruhan = first.jumlah_keseluruhan;
                payload.purata = first.purata;
            }
        }

        if (SHOW_SCORING) {
            var jenisSkor    = $('[name="jenis_skor_purata"]').val() || null;
            var scoringItems = [];
            if (jenisSkor === 'manual') {
                $('#tbl-purata-body .purata-row').each(function () {
                    var hinggaVal = $(this).find('[name="purata_hingga[]"]').val();
                    scoringItems.push({
                        dari:   parseRm($(this).find('[name="purata_dari[]"]').val()),
                        hingga: hinggaVal !== '' ? parseRm(hinggaVal) : null,
                        skema:  $(this).find('[name="purata_skema[]"]').val() || null,
                    });
                });
            }
            payload.jenis_skor_purata = jenisSkor;
            payload.scoring_items = scoringItems;
        }

        return payload;
    }

    // ── Save ─────────────────────────────────────────────────────────────────
    $('#btn-simpan').on('click', function () {
        blockUI('Menyimpan...');
        $.ajax({
            url:         STORE_URL,
            method:      'POST',
            contentType: 'application/json',
            data:        JSON.stringify(buildPayload()),
            headers:     { 'X-CSRF-TOKEN': CSRF_TOKEN },
        })
        .done(function (res) {
            if (res && res.success) {
                $('#loading-text').text('Berjaya disimpan! Mengalih...');
                $('#loading-overlay').addClass('success');
                if (typeof vendorFormNavigate === 'function') {
                    vendorFormNavigate(REDIRECT_URL);
                } else {
                    window.location.href = REDIRECT_URL;
                }
            } else {
                unblockUI();
                alert(res.message || 'Ralat semasa menyimpan.');
            }
        })
        .fail(function () {
            unblockUI();
            alert('Ralat semasa menyimpan. Sila cuba lagi.');
        });
    });

});
</script>
@endsection
