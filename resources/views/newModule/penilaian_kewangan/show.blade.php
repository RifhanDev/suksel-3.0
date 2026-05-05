@extends('layouts.v3.master')

@section('content')
{{-- Breadcrumb: back to SENARAI TENDER (first page) --}}
<nav aria-label="breadcrumb" class="py-2 mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">STOS</a></li>
        <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-decoration-none">Senarai Penilaian Kewangan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Penilaian Kewangan</li>
    </ol>
</nav>

<style>
    /* ========================
   GLOBAL
======================== */
    body {
        background: #F3F4F6;
    }

    .card {
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }

    .card-body {
        padding: 24px;
    }

    /* ========================
   HEADER SUMMARY
======================== */
    .card-body .row > .col-md-4 {
        border-right: 1px solid #E5E7EB;
    }

    .card-body .row > .col-md-4:last-child {
        border-right: none;
    }

    .card-body b {
        font-size: 13px;
        color: #374151;
    }

    /* ========================
   STEPPER
======================== */
    :root {
        --sg-red: #C4161C;
        --sg-red-dark: #9F1216;
        --step-grey: #E5E7EB;
        --text-grey: #6B7280;
    }

    /* ========================
   STEPPER WRAPPER
======================== */
    .progress-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        margin: 20px 0;
        padding: 10px 20px;
    }

    /* ========================
   STEPPER ITEM
======================== */
    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    /* ========================
   CONNECTOR LINE
======================== */
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 22px;
        /* aligns with circle center */
        left: 50%;
        width: 100%;
        height: 3px;
        background: var(--step-grey);
        z-index: 0;
    }

    /* Active / completed connector */
    .progress-step.done:not(:last-child)::after,
    .progress-step.active:not(:last-child)::after {
        background: var(--sg-red);
    }

    /* Reset future connectors */
    .progress-step.active~.progress-step:not(:last-child)::after {
        background: var(--step-grey);
    }

    /* ========================
   STEP CIRCLE
======================== */
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--step-grey);
        color: #374151;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: none;
        position: relative;
        z-index: 2;
        /* ABOVE line */
        cursor: pointer;
        margin-top: 5px;
    }

    /* Active & done circle */
    .progress-step.active .step-number,
    .progress-step.done .step-number {
        background: var(--sg-red);
        color: #fff;
    }

    /* ========================
   STEP LABEL
======================== */
    .step-label {
        margin-top: 8px;
        font-size: 13px;
        color: var(--text-grey);
        line-height: 1.3;
    }

    /* Active & done label */
    .progress-step.active .step-label,
    .progress-step.done .step-label {
        color: var(--sg-red-dark);
        font-weight: 600;
    }

    /* ========================
   OPTIONAL UX ENHANCEMENTS
======================== */
    .progress-step:hover .step-number {
        transform: scale(1.05);
        transition: 0.2s;
    }

    .progress-step:hover .step-label {
        color: var(--sg-red-dark);
    }

    /* =========================
   SECTION TITLES
========================= */
    .card-title-grey {
        background: #F9FAFB;
        padding: 12px 16px;
        border-left: 5px solid #C0392B;
        font-weight: 700;
        font-size: 15px;
        border-radius: 6px;
    }

    /* ==========================
   SUB TABS 
========================== */
    .custom-tab-size .nav-link {
        border-radius: 8px 8px 0 0;
        background: #FFFFFF;
        color: #374151;
        border: 1px solid #E5E7EB;
        font-weight: 600;
        padding: 10px 18px;
    }

    .custom-tab-size .nav-link.active {
        background: #C0392B !important;
        color: #FFFFFF !important;
        border-color: #C0392B !important;
    }

    /* ==========================
   TABLE
========================== */
    .table {
        border-radius: 0px;
        overflow: hidden;
    }

    .table td {
        font-size: 13px;
        padding: 12px;
        vertical-align: middle;
    }

    /* ==========================
   BUTTONS
========================== */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 16px;
    }

    .btn-success {
        background: #16A34A;
        border: none;
    }

    .btn-success:hover {
        background: #15803D;
    }

    .btn-primary {
        background: #1E3A8A;
        border: none;
    }

    .btn-primary:hover {
        background: #1E40AF;
    }

    .btn-outline-secondary {
        border-radius: 8px;
    }

    /* ==========================
   LINKS
========================== */
    .text-primary,
    .text-primary:hover {
        color: #2563EB !important;
    }

    /* ==========================
   FORM
========================== */
    .form-control,
    .form-select {
        border-radius: 8px;
        font-size: 13px;
    }

    .form-check-label {
        font-size: 13px;
    }

    /* ==========================
   MODAL
========================== */
    .modal-content {
        border-radius: 14px;
    }

    .modal-header {
        background: #1E3A8A;
        color: white;
    }

    .modal-title {
        color: white;
        font-weight: 700;
    }

    .modal-semakan-kewangan .modal-header {
        background: #F3F4F6;
        color: #111827;
        border-bottom: 1px solid #E5E7EB;
    }

    .modal-semakan-kewangan .modal-title {
        color: #111827;
        font-size: 1rem;
    }

    .modal-semakan-kewangan .btn-close {
        filter: none;
        opacity: 0.6;
    }

    .modal-footer {
        border-top: 1px solid #E5E7EB;
    }

    /* Status Pematuhan dropdown: enough width, no overlap with chevron */
    #modalSemakanKetepatanDokumenKewangan select.form-select {
        min-width: 100%;
        width: 100%;
        padding-right: 2.25rem;
        box-sizing: border-box;
    }

    #modalSemakanKetepatanDokumenKewangan td:nth-child(3) {
        min-width: 200px;
    }

    #modalSemakanKetepatanDokumenKewangan td:nth-child(4) textarea {
        min-height: 72px;
        resize: vertical;
    }

    /* ==========================
   TEXT HINTS
========================== */
    .card-title-desc {
        font-size: 13px;
        margin-bottom: 12px;
    }

    /* ==========================
   ACTION FOOTER
========================== */
    .d-flex.justify-content-end.gap-2 button {
        min-width: 120px;
    }

    /* ==========================
   TABLE – RED THEME OVERRIDE
========================== */

    /* Table header */
    .table thead th,
    .table-primary thead th,
    .table-primary th {
        background-color: #C0392B !important;
        color: #FFFFFF !important;
        text-align: center;
        font-size: 13px;
        padding: 12px;
        border-color: #A93226 !important;
    }

    /* Table header row */
    .table thead tr {
        background-color: #C0392B !important;
    }

    /* Table borders */
    .table-bordered> :not(caption)>* {
        border-color: #E5B4AF;
    }

    /* Table hover */
    .table tbody tr:hover {
        background: #FDEDEC;
    }

    /* Badges inside table */
    .table .badge.bg-success {
        background: #16A34A !important;
    }

    .table .badge.bg-warning {
        background: #F59E0B !important;
    }

    /* Action buttons inside table */
    .table .btn-success {
        background: #16A34A;
    }

    .table .btn-primary {
        background: #C0392B;
        border: none;
    }

    .table .btn-primary:hover {
        background: #A93226;
    }

    /* Pencil / icon buttons */
    .table .btn-outline-secondary {
        border-color: #C0392B;
        color: #C0392B;
    }

    .table .btn-outline-secondary:hover {
        background: #C0392B;
        color: #fff;
    }

    .profil-readonly-form {
        display: grid;
        gap: 1rem;
    }

    .profil-readonly-section {
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        background: #F9FAFB;
        padding: 14px;
    }

    .profil-readonly-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
    }

    .profil-readonly-form .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6B7280;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .2px;
    }

    .profil-readonly-form .form-control,
    .profil-readonly-form .form-select,
    .profil-readonly-form textarea {
        background: #fff;
        color: #111827;
        border: 1px solid #D1D5DB;
        font-size: 0.85rem;
    }

    .profil-readonly-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .72rem;
        font-weight: 700;
        color: #065F46;
        background: #D1FAE5;
        border: 1px solid #A7F3D0;
        border-radius: 999px;
        padding: 2px 10px;
    }

    .profil-readonly-chip {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        background: #EEF2FF;
        color: #3730A3;
        border: 1px solid #C7D2FE;
        font-size: .75rem;
        font-weight: 600;
    }
