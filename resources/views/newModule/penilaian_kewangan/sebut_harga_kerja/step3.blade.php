{{-- LANGKAH 3: PENILAIAN KERJA & TEKNIKAL (SEBUT HARGA KERJA) --}}
<div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">

    {{-- Sub-tabs (Kewangan & Rumusan) --}}
    <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-3" role="tab" aria-selected="true">
                <i class="bi bi-tools me-1"></i>Kewangan
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link" data-bs-toggle="tab" href="#rumusan-3" role="tab" aria-selected="false">
                <i class="bi bi-clipboard-data me-1"></i>Rumusan
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4">

        {{-- ========================================== --}}
        {{-- SUB-TAB 1: KEWANGAN (PENILAIAN KERJA)      --}}
        {{-- ========================================== --}}
        <div class="tab-pane fade show active" id="kewangan-3" role="tabpanel">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger-subtle p-2 rounded-2 me-3 text-danger">
                    <i class="bi bi-cone-striped fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Penilaian Kerja Semasa, Pengalaman & Kakitangan</h5>
                    <p class="text-secondary small mb-0">Semakan komitmen kerja dalam tangan semasa, rekod pengalaman projek terdahulu, dan keupayaan tenaga kerja teknikal petender.</p>
                </div>
            </div>

            {{-- Info Alert --}}
            <div class="rounded-3 px-3 py-2.5 d-inline-flex align-items-center gap-2 mb-3 w-100" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.825rem; color:#1e40af;">
                <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0"></i>
                <div>
                    <span class="fw-bold">Maklumat Saringan:</span>
                    Hanya petender yang telah <strong>melepasi Penilaian Kemampuan Kewangan (Langkah 2)</strong> dipaparkan di bawah. Klik butang <strong>Nilai Kerja</strong> untuk menyemak: <em>1. Kerja Semasa / Dalam Tangan</em>, <em>2. Pengalaman Kerja Terdahulu</em>, dan <em>3. Senarai Kakitangan Teknikal</em>.
                </div>
            </div>

            {{-- Technical Evaluation Criteria Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="p-3 rounded-3 border bg-white shadow-sm h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-danger text-white rounded-pill px-2">1</span>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Kerja Semasa Dalam Tangan</h6>
                        </div>
                        <p class="text-muted extra-small mb-0">Menilai baki nilai kontrak sedia ada agar petender tidak mengalami bebanan melampaui keupayaan pelaksanaan fizikal.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="p-3 rounded-3 border bg-white shadow-sm h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-danger text-white rounded-pill px-2">2</span>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Pengalaman Kerja Terdahulu</h6>
                        </div>
                        <p class="text-muted extra-small mb-0">Memastikan petender mempunyai rekod kejayaan melaksanakan projek binaan/kejuruteraan serupa dalam tempoh 5 tahun.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="p-3 rounded-3 border bg-white shadow-sm h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-danger text-white rounded-pill px-2">3</span>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Kakitangan Teknikal Kompeten</h6>
                        </div>
                        <p class="text-muted extra-small mb-0">Kecukupan jurutera, penyelia tapak, dan pegawai keselamatan (SHO/SSS) berkelayakan dengan caruman KWSP aktif.</p>
                    </div>
                </div>
            </div>

            {{-- Table of Step 3 Qualified Vendors --}}
            <div class="table-responsive mb-4 rounded-3 shadow-sm border bg-white">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead style="background-color: #1e293b;">
                        <tr>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 6%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 12%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD PETENDER</th>
                            <th class="py-3 px-3 text-start fw-bold text-white text-uppercase" style="width: 28%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER & CIDB</th>
                            <th class="py-3 px-3 text-end fw-bold text-white text-uppercase" style="width: 14%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BAKI KERJA SEMASA (RM)</th>
                            <th class="py-3 px-3 text-end fw-bold text-white text-uppercase" style="width: 14%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">PROJEK TERBESAR (RM)</th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 13%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KEPUTUSAN</th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 13%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="step3VendorTableBody">
                        {{-- Rendered dynamically by window.type2State.renderStep3() --}}
                    </tbody>
                </table>
            </div>

            {{-- Footer Action --}}
            <div class="d-flex justify-content-end align-items-center gap-2 pt-2 border-top">
                <button type="button" class="btn btn-seterusnya d-inline-flex align-items-center gap-2" onclick="bootstrap.Tab.getInstance(document.querySelector('a[href=\'#rumusan-3\']'))?.show() || new bootstrap.Tab(document.querySelector('a[href=\'#rumusan-3\']')).show()">
                    <span>Lihat Rumusan Penilaian Kerja</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- SUB-TAB 2: RUMUSAN LANGKAH 3               --}}
        {{-- ========================================== --}}
        <div class="tab-pane fade" id="rumusan-3" role="tabpanel">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger-subtle p-2 rounded-2 me-3 text-danger">
                    <i class="bi bi-clipboard-data fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Rumusan Penilaian Kerja & Prestasi</h5>
                    <p class="text-secondary small mb-0">Rumusan keputusan keupayaan kerja dan kelayakan petender ke Penyediaan Laporan Akhir (Langkah 4).</p>
                </div>
            </div>

            {{-- SECTION 1: Pembekal Memuaskan --}}
            <div class="mb-2 mt-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Memuaskan (Layak ke Langkah 4 - Penyediaan Laporan)
                </h6>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill" id="totalStep3MemuaskanBadge">
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
                                    <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BAKI KERJA (RM)</th>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KAKITANGAN</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">CATATAN PENILAI</th>
                                </tr>
                            </thead>
                            <tbody id="tableStep3MemuaskanBody">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Confirmation Box --}}
            <div class="card bg-light border-0 shadow-none mb-4 rounded-3 border-start border-4 border-success">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check me-2 text-success"></i>Pengesahan Penilaian Kerja & Prestasi</h6>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep3" name="confirm_layak_step3">
                        <label class="form-check-label small fw-semibold text-dark" for="confirmLayakStep3">
                            Saya mengesahkan bahawa keupayaan kerja semasa, pengalaman, dan tenaga teknikal petender di atas adalah <span class="text-success fw-bold">Memuaskan</span> dan layak dipertimbangkan dalam Penyediaan Laporan Akhir (Langkah 4).
                        </label>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Pembekal Tidak Memuaskan --}}
            <div class="mb-2 mt-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Senarai Pembekal Tidak Memuaskan (Tidak Layak)
                </h6>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill" id="totalStep3TidakMemuaskanBadge">
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
                                    <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BAKI KERJA (RM)</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">SEBAB / CATATAN KEGAGALAN</th>
                                </tr>
                            </thead>
                            <tbody id="tableStep3TidakMemuaskanBody">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Action Bar --}}
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <button type="button" class="btn btn-sebelumnya d-inline-flex align-items-center gap-1" id="btnPrevStep3">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Langkah 2 (Kemampuan Kewangan)</span>
                </button>
                <button type="button" class="btn btn-seterusnya d-inline-flex align-items-center gap-2" id="btnNextStep3">
                    <span>Seterusnya: Penyediaan Laporan (Langkah 4)</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

    </div>
</div>
