@section('content')

<style>
/* =====================================================
   STEPPER WRAPPER
===================================================== */
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
    padding: 0 40px;
    position: relative;
}

/* =====================================================
   STEPPER ITEM
===================================================== */
.stepper-item {
    flex: 1;
    text-align: center;
    position: relative;
}

/* =====================================================
   CONNECTOR LINE
===================================================== */
.stepper-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 18px;                 /* vertical center of circle */
    left: 50%;
    width: 100%;
    height: 3px;
    background: #E5E7EB;       /* light grey (default) */
    z-index: 0;
}

/* Connector for COMPLETED step */
.stepper-item.done:not(:last-child)::after {
    background: #C0392B;       /* red */
}

/* Reset future connectors */
.stepper-item.active ~ .stepper-item:not(:last-child)::after {
    background: #E5E7EB;
}

/* =====================================================
   STEP CIRCLE
===================================================== */
.step-counter {
    width: 36px;
    height: 36px;
    line-height: 36px;
    border-radius: 50%;
    background: #E5E7EB;       /* grey (default) */
    color: #374151;            /* dark grey text */
    font-weight: 700;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

/* ACTIVE STEP (current) */
.stepper-item.active .step-counter {
    background: #C0392B;       /* red */
    color: #FFFFFF;
}

/* COMPLETED STEP */
.stepper-item.done .step-counter {
    background: #C0392B;       /* red */
    color: #FFFFFF;
}

/* =====================================================
   STEP LABEL
===================================================== */
.step-label {
    margin-top: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;            /* grey */
}

/* Active & completed label */
.stepper-item.active .step-label,
.stepper-item.done .step-label {
    color: #C0392B;            /* red */
}


/* =====================================================
   TABLE HEADER
   ===================================================== */
.table-header {
    background: #C0392B;
    color: white;
    text-align: center;
    font-weight: bold;
}

/* =====================================================
   BUTTONS
   ===================================================== */
.btn-papar {
    background: #00a988;
    color: white;
    padding: 6px 18px;
    font-weight: bold;
    border-radius: 6px;
}

.btn-simpan {
    background: #00a988;
    color: white;
    padding: 8px 24px;
    border-radius: 6px;
    font-weight: bold;
}

/* =====================================================
   SECTION TITLES
   ===================================================== */
.section-title {
    background: #f5f5f5;
    padding: 8px 12px;
    font-weight: bold;
    border-left: 4px solid #C0392B;
    margin: 15px 0;
}

/* =====================================================
   SUB NOTES / HINTS
   ===================================================== */
.sub-note {
    font-size: 12px;
    color: #007aff;
    margin-bottom: 5px;
}

/* =====================================================
   GREY STRIP (SUMMARY HEADERS)
   ===================================================== */
.grey-strip {
    background: #f0f0f0;
    font-weight: 600;
    padding: 6px 10px;
    font-size: 13px;
}

/* =====================================================
   NESTED TABS (KEWANGAN / RUMUSAN)
   ===================================================== */
.nested-tabs {
    border-bottom: 1px solid #ddd;
    margin-bottom: 10px;
}

.nested-tab-btn {
    border: none;
    background: transparent;
    padding: 6px 20px;
    font-weight: 600;
    cursor: pointer;
    margin-right: 3px;
}

/* Active nested tab */
.nested-tab-btn.active {
    background: #c0392b;
    color: #fff;
    border-radius: 4px 4px 0 0;
}
</style>

<div class="card">
    <div class="card-body">

        {{-- ====================== HEADER INFO (COMMON) ====================== --}}
        <div class="row mb-2">
            <div class="col-md-3">
                <strong>No. Sebut Harga / Tender</strong><br>QT21000000023741
            </div>
            <div class="col-md-3">
                <strong>PTJ</strong><br>BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN
            </div>
            <div class="col-md-3">
                <strong>Status</strong><br>Menunggu Penilaian Cadangan Kewangan
            </div>
            <div class="col-md-3">
                <strong>Tempoh Sah Laku Tawaran (Hari)</strong><br>90
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col-md-3">
                <strong>Tajuk Perolehan</strong><br>
                Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX
            </div>
            <div class="col-md-3">
                <strong>Sah Laku Tawaran Tamat</strong><br>17/01/2022
            </div>
        </div>

        <hr>

        {{-- ====================== STEPPER (ALWAYS VISIBLE) ====================== --}}
        <div class="stepper-wrapper">
            <div class="stepper-item active" id="stepItem1">
                <div class="step-counter" id="stepCircle1">1</div>
                <div class="step-label">Pematuhan Dokumentasi</div>
            </div>

            <div class="stepper-item" id="stepItem2">
                <div class="step-counter" id="stepCircle2">2</div>
                <div class="step-label">Penyata Bulanan Bank</div>
            </div>

            <div class="stepper-item" id="stepItem3">
                <div class="step-counter" id="stepCircle3">3</div>
                <div class="step-label">Pematuhan Spesifikasi Kewangan</div>
            </div>

            <div class="stepper-item" id="stepItem4">
                <div class="step-counter" id="stepCircle4">4</div>
                <div class="step-label">Penyediaan Laporan</div>
            </div>

        </div>
        {{-- ====================== STEP 1 CONTENT ========================== --}}
        <div id="step1Content">

            <div class="section-title">PEMATUHAN CADANGAN KEWANGAN</div>

            {{-- Nested tabs Step 1 --}}
            <div class="nested-tabs">
                <button class="nested-tab-btn active"
                        data-step="1" data-tab="kewangan"
                        onclick="showNestedTab(1, 'kewangan')">Kewangan</button>
                <button class="nested-tab-btn"
                        data-step="1" data-tab="rumusan"
                        onclick="showNestedTab(1, 'rumusan')">Rumusan</button>
            </div>

            {{-- STEP 1 - KEWANGAN --}}
            <div id="step1TabKewangan">
                <div class="sub-note">Klik butang Papar untuk membuat penilaian.</div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Tajuk / Dokumen</th>
                                <th>Mekanisma</th>
                                <th>Status Penilaian</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                                <td>Spesifikasi</td>
                                <td>Selesai</td>
                                <td>
                                    <button class="btn btn-papar"
                                            onclick="openPenilaianModal('screen1')">
                                        Papar
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Maklumat Profil Petender</td>
                                <td>Borang Atas Talian</td>
                                <td>Selesai</td>
                                <td><button class="btn btn-papar">Papar</button></td>
                            </tr>
                            <tr>
                                <td>Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</td>
                                <td>Borang Atas Talian</td>
                                <td>Selesai</td>
                                <td><button class="btn btn-papar">Papar</button></td>
                            </tr>
                            <tr>
                                <td>Salinan Sijil Pendaftaran dengan Kementerian Kewangan</td>
                                <td>Petender Muat Naik</td>
                                <td>Selesai</td>
                                <td><button class="btn btn-papar">Papar</button></td>
                            </tr>
                            <tr>
                                <td>Surat Akuan Pembida</td>
                                <td>PTJ Muat Naik</td>
                                <td>Selesai</td>
                                <td><button class="btn btn-papar">Papar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- STEP 1 - RUMUSAN (gambar 1) --}}
            <div id="step1TabRumusan" style="display:none;">

                <div class="grey-strip">
                    SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PEMATUHAN DOKUMENTASI
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Bil</th>
                                <th>Nama Syarikat / Ulasan</th>
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

                <div class="row my-3">
                    <div class="col-md-3">
                        <label>Bilangan Pembekal</label>
                        <input type="text" class="form-control" value="2">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmStep1">
                    <label class="form-check-label" for="confirmStep1">
                        Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                    </label>
                </div>

                <div class="grey-strip">
                    SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PEMATUHAN DOKUMENTASI
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Bil</th>
                                <th>Nama Syarikat / Ulasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center">Tiada rekod dijumpai</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row my-3">
                    <div class="col-md-3">
                        <label>Bilangan Pembekal</label>
                        <input type="text" class="form-control" value="0">
                    </div>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary px-4" onclick="showStep(2)">Seterusnya</button>
            </div>
        </div>

        {{-- ====================== STEP 2 CONTENT ========================== --}}
        <div id="step2Content" style="display:none;">

            <div class="section-title">PENYATA BULANAN BANK</div>

            {{-- Nested tabs Step 2 --}}
            <div class="nested-tabs">
                <button class="nested-tab-btn active"
                        data-step="2" data-tab="kewangan"
                        onclick="showNestedTab(2, 'kewangan')">Kewangan</button>
                <button class="nested-tab-btn"
                        data-step="2" data-tab="rumusan"
                        onclick="showNestedTab(2, 'rumusan')">Rumusan</button>
            </div>

            {{-- STEP 2 - KEWANGAN (table + modal) --}}
            <div id="step2TabKewangan">
                <div class="sub-note">Klik butang Papar untuk membuat penilaian.</div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Tajuk / Dokumen</th>
                                <th>Mekanisma</th>
                                <th>Status Penilaian</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</td>
                                <td>Borang Atas Talian</td>
                                <td>Selesai</td>
                                <td>
                                    <button class="btn btn-papar" onclick="openStep2Modal()">Papar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- STEP 2 - RUMUSAN--}}
            <div id="step2TabRumusan" style="display:none;">

                <div class="grey-strip">
                    SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PENYATA BULANAN BANK
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Bil</th>
                                <th>Nama Syarikat / Ulasan</th>
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

                <div class="row my-3">
                    <div class="col-md-3">
                        <label>Bilangan Pembekal</label>
                        <input type="text" class="form-control" value="2">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmStep2">
                    <label class="form-check-label" for="confirmStep2">
                        Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                    </label>
                </div>

                <div class="grey-strip">
                    SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PENYATA BULANAN BANK
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Bil</th>
                                <th>Nama Syarikat / Ulasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center">Tiada rekod dijumpai</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row my-3">
                    <div class="col-md-3">
                        <label>Bilangan Pembekal</label>
                        <input type="text" class="form-control" value="0">
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary px-4" onclick="showStep(1)">Sebelumnya</button>
                <button class="btn btn-primary px-4" onclick="showStep(3)">Seterusnya</button>
            </div>
        </div>

        {{-- ====================== STEP 3 CONTENT ========================== --}}
        <div id="step3Content" style="display:none;">

            <div class="section-title">CADANGAN KEWANGAN</div>

            {{-- Nested tabs Step 3 --}}
            <div class="nested-tabs">
                <button class="nested-tab-btn active"
                        data-step="3" data-tab="kewangan"
                        onclick="showNestedTab(3, 'kewangan')">Kewangan</button>
                <button class="nested-tab-btn"
                        data-step="3" data-tab="rumusan"
                        onclick="showNestedTab(3, 'rumusan')">Rumusan</button>
            </div>

            {{-- STEP 3 - KEWANGAN --}}
            <div id="step3TabKewangan">
                <div class="sub-note">Klik butang Papar untuk meneruskan penilaian kewangan.</div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Tajuk / Dokumen</th>
                                <th>Mekanisma</th>
                                <th>Status Penilaian</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                                <td>Spesifikasi</td>
                                <td>Selesai</td>
                                <td>
                                    <button class="btn btn-papar"
                                            onclick="openPenilaianModal('screen3')">
                                        Papar
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Maklumat Profil Petender</td>
                                <td>Borang Atas Talian</td>
                                <td>Selesai</td>
                                <td><button class="btn btn-papar">Papar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- STEP 3 - RUMUSAN --}}
            <div id="step3TabRumusan" style="display:none;">

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Paparan mengikut</label>
                        <select class="form-select">
                            <option>Item</option>
                            <option>Syarikat</option>
                        </select>
                    </div>
                </div>

                <div class="grey-strip">SENARAI ITEM</div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grey-strip">SENARAI PEMBEKAL MELEPASI PENILAIAN KEWANGAN</div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Kedudukan</th>
                                <th>Bil</th>
                                <th>Jumlah Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>1/2</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2/2</td>
                                <td>100</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Penetapan Penanda Aras Tahap Lulus (%)</label>
                        <input type="text" class="form-control" value="51">
                    </div>
                    <div class="col-md-3">
                        <label>Bilangan Pembekal</label>
                        <input type="text" class="form-control" value="2">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmStep3">
                    <label class="form-check-label" for="confirmStep3">
                        Saya mengesahkan petender di atas layak untuk disyorkan kepada Lembaga.
                    </label>
                </div>

                <div class="grey-strip">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN KEWANGAN</div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-header">
                            <tr>
                                <th>Kod Pembekal</th>
                                <th>Jumlah Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center">Tiada rekod dijumpai</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row my-3">
                    <div class="col-md-3">
                        <label>Bilangan Pembekal</label>
                        <input type="text" class="form-control" value="0">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary px-4" onclick="showStep(2)">Sebelumnya</button>
                <button class="btn btn-primary px-4" onclick="showStep(4)">Seterusnya</button>
            </div>
        </div>

        {{-- =============================== STEP 4 CONTENT =============================== --}}
        <div id="step4Content" style="display:none;">

            <div class="section-title">PENILAIAN PERINGKAT PERTAMA</div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-header">
                        <tr>
                            <th>Bil</th>
                            <th>Nama Syarikat</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1/2</td>
                            <td>Syarikat A</td>
                            <td>Ulasan…</td>
                        </tr>
                        <tr>
                            <td>2/2</td>
                            <td>Syarikat B</td>
                            <td>Ulasan…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section-title">PENILAIAN PERINGKAT KEDUA</div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-header">
                        <tr>
                            <th>Bil</th>
                            <th>Nama Syarikat</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1/2</td>
                            <td>Syarikat A</td>
                            <td>Ulasan…</td>
                        </tr>
                        <tr>
                            <td>2/2</td>
                            <td>Syarikat B</td>
                            <td>Ulasan…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section-title">PENILAIAN PERINGKAT KETIGA</div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-header">
                        <tr>
                            <th>Kriteria</th>
                            <th>Bil</th>
                            <th>Jumlah Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nilai Tawaran</td>
                            <td>1/2</td>
                            <td>100</td>
                        </tr>
                        <tr>
                            <td>Tempoh Siap</td>
                            <td>2/2</td>
                            <td>100</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section-title">PENYOKONGAN</div>
            <textarea class="form-control" rows="3">
