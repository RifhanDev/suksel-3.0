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
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Semak Kewangan</h3>
            <p class="text-muted small m-0">Paparan senarai semak bahagian kewangan bagi tender / sebutharga.</p>
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
                    TENDER PERKHIDMATAN DIGITAL FORENSIK
                </h5>
            </div>

            <!-- Metadata: No. Tender · PTJ · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">T/2026/014</span>
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

    <form id="form-senarai-kewangan-bekalan" action="{{ route('jawatankuasa.simpanSenaraiKewanganBekalan') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tahap_lulus" id="tahap-lulus-hidden" value="0">

    <!-- ===================== SECTION 1: SENARAI SEMAK KEWANGAN & SKOR ===================== -->
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
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Senarai Semak Kewangan</p>
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
                </div>
                <!-- Divider -->
                <div style="width: 1px; height: 24px; background: #e2e8f0;"></div>
                <!-- Right: Row actions -->
                <div class="d-flex align-items-center gap-2">
                    <button type="button"
                        class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-kewangan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                    <button type="button"
                        class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 btn-hapus-kewangan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"><path fill="currentColor" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-9.489 5.14a1 1 0 0 0-1.218 1.567L10.585 14l-1.292 1.293l-.083.094a1 1 0 0 0 1.497 1.32L12 15.415l1.293 1.292l.094.083a1 1 0 0 0 1.32-1.497L13.415 14l1.292-1.293l.083-.094a1 1 0 0 0-1.497-1.32L12 12.585l-1.293-1.292l-.094-.083zM14 2a2 2 0 0 1 2 2a1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/></svg>
                        Hapus
                    </button>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table id="tbl-kewangan" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 44px;">
                                <input type="checkbox" name="check_all_kewangan" class="form-check-input px-0 check-all-kewangan">
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
                    <tbody id="tbl-kewangan-body">
                        <!-- Rows populated via JS initialRows -->
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

    <!-- ===================== SECTION 2: HARGA INDIKATIF ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2m0 9h-1l-.117.007a1 1 0 0 0 0 1.986L11 13v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876zm.01-3l-.127.007a1 1 0 0 0 0 1.986L12 10l.127-.007a1 1 0 0 0 0-1.986z"/></svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Harga Indikatif</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Masukkan anggaran harga indikatif bagi perolehan ini</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <label class="fw-semibold text-dark mb-0 flex-shrink-0" style="font-size: 0.875rem;">Harga Indikatif (RM)</label>
                <input type="text" id="input-harga-indikatif" name="harga_indikatif"
                    class="form-control form-control-sm fw-semibold text-end"
                    style="max-width: 200px; font-size: 1rem;"
                    value="300,000.00" placeholder="0.00">
            </div>
        </div>
    </div>

    <!-- ===================== SECTION 3: PENETAPAN PENANDA ARAS KEWANGAN ===================== -->
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

                <!-- Penilaian Kewangan input -->
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-2 h-100" style="background: #fafbfc; border: 1px solid #f1f5f9;">
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">Penilaian Kewangan</span>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="input-penilaian" name="input_penilaian" class="form-control form-control-sm text-center fw-semibold" style="max-width: 100px; font-size: 1rem;" placeholder="0" min="0">
                            <span class="text-muted small">daripada</span>
                            <span class="fw-bold text-dark" id="penilaian-kewangan-total" style="font-size: 1rem;">40</span>
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

    <!-- ===================== SECTION 4: DOKUMEN SOKONGAN ===================== -->
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
        <button type="submit" class="btn-form btn-form-success btn-hantar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"></path></svg>
            Hantar
        </button>
    </div>

    </form>
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
                                {{-- ── Standard items (Petender / PTJ Muat Naik) ── --}}
                                <tr data-tajuk="Modal Berbayar">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Modal Berbayar</td>
                                </tr>
                                <tr data-tajuk="Kemudahan Kredit (Overdraf, Pinjaman Bank)">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Kemudahan Kredit (Overdraf, Pinjaman Bank)</td>
                                </tr>
                                <tr data-tajuk="Pengesahan dari Institusi Kewangan ke atas jumlah yang telah dibayar">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Pengesahan dari Institusi Kewangan ke atas jumlah yang telah dibayar</td>
                                </tr>
                                <tr data-tajuk="Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Jumlah (RM) Kontrak yang pernah diikat)">
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                    <td>Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Jumlah (RM) Kontrak yang pernah diikat)</td>
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
@endpush

