{{-- LANGKAH 1: PEMATUHAN DOKUMENTASI (SEBUT HARGA KERJA) --}}
<div class="tab-pane fade show active" id="pematuhan" role="tabpanel" aria-labelledby="pematuhan-tab">

    {{-- Sub-tabs (Kewangan & Rumusan) --}}
    <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-1" role="tab" aria-selected="true">
                <i class="bi bi-file-earmark-check me-1"></i>Kewangan
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link" data-bs-toggle="tab" href="#rumusan-1" role="tab" aria-selected="false">
                <i class="bi bi-clipboard-data me-1"></i>Rumusan
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4">

        {{-- ========================================== --}}
        {{-- SUB-TAB 1: KEWANGAN (SENARAI DOKUMEN)      --}}
        {{-- ========================================== --}}
        <div class="tab-pane fade show active" id="kewangan-1" role="tabpanel">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger-subtle p-2 rounded-2 me-3 text-danger">
                    <i class="bi bi-folder-check fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Pematuhan Dokumentasi Mandatori (Kerja)</h5>
                    <p class="text-secondary small mb-0">Semakan kelengkapan dokumen asas, penyata kewangan, pendaftaran CIDB, dan maklumat teknikal kerja petender.</p>
                </div>
            </div>

            {{-- Info Alert --}}
            <div class="rounded-3 px-3 py-2.5 d-inline-flex align-items-center gap-2 mb-3 w-100" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.825rem; color:#1e40af;">
                <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0"></i>
                <div>
                    <span class="fw-bold">Panduan Penilai:</span>
                    Klik butang <strong>Menilai</strong> pada setiap dokumen semakan untuk mengesahkan pematuhan bagi setiap petender. Petender yang tidak mematuhi syarat asas akan disingkirkan daripada penilaian seterusnya.
                </div>
            </div>

            {{-- Table of Checklist Items --}}
            <div class="table-responsive mb-4 rounded-3 shadow-sm border bg-white">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead style="background-color: #1e293b;">
                        <tr>
                            <th class="py-3 px-3 text-start fw-bold text-white text-uppercase" style="width: 38%; font-size: 0.75rem; letter-spacing: 0.5px; background-color: #1e293b !important;">
                                <i class="bi bi-file-earmark-text text-danger me-1"></i>Tajuk / Dokumen Semakan
                            </th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 20%; font-size: 0.75rem; letter-spacing: 0.5px; background-color: #1e293b !important;">
                                <i class="bi bi-gear text-danger me-1"></i>Mekanisma
                            </th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 22%; font-size: 0.75rem; letter-spacing: 0.5px; background-color: #1e293b !important;">
                                <i class="bi bi-shield-check text-danger me-1"></i>Status Penilaian
                            </th>
                            <th class="py-3 px-3 text-center fw-bold text-white text-uppercase" style="width: 20%; font-size: 0.75rem; letter-spacing: 0.5px; background-color: #1e293b !important;">
                                <i class="bi bi-sliders text-danger me-1"></i>Tindakan
                            </th>
                        </tr>
                    </thead>
                    <tbody id="step1ChecklistTableBody">
                        {{-- Rendered dynamically by window.type2State.renderStep1() --}}
                    </tbody>
                </table>
            </div>

            {{-- Breakdown Status Per Petender Card --}}
            <div class="card border border-light-subtle rounded-3 shadow-none bg-light p-3 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-people-fill text-danger fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">Status Pematuhan Mengikut Petender (Ringkasan Semasa)</h6>
                    </div>
                    <span class="badge bg-secondary font-monospace" id="step1SummaryCountBadge">5 Petender</span>
                </div>
                <div class="row g-2" id="step1VendorStatusGrid">
                    {{-- Rendered by JS --}}
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="d-flex justify-content-end align-items-center gap-2 pt-2 border-top">
                <button type="button" class="btn btn-seterusnya d-inline-flex align-items-center gap-2" onclick="bootstrap.Tab.getInstance(document.querySelector('a[href=\'#rumusan-1\']'))?.show() || new bootstrap.Tab(document.querySelector('a[href=\'#rumusan-1\']')).show()">
                    <span>Lihat Rumusan Dokumentasi</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- SUB-TAB 2: RUMUSAN LANGKAH 1               --}}
        {{-- ========================================== --}}
        <div class="tab-pane fade" id="rumusan-1" role="tabpanel">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger-subtle p-2 rounded-2 me-3 text-danger">
                    <i class="bi bi-clipboard-data fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Rumusan Pematuhan Dokumentasi</h5>
                    <p class="text-secondary small mb-0">Rumusan kelayakan petender ke peringkat Penilaian Kemampuan Kewangan (Langkah 2).</p>
                </div>
            </div>

            {{-- SECTION 1: Pembekal Melepasi --}}
            <div class="mb-2 mt-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Melepasi Pematuhan Dokumentasi (Layak ke Langkah 2)
                </h6>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill" id="totalStep1MelepasiBadge">
                    0 Petender Melepasi
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-responsive rounded-3 border bg-white shadow-sm">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead style="background-color: #1e293b;">
                                <tr>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 8%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD PETENDER</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 35%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER</th>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 18%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">STATUS DOKUMEN</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">ULASAN / CATATAN</th>
                                </tr>
                            </thead>
                            <tbody id="tableStep1MelepasiBody">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Confirmation Box --}}
            <div class="card bg-light border-0 shadow-none mb-4 rounded-3 border-start border-4 border-success">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check me-2 text-success"></i>Pengesahan Semakan Pematuhan Dokumentasi</h6>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep1" name="confirm_layak_step1">
                        <label class="form-check-label small fw-semibold text-dark" for="confirmLayakStep1">
                            Saya mengesahkan bahawa semua dokumen mandatori petender di atas telah disemak dengan teliti dan petender yang bertanda <span class="text-success fw-bold">Layak</span> dibenarkan mara ke Penilaian Kemampuan Kewangan (Langkah 2).
                        </label>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Pembekal Tidak Melepasi --}}
            <div class="mb-2 mt-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Senarai Pembekal Tidak Melepasi Pematuhan Dokumentasi (Disingkirkan)
                </h6>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill" id="totalStep1TidakMelepasiBadge">
                    0 Petender Disingkirkan
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-responsive rounded-3 border bg-white shadow-sm">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead style="background-color: #1e293b;">
                                <tr>
                                    <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 8%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD PETENDER</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 32%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 25%; font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">DOKUMEN TIDAK MEMATUHI</th>
                                    <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px; background-color: #1e293b !important;">SEBAB / CATATAN</th>
                                </tr>
                            </thead>
                            <tbody id="tableStep1TidakMelepasiBody">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Action Bar --}}
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <button type="button" class="btn btn-sebelumnya d-inline-flex align-items-center gap-1" onclick="bootstrap.Tab.getInstance(document.querySelector('a[href=\'#kewangan-1\']'))?.show() || new bootstrap.Tab(document.querySelector('a[href=\'#kewangan-1\']')).show()">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Semakan Kewangan</span>
                </button>
                <button type="button" class="btn btn-seterusnya d-inline-flex align-items-center gap-2" id="btnNextStep1">
                    <span>Seterusnya: Penilaian Kemampuan Kewangan (Langkah 2)</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

    </div>
</div>