Dengan ini, PTJ mengesyorkan XX (YY) untuk melaksanakan projek ini berdasarkan justifikasi di atas.
            </textarea>

            <div class="d-flex justify-content_between mt-4">
                <button class="btn btn-secondary px-4" onclick="showStep(3)">Sebelumnya</button>
                <div>
                    <button class="btn btn-outline-success px-4">Laporan</button>
                    <button class="btn btn-primary px-4">Hantar</button>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- ====================== REUSABLE MODAL ====================== --}}
<div class="modal fade" id="penilaianModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="penilaianModalBody"><!-- content injected by JS --></div>
        </div>
    </div>
</div>

{{-- ====================== MODAL TEMPLATES (HIDDEN) ====================== --}}

{{-- Screen 1 – Modal Penilaian Dokumen Kewangan --}}
<div id="tpl-screen1" style="display:none;">
    <div class="grey-strip">PEMATUHAN DOKUMEN KEWANGAN</div>
    <div class="mb-2">
        <strong>Dokumen :</strong> Perkhidmatan Penilaian Forensik Keatas Sistem XXXX
    </div>

    <div class="grey-strip mt-3">SENARAI DOKUMEN PEMBEKAL</div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-header">
                <tr>
                    <th>Dokumen</th>
                    <th>Status Pematuhan</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">
                                Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.pdf
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select">
                            <option selected>Sila Pilih</option>
                            <option value="mematuhi">Mematuhi</option>
                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control" rows="2"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">
                                Perkhidmatan Penilaian Forensik Keatas Sistem XXXxn.pdf
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select">
                            <option selected>Sila Pilih</option>
                            <option value="mematuhi">Mematuhi</option>
                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control" rows="2"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3 mb-2">
        <button class="btn btn-simpan">Simpan</button>
    </div>
