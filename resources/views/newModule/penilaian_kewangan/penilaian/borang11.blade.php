@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
        --sg-teal: #19c1a7;
        --sg-teal-dark: #0d9488;
        --sg-blue: #2563eb;
        --sg-green: #16a34a;
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

    /* Modal Form Table Styling */
    .table-borang11-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang11-modern th {
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

    .table-borang11-modern th.subhead-main {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.825rem;
        text-transform: uppercase;
        padding: 0.9rem 1.25rem;
        letter-spacing: 0.5px;
    }

    .table-borang11-modern td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang11-modern tbody tr:hover {
        background-color: #f8fafc;
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
    }

    .btn-simpan:hover {
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(25, 193, 167, 0.35);
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no') ?: ($tender_no ?? '');
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', ['tender_no' => $tenderParam, 'tab' => 'p2']) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));
    $readOnly = $readOnly ?? false;

    // Build or fallback vendor summary for Borang 11b
    $b11List = $b11VendorSummary ?? [
        1 => [
            'vendor_id'    => 1,
            'kod_pembekal' => 'V001',
            'vendor_name'  => 'Syarikat Bina Jaya Sdn Bhd',
            'is_evaluated' => true,
            'kewangan_mark' => '25.00',
            'teknikal_mark' => '48.50',
            'jumlah_mark'  => '73.50',
            'keputusan'    => 'LULUS',
        ],
        2 => [
            'vendor_id'    => 2,
            'kod_pembekal' => 'V002',
            'vendor_name'  => 'Pembinaan Utama Sdn Bhd',
            'is_evaluated' => true,
            'kewangan_mark' => '28.00',
            'teknikal_mark' => '52.00',
            'jumlah_mark'  => '80.00',
            'keputusan'    => 'LULUS',
        ],
        3 => [
            'vendor_id'    => 3,
            'kod_pembekal' => 'V003',
            'vendor_name'  => 'Perusahaan Gagah Budi Enterprise',
            'is_evaluated' => false,
            'kewangan_mark' => '15.00',
            'teknikal_mark' => '25.00',
            'jumlah_mark'  => '40.00',
            'keputusan'    => 'GAGAL',
        ],
    ];
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: ($tender_no ?? '')) : ($tender_no ?? '');
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 11b</li>
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
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 11b - Penilaian Keupayaan Petender</h3>
                <p class="text-white-50 mb-0 small">Pengiraan markah penilaian keupayaan kewangan &amp; teknikal petender bagi tender kerja penyelenggaraan mekanikal / elektrikal.</p>
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
                        <div class="info-item-value text-danger font-monospace">{{ $no_tender_display ?? ($tender_no ?? 'TENDER-2026-001') }}</div>
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
                        <div class="info-item-value text-dark">{{ $ptj_display ?? 'JABATAN KERJA RAYA (JKR)' }}</div>
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
                    <h5 class="fw-bold mb-0">Senarai Petender Bagi Analisa Keupayaan Petender (Borang 11b)</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar &amp; Semak</strong> untuk menyemak jadual perincian markah keupayaan petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($b11List) }} Petender Berdaftar
            </span>
        </div>

        {{-- Table Participating Vendors --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-modern-b10">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">BIL</th>
                            <th style="width: 15%; text-align: center;">KOD PETENDER</th>
                            <th style="width: 35%;">NAMA PETENDER</th>
                            <th style="width: 15%; text-align: center;">STATUS PENILAIAN</th>
                            <th style="width: 15%; text-align: center;">STATUS KELAYAKAN</th>
                            <th style="width: 15%; text-align: center;">TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($b11List as $index => $v)
                            @php
                                $vId = $v['vendor_id'] ?? $loop->iteration;
                                $kodText = $v['kod_pembekal'] ?? ('V' . str_pad($vId, 3, '0', STR_PAD_LEFT));
                                $vName = $v['vendor_name'] ?? ('Syarikat Petender ' . $vId);
                                $isEvaluated = $v['is_evaluated'] ?? false;
                                $keputusan = $v['keputusan'] ?? 'LULUS';
                            @endphp
                            <tr>
                                <td class="text-center font-monospace fw-bold">{{ $loop->iteration }}</td>
                                <td class="text-center font-monospace fw-bold text-primary">{{ $kodText }}</td>
                                <td class="fw-bold text-dark">{{ $vName }}</td>
                                <td class="text-center" id="statusContainerB11_{{ $vId }}">
                                    @if($isEvaluated)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i>Telah Dinilai
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1 rounded-pill">
                                            <i class="bi bi-clock me-1"></i>Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($keputusan === 'LULUS')
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.775rem;">
                                            <i class="bi bi-check-lg me-1"></i>LULUS
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.775rem;">
                                            <i class="bi bi-x-lg me-1"></i>GAGAL
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="{{ $isEvaluated ? 'btn-action-dinilai' : 'btn-action-papar' }}" id="btnActionB11_{{ $vId }}" onclick="openB11DetailModal({{ $vId }})">
                                        <i class="bi bi-eye me-1"></i>Papar &amp; Semak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tiada petender ditemui.</td>
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
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSahB11" onchange="checkB11CompletionState()" {{ ($readOnly ?? false) ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSahB11">
                            Saya mengesahkan petender di atas telah dinilai keupayaan kewangan &amp; teknikal secara teliti.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamadB11" onclick="simpanB11Main()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- POPUP DETAIL MODAL FOR BORANG 11b --}}
