@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --kewangan-accent: #c41e3a;
        --kewangan-accent-dark: #8b1428;
        --p1-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        --teal-accent: #0d9488;
        --teal-dark: #0f766e;
    }

    .b2-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .b2-header-banner {
        background: var(--p1-gradient);
        padding: 1.75rem 2rem;
        color: #ffffff;
        position: relative;
    }

    /* Top Info Bar */
    .info-top-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .info-item-label {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-item-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
    }

    .section-title-bar {
        background: #f8fafc;
        border-left: 4px solid #0d9488;
        padding: 0.75rem 1.25rem;
        border-radius: 0 10px 10px 0;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .table-modern-b2 {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b2 thead th {
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.775rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.9rem 1.25rem;
        border-bottom: none;
        white-space: nowrap;
    }

    .table-modern-b2 tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern-b2 tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern-b2 tbody td {
        padding: 0.9rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #334155;
    }

    .btn-action-papar {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.38rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .btn-action-papar:hover {
        background: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
    }

    .status-badge-cukup {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-weight: 700;
        font-size: 0.775rem;
        padding: 0.3rem 0.8rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .confirmation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
    }

    /* Modal Styling */
    .doc-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header-custom {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: #ffffff;
        padding: 1.25rem 1.75rem;
    }

    .form-control-modern, .form-select-modern {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.45rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.15);
    }

    .doc-ic {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .doc-icon {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 6px;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no');
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewangan.show', $tenderParam) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
            <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
            <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 2: Analisa Kecukupan Dokumen</li>
        </ol>
    </nav>

    {{-- Header Banner Card --}}
    <div class="b2-card mb-4">
        <div class="b2-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-primary px-2.5 py-1 rounded-pill small fw-bold">Peringkat Pertama</span>
                    <span class="badge bg-white bg-opacity-20 text-white px-2.5 py-1 rounded-pill small">BORANG 2</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 2 - ANALISA KECUKUPAN DOKUMEN</h3>
                <p class="text-white-50 mb-0 small">Semakan kecukupan & penyemakan dokumen kewangan sokongan petender.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ $backToTenderUrl }}" class="btn btn-light btn-sm fw-semibold shadow-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Borang Penilaian
                </a>
            </div>
        </div>
    </div>

    {{-- Top Info Grid Card --}}
    <div class="info-top-card mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-sm-6 col-md-3 border-end-md">
                <div class="info-item-label"><i class="bi bi-hash me-1"></i>No. Sebut Harga / Tender</div>
                <div class="info-item-value text-danger font-monospace">QT210000000023741</div>
                <div class="text-muted extra-small mt-1">Tempoh Sah Laku: <strong>90 Hari</strong></div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end-md">
                <div class="info-item-label"><i class="bi bi-building me-1"></i>PTJ Perolehan</div>
                <div class="info-item-value text-dark line-clamp-1">JABATAN PENGAIRAN DAN SALIRAN</div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end-md">
                <div class="info-item-label"><i class="bi bi-hourglass-split me-1"></i>Status Proses</div>
                <div>
                    <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill small fw-semibold">
                        Menunggu Pengesahan Jawatan Kewangan
                    </span>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 text-md-end">
                <div class="info-item-label"><i class="bi bi-calendar-event me-1"></i>Sah Laku Tamat</div>
                <div class="info-item-value text-dark font-monospace">17/01/2022</div>
            </div>
        </div>
    </div>

    {{-- Section 1: Senarai Dokumen Untuk Disemak --}}
    <div class="b2-card p-4 mb-4">
        <div class="section-title-bar">
            <div>
                <h6 class="fw-bold text-dark mb-0">SENARAI DOKUMEN UNTUK DIANALSIS</h6>
                <small class="text-muted">Sila klik <strong>Papar & Semak</strong> untuk menyemak dokumen yang dikemukakan petender.</small>
            </div>
            <span class="badge bg-teal text-white fw-bold px-3 py-1.5 rounded-pill" style="background-color: #0d9488;">6 Dokumen Kewangan</span>
        </div>

        <div class="table-modern-wrapper mb-2">
            <div class="table-responsive">
                <table class="table table-modern-b2 align-middle">
                    <thead>
                        <tr>
                            <th>Dokumen Sokongan Kewangan</th>
                            <th style="width: 180px;" class="text-center">Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="doc-icon"><i class="bi bi-journal-bookmark"></i></div>
                                    <span class="fw-semibold text-dark">Lembaran Imbangan</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-papar" type="button" onclick="openDocModal('imbangan','Lembaran Imbangan')">
                                    <i class="bi bi-eye me-1"></i>Papar & Semak
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="doc-icon"><i class="bi bi-bank"></i></div>
                                    <span class="fw-semibold text-dark">Penyata Bulanan / Akaun Bank</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-papar" type="button" onclick="openDocModal('penyata_bank','Penyata Bank')">
                                    <i class="bi bi-eye me-1"></i>Papar & Semak
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="doc-icon"><i class="bi bi-cash-stack"></i></div>
                                    <span class="fw-semibold text-dark">Bon atau Saham</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-papar" type="button" onclick="openDocModal('bon_saham','Bon atau Saham')">
                                    <i class="bi bi-eye me-1"></i>Papar & Semak
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="doc-icon"><i class="bi bi-briefcase"></i></div>
                                    <span class="fw-semibold text-dark">Prestasi Kerja Semasa Petender</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-papar" type="button" onclick="openDocModal('prestasi','Prestasi Kerja Semasa Petender')">
                                    <i class="bi bi-eye me-1"></i>Papar & Semak
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="doc-icon"><i class="bi bi-file-earmark-text"></i></div>
                                    <span class="fw-semibold text-dark">Laporan Bank atau Borang CA</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-papar" type="button" onclick="openDocModal('laporan_ca','Laporan Bank atau Borang CA')">
                                    <i class="bi bi-eye me-1"></i>Papar & Semak
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="doc-icon"><i class="bi bi-person-badge"></i></div>
                                    <span class="fw-semibold text-dark">Laporan Penyelia Projek Bagi Kerja Semasa (Borang GA)</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-papar" type="button" onclick="openDocModal('laporan_penyelia','Laporan Penyelia Projek Bagi Kerja Semasa (Borang GA)')">
                                    <i class="bi bi-eye me-1"></i>Papar & Semak
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Section 2: Rumusan Keputusan --}}
    <div class="b2-card p-4 mb-4">
        <div class="section-title-bar">
            <div>
                <h6 class="fw-bold text-dark mb-0">RUMUSAN KEPUTUSAN ANALISA KECUKUPAN DOKUMEN</h6>
                <small class="text-muted">Status kecukupan dokumen petender <span class="text-primary font-italic">(*Cukup = Cukup walaupun tidak kemukakan Borang GA)</span></small>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1.5 rounded-pill">Hasil Semakan</span>
        </div>

        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b2 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 100px;" class="text-center">Bil</th>
                            <th style="width: 200px;" class="text-center">Keputusan Semakan</th>
                            <th>Ulasan / Catatan Kecukupan <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center fw-bold text-muted">1/2</td>
                            <td class="text-center">
                                <span class="status-badge-cukup">
                                    <i class="bi bi-check-circle-fill me-1"></i>Cukup
                                </span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-modern" value="Dokumen kewangan lengkap dikemukakan." placeholder="Isi ulasan...">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold text-muted">2/2</td>
                            <td class="text-center">
                                <span class="status-badge-cukup">
                                    <i class="bi bi-check-circle-fill me-1"></i>*Cukup
                                </span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-modern" value="Cukup (Tanpa Borang GA)." placeholder="Isi ulasan...">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center mb-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                        <i class="bi bi-people me-1"></i>Bilangan Pembekal Dinilai
                    </label>
                    <input type="number" class="form-control form-control-modern font-monospace fw-bold" value="2" style="max-width: 140px;">
                </div>
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" checked>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                            Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </a>
                <button type="button" class="btn btn-success px-4 rounded-3 fw-bold shadow-sm" style="background-color:#0d9488; border-color:#0d9488;" onclick="openSavedModal()">
                    <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                </button>
            </div>
        </div>

    </div>