</div>

{{-- ====================== MODAL STEP 2 FULL CODE ====================== --}}

{{-- Modal Wrapper --}}
<div class="modal fade" id="modalPenilaian2" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="padding:20px;">
            <div id="modalBodyStep2">

                {{-- ===== SCREEN 2A (Initial Table) ===== --}}
                <div id="screen2A">

                    <h5 class="fw-bold">Penyata Bank Terkini (3 Bulan Terakhir)</h5>
                    <hr>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-header">
                                <tr>
                                    <th>Tajuk / Dokumen</th>
                                    <th>Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Kunci Kira-kira Tahunan (Nota - Untuk syarikat ROC, ini mestilah salinan
                                        yang telah diaudit)
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-papar" onclick="showStep2Details()">
                                            Papar
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Pengesahan dari Institusi Kewangan untuk tiga bulan penyata bank</td>
                                    <td class="text-center">
                                        <button class="btn btn-papar" onclick="showStep2Details()">
                                            Papar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- ===== SCREEN 2B (Hidden until click) ===== --}}
                <div id="screen2B" style="display:none;">

                    <div class="grey-strip">BULANAN (PENYATA BANK)</div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-header">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Amaun (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>6</td>
                                    <td><input type="text" class="form-control" value="500,000.00"></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td><input type="text" class="form-control" value="300,000.00"></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td><input type="text" class="form-control" value="200,000.00"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="1,000,000.00">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="333,333.33">
                        </div>
                        <div class="col-md-3">
                            <label>Skor Automatik</label>
                            <input type="text" class="form-control" value="10">
                        </div>
                    </div>

                    <div class="grey-strip">JULAT SKOR</div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-header">
                                <tr>
                                    <th>Dari</th>
                                    <th>Hingga</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>10064.99</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>10065</td>
                                    <td>10205</td>
                                    <td>10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select">
                                <option>Layak / Tidak Layak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Catatan</label>
                            <textarea class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-3 mb-2">
                        <button class="btn btn-simpan">Simpan</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- Screen 3 – Modal Cadangan Kewangan (Perbandingan) --}}
