@extends('layouts.v3.master')

@section('content')
<style>
/* =====================
   GENERAL LINKS
===================== */
.tender-link{
    color:#3751FF;
    font-weight:600;
    text-decoration:underline;
    cursor:pointer;
}

/* =====================
   PAGE WRAP
===================== */
.page-wrap{ padding: 0; }
#pageDetail{ display:none; }

/* =====================
   INFO STRIP (current code look)
===================== */
.breadcrumb-header{
    font-size:13px;
    color:#6b778c;
    margin-bottom:10px;
}
.info-strip{
    background:#f7f9fc;
    border:1px solid #e9edf3;
    border-radius:10px;
    padding:12px 14px;
    gap:14px;
    margin-bottom:14px;
}
.info-strip small{
    display:block;
    color:#6b778c;
    font-size:12px;
}
.info-strip .value{
    display:block;
    font-weight:700;
    color:#1f2d3d;
    font-size:13px;
    margin-top:2px;
    max-width:520px;
}

/* =====================
   TABS UTAMA 
===================== */
.custom-tabs{
    border-bottom:1px solid #e9edf3;
    gap:6px;
}
.custom-tabs .nav-link{
    border:1px solid transparent;
    border-radius:8px 8px 0 0;
    color:#0d6efd;
    font-weight:500;
    padding:10px 14px;
}
.custom-tabs .nav-link.active{
    background:#fff;
    border-color:#e9edf3 #e9edf3 #fff;
    color:#000;
    font-weight:600;
}

/* =====================
   SECTION BAR
===================== */
.section-title-bar{
    background:#f3f5f8;
    border:1px solid #e9edf3;
    border-radius:10px;
    padding:14px 14px;
    font-weight:800;
    text-transform:uppercase;
    margin-top:14px;
    margin-bottom:12px;
}

/* =====================
   BUTTON COLORS 
===================== */
.btn-primary{
    background-color:#A4161A !important;
    border-color:#A4161A !important;
}
.btn-primary:hover{
    background-color:#8F1215 !important;
    border-color:#8F1215 !important;
}

/* =====================
   TABLE STYLE
===================== */
.table thead th{
    text-align:center;
    vertical-align:middle;
}
.thead-red{
    background:#A4161A !important;
    color:#fff !important;
}
.table tbody td{
    vertical-align:middle;
}

/* =====================
   RED TABLE HEADER 
===================== */
.table thead th{
    background-color:#B11217 !important;
    color:#ffffff !important;
    text-align:left;              
    vertical-align:middle;
    font-weight:600;
    border-color:#B11217 !important;
}

/* checkbox column alignment */
.table thead th:first-child{
    text-align:center;
    width:40px;
}

</style>

