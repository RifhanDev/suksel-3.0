@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Semak Teknikal</h3>
            <p class="text-muted small m-0">Paparan senarai semak bahagian teknikal bagi tender / sebutharga.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
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

            <!-- Metadata: No. Tender · PTJ · Status -->
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

    <!-- ===================== SECTION 1: PENYEDIAAN SPESIFIKASI & SKOR ===================== -->
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
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Penyediaan Spesifikasi &amp; Skor</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Senarai Semak Teknikal</p>
                </div>
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
                        <span class="guideline-item-text">Senarai semak dijana berdasarkan <span class="highlight">Kategori Perolehan</span>.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">2</span>
                        <span class="guideline-item-text">Pilih kotak semak dalam lajur <span class="highlight">Skor</span> untuk memilih senarai semak yang ingin dinilai.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">3</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Kemaskini</span> untuk kunci masuk skema skor penilaian atau pinda spesifikasi.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">4</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Cipta Spesifikasi</span> untuk cipta templat dan spesifikasi baru.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">5</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Tambah</span> untuk kunci masuk senarai semak baru.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">6</span>
                        <span class="guideline-item-text">Senarai semak dengan tindakan <span class="highlight">Muat Naik</span> oleh pembekal akan menjadi dokumen pematuhan secara automatik.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">7</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Senarai Semak Standard</span> dan pilih senarai semak yang diperlukan.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">8</span>
                        <span class="guideline-item-text">Untuk perkhidmatan bayaran progresif, sila pilih <span class="highlight">tempat perkhidmatan</span> yang berkenaan.</span>
                    </div>
                </div>
            </div>

            <!-- Table Action Buttons -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <!-- Left: Actions -->
                <div class="d-flex align-items-center gap-2">
                    <a href="#" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#senaraiSemakStandard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        Senarai Semak Standard
                    </a>
                    <a href="#" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#ciptaSpesifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Cipta Spesifikasi
                    </a>
                </div>
                <!-- Divider -->
                <div style="width: 1px; height: 24px; background: #e2e8f0;"></div>
                <!-- Right: Row actions -->
                <div class="d-flex align-items-center gap-2">
                    <button type="button"
                        class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-teknikal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                    <button type="button"
                        class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 btn-hapus-teknikal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"><path fill="currentColor" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-9.489 5.14a1 1 0 0 0-1.218 1.567L10.585 14l-1.292 1.293l-.083.094a1 1 0 0 0 1.497 1.32L12 15.415l1.293 1.292l.094.083a1 1 0 0 0 1.32-1.497L13.415 14l1.292-1.293l.083-.094a1 1 0 0 0-1.497-1.32L12 12.585l-1.293-1.292l-.094-.083zM14 2a2 2 0 0 1 2 2a1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/></svg>
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
                                <input type="checkbox" name="check_all_teknikal" class="form-check-input px-0 check-all-teknikal">
                            </th>
                            <th style="min-width: 180px;">Tajuk / Dokumen</th>
                            <th class="text-center" style="width: 185px;">Mekanisma</th>
                            <th class="text-center" style="width: 140px;">Tindakan Pembekal</th>
                            <th class="text-center" style="width: 110px;">Skema</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="text-center" style="width: 110px;">Dokumen</th>
                            <th class="text-center" style="width: 110px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ROW 1: Spesifikasi -->
                        <tr>
                            <td class="text-center"><input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal">
                            </td>
                            <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                            <td class="text-center"><span class="small fw-semibold text-muted">Spesifikasi</span></td>
                            <td class="text-center small">Kunci Masuk</td>
                            <td class="text-center">
                                <input type="number" name="skema[]"
                                    class="form-control form-control-sm text-center skema-input fw-semibold"
                                    value="40" min="0" style="max-width:90px;margin:0 auto;">
                            </td>
                            <td class="text-center"><span class="badge-status badge-status-success">Selesai</span></td>
                            <td class="text-center text-muted small rujukan-cell">—</td>
                            <td class="text-center">
                                <a href="{{ route('spesifikasiForm') }}"
                                    class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1"
                                    style="width:30px;height:30px;" title="Kemaskini Spesifikasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
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
                                <span class="small fw-semibold text-muted text-uppercase"
                                    style="letter-spacing: 0.5px;">Skema Maksima</span>
                            </td>
                            <td class="text-center py-3">
                                <input type="text" id="skema-maksima-display" name="skema_maksima"
                                    class="form-control form-control-sm text-center fw-bold" value="40" readonly
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">Penilaian Teknikal</span>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="input-penilaian" name="input_penilaian" class="form-control form-control-sm text-center fw-semibold" style="max-width: 80px; font-size: 1rem;" placeholder="0" min="0" max="121">
                            <span class="text-muted small">daripada</span>
                            <span class="fw-bold text-dark" id="penilaian-teknikal-total"
                                style="font-size: 1rem;">40</span>
                            <span class="text-muted small">markah</span>
                        </div>
                    </div>
                </div>

                <!-- Tahap Lulus display -->
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-2 h-100 d-flex flex-column justify-content-center"
                        style="background: #fafbfc; border: 1px solid #f1f5f9;">
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">Tahap Lulus</span>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="fw-bold text-primary" id="tahap-lulus"
                                style="font-size: 1.75rem; line-height: 1;">70</span>
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

            <!-- Upload Zone -->
            <label class="upload-zone w-100" id="upload-zone-sokongan" for="input-dokumen-sokongan">
                <div class="upload-zone-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 16 12 12 8 16"></polyline>
                        <line x1="12" y1="12" x2="12" y2="21"></line>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                    </svg>
                </div>
                <span class="upload-zone-label">Klik atau seret fail ke sini untuk muat naik</span>
                <span class="upload-zone-sub">PDF, Word, Excel, Imej, ZIP — saiz maksimum 10 MB setiap fail</span>
                <input type="file" id="input-dokumen-sokongan" name="dokumen_sokongan[]" multiple hidden
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.rar">
            </label>

            <!-- Uploaded file chips -->
            <div class="file-chip-list" id="file-chip-list-sokongan"></div>

        </div>
    </div>

    <!-- ===================== BOTTOM ACTION BUTTONS ===================== -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <button type="button" class="btn-form btn-form-primary btn-simpan">Simpan</button>
        <button type="button" class="btn-form btn-form-success btn-hantar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"></path></svg>
            Hantar
        </button>
    </div>
