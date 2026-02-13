@extends('layouts.v3.master')

@section('content')
 <style>
/* ========================
   GLOBAL
======================== */
body{
    background:#F3F4F6;
}

.card{
    border-radius:12px;
    border:1px solid #E5E7EB;
}

.card-body{
    padding:24px;
}

hr{
    border:1px solid #E5E7EB;
}

/* ========================
   HEADER SUMMARY
======================== */
.card-body .row > .col-4{
    border-right:1px solid #E5E7EB;
}

.card-body .row > .col-4:last-child{
    border-right:none;
}

.card-body b{
    font-size:13px;
    color:#374151;
}

/* ========================
   STEPPER
======================== */
:root{
    --sg-red:#C4161C;
    --sg-red-dark:#9F1216;
    --step-grey:#E5E7EB;
    --text-grey:#6B7280;
}

/* ========================
   STEPPER WRAPPER
======================== */
.progress-wrapper{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    position:relative;
    margin:20px 0;
    padding:10px 20px;
}

/* ========================
   STEPPER ITEM
======================== */
.progress-step{
    flex:1;
    text-align:center;
    position:relative;
}

/* ========================
   CONNECTOR LINE
======================== */
.progress-step:not(:last-child)::after{
    content:'';
    position:absolute;
    top:22px;                 /* aligns with circle center */
    left:50%;
    width:100%;
    height:3px;
    background:var(--step-grey);
    z-index:0;
}

/* Active / completed connector */
.progress-step.done:not(:last-child)::after,
.progress-step.active:not(:last-child)::after{
    background:var(--sg-red);
}

/* Reset future connectors */
.progress-step.active ~ .progress-step:not(:last-child)::after{
    background:var(--step-grey);
}

/* ========================
   STEP CIRCLE
======================== */
.step-number{
    width:36px;
    height:36px;
    border-radius:50%;
    background:var(--step-grey);
    color:#374151;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto;
    border:none;
    position:relative;
    z-index:2;                /* ABOVE line */
    cursor:pointer;
}

/* Active & done circle */
.progress-step.active .step-number,
.progress-step.done .step-number{
    background:var(--sg-red);
    color:#fff;
}

/* ========================
   STEP LABEL
======================== */
.step-label{
    margin-top:8px;
    font-size:13px;
    color:var(--text-grey);
    line-height:1.3;
}

/* Active & done label */
.progress-step.active .step-label,
.progress-step.done .step-label{
    color:var(--sg-red-dark);
    font-weight:600;
}

/* ========================
   OPTIONAL UX ENHANCEMENTS
======================== */
.progress-step:hover .step-number{
    transform:scale(1.05);
    transition:0.2s;
}

.progress-step:hover .step-label{
    color:var(--sg-red-dark);
}

/* =========================
   SECTION TITLES
========================= */
.card-title-grey{
    background:#F9FAFB;
    padding:12px 16px;
    border-left:5px solid #C0392B;
    font-weight:700;
    font-size:15px;
    border-radius:6px;
}

/* ==========================
   SUB TABS (Teknikal | Rumusan)
========================== */
.custom-tab-size .nav-link{
    border-radius:8px 8px 0 0;
    background:#FFFFFF;
    color:#374151;
    border:1px solid #E5E7EB;
    font-weight:600;
    padding:10px 18px;
}

.custom-tab-size .nav-link.active{
    background:#C0392B!important;
    color:#FFFFFF!important;
    border-color:#C0392B!important;
}

/* ==========================
   TABLE
========================== */
.table{
    border-radius:10px;
    overflow:hidden;
}

.table thead th{
    background:#1E3A8A;
    color:white;
    text-align:center;
    font-size:13px;
    padding:12px;
}

.table td{
    font-size:13px;
    padding:12px;
    vertical-align:middle;
}

.table tbody tr:hover{
    background:#F9FAFB;
}

/* ==========================
   BUTTONS
========================== */
.btn{
    border-radius:8px;
    font-weight:600;
    padding:8px 16px;
}

.btn-success{
    background:#16A34A;
    border:none;
}

.btn-success:hover{
    background:#15803D;
}

.btn-primary{
    background:#1E3A8A;
    border:none;
}

.btn-primary:hover{
    background:#1E40AF;
}

.btn-outline-secondary{
    border-radius:8px;
}

/* ==========================
   LINKS
========================== */
.text-primary,
.text-primary:hover{
    color:#2563EB!important;
}

