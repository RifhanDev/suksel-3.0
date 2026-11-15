{{-- MODAL: PENILAIAN KERJA & TEKNIKAL (LANGKAH 3) --}}
{{-- Mengandungi 3 Seksyen: Kerja Semasa, Pengalaman Kerja, dan Senarai Kakitangan Teknikal --}}
<style>
    #modalPenilaianKerjaVendor .mk-work-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    #modalPenilaianKerjaVendor .mk-work-tabs .nav-link {
        min-width: 180px;
        color: #475569;
        font-weight: 600;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    #modalPenilaianKerjaVendor .mk-work-tabs .nav-link.active {
        background: #fff;
        color: #b42318;
        border-color: #fecaca;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
    }
    #modalPenilaianKerjaVendor .mk-work-section-title {
        padding-bottom: .85rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    #modalPenilaianKerjaVendor .mk-work-table thead th {
        color: #475569;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    #modalPenilaianKerjaVendor .mk-work-table tbody td {
        padding-top: .8rem;
        padding-bottom: .8rem;
    }
    @media (max-width: 575.98px) {
        #modalPenilaianKerjaVendor .mk-work-tabs .nav-link { min-width: 155px; }
    }
</style>

<div class="modal fade" id="modalPenilaianKerjaVendor" tabindex="-1" aria-labelledby="modalLabelPenilaianKerja" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Header --}}
            <div class="modal-header px-3 px-md-4 py-3 border-0 bg-white">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 44px; height: 44px; background: #fee2e2; color: #dc2626;">
                        <i class="bi bi-tools fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill" style="font-size: 0.68rem;">LANGKAH 3</span>
                            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill" style="font-size: 0.68rem;">KEUPAYAAN & TEKNIKAL</span>
                        </div>
                        <h5 id="modalLabelPenilaianKerja" class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.05rem;">Penilaian Kerja Semasa, Pengalaman & Kakitangan Petender</h5>
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
                                <span id="modalKerjaVendorKod" class="badge bg-dark font-monospace px-2 py-1 rounded-2">1/5</span>
                                <span class="text-uppercase text-muted fw-bold" style="font-size: .65rem; letter-spacing: .06em;">Maklumat Petender</span>
                            </div>
                            <h6 id="modalKerjaVendorNama" class="fw-bold text-dark mb-1 fs-6">NAMA PETENDER BERHAD</h6>
                            <span id="modalKerjaVendorInfo" class="text-muted small">SSM: - | CIDB Gred: -</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                            <i class="bi bi-person-badge me-1"></i>Penilaian Beban Kerja & Kapasiti
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 rounded-3 border border-primary-subtle bg-primary-subtle bg-opacity-25 px-3 py-3 mb-3">
                    <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                    <div>
                        <div class="fw-bold text-dark small mb-1">Semakan merangkumi ketiga-tiga bahagian</div>
                        <p class="text-secondary small mb-0">Semak kerja semasa, pengalaman terdahulu dan kakitangan teknikal. Keputusan serta catatan di bawah meliputi keseluruhan penilaian petender ini.</p>
                    </div>
                </div>

                {{-- 3 Sections Navigation Tabs --}}
                <ul class="nav nav-pills nav-fill gap-1 p-2 bg-white border rounded-3 shadow-sm mb-3 mk-work-tabs" role="tablist" aria-label="Bahagian penilaian kerja">
                    <li class="nav-item">
                        <button type="button" class="nav-link active rounded-3" id="tabModalKerjaSemasaButton" data-bs-toggle="tab" data-bs-target="#tabModalKerjaSemasa" role="tab" aria-controls="tabModalKerjaSemasa" aria-selected="true">
                            <i class="bi bi-hourglass-split me-1"></i><span>1. Kerja Semasa</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-3" id="tabModalPengalamanKerjaButton" data-bs-toggle="tab" data-bs-target="#tabModalPengalamanKerja" role="tab" aria-controls="tabModalPengalamanKerja" aria-selected="false">
                            <i class="bi bi-briefcase me-1"></i><span>2. Pengalaman Kerja</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-3" id="tabModalKakitanganTeknikalButton" data-bs-toggle="tab" data-bs-target="#tabModalKakitanganTeknikal" role="tab" aria-controls="tabModalKakitanganTeknikal" aria-selected="false">
                            <i class="bi bi-people me-1"></i><span>3. Kakitangan Teknikal</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content border rounded-3 p-3 p-md-4 bg-white shadow-sm mt-3">

                    {{-- SEKSYEN 1: KERJA SEMASA / KERJA DALAM TANGAN --}}
                    <div class="tab-pane fade show active" id="tabModalKerjaSemasa" role="tabpanel">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mk-work-section-title">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-building-gear fs-5"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Senarai Kerja Dalam Tangan Semasa</h6>
                                    <p class="text-muted extra-small mb-0">Projek-projek yang sedang dilaksanakan dan baki komitmen kerja.</p>
                                </div>
                            </div>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill font-monospace" id="mkBakiKerjaTotalBadge">
                                Jumlah Baki Kerja: <strong>RM 0.00</strong>
                            </span>
                        </div>

                        <div class="table-responsive rounded-3 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0 mk-work-table" style="font-size: 0.82rem;">
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

                        <div class="p-3 rounded-3 border bg-light d-flex flex-wrap align-items-center justify-content-between gap-3" id="mkKerjaSemasaDocRow">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                                <div>
                                    <span class="small fw-semibold text-dark d-block">Surat Tawaran & Pesanan Kerja Projek Semasa</span>
                                    <span class="text-muted extra-small" id="mkKerjaSemasaDocLabel">Dokumen Pembuktian Kerja Dalam Tangan</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-3" id="btnPreviewKerjaSemasa" data-title="Dokumen Kerja Semasa">
                                <i class="bi bi-eye me-1"></i>Lihat Dokumen
                            </button>
                        </div>
                    </div>

                    {{-- SEKSYEN 2: PENGALAMAN KERJA TERDAHULU --}}
                    <div class="tab-pane fade" id="tabModalPengalamanKerja" role="tabpanel">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mk-work-section-title">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-award fs-5"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Senarai Pengalaman Kerja Yang Telah Disiapkan</h6>
                                    <p class="text-muted extra-small mb-0">Rekod projek terdahulu yang berjaya disiapkan dengan sempurna dalam tempoh 3 tahun.</p>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill font-monospace" id="mkProjekSiapCount">
                                0 Projek Berjaya Disiapkan
                            </span>
                        </div>

                        <div class="table-responsive rounded-3 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0 mk-work-table" style="font-size: 0.82rem;">
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
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mk-work-section-title">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger flex-shrink-0" style="width: 40px; height: 40px;"><i class="bi bi-person-vcard fs-5"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Senarai Kakitangan Teknikal Petender</h6>
                                    <p class="text-muted extra-small mb-0">Pegawai teknikal berkelayakan, sijil kecekapan dan pengalaman kerja dalam bidang pembinaan.</p>
                                </div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill font-monospace" id="mkKakitanganCount">
                                0 Kakitangan Teknikal
                            </span>
                        </div>

                        <div class="table-responsive rounded-3 border mb-3">
                            <table class="table table-sm table-hover align-middle mb-0 mk-work-table" style="font-size: 0.82rem;">
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
                        <div class="p-3 rounded-3 border bg-light" id="mkKakitanganGeneralDocsSection">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
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

            </div>

            <div class="modal-footer bg-white border-top px-3 px-md-4 py-3">
                <div class="w-100 rounded-3 border bg-light p-3 mb-2">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-clipboard-check text-danger fs-5"></i>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="fw-bold text-dark small">Keputusan Keseluruhan - Semua 3 Bahagian <span class="text-danger">*</span></div>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Satu keputusan untuk petender ini</span>
                            </div>
                            <div class="text-secondary" style="font-size: .72rem;">Tetapkan keputusan selepas menyemak semua tab; catatan ini meliputi keseluruhan penilaian kerja.</div>
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-4">
                            <label for="modalKerjaDecisionSelect" class="form-label fw-semibold text-secondary small mb-1">Keputusan</label>
                            <select id="modalKerjaDecisionSelect" class="form-select fw-semibold">
                                <option value="">-- Sila Pilih --</option>
                                <option value="memuaskan">Memuaskan</option>
                                <option value="tidak_memuaskan">Tidak Memuaskan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="modalKerjaCatatanInput" class="form-label fw-semibold text-secondary small mb-1">Catatan / Justifikasi Keseluruhan</label>
                            <input type="text" id="modalKerjaCatatanInput" class="form-control" placeholder="Nyatakan ulasan keupayaan kerja petender...">
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 w-100">
                    <button type="button" class="btn btn-outline-secondary px-3 fw-semibold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4 fw-bold shadow-sm" id="btnSimpanPenilaianKerja">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Penilaian Kerja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
