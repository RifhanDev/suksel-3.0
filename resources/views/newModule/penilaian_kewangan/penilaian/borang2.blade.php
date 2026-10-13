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

    .status-badge-tidak {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 700;
        font-size: 0.775rem;
        padding: 0.3rem 0.8rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-badge-pending {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
        font-weight: 600;
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
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $tender_no) : ($tender_no ?? '');
    $backToTenderUrl = route('penilaianKewanganKerja.show', $tenderIdentifier);
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
                        <div class="info-item-value text-danger font-monospace fw-bold" style="font-size: 0.95rem;">{{ $no_tender_display ?? $tender->no_tender ?? $tender->ref_number ?? '-' }}</div>
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
                        <div class="info-item-value text-dark fw-bold text-truncate" style="font-size: 0.88rem;" title="{{ $ptj_display ?? $tender->tenderer->name ?? '-' }}">{{ $ptj_display ?? $tender->tenderer->name ?? '-' }}</div>
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
                                {{ $status_label ?? 'Menunggu Penilaian Kewangan' }}
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
                        <div class="info-item-value text-dark font-monospace fw-bold" style="font-size: 0.95rem;">{{ $sah_laku_tamat ?? '-' }}</div>
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
                <i class="bi bi-file-earmark-medical me-1"></i>{{ count($b2DocsList) }} Dokumen Kewangan
            </span>
        </div>

        <div class="table-modern-wrapper mb-2">
            <div class="table-responsive">
                <table class="table table-modern-b2 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">#</th>
                            <th><i class="bi bi-file-earmark-text text-danger me-1"></i> Dokumen Sokongan Kewangan</th>
                            <th style="width: 180px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> Status Dinilai</th>
                            <th style="width: 180px;" class="text-center"><i class="bi bi-gear text-danger me-1"></i> Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($b2DocsList as $dId => $doc)
                            @php
                                $dStat = $b2DocStatusMap[$dId] ?? ['evaluated_count' => 0, 'total_count' => count($participants), 'is_complete' => false];
                            @endphp
                            <tr>
                                <td class="text-center text-muted fw-bold small">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; margin-right:10px">
                                            <i class="bi {{ $doc['icon'] }}"></i>
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $doc['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($dStat['is_complete'])
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill small">
                                            <i class="bi bi-check-circle-fill me-1"></i>Selesai ({{ $dStat['evaluated_count'] }}/{{ $dStat['total_count'] }})
                                        </span>
                                    @elseif($dStat['evaluated_count'] > 0)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1 rounded-pill small">
                                            <i class="bi bi-clock me-1"></i>Separa ({{ $dStat['evaluated_count'] }}/{{ $dStat['total_count'] }})
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 rounded-pill small">
                                            <i class="bi bi-hourglass-split me-1"></i>Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn-action-papar btn-papar-b2" type="button" 
                                        data-doc-id="{{ $dId }}"
                                        data-doc-name="{{ $doc['name'] }}"
                                        data-show-diaudit="{{ $doc['showDiaudit'] ? 1 : 0 }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#docModal">
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
                <i class="bi bi-check-circle-fill me-1"></i>Hasil Semakan ({{ count($participants) }} Petender)
            </span>
        </div>

        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b2 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">Bil</th>
                            <th style="width: 260px;">Maklumat Petender</th>
                            <th style="width: 200px;" class="text-center">Keputusan Semakan</th>
                            <th>Ulasan / Catatan Kecukupan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($b2VendorSummary as $vId => $vSum)
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $loop->iteration }}/{{ count($b2VendorSummary) }}</td>
                                <td>
                                    <div class="fw-bold text-dark mb-0.5">{{ $vSum['vendor_name'] }}</div>
                                    <div class="font-monospace extra-small text-muted">{{ $vSum['kod_pembekal'] }}</div>
                                </td>
                                <td class="text-center">
                                    @if($vSum['is_cukup'])
                                        <span class="status-badge-cukup">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ $vSum['final_status'] }}
                                        </span>
                                    @elseif($vSum['final_status'] === 'Tidak Cukup')
                                        <span class="status-badge-tidak">
                                            <i class="bi bi-x-circle-fill me-1"></i>Tidak Cukup
                                        </span>
                                    @else
                                        <span class="status-badge-pending">
                                            <i class="bi bi-hourglass-split me-1"></i>Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(! empty($vSum['failed_reasons']))
                                        <ul class="mb-0 text-danger extra-small ps-3 fw-medium">
                                            @foreach($vSum['failed_reasons'] as $reason)
                                                <li>{{ $reason }}</li>
                                            @endforeach
                                        </ul>
                                    @elseif($vSum['is_cukup'])
                                        <span class="text-success small fw-semibold"><i class="bi bi-check2 me-1"></i>{{ $vSum['catatan'] ?: 'Dokumen kewangan lengkap dikemukakan.' }}</span>
                                    @else
                                        <span class="text-muted extra-small">Sila selesaikan semakan dokumen kewangan bagi petender ini.</span>
                                    @endif
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
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                        <i class="bi bi-people me-1 text-danger"></i>Bilangan Pembekal Dinilai
                    </label>
                    <input type="number" class="form-control form-control-modern font-monospace fw-bold" value="{{ count($participants) }}" readonly style="max-width: 140px; background: #e2e8f0;">
                </div>
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah">
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
                <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamad">
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
                        <table class="table align-middle mb-0" id="modalVendorTableB2" style="font-size: 0.85rem;">
                            <thead style="--bs-table-bg: #d7d7d9; --bs-table-color: #3f3f3f;">
                                <tr>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 80px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Bil</th>
                                    <th class="text-uppercase fw-bold py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Dokumen / Syarikat Petender</th>
                                    <th class="text-center text-uppercase fw-bold py-2" style="width: 190px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Dikemukakan</th>
                                    <th id="thDiaudit" class="text-center text-uppercase fw-bold py-2" style="width: 190px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Diaudit</th>
                                    <th class="text-uppercase fw-bold py-2" style="width: 260px; font-size: 0.7rem; letter-spacing: 0.05em; background-color: #d7d7d9 !important; color: #3f3f3f !important;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participants as $idx => $p)
                                    @php
                                        $vId = $p->vendor_id;
                                        $vSum = $b2VendorSummary[$vId] ?? [];
                                        $docsData = $vSum['docs_data'] ?? [];
                                    @endphp
                                    <tr data-vendor-id="{{ $vId }}">
                                        <td class="text-center fw-bold text-muted" style="background-color: #efeff0ff; color: #3f3f3fff;">{{ $idx + 1 }}/{{ count($participants) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3 py-1">
                                                <div class="bg-danger bg-opacity-10 text-danger p-2.5 rounded-3 d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px;">
                                                    <i class="bi bi-file-earmark-pdf fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;">{{ $p->vendor->name ?? $p->vendor->company_name ?? ('Syarikat Petender ' . $vId) }}</div>
                                                    <a href="javascript:void(0)" class="d-inline-flex align-items-center gap-1.5 text-primary text-decoration-none small fw-medium btn-link-doc" data-vendor-id="{{ $vId }}">
                                                        <i class="bi bi-box-arrow-up-right me-1 icon-link-doc"></i><span class="text-link-doc">Buka Borang</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select form-select-modern text-center fw-semibold select-dikemukakan">
                                                <option value="Ya">Ya</option>
                                                <option value="Tidak">Tidak</option>
                                            </select>
                                        </td>
                                        <td class="text-center td-diaudit">
                                            <select class="form-select form-select-modern text-center fw-semibold select-diaudit">
                                                <option value="Ya">Ya</option>
                                                <option value="Tidak">Tidak</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-modern input-catatan" value="" placeholder="Catatan...">
                                        </td>
                                    </tr>
                                @endforeach
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
                        <button class="btn btn-submit-danger px-4 rounded-3" type="button" onclick="backToDocList()">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Detail Penyata Bank
                        </button>
                    </div>
                </div>

            </div>

            <div id="docViewListFooter" class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal / Tutup</button>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-success px-4 fw-bold" type="button" id="btnSimpanSemakanModal">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Semakan
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- =========================
    MODAL: PAPAR BORANG ATAS TALIAN (READ-ONLY)
========================== --}}
<div class="modal fade" id="borangModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header px-4 pt-3 pb-2 border-bottom bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div>
                        <span class="d-block text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.05em;">Paparan Semakan Borang (Mod Paparan Sahaja)</span>
                        <h6 id="borangModalTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Borang Atas Talian</h6>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 position-relative" style="background: #f8fafc; min-height: 350px;">
               
                <div id="borangModalContent" style="display:none;"></div>
            </div>
            <div class="modal-footer bg-light px-4 py-2 border-top justify-content-between">
                <span class="text-muted extra-small"><i class="bi bi-shield-lock me-1 text-primary"></i>Mod Paparan Sahaja — Data petender dilindungi daripada sebarang pengemaskinian.</span>
                <button type="button" class="btn btn-sm btn-secondary px-4 fw-bold rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentDocId = 'imbangan';
        let currentShowDiaudit = true;
        const b2VendorSummaryData = @json($b2VendorSummary);

        const docViewList = document.getElementById('docViewList');
        const docViewPenyataBank = document.getElementById('docViewPenyataBank');
        const docViewListFooter = document.getElementById('docViewListFooter');
        const thDiaudit = document.getElementById('thDiaudit');

        // Populate modal data when Papar & Semak button is clicked
        document.querySelectorAll('.btn-papar-b2').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentDocId = this.dataset.docId || 'imbangan';
                const docName = this.dataset.docName || 'DOKUMEN KEWANGAN';
                currentShowDiaudit = (parseInt(this.dataset.showDiaudit) === 1);

                document.getElementById('docModalName').textContent = docName;

                // Toggle views
                docViewList.style.display = 'block';
                if (docViewListFooter) docViewListFooter.style.display = 'flex';
                docViewPenyataBank.style.display = 'none';

                if (thDiaudit) {
                    thDiaudit.style.display = currentShowDiaudit ? '' : 'none';
                }

                // Populate vendor rows
                document.querySelectorAll('#modalVendorTableB2 tbody tr').forEach(function (tr) {
                    const vendorId = tr.getAttribute('data-vendor-id');
                    const vSum = b2VendorSummaryData[vendorId] || {};
                    const docsData = vSum.docs_data || {};
                    const item = docsData[currentDocId] || {};

                    const selectDikemukakan = tr.querySelector('.select-dikemukakan');
                    const selectDiaudit = tr.querySelector('.select-diaudit');
                    const tdDiaudit = tr.querySelector('.td-diaudit');
                    const inputCatatan = tr.querySelector('.input-catatan');

                    // Update Buka Borang / Dokumen link appearance per vendor
                    const textLinkDoc = tr.querySelector('.text-link-doc');
                    const iconLinkDoc = tr.querySelector('.icon-link-doc');
                    const docActions = vSum.doc_actions || {};
                    const action = docActions[currentDocId] || {};

                    if (action.type === 'borang_atas_talian') {
                        if (textLinkDoc) textLinkDoc.textContent = 'Buka Borang';
                        if (iconLinkDoc) iconLinkDoc.className = 'bi bi-box-arrow-up-right me-1 icon-link-doc';
                    } else {
                        if (action.has_file && action.is_pdf) {
                            if (textLinkDoc) textLinkDoc.textContent = 'Lihat PDF';
                            if (iconLinkDoc) iconLinkDoc.className = 'bi bi-file-earmark-pdf me-1 icon-link-doc';
                        } else if (action.has_file) {
                            if (textLinkDoc) textLinkDoc.textContent = 'Muat Turun Dokumen';
                            if (iconLinkDoc) iconLinkDoc.className = 'bi bi-file-earmark-arrow-down me-1 icon-link-doc';
                        } else {
                            if (textLinkDoc) textLinkDoc.textContent = 'Tiada Dokumen';
                            if (iconLinkDoc) iconLinkDoc.className = 'bi bi-exclamation-triangle me-1 icon-link-doc text-warning';
                        }
                    }

                    if (selectDikemukakan) {
                        selectDikemukakan.value = item.dikemukakan || 'Ya';
                    }

                    if (tdDiaudit) {
                        tdDiaudit.style.display = currentShowDiaudit ? '' : 'none';
                    }

                    if (selectDiaudit) {
                        selectDiaudit.value = item.diaudit || 'Ya';
                    }

                    if (inputCatatan) {
                        inputCatatan.value = item.catatan || '';
                    }
                });
            });
        });

        // Dynamic Buka Borang / Dokumen click handler
        document.querySelectorAll('.btn-link-doc').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const tr = this.closest('tr');
                const vendorId = tr ? tr.getAttribute('data-vendor-id') : this.getAttribute('data-vendor-id');
                const vSum = b2VendorSummaryData[vendorId] || {};
                const docActions = vSum.doc_actions || {};
                const action = docActions[currentDocId] || {};

                if (action.type === 'borang_atas_talian') {
                    if (action.url) {
                        const loader = document.getElementById('borangLoader');
                        const content = document.getElementById('borangModalContent');
                        const modalTitle = document.getElementById('borangModalTitle');

                        if (loader) loader.style.display = 'flex';
                        if (content) { content.style.display = 'none'; content.innerHTML = ''; }
                        if (modalTitle) modalTitle.textContent = (action.original_name || 'Borang Atas Talian') + ' — ' + (vSum.vendor_name || '');

                        const borangModalEl = document.getElementById('borangModal');
                        if (borangModalEl) {
                            const borangModal = bootstrap.Modal.getOrCreateInstance(borangModalEl);
                            borangModal.show();
                        }

                        fetch(action.url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('HTTP error ' + response.status);
                            return response.text();
                        })
                        .then(html => {
                            if (loader) loader.style.display = 'none';
                            if (content) {
                                content.innerHTML = html;
                                content.style.display = 'block';

                                // Dynamically execute ONLY inline script elements inside injected HTML (ignoring external library src scripts)
                                const scripts = Array.from(content.querySelectorAll('script'));
                                scripts.forEach(oldScript => {
                                    if (!oldScript.src) {
                                        const newScript = document.createElement('script');
                                        Array.from(oldScript.attributes).forEach(attr => {
                                            newScript.setAttribute(attr.name, attr.value);
                                        });
                                        newScript.text = oldScript.text || oldScript.textContent;
                                        oldScript.parentNode.replaceChild(newScript, oldScript);
                                    }
                                });

                                // Lock all inputs/buttons inside modal to read-only
                                const lockInputs = function () {
                                    content.querySelectorAll('input, select, textarea, button[type="submit"]').forEach(el => {
                                        el.disabled = true;
                                    });
                                };
                                lockInputs();
                                setTimeout(lockInputs, 100);
                            }
                        })
                        .catch(err => {
                            console.error('AJAX Fetch Borang Error:', err);
                            if (loader) loader.style.display = 'none';
                            if (content) {
                                content.innerHTML = '<div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 rounded-3 p-3"><i class="bi bi-exclamation-octagon-fill me-2"></i>Gagal memuatkan borang petender. Sila cuba lagi.</div>';
                                content.style.display = 'block';
                            }
                        });
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Pautan Borang Tidak Dijumpai',
                                text: 'Pautan ke borang atas talian ini belum dikonfigurasi.',
                                confirmButtonColor: '#dc2626'
                            });
                        } else {
                            alert('Pautan ke borang atas talian ini belum dikonfigurasi.');
                        }
                    }
                } else if (action.type === 'standard') {
                    if (!action.has_file || !action.url) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Tiada Dokumen',
                                text: 'Petender belum mengemukakan dokumen sokongan bagi item ini.',
                                confirmButtonColor: '#dc2626'
                            });
                        } else {
                            alert('Petender belum mengemukakan dokumen sokongan bagi item ini.');
                        }
                    } else if (action.is_pdf) {
                        window.open(action.url, '_blank');
                    } else {
                        window.location.href = action.url;
                    }
                }
            });
        });

        // Cleanup modal DOM and event handlers when modal is hidden to maintain high performance
        const borangModalEl = document.getElementById('borangModal');
        if (borangModalEl) {
            borangModalEl.addEventListener('hidden.bs.modal', function () {
                const content = document.getElementById('borangModalContent');
                if (content) {
                    content.innerHTML = '';
                    content.style.display = 'none';
                }
                if (typeof jQuery !== 'undefined') {
                    jQuery(document).off('focus blur input change', '.amount-input, .field-harga, .wang-kos-prima, .wang-peruntukan-semasa, .nilai-kerja, .penyata-bank-bulan-input, .jumlah-deposit');
                }
            });
        }

        // AJAX Save Modal Evaluations for Borang 2 Document Item
        document.getElementById('btnSimpanSemakanModal').addEventListener('click', function () {
            const btn = this;
            const evaluations = [];

            document.querySelectorAll('#modalVendorTableB2 tbody tr').forEach(function (tr) {
                const vendorId = tr.getAttribute('data-vendor-id');
                const dikemukakan = tr.querySelector('.select-dikemukakan').value;
                const selectDiaudit = tr.querySelector('.select-diaudit');
                const diaudit = (currentShowDiaudit && selectDiaudit) ? selectDiaudit.value : 'T.K.S';
                const catatan = tr.querySelector('.input-catatan').value;

                evaluations.push({
                    vendor_id: parseInt(vendorId),
                    dikemukakan: dikemukakan,
                    diaudit: diaudit,
                    catatan: catatan
                });
            });

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

            fetch('{{ route('penilaianKewanganKerja.borang2.simpanKriteria', $tenderIdentifier) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    doc_id: currentDocId,
                    evaluations: evaluations
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2-circle me-1"></i>Simpan Semakan';

                if (data.success) {
                    const docModalEl = document.getElementById('docModal');
                    const docModal = bootstrap.Modal.getInstance(docModalEl) || new bootstrap.Modal(docModalEl);
                    docModal.hide();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya Disimpan!',
                            text: data.message || 'Penilaian dokumen telah berjaya disimpan.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#047857'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(data.message || 'Berjaya disimpan.');
                        window.location.reload();
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: data.message || 'Gagal menyimpan penilaian dokumen.',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2-circle me-1"></i>Simpan Semakan';
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat Sistem!',
                    text: 'Berlaku masalah semasa berhubung dengan pelayan.',
                    confirmButtonColor: '#dc2626'
                });
            });
        });

        // AJAX Final Submission of Borang 2
        const btnSimpanMuktamad = document.getElementById('btnSimpanMuktamad');
        if (btnSimpanMuktamad) {
            btnSimpanMuktamad.addEventListener('click', function () {
                const chkSah = document.getElementById('chkSah');
                if (chkSah && ! chkSah.checked) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pengesahan Diperlukan',
                            html: '<p class="mb-1 text-secondary fs-6">Sila tandakan <strong>kotak pengesahan</strong> terlebih dahulu sebelum menyimpan keputusan.</p>',
                            confirmButtonText: 'Faham',
                            confirmButtonColor: '#dc2626',
                            customClass: {
                                popup: 'rounded-4 shadow',
                                confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                            }
                        });
                    } else {
                        alert('Sila tandakan kotak pengesahan terlebih dahulu.');
                    }
                    return;
                }

                btnSimpanMuktamad.disabled = true;
                btnSimpanMuktamad.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

                fetch('{{ route('penilaianKewanganKerja.borang2.simpanMuktamad', $tenderIdentifier) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ confirm: true })
                })
                .then(response => response.json())
                .then(data => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Borang 2 Disahkan!',
                            text: data.message || 'Borang 2 telah berjaya disimpan dan Borang 3 kini dibuka!',
                            confirmButtonText: 'Seterusnya (Papan Pemuka)',
                            confirmButtonColor: '#dc2626'
                        }).then(() => {
                            window.location.href = data.redirect || '{{ $backToTenderUrl }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat!',
                            text: data.message || 'Gagal mengesahkan Borang 2.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(err => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat Sistem!',
                        text: 'Berlaku masalah semasa berhubung dengan pelayan.',
                        confirmButtonColor: '#dc2626'
                    });
                });
            });
        }
    });

    function openPenyataBankDetail(){
        document.getElementById('docViewList').style.display = 'none';
        const docViewListFooter = document.getElementById('docViewListFooter');
        if (docViewListFooter) docViewListFooter.style.display = 'none';
        document.getElementById('docViewPenyataBank').style.display = 'block';
    }

    function backToDocList(){
        document.getElementById('docViewPenyataBank').style.display = 'none';
        document.getElementById('docViewList').style.display = 'block';
        const docViewListFooter = document.getElementById('docViewListFooter');
        if (docViewListFooter) docViewListFooter.style.display = 'flex';
    }

    window.openPenyataBankDetail = openPenyataBankDetail;
    window.backToDocList = backToDocList;
</script>
@endsection
