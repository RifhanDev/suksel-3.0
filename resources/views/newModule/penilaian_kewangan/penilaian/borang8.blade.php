@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b8-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b8-header-banner {
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

    /* Table Styling from Borang 3 */
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

    .table-borang3-modern th.subhead-main {
        background: #6d6d79ff;
        color: #ffffff;
        font-size: 0.75rem;
    }

    .table-borang3-modern th.subhead-level2 {
        background: #d7d7d9;
        color: #3f3f3f;
        font-size: 0.725rem;
        font-weight: 700;
        border-color: #cbd5e1;
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
        background: #e2e8f0;
        color: #334155;
        font-size: 0.675rem;
        font-weight: 600;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        margin-left: 0.3rem;
        font-family: monospace;
    }

    .notes-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 12px;
        padding: 1.25rem;
    }

    .formula-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
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
        ? route('penilaianKewanganKerja.show', ['tender_no' => $tenderParam, 'tab' => 'p2']) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $vendorsData = $b8VendorSummary ?? [];
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 8</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b8-card mb-4">
        <div class="b8-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Kedua</span>
                    @if($readOnly)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 8 - Analisa Data-Data Penilaian Keupayaan Petender</h3>
                <p class="text-white-50 mb-0 small">Analisa kedudukan kewangan, modal pusingan, aset, liabiliti, dan keupayaan biayawan petender.</p>
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
    <div class="b8-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-bar-chart-steps text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Bagi Analisa Kedudukan Kewangan</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar &amp; Semak</strong> untuk menyemak jadual perincian analisa kedudukan kewangan petender.</p>
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
                            <th class="text-end"><i class="bi bi-cash-stack text-danger me-1"></i> MODAL PUSINGAN (RM)</th>
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
                                <td class="text-end font-monospace text-dark fw-semibold">
                                    {{ $v['modal_pusingan_disp'] }}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-action-papar" onclick="openB8DetailModal('{{ $vId }}')">
                                        <i class="bi bi-eye me-1"></i>Papar &amp; Semak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-warning display-6"></i>
                                        <span class="fw-semibold">Tiada petender yang lulus Peringkat Pertama untuk analisa Borang 8.</span>
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
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" {{ $readOnly ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                            Saya mengesahkan petender di atas telah dinilai kedudukan kewangan secara teliti.
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
    MODAL: PERINCIAN KEDUDUKAN KEWANGAN (b8DetailModal)