@endsection

@push('modals')
    <!-- ===================== MODAL: SUCCESS ===================== -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#E6F7F3" />
                        <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7" />
                    </svg>
                </div>
                <h5 class="fw-bold mb-2">Berjaya</h5>
                <p class="text-muted mb-4">Maklumat telah berjaya disimpan.</p>
                <button type="button" class="btn-form btn-form-primary mx-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: SENARAI SEMAK STANDARD ===================== -->
    <div class="modal fade" id="senaraiSemakStandard" tabindex="-1" aria-labelledby="senaraiSemakStandardLabel"
        aria-hidden="true">
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
                                {{-- ── Borang Atas Talian items ── --}}
                                <tr data-type="borang_atas_talian" data-route="{{ route('pgmnKerjaForm') }}" data-tajuk="Senarai Pengalaman Kerja">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>
                                        Senarai Pengalaman Kerja
                                        <span class="ms-1 d-inline-flex align-items-center" style="font-size:0.6rem;background:#fef9c3;color:#854d0e;border-radius:3px;padding:1px 5px;vertical-align:middle;border:1px solid #fde68a;">Borang Atas Talian</span>
                                    </td>
                                </tr>
                                <tr data-type="borang_atas_talian" data-route="{{ route('kjDlmTanganForm') }}" data-tajuk="Kerja Dalam Tangan">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>
                                        Kerja Dalam Tangan
                                        <span class="ms-1 d-inline-flex align-items-center" style="font-size:0.6rem;background:#fef9c3;color:#854d0e;border-radius:3px;padding:1px 5px;vertical-align:middle;border:1px solid #fde68a;">Borang Atas Talian</span>
                                    </td>
                                </tr>
                                {{-- ── Standard items (Petender / PTJ Muat Naik) ── --}}
                                <tr data-tajuk="Pengalaman Syarikat Dengan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Pengalaman Syarikat Dengan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                </tr>
                                <tr data-tajuk="Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                </tr>
                                <tr data-tajuk="Skop Bekalan Dan Perkhidmatan">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Skop Bekalan Dan Perkhidmatan</td>
                                </tr>
                                <tr data-tajuk="Salinan Borang KWSP A setiap pekerja bagi bulan caruman terakhir">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Salinan Borang KWSP A setiap pekerja bagi bulan caruman terakhir</td>
                                </tr>
                                <tr data-tajuk="Bilangan Kakitangan">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Bilangan Kakitangan</td>
                                </tr>
                                <tr data-tajuk="Brosur / Risalah">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Brosur / Risalah</td>
                                </tr>
                                <tr data-tajuk="Surat pengesahan pendaftaran dengan Pertubuhan Keselamatan Sosial (Perkeso)">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Surat pengesahan pendaftaran dengan Pertubuhan Keselamatan Sosial (Perkeso) yang telah dikeluarkan mengikut Akta Keselamatan Sosial Pekerja 1969. Jadual Caruman Bulanan (Borang 8A) dan Resit Bayaran Caruman yang terbaru</td>
                                </tr>
                                <tr data-tajuk="Cadangan Bertulis">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Cadangan Bertulis</td>
                                </tr>
                                <tr data-tajuk="Lesen Premis oleh PBT">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Lesen Premis oleh PBT</td>
                                </tr>
                                <tr data-tajuk="Jadual Pelaksanaan">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Jadual Pelaksanaan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary btn-pilih-standard">Pilih</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL: CIPTA SPESIFIKASI ===================== -->
    <div class="modal fade" id="ciptaSpesifikasi" tabindex="-1" aria-labelledby="ciptaSpesifikasiLabel"
        aria-hidden="true">
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
                            <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Cari
                                Templat</span>
                        </div>
                        <div class="content-card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Klon Spesifikasi Daripada <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex flex-column gap-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="klonSpesifikasi"
                                                id="klonSpesifikasi1" checked>
                                            <label class="form-check-label" for="klonSpesifikasi1">Templat Standard /
                                                Kosong</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="klonSpesifikasi"
                                                id="klonSpesifikasi2">
                                            <label class="form-check-label" for="klonSpesifikasi2">Sebut Harga / Tender
                                                Yang Lepas</label>
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
                                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn-form btn-form-primary">Cari</button>
                            </div>
                        </div>
                    </div>

                    <!-- Template Results Table -->
                    <div class="content-card p-0">
                        <div class="content-card-header px-4 py-3 border-bottom">
                            <span class="fw-bold text-dark text-uppercase small"
                                style="letter-spacing: 0.5px;">Templat</span>
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
                                                <a href="{{ route('spesifikasiForm') }}"
                                                    class="btn btn-sm btn-primary text-white">Cipta</a>
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
    <script src="{{ asset('js/components/file-upload.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // ─── CHECKBOX: Senarai Semak Teknikal ────────────────────────────────────
            $('#tbl-teknikal').on('change', '.check-all-teknikal', function() {
                $('#tbl-teknikal .row-check-teknikal').prop('checked', $(this).prop('checked'));
            });

            $('#tbl-teknikal').on('change', '.row-check-teknikal', function() {
                var total = $('#tbl-teknikal .row-check-teknikal').length;
                var checked = $('#tbl-teknikal .row-check-teknikal:checked').length;
                $('#tbl-teknikal .check-all-teknikal').prop('checked', total === checked);
            });

            // ─── HELPERS ──────────────────────────────────────────────────────────────

            // Returns HTML for the Tindakan Pembekal cell based on mekanisma
            function buildTindakanPembekalCell(mekanisma) {
                if (mekanisma === 'ptj_muat_naik') {
                    return '<select name="tindakan_pembekal[]" class="form-select form-select-sm tindakan-pembekal-select" style="font-size:0.78rem;">' +
                        '<option value="muat_turun">Muat Turun</option>' +
                        '<option value="muat_turun_naik">Muat Turun &amp; Muat Naik</option>' +
                        '</select>';
                }
                // petender_muat_naik
                return '<span class="small">Muat Naik</span>';
            }

            // Upload button HTML — reused when showing/hiding for PTJ Muat Turun & Muat Naik
            var PTJ_UPLOAD_BTN =
                '<label class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-1 mb-0 btn-ptj-upload" style="width:30px;height:30px;cursor:pointer;" title="Muat Naik">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>' +
                '<input type="file" name="dokumen_ptj[]" hidden>' +
                '</label>';

            // Returns HTML for the Tindakan (action) cell based on mekanisma.
            // PTJ Muat Naik always starts with an upload button regardless of tindakan pembekal selection.
            function buildTindakanCell(mekanisma) {
                if (mekanisma === 'ptj_muat_naik') {
                    return PTJ_UPLOAD_BTN;
                }
                return '<span class="text-muted small">—</span>';
            }

            // Returns HTML for the Dokumen cell based on mekanisma.
            // PTJ Muat Naik starts empty — uploaded files are appended dynamically via the upload handler.
            function buildDokumenCell(mekanisma) {
                if (mekanisma === 'ptj_muat_naik') {
                    return '<div class="dokumen-ptj-list d-flex flex-column gap-1 align-items-start" style="min-width:130px;max-height:72px;overflow-y:auto;"></div>';
                }
                return '<span class="text-muted small">—</span>';
            }

            function buildNewRow() {
                var defaultMekanisma = 'petender_muat_naik';
                return $(
                    '<tr class="row-teknikal-tambah">' +
                    '<td class="text-center"><input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal"></td>' +
                    '<td><input type="text" name="tajuk_dokumen[]" class="form-control form-control-sm" placeholder="Tajuk / Dokumen..."></td>' +
                    '<td class="text-center">' +
                    '<select name="mekanisma[]" class="form-select form-select-sm mekanisma-select" style="font-size:0.78rem;">' +
                    '<option value="petender_muat_naik" selected>Petender Muat Naik</option>' +
                    '<option value="ptj_muat_naik">PTJ Muat Naik</option>' +
                    '</select>' +
                    '</td>' +
                    '<td class="text-center tindakan-pembekal">' + buildTindakanPembekalCell(defaultMekanisma) + '</td>' +
                    '<td class="text-center">' +
                    '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="0" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status badge-status-warning">Draf</span></td>' +
                    '<td class="text-center rujukan-cell">' + buildDokumenCell(defaultMekanisma) + '</td>' +
                    '<td class="text-center tindakan-cell">' + buildTindakanCell(defaultMekanisma) + '</td>' +
                    '</tr>'
                );
            }

            function updateSkemaMaksima() {
                var total = 0;
                $('#tbl-teknikal .skema-input').each(function() {
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
            $('.btn-tambah-teknikal').on('click', function() {
                $('#tbl-teknikal tbody').append(buildNewRow());
                syncTableEmpty();
                updateSkemaMaksima();
            });

            // ─── HAPUS ROW (CHECKED ONLY) ─────────────────────────────────────────────
            $('.btn-hapus-teknikal').on('click', function() {
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
            $('#tbl-teknikal').on('change', '.mekanisma-select', function() {
                var val  = $(this).val();
                var $row = $(this).closest('tr');
                $row.find('.tindakan-pembekal').html(buildTindakanPembekalCell(val));
                $row.find('.rujukan-cell').html(buildDokumenCell(val));
                $row.find('.tindakan-cell').html(buildTindakanCell(val));
            });

            // ─── TINDAKAN PEMBEKAL DROPDOWN CHANGE (PTJ only) ────────────────────────
            // Both options allow uploading — always keep the upload button visible.
            $('#tbl-teknikal').on('change', '.tindakan-pembekal-select', function() {
                $(this).closest('tr').find('.tindakan-cell').html(PTJ_UPLOAD_BTN);
            });

            // ─── SKEMA INPUT CHANGE ───────────────────────────────────────────────────
            $('#tbl-teknikal').on('input change', '.skema-input', function() {
                updateSkemaMaksima();
            });

            // ─── PTJ MUAT NAIK: File upload → append to Dokumen column, hide upload btn ─
            $('#tbl-teknikal').on('change', '.tindakan-cell input[type="file"]', function() {
                if (!this.files || !this.files[0]) return;
                var file  = this.files[0];
                var url   = URL.createObjectURL(file);
                var $row  = $(this).closest('tr');

                // Append our uploaded file entry (with delete) to the dokumen list
                var $entry = $(
                    '<div class="d-flex align-items-center gap-1 dokumen-ptj-ours">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>' +
                    '<a href="' + url + '" target="_blank" class="small fw-semibold text-truncate d-inline-block" style="max-width:85px;" title="' + file.name + '">' + file.name + '</a>' +
                    '<button type="button" class="btn-hapus-ptj-file d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:16px;height:16px;background:#fee2e2;border:none;border-radius:3px;padding:0;cursor:pointer;" title="Buang">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                    '</div>'
                );
                $row.find('.rujukan-cell .dokumen-ptj-list').append($entry);
                // Reset file input so same file can be re-selected if needed
                $(this).val('');
            });

            // ─── PTJ MUAT NAIK: Remove an uploaded file entry ────────────────────────
            $('#tbl-teknikal').on('click', '.btn-hapus-ptj-file', function() {
                $(this).closest('.dokumen-ptj-ours').remove();
            });

            // ─── ROW BUILDERS for Senarai Semak Standard ─────────────────────────────

            // Borang Atas Talian row: fixed mekanisma text, fixed tindakan pembekal, edit icon
            function buildBorangAtasTalianRow(tajuk, routeUrl) {
                return $(
                    '<tr>' +
                    '<td class="text-center"><input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal"></td>' +
                    '<td><span class="small fw-semibold">' + $('<span>').text(tajuk).html() + '</span></td>' +
                    '<td class="text-center"><span class="small fw-semibold text-muted">Borang Atas Talian</span></td>' +
                    '<td class="text-center small">Kunci Masuk</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="0" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status badge-status-warning">Draf</span></td>' +
                    '<td class="text-center text-muted small">—</td>' +
                    '<td class="text-center">' +
                        '<a href="' + routeUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                        '</a>' +
                    '</td>' +
                    '</tr>'
                );
            }

            // Standard row: fixed tajuk text, Petender/PTJ mekanisma dropdown (same logic as buildNewRow)
            function buildStandardRow(tajuk) {
                var defaultMekanisma = 'petender_muat_naik';
                return $(
                    '<tr class="row-teknikal-tambah">' +
                    '<td class="text-center"><input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal"></td>' +
                    '<td><span class="small fw-semibold">' + $('<span>').text(tajuk).html() + '</span></td>' +
                    '<td class="text-center">' +
                        '<select name="mekanisma[]" class="form-select form-select-sm mekanisma-select" style="font-size:0.78rem;">' +
                        '<option value="petender_muat_naik" selected>Petender Muat Naik</option>' +
                        '<option value="ptj_muat_naik">PTJ Muat Naik</option>' +
                        '</select>' +
                    '</td>' +
                    '<td class="text-center tindakan-pembekal">' + buildTindakanPembekalCell(defaultMekanisma) + '</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="0" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status badge-status-warning">Draf</span></td>' +
                    '<td class="text-center rujukan-cell">' + buildDokumenCell(defaultMekanisma) + '</td>' +
                    '<td class="text-center tindakan-cell">' + buildTindakanCell(defaultMekanisma) + '</td>' +
                    '</tr>'
                );
            }

            // ─── CHECKBOX: Modal Senarai Semak Standard ───────────────────────────────
            $('#senaraiSemakStandard').on('change', '.check-all-standard', function() {
                $('#tbl-standard .row-check-standard').prop('checked', $(this).prop('checked'));
            });

            $('#senaraiSemakStandard').on('change', '.row-check-standard', function() {
                var total = $('#tbl-standard .row-check-standard').length;
                var checked = $('#tbl-standard .row-check-standard:checked').length;
                $('#tbl-standard .check-all-standard').prop('checked', total === checked);
            });

            // ─── PILIH: Add selected standard items to main table ─────────────────────
            $('#senaraiSemakStandard').on('click', '.btn-pilih-standard', function() {
                var $checked = $('#tbl-standard .row-check-standard:checked');
                if ($checked.length === 0) {
                    alert('Sila pilih sekurang-kurangnya satu senarai semak.');
                    return;
                }

                $checked.each(function() {
                    var $tr    = $(this).closest('tr');
                    var type   = $tr.data('type');
                    var tajuk  = $tr.data('tajuk') || $tr.find('td:last-child').text().trim();

                    if (type === 'borang_atas_talian') {
                        $('#tbl-teknikal tbody').append(buildBorangAtasTalianRow(tajuk, $tr.data('route')));
                    } else {
                        $('#tbl-teknikal tbody').append(buildStandardRow(tajuk));
                    }
                    $tr.hide(); // hide from list once added — prevents duplicate selection
                });

                updateSkemaMaksima();
                syncTableEmpty();

                // Reset checkboxes and close modal
                $('#tbl-standard .row-check-standard, #tbl-standard .check-all-standard').prop('checked', false);
                bootstrap.Modal.getInstance($('#senaraiSemakStandard')[0]).hide();
            });


            // ─── DOKUMEN SOKONGAN: Upload zone + file chips ──────────────────────────
            FileUpload.init({
                zoneId     : 'upload-zone-sokongan',
                inputId    : 'input-dokumen-sokongan',
                chipListId : 'file-chip-list-sokongan'
            });

            // ─── SUCCESS MODAL: Simpan & Hantar ──────────────────────────────────────
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));

            $('.btn-simpan').on('click', function() {
                successModal.show();
            });

            $('.btn-hantar').on('click', function() {
                successModal.show();
            });

        });
    </script>
@endsection
