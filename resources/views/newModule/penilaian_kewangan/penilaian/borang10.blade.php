@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
        --sg-teal: #19c1a7;
        --sg-teal-dark: #0d9488;
    }

    .b10-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b10-header-banner {
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

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
    }

    .table-modern-b10 th {
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

    .table-modern-b10 td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern-b10 tbody tr:hover {
        background-color: #f8fafc;
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
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
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
        gap: 0.25rem;
    }

    .btn-action-dinilai:hover {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
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

    /* Modal Tabs Styling */
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

    /* Modal Table Form Styling */
    .table-borang10-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang10-modern th {
        background: #475569;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        vertical-align: middle;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 0.85rem 0.75rem;
    }

    .table-borang10-modern th.subhead-main {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.825rem;
        text-transform: uppercase;
        padding: 0.9rem 1.25rem;
        letter-spacing: 0.5px;
    }

    .table-borang10-modern td {
        padding: 0.85rem 1rem;
        vertical-align: top;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang10-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .section-title {
        font-weight: 800;
        color: #1e293b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .item-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .item-list li {
        padding: 0.3rem 0;
        color: #334155;
        font-weight: 500;
        line-height: 1.5;
    }

    .formula-tag {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.725rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        margin-top: 0.35rem;
        font-family: var(--bs-font-monospace, monospace);
        border: 1px solid #e2e8f0;
    }

    .symbol-badge-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        height: 100%;
        gap: 0.4rem;
    }

    .symbol-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #cbd5e1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .btn-simpan {
        background: linear-gradient(135deg, var(--sg-teal) 0%, var(--sg-teal-dark) 100%);
        color: #ffffff;
        border: 0;
        padding: 0.65rem 1.75rem;
        border-radius: 10px;
        font-weight: 700;
        min-width: 130px;
        box-shadow: 0 4px 12px rgba(25, 193, 167, 0.25);
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .btn-simpan:hover {
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(25, 193, 167, 0.35);
        transform: translateY(-1px);
    }

    /* Modal Styling */
    .modal-card {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .confetti-icon-wrapper {
        width: 64px;
        height: 64px;
        background: #ccfbf1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .confetti {
        width: 36px;
        height: 36px;
    }

    .btn-modal {
        background: #1e293b;
        color: #ffffff;
        border: 0;
        padding: 0.6rem 1.75rem;
        border-radius: 8px;
        font-weight: 700;
        min-width: 120px;
        transition: all 0.2s ease-in-out;
    }

    .btn-modal:hover {
        background: #0f172a;
        color: #ffffff;
    }
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no') ?: ($tender_no ?? '');
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $tenderParam) : $tenderParam;
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', $tenderParam) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $vendorsData = $b10VendorSummary ?? $b9VendorSummary ?? $b8VendorSummary ?? [];
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 10</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b10-card mb-4">
        <div class="b10-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Kedua</span>
                    @if($readOnly ?? false)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 10 - Analisa Data-Data Penilaian Keupayaan Teknikal Petender</h3>
                <p class="text-white-50 mb-0 small">Analisa kakitangan teknikal, faktor penyama, AKM, peratusan penggajian petender dan pengalaman.</p>
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
    <div class="b10-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-people-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Bagi Analisa Keupayaan Teknikal (Kakitangan Teknikal)</h5>
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
                <table class="table table-modern-b10 align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">BIL</th>
                            <th><i class="bi bi-person-vcard text-danger me-1"></i> KOD PEMBEKAL</th>
                            <th class="text-center"><i class="bi bi-person-badge text-danger me-1"></i> KAKITANGAN TEKNIKAL</th>
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
                                            <span class="fw-bold font-monospace text-dark d-block" style="font-size: 0.9rem;">{{ $v['kod_pembekal'] ?? (is_array($v) ? ($v['kod'] ?? $vId) : $vId) }}</span>
                                            @if(!empty($v['vendor_name']))
                                                <span class="small text-muted">{{ $v['vendor_name'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center font-monospace text-dark fw-semibold">
                                    {{ $v['kakitangan_disp'] ?? $v['bilangan_kakitangan'] ?? '-' }}
                                </td>
                                <td class="text-center">
                                    @if(!empty($v['is_evaluated']))
                                        <button type="button" class="btn-action-dinilai" id="btnActionB10_{{ $vId }}" onclick="openB10DetailModal('{{ $vId }}')">
                                            <i class="bi bi-check-circle me-1"></i>Telah Dinilai
                                        </button>
                                    @else
                                        <button type="button" class="btn-action-papar" id="btnActionB10_{{ $vId }}" onclick="openB10DetailModal('{{ $vId }}')">
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
                                        <span class="fw-semibold">Tiada petender berdaftar bagi analisa Borang 10.</span>
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
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSahB10" onchange="checkB10CompletionState()" {{ ($readOnly ?? false) ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSahB10">
                            Saya mengesahkan petender di atas telah dinilai keupayaan teknikal secara teliti.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamadB10" onclick="simpanB10Main()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- POPUP DETAIL MODAL FOR BORANG 10 --}}
<div class="modal fade" id="b10DetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 90%; width: 90%;">
        <div class="modal-content modal-card">
            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-danger flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2;">
                        <i class="bi bi-person-gear fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">BORANG 10 - ANALISA DATA-DATA PENILAIAN KEUPAYAAN TEKNIKAL PETENDER</h5>
                        <p class="text-secondary small mb-0">Analisa kakitangan teknikal, faktor penyama, AKM, peratusan penggajian petender dan pengalaman.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1.5 rounded-pill fw-bold font-monospace" id="b10ModalRefBadge">
                        NO. RUJUKAN PETENDER : -
                    </span>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">

                {{-- Navigation Tabs --}}
                <ul class="nav nav-tabs nav-tabs-modern mb-3" id="b10ModalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="kakitangan-tab" data-bs-toggle="tab" data-bs-target="#kakitangan-tab-pane" type="button" role="tab" aria-controls="kakitangan-tab-pane" aria-selected="true">
                            <i class="bi bi-people-fill me-1.5"></i>Senarai Kakitangan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="borang10-tab" data-bs-toggle="tab" data-bs-target="#borang10-tab-pane" type="button" role="tab" aria-controls="borang10-tab-pane" aria-selected="false">
                            <i class="bi bi-file-earmark-spreadsheet me-1.5"></i>Borang 10
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="b10ModalTabContent">
                    {{-- Tab 1: Senarai Kakitangan --}}
                    <div class="tab-pane fade show active" id="kakitangan-tab-pane" role="tabpanel" aria-labelledby="kakitangan-tab" tabindex="0">
                        <div class="py-2">
                            <div class="w-100">
                                <div class="p-4 rounded-3 border bg-white shadow-sm">
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-lines-fill text-danger fs-5"></i>
                                            <h6 class="fw-bold mb-0 text-dark">SENARAI KAKITANGAN TEKNIKAL PETENDER</h6>
                                        </div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill fw-semibold" id="kakitanganCountBadge">
                                            <i class="bi bi-person-badge me-1"></i>Kakitangan Dalam Penggajian Petender
                                        </span>
                                    </div>

                                    <div class="table-modern-wrapper">
                                        <div class="table-responsive">
                                            <table class="table table-borang10-modern align-middle mb-0" id="tblKakitangan">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50px;" class="text-center">BIL</th>
                                                        <th class="text-start ps-3">NAMA PEGAWAI</th>
                                                        <th style="width: 140px;" class="text-center">KATEGORI</th>
                                                        <th style="width: 220px;" class="text-start ps-3">SIJIL PROFESSIONAL TEKNIKAL</th>
                                                        <th style="width: 160px;" class="text-center">JUMLAH PENGALAMAN</th>
                                                        <th style="width: 120px;" class="text-center">DOKUMEN</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblKakitanganBody">
                                                    <tr>
                                                        <td class="text-center font-monospace fw-bold">1</td>
                                                        <td class="ps-3 fw-semibold text-dark">Ir. Ahmad Razali Bin Hassan</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill">Kat. A</span>
                                                        </td>
                                                        <td class="ps-3 text-muted">Ijazah Sarjana Muda Kejuruteraan Awam (BEM / IEM)</td>
                                                        <td class="text-center font-monospace">12 Tahun</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" onclick="viewKakitanganDoc('Ir. Ahmad Razali Bin Hassan', 'Kat. A')">
                                                                <i class="bi bi-file-earmark-text me-1"></i>Papar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center font-monospace fw-bold">2</td>
                                                        <td class="ps-3 fw-semibold text-dark">Muhammad Hafiz Bin Zakaria</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill">Kat. B</span>
                                                        </td>
                                                        <td class="ps-3 text-muted">Diploma Kejuruteraan Awam</td>
                                                        <td class="text-center font-monospace">7 Tahun</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" onclick="viewKakitanganDoc('Muhammad Hafiz Bin Zakaria', 'Kat. B')">
                                                                <i class="bi bi-file-earmark-text me-1"></i>Papar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center font-monospace fw-bold">3</td>
                                                        <td class="ps-3 fw-semibold text-dark">Siti Nurhaliza Binti Ismail</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill">Kat. C</span>
                                                        </td>
                                                        <td class="ps-3 text-muted">Sijil Kemahiran Malaysia (SKM Tahap 3)</td>
                                                        <td class="text-center font-monospace">4 Tahun</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" onclick="viewKakitanganDoc('Siti Nurhaliza Binti Ismail', 'Kat. C')">
                                                                <i class="bi bi-file-earmark-text me-1"></i>Papar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Nota Kategori Section --}}
                                    <div class="mt-3 p-3 rounded-3 bg-light bg-opacity-50 border">
                                        <div class="d-flex align-items-center gap-2 fw-bold text-dark mb-2" style="font-size:0.825rem;">
                                            <i class="bi bi-info-circle-fill text-danger me-1"></i>
                                            <span>Nota Kategori Kakitangan Teknikal:</span>
                                        </div>
                                        <ul class="mb-0 ps-3 text-secondary" style="font-size: 0.825rem; line-height: 1.6;">
                                            <li><strong>Kategori A (Kat. A)</strong> — Pegawai Profesional / Jurutera Bertauliah (BEM / IEM / CIDB).</li>
                                            <li><strong>Kategori B (Kat. B)</strong> — Penolong Jurutera / Pemegang Diploma Kejuruteraan / Penyelia Tapak.</li>
                                            <li><strong>Kategori C (Kat. C)</strong> — Juruteknik / Pemegang Sijil Kemahiran / Pekerja Mahir.</li>
                                        </ul>
                                    </div>

                                    {{-- Dokumen Sokongan Kakitangan Section --}}
                                    <div class="mt-4 p-4 rounded-3 border bg-white shadow-sm">
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-folder-symlink-fill text-danger fs-5"></i>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark">DOKUMEN SOKONGAN</h6>
                                                    <div class="text-muted" style="font-size:0.75rem;">Senarai dokumen sokongan yang dimuat naik oleh petender.</div>
                                                </div>
                                            </div>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1.5 rounded-pill fw-semibold" id="b10DocsCountBadge">
                                                <i class="bi bi-paperclip me-1"></i>3 Dokumen Dimuat Naik
                                            </span>
                                        </div>

                                        <div class="row g-3" id="b10DocsGrid">
                                            {{-- Doc 1 --}}
                                            <div class="col-12 col-md-4">
                                                <div class="p-3 rounded-3 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                                                    <div class="d-flex align-items-start gap-3 mb-3">
                                                        <div class="rounded-3 p-2.5 bg-danger bg-opacity-10 text-danger flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                            <i class="bi bi-file-earmark-pdf fs-4"></i>
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="Sijil_Pendaftaran_BEM_Ir_Ahmad.pdf">Sijil_Pendaftaran_BEM_Ir_Ahmad.pdf</h6>
                                                            <div class="small text-muted font-monospace">1.4 MB &bull; PDF</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                        <span class="text-muted font-monospace" style="font-size:0.75rem;"><i class="bi bi-clock me-1"></i>12/08/2026</span>
                                                        <div class="d-flex gap-1.5">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold" onclick="viewKakitanganDoc('#')">
                                                                <i class="bi bi-eye me-1"></i>Papar
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-light border text-secondary rounded-2 px-2 py-1" title="Muat Turun">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Doc 2 --}}
                                            <div class="col-12 col-md-4">
                                                <div class="p-3 rounded-3 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                                                    <div class="d-flex align-items-start gap-3 mb-3">
                                                        <div class="rounded-3 p-2.5 bg-primary bg-opacity-10 text-primary flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                            <i class="bi bi-file-earmark-pdf fs-4"></i>
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="Diploma_Kejuruteraan_Hafiz.pdf">Diploma_Kejuruteraan_Hafiz.pdf</h6>
                                                            <div class="small text-muted font-monospace">890 KB &bull; PDF</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                        <span class="text-muted font-monospace" style="font-size:0.75rem;"><i class="bi bi-clock me-1"></i>14/08/2026</span>
                                                        <div class="d-flex gap-1.5">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold" onclick="viewKakitanganDoc('#')">
                                                                <i class="bi bi-eye me-1"></i>Papar
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-light border text-secondary rounded-2 px-2 py-1" title="Muat Turun">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Doc 3 --}}
                                            <div class="col-12 col-md-4">
                                                <div class="p-3 rounded-3 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                                                    <div class="d-flex align-items-start gap-3 mb-3">
                                                        <div class="rounded-3 p-2.5 bg-success bg-opacity-10 text-success flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                            <i class="bi bi-file-earmark-pdf fs-4"></i>
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="Penyata_KWSP_SOCSO_Kakitangan.pdf">Penyata_KWSP_SOCSO_Kakitangan.pdf</h6>
                                                            <div class="small text-muted font-monospace">2.1 MB &bull; PDF</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                        <span class="text-muted font-monospace" style="font-size:0.75rem;"><i class="bi bi-clock me-1"></i>15/08/2026</span>
                                                        <div class="d-flex gap-1.5">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold" onclick="viewKakitanganDoc('#')">
                                                                <i class="bi bi-eye me-1"></i>Papar
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-light border text-secondary rounded-2 px-2 py-1" title="Muat Turun">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Borang 10 --}}
                    <div class="tab-pane fade" id="borang10-tab-pane" role="tabpanel" aria-labelledby="borang10-tab" tabindex="0">
                        <div class="py-2">
                            <div class="table-modern-wrapper mb-3">
                                <div class="table-responsive">
                                    <table class="table-borang10-modern">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="subhead-main text-start">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger">B</span>
                                                        <div>
                                                            <div class="fw-bold">B. KEUPAYAAN TEKNIKAL</div>
                                                            <div class="small text-white-50 font-monospace">B2. KAKITANGAN TEKNIKAL</div>
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 64%; text-align: left;" class="ps-3">Butiran</th>
                                                <th style="width: 12%; text-align: center;">Kat. A</th>
                                                <th style="width: 12%; text-align: center;">Kat. B</th>
                                                <th style="width: 12%; text-align: center;">Kat. C</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {{-- Section B2.1 Bilangan --}}
                                            <tr class="table-secondary">
                                                <td colspan="4" class="ps-3 fw-bold text-dark" style="background: #f1f5f9;">
                                                    <span class="badge bg-light text-dark border me-1">B2.1</span>
                                                    <span>Bilangan Kakitangan</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <span>1. Faktor Penyama</span>
                                                        <span class="symbol-badge" title="Faktor Penyama">a</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace fw-semibold">1.0</td>
                                                <td class="text-center font-monospace fw-semibold">0.7</td>
                                                <td class="text-center font-monospace fw-semibold">0.5</td>
                                            </tr>
                                            @php
                                                $katA_b = $akmTargets['KatA'] ?? 1;
                                                $katB_b = $akmTargets['KatB'] ?? 2;
                                                $katC_b = $akmTargets['KatC'] ?? 3;
                                            @endphp
                                            <tr class="table-light">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <span class="fw-semibold text-dark">2. Bilangan AKM</span>
                                                        <span class="symbol-badge" title="Bilangan AKM">b</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace fw-bold text-primary">{{ $katA_b }}</td>
                                                <td class="text-center font-monospace fw-bold text-primary">{{ $katB_b }}</td>
                                                <td class="text-center font-monospace fw-bold text-primary">{{ $katC_b }}</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <span class="fw-semibold text-dark">3. Bilangan dalam penggajian petender</span>
                                                        <span class="symbol-badge" title="Bilangan dalam penggajian">c</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace fw-bold text-dark" id="b10_katA_c">1</td>
                                                <td class="text-center font-monospace fw-bold text-dark" id="b10_katB_c">1</td>
                                                <td class="text-center font-monospace fw-bold text-dark" id="b10_katC_c">1</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <div>
                                                            <div class="fw-medium text-dark">4. Peratus (%) dpd. AKM</div>
                                                            <span class="formula-tag">[(c) x (a)] x 100 / Sum[(b) x (a)]</span>
                                                        </div>
                                                        <span class="symbol-badge" title="Peratus dpd AKM">d</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace fw-semibold text-primary" id="b10_katA_d">34.5%</td>
                                                <td class="text-center font-monospace fw-semibold text-primary" id="b10_katB_d">24.1%</td>
                                                <td class="text-center font-monospace fw-semibold text-primary" id="b10_katC_d">17.2%</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <div>
                                                            <div class="fw-semibold text-dark">5. (%) keseluruhan dpd. AKM</div>
                                                            <span class="formula-tag">[Sum. d)] atau [(Ad) + 100] jika [(Bd) + (Cd)] &gt; 100%</span>
                                                        </div>
                                                        <span class="symbol-badge" title="Peratus keseluruhan">e</span>
                                                    </div>
                                                </td>
                                                <td colspan="3" class="text-center font-monospace fw-bold bg-light-subtle text-success fs-6" id="b10_total_e">75.8%</td>
                                            </tr>

                                            {{-- Section B2.2 Pengalaman --}}
                                            <tr class="table-secondary">
                                                <td colspan="4" class="ps-3 fw-bold text-dark" style="background: #f1f5f9;">
                                                    <span class="badge bg-light text-dark border me-1">B2.2</span>
                                                    <span>Pengalaman Kakitangan</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <span class="fw-semibold text-dark">1. Jumlah sebenar (tahun)</span>
                                                        <span class="symbol-badge" title="Jumlah sebenar">g</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace fw-bold text-dark" id="b10_katA_g">12 thn</td>
                                                <td class="text-center font-monospace fw-bold text-dark" id="b10_katB_g">7 thn</td>
                                                <td class="text-center font-monospace fw-bold text-dark" id="b10_katC_g">4 thn</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <div>
                                                            <div class="fw-medium text-dark">2. Jumlah sama nilai (tahun)</div>
                                                            <span class="formula-tag">[(g) x (a) / Sum (b) x (a)]</span>
                                                        </div>
                                                        <span class="symbol-badge" title="Jumlah sama nilai">h</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace fw-semibold text-primary" id="b10_katA_h">4.1 thn</td>
                                                <td class="text-center font-monospace fw-semibold text-primary" id="b10_katB_h">1.7 thn</td>
                                                <td class="text-center font-monospace fw-semibold text-primary" id="b10_katC_h">0.7 thn</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-between pe-2">
                                                        <div>
                                                            <div class="fw-semibold text-dark">3. Jumlah sama nilai. Keseluruhan.</div>
                                                            <span class="formula-tag">[Sum.(h)] atau [(Ah)+10.00] jika [(Bh)+(Ch)] &gt; 10.00 tahun.</span>
                                                        </div>
                                                        <span class="symbol-badge" title="Jumlah sama nilai keseluruhan">i</span>
                                                    </div>
                                                </td>
                                                <td colspan="3" class="text-center font-monospace fw-bold bg-light-subtle text-success fs-6" id="b10_total_i">6.5 thn</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
                <button type="button" class="btn-simpan" onclick="simpanPenilaianModalB10()">
                    <i class="bi bi-check-circle-fill me-1"></i>Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

