@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --kewangan-accent: #c41e3a;
        --kewangan-accent-dark: #8b1428;
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    /* ========================
       TENDER SUMMARY CARD
    ======================== */
    .tender-summary-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .tender-summary-header {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        padding: 1.25rem 1.75rem;
        color: #ffffff;
    }

    .tender-summary-body {
        padding: 1.5rem 1.75rem;
    }

    .info-grid-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.15rem;
        height: 100%;
        transition: all 0.2s ease-in-out;
    }

    .info-grid-box:hover {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border-color: #cbd5e1;
    }

    .info-grid-label {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .info-grid-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .tender-badge-mono {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--sg-red-dark);
        background: var(--sg-red-light);
        border: 1px solid rgba(220, 38, 38, 0.2);
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        display: inline-block;
    }

    .status-pill-process {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-pill-process .pulse-dot {
        width: 7px;
        height: 7px;
        background-color: #f18705ff;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4);
        animation: pulse-ring 1.8s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.5); }
        70% { box-shadow: 0 0 0 6px rgba(217, 119, 6, 0); }
        100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
    }

    .btn-sebelumnya {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1rem;
        transition: all 0.2s ease-in-out;
    }

    .btn-sebelumnya:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    /* Section Stage Cards */
    .stage-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 2rem;
    }

    .stage-header-p1 {
        background: linear-gradient(135deg, #7f1d1d 0%, #b91c1c 100%);
        color: #ffffff;
        padding: 1.25rem 1.75rem;
    }

    .stage-header-p2 {
        background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%);
        color: #ffffff;
        padding: 1.25rem 1.75rem;
    }

    .stage-header-p3 {
        background: linear-gradient(135deg, #881337 0%, #e11d48 100%);
        color: #ffffff;
        padding: 1.25rem 1.75rem;
    }

    /* Borang Action Cards */
    .borang-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.35rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        text-decoration: none;
        color: inherit;
    }

    .borang-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        text-decoration: none;
        color: inherit;
    }

    .borang-card-p1:hover {
        border-color: #dc2626;
    }

    .borang-card-p2:hover {
        border-color: #ef4444;
    }

    .borang-card-p3:hover {
        border-color: #f43f5e;
    }

    .icon-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .icon-avatar-p1 {
        background: rgba(220, 38, 38, 0.1);
        color: #dc2626;
    }

    .icon-avatar-p2 {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .icon-avatar-p3 {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
    }

    .borang-badge {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        letter-spacing: 0.3px;
        display: inline-block;
    }

    .sub-link-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.775rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    a.sub-link-pill:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    .sub-link-pill-p2:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    .sub-link-pill-p3:hover {
        background: #f43f5e;
        color: #ffffff;
        border-color: #f43f5e;
    }

    /* ========================
       SIMPLIFIED STAGE TAB SWITCHER
    ======================== */
    .stage-tab-simple {
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        display: flex;
        gap: 4px;
        margin-bottom: 1.5rem;
    }

    .stage-tab-simple .nav-item {
        flex: 1;
    }

    .stage-tab-simple .nav-link {
        width: 100%;
        color: #64748b;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.55rem 1rem;
        border-radius: 9px;
        transition: all 0.2s ease-in-out;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .stage-tab-simple .nav-link:hover {
        color: #1e293b;
        background: rgba(255, 255, 255, 0.5);
    }

    .stage-tab-simple .nav-link.active {
        background: #ffffff !important;
        color: #dc2626 !important;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .stage-tab-simple .nav-link .badge {
        font-size: 0.725rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
    }

    .stage-tab-simple .nav-link.active .badge {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid rgba(220, 38, 38, 0.2) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Senarai Penilaian Kewangan</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Penilaian Kewangan (Kerja)</li>
            </ol>
        </nav>
        <a href="{{ route('penilaianKewangan') }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Senarai</span>
        </a>
    </div>

    {{-- Tender Summary Info Card --}}
    <div class="tender-summary-card">
        <div class="tender-summary-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bank2 fs-5"></i>
                <h5 class="fw-bold mb-0 text-white" style="letter-spacing: -0.3px;">RINGKASAN TENDER & PEROLEHAN</h5>
            </div>
            <span class="status-pill-process bg-warning text-white border-0">
                <span class="pulse-dot"></span>
                {{ $status_label ?? 'Menunggu Penilaian Kewangan' }}
            </span>
        </div>
        <div class="tender-summary-body">
            <div class="row g-3">
                
                <!-- No Tender -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-hash text-danger"></i>No. Sebut Harga / Tender
                        </div>
                        <div class="info-grid-value">
                            <span class="tender-badge-mono">{{ $no_tender_display ?? $tender->no_tender ?? $tender->ref_number ?? $tender_no ?? 'Belum Dijana' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tempoh Sah Laku -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-hourglass-split text-danger"></i>Tempoh Sah Laku Tawaran
                        </div>
                        <div class="info-grid-value">
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace fs-6 fw-bold">
                                {{ $tempoh_sah_laku ?? 90 }} Hari
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PTJ -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-building text-danger"></i>PTJ / Jabatan
                        </div>
                        <div class="info-grid-value text-truncate" title="{{ $ptj_display ?? $tender->tenderer->name ?? '-' }}">
                            {{ $ptj_display ?? $tender->tenderer->name ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- Tajuk Perolehan -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-file-earmark-text text-danger"></i>Tajuk Perolehan
                        </div>
                        <div class="info-grid-value small text-dark" style="line-height: 1.4;">
                            {{ $tajuk_display ?? $tender->name ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-info-circle text-danger"></i>Status Peringkat
                        </div>
                        <div class="info-grid-value">
                            <span class="status-pill-process">
                                <span class="pulse-dot"></span>
                                {{ $status_label ?? 'Menunggu Penilaian Kewangan' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Sah Laku Tamat -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-calendar-check text-danger"></i>Sah Laku Tawaran Tamat
                        </div>
                        <div class="info-grid-value font-monospace">
                            <i class="bi bi-calendar-event text-secondary me-1"></i>{{ $sah_laku_tamat ?? '-' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4">
            {{-- ========================================================================= --}}
            {{-- STAGE NAV TAB SWITCHER --}}
            {{-- ========================================================================= --}}
            {{-- Simplified Stage Nav Tab Switcher --}}
            <ul class="nav nav-pills stage-tab-simple" id="peringkat-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-p1" data-bs-toggle="pill" data-bs-target="#pane-p1" type="button" role="tab" aria-controls="pane-p1" aria-selected="true">
                        <i class="bi bi-layers me-1 text-danger"></i>
                        <span>Peringkat 1</span>
                        <span class="badge bg-light text-secondary border ms-1">Borang 1 – 6</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-p2" data-bs-toggle="pill" data-bs-target="#pane-p2" type="button" role="tab" aria-controls="pane-p2" aria-selected="false">
                        <i class="bi bi-cpu me-1 text-danger"></i>
                        <span>Peringkat 2</span>
                        <span class="badge bg-light text-secondary border ms-1">Borang 7 – 12</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-p3" data-bs-toggle="pill" data-bs-target="#pane-p3" type="button" role="tab" aria-controls="pane-p3" aria-selected="false">
                        <i class="bi bi-award me-1 text-danger"></i>
                        <span>Peringkat 3</span>
                        <span class="badge bg-light text-secondary border ms-1">Borang 13 – 15</span>
                    </button>
                </li>
            </ul>

            {{-- ========================================================================= --}}
            {{-- STAGE TAB CONTENT PANES --}}
            {{-- ========================================================================= --}}
            <div class="tab-content" id="peringkat-tab-content">

                {{-- PANE 1: PERINGKAT PERTAMA (BORANG 1 - 6) --}}
                <div class="tab-pane fade show active mt-4" id="pane-p1" role="tabpanel" aria-labelledby="tab-p1">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger-subtle p-2 rounded-2 me-3">
                            <i class="bi bi-layers text-danger fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Penilaian Peringkat Pertama
                                <span class="badge bg-warning text-white border border-white rounded-pill px-3 py-1 text-uppercase small" style="font-size: 0.7rem;">6 Komponen Borang</span>
                            </h5>
                            <p class="text-secondary small mb-0">Semakan Dokumen Wajib, Jadual Penyata Tender, Nisbah Kewangan & Kelayakan Peringkat 1 (Borang 1 – 6)</p>
                        </div>
                    </div>
            
                    <div class="row g-3">

                        <!-- Borang 1 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang1', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p1">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p1">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 1</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Jadual Penyata Tender</h6>
                                    <p class="text-muted extra-small mb-3">Jadual penyerahan tender, maklumat asas petender & harga tawaran.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 2 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang2', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p1">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p1">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 2</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Semakan Dokumen Wajib</h6>
                                    <p class="text-muted extra-small mb-3">Semakan kecukupan & kelayakan dokumen kewangan wajib petender.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 3 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang3', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p1">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p1">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 3</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Nisbah Asas Kewangan</h6>
                                    <p class="text-muted extra-small mb-3">Penilaian nisbah kewangan, penyata bank & lembaran imbangan.</p>
                                    <div class="d-flex flex-wrap gap-1.5 mb-3">
                                        <span class="sub-link-pill">
                                            <i class="bi bi-file-earmark-text"></i>Borang 3
                                        </span>
                                        <span class="sub-link-pill">
                                            <i class="bi bi-journal-text"></i>Lembaran
                                        </span>
                                        <span class="sub-link-pill">
                                            <i class="bi bi-bank"></i>Akaun Bank
                                        </span>
                                        <span class="sub-link-pill">
                                            <i class="bi bi-cash-coin"></i>Bon / Saham
                                        </span>
                                    </div>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 4 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang4', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p1">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p1">
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 4</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Keupayaan Kewangan</h6>
                                    <p class="text-muted extra-small mb-3">Penilaian modal pusingan & had kelayakan kewangan petender.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 5 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang5', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p1">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p1">
                                            <i class="bi bi-card-checklist"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 5</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Keputusan Peringkat Pertama</h6>
                                    <p class="text-muted extra-small mb-3">Jadual keputusan & rumusan kelayakan peringkat pertama.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 6 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang6', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p1">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p1">
                                            <i class="bi bi-list-stars"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 6</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Petender Lulus Peringkat 1</h6>
                                    <p class="text-muted extra-small mb-3">Senarai petender lulus disusun mengikut turutan harga tender.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- PANE 2: PERINGKAT KEDUA (BORANG 7 - 12) --}}
                <div class="tab-pane fade mt-4" id="pane-p2" role="tabpanel" aria-labelledby="tab-p2">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger-subtle p-2 rounded-2 me-3">
                            <i class="bi bi-cpu text-danger fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Penilaian Peringkat Kedua
                                <span class="badge bg-warning text-white border border-white rounded-pill px-3 py-1 text-uppercase small" style="font-size: 0.7rem;">6 Komponen Borang</span>
                            </h5>
                            <p class="text-secondary small mb-0">Analisis Keupayaan Teknikal, Prestasi Kerja Semasa & Pengalaman (Borang 7 – 12)</p>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- Borang 7 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="borang-card borang-card-p2">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p2">
                                            <i class="bi bi-bar-chart-steps"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 7</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Analisa Keupayaan Petender</h6>
                                    <p class="text-muted extra-small mb-2">Analisis data penilaian keupayaan petender (Serupa / Sebanding).</p>
                                    <div class="d-flex flex-wrap gap-1.5 mb-3">
                                        <a href="{{ route('serupa', ['tender' => $no_tender_display]) }}" class="sub-link-pill sub-link-pill-p2" title="Kerja Serupa">
                                            <i class="bi bi-diagram-2"></i>Serupa
                                        </a>
                                        <a href="{{ route('sebanding', ['tender' => $no_tender_display]) }}" class="sub-link-pill sub-link-pill-p2" title="Kerja Sebanding">
                                            <i class="bi bi-diagram-3"></i>Sebanding
                                        </a>
                                    </div>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <a href="{{ route('serupa', ['tender' => $no_tender_display]) }}" class="text-decoration-none text-danger d-flex align-items-center justify-content-between w-100">
                                        <span>Papar Borang 7</span>
                                        <i class="bi bi-arrow-right-short fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Borang 8 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang8', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p2">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p2">
                                            <i class="bi bi-pie-chart-fill"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 8</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Analisa Data Keupayaan</h6>
                                    <p class="text-muted extra-small mb-3">Jadual analisa data-data penilaian keupayaan petender.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 9 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="borang-card borang-card-p2">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p2">
                                            <i class="bi bi-gear-wide-connected"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 9</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Analisa Keupayaan Teknikal</h6>
                                    <p class="text-muted extra-small mb-2">Analisis data penilaian keupayaan teknikal petender.</p>
                                    <div class="d-flex flex-wrap gap-1.5 mb-3">
                                        <a href="{{ route('borang9', ['tender' => $no_tender_display]) }}" class="sub-link-pill sub-link-pill-p2" title="Ringkasan Borang 9">
                                            <i class="bi bi-file-earmark-text"></i>Utama
                                        </a>
                                        <a href="{{ route('kerjaSerupa', ['tender' => $no_tender_display]) }}" class="sub-link-pill sub-link-pill-p2" title="Kerja Serupa">
                                            <i class="bi bi-layers"></i>Serupa
                                        </a>
                                        <a href="{{ route('kerjaSebanding', ['tender' => $no_tender_display]) }}" class="sub-link-pill sub-link-pill-p2" title="Kerja Sebanding">
                                            <i class="bi bi-stack"></i>Sebanding
                                        </a>
                                    </div>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <a href="{{ route('borang9', ['tender' => $no_tender_display]) }}" class="text-decoration-none text-danger d-flex align-items-center justify-content-between w-100">
                                        <span>Papar Borang 9</span>
                                        <i class="bi bi-arrow-right-short fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Borang 10 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang10', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p2">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p2">
                                            <i class="bi bi-person-workspace"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 10</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Prestasi Kerja Semasa</h6>
                                    <p class="text-muted extra-small mb-3">Penilaian rekod & prestasi kerja semasa petender di tapak.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 11 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang11', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p2">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p2">
                                            <i class="bi bi-cpu"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 11</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Penilaian Keupayaan Teknikal</h6>
                                    <p class="text-muted extra-small mb-3">Penilaian kakitangan teknikal, loji & peralatan petender.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 12 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('borang12', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p2">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p2">
                                            <i class="bi bi-patch-check"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 12</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Keupayaan Keseluruhan</h6>
                                    <p class="text-muted extra-small mb-3">Penilaian skor gabungan keupayaan kewangan & teknikal.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- PANE 3: PERINGKAT KETIGA (BORANG 13 - 15) --}}
                <div class="tab-pane fade mt-4" id="pane-p3" role="tabpanel" aria-labelledby="tab-p3">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger-subtle p-2 rounded-2 me-3">
                            <i class="bi bi-award text-danger fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Penilaian Peringkat Ketiga
                                <span class="badge bg-warning text-white border border-white rounded-pill px-3 py-1 text-uppercase small" style="font-size: 0.7rem;">3 Komponen Borang</span>
                            </h5>
                            <p class="text-secondary small mb-0">Laporan Muktamad, Perakuan Jawatankuasa & Ringkasan Keputusan Syor (Borang 13 – 15)</p>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- Borang 13 -->
                        <div class="col-12 col-md-4">
                            <a href="{{ route('borang13', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p3">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p3">
                                            <i class="bi bi-file-earmark-bar-graph"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 13</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Laporan Penilaian Kewangan & Teknikal</h6>
                                    <p class="text-muted extra-small mb-3">Laporan rasmi lengkap gabungan penilaian kewangan & teknikal perolehan kerja.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 14 -->
                        <div class="col-12 col-md-4">
                            <a href="{{ route('borang14', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p3">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p3">
                                            <i class="bi bi-journal-check"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 14</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Perakuan Jawatankuasa Penilaian</h6>
                                    <p class="text-muted extra-small mb-3">Perakuan & pengesyoran rasmi oleh ahli jawatankuasa penilaian perolehan.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Borang 15 -->
                        <div class="col-12 col-md-4">
                            <a href="{{ route('borang15', ['tender' => $no_tender_display]) }}" class="borang-card borang-card-p3">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="icon-avatar icon-avatar-p3">
                                            <i class="bi bi-award"></i>
                                        </div>
                                        <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 15</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Ringkasan Keputusan & Syor</h6>
                                    <p class="text-muted extra-small mb-3">Ringkasan syor muktamad jawatankuasa untuk pertimbangan Lembaga Perolehan.</p>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                                    <span>Buka Borang</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
