@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <style>
        /* Full grid borders — outer table + column separators */
        #dt_tmpltSpec {
            border: 1px solid #e2e8f0;
        }

        #dt_tmpltSpec th,
        #dt_tmpltSpec td {
            border-right: 1px solid #e2e8f0 !important;
        }

        #dt_tmpltSpec th:last-child,
        #dt_tmpltSpec td:last-child {
            border-right: none !important;
        }

        /* Alternating group background — applied to item row + all its spec rows together */
        #dt_tmpltSpec tbody .group-alt td {
            background-color: #f8fafc;
        }

        /* Merge col 1 visually between item and its spec rows (no row divider in that column) */
        #dt_tmpltSpec tbody .item-row td:first-child,
        #dt_tmpltSpec tbody .spec-row td:first-child {
            border-bottom: none !important;
        }

        /* ── Tree connector lines ─────────────────────────────────────────────────── */

        /* Item row that has at least one spec: horizontal branch → input + vertical line ↓ specs */
        #dt_tmpltSpec tbody .item-has-specs>td:first-child {
            position: relative;
        }

        /* Horizontal branch pointing right to the item input (─) */
        #dt_tmpltSpec tbody .item-has-specs>td:first-child::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            width: 7px;
            height: 1.5px;
            background: #cbd5e1;
            transform: translateY(-50%);
        }

        /* Vertical line going down to first spec row (↓) */
        #dt_tmpltSpec tbody .item-has-specs>td:first-child::after {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            bottom: 0;
            width: 1.5px;
            background: #cbd5e1;
        }

        /* Spec row: full-height vertical line + horizontal branch (├ shape) */
        #dt_tmpltSpec tbody .spec-row>td:first-child {
            position: relative;
        }

        #dt_tmpltSpec tbody .spec-row>td:first-child::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 1.5px;
            background: #cbd5e1;
        }

        #dt_tmpltSpec tbody .spec-row>td:first-child::after {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            width: 18px;
            height: 1.5px;
            background: #cbd5e1;
            transform: translateY(-50%);
        }

        /* Last spec in a group: vertical line stops at midpoint (└ shape) */
        #dt_tmpltSpec tbody .spec-last>td:first-child::before {
            bottom: 50%;
        }
    </style>
@endsection

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Borang Spesifikasi</h3>
            <p class="text-muted small m-0">Kunci masuk templat dan spesifikasi teknikal bagi tender / sebutharga.</p>
        </div>
    </div>

    <!-- TENDER INFO CARD -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">

            <!-- Tajuk Tender -->
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    MEMBEKAL RANGSUM PUKAL (AIR MINERAL) UNTUK BANGUNAN KERAJAAN
                    <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">(Bekalan Perkhidmatan)</span>
                </h5>
            </div>

            <!-- Metadata -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">SUKSEL/PERT/2026/001</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">100-007</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle"
                            style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- ===================== SECTION 1: TEMPLAT SPESIFIKASI ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <h3 class="content-card-title mb-0" style="font-size: 1rem;">Templat Spesifikasi</h3>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Guidelines -->
            <div class="guideline-card mb-4">
                <div class="guideline-card-header">
                    <div class="guideline-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <span class="guideline-card-title">Panduan Pengisian</span>
                </div>
                <div class="guideline-list">
                    <div class="guideline-item">
                        <span class="guideline-num">1</span>
                        <span class="guideline-item-text">Sila klik ikon <span class="highlight">Pensil</span> untuk
                            penetapan skor bagi setiap item spesifikasi.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">2</span>
                        <span class="guideline-item-text">Sila klik pautan <span class="highlight">UOM</span> untuk
                            carian nama unit ukuran dan kunci masuk di ruang medan yang disediakan.</span>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="row g-3">

                <!-- Tajuk Dokumen -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Tajuk Dokumen <span class="text-danger">*</span></label>
                    <textarea name="tajuk_dokumen" class="form-control form-control-sm" rows="3" placeholder="Masukkan tajuk dokumen spesifikasi..."></textarea>
                </div>

                <!-- Jenis Item -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Jenis Item</label>
                    <input type="text" class="form-control form-control-sm" value="Bekalan Perkhidmatan" disabled>
                </div>

                <!-- Jenis Spesifikasi -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Jenis Spesifikasi</label>
                    <input type="text" class="form-control form-control-sm" value="Spesifikasi Teknikal" disabled>
                </div>

                <!-- Jenis Barang -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Jenis Barang <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenisBarang" id="jenisBarang1" checked>
                            <label class="form-check-label" for="jenisBarang1">Tempatan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenisBarang" id="jenisBarang2">
                            <label class="form-check-label" for="jenisBarang2">Import</label>
                        </div>
                    </div>
                </div>

                <!-- Wajaran Spesifikasi -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Wajaran Spesifikasi <span
                            class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wajaranSpesifikasi"
                                id="wajaranSpesifikasi1" checked>
                            <label class="form-check-label" for="wajaranSpesifikasi1">Mengikut Keutamaan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wajaranSpesifikasi"
                                id="wajaranSpesifikasi2">
                            <label class="form-check-label" for="wajaranSpesifikasi2">Secara Terus</label>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Status</label>
                    <span class="badge-status badge-status-warning">Draf</span>
                </div>

                <!-- Penghantaran Fizikal -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Penghantaran Fizikal <span
                            class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penghantaranFizikal"
                                id="penghantaranFizikal1" checked>
                            <label class="form-check-label" for="penghantaranFizikal1">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penghantaranFizikal"
                                id="penghantaranFizikal2">
                            <label class="form-check-label" for="penghantaranFizikal2">Tidak</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ===================== SECTION 2: ITEM SPESIFIKASI (table) ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <h3 class="content-card-title mb-0" style="font-size: 1rem;">Item Spesifikasi</h3>
            </div>
        </div>
        <div class="content-card-body p-3">

            <!-- Toolbar -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex gap-2">
                    <button type="button" id="btn-muat-turun-kosong"
                        class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Muat Turun Templat Kosong
                    </button>
                    <button type="button" id="btn-muat-turun-templat"
                        class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Muat Turun Templat
                    </button>
                    <label class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 mb-0"
                        style="cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Muat Naik Dokumen BQ/Spesifikasi
                        <input type="file" id="input-muat-naik" name="bq_spesifikasi_file" accept=".xlsx,.xls" hidden>
                    </label>
                </div>
                <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 add_btn_item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Item
                </button>
            </div>

            <div class="table-responsive">
                <table id="dt_tmpltSpec" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th>
                                Item
                                <div style="font-size: 0.68rem; font-weight: 600; text-transform: none; letter-spacing: 0; color: #94a3b8; margin-top: 2px;">
                                    Spesifikasi
                                </div>
                            </th>
                            <th class="text-center" style="width: 130px;">Kekerapan / Kuantiti</th>
                            <th class="text-center" style="width: 130px;">Unit Ukuran</th>
                            <th class="text-center" style="width: 200px;">Penetapan Skema</th>
                            <th class="text-center" style="width: 160px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="tbl-no-data">
                            <td colspan="5" class="text-center text-muted py-4 small">Tiada Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BOTTOM ACTION BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('senaraiTeknikal') }}"
            class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-primary">
                Simpan
            </button>
            <button type="button" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"></path></svg>
                Selesai
            </button>
        </div>
    </div>