</div>

{{-- =========================
    MODAL: PAPAR DOKUMEN 
========================== --}}
<div class="modal fade doc-modal" id="docModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header-custom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-20 p-2 rounded-3 text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-file-earmark-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-0" id="docModalTitle">DOKUMEN KEWANGAN</h6>
                        <small class="text-white-50" id="docModalName">-</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                {{-- ===== VIEW A: LIST PEMBEKAL ===== --}}
                <div id="docViewList">

                    <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 d-flex align-items-center gap-2 py-2.5 px-3 rounded-3 mb-3 text-primary small">
                        <i class="bi bi-info-circle-fill fs-6"></i>
                        <span>Klik pautan dokumen atau pilih status penyerahan untuk meneruskan penilaian pematuhan.</span>
                    </div>

                    <div class="table-modern-wrapper mb-4">
                        <div class="table-responsive">
                            <table class="table table-modern-b2 align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;" class="text-center">Bil</th>
                                        <th>Dokumen / Syarikat Petender</th>
                                        <th style="width: 190px;" class="text-center">Dikemukakan</th>
                                        <th id="thDiaudit" style="width: 190px;" class="text-center">Diaudit</th>
                                        <th style="width: 260px;">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="docRows">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-light px-3 fw-semibold" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary px-4 fw-bold shadow-sm" type="button" onclick="saveFromDocModal()">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Semakan
                        </button>
                    </div>

                </div>

                {{-- ===== VIEW B: PENYATA BANK DETAIL ===== --}}
                <div id="docViewPenyataBank" style="display:none;">

                    <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 d-flex align-items-center gap-2 py-2.5 px-3 rounded-3 mb-3 text-info-emphasis small">
                        <i class="bi bi-calculator-fill fs-6"></i>
                        <span>Sila pilih bulan pertama penyata bank yang perlu dikemukakan oleh petender dan isi baki bulanan.</span>
                    </div>

                    <div class="card p-3 mb-4 bg-light border-0">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Dari (Bulan)</label>
                                <select class="form-select form-select-modern">
                                    <option>Jun</option><option>Julai</option><option>Ogos</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tahun</label>
                                <select class="form-select form-select-modern"><option>2024</option><option>2025</option></select>
                            </div>

                            <div class="col-12 col-md-2 text-center text-muted fw-bold small pb-2">
                                hingga
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Hingga (Bulan)</label>
                                <select class="form-select form-select-modern">
                                    <option>Ogos</option><option>Sep</option><option>Okt</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tahun</label>
                                <select class="form-select form-select-modern"><option>2024</option><option>2025</option></select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Penyata Bank Bulan Jun (RM)</label>
                            <input class="form-control form-control-modern font-monospace text-end" value="0.00">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Penyata Bank Bulan Julai (RM)</label>
                            <input class="form-control form-control-modern font-monospace text-end" value="0.00">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Penyata Bank Bulan Ogos (RM)</label>
                            <input class="form-control form-control-modern font-monospace text-end" value="0.00">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-dark text-uppercase">Jumlah Keseluruhan Penyata Bank (RM)</label>
                            <input class="form-control form-control-modern font-monospace text-end fw-bold text-primary" value="0.00" readonly>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-dark text-uppercase">Purata Penyata Bank (RM)</label>
                            <input class="form-control form-control-modern font-monospace text-end fw-bold text-success" value="0.00" readonly>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <button class="btn btn-outline-secondary px-4 fw-semibold" type="button" onclick="backToDocList()">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Senarai
                        </button>
                        <button class="btn btn-primary px-4 fw-bold" type="button" onclick="saveFromDocModal()">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Detail Penyata Bank
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

