{{-- LANGKAH 2: PENILAIAN KEMAMPUAN KEWANGAN (SEBUT HARGA KERJA) --}}
<div class="tab-pane fade" id="penyata-bank" role="tabpanel" aria-labelledby="penyata-bank-tab">

    {{-- Sub-tabs (Kewangan & Rumusan) --}}
    <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-2" role="tab" aria-selected="true">
                <i class="bi bi-calculator me-1"></i>Kewangan
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link" data-bs-toggle="tab" href="#rumusan-2" role="tab" aria-selected="false">
                <i class="bi bi-clipboard-data me-1"></i>Rumusan
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4">

        {{-- ========================================== --}}
        {{-- SUB-TAB 1: KEWANGAN (ANALISIS MODAL & BANK)--}}
        {{-- ========================================== --}}
        <div class="tab-pane fade show active" id="kewangan-2" role="tabpanel">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger-subtle p-2 rounded-2 me-3 text-danger">
                    <i class="bi bi-bank2 fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Penilaian Kemampuan Kewangan & Had Modal</h5>
                    <p class="text-secondary small mb-0">Analisis kecukupan modal pusingan, lembaran imbangan, purata baki penyata bank 3 bulan, dan bon petender (Rujukan Borang 3).</p>
                </div>
            </div>

            {{-- Info Alert --}}
            <div class="rounded-3 px-3 py-2.5 d-inline-flex align-items-center gap-2 mb-3 w-100" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.825rem; color:#1e40af;">
                <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0"></i>
                <div>
                    <span class="fw-bold">Maklumat Saringan:</span>
                    Hanya petender yang telah <strong>melepasi Pematuhan Dokumentasi (Langkah 1)</strong> dipaparkan di bawah. Klik butang <strong>Nilai Kewangan</strong> untuk menyemak lembaran imbangan, penyata akaun bank, dan memasukkan keputusan penilaian kewangan.
                </div>
            </div>

            {{-- Financial Baseline KPI Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-3">
                    <div class="p-3 rounded-3 border bg-white shadow-sm">
                        <div class="text-muted extra-small text-uppercase fw-bold mb-1">
                            <i class="bi bi-tag text-danger me-1"></i>Anggaran Jabatan
                        </div>
                        <div class="fs-6 fw-bold text-dark font-monospace">{{ $anggaran_display ?? 'RM 500,000.00' }}</div>
                        <div class="extra-small text-muted">Nilai asas perolehan sebut harga</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="p-3 rounded-3 border bg-white shadow-sm">
                        <div class="text-muted extra-small text-uppercase fw-bold mb-1">
                            <i class="bi bi-percent text-danger me-1"></i>Syarat Min. Modal (1.5%)
                        </div>
                        <div class="fs-6 fw-bold text-danger font-monospace" id="step2MinModalText">{{ $min_modal_display ?? 'RM 7,500.00' }}</div>
                        <div class="extra-small text-muted">Ambang minimum kecukupan modal</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="p-3 rounded-3 border bg-white shadow-sm">
                        <div class="text-muted extra-small text-uppercase fw-bold mb-1">
                            <i class="bi bi-people text-danger me-1"></i>Petender Layak Dinilai
                        </div>
                        <div class="fs-6 fw-bold text-dark font-monospace" id="step2QualifiedCountText">5 Petender</div>
                        <div class="extra-small text-muted">Melepasi semakan dokumentasi</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="p-3 rounded-3 border bg-white shadow-sm">
                        <div class="text-muted extra-small text-uppercase fw-bold mb-1">
                            <i class="bi bi-check2-all text-danger me-1"></i>Status Penilaian
                        </div>
                        <div class="fs-6 fw-bold text-success" id="step2ProgressStatusText">Sedia Dinilai</div>
                        <div class="extra-small text-muted">Kriteria Borang 3 Kewangan</div>
                    </div>
                </div>
            </div>

            {{-- Table of Step 2 Qualified Vendors --}}
            <div class="table-responsive mb-4 rounded-3 shadow-sm border bg-white">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead style="background-color: #1e293b;">
                        <tr>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 6%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 12%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD PETENDER</th>
                            <th class="py-3 px-3 text-start fw-bold text-white text-uppercase" style="width: 28%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER & CIDB</th>
                            <th class="py-3 px-3 text-end fw-bold text-white text-uppercase" style="width: 14%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">MODAL PUSINGAN (RM)</th>
                            <th class="py-3 px-3 text-end fw-bold text-white text-uppercase" style="width: 14%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">PURATA PENYATA BANK (RM)</th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 13%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KEPUTUSAN</th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 13%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="step2VendorTableBody">
                        {{-- Rendered dynamically by window.type2State.renderStep2() --}}
                    </tbody>
                </table>
            </div>

            {{-- Footer Action --}}
            <div class="d-flex justify-content-end align-items-center gap-2 pt-2 border-top">
                <button type="button" class="btn btn-seterusnya d-inline-flex align-items-center gap-2" onclick="bootstrap.Tab.getInstance(document.querySelector('a[href=\'#rumusan-2\']'))?.show() || new bootstrap.Tab(document.querySelector('a[href=\'#rumusan-2\']')).show()">
                    <span>Lihat Rumusan Kemampuan Kewangan</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- SUB-TAB 2: RUMUSAN LANGKAH 2               --}}
        {{-- ========================================== --}}
        <div class="tab-pane fade" id="rumusan-2" role="tabpanel">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger-subtle p-2 rounded-2 me-3 text-danger">
                    <i class="bi bi-clipboard-data fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Rumusan Penilaian Kemampuan Kewangan</h5>
                    <p class="text-secondary small mb-0">Rumusan keputusan kemampuan kewangan dan kelayakan petender ke Penilaian Kerja (Langkah 3).</p>
                </div>
            </div>

            {{-- SECTION 1: Pembekal Memuaskan --}}
            <div class="mb-2 mt-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Memuaskan (Layak ke Langkah 3 - Penilaian Kerja)
                </h6>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill" id="totalStep2MemuaskanBadge">
                    0 Petender Memuaskan
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-responsive rounded-3 border bg-white shadow-sm">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead style="background-color: #1e293b;">
                                <tr>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 7%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 28%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PEMBEKAL</th>
                                    <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">MODAL PUSINGAN (RM)</th>
                                    <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">PURATA BANK (RM)</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">CATATAN PENILAI</th>
                                </tr>
                            </thead>
                            <tbody id="tableStep2MemuaskanBody">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Confirmation Box --}}
            <div class="card bg-light border-0 shadow-none mb-4 rounded-3 border-start border-4 border-success">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check me-2 text-success"></i>Pengesahan Penilaian Kemampuan Kewangan</h6>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep2" name="confirm_layak_step2">
                        <label class="form-check-label small fw-semibold text-dark" for="confirmLayakStep2">
                            Saya mengesahkan bahawa kedudukan kewangan dan kemampuan modal petender di atas adalah <span class="text-success fw-bold">Memuaskan</span> dan layak dipertimbangkan untuk Penilaian Kerja (Langkah 3).
                        </label>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Pembekal Tidak Memuaskan --}}
            <div class="mb-2 mt-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Senarai Pembekal Tidak Memuaskan (Tidak Layak)
                </h6>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill" id="totalStep2TidakMemuaskanBadge">
                    0 Petender Tidak Memuaskan
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-responsive rounded-3 border bg-white shadow-sm">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead style="background-color: #1e293b;">
                                <tr>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 7%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 28%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PEMBEKAL</th>
                                    <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">MODAL PUSINGAN (RM)</th>
                                    <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">PURATA BANK (RM)</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">SEBAB / CATATAN KEGAGALAN</th>
                                </tr>
                            </thead>
                            <tbody id="tableStep2TidakMemuaskanBody">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Action Bar --}}
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <button type="button" class="btn btn-sebelumnya d-inline-flex align-items-center gap-1" id="btnPrevStep2">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Langkah 1 (Dokumentasi)</span>
                </button>
                <button type="button" class="btn btn-seterusnya d-inline-flex align-items-center gap-2" id="btnNextStep2">
                    <span>Seterusnya: Penilaian Kerja (Langkah 3)</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

    </div>
</div>
