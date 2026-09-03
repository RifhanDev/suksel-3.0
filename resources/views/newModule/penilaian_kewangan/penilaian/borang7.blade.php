@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b7-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b7-header-banner {
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

    .modal-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
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

    .btn-action-checked {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.45rem 0.95rem;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-action-checked:hover {
        background: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
    }

    .table-modern-b1 th {
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
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no') ?: ($tender_no ?? '');
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $tenderParam) : $tenderParam;
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', $tenderParam) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $vendorsData = $b7VendorSummary ?? [];
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 7</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b7-card mb-4">
        <div class="b7-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Kedua</span>
                    @if($readOnly)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 7 - Analisa Nilai Baki Kerja Dalam Tangan</h3>
                <p class="text-white-50 mb-0 small">Analisis data-data penilaian keupayaan petender bagi nilai baki kerja dalam tangan.</p>
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

    {{-- Main Section Card: Vendor List Table --}}
    <div class="b7-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-bar-chart-steps text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Bagi Analisa Nilai Baki Kerja</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar & Semak</strong> untuk menyemak jadual perincian analisa baki kerja petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($vendorsData) }} Petender Berdaftar
            </span>
        </div>

        {{-- Table Vendor Participants --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">BIL</th>
                            <th><i class="bi bi-person-vcard text-danger me-1"></i> KOD PEMBEKAL</th>
                            <th class="text-end"><i class="bi bi-check-circle text-danger me-1"></i> JUMLAH KERJA DISIAPKAN (RM)</th>
                            <th class="text-end"><i class="bi bi-calendar-check text-danger me-1"></i> JUMLAH TAHUNAN BAKI KERJA (NTBK) (RM)</th>
                            <th class="text-end"><i class="bi bi-cash-stack text-danger me-1"></i> NILAI BAKI KERJA (NBK) (RM)</th>
                            <th style="width: 160px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> TINDAKAN</th>
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
                                <td class="text-end font-monospace text-dark fw-semibold">
                                    {{ $v['jumlah_disiapkan_disp'] }}
                                </td>
                                <td class="text-end font-monospace text-dark fw-semibold">
                                    {{ $v['jumlah_ntbk_disp'] }}
                                </td>
                                <td class="text-end font-monospace text-dark fw-semibold">
                                    {{ $v['jumlah_nbk_disp'] }}
                                </td>
                                @php
                                    $isVendorEvaluated = false;
                                    if (!empty($v['items']) && count($v['items']) > 0) {
                                        $isVendorEvaluated = collect($v['items'])->every(function($it) {
                                            return !is_null($it['jenis']) && $it['jenis'] !== '';
                                        });
                                    }
                                @endphp
                                <td class="text-center">
                                    @if($isVendorEvaluated)
                                        <button type="button" class="btn-action-checked" id="btnVendorStatus_{{ $vId }}" onclick="openB7DetailModal('{{ $vId }}')">
                                            <i class="bi bi-check-circle-fill me-1"></i>Telah Disemak
                                        </button>
                                    @else
                                        <button type="button" class="btn-action-papar" id="btnVendorStatus_{{ $vId }}" onclick="openB7DetailModal('{{ $vId }}')">
                                            <i class="bi bi-eye me-1"></i>Papar & Semak
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-warning display-6"></i>
                                        <span class="fw-semibold">Tiada petender yang lulus Peringkat Pertama untuk analisa Borang 7.</span>
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
            <div class="row g-3 align-items-center mb-0">
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" {{ $readOnly ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                            Saya mengesahkan maklumat analisa nilai baki kerja dalam tangan telah diisi dengan lengkap dan betul.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamad" {{ $readOnly ? 'disabled' : '' }}>
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- =========================
    MODAL: PERINCIAN ANALISA BAKI KERJA (b7DetailModal)
========================== --}}
<div class="modal fade" id="b7DetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 95%; width: 95%;">
        <div class="modal-content modal-card">

            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-bar-chart-steps fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">JADUAL ANALISA NILAI BAKI KERJA DALAM TANGAN</h5>
                        <p class="text-secondary small mb-0">Paparan Analisa Data-Data Penilaian Keupayaan Petender Bagi Nilai Baki Kerja Dalam Tangan.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1.5 rounded-pill fw-bold font-monospace" id="modalKodPetenderBadge">
                        NO. RUJUKAN PETENDER : -
                    </span>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">

                {{-- Table Modern Wrapper --}}
                <div class="table-modern-wrapper mb-4">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table-borang3-modern" style="table-layout: fixed; width: 100%; min-width: 100%;">
                            <colgroup>
                                <col style="width: 3.5%;">
                                <col style="width: 14%;">
                                <col style="width: 8%;">
                                <col style="width: 8.5%;">
                                <col style="width: 8%;">
                                <col style="width: 5.5%;">
                                <col style="width: 5.5%;">
                                <col style="width: 8%;">
                                <col style="width: 6.5%;">
                                <col style="width: 8%;">
                                <col style="width: 8%;">
                                <col style="width: 8.5%;">
                                <col style="width: 8%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th colspan="13" class="subhead-main text-start ps-3 py-2.5" style="background: #6d6d79ff;">
                                        <i class="bi bi-file-earmark-text me-1"></i> <span id="modalRefHeaderTitle">NO. RUJUKAN PETENDER : -</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="compact-th">BIL</th>
                                    <th class="compact-th">NAMA KONTRAK SEMASA</th>
                                    <th class="compact-th">NILAI KONTRAK<br><span class="formula-tag">(RM)</span></th>
                                    <th class="compact-th">NILAI WKP &amp; WPS<br><span class="formula-tag">(RM)</span></th>
                                    <th class="compact-th">NILAI KERJA PEMBINA<br><span class="formula-tag">(RM)</span></th>
                                    <th class="compact-th">PERATUS SIAP<br><span class="formula-tag">(%)</span></th>
                                    <th class="compact-th">PERATUS BELUM SIAP<br><span class="formula-tag">(%)</span></th>
                                    <th class="compact-th">TARIKH JANGKA SIAP SEBENAR</th>
                                    <th class="compact-th">BAKI TEMPOH PENYIAPAN<br><span class="formula-tag">(Bulan)</span></th>
                                    <th class="compact-th">NILAI KERJA DISIAPKAN<br><span class="formula-tag">(RM)</span></th>
                                    <th class="compact-th">NILAI TAHUNAN BAKI KERJA (NTBK)<br><span class="formula-tag">(RM)</span></th>
                                    <th class="compact-th">NILAI BAKI KERJA (NBK)<br><span class="formula-tag">(RM)</span></th>
                                    <th class="compact-th">JENIS</th>
                                </tr>
                            </thead>

                            <tbody id="b7ModalItemsTableBody">
                                {{-- Populated by JavaScript --}}
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold" style="background-color: #f8fafc; border-top: 2px solid #cbd5e1;">
                                    <td colspan="9" class="text-end font-monospace fw-bold text-dark pe-3 py-2.5">
                                        JUMLAH
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-dark py-2.5" id="modalFootJumlahDisiapkan">
                                        0.00
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-dark py-2.5" id="modalFootJumlahNtbk">
                                        0.00
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-dark py-2.5" id="modalFootJumlahNbk">
                                        0.00
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Rumusan Jumlah Section --}}
                <div class="mb-2">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #fef2f2; color: #dc2626;">
                                <i class="bi bi-calculator-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Rumusan Nilai Kerja &amp; Baki Kerja</h6>
                                <span class="text-muted small" style="font-size: 0.75rem;">Pengaliran data rumusan ke borang penilaian seterusnya</span>
                            </div>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold small">
                            3 Rumusan Data
                        </span>
                    </div>

                    <div class="row g-3">
                        {{-- Item 1: Nilai Kerja Disiapkan --}}
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rounded-2 p-1 d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: #eff6ff; color: #2563eb;">
                                            <i class="bi bi-check-circle-fill small"></i>
                                        </div>
                                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">NILAI KERJA YANG TELAH DISIAPKAN</span>
                                    </div>

                                    <div class="ps-1 my-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted small" style="font-size: 0.75rem;">(a) Serupa:</span>
                                            <span class="fw-bold text-dark font-monospace" style="font-size: 0.85rem;" id="cardRumusanDisiapkanSerupa">RM 0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                            <span class="text-muted small" style="font-size: 0.75rem;">(b) Sebanding:</span>
                                            <span class="fw-bold text-dark font-monospace" style="font-size: 0.85rem;" id="cardRumusanDisiapkanSebanding">RM 0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark small" style="font-size: 0.75rem;">JUMLAH:</span>
                                            <h6 class="fw-bold text-primary font-monospace mb-0" style="font-size: 0.95rem;" id="cardRumusanDisiapkan">RM 0.00</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between mt-auto">
                                    <span class="text-muted extra-small" style="font-size: 0.725rem;">Bawa ke:</span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.725rem;">
                                        <i class="bi bi-arrow-right-short me-0.5"></i>Butiran 3 Borang 9
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Item 2: NTBK --}}
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-2 p-1 d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: #fffbeb; color: #d97706;">
                                                <i class="bi bi-calendar-check-fill small"></i>
                                            </div>
                                            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">JUMLAH NILAI TAHUNAN BAKI KERJA (NTBK)</span>
                                        </div>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-3 font-monospace" style="font-size: 0.95rem; line-height: 1.4;" id="cardRumusanNtbk">
                                        RM 0.00
                                    </h6>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between mt-auto">
                                    <span class="text-muted extra-small" style="font-size: 0.725rem;">Bawa ke:</span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.725rem;">
                                        <i class="bi bi-arrow-right-short me-0.5"></i>Butiran 12 Borang 8
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Item 3: NBK --}}
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-2 p-1 d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: #f0fdf4; color: #16a34a;">
                                                <i class="bi bi-cash-stack small"></i>
                                            </div>
                                            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">JUMLAH NILAI BAKI KERJA (NBK)</span>
                                        </div>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-3 font-monospace" style="font-size: 0.95rem; line-height: 1.4;" id="cardRumusanNbk">
                                        RM 0.00
                                    </h6>
                                </div>
                                <div class="pt-2 border-top d-flex align-items-center justify-content-between mt-auto">
                                    <span class="text-muted extra-small" style="font-size: 0.725rem;">Bawa ke:</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.725rem;">
                                        <i class="bi bi-arrow-right-short me-0.5"></i>Borang 14
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success px-4 rounded-3" id="btnSimpanPenilaianB7">
                    <i class="bi bi-floppy me-1"></i>Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const tenderNo = "{{ $tenderParam }}";
    const b7VendorDataMap = @json($b7VendorSummary ?? []);
    let currentModalVendorId = null;

    function recalculateB7ModalTotals() {
        let sumSerupa = 0;
        let sumSebanding = 0;
        let sumTotalDisiapkan = 0;

        $('#b7ModalItemsTableBody tr').each(function () {
            const $row = $(this);
            const valDisiapkanText = $row.find('td:nth-child(10)').text().replace(/,/g, '');
            const valDisiapkan = parseFloat(valDisiapkanText) || 0;
            const jenisVal = $row.find('.field-jenis').val();

            sumTotalDisiapkan += valDisiapkan;
            if (jenisVal === '1') {
                sumSerupa += valDisiapkan;
            } else if (jenisVal === '2') {
                sumSebanding += valDisiapkan;
            }
        });

        const fmt = n => n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const elSerupa = document.getElementById('cardRumusanDisiapkanSerupa');
        const elSebanding = document.getElementById('cardRumusanDisiapkanSebanding');
        const elTotal = document.getElementById('cardRumusanDisiapkan');

        if (elSerupa) elSerupa.innerText = 'RM ' + fmt(sumSerupa);
        if (elSebanding) elSebanding.innerText = 'RM ' + fmt(sumSebanding);
        if (elTotal) elTotal.innerText = 'RM ' + fmt(sumTotalDisiapkan);
    }

    function openB7DetailModal(vId) {
        currentModalVendorId = vId;
        const vData = b7VendorDataMap[vId];
        if (!vData) return;

        const kodLabel = 'NO. RUJUKAN PETENDER : ' + vData.kod_pembekal;
        document.getElementById('modalKodPetenderBadge').innerText = kodLabel;
        document.getElementById('modalRefHeaderTitle').innerText = kodLabel;

        const tbody = document.getElementById('b7ModalItemsTableBody');
        tbody.innerHTML = '';

        const items = vData.items || [];
        if (items.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="13" class="text-center py-4 text-muted">
                        <span class="fw-semibold">Tiada rekod Kerja Semasa dikemukakan oleh petender ini.</span>
                    </td>
                </tr>
            `;
        } else {
            items.forEach(item => {
                const wkpWpsHtml = item.wkp_wps_display !== null ? item.wkp_wps_display : '<span class="text-danger fw-bold">0.00</span>';
                const kerjaPembinaHtml = item.kerja_pembina_display !== null ? item.kerja_pembina_display : '<span class="text-danger fw-bold">0.00</span>';
                const jVal = item.jenis !== null && item.jenis !== undefined ? String(item.jenis) : '';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-center font-monospace fw-bold">${item.bil}</td>
                    <td><span class="fw-bold text-dark d-block">${item.nama}</span></td>
                    <td class="text-end font-monospace">${item.nilai_kontrak_display}</td>
                    <td class="text-end font-monospace">${wkpWpsHtml}</td>
                    <td class="text-end font-monospace">${kerjaPembinaHtml}</td>
                    <td class="text-center font-monospace">${item.peratus_siap_display || '<span class="text-danger fw-bold">0.00%</span>'}</td>
                    <td class="text-center font-monospace">${item.peratus_belum_display || '<span class="text-danger fw-bold">0.00%</span>'}</td>
                    <td class="text-center font-monospace">${item.tarikh_siap || '-'}</td>
                    <td class="text-center font-monospace">${item.baki_bulan !== null ? item.baki_bulan : '<span class="text-danger fw-bold">0</span>'}</td>
                    <td class="text-end font-monospace">${item.nilai_disiapkan_display}</td>
                    <td class="text-end font-monospace">${item.ntbk_display}</td>
                    <td class="text-end font-monospace">${item.nbk_display}</td>
                    <td>
                        <select class="form-select form-select-sm field-jenis" data-item-id="${item.id || ''}" style="font-size: 0.75rem;">
                            <option value="" ${jVal === '' ? 'selected' : ''}>Sila Pilih</option>
                            <option value="1" ${jVal === '1' ? 'selected' : ''}>Serupa</option>
                            <option value="2" ${jVal === '2' ? 'selected' : ''}>Sebanding</option>
                        </select>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('modalFootJumlahDisiapkan').innerText = vData.jumlah_disiapkan_disp;
        document.getElementById('modalFootJumlahNtbk').innerText = vData.jumlah_ntbk_disp;
        document.getElementById('modalFootJumlahNbk').innerText = vData.jumlah_nbk_disp;

        document.getElementById('cardRumusanNtbk').innerText = 'RM ' + vData.jumlah_ntbk_disp;
        document.getElementById('cardRumusanNbk').innerText = 'RM ' + vData.jumlah_nbk_disp;

        recalculateB7ModalTotals();

        const modal = new bootstrap.Modal(document.getElementById('b7DetailModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('change', '.field-jenis', function () {
            if ($(this).val()) {
                $(this).removeClass('border-danger');
            }
            recalculateB7ModalTotals();
        });
        const btnSimpanPenilaianB7 = document.getElementById('btnSimpanPenilaianB7');
        if (btnSimpanPenilaianB7) {
            btnSimpanPenilaianB7.addEventListener('click', function () {
                if (!currentModalVendorId) return;

                let hasEmptyJenis = false;
                const itemsData = [];
                $('#b7ModalItemsTableBody .field-jenis').each(function () {
                    const itemId = $(this).data('item-id');
                    const val = $(this).val();
                    if (!val) {
                        hasEmptyJenis = true;
                        $(this).addClass('border-danger');
                    } else {
                        $(this).removeClass('border-danger');
                    }
                    itemsData.push({
                        id: itemId ? parseInt(itemId) : null,
                        jenis: val ? parseInt(val) : null
                    });
                });

                if (hasEmptyJenis) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maklumat Tidak Lengkap',
                        html: '<p class="mb-1 text-secondary fs-6">Sila pilih <strong>Jenis (Serupa / Sebanding)</strong> bagi kesemua senarai kerja semasa petender sebelum menyimpan penilaian.</p>',
                        confirmButtonText: 'Faham',
                        confirmButtonColor: '#dc2626',
                        customClass: {
                            popup: 'rounded-4 shadow',
                            confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                        }
                    });
                    return;
                }

                btnSimpanPenilaianB7.disabled = true;
                btnSimpanPenilaianB7.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

                fetch(`/penilaian-kewangan-kerja/${encodeURIComponent(tenderNo)}/borang/borang7/simpan-penilaian`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        vendor_id: currentModalVendorId,
                        items: itemsData
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btnSimpanPenilaianB7.disabled = false;
                    btnSimpanPenilaianB7.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Penilaian';

                    if (data.success) {
                        // Update local vendor data map
                        if (b7VendorDataMap[currentModalVendorId] && b7VendorDataMap[currentModalVendorId].items) {
                            b7VendorDataMap[currentModalVendorId].items.forEach(item => {
                                const matched = itemsData.find(i => i.id === item.id);
                                if (matched) {
                                    item.jenis = matched.jenis;
                                }
                            });
                        }

                        // Update main table button to Telah Disemak
                        const btnVendor = document.getElementById('btnVendorStatus_' + currentModalVendorId);
                        if (btnVendor) {
                            btnVendor.className = 'btn-action-checked';
                            btnVendor.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Telah Disemak';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya Disimpan!',
                            text: data.message || 'Penilaian Borang 7 telah berjaya disimpan!',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        const modalEl = document.getElementById('b7DetailModal');
                        const modalObj = bootstrap.Modal.getInstance(modalEl);
                        if (modalObj) modalObj.hide();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat!',
                            text: data.message || 'Gagal menyimpan penilaian Borang 7.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(err => {
                    btnSimpanPenilaianB7.disabled = false;
                    btnSimpanPenilaianB7.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Penilaian';
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
        const btnSimpanMuktamad = document.getElementById('btnSimpanMuktamad');
        if (btnSimpanMuktamad) {
            btnSimpanMuktamad.addEventListener('click', function () {
                const chkSah = document.getElementById('chkSah');
                if (chkSah && !chkSah.checked) {
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
                    return;
                }

                btnSimpanMuktamad.disabled = true;
                btnSimpanMuktamad.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

                fetch(`/penilaian-kewangan-kerja/${encodeURIComponent(tenderNo)}/borang/borang7/simpan-muktamad`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        chk_sah: 1
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya Disimpan!',
                            text: data.message || 'Maklumat Borang 7 telah berjaya disahkan dan disimpan!',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#047857'
                        }).then(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat!',
                            text: data.message || 'Gagal mengesahkan keputusan Borang 7.',
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
</script>
@endsection
