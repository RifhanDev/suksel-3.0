@extends('layouts.v3.master')

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
    </style>
@endsection

@section('content')

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

    <form id="form-penyata-bank" action="{{ route('jawatankuasa.hantarPenyataKewangan') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- ===================== SECTION 1: MAKLUMAT PENYATA BANK ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Maklumat Penyata Bank</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Penyata bank 3 bulan terkini</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Info note -->
            <div class="rounded-2 px-3 py-2 mb-4 d-inline-flex align-items-center gap-2"
                style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                Sila pilih bulan pertama penyata bank yang perlu dikemukakan oleh petender
            </div>

            <!-- Dari / Hingga selectors -->
            @php
                $penyataBankDari = now()->subMonths(2);
                $penyataBankHingga = $penyataBankDari->copy()->addMonths(2);
            @endphp

            <div class="row g-3 mb-4">
                <!-- Dari -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Dari (Bulan)</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <select name="penyata_dari_bulan" id="penyata_dari_bulan" class="form-select form-select-sm">
                                <option value="">Pilih Bulan</option>
                                @for ($mf = 1; $mf <= 12; $mf++)
                                    <option value="{{ $mf }}" @selected((int) $mf === (int) $penyataBankDari->month)>{{ $mf }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="penyata_dari_tahun" id="penyata_dari_tahun" class="form-select form-select-sm">
                                <option value="">Pilih Tahun</option>
                                @foreach (range(now()->year - 10, now()->year) as $yf)
                                    <option value="{{ $yf }}" @selected((int) $yf === (int) $penyataBankDari->year)>{{ $yf }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Hingga -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Hingga (Bulan)</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm bg-light" id="penyata_hingga_bulan_display" name="penyata_hingga_bulan" value="{{ $penyataBankHingga->month }}" readonly disabled tabindex="-1">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm bg-light" id="penyata_hingga_tahun_display" name="penyata_hingga_tahun" value="{{ $penyataBankHingga->year }}" readonly disabled tabindex="-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <div class="border-top pt-4 mb-3">
                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2"
                    style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Perlu diisi oleh petender
                </div>
            </div>

            <!-- Monthly inputs + Upload side by side -->
            <div class="row g-4 mb-4">
                <!-- LEFT: Dynamic monthly input rows -->
                <div class="col-12 col-md-6">

                    <label class="form-label fw-semibold small mb-2">Penyata Bank<span class="fw-normal text-muted"> (Untuk 3 bulan terakhir)</span></label>
                    
                    <div id="penyata_bank_bulan_rows_wrapper">
                        @php
                            $penyataBankBulanList = [];
                            $curBulan = $penyataBankDari->copy()->startOfMonth();
                            $akhirBulan = $penyataBankHingga->copy()->startOfMonth();
                            while ($curBulan->lte($akhirBulan)) {
                                $penyataBankBulanList[] = $curBulan->copy();
                                $curBulan->addMonth();
                            }
                        @endphp
                        @foreach ($penyataBankBulanList as $pbRow)
                        <div class="d-flex align-items-center gap-3 mb-2 penyata-bank-bulan-item" data-ym="{{ $pbRow->format('Y-m') }}">
                            <label class="fw-semibold text-dark mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:220px;">
                                Penyata Bank Bulan {{ $pbRow->month }} - {{ $pbRow->year }} (RM)
                            </label>
                            <input type="text" class="form-control form-control-sm text-end amount-input penyata-bank-bulan-input"
                                name="penyata_bulan_{{ $pbRow->format('Y_m') }}" value=""
                                data-bulan="{{ $pbRow->month }}" data-tahun="{{ $pbRow->year }}" placeholder="0.00">
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- RIGHT: Dokumen Sokongan upload -->
                <div class="col-12 col-md-6">
                    
                    <label class="form-label fw-semibold small mb-2">Dokumen Sokongan <span class="fw-normal text-muted">(Untuk rujukan pemilik projek)</span></label>

                    <label class="upload-zone w-100" id="upload-zone-penyata" for="input-dokumen-penyata">
                        <div class="upload-zone-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="16 16 12 12 8 16"></polyline>
                                <line x1="12" y1="12" x2="12" y2="21"></line>
                                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                            </svg>
                        </div>
                        <span class="upload-zone-label">Klik atau seret fail ke sini untuk muat naik</span>
                        <span class="upload-zone-sub">PDF, Word, Excel, Imej — saiz maksimum 10 MB setiap fail</span>
                        <input type="file" id="input-dokumen-penyata" name="dokumen_penyata_bank[]" multiple hidden
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                    </label>

                    <!-- Uploaded file chips -->
                    <div class="file-chip-list" id="file-chip-list-penyata"></div>
                </div>
            </div>

            <!-- Totals -->
            <div class="border-top pt-3">
                <div class="row g-3 mb-2">
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <label class="fw-semibold text-muted mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:220px;">Jumlah Keseluruhan (RM)</label>
                            <input type="text" class="form-control form-control-sm bg-light text-end fw-semibold" id="penyata_bank_jumlah_keseluruhan" name="jumlah_keseluruhan_penyata" value="" readonly tabindex="-1">
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <label class="fw-semibold text-muted mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:220px;">Purata Penyata Bank (RM)</label>
                            <input type="text" class="form-control form-control-sm bg-light text-end fw-semibold" id="penyata_bank_purata" name="purata_penyata_bank" value="" readonly tabindex="-1">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ===================== SECTION 2: PURATA PENYATA BANK (SKEMA) ===================== -->
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
                        <option value="automatik">Automatik</option>
                        <option value="manual" disabled>Manual</option>
                    </select>
                </div>
            </div>

            <!-- Automatik panel -->
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

    <!-- ===================== ACTION BUTTONS ===================== -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="{{ route('senaraiKewangan') }}" class="btn-form btn-form-secondary">
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

    // ══════════════════════════════════════════════════════════════════════════
    // PENYATA BANK — helpers
    // ══════════════════════════════════════════════════════════════════════════
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

    function updatePenyataBankTotals() {
        var $wrap = $('#penyata_bank_bulan_rows_wrapper');
        var $inputs = $wrap.find('.penyata-bank-bulan-input');
        var sum = 0;
        $inputs.each(function () { sum += parseRm($(this).val()); });
        var avg = $inputs.length > 0 ? sum / $inputs.length : 0;
        $('#penyata_bank_jumlah_keseluruhan').val(formatRm(sum));
        $('#penyata_bank_purata').val(formatRm(avg));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // DARI / HINGGA — auto-sync Hingga = Dari + 2 months, rebuild month rows
    // ══════════════════════════════════════════════════════════════════════════
    function bulanSelepas(dy, dm, n) {
        var d = new Date(dy, dm - 1 + n, 1);
        return { y: d.getFullYear(), m: d.getMonth() + 1 };
    }

    function rebuildBulanRows() {
        var dm = parseInt($('#penyata_dari_bulan').val(), 10);
        var dy = parseInt($('#penyata_dari_tahun').val(), 10);
        var hm = parseInt($('#penyata_hingga_bulan_display').val(), 10);
        var hy = parseInt($('#penyata_hingga_tahun_display').val(), 10);
        var $wrap = $('#penyata_bank_bulan_rows_wrapper');
        $wrap.empty();

        if (!dm || !dy || !hm || !hy || dm < 1 || dm > 12 || hm < 1 || hm > 12) {
            updatePenyataBankTotals();
            return;
        }

        var start = new Date(dy, dm - 1, 1);
        var end = new Date(hy, hm - 1, 1);
        if (start > end) { updatePenyataBankTotals(); return; }

        var cur = new Date(start.getTime());
        while (cur <= end) {
            var m = cur.getMonth() + 1;
            var y = cur.getFullYear();
            var ym = y + '_' + String(m).padStart(2, '0');
            var html =
                '<div class="d-flex align-items-center gap-3 mb-2 penyata-bank-bulan-item" data-ym="' + y + '-' + String(m).padStart(2, '0') + '">' +
                    '<label class="fw-semibold text-dark mb-0 flex-shrink-0" style="font-size:0.82rem; min-width:220px;">Penyata Bank Bulan ' + m + ' - ' + y + ' (RM)</label>' +
                    '<input type="text" class="form-control form-control-sm text-end amount-input penyata-bank-bulan-input" name="penyata_bulan_' + ym + '" value="" data-bulan="' + m + '" data-tahun="' + y + '" placeholder="0.00">' +
                '</div>';
            $wrap.append(html);
            cur.setMonth(cur.getMonth() + 1);
        }
        updatePenyataBankTotals();
    }

    function updateHingga() {
        var dm = parseInt($('#penyata_dari_bulan').val(), 10);
        var dy = parseInt($('#penyata_dari_tahun').val(), 10);
        if (!dm || !dy || dm < 1 || dm > 12) {
            $('#penyata_hingga_bulan_display').val('');
            $('#penyata_hingga_tahun_display').val('');
            rebuildBulanRows();
            return;
        }
        var h = bulanSelepas(dy, dm, 2);
        $('#penyata_hingga_bulan_display').val(h.m);
        $('#penyata_hingga_tahun_display').val(h.y);
        rebuildBulanRows();
    }

    $('#penyata_dari_bulan, #penyata_dari_tahun').on('change', updateHingga);

    // Listen for input on dynamic bulan rows
    $('#penyata_bank_bulan_rows_wrapper').on('input change', '.penyata-bank-bulan-input', updatePenyataBankTotals);

    // Initial calc
    updatePenyataBankTotals();

    // ══════════════════════════════════════════════════════════════════════════
    // PURATA PENYATA BANK — Dari/Hingga/Skema table
    // ══════════════════════════════════════════════════════════════════════════
    var DELETE_BTN =
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
            '<td class="text-center">' + DELETE_BTN + '</td>' +
        '</tr>');
    }

    // Seed first row
    $('#tbl-purata-body').append(buildPurataRow(1));

    function reNumberPurata() {
        $('#tbl-purata-body .purata-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    $('.btn-tambah-purata').on('click', function () {
        var bil = $('#tbl-purata-body .purata-row').length + 1;
        var $newRow = buildPurataRow(bil);

        // Auto-fill Dari = previous row's Hingga + 1
        var $lastRow = $('#tbl-purata-body tr.purata-row').last();
        if ($lastRow.length) {
            var lastHingga = parseRm($lastRow.find('td:eq(2) input').val());
            if (lastHingga > 0) {
                $newRow.find('td:eq(1) input').val(formatRm(lastHingga + 1));
            }
        }

        $('#tbl-purata-body').append($newRow);
    });

    $('#tbl-purata-body').on('click', '.btn-hapus-row', function () {
        if ($('#tbl-purata-body .purata-row').length <= 1) return;
        $(this).closest('tr').remove();
        reNumberPurata();
    });

    // Real-time range validation:
    // - Dari must be > previous row's Hingga
    // - Hingga change re-validates next row's Dari
    $('#tbl-purata-body').on('blur', 'tr.purata-row .amount-input', function () {
        var $input = $(this);
        var $td = $input.closest('td');
        var $row = $td.closest('tr');
        var colIdx = $td.index(); // 1=Dari, 2=Hingga

        if (colIdx === 1) {
            // Dari changed — must be > previous row's Hingga
            var $prevRow = $row.prev('tr.purata-row');
            if ($prevRow.length) {
                var prevHingga = parseRm($prevRow.find('td:eq(2) input').val());
                var dari = parseRm($input.val());
                $input.toggleClass('is-invalid', prevHingga > 0 && dari > 0 && dari <= prevHingga);
            }
        } else if (colIdx === 2) {
            // Hingga changed — re-validate next row's Dari
            var $nextRow = $row.next('tr.purata-row');
            if ($nextRow.length) {
                var hingga = parseRm($input.val());
                var $nextDari = $nextRow.find('td:eq(1) input');
                var nextDari = parseRm($nextDari.val());
                $nextDari.toggleClass('is-invalid', hingga > 0 && nextDari > 0 && nextDari <= hingga);
            }

            // Also validate Hingga >= Dari on same row
            var dariSame = parseRm($row.find('td:eq(1) input').val());
            var hinggaSame = parseRm($input.val());
            $input.toggleClass('is-invalid', dariSame > 0 && hinggaSame > 0 && hinggaSame < dariSame);
        }
    });

    // ══════════════════════════════════════════════════════════════════════════
    // JENIS SKOR — toggle automatik table panel
    // ══════════════════════════════════════════════════════════════════════════
    $('.jenis-skor-select').on('change', function () {
        var $panel = $($(this).data('target'));
        if ($(this).val() === 'automatik') {
            $panel.removeClass('d-none');
        } else {
            $panel.addClass('d-none');
        }
    });

    // ══════════════════════════════════════════════════════════════════════════
    // AMOUNT INPUT — auto comma formatting
    // ══════════════════════════════════════════════════════════════════════════
    $(document).on('focus', '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $(document).on('blur', '.amount-input', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatRm(parseRm(val)));
    });

    $(document).on('input', '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    // ══════════════════════════════════════════════════════════════════════════
    // DOKUMEN SOKONGAN — use file-upload.js
    // ══════════════════════════════════════════════════════════════════════════
    FileUpload.init({
        zoneId     : 'upload-zone-penyata',
        inputId    : 'input-dokumen-penyata',
        chipListId : 'file-chip-list-penyata'
    });

    // ══════════════════════════════════════════════════════════════════════════
    // FORM SUBMIT — strip commas from amount fields
    // ══════════════════════════════════════════════════════════════════════════
    $('#form-penyata-bank').on('submit', function () {
        $(this).find('.amount-input').each(function () {
            $(this).val($(this).val().replace(/,/g, ''));
        });
    });

});
</script>
@endsection