/* ==========================
   FORM
========================== */
.form-control,
.form-select{
    border-radius:8px;
    font-size:13px;
}

.form-check-label{
    font-size:13px;
}

/* ==========================
   MODAL
========================== */
.modal-content{
    border-radius:14px;
}

.modal-header{
    background:#1E3A8A;
    color:white;
}

.modal-title{
    color:white;
    font-weight:700;
}

.modal-footer{
    border-top:1px solid #E5E7EB;
}

/* ==========================
   TEXT HINTS
========================== */
.card-title-desc{
    font-size:13px;
    margin-bottom:12px;
}

/* ==========================
   ACTION FOOTER
========================== */
.d-flex.justify-content-end.gap-2 button{
    min-width:120px;
}
/* ==========================
   TABLE – RED THEME OVERRIDE
========================== */

/* Table header */
.table thead th,
.table-primary thead th,
.table-primary th {
    background-color:#C0392B !important;
    color:#FFFFFF !important;
    text-align:center;
    border-color:#A93226 !important;
}

/* Table header row */
.table thead tr {
    background-color:#C0392B !important;
}

/* Table borders */
.table-bordered > :not(caption) > * {
    border-color:#E5B4AF;
}

/* Table hover */
.table tbody tr:hover {
    background:#FDEDEC;
}

/* Badges inside table */
.table .badge.bg-success{
    background:#16A34A !important;
}

.table .badge.bg-warning{
    background:#F59E0B !important;
}

/* Action buttons inside table */
.table .btn-success{
    background:#16A34A;
}

.table .btn-primary{
    background:#C0392B;
    border:none;
}

.table .btn-primary:hover{
    background:#A93226;
}

/* Pencil / icon buttons */
.table .btn-outline-secondary{
    border-color:#C0392B;
    color:#C0392B;
}

.table .btn-outline-secondary:hover{
    background:#C0392B;
    color:#fff;
}
/* =========================
   STEPPER – CHANGE BLUE TO RED
========================= */

/* Default step circle (inactive) */
.progress-bar-tab .nav-link{
    background:#C0392B !important;   /* red */
    color:#fff !important;
}

/* Active step (current step) */
.progress-bar-tab .nav-link.active{
    background:#16A34A !important;   /* keep green for current (optional) */
}

/* Step label text */
.progress-bar-tab .nav-item::after{
    color:#000;
    font-weight:500;
}

/* Horizontal connector line */
.progress{
    height:2px !important;
}

.progress-bar{
    background:#C0392B !important;
}