<div id="tpl-screen3" style="display:none;">

    <div class="grey-strip">CADANGAN KEWANGAN</div>
    <div class="mb-2">
        <strong>Item :</strong> Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.
    </div>

    <div class="grey-strip mt-3">
        SEMAKAN KEWANGAN
        <span class="text-primary small d-block">
            Semakan lengkap dinilai dan butang Menilai bertukar kepada Lihat
        </span>
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle">
            <thead class="table-header">
                <tr>
                    <th>Status Bumiputra</th>
                    <th>Harga Tawaran (RM)</th>
                    <th>Jumlah Skor</th>
                    <th>Perbezaan Harga (RM)</th>
                    <th>Perbezaan (%)</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bukan</td>
                    <td>330,500.00</td>
                    <td>50/60</td>
                    <td>5,000.00</td>
                    <td>0</td>
                    <td class="text-center">
                        <button class="btn btn-papar"
                                onclick="openPenilaianModal('screen4')">
                            Papar
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Ya</td>
                    <td>365,500.00</td>
                    <td>60/60</td>
                    <td>30,000.00</td>
                    <td>9.09</td>
                    <td class="text-center">
                        <button class="btn btn-papar"
                                onclick="openPenilaianModal('screen4')">
                            Papar
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Anggaran Jabatan Sebenar (RM)</label>
            <input type="text" class="form-control" value="335,500.00">
        </div>
    </div>

    <div class="text-center mt-3 mb-2">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
    </div>
