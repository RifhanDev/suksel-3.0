{{-- Step 2: Pematuhan Spesifikasi Teknikal (Teknikal tab + Rumusan tab + modal) --}}
<!-- Inner tabs for step 2 -->
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#teknikal-2" role="tab" aria-selected="true">Teknikal</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-2" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content">
    {{-- Teknikal tab --}}
    <div class="tab-pane fade show active" id="teknikal-2" role="tabpanel" aria-labelledby="teknikal-2-tab">
        <h4 class="card-title card-title-grey">PENILAIAN SPESIFIKASI TEKNIKAL</h4>
        <p class="card-title-desc text-primary fst-italic">Klik butang Menilai untuk meneruskan penilaian</p>
        <table class="table table-bordered dt-responsive nowrap w-100">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">Tajuk / Dokumen</th>
                    <th class="text-center">Mekanisma</th>
                    <th class="text-center">Status Penilaian</th>
                    <th class="text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                    <td>Spesifikasi</td>
                    <td>Menunggu Penyerahan</td>
                    <td class="text-center">
                        <button type="button" id="btnMainMenilai" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPenilaianSpesifikasiTeknikal">Menilai</button>
                    </td>
                </tr>
                <tr>
                    <td>Surat Pengesahan Prinsipal yang lengkap ditandatangani</td>
                    <td>Petender Muat Naik</td>
                    <td>Selesai</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success">Papar</button>
                    </td>
                </tr>
                <tr>
                    <td>Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek</td>
                    <td>Petender Muat Naik</td>
                    <td>Selesai</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success">Papar</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row mb-3 px-3">
            <div class="col-md-12 d-flex justify-content-between">
                <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>

    {{-- Rumusan tab --}}
    <div class="tab-pane fade" id="rumusan-2" role="tabpanel" aria-labelledby="rumusan-2-tab">
        <div class="container-fluid mt-3">
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

            <div class="row my-3 align-items-center">
                <div class="col-md-2 text-end fw-bold">Penetapan Penanda Aras Tahap Lulus (%)</div>
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
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep2">
                        <label class="form-check-label" for="confirmLayakStep2">
                            Saya mengesahkan petender diatas layak untuk dinilai oleh Jawatankuasa Kewangan
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 bg-light p-2 fw-bold">
                    SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN TEKNIKAL
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
                                <td class="text-center" colspan="2">Tiada rekod dijumpai</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-4 align-items-center">
                <div class="col-md-2 text-end fw-bold">Bilangan Pembekal</div>
                <div class="col-md-1">
                    <input type="text" class="form-control text-center" value="0" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-between">
                    <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                    <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 1: PENILAIAN SPESIFIKASI TEKNIKAL - SENARAI PEMBEKAL (opens when user clicks Menilai on main table) --}}
<div class="modal fade" id="modalPenilaianSpesifikasiTeknikal" tabindex="-1" aria-labelledby="modalPenilaianSpesifikasiTeknikalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPenilaianSpesifikasiTeknikalLabel">PENILAIAN CADANGAN TEKNIKAL</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Tajuk / Dokumen :</strong> Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</p>
                <div class="mb-3">
                    <h4 class="card-title card-title-grey">SENARAI PEMBEKAL</h4>
                </div>
                <p class="card-title-desc text-primary fst-italic small">Pastikan semua senarai semak lengkap dinilai dan butang Menilai bertukar kepada Lihat</p>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th>Bil</th>
                                <th>Skor Automatik</th>
                                <th>Skor Manual</th>
                                <th>Jumlah Skor</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1/2</td>
                                <td class="text-center">11</td>
                                <td class="text-center"><span id="skorManualRow1"></span></td>
                                <td class="text-center"><span id="jumlahSkorRow1">11</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success" id="btnTindakanRow1" data-bs-toggle="modal" data-bs-target="#modalSenaraiSpesifikasiTeknikal">Menilai</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2/2</td>
                                <td class="text-center">6</td>
                                <td class="text-center"><span id="skorManualRow2"></span></td>
                                <td class="text-center"><span id="jumlahSkorRow2">6</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success" id="btnTindakanRow2" data-bs-toggle="modal" data-bs-target="#modalSenaraiSpesifikasiTeknikal">Menilai</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 2: SENARAI SPESIFIKASI TEKNIKAL (8 columns, Simpan only - opened from row Menilai in modal above) --}}