@endsection

@push('modals')
    <!-- ===================== MODAL: Confirm Hapus ===================== -->
    <div class="modal fade" id="modalHapusConfirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m9-3v4m0 3v.01"/></svg>
                        Sahkan Penghapusan
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p id="modal-hapus-message" class="text-muted small mb-0"></p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-danger" id="btn-confirm-hapus">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: Penetapan Skor - Text ===================== -->
    <div class="modal fade" id="modalText" tabindex="-1" aria-labelledby="modalTextLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTextLabel">Penetapan Skor — Text</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Spesifikasi</label>
                            <textarea id="modalText-spesifikasi" class="form-control form-control-sm bg-light" rows="3" readonly></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Skema Maklumbalas</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="Text" readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Skor Pengguna</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="Pengguna"
                                readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Skema Maksima <span class="text-danger">*</span></label>
                            <input type="number" id="modalText-skema-maksima" name="text_skema_maksima" class="form-control form-control-sm"
                                min="0" placeholder="Masukkan nilai maksima...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btn-modalText-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: Penetapan Skor - Nombor ===================== -->
    <div class="modal fade" id="modalNumber" tabindex="-1" aria-labelledby="modalNumberLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalNumberLabel">Penetapan Skor — Nombor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold">Spesifikasi</label>
                            <textarea id="modalNumber-spesifikasi" class="form-control form-control-sm bg-light" rows="3" readonly></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Skema Maklumbalas</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="Nombor" readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Skema</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="Automatik"
                                readonly>
                        </div>

                        <!-- Skema Skor -->
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-semibold mb-0">Skema Skor</label>
                                <div class="d-flex gap-2">
                                    <button type="button" id="btn-nombor-tambah"
                                        class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Tambah
                                    </button>
                                    <button type="button" id="btn-nombor-hapus"
                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="13"
                                            viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M4 7h16M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-5 5l4 4m0-4l-4 4" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle mb-0" style="font-size:0.82rem;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" style="width:44px;">
                                                <input type="checkbox" id="nombor-check-all" name="nombor_check_all" class="form-check-input">
                                            </th>
                                            <th class="text-center">Dari</th>
                                            <th class="text-center">Hingga</th>
                                            <th class="text-center">Skema</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-nombor-skema-body">
                                        <tr id="tbl-nombor-no-data">
                                            <td colspan="4" class="text-center text-muted py-3 small">Tiada Data. Klik
                                                Tambah untuk menambah baris.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btn-modalNumber-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: Penetapan Skor - Ya/Tidak ===================== -->
    <div class="modal fade" id="modalYesNo" tabindex="-1" aria-labelledby="modalYesNoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalYesNoLabel">Penetapan Skor — Ya/Tidak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold">Spesifikasi</label>
                            <textarea id="modalYesNo-spesifikasi" class="form-control form-control-sm bg-light" rows="3" readonly></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Skema Maklumbalas</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="Ya/Tidak"
                                readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Skema <span class="text-danger">*</span></label>
                            <select id="yt_fg" name="yt_jenis_skema" class="form-select form-select-sm">
                                <option value="">Sila pilih...</option>
                                <option value="automatik">Automatik</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <!-- Skor Maksimum — shown when Manual -->
                        <div class="col-12 col-md-6" id="yt-manual-section" style="display:none;">
                            <label class="form-label fw-semibold">Skor Maksimum <span class="text-danger">*</span></label>
                            <input type="number" id="yt-skor-maksimum" name="yt_skor_maksimum" class="form-control form-control-sm"
                                min="0" placeholder="Masukkan nilai maksimum...">
                        </div>

                        <!-- Skema Skor — shown when Automatik -->
                        <div class="col-12" id="yt-automatik-section" style="display:none;">
                            <label class="form-label fw-semibold mb-2">Skema Skor</label>
                            <div class="d-flex gap-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold" style="min-width:40px;">Ya</span>
                                    <input type="number" id="yt-skor-ya" name="yt_skor_ya" class="form-control form-control-sm"
                                        style="width:90px;" value="1" min="0">
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold" style="min-width:40px;">Tidak</span>
                                    <input type="number" id="yt-skor-tidak" name="yt_skor_tidak" class="form-control form-control-sm"
                                        style="width:90px;" value="0" min="0">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btn-modalYesNo-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.3.0/dist/exceljs.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // ── Build item row (5 tds) ────────────────────────────────────────────────

            function buildItemRow() {
                return $(
                    '<tr class="item-row">' +
                    '<td>' +
                    '<textarea name="item_tajuk[]" class="form-control form-control-sm auto-resize" rows="1" placeholder="Tajuk item..." style="resize:none;overflow:hidden;min-height:32px;"></textarea>' +
                    '</td>' +
                    '<td class="text-center">' +
                    '<input type="number" name="item_qty[]" class="form-control form-control-sm text-center" value="0" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td>' +
                    '<select name="item_unit[]" class="form-select form-select-sm">' +
                    '<option value="">Pilih...</option>' +
                    '<option value="lot">Lot</option>' +
                    '<option value="unit">Unit</option>' +
                    '<option value="pax">Pax</option>' +
                    '<option value="hari">Hari</option>' +
                    '</select>' +
                    '</td>' +
                    '<td class="text-center text-muted small">-</td>' +
                    '<td class="text-center">' +
                    '<div class="d-flex flex-column gap-1 align-items-stretch">' +
                    '<button type="button" class="btn btn-sm btn-primary btn-tambah-spec" style="font-size:0.72rem;white-space:nowrap;">+ Tambah Spesifikasi</button>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-item" style="font-size:0.72rem;">Hapus Item</button>' +
                    '</div>' +
                    '</td>' +
                    '</tr>'
                );
            }

            // ── Build spec row (5 tds — col 1 has indented spec input, col 2-3 empty) ─
            // "Tambah Spesifikasi" is on item row only. Spec row only has "Hapus".

            function buildSpecRow() {
                return $(
                    '<tr class="spec-row">' +
                    '<td style="padding-left:28px;">' +
                    '<textarea name="spec_penerangan[]" class="form-control form-control-sm auto-resize" rows="1" placeholder="Penerangan spesifikasi..." style="resize:none;overflow:hidden;min-height:32px;"></textarea>' +
                    '</td>' +
                    '<td class="text-center text-muted small">-</td>' +
                    '<td class="text-center text-muted small">-</td>' +
                    '<td>' +
                    '<div class="d-flex align-items-center justify-content-center gap-2">' +
                    '<select name="spec_type[]" class="form-select form-select-sm typeSelect" style="font-size:0.78rem;">' +
                    '<option value="">Pilih...</option>' +
                    '<option value="1">Text</option>' +
                    '<option value="2">Nombor</option>' +
                    '<option value="3">Ya/Tidak</option>' +
                    '</select>' +
                    '<svg class="edit-icon flex-shrink-0" style="cursor:pointer;color:#f59e0b;" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                    '<path d="M12 20h9"></path>' +
                    '<path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>' +
                    '</svg>' +
                    '</div>' +
                    '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-spec" style="font-size:0.72rem;width:100%;">Hapus</button>' +
                    '</td>' +
                    '</tr>'
                );
            }

            // ── Get all consecutive spec rows belonging to an item row ────────────────

            function getItemSpecs($itemRow) {
                var $specs = $();
                var $next = $itemRow.next('.spec-row');
                while ($next.length) {
                    $specs = $specs.add($next);
                    $next = $next.next('.spec-row');
                }
                return $specs;
            }

            // ── Reindex group alternating background after any add/remove ─────────────
            // Walks all item rows in order; odd groups get no class, even groups get
            // .group-alt — applied to the item row AND all its consecutive spec rows.

            function reindexGroups() {
                // Clear all dynamic classes from a previous pass
                $('#dt_tmpltSpec tbody tr').removeClass('group-alt item-has-specs spec-last');

                var idx = 0;
                $('#dt_tmpltSpec tbody tr.item-row').each(function() {
                    idx++;
                    var $item = $(this);
                    var $specs = getItemSpecs($item);
                    var $group = $item.add($specs);

                    if (idx % 2 === 0) {
                        $group.addClass('group-alt');
                    }

                    // Tree connector classes
                    if ($specs.length > 0) {
                        $item.addClass('item-has-specs');
                        $specs.last().addClass('spec-last');
                    }
                });
            }

            // ── No-data state ─────────────────────────────────────────────────────────

            function syncNoData() {
                var hasRows = $('#dt_tmpltSpec tbody tr.item-row').length > 0;
                if (hasRows) {
                    $('#tbl-no-data').remove();
                } else if ($('#tbl-no-data').length === 0) {
                    $('#dt_tmpltSpec tbody').append(
                        '<tr id="tbl-no-data"><td colspan="5" class="text-center text-muted py-4 small">Tiada Data</td></tr>'
                        );
                }
            }

            // ── Tambah Item ───────────────────────────────────────────────────────────

            $('.add_btn_item').on('click', function() {
                $('#tbl-no-data').remove();
                $('#dt_tmpltSpec tbody').append(buildItemRow());
                reindexGroups();
            });

            // ── Tambah Spesifikasi (item row only — inserts after last spec of group) ─

            $('#dt_tmpltSpec').on('click', '.btn-tambah-spec', function() {
                var $itemRow = $(this).closest('tr');
                var $specs = getItemSpecs($itemRow);
                var $insertAfter = $specs.length > 0 ? $specs.last() : $itemRow;
                $insertAfter.after(buildSpecRow());
                reindexGroups();
            });

            // ── Excel helpers ─────────────────────────────────────────────────────────

            var HEADERS = ['Item', 'Spesifikasi', 'Kekerapan / Kuantiti', 'Unit Ukuran', 'Penetapan Skema'];
            var SKEMA_LABEL = {
                '1': 'Text',
                '2': 'Nombor',
                '3': 'Ya/Tidak'
            };
            var SKEMA_MAP = {
                'text': '1',
                'nombor': '2',
                'ya/tidak': '3'
            };

            // Download Excel using ExcelJS (supports merges + vertical top alignment).
            // includeData=true → fill table data; false → headers only (templat kosong).
            function downloadExcel(filename, includeData) {
                var wb = new ExcelJS.Workbook();
                var ws = wb.addWorksheet('Spesifikasi');

                ws.columns = [{
                        width: 35
                    },
                    {
                        width: 40
                    },
                    {
                        width: 22
                    },
                    {
                        width: 14
                    },
                    {
                        width: 18
                    }
                ];

                // Header row with red background + white bold text
                var headerRow = ws.addRow(HEADERS);
                headerRow.height = 22;
                headerRow.eachCell(function(cell) {
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: 'FFCC0000'
                        }
                    };
                    cell.font = {
                        bold: true,
                        color: {
                            argb: 'FFFFFFFF'
                        },
                        size: 10
                    };
                    cell.alignment = {
                        vertical: 'middle',
                        horizontal: 'center',
                        wrapText: true
                    };
                });

                if (includeData) {
                    $('#dt_tmpltSpec tbody tr.item-row').each(function() {
                        var $item = $(this);
                        var $specs = getItemSpecs($item);
                        var itemStart = ws.rowCount + 1;
                        var unitVal = $item.find('select').val();
                        var itemText = $item.find('td:first-child textarea').val() || '';

                        // Item row — wrapText lets Excel wrap newlines; no explicit height
                        // so the user can double-click the row border in Excel to auto-fit.
                        var iRow = ws.addRow([
                            itemText,
                            '-',
                            $item.find('input[type="number"]').val() || '0',
                            unitVal ? $item.find('select option:selected').text() : '-',
                            '-'
                        ]);
                        iRow.getCell(1).alignment = {
                            wrapText: true,
                            vertical: 'top'
                        };

                        // Spec rows — wrapText on col B, no locked height
                        $specs.each(function() {
                            var $spec = $(this);
                            var skemaVal = $spec.find('.typeSelect').val();
                            var specText = $spec.find('td:first-child textarea').val() || '';
                            var sRow = ws.addRow([
                                '',
                                specText,
                                '-',
                                '-',
                                SKEMA_LABEL[skemaVal] || ''
                            ]);
                            sRow.getCell(2).alignment = {
                                wrapText: true,
                                vertical: 'top'
                            };
                        });

                        // Merge col A across item + all its spec rows, then top-align
                        if ($specs.length > 0) {
                            var itemEnd = ws.rowCount;
                            ws.mergeCells(itemStart, 1, itemEnd, 1);
                            ws.getCell(itemStart, 1).alignment = {
                                vertical: 'top',
                                wrapText: true
                            };
                        }
                    });

                } else {
                    // Templat kosong: add sample rows (green, italic) so users know the format.
                    // Users should DELETE these rows before filling in real data.
                    // Import automatically skips rows starting with "[CONTOH]".

                    var sItemText = '[CONTOH] Keperluan Kerja Awam Dan Infrastruktur';
                    var sSpec1 = 'a) Bahan mestilah mengikut standard MS xxxx\nb) xxxx\nc) xxxx';
                    var sSpec2 = 'a) Ketebalan minimum 10mm\nb) xxxx\nc) xxxx';

                    var sampleFill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: 'FFE8F5E9'
                        }
                    };
                    var sampleFont = {
                        italic: true,
                        color: {
                            argb: 'FF2E7D32'
                        },
                        size: 9
                    };

                    function applySampleStyle(row) {
                        row.eachCell({
                            includeEmpty: true
                        }, function(cell) {
                            cell.fill = sampleFill;
                            cell.font = sampleFont;
                        });
                    }

                    // Sample item row
                    var siRow = ws.addRow([sItemText, '-', '5', 'Lot', '-']);
                    siRow.getCell(1).alignment = {
                        wrapText: true,
                        vertical: 'top'
                    };
                    applySampleStyle(siRow);
                    siRow.getCell(1).note =
                        'Isi nama item di sini. NOTA: Padam baris [CONTOH] sebelum mengisi data sebenar.';
                    siRow.getCell(2).note = 'BIARKAN sebagai "-". Lajur ini tidak digunakan untuk baris Item.';
                    siRow.getCell(5).note = 'BIARKAN sebagai "-". Lajur ini tidak digunakan untuk baris Item.';

                    // Sample spec rows (each with 3 sub-points a/b/c on separate lines)
                    var ss1Row = ws.addRow(['', sSpec1, '-', '-', 'Text']);
                    ss1Row.getCell(2).alignment = {
                        wrapText: true,
                        vertical: 'top'
                    };
                    applySampleStyle(ss1Row);
                    ss1Row.getCell(1).note =
                        'KOSONGKAN lajur ini untuk baris Spesifikasi. Sistem akan menghubungkannya dengan Item di atas.';
                    ss1Row.getCell(3).note =
                        'BIARKAN sebagai "-". Lajur ini tidak digunakan untuk baris Spesifikasi.';
                    ss1Row.getCell(4).note =
                        'BIARKAN sebagai "-". Lajur ini tidak digunakan untuk baris Spesifikasi.';

                    var ss2Row = ws.addRow(['', sSpec2, '-', '-', 'Nombor']);
                    ss2Row.getCell(2).alignment = {
                        wrapText: true,
                        vertical: 'top'
                    };
                    applySampleStyle(ss2Row);

                    // Merge col A across sample item + its 2 spec rows (visual rowspan)
                    ws.mergeCells(2, 1, 4, 1);
                    ws.getCell(2, 1).alignment = {
                        vertical: 'top',
                        wrapText: true
                    };
                }

                // Trigger download
                wb.xlsx.writeBuffer().then(function(buffer) {
                    var blob = new Blob([buffer], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                });
            }

            // ── Muat Turun Templat (with current table data) ──────────────────────────

            $('#btn-muat-turun-templat').on('click', function() {
                downloadExcel('templat_spesifikasi.xlsx', true);
            });

            // ── Muat Turun Templat Kosong (header only) ───────────────────────────────

            $('#btn-muat-turun-kosong').on('click', function() {
                downloadExcel('templat_spesifikasi_kosong.xlsx', false);
            });

            // ── Muat Naik (import from Excel via ExcelJS) ────────────────────────────
            // Detects row type: col A has value → Item row, col A slave/empty + col B has value → Spec row.
            // ExcelJS reads merged cells correctly: slave cells return null, master returns the value.

            $('#input-muat-naik').on('change', function() {
                var file = this.files[0];
                if (!file) return;

                var reader = new FileReader();
                reader.onload = function(e) {
                    var wb = new ExcelJS.Workbook();
                    wb.xlsx.load(e.target.result).then(function() {
                        var ws = wb.getWorksheet(1);

                        $('#dt_tmpltSpec tbody tr.item-row, #dt_tmpltSpec tbody tr.spec-row')
                            .remove();

                        var $lastItemRow = null;

                        // Helper: get plain text from a cell.
                        // Handles strings, numbers, RichText, and merged slave cells.
                        // Slave cells (non-top-left of a merge) must be treated as empty —
                        // ExcelJS may return the master's value for them, which would break
                        // our item-vs-spec detection. We detect slaves via cell.master !== cell.
                        function cellText(row, col) {
                            var cell = row.getCell(col);
                            // Slave cell: master is a different cell object — treat as empty
                            if (cell.master && cell.master !== cell) return '';
                            var val = cell.value;
                            if (val === null || val === undefined) return '';
                            if (typeof val === 'object' && val.richText) {
                                return val.richText.map(function(rt) {
                                    return rt.text;
                                }).join('').trim();
                            }
                            return val.toString().trim();
                        }

                        ws.eachRow(function(row, rowNum) {
                            if (rowNum === 1) return; // skip header

                            var itemText = cellText(row, 1);
                            var specText = cellText(row, 2);

                            if (!itemText && !specText) return;

                            // Skip sample/example rows from templat kosong
                            if (itemText.indexOf('[CONTOH]') === 0) return;

                            if (itemText && itemText !== '-') {
                                // Item row — append to DOM FIRST so scrollHeight is valid for resize
                                $('#tbl-no-data').remove();
                                var $itemRow = buildItemRow();
                                var qty = cellText(row, 3);
                                var unitRaw = cellText(row, 4).toLowerCase();
                                $itemRow.find('input[type="number"]').val(qty !== '-' ?
                                    qty : '0');
                                $itemRow.find('select').val(unitRaw !== '-' ? unitRaw :
                                    '');
                                $('#dt_tmpltSpec tbody').append($itemRow);
                                // Set value and trigger resize AFTER DOM insertion
                                $itemRow.find('td:first-child textarea').val(itemText)
                                    .trigger('input');
                                $lastItemRow = $itemRow;

                            } else if (specText && specText !== '-' && $lastItemRow) {
                                // Spec row — insert into DOM FIRST so scrollHeight is valid for resize
                                var $specRow = buildSpecRow();
                                var skemaKey = cellText(row, 5).toLowerCase();
                                $specRow.find('.typeSelect').val(SKEMA_MAP[skemaKey] ||
                                    '');
                                var $existing = getItemSpecs($lastItemRow);
                                var $insertAfter = $existing.length > 0 ? $existing
                                    .last() : $lastItemRow;
                                $insertAfter.after($specRow);
                                // Set value and trigger resize AFTER DOM insertion
                                $specRow.find('td:first-child textarea').val(specText)
                                    .trigger('input');
                            }
                        });

                        syncNoData();
                        reindexGroups();
                        $('#input-muat-naik').val('');
                    }).catch(function(err) {
                        console.error('ExcelJS import error:', err);
                        alert(
                            'Gagal membaca fail Excel. Sila pastikan fail adalah format .xlsx yang sah.');
                        $('#input-muat-naik').val('');
                    });
                };
                reader.readAsArrayBuffer(file);
            });

            // ── Auto-resize textareas as user types ───────────────────────────────────

            $(document).on('input', '#dt_tmpltSpec textarea.auto-resize', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            // ── Confirm hapus modal ───────────────────────────────────────────────────

            var pendingDelete = null;

            function showHapusConfirm(message, callback) {
                pendingDelete = callback;
                $('#modal-hapus-message').text(message);
                new bootstrap.Modal($('#modalHapusConfirm')[0]).show();
            }

            $('#btn-confirm-hapus').on('click', function() {
                if (pendingDelete) {
                    pendingDelete();
                    pendingDelete = null;
                }
                bootstrap.Modal.getInstance($('#modalHapusConfirm')[0]).hide();
            });

            // ── Hapus Item (confirms first, then removes item + all its spec rows) ────

            $('#dt_tmpltSpec').on('click', '.btn-hapus-item', function() {
                var $row = $(this).closest('tr');
                var specCount = getItemSpecs($row).length;
                var msg = specCount > 0 ?
                    'Adakah anda pasti untuk menghapus item ini? Sebanyak ' + specCount +
                    ' spesifikasi di bawahnya turut akan dihapus.' :
                    'Adakah anda pasti untuk menghapus item ini?';
                showHapusConfirm(msg, function() {
                    getItemSpecs($row).remove();
                    $row.remove();
                    syncNoData();
                    reindexGroups();
                });
            });

            // ── Hapus Spesifikasi (confirms first) ────────────────────────────────────

            $('#dt_tmpltSpec').on('click', '.btn-hapus-spec', function() {
                var $row = $(this).closest('tr');
                showHapusConfirm('Adakah anda pasti untuk menghapus spesifikasi ini?', function() {
                    $row.remove();
                    syncNoData();
                    reindexGroups();
                });
            });

            // ── Penetapan Skema — edit icon opens modal ───────────────────────────────

            var $activeSpecRow = null; // tracks which spec row's edit icon was clicked

            $('#dt_tmpltSpec').on('click', '.edit-icon', function() {
                var $specRow = $(this).closest('tr.spec-row');
                var val = $(this).closest('td').find('.typeSelect').val();

                if (val === '1') {
                    $activeSpecRow = $specRow;
                    var specText = $specRow.find('td:first-child textarea').val() || '';
                    $('#modalText-spesifikasi').val(specText);
                    $('#modalText-skema-maksima').val('');
                    new bootstrap.Modal($('#modalText')[0]).show();
                } else if (val === '2') {
                    $activeSpecRow = $specRow;
                    var specText = $specRow.find('td:first-child textarea').val() || '';
                    $('#modalNumber-spesifikasi').val(specText);
                    // Reset skema skor table
                    $('#tbl-nombor-skema-body').empty().append(
                        '<tr id="tbl-nombor-no-data"><td colspan="4" class="text-center text-muted py-3 small">Tiada Data. Klik Tambah untuk menambah baris.</td></tr>'
                    );
                    $('#nombor-check-all').prop('checked', false);
                    new bootstrap.Modal($('#modalNumber')[0]).show();
                } else if (val === '3') {
                    $activeSpecRow = $specRow;
                    var specText = $specRow.find('td:first-child textarea').val() || '';
                    $('#modalYesNo-spesifikasi').val(specText);
                    // Reset dynamic fields
                    $('#yt_fg').val('');
                    $('#yt-manual-section, #yt-automatik-section').hide();
                    $('#yt-skor-maksimum').val('').removeClass('is-invalid');
                    $('#yt-skor-ya').val(1);
                    $('#yt-skor-tidak').val(0);
                    new bootstrap.Modal($('#modalYesNo')[0]).show();
                } else {
                    alert('Sila pilih jenis terlebih dahulu.');
                }
            });

            // ── Modal Text — Simpan ───────────────────────────────────────────────────

            $('#btn-modalText-simpan').on('click', function() {
                var maksima = $('#modalText-skema-maksima').val().trim();
                if (!maksima) {
                    $('#modalText-skema-maksima').focus();
                    return;
                }
                // Store skema maksima back on the spec row as a data attribute for later use
                if ($activeSpecRow) {
                    $activeSpecRow.data('skema-maksima', maksima);
                    $activeSpecRow = null;
                }
                bootstrap.Modal.getInstance($('#modalText')[0]).hide();
            });

            // ── Modal Number — Skema Skor table helpers ───────────────────────────────

            function buildNomborRow() {
                return $(
                    '<tr class="nombor-skema-row">' +
                    '<td class="text-center"><input type="checkbox" name="nombor_row_check[]" class="form-check-input nombor-row-check"></td>' +
                    '<td><input type="number" name="nombor_dari[]" class="form-control form-control-sm text-center" min="0" placeholder="0"></td>' +
                    '<td><input type="number" name="nombor_hingga[]" class="form-control form-control-sm text-center" min="0" placeholder="0"></td>' +
                    '<td><input type="number" name="nombor_skema[]" class="form-control form-control-sm text-center" min="0" placeholder="0"></td>' +
                    '</tr>'
                );
            }

            function syncNomborNoData() {
                var hasRows = $('#tbl-nombor-skema-body tr.nombor-skema-row').length > 0;
                if (hasRows) {
                    $('#tbl-nombor-no-data').remove();
                } else if ($('#tbl-nombor-no-data').length === 0) {
                    $('#tbl-nombor-skema-body').append(
                        '<tr id="tbl-nombor-no-data"><td colspan="4" class="text-center text-muted py-3 small">Tiada Data. Klik Tambah untuk menambah baris.</td></tr>'
                    );
                }
            }

            $('#btn-nombor-tambah').on('click', function() {
                $('#tbl-nombor-no-data').remove();
                var $newRow = buildNomborRow();

                // Auto-fill Dari based on the last row's Hingga (+1)
                var $lastRow = $('#tbl-nombor-skema-body tr.nombor-skema-row').last();
                if ($lastRow.length) {
                    var lastHingga = parseFloat($lastRow.find('td:eq(2) input').val());
                    if (!isNaN(lastHingga) && lastHingga !== '') {
                        $newRow.find('td:eq(1) input').val(lastHingga + 1);
                    }
                }

                $('#tbl-nombor-skema-body').append($newRow);
                $('#nombor-check-all').prop('checked', false);
            });

            $('#btn-nombor-hapus').on('click', function() {
                $('#tbl-nombor-skema-body tr.nombor-skema-row').filter(function() {
                    return $(this).find('.nombor-row-check').is(':checked');
                }).remove();
                $('#nombor-check-all').prop('checked', false);
                syncNomborNoData();
            });

            $('#nombor-check-all').on('change', function() {
                var checked = $(this).is(':checked');
                $('#tbl-nombor-skema-body .nombor-row-check').prop('checked', checked);
            });

            // Sync select-all when individual checkboxes change
            $('#tbl-nombor-skema-body').on('change', '.nombor-row-check', function() {
                var total = $('#tbl-nombor-skema-body .nombor-row-check').length;
                var checked = $('#tbl-nombor-skema-body .nombor-row-check:checked').length;
                $('#nombor-check-all').prop('checked', total > 0 && total === checked);
            });

            // Real-time sequence validation:
            // - Dari change: validate it is > previous row's Hingga
            // - Hingga change: re-validate the next row's Dari
            $('#tbl-nombor-skema-body').on('input', 'tr.nombor-skema-row input[type="number"]', function() {
                var $input = $(this);
                var $td = $input.closest('td');
                var $row = $td.closest('tr');
                var colIdx = $td.index(); // 1=Dari, 2=Hingga, 3=Skema

                if (colIdx === 1) {
                    // Dari changed — check against previous row's Hingga
                    var $prevRow = $row.prev('tr.nombor-skema-row');
                    if ($prevRow.length) {
                        var prevHingga = parseFloat($prevRow.find('td:eq(2) input').val());
                        var dari = parseFloat($input.val());
                        var invalid = !isNaN(prevHingga) && !isNaN(dari) && dari <= prevHingga;
                        $input.toggleClass('is-invalid', invalid);
                    }
                } else if (colIdx === 2) {
                    // Hingga changed — re-validate next row's Dari
                    var $nextRow = $row.next('tr.nombor-skema-row');
                    if ($nextRow.length) {
                        var hingga = parseFloat($input.val());
                        var $nextDari = $nextRow.find('td:eq(1) input');
                        var nextDari = parseFloat($nextDari.val());
                        var invalid = !isNaN(hingga) && !isNaN(nextDari) && nextDari <= hingga;
                        $nextDari.toggleClass('is-invalid', invalid);
                    }
                }
            });

            $('#btn-modalNumber-simpan').on('click', function() {
                // Validate sequence: each row's Dari must be > previous row's Hingga
                var valid = true;
                var prevHingga = null;
                $('#tbl-nombor-skema-body tr.nombor-skema-row').each(function() {
                    var $tds = $(this).find('td');
                    var $dari = $tds.eq(1).find('input');
                    var dari = parseFloat($dari.val());
                    var hingga = parseFloat($tds.eq(2).find('input').val());

                    if (prevHingga !== null && !isNaN(dari) && dari <= prevHingga) {
                        $dari.addClass('is-invalid');
                        valid = false;
                    }
                    if (!isNaN(hingga)) prevHingga = hingga;
                });

                if (!valid) {
                    alert('Nilai "Dari" mesti lebih besar daripada nilai "Hingga" baris sebelumnya.');
                    return;
                }

                // Collect and save
                var skemaRows = [];
                $('#tbl-nombor-skema-body tr.nombor-skema-row').each(function() {
                    var $tds = $(this).find('td');
                    skemaRows.push({
                        dari: $tds.eq(1).find('input').val(),
                        hingga: $tds.eq(2).find('input').val(),
                        skema: $tds.eq(3).find('input').val()
                    });
                });
                if ($activeSpecRow) {
                    $activeSpecRow.data('skema-nombor', skemaRows);
                    $activeSpecRow = null;
                }
                bootstrap.Modal.getInstance($('#modalNumber')[0]).hide();
            });

            // ── Ya/Tidak modal — toggle sections based on Jenis Skema ─────────────────

            $('#yt_fg').on('change', function() {
                var val = $(this).val();
                $('#yt-manual-section').toggle(val === 'manual');
                $('#yt-automatik-section').toggle(val === 'automatik');
                // Reset values on switch
                $('#yt-skor-maksimum').val('').removeClass('is-invalid');
                $('#yt-skor-ya').val(1);
                $('#yt-skor-tidak').val(0);
            });

            $('#btn-modalYesNo-simpan').on('click', function() {
                var jenis = $('#yt_fg').val();
                if (!jenis) {
                    $('#yt_fg').addClass('is-invalid').focus();
                    return;
                }
                $('#yt_fg').removeClass('is-invalid');

                if (jenis === 'manual') {
                    var maks = $('#yt-skor-maksimum').val().trim();
                    if (!maks) {
                        $('#yt-skor-maksimum').addClass('is-invalid').focus();
                        return;
                    }
                    $('#yt-skor-maksimum').removeClass('is-invalid');
                    if ($activeSpecRow) {
                        $activeSpecRow.data('skema-yatidak', {
                            jenis: 'manual',
                            skorMaksimum: maks
                        });
                    }
                } else {
                    if ($activeSpecRow) {
                        $activeSpecRow.data('skema-yatidak', {
                            jenis: 'automatik',
                            skorYa: $('#yt-skor-ya').val(),
                            skorTidak: $('#yt-skor-tidak').val()
                        });
                    }
                }

                $activeSpecRow = null;
                bootstrap.Modal.getInstance($('#modalYesNo')[0]).hide();
            });

        });
    </script>
@endsection
