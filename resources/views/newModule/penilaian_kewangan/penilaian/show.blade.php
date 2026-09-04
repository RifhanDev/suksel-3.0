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

    /* Locked Borang Card Styling */
    .borang-card-locked {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
        opacity: 0.72;
        cursor: pointer;
    }

    .borang-card-locked:hover {
        transform: none !important;
        box-shadow: none !important;
        border-color: #cbd5e1 !important;
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

    .icon-avatar-locked {
        background: #e2e8f0 !important;
        color: #94a3b8 !important;
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

@php
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $no_tender_display) : $no_tender_display;

    // Helper closure to render Borang card with sequential access control
    $renderBorangCard = function ($code, $stageClass, $iconClass, $badgeLabel, $title, $description, $subPills = []) use ($borangAccess, $tenderIdentifier) {
        $acc = $borangAccess[$code] ?? ['is_unlocked' => ($code === 'borang1'), 'is_completed' => false, 'prev_title' => 'Borang Terdahulu'];
        $unlocked = $acc['is_unlocked'];
        $completed = $acc['is_completed'];
        $prevTitle = $acc['prev_title'] ?? 'Borang Terdahulu';

        $href = $unlocked 
            ? route('penilaianKewanganKerja.borang.show', ['tender_no' => $tenderIdentifier, 'borang_code' => $code]) 
            : 'javascript:void(0);';

        $cardClass = 'borang-card ' . ($unlocked ? 'borang-card-' . $stageClass : 'borang-card-locked js-locked-borang');
        $avatarClass = $unlocked ? 'icon-avatar-' . $stageClass : 'icon-avatar-locked';
        $textColor = $unlocked ? 'text-dark' : 'text-secondary';
        $btnColor = $unlocked ? 'text-danger' : 'text-muted';

        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo '  <a href="' . $href . '" class="' . $cardClass . '" data-borang-title="' . e($badgeLabel . ' - ' . $title) . '" data-prev-title="' . e($prevTitle) . '">';
        echo '    <div>';
        echo '      <div class="d-flex align-items-center justify-content-between mb-3">';
        echo '        <div class="icon-avatar ' . $avatarClass . '">';
        echo '          <i class="bi ' . ($unlocked ? $iconClass : 'bi-lock-fill') . '"></i>';
        echo '        </div>';

        if ($completed) {
            echo '        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>SELESAI</span>';
        } elseif ($unlocked) {
            echo '        <span class="borang-badge bg-danger bg-opacity-10 text-danger">' . e($badgeLabel) . '</span>';
        } else {
            echo '        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="bi bi-lock-fill me-1"></i>TERKUNCI</span>';
        }

        echo '      </div>';
        echo '      <h6 class="fw-bold ' . $textColor . ' mb-1">' . e($title) . '</h6>';
        echo '      <p class="text-muted extra-small mb-3">' . e($description) . '</p>';

        if (! empty($subPills)) {
            echo '      <div class="d-flex flex-wrap gap-1.5 mb-3">';
            foreach ($subPills as $pill) {
                echo '        <span class="sub-link-pill ' . (! $unlocked ? 'opacity-50' : '') . '">';
                echo '          <i class="bi ' . e($pill['icon']) . ' me-1"></i>' . e($pill['label']);
                echo '        </span>';
            }
            echo '      </div>';
        }

        echo '    </div>';
        echo '    <div class="pt-2 border-top d-flex align-items-center justify-content-between ' . $btnColor . ' small fw-semibold">';
        if ($unlocked) {
            echo '      <span>Buka Borang</span>';
            echo '      <i class="bi bi-arrow-right-short fs-5"></i>';
        } else {
            echo '      <span class="text-muted fw-normal">Terkunci</span>';
            echo '      <i class="bi bi-lock-fill small text-muted"></i>';
        }
        echo '    </div>';
        echo '  </a>';
        echo '</div>';
    };
@endphp

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
                            <p class="text-secondary small mb-0">Analisa Kecukupan Dokumen, Analisa Kesempurnaan Tender, Nisbah Kewangan & Kelayakan Peringkat 1 (Borang 1 – 6)</p>
                        </div>
                    </div>
            
                    <div class="row g-3">
                        @php
                            $renderBorangCard('borang1', 'p1', 'bi-file-earmark-spreadsheet', 'BORANG 1', 'Analisa Kesempurnaan Tender', 'Jadual penyerahan tender, maklumat asas petender & harga tawaran.');
                            $renderBorangCard('borang2', 'p1', 'bi-shield-check', 'BORANG 2', 'Analisa Kecukupan Dokumen', 'Semakan kecukupan & kelayakan dokumen kewangan wajib petender.');
                            $renderBorangCard('borang3', 'p1', 'bi-shield-check', 'BORANG 3', 'Nisbah Asas Kewangan', 'Penilaian nisbah kewangan, penyata bank & lembaran imbangan.', [
                                ['icon' => 'bi-file-earmark-text', 'label' => 'Borang 3'],
                                ['icon' => 'bi-journal-text', 'label' => 'Lembaran'],
                                ['icon' => 'bi-bank', 'label' => 'Akaun Bank'],
                                ['icon' => 'bi-cash-coin', 'label' => 'Bon / Saham']
                            ]);
                            $renderBorangCard('borang4', 'p1', 'bi-graph-up-arrow', 'BORANG 4', 'Keupayaan Kewangan', 'Penilaian modal pusingan & had kelayakan kewangan petender.');
                            $renderBorangCard('borang5', 'p1', 'bi-card-checklist', 'BORANG 5', 'Keputusan Peringkat Pertama', 'Jadual keputusan & rumusan kelayakan peringkat pertama.');
                            $renderBorangCard('borang6', 'p1', 'bi-list-stars', 'BORANG 6', 'Petender Lulus Peringkat 1', 'Senarai petender lulus disusun mengikut turutan harga tender.');
                        @endphp
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
                        @php
                            $renderBorangCard('borang7', 'p2', 'bi-bar-chart-steps', 'BORANG 7', 'Analisa Keupayaan Petender', 'Analisis data penilaian keupayaan petender.');
                            $renderBorangCard('borang8', 'p2', 'bi-pie-chart-fill', 'BORANG 8', 'Analisa Data Keupayaan', 'Jadual analisa data-data penilaian keupayaan petender.');
                            $renderBorangCard('borang9', 'p2', 'bi-gear-wide-connected', 'BORANG 9', 'Analisa Keupayaan Teknikal', 'Analisis data penilaian keupayaan teknikal petender.');
                            $renderBorangCard('borang10', 'p2', 'bi-person-workspace', 'BORANG 10', 'Prestasi Kerja Semasa', 'Penilaian rekod & prestasi kerja semasa petender di tapak.');
                            $renderBorangCard('borang11', 'p2', 'bi-cpu', 'BORANG 11', 'Penilaian Keupayaan Teknikal', 'Penilaian kakitangan teknikal, loji & peralatan petender.');
                            $renderBorangCard('borang12', 'p2', 'bi-patch-check', 'BORANG 12', 'Keupayaan Keseluruhan', 'Penilaian skor gabungan keupayaan kewangan & teknikal.');
                        @endphp
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
                        @php
                            $renderBorangCard('borang13', 'p3', 'bi-file-earmark-bar-graph', 'BORANG 13', 'Laporan Penilaian Kewangan & Teknikal', 'Laporan rasmi lengkap gabungan penilaian kewangan & teknikal perolehan kerja.');
                            $renderBorangCard('borang14', 'p3', 'bi-journal-check', 'BORANG 14', 'Perakuan Jawatankuasa Penilaian', 'Perakuan & pengesyoran rasmi oleh ahli jawatankuasa penilaian perolehan.');
                            $renderBorangCard('borang15', 'p3', 'bi-award', 'BORANG 15', 'Ringkasan Keputusan & Syor', 'Ringkasan syor muktamad jawatankuasa untuk pertimbangan Lembaga Perolehan.');
                        @endphp
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- SweetAlert2 JS inclusion & Trigger Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-locked-borang').forEach(function(card) {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                var borangTitle = this.getAttribute('data-borang-title') || 'Borang ini';
                var prevTitle = this.getAttribute('data-prev-title') || 'Borang terdahulu';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: borangTitle + ' Belum Boleh Diakses',
                        html: '<p class="mb-1 text-secondary fs-6">Sila selesaikan <strong>' + prevTitle + '</strong> terlebih dahulu sebelum meneruskan ke borang ini.</p>',
                        confirmButtonText: 'Faham',
                        confirmButtonColor: '#dc2626',
                        customClass: {
                            popup: 'rounded-4 shadow',
                            confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                        }
                    });
                } else {
                    alert(borangTitle + ' belum boleh diakses.\nSila selesaikan ' + prevTitle + ' terlebih dahulu.');
                }
            });
        });

        @if(session('error_locked'))
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Dihalang',
                    text: '{{ session('error_locked') }}',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-4 shadow',
                        confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                    }
                });
            }
        @endif
    });
</script>
@endsection
