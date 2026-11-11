{{-- MODAL: PENILAIAN KEMAMPUAN KEWANGAN (LANGKAH 2) --}}
{{-- Diadaptasi daripada Borang 3 Tender Kerja Besar (Ringkasan Modal, Lembaran Imbangan, Penyata Bank, Bon & Saham) --}}
<style>
    #modalKemampuanKewanganVendor .mk-tab-nav {
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    #modalKemampuanKewanganVendor .mk-tab-nav .nav-link {
        min-width: 150px;
        color: #475569;
        font-weight: 600;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    #modalKemampuanKewanganVendor .mk-tab-nav .nav-link.active {
        background: #fff;
        color: #b42318;
        border-color: #fecaca;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
    }
    #modalKemampuanKewanganVendor .mk-tab-pane-title {
        padding-bottom: .85rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    #modalKemampuanKewanganVendor .mk-data-table thead th {
        color: #475569;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    #modalKemampuanKewanganVendor .mk-data-table tbody td {
        padding-top: .8rem;
        padding-bottom: .8rem;
    }
    #modalKemampuanKewanganVendor .mk-metric-card {
        min-height: 128px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    #modalKemampuanKewanganVendor .mk-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1.25rem rgba(15, 23, 42, .08);
    }
    #modalKemampuanKewanganVendor .mk-bank-file-item {
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
    }
    #modalKemampuanKewanganVendor .mk-bank-file-item:hover {
        border-color: #fca5a5;
        box-shadow: 0 .25rem .75rem rgba(15, 23, 42, .07);
        transform: translateY(-1px);
    }
    #modalKemampuanKewanganVendor .mk-bank-file-icon {
        width: 42px;
        height: 42px;
        border-radius: .65rem;
        background: #fff1f2;
        color: #dc2626;
    }
    @media (max-width: 575.98px) {
        #modalKemampuanKewanganVendor .mk-tab-nav .nav-link { min-width: 132px; }
        #modalKemampuanKewanganVendor .mk-metric-card { min-height: 116px; }
    }
</style>