</style>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                     <div class="row">
        <div class="col-4">
            <div class="row">
                <div class="col-5 text-center">
                    <b>No. Sebut Harga / Tender</b>
                </div>
                <div class="col-7 text-center text-success">
                    Belum Dijana
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-2 text-center">
                    <b>PTJ</b>
                </div>
                <div class="col-10 text-center">
                    BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-3 text-end">
                    <b>Status</b>
                </div>
                <div class="col-9 text-center">
                    Menunggu Penyerahan Sebut Harga / Tender
                </div>
            </div>
        </div>
            <div class="row">
                <div id="custom-progress-bar" class="progress-nav mb-4 p-2">

                    <ul class="nav progress-wrapper" role="tablist">

                        <li class="nav-item progress-step active" role="Peringkat pematuhan cadangan teknikal">
                            <button type="button"
                                    id="pematuhan-tab"
                                    class="nav-link step-number active"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pematuhan"
                                    role="tab">1</button>
                            <div class="step-label">Peringkat pematuhan cadangan teknikal</div>
                        </li>

                        <li class="nav-item progress-step" role="Penilaian Spesifikasi Teknikal">
                            <button type="button"
                                    id="penilaian-tab"
                                    class="nav-link step-number"
                                    data-bs-toggle="pill"
                                    data-bs-target="#penilaian"
                                    role="tab">2</button>
                            <div class="step-label">Penilaian Spesifikasi Teknikal</div>
                        </li>

                        <li class="nav-item progress-step" role="Laporan">
                            <button type="button"
                                    id="laporan-tab"
                                    class="nav-link step-number"
                                    data-bs-toggle="pill"
                                    data-bs-target="#laporan"
                                    role="tab">3</button>
                            <div class="step-label">Laporan</div>
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
                                <p class="card-title-desc text-primary fst-italic">Klik butang Semak untuk meneruskan
                                    penilaian pematuhan</p>
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
                                            <td class="">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</td>
                                            <td class="">Spesifikasi</td>
                                            <td class="">Menunggu Penyerahan</td>
                                            <td class="text-center">
                                                <button class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#modalSemakanKetepatanDokumenTeknikal">Menilai</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">Surat Pengesahan Prinsipal yang lengkap ditandatangani</td>
                                            <td class="">Petender Muat Naik</td>
                                            <td class="">Selesai</td>
                                            <td class="text-center">
                                                <button class="btn btn-success">Papar</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek
                                            </td>
                                            <td class="">Petender Muat Naik</td>
                                            <td class="">Selesai</td>
                                            <td class="text-center">
                                                <button class="btn btn-success">Papar</button>
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
                                    <div class="row my-3 align-items-center">
                                        <div class="col-md-2 text-end fw-bold">Bilangan Pembekal</div>
                                        <div class="col-md-1">
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
                                        <div class="col-md-2 text-end fw-bold">Bilangan Pembekal</div>
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

                    <!-- Outer Tab 2 Content -->
                    <div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">

                        <!-- Inner tabs for outer tab 2 -->
                        <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-bs-toggle="tab" href="#teknikal-2" role="tab"
                                    aria-selected="true">Teknikal</a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-bs-toggle="tab" href="#rumusan-2" role="tab"
                                        aria-selected="false">Rumusan</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="teknikal-2" role="tabpanel"
                                    aria-labelledby="teknikal-2-tab">
                                    <!-- Content for Teknikal of progress 2 -->
                                    <h4 class="card-title card-title-grey">PENILAIAN SPESIFIKASI TEKNIKAL</h4>
                                    <p class="card-title-desc text-primary fst-italic">Klik butang Menilai untuk meneruskan
                                        penilaian</p>
                                    <table class="table table-bordered dt-responsive nowrap w-100">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Tajuk / Dokumen</th>
                                                <th>Mekanisma</th>
                                                <th>Status Penilaian</th>
                                                <th>Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Spesifikasi Teknikal 1</td>
                                                <td>Dokumen Rujukan</td>
                                                <td>Dalam Proses</td>
                                                <td class="text-center">
                                                    <button class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#modalSpesifikasiTeknikal1">Papar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Spesifikasi Teknikal 2</td>
                                                <td>Dokumen Rujukan</td>
                                                <td>Lengkap</td>
                                                <td class="text-center"><button class="btn btn-success">Papar</button></td>
                                            </tr>
                                            <tr>
                                                <td>Spesifikasi Teknikal 3</td>
                                                <td>Dokumen Rujukan</td>
                                                <td>Lengkap</td>
                                                <td class="text-center"><button class="btn btn-success">Papar</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row mb-3 px-3">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <button class="btn btn-primary btn-seterusnya">Seterusnya</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="rumusan-2" role="tabpanel" aria-labelledby="rumusan-2-tab">
                                    <div class="container-fluid mt-3">
                                        <!-- SECTION 1: Pembekal Melepasi -->
                                        <div class="row">
                                            <div class="col-12 bg-light p-2 fw-bold">
                                                SENARAI PEMBEKAL YANG MELEPASI PENILAIAN TEKNIKAL
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table table-bordered mt-2">
                                                    <thead class="table-primary text-center text-white">
                                                        <tr>
                                                            <th>Kedudukan</th>
                                                            <th>Bil</th>
                                                            <th>Jumlah Skor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">1</td>
                                                            <td class="text-center">2/2</td>
                                                            <td class="text-center">95.87</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">2</td>
                                                            <td class="text-center">1/2</td>
                                                            <td class="text-center">91.74</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Bilangan Pembekal + Checkbox -->
                                        <div class="row my-3 align-items-center">
                                            <div class="col-md-2 text-end fw-bold">Penetapan Penanda Aras Tahap Lulus (%)
                                            </div>
                                            <div class="col-md-1">
                                                <input type="text" class="form-control text-center" value="70" readonly>
                                            </div>
                                        </div>
                                        <div class="row my-3 align-items-center">
                                            <div class="col-md-2 text-end fw-bold">Bilangan Pembekal</div>
                                            <div class="col-md-1">
                                                <input type="text" class="form-control text-center" value="2" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="confirmLayak">
                                                    <label class="form-check-label" for="confirmLayak">
                                                        Saya mengesahkan petender diatas layak untuk dinilai oleh
                                                        Jawatankuasa Kewangan
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
                                                            <th>Jumlah Skor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">Tiada rekod dijumpai</td>
                                                            <td class="text-center">Tiada rekod dijumpai</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Bilangan Pembekal Tidak Melepasi -->
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-md-2 text-end fw-bold">Bilangan Pembekal</div>
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

                        <!-- Outer Tab 3 Content -->
                        <div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
                            <!-- Add inner tabs if needed, or direct content -->
                            <!-- Penilaian Peringkat Pertama -->
                            <h5 class="fw-bold mt-3">PENILAIAN PERINGKAT PERTAMA:</h5>

                            <div class="mb-3 mt-2">
                                <div class="card-title card-title-grey">SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PEMATUHAN
                                    DOKUMENTASI</div>
                                <table class="table table-bordered text-center align-middle mt-2">
                                    <thead class="table-primary text-white">
                                        <tr>
                                            <th>BIL</th>
                                            <th>ULASAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1/2</td>
                                            <td>XXX</td>
                                        </tr>
                                        <tr>
                                            <td>2/2</td>
                                            <td>XXX</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-3">
                                <div class="card-title card-title-grey">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PEMATUHAN
                                    DOKUMENTASI</div>
                                <table class="table table-bordered text-center align-middle mt-2">
                                    <thead class="table-primary text-white">
                                        <tr>
                                            <th>BIL</th>
                                            <th>ULASAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">Tiada rekod dijumpai</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <textarea class="form-control mt-2"
                                    rows="2">Sehubungan dengan itu, JPT bersetuju untuk mengambil xx penyebut harga iaitu XX untuk ke Penilaian Peringkat Kedua</textarea>
                            </div>

                            <!-- Penilaian Peringkat Kedua -->
                            <h5 class="fw-bold mt-4">PENILAIAN PERINGKAT KEDUA:</h5>

                            <div class="mb-3 mt-2">
                                <div class="card-title card-title-grey">SENARAI PEMBEKAL MELEPASI PENILAIAN SPESIFIKASI
                                    TEKNIKAL
                                </div>
                                <table class="table table-bordered text-center align-middle mt-2">
                                    <thead class="table-primary text-white">
                                        <tr>
                                            <th>KEDUDUKAN</th>
                                            <th>BIL</th>
                                            <th>JUMLAH SKOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>2/2</td>
                                            <td>96.87</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>1/2</td>
                                            <td>91.74</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row mt-2">
                                    <div class="col-md-4 d-flex align-items-center fw-bold">Penetapan Pemanda Aras Tahap
                                        Lulus (%)</div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control text-center" value="70">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="card-title card-title-grey">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN
                                    SPESIFIKASI
                                    TEKNIKAL</div>
                                <table class="table table-bordered text-center align-middle mt-2">
                                    <thead class="table-primary text-white">
                                        <tr>
                                            <th>BIL</th>
                                            <th>JUMLAH SKOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">Tiada rekod dijumpai</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <textarea class="form-control mt-2"
                                    rows="2">Sehubungan dengan itu, JPT bersetuju untuk mengambil xx penyebut harga iaitu XX untuk ke Peringkat Pengesyoran.</textarea>
                            </div>
                            <div class="mb-3">

                                <!-- Pengesyoran -->
                                <div class="card-title card-title-grey">PENGESYORAN</div>
                                <textarea class="form-control mb-3" rows="2">
                            Dengan ini, JPT mengesyorkan XX (bil) untuk melaksanakan (NAMA PROJEK) untuk dibawa ke mesyuarat Jawatankuasa Sebut Harga PSU(K) berdasarkan justifikasi seperti berikut:
                            </textarea>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-success">Tambah</button>
                                </div>

                            </div>
                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-outline-secondary">Laporan</button>
                                <button class="btn btn-primary">Hantar</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="modal fade" id="modalSemakanKetepatanDokumenTeknikal" tabindex="-1" aria-labelledby="modalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="modalLabel">SEMAKAN PEMATUHAN DOKUMEN TEKNIKAL
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Tajuk/Dokumen:</strong> Salinan Sijil Pedaftaran dengan Kementerian
                                Teknikal
                            </p>
                            <!-- Title / Document -->
                            <div class="mb-3">
                                <h4 class="card-title card-title-grey">SENARAI PEMBEKAL</h4>
                            </div>

                            <!-- Senarai Pembekal Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th style="width: 15%;">Kod Pembekal</th>
                                            <th style="width: 45%;">Dokumen</th>
                                            <th style="width: 20%;">Status Pematuhan</th>
                                            <th style="width: 20%;">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>
                                                <i class="bi bi-file-earmark-pdf-fill text-primary me-2"></i>
                                                Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Status Pematuhan">
                                                    <option selected>Mematuhi / Tidak Mematuhi</option>
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
                                                <i class="bi bi-file-earmark-pdf-fill text-primary me-2"></i>
                                                Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Status Pematuhan">
                                                    <option selected>Mematuhi / Tidak Mematuhi</option>
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
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for Spesifikasi Teknikal 1 -->
            <div class="modal fade" id="modalSpesifikasiTeknikal1" tabindex="-1"
                aria-labelledby="modalSpesifikasiTeknikal1Label" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSpesifikasiTeknikal1Label">Penilaian Spesifikasi Teknikal
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Tajuk/Dokumen:</strong> Perkhidmatan Penilaian Forensik Keatas Sistem XXXXX
                            </p>
                            <!-- Title / Document -->
                            <div class="mb-3">
                                <h4 class="card-title card-title-grey">SENARAI PEMBEKAL</h4>
                            </div>
                            <!-- Senarai Pembekal Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th style="width: 5%;">Bil</th>
                                            <th style="width: 25%;">Skor Automatik</th>
                                            <th style="width: 25%;">Skor Manual</th>
                                            <th style="width: 25%;">Jumlah Skor</th>
                                            <th style="width: 20%;">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1/2</td>
                                            <td class="text-center">
                                                11
                                            </td>
                                            <td class="text-center">

                                            </td>
                                            <td class="text-center">
                                                11
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#senaraiSpesifikasiTeknikal">Papar</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2/2</td>
                                            <td class="text-center">
                                                6
                                            </td>
                                            <td class="text-center">

                                            </td>
                                            <td class="text-center">
                                                6
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-success">Papar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal: Senarai Spesifikasi Teknikal Detail -->
            <div class="modal fade" id="senaraiSpesifikasiTeknikal" tabindex="-1"
                aria-labelledby="senaraiSpesifikasiTeknikalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h5 class="modal-title" id="senaraiSpesifikasiTeknikalLabel">SENARAI SPESIFIKASI TEKNIKAL</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th rowspan="2">Item / Spesifikasi</th>
                                            <th rowspan="2">Unit Ukuran</th>
                                            <th rowspan="2">Kekerapan / Unit Ukuran</th>
                                            <th rowspan="2">Bil. Unit Ukuran Sehari</th>
                                            <th rowspan="2">Bil. Unit Ukuran Sebulan</th>
                                            <th rowspan="2">Kuantiti</th>
                                            <th rowspan="2">Maklumbalas</th>
                                            <th rowspan="2">Catatan Pembekal</th>
                                            <th rowspan="2">Skor Automatik</th>
                                            <th rowspan="2">Skor Manual</th>
                                            <th rowspan="2">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-start" rowspan="4">
                                                PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX
                                            </td>
                                            <td rowspan="4">1</td>
                                            <td rowspan="4">1</td>
                                            <td rowspan="4">1</td>
                                            <td rowspan="4">1</td>
                                            <td rowspan="4">1</td>
                                            <td class="text-start">Temuduga dan kajian sistem</td>
                                            <td></td>
                                            <td>10 / 10</td>
                                            <td></td>
                                            <td><input type="text" class="form-control" placeholder=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success" id="btnSimpanSenarai">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const steps = document.querySelectorAll('.progress-step');
    const tabs  = document.querySelectorAll('.step-number');

    function updateStepper(activeIndex){
        steps.forEach((step, i) => {
            step.classList.remove('active','done');

            if(i < activeIndex) step.classList.add('done');
            if(i === activeIndex) step.classList.add('active');
        });
    }

    tabs.forEach((tab, index) => {
        tab.addEventListener('shown.bs.tab', () => {
            updateStepper(index);
        });
    });

    // Init
    updateStepper(0);
});

// Seterusnya Button Functionality
document.querySelectorAll('.btn-seterusnya').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const current = document.querySelector('.step-number.active');
        const next = current?.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
        if(next) next.click();
    });
});

</script>

@endsection