{{-- INDIVIDUAL PERSON DOCUMENTS MODAL --}}
<div class="modal fade" id="personDocsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-card">
            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff;">
                        <i class="bi bi-folder-check fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title fw-bold text-dark mb-0" id="personDocModalName">Ir. Ahmad Razali Bin Hassan</h5>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill" id="personDocModalBadge">Kat. A</span>
                        </div>
                        <p class="text-secondary small mb-0">Senarai dokumen sokongan &amp; sijil kelayakan yang dimuat naik bagi pegawai ini.</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small fw-semibold text-dark"><i class="bi bi-info-circle text-primary me-1.5"></i>Jumlah Dokumen Dimuat Naik</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill fw-bold">2 Dokumen</span>
                    </div>
                </div>

                {{-- Documents List Table --}}
                <div class="table-modern-wrapper">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="font-size: 0.825rem;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center bg-secondary text-white py-2.5">BIL</th>
                                    <th class="ps-3 bg-secondary text-white py-2.5">NAMA DOKUMEN / SIJIL</th>
                                    <th style="width: 140px;" class="text-center bg-secondary text-white py-2.5">SAIZ &amp; FORMAT</th>
                                    <th style="width: 130px;" class="text-center bg-secondary text-white py-2.5">TARIKH MUAT NAIK</th>
                                    <th style="width: 120px;" class="text-center bg-secondary text-white py-2.5">TINDAKAN</th>
                                </tr>
                            </thead>
                            <tbody id="tblPersonDocsBody">
                                <tr>
                                    <td class="text-center font-monospace fw-bold">1</td>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                            <div>
                                                <span class="fw-bold text-dark d-block">Sijil_Pendaftaran_BEM_Ir_Ahmad.pdf</span>
                                                <span class="extra-small text-muted">Sijil Pendaftaran Badan Jurutera Malaysia (BEM)</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center font-monospace text-muted">1.4 MB (PDF)</td>
                                    <td class="text-center font-monospace text-muted">12/08/2026</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold">
                                            <i class="bi bi-eye me-1"></i>Papar
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">2</td>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                            <div>
                                                <span class="fw-bold text-dark d-block">Ijazah_Kejuruteraan_Awam.pdf</span>
                                                <span class="extra-small text-muted">Salinan Ijazah Sarjana Muda Kejuruteraan Awam</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center font-monospace text-muted">980 KB (PDF)</td>
                                    <td class="text-center font-monospace text-muted">12/08/2026</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold">
                                            <i class="bi bi-eye me-1"></i>Papar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-end">
                <button type="button" class="btn btn-secondary px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const b10VendorDataMap = @json($vendorsData);
    let currentB10VendorId = null;

    function openB10DetailModal(vendorId) {
        currentB10VendorId = vendorId;
        const vData = b10VendorDataMap[vendorId] || {};
        const kodText = vData.kod_pembekal || (typeof vData === 'object' ? (vData.kod || vendorId) : vendorId);

        const refBadge = document.getElementById('b10ModalRefBadge');
        if (refBadge) {
            refBadge.innerText = 'NO. RUJUKAN PETENDER : ' + kodText;
        }

        // Compute Row 3 (c): Bilangan Kakitangan per Category
        let countA = 0, countB = 0, countC = 0;
        if (vData.kakitangan_items && Array.isArray(vData.kakitangan_items) && vData.kakitangan_items.length > 0) {
            vData.kakitangan_items.forEach(item => {
                const kat = (item.kategori || '').toString().toUpperCase();
                if (kat.includes('A')) countA++;
                else if (kat.includes('B')) countB++;
                else if (kat.includes('C')) countC++;
            });
        } else {
            countA = vData.count_kat_a || 0;
            countB = vData.count_kat_b || 0;
            countC = vData.count_kat_c || 0;
        }

        const elA_c = document.getElementById('b10_katA_c');
        const elB_c = document.getElementById('b10_katB_c');
        const elC_c = document.getElementById('b10_katC_c');
        if (elA_c) elA_c.innerText = countA;
        if (elB_c) elB_c.innerText = countB;
        if (elC_c) elC_c.innerText = countC;

        // Compute Row 4 (d) & Row 5 (e)
        const bA = {{ json_encode($katA_b) }};
        const bB = {{ json_encode($katB_b) }};
        const bC = {{ json_encode($katC_b) }};
        const sum_ba = (bA * 1.0) + (bB * 0.7) + (bC * 0.5);

        let dA = 0, dB = 0, dC = 0;
        if (sum_ba > 0) {
            dA = (countA * 1.0 * 100) / sum_ba;
            dB = (countB * 0.7 * 100) / sum_ba;
            dC = (countC * 0.5 * 100) / sum_ba;
        }

        const elA_d = document.getElementById('b10_katA_d');
        const elB_d = document.getElementById('b10_katB_d');
        const elC_d = document.getElementById('b10_katC_d');
        if (elA_d) elA_d.innerText = dA.toFixed(1) + '%';
        if (elB_d) elB_d.innerText = dB.toFixed(1) + '%';
        if (elC_d) elC_d.innerText = dC.toFixed(1) + '%';

        let totalE = dA + dB + dC;
        if ((dB + dC) > 100) {
            totalE = dA + 100;
        }
        const elTotalE = document.getElementById('b10_total_e');
        if (elTotalE) elTotalE.innerText = totalE.toFixed(1) + '%';

        // Compute B2.2 Row 1 (g): Total Experience Years per Category
        let expA = 0, expB = 0, expC = 0;
        if (vData.kakitangan_items && Array.isArray(vData.kakitangan_items) && vData.kakitangan_items.length > 0) {
            vData.kakitangan_items.forEach(item => {
                const kat = (item.kategori || '').toString().toUpperCase();
                const yr = parseInt(item.jumlah_pengalaman || item.pengalaman || 0) || 0;
                if (kat.includes('A')) expA += yr;
                else if (kat.includes('B')) expB += yr;
                else if (kat.includes('C')) expC += yr;
            });
        } else {
            expA = vData.exp_kat_a || 0;
            expB = vData.exp_kat_b || 0;
            expC = vData.exp_kat_c || 0;
        }

        const elA_g = document.getElementById('b10_katA_g');
        const elB_g = document.getElementById('b10_katB_g');
        const elC_g = document.getElementById('b10_katC_g');
        if (elA_g) elA_g.innerText = expA + ' thn';
        if (elB_g) elB_g.innerText = expB + ' thn';
        if (elC_g) elC_g.innerText = expC + ' thn';

        // Compute B2.2 Row 2 (h) & Row 3 (i): Equalized Experience Years
        let hA = 0, hB = 0, hC = 0;
        if (sum_ba > 0) {
            hA = (expA * 1.0) / sum_ba;
            hB = (expB * 0.7) / sum_ba;
            hC = (expC * 0.5) / sum_ba;
        }

        const elA_h = document.getElementById('b10_katA_h');
        const elB_h = document.getElementById('b10_katB_h');
        const elC_h = document.getElementById('b10_katC_h');
        if (elA_h) elA_h.innerText = hA.toFixed(1) + ' thn';
        if (elB_h) elB_h.innerText = hB.toFixed(1) + ' thn';
        if (elC_h) elC_h.innerText = hC.toFixed(1) + ' thn';

        let totalI = hA + hB + hC;
        if ((hB + hC) > 10.0) {
            totalI = hA + 10.0;
        }
        const elTotalI = document.getElementById('b10_total_i');
        if (elTotalI) elTotalI.innerText = totalI.toFixed(1) + ' thn';

        const tbodyStaff = document.getElementById('tblKakitanganBody');
        if (tbodyStaff && vData.kakitangan_items && Array.isArray(vData.kakitangan_items) && vData.kakitangan_items.length > 0) {
            tbodyStaff.innerHTML = vData.kakitangan_items.map((item, idx) => `
                <tr>
                    <td class="text-center font-monospace fw-bold">${idx + 1}</td>
                    <td class="ps-3 fw-semibold text-dark">${escapeHtml(item.nama_pegawai || item.nama || '-')}</td>
                    <td class="text-center">
                        <span class="badge ${item.kategori === 'A' ? 'bg-danger' : (item.kategori === 'B' ? 'bg-primary' : 'bg-success')} bg-opacity-10 text-${item.kategori === 'A' ? 'danger' : (item.kategori === 'B' ? 'primary' : 'success')} border border-${item.kategori === 'A' ? 'danger' : (item.kategori === 'B' ? 'primary' : 'success')} border-opacity-25 px-2.5 py-1 rounded-pill">Kat. ${item.kategori || 'A'}</span>
                    </td>
                    <td class="ps-3 text-muted">${escapeHtml(item.sijil_professional || item.sijil || item.kelayakan || '-')}</td>
                    <td class="text-center font-monospace">${item.jumlah_pengalaman !== undefined ? item.jumlah_pengalaman + ' Tahun' : (item.pengalaman ? item.pengalaman + ' Tahun' : '-')}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" onclick="viewKakitanganDoc('${escapeHtml(item.nama_pegawai || item.nama || '')}', 'Kat. ${item.kategori || 'A'}', ${JSON.stringify(item.dokumens || []).replace(/"/g, '&quot;')})">
                            <i class="bi bi-file-earmark-text me-1"></i>Papar
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        const docsGrid = document.getElementById('b10DocsGrid');
        const docsCountBadge = document.getElementById('b10DocsCountBadge');
        const docList = (vData && Array.isArray(vData.dokumen_items)) ? vData.dokumen_items : [];

        if (docsCountBadge) {
            docsCountBadge.innerHTML = `<i class="bi bi-paperclip me-1"></i>${docList.length} Dokumen Dimuat Naik`;
        }

        if (docsGrid && docList.length > 0) {
            docsGrid.innerHTML = docList.map((doc, idx) => {
                const badgeColors = ['bg-danger text-danger', 'bg-primary text-primary', 'bg-success text-success'];
                const colorClass = badgeColors[idx % badgeColors.length];
                const isPdf = doc.original_name && doc.original_name.toLowerCase().endsWith('.pdf');
                return `
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded-3 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="rounded-3 p-2.5 ${colorClass} bg-opacity-10 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="bi ${isPdf ? 'bi-file-earmark-pdf' : 'bi-file-earmark-text'} fs-4"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="${escapeHtml(doc.original_name || 'Dokumen')}">${escapeHtml(doc.original_name || 'Dokumen')}</h6>
                                    <div class="small text-muted font-monospace">${escapeHtml(doc.size_formatted || '1.2 MB')} &bull; ${isPdf ? 'PDF' : 'Dokumen'}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <span class="text-muted font-monospace" style="font-size:0.75rem;"><i class="bi bi-clock me-1"></i>${escapeHtml(doc.created_at || '-')}</span>
                                <div class="d-flex gap-1.5">
                                    <a href="${escapeHtml(doc.file_url || '#')}" target="_blank" class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold">
                                        <i class="bi bi-eye me-1"></i>Papar
                                    </a>
                                    <a href="${escapeHtml(doc.file_url || '#')}" download class="btn btn-sm btn-light border text-secondary rounded-2 px-2 py-1" title="Muat Turun">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        const firstTab = document.getElementById('kakitangan-tab');
        if (firstTab) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(firstTab);
            tabInstance.show();
        }

        const modalEl = document.getElementById('b10DetailModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function viewKakitanganDoc(nama, kat, docs) {
        const modalName = document.getElementById('personDocModalName');
        const modalBadge = document.getElementById('personDocModalBadge');
        if (modalName) modalName.innerText = (typeof nama === 'string' && nama !== '#' && nama.trim() !== '') ? nama : 'Ir. Ahmad Razali Bin Hassan';
        if (modalBadge) {
            const katStr = typeof kat === 'string' ? kat : 'Kat. A';
            modalBadge.innerText = katStr;
            const bgClass = katStr.includes('B') ? 'bg-primary' : (katStr.includes('C') ? 'bg-success' : 'bg-danger');
            const textClass = katStr.includes('B') ? 'text-primary' : (katStr.includes('C') ? 'text-success' : 'text-danger');
            const borderClass = katStr.includes('B') ? 'border-primary' : (katStr.includes('C') ? 'border-success' : 'border-danger');
            modalBadge.className = `badge ${bgClass} bg-opacity-10 ${textClass} border ${borderClass} border-opacity-25 px-2.5 py-1 rounded-pill`;
        }

        const tbodyDocs = document.getElementById('tblPersonDocsBody');
        if (tbodyDocs) {
            if (Array.isArray(docs) && docs.length > 0) {
                tbodyDocs.innerHTML = docs.map((doc, idx) => `
                    <tr>
                        <td class="text-center font-monospace fw-bold">${idx + 1}</td>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                <div>
                                    <span class="fw-bold text-dark d-block">${escapeHtml(doc.original_name || 'Dokumen_Sokongan.pdf')}</span>
                                    <span class="extra-small text-muted">Sijil Sokongan / Kelayakan Staff</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center font-monospace text-muted">${escapeHtml(doc.size_formatted || '1.2 MB')}</td>
                        <td class="text-center font-monospace text-muted">${escapeHtml(doc.created_at || '-')}</td>
                        <td class="text-center">
                            <a href="${escapeHtml(doc.file_url || '#')}" target="_blank" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold">
                                <i class="bi bi-eye me-1"></i>Papar
                            </a>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbodyDocs.innerHTML = `
                    <tr>
                        <td class="text-center font-monospace fw-bold">1</td>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                <div>
                                    <span class="fw-bold text-dark d-block">Sijil_Pendaftaran_BEM_Ir_Ahmad.pdf</span>
                                    <span class="extra-small text-muted">Sijil Pendaftaran Badan Jurutera Malaysia (BEM)</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center font-monospace text-muted">1.4 MB (PDF)</td>
                        <td class="text-center font-monospace text-muted">12/08/2026</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold">
                                <i class="bi bi-eye me-1"></i>Papar
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center font-monospace fw-bold">2</td>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                <div>
                                    <span class="fw-bold text-dark d-block">Ijazah_Kejuruteraan_Awam.pdf</span>
                                    <span class="extra-small text-muted">Salinan Ijazah Sarjana Muda Kejuruteraan Awam</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center font-monospace text-muted">980 KB (PDF)</td>
                        <td class="text-center font-monospace text-muted">12/08/2026</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary rounded-2 px-2.5 py-1 text-nowrap fw-semibold">
                                <i class="bi bi-eye me-1"></i>Papar
                            </button>
                        </td>
                    </tr>
                `;
            }
        }

        const modalEl = document.getElementById('personDocsModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function simpanPenilaianModalB10() {
        if (currentB10VendorId) {
            const btn = document.getElementById('btnActionB10_' + currentB10VendorId);
            if (btn) {
                btn.className = 'btn-action-dinilai';
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Telah Dinilai';
            }
            if (b10VendorDataMap[currentB10VendorId]) {
                b10VendorDataMap[currentB10VendorId].is_evaluated = true;
            }
        }

        const modalEl = document.getElementById('b10DetailModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

        checkB10CompletionState();
        showSuccessModal();
    }

    function checkB10CompletionState() {
        const btn = document.getElementById('btnSimpanMuktamadB10');
        if (!btn) return;

        const isReadOnly = {{ json_encode($readOnly ?? false) }};
        if (isReadOnly) {
            btn.disabled = true;
            return;
        }
        btn.disabled = false;
    }

    function simpanB10Main() {
        const chk = document.getElementById('chkSahB10');
        if (chk && !chk.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Sila tandakan pengesahan terlebih dahulu.',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-4 p-4',
                    confirmButton: 'px-4 py-2 rounded-3'
                }
            });
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('chk_sah', '1');

        fetch('{{ route("penilaianKewanganKerja.borang10.simpanMuktamad", $tenderIdentifier) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya Disimpan!',
                    text: data.message || 'Maklumat Borang 10 telah berjaya disahkan.',
                    confirmButtonText: 'Teruskan',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-4 p-4',
                        confirmButton: 'px-4 py-2 rounded-3'
                    }
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Ralat berlaku semasa menyimpan.',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Ralat pelayan berlaku.',
                confirmButtonColor: '#dc2626'
            });
        });
    }

    function showSuccessModal(msg){
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya Disimpan!',
                text: msg || 'Maklumat borang 10 telah berjaya disimpan ke dalam sistem.',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#19c1a7',
                customClass: {
                    popup: 'rounded-4 p-4',
                    confirmButton: 'px-4 py-2 rounded-3'
                }
            });
        } else {
            alert(msg || 'Maklumat borang 10 telah berjaya disimpan.');
        }
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

    document.addEventListener('DOMContentLoaded', function () {
        checkB10CompletionState();
    });
</script>
@endsection