{{-- =========================
    MODAL: SIMPAN SUCCESS 
========================== --}}
<div class="modal fade" id="savedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content modal-card p-4 text-center">

            <div class="my-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="width: 72px; height: 72px;">
                    <i class="bi bi-check-circle-fill display-5"></i>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-1">Berjaya Disimpan!</h5>
            <p class="text-muted small mb-4">Maklumat analisa kecukupan dokumen telah berjaya disimpan ke dalam sistem.</p>

            <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-bold w-100" data-bs-dismiss="modal">
                Faham & Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // ====== DATA ======
    const docTemplates = {
        imbangan: {
            showDiaudit: true,
            rows: [
                { bil:'1/2', label:'Lembaran Imbangan (Syarikat A)', link:true, showAudit:true },
                { bil:'2/2', label:'Lembaran Imbangan (Syarikat B)', link:true, showAudit:true },
            ],
        },
        bon_saham: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Bon atau Saham (Syarikat A)', link:true, showAudit:false },
                { bil:'2/2', label:'Bon atau Saham (Syarikat B)', link:true, showAudit:false },
            ],
        },
        prestasi: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Prestasi Kerja (Syarikat A)', link:true, showAudit:false },
                { bil:'2/2', label:'Prestasi Kerja (Syarikat B)', link:true, showAudit:false },
            ],
        },
        laporan_ca: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Laporan Bank / Borang CA (Syarikat A)', link:true, showAudit:false },
                { bil:'2/2', label:'Laporan Bank / Borang CA (Syarikat B)', link:true, showAudit:false },
            ],
        },
        laporan_penyelia: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Laporan Penyelia (Syarikat A)', icon:true, link:false, showAudit:false, ddText:'Ya / Tidak / T.K.S' },
                { bil:'2/2', label:'Laporan Penyelia (Syarikat B)', icon:true, link:false, showAudit:false, ddText:'Ya / Tidak / T.K.S' },
            ],
        },
        penyata_bank: {
            // first view is list with link; clicking link goes to detail view
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Penyata Bank (Syarikat A)', link:true, goDetail:true, showAudit:false },
                { bil:'2/2', label:'Penyata Bank (Syarikat B)', link:true, goDetail:true, showAudit:false },
            ],
        }
    };

    // ====== Modal refs ======
    const docModalEl = document.getElementById('docModal');
    const docModal = () => new bootstrap.Modal(docModalEl);

    const docModalName = document.getElementById('docModalName');
    const docRows = document.getElementById('docRows');
    const thDiaudit = document.getElementById('thDiaudit');

    const docViewList = document.getElementById('docViewList');
    const docViewPenyataBank = document.getElementById('docViewPenyataBank');

    let currentDocType = null;

    function openDocModal(type, name){
        currentDocType = type;

        docModalName.textContent = name || '-';

        // reset views
        docViewList.style.display = 'block';
        docViewPenyataBank.style.display = 'none';

        // inject rows
        const conf = docTemplates[type] || { showDiaudit:false, rows:[] };

        // show/hide Diaudit column like screenshot (some have Diaudit, some don't)
        thDiaudit.style.display = conf.showDiaudit ? '' : 'none';

        docRows.innerHTML = conf.rows.map(r => {
            const docCell = r.icon
                ? `<div class="doc-ic">
                        <span class="doc-icon">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </span>
                        <span class="fw-semibold text-dark">${escapeHtml(r.label)}</span>
                   </div>`
                : (r.link
                    ? `<a href="javascript:void(0)" class="text-primary text-decoration-none fw-semibold"
                         onclick="${r.goDetail ? `openPenyataBankDetail()` : ``}">
                         <i class="bi bi-file-earmark-text me-1"></i>${escapeHtml(r.label)}
                       </a>`
                    : escapeHtml(r.label)
                );

            const dikemukakanDd = r.ddText
                ? `<select class="form-select form-select-modern text-center">
                        <option selected>${escapeHtml(r.ddText)}</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                   </select>`
                : `<select class="form-select form-select-modern text-center">
                        <option selected>Ya / Tidak</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                   </select>`;

            const diauditDd = conf.showDiaudit
                ? `<td class="text-center" style="${conf.showDiaudit ? '' : 'display:none;'}">
                        <select class="form-select form-select-modern text-center">
                            <option selected>Ya / Tidak</option>
                            <option>Ya</option>
                            <option>Tidak</option>
                        </select>
                   </td>`
                : ``;

            return `
                <tr>
                    <td class="text-center fw-bold text-muted">${escapeHtml(r.bil)}</td>
                    <td>${docCell}</td>
                    <td class="text-center">${dikemukakanDd}</td>
                    ${diauditDd}
                    <td><input class="form-control form-control-modern" placeholder="Catatan..."></td>
                </tr>
            `;
        }).join('');

        docModal().show();
    }

    function openPenyataBankDetail(){
        docViewList.style.display = 'none';
        docViewPenyataBank.style.display = 'block';
    }

    function backToDocList(){
        docViewPenyataBank.style.display = 'none';
        docViewList.style.display = 'block';
    }

    function saveFromDocModal(){
        const m = bootstrap.Modal.getInstance(docModalEl);
        if(m) m.hide();
        openSavedModal();
    }

    function openSavedModal(){
        new bootstrap.Modal(document.getElementById('savedModal')).show();
    }

    // helpers
    function escapeHtml(str){
        return String(str ?? '')
            .replaceAll('&','&amp;')
            .replaceAll('<','&lt;')
            .replaceAll('>','&gt;')
            .replaceAll('"','&quot;')
            .replaceAll("'","&#039;");
    }

    // expose
    window.openDocModal = openDocModal;
    window.openSavedModal = openSavedModal;
    window.saveFromDocModal = saveFromDocModal;
    window.openPenyataBankDetail = openPenyataBankDetail;
    window.backToDocList = backToDocList;
</script>
@endsection
