@extends('layouts.v3.master')

@section('content')
    <style>
        #datatable-buttons th {
            background-color: #A4161A !important; /* RED */
            color: #ffffff !important;
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
                                <h4 class="card-title card-title-grey">SENARAI SEMAK KEWANGAN</h4>
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
                                        <th class="text-center"><input type="checkbox" class="form-check-input px-0 check-all-kewangan"></th>
                                        <th>Tajuk / Dokumen</th>
                                        <th class="text-center">Mekanisma</th>
                                        <th class="text-center">Tindakan Pembekal</th>
                                        <th class="text-center">Skema</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Rujukan</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                                        <td class="text-center">Spesifikasi</td>
                                        <td class="text-center">Kunci Masuk</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Menunggu Penyerahan</td>
                                        <td></td>
                                        <td class="text-center"><i class='bx bxs-pencil'></i></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Maklumat Profil Petender</td>
                                        <td class="text-center">Borang Atas Talian</td>
                                        <td class="text-center">Kunci Masuk</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Menunggu Penyerahan</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</td>
                                        <td class="text-center">Borang Atas Talian</td>
                                        <td class="text-center">Kunci Masuk</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Menunggu Penyerahan</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Salinan Sijil Pendaftaran dengan Kementerian Kewangan</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Petender Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Salinan Sijil Akuan Syarikat Bumiputera dengan Kementerian Kewangan</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Petender Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Salinan Sijil Suruhanjaya Syarikat Malaysia (SSM)</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Petender Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Salinan Surat Pendaftaran Cukai Jualan Dan Cukai Perkhidmatan (CJCP)</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Petender Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="text-center">Muat Naik</td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Surat Akuan Pembida</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">PTJ Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Muat Turun</option>
                                            </select>
                                        </td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td><a href="javascript: void(0);">Surat Akuan Pembida.docx</a></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Surat Perwakilan Kuasa</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">PTJ Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Muat Turun</option>
                                            </select>
                                        </td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td><a href="javascript: void(0);">Surat Perwakilan Kuasa.docx</a></td>
                                        <td class="text-center"></td>   
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Surat Setuju Terima</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">PTJ Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Muat Turun</option>
                                            </select>
                                        </td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td><a href="javascript: void(0);">Surat Setuju Terima.docx</a></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Surat Akuan Pembida Berjaya (Lampiran B)</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">PTJ Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Muat Turun</option>
                                            </select>
                                        </td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td><a href="javascript: void(0);">Surat Akuan Pembida Berjaya.docx</a></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Surat Akuan Sumpah Syarikat (Lampiran C)</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">PTJ Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Muat Turun</option>
                                            </select>
                                        </td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td><a href="javascript: void(0);">Surat Akuan Sumpah Syarikat.docx</a></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                        <td>Penyata Kewangan (2 Tahun) Syarikat yang telah diaudit</td>
                                        <td class="text-center">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Petender Muat Naik</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <select name="" id="" class="form-control">
                                                <option value="" class="text-center">Muat Turun</option>
                                            </select>
                                        </td>
                                        <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td>
                                        <td>Selesai</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="#" class="btn-md-sm btn btn-success mx-1" data-bs-toggle="modal" data-bs-target="#senaraiSemakStandard">Senarai Semak Standard</a>
                                <button type="button" class="btn-md-sm btn btn-success mx-1 btn-tambah-kewangan">Tambah</button>
                                <button type="button" class="btn-md-sm btn btn-danger mx-1 btn-hapus-kewangan">Hapus</button>
                            </div>
                        </div>
                    <!-- Penetapan Skor -->

                    <!-- ===================== CONTENT BLOCK ===================== -->


        <!-- HARGA INDIKATIF -->
        <div class="card-title-grey mb-3">
            <strong>HARGA INDIKATIF</strong>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label">Harga Indikatif (RM)</label>
                <input type="text" class="form-control" value="29,000.00">
            </div>
        </div>

        <!-- PENETAPAN SKOR -->
        <div class="card-title-grey mb-3">
            <strong>PENETAPAN PENANDA ARAS TAHAP LULUS (%)</strong>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label">Penilaian Kewangan</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="number" class="form-control w-50">
                    <span>/ 70</span>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tahap Lulus</label>
                <input type="text" class="form-control" placeholder="XX Peratus" disabled>
            </div>
        </div>

        <!-- DOKUMEN SOKONGAN -->
        <div class="card-title-grey mb-3">
            <strong>DOKUMEN SOKONGAN / RUJUKAN</strong>
        </div>

        <div class="row mb-2">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Nama Dokumen">
            </div>
            <div class="col-md-4">
                <input type="file" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Tambah Dokumen</button>
            </div>
        </div>

             
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn-md-sm btn btn-success mx-1 btn-simpan">Simpan</button>
                            <button type="button" class="btn-md-sm btn btn-primary mx-1 btn-hantar">Hantar</button>
                        </div>
                    </div>
                    <div class="modal fade" id="successModal" tabindex="-1">
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
                                <p class="text-muted mb-4">Maklumat telah berjaya disimpan.</p>

                                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">
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
                                        <th><input type="checkbox" name="" id="" class="form-check-input"></th>
                                        <th>Tajuk / Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" name="" id="" class="form-check-input"></td>
                                        <td>Modal Berbayar</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="" id="" class="form-check-input"></td>
                                        <td>Kemudahan Kredit (Overdraf, Pinjaman Bank)</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="" id="" class="form-check-input"></td>
                                        <td>Pengesahan dari Institusi Kewangan ke atas jumlah yang telah dibayar</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="" id="" class="form-check-input"></td>
                                        <td>Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Jumlah (RM) Kontrak yang pernah diikat)</td>
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

    <script>
