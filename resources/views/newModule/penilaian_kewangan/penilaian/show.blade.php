@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --kewangan-accent: #c41e3a;
        --kewangan-accent-dark: #8b1428;
    }

    .kewangan-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .kewangan-header-banner {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        padding: 2rem 2.25rem;
        color: #ffffff;
        position: relative;
    }

    .tender-info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
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

    .sub-link-pill:hover {
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
</style>
@endsection

@section('content')
<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
            <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Penilaian Kewangan (Kerja)</li>
        </ol>
    </nav>

    {{-- Header Banner Card --}}
    <div class="kewangan-card mb-4">
        <div class="kewangan-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Kategori: Kerja</span>
                    <span class="badge bg-warning bg-opacity-20 text-white px-2.5 py-1 rounded-pill small">Modul Penilaian Kerja</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">PENILAIAN KEWANGAN (KERJA)</h3>
                <p class="text-white-50 mb-0 small">Sila pilih borang penilaian mengikut peringkat di bawah untuk membuat penilaian perolehan kerja.</p>
            </div>
            <div>
                <a href="{{ route('penilaianKewangan') }}" class="btn btn-light btn-sm fw-semibold shadow-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Senarai
                </a>
            </div>
        </div>
    </div>

    {{-- Tender Summary Info Card --}}
    <div class="tender-info-card mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-4 border-end-md">
                <div class="text-muted small fw-semibold text-uppercase">No. Tender / Sebut Harga</div>
                <div class="fw-bold text-danger fs-6 font-monospace mt-1">
                    <i class="bi bi-file-text me-1"></i>{{ $no_tender_display }}
                </div>
            </div>
            <div class="col-12 col-md-5 border-end-md">
                <div class="text-muted small fw-semibold text-uppercase">Tajuk Perolehan</div>
                <div class="fw-semibold text-dark small mt-1 line-clamp-2">{{ $tajuk_display }}</div>
            </div>
            <div class="col-12 col-md-3 text-md-end">
                <div class="text-muted small fw-semibold text-uppercase mb-1">Status Process</div>
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill small fw-semibold">
                    <i class="bi bi-hourglass-split me-1"></i>{{ $status_label }}
                </span>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- SECTION 1: PERINGKAT PERTAMA (BORANG 1 - 6) --}}
    {{-- ========================================================================= --}}
    <div class="stage-section-card">
        <div class="stage-header-p1 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-warning text-white fw-bold px-3 py-1.5 rounded-pill" style="font-size: 0.78rem;">PERINGKAT 1</span>
                <div>
                    <h5 class="fw-bold mb-0 text-white">PENILAIAN PERINGKAT PERTAMA</h5>
                    <small class="text-white-50">Semakan Dokumen Wajib, Jadual Penyata Tender, Nisbah Kewangan & Kelayakan Peringkat 1 (Borang 1 – 6)</small>
                </div>
            </div>
            <span class="badge bg-danger bg-opacity-25 text-white border border-white border-opacity-25 rounded-pill px-3 py-1 text-uppercase small">6 Komponen Borang</span>
        </div>
        <div class="p-4">
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

                <!-- Borang 3 (With sub-links) -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="borang-card borang-card-p1">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-avatar icon-avatar-p1">
                                    <i class="bi bi-calculator"></i>
                                </div>
                                <span class="borang-badge bg-danger bg-opacity-10 text-danger">BORANG 3</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Nisbah Asas Kewangan</h6>
                            <p class="text-muted extra-small mb-2">Penilaian nisbah kewangan, penyata bank & lembaran imbangan.</p>
                            <div class="d-flex flex-wrap gap-1.5 mb-3">
                                <a href="{{ route('borang3', ['tender' => $no_tender_display]) }}" class="sub-link-pill" title="Ringkasan Borang 3">
                                    <i class="bi bi-file-earmark-text"></i>Borang 3
                                </a>
                                <a href="{{ route('lembaran', ['tender' => $no_tender_display]) }}" class="sub-link-pill" title="Lembaran Imbangan">
                                    <i class="bi bi-journal-text"></i>Lembaran
                                </a>
                                <a href="{{ route('akaunBank', ['tender' => $no_tender_display]) }}" class="sub-link-pill" title="Penyata / Akaun Bank">
                                    <i class="bi bi-bank"></i>Akaun Bank
                                </a>
                                <a href="{{ route('bonSaham', ['tender' => $no_tender_display]) }}" class="sub-link-pill" title="Bon & Saham">
                                    <i class="bi bi-cash-coin"></i>Bon / Saham
                                </a>
                            </div>
                        </div>
                        <div class="pt-2 border-top d-flex align-items-center justify-content-between text-danger small fw-semibold">
                            <a href="{{ route('borang3', ['tender' => $no_tender_display]) }}" class="text-decoration-none text-danger d-flex align-items-center justify-content-between w-100">
                                <span>Papar Nisbah</span>
                                <i class="bi bi-arrow-right-short fs-5"></i>
                            </a>
                        </div>
                    </div>
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
    </div>

    {{-- ========================================================================= --}}
    {{-- SECTION 2: PERINGKAT KEDUA (BORANG 7 - 12) --}}
    {{-- ========================================================================= --}}
    <div class="stage-section-card">
        <div class="stage-header-p2 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-warning text-white fw-bold px-3 py-1.5 rounded-pill" style="font-size: 0.78rem;">PERINGKAT 2</span>
                <div>
                    <h5 class="fw-bold mb-0 text-white">PENILAIAN PERINGKAT KEDUA</h5>
                    <small class="text-white-50">Analisis Keupayaan Teknikal, Prestasi Kerja Semasa & Pengalaman (Borang 7 – 12)</small>
                </div>
            </div>
            <span class="badge bg-danger bg-opacity-25 text-white border border-white border-opacity-25 rounded-pill px-3 py-1 text-uppercase small">6 Komponen Borang</span>
        </div>
        <div class="p-4">
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
    </div>

    {{-- ========================================================================= --}}
    {{-- SECTION 3: PERINGKAT KETIGA (BORANG 13 - 15) --}}
    {{-- ========================================================================= --}}
    <div class="stage-section-card">
        <div class="stage-header-p3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-warning text-white fw-bold px-3 py-1.5 rounded-pill" style="font-size: 0.78rem;">PERINGKAT 3</span>
                <div>
                    <h5 class="fw-bold mb-0 text-white">PENILAIAN PERINGKAT KETIGA</h5>
                    <small class="text-white-50">Laporan Muktamad, Perakuan Jawatankuasa & Ringkasan Keputusan Syor (Borang 13 – 15)</small>
                </div>
            </div>
            <span class="badge bg-danger bg-opacity-25 text-white border border-white border-opacity-25 rounded-pill px-3 py-1 text-uppercase small">3 Komponen Borang</span>
        </div>
        <div class="p-4">
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
@endsection