</div>

{{-- Screen 4 – Modal Butiran Item / Skor --}}
<div id="tpl-screen4" style="display:none;">

    <div class="grey-strip">
        BUTIRAN ITEM – Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.
        <span class="text-primary small d-block">
            Semakan lengkap dinilai dan butang Menilai bertukar kepada Lihat
        </span>
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle">
            <thead class="table-header">
                <tr>
                    <th>Kekerapan / Kuantiti</th>
                    <th>Unit Ukuran</th>
                    <th>Anggaran Jabatan (RM)</th>
                    <th>Tawaran Harga (RM)</th>
                    <th>Catatan Pembekal</th>
                    <th>Skor Automatik</th>
                    <th>Skor Manual</th>
                    <th>Catatan Penilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Activity Unit</td>
                    <td>335,500.00</td>
                    <td>330,500.00</td>
                    <td></td>
                    <td>50/60</td>
                    <td><input type="text" class="form-control"></td>
                    <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end"><strong>JUMLAH</strong></td>
                    <td><strong>335,500.00</strong></td>
                    <td><strong>330,500.00</strong></td>
                    <td></td>
                    <td>50/60</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3 mb-2">
        <button class="btn btn-simpan">Simpan</button>
    </div>
</div>

{{-- ====================== JAVASCRIPT ====================== --}}
<script>
/* =====================================================
   STEP VIEW SWITCHING (MAIN STEPPER)
   ===================================================== */

