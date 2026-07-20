@extends('layouts.v3.master')

@section('content')
{{-- Breadcrumb: back to SENARAI TENDER (first page) --}}
<nav aria-label="breadcrumb" class="py-2 mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">STOS</a></li>
        <li class="breadcrumb-item"><a href="{{ route('penilaianTeknikal') }}" class="text-decoration-none">Peringkat Penilaian Teknikal</a></li>
        <li class="breadcrumb-item active" aria-current="page">Penilaian Teknikal</li>
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
        border-radius: 10px;
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

    .modal-footer {
        border-top: 1px solid #E5E7EB;
    }

    /* Status Pematuhan dropdown: enough width, no overlap with chevron */
    #modalSemakanKetepatanDokumenTeknikal select.form-select {
        min-width: 100%;
        width: 100%;
        padding-right: 2.25rem;
        box-sizing: border-box;
    }

    #modalSemakanKetepatanDokumenTeknikal td:nth-child(3) {
        min-width: 200px;
    }

    #modalViewDokumenTeknikal .modal-body iframe {
        background: #fff;
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
                    <div>Menunggu Penilaian Cadangan Teknikal</div>
                </div>
                <div class="col-md-4">
                    <b>Sah Laku Tawaran Tamat</b>
                    <div>17/01/2022</div>
                </div>
            </div>
            <div class="row">
                <div id="custom-progress-bar" class="progress-nav mb-4 p-2">

                    <ul class="nav progress-wrapper" role="tablist">

                        <li class="nav-item progress-step active" role="Pematuhan Dokumentasi">
                            <button type="button"
                                id="pematuhan-tab"
                                class="nav-link step-number active"
                                data-bs-toggle="pill"
                                data-bs-target="#pematuhan"
                                role="tab">1</button>
                            <div class="step-label">Pematuhan Dokumentasi</div>
                        </li>

                        <li class="nav-item progress-step" role="Pematuhan Spesifikasi Teknikal">
                            <button type="button"
                                id="penilaian-tab"
                                class="nav-link step-number"
                                data-bs-toggle="pill"
                                data-bs-target="#penilaian"
                                role="tab">2</button>
                            <div class="step-label">Pematuhan Spesifikasi Teknikal</div>
                        </li>

                        <li class="nav-item progress-step" role="Penyediaan Laporan">
                            <button type="button"
                                id="laporan-tab"
                                class="nav-link step-number"
                                data-bs-toggle="pill"
                                data-bs-target="#laporan"
                                role="tab">3</button>
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
                            <a class="nav-link active" data-bs-toggle="tab" href="#teknikal-1" role="tab"
                                aria-selected="true">Teknikal</a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#rumusan-1" role="tab"
                                aria-selected="false">Rumusan</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="teknikal-1" role="tabpanel">
                            <!-- Content for Teknikal of progress 1 -->
                            <h4 class="card-title card-title-grey">PEMATUHAN CADANGAN TEKNIKAL</h4>
                            <p class="card-title-desc text-primary fst-italic">Klik butang Menilai untuk meneruskan penilaian</p>
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
                                        <td>Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</td>
                                        <td>Spesifikasi</td>
                                        <td>Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button"
                                                id="btnStep1Menilai"
                                                class="btn btn-success btn-semakan-dok-teknikal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                data-tajuk="Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX"
                                                data-doc-pembekal-1="Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf"
                                                data-doc-pembekal-2="Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf"
                                                data-doc-url="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf">
                                                Menilai
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-mekanisma="Petender Muat Naik">
                                        <td>Surat Pengesahan Prinsipal yang lengkap ditandatangani</td>
                                        <td>Petender Muat Naik</td>
                                        <td>Selesai</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-success btn-semakan-dok-teknikal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                data-tajuk="Surat Pengesahan Prinsipal yang lengkap ditandatangani"
                                                data-doc-pembekal-1="Surat Pengesahan Prinsipal — Pembekal 1.pdf"
                                                data-doc-pembekal-2="Surat Pengesahan Prinsipal — Pembekal 2.pdf"
                                                data-doc-url="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf">
                                                Menilai
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-mekanisma="Petender Muat Naik">
                                        <td>Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek
                                        </td>
                                        <td>Petender Muat Naik</td>
                                        <td>Selesai</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-success btn-semakan-dok-teknikal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                data-tajuk="Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek"
                                                data-doc-pembekal-1="Senarai Kakitangan dan Carta Organisasi — Pembekal 1.pdf"
                                                data-doc-pembekal-2="Senarai Kakitangan dan Carta Organisasi — Pembekal 2.pdf"
                                                data-doc-url="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf">
                                                Menilai
                                            </button>
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

                <!-- Outer Tab 2 Content (Step 2: Pematuhan Spesifikasi Teknikal - from teknikal_step2.blade.php) -->
                <div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">
                    @include('newModule.penilaian_teknikal.teknikal_step2')
                </div>

                <!-- Outer Tab 3 Content -->
                <div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
                    @include('newModule.penilaian_teknikal.teknikal_step3')
                </div>

            </div>

        </div>
    </div>

    {{-- Step 1 modal: SEMAKAN PEMATUHAN DOKUMEN TEKNIKAL (second page, step 1) --}}
    <div class="modal fade" id="modalSemakanKetepatanDokumenTeknikal" tabindex="-1" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">SEMAKAN PEMATUHAN DOKUMEN TEKNIKAL</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted small mb-2">Klik butang Semak untuk meneruskan penilaian pematuhan</p>
                    <p class="mb-0"><strong>Tajuk / Dokumen:</strong> <span id="modalSemakanTajukDokumen">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</span></p>
                    <div class="mb-3 mt-3">
                        <h4 class="card-title card-title-grey">SENARAI PEMBEKAL</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle w-100 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 12%;">Kod Pembekal</th>
                                    <th class="text-center" style="width: 38%;">Dokumen</th>
                                    <th class="text-center" style="width: 28%;">Status Pematuhan</th>
                                    <th class="text-center" style="width: 22%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Tajuk / Dokumen & label fail dikemas kini oleh JS daripada butang Menilai --}}
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="semakan-pdf-link text-primary text-decoration-none d-inline-flex align-items-center flex-shrink-0">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break semakan-doc-label">Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-select" name="status_pematuhan_1" aria-label="Status Pematuhan">
                                            <option value="" selected disabled>Sila Pilih</option>
                                            <option value="mematuhi">Mematuhi</option>
                                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Catatan">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="semakan-pdf-link text-primary text-decoration-none d-inline-flex align-items-center flex-shrink-0">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break semakan-doc-label">Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-select" name="status_pematuhan_2" aria-label="Status Pematuhan">
                                            <option value="" selected disabled>Sila Pilih</option>
                                            <option value="mematuhi">Mematuhi</option>
                                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Catatan">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" id="btnStep1SimpanDokTeknikal" class="btn btn-success" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal paparan dokumen (PDF / dipaparkan dalam iframe) --}}
    <div class="modal fade" id="modalViewDokumenTeknikal" tabindex="-1" aria-labelledby="modalViewDokumenTeknikalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-truncate pe-3 mb-0" id="modalViewDokumenTeknikalLabel">Paparan dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-0 bg-light">
                    <iframe id="iframeViewDokumenTeknikal" title="Paparan dokumen" class="w-100 border-0 d-block"
                        style="min-height: 70vh; height: min(78vh, 820px);"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- Petender Muat Naik: modal MUST live outside tab-panes (Bootstrap breaks modals inside display:none tabs → black screen) --}}
    <div class="modal fade" id="modalPenilaianMuatNaikTeknikal" tabindex="-1" aria-labelledby="modalPenilaianMuatNaikTeknikalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenilaianMuatNaikTeknikalLabel">PENILAIAN SPESIFIKASI TEKNIKAL</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rounded border bg-light px-3 py-2 mb-3">
                        <div class="fw-bold text-uppercase small">PENILAIAN SPESIFIKASI TEKNIKAL</div>
                        <p class="mb-0 mt-2 small"><strong>Tajuk / Dokumen :</strong> <span id="muatNaikModalTajuk">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</span></p>
                    </div>
                    <div class="rounded border bg-light px-3 py-2 mb-3">
                        <div class="fw-bold text-uppercase small">SKEMA PEMARKAHAN PENGGUNA BAGI TEKNIKAL</div>
                        <p class="mb-0 mt-2 small"><strong>Dokumen Sokongan :</strong> <span id="muatNaikModalSkema">Skema Pemarkahan Senarai Semakan Teknikal Digital Forensik.docx</span></p>
                    </div>
                    <div class="rounded border bg-light px-3 py-2 mb-2">
                        <div class="fw-bold text-uppercase small">SENARAI PEMBEKAL</div>
                    </div>
                    <p class="card-title-desc text-primary fst-italic small mb-3">Pastikan semua senarai semak lengkap dinilai dan butang Menilai bertukar kepada Papar</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-white">
                                <tr>
                                    <th style="width: 10%;">Kod Pembekal</th>
                                    <th style="width: 26%;">Dokumen</th>
                                    <th style="width: 12%;">Status Penyerahan</th>
                                    <th style="width: 12%;">Skor Pematuhan</th>
                                    <th style="width: 14%;">Skor Manual <span class="text-danger">*</span></th>
                                    <th style="width: 26%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="muat-naik-doc-link text-primary text-decoration-none d-inline-flex align-items-center flex-shrink-0"
                                                aria-label="Buka dokumen pembekal 1 dalam tab baharu">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break muat-naik-supplier-doc" data-slot="1">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-center">Hantar</td>
                                    <td class="text-center">Mematuhi</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                            <input type="number" min="0" max="10" step="0.01"
                                                class="form-control form-control-sm text-center muat-naik-skor-manual"
                                                style="width: 4.25rem; max-width: 100%;"
                                                name="skor_manual_muat_naik_1"
                                                aria-label="Skor manual pembekal 1">
                                            <span class="small text-nowrap">/ 10</span>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm muat-naik-catatan" rows="2" name="catatan_muat_naik_1" placeholder="Catatan"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="muat-naik-doc-link text-primary text-decoration-none d-inline-flex align-items-center flex-shrink-0"
                                                aria-label="Buka dokumen pembekal 2 dalam tab baharu">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break muat-naik-supplier-doc" data-slot="2">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-center">Hantar</td>
                                    <td class="text-center">Mematuhi</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                            <input type="number" min="0" max="10" step="0.01"
                                                class="form-control form-control-sm text-center muat-naik-skor-manual"
                                                style="width: 4.25rem; max-width: 100%;"
                                                name="skor_manual_muat_naik_2"
                                                aria-label="Skor manual pembekal 2">
                                            <span class="small text-nowrap">/ 10</span>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm muat-naik-catatan" rows="2" name="catatan_muat_naik_2" placeholder="Catatan"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn btn-success" id="btnSimpanPenilaianMuatNaikTeknikal" data-bs-dismiss="modal">Simpan</button>
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

        // Step 1: satu modal SEMAKAN PEMATUHAN DOKUMEN TEKNIKAL untuk semua baris;
        // kandungan Tajuk/Dokumen & label fail daripada data-* pada butang Menilai.
        let semakanStep1Trigger = null;
        const modalSemakanStep1 = document.getElementById('modalSemakanKetepatanDokumenTeknikal');
        if (modalSemakanStep1) {
            modalSemakanStep1.addEventListener('show.bs.modal', function(event) {
                const trigger = event.relatedTarget;
                if (!trigger || !trigger.classList.contains('btn-semakan-dok-teknikal')) return;
                semakanStep1Trigger = trigger;
                const tajuk = trigger.getAttribute('data-tajuk') || '';
                const d1 = trigger.getAttribute('data-doc-pembekal-1') || '';
                const d2 = trigger.getAttribute('data-doc-pembekal-2') || '';
                const docUrl = (trigger.getAttribute('data-doc-url') || 'https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf').trim();
                const elTajuk = document.getElementById('modalSemakanTajukDokumen');
                if (elTajuk && tajuk) elTajuk.textContent = tajuk;
                const labels = modalSemakanStep1.querySelectorAll('.semakan-doc-label');
                if (labels[0] && d1) labels[0].textContent = d1;
                if (labels[1] && d2) labels[1].textContent = d2;
                modalSemakanStep1.querySelectorAll('a.semakan-pdf-link').forEach(function(a) {
                    a.setAttribute('href', docUrl);
                });
            });
        }
        const btnStep1Simpan = document.getElementById('btnStep1SimpanDokTeknikal');
        if (btnStep1Simpan) {
            btnStep1Simpan.addEventListener('click', function() {
                if (semakanStep1Trigger) {
                    semakanStep1Trigger.textContent = 'Papar';
                    semakanStep1Trigger = null;
                }
            });
        }

        // Paparan dokumen: set iframe URL & tajuk daripada butang view
        const modalViewDoc = document.getElementById('modalViewDokumenTeknikal');
        const iframeViewDoc = document.getElementById('iframeViewDokumenTeknikal');
        const titleViewDoc = document.getElementById('modalViewDokumenTeknikalLabel');
        if (modalViewDoc && iframeViewDoc && titleViewDoc) {
            modalViewDoc.addEventListener('show.bs.modal', function(event) {
                const trigger = event.relatedTarget;
                if (!trigger || !trigger.matches('.btn-view-doc-teknikal')) return;
                const url = trigger.getAttribute('data-doc-url');
                const docTitle = trigger.getAttribute('data-doc-title') || 'Dokumen';
                titleViewDoc.textContent = docTitle;
                iframeViewDoc.src = url ? url.trim() : 'about:blank';
            });
            modalViewDoc.addEventListener('hidden.bs.modal', function() {
                iframeViewDoc.src = 'about:blank';
            });
        }
    });

    const msgTandakanPengesahan = 'Sila tandakan kotak pengesahan terlebih dahulu sebelum meneruskan.';
    document.querySelectorAll('.btn-seterusnya').forEach(btn => {
        btn.addEventListener('click', () => {
            const current = document.querySelector('.step-number.active');
            if (!current) return;

            const currentId = current.id;
            const checks = [
                { id: 'pematuhan-tab', el: document.getElementById('confirmLayak') },
                { id: 'penilaian-tab', el: document.getElementById('confirmLayakStep2') },
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