@extends('layouts.v3.master')


@section('content')
    <style>
        #datatable-buttons th {
            background-color: #405393 !important; 
            color: white !important;
            border: 1px solid #848484;
        }
        .btn-primary {
            background: #405189;
        }
        .card-title-grey {
            background: #D9D9D9;
            padding: 5px 15px;
        }
        hr {
            border:1px solid #E9EBEC;
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
                <div class="col-7 text-center">
                    QT21000000000023741
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
                    <!-- Penetapan Skor -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">Penyediaan Spesifikasi & Skor</div>
                            </div>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title card-title-grey">SENARAI SEMAK TEKNIKAL</h4>
                                <p class="card-title-desc text-primary fst-italic">
                                    1. Senarai semak dokumen tawaran (Teknikal) dijana berdasarkan Kategori Perolehan.<br>
                                    2. Sila pilih kotak semak dalam lajur skor jika hendak memilih senarai semak tersebut untuk dinilai.<br>
                                    3. Klik "ikon pensil" untuk kunci masuk skema skor penilaian atau pinda spesifikasi.<br>
                                    4. Klik butang Cipta Spesifikasi untuk cipta templat dan spesifikasi baru. Sila klik untuk Panduan Penyediaan Item dan Spesifikasi.<br>
                                    5. Klik butang Tambah untuk kunci masuk senarai semak baru.<br>
                                    6. Senarai semak dengan tindakan Muatnaik dokumen oleh pembekal, secara automatik menjadi dokumen pematuhan.<br>
                                    7. Klik butang Senarai Semak Standard dan pilih senarai semak yang diperlukan.<br>
                                    8. Untuk perkhidmatan yang memerlukan bayaran secara progresif, sila pilih tempat perkhidmatan.<br>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3 mx-3">
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100" 
                                data-table-sort="id"
                                data-table-order="asc"
                                data-page="1">
                                <thead>
                                    <tr>
                                        <th class="text-center"><input type="checkbox"class="form-check-input px-0 check-all-teknikal"></th>
                                        <th>Tajuk / Dokumen</th>
                                        <th class="text-center">Mekanisma</th>
                                        <th class="text-center">Tindakan Pembekal</th>
                                        <th class="text-center">Skor</th>
                                        <th class="text-center">Status Spesifikasi</th>
                                        <th class="text-center">Dokumen</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input row-check-teknikal">
                                        </td>
                                        <td>Pengalaman Syarikat Dengan Kerajaan Persekutuan</td>
                                        <td class="text-center">Wajib</td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center">10</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">Belum Lengkap</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary">Lihat</button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input row-check-teknikal">
                                        </td>
                                        <td>Skop Bekalan dan Perkhidmatan</td>
                                        <td class="text-center">Wajib</td>
                                        <td class="text-center">Isi Borang</td>
                                        <td class="text-center">20</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Lengkap</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary">Lihat</button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input row-check-teknikal">
                                        </td>
                                        <td>Bilangan Kakitangan Teknikal</td>
                                        <td class="text-center">Pilihan</td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center">15</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Lengkap</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary">Lihat</button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input row-check-teknikal">
                                        </td>
                                        <td>Jadual Pelaksanaan Projek</td>
                                        <td class="text-center">Wajib</td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center">25</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">Belum Lengkap</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary">Lihat</button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="row-template-teknikal d-none">
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input row-check-teknikal">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                placeholder="Tajuk / Dokumen">
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select form-select-sm">
                                                <option>Wajib</option>
                                                <option>Pilihan</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select form-select-sm">
                                                <option>Muat Naik</option>
                                                <option>Isi Borang</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="number" class="form-control form-control-sm text-center"
                                                value="0" min="0">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">Baharu</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary">Lihat</button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="#" class="btn-md-sm btn btn-success mx-1" data-bs-toggle="modal" data-bs-target="#senaraiSemakStandard">Senarai Semak Standard</a>
                                <a href="#" class="btn-md-sm btn btn-success mx-1" data-bs-toggle="modal" data-bs-target="#ciptaSpesifikasi">Cipta Spesifikasi</a>
                                <button type="button" class="btn-md-sm btn btn-success mx-1 btn-tambah-teknikal">Tambah</button>
                                <button type="button" class="btn btn-danger btn-hapus-teknikal">Hapus</button>
                            </div>
                        </div>
                    <!-- Penetapan Skor -->
                    
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn-md-sm btn btn-success mx-1 btn-simpan">
                                Simpan
                            </button>
                            <button type="button" class="btn-md-sm btn btn-primary mx-1 btn-hantar">
                                Hantar
                            </button>
                        </div>
                    </div>
                    <!-- ===================== SUCCESS MODAL ===================== -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">

            <div class="mb-3">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" fill="#E6F7F3"/>
                    <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z"
                          fill="#19c1a7"/>
                </svg>
            </div>

            <h5 class="fw-bold mb-2">Berjaya</h5>
            <p class="text-muted mb-4">
                Maklumat telah berjaya disimpan.
            </p>

            <button type="button"
                    class="btn btn-primary px-4"
                    data-bs-dismiss="modal">
                Tutup
            </button>

        </div>
    </div>
</div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="senaraiSemakStandard" tabindex="-1" aria-labelledby="senaraiSemakStandardLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="senaraiSemakStandardLabel">Senarai Semak Standard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-10">
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100" 
                                data-table-sort="id"
                                data-table-order="asc"
                                data-page="1">
                                <thead>
                                    <tr>
                                        <th> <input type="checkbox" class="form-check-input check-all-standard"></th><th>Tajuk / Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Pengalaman Syarikat Dengan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Skop Bekalan Dan Perkhidmatan</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Salinan Borang KWSP A setiap pekerja bagi bulan caruman terakhir</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Bilangan Kakitangan</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Brosur / Risalah</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Surat pengesahan pendaftaran dengan Pertubuhan Keselamatan Sosial (Perkeso) yang telah dikeluarkan mengikut Akta Keselamatan Sosial Pekerja 1969. Jadual Caruman Bulanan (Borang 8A) dan Resit Bayaran Caruman yang terbaru</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Cadangan Bertulis</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Lesen Premis oleh PBT</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard">
</td>
                                        <td>Jadual Pelaksanaan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-success m-1">OK</button>
                            <button class="btn btn-danger m-1">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ciptaSpesifikasi" tabindex="-1" aria-labelledby="ciptaSpesifikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ciptaSpesifikasiLabel">Cipta Spesifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="card-title card-title-grey">CARI</h4>
                        
                    <div class="mx-3">
                        <div class="row my-4">
                            <div class="col-md-4 text-end">
                                Klon Spesifikasi Daripada <span class="text-danger">*</span>
                            </div>
                            <div class="col-md-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="klonSpesifikasi" id="klonSpesifikasi1" checked>
                                    <label class="form-check-label" for="klonSpesifikasi1">
                                        Templat Standard / Kosong
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="klonSpesifikasi" id="klonSpesifikasi2">
                                    <label class="form-check-label" for="klonSpesifikasi2">
                                        Sebut Harga / Tender Yang Lepas
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col-4 text-end">Jenis Item</div>
                            <div class="col-4">
                                <select name="" id="" class="form-control">
                                    <option value="">Bekalan</option>
                                    <option value="">Perkhidmatan</option>
                                </select>
                            </div>
                        </div>
                            
                        <div class="row my-4">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-success mx-1" data-bs-dismiss="modal">Cari</button>
                                <button type="button" class="btn btn-success mx-1">Set Semula</button>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="card-title card-title-grey">TEMPLAT</h4>

                    <div class="mx-3">
                        <table id="templateTable" class="table table-bordered dt-responsive nowrap w-100" 
                            data-table-sort="id"
                            data-table-order="asc"
                            data-page="1">
                            <thead>
                            <tr>
                            <th class="text-center">
                                <input type="checkbox"
                                    class="form-check-input check-all-template">
                                    </th>
                                    <th class="text-center">Tajuk / Dokumen</th>
                                    <th class="text-center">Skor Maksima</th>
                                    <th class="text-center">Jenis Item</th>
                                    <th class="text-center">Dicipta Oleh</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- ROW 1 -->
                                <tr class="template-row">
                                    <td class="text-center">
                                        <input type="checkbox"
                                            class="form-check-input row-check-template">
                                    </td>
                                    <td>Pengalaman Syarikat Dengan Kerajaan</td>
                                    <td class="text-center">20</td>
                                    <td class="text-center">Perkhidmatan</td>
                                    <td class="text-center">Admin Sistem</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ROW 2 -->
                                <tr class="template-row">
                                    <td class="text-center">
                                        <input type="checkbox"
                                            class="form-check-input row-check-template">
                                    </td>
                                    <td>Skop Bekalan dan Perkhidmatan</td>
                                    <td class="text-center">30</td>
                                    <td class="text-center">Bekalan</td>
                                    <td class="text-center">Urusetia</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ROW 3 -->
                                <tr class="template-row">
                                    <td class="text-center">
                                        <input type="checkbox"
                                            class="form-check-input row-check-template">
                                    </td>
                                    <td>Jadual Pelaksanaan Projek</td>
                                    <td class="text-center">25</td>
                                    <td class="text-center">Perkhidmatan</td>
                                    <td class="text-center">Admin Sistem</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<script>
// CHECKBOX FUNCTIONALITY Table Senarai Semak Teknikal
document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('#datatable-buttons');
    if (!table) return;

    const checkAll = table.querySelector('.check-all-teknikal');
    if (!checkAll) return;

    // CHECK / UNCHECK ALL
    checkAll.addEventListener('change', function () {
        table.querySelectorAll('.row-check-teknikal').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // UPDATE HEADER CHECKBOX WHEN ROW CHANGES
    table.querySelectorAll('.row-check-teknikal').forEach(cb => {
        cb.addEventListener('change', function () {
            const total = table.querySelectorAll('.row-check-teknikal').length;
            const checked = table.querySelectorAll('.row-check-teknikal:checked').length;
            checkAll.checked = total === checked;
        });
    });

});

// CHECKBOX FUNCTIONALITY Modal Senarai Semak Standard
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('senaraiSemakStandard');

    if (!modal) return;

    const checkAll = modal.querySelector('.check-all-standard');

    // CHECK / UNCHECK ALL
    checkAll.addEventListener('change', function () {
        const rows = modal.querySelectorAll('.row-check-standard');
        rows.forEach(cb => cb.checked = this.checked);
    });

    // UPDATE HEADER CHECKBOX WHEN ROW CHANGES
    modal.querySelectorAll('.row-check-standard').forEach(cb => {
        cb.addEventListener('change', function () {
            const rows = modal.querySelectorAll('.row-check-standard');
            const checked = modal.querySelectorAll('.row-check-standard:checked');
            checkAll.checked = rows.length === checked.length;
        });
    });

});

