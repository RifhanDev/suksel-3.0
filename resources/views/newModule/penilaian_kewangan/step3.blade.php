{{-- Step 3: Pematuhan Spesifikasi Kewangan (Kewangan + Rumusan) --}}
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-3" role="tab" aria-selected="true">Kewangan</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-3" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active step3-kewangan-pane" id="kewangan-3" role="tabpanel" aria-labelledby="kewangan-3-tab">
        <h4 class="card-title card-title-grey mb-0">CADANGAN KEWANGAN</h4>
        <p class="card-title-desc text-primary fst-italic mb-3">Klik butang Menilai untuk meneruskan penilaian.</p>

        <div class="table-responsive">
            <table class="table table-bordered dt-responsive w-100 align-middle mb-0 step3-kewangan-table">
                <thead class="text-white">
                    <tr>
                        <th class="text-start">Tajuk / Dokumen</th>
                        <th class="text-center">Mekanisma</th>
                        <th class="text-center">Status Penilaian</th>
                        <th class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                        <td class="text-center">Spesifikasi</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalPaparCadanganKewanganStep3"
                                data-dokumen="Perkhidmatan Penilaian Forensik Keatas Sistem XXXX">Papar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Maklumat Profil Petender</td>
                        <td class="text-center">Borang Atas Talian</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-papar-semakan-kewangan btn-open-profil-petender-readonly"
                                data-bs-toggle="modal" data-bs-target="#modalProfilPetenderReadonly">Papar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Salinan Sijil Pendaftaran dengan Kementerian Kewangan</td>
                        <td class="text-center">Petender Muat Naik</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalPaparCadanganKewanganStep3"
                                data-dokumen="Salinan Sijil Pendaftaran dengan Kementerian Kewangan">Papar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Surat Akuan Pembida</td>
                        <td class="text-center">PTJ Muat Naik</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalPaparCadanganKewanganStep3"
                                data-dokumen="Surat Akuan Pembida">Papar</button>
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

    <div class="tab-pane fade step3-rumusan-pane" id="rumusan-3" role="tabpanel" aria-labelledby="rumusan-3-tab">
        <div class="px-0">
            <div class="row mb-2 align-items-center g-2">
                <label class="col-sm-auto col-form-label">Paparan mengikut</label>
                <div class="col-sm-3 col-md-2">
                    <select class="form-select form-select-sm" aria-label="Paparan mengikut">
                        <option selected>Item</option>
                    </select>
                </div>
            </div>

            <div class="step3-rumusan-strip mt-2">SENARAI ITEM</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-3 step3-rumusan-table">
                    <thead class="text-white text-center">
                        <tr>
                            <th>Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="step3-rumusan-strip">SENARAI PEMBEKAL MELEPASI PENILAIAN KEWANGAN</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-3 step3-rumusan-table">
                    <thead class="text-white text-center">
                        <tr>
                            <th>Kedudukan</th>
                            <th>Bil</th>
                            <th>Jumlah Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">1/2</td>
                            <td class="text-center">100</td>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">2/2</td>
                            <td class="text-center">100</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mb-3 align-items-center g-2">
                <div class="col-auto ps-0">
                    <label class="form-label mb-0">Penetapan Penanda Aras<br>Tahap Lulus (%)</label>
                </div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control form-control-sm text-center" value="51" readonly aria-label="Penanda aras tahap lulus">
                </div>
                <div class="col-auto ms-md-4">
                    <label class="form-label mb-0">Bilangan Pembekal</label>
                </div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control form-control-sm text-center" value="2" readonly aria-label="Bilangan pembekal melepasi step 3">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep3">
                        <label class="form-check-label" for="confirmLayakStep3">
                            Saya mengesahkan petender diatas layak untuk disyorkan kepada Lembaga.
                        </label>
                    </div>
                </div>
            </div>

            <div class="step3-rumusan-strip">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN KEWANGAN</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-3 step3-rumusan-table">
                    <thead class="text-white text-center">
                        <tr>
                            <th>Kod Pembekal</th>
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

            <div class="row my-3 mb-4 align-items-center g-2">
                <div class="col-sm-auto">Bilangan Pembekal</div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control form-control-sm text-center" value="0" readonly aria-label="Bilangan pembekal tidak melepasi step 3">
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

