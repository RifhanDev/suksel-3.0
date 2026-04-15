@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Saham atau Bon</h3>
            <p class="text-muted small m-0">Isi maklumat saham atau bon syarikat petender.</p>
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
                    KERJA-KERJA MENAIK TARAF SUNGAI BATU DAN KAWASAN SEKITAR, SELANGOR DARUL EHSAN
                </h5>
            </div>

            <!-- Metadata -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">T/2026/015</span>
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

    <form id="form-bon-saham" action="{{ route('jawatankuasa.hantarBonSaham') }}" method="POST">
    @csrf

        <!-- ===================== SECTION: SAHAM ATAU BON ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Saham atau Bon</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Maklumat akaun deposit tetap / saham / bon</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Akaun rows container -->
                <div id="akaun-list">
                    <!-- Akaun 1 -->
                    <div class="akaun-item mb-3 p-3 rounded-2" style="border:1px solid #e2e8f0;" data-akaun="1">
                        <span class="fw-bold text-dark d-block mb-3" style="font-size:0.9rem;">Akaun 1</span>

                        <!-- Info note -->
                        <div class="rounded-2 px-3 py-2 mb-3 d-inline-flex align-items-center gap-2"
                            style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            Perlu diisi oleh Petender
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold small">Bank / Institusi</label>
                                <select name="bank_institusi[]" class="form-select form-select-sm">
                                    <option value="">— Sila pilih —</option>
                                    <option value="maybank">Maybank</option>
                                    <option value="cimb">CIMB Bank</option>
                                    <option value="publicbank">Public Bank</option>
                                    <option value="rhb">RHB Bank</option>
                                    <option value="hongleong">Hong Leong Bank</option>
                                    <option value="ambank">AmBank</option>
                                    <option value="bsn">Bank Simpanan Nasional (BSN)</option>
                                    <option value="bkr">Bank Kerjasama Rakyat</option>
                                    <option value="affin">Affin Bank</option>
                                    <option value="agro">Agrobank</option>
                                    <option value="lain">Lain-lain</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold small">Jumlah Deposit Tetap / Saham / Bon (RM)</label>
                                <input type="text" name="jumlah_deposit[]" class="form-control form-control-sm text-end amount-input jumlah-deposit" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tambah Akaun -->
                <button type="button" id="btn-tambah-akaun"
                    class="d-flex align-items-center justify-content-center gap-2 w-100 py-2 mb-4 rounded-2 fw-semibold small"
                    style="background:#f8fafc; border:1.5px dashed #cbd5e1; color:#64748b; cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Akaun
                </button>

                <!-- Jumlah Keseluruhan -->
                <div class="row">
                    <div class="col-12 col-md-6 ms-auto">
                        <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 border-top mt-2"
                            style="background:#f8fafc; border:1px solid #e2e8f0 !important;">
                            <span class="fw-semibold text-muted" style="font-size:0.82rem;">Jumlah Keseluruhan Deposit Tetap / Saham / Bon (RM)</span>
                            <span class="fw-bold text-dark ms-3 flex-shrink-0" id="jumlah-keseluruhan" style="font-size:1rem;">0.00</span>
                            <input type="hidden" name="jumlah_keseluruhan" id="jumlah-keseluruhan-input" value="0">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="{{ route('senaraiKewanganKerja') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="d-flex gap-2">
                {{-- <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button> --}}
                <button type="button" class="btn-form btn-form-success btn-simpan-bon">
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
<script>
$(document).ready(function () {

    var akaunCount = 1;

    // ── Bank options (reused when building new rows) ─────────────────────────
    var bankOptions =
        '<option value="">— Sila pilih —</option>' +
        '<option value="maybank">Maybank</option>' +
        '<option value="cimb">CIMB Bank</option>' +
        '<option value="publicbank">Public Bank</option>' +
        '<option value="rhb">RHB Bank</option>' +
        '<option value="hongleong">Hong Leong Bank</option>' +
        '<option value="ambank">AmBank</option>' +
        '<option value="bsn">Bank Simpanan Nasional (BSN)</option>' +
        '<option value="bkr">Bank Kerjasama Rakyat</option>' +
        '<option value="affin">Affin Bank</option>' +
        '<option value="agro">Agrobank</option>' +
        '<option value="lain">Lain-lain</option>';

    // ── Info note HTML ────────────────────────────────────────────────────────
    var infoNote =
        '<div class="rounded-2 px-3 py-2 mb-3 d-inline-flex align-items-center gap-2" ' +
        'style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af;">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" ' +
            'stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">' +
                '<circle cx="12" cy="12" r="10"></circle>' +
                '<line x1="12" y1="16" x2="12" y2="12"></line>' +
                '<line x1="12" y1="8" x2="12.01" y2="8"></line>' +
            '</svg>' +
            'Perlu diisi oleh Petender' +
        '</div>';

    // ── Build new akaun block ─────────────────────────────────────────────────
    function buildAkaun(num) {
        return $(
            '<div class="akaun-item mb-3 p-3 rounded-2" style="border:1px solid #e2e8f0;" data-akaun="' + num + '">' +
                '<div class="d-flex align-items-center justify-content-between mb-3">' +
                    '<span class="fw-bold text-dark" style="font-size:0.9rem;">Akaun ' + num + '</span>' +
                    '<button type="button" class="btn btn-sm btn-hapus-akaun d-inline-flex align-items-center gap-1" ' +
                    'style="background:#fee2e2;color:#ef4444;border:none;border-radius:6px;padding:4px 10px;font-size:0.78rem;">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
                        'stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                            '<polyline points="3 6 5 6 21 6"></polyline>' +
                            '<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>' +
                            '<path d="M10 11v6"></path><path d="M14 11v6"></path>' +
                            '<path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>' +
                        '</svg>' +
                        'Buang' +
                    '</button>' +
                '</div>' +
                infoNote +
                '<div class="row g-3">' +
                    '<div class="col-12 col-md-6">' +
                        '<label class="form-label fw-semibold small">Bank / Institusi</label>' +
                        '<select name="bank_institusi[]" class="form-select form-select-sm">' + bankOptions + '</select>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                        '<label class="form-label fw-semibold small">Jumlah Deposit Tetap / Saham / Bon (RM)</label>' +
                        '<input type="text" name="jumlah_deposit[]" class="form-control form-control-sm text-end amount-input jumlah-deposit" placeholder="0.00">' +
                    '</div>' +
                '</div>' +
            '</div>'
        );
    }

    // ── Re-number all akaun titles ────────────────────────────────────────────
    function reNumberAkaun() {
        $('#akaun-list .akaun-item').each(function (i) {
            $(this).attr('data-akaun', i + 1);
            $(this).find('span.fw-bold').first().text('Akaun ' + (i + 1));
        });
        akaunCount = $('#akaun-list .akaun-item').length;
    }

    // ── Tambah Akaun ──────────────────────────────────────────────────────────
    $('#btn-tambah-akaun').on('click', function () {
        akaunCount++;
        var $newAkaun = buildAkaun(akaunCount);
        $('#akaun-list').append($newAkaun);
    });

    // ── Hapus Akaun (delegated) ───────────────────────────────────────────────
    $('#akaun-list').on('click', '.btn-hapus-akaun', function () {
        $(this).closest('.akaun-item').remove();
        reNumberAkaun();
        updateJumlah();
    });

    // ── Jumlah Keseluruhan ────────────────────────────────────────────────────
    function parseAmount(val) {
        return parseFloat(String(val).replace(/,/g, '')) || 0;
    }

    function formatAmount(n) {
        return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateJumlah() {
        var total = 0;
        $('.jumlah-deposit').each(function () {
            total += parseAmount($(this).val());
        });
        $('#jumlah-keseluruhan').text(formatAmount(total));
        $('#jumlah-keseluruhan-input').val(total.toFixed(2));
    }

    $('#akaun-list').on('input change', '.jumlah-deposit', updateJumlah);

    // ── Amount input formatting ───────────────────────────────────────────────
    $(document).on('focus', '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $(document).on('blur', '.amount-input', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatAmount(parseAmount(val)));
        updateJumlah();
    });

    $(document).on('input', '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    // ── Form submit — strip commas ────────────────────────────────────────────
    // TODO: restore real submit after demo — currently intercepted for demo purposes
    // $('#form-bon-saham').on('submit', function () {
    //     $(this).find('.amount-input').each(function () {
    //         $(this).val($(this).val().replace(/,/g, ''));
    //     });
    // });

    // DEMO: Simpan button shows success modal instead of submitting
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    $('.btn-simpan-bon').on('click', function () {
        successModal.show();
    });

});
</script>
@endsection

@push('modals')
    <!-- ===================== MODAL: SUCCESS ===================== -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#E6F7F3" />
                        <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7" />
                    </svg>
                </div>
                <h5 class="fw-bold mb-2">Berjaya</h5>
                <p class="text-muted mb-4">Maklumat telah berjaya disimpan.</p>
                <button type="button" class="btn-form btn-form-primary mx-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endpush
