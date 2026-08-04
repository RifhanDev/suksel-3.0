@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        #page-loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }
        #page-loading-overlay.active { display: flex; }
        #page-loading-overlay .overlay-spinner {
            width: 48px; height: 48px;
            border: 4px solid rgba(255,255,255,0.25);
            border-top-color: #fff;
            border-radius: 50%;
            animation: overlay-spin 0.75s linear infinite;
        }
        #page-loading-overlay .overlay-label {
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        @keyframes overlay-spin { to { transform: rotate(360deg); } }
    </style>
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
                    {{ $tender->name ?? '-' }}
                    @if (!empty($tender->kategori_perolehan_name))
                        <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">({{ $tender->kategori_perolehan_name }})</span>
                    @endif
                </h5>
            </div>

            <!-- Metadata: No. Tender · PTJ · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $tender->no_tender ?: ($tender->ref_number ?? '-') }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ optional($tender->tenderer)->name ?? '-' }}</span>
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
                        <tr id="tbl-legacy-demo-row" class="d-none" aria-hidden="true">
                            <td class="text-center">
                                <input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal">
                            </td>
                            <td></td>
                            <td class="text-center"><span class="small fw-semibold text-muted">Spesifikasi</span></td>
                            <td class="text-center small">Kunci Masuk</td>
                            <td class="text-center">
                                <input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="0" min="0" style="max-width:90px;margin:0 auto;">
                            </td>
                            <td class="text-center"><span class="badge-status badge-status-success">Selesai</span></td>
                            <td class="text-center text-muted small rujukan-cell">—</td>
                            <td class="text-center">
                                <a href="{{ route('spesifikasiForm', ['tenderUuid' => $tender->uuid]) }}"
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
                        <tr id="tbl-empty-row">
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
                                    class="form-control form-control-sm text-center fw-bold" value="0" readonly
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
                            <input type="number" id="input-penilaian" name="input_penilaian" class="form-control form-control-sm text-center fw-semibold" style="max-width: 100px; font-size: 1rem;" placeholder="0" min="0">
                            <span class="text-muted small">daripada</span>
                            <span class="fw-bold text-dark" id="penilaian-teknikal-total" style="font-size: 1rem;">0</span>
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
                                style="font-size: 1.75rem; line-height: 1;">0</span>
                            <span class="fw-semibold text-primary" style="font-size: 1rem;">%</span>
                            {{-- <span class="text-muted small ms-1">peratus</span> --}}
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

    <!-- ===================== FULL-PAGE LOADING OVERLAY ===================== -->
    <div id="page-loading-overlay" role="status" aria-live="polite">
        <div class="overlay-spinner"></div>
        <span class="overlay-label" id="overlay-label-text">Menyimpan...</span>
    </div>

    <!-- ===================== BOTTOM ACTION BUTTONS ===================== -->
    <form id="form-senarai-teknikal" action="{{ route('senaraiTeknikal.store', $tender->uuid) }}" method="POST">
    @csrf
        <!-- Hidden: tahap lulus value for submission -->
        <input type="hidden" name="tahap_lulus" id="tahap-lulus-hidden" value="0">

        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="button" class="btn-form btn-form-primary btn-simpan">Simpan</button>
            <button type="submit" class="btn-form btn-form-success btn-hantar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"></path></svg>
                Hantar
            </button>
        </div>
    </form>
@endsection