<div class="modal fade" id="modalSenaraiSpesifikasiTeknikal" tabindex="-1" aria-labelledby="modalSenaraiSpesifikasiTeknikalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSenaraiSpesifikasiTeknikalLabel">SENARAI SPESIFIKASI TEKNIKAL</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th>Item / Spesifikasi *</th>
                                <th>Kekerapan / Unit Ukuran *</th>
                                <th>Unit Ukuran *</th>
                                <th>Pematuhan</th>
                                <th>Cadangan Petender</th>
                                <th>Skor Automatik</th>
                                <th>Skor Manual *</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX</td>
                                <td class="text-center">1</td>
                                <td class="text-center">Lot</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><input type="text" class="form-control" placeholder=""></td>
                            </tr>
                            <tr>
                                <td class="text-start">Melaksanakan Perkhidmatan Kajian 1.1 Pengumpulan Maklumat Dan Mengenalpasti Proses.<br>a. Kaedah pengumpulan maklumat yang bersesuaian yang ingin digunakan bagi setiap proses terlibat. Sebagai contoh melalui temuduga, bengkel, kajian dokumen, kajian sistem dan sebagainya. Sila nyatakan dengan jelas kaedah pengumpulan maklumat.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Temuduga dan kajian sistem</td>
                                <td></td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="text" class="form-control form-control-sm text-center" style="width:3rem" value="10">
                                        <span>/ 10</span>
                                    </div>
                                </td>
                                <td><input type="text" class="form-control" placeholder=""></td>
                            </tr>
                            <tr>
                                <td class="text-start">Melaksanakan Perkhidmatan Kajian 1.1 Pengumpulan Maklumat Dan Mengenalpasti Proses.<br>b. Mengadakan perbincangan bersama pasukan projek bagi setiap kaedah yang ingin dilaksanakan.</td>
                                <td></td>
                                <td></td>
                                <td>Yes</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="text" class="form-control form-control-sm text-center" style="width:3rem" value="1">
                                        <span>/ 1</span>
                                    </div>
                                </td>
                                <td><input type="text" class="form-control" placeholder=""></td>
                            </tr>
                            <tr>
                                <td class="text-start">Melaksanakan Perkhidmatan Kajian 1.1 Pengumpulan Maklumat Dan Mengenalpasti Proses.<br>c. Tempoh masa yang diperlukan dalam bulan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>3.00</td>
                                <td></td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="text" class="form-control form-control-sm text-center" style="width:3rem" value="10">
                                        <span>/ 10</span>
                                    </div>
                                </td>
                                <td><input type="text" class="form-control" placeholder=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" id="btnSimpanSenaraiSpesifikasiTeknikal" data-bs-dismiss="modal">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#penilaian .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#pematuhan-tab');
            if (tab) tab.click();
        });
    });

    // Simulate result: after saving in SENARAI SPESIFIKASI TEKNIKAL,
    // update scores in PENILAIAN CADANGAN TEKNIKAL dialog.
    const btnSimpan = document.getElementById('btnSimpanSenaraiSpesifikasiTeknikal');
    if (btnSimpan) {
        btnSimpan.addEventListener('click', function() {
            const skorManualRow1 = document.getElementById('skorManualRow1');
            const skorManualRow2 = document.getElementById('skorManualRow2');
            const jumlahSkorRow1 = document.getElementById('jumlahSkorRow1');
            const jumlahSkorRow2 = document.getElementById('jumlahSkorRow2');
            const btnTindakanRow1 = document.getElementById('btnTindakanRow1');
            const btnTindakanRow2 = document.getElementById('btnTindakanRow2');
            const btnMainMenilai = document.getElementById('btnMainMenilai');

            if (skorManualRow1) skorManualRow1.textContent = '10';
            if (skorManualRow2) skorManualRow2.textContent = '10';
            if (jumlahSkorRow1) jumlahSkorRow1.textContent = '21';
            if (jumlahSkorRow2) jumlahSkorRow2.textContent = '16';
            if (btnTindakanRow1) btnTindakanRow1.textContent = 'Papar';
            if (btnTindakanRow2) btnTindakanRow2.textContent = 'Papar';
            if (btnMainMenilai) btnMainMenilai.textContent = 'Papar';

            const modal1El = document.getElementById('modalPenilaianSpesifikasiTeknikal');
            if (window.bootstrap && modal1El) {
                const modal1 = new bootstrap.Modal(modal1El);
                modal1.show();
            }
        });
    }
});
</script>