<div class="modal fade" id="modalKemampuanKewanganVendor" tabindex="-1" aria-labelledby="modalLabelKemampuanKewangan" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Modal Header --}}
            <div class="modal-header px-3 px-md-4 py-3 border-0 bg-white">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 44px; height: 44px; background: #fee2e2; color: #dc2626;">
                        <i class="bi bi-calculator-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill" style="font-size: 0.68rem;">LANGKAH 2</span>
                            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill" style="font-size: 0.68rem;">RUJUKAN BORANG 3</span>
                        </div>
                        <h5 id="modalLabelKemampuanKewangan" class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.05rem;">Penilaian Kemampuan & Had Modal Petender</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-3 p-md-4 bg-light">
                {{-- Vendor Identity Bar --}}
                <div class="p-3 p-md-4 mb-4 rounded-3 border bg-white shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-left: 4px solid #dc2626 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 46px; height: 46px;"><i class="bi bi-building fs-5"></i></span>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span id="modalKewanganVendorKod" class="badge bg-dark font-monospace px-2 py-1 rounded-2">1/5</span>
                                <span class="text-uppercase text-muted fw-bold" style="font-size: .65rem; letter-spacing: .06em;">Maklumat Petender</span>
                            </div>
                            <h6 id="modalKewanganVendorNama" class="fw-bold text-dark mb-1 fs-6">NAMA PETENDER BERHAD</h6>
                            <span id="modalKewanganVendorInfo" class="text-muted small">SSM: - | CIDB Gred: -</span>
                        </div>
                    </div>
                    <div class="rounded-3 border bg-danger-subtle px-3 py-2 text-md-end">
                        <div class="text-danger text-uppercase fw-bold mb-1" style="font-size: .68rem; letter-spacing: .05em;"><i class="bi bi-wallet2 me-1"></i>Syarat Minimum Modal (1.5%)</div>
                        <strong id="modalKewanganMinModal" class="font-monospace text-dark">{{ $min_modal_display ?? 'RM 7,500.00' }}</strong>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 rounded-3 border border-primary-subtle bg-primary-subtle bg-opacity-25 px-3 py-3 mb-3">
                    <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                    <div>
                        <div class="fw-bold text-dark small mb-1">Semakan merangkumi keempat-empat bahagian</div>
                        <p class="text-secondary small mb-0">Tab di bawah memaparkan maklumat sokongan penilaian. Pilih satu keputusan keseluruhan dan masukkan catatan selepas menyemak semua bahagian.</p>
                    </div>
                </div>

                {{-- 4-Tab Navigation (Adapted from Type 3 Borang 3) --}}
                <ul class="nav nav-pills nav-fill gap-1 p-2 bg-white border rounded-3 shadow-sm mb-3 mk-tab-nav" role="tablist" aria-label="Bahagian penilaian kewangan">
                    <li class="nav-item">
                        <button type="button" class="nav-link active rounded-3" id="tabModalRingkasanModalButton" data-bs-toggle="tab" data-bs-target="#tabModalRingkasanModal" role="tab" aria-controls="tabModalRingkasanModal" aria-selected="true">
                            <i class="bi bi-calculator me-1"></i><span>1. Ringkasan Modal</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-3" id="tabModalLembaranImbanganButton" data-bs-toggle="tab" data-bs-target="#tabModalLembaranImbangan" role="tab" aria-controls="tabModalLembaranImbangan" aria-selected="false">
                            <i class="bi bi-journal-text me-1"></i><span>2. Lembaran Imbangan</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-3" id="tabModalAkaunBankButton" data-bs-toggle="tab" data-bs-target="#tabModalAkaunBank" role="tab" aria-controls="tabModalAkaunBank" aria-selected="false">
                            <i class="bi bi-bank me-1"></i><span>3. Penyata Akaun Bank</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-3" id="tabModalBonSahamButton" data-bs-toggle="tab" data-bs-target="#tabModalBonSaham" role="tab" aria-controls="tabModalBonSaham" aria-selected="false">
                            <i class="bi bi-cash-coin me-1"></i><span>4. Bon & Saham</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content border rounded-3 p-3 p-md-4 bg-white shadow-sm mt-3">

                    {{-- TAB 1: Ringkasan Modal --}}
                    <div class="tab-pane fade show active" id="tabModalRingkasanModal" role="tabpanel">
                        <div class="d-flex align-items-center gap-3 mk-tab-pane-title">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-graph-up-arrow fs-5"></i></span>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Analisis Nisbah Kecukupan Modal vs Had Minimum</h6>
                                <p class="text-muted extra-small mb-0">Ringkasan modal pusingan, purata bank dan aset cair berbanding modal minimum diperlukan.</p>
                            </div>
                        </div>

                        {{-- Metric Cards --}}
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="mk-metric-card p-3 rounded-3 border bg-light">
                                    <div class="text-muted extra-small text-uppercase fw-bold">Modal Pusingan</div>
                                    <div class="fs-6 fw-bold text-dark font-monospace" id="mkModalPusingan">RM 0.00</div>
                                    <div class="extra-small text-muted">(Aset Semasa - Liabiliti)</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mk-metric-card p-3 rounded-3 border bg-light">
                                    <div class="text-muted extra-small text-uppercase fw-bold">Purata Penyata Bank</div>
                                    <div class="fs-6 fw-bold text-dark font-monospace" id="mkPurataBank">RM 0.00</div>
                                    <div class="extra-small text-muted">(Purata baki 3 bulan)</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mk-metric-card p-3 rounded-3 border bg-light">
                                    <div class="text-muted extra-small text-uppercase fw-bold">Aset Cair</div>
                                    <div class="fs-6 fw-bold text-dark font-monospace" id="mkWangTangan">RM 0.00</div>
                                    <div class="extra-small text-muted">Tunai dalam tangan + bon / saham</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mk-metric-card p-3 rounded-3 border bg-danger-subtle border-danger-subtle">
                                    <div class="text-danger extra-small text-uppercase fw-bold">Mudah Cair Boleh Guna</div>
                                    <div class="fs-6 fw-bold text-danger font-monospace" id="mkJumlahModal">RM 0.00</div>
                                    <div class="extra-small text-success fw-semibold" id="mkStatusKecukupan">
                                        <i class="bi bi-check-circle-fill me-1"></i>Mencukupi Had Minimum
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Calculation Breakdown Table --}}
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-sm table-hover align-middle mb-0 mk-data-table" style="font-size: 0.82rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%;">Komponen Penilaian Modal</th>
                                        <th class="text-end" style="width: 25%;">Nilai Komponen (RM)</th>
                                        <th class="text-center" style="width: 25%;">Keterangan Sumber</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">Aset Semasa - Liabiliti Semasa</td>
                                        <td class="text-end font-monospace" id="mkRowModalPusingan">RM 0.00</td>
                                        <td class="text-center text-muted">Lembaran Imbangan</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Purata Baki 3 Bulan Penyata Bank</td>
                                        <td class="text-end font-monospace" id="mkRowPurataBank">RM 0.00</td>
                                        <td class="text-center text-muted">Penyata Akaun Bank</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Wang Dalam Tangan</td>
                                        <td class="text-end font-monospace" id="mkRowWangTangan">RM 0.00</td>
                                        <td class="text-center text-muted">Lembaran Imbangan</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Bon / Deposit Tetap / Saham</td>
                                        <td class="text-end font-monospace" id="mkRowBonSaham">RM 0.00</td>
                                        <td class="text-center text-muted">Borang CA 2 / Sijil Saham</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Aset Cair</td>
                                        <td class="text-end font-monospace fw-semibold" id="mkRowAsetCair">RM 0.00</td>
                                        <td class="text-center text-muted">Jumlah</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Kemudahan Kredit Diluluskan</td>
                                        <td class="text-end font-monospace" id="mkRowKredit">RM 0.00</td>
                                        <td class="text-center text-muted">Surat Bank / Borang CA 1</td>
                                    </tr>
                                    <tr class="table-light fw-bold">
                                        <td>JUMLAH MODAL TERKUMPUL</td>
                                        <td class="text-end text-danger font-monospace" id="mkRowJumlahTerkumpul">RM 0.00</td>
                                        <td class="text-center text-muted">Purata bank + aset cair + kredit</td>
                                    </tr>
                                    <tr class="fw-semibold">
                                        <td>Mudah Cair Boleh Guna</td>
                                        <td class="text-end font-monospace" id="mkRowMudahCair">RM 0.00</td>
                                        <td class="text-center" id="mkRowStatusMinimum">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 2: Lembaran Imbangan --}}
                    <div class="tab-pane fade" id="tabModalLembaranImbangan" role="tabpanel">
                        <div class="d-flex align-items-center gap-3 mk-tab-pane-title">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-file-earmark-spreadsheet fs-5"></i></span>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Maklumat Lembaran Imbangan (Balance Sheet) Petender</h6>
                                <p class="text-muted extra-small mb-0">Penyata kedudukan kewangan beraudit yang dikemukakan bagi tahun kewangan terkini.</p>
                            </div>
                        </div>

                        <div class="table-responsive rounded-3 border">
                            <table class="table table-sm table-hover align-middle mb-0 mk-data-table" style="font-size: 0.82rem;">
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
                                        <td class="text-end font-monospace text-dark" id="mkCurrentRatio">-</td>
                                        <td class="text-muted small" id="mkCurrentRatioStatus">Nisbah aset semasa kepada liabiliti semasa</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 3: Penyata Akaun Bank --}}
                    <div class="tab-pane fade" id="tabModalAkaunBank" role="tabpanel">
                        <div class="d-flex align-items-center gap-3 mk-tab-pane-title">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-bank fs-5"></i></span>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Maklumat Penyata Akaun Bank Bagi 3 Bulan Lepas</h6>
                                <p class="text-muted extra-small mb-0">Pecahan baki akhir setiap bulan mengikut akaun bank yang disahkan.</p>
                            </div>
                        </div>

                        <div class="table-responsive rounded-3 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0 mk-data-table" style="font-size: 0.8rem;">
                                <thead style="background-color: #f1f5f9; text-align: center;">
                                    <tr>
                                        <th class="table-light">Institusi Perbankan</th>
                                        <th class="table-light">Bulan 1 (RM)</th>
                                        <th class="table-light">Bulan 2 (RM)</th>
                                        <th class="table-light">Bulan 3 (RM)</th>
                                        <th class="table-light">Purata 3 Bulan (RM)</th>
                                    </tr>
                                </thead>
                                <tbody id="mkBankBody">
                                    {{-- Dynamically rendered via JS based on vendor's actual submission --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Bank Document Attachment Container (Multi-file Support) --}}
                        <section class="rounded-3 border bg-white shadow-sm overflow-hidden mt-3 mb-3" aria-labelledby="mkBankDocumentsTitle">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 bg-light border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-folder2-open fs-5"></i>
                                    </span>
                                    <div>
                                        <h6 class="small fw-bold text-dark mb-1" id="mkBankDocumentsTitle">Dokumen Penyata Bank Yang Dimuat Naik</h6>
                                        <p class="text-muted extra-small mb-0">Semak dan pratonton fail yang dikemukakan oleh petender ini.</p>
                                    </div>
                                </div>
                                <span class="badge bg-white text-secondary border rounded-pill px-3 py-2" id="mkBankFilesBadge">
                                    <i class="bi bi-files me-1"></i>0 Fail
                                </span>
                            </div>
                            <div class="p-3" style="background: #f8fafc;">
                                <div class="d-flex flex-column gap-2" id="mkBankFilesContainer">
                                {{-- Populated dynamically by JS with all files uploaded by this vendor --}}
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- TAB 4: Bon & Saham --}}
                    <div class="tab-pane fade" id="tabModalBonSaham" role="tabpanel">
                        <div class="d-flex align-items-center gap-3 mk-tab-pane-title">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-cash-stack fs-5"></i></span>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Maklumat Bon Pelaksanaan, Simpanan Tetap & Modal Saham</h6>
                                <p class="text-muted extra-small mb-0">Semakan sijil deposit tetap, bon penjamin, atau modal berbayar petender.</p>
                            </div>
                        </div>

                        <div class="table-responsive rounded-3 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0 mk-data-table" style="font-size: 0.82rem;">
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

            </div>

            <div class="modal-footer bg-white border-top px-3 px-md-4 py-3">
                <div class="w-100 rounded-3 border bg-light p-3 mb-2">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-clipboard-check text-danger fs-5"></i>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="fw-bold text-dark small">Keputusan Keseluruhan — Semua 4 Bahagian <span class="text-danger">*</span></div>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Satu keputusan untuk petender ini</span>
                            </div>
                            <div class="text-secondary" style="font-size: .72rem;">Pilih keputusan akhir selepas menyemak semua tab; keputusan ini merangkumi keseluruhan penilaian kemampuan kewangan.</div>
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-4">
                            <label for="modalKewanganDecisionSelect" class="form-label fw-semibold text-secondary small mb-1">Keputusan</label>
                            <select id="modalKewanganDecisionSelect" class="form-select fw-semibold">
                                <option value="">-- Sila Pilih --</option>
                                <option value="memuaskan">Memuaskan</option>
                                <option value="tidak_memuaskan">Tidak Memuaskan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="modalKewanganCatatanInput" class="form-label fw-semibold text-secondary small mb-1">Catatan / Justifikasi Keseluruhan</label>
                            <input type="text" id="modalKewanganCatatanInput" class="form-control" placeholder="Nyatakan ulasan kedudukan kewangan petender...">
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 w-100">
                    <button type="button" class="btn btn-outline-secondary px-3 fw-semibold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4 fw-bold shadow-sm" id="btnSimpanKemampuanKewangan">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Keputusan Kewangan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