// CHECKBOX FUNCTIONALITY Modal Cipta Spesifikasi
document.addEventListener('DOMContentLoaded', function () {

    const table = document.getElementById('templateTable');
    if (!table) return;

    const checkAll = table.querySelector('.check-all-template');
    const rows = table.querySelectorAll('.row-check-template');

    /* =========================
       CLICK ROW TO TOGGLE CHECK
    ========================= */
    table.querySelectorAll('.template-row').forEach(row => {
        row.addEventListener('click', function (e) {

            // Prevent double toggle when clicking checkbox or button
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }

            const checkbox = row.querySelector('.row-check-template');
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    /* =========================
       CHECK / UNCHECK ALL
    ========================= */
    checkAll.addEventListener('change', function () {
        rows.forEach(cb => cb.checked = this.checked);
    });

    /* =========================
       UPDATE HEADER CHECKBOX
    ========================= */
    rows.forEach(cb => {
        cb.addEventListener('change', function () {
            const total = rows.length;
            const checked = table.querySelectorAll('.row-check-template:checked').length;
            checkAll.checked = total === checked;
        });
    });

});

// TABLE FUNCTIONALITY Senarai Semak Teknikal
document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('#datatable-buttons');
    if (!table) return;

    const tbody = table.querySelector('tbody');

    /* =========================
       TAMBAH ROW
    ========================= */
    document.querySelector('.btn-tambah-teknikal')
        .addEventListener('click', function () {

            const template = tbody.querySelector('.row-template-teknikal');
            const clone = template.cloneNode(true);

            clone.classList.remove('d-none', 'row-template-teknikal');
            tbody.appendChild(clone);
        });

    /* =========================
       HAPUS ROW (CHECKED ONLY)
    ========================= */
    document.querySelector('.btn-hapus-teknikal')
        .addEventListener('click', function () {

            const checkedRows = tbody.querySelectorAll('.row-check-teknikal:checked');

            if (checkedRows.length === 0) {
                alert('Sila pilih sekurang-kurangnya satu rekod untuk dihapus.');
                return;
            }

            checkedRows.forEach(cb => {
                cb.closest('tr').remove();
            });

            // Uncheck header checkbox
            const checkAll = table.querySelector('.check-all-teknikal');
            if (checkAll) checkAll.checked = false;
        });

});

// SUCCESS MODAL FOR SIMPAN & HANTAR BUTTONS
document.addEventListener('DOMContentLoaded', function () {

    const successModal = new bootstrap.Modal(
        document.getElementById('successModal')
    );

    // SIMPAN
    document.querySelector('.btn-simpan')
        .addEventListener('click', function () {
            successModal.show();
        });

    // HANTAR
    document.querySelector('.btn-hantar')
        .addEventListener('click', function () {
            successModal.show();
        });

});
</script>

  
@endsection