<div class="container-fluid mt-3 page-wrap">

    {{-- ===================== PAGE 1: LIST ====================== --}}
    <div id="pageList">
        <div class="card p-4">
            <h4 class="fw-bold mb-4">SENARAI TENDER</h4>

            <div class="row mb-4 g-2">
                <div class="col-md-3"><input type="text" class="form-control" placeholder="No Tender"></div>
                <div class="col-md-3"><input type="text" class="form-control" placeholder="Tajuk Perolehan"></div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>Status</option>
                        <option>Diluluskan</option>
                        <option>Dalam Proses</option>
                    </select>
                </div>
                <div class="col-md-3"><input type="date" class="form-control"></div>
            </div>

            <button class="btn btn-primary w-100 mb-3">Tapis</button>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-red">
                        <tr>
                            <th style="width:220px;">No Tender</th>
                            <th>Tajuk Perolehan</th>
                            <th style="width:140px;">Tarikh</th>
                            <th style="width:160px;">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- contoh row 1: perkhidmatan/bekalan --}}
                        <tr>
                            <td class="text-center">QT21000000023741</td>
                            <td>
                                <span class="tender-link"
                                    data-kategori="perkhidmatan_bekalan"
                                    data-no="QT21000000023741"
                                    data-ptj="BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN"
                                    data-status="Penyediaan Pemilihan Akhir Pembekal"
                                    data-tamat="17/01/2022"
                                    onclick="openTender(this)">
                                    (Klik) TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX
                                </span>
                            </td>
                            <td class="text-center">03/03/2024</td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">Dalam Proses</span>
                            </td>
                        </tr>

                        {{-- contoh row 2: kerja --}}
                        <tr>
                            <td class="text-center">QT21000000023799</td>
                            <td>
                                <span class="tender-link"
                                    data-kategori="kerja"
                                    data-no="QT21000000023799"
                                    data-ptj="BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN"
                                    data-status="Penyediaan Pemilihan Akhir Pembekal"
                                    data-tamat="17/01/2022"
                                    onclick="openTender(this)">
                                    (Klik) TENDER KERJA-KERJA NAIK TARAF INFRASTRUKTUR RANGKAIAN ICT
                                </span>
                            </td>
                            <td class="text-center">05/03/2024</td>
                            <td class="text-center">
                                <span class="badge bg-success">Aktif</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- ===================== PAGE 2: DETAIL (current code UI) ====================== --}}
    <div id="laporanArea">
        <div id="pageDetail">
            <div class="card p-4">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="breadcrumb-header mb-0">
                        STOS &gt; <strong>Penyediaan Kertas Taklimat dan Pengesyoran Pembekal</strong>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="backToList()">Kembali</button>
                </div>

                {{-- info strip dynamic --}}
                <div class="info-strip d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <small>No. Sebut Harga / Tender</small>
                        <span class="value" id="dNo">-</span>
                    </div>
                    <div>
                        <small>PTJ</small>
                        <span class="value" id="dPtj">-</span>
                    </div>
                    <div>
                        <small>Status</small>
                        <span class="value" id="dStatus">-</span>
                    </div>
                    <div>
                        <small>Sah Laku Tawaran Tamat</small>
                        <span class="value" id="dTamat">-</span>
                    </div>
                </div>

               {{-- Tabs utama (current code) --}}
                <ul class="nav nav-tabs custom-tabs" id="mainTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link main-tab-btn active"
                                id="tab-penyediaan-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-penyediaan"
                                type="button" role="tab">
                            Penyediaan Mesyuarat
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link main-tab-btn"
                                id="tab-taklimat-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-taklimat"
                                type="button" role="tab">
                            Paparan Kertas Taklimat
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link main-tab-btn"
                                id="tab-pemilihan-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-pemilihan"
                                type="button" role="tab">
                            Memuktamadkan Pemilihan Pembekal
                        </button>
                    </li>

                    {{-- NEW: Pengesyoran Pembekal --}}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link main-tab-btn"
                                id="tab-pengesyoran-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-pengesyoran"
                                type="button" role="tab">
                            Pengesyoran Pembekal
                        </button>
                    </li>

                    {{-- NEW: Penyediaan Jadual Bidaan --}}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link main-tab-btn"
                                id="tab-jadual-bidaan-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-jadual-bidaan"
                                type="button" role="tab">
                            Penyediaan Jadual Bidaan
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link main-tab-btn"
                                id="tab-keputusan-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-keputusan"
                                type="button" role="tab">
                            Kertas Keputusan
                        </button>
                    </li>

                </ul>

                <div class="tab-content mt-3" id="mainTabContent">

                    {{-- ============ TAB 1: Penyediaan Mesyuarat ============ --}}
                    <div class="tab-pane fade show active" id="tab-penyediaan" role="tabpanel">
                        <div class="section-title-bar">PERINCIAN MESYUARAT</div>

                        <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-2">
                        <thead class="text-white text-center" style="background-color:#2d3e84;">
                            <tr>
                                <th style="width:42px;"></th>
                                <th>Bil Mesyuarat <span class="text-danger">*</span></th>
                                <th>Tarikh Mesyuarat <span class="text-danger">*</span></th>
                                <th>Tajuk Agenda <span class="text-danger">*</span></th>
                                <th>Tempat <span class="text-danger">*</span></th>
                                <th>No. Kod Kertas <span class="text-danger">*</span></th>
                                <th>Status <span class="text-danger">*</span></th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td><input type="text" class="form-control form-control-sm" placeholder=""></td>
                                <td><input type="date" class="form-control form-control-sm" value="2021-10-18"></td>
                                <td><input type="text" class="form-control form-control-sm" placeholder=""></td>
                                <td><input type="text" class="form-control form-control-sm"
                                        value="Bilik Mesyuarat Tingkat 3"></td>
                                <td><input type="text" class="form-control form-control-sm" placeholder=""></td>
                                <td>
                                    <select class="form-select form-select-sm">
                                        <option selected>Belum Selesai</option>
                                        <option>Selesai</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control form-control-sm" placeholder=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button type="button" class="btn btn-success">Tambah</button>
                    <button type="button" class="btn btn-warning text-white">Hapus</button>
                </div>



                <!-- Save/Submit buttons (bawah kanan, seperti gambar) -->
                <div class="d-flex justify-content-end gap-3 mt-3">
                    <button type="button" class="btn" style="background-color:#19c1a7;color:#fff;">Simpan</button>
                    <button type="button" class="btn" style="background-color:#324d92;color:#fff;">Hantar</button>
                </div>
                    </div>

                    {{-- ============ TAB 2: Paparan Kertas Taklimat ============ --}}
                    <div class="tab-pane fade" id="tab-taklimat" role="tabpanel">
                        <h6 class="fw-bold mb-3">SENARAI DOKUMEN</h6>
                        <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color:#2d3e84;">
                            <tr>
                                <th>Kandungan</th>
                                <th width="15%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Laporan Jawatankuasa Pembuka</td>
                                <td class="text-center"><a href="#" class="text-primary">Papar</a></td>
                            </tr>
                            <tr>
                                <td>Laporan Jawatankuasa Teknikal</td>
                                <td class="text-center"><a href="#" class="text-primary">Papar</a></td>
                            </tr>
                            <tr>
                                <td>Laporan Jawatankuasa Kewangan</td>
                                <td class="text-center"><a href="#" class="text-primary">Papar</a></td>
                            </tr>
                            <tr>
                                <td>Kertas Taklimat (Perakuan Jabatan)</td>
                                <td class="text-center"><a href="#" class="text-primary">Papar</a></td>
                            </tr>
                            <tr>
                                <td>Laporan Bidaan</td>
                                <td class="text-center"><a href="#" class="text-primary">Papar</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                    </div>

                    {{-- ============ TAB 3: Memuktamadkan Pemilihan Pembekal ============ --}}
                    <div class="tab-pane fade" id="tab-pemilihan" role="tabpanel">
                        <h6 class="fw-bold">KEPUTUSAN PIHAK BERKUASA MELULUS</h6>
                        <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Keputusan Mesyuarat</label>
                        <select class="form-select">
                            <option selected>Pengesyoran Pembekal</option>
                            <option>Penilaian Semula</option>
                            <option>Iklan Semula</option>
                            <option>Kemukakan kepada Pihak Berkuasa Yang Lebih Tinggi</option>
                            <option>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kaedah Memuktamadkan Pembekal</label>
                        <select class="form-select">
                            <option selected>Bidaan</option>
                            <option>Pemilihan Terus</option>
                            <option>Pemilihan Lebih Daripada Satu Syarikat</option>
                        </select>
                    </div>
                </div>

                <h6 class="fw-bold">SENARAI ITEM</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color:#2d3e84;">
                            <tr>
                                <th></th>
                                <th>Item</th>
                                <th>Jenis Item</th>
                                <th>Unit Ukuran</th>
                                <th>Jenis Harga</th>
                                <th>Dibatalkan</th>
                                <th>Pembekal Dipilih</th>
                                <th>Kuantiti</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><input type="checkbox"></td>
                                <td>Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                                <td>Perkhidmatan</td>
                                <td>Activity Unit</td>
                                <td>Biasa Standard</td>
                                <td>
                                    <select class="form-select">
                                        <option selected>Tidak</option>
                                        <option>Ya</option>
                                    </select>
                                </td>
                                <td>2</td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-success">Terapkan untuk semua</button>
                </div>

                <h6 class="fw-bold mt-4">SENARAI PEMBEKAL</h6>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="text-white" style="background-color:#2d3e84;">
                            <tr>
                                <th>Bil</th>
                                <th>Status Bumiputra</th>
                                <th>Harga Tawaran (RM)</th>
                                <th>Jumlah Skor</th>
                                <th>Kedudukan Penilaian Teknikal Kewangan</th>
                                <th>Status Pendaftaran MOF</th>
                                <th colspan="2">Maklumat Tambahan</th>
                                <th>Keputusan oleh Urusetia</th>
                                <th>Catatan Urusetia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2/2</td>
                                <td>Ya</td>
                                <td>360,000.00</td>
                                <td>96.43</td>
                                <td>1</td>
                                <td>Aktif</td>
                                <td>Tindakan Disiplin Diambil</td>
                                <td><button class="btn btn-light"><i class="bi bi-file-earmark"></i></button></td>
                                <td>Disyorkan</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1/2</td>
                                <td>Tidak</td>
                                <td>330,000.00</td>
                                <td>94.53</td>
                                <td>2</td>
                                <td>Aktif</td>
                                <td></td>
                                <td><button class="btn btn-light"><i class="bi bi-file-earmark"></i></button></td>
                                <td>Disyorkan</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="pengakuan">
                    <label class="form-check-label" for="pengakuan">Saya mengesahkan petender diatas layak untuk
                        menyertai Bidaan.</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-success">Simpan</button>
                    <button class="btn btn-primary">Hantar</button>
                </div>
                    </div>

                    {{-- ============ TAB: Pengesyoran Pembekal ============ --}}
