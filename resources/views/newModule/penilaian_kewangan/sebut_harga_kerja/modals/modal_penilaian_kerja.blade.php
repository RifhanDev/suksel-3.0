{{-- MODAL: PENILAIAN KERJA & TEKNIKAL (LANGKAH 3) --}}
{{-- Mengandungi 3 Seksyen: Kerja Semasa, Pengalaman Kerja, dan Senarai Kakitangan Teknikal --}}
<div class="modal fade" id="modalPenilaianKerjaVendor" tabindex="-1" aria-labelledby="modalLabelPenilaianKerja" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            {{-- Header --}}
            <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 44px; height: 44px; background: #fee2e2; color: #dc2626;">
                        <i class="bi bi-tools fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Langkah 3: Penilaian Kerja</span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Keupayaan & Teknikal</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.05rem;">Penilaian Kerja Semasa, Pengalaman & Kakitangan Petender</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Vendor Identity Bar --}}
                <div class="p-3 mb-4 rounded-3 border bg-white shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-left: 4px solid #dc2626 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <span id="modalKerjaVendorKod" class="badge bg-dark font-monospace px-3 py-2 fs-6 rounded-2">1/5</span>
                        <div>
                            <h6 id="modalKerjaVendorNama" class="fw-bold text-dark mb-0 fs-6">NAMA PETENDER BERHAD</h6>
                            <span id="modalKerjaVendorInfo" class="text-muted extra-small">SSM: 201801034567 | CIDB Gred G3 (B04, CE21)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-2">
                            <i class="bi bi-person-badge text-danger me-1"></i>Penilaian Beban Kerja & Kapasiti
                        </span>
                    </div>
                </div>

                {{-- 3 Sections Navigation Tabs --}}
                <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabModalKerjaSemasa" role="tab">
                            <i class="bi bi-hourglass-split me-1"></i>1. Kerja Semasa / Dalam Tangan
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabModalPengalamanKerja" role="tab">
                            <i class="bi bi-briefcase me-1"></i>2. Pengalaman Kerja Terdahulu
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabModalKakitanganTeknikal" role="tab">
                            <i class="bi bi-people me-1"></i>3. Senarai Kakitangan Teknikal
                        </button>
                    </li>
                </ul>

                <div class="tab-content border rounded-3 p-3.5 bg-white shadow-sm mt-3">

                    {{-- SEKSYEN 1: KERJA SEMASA / KERJA DALAM TANGAN --}}
                    <div class="tab-pane fade show active" id="tabModalKerjaSemasa" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building-gear text-danger fs-5 me-2"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Senarai Kerja Dalam Tangan Semasa</h6>
                                    <p class="text-muted extra-small mb-0">Projek-projek yang sedang dilaksanakan dan baki komitmen kerja.</p>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace" id="mkBakiKerjaTotalBadge">
                                Jumlah Baki Kerja: <strong>RM 180,000.00</strong>
                            </span>
                        </div>

                        <div class="table-responsive rounded-2 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;" class="text-center">Bil</th>
                                        <th style="width: 35%;">Tajuk Projek / Kontrak Semasa</th>
                                        <th style="width: 20%;">Agensi / Majikan</th>
                                        <th class="text-end" style="width: 15%;">Nilai Kontrak (RM)</th>
                                        <th class="text-center" style="width: 10%;">Kemajuan (%)</th>
                                        <th class="text-end" style="width: 15%;">Baki Nilai Kerja (RM)</th>
                                    </tr>
                                </thead>
                                <tbody id="mkKerjaSemasaBody">
                                    {{-- Rendered dynamically by JS --}}
                                </tbody>
                            </table>
                        </div>

                        <div class="p-2.5 rounded-2 border bg-light d-flex align-items-center justify-content-between" id="mkKerjaSemasaDocRow">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                                <div>
                                    <span class="small fw-semibold text-dark d-block">Surat Tawaran & Pesanan Kerja Projek Semasa</span>
                                    <span class="text-muted extra-small" id="mkKerjaSemasaDocLabel">Dokumen Pembuktian Kerja Dalam Tangan</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-danger px-2.5 py-1 rounded-2" id="btnPreviewKerjaSemasa" data-title="Dokumen Kerja Semasa">
                                <i class="bi bi-eye me-1"></i>Prebiu Dokumen
                            </button>
                        </div>
                    </div>

                    {{-- SEKSYEN 2: PENGALAMAN KERJA TERDAHULU --}}
                    <div class="tab-pane fade" id="tabModalPengalamanKerja" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-award text-danger fs-5 me-2"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Senarai Pengalaman Kerja Yang Telah Disiapkan</h6>
                                    <p class="text-muted extra-small mb-0">Rekod projek terdahulu yang berjaya disiapkan dengan sempurna dalam tempoh 3 tahun.</p>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success border px-2.5 py-1.5 rounded-2 font-monospace" id="mkProjekSiapCount">
                                0 Projek Berjaya Disiapkan
                            </span>
                        </div>

                        <div class="table-responsive rounded-2 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;" class="text-center">Bil</th>
                                        <th style="width: 32%;">Nama Projek Disiapkan</th>
                                        <th style="width: 23%;">Pelanggan / Agensi</th>
                                        <th class="text-end" style="width: 15%;">Nilai Projek (RM)</th>
                                        <th class="text-center" style="width: 12%;">Tarikh / Tahun</th>
                                        <th class="text-center" style="width: 13%;">Sijil CPC / Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody id="mkPengalamanBody">
                                    {{-- Rendered dynamically by JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SEKSYEN 3: SENARAI KAKITANGAN TEKNIKAL --}}
                    <div class="tab-pane fade" id="tabModalKakitanganTeknikal" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-vcard text-danger fs-5 me-2"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Senarai Kakitangan Teknikal Petender</h6>
                                    <p class="text-muted extra-small mb-0">Pegawai teknikal berkelayakan, sijil kecekapan dan pengalaman kerja dalam bidang pembinaan.</p>
                                </div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border px-2.5 py-1.5 rounded-2 font-monospace" id="mkKakitanganCount">
                                0 Kakitangan Teknikal
                            </span>
                        </div>

                        <div class="table-responsive rounded-2 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;" class="text-center">Bil</th>
                                        <th style="width: 25%;">Nama Pegawai Teknikal</th>
                                        <th class="text-center" style="width: 12%;">Kategori</th>
                                        <th style="width: 18%;">Kelayakan Akademik</th>
                                        <th style="width: 18%;">Sijil Profesional</th>
                                        <th class="text-center" style="width: 10%;">Pengalaman</th>
                                        <th class="text-center" style="width: 12%;">Sijil / Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody id="mkKakitanganBody">
                                    {{-- Rendered dynamically by JS --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Dokumen Sokongan Kakitangan (Am / KWSP / SOCSO / Syarikat) --}}
                        <div class="p-3 rounded-2 border bg-light" id="mkKakitanganGeneralDocsSection">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-medical text-danger fs-5"></i>
                                    <div>
                                        <span class="small fw-semibold text-dark d-block">Dokumen Sokongan Kakitangan Am</span>
                                        <span class="text-muted extra-small">Penyata KWSP, caruman SOCSO, atau resume am syarikat</span>
                                    </div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary" id="mkKakitanganGeneralDocsBadge">0 Dokumen</span>
                            </div>
                            <div id="mkKakitanganGeneralDocsContainer" class="d-flex flex-wrap gap-2 pt-1">
                                {{-- Rendered dynamically by JS --}}
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Overall Decision Section inside Modal --}}
                <div class="card bg-light border p-3 mt-4 rounded-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">
                                Keputusan Penilaian Kerja: <span class="text-danger">*</span>
                            </label>
                            <select id="modalKerjaDecisionSelect" class="form-select form-select-sm fw-bold">
                                <option value="" disabled>-- Sila Pilih --</option>
                                <option value="memuaskan" selected>Memuaskan</option>
                                <option value="tidak_memuaskan">Tidak Memuaskan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold text-dark small mb-1">
                                Catatan / Justifikasi Penilaian Kerja:
                            </label>
                            <input type="text" id="modalKerjaCatatanInput" class="form-control form-control-sm" placeholder="Nyatakan ulasan keupayaan kerja petender...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-sm btn-danger px-4 fw-bold shadow-sm" id="btnSimpanPenilaianKerja">
                    <i class="bi bi-check2-circle me-1"></i>Simpan Penilaian Kerja
                </button>
            </div>
        </div>
    </div>
</div>
