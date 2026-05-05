{{-- Step 2 (partial): Penyata Bulanan Bank — Kewangan & Rumusan --}}
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-2" role="tab" aria-selected="true">Kewangan</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-2" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="kewangan-2" role="tabpanel" aria-labelledby="kewangan-2-tab">
        <h4 class="card-title card-title-grey mb-0">PENYATA BULANAN BANK</h4>
        <p class="card-title-desc text-primary fst-italic mb-3">Klik butang Papar untuk melihat dokumen dan menjalankan semakan.</p>
        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap w-100 align-middle">
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
                        <td>Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</td>
                        <td class="text-center">Borang Atas Talian</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-papar-cadangan-kewangan-step2"
                                data-bs-toggle="modal" data-bs-target="#modalPenilaianCadanganKewanganStep2"
                                data-dokumen="Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX.">Papar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row mb-3 mt-2">
            <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="rumusan-2" role="tabpanel" aria-labelledby="rumusan-2-tab">
        <div class="px-0">
            <div class="row g-0">
                <div class="col-12 rounded-top border border-bottom-0 bg-light px-3 py-2 fw-bold">
                    SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PENYATA BULANAN BANK
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
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

            <div class="row my-3 align-items-center g-2">
                <div class="col-sm-auto">Bilangan Pembekal</div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control text-center" value="2" readonly aria-label="Bilangan pembekal melepasi">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep2">
                        <label class="form-check-label" for="confirmLayakStep2">
                            Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-0">
                <div class="col-12 border border-bottom-0 bg-light px-3 py-2 fw-bold">
                    SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PENYATA BULANAN BANK
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
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

            <div class="row my-3 mb-4 align-items-center g-2">
                <div class="col-sm-auto">Bilangan Pembekal</div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control text-center" value="0" readonly aria-label="Bilangan pembekal tidak melepasi">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                    <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #modalButiranCadanganKewanganStep2 .step2-harga-table thead th {
        font-size: 12px;
        vertical-align: middle;
    }

    #modalButiranCadanganKewanganStep2 .step2-harga-table .skor-sub th {
        font-size: 11px;
        font-weight: 600;
    }

    #modalButiranCadanganKewanganStep2 .step2-harga-table td {
        font-size: 12px;
    }

    #modalButiranCadanganKewanganStep2 .input-skor-manual {
        max-width: 88px;
        margin: 0 auto;
        text-align: center;
    }

    #modalButiranCadanganKewanganStep2 .input-catatan-penilai {
        min-width: 120px;
        font-size: 12px;
    }
</style>

{{-- First modal (Step 2): Senarai Pembekal + Skema Skor — Papar membuka modal kedua --}}
<div class="modal fade" id="modalPenilaianCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="modalLabelCadanganKewanganStep2">PENILAIAN CADANGAN KEWANGAN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3"><strong>Tajuk / Dokumen:</strong> <span id="modalCadanganKewanganTajukStep2">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX.</span></p>

                <div class="card-title card-title-grey mb-2">Senarai Pembekal</div>
                <p class="card-title-desc text-primary fst-italic mb-3">Pastikan semua senarai semak lengkap dinilai dan butang Menilai bertukar kepada Lihat.</p>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th style="width: 7%;">Bil</th>
                                <th style="width: 12%;">Status Bumiputra</th>
                                <th style="width: 14%;">Harga Tawaran (RM)</th>
                                <th style="width: 11%;">Jumlah Skor</th>
                                <th style="width: 14%;">Perbezaan Harga (RM)</th>
                                <th style="width: 10%;">Perbezaan (%)</th>
                                <th style="width: 14%;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1/2</td>
                                <td class="text-center">Bukan</td>
                                <td class="text-end">330,500.00</td>
                                <td class="text-center">50/60</td>
                                <td class="text-end">5,000.00</td>
                                <td class="text-center">—</td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-success btn-sm btn-papar-butiran-cadangan-step2 px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalButiranCadanganKewanganStep2"
                                        data-bs-dismiss="modal"
                                        data-dokumen="Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX."
                                        data-kod-pembekal="1/2"
                                        data-anggaran-jabatan="335,500.00"
                                        data-tawaran-harga="330,500.00"
                                        data-skor-automatik="50/60">Papar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2/2</td>
                                <td class="text-center">Ya</td>
                                <td class="text-end">365,500.00</td>
                                <td class="text-center">60/60</td>
                                <td class="text-end">30,000.00</td>
                                <td class="text-center">9.09</td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-success btn-sm btn-papar-butiran-cadangan-step2 px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalButiranCadanganKewanganStep2"
                                        data-bs-dismiss="modal"
                                        data-dokumen="Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX."
                                        data-kod-pembekal="2/2"
                                        data-anggaran-jabatan="335,500.00"
                                        data-tawaran-harga="365,500.00"
                                        data-skor-automatik="60/60">Papar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-title card-title-grey mb-2 mt-4">Skema Skor</div>
                <div class="row align-items-center g-2 mb-1">
                    <div class="col-auto fw-semibold">Anggaran Jabatan Sebenar (RM)</div>
                    <div class="col-md-3">
                        <input type="text" class="form-control text-end" id="modalStep2AnggaranJabatanSebenar" value="335,500.00" readonly aria-label="Anggaran Jabatan Sebenar">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-primary px-5" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

