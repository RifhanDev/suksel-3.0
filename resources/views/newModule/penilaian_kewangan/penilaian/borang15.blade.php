@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b15-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b15-header-banner {
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

    /* Table Styling from Borang 6 */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .table-modern-b1 {
        margin-bottom: 0;
        width: 100%;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b1 thead th {
        background: #1e293b;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.675rem;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        padding: 0.55rem 0.25rem;
        border-bottom: none;
        white-space: normal;
        vertical-align: middle;
        text-align: center;
        line-height: 1.25;
        word-break: break-word;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table-modern-b1 tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern-b1 tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern-b1 tbody td {
        padding: 0.5rem 0.3rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.775rem;
        color: #334155;
        word-wrap: break-word;
    }

    .notes-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
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
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no') ?: ($tender_no ?? '');
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $tenderParam) : $tenderParam;
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', $tenderParam) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $displayVendors = !empty($b15VendorSummary) ? array_values($b15VendorSummary) : (
        !empty($b6PassingVendors) ? $b6PassingVendors : (
            !empty($b8VendorSummary) ? array_values($b8VendorSummary) : []
        )
    );

    $fallbackRows = [
        ['bil'=>1,'ruj'=>'27/53','vendor_id'=>101,'nama'=>'MZ PROBINA','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,798,852.00','bwam'=>'-18.24%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'4,798,852.00','kerja'=>'Memuaskan','keputusan'=>'Lulus'],
        ['bil'=>2,'ruj'=>'34/53','vendor_id'=>102,'nama'=>'SIERRA AQUATECH SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,830,689.40','bwam'=>'-17.64%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'4,830,689.40','kerja'=>'T.K.S','keputusan'=>'Lulus'],
        ['bil'=>3,'ruj'=>'24/53','vendor_id'=>103,'nama'=>'RMS BINJAYA SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,864,594.40','bwam'=>'-17.00%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'4,864,594.40','kerja'=>'Memuaskan','keputusan'=>'Lulus'],
        ['bil'=>4,'ruj'=>'37/53','vendor_id'=>104,'nama'=>'ZEFHILL (M) SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,956,328.00','bwam'=>'-15.28%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'4,956,328.00','kerja'=>'-','keputusan'=>'Lulus'],
        ['bil'=>5,'ruj'=>'30/53','vendor_id'=>105,'nama'=>'YAKIN TELUS SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,980,824.00','bwam'=>'-14.82%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'4,980,824.00','kerja'=>'T.K.S','keputusan'=>'Lulus'],
        ['bil'=>6,'ruj'=>'32/53','vendor_id'=>106,'nama'=>'ARMADA KASIH SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,010,773.00','bwam'=>'-14.25%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'5,010,773.00','kerja'=>'T.K.S','keputusan'=>'Lulus'],
        ['bil'=>7,'ruj'=>'2/53','vendor_id'=>107,'nama'=>'UER VENTURES SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,018,400.10','bwam'=>'-14.11%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'5,018,400.10','kerja'=>'Memuaskan','keputusan'=>'Lulus'],
        ['bil'=>8,'ruj'=>'7/53','vendor_id'=>108,'nama'=>'JUJUR PERANGSANG SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,050,444.00','bwam'=>'-13.51%','tempoh'=>'104','sempurna'=>'✓ Sempurna','dok'=>'Cukup','modal'=>'5,050,444.00','kerja'=>'T.K.S','keputusan'=>'Lulus'],
    ];

    $renderList = count($displayVendors) > 0 ? $displayVendors : $fallbackRows;
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 15</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b15-card mb-4">
        <div class="b15-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Akhir</span>
                    @if(!empty($readOnly))
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 15 - Keputusan &amp; Pengesyoran Penilaian Tender</h3>
                <p class="text-white-50 mb-0 small">Ringkasan laporan penilaian tender, perincian peruntukan, anggaran jabatan, harga cut-off, dan pengesyoran petender lulus.</p>
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
                        <div class="info-item-value text-danger font-monospace">{{ $no_tender_display ?? ($tenderParam ?: '-') }}</div>
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
                        <div class="info-item-value text-dark">{{ $ptj_display ?? 'JABATAN PENGAIRAN DAN SALIRAN' }}</div>
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
                                {{ $status_label ?? 'Dalam Penilaian Akhir' }}
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
                        <div class="info-item-value text-dark font-monospace">{{ $sah_laku_tamat ?? '17/01/2022' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Parameter & Tender Summary Meta Card --}}
    <div class="b15-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2 d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width: 38px; height: 38px;">
                    <i class="bi bi-file-earmark-text fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Maklumat &amp; Parameter Ringkasan Tender</h6>
                    <span class="text-muted small">Perincian maklumat tarikh, peruntukan dan nilaian kewangan tender</span>
                </div>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill font-monospace fw-semibold" style="font-size: 0.75rem;">
                <i class="bi bi-info-circle me-1 text-primary"></i>Ringkasan
            </span>
        </div>

        <div class="row g-4">
            {{-- Column 1 --}}
            <div class="col-12 col-lg-6 border-lg-end">
                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Jenis Tender</span>
                        <span class="fw-bold text-dark font-monospace small">Konvensional</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Tarikh Tender Diiklankan</span>
                        <span class="fw-bold text-dark font-monospace small">07/10/2024</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Tarikh Tender Ditutup</span>
                        <span class="fw-bold text-dark font-monospace small">28/10/2024</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Tarikh Luput Sahlaku Tender</span>
                        <span class="fw-bold text-dark font-monospace small">26/01/2025</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Tarikh Lawatan Tapak</span>
                        <span class="fw-bold text-dark font-monospace small">07/11/2024</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Tempoh Siap Maksimum</span>
                        <span class="fw-bold text-dark font-monospace small">104 Minggu</span>
                    </div>
                </div>
            </div>

            {{-- Column 2 --}}
            <div class="col-12 col-lg-6">
                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Peruntukan (PDA)</span>
                        <span class="fw-bold text-dark font-monospace small">-</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Anggaran Jabatan</span>
                        <span class="fw-bold text-primary font-monospace small">RM 6,120,000.00</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Harga Cut-Off</span>
                        <span class="fw-bold text-dark font-monospace small">RM 5,279,000.00</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                        <span class="text-muted small fw-semibold">Harga Adjusted Mean</span>
                        <span class="fw-bold text-dark font-monospace small">RM 5,321,181.14</span>
                    </div>
                    <div class="p-2 rounded-2 bg-light border">
                        <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Modal Mudah Cair Terlaras</div>
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <span class="text-secondary extra-small" style="font-size: 0.775rem;">i) Bawah Cut-Off (5%)</span>
                            <span class="fw-bold text-dark font-monospace small">RM 283,600.00</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-secondary extra-small" style="font-size: 0.775rem;">ii) Atas Cut-Off (3%)</span>
                            <span class="fw-bold text-dark font-monospace small">RM 170,160.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section Card: Table Results --}}
    <div class="b15-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-journal-check text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Jadual Keputusan &amp; Pengesyoran Penilaian Tender</h5>
                    <p class="text-secondary small mb-0">Ringkasan rumusan penilaian petender, kesempurnaan dokumen, status keputusan, dan pengesyoran petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-success ms-auto">
                <i class="bi bi-check-circle me-1"></i>{{ count($renderList) }} Petender Layak Dinilai
            </span>
        </div>

        {{-- Table --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-modern-b1 align-middle">
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 19%;">
                        <col style="width: 5%;">
                        <col style="width: 8%;">
                        <col style="width: 5%;">
                        <col style="width: 10%;">
                        <col style="width: 6%;">
                        <col style="width: 7%;">
                        <col style="width: 8%;">
                        <col style="width: 8.5%;">
                        <col style="width: 9.5%;">
                        <col style="width: 6.5%;">
                        <col style="width: 5.5%;">
                        <col style="width: 7.5%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">BIL</th>
                            <th class="text-start ps-2">RUJUKAN TENDER</th>
                            <th class="text-center">GRED</th>
                            <th class="text-center">TARAF</th>
                            <th class="text-center">LOKASI</th>
                            <th class="text-center pe-2">HARGA (RM)</th>
                            <th class="text-center">% BWAM</th>
                            <th class="text-center">TEMPOH (MINGGU)</th>
                            <th class="text-center">KESEMPURNAAN TENDER</th>
                            <th class="text-center">KECUKUPAN DOKUMEN WAJIB</th>
                            <th class="text-center pe-2">MODAL MINIMUM (RM)</th>
                            <th class="text-center">KERJA SEMASA</th>
                            <th class="text-center">KEPUTUSAN</th>
                            <th class="text-center">PENGESYORAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($renderList as $idx => $r)
                            @php
                                $vId = $r['vendor_id'] ?? ($r['id'] ?? ($idx + 1));
                                $kodPembekal = $r['kod_pembekal'] ?? ($r['ruj'] ?? ('45/' . str_pad($idx+1, 2, '0', STR_PAD_LEFT)));
                                $vendorName = $r['vendor_name'] ?? ($r['nama'] ?? 'SYARIKAT PETENDER');
                                $hargaDisp = $r['harga_display'] ?? ($r['harga'] ?? (isset($r['harga_tawaran']) ? 'RM ' . number_format($r['harga_tawaran'], 2) : 'RM 0.00'));
                                $isChecked = !empty($r['is_recommended']);
                            @endphp
                            <tr>
                                <td class="text-center font-monospace fw-bold" style="background-color: #f8fafc; color: #475569; font-size: 0.75rem;">
                                    {{ $idx + 1 }}
                                </td>
                                <td class="ps-2">
                                    <div class="fw-bold font-monospace text-danger" style="font-size: 0.775rem;">{{ $kodPembekal }}</div>
                                    <div class="fw-bold text-dark" style="font-size: 0.775rem; line-height: 1.2;">{{ $vendorName }}</div>
                                </td>
                                <td class="text-center font-monospace">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-1.5 py-0.5 rounded-2" style="font-size: 0.7rem;">{{ $r['gred'] ?? 'G6' }}</span>
                                </td>
                                <td class="text-center extra-small" style="font-size: 0.7rem;">
                                    {{ $r['taraf'] ?? 'BUMIPUTERA' }}
                                </td>
                                <td class="text-center font-monospace" style="font-size: 0.75rem;">
                                    {{ $r['lokasi'] ?? '0' }}
                                </td>
                                <td class="text-end pe-2 font-monospace fw-bold text-dark" style="font-size: 0.775rem;">
                                    {{ $hargaDisp }}
                                </td>
                                <td class="text-center font-monospace" style="font-size: 0.75rem;">
                                    -
                                </td>
                                <td class="text-center font-monospace" style="font-size: 0.75rem;">
                                    {{ $r['tempoh'] ?? '104' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-1.5 py-0.5 rounded-pill" style="font-size: 0.7rem;">{{ $r['sempurna'] ?? '✓ Sempurna' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-1.5 py-0.5 rounded-pill" style="font-size: 0.7rem;">{{ $r['dok'] ?? 'Cukup' }}</span>
                                </td>
                                <td class="text-end pe-2 font-monospace" style="font-size: 0.75rem;">
                                    {{ $r['modal'] ?? $hargaDisp }}
                                </td>
                                <td class="text-center extra-small" style="font-size: 0.7rem;">
                                    {{ $r['kerja'] ?? 'Memuaskan' }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $kepText = trim($r['keputusan'] ?? 'Lulus');
                                        $isLulusVal = in_array(strtoupper($kepText), ['LULUS', 'PASS', 'SEMPURNA', 'MEMATUHI']);
                                    @endphp
                                    <span class="badge {{ $isLulusVal ? 'bg-success text-white' : 'bg-danger text-white' }} px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.725rem;">{{ $kepText }}</span>
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input chk-pengesyoran" type="checkbox" name="selected_vendors[]" value="{{ $vId }}" style="cursor: pointer; transform: scale(1.2);" {{ !empty($readOnly) ? 'disabled' : '' }} {{ $isChecked ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notes Box --}}
        <div class="notes-box mb-4">
            <div class="d-flex align-items-center gap-2 fw-bold mb-2" style="color: #92400e;">
                <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                <span>Petunjuk &amp; Pengesyoran Penilaian:</span>
            </div>
            <ul class="list-unstyled mb-0 small text-secondary">
                <li class="d-flex gap-2 mb-1">
                    <span class="fw-bold text-dark">• Pengesyoran</span>
                    <span>- Sila tandakan petender yang disyorkan untuk meneruskan proses seterusnya.</span>
                </li>
                <li class="d-flex gap-2">
                    <span class="fw-bold text-dark">• Petender Tidak Disyorkan</span>
                    <span>- Petender yang tidak ditandakan akan dimuktamadkan sebagai tidak disyorkan.</span>
                </li>
            </ul>
        </div>

        {{-- Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border mb-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" {{ !empty($readOnly) ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                            Saya mengesahkan keputusan &amp; pengesyoran penilaian tender ini telah disemak secara teliti.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnHantarB15" onclick="hantarB15()">
                            <i class="bi bi-send me-1"></i>Hantar Keputusan Penilaian
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const tenderNo = "{{ $tenderParam }}";

    function hantarB15() {
        const checkedVendors = document.querySelectorAll('.chk-pengesyoran:checked');
        if (checkedVendors.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Pengesyoran Diperlukan',
                html: '<p class="mb-1 text-secondary fs-6">Sila pilih <strong>sekurang-kurangnya satu petender</strong> yang disyorkan untuk meneruskan proses.</p>',
                confirmButtonText: 'Faham',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-4 shadow',
                    confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                }
            });
            return;
        }

        const chkSah = document.getElementById('chkSah');
        if (chkSah && !chkSah.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Pengesahan Diperlukan',
                html: '<p class="mb-1 text-secondary fs-6">Sila tandakan <strong>kotak pengesahan</strong> terlebih dahulu sebelum menghantar keputusan.</p>',
                confirmButtonText: 'Faham',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-4 shadow',
                    confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                }
            });
            return;
        }

        const selectedVendorIds = Array.from(checkedVendors).map(cb => cb.value);

        const btnHantar = document.getElementById('btnHantarB15');
        btnHantar.disabled = true;
        btnHantar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghantar...';

        fetch(`/penilaian-kewangan-kerja/${encodeURIComponent(tenderNo)}/borang/borang15/simpan-muktamad`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                chk_sah: 1,
                selected_vendors: selectedVendorIds
            })
        })
        .then(res => res.json())
        .then(data => {
            btnHantar.disabled = false;
            btnHantar.innerHTML = '<i class="bi bi-send me-1"></i>Hantar Keputusan Penilaian';

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tahniah!',
                    text: data.message || 'Penilaian Kewangan telah berjaya diselesaikan dan tender telah diteruskan ke proses seterusnya.',
                    confirmButtonText: 'Tutup & Kembali Ke Senarai',
                    confirmButtonColor: '#047857',
                    customClass: {
                        popup: 'rounded-4 shadow',
                        confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                    }
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = "{{ route('penilaianKewangan') }}";
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat!',
                    text: data.message || 'Gagal menghantar keputusan Borang 15.',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(err => {
            btnHantar.disabled = false;
            btnHantar.innerHTML = '<i class="bi bi-send me-1"></i>Hantar Keputusan Penilaian';
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Ralat Sistem!',
                text: 'Berlaku masalah semasa berhubung dengan pelayan.',
                confirmButtonColor: '#dc2626'
            });
        });
    }
</script>
@endsection