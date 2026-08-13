@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b5-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b5-header-banner {
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

    .table-modern-b1 {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b1 thead th {
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

    .table-modern-b1 tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern-b1 tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern-b1 tbody td {
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
        font-size: 0.825rem;
        padding: 0.45rem 0.95rem;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-action-papar:hover {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
    }

    .status-badge-sempurna {
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

    .form-control-modern {
        border-color: #e2e8f0;
        border-radius: 8px;
        font-size: 0.825rem;
        padding: 0.45rem 0.75rem;
    }

    .form-control-modern:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-select-modern {
        border-color: #e2e8f0;
        border-radius: 8px;
        font-size: 0.825rem;
        padding: 0.45rem 0.75rem;
    }

    .confirmation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
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

    /* Modal Styling */
    .modal-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
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
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 5</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b5-card mb-4">
        <div class="b5-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Pertama</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 5 - Jadual Keputusan Penilaian Peringkat Pertama</h3>
                <p class="text-white-50 mb-0 small">Rumusan keseluruhan semakan kesempurnaan, kecukupan dokumen, modal & prestasi petender.</p>
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

    {{-- Section 1: Kriteria Penilaian Peringkat Pertama --}}
    <div class="b5-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-list-check text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Kriteria Penilaian Peringkat Pertama</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar & Semak</strong> untuk menyemak keputusan kriteria bagi setiap petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-shield-check me-1"></i>4 Kriteria Utama
            </span>
        </div>

        <div class="table-modern-wrapper mb-2">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">#</th>
                            <th><i class="bi bi-file-earmark-text text-danger me-1"></i> Kriteria-Kriteria Penilaian</th>
                            <th style="width: 200px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $kriteria = [
                                ['label' => 'Kesempurnaan Tender (Borang 1)', 'icon' => 'bi-file-earmark-check'],
                                ['label' => 'Kecukupan Dokumen (Borang 2)', 'icon' => 'bi-folder-check'],
                                ['label' => 'Kecukupan Modal (Borang 3)', 'icon' => 'bi-calculator'],
                                ['label' => 'Prestasi Kerja Semasa (Borang 4)', 'icon' => 'bi-speedometer2'],
                            ];
                        @endphp

                        @foreach($kriteria as $idx => $item)
                            <tr>
                                <td class="text-center text-muted fw-bold small">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; margin-right:10px">
                                            <i class="bi {{ $item['icon'] }}"></i>
                                        </div>
                                        <span class="fw-semibold text-dark" style="font-size: 0.88rem;">{{ $item['label'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-action-papar" onclick="openPaparModal('{{ e($item['label']) }}')">
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

    {{-- Section 2: Rumusan Keputusan Penilaian Peringkat Pertama --}}
    <div class="b5-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-success-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-clipboard-data text-success fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Rumusan Keputusan Penilaian Peringkat Pertama</h5>
                    <p class="text-secondary small mb-0">Keputusan akhir penilaian peringkat pertama mengikut bilangan petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-success ms-auto">
                <i class="bi bi-check-circle-fill me-1"></i>Keputusan Semakan
            </span>
        </div>

        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 100px;" class="text-center">Bil</th>
                            <th style="width: 220px;" class="text-center">Keputusan Semakan</th>
                            <th>Catatan & Ulasan Semakan <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center fw-bold text-muted">1/2</td>
                            <td class="text-center">
                                <span class="status-badge-sempurna">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sempurna
                                </span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-modern" value="Semua dokumen lengkap dan mematuhi syarat." placeholder="Isi catatan...">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold text-muted">2/2</td>
                            <td class="text-center">
                                <span class="status-badge-sempurna">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sempurna
                                </span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-modern" value="Memenuhi syarat kelayakan penilaian." placeholder="Isi catatan...">
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
                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                        <span class="small fw-semibold text-dark">Bilangan Syarikat yang Berjaya:</span>
                        <input type="text" class="form-control form-control-sm text-center fw-bold font-monospace border-secondary-subtle" style="width: 60px;" value="2">
                    </div>
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
                <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </a>
                <button type="button" class="btn btn-submit-danger px-4 rounded-3" onclick="openSuccessModal()">
                    <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                </button>
            </div>
        </div>

    </div>

</div>

{{-- =========================
    MODAL PAPAR KRITERIA (paparModal)
========================== --}}
<div class="modal fade" id="paparModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-card">

            {{-- Modal Header --}}
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
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Semakan Kriteria Penilaian</span>
                        <h6 id="paparJenisKriteriaBadge" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Kesempurnaan Tender (Borang 1)</h6>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">

                {{-- Table Vendors in Modal --}}
                <div class="table-responsive rounded-3 mb-3" style="border: 1px solid #e5e7eb;">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                            <tr>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 80px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Bil</th>
                                <th class="text-uppercase fw-bold py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Maklumat Dokumen / Syarikat</th>
                                <th class="text-center text-uppercase fw-bold py-2" style="width: 220px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Status Kesempurnaan</th>
                                <th class="text-uppercase fw-bold py-2" style="width: 280px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold text-muted" style="background-color: #efeff0ff; color: #3f3f3fff;">1/2</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3 py-1">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2.5 rounded-3 d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px;">
                                            <i class="bi bi-file-earmark-pdf fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;">Syarikat Petender A</div>
                                            <a href="#" target="_blank" onclick="event.preventDefault();" class="d-inline-flex align-items-center gap-1.5 text-primary text-decoration-none small fw-medium" style="font-size: 0.78rem;">
                                                <i class="bi bi-file-pdf me-0.5"></i><span>Dokumen_Sokongan.pdf</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <select class="form-select form-select-modern text-center fw-semibold">
                                        <option value="Sempurna" selected>Sempurna</option>
                                        <option value="Tidak">Tidak Sempurna</option>
                                        <option value="GA">Tiada Borang GA</option>
                                        <option value="KS">Tiada Kerja Semasa</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-modern" value="Lengkap & teratur" placeholder="Catatan...">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold text-muted" style="background-color: #efeff0ff; color: #3f3f3fff;">2/2</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3 py-1">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2.5 rounded-3 d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px;">
                                            <i class="bi bi-file-earmark-pdf fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;">Syarikat Petender B</div>
                                            <a href="#" target="_blank" onclick="event.preventDefault();" class="d-inline-flex align-items-center gap-1.5 text-primary text-decoration-none small fw-medium" style="font-size: 0.78rem;">
                                                <i class="bi bi-file-pdf me-0.5"></i><span>Dokumen_Sokongan.pdf</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <select class="form-select form-select-modern text-center fw-semibold">
                                        <option value="Sempurna" selected>Sempurna</option>
                                        <option value="Tidak">Tidak Sempurna</option>
                                        <option value="GA">Tiada Borang GA</option>
                                        <option value="KS">Tiada Kerja Semasa</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-modern" value="Lengkap & teratur" placeholder="Catatan...">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    <strong>Perhatian:</strong> Sila pilih status kesempurnaan bagi setiap petender dan isi catatan pengesahan yang diperlukan.
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal / Tutup</button>
                <div class="d-flex align-items-center gap-2">
                    <span id="readOnlyNoticeStep1" class="badge bg-secondary text-white px-3 py-2 d-none"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja (Langkah Telah Disahkan)</span>
                    <button type="button" class="btn btn-sm btn-success px-4 fw-bold" id="btnSimpanDalamModal">
                        <i class="bi bi-save me-2"></i>Simpan Penilaian
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- =========================
    MODAL SUCCESS
========================== --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content modal-card p-4 text-center">

            <div class="my-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="width: 72px; height: 72px;">
                    <i class="bi bi-check-circle-fill display-5"></i>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-1">Berjaya Disimpan!</h5>
            <p class="text-muted small mb-4">Maklumat keputusan penilaian peringkat pertama telah berjaya disimpan ke dalam sistem.</p>

            <button type="button" class="btn btn-submit-danger px-4 py-2 rounded-3 w-100" data-bs-dismiss="modal">
                Faham & Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openPaparModal(kriteriaLabel){
        const badgeEl = document.getElementById('paparJenisKriteriaBadge');
        if (badgeEl) badgeEl.textContent = kriteriaLabel;

        const modal = new bootstrap.Modal(document.getElementById('paparModal'));
        modal.show();
    }

    function openSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }

    function onModalSimpan(){
        const paparEl = document.getElementById('paparModal');
        const paparModal = bootstrap.Modal.getInstance(paparEl) || new bootstrap.Modal(paparEl);
        paparModal.hide();

        setTimeout(() => openSuccessModal(), 250);
    }
</script>
@endsection
