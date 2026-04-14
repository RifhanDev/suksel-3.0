@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Lembaran Imbangan</h3>
            <p class="text-muted small m-0">Isi maklumat kewangan dan kemudahan kredit syarikat petender.</p>
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
                    PROJEK MENAIKTARAF JALAN PELABUHAN UTARA DARI KLANG CONTAINER TERMINAL
                    <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">(Kerja)</span>
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

    <form id="form-lembaran-imbangan" action="#" method="POST">
    @csrf

    <!-- ===================== SECTION 1: LEMBARAN IMBANGAN ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20h10M6 6l6-1l6 1m-6-3v17m-3-8L6 6l-3 6a3 3 0 0 0 6 0m12 0l-3-6l-3 6a3 3 0 0 0 6 0"/></svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Lembaran Imbangan</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Maklumat kewangan syarikat petender</p>
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
                Perlu diisi oleh Petender
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Aset Tetap (RM)</label>
                    <input type="text" name="aset_tetap" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Aset Semasa (RM)</label>
                    <input type="text" name="aset_semasa" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Liabiliti Semasa (RM)</label>
                    <input type="text" name="liabiliti_semasa" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Long Term / Liabiliti Tetap (RM)</label>
                    <input type="text" name="liabiliti_tetap" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Wang Tunai Dalam Tangan (RM)</label>
                    <input type="text" name="wang_tunai" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                </div>
            </div>

        </div>
    </div>

    <!-- ===================== SECTION 2: BORANG CA / SURAT BANK ===================== -->
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
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Borang CA / Surat Bank</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Maklumat kemudahan kredit syarikat</p>
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
                Perlu diisi oleh Petender
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold small">Baki Kemudahan Kredit (RM)</label>
                    <input type="text" name="baki_kemudahan_kredit" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
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
<script>
$(document).ready(function () {

    function formatAmount(value) {
        var num = parseFloat(value.replace(/,/g, '')) || 0;
        return num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    $(document).on('focus', '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $(document).on('blur', '.amount-input', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatAmount(val));
    });

    $(document).on('input', '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    $('#form-lembaran-imbangan').on('submit', function () {
        $(this).find('.amount-input').each(function () {
            $(this).val($(this).val().replace(/,/g, ''));
        });
    });

});
</script>
@endsection

