{{-- MODAL: PENILAIAN KEMAMPUAN KEWANGAN (LANGKAH 2) --}}
{{-- Diadaptasi daripada Borang 3 Tender Kerja Besar (Ringkasan Modal, Lembaran Imbangan, Penyata Bank, Bon & Saham) --}}
<div class="modal fade" id="modalKemampuanKewanganVendor" tabindex="-1" aria-labelledby="modalLabelKemampuanKewangan" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            {{-- Modal Header --}}
            <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 44px; height: 44px; background: #fee2e2; color: #dc2626;">
                        <i class="bi bi-calculator-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Langkah 2: Kemampuan Kewangan</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Rujukan Borang 3</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.05rem;">Penilaian Kemampuan & Had Modal Petender</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Vendor Identity Bar --}}
                <div class="p-3 mb-4 rounded-3 border bg-white shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-left: 4px solid #dc2626 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <span id="modalKewanganVendorKod" class="badge bg-dark font-monospace px-3 py-2 fs-6 rounded-2">1/5</span>
                        <div>
                            <h6 id="modalKewanganVendorNama" class="fw-bold text-dark mb-0 fs-6">NAMA PETENDER BERHAD</h6>
                            <span id="modalKewanganVendorInfo" class="text-muted extra-small">SSM: 201801034567 | CIDB Gred G3 (B04, CE21)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-2">
                            <i class="bi bi-wallet2 text-danger me-1"></i>Syarat Min. Modal (1.5%): <strong id="modalKewanganMinModal">{{ $min_modal_display ?? 'RM 7,500.00' }}</strong>
                        </span>
                    </div>
                </div>

                {{-- 4-Tab Navigation (Adapted from Type 3 Borang 3) --}}
                <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabModalRingkasanModal" role="tab">
                            <i class="bi bi-calculator me-1"></i>1. Ringkasan Modal
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabModalLembaranImbangan" role="tab">
                            <i class="bi bi-journal-text me-1"></i>2. Lembaran Imbangan
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabModalAkaunBank" role="tab">
                            <i class="bi bi-bank me-1"></i>3. Penyata Akaun Bank
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabModalBonSaham" role="tab">
                            <i class="bi bi-cash-coin me-1"></i>4. Bon & Saham
                        </button>
                    </li>
                </ul>

                <div class="tab-content border rounded-3 p-3.5 bg-white shadow-sm mt-3">

                    {{-- TAB 1: Ringkasan Modal --}}
                    <div class="tab-pane fade show active" id="tabModalRingkasanModal" role="tabpanel">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-graph-up-arrow text-danger fs-5 me-2"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Analisis Nisbah Kecukupan Modal vs Had Minimum</h6>
                                <p class="text-muted extra-small mb-0">Ringkasan modal pusingan, purata bank dan aset cair berbanding modal minimum diperlukan.</p>
                            </div>
                        </div>

                        {{-- Metric Cards --}}
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 border bg-light">
                                    <div class="text-muted extra-small text-uppercase fw-bold">Modal Pusingan</div>
                                    <div class="fs-6 fw-bold text-dark font-monospace" id="mkModalPusingan">RM 124,500.00</div>
                                    <div class="extra-small text-muted">(Aset Semasa - Liabiliti)</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 border bg-light">
                                    <div class="text-muted extra-small text-uppercase fw-bold">Purata Penyata Bank</div>
                                    <div class="fs-6 fw-bold text-dark font-monospace" id="mkPurataBank">RM 48,200.00</div>
                                    <div class="extra-small text-muted">(Purata baki 3 bulan)</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 border bg-light">
                                    <div class="text-muted extra-small text-uppercase fw-bold">Wang Dalam Tangan / Bon</div>
                                    <div class="fs-6 fw-bold text-dark font-monospace" id="mkWangTangan">RM 20,000.00</div>
                                    <div class="extra-small text-muted">(Simpanan / Saham cair)</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 border bg-light border-danger-subtle">
                                    <div class="text-danger extra-small text-uppercase fw-bold">Jumlah Modal Boleh Guna</div>
                                    <div class="fs-6 fw-bold text-danger font-monospace" id="mkJumlahModal">RM 192,700.00</div>
                                    <div class="extra-small text-success fw-semibold" id="mkStatusKecukupan">
                                        <i class="bi bi-check-circle-fill me-1"></i>Mencukupi Had Min (3%)
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Calculation Breakdown Table --}}
                        <div class="table-responsive rounded-2 border">
                            <table class="table table-sm align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%;">Komponen Penilaian Modal</th>
                                        <th class="text-end" style="width: 25%;">Nilai Komponen (RM)</th>
                                        <th class="text-center" style="width: 25%;">Keterangan Sumber</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">Aset Semasa (b) - Liabiliti Semasa (c)</td>
                                        <td class="text-end font-monospace" id="mkRowModalPusingan">RM 124,500.00</td>
                                        <td class="text-center text-muted">Lembaran Imbangan</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Purata Baki 3 Bulan Penyata Bank (f)</td>
                                        <td class="text-end font-monospace" id="mkRowPurataBank">RM 48,200.00</td>
                                        <td class="text-center text-muted">Penyata Akaun Bank</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Wang Tunai Dalam Tangan / Deposit Saham (g + h)</td>
                                        <td class="text-end font-monospace" id="mkRowWangTangan">RM 20,000.00</td>
                                        <td class="text-center text-muted">Borang CA 2 / Sijil Saham</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Kemudahan Kredit Diluluskan (j)</td>
                                        <td class="text-end font-monospace" id="mkRowKredit">RM 0.00</td>
                                        <td class="text-center text-muted">Surat Bank / Borang CA 1</td>
                                    </tr>
                                    <tr class="table-light fw-bold">
                                        <td>JUMLAH BESAR MODAL TERKUMPUL (k)</td>
                                        <td class="text-end text-danger font-monospace" id="mkRowJumlahTerkumpul">RM 192,700.00</td>
                                        <td class="text-center text-success"><i class="bi bi-shield-check me-1"></i>Layak (Melebihi Had 3%)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 2: Lembaran Imbangan --}}
                    <div class="tab-pane fade" id="tabModalLembaranImbangan" role="tabpanel">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-file-earmark-spreadsheet text-danger fs-5 me-2"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Maklumat Lembaran Imbangan (Balance Sheet) Petender</h6>
                                <p class="text-muted extra-small mb-0">Penyata kedudukan kewangan beraudit yang dikemukakan bagi tahun kewangan terkini.</p>
                            </div>
                        </div>

                        <div class="table-responsive rounded-2 border">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Butiran Lembaran Imbangan</th>
                                        <th class="text-end" style="width: 200px;">Jumlah (RM)</th>
                                        <th style="width: 250px;">Catatan Penilai</th>
                                    </tr>
                                </thead>
                                <tbody id="mkLembaranBody">
                                    <tr>
                                        <td>Aset Tetap (Fixed Assets)</td>
                                        <td class="text-end font-monospace fw-medium" id="mkAsetTetap">RM 320,000.00</td>
                                        <td class="text-muted small">Hartanah, jentera & peralatan operasi</td>
                                    </tr>
                                    <tr>
                                        <td>Aset Semasa (Current Assets)</td>
                                        <td class="text-end font-monospace fw-medium text-primary" id="mkAsetSemasa">RM 210,000.00</td>
                                        <td class="text-muted small">Tunai, penghutang & inventori</td>
                                    </tr>
                                    <tr>
                                        <td>Liabiliti Semasa (Current Liabilities)</td>
                                        <td class="text-end font-monospace fw-medium text-danger" id="mkLiabilitiSemasa">RM 85,500.00</td>
                                        <td class="text-muted small">Pemiutang perdagangan & pinjaman jangka pendek</td>
                                    </tr>
                                    <tr>
                                        <td>Liabiliti Tetap / Jangka Panjang</td>
                                        <td class="text-end font-monospace fw-medium" id="mkLiabilitiTetap">RM 110,000.00</td>
                                        <td class="text-muted small">Pinjaman bank jangka panjang / sewa beli</td>
                                    </tr>
                                    <tr>
                                        <td>Wang Tunai Dalam Tangan</td>
                                        <td class="text-end font-monospace fw-medium text-success" id="mkWangTunai">RM 20,000.00</td>
                                        <td class="text-muted small">Baki tunai fizikal dan simpanan segera</td>
                                    </tr>
                                    <tr class="table-light fw-bold">
                                        <td>Nisbah Semasa (Current Ratio)</td>
                                        <td class="text-end font-monospace text-dark" id="mkCurrentRatio">2.45</td>
                                        <td class="text-success small"><i class="bi bi-check-circle me-1"></i>Kedudukan kecairan sihat (> 1.0)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 3: Penyata Akaun Bank --}}
                    <div class="tab-pane fade" id="tabModalAkaunBank" role="tabpanel">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-bank text-danger fs-5 me-2"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Maklumat Penyata Akaun Bank Bagi 3 Bulan Lepas</h6>
                                <p class="text-muted extra-small mb-0">Pecahan baki akhir setiap bulan mengikut akaun bank yang disahkan.</p>
                            </div>
                        </div>

                        <div class="table-responsive rounded-2 border mb-3">
                            <table class="table table-sm table-bordered align-middle mb-0" style="font-size: 0.8rem;">
                                <thead style="background-color: #f1f5f9; text-align: center;">
                                    <tr>
                                        <th>Institusi Perbankan</th>
                                        <th>No. Akaun Bank</th>
                                        <th>Bulan 1 (RM)</th>
                                        <th>Bulan 2 (RM)</th>
                                        <th>Bulan 3 (RM)</th>
                                        <th class="table-light">Purata 3 Bulan (RM)</th>
                                    </tr>
                                </thead>
                                <tbody id="mkBankBody">
                                    {{-- Dynamically rendered via JS based on vendor's actual submission --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Bank Document Attachment Container (Multi-file Support) --}}
                        <div class="card border rounded-3 bg-light p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-paperclip text-danger fs-5"></i>
                                    <div>
                                        <span class="small fw-bold text-dark d-block">Dokumen Penyata Bank Yang Dimuat Naik</span>
                                        <span class="text-muted extra-small">Semua fail penyata akaun bank yang dikemukakan oleh petender ini.</span>
                                    </div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5 rounded-pill extra-small" id="mkBankFilesBadge">0 Fail</span>
                            </div>
                            <div class="d-flex flex-column gap-2 mt-2" id="mkBankFilesContainer">
                                {{-- Populated dynamically by JS with all files uploaded by this vendor --}}
                            </div>
                        </div>
                    </div>

                    {{-- TAB 4: Bon & Saham --}}
                    <div class="tab-pane fade" id="tabModalBonSaham" role="tabpanel">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-cash-stack text-danger fs-5 me-2"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Maklumat Bon Pelaksanaan, Simpanan Tetap & Modal Saham</h6>
                                <p class="text-muted extra-small mb-0">Semakan sijil deposit tetap, bon penjamin, atau modal berbayar petender.</p>
                            </div>
                        </div>

                        <div class="table-responsive rounded-2 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 10%;" class="text-center">Bil</th>
                                        <th style="width: 50%;">Institusi / Bank / Instrumen</th>
                                        <th class="text-end" style="width: 40%;">Jumlah Deposit / Nilai (RM)</th>
                                    </tr>
                                </thead>
                                <tbody id="mkBonSahamBody">
                                    {{-- Rendered dynamically by JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- Overall Decision Section inside Modal --}}
                <div class="card bg-light border p-3 mt-4 rounded-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">
                                Keputusan Penilaian Kewangan: <span class="text-danger">*</span>
                            </label>
                            <select id="modalKewanganDecisionSelect" class="form-select form-select-sm fw-bold">
                                <option value="" disabled>-- Sila Pilih --</option>
                                <option value="memuaskan" selected>Memuaskan</option>
                                <option value="tidak_memuaskan">Tidak Memuaskan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold text-dark small mb-1">
                                Catatan / Justifikasi Penilai:
                            </label>
                            <input type="text" id="modalKewanganCatatanInput" class="form-control form-control-sm" placeholder="Nyatakan ulasan kedudukan kewangan petender...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-sm btn-danger px-4 fw-bold shadow-sm" id="btnSimpanKemampuanKewangan">
                    <i class="bi bi-check2-circle me-1"></i>Simpan Keputusan Kewangan
                </button>
            </div>
        </div>
    </div>
</div>
