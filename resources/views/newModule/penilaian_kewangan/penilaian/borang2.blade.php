@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b2-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b2-header-banner {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        padding: 1.5rem 1.75rem;
        color: #ffffff;
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

    .section-badge-pill-primary {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 600;
        font-size: 0.725rem;
        padding: 0.25rem 0.65rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(29, 78, 216, 0.05);
    }

    .section-badge-pill-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        font-size: 0.725rem;
        padding: 0.25rem 0.65rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(4, 120, 87, 0.05);
    }

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .table-modern-b2 {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b2 thead th {
        background: #1e293b;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.775rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.85rem 1.25rem;
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
        padding: 0.85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #334155;
    }

    .btn-action-papar {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.38rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .btn-action-papar:hover {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
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
        padding: 1.25rem 1.5rem;
    }

    /* Modal Styling */
    .modal-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .form-control-modern, .form-select-modern {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.45rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        border-color: var(--sg-red);
        box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.15);
    }

    .btn-submit-danger {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        font-weight: 700;
    }

    .btn-submit-danger:hover {
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
        transform: translateY(-1px);
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

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 2</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b2-card mb-4">
        <div class="b2-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Pertama</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 2 - Analisa Kecukupan Dokumen</h3>
                <p class="text-white-50 mb-0 small">Semakan kecukupan & penyemakan dokumen kewangan sokongan petender.</p>
            </div>
        </div>
    </div>

    {{-- Top Info Grid Card --}}
    <div class="info-top-card p-3.5 mb-4" style="border: 1px solid #e2e8f0; border-radius: 16px; background: #ffffff; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-archive fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">No. Sebut Harga / Tender</div>
                        <div class="info-item-value text-danger font-monospace fw-bold" style="font-size: 0.95rem;">QT210000000023741</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-building fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">PTJ Perolehan</div>
                        <div class="info-item-value text-dark fw-bold" style="font-size: 0.88rem;">JABATAN PENGAIRAN DAN SALIRAN</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fffbeb; color: #d97706;">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Status Proses</div>
                        <div class="mt-1">
                            <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                Menunggu Pengesahan
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #ecfdf5; color: #059669;">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Sah Laku Tamat</div>
                        <div class="info-item-value text-dark font-monospace fw-bold" style="font-size: 0.95rem;">17/01/2022</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 1: Senarai Dokumen Untuk Disemak --}}
    <div class="b2-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-folder-check text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Dokumen Untuk Dianalisis</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar & Semak</strong> untuk menyemak dokumen yang dikemukakan petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-file-earmark-medical me-1"></i>6 Dokumen Kewangan
            </span>
        </div>

        <div class="table-modern-wrapper mb-2">
            <div class="table-responsive">
                <table class="table table-modern-b2 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">#</th>
                            <th><i class="bi bi-file-earmark-text text-danger me-1"></i> Dokumen Sokongan Kewangan</th>
                            <th style="width: 180px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $docs = [
                                ['id' => 'imbangan', 'name' => 'Lembaran Imbangan', 'icon' => 'bi-journal-bookmark'],
                                ['id' => 'penyata_bank', 'name' => 'Penyata Bulanan / Akaun Bank', 'icon' => 'bi-bank'],
                                ['id' => 'bon_saham', 'name' => 'Bon atau Saham', 'icon' => 'bi-cash-stack'],
                                ['id' => 'prestasi', 'name' => 'Prestasi Kerja Semasa Petender', 'icon' => 'bi-briefcase'],
                                ['id' => 'laporan_ca', 'name' => 'Laporan Bank atau Borang CA', 'icon' => 'bi-file-earmark-text'],
                                ['id' => 'laporan_penyelia', 'name' => 'Laporan Penyelia Projek Bagi Kerja Semasa (Borang GA)', 'icon' => 'bi-person-badge'],
                            ];
                        @endphp
                        @foreach($docs as $idx => $doc)
                            <tr>
                                <td class="text-center text-muted fw-bold small">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; margin-right:10px">
                                            <i class="bi {{ $doc['icon'] }}"></i>
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $doc['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action-papar" type="button" onclick="openDocModal('{{ $doc['id'] }}','{{ $doc['name'] }}')">
                                        <i class="bi bi-eye me-1"></i>Papar & Semak
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Section 2: Rumusan Keputusan --}}
    <div class="b2-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-success-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-clipboard-data text-success fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Rumusan Keputusan Analisa Kecukupan Dokumen</h5>
                    <p class="text-secondary small mb-0">Status kecukupan dokumen petender <span class="text-primary font-italic">(*Cukup = Cukup walaupun tidak kemukakan Borang GA)</span></p>
                </div>
            </div>
            <span class="section-badge-pill-success ms-auto">
                <i class="bi bi-check-circle-fill me-1"></i>Hasil Semakan
            </span>
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
                        <i class="bi bi-people me-1 text-danger"></i>Bilangan Pembekal Dinilai
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

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </a>
                <button type="button" class="btn btn-submit-danger px-4 rounded-3" onclick="openSavedModal()">
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
        <div class="modal-content modal-card">

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
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">DOKUMEN KEWANGAN</span>
                        <h6 id="docModalName" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">-</h6>
                    </div>
                    <div class="mx-3 align-self-stretch" style="width: 1px; background: #d1d5db;"></div>
                    <span class="text-secondary" style="font-size: 0.78rem;">Semakan Kecukupan Dokumen Mengikut Syarikat Petender</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                {{-- ===== VIEW A: LIST PEMBEKAL ===== --}}
                <div id="docViewList">

                    <div class="table-responsive rounded-3 mb-2" style="border: 1px solid #e5e7eb;">
                        <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                                <tr>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 80px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Bil</th>
                                    <th class="text-uppercase fw-bold py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Dokumen / Syarikat Petender</th>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 190px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Dikemukakan</th>
                                    <th id="thDiaudit" class="text-center text-uppercase fw-bold py-2" style="width: 190px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Diaudit</th>
                                    <th class="text-uppercase fw-bold py-2" style="width: 260px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="docRows">
                            </tbody>
                        </table>
                    </div>

                    <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mt-3" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        </svg>
                        Klik pautan dokumen atau pilih status penyerahan untuk meneruskan penilaian pematuhan.
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
                        <button class="btn btn-submit-danger px-4 rounded-3" type="button" onclick="saveFromDocModal()">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Detail Penyata Bank
                        </button>
                    </div>
                </div>

            </div>

            <div id="docViewListFooter" class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal / Tutup</button>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-success px-4 fw-bold" type="button" onclick="saveFromDocModal()">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Semakan
                    </button>
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

            <button type="button" class="btn btn-submit-danger px-4 py-2 rounded-3 w-100" data-bs-dismiss="modal">
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
                { bil:'1/2', label:'Syarikat Petender A', showAudit:true },
                { bil:'2/2', label:'Syarikat Petender B', showAudit:true },
            ],
        },
        bon_saham: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Syarikat Petender A', showAudit:false },
                { bil:'2/2', label:'Syarikat Petender B', showAudit:false },
            ],
        },
        prestasi: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Syarikat Petender A', showAudit:false },
                { bil:'2/2', label:'Syarikat Petender B', showAudit:false },
            ],
        },
        laporan_ca: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Syarikat Petender A', showAudit:false },
                { bil:'2/2', label:'Syarikat Petender B', showAudit:false },
            ],
        },
        laporan_penyelia: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Syarikat Petender A', icon:true, link:false, showAudit:false, ddText:'Ya / Tidak / T.K.S' },
                { bil:'2/2', label:'Syarikat Petender B', icon:true, link:false, showAudit:false, ddText:'Ya / Tidak / T.K.S' },
            ],
        },
        penyata_bank: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Syarikat Petender A', link:true, goDetail:true, showAudit:false },
                { bil:'2/2', label:'Syarikat Petender B', link:true, goDetail:true, showAudit:false },
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
    const docViewListFooter = document.getElementById('docViewListFooter');

    let currentDocType = null;

    function openDocModal(type, name){
        currentDocType = type;

        docModalName.textContent = name || '-';

        // reset views
        docViewList.style.display = 'block';
        if (docViewListFooter) docViewListFooter.style.display = 'flex';
        docViewPenyataBank.style.display = 'none';

        // inject rows
        const conf = docTemplates[type] || { showDiaudit:false, rows:[] };

        thDiaudit.style.display = conf.showDiaudit ? '' : 'none';

        docRows.innerHTML = conf.rows.map(r => {
            const docCell = `
                <div class="d-flex align-items-center gap-3 py-1">
                    <div class="bg-danger bg-opacity-10 text-danger p-2.5 rounded-3 d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px;">
                        <i class="bi bi-file-earmark-pdf fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;">${escapeHtml(r.label)}</div>
                        <a href="javascript:void(0)" onclick="${r.goDetail ? `openPenyataBankDetail()` : `event.preventDefault();`}" class="d-inline-flex align-items-center gap-1.5 text-primary text-decoration-none small fw-medium" style="font-size: 0.78rem;">
                            <span>Dokumen Sokongan.pdf</span>
                        </a>
                    </div>
                </div>
            `;

            const dikemukakanDd = r.ddText
                ? `<select class="form-select form-select-modern text-center fw-semibold">
                        <option selected>${escapeHtml(r.ddText)}</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                   </select>`
                : `<select class="form-select form-select-modern text-center fw-semibold">
                        <option selected>Ya / Tidak</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                   </select>`;

            const diauditDd = conf.showDiaudit
                ? `<td class="text-center">
                        <select class="form-select form-select-modern text-center fw-semibold">
                            <option selected>Ya / Tidak</option>
                            <option>Ya</option>
                            <option>Tidak</option>
                        </select>
                   </td>`
                : ``;

            return `
                <tr>
                    <td class="text-center fw-bold text-muted" style="background-color: #efeff0ff; color: #3f3f3fff;">${escapeHtml(r.bil)}</td>
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
        if (docViewListFooter) docViewListFooter.style.display = 'none';
        docViewPenyataBank.style.display = 'block';
    }

    function backToDocList(){
        docViewPenyataBank.style.display = 'none';
        docViewList.style.display = 'block';
        if (docViewListFooter) docViewListFooter.style.display = 'flex';
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
