@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b4-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b4-header-banner {
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

    .table-borang4-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang4-modern th {
        background: #6d6d79ff;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 0.75rem 0.6rem;
    }

    .table-borang4-modern th.subhead-main {
        background: #6d6d79ff;
        color: #ffffff;
        font-size: 0.75rem;
    }

    .table-borang4-modern th.subhead-level2 {
        background: #d7d7d9;
        color: #3f3f3f;
        font-size: 0.725rem;
        font-weight: 700;
        border-color: #cbd5e1;
    }

    .table-borang4-modern td {
        padding: 0.75rem 0.8rem;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang4-modern tbody tr:hover {
        background-color: #f8fafc;
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

    .btn-action-papar {
        background-color: #eff6ff;
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
        background-color: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
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

    $vendors = [
        ['no' => 1, 'kod' => '45/53'],
        ['no' => 2, 'kod' => '27/53'],
        ['no' => 3, 'kod' => '34/53'],
        ['no' => 4, 'kod' => '24/53'],
        ['no' => 5, 'kod' => '37/53'],
    ];
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 4</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b4-card mb-4">
        <div class="b4-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Pertama</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 4 - Analisa Data-Data Penilaian Prestasi Petender</h3>
                <p class="text-white-50 mb-0 small">Semakan dan penilaian prestasi kerja semasa petender & peratus kemajuan dicapai.</p>
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

    {{-- Main Section Card: Vendor List Table --}}
    <div class="b4-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-speedometer2 text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Untuk Penilaian Prestasi Kerja</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar & Semak</strong> untuk menyemak dan menganalisis prestasi kerja semasa petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>5 Petender Berdaftar
            </span>
        </div>

        {{-- Table Vendor Participants --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">#</th>
                            <th><i class="bi bi-person-vcard text-danger me-1"></i> Kod Pembekal</th>
                            <th style="width: 200px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $v)
                            <tr>
                                <td class="text-center text-muted fw-bold small">{{ $v['no'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; margin-right:10px">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <span class="fw-bold font-monospace text-dark" style="font-size: 0.9rem;">{{ $v['kod'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-action-papar" onclick="openPrestasiModal('{{ $v['kod'] }}')">
                                        <i class="bi bi-eye me-1"></i>Papar & Semak
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center mb-3">
                <div class="col-12 col-md-7">
                    <div class="form-check p-3 bg-white rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="sahPenilaian" checked>
                        <label class="form-check-label fw-semibold text-dark small" for="sahPenilaian">
                            Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-5 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" onclick="showSuccessModal()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- =========================
    MODAL: PRESTASI PETENDER (modalprestasipetender)
========================== --}}
<div class="modal fade" id="modalprestasipetender" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 94%; width: 94%;">
        <div class="modal-content modal-card">

            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #dbeafe; color: #2563eb;">
                        <i class="bi bi-speedometer2 fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Prestasi Kerja Semasa Petender</h5>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold font-monospace" id="modalKodPembekalBadge">Kod Pembekal: 45/53</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">
                <!-- <div class="d-flex align-items-center justify-content-end gap-2 mb-3">
                    <button type="button" class="btn btn-action-papar" onclick="onTambahModal()" disabled>
                        <i class="bi bi-plus-circle"></i>Tambah Kerja
                    </button>
                </div> -->
                {{-- Detailed Prestasi Table --}}
                <div class="table-modern-wrapper mb-2">
                    <div class="table-responsive">
                        <table class="table-borang4-modern">
                            <thead>
                                <tr>
                                    <th class="subhead-main text-start ps-4" style="width: 280px;">Perkara</th>
                                    <th class="subhead-level2">Kerja 1</th>
                                    <th class="subhead-level2">Kerja 2</th>
                                    <th class="subhead-level2">Kerja 3</th>
                                    <th class="subhead-level2">Kerja 4</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Nama Ringkas Kerja Semasa</td>
                                    <td class="fw-medium text-dark">Kerja Penyelenggaraan Dan Lain-lain Kerja Berkaitan Di PGC, Pj, Putrajaya Untuk Tempoh 2 Tahun</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">No. Kontrak Kerja Semasa</td>
                                    <td class="font-monospace fw-semibold text-dark">PPJ/KJ/32(TB)/5/2023(KW)</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Harga Kontrak (RM)</td>
                                    <td class="font-monospace fw-bold text-dark">8,985,719.20</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tarikh Pemilikan Tapak</td>
                                    <td class="font-monospace">15 JUN 2023</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tempoh Kontrak (Hari) (P)</td>
                                    <td class="font-monospace">730</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tarikh Siap Kontrak (termasuk EOT diluluskan)</td>
                                    <td class="font-monospace">14 JUN 2025</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tarikh Penilaian Kemajuan</td>
                                    <td class="font-monospace">19 NOV 2024</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Lepas Tarikh Siap Kontrak (Hari) (D)</td>
                                    <td class="font-monospace">0</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Peratus Kemajuan Sebenar Dicapai (A) (%)</td>
                                    <td class="font-monospace fw-bold text-primary">48.15</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Peratus Kemajuan Mengikut Jadual (S) (%)</td>
                                    <td class="font-monospace fw-bold text-dark">48.15</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Prestasi Kerja Semasa (A-(S*P-D)/P)</td>
                                    <td class="font-monospace fw-bold">0</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Status Prestasi</td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-bold">MEMUASKAN</span>
                                    </td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Prestasi Kerja Semasa Terdahulu (%)</td>
                                    <td class="font-monospace">0.00</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Semakan Projek Sakit Oleh Pegawai Penilai</td>
                                    <td>
                                        <select class="form-select form-select-sm border-secondary-subtle rounded-3" style="max-width:140px;">
                                            <option selected>TIADA</option>
                                            <option>ADA</option>
                                        </select>
                                    </td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                    <td class="text-muted">-</td>
                                </tr>

                                <tr style="background-color: #f8fafc;">
                                    <td class="fw-bold text-dark ps-4">STATUS PRESTASI:</td>
                                    <td colspan="4">
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold fs-6">MEMUASKAN</span>
                                    </td>
                                </tr>

                                <tr style="background-color: #f8fafc;">
                                    <td class="fw-semibold text-muted small ps-4">Formula Pengiraan Nilai Baki Kerja Semasa Dalam Tangan:</td>
                                    <td colspan="4" class="font-monospace text-muted small">
                                        (100% - %Kerja Sebenar) x Harga Kontrak Kerja
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    <strong>Perhatian:</strong> Sila pastikan maklumat peratus kemajuan dan status projek disemak dengan teliti sebelum membuat pengesahan penilaian.
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-top px-4 py-3 justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Tutup
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-submit-danger px-4 rounded-3" onclick="savePrestasiModal()">
                        <i class="bi bi-floppy me-1"></i>Simpan
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- =========================
    MODAL: SIMPAN SUCCESS
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
            <p class="text-muted small mb-4">Maklumat analisa penilaian prestasi petender telah berjaya disimpan ke dalam sistem.</p>

            <button type="button" class="btn btn-submit-danger px-4 py-2 rounded-3 w-100" data-bs-dismiss="modal">
                Faham & Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openPrestasiModal(kodPembekal) {
        document.getElementById('modalKodPembekalBadge').textContent = 'Kod Pembekal: ' + (kodPembekal || '45/53');
        const modal = new bootstrap.Modal(document.getElementById('modalprestasipetender'));
        modal.show();
    }

    function savePrestasiModal() {
        const modalEl = document.getElementById('modalprestasipetender');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();
        showSuccessModal();
    }

    function showSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }

    function onTambahModal(){
        showSuccessModal();
    }
</script>
@endsection
