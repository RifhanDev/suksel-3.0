@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b9-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b9-header-banner {
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

    @keyframes subtle-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(29, 78, 216, 0.4);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 6px rgba(29, 78, 216, 0);
            transform: scale(1.03);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(29, 78, 216, 0);
            transform: scale(1);
        }
    }

    .pulse-badge {
        animation: subtle-pulse 2s infinite ease-in-out;
    }

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .table-modern-b9 th {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
        padding: 0.75rem 0.85rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .btn-action-papar {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.4rem 0.85rem;
        transition: all 0.2s ease-in-out;
    }

    .btn-action-papar:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .btn-action-dinilai {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.4rem 0.85rem;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action-dinilai:hover {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
    }

    .table-borang3-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang3-modern th {
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

    .table-borang3-modern th.compact-th {
        font-size: 0.68rem;
        padding: 0.5rem 0.2rem;
        line-height: 1.2;
        white-space: normal;
        word-break: break-word;
        background: #d7d7d9;
        color: #3f3f3f;
    }

    .table-borang3-modern th.subhead-main {
        background: #6d6d79ff;
        color: #ffffff;
        font-size: 0.75rem;
    }

    .table-borang3-modern td {
        padding: 0.75rem 0.6rem;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang3-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .formula-tag {
        display: inline-block;
        background: rgba(0, 0, 0, 0.08);
        color: #3f3f3f;
        font-size: 0.675rem;
        font-weight: 600;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        margin-top: 0.2rem;
        font-family: monospace;
    }

    .table-borang9-modal {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang9-modal th {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        vertical-align: middle;
        padding: 0.85rem 1.25rem;
        border: none;
    }

    .table-borang9-modal td {
        padding: 0.85rem 1.25rem;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-borang9-modal tr:last-child td {
        border-bottom: none;
    }

    .subhead-bg {
        background: #f8fafc;
        font-weight: 700;
        color: #334155;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .total-row {
        background: #f1f5f9 !important;
        font-weight: 800;
    }

    .total-row td {
        border-top: 2px solid #cbd5e1 !important;
        border-bottom: 2px solid #cbd5e1 !important;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .confirmation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
    }

    .btn-submit-danger {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        font-weight: 700;
        transition: all 0.2s ease-in-out;
    }

    .btn-submit-danger:hover {
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
        transform: translateY(-1px);
    }

    .nav-tabs-modern {
        border-bottom: 2px solid #e2e8f0;
    }

    .nav-tabs-modern .nav-link {
        color: #64748b;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.65rem 1.25rem;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
        margin-bottom: -2px;
    }

    .nav-tabs-modern .nav-link:hover {
        color: #dc2626;
        border-color: transparent;
    }

    .nav-tabs-modern .nav-link.active {
        color: #dc2626;
        background: transparent;
        border-bottom: 2px solid #dc2626;
    }
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no') ?: ($tender_no ?? '');
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $tenderParam) : $tenderParam;
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', ['tender_no' => $tenderParam, 'tab' => 'p2']) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $vendorsData = $b9VendorSummary ?? $b8VendorSummary ?? [];
    $hargaIndikatif = (float) ($tender->harga_indikatif ?? 0);
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 9</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b9-card mb-4">
        <div class="b9-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Kedua</span>
                    @if($readOnly ?? false)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 9 - Analisa Data-Data Penilaian Keupayaan Teknikal Petender</h3>
                <p class="text-white-50 mb-0 small">Analisa pengalaman kerja lima (5) tahun lepas, bahagian kerja semasa yang siap, dan pelarasan nilai kerja petender.</p>
            </div>
        </div>
    </div>

    {{-- Top Info Grid Card --}}
    <div class="info-top-card p-3.5 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-archive fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">No. Sebut Harga / Tender</div>
                        <div class="info-item-value text-danger font-monospace">{{ $no_tender_display ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-building fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">PTJ Perolehan</div>
                        <div class="info-item-value text-dark">{{ $ptj_display ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fffbeb; color: #d97706;">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">Status Proses</div>
                        <div class="mt-1">
                            <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                {{ $status_label ?? 'Menunggu Penilaian' }}
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
                        <div class="info-item-label">Sah Laku Tamat</div>
                        <div class="info-item-value text-dark font-monospace">{{ $sah_laku_tamat ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section Card: Vendor Participant List Table --}}
    <div class="b9-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-bar-chart-steps text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Bagi Analisa Keupayaan Teknikal</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar &amp; Semak</strong> untuk menyemak jadual perincian analisa keupayaan teknikal petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($vendorsData) }} Petender Berdaftar
            </span>
        </div>

        {{-- Table Vendor Participants --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b9 align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">BIL</th>
                            <th><i class="bi bi-person-vcard text-danger me-1"></i> KOD PEMBEKAL</th>
                            <th class="text-end"><i class="bi bi-cash-stack text-danger me-1"></i> JUMLAH KESELURUHAN KERJA (RM)</th>
                            <th style="width: 180px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendorsData as $vId => $v)
                            <tr>
                                <td class="text-center text-muted font-monospace fw-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold font-monospace text-dark d-block" style="font-size: 0.9rem;">{{ $v['kod_pembekal'] }}</span>
                                            @if(!empty($v['vendor_name']))
                                                <span class="small text-muted">{{ $v['vendor_name'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end font-monospace text-dark fw-semibold" id="b9VendorTotalCell_{{ $vId }}">
                                    {{ $v['jumlah_kerja_disp'] ?? '0.00' }}
                                </td>
                                <td class="text-center">
                                    @if(!empty($v['is_evaluated']))
                                        <button type="button" class="btn-action-dinilai" id="btnActionB9_{{ $vId }}" onclick="openB9DetailModal('{{ $vId }}')">
                                            <i class="bi bi-check-circle me-1"></i>Telah Dinilai
                                        </button>
                                    @else
                                        <button type="button" class="btn-action-papar" id="btnActionB9_{{ $vId }}" onclick="openB9DetailModal('{{ $vId }}')">
                                            <i class="bi bi-eye me-1"></i>Papar &amp; Semak
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-warning display-6"></i>
                                        <span class="fw-semibold">Tiada petender berdaftar bagi analisa Borang 9.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border mb-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSahB9" onchange="checkB9CompletionState()" {{ ($readOnly ?? false) ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSahB9">
                            Saya mengesahkan petender di atas telah dinilai keupayaan teknikal secara teliti.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamadB9" onclick="simpanB9Main()" disabled>
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- POPUP DETAIL MODAL FOR BORANG 9 --}}
<div class="modal fade" id="b9DetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 90%; width: 90%;">
        <div class="modal-content modal-card">
            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-danger flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2;">
                        <i class="bi bi-tools fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">BORANG 9 - ANALISA DATA-DATA PENILAIAN KEUPAYAAN TEKNIKAL PETENDER</h5>
                        <p class="text-secondary small mb-0">Analisa pengalaman kerja lima (5) tahun lepas, bahagian kerja semasa yang siap, dan pelarasan nilai kerja petender.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1.5 rounded-pill fw-bold font-monospace" id="b9ModalRefBadge">
                        NO. RUJUKAN PETENDER : -
                    </span>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">
                {{-- Navigation Tabs --}}
                <ul class="nav nav-tabs nav-tabs-modern mb-3" id="b9ModalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="pengalaman-tab" data-bs-toggle="tab" data-bs-target="#pengalaman-tab-pane" type="button" role="tab" aria-controls="pengalaman-tab-pane" aria-selected="true">
                            <i class="bi bi-journal-text me-1.5"></i>Pengalaman Kerja
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="borang9-tab" data-bs-toggle="tab" data-bs-target="#borang9-tab-pane" type="button" role="tab" aria-controls="borang9-tab-pane" aria-selected="false">
                            <i class="bi bi-file-earmark-spreadsheet me-1.5"></i>Borang 9
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="b9ModalTabContent">
                    {{-- Tab 1: Pengalaman Kerja (Borang 9b Kerja Sebanding) --}}
                    <div class="tab-pane fade show active" id="pengalaman-tab-pane" role="tabpanel" aria-labelledby="pengalaman-tab" tabindex="0">
                        <div class="py-2">
                            {{-- Pengalaman Kerja --}}
                            <div class="col-12">
                                <div class="p-4 rounded-3 border bg-white shadow-sm">
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="fw-bold mb-0 text-dark">PENILAIAN PENGALAMAN KERJA DALAM LIMA (5) TAHUN LEPAS</h6>
                                        </div>
                                    </div>

                                    <div class="table-modern-wrapper">
                                        <div class="table-responsive" style="overflow-x: auto;">
                                            <table class="table-borang3-modern" style="table-layout: fixed; width: 100%; min-width: 100%;" id="tblB9b">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 45px;" class="compact-th text-center">BIL</th>
                                                        <th class="compact-th text-start ps-3">SENARAI KERJA YANG DISIAPKAN</th>
                                                        <th style="width: 150px;" class="compact-th text-start ps-3">NAMA PIC</th>
                                                        <th style="width: 140px;" class="compact-th text-center">NO. TELEFON PIC</th>
                                                        <th style="width: 160px;" class="compact-th text-end pe-3">NILAI KERJA<br><span class="formula-tag">(RM)</span></th>
                                                        <th style="width: 180px;" class="compact-th text-end pe-3">PELARASAN NILAI KERJA<br><span class="formula-tag">(RM)</span></th>
                                                        <th style="width: 140px;" class="compact-th text-center">JENIS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center font-monospace fw-bold">1</td>
                                                        <td contenteditable="true" class="bg-light bg-opacity-50 ps-3"></td>
                                                        <td contenteditable="true" class="bg-light bg-opacity-50 ps-3"></td>
                                                        <td contenteditable="true" class="bg-light bg-opacity-50 text-center font-monospace"></td>
                                                        <td contenteditable="true" class="text-end font-monospace bg-light bg-opacity-50 pe-3" oninput="calcRowB9b(this)">0.00</td>
                                                        <td class="text-end font-monospace text-muted fw-semibold b9b-adj-val pe-3">0.00</td>
                                                        <td class="text-center">
                                                            <select class="form-select form-select-sm b9-jenis-select" onchange="calcRowB9b(this)">
                                                                <option value="0" selected>-- Sila Pilih --</option>
                                                                <option value="1">Serupa</option>
                                                                <option value="2">Sebanding</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="total-row" id="rowJumlahB9b" style="background: #f8fafc; border-top: 2px solid #cbd5e1;">
                                                        <td colspan="4" class="text-end fw-bold text-dark pe-3">JUMLAH</td>
                                                        <td class="text-end font-monospace fw-bold text-dark pe-3" id="b9bTotalRawVal">0.00</td>
                                                        <td class="text-end font-monospace fw-bold text-dark pe-3" id="b9bTotalAdjVal">0.00</td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                     </div>

                                     {{-- Nota Section --}}
                                     <div class="mt-3 p-3 rounded-3 bg-light bg-opacity-50 border">
                                         <div class="d-flex align-items-center gap-2 fw-bold text-dark mb-2" style="font-size:0.825rem;">
                                             <i class="bi bi-info-circle-fill text-danger me-1"></i>
                                             <span>Nota Pelarasan Nilai Kerja:</span>
                                         </div>
                                         <ul class="mb-0 ps-3 text-secondary" style="font-size: 0.825rem; line-height: 1.6;">
                                             <li><strong>(i) Kerja Serupa</strong> — tidak perlu pelarasan (faktor 1.0).</li>
                                             <li><strong>(ii) Kerja Sebanding</strong> — Nilai Kerja sebanding didarab dengan 0.5.</li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>

                    {{-- Tab 2: Borang 9 --}}
                    <div class="tab-pane fade" id="borang9-tab-pane" role="tabpanel" aria-labelledby="borang9-tab" tabindex="0">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 p-3 bg-light rounded-3 border">
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: #fef2f2; color: #dc2626;">
                                    <i class="bi bi-tools fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">B. KEUPAYAAN TEKNIKAL</h6>
                                    <p class="text-secondary extra-small mb-0">B1. PENGALAMAN KERJA DALAM LIMA (5) TAHUN LEPAS (termasuk kerja semasa yang telah siap)</p>
                                </div>
                            </div>
                            <div class="section-badge-pill-primary pulse-badge align-self-start align-self-md-center">
                                <i class="bi bi-cash-stack me-1"></i>Anggaran Jabatan (AJ): <span class="fw-bold font-monospace ms-1" id="b9ModalAjVal">RM {{ number_format($hargaIndikatif, 2) }}</span>
                            </div>
                        </div>

                        {{-- Table Details Container --}}
                        <div class="table-modern-wrapper mb-4">
                            <table class="table table-borang9-modal align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="subhead-main text-start ps-3 py-2.5 bg-secondary text-white fw-bold" style="background:#6d6d79ff!important; font-size:0.75rem;">
                                            <i class="bi bi-person-badge me-1"></i> <span id="b9ModalRefHeaderTitle">NO. RUJUKAN PETENDER : -</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 65%;">SENARAI PENGALAMAN KERJA</th>
                                        <th style="width: 35%;" class="text-end">PELARASAN NILAI KERJA (RM)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2.5 fw-semibold text-dark" style="font-size: 0.875rem;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-1" style="width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;">1</span>
                                                <span>Kerja-kerja serupa yang disiapkan</span>
                                            </div>
                                        </td>
                                        <td class="text-end font-monospace text-dark fw-semibold" id="b9ModalItem1Val">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 fw-semibold text-dark" style="font-size: 0.875rem;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-1" style="width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;">2</span>
                                                <span>Kerja-kerja sebanding yang disiapkan</span>
                                            </div>
                                        </td>
                                        <td class="text-end font-monospace text-dark fw-semibold" id="b9ModalItem2Val">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 fw-semibold text-dark" style="font-size: 0.875rem;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-1" style="width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;">3</span>
                                                <span>Bahagian kerja semasa (serupa) yang telah siap (Borang 7)</span>
                                            </div>
                                        </td>
                                        <td class="text-end font-monospace text-dark fw-semibold" id="b9ModalItem3Val">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 fw-semibold text-dark" style="font-size: 0.875rem;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-1" style="width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;">4</span>
                                                <span>Bahagian kerja semasa (sebanding) yang telah siap (Borang 7)</span>
                                            </div>
                                        </td>
                                        <td class="text-end font-monospace text-dark fw-semibold" id="b9ModalItem4Val">0.00</td>
                                    </tr>

                                    <tr class="total-row">
                                        <td class="fw-bold text-dark"><i class="bi bi-calculator me-2 text-danger"></i>Jumlah Keseluruhan Kerja</td>
                                        <td class="text-end font-monospace text-dark fw-bold" id="b9ModalJumlahKerjaVal">0.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" class="p-4 bg-light bg-opacity-50">
                                            <div class="row g-4">
                                                {{-- B1.1 Keseluruhan Kerja --}}
                                                <div class="col-12 col-md-6">
                                                    <div class="p-3 rounded-3 border bg-white h-100 shadow-sm">
                                                        <div class="fw-bold text-dark mb-2.5 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size:0.875rem;">
                                                            <span class="badge bg-danger text-white rounded-2 px-2 py-0.5" style="font-size:0.75rem;">B1.1</span>
                                                            <span>Keseluruhan Kerja</span>
                                                        </div>
                                                        <div class="d-flex flex-column gap-2 font-monospace small">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-muted">(i) Jumlah Keseluruhan Kerja =</span>
                                                                <span class="fw-bold text-dark" id="b9ModalB11JumlahVal">RM 0.00</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-muted">(ii) % berbanding dengan AJ =</span>
                                                                <span class="fw-bold text-dark" id="b9ModalB11PctVal">0.00 %</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- B1.2 Kerja Terbesar --}}
                                                <div class="col-12 col-md-6">
                                                    <div class="p-3 rounded-3 border bg-white h-100 shadow-sm">
                                                        <div class="fw-bold text-dark mb-2.5 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size:0.875rem;">
                                                            <span class="badge bg-danger text-white rounded-2 px-2 py-0.5" style="font-size:0.75rem;">B1.2</span>
                                                            <span>Kerja Terbesar <span class="text-danger">*</span></span>
                                                        </div>
                                                        <div class="d-flex flex-column gap-2 font-monospace small">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-muted">(i) Nilai Kerja Terbesar =</span>
                                                                <span class="fw-bold text-dark" id="b9ModalB12TerbesarVal">RM 0.00</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-muted">(ii) % berbanding dengan AJ =</span>
                                                                <span class="fw-bold text-dark" id="b9ModalB12PctVal">0.00 %</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Nota Section --}}
                                            <div class="mt-4 p-4 rounded-3 bg-white border shadow-sm">
                                                <div class="d-flex align-items-center gap-2 fw-bold text-dark mb-3 pb-2 border-bottom" style="font-size:0.85rem;">
                                                    <i class="bi bi-info-circle-fill text-danger fs-5 me-1"></i>
                                                    <span>Nota Penting Pelarasan Nilai Kerja</span>
                                                </div>
                                                <div class="text-secondary extra-small ps-1" style="font-size:0.8rem; line-height:1.75;">
                                                    <div class="mb-2.5">
                                                        <strong class="text-dark">1. Pelarasan Nilai Kerja:</strong>
                                                        <ul class="mb-0 mt-1 ps-4 text-muted">
                                                            <li>(i) <strong>Kerja Serupa</strong> &mdash; tidak perlu pelarasan (faktor 1.0).</li>
                                                            <li>(ii) <strong>Kerja Sebanding</strong> &mdash; Nilai Kerja sebanding didarab dengan <strong>0.5</strong>.</li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">2. <span class="text-danger">*</span> Kerja Terbesar:</strong>
                                                        <span class="text-muted ms-1">Nilai kerja terbesar selepas mengambil kira pelarasan nilai kerja di atas.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success px-4 rounded-3 fw-semibold" onclick="simpanPenilaianModalB9()">
                    <i class="bi bi-check-circle me-1.5"></i>Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const b9VendorDataMap = @json($b9VendorSummary ?? []);
    let currentB9VendorId = null;

    function openB9DetailModal(vId) {
        currentB9VendorId = vId;
        const vData = b9VendorDataMap[vId];
        const kodPembekal = vData ? vData.kod_pembekal : vId;

        document.getElementById('b9ModalRefBadge').innerText = 'NO. RUJUKAN PETENDER : ' + kodPembekal;
        const refTitleEl = document.getElementById('b9ModalRefHeaderTitle');
        if (refTitleEl) {
            refTitleEl.innerText = 'NO. RUJUKAN PETENDER : ' + kodPembekal;
        }

        if (vData) {
            document.getElementById('b9ModalItem1Val').innerText = vData.kerja_serupa_disp || '0.00';
            document.getElementById('b9ModalItem2Val').innerText = vData.kerja_sebanding_disp || '0.00';
            document.getElementById('b9ModalItem3Val').innerText = vData.b7a_serupa_disp || '0.00';
            document.getElementById('b9ModalItem4Val').innerText = vData.b7b_sebanding_disp || '0.00';

            document.getElementById('b9ModalJumlahKerjaVal').innerText = vData.jumlah_kerja_disp || '0.00';
            document.getElementById('b9ModalB11JumlahVal').innerText = 'RM ' + (vData.jumlah_kerja_disp || '0.00');
            document.getElementById('b9ModalB11PctVal').innerText = (vData.keseluruhan_pct || '0.00') + ' %';
            document.getElementById('b9ModalB12TerbesarVal').innerText = 'RM ' + (vData.kerja_terbesar_disp || '0.00');
            document.getElementById('b9ModalB12PctVal').innerText = (vData.terbesar_pct || '0.00') + ' %';
        }

        const tbodyB9b = document.getElementById('tblB9b') ? document.getElementById('tblB9b').querySelector('tbody') : null;
        if (tbodyB9b) {
            tbodyB9b.innerHTML = '';
            const items = (vData && vData.pengalaman_items && vData.pengalaman_items.length > 0)
                ? vData.pengalaman_items
                : [];

            let sumRaw = 0;
            let sumAdj = 0;

            if (items.length > 0) {
                items.forEach((item, idx) => {
                    sumRaw += item.nilai_kerja;
                    sumAdj += item.pelarasan;
                    const jenisVal = item.jenis !== undefined ? item.jenis : 0;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center font-monospace fw-bold">${idx + 1}</td>
                        <td contenteditable="true" class="bg-light bg-opacity-50 ps-3">${escapeHtml(item.tajuk)}</td>
                        <td contenteditable="true" class="bg-light bg-opacity-50 ps-3">${escapeHtml(item.pic || '')}</td>
                        <td contenteditable="true" class="bg-light bg-opacity-50 text-center font-monospace">${escapeHtml(item.telefon_pic || '')}</td>
                        <td contenteditable="true" class="text-end font-monospace bg-light bg-opacity-50 pe-3" oninput="calcRowB9b(this)">${item.nilai_disp}</td>
                        <td class="text-end font-monospace text-muted fw-semibold b9b-adj-val pe-3">${item.pelarasan_disp}</td>
                        <td class="text-center">
                            <select class="form-select form-select-sm b9-jenis-select" onchange="calcRowB9b(this)">
                                <option value="0" ${jenisVal === 0 ? 'selected' : ''}>-- Sila Pilih --</option>
                                <option value="1" ${jenisVal === 1 ? 'selected' : ''}>Serupa</option>
                                <option value="2" ${jenisVal === 2 ? 'selected' : ''}>Sebanding</option>
                            </select>
                        </td>
                    `;
                    tbodyB9b.appendChild(tr);
                });
            } else {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-center font-monospace fw-bold">1</td>
                    <td contenteditable="true" class="bg-light bg-opacity-50 ps-3"></td>
                    <td contenteditable="true" class="bg-light bg-opacity-50 ps-3"></td>
                    <td contenteditable="true" class="bg-light bg-opacity-50 text-center font-monospace"></td>
                    <td contenteditable="true" class="text-end font-monospace bg-light bg-opacity-50 pe-3" oninput="calcRowB9b(this)">0.00</td>
                    <td class="text-end font-monospace text-muted fw-semibold b9b-adj-val pe-3">0.00</td>
                    <td class="text-center">
                        <select class="form-select form-select-sm b9-jenis-select" onchange="calcRowB9b(this)">
                            <option value="0" selected>-- Sila Pilih --</option>
                            <option value="1">Serupa</option>
                            <option value="2">Sebanding</option>
                        </select>
                    </td>
                `;
                tbodyB9b.appendChild(tr);
            }

            const totalTr = document.createElement('tr');
            totalTr.className = 'total-row';
            totalTr.id = 'rowJumlahB9b';
            totalTr.style.cssText = 'background: #f8fafc; border-top: 2px solid #cbd5e1;';
            totalTr.innerHTML = `
                <td colspan="4" class="text-end fw-bold text-dark pe-3">JUMLAH</td>
                <td class="text-end font-monospace fw-bold text-dark pe-3" id="b9bTotalRawVal">${sumRaw.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                <td class="text-end font-monospace fw-bold text-dark pe-3" id="b9bTotalAdjVal">${sumAdj.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                <td></td>
            `;
            tbodyB9b.appendChild(totalTr);
        }

        const firstTab = document.getElementById('pengalaman-tab');
        if (firstTab) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(firstTab);
            tabInstance.show();
        }

        const modal = new bootstrap.Modal(document.getElementById('b9DetailModal'));
        modal.show();
    }

    function checkB9CompletionState() {
        const chk = document.getElementById('chkSahB9');
        const btn = document.getElementById('btnSimpanMuktamadB9');
        if (!btn) return;

        const isReadOnly = {{ json_encode($readOnly ?? false) }};
        if (isReadOnly) {
            btn.disabled = true;
            return;
        }

        const vendorList = Object.values(b9VendorDataMap);
        const allEvaluated = vendorList.length > 0 && vendorList.every(v => v.is_evaluated === true);

        if (allEvaluated && chk && chk.checked) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        checkB9CompletionState();
    });

    function simpanB9Main() {
        const chk = document.getElementById('chkSahB9');
        if (chk && !chk.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Pengesahan Diperlukan',
                text: 'Sila tandakan pengesahan terlebih dahulu sebelum menyimpan keputusan Borang 9.',
                confirmButtonText: 'Faham',
                confirmButtonColor: '#dc2626',
            });
            return;
        }

        const vendorList = Object.values(b9VendorDataMap);
        const unEvaluated = vendorList.filter(v => v.is_evaluated !== true);
        if (unEvaluated.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Penilaian Belum Lengkap',
                text: 'Terdapat ' + unEvaluated.length + ' petender yang belum dinilai. Sila lengkapkan penilaian semua petender terlebih dahulu.',
                confirmButtonText: 'Faham',
                confirmButtonColor: '#dc2626',
            });
            return;
        }

        const saveUrl = "{{ route('penilaianKewanganKerja.borang9.simpanMuktamad', $tender_no) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

        Swal.fire({
            title: 'Menyimpan Keputusan...',
            text: 'Sila tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                chk_sah: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya Disimpan!',
                    text: data.message || 'Maklumat penilaian Keupayaan Teknikal (Borang 9) telah berjaya disahkan dan disimpan.',
                    confirmButtonText: 'Maju ke Borang Seterusnya',
                    confirmButtonColor: '#16a34a',
                    customClass: {
                        popup: 'rounded-4 p-4',
                        confirmButton: 'px-4 py-2 rounded-3'
                    }
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = "{{ route('penilaianKewanganKerja.show', $tender_no) }}";
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat',
                    text: data.message || 'Gagal menyimpan keputusan Borang 9.',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc2626',
                });
            }
        })
        .catch(err => {
            console.error('Finalize error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Ralat Sistem',
                text: 'Gagal menghubungi pelayan. Sila cuba lagi.',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc2626',
            });
        });
    }

    function simpanPenilaianModalB9() {
        if (!currentB9VendorId) {
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Petender tidak dikenal pasti.',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc2626',
            });
            return;
        }

        const selects = document.querySelectorAll('#tblB9b .b9-jenis-select');
        let unselectedCount = 0;
        selects.forEach(s => {
            if (s.value === "0") unselectedCount++;
        });

        if (unselectedCount > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Pemilihan Jenis Diperlukan',
                text: 'Terdapat ' + unselectedCount + ' item pengalaman kerja yang belum dipilih Jenis (Serupa / Sebanding). Sila pilih jenis terlebih dahulu.',
                confirmButtonText: 'Faham',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-4 p-4',
                    confirmButton: 'px-4 py-2 rounded-3'
                }
            });
            return;
        }

        const rows = document.querySelectorAll('#tblB9b tbody tr:not(.total-row)');
        const items = [];
        rows.forEach((r, idx) => {
            const tajuk = r.children[1] ? (r.children[1].innerText || '').trim() : '';
            const pic = r.children[2] ? (r.children[2].innerText || '').trim() : '';
            const telefonPic = r.children[3] ? (r.children[3].innerText || '').trim() : '';
            const nilaiKerja = parseFloat((r.children[4] ? r.children[4].innerText : '').replace(/,/g, '').trim()) || 0;
            const select = r.querySelector('.b9-jenis-select');
            const jenis = select ? parseInt(select.value) : 0;

            if (tajuk.length > 0 || nilaiKerja > 0) {
                items.push({
                    bil: idx + 1,
                    tajuk: tajuk,
                    pic: pic,
                    telefon_pic: telefonPic,
                    nilai_kerja: nilaiKerja,
                    jenis: jenis
                });
            }
        });

        const saveUrl = "{{ route('penilaianKewanganKerja.borang9.simpanPenilaian', $tender_no) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

        Swal.fire({
            title: 'Menyimpan...',
            text: 'Sila tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                vendor_id: currentB9VendorId,
                items: items
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.updated_data) {
                    b9VendorDataMap[currentB9VendorId] = data.updated_data;

                    const mainTotalEl = document.getElementById('b9VendorTotalCell_' + currentB9VendorId);
                    if (mainTotalEl) {
                        mainTotalEl.innerText = data.updated_data.jumlah_kerja_disp;
                    }

                    const actionBtn = document.getElementById('btnActionB9_' + currentB9VendorId);
                    if (actionBtn) {
                        actionBtn.className = 'btn-action-dinilai';
                        actionBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Telah Dinilai';
                    }

                    checkB9CompletionState();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Penilaian Berjaya Disimpan!',
                    text: 'Maklumat penilaian Pengalaman Kerja bagi petender ini telah berjaya disimpan.',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#16a34a',
                    customClass: {
                        popup: 'rounded-4 p-4',
                        confirmButton: 'px-4 py-2 rounded-3'
                    }
                }).then(() => {
                    const modalEl = document.getElementById('b9DetailModal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat',
                    text: data.message || 'Gagal menyimpan penilaian.',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc2626',
                });
            }
        })
        .catch(err => {
            console.error('Save error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Ralat Sistem',
                text: 'Gagal menghubungi pelayan. Sila cuba lagi.',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc2626',
            });
        });
    }

    function addRowB9a() {
        const tbody = document.getElementById('tblB9a').querySelector('tbody');
        const totalRow = document.getElementById('rowJumlahB9a');
        const rowCount = tbody.querySelectorAll('tr:not(.total-row)').length + 1;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center font-monospace fw-bold">${rowCount}</td>
            <td contenteditable="true" class="bg-light bg-opacity-50"></td>
            <td contenteditable="true" class="text-end font-monospace bg-light bg-opacity-50" oninput="calcTotalB9a()">0.00</td>
        `;
        tbody.insertBefore(tr, totalRow);
    }

    function calcTotalB9a() {
        const tbody = document.getElementById('tblB9a').querySelector('tbody');
        const rows = tbody.querySelectorAll('tr:not(.total-row)');
        let sum = 0;
        rows.forEach(r => {
            const cell = r.children[2];
            if (cell) {
                const val = parseFloat((cell.innerText || '').replace(/,/g, '').trim()) || 0;
                sum += val;
            }
        });
        document.getElementById('b9aTotalVal').innerText = sum.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    }

    function addRowB9b() {
        const tbody = document.getElementById('tblB9b').querySelector('tbody');
        const totalRow = document.getElementById('rowJumlahB9b');
        const rowCount = tbody.querySelectorAll('tr:not(.total-row)').length + 1;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center font-monospace fw-bold">${rowCount}</td>
            <td contenteditable="true" class="bg-light bg-opacity-50 ps-3"></td>
            <td contenteditable="true" class="bg-light bg-opacity-50 ps-3"></td>
            <td contenteditable="true" class="bg-light bg-opacity-50 text-center font-monospace"></td>
            <td contenteditable="true" class="text-end font-monospace bg-light bg-opacity-50 pe-3" oninput="calcRowB9b(this)">0.00</td>
            <td class="text-end font-monospace text-muted fw-semibold b9b-adj-val pe-3">0.00</td>
            <td class="text-center">
                <select class="form-select form-select-sm b9-jenis-select" onchange="calcRowB9b(this)">
                    <option value="0" selected>-- Sila Pilih --</option>
                    <option value="1">Serupa</option>
                    <option value="2">Sebanding</option>
                </select>
            </td>
        `;
        tbody.insertBefore(tr, totalRow);
    }

    function calcRowB9b(element) {
        const tr = element.closest('tr');
        const rawCell = tr.children[4];
        const bCell   = tr.children[5];
        const select  = tr.querySelector('.b9-jenis-select');

        const rawVal = parseFloat((rawCell ? rawCell.innerText : '').replace(/,/g, '').trim()) || 0;
        const jenisVal = select ? parseInt(select.value) : 0;

        let multiplier = 0.0;
        if (jenisVal === 1) {
            multiplier = 1.0;
        } else if (jenisVal === 2) {
            multiplier = 0.5;
        }

        const adjVal = rawVal * multiplier;
        if (bCell) {
            bCell.innerText = adjVal.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        }

        calcTotalB9b();
    }

    function calcTotalB9b() {
        const tbody = document.getElementById('tblB9b').querySelector('tbody');
        const rows = tbody.querySelectorAll('tr:not(.total-row)');
        let sumRaw = 0;
        let sumAdj = 0;
        let sumSerupa = 0;
        let sumSebanding = 0;

        rows.forEach(r => {
            const rawCell = r.children[4];
            const adjCell = r.children[5];
            const select  = r.querySelector('.b9-jenis-select');
            const jenisVal = select ? parseInt(select.value) : 0;

            const rawVal = parseFloat((rawCell ? rawCell.innerText : '').replace(/,/g, '').trim()) || 0;
            const adjVal = parseFloat((adjCell ? adjCell.innerText : '').replace(/,/g, '').trim()) || 0;

            sumRaw += rawVal;
            sumAdj += adjVal;

            if (jenisVal === 1) {
                sumSerupa += adjVal;
            } else if (jenisVal === 2) {
                sumSebanding += adjVal;
            }
        });

        document.getElementById('b9bTotalRawVal').innerText = sumRaw.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById('b9bTotalAdjVal').innerText = sumAdj.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});

        const item1El = document.getElementById('b9ModalItem1Val');
        const item2El = document.getElementById('b9ModalItem2Val');
        if (item1El) item1El.innerText = sumSerupa.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        if (item2El) item2El.innerText = sumSebanding.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});

        recalculateB9SummaryTotals();
    }

    function recalculateB9SummaryTotals() {
        const item1 = parseFloat((document.getElementById('b9ModalItem1Val') ? document.getElementById('b9ModalItem1Val').innerText : '').replace(/,/g, '').trim()) || 0;
        const item2 = parseFloat((document.getElementById('b9ModalItem2Val') ? document.getElementById('b9ModalItem2Val').innerText : '').replace(/,/g, '').trim()) || 0;
        const item3 = parseFloat((document.getElementById('b9ModalItem3Val') ? document.getElementById('b9ModalItem3Val').innerText : '').replace(/,/g, '').trim()) || 0;
        const item4 = parseFloat((document.getElementById('b9ModalItem4Val') ? document.getElementById('b9ModalItem4Val').innerText : '').replace(/,/g, '').trim()) || 0;

        const totalKerja = item1 + item2 + item3 + item4;
        const ajValStr = (document.getElementById('b9ModalAjVal') ? document.getElementById('b9ModalAjVal').innerText : '').replace(/[^\d.]/g, '');
        const ajVal = parseFloat(ajValStr) || 0;

        const totalKerjaDisp = totalKerja.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        if (document.getElementById('b9ModalJumlahKerjaVal')) document.getElementById('b9ModalJumlahKerjaVal').innerText = totalKerjaDisp;
        if (document.getElementById('b9ModalB11JumlahVal')) document.getElementById('b9ModalB11JumlahVal').innerText = 'RM ' + totalKerjaDisp;

        const overallPct = ajVal > 0 ? ((totalKerja / ajVal) * 100).toFixed(2) : '0.00';
        if (document.getElementById('b9ModalB11PctVal')) document.getElementById('b9ModalB11PctVal').innerText = overallPct + ' %';

        const maxVal = Math.max(item1, item2, item3, item4);
        const maxValDisp = maxVal.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        if (document.getElementById('b9ModalB12TerbesarVal')) document.getElementById('b9ModalB12TerbesarVal').innerText = 'RM ' + maxValDisp;

        const maxPct = ajVal > 0 ? ((maxVal / ajVal) * 100).toFixed(2) : '0.00';
        if (document.getElementById('b9ModalB12PctVal')) document.getElementById('b9ModalB12PctVal').innerText = maxPct + ' %';
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endsection