========================== --}}
<div class="modal fade" id="b8DetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 90%; width: 90%;">
        <div class="modal-content modal-card">

            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-danger" style="width: 44px; height: 44px; background: #fef2f2;">
                        <i class="bi bi-calculator-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">BORANG 8 - ANALISA DATA-DATA PENILAIAN KEUPAYAAN PETENDER</h5>
                        <p class="text-secondary small mb-0">Analisa kedudukan kewangan, modal pusingan, aset, liabiliti, dan keupayaan biayawan petender.</p>
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

                {{-- Modern Table Wrapper --}}
                <div class="table-modern-wrapper mb-4">
                    <div class="table-responsive">
                        <table class="table-borang3-modern">
                            <thead>
                                <tr>
                                    <th colspan="3" class="subhead-main text-start ps-3 py-2.5">
                                        <i class="bi bi-person-badge me-1"></i> <span id="modalRefHeaderTitle">NO. RUJUKAN PETENDER : -</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center subhead-level2" style="width: 80px;">Bil.</th>
                                    <th class="text-start subhead-level2 ps-3">Butiran</th>
                                    <th class="text-end subhead-level2 pe-3" style="width: 280px;">Nilai (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">1</td>
                                    <td class="fw-medium">Modal Pusingan <span class="formula-tag">MP *</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_mp">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">2</td>
                                    <td class="fw-medium">Jumlah Asset <span class="formula-tag">JA **</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_ja">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">3</td>
                                    <td class="fw-medium">Jumlah Liabiliti <span class="formula-tag">JL **</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_jl">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">4</td>
                                    <td class="fw-medium">Nett Worth <span class="formula-tag">NW = (JA - JL)</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_nw">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">5</td>
                                    <td class="fw-medium">Jumlah Baki Nilai Kemudahan Kredit yang telah diperolehi <span class="formula-tag">KK ***</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_kk">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">6</td>
                                    <td class="fw-medium">Wang Dalam Tangan Semasa <span class="formula-tag">WDTS</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_wdts">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">7</td>
                                    <td class="fw-medium">Harga Tender <span class="formula-tag">T</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_t">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">8</td>
                                    <td class="fw-medium">Harga Tender mengikut Anggaran Jabatan <span class="formula-tag">AJ</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_aj">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">9</td>
                                    <td class="fw-medium">Nilai Wang Kos Prima <span class="formula-tag">WKP</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_wkp">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">10</td>
                                    <td class="fw-medium">Wang Peruntukan Sementara <span class="formula-tag">WPS</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_wps">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">11</td>
                                    <td class="fw-medium">Tempoh Siap Penengah <span class="formula-tag">TSP</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_tsp">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">12</td>
                                    <td class="fw-medium">Tempoh Penyiapan yang di Tender <span class="formula-tag">TS</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_ts">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">13</td>
                                    <td class="fw-medium">Jumlah Nilai Tahunan Baki Kerja Dalam Tangan <span class="formula-tag">NTBK (Borang 7)</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_ntbk">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">14</td>
                                    <td class="fw-medium text-danger">Nilai Keupayaan Biayawan <span class="formula-tag">KB +</span></td>
                                    <td class="text-end pe-3 font-monospace fw-bold text-danger" id="b8_val_kb">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">15</td>
                                    <td class="fw-medium">Nilai Tahunan Projek <span class="formula-tag">NTP = [ AJ - (WKP + WPS) / TSP ]</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_ntp">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-center font-monospace fw-bold">16</td>
                                    <td class="fw-medium">Peratus Nilai Keupayaan Biayawan berbanding Nilai Tahunan Projek <span class="formula-tag">[ (KB) x 100 / (NTP) ]</span></td>
                                    <td class="text-end pe-3 font-monospace" id="b8_val_peratus">0.00 %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Notes & Formula Section --}}
                <div class="row g-3 mb-4">
                    <div class="col-12 col-lg-7">
                        <div class="notes-box h-100">
                            <div class="d-flex align-items-center gap-2 fw-bold text-amber-900 mb-2" style="color: #92400e;">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                <span>Catatan : (Semua tempoh hendaklah dalam dua titik perpuluhan)</span>
                            </div>
                            <ul class="list-unstyled mb-0 small text-secondary">
                                <li class="d-flex gap-2 mb-2">
                                    <span class="fw-bold text-dark">*</span>
                                    <span>Modal Pusingan (MP) adalah perbezaan antara Asset Semasa dan Liabiliti Semasa petender seperti yang dinyatakan dalam Lembaran Imbangan dan dicontohi nilai positif perbezaan antara WDTS (Penyata Akaun) dengan WDT (Lembaran Imbangan) [MP = (Aset Semasa - Liabiliti Semasa) + Nilai positif (WDTS - WDT)].</span>
                                </li>
                                <li class="d-flex gap-2 mb-2">
                                    <span class="fw-bold text-dark">**</span>
                                    <span>Nilai ini hendaklah seperti yang dinyatakan dalam Lembaran Imbangan seperti yang terdapat dalam Akaun Syarikat yang diaudit oleh Juru Audit bertauliah bagi tahun kewangan terakhir atau sekiranya tiada, bagi tahun kewangan setahun sebelumnya.</span>
                                </li>
                                <li class="d-flex gap-2">
                                    <span class="fw-bold text-dark">***</span>
                                    <span>Nilai-nilai ini hendaklah seperti yang dinyatakan dalam Laporan Bank mengenai kedudukan kewangan petender.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="formula-box h-100">
                            <div class="d-flex align-items-center gap-2 fw-bold text-dark mb-2">
                                <i class="bi bi-calculator me-1 text-danger"></i>
                                <span>Formula Keupayaan Biayawan (KB)</span>
                            </div>
                            <div class="bg-white p-3 rounded-3 border font-monospace small text-dark mb-2 shadow-sm">
                                <div class="mb-1"><span class="badge bg-secondary me-1">1</span> (KB) = [ (10 &times; MP) + (5 &times; (NW - MP)) ] - [0.5 &times; NTBK]</div>
                                <div class="mb-1"><span class="badge bg-secondary me-1">2</span> (KB) = [ (10 &times; MP) + (9 &times; KK) ] - [0.5 &times; NTBK]</div>
                                <div><span class="badge bg-secondary me-1">3</span> (KB) = [ (10 &times; WDTS) + (9 &times; KK) ] - (0.5 &times; NTBK)</div>
                            </div>
                            <div class="small text-muted fst-italic">
                                <i class="bi bi-info-circle me-1"></i>* Yang mana lebih tinggi.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Final RM Value Input Box --}}
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-cash-stack text-danger fs-5"></i>
                        <span class="fw-bold text-dark">Nilai Keupayaan Biayawan (KB):</span>
                    </div>
                    <div class="input-group" style="max-width: 280px;">
                        <span class="input-group-text fw-bold bg-white text-muted">RM</span>
                        <input type="text" class="form-control font-monospace fw-bold text-end fs-6" id="b8_input_kb" placeholder="0.00" readonly>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const tenderNo = "{{ $tenderParam }}";
    const b8VendorDataMap = @json($b8VendorSummary ?? []);

    function openB8DetailModal(vId) {
        const vData = b8VendorDataMap[vId];
        const kodPembekal = vData ? vData.kod_pembekal : vId;

        document.getElementById('modalKodPetenderBadge').innerText = 'NO. RUJUKAN PETENDER : ' + kodPembekal;
        document.getElementById('modalRefHeaderTitle').innerText = 'NO. RUJUKAN PETENDER : ' + kodPembekal;

        if (vData && vData.b8_items) {
            const items = vData.b8_items;

            const fieldsMap = [
                { id: 'b8_val_mp', item: items.item1_mp },
                { id: 'b8_val_ja', item: items.item2_ja },
                { id: 'b8_val_jl', item: items.item3_jl },
                { id: 'b8_val_nw', item: items.item4_nw },
                { id: 'b8_val_kk', item: items.item5_kk },
                { id: 'b8_val_wdts', item: items.item6_wdts },
                { id: 'b8_val_t', item: items.item7_t },
                { id: 'b8_val_aj', item: items.item8_aj },
                { id: 'b8_val_wkp', item: items.item9_wkp },
                { id: 'b8_val_wps', item: items.item10_wps },
                { id: 'b8_val_tsp', item: items.item11_tsp },
                { id: 'b8_val_ts', item: items.item12_ts },
                { id: 'b8_val_ntbk', item: items.item13_ntbk },
                { id: 'b8_val_kb', item: items.item14_kb },
                { id: 'b8_val_ntp', item: items.item15_ntp },
                { id: 'b8_val_peratus', item: items.item16_peratus },
            ];

            fieldsMap.forEach(f => {
                const el = document.getElementById(f.id);
                if (el && f.item) {
                    if (f.item.is_null) {
                        el.innerHTML = '<span class="text-danger fw-bold">' + f.item.disp + '</span>';
                    } else {
                        el.innerText = f.item.disp;
                    }
                }
            });

            const inputKb = document.getElementById('b8_input_kb');
            if (inputKb && items.item14_kb) {
                inputKb.value = items.item14_kb.disp;
            }
        }

        const modal = new bootstrap.Modal(document.getElementById('b8DetailModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
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

                fetch(`/penilaian-kewangan-kerja/${encodeURIComponent(tenderNo)}/borang/borang8/simpan-muktamad`, {
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
                            text: data.message || 'Maklumat Borang 8 telah berjaya disahkan dan disimpan!',
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
                            text: data.message || 'Gagal mengesahkan keputusan Borang 8.',
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