<div class="tab-pane fade" id="tab-pengesyoran" role="tabpanel">


    <div class="section-grey">Senarai Item</div>
    <div class="text-primary small mb-2" style="font-style:italic;">
        Sila klik pada item untuk melihat senarai pembekal
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-blue align-middle">
            <thead class="text-center">
            <tr>
                <th style="width:36px;"></th>
                <th>Item</th>
                <th style="width:140px;">Jenis Item</th>
                <th style="width:120px;">Unit Ukuran</th>
                <th style="width:120px;">Jenis Harga</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td>Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                <td class="text-center">Perkhidmatan</td>
                <td class="text-center">Activity Unit</td>
                <td class="text-center">Biasa Standard</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section-grey">Senarai Pembekal</div>
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-blue text-center align-middle">
            <thead>
            <tr>
                <th style="width:60px;">Bil</th>
                <th style="width:110px;">Status Bumiputra</th>
                <th style="width:120px;">Harga Tawaran (RM)</th>
                <th style="width:90px;">Jumlah Skor</th>
                <th style="width:130px;">Kedudukan Penilaian Teknikal</th>
                <th style="width:150px;">Status Pendaftaran MOF</th>
                <th colspan="2" style="width:220px;">Maklumat Tambahan</th>
                <th style="width:180px;">Kaedah Memuktamadkan Pembekal oleh SULP</th>
                <th style="width:120px;">Harga Bidaan (RM)</th>
            </tr>
            <tr>
                <th colspan="6"></th>
                <th>Tindakan Disiplin Diambil</th>
                <th>Lembaga Pengarah</th>
                <th colspan="2"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>2/2</td>
                <td>Ya</td>
                <td>360,000.00</td>
                <td>96.43</td>
                <td>1</td>
                <td>Aktif</td>
                <td></td>
                <td><button class="btn btn-light btn-sm"><i class="bi bi-file-earmark"></i></button></td>
                <td>Bidaan</td>
                <td>300,000</td>
            </tr>
            <tr>
                <td>1/2</td>
                <td>Tidak</td>
                <td>330,000.00</td>
                <td>94.53</td>
                <td>2</td>
                <td>Aktif</td>
                <td></td>
                <td><button class="btn btn-light btn-sm"><i class="bi bi-file-earmark"></i></button></td>
                <td>Bidaan</td>
                <td>330,000</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section-grey">Catatan</div>
    <div class="row g-3 align-items-start">
        <div class="col-md-6">
            <textarea class="form-control" rows="4"></textarea>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="sahBidaan">
                <label class="form-check-label" for="sahBidaan">Saya mengesahkan Bidaan</label>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-end">
            <div class="d-flex gap-2">
                <button class="btn btn-teal px-4">Simpan</button>
                <button class="btn btn-navy px-4">Hantar</button>
            </div>
        </div>
    </div>