@section('scripts')
    <script src="{{ asset('js/components/file-upload.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // ─── INITIAL ROWS (simulates data from backend) ─────────────────────────
            var EDIT_ICON = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';

            var initialRows = [
                {
                    tajuk:      'TENDER PERKHIDMATAN DIGITAL FORENSIK',
                    mekanisma:  'Spesifikasi',
                    tindakanPembekal: 'Kunci Masuk',
                    skema:      40,
                    status:     'Selesai',
                    statusClass:'badge-status-success',
                    dokumen:    '—',
                    tindakanUrl:'{{ route('spesifikasiFormKewanganBekalan') }}'
                },
                {
                    tajuk:      'Maklumat Profil Petender',
                    mekanisma:  'Borang Atas Talian',
                    tindakanPembekal: 'Kunci Masuk',
                    skema:      0,
                    status:     'Draf',
                    statusClass:'badge-status-warning',
                    dokumen:    '—',
                    tindakanUrl:'{{ route('prflPetender') }}'
                },
                {
                    tajuk:      'Penyata Bank Terkini (3 Bulan Terakhir) Syarikat',
                    mekanisma:  'Borang Atas Talian',
                    tindakanPembekal: 'Kunci Masuk',
                    skema:      0,
                    status:     'Draf',
                    statusClass:'badge-status-warning',
                    dokumen:    '—',
                    tindakanUrl:'{{ route('pnytBank') }}'
                }
            ];

            // Build a table row from an initial row object (locked — no checkbox, no delete)
            function buildInitialRow(data) {
                return $(
                    '<tr class="initial-row">' +
                    '<td class="text-center"><span class="text-muted" style="font-size:0.75rem;" title="Data daripada sistem">—</span></td>' +
                    '<td><span class="small fw-semibold">' + $('<span>').text(data.tajuk).html() + '</span></td>' +
                    '<td class="text-center"><span class="small fw-semibold text-muted">' + data.mekanisma + '</span></td>' +
                    '<td class="text-center small">' + data.tindakanPembekal + '</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="' + data.skema + '" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status ' + data.statusClass + '">' + data.status + '</span></td>' +
                    '<td class="text-center text-muted small rujukan-cell">' + data.dokumen + '</td>' +
                    '<td class="text-center">' +
                        '<a href="' + data.tindakanUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                        EDIT_ICON +
                        '</a>' +
                    '</td>' +
                    '</tr>'
                );
            }

            // Render initial rows on page load
            $.each(initialRows, function(i, row) {
                $('#tbl-kewangan-body').append(buildInitialRow(row));
            });

            // ─── CHECKBOX: Senarai Semak Kewangan ────────────────────────────────────
            $('#tbl-kewangan').on('change', '.check-all-kewangan', function() {
                $('#tbl-kewangan .row-check-kewangan').prop('checked', $(this).prop('checked'));
            });

            $('#tbl-kewangan').on('change', '.row-check-kewangan', function() {
                var total = $('#tbl-kewangan .row-check-kewangan').length;
                var checked = $('#tbl-kewangan .row-check-kewangan:checked').length;
                $('#tbl-kewangan .check-all-kewangan').prop('checked', total === checked);
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
                    '<tr class="row-kewangan-tambah">' +
                    '<td class="text-center"><input type="checkbox" name="row_check_kewangan[]" class="form-check-input row-check-kewangan"></td>' +
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
                $('#tbl-kewangan .skema-input').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                $('#skema-maksima-display').val(total);
                $('#penilaian-kewangan-total').text(total);

                // Update max and re-validate without clamping
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
                $('#tahap-lulus').text((pct % 1 === 0) ? pct : pct.toFixed(2));
                $('#tahap-lulus-hidden').val(pct);
            }

            // ─── PENILAIAN INPUT: validate + recalculate ─────────────────────────────
            $('#input-penilaian').on('input change', function() {
                var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
                var val = parseInt($(this).val()) || 0;
                $(this).toggleClass('is-invalid', skemaMaksima > 0 && val > skemaMaksima);
                updateTahapLulus();
            });

            function syncTableEmpty() {
                var realRows = $('#tbl-kewangan tbody tr:not(#tbl-empty-row)').length;
                if (realRows === 0) {
                    $('#tbl-empty-row').removeClass('d-none');
                } else {
                    $('#tbl-empty-row').addClass('d-none');
                }
            }

            // ─── TAMBAH ROW ───────────────────────────────────────────────────────────
            $('.btn-tambah-kewangan').on('click', function() {
                $('#tbl-kewangan tbody').append(buildNewRow());
                syncTableEmpty();
                updateSkemaMaksima();
            });

            // ─── HAPUS ROW (CHECKED ONLY) ─────────────────────────────────────────────
            $('.btn-hapus-kewangan').on('click', function() {
                var checked = $('#tbl-kewangan .row-check-kewangan:checked');
                if (checked.length === 0) {
                    alert('Sila pilih sekurang-kurangnya satu rekod untuk dihapus.');
                    return;
                }
                checked.closest('tr').remove();
                $('#tbl-kewangan .check-all-kewangan').prop('checked', false);
                syncTableEmpty();
                updateSkemaMaksima();
            });

            // ─── MEKANISMA CHANGE ─────────────────────────────────────────────────────
            $('#tbl-kewangan').on('change', '.mekanisma-select', function() {
                var val  = $(this).val();
                var $row = $(this).closest('tr');
                $row.find('.tindakan-pembekal').html(buildTindakanPembekalCell(val));
                $row.find('.rujukan-cell').html(buildDokumenCell(val));
                $row.find('.tindakan-cell').html(buildTindakanCell(val));
            });

            // ─── TINDAKAN PEMBEKAL DROPDOWN CHANGE (PTJ only) ────────────────────────
            // Both options allow uploading — always keep the upload button visible.
            $('#tbl-kewangan').on('change', '.tindakan-pembekal-select', function() {
                $(this).closest('tr').find('.tindakan-cell').html(PTJ_UPLOAD_BTN);
            });

            // ─── SKEMA INPUT CHANGE ───────────────────────────────────────────────────
            $('#tbl-kewangan').on('input change', '.skema-input', function() {
                updateSkemaMaksima();
            });

            // ─── PTJ MUAT NAIK: File upload → append to Dokumen column, hide upload btn ─
            $('#tbl-kewangan').on('change', '.tindakan-cell input[type="file"]', function() {
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
            $('#tbl-kewangan').on('click', '.btn-hapus-ptj-file', function() {
                $(this).closest('.dokumen-ptj-ours').remove();
            });

            // ─── ROW BUILDER for Senarai Semak Standard ─────────────────────────────
            function buildStandardRow(tajuk) {
                var defaultMekanisma = 'petender_muat_naik';
                return $(
                    '<tr class="row-kewangan-tambah">' +
                    '<td class="text-center"><input type="checkbox" name="row_check_kewangan[]" class="form-check-input row-check-kewangan"></td>' +
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
                    var $tr   = $(this).closest('tr');
                    var tajuk = $tr.data('tajuk') || $tr.find('td:last-child').text().trim();

                    $('#tbl-kewangan tbody').append(buildStandardRow(tajuk));
                    $tr.hide(); // hide from list once added — prevents duplicate selection
                });

                updateSkemaMaksima();
                syncTableEmpty();

                // Reset checkboxes and close modal
                $('#tbl-standard .row-check-standard, #tbl-standard .check-all-standard').prop('checked', false);
                bootstrap.Modal.getInstance($('#senaraiSemakStandard')[0]).hide();
            });


            // Initial skema calculation after rows are loaded
            updateSkemaMaksima();
            syncTableEmpty();

            // ─── HARGA INDIKATIF: Format with commas ─────────────────────────────────
            var $hargaInput = $('#input-harga-indikatif');

            function formatHarga(value) {
                var num = parseFloat(value.replace(/,/g, '')) || 0;
                return num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            $hargaInput.on('focus', function() {
                var raw = $(this).val().replace(/,/g, '');
                if (parseFloat(raw) === 0) raw = '';
                $(this).val(raw);
            });

            $hargaInput.on('blur', function() {
                $(this).val(formatHarga($(this).val()));
            });

            $hargaInput.on('input', function() {
                $(this).val($(this).val().replace(/[^\d.]/g, ''));
            });

            // ─── DOKUMEN SOKONGAN: Upload zone + file chips ──────────────────────────
            FileUpload.init({
                zoneId     : 'upload-zone-sokongan',
                inputId    : 'input-dokumen-sokongan',
                chipListId : 'file-chip-list-sokongan'
            });

            // ─── SIMPAN (no submit, success modal only) ──────────────────────────────
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));

            $('.btn-simpan').on('click', function() {
                successModal.show();
            });

            // ─── FORM SUBMIT: block if penilaian exceeds skema maksima ───────────────
            // TODO: restore real submit after demo — currently intercepted for demo purposes
            // $('#form-senarai-kewangan-bekalan').on('submit', function(e) {
            //     // Strip commas from harga_indikatif before submit
            //     $('#input-harga-indikatif').val($('#input-harga-indikatif').val().replace(/,/g, ''));
            //
            //     var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
            //     var penilaian    = parseInt($('#input-penilaian').val()) || 0;
            //     if (skemaMaksima > 0 && penilaian > skemaMaksima) {
            //         e.preventDefault();
            //         $('#input-penilaian').addClass('is-invalid').focus();
            //     }
            // });

            // DEMO: btn-hantar shows success modal instead of submitting
            $('.btn-hantar').on('click', function (e) {
                e.preventDefault();
                successModal.show();
            });

        });
    </script>
@endsection