</style>

<div class="col-12">
    <div class="card">
        <div class="card-body">
            {{-- Tender info strip (step 1 reference: No. Tender, Tempoh, PTJ, Tajuk, STATUS, Sah Laku Tamat) --}}
            <div class="row mb-2">
                <div class="col-md-4 border-end">
                    <b>No. Sebut Harga / Tender</b>
                    <div class="text-success">{{ $tender_no ?? 'Belum Dijana' }}</div>
                </div>
                <div class="col-md-4 border-end">
                    <b>Tempoh Sah Laku Tawaran (Hari)</b>
                    <div>90</div>
                </div>
                <div class="col-md-4">
                    <b>PTJ</b>
                    <div class="small">BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 border-end">
                    <b>Tajuk Perolehan</b>
                    <div class="small">Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</div>
                </div>
                <div class="col-md-4 border-end">
                    <b>STATUS</b>
                    <div>Menunggu Penilaian Kewangan</div>
                </div>
                <div class="col-md-4">
                    <b>Sah Laku Tawaran Tamat</b>
                    <div>17/01/2022</div>
                </div>
            </div>
            <div class="row">
                <div id="custom-progress-bar" class="progress-nav mb-4 p-2">

                    <ul class="nav progress-wrapper" role="tablist">

                        <li class="nav-item progress-step active" role="presentation">
                            <button type="button"
                                id="pematuhan-tab"
                                class="nav-link step-number active"
                                data-bs-toggle="pill"
                                data-bs-target="#pematuhan"
                                role="tab">1</button>
                            <div class="step-label">Pematuhan Dokumentasi</div>
                        </li>

                        <li class="nav-item progress-step" role="presentation">
                            <button type="button"
                                id="penyata-bank-tab"
                                class="nav-link step-number"
                                data-bs-toggle="pill"
                                data-bs-target="#penyata-bank"
                                role="tab">2</button>
                            <div class="step-label">Penyata Bulanan Bank</div>
                        </li>

                        <li class="nav-item progress-step" role="presentation">
                            <button type="button"
                                id="penilaian-tab"
                                class="nav-link step-number"
                                data-bs-toggle="pill"
                                data-bs-target="#penilaian"
                                role="tab">3</button>
                            <div class="step-label">Pematuhan Spesifikasi Kewangan</div>
                        </li>

                        <li class="nav-item progress-step" role="presentation">
                            <button type="button"
                                id="laporan-tab"
                                class="nav-link step-number"
                                data-bs-toggle="pill"
                                data-bs-target="#laporan"
                                role="tab">4</button>
                            <div class="step-label">Penyediaan Laporan</div>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="tab-content px-3" id="application-content">

                <!-- Outer Tab 1 Content -->
                <div class="tab-pane fade show active" id="pematuhan" role="tabpanel"
                    aria-labelledby="pematuhan-tab">

                    <!-- Inner tabs for outer tab 1 -->
                    <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-1" role="tab" aria-selected="true">Kewangan</a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#rumusan-1" role="tab" aria-selected="false">Rumusan</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="kewangan-1" role="tabpanel">
                            <h4 class="card-title card-title-grey">PEMATUHAN CADANGAN KEWANGAN</h4>
                            <p class="card-title-desc text-primary fst-italic">Klik butang Menilai untuk meneruskan penilaian.</p>
                            <table class="table table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">Tajuk / Dokumen</th>
                                        <th class="text-center">Mekanisma</th>
                                        <th class="text-center">Status Penilaian</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="me-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                            </a>
                                            Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX
                                        </td>
                                        <td>Spesifikasi</td>
                                        <td class="status-penilaian">Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                                data-dokumen="Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX">Menilai</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="me-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                            </a>
                                            Maklumat Profil Petender
                                        </td>
                                        <td>Borang Atas Talian</td>
                                        <td class="status-penilaian">Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                                data-dokumen="Maklumat Profil Petender">Menilai</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="me-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                            </a>
                                            Penyata Bank Terkini (3 Bulan Terakhir) Syarikat
                                        </td>
                                        <td>Borang Atas Talian</td>
                                        <td class="status-penilaian">Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                                data-dokumen="Penyata Bank Terkini (3 Bulan Terakhir) Syarikat">Menilai</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="me-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                            </a>
                                            Salinan Sijil Pendaftaran dengan Kementerian Kewangan
                                        </td>
                                        <td>Petender Muat Naik</td>
                                        <td class="status-penilaian">Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                                data-dokumen="Salinan Sijil Pendaftaran dengan Kementerian Kewangan">Menilai</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="me-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                            </a>
                                            Surat Akuan Pembida
                                        </td>
                                        <td>PTJ Muat Naik</td>
                                        <td class="status-penilaian">Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                                data-dokumen="Surat Akuan Pembida">Menilai</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row mb-3 px-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn btn-primary btn-seterusnya">Seterusnya</button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="rumusan-1" role="tabpanel" aria-labelledby="rumusan-tab">
                            <div class="container-fluid mt-3">
                                <!-- SECTION 1: Pembekal Melepasi -->
                                <div class="row">
                                    <div class="col-12 bg-light p-2 fw-bold">
                                        SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PEMATUHAN DOKUMENTASI
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered mt-2">
                                            <thead class="table-primary text-center text-white">
                                                <tr>
                                                    <th>Bil</th>
                                                    <th>Ulasan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1/2</td>
                                                    <td class="text-center">XXX</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">2/2</td>
                                                    <td class="text-center">XXX</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Bilangan Pembekal + Checkbox -->
                                <div class="row my-3">
                                    <div class="col-md-3 text-start fw-bold mt-1">Bilangan Pembekal</div>
                                    <div class="col-md-1 text-start">
                                        <input type="text" class="form-control text-center" value="2" readonly>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="confirmLayak">
                                            <label class="form-check-label" for="confirmLayak">
                                                Saya mengesahkan petender diatas layak untuk penilaian peringkat
                                                seterusnya.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 2: Pembekal Tidak Melepasi -->
                                <div class="row">
                                    <div class="col-12 bg-light p-2 fw-bold">
                                        SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PEMATUHAN DOKUMENTASI
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered mt-2">
                                            <thead class="table-primary text-center text-white">
                                                <tr>
                                                    <th>Bil</th>
                                                    <th>Ulasan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center" colspan="2">Tiada rekod dijumpai</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Bilangan Pembekal Tidak Melepasi -->
                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-3 text-start fw-bold mt-1">Bilangan Pembekal</div>
                                    <div class="col-md-1">
                                        <input type="text" class="form-control text-center" value="0" readonly>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="row mb-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button class="btn btn-primary btn-seterusnya">Seterusnya</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Penyata Bulanan Bank -->
                <div class="tab-pane fade" id="penyata-bank" role="tabpanel" aria-labelledby="penyata-bank-tab">
                    @include('newModule.penilaian_kewangan.step2')
                </div>

                <!-- Step 3: Pematuhan Spesifikasi Kewangan -->
                <div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">
                    @include('newModule.penilaian_kewangan.step3')
                </div>

                <!-- Step 4: Penyediaan Laporan -->
                <div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
                    @include('newModule.penilaian_kewangan.step4')
                </div>

            </div>

        </div>
    </div>

    {{-- Dialog: SEMAKAN PEMATUHAN DOKUMEN KEWANGAN  --}}
    <div class="modal fade" id="modalSemakanKetepatanDokumenKewangan" tabindex="-1" aria-labelledby="modalLabelSemakanKewangan"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-semakan-kewangan">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalLabelSemakanKewangan">SEMAKAN PEMATUHAN DOKUMEN KEWANGAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2"><strong>Tajuk / Dokumen:</strong> <span id="modalSemakanKewanganTajuk">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</span></p>

                    <div class="card-title card-title-grey mb-2">Senarai Pembekal</div>
                    <p class="card-title-desc text-primary fst-italic mb-3">Sila pilih status pematuhan untuk meneruskan penilaian pematuhan.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-primary text-center text-white">
                                <tr>
                                    <th style="width: 10%;">Bil</th>
                                    <th style="width: 32%;">Dokumen</th>
                                    <th style="width: 22%;">Status Pematuhan</th>
                                    <th style="width: 22%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1/2</td>
                                    <td>
                                        <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                        </a>
                                        Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX.pdf
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-select" aria-label="Status Pematuhan baris 1">
                                            <option selected value="">Sila Pilih</option>
                                            <option value="mematuhi">Mematuhi</option>
                                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="form-control" rows="2" placeholder="Catatan" aria-label="Catatan pembekal 1"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2/2</td>
                                    <td>
                                        <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-file-earmark-pdf-fill text-primary" aria-hidden="true"></i>
                                        </a>
                                        Perkhidmatan Penilaian Forensik Ke atas Sistem XXXXn.pdf
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-select" aria-label="Status Pematuhan baris 2">
                                            <option selected value="">Sila Pilih</option>
                                            <option value="mematuhi">Mematuhi</option>
                                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="form-control" rows="2" placeholder="Catatan" aria-label="Catatan pembekal 2"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button type="button" id="btnStep1SimpanDokKewangan" class="btn btn-success" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalProfilPetenderReadonly" tabindex="-1" aria-labelledby="modalProfilPetenderReadonlyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
            <div class="modal-content modal-semakan-kewangan">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalProfilPetenderReadonlyLabel">Maklumat Profil Petender</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="card-title-desc text-primary fst-italic mb-3">Paparan ringkas borang profil petender (contoh data).</p>

                    <div class="profil-readonly-form">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="profil-readonly-chip">No. Tender: SUKSEL/PERT/2026/001</div>
                            <div class="profil-readonly-badge">Profil Lengkap</div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Maklumat Syarikat</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Syarikat</label>
                                    <input type="text" class="form-control" value="Inovasi Digital Nusantara Sdn. Bhd." readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Jenis Syarikat</label>
                                    <input type="text" class="form-control" value="Sdn. Bhd." readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Taraf Petender</label>
                                    <input type="text" class="form-control" value="Bumiputera" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat Syarikat</label>
                                    <textarea class="form-control" rows="2" readonly>No. 18, Persiaran Teknologi 2, Taman Sains Selangor, 47810 Petaling Jaya, Selangor.</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Pegawai Untuk Dihubungi</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" value="Nur Aisyah Binti Rahman" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No. Telefon</label>
                                    <input type="text" class="form-control" value="012-888 7766" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">E-mel</label>
                                    <input type="text" class="form-control" value="aisyah.rahman@idn.com.my" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Maklumat Pendaftaran & Kewangan</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">No. SSM</label>
                                    <input type="text" class="form-control" value="201901045678 (1345000-X)" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No. MOF</label>
                                    <input type="text" class="form-control" value="357-021-000987" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tempoh Sah MOF</label>
                                    <input type="text" class="form-control" value="31/12/2027" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bil. Pekerja Semasa</label>
                                    <input type="text" class="form-control" value="42 orang" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bil. Pekerja Teknikal</label>
                                    <input type="text" class="form-control" value="15 orang" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Modal Berbayar (RM)</label>
                                    <input type="text" class="form-control" value="750,000.00" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Modal Dibenarkan (RM)</label>
                                    <input type="text" class="form-control" value="1,500,000.00" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Ringkasan Projek Terdahulu (2 Tahun)</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Projek 1</label>
                                    <input type="text" class="form-control" value="Naik taraf infrastruktur rangkaian data - Jabatan Kastam Malaysia (RM 420,000.00)" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Projek 2</label>
                                    <input type="text" class="form-control" value="Penyenggaraan sistem keselamatan siber - Kementerian Kewangan (RM 380,000.00)" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Projek 3</label>
                                    <input type="text" class="form-control" value="Perkhidmatan sokongan aplikasi dalaman - MAMPU (RM 290,000.00)" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Kedudukan Kewangan Semasa</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Aset Utama (5 Terbesar)</label>
                                    <textarea class="form-control" rows="5" readonly>1. Bangunan pejabat 3 tingkat - RM 1,200,000.00