document.addEventListener('DOMContentLoaded', function () {

    /* =============================
       TABLE: SENARAI SEMAK KEWANGAN
    ============================== */
    const table = document.querySelector('#datatable-buttons');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const checkAll = table.querySelector('.check-all-kewangan');

    // CHECK / UNCHECK ALL
    checkAll.addEventListener('change', function () {
        tbody.querySelectorAll('.row-check-kewangan')
            .forEach(cb => cb.checked = this.checked);
    });

    // UPDATE HEADER CHECKBOX
    tbody.addEventListener('change', function () {
        const total = tbody.querySelectorAll('.row-check-kewangan').length;
        const checked = tbody.querySelectorAll('.row-check-kewangan:checked').length;
        checkAll.checked = total > 0 && total === checked;
    });

    /* =============================
       TAMBAH ROW
    ============================== */
    document.querySelector('.btn-tambah-kewangan')
        .addEventListener('click', function () {

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="text-center">
                    <input type="checkbox" class="form-check-input row-check-kewangan">
                </td>
                <td><input type="text" class="form-control form-control-sm" placeholder="Tajuk / Dokumen"></td>
                <td class="text-center">Borang</td>
                <td class="text-center">Muat Naik</td>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input">
                </td>
                <td>Baharu</td>
                <td></td>
                <td class="text-center">
                    <i class="bx bxs-pencil"></i>
                </td>
            `;
            tbody.appendChild(row);
        });

    /* =============================
       HAPUS ROW (CHECKED ONLY)
    ============================== */
    document.querySelector('.btn-hapus-kewangan')
        .addEventListener('click', function () {

            const checked = tbody.querySelectorAll('.row-check-kewangan:checked');
            if (checked.length === 0) {
                alert('Sila pilih sekurang-kurangnya satu rekod.');
                return;
            }

            checked.forEach(cb => cb.closest('tr').remove());
            checkAll.checked = false;
        });

    /* =============================
       SUCCESS MODAL (SIMPAN & HANTAR)
    ============================== */
    const successModal = new bootstrap.Modal(
        document.getElementById('successModal')
    );

    document.querySelector('.btn-simpan')
        .addEventListener('click', () => successModal.show());

    document.querySelector('.btn-hantar')
        .addEventListener('click', () => successModal.show());

});
</script>

@endsection