{{-- Second modal: Harga Tawaran (butiran penilaian selepas Papar) --}}
<div class="modal fade" id="modalButiranCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelButiranCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="modalLabelButiranCadanganKewanganStep2">PENILAIAN CADANGAN KEWANGAN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1"><strong>Kod Pembekal :</strong> <span id="modalStep2KodPembekal">1/2</span></p>
                <p class="mb-3"><strong>Tajuk / Dokumen :</strong> <span id="modalButiranCadanganKewanganTajukStep2">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX.</span></p>

                <div class="card-title card-title-grey mb-2 text-uppercase">Harga Tawaran</div>
                <p class="card-title-desc text-primary fst-italic mb-3">Pastikan semua senarai semak lengkap dinilai dan butang Menilai bertukar kepada Lihat.</p>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center step2-harga-table mb-0">
                        <thead class="table-primary text-white">
                            <tr>
                                <th rowspan="2" class="align-middle">Item</th>
                                <th rowspan="2" class="align-middle">Kekerapan<br>/<br>Kuantiti</th>
                                <th rowspan="2" class="align-middle">Unit Ukuran</th>
                                <th rowspan="2" class="align-middle">Anggaran Jabatan<br>(RM)</th>
                                <th rowspan="2" class="align-middle">Tawaran Harga<br>(RM)</th>
                                <th rowspan="2" class="align-middle">Catatan<br>Pembekal</th>
                                <th colspan="2">Skor</th>
                                <th rowspan="2" class="align-middle">Catatan Penilai</th>
                            </tr>
                            <tr class="skor-sub table-primary text-white">
                                <th>Automatik</th>
                                <th>Manual <span class="text-danger">*</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX.</td>
                                <td>1</td>
                                <td>Activity Unit</td>
                                <td class="text-end"><span id="modalStep2RowAnggaran">335,500.00</span></td>
                                <td class="text-end"><span id="modalStep2RowTawaran">330,500.00</span></td>
                                <td></td>
                                <td id="modalStep2RowSkorAuto">50/60</td>
                                <td><input type="text" class="form-control form-control-sm input-skor-manual" id="modalStep2SkorManual" name="skor_manual" placeholder="" aria-label="Skor manual"></td>
                                <td><input type="text" class="form-control form-control-sm input-catatan-penilai" id="modalStep2CatatanPenilai" placeholder="" aria-label="Catatan penilai"></td>
                            </tr>
                            <tr class="fw-bold">
                                <td colspan="2"></td>
                                <td class="text-start">JUMLAH</td>
                                <td class="text-end"><span id="modalStep2JumlahAnggaran">335,500.00</span></td>
                                <td class="text-end"><span id="modalStep2JumlahTawaran">330,500.00</span></td>
                                <td></td>
                                <td colspan="2" id="modalStep2JumlahSkor">50/60</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-success px-5" id="btnStep2SimpanHargaTawaran">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#penyata-bank .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#pematuhan-tab');
            if (tab) tab.click();
        });
    });

    function setStep2DetailFromButton(btn) {
        const tajuk = (btn.getAttribute('data-dokumen') || '').trim();
        const kod = (btn.getAttribute('data-kod-pembekal') || '').trim();
        const anggaran = (btn.getAttribute('data-anggaran-jabatan') || '').trim();
        const tawaran = (btn.getAttribute('data-tawaran-harga') || '').trim();
        const skorAuto = (btn.getAttribute('data-skor-automatik') || '').trim();

        const elTajuk = document.getElementById('modalButiranCadanganKewanganTajukStep2');
        const elKod = document.getElementById('modalStep2KodPembekal');
        const elRowAnggaran = document.getElementById('modalStep2RowAnggaran');
        const elRowTawaran = document.getElementById('modalStep2RowTawaran');
        const elRowSkorAuto = document.getElementById('modalStep2RowSkorAuto');
        const elJumlahAnggaran = document.getElementById('modalStep2JumlahAnggaran');
        const elJumlahTawaran = document.getElementById('modalStep2JumlahTawaran');
        const elJumlahSkor = document.getElementById('modalStep2JumlahSkor');

        if (elTajuk && tajuk) elTajuk.textContent = tajuk;
        if (elKod && kod) elKod.textContent = kod;
        if (elRowAnggaran && anggaran) elRowAnggaran.textContent = anggaran;
        if (elRowTawaran && tawaran) elRowTawaran.textContent = tawaran;
        if (elRowSkorAuto && skorAuto) elRowSkorAuto.textContent = skorAuto;
        if (elJumlahAnggaran && anggaran) elJumlahAnggaran.textContent = anggaran;
        if (elJumlahTawaran && tawaran) elJumlahTawaran.textContent = tawaran;
        if (elJumlahSkor && skorAuto) elJumlahSkor.textContent = skorAuto;

        const manualIn = document.getElementById('modalStep2SkorManual');
        const catatanIn = document.getElementById('modalStep2CatatanPenilai');
        if (manualIn) manualIn.value = '';
        if (catatanIn) catatanIn.value = '';
    }

    document.querySelectorAll('.btn-papar-cadangan-kewangan-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tajuk = (btn.getAttribute('data-dokumen') || '').trim();
            const tajukFirstModal = document.getElementById('modalCadanganKewanganTajukStep2');
            if (tajukFirstModal && tajuk) tajukFirstModal.textContent = tajuk;
        });
    });

    document.querySelectorAll('.btn-papar-butiran-cadangan-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            setStep2DetailFromButton(btn);
        });
    });

    const btnStep2SimpanHarga = document.getElementById('btnStep2SimpanHargaTawaran');
    const elModalButiranStep2 = document.getElementById('modalButiranCadanganKewanganStep2');
    const elModalCadanganStep2 = document.getElementById('modalPenilaianCadanganKewanganStep2');
    if (btnStep2SimpanHarga && elModalButiranStep2 && elModalCadanganStep2 && typeof bootstrap !== 'undefined') {
        btnStep2SimpanHarga.addEventListener('click', function() {
            const modalButiran = bootstrap.Modal.getInstance(elModalButiranStep2)
                || new bootstrap.Modal(elModalButiranStep2);
            const modalCadangan = bootstrap.Modal.getInstance(elModalCadanganStep2)
                || new bootstrap.Modal(elModalCadanganStep2);
            elModalButiranStep2.addEventListener('hidden.bs.modal', function reopenSenarai() {
                modalCadangan.show();
            }, { once: true });
            modalButiran.hide();
        });
    }
});
</script>