</div>

{{-- ============ TAB: Penyediaan Jadual Bidaan ============ --}}
<div class="tab-pane fade" id="tab-jadual-bidaan" role="tabpanel">
    <h6 class="fw-bold">Penyediaan Jadual Bidaan</h6>

    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">Tarikh Bidaan Mula<span class="text-danger">*</span></label>
            <input type="date" class="form-control" value="2021-10-11">
        </div>
        <div class="col-md-3">
            <label class="form-label">Masa Bidaan Mula<span class="text-danger">*</span></label>
            <input type="time" class="form-control" value="12:00">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tarikh Bidaan Tamat<span class="text-danger">*</span></label>
            <input type="date" class="form-control" value="2021-10-11">
        </div>
        <div class="col-md-3">
            <label class="form-label">Masa Bidaan Tamat<span class="text-danger">*</span></label>
            <input type="time" class="form-control" value="17:00">
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary">Mula Bidaan</button>
    </div>
</div>


                    {{-- ============ TAB 4: Kertas Keputusan ============ --}}
                    <div class="tab-pane fade" id="tab-keputusan" role="tabpanel">
                        <div class="section-title-bar">KERTAS KEPUTUSAN</div>
                <div class="section-header text-uppercase">Syarat-Syarat</div>
                    <div class="py-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Dengan Syarat</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="syarat" id="syaratYa" value="Ya">
                                    <label class="form-check-label" for="syaratYa">Ya</label>
                                </div>
                                <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="radio" name="syarat" id="syaratTidak" value="Tidak"
                                        checked>
                                    <label class="form-check-label" for="syaratTidak">Tidak</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Jika Ya, sila nyatakan</label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- PENGESYORAN --}}
                    <div class="section-header text-uppercase mt-3">Pengesyoran</div>
                    <div class="py-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Catatan</label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="3"
                                    placeholder="Pengesyoran Urusetia Perolehan adalah berdasarkan keputusan Jawatankuasa Penilaian...."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- JUSTIFIKASI --}}
                    <div class="section-header text-uppercase mt-3">Justifikasi</div>
                    <div class="py-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Justifikasi Pemilihan Pembekal <span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-select">
                                    <option selected>Harga dalam lingkungan harga indikatif jabatan</option>
                                    <option>Prestasi kerja terdahulu</option>
                                    <option>Kepakaran teknikal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                {{-- KERTAS KEPUTUSAN (OPTIONAL) --}}
                <div class="section-header text-uppercase mt-3">Kertas Keputusan (Optional)</div>
                <div class="py-4">
                    <div class="row justify-content-start align-items-center g-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="fw-semibold mb-1">Lampiran</div>
                                <small class="text-info d-block" style="line-height:1.2">
                                    (Memuat naik kertas keputusan yang telah<br>ditanda tangan oleh kesemua ahli
                                    PBM)
                                </small>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center gap-3">
                                <label class="btn btn-outline-success d-inline-flex align-items-center px-3">
                                    <i class="bi bi-cloud-arrow-up me-2"></i> Muat Naik
                                    <input type="file" class="d-none">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KEPUTUSAN --}}
                <div class="section-header text-uppercase mt-3">Keputusan</div>
                <div class="py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label mb-0">Keputusan</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-select">
                                <option selected>Keputusan</option>
                                <option>Lulus</option>
                                <option>Tawaran Semula</option>
                                <option>Batal</option>
                                <option>Tangguh</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="section-header text-uppercase mt-3">Catatan</div>
                <div class="py-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label mb-0">Catatan</label>
                        </div>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex justify-content-end gap-3 mt-2">
                    <button type="button" class="btn" style="background-color:#19c1a7;color:#fff;">Simpan</button>
                    <button type="button" class="btn" style="background-color:#324d92;color:#fff;">Hantar</button>
                </div>                    
            
            </div>

            <div class="tab-pane fade" id="jadual-bidaan">
                <h6 class="fw-bold">Penyediaan Jadual Bidaan</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Tarikh Bidaan Mula<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" value="2021-10-11">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Masa Bidaan Mula<span class="text-danger">*</span></label>
                        <input type="time" class="form-control" value="12:00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarikh Bidaan Tamat<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" value="2021-10-11">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Masa Bidaan Tamat<span class="text-danger">*</span></label>
                        <input type="time" class="form-control" value="17:00">
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">Mula Bidaan</button>
                </div>
            </div>

                </div>

            </div>
        </div>
    </div>