2. Pelayan data (server cluster) - RM 350,000.00
3. 15 unit workstation teknikal - RM 180,000.00
4. Perisian lesen enterprise - RM 120,000.00
5. Kenderaan operasi - RM 95,000.00</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Peralatan Berkaitan Tender (5 Item)</label>
                                    <textarea class="form-control" rows="5" readonly>1. Network analyzer set - RM 80,000.00
2. Security appliance - RM 65,000.00
3. Portable forensic workstation - RM 58,000.00
4. Backup storage system - RM 45,000.00
5. Audit toolkit license - RM 30,000.00</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggungan / Liabilities (RM)</label>
                                    <input type="text" class="form-control" value="210,000.00" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Baki Wang Dalam Bank (RM)</label>
                                    <input type="text" class="form-control" value="980,000.00" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Analisa Kecukupan Modal</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Skor Modal Berbayar</label>
                                    <input type="text" class="form-control" value="Automatik" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Skor Modal Berbayar</label>
                                    <input type="text" class="form-control" value="10/10" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Skor Modal Dibenarkan</label>
                                    <input type="text" class="form-control" value="Automatik" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Skor Modal Dibenarkan</label>
                                    <input type="text" class="form-control" value="10/10" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        const steps = document.querySelectorAll('.progress-step');
        const tabs = document.querySelectorAll('.step-number');

        function updateStepper(activeIndex) {
            steps.forEach((step, i) => {
                step.classList.remove('active', 'done');

                if (i < activeIndex) step.classList.add('done');
                if (i === activeIndex) step.classList.add('active');
            });
        }

        tabs.forEach((tab, index) => {
            tab.addEventListener('shown.bs.tab', () => {
                updateStepper(index);
            });
        });

        // Init
        updateStepper(0);

        let activeSemakanButton = null;
        const btnSimpanDokKewangan = document.getElementById('btnStep1SimpanDokKewangan');

        document.querySelectorAll('.btn-papar-semakan-kewangan:not(.btn-open-profil-petender-readonly)').forEach((btn) => {
            btn.addEventListener('click', () => {
                const t = btn.getAttribute('data-dokumen')?.trim() || '';
                const el = document.getElementById('modalSemakanKewanganTajuk');
                activeSemakanButton = btn;
                if (el && t) {
                    el.textContent = t;
                }
            });
        });

        if (btnSimpanDokKewangan) {
            btnSimpanDokKewangan.addEventListener('click', () => {
                if (!activeSemakanButton) return;

                const currentRow = activeSemakanButton.closest('tr');
                const statusCell = currentRow?.querySelector('.status-penilaian');
                if (statusCell) {
                    statusCell.textContent = 'Selesai';
                }

                activeSemakanButton.textContent = 'Papar';
                activeSemakanButton = null;
            });
        }

    });

    // Seterusnya Button Functionality with checkbox validation
    const msgTandakanPengesahan = 'Sila tandakan kotak pengesahan terlebih dahulu sebelum meneruskan.';
    document.querySelectorAll('.btn-seterusnya').forEach(btn => {
        btn.addEventListener('click', () => {
            const current = document.querySelector('.step-number.active');
            if (!current) return;

            const currentId = current.id;
            const checks = [
                { id: 'pematuhan-tab', el: document.getElementById('confirmLayak') },
                { id: 'penyata-bank-tab', el: document.getElementById('confirmLayakStep2') },
                { id: 'penilaian-tab', el: document.getElementById('confirmLayakStep3') },
            ];
            const stepCheck = checks.find(c => c.id === currentId);
            if (stepCheck?.el && !stepCheck.el.checked) {
                alert(msgTandakanPengesahan);
                return;
            }

            const next = current.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
            if (next) next.click();
        });
    });
</script>

@endsection