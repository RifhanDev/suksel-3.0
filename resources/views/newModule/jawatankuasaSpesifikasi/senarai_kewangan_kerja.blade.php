@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        #tbl-kewangan { border: 1px solid #e2e8f0; }
        #tbl-kewangan th, #tbl-kewangan td { border-right: 1px solid #e2e8f0 !important; }
        #tbl-kewangan th:last-child, #tbl-kewangan td:last-child { border-right: none !important; }
        .rujukan-cell { min-width: 130px; max-width: 180px; }
        .file-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 2px 6px 2px 4px; font-size: 0.7rem;
            margin: 2px;
        }
        .file-chip .ext-badge {
            background: #64748b; color: #fff; border-radius: 3px;
            padding: 1px 4px; font-size: 0.6rem; font-weight: 700;
            text-transform: uppercase; flex-shrink: 0;
        }
        .file-chip a { color: #334155; font-weight: 600; max-width: 80px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; }
        .file-chip .chip-delete {
            background: none; border: none; padding: 0;
            color: #94a3b8; cursor: pointer; line-height: 1;
        }
        .file-chip .chip-delete:hover { color: #ef4444; }
        #toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; min-width: 280px; }
    </style>
@endsection

@section('content')

    {{-- Toast container --}}
    <div id="toast-container"></div>

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Semak Kewangan</h3>
            <p class="text-muted small m-0">Paparan senarai semak bahagian kewangan bagi tender / sebutharga (Kerja).</p>
        </div>
    </div>

    {{-- TENDER INFO --}}
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size:0.67rem;letter-spacing:0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height:1.45;font-size:1rem;">
                    {{ $tender->name ?? '-' }}
                    <span class="fw-normal text-muted fst-italic" style="font-size:0.85rem;">(Kerja)</span>
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size:0.67rem;letter-spacing:0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">
                        {{ $tender->no_tender ?: ($tender->ref_number ?? '-') }}
                    </span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size:0.67rem;letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">
                        {{ $tender->tenderer->name ?? '-' }}
                    </span>
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
                            style="background:#fef9c3;color:#854d0e;font-size:0.8rem;border:1px solid #fde68a;">
                            <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                            Dalam Proses
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="form-senarai-kewangan-kerja">
    @csrf

    {{-- ═══ SECTION 1: SENARAI SEMAK KEWANGAN ═══ --}}
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width:38px;height:38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size:1rem;">Penyediaan Spesifikasi &amp; Skor</h3>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Senarai Semak Kewangan — Kerja</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- {{-- Guidelines --}}
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
                        <span class="guideline-item-text">Senarai semak dijana berdasarkan item dari <span class="highlight">Penyediaan Spesifikasi Tender</span> yang telah dihantar.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">2</span>
                        <span class="guideline-item-text">Pilih <span class="highlight">Mekanisma</span> dan tetapkan <span class="highlight">Skema</span> markah untuk setiap item.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">3</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Tambah</span> untuk menambah item kewangan baru secara manual.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">4</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Senarai Semak Standard</span> untuk menambah item dari templat yang telah ditetapkan.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">5</span>
                        <span class="guideline-item-text">Klik <span class="highlight">Simpan</span> untuk menyimpan draf, kemudian <span class="highlight">Hantar</span> untuk menghantar senarai semak.</span>
                    </div>
                </div>
            </div> -->

            {{-- Table toolbar --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="#" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#senaraiSemakStandard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        Senarai Semak Standard
                    </a>
                </div>
                <div style="width:1px;height:24px;background:#e2e8f0;"></div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button"
                        class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-kewangan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                    <button type="button"
                        class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 btn-hapus-kewangan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-9.489 5.14a1 1 0 0 0-1.218 1.567L10.585 14l-1.292 1.293-.083.094a1 1 0 0 0 1.497 1.32L12 15.415l1.293 1.292.094.083a1 1 0 0 0 1.32-1.497L13.415 14l1.292-1.293.083-.094a1 1 0 0 0-1.497-1.32L12 12.585l-1.293-1.292-.094-.083zM14 2a2 2 0 0 1 2 2 1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>

            {{-- Main Table --}}
            <div class="table-responsive">
                <table id="tbl-kewangan" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:44px;">
                                <input type="checkbox" class="form-check-input px-0 check-all-kewangan">
                            </th>
                            <th style="min-width:180px;">Tajuk / Dokumen</th>
                            <th class="text-center" style="width:185px;">Mekanisma</th>
                            <th class="text-center" style="width:140px;">Tindakan Pembekal</th>
                            <th class="text-center" style="width:110px;">Skema</th>
                            <th class="text-center" style="width:110px;">Status</th>
                            <th class="text-center rujukan-cell">Dokumen</th>
                            <th class="text-center" style="width:110px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-kewangan-body">
                        <tr id="tbl-empty-row">
                            <td colspan="8" class="text-center text-muted py-4 small">Tiada Data</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;border-top:2px solid #e2e8f0;">
                            <td colspan="4" class="text-end py-3 pe-3">
                                <span class="small fw-semibold text-muted text-uppercase" style="letter-spacing:0.5px;">Skema Maksima</span>
                            </td>
                            <td class="text-center py-3">
                                <input type="text" id="skema-maksima-display" name="skema_maksima"
                                    class="form-control form-control-sm text-center fw-bold" value="0" readonly
                                    style="max-width:68px;margin:0 auto;background:#fff;border-color:#e2e8f0;">
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

    {{-- ═══ SECTION 2: HARGA INDIKATIF ═══ --}}
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width:38px;height:38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2m0 9h-1l-.117.007a1 1 0 0 0 0 1.986L11 13v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876zm.01-3l-.127.007a1 1 0 0 0 0 1.986L12 10l.127-.007a1 1 0 0 0 0-1.986z"/></svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size:1rem;">Harga Indikatif</h3>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Masukkan anggaran harga indikatif bagi perolehan ini</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <label class="fw-semibold text-dark mb-0 flex-shrink-0" style="font-size:0.875rem;">Harga Indikatif (RM)</label>
                <span class="fw-bold text-dark" style="font-size:1.1rem; letter-spacing: -0.2px;">
                    {{ number_format(floatval($tender->harga_indikatif ?? 0), 2) }}
                </span>
                <input type="hidden" id="input-harga-indikatif" name="harga_indikatif" value="{{ floatval($tender->harga_indikatif ?? 0) }}">
            </div>
        </div>
    </div>

    {{-- ═══ SECTION 3: PENETAPAN PENANDA ARAS ═══ --}}
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width:38px;height:38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size:1rem;">Penetapan Penanda Aras Tahap Lulus</h3>
            </div>
        </div>
        <div class="content-card-body p-4">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-2 h-100" style="background:#fafbfc;border:1px solid #f1f5f9;">
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2"
                            style="font-size:0.67rem;letter-spacing:0.5px;">Penilaian Kewangan</span>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="input-penilaian" name="input_penilaian"
                                class="form-control form-control-sm text-center fw-semibold"
                                style="max-width:80px;font-size:1rem;" placeholder="0" min="0"
                                value="{{ floatval($checklistData['passing_score'] ?? 0) }}">
                            <span class="text-muted small">daripada</span>
                            <span class="fw-bold text-dark" id="penilaian-kewangan-total" style="font-size:1rem;">0</span>
                            <span class="text-muted small">markah</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-2 h-100 d-flex flex-column justify-content-center"
                        style="background:#fafbfc;border:1px solid #f1f5f9;">
                        <span class="d-block text-muted fw-semibold text-uppercase mb-2"
                            style="font-size:0.67rem;letter-spacing:0.5px;">Tahap Lulus</span>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="fw-bold text-primary" id="tahap-lulus" style="font-size:1.75rem;line-height:1;">
                                {{ floatval($checklistData['passing_percentage'] ?? 0) }}
                            </span>
                            <span class="fw-semibold text-primary" style="font-size:1rem;">%</span>
                            <span class="text-muted small ms-1">peratus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ SECTION 4: DOKUMEN SOKONGAN ═══ --}}
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width:38px;height:38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size:1rem;">Dokumen Sokongan / Rujukan</h3>
            </div>
        </div>
        <div class="content-card-body p-4">
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
                <span class="upload-zone-sub">PDF, Word, Excel, Imej — saiz maksimum 10 MB setiap fail</span>
                <input type="file" id="input-dokumen-sokongan" multiple hidden
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
            </label>
            <div class="file-chip-list mt-3" id="file-chip-list-sokongan"></div>
        </div>
    </div>

    {{-- ═══ BOTTOM ACTION BUTTONS ═══ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="{{ route('pengurusanSpesifikasi') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
        <div id="action-btn-group" class="d-flex gap-2">
            <button type="button" id="btn-simpan" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
            <button type="button" id="btn-hantar" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12l5 5L20 7"></path>
                </svg>
                Hantar
            </button>
        </div>
    </div>

    </form>

@endsection

@push('modals')
    {{-- Modal: Senarai Semak Standard --}}
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
                                    <th class="text-center" style="width:44px;">
                                        <input type="checkbox" class="form-check-input check-all-standard">
                                    </th>
                                    <th>Tajuk / Dokumen</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-standard-body">
                                {{-- Populated by JS from $standardItems --}}
                                @forelse($standardItems ?? [] as $stdItem)
                                    <tr data-tajuk="{{ $stdItem['title'] }}" data-uuid="{{ $stdItem['uuid'] ?? '' }}">
                                        <td class="text-center"><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>{{ $stdItem['title'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted small py-3">Tiada item standard.</td></tr>
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
<script>
$(document).ready(function () {

    // ── CSRF Setup ────────────────────────────────────────────────────────────
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── Constants ─────────────────────────────────────────────────────────────
    const TENDER_UUID  = @json($tender->uuid);
    const SAVE_URL     = @json(route('senaraiKewanganKerja.store', $tender->uuid));
    const SUBMIT_URL   = @json(route('senaraiKewanganKerja.submit', $tender->uuid));
    const UPLOAD_URL   = @json(route('senaraiKewanganKerja.uploadFile', $tender->uuid));
    const DELETE_BASE  = @json(url('/senarai-kewangan-kerja/fail'));
    const USER_ID      = @json(auth()->id());
    const IS_SUBMITTED = @json(($checklistData['status'] ?? null) === 'submitted');

    var serverData     = @json($checklistData ?? null);

    var EDIT_ICON = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
    var PTJ_UPLOAD_BTN = '<label class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-1 mb-0 btn-ptj-upload" style="width:30px;height:30px;cursor:pointer;" title="Muat Naik"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg><input type="file" name="dokumen_ptj[]" hidden></label>';

    // ── Init ──────────────────────────────────────────────────────────────────
    if (serverData && serverData.items && serverData.items.length > 0) {
        loadData(serverData.items);
    }
    if (serverData) {
        $('#skema-maksima-display').val(serverData.max_score || 0);
        $('#penilaian-kewangan-total').text(serverData.max_score || 0);
        updateTahapLulus();
    }
    if (IS_SUBMITTED) { setFormReadonly(true); }

    // ── Load data into table ──────────────────────────────────────────────────
    function loadData(items) {
        $('#tbl-empty-row').addClass('d-none');
        items.forEach(function (item) {
            var $tr;
            if (item.source_type === 'borang_atas_talian' && item.action_url) {
                $tr = buildInitialRow({
                    uuid:                  item.uuid,
                    tajuk:                 item.title,
                    mekanisma:             'Borang Atas Talian',
                    tindakanPembekal:      'Kunci Masuk',
                    skema:                 item.score || 0,
                    status:                item.status === 'submitted' ? 'Selesai' : 'Draf',
                    statusClass:           item.status === 'submitted' ? 'badge-status-success' : 'badge-status-warning',
                    tindakanUrl:           item.action_url + '/' + TENDER_UUID,
                    score:                 item.score || 0,
                    standard_item_uuid:    item.standard_item_uuid || '',
                    spesifikasi_item_uuid: item.spesifikasi_item_uuid || '',
                    statusKey:             item.status || 'draft',
                    form_updated:          item.form_updated ?? true,
                });
            } else {
                $tr = buildEditableRow(item);
            }
            $('#tbl-kewangan-body').append($tr);
        });
        syncTableEmpty();
        updateSkemaMaksima();
    }

    function buildInitialRow(data) {
        var statusVal = data.statusKey || 'draft';

        var kemaskiniButton = '-';

        if (data.tajuk === 'Penyata Bulanan / Akaun Bank') {
            kemaskiniButton =
                '<a href="' + data.tindakanUrl + '" class="btn btn-sm btn-warning d-inline-flex align-items-center justify-content-center p-1" style="width:30px;height:30px;" title="Kemaskini">' +
                EDIT_ICON +
                '</a>';
        }

        var formUpdatedAttr = (data.form_updated !== undefined) ? ' data-form-updated="' + data.form_updated + '"' : '';

        return $(
            '<tr class="initial-row" data-uuid="' + (data.uuid || '') + '" data-source="borang_atas_talian" data-standard-item-uuid="' + (data.standard_item_uuid || '') + '" data-spesifikasi-item-uuid="' + (data.spesifikasi_item_uuid || '') + '" data-status="' + statusVal + '"' + formUpdatedAttr + '>' +
            '<td class="text-center"><span class="text-muted" style="font-size:0.75rem;" title="Item sistem">—</span></td>' +
            '<td><span class="small fw-semibold">' + htmlEscape(data.tajuk) + '</span></td>' +
            '<td class="text-center"><span class="small fw-semibold text-muted">' + data.mekanisma + '</span></td>' +
            '<td class="text-center small">' + data.tindakanPembekal + '</td>' +
            '<td class="text-center"><input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="' + data.score + '" min="0" style="max-width:90px;margin:0 auto;"></td>' +
            '<td class="text-center"><span class="badge-status ' + data.statusClass + '">' + data.status + '</span></td>' +
            '<td class="text-center text-muted small rujukan-cell">—</td>' +
            '<td class="text-center">' + kemaskiniButton + '</td>' +
            '</tr>'
        );
    }

    function buildEditableRow(item) {
        item = item || {};
        var uuid      = item.uuid || '';
        var mechanism = item.mechanism || 'petender_muat_naik';
        var score     = item.score || 0;
        var status    = item.status || 'draft';
        var standardItemUuid = item.standard_item_uuid || '';
        var spesifikasiItemUuid = item.spesifikasi_item_uuid || '';
        var sourceType = item.source_type || 'manual';

        var titleCell = (sourceType === 'manual')
            ? '<input type="text" name="tajuk_dokumen[]" class="form-control form-control-sm" placeholder="Tajuk / Dokumen..." value="' + htmlEscape(item.title || '') + '">'
            : '<span class="small fw-semibold">' + htmlEscape(item.title || '') + '</span>';

        var $tr = $(
            '<tr class="row-kewangan-tambah" data-uuid="' + uuid + '" data-source="' + sourceType + '" data-standard-item-uuid="' + standardItemUuid + '" data-spesifikasi-item-uuid="' + spesifikasiItemUuid + '" data-status="' + status + '">' +
            '<td class="text-center"><input type="checkbox" name="row_check_kewangan[]" class="form-check-input row-check-kewangan"></td>' +
            '<td>' + titleCell + '</td>' +
            '<td class="text-center">' +
                '<select name="mekanisma[]" class="form-select form-select-sm mekanisma-select" style="font-size:0.78rem;">' +
                    '<option value="petender_muat_naik"' + (mechanism === 'petender_muat_naik' ? ' selected' : '') + '>Petender Muat Naik</option>' +
                    '<option value="ptj_muat_naik"' + (mechanism === 'ptj_muat_naik' ? ' selected' : '') + '>PTJ Muat Naik</option>' +
                '</select>' +
            '</td>' +
            '<td class="text-center tindakan-pembekal">' + buildTindakanPembekalCell(mechanism) + '</td>' +
            '<td class="text-center"><input type="number" name="skema[]" class="form-control form-control-sm text-center skema-input fw-semibold" value="' + score + '" min="0" style="max-width:90px;margin:0 auto;"></td>' +
            '<td class="text-center"><span class="badge-status ' + (status === 'submitted' ? 'badge-status-success' : 'badge-status-warning') + '">' + (status === 'submitted' ? 'Selesai' : 'Draf') + '</span></td>' +
            '<td class="text-center rujukan-cell">' + buildDokumenCell(mechanism) + '</td>' +
            '<td class="text-center tindakan-cell">' + buildTindakanCell(mechanism) + '</td>' +
            '</tr>'
        );

        // Render existing files
        if (item.files && item.files.length > 0) {
            item.files.forEach(function (f) {
                appendFileToDokumenCell($tr.find('.rujukan-cell'), f, uuid);
            });
        }

        return $tr;
    }

    function buildNewRow() {
        return buildEditableRow({ source_type: 'manual' });
    }

    function buildStandardRow(tajuk, uuid) {
        return buildEditableRow({ title: tajuk, standard_item_uuid: uuid, source_type: 'standard' });
    }

    // ── Helper: append file chip to a dokumen cell ────────────────────────────
    function appendFileToDokumenCell($cell, fileData, itemUuid) {
        var ext = fileData.original_name.split('.').pop().toLowerCase();
        var $chip = $(
            '<div class="file-chip" data-file-uuid="' + fileData.uuid + '">' +
            '<span class="ext-badge">' + htmlEscape(ext) + '</span>' +
            '<a href="' + fileData.url + '" target="_blank" title="' + htmlEscape(fileData.original_name) + '">' + htmlEscape(fileData.original_name) + '</a>' +
            '<button type="button" class="chip-delete" title="Padam fail">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
            '</button>' +
            '</div>'
        );
        $chip.find('.chip-delete').on('click', function () {
            if (!confirm('Padam fail ini?')) return;
            deleteFile(fileData.uuid, $chip);
        });
        $cell.find('.dokumen-ptj-list').append($chip);
    }

    // ── Mechanism cell helpers ─────────────────────────────────────────────────
    function buildTindakanPembekalCell(mechanism) {
        if (mechanism === 'ptj_muat_naik') {
            return '<select name="tindakan_pembekal[]" class="form-select form-select-sm tindakan-pembekal-select" style="font-size:0.78rem;">' +
                '<option value="muat_turun">Muat Turun</option>' +
                '<option value="muat_turun_naik">Muat Turun &amp; Muat Naik</option>' +
                '</select>';
        }
        return '<span class="small">Muat Naik</span>';
    }

    function buildTindakanCell(mechanism) {
        if (mechanism === 'ptj_muat_naik') return PTJ_UPLOAD_BTN;
        return '<span class="text-muted small">—</span>';
    }

    function buildDokumenCell(mechanism) {
        if (mechanism === 'ptj_muat_naik') {
            return '<div class="dokumen-ptj-list d-flex flex-column gap-1 align-items-start" style="min-width:120px;max-height:72px;overflow-y:auto;"></div>';
        }
        return '<div class="dokumen-ptj-list d-flex flex-column gap-1 align-items-start" style="min-width:120px;max-height:72px;overflow-y:auto;"></div>';
    }

    // ── Collect payload ────────────────────────────────────────────────────────
    function collectPayload() {
        var items = [];
        $('#tbl-kewangan-body tr[data-uuid], #tbl-kewangan-body .row-kewangan-tambah').each(function (idx) {
            var $tr    = $(this);
            var uuid   = $tr.data('uuid') || null;
            var source = $tr.data('source') || 'manual';
            var title  = $tr.find('[name="tajuk_dokumen[]"]').val() || $tr.find('td:eq(1) span').text().trim();
            var mech   = $tr.find('[name="mekanisma[]"]').val() || (source === 'borang_atas_talian' ? 'borang_atas_talian' : null);
            var score  = parseFloat($tr.find('.skema-input').val()) || 0;
            var standardItemUuid = $tr.data('standard-item-uuid') || null;
            var spesifikasiItemUuid = $tr.data('spesifikasi-item-uuid') || null;
            var status = $tr.attr('data-status') || 'draft';

            if (!title) return;

            items.push({
                uuid:                  uuid,
                source_type:           source,
                title:                 title,
                mechanism:             mech,
                score:                 score,
                sort_order:            idx,
                standard_item_uuid:    standardItemUuid,
                spesifikasi_item_uuid: spesifikasiItemUuid,
                status:                status,
            });
        });
        return items;
    }

    // ── Validation ────────────────────────────────────────────────────────────
    function validateBeforeSubmit() {
        var rows = $('#tbl-kewangan-body tr[data-uuid], #tbl-kewangan-body .row-kewangan-tambah');
        if (rows.length === 0) {
            showToast('Sila tambah sekurang-kurangnya satu item senarai semak sebelum menghantar.', 'danger');
            return false;
        }
        var maxScore    = parseFloat($('#skema-maksima-display').val()) || 0;
        var passingScore = parseFloat($('#input-penilaian').val()) || 0;
        if (maxScore > 0 && passingScore > maxScore) {
            showToast('Skor lulus tidak boleh melebihi skor maksima (' + maxScore + ').', 'danger');
            $('#input-penilaian').addClass('is-invalid');
            return false;
        }
        return true;
    }

    // ── Save draft ────────────────────────────────────────────────────────────
    function saveDraft(callback) {
        var items       = collectPayload();
        var passingScore = parseFloat($('#input-penilaian').val()) || 0;
        var harga       = parseFloat($('#input-harga-indikatif').val().replace(/,/g, '')) || 0;

        setBusy('#btn-simpan', true, 'Menyimpan...');

        $.ajax({
            url:         SAVE_URL,
            method:      'POST',
            contentType: 'application/json',
            data:        JSON.stringify({
                items:           items,
                passing_score:   passingScore,
                harga_indikatif: harga,
                status:          'draft',
                user_id:         USER_ID,
            }),
            success: function (response) {
                if (response && response.success && response.data) {
                    // Re-render with server data (assigns UUIDs to new rows)
                    $('#tbl-kewangan-body').empty();
                    loadData(response.data.items);
                    $('#skema-maksima-display').val(response.data.max_score || 0);
                    $('#penilaian-kewangan-total').text(response.data.max_score || 0);
                    updateTahapLulus();
                    showToast('Senarai kewangan berjaya disimpan.', 'success');
                    if (typeof callback === 'function') callback(response);
                } else {
                    showToast('Gagal menyimpan. Sila cuba lagi.', 'danger');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan.';
                showToast(msg, 'danger');
            },
            complete: function () {
                setBusy('#btn-simpan', false, 'Simpan');
            }
        });
    }

    // ── Submit ────────────────────────────────────────────────────────────────
    function submitChecklist() {
        if (!validateBeforeSubmit()) return;
        if (!confirm('Hantar senarai kewangan ini? Selepas menghantar, borang tidak boleh diedit.')) return;

        saveDraft(function () {
            var passingScore = parseFloat($('#input-penilaian').val()) || 0;
            var harga        = parseFloat($('#input-harga-indikatif').val().replace(/,/g, '')) || 0;
            setBusy('#btn-hantar', true, 'Menghantar...');

            $.ajax({
                url:         SUBMIT_URL,
                method:      'POST',
                contentType: 'application/json',
                data:        JSON.stringify({ passing_score: passingScore, harga_indikatif: harga, user_id: USER_ID }),
                success: function (response) {
                    if (response && response.success) {
                        showToast('Senarai kewangan berjaya dihantar!', 'success');
                        updateStatusBadge('submitted');
                        setFormReadonly(true);
                    } else {
                        var msg = (response && response.message) ? response.message : 'Gagal menghantar.';
                        showToast(msg, 'danger');
                        setBusy('#btn-hantar', false, 'Hantar');
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON && xhr.responseJSON.errors;
                    var msg    = errors && errors.passing_score ? errors.passing_score[0] : 'Gagal menghantar. Sila cuba lagi.';
                    showToast(msg, 'danger');
                    setBusy('#btn-hantar', false, 'Hantar');
                }
            });
        });
    }

    // ── Delete file ───────────────────────────────────────────────────────────
    function deleteFile(fileUuid, $chip) {
        $.ajax({
            url:    DELETE_BASE + '/' + fileUuid,
            method: 'DELETE',
            success: function (response) {
                if (response && response.success) {
                    $chip.remove();
                    showToast('Fail berjaya dipadam.', 'success');
                } else {
                    showToast('Gagal memadam fail.', 'danger');
                }
            },
            error: function () { showToast('Gagal memadam fail. Sila cuba lagi.', 'danger'); }
        });
    }

    // ── UI Helpers ─────────────────────────────────────────────────────────────
    function updateRowStatus($row) {
        var scoreVal = $row.find('.skema-input').val();
        var isFilled = (scoreVal !== undefined && scoreVal !== null && scoreVal.trim() !== '');

        var title = $row.find('[name="tajuk_dokumen[]"]').val() || $row.find('td:eq(1) span').text().trim();

        var isComplete = false;

        if (title === 'Penyata Bulanan / Akaun Bank') {
            var formUpdated = $row.attr('data-form-updated') === 'true';
            isComplete = isFilled && formUpdated;
        } else {
            isComplete = isFilled;
        }

        var $badge = $row.find('.badge-status');
        if (isComplete) {
            $badge.removeClass('badge-status-warning').addClass('badge-status-success').text('Selesai');
            $row.attr('data-status', 'submitted');
        } else {
            $badge.removeClass('badge-status-success').addClass('badge-status-warning').text('Draf');
            $row.attr('data-status', 'draft');
        }
    }

    function updateSkemaMaksima() {
        var total = 0;
        $('#tbl-kewangan .skema-input').each(function () { total += parseFloat($(this).val()) || 0; });
        $('#skema-maksima-display').val(total);
        $('#penilaian-kewangan-total').text(total);
        $('#input-penilaian').attr('max', total);
        var cur = parseFloat($('#input-penilaian').val()) || 0;
        $('#input-penilaian').toggleClass('is-invalid', total > 0 && cur > total);
        updateTahapLulus();
    }

    function updateTahapLulus() {
        var max  = parseFloat($('#skema-maksima-display').val()) || 0;
        var pass = parseFloat($('#input-penilaian').val()) || 0;
        var pct  = max > 0 ? Math.round((pass / max) * 100 * 100) / 100 : 0;
        $('#tahap-lulus').text((pct % 1 === 0) ? pct : pct.toFixed(2));
    }

    function syncTableEmpty() {
        var real = $('#tbl-kewangan tbody tr:not(#tbl-empty-row)').length;
        if (real === 0) $('#tbl-empty-row').removeClass('d-none');
        else            $('#tbl-empty-row').addClass('d-none');
    }

    function updateStatusBadge(status) {
        if (status !== 'submitted') return;
        $('#status-badge')
            .attr('style', 'background:#dcfce7;color:#166534;font-size:0.8rem;border:1px solid #bbf7d0;')
            .addClass('d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold')
            .html('<span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span> Telah Dihantar');
    }

    function setFormReadonly(readonly) {
        if (!readonly) return;
        $('#tbl-kewangan input, #tbl-kewangan select, #input-penilaian, #input-harga-indikatif').prop('disabled', true);
        $('#btn-tambah-kewangan, #btn-hapus-kewangan, #btn-simpan, #btn-hantar').hide();
        $('#upload-zone-sokongan').hide();
    }

    function setBusy(selector, busy, label) {
        $(selector).prop('disabled', busy).text(busy ? label : label);
    }

    function showToast(message, type) {
        var colors = { success: '#dcfce7', danger: '#fee2e2', warning: '#fef9c3', info: '#e0f2fe' };
        var texts  = { success: '#166534', danger: '#991b1b', warning: '#854d0e', info: '#0c4a6e' };
        var id = 'toast-' + Date.now();
        var $t = $('<div id="' + id + '" style="background:' + (colors[type]||colors.info) + ';color:' + (texts[type]||texts.info) + ';border:1px solid;border-radius:8px;padding:0.75rem 1rem;margin-bottom:0.5rem;font-size:0.85rem;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.1);">' + message + '</div>');
        $('#toast-container').append($t);
        setTimeout(function () { $t.fadeOut(400, function () { $(this).remove(); }); }, 4000);
    }

    function htmlEscape(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Event Handlers ────────────────────────────────────────────────────────

    // Check-all
    $('#tbl-kewangan').on('change', '.check-all-kewangan', function () {
        $('#tbl-kewangan .row-check-kewangan').prop('checked', $(this).prop('checked'));
    });
    $('#tbl-kewangan').on('change', '.row-check-kewangan', function () {
        var total   = $('#tbl-kewangan .row-check-kewangan').length;
        var checked = $('#tbl-kewangan .row-check-kewangan:checked').length;
        $('#tbl-kewangan .check-all-kewangan').prop('checked', total === checked);
    });

    // Tambah row
    $('.btn-tambah-kewangan').on('click', function () {
        $('#tbl-empty-row').addClass('d-none');
        $('#tbl-kewangan-body').append(buildNewRow());
        syncTableEmpty();
        updateSkemaMaksima();
    });

    // Hapus checked rows
    $('.btn-hapus-kewangan').on('click', function () {
        var $checked = $('#tbl-kewangan .row-check-kewangan:checked');
        if ($checked.length === 0) { alert('Sila pilih sekurang-kurangnya satu rekod untuk dihapus.'); return; }
        $checked.closest('tr').remove();
        $('#tbl-kewangan .check-all-kewangan').prop('checked', false);
        syncTableEmpty();
        updateSkemaMaksima();
    });

    // Mekanisma change
    $('#tbl-kewangan').on('change', '.mekanisma-select', function () {
        var val  = $(this).val();
        var $row = $(this).closest('tr');
        $row.find('.tindakan-pembekal').html(buildTindakanPembekalCell(val));
        $row.find('.rujukan-cell').html(buildDokumenCell(val));
        $row.find('.tindakan-cell').html(buildTindakanCell(val));
    });

    // PTJ tindakan pembekal change
    $('#tbl-kewangan').on('change', '.tindakan-pembekal-select', function () {
        $(this).closest('tr').find('.tindakan-cell').html(PTJ_UPLOAD_BTN);
    });

    // Skema input change
    $('#tbl-kewangan').on('input change', '.skema-input', function () {
        updateSkemaMaksima();
        updateRowStatus($(this).closest('tr'));
    });

    // PTJ file upload → save row first if needed, then upload to backend
    $('#tbl-kewangan').on('change', '.tindakan-cell input[type="file"]', function () {
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
            fd.append('user_id', USER_ID);

            $.ajax({
                url:         UPLOAD_URL,
                method:      'POST',
                data:        fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    var f = res && res.data ? res.data : null;
                    if (!f) return;
                    appendFileToDokumenCell($row.find('.rujukan-cell'), f, rowUuid);
                    showToast('Fail berjaya dimuat naik.', 'success');
                },
                error: function () {
                    showToast('Gagal memuat naik fail. Sila cuba lagi.', 'danger');
                }
            });
        }

        var rowUuid = $row.data('uuid') || $row.attr('data-uuid');
        if (rowUuid) {
            doUpload(rowUuid);
        } else {
            saveDraft(function (response) {
                var index = $('#tbl-kewangan-body tr').index($row);
                var newUuid = (response.data && response.data.items && response.data.items[index]) ? response.data.items[index].uuid : null;
                if (newUuid) {
                    doUpload(newUuid);
                } else {
                    showToast('Gagal mendapatkan ID baris. Sila simpan semula dan cuba lagi.', 'danger');
                }
            });
        }
    });

    // Penilaian input validate
    $('#input-penilaian').on('input change', function () {
        var max = parseFloat($('#skema-maksima-display').val()) || 0;
        var val = parseFloat($(this).val()) || 0;
        $(this).toggleClass('is-invalid', max > 0 && val > max);
        updateTahapLulus();
    });

    // Harga Indikatif formatting
    $('#input-harga-indikatif').on('blur', function () {
        var raw = parseFloat($(this).val().replace(/,/g, '')) || 0;
        $(this).val(raw.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    });

    // Senarai Semak Standard: check-all in modal
    $('#senaraiSemakStandard').on('change', '.check-all-standard', function () {
        $('#tbl-standard .row-check-standard').prop('checked', $(this).prop('checked'));
    });
    $('#senaraiSemakStandard').on('change', '.row-check-standard', function () {
        var total   = $('#tbl-standard .row-check-standard').length;
        var checked = $('#tbl-standard .row-check-standard:checked').length;
        $('#tbl-standard .check-all-standard').prop('checked', total === checked);
    });

    // Pilih standard items
    $('#senaraiSemakStandard').on('click', '.btn-pilih-standard', function () {
        var $checked = $('#tbl-standard .row-check-standard:checked');
        if ($checked.length === 0) { alert('Sila pilih sekurang-kurangnya satu senarai semak.'); return; }
        $checked.each(function () {
            var $tr  = $(this).closest('tr');
            var tajuk = $tr.data('tajuk') || $tr.find('td:last-child').text().trim();
            var uuid  = $tr.data('uuid') || '';
            $('#tbl-kewangan-body').append(buildStandardRow(tajuk, uuid));
            $tr.hide();
        });
        updateSkemaMaksima();
        syncTableEmpty();
        $('#tbl-standard .row-check-standard, #tbl-standard .check-all-standard').prop('checked', false);
        bootstrap.Modal.getInstance($('#senaraiSemakStandard')[0]).hide();
    });

    // Simpan button
    $('#btn-simpan').on('click', function () { if (!IS_SUBMITTED) saveDraft(); });

    // Hantar button
    $('#btn-hantar').on('click', function () { if (!IS_SUBMITTED) submitChecklist(); });

    // Header-level document upload
    $('#input-dokumen-sokongan').on('change', function () {
        if (!this.files || !this.files.length) return;
        var $list = $('#file-chip-list-sokongan');
        $.each(this.files, function (i, file) {
            var formData = new FormData();
            formData.append('file', file);
            formData.append('file_type', 'support');
            formData.append('user_id', USER_ID);

            $.ajax({
                url:         UPLOAD_URL,
                method:      'POST',
                data:        formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response && response.success && response.data) {
                        var f = response.data;
                        var ext = f.original_name.split('.').pop().toLowerCase();
                        var $chip = $(
                            '<div class="file-chip" data-file-uuid="' + f.uuid + '">' +
                            '<span class="ext-badge">' + htmlEscape(ext) + '</span>' +
                            '<a href="' + f.url + '" target="_blank">' + htmlEscape(f.original_name) + '</a>' +
                            '<button type="button" class="chip-delete header-file-delete" title="Padam">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                            '</button>' +
                            '</div>'
                        );
                        $chip.find('.header-file-delete').on('click', function () {
                            if (!confirm('Padam fail ini?')) return;
                            deleteFile(f.uuid, $chip);
                        });
                        $list.append($chip);
                        showToast('Fail berjaya dimuat naik.', 'success');
                    } else {
                        showToast('Gagal memuat naik fail.', 'danger');
                    }
                },
                error: function () { showToast('Gagal memuat naik fail. Sila cuba lagi.', 'danger'); }
            });
        });
        $(this).val('');
    });

    // Render header-level existing files
    if (serverData && serverData.files && serverData.files.length > 0) {
        var $list = $('#file-chip-list-sokongan');
        serverData.files.forEach(function (f) {
            var ext = f.original_name.split('.').pop().toLowerCase();
            var $chip = $(
                '<div class="file-chip" data-file-uuid="' + f.uuid + '">' +
                '<span class="ext-badge">' + htmlEscape(ext) + '</span>' +
                '<a href="' + f.url + '" target="_blank">' + htmlEscape(f.original_name) + '</a>' +
                '<button type="button" class="chip-delete header-file-delete" title="Padam">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                '</button>' +
                '</div>'
            );
            var fileUuid = f.uuid;
            $chip.find('.header-file-delete').on('click', function () {
                if (!confirm('Padam fail ini?')) return;
                deleteFile(fileUuid, $chip);
            });
            $list.append($chip);
        });
    }

    // Initial calculations
    updateSkemaMaksima();
    syncTableEmpty();

});
</script>
@endsection
