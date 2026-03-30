@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Semak Teknikal</h3>
            <p class="text-muted small m-0">Paparan senarai semak bahagian teknikal bagi tender / sebutharga.</p>
        </div>
    </div>

    <!-- TENDER INFO CARD -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">

            <!-- Tajuk Tender -->
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    MEMBEKAL RANGSUM PUKAL (AIR MINERAL) UNTUK BANGUNAN KERAJAAN
                    <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">(Bekalan Perkhidmatan)</span>
                </h5>
            </div>

            <!-- Metadata: No. Tender · PTJ · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">SUKSEL/PERT/2026/001</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">100-007</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- ===================== SECTION 1: PENYEDIAAN SPESIFIKASI & SKOR ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Penyediaan Spesifikasi &amp; Skor</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Senarai Semak Teknikal</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Guidelines: numbered grid -->
            <div class="rounded-2 p-3 mb-4" style="background: #fafbfc; border: 1px solid #f1f5f9;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-muted flex-shrink-0">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span class="fw-semibold text-muted text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">Panduan Pengisian</span>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">1</span>
                            <span class="small text-muted" style="line-height:1.5;">Senarai semak dijana berdasarkan <strong class="text-dark">Kategori Perolehan</strong>.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">2</span>
                            <span class="small text-muted" style="line-height:1.5;">Pilih kotak semak dalam lajur <strong class="text-dark">Skor</strong> untuk memilih senarai semak yang ingin dinilai.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">3</span>
                            <span class="small text-muted" style="line-height:1.5;">Klik <strong class="text-dark">Kemaskini</strong> untuk kunci masuk skema skor penilaian atau pinda spesifikasi.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">4</span>
                            <span class="small text-muted" style="line-height:1.5;">Klik <strong class="text-dark">Cipta Spesifikasi</strong> untuk cipta templat dan spesifikasi baru.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">5</span>
                            <span class="small text-muted" style="line-height:1.5;">Klik <strong class="text-dark">Tambah</strong> untuk kunci masuk senarai semak baru.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">6</span>
                            <span class="small text-muted" style="line-height:1.5;">Senarai semak dengan tindakan <strong class="text-dark">Muat Naik</strong> oleh pembekal akan menjadi dokumen pematuhan secara automatik.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">7</span>
                            <span class="small text-muted" style="line-height:1.5;">Klik <strong class="text-dark">Senarai Semak Standard</strong> dan pilih senarai semak yang diperlukan.</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex gap-2 align-items-start">
                            <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white rounded-circle" style="width:18px;height:18px;min-width:18px;font-size:0.6rem;background:var(--sg-red);margin-top:2px;">8</span>
                            <span class="small text-muted" style="line-height:1.5;">Untuk perkhidmatan bayaran progresif, sila pilih <strong class="text-dark">tempat perkhidmatan</strong> yang berkenaan.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Action Buttons -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <!-- Left: Template actions -->
                <div class="d-flex align-items-center gap-2">
                    <a href="#" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#senaraiSemakStandard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        Senarai Semak Standard
                    </a>
                    <a href="#" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#ciptaSpesifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Cipta Spesifikasi
                    </a>
                </div>
                <!-- Divider -->
                <div style="width: 1px; height: 24px; background: #e2e8f0;"></div>
                <!-- Right: Row actions -->
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-teknikal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah
                    </button>
                    <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 btn-hapus-teknikal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                        Hapus
                    </button>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table id="tbl-teknikal" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 44px;">
                                <input type="checkbox" class="form-check-input px-0 check-all-teknikal">
                            </th>
                            <th style="min-width: 180px;">Tajuk / Dokumen</th>
                            <th class="text-center" style="width: 185px;">Mekanisma</th>
                            <th class="text-center" style="width: 140px;">Tindakan Pembekal</th>
                            <th class="text-center" style="width: 110px;">Skema</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="text-center" style="width: 110px;">Rujukan</th>
                            <th class="text-center" style="width: 110px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ROW 1: Spesifikasi — hardcoded, fixed -->
                        <tr>
                            <td class="text-center"><input type="checkbox" class="form-check-input row-check-teknikal"></td>
                            <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                            <td class="text-center"><span class="small fw-semibold text-muted">Spesifikasi</span></td>
                            <td class="text-center small">Kunci Masuk</td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm text-center skema-input fw-semibold" value="40" min="0" style="max-width:90px;margin:0 auto;">
                            </td>
                            <td class="text-center"><span class="badge-status badge-status-success">Selesai</span></td>
                            <td class="text-center text-muted small rujukan-cell">—</td>
                            <td class="text-center">
                                <a href="{{ route('spesifikasiForm') }}" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini Spesifikasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        <!-- EMPTY STATE ROW (shown when all rows deleted) -->
                        <tr id="tbl-empty-row" class="d-none">
                            <td colspan="8" class="text-center text-muted py-4 small">Tiada Data</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                            <td colspan="4" class="text-end py-3 pe-3">
                                <span class="small fw-semibold text-muted text-uppercase" style="letter-spacing: 0.5px;">Skema Maksima</span>
                            </td>
                            <td class="text-center py-3">
                                <input type="text" id="skema-maksima-display"
                                    class="form-control form-control-sm text-center fw-bold"
                                    value="40" readonly
                                    style="max-width: 68px; margin: 0 auto; background: #fff; border-color: #e2e8f0;">
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>


        </div>
    </div>

    <!-- ===================== SECTION 2: PENETAPAN PENANDA ARAS ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size: 1rem;">Penetapan Penanda Aras Tahap Lulus</h3>
            </div>
        </div>
        <div class="content-card-body p-4">
            <div class="row g-3">

                <!-- Penilaian Teknikal input -->
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-2 h-100" style="background: #fafbfc; border: 1px solid #f1f5f9;">
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.67rem; letter-spacing: 0.5px;">Penilaian Teknikal</span>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="input-penilaian" class="form-control form-control-sm text-center fw-semibold"
                                style="max-width: 80px; font-size: 1rem;" placeholder="0" min="0" max="121">
                            <span class="text-muted small">daripada</span>
                            <span class="fw-bold text-dark" id="penilaian-teknikal-total" style="font-size: 1rem;">40</span>
                            <span class="text-muted small">markah</span>
                        </div>
                    </div>
                </div>

                <!-- Tahap Lulus display -->
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-2 h-100 d-flex flex-column justify-content-center" style="background: #fafbfc; border: 1px solid #f1f5f9;">
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.67rem; letter-spacing: 0.5px;">Tahap Lulus</span>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="fw-bold text-primary" id="tahap-lulus" style="font-size: 1.75rem; line-height: 1;">70</span>
                            <span class="fw-semibold text-primary" style="font-size: 1rem;">%</span>
                            <span class="text-muted small ms-1">peratus</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ===================== SECTION 3: DOKUMEN SOKONGAN ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size: 1rem;">Dokumen Sokongan / Rujukan</h3>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Empty state -->
            <div id="dokumen-empty" class="text-center py-3 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                    stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <p class="text-muted small mb-0 mt-2">Tiada dokumen sokongan. Klik <strong>Tambah Dokumen</strong> untuk menambah.</p>
            </div>

            <!-- Document rows (injected via jQuery) -->
            <div id="dokumen-list" class="mb-3"></div>

            <!-- Add button — dashed full-width, always at the bottom -->
            <button type="button" id="btn-tambah-dokumen"
                class="d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-2 fw-semibold small"
                style="background: #f8fafc; border: 1.5px dashed #cbd5e1; color: #64748b; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Dokumen
            </button>

        </div>
    </div>

    <!-- ===================== BOTTOM ACTION BUTTONS ===================== -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <button type="button" class="btn btn-success btn-simpan">Simpan</button>
        <button type="button" class="btn btn-primary btn-hantar">Hantar</button>
    </div>