function showStep(step) {

    /* ---------------------------------
       1. SHOW / HIDE STEP CONTENT
       --------------------------------- */
    ['step1Content', 'step2Content', 'step3Content', 'step4Content']
        .forEach((id, index) => {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = (index + 1 === step) ? 'block' : 'none';
            }
        });

    /* ---------------------------------
       2. UPDATE STEPPER STATE
       active  = current step
       done    = completed step
       --------------------------------- */
    document.querySelectorAll('.stepper-item').forEach((item, index) => {

        item.classList.remove('active', 'done');

        if (index + 1 < step) {
            item.classList.add('done');       // completed
        }

        if (index + 1 === step) {
            item.classList.add('active');     // current
        }
    });

    /* ---------------------------------
       3. BACKWARD COMPATIBILITY
       (keep your existing active-step)
       --------------------------------- */
    [1,2,3,4].forEach(i => {
        const circle = document.getElementById('stepCircle' + i);
        if (circle) circle.classList.remove('active-step');
    });

    const activeCircle = document.getElementById('stepCircle' + step);
    if (activeCircle) activeCircle.classList.add('active-step');
}

/* =====================================================
   NESTED TABS PER STEP (KEWANGAN / RUMUSAN)
   ===================================================== */
function showNestedTab(step, tab) {

    /* Toggle tab buttons */
    document
        .querySelectorAll(`.nested-tab-btn[data-step="${step}"]`)
        .forEach(btn => btn.classList.remove('active'));

    const activeBtn = document.querySelector(
        `.nested-tab-btn[data-step="${step}"][data-tab="${tab}"]`
    );
    if (activeBtn) activeBtn.classList.add('active');

    /* Toggle tab content */
    ['kewangan', 'rumusan'].forEach(t => {
        const divId =
            'step' + step + 'Tab' + t.charAt(0).toUpperCase() + t.slice(1);
        const div = document.getElementById(divId);
        if (div) div.style.display = (t === tab) ? 'block' : 'none';
    });
}

/* =====================================================
   GENERIC MODAL LOADER
   ===================================================== */
function openPenilaianModal(screenId) {
    const tpl = document.getElementById('tpl-' + screenId);
    if (!tpl) return;

    document.getElementById('penilaianModalBody').innerHTML = tpl.innerHTML;

    new bootstrap.Modal(
        document.getElementById('penilaianModal')
    ).show();
}

/* =====================================================
   STEP 2 SPECIAL MODAL BEHAVIOUR
   ===================================================== */
function openStep2Modal() {
    new bootstrap.Modal(
        document.getElementById('modalPenilaian2')
    ).show();

    document.getElementById('screen2A').style.display = 'block';
    document.getElementById('screen2B').style.display = 'none';
}

function showStep2Details() {
    document.getElementById('screen2A').style.display = 'none';
    document.getElementById('screen2B').style.display = 'block';
}

/* =====================================================
   INIT – ALWAYS START AT STEP 1
   ===================================================== */
document.addEventListener('DOMContentLoaded', () => {
    showStep(1);
});
</script>

@endsection