<div class="modal fade" id="modalPaparCadanganKewanganStep3" tabindex="-1" aria-labelledby="modalPaparCadanganKewanganStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content step3-papar-modal">
            <div class="modal-body p-4">
                <div class="step3-modal-section-title mb-3">PENILAIAN CADANGAN KEWANGAN</div>
                <p class="mb-4">
                    <strong>Tajuk / Dokumen :</strong>
                    <span id="step3ModalDokumenTitle">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</span>
                </p>

                <div class="step3-modal-section-title mb-2">SENARAI PEMBEKAL</div>
                <p class="step3-modal-hint mb-2">Pemohon semua vendor semak lengkap dahulu dan butang simpan bertukar kepada lulus</p>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle mb-0 step3-modal-table">
                        <thead class="text-white text-center">
                            <tr>
                                <th>Bil</th>
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
                                <td class="text-center">1/2</td>
                                <td>Bukan</td>
                                <td>330,500.00</td>
                                <td class="text-center">50/60</td>
                                <td>5,000.00</td>
                                <td class="text-center"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-papar-semakan-kewangan btn-step3-papar-vendor"
                                        data-vendor-kod="1/2"
                                        data-vendor-status="Bukan"
                                        data-harga="330,500.00"
                                        data-skor-automatik="50/60"
                                        data-perbezaan-harga="5,000.00"
                                        data-perbezaan-peratus="">
                                        Papar
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2/2</td>
                                <td>Ya</td>
                                <td>365,500.00</td>
                                <td class="text-center">60/60</td>
                                <td>30,000.00</td>
                                <td class="text-center">9.09</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-papar-semakan-kewangan btn-step3-papar-vendor"
                                        data-vendor-kod="2/2"
                                        data-vendor-status="Ya"
                                        data-harga="365,500.00"
                                        data-skor-automatik="60/60"
                                        data-perbezaan-harga="30,000.00"
                                        data-perbezaan-peratus="9.09">
                                        Papar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="step3-modal-section-title mb-2">SKEMA SKOR</div>
                <div class="row g-2 align-items-center mb-4">
                    <label class="col-sm-auto col-form-label fw-semibold">Anggaran Jabatan Sebenar (RM)</label>
                    <div class="col-sm-3 col-md-2">
                        <input type="text" class="form-control text-center" value="335,500.00" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-papar-semakan-kewangan px-4 py-2" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPaparVendorCadanganKewanganStep3" tabindex="-1" aria-labelledby="modalPaparVendorCadanganKewanganStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content step3-papar-modal">
            <div class="modal-body p-3 p-md-4">
                <div class="step3-modal-section-title mb-3">PENILAIAN CADANGAN KEWANGAN</div>
                <p class="mb-1"><strong>Kod Pembekal:</strong> <span id="step3VendorKod">1/2</span></p>
                <p class="mb-4"><strong>Tajuk /Dokumen:</strong> <span id="step3VendorDokumenTitle">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</span></p>

                <div class="step3-modal-section-title mb-2">HARGA TAWARAN</div>
                <p class="step3-modal-hint mb-2">Pastikan semua senarai semak lengkap dahulu dan butang hantar bertukar kepada lulus</p>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0 step3-modal-table-detail">
                        <thead class="text-white text-center">
                            <tr>
                                <th rowspan="2">Item</th>
                                <th rowspan="2">Kekerapan / Kuantiti</th>
                                <th rowspan="2">Unit Ukuran</th>
                                <th rowspan="2">Anggaran Jabatan (RM)</th>
                                <th rowspan="2">Tawaran Harga (RM)</th>
                                <th rowspan="2">Catatan Pembekal</th>
                                <th colspan="2">Skor</th>
                                <th rowspan="2">Catatan Penilai</th>
                            </tr>
                            <tr>
                                <th>Automatik</th>
                                <th>Manual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="step3DetailItem">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</td>
                                <td class="text-center">1</td>
                                <td class="text-center">Activity Unit</td>
                                <td>335,500.00</td>
                                <td id="step3DetailHarga">330,500.00</td>
                                <td></td>
                                <td class="text-center" id="step3DetailSkorAuto">50/60</td>
                                <td class="text-center" id="step3DetailSkorManual">-</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" aria-label="Catatan Penilai">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center fw-semibold">JUMLAH</td>
                                <td class="fw-semibold">335,500.00</td>
                                <td id="step3DetailJumlahHarga" class="fw-semibold">330,500.00</td>
                                <td></td>
                                <td colspan="2" class="text-center" id="step3DetailJumlahSkor">50/60</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-papar-semakan-kewangan px-4 py-2" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .step3-kewangan-pane .step3-kewangan-table {
        border-color: #bfc5d1;
    }

    .step3-kewangan-pane .step3-kewangan-table thead th {
        background-color: #324e9b;
        border-color: #3b5398;
        font-weight: 500;
        font-size: 13px;
        padding: 10px 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .step3-kewangan-pane .step3-kewangan-table tbody td {
        border-color: #c9ced8;
        font-size: 13px;
        padding: 11px 12px;
        vertical-align: middle;
        background-color: #fff;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(1),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(1) {
        width: 63%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(2),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(2) {
        width: 14%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(3),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(3) {
        width: 15%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(4),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(4) {
        width: 8%;
        min-width: 110px;
    }

    .step3-kewangan-pane .btn-papar-semakan-kewangan,
    .step3-papar-modal .btn-papar-semakan-kewangan {
        background-color: #16A34A;
        border-color: #16A34A;
        color: #fff;
        min-width: 110px;
        font-size: 13px;
        line-height: 1.25;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .step3-kewangan-pane .btn-papar-semakan-kewangan:hover,
    .step3-kewangan-pane .btn-papar-semakan-kewangan:focus,
    .step3-papar-modal .btn-papar-semakan-kewangan:hover,
    .step3-papar-modal .btn-papar-semakan-kewangan:focus {
        background-color: #15803D;
        border-color: #15803D;
        color: #fff;
    }

    .step3-rumusan-pane .form-select-sm,
    .step3-rumusan-pane .form-control-sm {
        height: 32px;
        font-size: 12px;
    }

    .step3-rumusan-strip {
        background-color: #d9d9d9;
        border: 1px solid #d2d4da;
        border-bottom: 0;
        color: #222;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
        text-transform: uppercase;
    }

    .step3-rumusan-table {
        border-color: #a6adba;
    }

    .step3-rumusan-table thead th {
        background-color: #324e9b;
        border-color: #5f6fa4;
        color: #fff;
        font-weight: 500;
        font-size: 12px;
        padding: 6px 10px;
        vertical-align: middle;
    }

    .step3-rumusan-table td {
        border-color: #a6adba;
        font-size: 12px;
        padding: 7px 10px;
        vertical-align: middle;
        background-color: #fff;
    }

    .step3-rumusan-pane .form-check-input {
        width: 12px;
        height: 12px;
        border-radius: 0;
        margin-top: 0.35em;
    }

    .step3-rumusan-pane .form-check-label {
        font-size: 14px;
    }

    .step3-papar-modal .modal-body {
        background: #f6f7f9;
    }

    .step3-modal-section-title {
        background-color: #d9d9d9;
        font-size: 14px;
        font-weight: 700;
        padding: 6px 10px;
        color: #222;
    }

    .step3-modal-hint {
        font-size: 12px;
        color: #2b5cba;
        font-style: italic;
    }

    .step3-modal-table {
        border-color: #9aa3b3;
    }

    .step3-modal-table thead th {
        background-color: #324e9b;
        border-color: #51629a;
        font-weight: 500;
        font-size: 13px;
        padding: 8px 10px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .step3-modal-table td {
        border-color: #9aa3b3;
        font-size: 13px;
        padding: 8px 10px;
        background: #fff;
    }

    .step3-modal-table-detail {
        border-color: #9aa3b3;
    }

    .step3-modal-table-detail thead th {
        background-color: #324e9b;
        border-color: #51629a;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 8px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .step3-modal-table-detail td {
        border-color: #9aa3b3;
        font-size: 13px;
        padding: 8px 8px;
        background-color: #fff;
        vertical-align: middle;
    }

    .step3-modal-table-detail th:nth-child(1),
    .step3-modal-table-detail td:nth-child(1) {
        min-width: 220px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#kewangan-3 .btn-papar-semakan-kewangan:not(.btn-open-profil-petender-readonly)').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const dokumen = btn.getAttribute('data-dokumen') || '';
            const titleEl = document.getElementById('step3ModalDokumenTitle');
            if (titleEl && dokumen) {
                titleEl.textContent = `${dokumen}.`;
            }
        });
    });

    const firstModalEl = document.getElementById('modalPaparCadanganKewanganStep3');
    const secondModalEl = document.getElementById('modalPaparVendorCadanganKewanganStep3');
    const firstModal = firstModalEl ? bootstrap.Modal.getOrCreateInstance(firstModalEl) : null;
    const secondModal = secondModalEl ? bootstrap.Modal.getOrCreateInstance(secondModalEl) : null;

    document.querySelectorAll('.btn-step3-papar-vendor').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const vendorKod = btn.getAttribute('data-vendor-kod') || '';
            const vendorHarga = btn.getAttribute('data-harga') || '';
            const vendorSkorAuto = btn.getAttribute('data-skor-automatik') || '-';
            const vendorSkorManual = btn.getAttribute('data-skor-manual') || '-';
            const dokumen = document.getElementById('step3ModalDokumenTitle')?.textContent?.trim() || '';

            const elKod = document.getElementById('step3VendorKod');
            const elDokumen = document.getElementById('step3VendorDokumenTitle');
            const elItem = document.getElementById('step3DetailItem');
            const elHarga = document.getElementById('step3DetailHarga');
            const elJumlahHarga = document.getElementById('step3DetailJumlahHarga');
            const elSkorAuto = document.getElementById('step3DetailSkorAuto');
            const elJumlahSkor = document.getElementById('step3DetailJumlahSkor');
            const elSkorManual = document.getElementById('step3DetailSkorManual');

            if (elKod) elKod.textContent = vendorKod;
            if (elDokumen && dokumen) elDokumen.textContent = dokumen;
            if (elItem && dokumen) elItem.textContent = dokumen;
            if (elHarga && vendorHarga) elHarga.textContent = vendorHarga;
            if (elJumlahHarga && vendorHarga) elJumlahHarga.textContent = vendorHarga;
            if (elSkorAuto) elSkorAuto.textContent = vendorSkorAuto;
            if (elJumlahSkor) elJumlahSkor.textContent = vendorSkorAuto;
            if (elSkorManual) elSkorManual.textContent = vendorSkorManual;

            if (firstModal && secondModal) {
                firstModalEl.addEventListener('hidden.bs.modal', function handleHidden() {
                    secondModal.show();
                    firstModalEl.removeEventListener('hidden.bs.modal', handleHidden);
                });
                firstModal.hide();
            }
        });
    });

    document.querySelectorAll('#penilaian .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#penyata-bank-tab');
            if (tab) tab.click();
        });
    });
});
</script>

