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

{{-- Modal 1 (Step 2): Senarai Pembekal penyata bank — Papar baris membuka modal penilaian penyata --}}
<div class="modal fade" id="modalPenilaianCadanganKewanganStep2" tabindex="-1"
    aria-labelledby="modalLabelCadanganKewanganStep2" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-semakan-kewangan">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="modalLabelCadanganKewanganStep2">PENILAIAN CADANGAN KEWANGAN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3"><strong>Tajuk / Dokumen:</strong> <span id="modalCadanganKewanganTajukStep2">Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</span></p>

                <div class="card-title card-title-grey mb-2">Senarai Pembekal</div>
                <p class="card-title-desc text-primary fst-italic mb-3">Pastikan semua senarai semak telah dinilai dan butang Menilai telah bertukar kepada Lihat.</p>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th style="width: 15%;">Bil</th>
                                <th style="width: 22%;">Jumlah Skor</th>
                                <th style="width: 28%;">Status Penilaian</th>
                                <th style="width: 35%;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td>1/2</td>
                                <td>10</td>
                                <td>Selesai</td>
                                <td>
                                    <button type="button"
                                        class="btn btn-success btn-sm btn-papar-penyata-bank-detail-step2 px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalButiranCadanganKewanganStep2"
                                        data-bs-dismiss="modal"
                                        data-kod-pembekal="1/2">Papar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2/2</td>
                                <td>10</td>
                                <td>Selesai</td>
                                <td>
                                    <button type="button"
                                        class="btn btn-success btn-sm btn-papar-penyata-bank-detail-step2 px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalButiranCadanganKewanganStep2"
                                        data-bs-dismiss="modal"
                                        data-kod-pembekal="2/2">Papar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-primary px-5" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 2: Butiran penilaian penyata bulanan bank (selepas Papar pada senarai pembekal) --}}
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
                <p class="mb-3"><strong>Tajuk / Dokumen :</strong> <span id="modalButiranCadanganKewanganTajukStep2">Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</span></p>

                <div class="card-title card-title-grey mb-2">Dokumen Semak Silang</div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th style="width: 62%;">Tajuk / Dokumen</th>
                                <th style="width: 38%;">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kunci Kira-kira Tahunan (Nota - Untuk syarikat ROC, ini mestilah salinan yang telah diaudit)</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm">Papar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Pengesahan dari Institusi Kewangan untuk tiga bulan penyata bank</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm">Papar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-title card-title-grey mb-2">Penyata Bank</div>
                <p class="card-title-desc text-primary fst-italic mb-3">Pastikan semua senarai semak lengkap dinilai dan butang Menilai bertukar kepada Lihat.</p>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-primary text-center text-white">
                            <tr>
                                <th style="width: 35%;">Bulan</th>
                                <th style="width: 65%;">Amaun (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-semibold">6</td>
                                <td><input type="text" class="form-control text-end step2-bulan-input" id="step2-bulan-6" inputmode="decimal" value="500,000.00" aria-label="Amaun bulan 6"></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-semibold">7</td>
                                <td><input type="text" class="form-control text-end step2-bulan-input" id="step2-bulan-7" inputmode="decimal" value="300,000.00" aria-label="Amaun bulan 7"></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-semibold">8</td>
                                <td><input type="text" class="form-control text-end step2-bulan-input" id="step2-bulan-8" inputmode="decimal" value="200,000.00" aria-label="Amaun bulan 8"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1" for="step2-jumlah-amaun">Jumlah Amaun (RM)</label>
                        <input type="text" class="form-control text-end" id="step2-jumlah-amaun" readonly value="1,000,000.00" aria-live="polite">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1" for="step2-purata">Purata (RM)</label>
                        <input type="text" class="form-control text-end" id="step2-purata" readonly value="333,333.33" aria-live="polite">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1" for="step2-skor-automatik">Skor Automatik</label>
                        <input type="text" class="form-control text-center" id="step2-skor-automatik" readonly value="10" aria-live="polite">
                    </div>
                </div>

                <div class="card-title card-title-grey mb-2">Skor Purata Penyata Bank (RM)</div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0 text-center">
                        <thead class="table-primary text-white">
                            <tr>
                                <th>Dari</th>
                                <th>Hingga</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0</td>
                                <td>10,064.99</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>10,065</td>
                                <td>10,205</td>
                                <td>10</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" for="step2-status-kelayakan">Status Kelayakan</label>
                        <select class="form-select" id="step2-status-kelayakan" name="status_kelayakan_penyata">
                            <option value="" selected disabled>Sila Pilih</option>
                            <option value="layak">Layak</option>
                            <option value="tidak_layak">Tidak Layak</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="step2-catatan-penyata">Catatan</label>
                        <textarea class="form-control" id="step2-catatan-penyata" name="catatan_penyata" rows="4" placeholder=""></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-success px-5" id="btnStep2SimpanPenyataBank">Simpan</button>
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

    function parseMoney(str) {
        return parseFloat(String(str || '').replace(/,/g, '')) || 0;
    }

    function formatMoney(num) {
        return num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    /** Ikut jadual skor paparan: 0–10,064.99 → 0; ≥10,065 (julat atas contoh 10,065–10,205 → 10); purata lebih besar daripada julat contoh dikira layak skor penuh paparan */
    function step2SkorFromPurata(purata) {
        if (purata <= 10064.99) return 0;
        return 10;
    }

    function recalcStep2PenyataBank() {
        const ids = ['step2-bulan-6', 'step2-bulan-7', 'step2-bulan-8'];
        let sum = 0;
        ids.forEach(function(id) {
            const el = document.getElementById(id);
            if (el) sum += parseMoney(el.value);
        });
        const avg = sum / 3;
        const elJumlah = document.getElementById('step2-jumlah-amaun');
        const elPurata = document.getElementById('step2-purata');
        const elSkor = document.getElementById('step2-skor-automatik');
        if (elJumlah) elJumlah.value = formatMoney(sum);
        if (elPurata) elPurata.value = formatMoney(avg);
        if (elSkor) elSkor.value = String(step2SkorFromPurata(avg));
    }

    document.querySelectorAll('.step2-bulan-input').forEach(function(inp) {
        inp.addEventListener('change', recalcStep2PenyataBank);
        inp.addEventListener('blur', recalcStep2PenyataBank);
    });

    document.querySelectorAll('.btn-papar-cadangan-kewangan-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tajuk = (btn.getAttribute('data-dokumen') || '').trim();
            const tajukFirstModal = document.getElementById('modalCadanganKewanganTajukStep2');
            if (tajukFirstModal && tajuk) tajukFirstModal.textContent = tajuk;
            const tajukDetail = document.getElementById('modalButiranCadanganKewanganTajukStep2');
            if (tajukDetail && tajuk) tajukDetail.textContent = tajuk;
        });
    });

    document.querySelectorAll('.btn-papar-penyata-bank-detail-step2').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const kod = (btn.getAttribute('data-kod-pembekal') || '').trim();
            const elKod = document.getElementById('modalStep2KodPembekal');
            if (elKod && kod) elKod.textContent = kod;

            const tajukSenarai = document.getElementById('modalCadanganKewanganTajukStep2');
            const tajukDetail = document.getElementById('modalButiranCadanganKewanganTajukStep2');
            const t = (tajukSenarai && tajukSenarai.textContent) ? tajukSenarai.textContent.trim() : '';
            if (tajukDetail && t) tajukDetail.textContent = t;

            recalcStep2PenyataBank();
        });
    });

    const btnStep2SimpanPenyata = document.getElementById('btnStep2SimpanPenyataBank');
    const elModalButiranStep2 = document.getElementById('modalButiranCadanganKewanganStep2');
    const elModalCadanganStep2 = document.getElementById('modalPenilaianCadanganKewanganStep2');
    if (btnStep2SimpanPenyata && elModalButiranStep2 && elModalCadanganStep2 && typeof bootstrap !== 'undefined') {
        btnStep2SimpanPenyata.addEventListener('click', function() {
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
