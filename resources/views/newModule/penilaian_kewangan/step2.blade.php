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
                                data-dokumen="Penyata Bank Terkini (3 Bulan Terakhir) Syarikat">Papar</button>
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
    #modalPenilaianCadanganKewanganStep2 .table-primary thead th,
    #modalPenilaianCadanganKewanganStep2 .table-primary th,
    #modalButiranCadanganKewanganStep2 .table-primary thead th,
    #modalButiranCadanganKewanganStep2 .table-primary th {
        background-color: #3b5998 !important;
        color: #fff !important;
        border-color: #2d4373 !important;
    }

    #modalPenilaianCadanganKewanganStep2 .modal-header,
    #modalButiranCadanganKewanganStep2 .modal-header {
        background: #f3f4f6;
        color: #111827;
        border-bottom: 1px solid #e5e7eb;
    }

    #modalPenilaianCadanganKewanganStep2 .modal-title,
    #modalButiranCadanganKewanganStep2 .modal-title {
        color: #111827;
        font-size: 1rem;
        font-weight: 700;
    }

    #modalPenilaianCadanganKewanganStep2 .btn-close,
    #modalButiranCadanganKewanganStep2 .btn-close {
        filter: none;
        opacity: 0.6;
    }

    #modalButiranCadanganKewanganStep2 .input-amaun {
        max-width: 160px;
        margin: 0 auto;
        text-align: center;
    }

    #modalButiranCadanganKewanganStep2 .summary-input {
        max-width: 170px;
    }
</style>

{{-- First modal (Step 2): Penilaian Cadangan Kewangan --}}
<div class="modal fade" id="modalPenilaianCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="modalLabelCadanganKewanganStep2">PENILAIAN CADANGAN KEWANGAN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Tajuk / Dokumen:</strong> <span id="modalCadanganKewanganTajukStep2">Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</span></p>

                <div class="card-title card-title-grey mb-2">Senarai Pembekal</div>
                <p class="card-title-desc text-primary fst-italic mb-3">Sila pastikan semua senarai semak telah dinilai dan butang Menilai telah bertukar kepada Lihat.</p>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th style="width: 12%;">Bil</th>
                                <th style="width: 24%;">Jumlah Skor</th>
                                <th style="width: 34%;">Status Penilaian</th>
                                <th style="width: 30%;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1/2</td>
                                <td class="text-center">10</td>
                                <td class="text-center">Selesai</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-papar-butiran-cadangan-step2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalButiranCadanganKewanganStep2"
                                        data-bs-dismiss="modal"
                                        data-dokumen="Penyata Bank Terkini (3 Bulan Terakhir) Syarikat">Papar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2/2</td>
                                <td class="text-center">10</td>
                                <td class="text-center">Selesai</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-papar-butiran-cadangan-step2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalButiranCadanganKewanganStep2"
                                        data-bs-dismiss="modal"
                                        data-dokumen="Penyata Bank Terkini (3 Bulan Terakhir) Syarikat">Papar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Second modal (from first modal -> Papar): Butiran Cadangan Kewangan --}}
<div class="modal fade" id="modalButiranCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelButiranCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="modalLabelButiranCadanganKewanganStep2">PENILAIAN CADANGAN KEWANGAN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Tajuk / Dokumen:</strong> <span id="modalButiranCadanganKewanganTajukStep2">Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</span></p>

                <div class="card-title card-title-grey mb-2 text-uppercase">DOKUMEN SEMAK SILANG</div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th>Tajuk / Dokumen</th>
                                <th style="width: 22%;">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Kunci Kira-kira Tahunan (Nota - Untuk syarikat ROC,n i mestilah salinan yang telah diaudit)</td>
                                <td class="text-center"><button type="button" class="btn btn-success">Papar</button></td>
                            </tr>
                            <tr>
                                <td class="text-center">Pengesahan dari Institusi Kewangan untuk tiga bulan penyata bank</td>
                                <td class="text-center"><button type="button" class="btn btn-success">Papar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-title card-title-grey mb-2 text-uppercase">PENYATA BANK</div>
                <p class="card-title-desc text-primary fst-italic mb-2">Pastikan semua senarai semak lengkap dipilih dan butang Menilai bertukar kepada Lihat.</p>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th style="width: 40%;">Bulan</th>
                                <th>Amaun (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">6</td>
                                <td class="text-center"><input type="text" class="form-control input-amaun" value="500,000.00" readonly></td>
                            </tr>
                            <tr>
                                <td class="text-center">7</td>
                                <td class="text-center"><input type="text" class="form-control input-amaun" value="300,000.00" readonly></td>
                            </tr>
                            <tr>
                                <td class="text-center">8</td>
                                <td class="text-center"><input type="text" class="form-control input-amaun" value="200,000.00" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row g-2 mb-3 align-items-center">
                    <div class="col-md-3 text-end fw-semibold">Jumlah Amaun (RM)</div>
                    <div class="col-md-2"><input type="text" class="form-control summary-input text-center" value="1,000,000.00" readonly></div>
                    <div class="col-md-2 text-end fw-semibold">Purata (RM)</div>
                    <div class="col-md-2"><input type="text" class="form-control summary-input text-center" value="333,333.33" readonly></div>
                    <div class="col-md-1 text-end fw-semibold">Skor Automatik</div>
                    <div class="col-md-2"><input type="text" class="form-control summary-input text-center" value="10" readonly></div>
                </div>

                <div class="card-title card-title-grey mb-2 text-uppercase">SKOR PURATA PENYATA BANK (RM)</div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th>Dari</th>
                                <th>Hingga</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">0</td>
                                <td class="text-center">10064.99</td>
                                <td class="text-center">0</td>
                            </tr>
                            <tr>
                                <td class="text-center">10065</td>
                                <td class="text-center">10205</td>
                                <td class="text-center">10</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row g-3 mb-2 align-items-center">
                    <div class="col-md-2 fw-semibold">Status Kelayakan</div>
                    <div class="col-md-3">
                        <select class="form-select" aria-label="Status Kelayakan">
                            <option selected>Layak / Tidak Layak</option>
                            <option value="layak">Layak</option>
                            <option value="tidak-layak">Tidak Layak</option>
                        </select>
                    </div>
                    <div class="col-md-1 fw-semibold">Catatan</div>
                    <div class="col-md-4">
                        <textarea class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Simpan</button>
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

    document.querySelectorAll('.btn-papar-cadangan-kewangan-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tajuk = btn.getAttribute('data-dokumen') || '';
            const tajukFirstModal = document.getElementById('modalCadanganKewanganTajukStep2');
            const tajukSecondModal = document.getElementById('modalButiranCadanganKewanganTajukStep2');
            if (tajukFirstModal && tajuk) tajukFirstModal.textContent = tajuk;
            if (tajukSecondModal && tajuk) tajukSecondModal.textContent = tajuk;
        });
    });

    document.querySelectorAll('.btn-papar-butiran-cadangan-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tajuk = btn.getAttribute('data-dokumen') || '';
            const tajukSecondModal = document.getElementById('modalButiranCadanganKewanganTajukStep2');
            if (tajukSecondModal && tajuk) tajukSecondModal.textContent = tajuk;
        });
    });
});
</script>
