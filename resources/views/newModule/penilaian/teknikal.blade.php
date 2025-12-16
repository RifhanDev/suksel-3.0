@extends('layouts.v3.master')

@section('content')
 <style>

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
    color:#000;
    font-size:14px;
}

/* ========================
   STEPPER
======================== */
.progress-nav{
    border-top:1px solid #E5E7EB;
    border-bottom:1px solid #E5E7EB;
}

.progress-bar-tab{
    display:flex;
    justify-content:space-between;
}

.progress-bar-tab .nav-item{
    flex:1;
    text-align:center;
}

.progress-bar-tab .nav-link{
    margin:auto;
    width:40px;
    height:40px;
    border-radius:50%;
    background:#1E3A8A;
    color:white;
    font-weight:600;
    display:flex;
    align-items:center;
    justify-content:center;
}

.progress-bar-tab .nav-link.active{
    background:#10b981;
}

/* step label bawah circle */
.progress-bar-tab .nav-item::after{
    content:attr(role);
    display:block;
    margin-top:6px;
    font-size:13px;
    color:#000;
}

/* horizontal connector */
.progress{
    height:2px!important;
}
.progress-bar{
    background:#1E3A8A!important;
}

/* =========================
   SECTION TITLE
========================= */
.card-title-grey{
    background:#F5F5F5;
    padding:10px 15px;
    border-left:5px solid #1E3A8A;
    font-weight:600;
}

/* ==========================
   SUB TAB (Kewangan | Rumusan)
========================== */
.custom-tab-size .nav-link{
    border-radius:0!important;
    background:#fff;
    color:#000;
    border:1px solid #E5E7EB;
    font-weight:600;
}

.custom-tab-size .nav-link.active{
    background:#C0392B!important;
    color:#fff!important;
    border-color:#C0392B!important;
}

/* ==========================
   LINKS
========================== */
.text-primary,
.text-primary:hover{
    color:#2563EB!important;
}

/* ==========================
   TABLE
========================== */
.table thead th{
    background:#1E3A8A;
    color:white;
    text-align:center;
}

.table td,
.table th{
    vertical-align:middle;
}

/* ==========================
   BUTTON
========================== */
.btn-success{
    background:#16A34A;
    border:none;
}

.btn-primary{
    background:#1E3A8A;
    border:none;
}

/* ==========================
   MODAL
========================== */
.modal-header{
    background:#1E3A8A;
}

.modal-title{
    color:white;
}

/* ==========================
   FORMS
========================== */
.form-control,
.form-select{
    border-radius:6px;
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
                            <div class="progress" style="height: 1px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                                <li class="nav-item" role="peringkat-pematuhan-cadangan-teknikal">
                                    <button type="button" id="pematuhan-tab" class="nav-link rounded-pill active"
                                        data-progressbar="custom-progress-bar" data-bs-toggle="pill"
                                        data-bs-target="#pematuhan"
                                        data-title="@lang('translation.application-information')" role="tab"
                                        aria-controls="pematuhan" aria-selected="true">1</button>
                                </li>
                                <li class="nav-item" role="penilaian-spesifikasi-teknikal">
                                    <button type="button" id="penilaian-tab" class="nav-link rounded-pill"
                                        data-progressbar="custom-progress-bar" data-bs-toggle="pill"
                                        data-bs-target="#penilaian" data-title="@lang('translation.program-information')"
                                        role="tab" aria-controls="penilaian" aria-selected="false">2</button>
                                </li>
                                <li class="nav-item" role="laporan">
                                    <button type="button" id="laporan-tab" class="nav-link rounded-pill"
                                        data-progressbar="custom-progress-bar" data-bs-toggle="pill"
                                        data-bs-target="#laporan" data-title="@lang('translation.program-information')"
                                        role="tab" aria-controls="laporan" aria-selected="false">3</button>
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
                                            <button class="btn btn-primary">Seterusnya</button>
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
                                                <button class="btn btn-primary">Seterusnya</button>
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
                                            <button class="btn btn-primary">Seterusnya</button>
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
                                                <button class="btn btn-primary">Seterusnya</button>
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

            <script>
                document.getElementById('btnSimpanSenarai').addEventListener('click', function () {
                    const currentModal = bootstrap.Modal.getInstance(document.getElementById('senaraiSpesifikasiTeknikal'));

                    currentModal.hide();

                    // Wait for the modal to fully hide
                    document.getElementById('senaraiSpesifikasiTeknikal').addEventListener('hidden.bs.modal', function handler() {
                        // Clean up lingering backdrops manually
                        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style = '';

                        // Now show the original modal
                        const nextModal = new bootstrap.Modal(document.getElementById('modalSpesifikasiTeknikal1'));
                        nextModal.show();

                        // Remove event listener to avoid multiple triggers
                        document.getElementById('senaraiSpesifikasiTeknikal').removeEventListener('hidden.bs.modal', handler);
                    });
                });
            </script>
        </div>

    </div>

@endsection