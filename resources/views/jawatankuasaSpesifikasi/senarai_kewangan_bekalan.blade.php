@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        #loading-overlay { display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.45); backdrop-filter:blur(2px); align-items:center; justify-content:center; }
        #loading-overlay.active { display:flex; }
        .loading-box { background:#fff; border-radius:12px; padding:28px 36px; display:flex; align-items:center; gap:16px; box-shadow:0 8px 32px rgba(0,0,0,0.18); }
        .loading-spinner { width:28px; height:28px; border:3px solid #e2e8f0; border-top-color:#3b82f6; border-radius:50%; animation:spin 0.7s linear infinite; flex-shrink:0; }
        @keyframes spin { to { transform:rotate(360deg); } }
        .loading-text { font-size:0.9rem; font-weight:600; color:#1e293b; }
        .loading-check { display:none; width:28px; height:28px; flex-shrink:0; color:#22c55e; }
        #loading-overlay.success .loading-spinner { display:none; }
        #loading-overlay.success .loading-check  { display:block; }
        #loading-overlay.success .loading-text   { color:#16a34a; }
    </style>
@endsection

@section('content')
    <div id="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <svg class="loading-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span class="loading-text" id="loading-text">Menyimpan...</span>
        </div>
    </div>

    @php
        $tajukTender = $tender->tajuk_tender ?? $tender->name ?? 'Tiada Tajuk';
        $kategoriPerolehan = $tender->kategori_perolehan_name ?? null;
        $noTender = $tender->no_tender ?? $tender->ref_number ?? '-';
        $ptj = optional($tender->tenderer)->name ?? '-';
        $hargaIndikatif = old('harga_indikatif', $tender->harga_indikatif);
        $hargaIndikatifDisplay = is_numeric($hargaIndikatif)
            ? number_format((float) $hargaIndikatif, 2, '.', ',')
            : '';
    @endphp

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
                    {{ $tajukTender }}
                    @if($kategoriPerolehan)
                        <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">({{ $kategoriPerolehan }})</span>
                    @endif
                </h5>
            </div>

            <!-- Metadata: No. Tender · PTJ · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $noTender }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $ptj }}</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    @if(($checklistData['status'] ?? null) === 'submitted')
                        <span id="status-badge" class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background:#dcfce7;color:#166534;font-size:0.8rem;border:1px solid #bbf7d0;">
                            <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span>
                            Telah Dihantar
                        </span>
                    @else
                    <span id="status-badge" class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle"
                            style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <form id="form-senarai-kewangan-bekalan" action="{{ route('senaraiKewanganBekalan.store', $tender->uuid) }}" method="POST" enctype="multipart/form-data">
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
                                    class="form-control form-control-sm text-center fw-bold" value="{{ $checklistData['max_score'] ?? 0 }}" readonly
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
                    {{-- <p class="text-muted mb-0" style="font-size: 0.78rem;">Masukkan anggaran harga indikatif bagi perolehan ini</p> --}}
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <label class="fw-semibold text-dark mb-0 flex-shrink-0" style="font-size: 0.875rem;">Harga Indikatif (RM)</label>
                <span id="input-harga-indikatif" class="fw-bold text-dark" style="font-size: 1rem;">{{ $hargaIndikatifDisplay }}</span>
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
                            <input type="number" id="input-penilaian" name="input_penilaian" class="form-control form-control-sm text-center fw-semibold" style="max-width: 100px; font-size: 1rem;" placeholder="0" min="0" value="{{ $checklistData['passing_score'] ?? 0 }}">
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
        <button type="button" class="btn-form btn-form-success btn-hantar">
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

    <!-- ===================== MODAL: VALIDASI HANTAR ===================== -->
    <div class="modal fade" id="validasiHantarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                        style="width:44px;height:44px;background:#fef3c7;flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#92400e;">Tidak Boleh Dihantar</h6>
                        <p class="text-muted mb-0" style="font-size:0.82rem;">
                            Semua baris dalam jadual perlu berstatus <strong>Selesai</strong> sebelum boleh dihantar.
                        </p>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn-form btn-form-primary" data-bs-dismiss="modal">Faham</button>
                </div>
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
                                            @if (($item['type'] ?? '') === 'borang_atas_talian')
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
@endpush

@section('scripts')
    <script src="{{ asset('js/components/file-upload.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // ─── INITIAL ROWS (from backend) ────────────────────────────────────────
            var EDIT_ICON = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';

            var SPEC_FORM_BASE   = @json(rtrim(route('spesifikasiFormKewanganBekalan', ['spesifikasiUuid' => '__uuid__']), '/'));
            var SPEC_TEKNIKAL_FORM_BASE = @json(rtrim(route('spesifikasiForm', ['tenderUuid' => $tender->uuid]), '/'));
            var TENDER_UUID      = @json($tender->uuid);
            var STORE_URL       = '{{ route('senaraiKewanganBekalan.store', $tender->uuid) }}';
            var SUBMIT_URL      = '{{ route('senaraiKewanganBekalan.submit', $tender->uuid) }}';
            var UPLOAD_FILE_URL = @json(route('senaraiKewanganBekalan.uploadFile', $tender->uuid));
            var DELETE_FILE_URL = @json(route('senaraiKewanganBekalan.deleteFile', ':uuid'));
            var CSRF_TOKEN      = '{{ csrf_token() }}';
            var checklistItems   = @json($checklistData['items'] ?? []);
            var sokonganFiles   = @json($checklistData['files'] ?? []);
            var IS_SUBMITTED    = @json(($checklistData['status'] ?? null) === 'submitted');

            var statusLabelMap = {
                'submitted': { label: 'Selesai', cls: 'badge-status-success' },
                'completed': { label: 'Selesai', cls: 'badge-status-success' },
                'complete':  { label: 'Selesai', cls: 'badge-status-success' },
                'draft':     { label: 'Draf',    cls: 'badge-status-warning' },
            };

            // Upload button HTML — used while initial rows are rendered.
            var PTJ_UPLOAD_BTN =
                '<label class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-1 mb-0 btn-ptj-upload" style="width:30px;height:30px;cursor:pointer;" title="Muat Naik">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>' +
                '<input type="file" name="dokumen_ptj[]" hidden>' +
                '</label>';

            function isBlankValue(value) {
                return value === null
                    || value === undefined
                    || value === ''
                    || value === 'undefined'
                    || value === 'null';
            }

            function valueOr(value, fallback) {
                return isBlankValue(value) ? fallback : value;
            }

            function normalizeMechanism(mechanism) {
                mechanism = valueOr(mechanism, 'petender_muat_naik');
                return ['petender_muat_naik', 'ptj_muat_naik'].indexOf(mechanism) !== -1
                    ? mechanism
                    : 'petender_muat_naik';
            }

            function normalizeSourceType(sourceType, item) {
                sourceType = valueOr(sourceType, item && item.standard_item_uuid ? 'standard_item' : 'manual');
                return sourceType === 'standard' ? 'standard_item' : sourceType;
            }

            function statusMeta(status) {
                status = valueOr(status, 'draft');
                return statusLabelMap[status] || { label: valueOr(status, 'Draf'), cls: 'badge-status-warning' };
            }

            function setRowStatus($row, status) {
                var s = statusMeta(status);
                $row.attr('data-status', status);
                $row.find('td:nth-child(6)').html(
                    '<span class="badge-status ' + s.cls + '">' + s.label + '</span>'
                );
            }

            function hasSkemaValue($row) {
                var value = $row.find('.skema-input').val();
                var parsedValue = parseFloat(value);

                return value !== null
                    && value !== undefined
                    && String(value).trim() !== ''
                    && !isNaN(parsedValue)
                    && parsedValue > 0;
            }

            function isStandardChecklistRow($row) {
                var sourceType = normalizeSourceType($row.data('source-type'), {
                    standard_item_uuid: $row.data('standard-item-uuid') || null
                });

                return sourceType === 'standard_item';
            }

            function hasTajukValue($row) {
                var val = $row.find('input[name="tajuk_dokumen[]"]').val();
                return val !== null && val !== undefined && String(val).trim() !== '';
            }

            function updateRowCompletionStatus($row) {
                var sourceType = normalizeSourceType($row.data('source-type'), {
                    standard_item_uuid: $row.data('standard-item-uuid') || null
                });
                var rawMechanism    = String($row.find('.mekanisma-select').val() || $row.data('mechanism') || '').toLowerCase();
                var mechanismLabel  = String($row.find('td:nth-child(3)').text() || '').toLowerCase();
                var isBorangAtasTalian = (sourceType === 'borang_atas_talian' || rawMechanism === 'borang_atas_talian' || mechanismLabel.indexOf('borang atas talian') !== -1);

                if (!isBorangAtasTalian) {
                    var hasSkema = hasSkemaValue($row);
                    setRowStatus($row, hasSkema ? 'submitted' : 'draft');
                    return;
                }

                var mechanism       = normalizeMechanism(rawMechanism);
                var hasUploadedFile = $row.find('.dokumen-ptj-ours').length > 0;

                if (sourceType === 'standard_item') {
                    var isComplete = mechanism === 'ptj_muat_naik' && hasUploadedFile && hasSkemaValue($row);
                    setRowStatus($row, isComplete ? 'submitted' : 'draft');
                } else if (sourceType === 'manual') {
                    var hasTajuk  = hasTajukValue($row);
                    var hasSkema  = hasSkemaValue($row);
                    var needsFile = mechanism === 'ptj_muat_naik';
                    var isComplete = hasTajuk && hasSkema && (!needsFile || hasUploadedFile);
                    setRowStatus($row, isComplete ? 'submitted' : 'draft');
                }
            }

            function updateAllCompletionStatuses() {
                $('#tbl-kewangan tbody tr').each(function() {
                    updateRowCompletionStatus($(this));
                });
            }

            // Build a table row from an initial row object (locked — no checkbox, no delete)
            function buildInitialRow(data) {
                var tindakanUrl = valueOr(data.tindakanUrl, '#');
                var isSpec = (data.source_type === 'specification_document' || data.mechanism === 'spesifikasi');
                var skemaReadonlyAttr = isSpec ? ' readonly style="max-width:90px;margin:0 auto;background:#f8fafc;"' : ' style="max-width:90px;margin:0 auto;"';
                return $(
                    '<tr class="initial-row"' +
                    ' data-row-uuid="' + (data.uuid || '') + '"' +
                    ' data-status="' + valueOr(data.statusKey, 'draft') + '"' +
                    ' data-source-type="' + (data.source_type || '') + '"' +
                    ' data-mechanism="' + (data.mechanism || '') + '"' +
                    ' data-standard-item-uuid="' + (data.standard_item_uuid || '') + '"' +
                    ' data-technical-item-uuid="' + (data.technical_item_uuid || '') + '"' +
                    '>' +
                    '<td class="text-center"><span class="text-muted" style="font-size:0.75rem;" title="Data daripada sistem">—</span></td>' +
                    '<td><span class="small fw-semibold">' + $('<span>').text(valueOr(data.tajuk, '')).html() + '</span></td>' +
                    '<td class="text-center"><span class="small fw-semibold text-muted">' + valueOr(data.mekanismaLabel, '—') + '</span></td>' +
                    '<td class="text-center small">' + valueOr(data.tindakanPembekal, 'Kunci Masuk') + '</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="' + valueOr(data.skema, 0) + '" min="0"' + skemaReadonlyAttr + '>' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status ' + valueOr(data.statusClass, 'badge-status-warning') + '">' + valueOr(data.status, 'Draf') + '</span></td>' +
                    '<td class="text-center text-muted small rujukan-cell">' + valueOr(data.dokumen, '—') + '</td>' +
                    '<td class="text-center">' +
                        '<a href="' + tindakanUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                        EDIT_ICON +
                        '</a>' +
                    '</td>' +
                    '</tr>'
                );
            }

            // Build an editable row for manual/standard_item rows returning from the API
            function buildEditableRow(item) {
                var sourceType = normalizeSourceType(item.source_type, item);
                var mek        = normalizeMechanism(item.mechanism);
                var isManual   = sourceType === 'manual';
                var titleCell = isManual
                    ? '<input type="text" name="tajuk_dokumen[]" class="form-control form-control-sm" value="' + $('<span>').text(valueOr(item.title, '')).html() + '">'
                    : '<span class="small fw-semibold">' + $('<span>').text(valueOr(item.title, '')).html() + '</span>';
                var mekOptions =
                    '<option value="petender_muat_naik"' + (mek === 'petender_muat_naik' ? ' selected' : '') + '>Petender Muat Naik</option>' +
                    '<option value="ptj_muat_naik"'      + (mek === 'ptj_muat_naik'      ? ' selected' : '') + '>PTJ Muat Naik</option>';
                return $(
                    '<tr class="row-kewangan-tambah"' +
                    ' data-row-uuid="'          + (item.uuid               || '') + '"' +
                    ' data-status="'            + valueOr(item.status, 'draft') + '"' +
                    ' data-source-type="'       + sourceType + '"' +
                    ' data-standard-item-uuid="'+ (item.standard_item_uuid || '') + '"' +
                    ' data-technical-item-uuid="'+(item.technical_item_uuid|| '') + '"' +
                    '>' +
                    '<td class="text-center"><input type="checkbox" name="row_check_kewangan[]" class="form-check-input row-check-kewangan"></td>' +
                    '<td>' + titleCell + '</td>' +
                    '<td class="text-center">' +
                        '<select name="mekanisma[]" class="form-select form-select-sm mekanisma-select" style="font-size:0.78rem;">' + mekOptions + '</select>' +
                    '</td>' +
                    '<td class="text-center tindakan-pembekal">' + buildTindakanPembekalCell(mek, item.vendor_action) + '</td>' +
                    '<td class="text-center">' +
                        '<input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="' + (item.score || 0) + '" min="0" style="max-width:90px;margin:0 auto;">' +
                    '</td>' +
                    '<td class="text-center"><span class="badge-status ' + statusMeta(item.status).cls + '">' + statusMeta(item.status).label + '</span></td>' +
                    '<td class="text-center rujukan-cell">'  + buildDokumenCell(mek) + '</td>' +
                    '<td class="text-center tindakan-cell">' + buildTindakanCell(mek) + '</td>' +
                    '</tr>'
                );
            }

            // Render rows on page load — locked for spec/borang items, editable for manual/standard
            var seenSpecKeys = {};
            checklistItems.forEach(function(item) {
                item.source_type = normalizeSourceType(item.source_type, item);
                item.mechanism = normalizeMechanism(item.mechanism);

                var isSpec = item.source_type === 'specification_document' || item.mechanism === 'spesifikasi';
                if (isSpec) {
                    var specKey = item.specification_document_uuid || item.technical_item_uuid || item.title || item.uuid;
                    if (seenSpecKeys[specKey]) {
                        return; // Skip duplicate specification_document row
                    }
                    seenSpecKeys[specKey] = true;
                }

                var locked = item.source_type === 'specification_document' || item.source_type === 'borang_atas_talian';
                if (locked) {
                    var s = statusMeta(item.status);
                    var mekanismaLabel = item.source_type === 'specification_document' ? 'Spesifikasi' : 'Borang Atas Talian';
                    var specDocUuid    = item.specification_document_uuid || item.technical_item_uuid || item.uuid || '';
                    var tindakanUrl    = item.source_type === 'specification_document'
                        ? (specDocUuid ? SPEC_FORM_BASE.replace('__uuid__', encodeURIComponent(specDocUuid)) : '#')
                        : (valueOr(item.action_url, '') ? item.action_url + '/' + TENDER_UUID : '#');
                    $('#tbl-kewangan-body').append(buildInitialRow({
                        uuid:                item.uuid,
                        source_type:         item.source_type,
                        mechanism:           item.mechanism,
                        standard_item_uuid:  item.standard_item_uuid  || '',
                        technical_item_uuid: item.technical_item_uuid || '',
                        tajuk:               item.title,
                        mekanismaLabel:      mekanismaLabel,
                        tindakanPembekal:    'Kunci Masuk',
                        skema:               item.score,
                        statusKey:           item.status,
                        status:              s.label,
                        statusClass:         s.cls,
                        dokumen:             '—',
                        tindakanUrl:         tindakanUrl,
                    }));
                } else {
                    var $row = buildEditableRow(item);

                    if (item.mechanism === 'ptj_muat_naik' && item.files && item.files.length) {
                        var $list = $row.find('.rujukan-cell .dokumen-ptj-list');
                        item.files.forEach(function(f) {
                            $list.append(buildPtjFileEntry(f.uuid, f.original_name, f.url));
                        });
                    }

                    updateRowCompletionStatus($row);
                    $('#tbl-kewangan-body').append($row);
                }
            });

            updateAllCompletionStatuses();

            // Hide modal items that are already in the checklist on initial load
            $('#tbl-standard tbody tr[data-uuid]').each(function() {
                var $stdTr = $(this);
                var uuid   = $stdTr.data('uuid');
                if (uuid && $('#tbl-kewangan tbody tr[data-standard-item-uuid="' + uuid + '"]').length) {
                    $stdTr.hide();
                }
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
            function buildTindakanPembekalCell(mekanisma, vendorAction) {
                mekanisma = normalizeMechanism(mekanisma);
                vendorAction = valueOr(vendorAction, 'muat_turun');

                if (mekanisma === 'ptj_muat_naik') {
                    return '<select name="tindakan_pembekal[]" class="form-select form-select-sm tindakan-pembekal-select" style="font-size:0.78rem;">' +
                        '<option value="muat_turun"' + (vendorAction === 'muat_turun' ? ' selected' : '') + '>Muat Turun</option>' +
                        '<option value="muat_turun_naik"' + (vendorAction === 'muat_turun_naik' ? ' selected' : '') + '>Muat Turun &amp; Muat Naik</option>' +
                        '</select>';
                }
                // petender_muat_naik
                return '<span class="small">Muat Naik</span>';
            }

            // Returns HTML for the Tindakan (action) cell based on mekanisma.
            // PTJ Muat Naik always starts with an upload button regardless of tindakan pembekal selection.
            function buildTindakanCell(mekanisma) {
                mekanisma = normalizeMechanism(mekanisma);

                if (mekanisma === 'ptj_muat_naik') {
                    return PTJ_UPLOAD_BTN;
                }
                return '<span class="text-muted small">—</span>';
            }

            // Returns HTML for the Dokumen cell based on mekanisma.
            // PTJ Muat Naik starts empty — uploaded files are appended dynamically via the upload handler.
            function buildDokumenCell(mekanisma) {
                mekanisma = normalizeMechanism(mekanisma);

                if (mekanisma === 'ptj_muat_naik') {
                    return '<div class="dokumen-ptj-list d-flex flex-column gap-1 align-items-start" style="min-width:130px;max-height:72px;overflow-y:auto;"></div>';
                }
                return '<span class="text-muted small">—</span>';
            }

            function buildNewRow() {
                var defaultMekanisma = 'petender_muat_naik';
                return $(
                    '<tr class="row-kewangan-tambah" data-source-type="manual" data-status="draft">' +
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

            // ─── AUTO-SAVE ENGINE ────────────────────────────────────────────────────
            var autoSaveTimer = null;

            function collectItems() {
                var items = [];
                var idx   = 0;
                $('#tbl-kewangan tbody tr').each(function() {
                    var $tr = $(this);
                    if ($tr.attr('id') === 'tbl-empty-row') return;

                    var sourceType = $tr.data('source-type') || 'manual';

                    var title;
                    var $tajukInput = $tr.find('input[name="tajuk_dokumen[]"]');
                    if ($tajukInput.length) {
                        title = $.trim($tajukInput.val());
                    } else {
                        title = $.trim($tr.find('td:nth-child(2) .fw-semibold').text());
                    }
                    if (!title) return;

                    var mechanism;
                    var $mekSelect = $tr.find('select.mekanisma-select');
                    if ($mekSelect.length) {
                        mechanism = $mekSelect.val();
                    } else {
                        mechanism = $tr.data('mechanism') || null;
                    }

                    var vendorAction = null;
                    if (mechanism === 'ptj_muat_naik') {
                        vendorAction = $tr.find('.tindakan-pembekal-select').val() || 'muat_turun';
                    } else if (mechanism === 'petender_muat_naik') {
                        vendorAction = 'muat_naik';
                    }

                    items.push({
                        uuid:                $tr.data('row-uuid') || null,
                        source_type:         sourceType,
                        title:               title,
                        mechanism:           mechanism,
                        vendor_action:       vendorAction,
                        score:               parseFloat($tr.find('.skema-input').val()) || 0,
                        status:              $tr.data('status') || 'draft',
                        sort_order:          idx,
                        standard_item_uuid:  $tr.data('standard-item-uuid') || null,
                        technical_item_uuid: $tr.data('technical-item-uuid') || null,
                    });
                    idx++;
                });
                return items;
            }

            function updateRowUuids(savedItems) {
                var $rows = $('#tbl-kewangan tbody tr').filter(function() {
                    return $(this).attr('id') !== 'tbl-empty-row';
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
                    data:        JSON.stringify({
                        items:              items,
                        max_score:          maxScore,
                        passing_score:      penilaian,
                        passing_percentage: pct,
                        status:             'draft',
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

            // ─── PENILAIAN INPUT: validate + recalculate + auto-save ─────────────────
            $('#input-penilaian').on('input change', function() {
                var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
                var val = parseInt($(this).val()) || 0;
                $(this).toggleClass('is-invalid', skemaMaksima > 0 && val > skemaMaksima);
                updateTahapLulus();
                autoSave();
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
                autoSave(true);
            });

            // ─── HAPUS ROW (CHECKED ONLY) ─────────────────────────────────────────────
            $('.btn-hapus-kewangan').on('click', function() {
                var $checked = $('#tbl-kewangan .row-check-kewangan:checked');
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
                    $row.remove();
                });

                $('#tbl-standard .check-all-standard').prop('checked', false);
                $('#tbl-kewangan .check-all-kewangan').prop('checked', false);
                syncTableEmpty();
                updateSkemaMaksima();
                autoSave(true);
            });

            // ─── MEKANISMA CHANGE ─────────────────────────────────────────────────────
            $('#tbl-kewangan').on('change', '.mekanisma-select', function() {
                var val  = $(this).val();
                var $row = $(this).closest('tr');
                $row.find('.tindakan-pembekal').html(buildTindakanPembekalCell(val));
                $row.find('.rujukan-cell').html(buildDokumenCell(val));
                $row.find('.tindakan-cell').html(buildTindakanCell(val));
                updateRowCompletionStatus($row);
                autoSave();
            });

            // ─── TINDAKAN PEMBEKAL DROPDOWN CHANGE (PTJ only) ────────────────────────
            $('#tbl-kewangan').on('change', '.tindakan-pembekal-select', function() {
                var $row = $(this).closest('tr');
                $row.find('.tindakan-cell').html(PTJ_UPLOAD_BTN);
                updateRowCompletionStatus($row);
                autoSave();
            });

            // ─── SKEMA INPUT CHANGE ───────────────────────────────────────────────────
            $('#tbl-kewangan').on('input change', '.skema-input', function() {
                updateSkemaMaksima();
                updateRowCompletionStatus($(this).closest('tr'));
                autoSave();
            });

            // ─── TAJUK INPUT ─────────────────────────────────────────────────────────
            $('#tbl-kewangan').on('input', 'input[name="tajuk_dokumen[]"]', function() {
                updateRowCompletionStatus($(this).closest('tr'));
                autoSave();
            });

            function buildPtjFileEntry(fileUuid, fileName, fileUrl) {
                var safeName = $('<span>').text(fileName || 'Dokumen').html();
                return $(
                    '<div class="d-flex align-items-center gap-1 dokumen-ptj-ours" data-file-uuid="' + (fileUuid || '') + '">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>' +
                    '<a href="' + (fileUrl || '#') + '" target="_blank" class="small fw-semibold text-truncate d-inline-block" style="max-width:85px;" title="' + safeName + '">' + safeName + '</a>' +
                    '<button type="button" class="btn-hapus-ptj-file d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:16px;height:16px;background:#fee2e2;border:none;border-radius:3px;padding:0;cursor:pointer;" title="Buang">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                    '</div>'
                );
            }

            // ─── PTJ MUAT NAIK: File upload → save row first, then upload file ───────
            $('#tbl-kewangan').on('change', '.tindakan-cell input[type="file"]', function() {
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
                            updateRowCompletionStatus($row);
                            autoSave(true);
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

            // ─── PTJ MUAT NAIK: Remove an uploaded file entry ────────────────────────
            $('#tbl-kewangan').on('click', '.btn-hapus-ptj-file', function() {
                var $entry   = $(this).closest('.dokumen-ptj-ours');
                var fileUuid = $entry.data('file-uuid');

                if (!fileUuid) {
                    var $row = $entry.closest('tr');
                    $entry.remove();
                    updateRowCompletionStatus($row);
                    autoSave(true);
                    return;
                }

                var url = DELETE_FILE_URL.replace(':uuid', encodeURIComponent(fileUuid));
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                    success: function() {
                        var $row = $entry.closest('tr');
                        $entry.remove();
                        updateRowCompletionStatus($row);
                        autoSave(true);
                    },
                    error: function() { alert('Gagal memadam fail. Sila cuba lagi.'); },
                });
            });

            function buildBorangAtasTalianActionCell(routeUrl) {
                if (!routeUrl) {
                    return '<span class="text-muted small">—</span>';
                }

                return '' +
                    '<a href="' + routeUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                    '</a>';
            }

            function buildBorangAtasTalianRow(tajuk, actionUrl, standardItemUuid) {
                var routeUrl = actionUrl ? (actionUrl.startsWith('/') || actionUrl.startsWith('http') ? actionUrl : '/' + actionUrl + '/' + TENDER_UUID) : '';
                var stdAttr  = standardItemUuid ? ' data-standard-item-uuid="' + $('<span>').text(standardItemUuid).html() + '"' : '';
                var $row = $(
                    '<tr data-source-type="borang_atas_talian"' + stdAttr + '>' +
                    '<td class="text-center"><input type="checkbox" name="row_check_kewangan[]" class="form-check-input row-check-kewangan"></td>' +
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

            // ─── ROW BUILDER for Senarai Semak Standard ─────────────────────────────
            function buildStandardRow(tajuk, standardItemUuid) {
                var defaultMekanisma = 'petender_muat_naik';
                var stdAttr = standardItemUuid ? ' data-standard-item-uuid="' + (standardItemUuid || '') + '"' : '';
                var $row = $(
                    '<tr class="row-kewangan-tambah" data-source-type="standard_item" data-status="draft"' + stdAttr + '>' +
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
                if (standardItemUuid) {
                    $row.data('standard-item-uuid', standardItemUuid);
                }
                return $row;
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
                    var $tr              = $(this).closest('tr');
                    var type             = $tr.data('type');
                    var tajuk            = $tr.data('tajuk') || $tr.find('td:last-child').text().trim();
                    var standardItemUuid = $tr.data('uuid') || '';
                    var actionUrl        = $tr.data('actionUrl') || '';

                    if (standardItemUuid && $('#tbl-kewangan tbody tr[data-standard-item-uuid="' + standardItemUuid + '"]').length > 0) {
                        $tr.hide();
                        return;
                    }

                    if (type === 'borang_atas_talian') {
                        $('#tbl-kewangan tbody').append(buildBorangAtasTalianRow(tajuk, actionUrl, standardItemUuid));
                    } else {
                        $('#tbl-kewangan tbody').append(buildStandardRow(tajuk, standardItemUuid));
                    }
                    $tr.hide(); // hide from list once added — prevents duplicate selection
                });

                updateSkemaMaksima();
                syncTableEmpty();
                autoSave(true);

                // Reset checkboxes and close modal
                $('#tbl-standard .row-check-standard, #tbl-standard .check-all-standard').prop('checked', false);
                bootstrap.Modal.getInstance($('#senaraiSemakStandard')[0]).hide();
            });


            // Initial skema calculation after rows are loaded
            updateSkemaMaksima();
            syncTableEmpty();

            // ─── HARGA INDIKATIF: Format with commas ─────────────────────────────────

            // ─── DOKUMEN SOKONGAN: Immediate AJAX upload ─────────────────────────────
            function formatSokonganBytes(bytes) {
                if (!bytes) return '—';
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            }

            function buildSokonganChip(fileUuid, fileName, fileSize) {
                var ext      = (fileName || '').split('.').pop().toLowerCase();
                var safeName = $('<span>').text(fileName || '').html();
                var $chip = $(
                    '<div class="file-chip" data-file-uuid="' + (fileUuid || '') + '">' +
                        '<span class="file-chip-ext ext-' + ext + '">' + ext + '</span>' +
                        '<div class="file-chip-body">' +
                            '<span class="file-chip-name" title="' + safeName + '">' + safeName + '</span>' +
                            '<span class="file-chip-size">' + formatSokonganBytes(fileSize) + '</span>' +
                        '</div>' +
                        '<button type="button" class="file-chip-remove" title="Buang fail">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' +
                            '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                        '</button>' +
                    '</div>'
                );
                $chip.find('.file-chip-remove').on('click', function() {
                    var uuid = $chip.data('file-uuid');
                    if (uuid) {
                        var url = DELETE_FILE_URL.replace(':uuid', encodeURIComponent(uuid));
                        $.ajax({ url: url, method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
                         .always(function() { $chip.remove(); });
                    } else {
                        $chip.remove();
                    }
                });
                return $chip;
            }

            function uploadSokongan(file) {
                var $chip = buildSokonganChip(null, file.name, file.size);
                $chip.find('.file-chip-size').text('Memuat naik...');
                $('#file-chip-list-sokongan').append($chip);

                var fd = new FormData();
                fd.append('file', file);
                fd.append('file_type', 'support');
                fd.append('_token', CSRF_TOKEN);

                $.ajax({
                    url: UPLOAD_FILE_URL, method: 'POST', data: fd,
                    processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                }).done(function(res) {
                    var f = res && res.data ? res.data : null;
                    if (f) {
                        $chip.attr('data-file-uuid', f.uuid).data('file-uuid', f.uuid);
                        $chip.find('.file-chip-size').text(formatSokonganBytes(f.size || file.size));
                    } else {
                        $chip.remove();
                        alert('Fail tidak berjaya dimuat naik.');
                    }
                }).fail(function() {
                    $chip.remove();
                    alert('Ralat semasa muat naik fail. Sila cuba lagi.');
                });
            }

            var $sokonganInput = $('#input-dokumen-sokongan');
            var $sokonganZone  = $('#upload-zone-sokongan');

            $sokonganInput.on('change', function() {
                if (!this.files || !this.files.length) return;
                var arr = []; for (var i = 0; i < this.files.length; i++) arr.push(this.files[i]);
                $sokonganInput.val('');
                arr.forEach(uploadSokongan);
            });

            $sokonganZone[0].addEventListener('dragover',  function(e) { e.preventDefault(); $sokonganZone.addClass('dragover'); });
            $sokonganZone[0].addEventListener('dragleave', function()  { $sokonganZone.removeClass('dragover'); });
            $sokonganZone[0].addEventListener('drop',      function(e) {
                e.preventDefault(); $sokonganZone.removeClass('dragover');
                var files = e.dataTransfer.files;
                if (!files || !files.length) return;
                var arr = []; for (var i = 0; i < files.length; i++) arr.push(files[i]);
                arr.forEach(uploadSokongan);
            });

            // Pre-fill existing sokongan files
            if (sokonganFiles && sokonganFiles.length) {
                sokonganFiles.forEach(function(f) {
                    $('#file-chip-list-sokongan').append(buildSokonganChip(f.uuid, f.original_name, f.size));
                });
            }

            if (IS_SUBMITTED) {
                setFormReadonly(true);
            }

            // ─── LOADING OVERLAY ─────────────────────────────────────────────────────
            function blockUI(msg) {
                $('#loading-overlay').removeClass('success');
                $('#loading-text').text(msg || 'Menyimpan...');
                $('#loading-overlay').addClass('active');
            }
            function unblockUI() {
                $('#loading-overlay').removeClass('active success');
            }

            function updateStatusBadge() {
                $('#status-badge')
                    .attr('style', 'background:#dcfce7;color:#166534;font-size:0.8rem;border:1px solid #bbf7d0;')
                    .addClass('d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold')
                    .html('<span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span> Telah Dihantar');
            }

            function setFormReadonly(readonly) {
                if (!readonly) return;
                $('#tbl-kewangan input, #tbl-kewangan select, #input-penilaian, #input-harga-indikatif').prop('disabled', true);
                $('.btn-tambah-kewangan, .btn-hapus-kewangan, .btn-simpan, .btn-hantar').hide();
                $('#upload-zone-sokongan').hide();
            }

            // ─── SIMPAN ───────────────────────────────────────────────────────────────
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));

            $('.btn-simpan').on('click', function() {
                if (IS_SUBMITTED) return;
                blockUI('Menyimpan...');
                doSave(function() {
                    unblockUI();
                    successModal.show();
                }, function() {
                    unblockUI();
                    alert('Ralat semasa menyimpan. Sila cuba lagi.');
                });
            });

            var validasiHantarModal = new bootstrap.Modal(document.getElementById('validasiHantarModal'));

            // ─── HANTAR ───────────────────────────────────────────────────────────────
            $('.btn-hantar').on('click', function() {
                if (IS_SUBMITTED) return;
                // Check all rows are selesai
                var $rows      = $('#tbl-kewangan tbody tr:not(#tbl-empty-row)');
                var notSelesai = [];
                $rows.each(function() {
                    var status = $(this).attr('data-status');
                    if (status !== 'submitted' && status !== 'completed' && status !== 'complete') {
                        notSelesai.push(true);
                    }
                });
                if (notSelesai.length > 0) {
                    validasiHantarModal.show();
                    return;
                }

                var skemaMaksima = parseInt($('#skema-maksima-display').val()) || 0;
                var penilaian    = parseInt($('#input-penilaian').val()) || 0;
                if (skemaMaksima > 0 && penilaian > skemaMaksima) {
                    $('#input-penilaian').addClass('is-invalid').focus();
                    return;
                }

                blockUI('Menghantar...');
                doSave(function() {
                    $.ajax({
                        url:         SUBMIT_URL,
                        method:      'POST',
                        headers:     { 'X-CSRF-TOKEN': CSRF_TOKEN },
                        contentType: 'application/json',
                        data:        JSON.stringify({ passing_score: penilaian }),
                    })
                    .done(function(res) {
                        if (res && res.success) {
                            IS_SUBMITTED = true;
                            updateStatusBadge();
                            setFormReadonly(true);
                            $('#loading-text').text('Berjaya dihantar! Mengalih...');
                            $('#loading-overlay').addClass('success');
                            window.location.href = @json($afterSpecificationUrl ?? route('pengurusanSpesifikasi'));
                        } else {
                            unblockUI();
                            alert(res.message || 'Ralat semasa menghantar.');
                        }
                    })
                    .fail(function() {
                        unblockUI();
                        alert('Ralat semasa menghantar. Sila cuba lagi.');
                    });
                }, function() {
                    unblockUI();
                    alert('Ralat semasa menyimpan. Sila cuba lagi.');
                });
            });

        });
    </script>
@endsection