@push('modals')
    <!-- ===================== MODAL: SUCCESS ===================== -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#E6F7F3" />
                        <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7" />
                    </svg>
                </div>
                <h5 class="fw-bold mb-2" id="success-modal-title">Berjaya</h5>
                <p class="text-muted mb-4" id="success-modal-message">Maklumat telah berjaya disimpan.</p>
                <button type="button" class="btn-form btn-form-primary mx-auto" id="success-modal-close" data-bs-dismiss="modal">Tutup</button>
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
                                @forelse ($standardItems as $item)
                                <tr data-uuid="{{ $item['uuid'] }}" data-type="{{ $item['type'] }}" data-tajuk="{{ $item['title'] }}" data-action-url="{{ $item['action_url'] ?? '' }}">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>
                                        {{ $item['title'] }}
                                        @if ($item['type'] === 'borang_atas_talian')
                                            <span class="ms-1 d-inline-flex align-items-center" style="font-size:0.6rem;background:#fef9c3;color:#854d0e;border-radius:3px;padding:1px 5px;vertical-align:middle;border:1px solid #fde68a;">Borang Atas Talian</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Tiada senarai semak standard dijumpai.</td>
                                </tr>
                                @endforelse
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
                                                id="klonSpesifikasi1" value="standard" checked>
                                            <label class="form-check-label" for="klonSpesifikasi1">Templat Standard /
                                                Kosong</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="klonSpesifikasi"
                                                id="klonSpesifikasi2" value="previous">
                                            <label class="form-check-label" for="klonSpesifikasi2">Sebut Harga / Tender
                                                Yang Lepas</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-none" id="cipta-spesifikasi-jenis-item-wrapper">
                                    <label class="form-label fw-semibold small">Jenis Item</label>
                                    <select id="cipta-spesifikasi-jenis-item" class="form-select form-select-sm">
                                        <option value="">Sila pilih...</option>
                                        <option value="bekalan">Bekalan</option>
                                        <option value="perkhidmatan">Perkhidmatan</option>
                                        <option value="kerja">Kerja</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-3 d-none" id="cipta-spesifikasi-search-actions">
                                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="button" id="btn-cari-spesifikasi" class="btn-form btn-form-primary">Cari</button>
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
                                                <a href="{{ route('spesifikasiForm', ['tenderUuid' => $tender->uuid]) }}"
                                                    class="btn btn-sm btn-primary text-white">Cipta</a>
                                            </td>
                                        </tr>
                                        <tr id="template-loading-row" class="d-none">
                                            <td colspan="5" class="text-center py-3 text-muted small">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Mencari templat...
                                            </td>
                                        </tr>
                                        <tr id="template-empty-row" class="d-none">
                                            <td colspan="5" class="text-center text-muted py-3 small">Tiada templat dijumpai untuk jenis item yang dipilih.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="template-pagination" class="px-2 pt-2 pb-1"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-end">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('scripts')
    <script src="{{ asset('js/components/file-upload.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var SPEC_INDEX_URL   = @json(route('spesifikasiTeknikal.index'));
            var SPEC_FORM_BASE   = @json(route('spesifikasiForm', ['tenderUuid' => $tender->uuid]));
            var CURRENT_TENDER_UUID = @json($tender->uuid);
            var PENDING_SPEC_STORAGE_KEY = 'technical-checklist:pending-spec:' + CURRENT_TENDER_UUID;
            var STORE_URL       = @json(route('senaraiTeknikal.store', $tender->uuid));
            var SUBMIT_URL      = @json(route('senaraiTeknikal.submit', $tender->uuid));
            var UPLOAD_FILE_URL = @json(route('senaraiTeknikal.uploadFile', $tender->uuid));
            var DELETE_FILE_URL = @json(route('senaraiTeknikal.deleteFile', ':uuid'));
            var CSRF_TOKEN      = @json(csrf_token());
            var EXISTING_CHECKLIST = @json($checklistData);
            var STANDARD_ACTION_URLS = @json(collect($standardItems ?? [])->pluck('action_url', 'uuid')->all());

            function toggleCiptaSpesifikasiSearchControls() {
                var selectedSource = $('input[name="klonSpesifikasi"]:checked').val();
                var shouldShowSearchControls = selectedSource === 'previous';

                $('#cipta-spesifikasi-jenis-item-wrapper').toggleClass('d-none', !shouldShowSearchControls);
                $('#cipta-spesifikasi-search-actions').toggleClass('d-none', !shouldShowSearchControls);
                $('#ciptaSpesifikasi .template-row').toggleClass('d-none', shouldShowSearchControls);

                if (!shouldShowSearchControls) {
                    // Clear search results when switching back to standard
                    resetTemplateResults();
                    $('#cipta-spesifikasi-jenis-item').val('');
                }
            }

            $('input[name="klonSpesifikasi"]').on('change', toggleCiptaSpesifikasiSearchControls);
            $('#ciptaSpesifikasi').on('shown.bs.modal', toggleCiptaSpesifikasiSearchControls);

            // Reset state when modal is closed
            $('#ciptaSpesifikasi').on('hidden.bs.modal', function() {
                $('#klonSpesifikasi1').prop('checked', true);
                toggleCiptaSpesifikasiSearchControls();
            });

            toggleCiptaSpesifikasiSearchControls();

            // ─── CARI: Search past specifications — with pagination ───────────────────

            var templateAllSpecs   = [];
            var templateCurrentPage = 1;
            var TEMPLATE_PAGE_SIZE  = 10;

            function buildTemplateRow(spec) {
                var title   = $('<span>').text(spec.title || '-').html();
                var itype   = $('<span>').text(spec.item_type || '-').html();
                var score   = (spec.total_score !== null && spec.total_score !== undefined) ? spec.total_score : '-';
                var creator = $('<span>').text(spec.created_by || '-').html();
                var documentUrl = SPEC_FORM_BASE + '/' + encodeURIComponent(spec.uuid);
                var $row = $(
                    '<tr class="template-result-row">' +
                    '<td>' + title + '</td>' +
                    '<td class="text-center">' + score + '</td>' +
                    '<td class="text-center">' + itype + '</td>' +
                    '<td class="text-center">' + creator + '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-outline-primary btn-tambah-spesifikasi-templat" style="font-size:0.78rem;">Tambah</button>' +
                    '</td>' +
                    '</tr>'
                );
                $row.data('spec', {
                    uuid: spec.uuid,
                    title: spec.title || '',
                    total_score: spec.total_score,
                    status: spec.status || 'draft',
                    item_type: spec.item_type || '',
                    document_url: documentUrl
                });
                return $row;
            }

            function renderTemplatePage(page) {
                templateCurrentPage = page;
                $('#templateTable tbody .template-result-row').remove();

                var start    = (page - 1) * TEMPLATE_PAGE_SIZE;
                var pageData = templateAllSpecs.slice(start, start + TEMPLATE_PAGE_SIZE);
                pageData.forEach(function(spec) {
                    $('#templateTable tbody').append(buildTemplateRow(spec));
                });

                renderTemplatePagination(templateAllSpecs.length, page);
            }

            function renderTemplatePagination(total, page) {
                var totalPages = Math.ceil(total / TEMPLATE_PAGE_SIZE);
                var $container = $('#template-pagination').empty();
                if (totalPages <= 1) return;

                var $ul = $('<ul class="pagination pagination-sm justify-content-center mb-0">');

                var $prev = $('<li class="page-item">').toggleClass('disabled', page === 1);
                $prev.append($('<a class="page-link" href="#">&laquo;</a>').data('tpl-page', page - 1));
                $ul.append($prev);

                var from = Math.max(1, page - 2);
                var to   = Math.min(totalPages, from + 4);
                from = Math.max(1, to - 4);

                if (from > 1) $ul.append('<li class="page-item disabled"><span class="page-link">…</span></li>');

                for (var i = from; i <= to; i++) {
                    var $li = $('<li class="page-item">').toggleClass('active', i === page);
                    $li.append($('<a class="page-link" href="#">' + i + '</a>').data('tpl-page', i));
                    $ul.append($li);
                }

                if (to < totalPages) $ul.append('<li class="page-item disabled"><span class="page-link">…</span></li>');

                var $next = $('<li class="page-item">').toggleClass('disabled', page === totalPages);
                $next.append($('<a class="page-link" href="#">&raquo;</a>').data('tpl-page', page + 1));
                $ul.append($next);

                $container.append($('<nav aria-label="Navigasi templat">').append($ul));
            }

            function resetTemplateResults() {
                templateAllSpecs = [];
                templateCurrentPage = 1;
                $('#templateTable tbody .template-result-row').remove();
                $('#template-loading-row, #template-empty-row').addClass('d-none');
                $('#template-pagination').empty();
            }

            $('#template-pagination').on('click', 'a.page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('tpl-page');
                var maxPage = Math.ceil(templateAllSpecs.length / TEMPLATE_PAGE_SIZE);
                if (!page || page < 1 || page > maxPage) return;
                renderTemplatePage(page);
                $('#templateTable').closest('.table-responsive')[0].scrollTop = 0;
            });

            $('#btn-cari-spesifikasi').on('click', function() {
                var jenisItem = $('#cipta-spesifikasi-jenis-item').val();
                if (!jenisItem) {
                    $('#cipta-spesifikasi-jenis-item').addClass('is-invalid').focus();
                    return;
                }
                $('#cipta-spesifikasi-jenis-item').removeClass('is-invalid');

                resetTemplateResults();
                $('#template-loading-row').removeClass('d-none');

                $.ajax({
                    url: SPEC_INDEX_URL,
                    method: 'GET',
                    data: { item_type: jenisItem, tender_uuid: CURRENT_TENDER_UUID },
                    success: function(response) {
                        $('#template-loading-row').addClass('d-none');
                        var specs = response.data || [];
                        if (!specs.length) {
                            $('#template-empty-row').removeClass('d-none');
                            return;
                        }
                        specs.sort(function(a, b) {
                            return (a.title || '').localeCompare(b.title || '', 'ms', { sensitivity: 'base', numeric: true });
                        });
                        templateAllSpecs = specs;
                        renderTemplatePage(1);
                    },
                    error: function(xhr) {
                        $('#template-loading-row').addClass('d-none');
                        var msg = 'Ralat semasa mencari. Sila cuba semula.';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        $('#template-empty-row').removeClass('d-none').find('td').text(msg);
                    }
                });
            });

            // ─── CHECKBOX: Senarai Semak Teknikal ────────────────────────────────────
            $('#tbl-teknikal').on('change', '.check-all-teknikal', function() {
                $('#tbl-teknikal tbody tr:visible .row-check-teknikal').prop('checked', $(this).prop('checked'));
            });

            $('#tbl-teknikal').on('change', '.row-check-teknikal', function() {
                var total = $('#tbl-teknikal tbody tr:visible .row-check-teknikal').length;
                var checked = $('#tbl-teknikal tbody tr:visible .row-check-teknikal:checked').length;
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
                    '<tr class="row-teknikal-tambah" data-source-type="manual">' +
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

            function buildChecklistStatusBadge(status) {
                var normalizedStatus = (status || 'draft').toString().toLowerCase();
                var label = normalizedStatus === 'completed'
                    ? 'Selesai'
                    : (normalizedStatus === 'submitted' ? 'Dihantar' : 'Draf');
                var badgeClass = normalizedStatus === 'completed'
                    ? 'badge-status-success'
                    : 'badge-status-warning';

                return '<span class="badge-status ' + badgeClass + '">' + label + '</span>';
            }

            function buildSpecificationDocumentRow(spec) {
                var title = $('<span>').text(spec.title || '-').html();
                var score = parseFloat(spec.total_score);
                var resolvedScore = isNaN(score) ? 0 : score;
                var documentUrl = spec.document_url || (SPEC_FORM_BASE + '/' + encodeURIComponent(spec.uuid));

                return $(
                    '<tr class="row-teknikal-spesifikasi" data-source-type="specification_document" data-specification-uuid="' + $('<span>').text(spec.uuid || '').html() + '">' +
                    '<td class="text-center"><input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal" form="form-senarai-teknikal"></td>' +
                    '<td>' +
                        '<span class="small fw-semibold">' + title + '</span>' +
                        '<input type="hidden" name="source_type[]" value="specification_document" form="form-senarai-teknikal">' +
                        '<input type="hidden" name="specification_document_uuid[]" value="' + $('<span>').text(spec.uuid || '').html() + '" form="form-senarai-teknikal">' +
                        '<input type="hidden" name="tajuk_dokumen[]" value="' + $('<span>').text(spec.title || '').html() + '" form="form-senarai-teknikal">' +
                        '<input type="hidden" name="mekanisma[]" value="spesifikasi" form="form-senarai-teknikal">' +
                    '</td>' +
                    '<td class="text-center"><span class="small fw-semibold text-muted">Spesifikasi</span></td>' +
                    '<td class="text-center small">Kunci Masuk</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="' + resolvedScore + '" min="0" style="max-width:90px;margin:0 auto;" form="form-senarai-teknikal">' +
                    '</td>' +
                    '<td class="text-center">' + buildChecklistStatusBadge(spec.status) + '</td>' +
                    '<td class="text-center text-muted small rujukan-cell">—</td>' +
                    '<td class="text-center">' +
                        '<a href="' + documentUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini Spesifikasi">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>' +
                        '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>' +
                        '</svg>' +
                        '</a>' +
                    '</td>' +
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

                // max attribute for input-penilaian number
                $('#input-penilaian').attr('max', total);
                var currentVal = parseInt($('#input-penilaian').val()) || 0;
                $('#input-penilaian').toggleClass('is-invalid', total > 0 && currentVal > total);
                updateTahapLulus();
            }

            function updateTahapLulus() {
                var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
                var penilaian    = parseInt($('#input-penilaian').val()) || 0;
                var pct = skemaMaksima > 0
                    ? Math.round((penilaian / skemaMaksima) * 100 * 100) / 100
                    : 0;
                var display = (pct % 1 === 0) ? pct : pct.toFixed(2);
                $('#tahap-lulus').text(display);
                $('#tahap-lulus-hidden').val(pct);
            }

            // ─── PENILAIAN INPUT: validate + recalculate + auto-save ─────────────────
            $('#input-penilaian').on('input change', function() {
                var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
                var val = parseInt($(this).val()) || 0;
                $(this).toggleClass('is-invalid', skemaMaksima > 0 && val > skemaMaksima);
                updateTahapLulus();
                autoSave();
            });

            function syncTableEmpty() {
                var realRows = $('#tbl-teknikal tbody tr:not(#tbl-empty-row):visible').length;
                if (realRows === 0) {
                    $('#tbl-empty-row').removeClass('d-none');
                } else {
                    $('#tbl-empty-row').addClass('d-none');
                }
            }

            function upsertSpecificationDocumentRow(spec) {
                if (!spec || !spec.uuid) {
                    return;
                }

                var $newRow = buildSpecificationDocumentRow(spec);
                var $existingRow = $('#tbl-teknikal tbody tr[data-specification-uuid="' + spec.uuid + '"]');

                if ($existingRow.length) {
                    $existingRow.first().replaceWith($newRow);
                } else {
                    $('#tbl-teknikal tbody').append($newRow);
                }

                syncTableEmpty();
                updateSkemaMaksima();
            }

            var autoSaveTimer = null;

            function normalizeActionSlug(slug) {
                return (slug || '').toString().trim().replace(/^\/+|\/+$/g, '');
            }

            function getLatestStandardActionSlug(standardItemUuid, fallbackSlug) {
                if (standardItemUuid && Object.prototype.hasOwnProperty.call(STANDARD_ACTION_URLS, standardItemUuid)) {
                    return normalizeActionSlug(STANDARD_ACTION_URLS[standardItemUuid]);
                }

                return normalizeActionSlug(fallbackSlug);
            }

            function buildBorangAtasTalianActionCell(routeUrl) {
                if (!routeUrl) {
                    return '<span class="text-muted small">—</span>';
                }

                return '' +
                    '<a href="' + routeUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                    '</a>';
            }

            function collectItems() {
                var items = [];
                var idx   = 0;
                $('#tbl-teknikal tbody tr').each(function() {
                    var $tr = $(this);
                    if ($tr.attr('id') === 'tbl-empty-row' || $tr.attr('id') === 'tbl-legacy-demo-row') return;

                    var sourceType = $tr.data('source-type') || $tr.find('input[name="source_type[]"]').val() || 'manual';

                    var title;
                    var $tajukText = $tr.find('input[name="tajuk_dokumen[]"]:not([type="hidden"])');
                    if ($tajukText.length) {
                        title = $.trim($tajukText.val());
                    } else {
                        var $tajukHidden = $tr.find('input[name="tajuk_dokumen[]"][type="hidden"]');
                        title = $tajukHidden.length
                            ? $.trim($tajukHidden.val())
                            : $.trim($tr.find('td:nth-child(2) .fw-semibold').text());
                    }
                    if (!title) return;

                    var mechanism;
                    var $mekSelect = $tr.find('select.mekanisma-select');
                    if ($mekSelect.length) {
                        mechanism = $mekSelect.val();
                    } else if ($tr.find('input[name="mekanisma[]"]').length) {
                        mechanism = $tr.find('input[name="mekanisma[]"]').val();
                    } else {
                        mechanism = sourceType === 'borang_atas_talian' ? 'borang_atas_talian' : 'petender_muat_naik';
                    }

                    var vendorAction;
                    if (sourceType === 'borang_atas_talian') {
                        vendorAction = normalizeActionSlug($tr.data('action-url-slug')) || null;
                    } else if (mechanism === 'ptj_muat_naik') {
                        vendorAction = $tr.find('.tindakan-pembekal select').val() || 'muat_turun';
                    } else {
                        vendorAction = 'muat_naik';
                    }

                    items.push({
                        uuid:                        $tr.data('row-uuid') || null,
                        source_type:                 sourceType,
                        title:                       title,
                        mechanism:                   mechanism,
                        vendor_action:               vendorAction,
                        score:                       parseFloat($tr.find('.skema-input').val()) || 0,
                        sort_order:                  idx,
                        standard_item_uuid:          $tr.data('standard-item-uuid') || null,
                        specification_document_uuid: $tr.find('input[name="specification_document_uuid[]"]').val() || null,
                    });
                    idx++;
                });
                return items;
            }

            function updateRowUuids(savedItems) {
                var $rows = $('#tbl-teknikal tbody tr').filter(function() {
                    var id = $(this).attr('id');
                    return id !== 'tbl-empty-row' && id !== 'tbl-legacy-demo-row';
                });
                (savedItems || []).forEach(function(item, index) {
                    if (!item.uuid) return;
                    var $row = $rows.eq(index);
                    if ($row.length) {
                        $row.data('row-uuid', item.uuid).attr('data-row-uuid', item.uuid);
                    }
                });
            }

            function doSave(onSuccess, onError) {
                var items     = collectItems();
                var maxScore  = parseFloat($('#skema-maksima-display').val()) || 0;
                var penilaian = parseFloat($('#input-penilaian').val()) || 0;
                var pct       = maxScore > 0 ? Math.round((penilaian / maxScore) * 100 * 100) / 100 : 0;

                $.ajax({
                    url:         STORE_URL,
                    method:      'POST',
                    headers:     { 'X-CSRF-TOKEN': CSRF_TOKEN },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        items:              items,
                        max_score:          maxScore,
                        passing_score:      penilaian,
                        passing_percentage: pct,
                    }),
                    success: function(response) {
                        if (response.data && response.data.items) {
                            updateRowUuids(response.data.items);
                        }
                        if (typeof onSuccess === 'function') onSuccess(response);
                    },
                    error: function(xhr) {
                        console.warn('Checklist auto-save failed', xhr.status, xhr.responseJSON);
                        if (typeof onError === 'function') onError(xhr);
                    }
                });
            }

            function autoSave(immediate) {
                if (autoSaveTimer) clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() { doSave(); }, immediate ? 0 : 1000);
            }

            function populateTable(data) {
                if (!data || !data.items || !data.items.length) return;

                data.items.slice().sort(function(a, b) {
                    return (a.sort_order || 0) - (b.sort_order || 0);
                }).forEach(function(item) {
                    var $row;
                    var mech = item.mechanism || 'petender_muat_naik';

                    if (item.source_type === 'specification_document') {
                        $row = buildSpecificationDocumentRow({
                            uuid:        item.specification_document_uuid || '',
                            title:       item.title || '',
                            total_score: item.score,
                            status:      item.status || 'draft',
                        });
                    } else if (item.source_type === 'borang_atas_talian') {
                        $row = buildBorangAtasTalianRow(
                            item.title || '',
                            getLatestStandardActionSlug(item.standard_item_uuid || null, item.vendor_action || ''),
                            item.standard_item_uuid || null
                        );
                        $row.find('.skema-input').val(item.score || 0);
                    } else {
                        if (item.source_type === 'standard') {
                            $row = buildStandardRow(item.title || '', item.standard_item_uuid || null);
                        } else {
                            $row = buildNewRow();
                            $row.find('input[name="tajuk_dokumen[]"]').val(item.title || '');
                        }
                        if (mech !== 'petender_muat_naik') {
                            $row.find('.mekanisma-select').val(mech);
                            $row.find('.tindakan-pembekal').html(buildTindakanPembekalCell(mech));
                            $row.find('.rujukan-cell').html(buildDokumenCell(mech));
                            $row.find('.tindakan-cell').html(buildTindakanCell(mech));
                            if (mech === 'ptj_muat_naik' && item.vendor_action) {
                                $row.find('.tindakan-pembekal select').val(item.vendor_action);
                            }
                        }
                        if (mech === 'ptj_muat_naik' && item.files && item.files.length) {
                            var $list = $row.find('.rujukan-cell .dokumen-ptj-list');
                            item.files.forEach(function(f) {
                                $list.append(buildPtjFileEntry(f.uuid, f.original_name, f.url));
                            });
                        }
                        $row.find('.skema-input').val(item.score || 0);
                    }

                    $row.data('row-uuid', item.uuid).attr('data-row-uuid', item.uuid);
                    $('#tbl-teknikal tbody').append($row);
                });

                if (data.passing_score) {
                    $('#input-penilaian').val(data.passing_score);
                }

                syncTableEmpty();
                updateSkemaMaksima();

                // Hide modal items that are already in the checklist
                $('#tbl-standard tbody tr[data-uuid]').each(function() {
                    var $stdTr = $(this);
                    var uuid   = $stdTr.data('uuid');
                    if (uuid && $('#tbl-teknikal tbody tr[data-standard-item-uuid="' + uuid + '"]').length) {
                        $stdTr.hide();
                    }
                });
            }

            function consumePendingChecklistSpecification() {
                if (!window.sessionStorage) {
                    return;
                }

                var raw = window.sessionStorage.getItem(PENDING_SPEC_STORAGE_KEY);
                if (!raw) {
                    return;
                }

                window.sessionStorage.removeItem(PENDING_SPEC_STORAGE_KEY);

                try {
                    upsertSpecificationDocumentRow(JSON.parse(raw));
                } catch (error) {
                    console.warn('Failed to consume pending checklist specification.', error);
                }
            }

            populateTable(EXISTING_CHECKLIST);

            // Restore Dokumen Sokongan chips from saved header files
            if (EXISTING_CHECKLIST && EXISTING_CHECKLIST.files && EXISTING_CHECKLIST.files.length) {
                EXISTING_CHECKLIST.files.forEach(function(f) {
                    $('#file-chip-list-sokongan').append(buildSokonganChip(f.uuid, f.original_name, f.url, f.size));
                });
            }

            consumePendingChecklistSpecification();

            $('.btn-tambah-teknikal').on('click', function() {
                $('#tbl-teknikal tbody').append(buildNewRow());
                syncTableEmpty();
                updateSkemaMaksima();
            });

            $('.btn-hapus-teknikal').on('click', function() {
                var $checked = $('#tbl-teknikal .row-check-teknikal:checked');
                if ($checked.length === 0) {
                    alert('Sila pilih sekurang-kurangnya satu rekod untuk dihapus.');
                    return;
                }

                $checked.each(function() {
                    var $row = $(this).closest('tr');
                    var standardItemUuid = $row.data('standard-item-uuid') || $row.data('standardItemUuid') || $row.attr('data-standard-item-uuid');
                    if (standardItemUuid) {
                        var $stdTr = $('#tbl-standard tbody tr[data-uuid="' + standardItemUuid + '"]');
                        if ($stdTr.length) {
                            $stdTr.find('.row-check-standard').prop('checked', false);
                            $stdTr.show();
                        }
                    }

                    var specUuid = $row.data('specification-uuid') || $row.attr('data-specification-uuid') || $row.find('input[name="specification_document_uuid[]"]').val();
                    if (specUuid) {
                        $('#templateTable tbody tr').filter(function() {
                            var s = $(this).data('spec');
                            return s && s.uuid === specUuid;
                        }).show();
                    }

                    $row.remove();
                });

                $('#tbl-standard .check-all-standard').prop('checked', false);
                $('#tbl-teknikal .check-all-teknikal').prop('checked', false);
                syncTableEmpty();
                updateSkemaMaksima();
                autoSave(true);
            });

            $('#tbl-teknikal').on('change', '.mekanisma-select', function() {
                var val  = $(this).val();
                var $row = $(this).closest('tr');
                $row.find('.tindakan-pembekal').html(buildTindakanPembekalCell(val));
                $row.find('.rujukan-cell').html(buildDokumenCell(val));
                $row.find('.tindakan-cell').html(buildTindakanCell(val));
                autoSave();
            });

            $('#tbl-teknikal').on('change', '.tindakan-pembekal-select', function() {
                $(this).closest('tr').find('.tindakan-cell').html(PTJ_UPLOAD_BTN);
                autoSave();
            });

            $('#tbl-teknikal').on('input change', '.skema-input', function() {
                updateSkemaMaksima();
                autoSave();
            });

            $('#tbl-teknikal').on('input', 'input[name="tajuk_dokumen[]"]', function() {
                autoSave();
            });

            function buildPtjFileEntry(fileUuid, fileName, fileUrl) {
                var safeName = $('<span>').text(fileName).html();
                return $(
                    '<div class="d-flex align-items-center gap-1 dokumen-ptj-ours" data-file-uuid="' + fileUuid + '">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>' +
                    '<a href="' + fileUrl + '" target="_blank" class="small fw-semibold text-truncate d-inline-block" style="max-width:85px;" title="' + safeName + '">' + safeName + '</a>' +
                    '<button type="button" class="btn-hapus-ptj-file d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:16px;height:16px;background:#fee2e2;border:none;border-radius:3px;padding:0;cursor:pointer;" title="Buang">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                    '</div>'
                );
            }

            $('#tbl-teknikal').on('change', '.tindakan-cell input[type="file"]', function() {
                if (!this.files || !this.files[0]) return;
                var file   = this.files[0];
                var $input = $(this);
                var $row   = $input.closest('tr');
                $input.val('');

                function doUpload(rowUuid) {
                    var fd = new FormData();
                    fd.append('file', file);
                    fd.append('checklist_item_uuid', rowUuid);
                    fd.append('file_type', 'support');
                    fd.append('_token', CSRF_TOKEN);

                    $.ajax({
                        url: UPLOAD_FILE_URL,
                        method: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            var f = res && res.data ? res.data : null;
                            if (!f) return;
                            $row.find('.rujukan-cell .dokumen-ptj-list').append(
                                buildPtjFileEntry(f.uuid, f.original_name, f.url)
                            );
                        },
                        error: function() {
                            alert('Gagal memuat naik fail. Sila cuba lagi.');
                        },
                    });
                }

                var rowUuid = $row.data('row-uuid');
                if (rowUuid) {
                    doUpload(rowUuid);
                } else {
                    doSave(function() {
                        var newUuid = $row.data('row-uuid');
                        if (newUuid) {
                            doUpload(newUuid);
                        } else {
                            alert('Gagal mendapatkan ID baris. Sila simpan semula dan cuba lagi.');
                        }
                    });
                }
            });

            $('#tbl-teknikal').on('click', '.btn-hapus-ptj-file', function() {
                var $entry   = $(this).closest('.dokumen-ptj-ours');
                var fileUuid = $entry.data('file-uuid');

                if (!fileUuid) {
                    $entry.remove();
                    return;
                }

                var url = DELETE_FILE_URL.replace(':uuid', encodeURIComponent(fileUuid));
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                    success: function() { $entry.remove(); },
                    error: function() { alert('Gagal memadam fail. Sila cuba lagi.'); },
                });
            });

            function buildBorangAtasTalianRow(tajuk, slug, standardItemUuid) {
                var normalizedSlug = normalizeActionSlug(slug);
                var routeUrl = normalizedSlug ? '/senarai-teknikal/' + CURRENT_TENDER_UUID + '/' + normalizedSlug : '';
                var stdAttr  = standardItemUuid ? ' data-standard-item-uuid="' + $('<span>').text(standardItemUuid).html() + '"' : '';
                var $row = $(
                    '<tr data-source-type="borang_atas_talian" data-action-url-slug="' + $('<span>').text(normalizedSlug).html() + '"' + stdAttr + '>' +
                    '<td class="text-center"><input type="checkbox" name="row_check_teknikal[]" class="form-check-input row-check-teknikal"></td>' +
                    '<td><span class="small fw-semibold">' + $('<span>').text(tajuk).html() + '</span></td>' +
                    '<td class="text-center"><span class="small fw-semibold text-muted">Borang Atas Talian</span></td>' +
                    '<td class="text-center small">Kunci Masuk</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="0" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status badge-status-warning">Draf</span></td>' +
                    '<td class="text-center text-muted small">—</td>' +
                    '<td class="text-center">' + buildBorangAtasTalianActionCell(routeUrl) + '</td>' +
                    '</tr>'
                );
                if (standardItemUuid) {
                    $row.data('standard-item-uuid', standardItemUuid);
                }
                return $row;
            }

            function buildStandardRow(tajuk, standardItemUuid) {
                var defaultMekanisma = 'petender_muat_naik';
                var stdAttr = standardItemUuid ? ' data-standard-item-uuid="' + $('<span>').text(standardItemUuid).html() + '"' : '';
                var $row = $(
                    '<tr class="row-teknikal-tambah" data-source-type="standard"' + stdAttr + '>' +
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
                if (standardItemUuid) {
                    $row.data('standard-item-uuid', standardItemUuid);
                }
                return $row;
            }

            $('#senaraiSemakStandard').on('change', '.check-all-standard', function() {
                $('#tbl-standard .row-check-standard').prop('checked', $(this).prop('checked'));
            });

            $('#senaraiSemakStandard').on('change', '.row-check-standard', function() {
                var total = $('#tbl-standard .row-check-standard').length;
                var checked = $('#tbl-standard .row-check-standard:checked').length;
                $('#tbl-standard .check-all-standard').prop('checked', total === checked);
            });

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
                    var uuid   = $tr.data('uuid');

                    if (uuid && $('#tbl-teknikal tbody tr[data-standard-item-uuid="' + uuid + '"]').length > 0) {
                        $tr.hide();
                        return;
                    }

                    if (type === 'borang_atas_talian') {
                        $('#tbl-teknikal tbody').append(buildBorangAtasTalianRow(tajuk, $tr.data('actionUrl'), uuid));
                    } else {
                        $('#tbl-teknikal tbody').append(buildStandardRow(tajuk, uuid));
                    }
                    $tr.hide();
                });

                updateSkemaMaksima();
                syncTableEmpty();
                autoSave(true);

                $('#tbl-standard .row-check-standard, #tbl-standard .check-all-standard').prop('checked', false);
                bootstrap.Modal.getInstance($('#senaraiSemakStandard')[0]).hide();
            });

            $('#templateTable').on('click', '.btn-tambah-spesifikasi-templat', function() {
                var $row = $(this).closest('tr');
                var spec = $row.data('spec') || {};

                if (!spec.uuid) {
                    alert('Ralat: maklumat spesifikasi tidak lengkap.');
                    return;
                }

                if ($('#tbl-teknikal tbody tr[data-specification-uuid="' + spec.uuid + '"]').length > 0) {
                    alert('Spesifikasi ini telah ditambah ke dalam senarai.');
                    return;
                }

                upsertSpecificationDocumentRow(spec);
                autoSave(true);

                $row.hide();
            });


            // ─── DOKUMEN SOKONGAN: chip builder ──────────────────────────────────────
            function buildSokonganChip(fileUuid, fileName, fileUrl, fileSize) {
                var ext      = fileName.split('.').pop().toLowerCase();
                var safeName = $('<span>').text(fileName).html();
                var sz       = fileSize < 1024 ? fileSize + ' B'
                             : fileSize < 1048576 ? (fileSize / 1024).toFixed(1) + ' KB'
                             : (fileSize / 1048576).toFixed(1) + ' MB';
                var $chip = $(
                    '<div class="file-chip" data-file-uuid="' + fileUuid + '">' +
                    '<span class="file-chip-ext ext-' + ext + '">' + ext + '</span>' +
                    '<div class="file-chip-body">' +
                    '<a href="' + fileUrl + '" target="_blank" class="file-chip-name" title="' + safeName + '">' + safeName + '</a>' +
                    '<span class="file-chip-size">' + sz + '</span>' +
                    '</div>' +
                    '<button type="button" class="file-chip-remove" title="Buang fail">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                    '</button>' +
                    '</div>'
                );
                $chip.find('.file-chip-remove').on('click', function() {
                    var uuid = $chip.data('file-uuid');
                    if (!uuid) { $chip.remove(); return; }
                    var url = DELETE_FILE_URL.replace(':uuid', encodeURIComponent(uuid));
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                        success: function() { $chip.remove(); },
                        error: function() { alert('Gagal memadam fail. Sila cuba lagi.'); },
                    });
                });
                return $chip;
            }

            // ─── DOKUMEN SOKONGAN: AJAX upload ────────────────────────────────────────
            function uploadSokonganFile(file) {
                var fd = new FormData();
                fd.append('file', file);
                fd.append('file_type', 'sokongan');
                fd.append('_token', CSRF_TOKEN);
                $.ajax({
                    url: UPLOAD_FILE_URL,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        var f = res && res.data ? res.data : null;
                        if (!f) return;
                        $('#file-chip-list-sokongan').append(buildSokonganChip(f.uuid, f.original_name, f.url, f.size));
                    },
                    error: function() {
                        alert('Gagal memuat naik fail. Sila cuba lagi.');
                    },
                });
            }

            $('#input-dokumen-sokongan').on('change', function() {
                if (!this.files || !this.files.length) return;
                var fileArr = [];
                for (var i = 0; i < this.files.length; i++) fileArr.push(this.files[i]);
                $(this).val('');
                fileArr.forEach(uploadSokonganFile);
            });

            var sokonganZoneEl = document.getElementById('upload-zone-sokongan');
            sokonganZoneEl.addEventListener('dragover', function(e) {
                e.preventDefault();
                $('#upload-zone-sokongan').addClass('dragover');
            });
            sokonganZoneEl.addEventListener('dragleave', function() {
                $('#upload-zone-sokongan').removeClass('dragover');
            });
            sokonganZoneEl.addEventListener('drop', function(e) {
                e.preventDefault();
                $('#upload-zone-sokongan').removeClass('dragover');
                var files = e.dataTransfer.files;
                if (!files || !files.length) return;
                var fileArr = [];
                for (var i = 0; i < files.length; i++) fileArr.push(files[i]);
                fileArr.forEach(uploadSokonganFile);
            });

            var PENGURUSAN_URL = @json(route('pengurusanSpesifikasi'));

            var $overlay     = $('#page-loading-overlay');
            var $overlayText = $('#overlay-label-text');
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            var redirectAfterModal = false;

            function showOverlay(label) {
                $overlayText.text(label || 'Menyimpan...');
                $overlay.addClass('active');
            }

            function hideOverlay() {
                $overlay.removeClass('active');
            }

            function showSuccessModal(title, message, redirectOnClose) {
                redirectAfterModal = !!redirectOnClose;
                $('#success-modal-title').text(title);
                $('#success-modal-message').text(message);
                hideOverlay();
                successModal.show();
            }

            $('#successModal').on('hidden.bs.modal', function() {
                if (redirectAfterModal) {
                    redirectAfterModal = false;
                    showOverlay('Mengalih hala...');
                    window.location.href = PENGURUSAN_URL;
                }
            });

            $('.btn-simpan').on('click', function() {
                showOverlay('Menyimpan...');
                doSave(function() {
                    showSuccessModal('Berjaya Disimpan', 'Maklumat telah berjaya disimpan sebagai draf.');
                }, function() {
                    hideOverlay();
                    alert('Ralat semasa menyimpan. Sila cuba lagi.');
                });
            });

            $('#form-senarai-teknikal').on('submit', function (e) {
                e.preventDefault();
                var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
                var penilaian    = parseInt($('#input-penilaian').val()) || 0;
                if (skemaMaksima > 0 && penilaian > skemaMaksima) {
                    $('#input-penilaian').addClass('is-invalid').focus();
                    return;
                }
                showOverlay('Menghantar...');
                doSave(function() {
                    $overlayText.text('Melengkapkan...');
                    $.ajax({
                        url:         SUBMIT_URL,
                        method:      'POST',
                        headers:     { 'X-CSRF-TOKEN': CSRF_TOKEN },
                        contentType: 'application/json',
                        data: JSON.stringify({ passing_score: penilaian }),
                        success: function() {
                            showSuccessModal('Berjaya Dihantar', 'Senarai teknikal telah berjaya dihantar dan dilengkapkan.', true);
                        },
                        error: function(xhr) {
                            hideOverlay();
                            var msg = 'Ralat semasa menghantar.';
                            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            alert(msg);
                        }
                    });
                }, function() {
                    hideOverlay();
                    alert('Ralat semasa menyimpan sebelum hantar. Sila cuba lagi.');
                });
            });

        });
    </script>
@endsection
