@extends('layouts.v3.master')
@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
        --step-grey: #e2e8f0;
        --text-grey: #64748b;
    }

    body {
        background: #f8fafc;
    }

    .kewangan-detail-container {
        padding: 0.5rem 0 2rem 0;
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

    /* ========================
       STEPPER WIZARD
    ======================== */
    .progress-nav {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
    }

    .progress-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        margin: 0;
        padding: 0.5rem 0;
    }

    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: var(--step-grey);
        z-index: 0;
        transition: background 0.3s ease;
    }

    .progress-step.done:not(:last-child)::after,
    .progress-step.active:not(:last-child)::after {
        background: var(--sg-red);
    }

    .progress-step.active~.progress-step:not(:last-child)::after {
        background: var(--step-grey);
    }

    .step-number {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ffffff;
        color: #64748b;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 2px solid var(--step-grey);
        position: relative;
        z-index: 2;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .progress-step.active .step-number {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: #ffffff;
        border-color: var(--sg-red);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        transform: scale(1.08);
    }

    .progress-step.done .step-number {
        background: var(--sg-red-dark);
        color: #ffffff;
        border-color: var(--sg-red-dark);
    }

    .progress-step.locked {
        opacity: 0.55;
    }

    .progress-step.locked .step-number {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #cbd5e1 !important;
        box-shadow: none !important;
        transform: none !important;
        cursor: not-allowed !important;
    }

    .progress-step.locked .step-label {
        color: #94a3b8 !important;
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--text-grey);
        line-height: 1.3;
        transition: color 0.2s ease;
    }

    .progress-step.active .step-label {
        color: var(--sg-red-dark);
        font-weight: 700;
    }

    .progress-step.done .step-label {
        color: #334155;
    }

    /* ========================
       SECTION TITLES & TABS
    ======================== */
    .card-title-grey {
        background: #f8fafc;
        padding: 0.85rem 1.25rem;
        border-left: 4px solid var(--sg-red);
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .custom-tab-size {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        gap: 4px;
    }

    .custom-tab-size .nav-link {
        border-radius: 9px;
        background: transparent;
        color: #64748b;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 20px;
        transition: all 0.2s ease;
    }

    .custom-tab-size .nav-link:hover {
        color: #1e293b;
    }

    .custom-tab-size .nav-link.active {
        background: #ffffff !important;
        color: var(--sg-red-dark) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        font-weight: 700;
    }

    /* ========================
       TABLES
    ======================== */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th,
    .table-primary thead th {
        background: #1e293b !important;
        color: #ffffff !important;
        text-align: center;
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.9rem 1rem;
        border: none !important;
    }

    .table tbody td {
        font-size: 0.875rem;
        padding: 0.9rem 1rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:hover {
        background: #fcf8f8;
    }

    /* ========================
       BUTTONS
    ======================== */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary, .btn-seterusnya {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .btn-primary:hover, .btn-seterusnya:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
        color: #ffffff;
    }

    .btn-sebelumnya {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
    }

    .btn-sebelumnya:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .btn-success {
        background: #16a34a;
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.2);
    }

    .btn-success:hover {
        background: #15803d;
        color: #ffffff;
    }

    .profil-readonly-chip {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
        font-size: .775rem;
        font-weight: 600;
    }

    .profil-readonly-section {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }

    .profil-readonly-title {
        font-size: 0.925rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }
</style>
@endsection

@section('content')

<div class="container-fluid px-0 kewangan-detail-container">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Senarai Penilaian Kewangan</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Maklumat Penilaian Kewangan</li>
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

    {{-- Main Process Card with Stepper --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4">

            {{-- Progress Stepper Bar --}}
            <div id="custom-progress-bar" class="progress-nav mb-4">
                <ul class="nav progress-wrapper" role="tablist">

                    <li class="nav-item progress-step active" role="presentation">
                        <button type="button"
                            id="pematuhan-tab"
                            class="nav-link step-number active"
                            data-bs-toggle="pill"
                            data-bs-target="#pematuhan"
                            role="tab">1</button>
                        <div class="step-label">Pematuhan Dokumentasi</div>
                    </li>

                    <li class="nav-item progress-step" role="presentation">
                        <button type="button"
                            id="penyata-bank-tab"
                            class="nav-link step-number"
                            data-bs-toggle="pill"
                            data-bs-target="#penyata-bank"
                            role="tab">2</button>
                        <div class="step-label">Penyata Bulanan Bank</div>
                    </li>

                    <li class="nav-item progress-step" role="presentation">
                        <button type="button"
                            id="penilaian-tab"
                            class="nav-link step-number"
                            data-bs-toggle="pill"
                            data-bs-target="#penilaian"
                            role="tab">3</button>
                        <div class="step-label">Pematuhan Spesifikasi Kewangan</div>
                    </li>

                    <li class="nav-item progress-step" role="presentation">
                        <button type="button"
                            id="laporan-tab"
                            class="nav-link step-number"
                            data-bs-toggle="pill"
                            data-bs-target="#laporan"
                            role="tab">4</button>
                        <div class="step-label">Penyediaan Laporan</div>
                    </li>

                </ul>
            </div>

            {{-- Tab Content Container --}}
            <div class="tab-content px-1" id="application-content">

                <!-- Outer Tab 1 Content -->
                <div class="tab-pane fade show active" id="pematuhan" role="tabpanel"
                    aria-labelledby="pematuhan-tab">

                    <!-- Inner tabs for outer tab 1 -->
                    <ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-1" role="tab" aria-selected="true">Kewangan</a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#rumusan-1" role="tab" aria-selected="false">Rumusan</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-4">
                        <div class="tab-pane fade show active" id="kewangan-1" role="tabpanel">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                                    <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Pematuhan Cadangan Kewangan</h5>
                                    <p class="text-secondary small mb-0">Papar maklumat penilaian cadangan kewangan.</p>
                                </div>
                            </div>
                            <!-- <p class="card-title-desc text-primary fst-italic mb-3">Klik butang Menilai untuk meneruskan penilaian.</p> -->
                            <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af; animation: alertPopBuzz 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                <div>
                                    <span class="small fw-medium text-info-emphasis"><strong>Informasi:</strong></span>
                                    <p class="mb-0 small">Klik butang <strong>Menilai</strong> untuk meneruskan penilaian.</p>
                                </div>
                            </div>
                            <div class="table-responsive mb-3 rounded-3 shadow-sm border bg-white">
                                <table class="table table-hover align-middle mb-0 w-100">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="py-3 px-3 text-start fw-bold text-secondary text-uppercase" style="width: 42%; font-size: 0.75rem; letter-spacing: 0.5px;">
                                                <i class="bi bi-file-earmark-text text-danger me-1"></i>Tajuk / Dokumen
                                            </th>
                                            <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 22%; font-size: 0.75rem; letter-spacing: 0.5px;">
                                                <i class="bi bi-gear text-danger me-1"></i>Mekanisma
                                            </th>
                                            <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 20%; font-size: 0.75rem; letter-spacing: 0.5px;">
                                                <i class="bi bi-shield-check text-danger me-1"></i>Status Penilaian
                                            </th>
                                            <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 16%; font-size: 0.75rem; letter-spacing: 0.5px;">
                                                <i class="bi bi-sliders text-danger me-1"></i>Tindakan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kewanganItems as $index => $item)
                                            @php
                                                $itemTitle = $item['title'] ?? $item['nama'] ?? 'Item Senarai Semak Kewangan';
                                                $itemMekanisma = $item['tindakan'] ?? $item['mekanisma'] ?? 'Spesifikasi';
                                                $itemUuid = $item['uuid'] ?? '';

                                                $itemPayload = $semakPayload[$itemUuid] ?? null;
                                                $itemVendors = $itemPayload['vendors'] ?? [];
                                                $totalVendors = count($itemVendors);

                                                $reviewedCount = collect($itemVendors)->filter(function($v) {
                                                    return $v['status_pematuhan'] !== null && $v['status_pematuhan'] !== '';
                                                })->count();

                                                $isItemSelesai = ($totalVendors > 0 && $reviewedCount === $totalVendors);
                                            @endphp
                                            <tr data-item-uuid="{{ $itemUuid }}">
                                                <td class="px-3 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-success bg-opacity-10 p-2 rounded-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-file-earmark-text fs-6 text-success"></i>
                                                        </div>
                                                        <span class="fw-semibold text-dark">{{ $itemTitle }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center px-3">
                                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace fw-medium">
                                                        {{ $itemMekanisma }}
                                                    </span>
                                                </td>
                                                <td class="status-penilaian text-center px-3">
                                                    @if($isItemSelesai)
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill">
                                                            <i class="bi bi-check-circle me-1"></i>Selesai
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill">
                                                            <i class="bi bi-clock me-1"></i>Menunggu Penilaian
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center px-3">
                                                    <button type="button" class="btn btn-sm btn-success btn-papar-semakan-kewangan px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                                        data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                                        data-dokumen="{{ $itemTitle }}"
                                                        data-uuid="{{ $itemUuid }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                        <span>Menilai</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox me-1 fs-5"></i>Tiada item senarai semak kewangan dijumpai bagi tender ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn btn-primary btn-seterusnya">Seterusnya <i class="bi bi-chevron-right ms-1"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="rumusan-1" role="tabpanel" aria-labelledby="rumusan-tab">
                            <div class="container-fluid mt-3 px-0">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary-subtle p-2 rounded-2 me-3">
                                        <i class="bi bi-clipboard-data text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-0">Rumusan</h5>
                                        <p class="text-secondary small mb-0">Rumusan keseluruhan bagi penilaian pematuhan dokumentasi.</p>
                                    </div>
                                </div>
                                <!-- SECTION 1: Pembekal Melepasi -->
                                <div class="mb-2 mt-2">
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-check-circle text-success me-2"></i>Senarai Pembekal Yang Melepasi Penilaian Pematuhan Dokumentasi</h6>
                                    <div class="small text-muted mt-1" id="totalMelepasiText">{{ count($pembekalMelepasi ?? []) }} pembekal melepasi</div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="table-responsive rounded-3 border bg-white shadow-sm">
                                            <table class="table table-hover align-middle mb-0 w-100">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 8%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 35%; font-size: 0.725rem; letter-spacing: 0.5px;">NAMA PEMBEKAL</th>
                                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($pembekalMelepasi ?? [] as $i => $p)
                                                        <tr>
                                                            <td class="text-center py-3 px-3">
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill font-monospace fw-bold">{{ $i + 1 }}</span>
                                                            </td>
                                                            <td class="py-3 px-3 fw-semibold text-dark">
                                                                <div>{{ $p['name'] }}</div>
                                                                @if(!empty($p['kod']))
                                                                    <div class="small text-muted font-monospace">{{ $p['kod'] }}</div>
                                                                @endif
                                                            </td>
                                                            <td class="py-3 px-3 fw-medium text-dark">{{ $p['ulasan'] }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center text-muted py-4" colspan="3" style="font-size: 0.875rem;">
                                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada pembekal melepasi lagi buat masa ini.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-light border-0 shadow-none mt-3 rounded-3">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check me-2 text-primary"></i>Pengesahan Akhir</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="confirmLayakStep1" name="confirm_layak_step1">
                                            <label class="form-check-label small fw-medium" for="confirmLayakStep1">
                                                Saya mengesahkan petender di atas <span class="text-success fw-bold">layak</span> untuk penilaian peringkat seterusnya.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 2: Pembekal Tidak Melepasi -->
                                <div class="mb-2 mt-4">
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-exclamation-circle text-danger me-2"></i>Senarai Pembekal Tidak Melepasi Penilaian Pematuhan Dokumentasi</h6>
                                    <div class="small text-muted mt-1" id="totalTidakLayakText">{{ count($pembekalTidakMelepasi ?? []) }} pembekal tidak melepasi</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="table-responsive rounded-3 border bg-white shadow-sm">
                                            <table class="table table-hover align-middle mb-0 w-100">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 8%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 35%; font-size: 0.725rem; letter-spacing: 0.5px;">NAMA PEMBEKAL</th>
                                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">SEBAB / ULASAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($pembekalTidakMelepasi ?? [] as $i => $p)
                                                        <tr>
                                                            <td class="text-center py-3 px-3">
                                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill font-monospace fw-bold">{{ $i + 1 }}</span>
                                                            </td>
                                                            <td class="py-3 px-3 fw-semibold text-dark">
                                                                <div>{{ $p['name'] }}</div>
                                                                @if(!empty($p['kod']))
                                                                    <div class="small text-muted font-monospace">{{ $p['kod'] }}</div>
                                                                @endif
                                                            </td>
                                                            <td class="py-3 px-3 fw-medium text-danger">{{ $p['ulasan'] }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center text-muted py-4" colspan="3" style="font-size: 0.875rem;">
                                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada pembekal gagal.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 3: Pembekal Belum Dinilai Sepenuhnya -->
                                @if(count($pembekalBelumDinilai ?? []) > 0)
                                <div class="mb-2 mt-4">
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-hourglass-split text-secondary me-2"></i>Pembekal Belum Dinilai Sepenuhnya</h6>
                                    <div class="small text-muted mt-1">{{ count($pembekalBelumDinilai) }} pembekal masih menunggu penilaian lengkap</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="table-responsive rounded-3 border bg-white shadow-sm">
                                            <table class="table table-hover align-middle mb-0 w-100">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 8%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 30%; font-size: 0.725rem; letter-spacing: 0.5px;">NAMA PEMBEKAL</th>
                                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 20%; font-size: 0.725rem; letter-spacing: 0.5px;">KEMAJUAN</th>
                                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">STATUS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pembekalBelumDinilai as $i => $p)
                                                        @php $pct = $p['total'] > 0 ? round(($p['evaluated'] / $p['total']) * 100) : 0; @endphp
                                                        <tr>
                                                            <td class="text-center py-3 px-3">
                                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded-pill font-monospace fw-bold">{{ $i + 1 }}</span>
                                                            </td>
                                                            <td class="py-3 px-3 fw-semibold text-dark">
                                                                <div>{{ $p['name'] }}</div>
                                                                @if(!empty($p['kod']))
                                                                    <div class="small text-muted font-monospace">{{ $p['kod'] }}</div>
                                                                @endif
                                                            </td>
                                                            <td class="py-3 px-3 text-center">
                                                                <div class="progress rounded-pill" style="height: 8px;">
                                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                                <div class="small text-muted mt-1 font-monospace">{{ $p['evaluated'] }}/{{ $p['total'] }}</div>
                                                            </td>
                                                            <td class="py-3 px-3 fw-medium text-secondary">{{ $p['ulasan'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Action Button -->
                                <div class="row mb-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button class="btn btn-primary btn-seterusnya">Seterusnya <i class="bi bi-chevron-right ms-1"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Penyata Bulanan Bank -->
                <div class="tab-pane fade" id="penyata-bank" role="tabpanel" aria-labelledby="penyata-bank-tab">
                    @include('newModule.penilaian_kewangan.step2')
                </div>

                <!-- Step 3: Pematuhan Spesifikasi Kewangan -->
                <div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">
                    @include('newModule.penilaian_kewangan.step3')
                </div>

                <!-- Step 4: Penyediaan Laporan -->
                <div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
                    @include('newModule.penilaian_kewangan.step4')
                </div>

            </div>

        </div>
    </div>

    {{-- Dialog: SEMAKAN PEMATUHAN DOKUMEN KEWANGAN  --}}
    <div class="modal fade" id="modalSemakanKetepatanDokumenKewangan" tabindex="-1" aria-labelledby="modalLabelSemakanKewangan"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-semakan-kewangan">
                <!-- <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalLabelSemakanKewangan">SEMAKAN PEMATUHAN DOKUMEN KEWANGAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div> -->
                <div class="modal-header px-4 pt-4 border-0">
                    <div class="d-flex align-items-center rounded-3 mt-3">
                        <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background: #dbeafe;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Tajuk / Dokumen</span>
                            <h6 id="modalDocTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</h6>
                        </div>
                        <div class="mx-3 align-self-stretch" style="width: 1px; background: #d1d5db;"></div>
                        <span class="text-secondary" style="font-size: 0.78rem;">Senarai dokumen yang perlu dikemukakan oleh petender.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive rounded-3" style="border: 1px solid #e5e7eb;">
                        <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                                <tr>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 120px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Kod Pembekal</th>
                                    <th class="text-center text-uppercase fw-bold py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Dokumen / Penyerahan</th>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 150px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Status Penyerahan</th>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 180px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Status Pematuhan</th>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 220px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="modalSemakanKewanganBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Pilih dokumen untuk semakan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mt-3" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        </svg>
                        Sila pilih status pematuhan untuk meneruskan penilaian pematuhan.
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                    <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal / Tutup</button>
                    <span id="readOnlyNoticeStep1" class="badge bg-secondary text-white px-3 py-2 d-none"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja (Langkah Telah Disahkan)</span>
                    <button type="button" class="btn btn-sm btn-success px-4 fw-bold" id="btnStep1SimpanDokKewangan">
                        <i class="bi bi-save me-2"></i>Simpan Penilaian
                    </button>
                </div>
    </div>

    {{-- MODAL: Prebiu Dokumen/Borang --}}
    <div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90%; height: 90vh;">
            <div class="modal-content h-100 border-0 shadow-lg rounded-3">
                <div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background-color: #e0f2fe;">
                            <i class="bi bi-file-earmark-pdf text-primary fs-5" id="previewIcon"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Prebiu Dokumen</span>
                            <h6 id="modalPreviewTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">-</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="btnNewTabPreview" href="#" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-up-right"></i> <span class="d-none d-sm-inline">Buka di Tab Baru</span>
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-0 bg-light position-relative d-flex align-items-center justify-content-center" style="height: calc(100% - 75px); overflow: hidden;">
                    <div id="previewSpinner" class="spinner-border text-primary position-absolute" role="status" style="z-index: 10; width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Memuatkan...</span>
                    </div>
                    <iframe id="previewIframe" src="" class="w-100 h-100 border-0 d-none" style="background: white;"></iframe>
                    <div id="previewImageWrapper" class="w-100 h-100 d-none overflow-auto p-3 text-center">
                        <img id="previewImage" src="" class="img-fluid rounded shadow-sm" style="max-height: 100%; object-fit: contain;" />
                    </div>
                    <div id="previewFallback" class="text-center p-4 d-none">
                        <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-file-earmark-zip fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Prebiu tidak disokong</h5>
                        <p class="text-muted small mx-auto" style="max-width: 400px;">Format fail ini tidak menyokong paparan terus. Sila klik butang di bawah untuk memuat turun.</p>
                        <a id="btnFallbackDownload" href="#" target="_blank" class="btn btn-primary px-4 fw-bold mt-2">
                            <i class="bi bi-download me-2"></i>Muat Turun Fail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalProfilPetenderReadonly" tabindex="-1" aria-labelledby="modalProfilPetenderReadonlyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
            <div class="modal-content modal-semakan-kewangan">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalProfilPetenderReadonlyLabel">Maklumat Profil Petender</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="card-title-desc text-primary fst-italic mb-3">Paparan ringkas borang profil petender (contoh data).</p>

                    <div class="profil-readonly-form">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <div class="profil-readonly-chip">No. Tender: {{ $no_tender_display ?? 'SUKSEL/PERT/2026/001' }}</div>
                            <div class="profil-readonly-badge">Profil Lengkap</div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Maklumat Syarikat</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Syarikat</label>
                                    <input type="text" class="form-control" value="Inovasi Digital Nusantara Sdn. Bhd." readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Jenis Syarikat</label>
                                    <input type="text" class="form-control" value="Sdn. Bhd." readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Taraf Petender</label>
                                    <input type="text" class="form-control" value="Bumiputera" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat Syarikat</label>
                                    <textarea class="form-control" rows="2" readonly>No. 18, Persiaran Teknologi 2, Taman Sains Selangor, 47810 Petaling Jaya, Selangor.</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Pegawai Untuk Dihubungi</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" value="Nur Aisyah Binti Rahman" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No. Telefon</label>
                                    <input type="text" class="form-control" value="012-888 7766" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">E-mel</label>
                                    <input type="text" class="form-control" value="aisyah.rahman@idn.com.my" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Maklumat Pendaftaran & Kewangan</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">No. SSM</label>
                                    <input type="text" class="form-control" value="201901045678 (1345000-X)" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No. MOF</label>
                                    <input type="text" class="form-control" value="357-021-000987" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tempoh Sah MOF</label>
                                    <input type="text" class="form-control" value="31/12/2027" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bil. Pekerja Semasa</label>
                                    <input type="text" class="form-control" value="42 orang" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bil. Pekerja Teknikal</label>
                                    <input type="text" class="form-control" value="15 orang" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Modal Berbayar (RM)</label>
                                    <input type="text" class="form-control" value="750,000.00" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Modal Dibenarkan (RM)</label>
                                    <input type="text" class="form-control" value="1,500,000.00" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Ringkasan Projek Terdahulu (2 Tahun)</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Projek 1</label>
                                    <input type="text" class="form-control" value="Naik taraf infrastruktur rangkaian data - Jabatan Kastam Malaysia (RM 420,000.00)" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Projek 2</label>
                                    <input type="text" class="form-control" value="Penyenggaraan sistem keselamatan siber - Kementerian Kewangan (RM 380,000.00)" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Projek 3</label>
                                    <input type="text" class="form-control" value="Perkhidmatan sokongan aplikasi dalaman - MAMPU (RM 290,000.00)" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Kedudukan Kewangan Semasa</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Aset Utama (5 Terbesar)</label>
                                    <textarea class="form-control" rows="5" readonly>
                                        1. Bangunan pejabat 3 tingkat - RM 1,200,000.00
                                        2. Pelayan data (server cluster) - RM 350,000.00
                                        3. 15 unit workstation teknikal - RM 180,000.00
                                        4. Perisian lesen enterprise - RM 120,000.00
                                        5. Kenderaan operasi - RM 95,000.00
                                    </textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Peralatan Berkaitan Tender (5 Item)</label>
                                    <textarea class="form-control" rows="5" readonly>
                                        1. Network analyzer set - RM 80,000.00
                                        2. Security appliance - RM 65,000.00
                                        3. Portable forensic workstation - RM 58,000.00
                                        4. Backup storage system - RM 45,000.00
                                        5. Audit toolkit license - RM 30,000.00
                                    </textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggungan / Liabilities (RM)</label>
                                    <input type="text" class="form-control" value="210,000.00" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Baki Wang Dalam Bank (RM)</label>
                                    <input type="text" class="form-control" value="980,000.00" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="profil-readonly-section">
                            <div class="profil-readonly-title">Analisa Kecukupan Modal</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Skor Modal Berbayar</label>
                                    <input type="text" class="form-control" value="Automatik" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Skor Modal Berbayar</label>
                                    <input type="text" class="form-control" value="10/10" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Skor Modal Dibenarkan</label>
                                    <input type="text" class="form-control" value="Automatik" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Skor Modal Dibenarkan</label>
                                    <input type="text" class="form-control" value="10/10" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const SEMAK_PAYLOAD = @json($semakPayload ?? []);
    const PENYATA_BANK_UUIDS = @json(collect($penyataBankItems ?? [])->pluck('uuid')->filter()->values()->all());
    const PENYATA_BANK_CONFIG = @json($penyataBankConfig ?? []);
    const VENDOR_FORM_PAYLOADS = @json($vendorFormPayloads ?? []);
    const DOKUMEN_BY_VENDOR = @json($dokumenByVendor ?? []);

    let activeStep2Uuid = PENYATA_BANK_UUIDS[0] || null;
    let activeStep2VendorId = null;

    let dbConfirmed = {
        step1: {{ $progress?->isStep1Confirmed() ? 'true' : 'false' }},
        step2: {{ $progress?->isStep2Confirmed() ? 'true' : 'false' }},
        step3: {{ $progress?->isStep3Confirmed() ? 'true' : 'false' }}
    };
    let dbCurrentStep = {{ (int) ($progress?->current_step ?? 1) }};

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function renderSemakanKewanganRows(item) {
        const $body = $('#modalSemakanKewanganBody');
        $body.empty();

        const vendors = item?.vendors || [];
        if (!vendors.length) {
            $body.append('<tr><td colspan="5" class="text-center text-muted py-4">Tiada petender yang membeli dokumen bagi tender ini.</td></tr>');
            return;
        }

        const itemUuid = item?.uuid || activeItemUuid;
        const isStep2Item = (PENYATA_BANK_UUIDS.includes(itemUuid));
        const isReadOnly = isStep2Item
            ? (dbConfirmed.step2 || dbCurrentStep > 2)
            : (dbConfirmed.step1 || dbCurrentStep > 1);

        if (isReadOnly) {
            $('#btnStep1SimpanDokKewangan').addClass('d-none');
            $('#readOnlyNoticeStep1').removeClass('d-none');
        } else {
            $('#btnStep1SimpanDokKewangan').removeClass('d-none');
            $('#readOnlyNoticeStep1').addClass('d-none');
        }

        vendors.forEach(function (vendor) {
            const isSubmitted = vendor.status === 'submitted';
            const statusBadge = isSubmitted
                ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-check-circle me-1"></i>Hantar</span>'
                : '<span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-clock me-1"></i>Menunggu</span>';

            let docHtml = '<div class="small text-muted">' + escapeHtml(vendor.summary || '-') + '</div>';
            if (Array.isArray(vendor.files) && vendor.files.length) {
                docHtml = vendor.files.map(function (file) {
                    const ext = (file.name || '').split('.').pop().toLowerCase();
                    if (['pdf', 'png', 'jpg', 'jpeg', 'svg', 'webp'].includes(ext)) {
                        return '<a href="' + escapeHtml(file.url) + '" data-name="' + escapeHtml(file.name) + '" class="d-block small text-primary btn-preview-file text-decoration-none mb-1">' +
                            '<i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>' + escapeHtml(file.name) +
                            '</a>';
                    } else {
                        return '<a href="' + escapeHtml(file.url) + '" download data-name="' + escapeHtml(file.name) + '" class="d-block small text-primary text-decoration-none mb-1">' +
                            '<i class="bi bi-file-earmark-arrow-down me-1"></i>' + escapeHtml(file.name) +
                            '</a>';
                    }
                }).join('');
            } else if (vendor.form_url) {
                const isSpec = (item?.action === 'view_specification');
                const label  = isSpec ? 'Buka spesifikasi' : 'Buka borang';
                const icon   = isSpec ? 'bi bi-file-earmark-text' : 'bi bi-window';
                docHtml = '<div class="mt-1"><a href="' + escapeHtml(vendor.form_url) + '" data-name="' + (isSpec ? 'Spesifikasi ' : 'Borang ') + escapeHtml(vendor.name) + '" class="small btn-preview-file text-decoration-none"><i class="' + icon + ' me-1"></i>' + label + '</a></div>';
            }

            const savedStatus  = vendor.status_pematuhan;
            const savedCatatan = vendor.catatan || '';

            const disabledAttr = isReadOnly ? 'disabled' : '';
            const readonlyAttr = isReadOnly ? 'readonly disabled' : '';

            const selectHtml =
                '<select class="form-select form-select-sm semak-pematuhan" ' + disabledAttr + ' data-vendor-id="' + vendor.vendor_id + '">' +
                    '<option value="" ' + (savedStatus === null || savedStatus === undefined || savedStatus === '' ? 'selected' : '') + ' disabled>-- Sila Pilih --</option>' +
                    '<option value="mematuhi" ' + (savedStatus === 'mematuhi' || savedStatus === 1 ? 'selected' : '') + '>Mematuhi</option>' +
                    '<option value="tidak_mematuhi" ' + (savedStatus === 'tidak_mematuhi' || savedStatus === 0 ? 'selected' : '') + '>Tidak Mematuhi</option>' +
                '</select>';

            const catatanHtml =
                '<textarea class="form-control form-control-sm semak-catatan" ' + readonlyAttr + ' rows="2" placeholder="Catatan...">' +
                escapeHtml(savedCatatan) + '</textarea>';

            const kodDisplay = vendor.kod
                ? escapeHtml(vendor.kod)
                : '<span class="fst-italic small text-muted">Belum Dijana</span>';

            $body.append(
                '<tr>' +
                    '<td class="text-center fw-bold align-middle" style="background-color: #efeff0ff; color: #3f3f3fff;">' + kodDisplay + '</td>' +
                    '<td>' +
                        '<div class="fw-semibold text-dark small mb-1">' + escapeHtml(vendor.name) + '</div>' +
                        docHtml +
                    '</td>' +
                    '<td class="text-center align-middle">' + statusBadge + '</td>' +
                    '<td class="align-middle">' + selectHtml + '</td>' +
                    '<td class="align-middle">' + catatanHtml + '</td>' +
                '</tr>'
            );
        });
    }

    document.addEventListener('DOMContentLoaded', () => {

        const KEMASKINI_LANGKAH_URL = "{{ route('penilaianKewangan.kemaskiniLangkah') }}";
        const TENDER_IDENTIFIER = "{{ $tender_no }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        const steps = document.querySelectorAll('.progress-step');
        const tabs = document.querySelectorAll('.step-number');

        const tabList = [
            { id: 'pematuhan-tab', target: '#pematuhan', checkboxId: 'confirmLayakStep1', stepNum: 1, name: 'Langkah 1 (Pematuhan Dokumentasi)', index: 0 },
            { id: 'penyata-bank-tab', target: '#penyata-bank', checkboxId: 'confirmLayakStep2', stepNum: 2, name: 'Langkah 2 (Penyata Bulanan Bank)', index: 1 },
            { id: 'penilaian-tab', target: '#penilaian', checkboxId: 'confirmLayakStep3', stepNum: 3, name: 'Langkah 3 (Pematuhan Spesifikasi Kewangan)', index: 2 },
            { id: 'laporan-tab', target: '#laporan', checkboxId: null, stepNum: 4, name: 'Langkah 4 (Penyediaan Laporan)', index: 3 }
        ];

        // Set initial checkbox states from DB
        const cb1 = document.getElementById('confirmLayakStep1') || document.getElementById('confirmLayak');
        const cb2 = document.getElementById('confirmLayakStep2');
        const cb3 = document.getElementById('confirmLayakStep3');

        if (cb1) cb1.checked = dbConfirmed.step1;
        if (cb2) cb2.checked = dbConfirmed.step2;
        if (cb3) cb3.checked = dbConfirmed.step3;

        function isStepUnlocked(stepIndex) {
            if (stepIndex <= 0) return true; // Step 1 is always unlocked
            if ((stepIndex + 1) <= dbCurrentStep) return true;
            if (stepIndex === 1) return dbConfirmed.step1;
            if (stepIndex === 2) return dbConfirmed.step1 && dbConfirmed.step2;
            if (stepIndex === 3) return dbConfirmed.step1 && dbConfirmed.step2 && dbConfirmed.step3;
            return false;
        }

        function updateStepLocks() {
            tabList.forEach((tabInfo, idx) => {
                const stepEl = steps[idx];
                const tabBtn = document.getElementById(tabInfo.id);
                if (!stepEl || !tabBtn) return;

                const unlocked = isStepUnlocked(idx);
                if (unlocked) {
                    stepEl.classList.remove('locked');
                    tabBtn.removeAttribute('aria-disabled');
                    tabBtn.style.pointerEvents = 'auto';
                } else {
                    stepEl.classList.add('locked');
                    tabBtn.setAttribute('aria-disabled', 'true');
                }
            });

            // Lock confirmation checkboxes for completed steps when process has advanced past them
            if (cb1) cb1.disabled = (dbCurrentStep > 1);
            if (cb2) cb2.disabled = (dbCurrentStep > 2);
            if (cb3) cb3.disabled = (dbCurrentStep > 3);
        }

        function areAllStep2VendorsEvaluated() {
            const uuidToUse = activeStep2Uuid || (PENYATA_BANK_UUIDS && PENYATA_BANK_UUIDS[0]) || '';
            const itemObj = SEMAK_PAYLOAD[uuidToUse] || Object.values(SEMAK_PAYLOAD).find(item => PENYATA_BANK_UUIDS.includes(item?.uuid));
            const rawVendors = itemObj?.vendors || [];
            const failedStep1Ids = getFailedStep1VendorIds();
            const eligibleVendors = rawVendors.filter(v => !failedStep1Ids.includes(parseInt(v.vendor_id)));

            if (!eligibleVendors.length) return true;

            const unevaluated = eligibleVendors.filter(v => v.status_pematuhan === null || v.status_pematuhan === '' || v.status_pematuhan === undefined);
            return unevaluated.length === 0;
        }

        window.saveConfirmedStates = async function(stepNum, confirmedVal) {
            if (stepNum === 2 && confirmedVal) {
                if (!areAllStep2VendorsEvaluated()) {
                    const cb = document.getElementById('confirmLayakStep2');
                    if (cb) cb.checked = false;
                    Swal.fire({
                        title: 'Penilaian Belum Selesai',
                        text: 'Semua pembekal yang melepasi Langkah 1 mesti dinilai terlebih dahulu sebelum membuat pengesahan akhir Langkah 2.',
                        icon: 'warning',
                        confirmButtonText: 'Faham',
                        confirmButtonColor: '#1e293b'
                    });
                    return;
                }
            }

            try {
                const res = await $.ajax({
                    url: KEMASKINI_LANGKAH_URL,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        tender: TENDER_IDENTIFIER,
                        step: stepNum,
                        confirmed: confirmedVal ? 1 : 0
                    }
                });

                if (res.confirmed) {
                    dbConfirmed.step1 = res.confirmed.step1;
                    dbConfirmed.step2 = res.confirmed.step2;
                    dbConfirmed.step3 = res.confirmed.step3;
                }
                if (res.current_step) {
                    dbCurrentStep = res.current_step;
                }
            } catch (err) {
                const cb = document.getElementById(stepNum === 2 ? 'confirmLayakStep2' : (stepNum === 1 ? 'confirmLayakStep1' : 'confirmLayakStep3'));
                if (cb) cb.checked = !confirmedVal;
                Swal.fire({
                    title: 'Ralat',
                    text: err.responseJSON?.message || 'Gagal mengemaskini pengesahan langkah ke DB.',
                    icon: 'error',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#1e293b'
                });
            }
            updateStepLocks();
        };

        // Attach listeners to confirmation checkboxes
        [
            { id: 'confirmLayakStep1', step: 1 },
            { id: 'confirmLayak', step: 1 },
            { id: 'confirmLayakStep2', step: 2 },
            { id: 'confirmLayakStep3', step: 3 }
        ].forEach(item => {
            const cb = document.getElementById(item.id);
            if (cb) {
                cb.addEventListener('change', function () {
                    window.saveConfirmedStates(item.step, this.checked);
                });
            }
        });

        // Tab click protection & activation listeners
        tabList.forEach((tabInfo, index) => {
            const tabBtn = document.getElementById(tabInfo.id);
            if (!tabBtn) return;

            tabBtn.addEventListener('click', (e) => {
                if (index === 2) { // Target: Step 3
                    if (!areAllStep2VendorsEvaluated()) {
                        e.preventDefault();
                        e.stopPropagation();
                        Swal.fire({
                            title: 'Penilaian Belum Selesai',
                            text: 'Semua pembekal yang melepasi Langkah 1 mesti dinilai terlebih dahulu sebelum meneruskan ke Langkah 3.',
                            icon: 'warning',
                            confirmButtonText: 'Faham',
                            confirmButtonColor: '#1e293b'
                        });
                        return false;
                    }
                    if (!dbConfirmed.step2 && !$('#confirmLayakStep2').is(':checked')) {
                        e.preventDefault();
                        e.stopPropagation();
                        Swal.fire({
                            title: 'Pengesahan Akhir Diperlukan',
                            text: 'Sila lengkapkan pengesahan akhir (Pengesahan Akhir) di tab Rumusan Langkah 2 terlebih dahulu sebelum meneruskan ke Langkah 3.',
                            icon: 'warning',
                            confirmButtonText: 'Faham',
                            confirmButtonColor: '#1e293b'
                        });
                        return false;
                    }
                }

                if (!isStepUnlocked(index)) {
                    e.preventDefault();
                    e.stopPropagation();

                    let reqName = 'Langkah 1 (Pematuhan Dokumentasi)';
                    if (index === 2 && !isStepUnlocked(1)) reqName = 'Langkah 2 (Penyata Bulanan Bank)';
                    else if (index === 3 && !isStepUnlocked(2)) reqName = 'Langkah 3 (Pematuhan Spesifikasi Kewangan)';

                    Swal.fire({
                        title: 'Langkah Terkunci',
                        text: 'Sila lengkapkan dan sahkan ' + reqName + ' terlebih dahulu sebelum meneruskan.',
                        icon: 'warning',
                        confirmButtonText: 'Faham',
                        confirmButtonColor: '#1e293b'
                    });
                    return false;
                }
            });

            tabBtn.addEventListener('shown.bs.tab', () => {
                updateStepper(index);
                $.ajax({
                    url: KEMASKINI_LANGKAH_URL,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        tender: TENDER_IDENTIFIER,
                        target_step: index + 1
                    }
                }).done(res => {
                    if (res.current_step) dbCurrentStep = res.current_step;
                });
            });
        });

        function updateStepper(activeIndex) {
            steps.forEach((step, i) => {
                step.classList.remove('active', 'done');

                if (i < activeIndex) step.classList.add('done');
                if (i === activeIndex) step.classList.add('active');
            });
        }

        // Initialize step locks
        updateStepLocks();

        // Activate active step from DB
        let initialStepIndex = dbCurrentStep - 1;
        if (!isStepUnlocked(initialStepIndex)) {
            initialStepIndex = 0;
            for (let i = tabList.length - 1; i >= 0; i--) {
                if (isStepUnlocked(i)) {
                    initialStepIndex = i;
                    break;
                }
            }
        }

        const initialBtn = document.getElementById(tabList[initialStepIndex].id);
        if (initialBtn && typeof bootstrap !== 'undefined') {
            bootstrap.Tab.getOrCreateInstance(initialBtn).show();
        }

        renderStep2RumusanTables();

        let activeSemakanButton = null;
        let activeItemUuid = null;
        const SIMPAN_URL = "{{ route('penilaianKewangan.simpanPematuhan') }}";
        const btnSimpanDokKewangan = document.getElementById('btnStep1SimpanDokKewangan');

        $(document).on('click', '.btn-papar-semakan-kewangan:not(.btn-open-profil-petender-readonly)', function() {
            const btn = this;
            const t = btn.getAttribute('data-dokumen')?.trim() || '';
            activeItemUuid = btn.getAttribute('data-uuid')?.trim() || '';
            const el = document.getElementById('modalDocTitle') || document.getElementById('modalSemakanKewanganTajuk');
            activeSemakanButton = btn;
            const item = SEMAK_PAYLOAD[activeItemUuid] || null;

            if (el && (t || item?.title)) {
                el.textContent = t || item?.title;
            }

            renderSemakanKewanganRows(item);
        });

        if (btnSimpanDokKewangan) {
            btnSimpanDokKewangan.addEventListener('click', async () => {
                if (!activeItemUuid) return;

                const rows = [];
                let hasError = false;

                $('#modalSemakanKewanganBody tr').each(function () {
                    const $select  = $(this).find('.semak-pematuhan');
                    const $catatan = $(this).find('.semak-catatan');
                    if (!$select.length) return;

                    const vendorId        = $select.data('vendor-id');
                    const statusPematuhan = $select.val();
                    const catatan         = $catatan.val() ? $catatan.val().trim() : '';

                    if (statusPematuhan === '' || statusPematuhan === null) {
                        hasError = true;
                        $select.addClass('is-invalid');
                        return;
                    }
                    $select.removeClass('is-invalid');

                    if (statusPematuhan === 'tidak_mematuhi' && !catatan) {
                        hasError = true;
                        $catatan.addClass('is-invalid');
                        return;
                    }
                    $catatan.removeClass('is-invalid');

                    rows.push({ vendor_id: vendorId, status_pematuhan: statusPematuhan, catatan });
                });

                if (hasError) {
                    Swal.fire({
                        title: 'Ralat Validasi',
                        text: 'Sila pilih Status Pematuhan bagi setiap petender. Catatan wajib diisi jika Status Pematuhan = Tidak Mematuhi.',
                        icon: 'warning',
                        confirmButtonText: 'Faham',
                        confirmButtonColor: '#1e293b'
                    });
                    return;
                }

                // Save evaluations to backend
                let saveErrors = 0;
                for (const row of rows) {
                    try {
                        await $.ajax({
                            url: SIMPAN_URL,
                            method: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                                tender: TENDER_IDENTIFIER,
                                vendor_id: row.vendor_id,
                                checklist_item_uuid: activeItemUuid,
                                status_pematuhan: row.status_pematuhan,
                                catatan: row.catatan,
                            }
                        });

                        if (SEMAK_PAYLOAD[activeItemUuid]) {
                            const vRow = SEMAK_PAYLOAD[activeItemUuid].vendors.find(v => v.vendor_id == row.vendor_id);
                            if (vRow) {
                                vRow.status_pematuhan = row.status_pematuhan;
                                vRow.catatan = row.catatan;
                            }
                        }
                    } catch (err) {
                        saveErrors++;
                        console.error('Failed to save evaluation for vendor ' + row.vendor_id, err);
                    }
                }

                if (saveErrors > 0) {
                    Swal.fire({
                        title: 'Ralat',
                        text: saveErrors + ' daripada ' + rows.length + ' penilaian gagal disimpan. Sila semak konsol untuk maklumat lanjut.',
                        icon: 'error',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#1e293b'
                    });
                    return;
                }

                // Re-evaluate if ALL vendor submissions for activeItemUuid are reviewed
                const itemObj = SEMAK_PAYLOAD[activeItemUuid];
                const vendorsArr = itemObj?.vendors || [];
                const totalV = vendorsArr.length;
                const reviewedV = vendorsArr.filter(v => v.status_pematuhan !== null && v.status_pematuhan !== '').length;
                const isAllReviewed = (totalV > 0 && reviewedV === totalV);

                const $outerTr = $('tr[data-item-uuid="' + activeItemUuid + '"]');
                const $statusCell = $outerTr.find('.status-penilaian');

                if ($statusCell.length) {
                    if (isAllReviewed) {
                        $statusCell.html('<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-check-circle me-1"></i>Selesai</span>');
                    } else {
                        $statusCell.html('<span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-clock me-1"></i>Menunggu Penilaian</span>');
                    }
                }

                Swal.fire({
                    title: 'Berjaya!',
                    text: 'Penilaian pematuhan telah disimpan.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1e293b'
                }).then(() => {
                    location.reload();
                });

                activeSemakanButton = null;
                const modalEl = document.getElementById('modalSemakanKetepatanDokumenKewangan');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
            });
        }

        // Click Papar on Step 2 table row
        $(document).on('click', '.btn-papar-cadangan-kewangan-step2', function () {
            activeStep2Uuid = $(this).data('uuid') || (PENYATA_BANK_UUIDS[0] ?? '');
            const docTitle = $(this).data('dokumen') || 'Penyata Bank Terkini (3 Bulan Terakhir) Syarikat';
            $('#modalCadanganKewanganTajukStep2').text(docTitle);

            renderStep2VendorListModal();
        });

        function getFailedStep1VendorIds() {
            const failedSet = new Set();

            Object.keys(SEMAK_PAYLOAD).forEach(uuid => {
                if (!PENYATA_BANK_UUIDS.includes(uuid)) {
                    const itemObj = SEMAK_PAYLOAD[uuid];
                    (itemObj?.vendors || []).forEach(v => {
                        if (v.status_pematuhan === 'tidak_mematuhi' || v.status_pematuhan === 0 || v.status_pematuhan === '0') {
                            failedSet.add(parseInt(v.vendor_id));
                        }
                    });
                }
            });

            return Array.from(failedSet);
        }

        function renderStep2VendorListModal() {
            const $tbody = $('#modalStep2VendorTableBody');
            $tbody.empty();

            const itemObj = SEMAK_PAYLOAD[activeStep2Uuid];
            const rawVendors = itemObj?.vendors || [];
            const failedVendorIds = getFailedStep1VendorIds();

            // Exclude vendors listed under "Tidak Melepasi" in Step 1
            const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

            if (!eligibleVendors.length) {
                $tbody.append('<tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-info-circle me-1"></i>Tiada petender yang melepasi penilaian pematuhan dokumentasi.</td></tr>');
                return;
            }

            const maxScore = (function() {
                let max = 0;
                if (PENYATA_BANK_CONFIG.scoring_items && PENYATA_BANK_CONFIG.scoring_items.length) {
                    PENYATA_BANK_CONFIG.scoring_items.forEach(s => {
                        const val = parseFloat(s.skema) || 0;
                        if (val > max) max = val;
                    });
                }
                if (max === 0) {
                    const itemObj = SEMAK_PAYLOAD[activeStep2Uuid];
                    max = parseFloat(itemObj?.score || itemObj?.weight) || 10;
                }
                return max;
            })();

            eligibleVendors.forEach((v, idx) => {
                const kodDisplay = v.kod ? escapeHtml(v.kod) : ((idx + 1) + '/' + eligibleVendors.length);
                const isEvaluated = (v.status_pematuhan !== null && v.status_pematuhan !== '' && v.status_pematuhan !== undefined);

                const statusBadge = isEvaluated
                    ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-check-circle me-1"></i>Selesai</span>'
                    : '<span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-clock me-1"></i>Menunggu Penilaian</span>';

                const scoreVal = (v.skor !== null && v.skor !== undefined && v.skor !== '') ? v.skor : ((v.status_pematuhan === 'mematuhi' || v.status_pematuhan === 1) ? maxScore : 0);
                const scoreDisplay = isEvaluated
                    ? (scoreVal + ' / <strong class="fw-bold text-dark">' + maxScore + '</strong>')
                    : ('- / <strong class="fw-bold text-muted">' + maxScore + '</strong>');

                const actionBtn = isEvaluated
                    ? '<button type="button" class="btn btn-outline-primary btn-sm btn-papar-penyata-bank-detail-step2 px-3 py-1.5" ' +
                        'data-vendor-id="' + v.vendor_id + '" ' +
                        'data-kod="' + kodDisplay + '" ' +
                        'data-nama="' + escapeHtml(v.name || '') + '">' +
                        '<i class="bi bi-eye me-1"></i>Lihat' +
                    '</button>'
                    : '<button type="button" class="btn btn-success btn-sm btn-papar-penyata-bank-detail-step2 px-3 py-1.5" ' +
                        'data-vendor-id="' + v.vendor_id + '" ' +
                        'data-kod="' + kodDisplay + '" ' +
                        'data-nama="' + escapeHtml(v.name || '') + '">' +
                        '<i class="bi bi-pencil-square me-1"></i>Papar / Menilai' +
                    '</button>';

                $tbody.append(
                    '<tr>' +
                        '<td class="text-center fw-bold align-middle" style="background-color: #efeff0ff; color: #3f3f3fff;">' + kodDisplay + '</td>' +
                        '<td class="text-center align-middle font-monospace">' + scoreDisplay + '</td>' +
                        '<td class="text-center align-middle">' + statusBadge + '</td>' +
                        '<td class="text-center align-middle">' + actionBtn + '</td>' +
                    '</tr>'
                );
            });
        }

        function renderStep2RumusanTables() {
            const $melepasiTbody = $('#step2RumusanMelepasiTableBody');
            const $tidakMelepasiTbody = $('#step2RumusanTidakMelepasiTableBody');
            const $melepasiText = $('#step2TotalMelepasiText');
            const $tidakMelepasiText = $('#step2TotalTidakMelepasiText');

            if (!$melepasiTbody.length || !$tidakMelepasiTbody.length) return;

            $melepasiTbody.empty();
            $tidakMelepasiTbody.empty();

            const targetUuid = activeStep2Uuid || (PENYATA_BANK_UUIDS && PENYATA_BANK_UUIDS[0]) || '';
            const itemObj = SEMAK_PAYLOAD[targetUuid] || Object.values(SEMAK_PAYLOAD).find(item => PENYATA_BANK_UUIDS.includes(item?.uuid));
            const rawVendors = itemObj?.vendors || [];
            const failedStep1Ids = getFailedStep1VendorIds();
            const eligibleVendors = rawVendors.filter(v => !failedStep1Ids.includes(parseInt(v.vendor_id)));

            const melepasiList = [];
            const tidakMelepasiList = [];

            eligibleVendors.forEach(v => {
                if (v.status_pematuhan === 'mematuhi' || v.status_pematuhan === 1) {
                    melepasiList.push(v);
                } else if (v.status_pematuhan === 'tidak_mematuhi' || v.status_pematuhan === 0) {
                    tidakMelepasiList.push(v);
                }
            });

            // Render Melepasi Table
            if (melepasiList.length > 0) {
                melepasiList.forEach((v, idx) => {
                    const kodDisplay = v.kod ? escapeHtml(v.kod) : ((idx + 1) + '/' + melepasiList.length);
                    const ulasanTxt = v.catatan ? escapeHtml(v.catatan) : 'Mematuhi semua syarat penilaian penyata bulanan bank.';
                    $melepasiTbody.append(
                        '<tr>' +
                            '<td class="text-center py-3 px-3">' +
                                '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill font-monospace fw-bold">' + kodDisplay + '</span>' +
                            '</td>' +
                            '<td class="py-3 px-3 fw-semibold text-dark">' +
                                '<div>' + escapeHtml(v.name || '') + '</div>' +
                                '<div class="small text-muted font-normal mt-1">' + ulasanTxt + '</div>' +
                            '</td>' +
                        '</tr>'
                    );
                });
            } else {
                $melepasiTbody.append('<tr><td class="text-center text-muted py-4" colspan="2" style="font-size: 0.875rem;"><i class="bi bi-inbox me-1 fs-5"></i>Tiada pembekal melepasi lagi buat masa ini.</td></tr>');
            }

            // Render Tidak Melepasi Table
            if (tidakMelepasiList.length > 0) {
                tidakMelepasiList.forEach((v, idx) => {
                    const kodDisplay = v.kod ? escapeHtml(v.kod) : ((idx + 1) + '/' + tidakMelepasiList.length);
                    const ulasanTxt = v.catatan ? escapeHtml(v.catatan) : 'Tidak mematuhi syarat penilaian penyata bulanan bank.';
                    $tidakMelepasiTbody.append(
                        '<tr>' +
                            '<td class="text-center py-3 px-3">' +
                                '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill font-monospace fw-bold">' + kodDisplay + '</span>' +
                            '</td>' +
                            '<td class="py-3 px-3 fw-semibold text-dark">' +
                                '<div>' + escapeHtml(v.name || '') + '</div>' +
                                '<div class="small text-danger font-medium mt-1">' + ulasanTxt + '</div>' +
                            '</td>' +
                        '</tr>'
                    );
                });
            } else {
                $tidakMelepasiTbody.append('<tr><td class="text-center text-muted py-4" colspan="2" style="font-size: 0.875rem;"><i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod dijumpai</td></tr>');
            }

            if ($melepasiText.length) $melepasiText.text(melepasiList.length + ' pembekal melepasi');
            if ($tidakMelepasiText.length) $tidakMelepasiText.text(tidakMelepasiList.length + ' pembekal tidak melepasi');
        }

        function calculateStep2Totals() {
            let total = 0;
            let count = 0;
            $('.step2-bulan-input').each(function () {
                const val = parseFloat($(this).val().replace(/,/g, '')) || 0;
                total += val;
                count++;
            });

            const purata = count > 0 ? (total / count) : 0;
            const roundedPurata = Math.round(purata * 100) / 100;

            let autoSkor = 0;
            const itemsArr = (PENYATA_BANK_CONFIG.scoring_items && PENYATA_BANK_CONFIG.scoring_items.length)
                ? [...PENYATA_BANK_CONFIG.scoring_items].sort((a, b) => (parseFloat(a.dari) || 0) - (parseFloat(b.dari) || 0))
                : [
                    { dari: 0, hingga: 10064.99, skema: '0' },
                    { dari: 10065, hingga: null, skema: '10' }
                ];

            let matchFound = false;

            // 1. Check exact range match
            for (const s of itemsArr) {
                const dari = parseFloat(s.dari) || 0;
                const hingga = (s.hingga !== null && s.hingga !== undefined && parseFloat(s.hingga) > 0)
                    ? parseFloat(s.hingga)
                    : Infinity;

                if (roundedPurata >= dari && roundedPurata <= hingga) {
                    autoSkor = s.skema;
                    matchFound = true;
                    break;
                }
            }

            // 2. Exceeds highest range? Assign highest available score
            if (!matchFound && itemsArr.length > 0) {
                const maxItem = itemsArr[itemsArr.length - 1];
                const maxHingga = (maxItem.hingga !== null && maxItem.hingga !== undefined && parseFloat(maxItem.hingga) > 0)
                    ? parseFloat(maxItem.hingga)
                    : Infinity;

                if (roundedPurata > maxHingga) {
                    autoSkor = maxItem.skema;
                    matchFound = true;
                } else {
                    // 3. Middle gap fallback: assign nearest lower tier score
                    for (const s of itemsArr) {
                        if (roundedPurata >= (parseFloat(s.dari) || 0)) {
                            autoSkor = s.skema;
                        }
                    }
                }
            }

            $('#step2-jumlah-amaun').val(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#step2-purata').val(purata.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#step2-skor-automatik').val(autoSkor);
        }

        // Click Papar / Menilai inside Step 2 Modal 1 for a vendor
        $(document).on('click', '.btn-papar-penyata-bank-detail-step2', function () {
            activeStep2VendorId = $(this).data('vendor-id');
            const kod = $(this).data('kod');
            const nama = $(this).data('nama');

            $('#modalStep2KodPembekal').text(kod);
            $('#modalStep2NamaPembekal').text(nama || '-');
            $('#modalButiranCadanganKewanganTajukStep2').text($('#modalCadanganKewanganTajukStep2').text() || $('#modalDocTitle').text() || 'Penyata Bank Terkini (3 Bulan Terakhir) Syarikat');

            // Render Section 1: Dokumen Sokongan Petender
            const $dokTbody = $('#modalStep2DokumenSemakSilangTableBody');
            if ($dokTbody.length) {
                $dokTbody.empty();

                let vendorFiles = [];

                // 1. From VENDOR_FORM_PAYLOADS (Penyata Bank Form submitted files)
                const vPayloadDataForDocs = (typeof VENDOR_FORM_PAYLOADS !== 'undefined' && VENDOR_FORM_PAYLOADS[activeStep2VendorId]) ? VENDOR_FORM_PAYLOADS[activeStep2VendorId] : {};
                if (vPayloadDataForDocs) {
                    if (Array.isArray(vPayloadDataForDocs.dokumen_sokongan)) {
                        vendorFiles = vendorFiles.concat(vPayloadDataForDocs.dokumen_sokongan);
                    }
                    if (Array.isArray(vPayloadDataForDocs.files)) {
                        vendorFiles = vendorFiles.concat(vPayloadDataForDocs.files);
                    }
                    if (Array.isArray(vPayloadDataForDocs.attachments)) {
                        vendorFiles = vendorFiles.concat(vPayloadDataForDocs.attachments);
                    }
                }

                // 2. From PENYATA_BANK_CONFIG.files
                if (PENYATA_BANK_CONFIG.files && Array.isArray(PENYATA_BANK_CONFIG.files)) {
                    const pFiles = PENYATA_BANK_CONFIG.files.filter(f => !f.vendor_id || f.vendor_id == activeStep2VendorId);
                    vendorFiles = vendorFiles.concat(pFiles);
                }

                // 3. From DOKUMEN_BY_VENDOR for penyata_bank
                if (typeof DOKUMEN_BY_VENDOR !== 'undefined' && DOKUMEN_BY_VENDOR[activeStep2VendorId]) {
                    const vDocs = DOKUMEN_BY_VENDOR[activeStep2VendorId];
                    if (Array.isArray(vDocs)) {
                        vDocs.forEach(d => {
                            const files = d.vendor_content?.files || d.files || [];
                            if (Array.isArray(files) && files.length > 0) {
                                vendorFiles = vendorFiles.concat(files);
                            }
                        });
                    }
                }

                // Deduplicate files by URL/path/name
                const seen = new Set();
                const uniqueFiles = [];
                vendorFiles.forEach(f => {
                    if (!f) return;
                    const key = f.url || f.path || f.name || f.filename || JSON.stringify(f);
                    if (!seen.has(key)) {
                        seen.add(key);
                        uniqueFiles.push(f);
                    }
                });

                if (uniqueFiles.length > 0) {
                    uniqueFiles.forEach(f => {
                        const fileName = f.name || f.filename || f.original_name || f.title || 'Dokumen Sokongan';
                        let rawUrl = f.url || f.path || f.file_path || f.filepath || '#';
                        let fileUrl = '#';
                        if (rawUrl && rawUrl !== '#') {
                            if (/^(https?:|\/\/|data:)/i.test(rawUrl)) {
                                fileUrl = rawUrl;
                            } else {
                                rawUrl = rawUrl.replace(/\\/g, '/').replace(/^public\//, '');
                                if (!rawUrl.startsWith('/')) rawUrl = '/' + rawUrl;
                                if (!rawUrl.startsWith('/storage/')) rawUrl = '/storage' + rawUrl;
                                fileUrl = rawUrl;
                            }
                        }
                        const ext = (fileName.split('.').pop() || '').toLowerCase();
                        const isPdf = (ext === 'pdf');

                        const actionBtn = isPdf
                            ? '<a href="' + escapeHtml(fileUrl) + '" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary px-3 py-1 d-inline-flex align-items-center gap-1 font-monospace">' +
                                '<i class="bi bi-eye me-1"></i><span>Papar Dokumen</span>' +
                              '</a>'
                            : '<a href="' + escapeHtml(fileUrl) + '" download="' + escapeHtml(fileName) + '" class="btn btn-sm btn-outline-primary px-3 py-1 d-inline-flex align-items-center gap-1 font-monospace">' +
                                '<i class="bi bi-download me-1"></i><span>Papar Dokumen</span>' +
                              '</a>';

                        $dokTbody.append(
                            '<tr>' +
                                '<td class="px-3 py-2.5">' +
                                    '<div class="d-flex align-items-center gap-2">' +
                                        '<i class="bi ' + (isPdf ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-text text-primary') + ' fs-6 me-1"></i>' +
                                        '<span class="fw-medium text-dark">' + escapeHtml(fileName) + '</span>' +
                                    '</div>' +
                                '</td>' +
                                '<td class="text-center px-3 py-2.5">' + actionBtn + '</td>' +
                            '</tr>'
                        );
                    });
                } else {
                    $dokTbody.append('<tr><td colspan="2" class="text-center text-muted py-3 fst-italic"><i class="bi bi-info-circle me-1"></i>Tiada Dokumen Dimuatnaik</td></tr>');
                }
            }

            // Render dynamic month rows matching PENYATA_BANK_CONFIG.bulans
            const $bulanTbody = $('#modalStep2BulanTableBody');
            $bulanTbody.empty();

            const bulansArr = (PENYATA_BANK_CONFIG.bulans && PENYATA_BANK_CONFIG.bulans.length)
                ? PENYATA_BANK_CONFIG.bulans
                : [
                    { bulan: 6, tahun: 2025, nama: 'Bulan 6 (Jun 2025)', jumlah: 500000 },
                    { bulan: 7, tahun: 2025, nama: 'Bulan 7 (Julai 2025)', jumlah: 300000 },
                    { bulan: 8, tahun: 2025, nama: 'Bulan 8 (Ogos 2025)', jumlah: 200000 }
                ];

            const vPayloadData = (typeof VENDOR_FORM_PAYLOADS !== 'undefined' && VENDOR_FORM_PAYLOADS[activeStep2VendorId])
                ? VENDOR_FORM_PAYLOADS[activeStep2VendorId]
                : {};

            const itemObj = SEMAK_PAYLOAD[activeStep2Uuid];
            const vRow = itemObj?.vendors?.find(v => v.vendor_id == activeStep2VendorId);

            bulansArr.forEach((b, idx) => {
                const rowId = 'step2-bulan-' + (b.bulan || (idx + 6));
                
                let vendorAmtVal = null;
                const mNum = parseInt(b.bulan);

                // 1. Check multi-account array in vendor payload
                if (vPayloadData.accounts && Array.isArray(vPayloadData.accounts) && vPayloadData.accounts.length > 0) {
                    let totalMonthFromAccounts = 0;
                    let foundAnyInAccounts = false;
                    vPayloadData.accounts.forEach(acc => {
                        if (acc.bulans && Array.isArray(acc.bulans)) {
                            const mb = acc.bulans.find(m => parseInt(m.bulan) === mNum);
                            if (mb && mb.jumlah !== undefined && mb.jumlah !== null) {
                                totalMonthFromAccounts += (parseFloat(mb.jumlah) || 0);
                                foundAnyInAccounts = true;
                            }
                        }
                    });
                    if (foundAnyInAccounts) {
                        vendorAmtVal = totalMonthFromAccounts;
                    }
                }

                // 2. Check top-level bulans array in vendor payload
                if (vendorAmtVal === null && vPayloadData.bulans && Array.isArray(vPayloadData.bulans)) {
                    const matchB = vPayloadData.bulans.find(mb => parseInt(mb.bulan) === mNum);
                    if (matchB && matchB.jumlah !== undefined && matchB.jumlah !== null) {
                        vendorAmtVal = parseFloat(matchB.jumlah);
                    }
                }

                // 3. Fallback direct property matches
                if (vendorAmtVal === null && vPayloadData['bulan_' + mNum] !== undefined) {
                    vendorAmtVal = parseFloat(vPayloadData['bulan_' + mNum]);
                } else if (vendorAmtVal === null && vPayloadData['bulan' + mNum] !== undefined) {
                    vendorAmtVal = parseFloat(vPayloadData['bulan' + mNum]);
                } else if (vendorAmtVal === null && vRow && vRow.bulans && vRow.bulans[mNum] !== undefined) {
                    vendorAmtVal = parseFloat(vRow.bulans[mNum]);
                }

                if (vendorAmtVal === null || isNaN(vendorAmtVal)) {
                    vendorAmtVal = (typeof b.jumlah === 'number' && b.jumlah > 0) ? b.jumlah : (parseFloat(b.jumlah) || 0);
                }

                const amtVal = vendorAmtVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                $bulanTbody.append(
                    '<tr>' +
                        '<td class="text-center font-monospace fw-semibold px-3 py-2.5 bg-light-subtle">' + escapeHtml(b.nama || ('Bulan ' + b.bulan)) + '</td>' +
                        '<td class="px-3 py-2">' +
                            '<div class="input-group input-group-sm">' +
                                '<span class="input-group-text bg-light text-muted fw-bold">RM</span>' +
                                '<input type="text" class="form-control text-end font-monospace fw-semibold step2-bulan-input" id="' + rowId + '" inputmode="decimal" value="' + escapeHtml(amtVal) + '" aria-label="Amaun ' + escapeHtml(b.nama) + '">' +
                            '</div>' +
                        '</td>' +
                    '</tr>'
                );
            });

            // Render dynamic reference scoring scale matching PENYATA_BANK_CONFIG.scoring_items
            const $scaleTbody = $('#modalStep2ScoringScaleTableBody');
            $scaleTbody.empty();

            const itemsArr = (PENYATA_BANK_CONFIG.scoring_items && PENYATA_BANK_CONFIG.scoring_items.length)
                ? PENYATA_BANK_CONFIG.scoring_items
                : [
                    { dari: 0, hingga: 10064.99, skema: '0' },
                    { dari: 10065, hingga: null, skema: '10' }
                ];

            itemsArr.forEach(s => {
                const dariStr = (typeof s.dari === 'number') ? s.dari.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : s.dari;
                const hinggaStr = (s.hingga !== null && s.hingga !== undefined)
                    ? ((typeof s.hingga === 'number') ? s.hingga.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : s.hingga)
                    : 'Ke Atas';

                const isPass = (s.skema && s.skema !== '0' && s.skema !== 0);
                const badge = isPass
                    ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1 rounded-pill">' + escapeHtml(s.skema) + '</span>'
                    : '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2.5 py-1 rounded-pill">' + escapeHtml(s.skema) + '</span>';

                $scaleTbody.append(
                    '<tr>' +
                        '<td class="font-monospace">' + dariStr + '</td>' +
                        '<td class="font-monospace">' + hinggaStr + '</td>' +
                        '<td>' + badge + '</td>' +
                    '</tr>'
                );
            });

            const savedStatus  = vRow ? vRow.status_pematuhan : null;
            const savedCatatan = vRow ? (vRow.catatan || '') : '';

            const $kelayakan = $('#step2-status-kelayakan');
            const $catatan   = $('#step2-catatan-penyata');

            if (savedStatus === 'mematuhi' || savedStatus === 1) {
                $kelayakan.val('layak');
            } else if (savedStatus === 'tidak_mematuhi' || savedStatus === 0) {
                $kelayakan.val('tidak_layak');
            } else {
                $kelayakan.val('');
            }

            $catatan.val(savedCatatan);

            // Read-only handling for Step 2
            const isStep2ReadOnly = (dbConfirmed.step2 || dbCurrentStep > 2);
            if (isStep2ReadOnly) {
                $kelayakan.prop('disabled', true);
                $catatan.prop('disabled', true).prop('readonly', true);
                $('.step2-bulan-input').prop('disabled', true).prop('readonly', true);
                $('#btnStep2SimpanPenyataBank').addClass('d-none');
            } else {
                $kelayakan.prop('disabled', false);
                $catatan.prop('disabled', false).prop('readonly', false);
                $('.step2-bulan-input').prop('disabled', false).prop('readonly', false);
                $('#btnStep2SimpanPenyataBank').removeClass('d-none');
            }

            calculateStep2Totals();

            const modal1El = document.getElementById('modalPenilaianCadanganKewanganStep2');
            const modal2El = document.getElementById('modalButiranCadanganKewanganStep2');

            if (modal1El) bootstrap.Modal.getInstance(modal1El)?.hide();
            if (modal2El) bootstrap.Modal.getOrCreateInstance(modal2El).show();
        });

        // Calculate totals on monthly amount inputs change
        $(document).on('input', '.step2-bulan-input', calculateStep2Totals);

        // Save evaluation in Modal 2
        $(document).on('click', '#btnStep2SimpanPenyataBank', async function () {
            const kelayakan = $('#step2-status-kelayakan').val();
            const catatan   = $('#step2-catatan-penyata').val() ? $('#step2-catatan-penyata').val().trim() : '';

            if (!kelayakan) {
                Swal.fire({
                    title: 'Ralat Validasi',
                    text: 'Sila pilih Status Kelayakan (Layak / Tidak Layak).',
                    icon: 'warning',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#1e293b'
                });
                return;
            }

            if (kelayakan === 'tidak_layak' && !catatan) {
                Swal.fire({
                    title: 'Ralat Validasi',
                    text: 'Catatan wajib diisi jika Status Kelayakan = Tidak Layak.',
                    icon: 'warning',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#1e293b'
                });
                return;
            }

            const statusPematuhan = (kelayakan === 'layak') ? 'mematuhi' : 'tidak_mematuhi';
            const skorVal = parseFloat($('#step2-skor-automatik').val()) || 0;

            try {
                const res = await $.ajax({
                    url: SIMPAN_URL,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        tender: TENDER_IDENTIFIER,
                        vendor_id: activeStep2VendorId,
                        checklist_item_uuid: activeStep2Uuid,
                        status_pematuhan: statusPematuhan,
                        catatan: catatan,
                        skor: skorVal,
                        step: 2
                    }
                });

                if (SEMAK_PAYLOAD[activeStep2Uuid]) {
                    const vRow = SEMAK_PAYLOAD[activeStep2Uuid].vendors.find(v => v.vendor_id == activeStep2VendorId);
                    if (vRow) {
                        vRow.status_pematuhan = statusPematuhan;
                        vRow.catatan = catatan;
                        vRow.skor = (res.skor !== undefined && res.skor !== null) ? res.skor : skorVal;
                    }
                }

                const itemObj = SEMAK_PAYLOAD[activeStep2Uuid];
                const rawVendors = itemObj?.vendors || [];
                const failedVendorIds = getFailedStep1VendorIds();
                const eligibleVendors = rawVendors.filter(v => !failedVendorIds.includes(parseInt(v.vendor_id)));

                const totalV = eligibleVendors.length;
                const reviewedV = eligibleVendors.filter(v => v.status_pematuhan !== null && v.status_pematuhan !== '' && v.status_pematuhan !== undefined).length;
                const isAllReviewed = (totalV > 0 && reviewedV === totalV);

                const $outerTr = $('tr[data-item-uuid="' + activeStep2Uuid + '"]');
                const $statusCell = $outerTr.find('.status-penilaian');

                if ($statusCell.length) {
                    if (isAllReviewed) {
                        $statusCell.html('<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-check-circle me-1"></i>Selesai</span>');
                    } else {
                        $statusCell.html('<span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill"><i class="bi bi-clock me-1"></i>Menunggu Penilaian</span>');
                    }
                }

                Swal.fire({
                    title: 'Berjaya!',
                    text: 'Penilaian Penyata Bulanan Bank telah disimpan.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                const modal2El = document.getElementById('modalButiranCadanganKewanganStep2');
                const modal1El = document.getElementById('modalPenilaianCadanganKewanganStep2');

                if (modal2El) bootstrap.Modal.getInstance(modal2El)?.hide();
                renderStep2VendorListModal();
                renderStep2RumusanTables();
                if (modal1El) bootstrap.Modal.getOrCreateInstance(modal1El).show();

            } catch (err) {
                Swal.fire({
                    title: 'Ralat',
                    text: err.responseJSON?.message || 'Gagal menyimpan penilaian. Sila cuba lagi.',
                    icon: 'error',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#1e293b'
                });
            }
        });

        $(document).on('click', '.btn-preview-file', function (e) {
            e.preventDefault();
            let url  = $(this).attr('href');
            const name = $(this).data('name') || $(this).text();

            if (!url || url === '#') return;

            if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
                try {
                    const parsed = new URL(url);
                    url = parsed.pathname + parsed.search + parsed.hash;
                } catch (err) {}
            }

            $('#modalPreviewTitle').text(name);
            $('#btnNewTabPreview').attr('href', url);
            $('#btnFallbackDownload').attr('href', url);

            $('#previewSpinner').removeClass('d-none');
            $('#previewIframe').addClass('d-none').attr('src', '');
            $('#previewImageWrapper').addClass('d-none');
            $('#previewImage').attr('src', '');
            $('#previewFallback').addClass('d-none');

            const urlPath  = url.split(/[#?]/)[0];
            const extension = urlPath.split('.').pop().trim().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            const filenamePortion = urlPath.substring(urlPath.lastIndexOf('/') + 1);
            const isProbablyPage  = !filenamePortion.includes('.') || filenamePortion.endsWith('.html') || url.includes('form') || url.includes('borang');

            const $icon = $('#previewIcon');
            $icon.removeClass();
            if (imageExtensions.includes(extension)) {
                $icon.addClass('bi bi-file-earmark-image text-primary fs-5');
                $('#previewImage').attr('src', url);
                $('#previewImageWrapper').removeClass('d-none');
            } else if (extension === 'pdf') {
                $icon.addClass('bi bi-file-earmark-pdf text-danger fs-5');
                $('#previewIframe').attr('src', url).removeClass('d-none');
            } else if (isProbablyPage) {
                $icon.addClass('bi bi-window text-success fs-5');
                $('#previewIframe').attr('src', url).removeClass('d-none');
            } else {
                $icon.addClass('bi bi-file-earmark-zip text-warning fs-5');
                $('#previewSpinner').addClass('d-none');
                $('#previewFallback').removeClass('d-none');
            }

            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPreview')).show();
        });

        $('#previewIframe').on('load', function () { $('#previewSpinner').addClass('d-none'); });
        $('#previewImage').on('load',  function () { $('#previewSpinner').addClass('d-none'); });

    });

    // Seterusnya Button Functionality with checkbox validation
    const msgTandakanPengesahan = 'Sila tandakan kotak pengesahan terlebih dahulu sebelum meneruskan.';
    document.querySelectorAll('.btn-seterusnya').forEach(btn => {
        btn.addEventListener('click', () => {
            const current = document.querySelector('.step-number.active');
            if (!current) return;

            const currentId = current.id;
            const checks = [
                { id: 'pematuhan-tab', el: document.getElementById('confirmLayakStep1') || document.getElementById('confirmLayak') },
                { id: 'penyata-bank-tab', el: document.getElementById('confirmLayakStep2') },
                { id: 'penilaian-tab', el: document.getElementById('confirmLayakStep3') },
            ];
            const stepCheck = checks.find(c => c.id === currentId);
            if (stepCheck?.el && !stepCheck.el.checked) {
                Swal.fire({
                    title: 'Amaran Pengesahan',
                    text: msgTandakanPengesahan,
                    icon: 'warning',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#1e293b'
                });
                return;
            }

            const stepNumMap = { 'pematuhan-tab': 1, 'penyata-bank-tab': 2, 'penilaian-tab': 3 };
            const currentStepNum = stepNumMap[currentId] || 1;

            if (typeof window.saveConfirmedStates === 'function') {
                window.saveConfirmedStates(currentStepNum, true);
            }

            const next = current.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
            if (next) next.click();
        });
    });
</script>

@endsection