@endsection

@push('modals')
    <!-- ===================== MODAL: SUCCESS ===================== -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#E6F7F3"/>
                        <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7"/>
                    </svg>
                </div>
                <h5 class="fw-bold mb-2">Berjaya</h5>
                <p class="text-muted mb-4">Maklumat telah berjaya disimpan.</p>
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: SENARAI SEMAK STANDARD ===================== -->
    <div class="modal fade" id="senaraiSemakStandard" tabindex="-1" aria-labelledby="senaraiSemakStandardLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="senaraiSemakStandardLabel">Senarai Semak Standard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbl-standard" class="table table-modern w-100 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 44px;">
                                        <input type="checkbox" class="form-check-input check-all-standard">
                                    </th>
                                    <th>Tajuk / Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Pengalaman Syarikat Dengan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Skop Bekalan Dan Perkhidmatan</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Salinan Borang KWSP A setiap pekerja bagi bulan caruman terakhir</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Bilangan Kakitangan</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Brosur / Risalah</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Surat pengesahan pendaftaran dengan Pertubuhan Keselamatan Sosial (Perkeso) yang telah dikeluarkan mengikut Akta Keselamatan Sosial Pekerja 1969. Jadual Caruman Bulanan (Borang 8A) dan Resit Bayaran Caruman yang terbaru</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Cadangan Bertulis</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Lesen Premis oleh PBT</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Jadual Pelaksanaan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm btn-success btn-pilih-standard">Pilih</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: CIPTA SPESIFIKASI ===================== -->
    <div class="modal fade" id="ciptaSpesifikasi" tabindex="-1" aria-labelledby="ciptaSpesifikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="ciptaSpesifikasiLabel">Cipta Spesifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Search / Filter -->
                    <div class="content-card mb-3 p-0">
                        <div class="content-card-header px-4 py-3 border-bottom">
                            <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Cari Templat</span>
                        </div>
                        <div class="content-card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Klon Spesifikasi Daripada <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-column gap-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="klonSpesifikasi" id="klonSpesifikasi1" checked>
                                            <label class="form-check-label" for="klonSpesifikasi1">Templat Standard / Kosong</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="klonSpesifikasi" id="klonSpesifikasi2">
                                            <label class="form-check-label" for="klonSpesifikasi2">Sebut Harga / Tender Yang Lepas</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Jenis Item</label>
                                    <select class="form-select form-select-sm">
                                        <option value="">Sila pilih...</option>
                                        <option value="bekalan">Bekalan</option>
                                        <option value="perkhidmatan">Perkhidmatan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-sm btn-primary">Cari</button>
                            </div>
                        </div>
                    </div>

                    <!-- Template Results Table -->
                    <div class="content-card p-0">
                        <div class="content-card-header px-4 py-3 border-bottom">
                            <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Templat</span>
                        </div>
                        <div class="content-card-body">
                            <div class="table-responsive">
                                <table id="templateTable" class="table table-modern w-100 mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tajuk / Dokumen</th>
                                            <th class="text-center" style="width: 110px;">Skor Maksima</th>
                                            <th class="text-center" style="width: 120px;">Jenis Item</th>
                                            <th class="text-center" style="width: 130px;">Dicipta Oleh</th>
                                            <th class="text-center" style="width: 90px;">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="template-row">
                                            <td>Templat Standard / Kosong</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">
                                                <a href="{{ route('spesifikasiForm') }}" class="btn btn-sm btn-primary text-white">Cipta</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endpush

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            // ─── CHECKBOX: Senarai Semak Teknikal ────────────────────────────────────
            $('#tbl-teknikal').on('change', '.check-all-teknikal', function () {
                $('#tbl-teknikal .row-check-teknikal').prop('checked', $(this).prop('checked'));
            });

            $('#tbl-teknikal').on('change', '.row-check-teknikal', function () {
                var total   = $('#tbl-teknikal .row-check-teknikal').length;
                var checked = $('#tbl-teknikal .row-check-teknikal:checked').length;
                $('#tbl-teknikal .check-all-teknikal').prop('checked', total === checked);
            });

            // ─── HELPERS ──────────────────────────────────────────────────────────────
            var urlBorangAtasTalian = "{{ route('kjDlmTanganForm', ':id') }}";

            function getTindakanPembekal(mekanisma) {
                if (mekanisma === 'borang_atas_talian' || mekanisma === 'spesifikasi') return 'Kunci Masuk';
                return 'Muat Naik';
            }

            function buildTindakanCell(mekanisma, id) {
                if (mekanisma === 'borang_atas_talian') {
                    var href = id ? urlBorangAtasTalian.replace(':id', id) : urlBorangAtasTalian;
                    return '<a href="' + href + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                        '</a>';
                }
                // petender_muat_naik or ptj_muat_naik
                return '<label class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-1 mb-0" style="width:30px;height:30px;cursor:pointer;" title="Muat Naik">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>' +
                    '<input type="file" hidden>' +
                    '</label>';
            }

            function buildNewRow() {
                return $(
                    '<tr class="row-teknikal-tambah">' +
                        '<td class="text-center"><input type="checkbox" class="form-check-input row-check-teknikal"></td>' +
                        '<td><input type="text" class="form-control form-control-sm" placeholder="Tajuk / Dokumen..."></td>' +
                        '<td class="text-center">' +
                            '<select class="form-select form-select-sm mekanisma-select" style="font-size:0.78rem;">' +
                                '<option value="borang_atas_talian">Borang Atas Talian</option>' +
                                '<option value="petender_muat_naik">Petender Muat Naik</option>' +
                                '<option value="ptj_muat_naik">PTJ Muat Naik</option>' +
                            '</select>' +
                        '</td>' +
                        '<td class="text-center small tindakan-pembekal">Kunci Masuk</td>' +
                        '<td class="text-center">' +
                            '<input type="number" class="form-control form-control-sm text-center skema-input fw-semibold" value="0" min="0" style="max-width:90px;margin:0 auto;">' +
                        '</td>' +
                        '<td class="text-center"><span class="badge-status badge-status-warning">Draf</span></td>' +
                        '<td class="text-center text-muted small rujukan-cell">—</td>' +
                        '<td class="text-center tindakan-cell">' + buildTindakanCell('borang_atas_talian') + '</td>' +
                    '</tr>'
                );
            }

            function updateSkemaMaksima() {
                var total = 0;
                $('#tbl-teknikal .skema-input').each(function () {
                    total += parseInt($(this).val()) || 0;
                });
                $('#skema-maksima-display').val(total);
                $('#penilaian-teknikal-total').text(total);
            }

            function syncTableEmpty() {
                var realRows = $('#tbl-teknikal tbody tr:not(#tbl-empty-row)').length;
                if (realRows === 0) {
                    $('#tbl-empty-row').removeClass('d-none');
                } else {
                    $('#tbl-empty-row').addClass('d-none');
                }
            }

            // ─── TAMBAH ROW ───────────────────────────────────────────────────────────
            $('.btn-tambah-teknikal').on('click', function () {
                $('#tbl-teknikal tbody').append(buildNewRow());
                syncTableEmpty();
                updateSkemaMaksima();
            });

            // ─── HAPUS ROW (CHECKED ONLY) ─────────────────────────────────────────────
            $('.btn-hapus-teknikal').on('click', function () {
                var checked = $('#tbl-teknikal .row-check-teknikal:checked');
                if (checked.length === 0) {
                    alert('Sila pilih sekurang-kurangnya satu rekod untuk dihapus.');
                    return;
                }
                checked.closest('tr').remove();
                $('#tbl-teknikal .check-all-teknikal').prop('checked', false);
                syncTableEmpty();
                updateSkemaMaksima();
            });

            // ─── MEKANISMA CHANGE ─────────────────────────────────────────────────────
            $('#tbl-teknikal').on('change', '.mekanisma-select', function () {
                var val = $(this).val();
                var row = $(this).closest('tr');
                row.find('.tindakan-pembekal').text(getTindakanPembekal(val));
                row.find('.tindakan-cell').html(buildTindakanCell(val));
            });

            // ─── SKEMA INPUT CHANGE ───────────────────────────────────────────────────
            $('#tbl-teknikal').on('input change', '.skema-input', function () {
                updateSkemaMaksima();
            });

            // ─── FILE UPLOAD → FILL RUJUKAN CELL ─────────────────────────────────────
            $('#tbl-teknikal').on('change', '.tindakan-cell input[type="file"]', function () {
                if (this.files && this.files[0]) {
                    var file    = this.files[0];
                    var url     = URL.createObjectURL(file);
                    var fileInput = this;
                    $(this).closest('tr').find('.rujukan-cell').html(
                        '<div class="d-inline-flex align-items-center gap-1">' +
                            '<a href="' + url + '" target="_blank" class="small fw-semibold text-truncate d-inline-block" style="max-width: 80px;" title="' + file.name + '">' + file.name + '</a>' +
                            '<button type="button" class="btn-hapus-rujukan d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:16px;height:16px;background:#fee2e2;border:none;border-radius:3px;padding:0;cursor:pointer;" title="Buang">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                            '</button>' +
                        '</div>'
                    );
                }
            });

            // ─── REMOVE RUJUKAN ───────────────────────────────────────────────────────
            $('#tbl-teknikal').on('click', '.btn-hapus-rujukan', function () {
                var row = $(this).closest('tr');
                row.find('.rujukan-cell').html('<span class="text-muted">—</span>');
                row.find('.tindakan-cell input[type="file"]').val('');
            });

            // ─── CHECKBOX: Modal Senarai Semak Standard ───────────────────────────────
            $('#senaraiSemakStandard').on('change', '.check-all-standard', function () {
                $('#tbl-standard .row-check-standard').prop('checked', $(this).prop('checked'));
            });

            $('#senaraiSemakStandard').on('change', '.row-check-standard', function () {
                var total   = $('#tbl-standard .row-check-standard').length;
                var checked = $('#tbl-standard .row-check-standard:checked').length;
                $('#tbl-standard .check-all-standard').prop('checked', total === checked);
            });


            // ─── DOKUMEN SOKONGAN: Tambah / Hapus / Auto-fill filename ───────────────
            function syncDokumenEmpty() {
                if ($('#dokumen-list .dokumen-row').length === 0) {
                    $('#dokumen-empty').show();
                } else {
                    $('#dokumen-empty').hide();
                }
            }

            function buildDokumenRow() {
                return $(
                    '<div class="dokumen-row d-flex align-items-center gap-2 mb-2">' +
                        '<input type="text" class="form-control form-control-sm dokumen-nama" placeholder="Nama dokumen akan diisi setelah fail dipilih..." disabled>' +
                        '<label class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 flex-shrink-0 mb-0" style="cursor:pointer; white-space:nowrap;">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2m-5-10v6"/><path d="M9.5 13.5L12 11l2.5 2.5"/></g></svg>' +
                            'Pilih Fail' +
                            '<input type="file" class="dokumen-file" hidden>' +
                        '</label>' +
                        '<button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center flex-shrink-0 btn-hapus-dokumen" style="width:30px;height:30px;padding:0;" title="Hapus">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-5 5l4 4m0-4l-4 4"/></svg>' +
                        '</button>' +
                    '</div>'
                );
            }

            $('#btn-tambah-dokumen').on('click', function () {
                $('#dokumen-list').append(buildDokumenRow());
                syncDokumenEmpty();
            });

            // File selected → auto-fill name input
            $('#dokumen-list').on('change', '.dokumen-file', function () {
                if (this.files && this.files[0]) {
                    $(this).closest('.dokumen-row').find('.dokumen-nama').val(this.files[0].name);
                }
            });

            // Remove row
            $('#dokumen-list').on('click', '.btn-hapus-dokumen', function () {
                $(this).closest('.dokumen-row').remove();
                syncDokumenEmpty();
            });

            // ─── SUCCESS MODAL: Simpan & Hantar ──────────────────────────────────────
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));

            $('.btn-simpan').on('click', function () {
                successModal.show();
            });

            $('.btn-hantar').on('click', function () {
                successModal.show();
            });

        });
    </script>
@endsection