<div class="modal fade" id="b11DetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 90%; width: 90%;">
        <div class="modal-content modal-card border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-danger flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2;">
                        <i class="bi bi-calculator-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">BORANG 11b - PENILAIAN KEUPAYAAN PETENDER</h5>
                        <p class="text-secondary small mb-0">Pengiraan markah penilaian bagi tender kerja penyelenggaraan mekanikal / elektrikal.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill font-monospace" id="b11ModalRefBadge">
                        NO. RUJUKAN PETENDER : V001
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4">
                
                <div class="table-modern-wrapper mb-3">
                    <div class="table-responsive">
                        <table class="table-borang11-modern">
                            <thead>
                                <tr>
                                    <th style="width: 45%; text-align: left;" class="ps-3" rowspan="3">No. Rujukan Petender:</th>
                                    <th style="width: 10%;" rowspan="3">NILAI</th>
                                    <th style="width: 10%;" rowspan="3">MARKAH</th>
                                    <th style="width: 10%;" rowspan="3">WAJARAN</th>
                                    <th style="width: 11%;" rowspan="3">JUMLAH MARKAH</th>
                                    <th style="width: 14%;" colspan="2">MARKAH KELAYAKAN MINIMUM</th>
                                </tr>
                                <tr>
                                    <th style="width: 7%;">P.B.*</th>
                                    <th style="width: 7%;">P.B.P #</th>
                                </tr>
                                <tr>
                                    <th style="width: 7%; background-color: #5d708a; color: #ffffff;">50%</th>
                                    <th style="width: 7%; background-color: #5d708a; color: #ffffff;">50%</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- Section A --}}
                                <tr class="table-secondary">
                                    <td colspan="7" class="ps-3 fw-bold text-dark" style="background: #f1f5f9;">
                                        <span class="badge bg-danger me-1">A</span>
                                        <span>KEUPAYAAN KEWANGAN</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">A1 - Keupayaan Biayawan</div>
                                        <span class="extra-small text-muted d-block">Penilaian keupayaan biayawan petender</span>
                                    </td>
                                    <td class="text-center font-monospace" id="b11_a1_pct">250.00%</td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_a1_markah">100.00</td>
                                    <td class="text-center font-monospace text-center">45%</td>
                                    <td class="text-center font-monospace fw-bold text-primary" id="b11_a1_weighted">45.00</td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>

                                {{-- Section B --}}
                                <tr class="table-secondary">
                                    <td colspan="7" class="ps-3 fw-bold text-dark" style="background: #f1f5f9;">
                                        <span class="badge bg-danger me-1">B</span>
                                        <span>KEUPAYAAN TEKNIKAL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4" colspan="7">
                                        <div class="fw-bold text-dark">B1 - Pengalaman Kerja</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-5">B1.1 Jumlah Keseluruhan Kerja</td>
                                    <td class="text-center font-monospace" id="b11_b11_pct">96.00%</td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_b11_markah">96.00</td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>
                                <tr>
                                    <td class="ps-5">B1.2 Nilai Kerja Terbesar</td>
                                    <td class="text-center font-monospace" id="b11_b12_pct">36.00%</td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_b12_markah">36.00</td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>
                                <tr class="bg-light-subtle">
                                    <td class="ps-5 fw-bold text-dark">Markah Purata (B1)</td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_b1_purata">66.00</td>
                                    <td class="text-center font-monospace text-muted text-center">40%</td>
                                    <td class="text-center font-monospace fw-bold text-primary" id="b11_b1_weighted">26.40</td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>

                                <tr>
                                    <td class="ps-4" colspan="7">
                                        <div class="fw-bold text-dark">B2 - Kakitangan Teknikal</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-5">B2.1 Bilangan Kakitangan Teknikal</td>
                                    <td class="text-center font-monospace" id="b11_b21_pct">75.8%</td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_b21_markah">56.85</td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>
                                <tr>
                                    <td class="ps-5">B2.2 Pengalaman Kakitangan Teknikal</td>
                                    <td class="text-center font-monospace" id="b11_b22_val">6.5 Tahun</td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_b22_markah">48.75</td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>
                                <tr class="bg-light-subtle">
                                    <td class="ps-5 fw-bold text-dark">Markah Purata (B2)</td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace fw-semibold" id="b11_b2_purata">52.80</td>
                                    <td class="text-center font-monospace text-muted text-center">15%</td>
                                    <td class="text-center font-monospace fw-bold text-primary" id="b11_b2_weighted">7.92</td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace text-muted" style="background: #eceaeaff;"></td>
                                </tr>

                                {{-- Total Marks Row --}}
                                <tr class="table-dark text-white fw-bold">
                                    <td colspan="4" class="ps-3 text-uppercase">
                                        MARKAH KESELURUHAN PENILAIAN KEUPAYAAN PETENDER
                                    </td>
                                    <td class="text-center font-monospace fs-6 text-warning fw-bold" id="b11_total_markah" style="background: #eceaeaff;"></td>
                                    <td class="text-center font-monospace" id="b11_markah_pb">79.32</td>
                                    <td class="text-center font-monospace" id="b11_markah_pbp">-</td>
                                </tr>

                                {{-- Final Status Row --}}
                                <tr>
                                    <td colspan="4" class="ps-3 fw-bold text-dark">
                                        KEPUTUSAN PENILAIAN PERINGKAT KEDUA (LULUS/GAGAL)
                                    </td>
                                    <td colspan="3" class="text-center">
                                        <span class="badge bg-success text-white px-4 py-2 rounded-pill fw-bold fs-6" id="b11ModalResultBadge">
                                            <i class="bi bi-check-circle me-1"></i>LULUS
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Legend Footer --}}
                <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between text-muted extra-small">
                    <div>
                        <span class="fw-bold text-dark me-2">* P.B.</span> Petender Berpengalaman
                        <span class="mx-3">&bull;</span>
                        <span class="fw-bold text-dark me-2"># P.B.P</span> Petender Belum Berpengalaman
                    </div>
                    <div class="font-monospace">Sistem E-Penilaian Suksel 3.0</div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-simpan px-4 rounded-3" onclick="simpanPenilaianModalB11()">
                    <i class="bi bi-check2-circle me-1"></i>Sahkan &amp; Simpan
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const b11VendorDataMap = @json($b11List);
    let currentB11VendorId = null;

    function openB11DetailModal(vendorId) {
        currentB11VendorId = vendorId;
        const vData = b11VendorDataMap[vendorId] || {};
        const kodText = vData.kod_pembekal || (typeof vData === 'object' ? (vData.kod || vendorId) : vendorId);

        const refBadge = document.getElementById('b11ModalRefBadge');
        if (refBadge) {
            refBadge.innerText = 'NO. RUJUKAN PETENDER : ' + kodText;
        }

        const elA1Pct = document.getElementById('b11_a1_pct');
        let a1Val = 0;
        if (elA1Pct) {
            elA1Pct.innerText = vData.b11_a1_pct || '250.00%';
            a1Val = parseFloat(vData.b11_a1_pct) || 0;
        }

        let a1M = 0;
        if (a1Val <= 0) a1M = 0;
        else if (a1Val >= 250) a1M = 100;
        else a1M = a1Val / 2.5;

        const elA1Markah = document.getElementById('b11_a1_markah');
        if (elA1Markah) {
            elA1Markah.innerText = vData.b11_a1_markah || a1M.toFixed(2);
        }

        const a1W = a1M * 0.45;
        const elA1Weighted = document.getElementById('b11_a1_weighted');
        if (elA1Weighted) {
            elA1Weighted.innerText = vData.b11_a1_weighted || a1W.toFixed(2);
        }

        const elB11Pct = document.getElementById('b11_b11_pct');
        if (elB11Pct) {
            elB11Pct.innerText = vData.b11_keseluruhan_pct || '96.00%';
        }

        let m11 = 0;
        const elB11Markah = document.getElementById('b11_b11_markah');
        if (elB11Markah) {
            let pctNum = parseFloat(vData.b11_keseluruhan_pct) || 0;
            if (pctNum <= 0) m11 = 0;
            else if (pctNum >= 100) m11 = 100;
            else m11 = pctNum * 1;
            elB11Markah.innerText = vData.b11_b11_markah || m11.toFixed(2);
        }

        const elB12Pct = document.getElementById('b11_b12_pct');
        if (elB12Pct) {
            elB12Pct.innerText = vData.b11_b12_pct || '36.00%';
        }

        let m12 = 0;
        const elB12Markah = document.getElementById('b11_b12_markah');
        if (elB12Markah) {
            let pctNum = parseFloat(vData.b11_b12_pct) || 0;
            if (pctNum <= 0) m12 = 0;
            else if (pctNum >= 100) m12 = 100;
            else m12 = pctNum * 1;
            elB12Markah.innerText = vData.b11_b12_markah || m12.toFixed(2);
        }

        const b1Avg = (m11 + m12) / 2;
        const b1W = b1Avg * 0.40;

        const elB1Purata = document.getElementById('b11_b1_purata');
        if (elB1Purata) {
            elB1Purata.innerText = vData.b11_b1_purata || b1Avg.toFixed(2);
        }

        const elB1Weighted = document.getElementById('b11_b1_weighted');
        if (elB1Weighted) {
            elB1Weighted.innerText = vData.b11_b1_weighted || b1W.toFixed(2);
        }

        const elB21Pct = document.getElementById('b11_b21_pct');
        let ePct = 0;
        if (elB21Pct) {
            elB21Pct.innerText = vData.b10_e_pct || '75.8%';
            ePct = parseFloat(vData.b10_e_pct) || 0;
        }

        let m21 = 0;
        if (ePct <= 0) m21 = 0;
        else if (ePct >= 133) m21 = 100;
        else m21 = ePct * 0.75;

        const elB21Markah = document.getElementById('b11_b21_markah');
        if (elB21Markah) {
            elB21Markah.innerText = vData.b11_b21_markah || m21.toFixed(2);
        }

        const elB22Val = document.getElementById('b11_b22_val');
        let iVal = 0;
        if (elB22Val) {
            elB22Val.innerText = vData.b10_i_disp || '6.5 Tahun';
            iVal = parseFloat(vData.b10_i_disp) || 0;
        }

        let m22 = 0;
        const targetUpperI = 13.3333333333;
        if (iVal <= 0.5) m22 = 0;
        else if (iVal >= targetUpperI) m22 = 100;
        else m22 = iVal * 7.5;

        const elB22Markah = document.getElementById('b11_b22_markah');
        if (elB22Markah) {
            elB22Markah.innerText = vData.b11_b22_markah || m22.toFixed(2);
        }

        const b2Avg = (m21 + m22) / 2;
        const b2W = b2Avg * 0.15;

        const elB2Purata = document.getElementById('b11_b2_purata');
        if (elB2Purata) {
            elB2Purata.innerText = vData.b11_b2_purata || b2Avg.toFixed(2);
        }

        const elB2Weighted = document.getElementById('b11_b2_weighted');
        if (elB2Weighted) {
            elB2Weighted.innerText = vData.b11_b2_weighted || b2W.toFixed(2);
        }

        // Total Overall Marks = A1 (45%) + B1 (40%) + B2 (15%)
        const grandTotal = a1W + b1W + b2W;
        const finalScoreStr = vData.jumlah_mark || grandTotal.toFixed(2);
        const elTotalMarkah = document.getElementById('b11_total_markah');
        if (elTotalMarkah) {
            elTotalMarkah.innerText = '';
        }

        const elPbMarkah = document.getElementById('b11_markah_pb');
        const elPbpMarkah = document.getElementById('b11_markah_pbp');
        const hasPengalaman = vData.has_pengalaman !== undefined ? vData.has_pengalaman : true;

        if (elPbMarkah) {
            elPbMarkah.innerText = hasPengalaman ? finalScoreStr : '-';
        }
        if (elPbpMarkah) {
            elPbpMarkah.innerText = !hasPengalaman ? finalScoreStr : '-';
        }

        const resBadge = document.getElementById('b11ModalResultBadge');
        if (resBadge) {
            const finalScore = parseFloat(vData.jumlah_mark || grandTotal) || 0;
            const kep = (finalScore >= 50.0) ? 'LULUS' : 'GAGAL';
            if (kep === 'LULUS') {
                resBadge.className = 'badge bg-success text-white px-4 py-2 rounded-pill fw-bold fs-6';
                resBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i>LULUS';
            } else {
                resBadge.className = 'badge bg-danger text-white px-4 py-2 rounded-pill fw-bold fs-6';
                resBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i>GAGAL';
            }
        }

        const modalEl = document.getElementById('b11DetailModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function simpanPenilaianModalB11() {
        if (currentB11VendorId) {
            const btn = document.getElementById('btnActionB11_' + currentB11VendorId);
            if (btn) {
                btn.className = 'btn-action-dinilai';
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Telah Dinilai';
            }

            const statusContainer = document.getElementById('statusContainerB11_' + currentB11VendorId);
            if (statusContainer) {
                statusContainer.innerHTML = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill"><i class="bi bi-check-circle me-1"></i>Telah Dinilai</span>';
            }

            if (b11VendorDataMap[currentB11VendorId]) {
                b11VendorDataMap[currentB11VendorId].is_evaluated = true;
            }
        }

        const modalEl = document.getElementById('b11DetailModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

        showSuccessModal('Maklumat penilaian keupayaan petender (Borang 11b) telah disahkan.');
    }

    function checkB11CompletionState() {
        const btn = document.getElementById('btnSimpanMuktamadB11');
        if (!btn) return;

        const isReadOnly = {{ json_encode($readOnly ?? false) }};
        if (isReadOnly) {
            btn.disabled = true;
            return;
        }
        btn.disabled = false;
    }

    function simpanB11Main() {
        const chk = document.getElementById('chkSahB11');
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

        fetch('{{ route("penilaianKewanganKerja.borang11.simpanMuktamad", $tenderIdentifier) }}', {
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
                    text: data.message || 'Maklumat Borang 11 telah berjaya disahkan.',
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
        Swal.fire({
            icon: 'success',
            title: 'Berjaya Disimpan!',
            text: msg || 'Maklumat borang 11b telah berjaya disimpan ke dalam sistem.',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#19c1a7',
            customClass: {
                popup: 'rounded-4 p-4',
                confirmButton: 'px-4 py-2 rounded-3'
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        checkB11CompletionState();
    });
</script>
@endsection