</div>

<script>
/**
 * DESIGN+FLOW ikut contoh:
 * - pageList (table) -> klik -> pageDetail (tabs)
 * - tab yang dipaparkan ikut kategori (kerja / perkhidmatan_bekalan)
 *
 * NOTE:
 * - Kita guna Bootstrap tab, jadi untuk hide tab, kita hide nav-item.
 * - Lepas hide/show, kita paksa active tab pertama yang available.
 */

// cache
const pageList   = document.getElementById('pageList');
const pageDetail = document.getElementById('pageDetail');

// info targets
const dNo     = document.getElementById('dNo');
const dPtj    = document.getElementById('dPtj');
const dStatus = document.getElementById('dStatus');
const dTamat  = document.getElementById('dTamat');

// tab config (ID button)
const tabsMap = {
    penyediaan: document.getElementById('tab-penyediaan-btn').closest('li'),
    taklimat:   document.getElementById('tab-taklimat-btn').closest('li'),
    pemilihan:  document.getElementById('tab-pemilihan-btn').closest('li'),
    keputusan:  document.getElementById('tab-keputusan-btn').closest('li'),
};

function openTender(el){
    const kategori = el.dataset.kategori;

    // inject header info
    dNo.textContent     = el.dataset.no || '-';
    dPtj.textContent    = el.dataset.ptj || '-';
    dStatus.textContent = el.dataset.status || '-';
    dTamat.textContent  = el.dataset.tamat || '-';

    // switch page
    pageList.style.display = 'none';
    pageDetail.style.display = 'block';

    // reset: show all first
    Object.values(tabsMap).forEach(li => li.style.display = 'block');

    // FLOW ikut contoh (anda boleh ubah rule sini)
    if(kategori === 'perkhidmatan_bekalan'){
        // show all 4
        showMainTabs(['penyediaan','taklimat','pemilihan','keputusan'], 'penyediaan');
    } else if(kategori === 'kerja'){
        // contoh: kerja -> hide "taklimat"
        showMainTabs(['penyediaan','pemilihan','keputusan'], 'penyediaan');
    } else {
        // default
        showMainTabs(['penyediaan','taklimat','pemilihan','keputusan'], 'penyediaan');
    }
}

function showMainTabs(tabList, activeTab){
    // hide all
    Object.keys(tabsMap).forEach(key => {
        tabsMap[key].style.display = 'none';
    });

    // show only requested
    tabList.forEach(key => {
        if(tabsMap[key]) tabsMap[key].style.display = 'block';
    });

    // force active tab to activeTab (bootstrap)
    const btnId = `tab-${activeTab}-btn`;
    const btn = document.getElementById(btnId);

    if(btn){
        // remove current active
        document.querySelectorAll('#mainTabs .nav-link').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('#mainTabContent .tab-pane').forEach(p => p.classList.remove('show','active'));

        // trigger bootstrap tab show
        const tab = new bootstrap.Tab(btn);
        tab.show();
    }
}

function backToList(){
    pageDetail.style.display = 'none';
    pageList.style.display = 'block';
}

// expose to inline onclick
window.openTender = openTender;
window.backToList = backToList;
</script>
@endsection
