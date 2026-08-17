@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b3-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b3-header-banner {
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

    /* Sub Navigation Tabs */
    .sub-nav-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .sub-nav-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
    }

    .sub-nav-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .sub-nav-btn.active {
        background: var(--sg-red);
        color: #ffffff;
        border-color: var(--sg-red);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
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

    .table-borang3-modern th.subhead-level2 .formula-tag {
        background: rgba(0, 0, 0, 0.08);
        color: #3f3f3f;
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
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
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
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: ($tender_no ?? '')) : ($tender_no ?? '');
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
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 3</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b3-card mb-4">
        <div class="b3-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Pertama</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 3 - Analisa Kecukupan Modal</h3>
                <p class="text-white-50 mb-0 small">Analisis modal pusingan, penyata bank, aset cair & had modal minimum (3% nilai Kerja Pembina).</p>
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
                        <div class="info-item-value text-danger font-monospace fw-bold" style="font-size: 0.95rem;">{{ $no_tender_display ?? ($tender->no_tender ?? $tender->ref_number ?? '-') }}</div>
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
                        <div class="info-item-value text-dark fw-bold" style="font-size: 0.88rem;">{{ $ptj_display ?? ($tender->tenderer->name ?? '-') }}</div>
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
                                {{ $status_label ?? 'Menunggu Pengesahan' }}
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

    {{-- Sub Navigation Tabs for Borang 3 Components --}}
    <div class="sub-nav-tabs">
        <button type="button" class="sub-nav-btn active" onclick="switchBorang3Tab('ringkasanModal', this)">
            <i class="bi bi-calculator"></i>Borang 3 (Ringkasan Modal)
        </button>
        <button type="button" class="sub-nav-btn" onclick="switchBorang3Tab('lembaranImbangan', this)">
            <i class="bi bi-journal-text"></i>Lembaran Imbangan
        </button>
        <button type="button" class="sub-nav-btn" onclick="switchBorang3Tab('akaunBank', this)">
            <i class="bi bi-bank"></i>Penyata / Akaun Bank
        </button>
        <button type="button" class="sub-nav-btn" onclick="switchBorang3Tab('bonSaham', this)">
            <i class="bi bi-cash-coin"></i>Bon & Saham
        </button>
    </div>

    {{-- TAB 1: Ringkasan Modal --}}
    <div id="tab-ringkasanModal" class="borang3-tab-pane">
        <div class="b3-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle p-2 rounded-2 me-3">
                        <i class="bi bi-calculator text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Jadual Analisa Kecukupan Modal</h5>
                        <p class="text-secondary small mb-0">Perbandingan Nisbah Modal Pusingan vs Modal Minimum yang Diperlukan (3% Kerja Pembina).</p>
                    </div>
                </div>
                <span class="section-badge-pill-primary ms-auto">
                    <i class="bi bi-graph-up me-1"></i>Nisbah Kewangan
                </span>
            </div>

            <div class="table-modern-wrapper mb-4">
                <div class="table-responsive">
                    <table class="table-borang3-modern">
                        <thead>
                            <tr>
                                <th rowspan="3" style="width: 100px;">
                                    Ruj. Petender<br><span class="formula-tag">(a)</span>
                                </th>
                                <th colspan="8" class="subhead-main">
                                    ANALISA KECUKUPAN MODAL
                                </th>
                                <th colspan="5" class="subhead-main">
                                    Modal Minimum Diperlukan (3% dari Nilai Kerja Pembina)
                                </th>
                            </tr>

                            <tr>
                                {{-- ANALISA KECUKUPAN MODAL --}}
                                <th colspan="3" class="subhead-level2">Lembaran Imbangan</th>
                                <th colspan="2" class="subhead-level2">Penyata Bulanan Bank</th>
                                <th rowspan="2" class="subhead-level2">
                                    Wang Dalam Tangan Semasa<br><span class="formula-tag">Nilai Positif (g)</span>
                                </th>
                                <th rowspan="2" class="subhead-level2">
                                    Jumlah Modal<br><span class="formula-tag">(k)=(f)+(g)+(h)+(i)+(j)</span>
                                </th>
                                <th rowspan="2" class="subhead-level2">
                                    Mudah Cair Boleh Guna<br><span class="formula-tag">(m)=(j)-(k)</span>
                                </th>

                                {{-- Modal Minimum Diperlukan --}}
                                <th rowspan="2" class="subhead-level2">
                                    Borang CA 2 / Deposit / Saham<br><span class="formula-tag">(h)</span>
                                </th>
                                <th rowspan="2" class="subhead-level2">
                                    Aset Cair<br><span class="formula-tag">(i) = (h) atau (g)+(h)</span>
                                </th>
                                <th rowspan="2" class="subhead-level2">
                                    Borang CA 1 (Kredit)<br><span class="formula-tag">(j)</span>
                                </th>
                                <th rowspan="2" class="subhead-level2">
                                    Jumlah Modal<br><span class="formula-tag">(k)=(f)+(g)+(h)+(i)+(j)</span>
                                </th>
                                <th rowspan="2" class="subhead-level2">
                                    Mudah Cair Boleh Guna<br><span class="formula-tag">(m)=(j)-(k)</span>
                                </th>
                            </tr>

                            <tr>
                                {{-- Lembaran Imbangan --}}
                                <th class="subhead-level2">Aset Semasa<br><span class="formula-tag">(b)</span></th>
                                <th class="subhead-level2">Liabiliti Semasa<br><span class="formula-tag">(c)</span></th>
                                <th class="subhead-level2">Modal Pusingan<br><span class="formula-tag">(d)=(b)-(c)</span></th>

                                {{-- Penyata Bulanan Bank --}}
                                <th class="subhead-level2">Baki 3 Bulan<br><span class="formula-tag">(e)</span></th>
                                <th class="subhead-level2">Purata 3 Bulan<br><span class="formula-tag">(f)=(e)/3</span></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $b3DataMap = $b3VendorData ?? [];
                            @endphp
                            @forelse($participants as $idx => $p)
                                @php
                                    $vId = $p->vendor_id;
                                    $vData = $b3DataMap[$vId] ?? [];
                                    $ruj = $p->kod_pembekal ?: ($loop->iteration . '/' . count($participants));
                                    $mudahCair = $vData['mudah_cair_m'] ?? 0;
                                    $ca1Kredit = $vData['ca1_kredit_j'] ?? 0;
                                @endphp
                                <tr>
                                    {{-- (a) Ruj. Petender --}}
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">
                                        {{ $ruj }}
                                    </td>

                                    {{-- (b) Aset Semasa --}}
                                    <td class="text-end font-monospace">{{ number_format($vData['aset_semasa'] ?? 0, 2) }}</td>

                                    {{-- (c) Liabiliti Semasa --}}
                                    <td class="text-end font-monospace">{{ number_format($vData['liabiliti_semasa'] ?? 0, 2) }}</td>

                                    {{-- (d) Modal Pusingan = (b) - (c) --}}
                                    <td class="text-end font-monospace fw-bold text-dark">{{ number_format($vData['modal_pusingan'] ?? 0, 2) }}</td>

                                    {{-- (e) Baki 3 Bulan --}}
                                    <td class="text-end font-monospace">{{ number_format($vData['baki_3_bulan'] ?? 0, 2) }}</td>

                                    {{-- (f) Purata 3 Bulan = (e)/3 --}}
                                    <td class="text-end font-monospace">{{ number_format($vData['purata_3_bulan'] ?? 0, 2) }}</td>

                                    {{-- (g) Wang Dalam Tangan Semasa --}}
                                    <td class="text-end font-monospace text-success fw-semibold">{{ number_format($vData['wang_tangan_g'] ?? 0, 2) }}</td>

                                    {{-- (k) Jumlah Modal --}}
                                    <td class="text-end font-monospace fw-bold">{{ number_format($vData['jumlah_modal_k'] ?? 0, 2) }}</td>

                                    {{-- (m) Mudah Cair Boleh Guna (Left Section) --}}
                                    <td class="text-end font-monospace text-muted">{{ number_format($mudahCair, 2) }}</td>

                                    {{-- (h) Borang CA 2 / Deposit / Saham --}}
                                    <td class="text-end font-monospace">{{ number_format($vData['bon_saham_h'] ?? 0, 2) }}</td>

                                    {{-- (i) Aset Cair = (h) atau (g)+(h) --}}
                                    <td class="text-end font-monospace">{{ number_format($vData['aset_cair_i'] ?? 0, 2) }}</td>

                                    {{-- (j) Borang CA 1 (Kredit) --}}
                                    <td class="text-end font-monospace">
                                        @if($ca1Kredit > 0)
                                            {{ number_format($ca1Kredit, 2) }}
                                        @else
                                            <span class="text-danger fw-bold" title="Tiada pengesahan kredit">0.00</span>
                                        @endif
                                    </td>

                                    {{-- (k) Jumlah Modal --}}
                                    <td class="text-end font-monospace fw-bold">{{ number_format($vData['jumlah_modal_k'] ?? 0, 2) }}</td>

                                    {{-- (m) Mudah Cair Boleh Guna vs 3% Min Requirement --}}
                                    <td class="text-end font-monospace fw-bold {{ $mudahCair >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($mudahCair, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center text-muted py-4">Tiada petender ditemui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Form Actions & Confirmation Box --}}
            <div class="confirmation-box">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-12 col-md-8">
                        <div class="form-check p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="sahPenilaian">
                            <label class="form-check-label fw-semibold text-dark small" for="sahPenilaian">
                                Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <div class="d-flex justify-content-md-end gap-2">
                            <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="button" id="btnSimpanMuktamad" class="btn btn-submit-danger px-4 rounded-3" onclick="showSuccessModal()">
                                <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- TAB 2: Lembaran Imbangan --}}
    <div id="tab-lembaranImbangan" class="borang3-tab-pane" style="display: none;">
        <div class="b3-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle p-2 rounded-2 me-3">
                        <i class="bi bi-journal-text text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Maklumat Lembaran Imbangan (Balance Sheet)</h5>
                        <p class="text-secondary small mb-0">Maklumat kewangan daripada Lembaran Imbangan dan Borang CA / Surat Bank petender.</p>
                    </div>
                </div>
                <span class="section-badge-pill-primary ms-auto">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Lembaran Imbangan
                </span>
            </div>

            <div class="table-modern-wrapper mb-4">
                <div class="table-responsive">
                    <table class="table-borang3-modern">
                        <thead>
                            <tr>
                                <th colspan="8">ANALISA KECUKUPAN MODAL</th>
                            </tr>
                            <tr>
                                <th colspan="6" class="subhead-main">
                                    MAKLUMAT DARI LEMBARAN IMBANGAN (BALANCE SHEET)
                                </th>
                                <th colspan="2" class="subhead-main">
                                    BORANG CA / SURAT BANK
                                </th>
                            </tr>
                            <tr>
                                <th class="subhead-main" style="width: 100px;">Ruj. Petender</th>
                                <th class="subhead-level2">Aset Tetap</th>
                                <th class="subhead-level2">Aset Semasa</th>
                                <th class="subhead-level2">Liabiliti Semasa</th>
                                <th class="subhead-level2">Long Term / Liabiliti Tetap</th>
                                <th class="subhead-level2">Wang Tunai Dalam Tangan</th>
                                <th class="subhead-level2">Baki Kemudahan Kredit</th>
                                <th class="subhead-level2">Pinjaman Bank Yang Akan Diluluskan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $b3DataMap = $b3VendorData ?? [];
                            @endphp
                            @forelse($participants as $idx => $p)
                                @php
                                    $vId = $p->vendor_id;
                                    $vData = $b3DataMap[$vId] ?? [];
                                    $ruj = $p->kod_pembekal ?: ($loop->iteration . '/' . count($participants));
                                @endphp
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">
                                        {{ $ruj }}
                                    </td>
                                    <td class="text-end font-monospace">{{ number_format($vData['aset_tetap'] ?? 0, 2) }}</td>
                                    <td class="text-end font-monospace">{{ number_format($vData['aset_semasa'] ?? 0, 2) }}</td>
                                    <td class="text-end font-monospace">{{ number_format($vData['liabiliti_semasa'] ?? 0, 2) }}</td>
                                    <td class="text-end font-monospace">{{ number_format($vData['liabiliti_tetap'] ?? 0, 2) }}</td>
                                    <td class="text-end font-monospace">{{ number_format($vData['wang_tunai'] ?? 0, 2) }}</td>
                                    <td class="text-end font-monospace">{{ number_format($vData['baki_kredit'] ?? 0, 2) }}</td>
                                    <td class="text-end font-monospace text-danger fw-bold" title="Data tiada sumber dalam sistem">0.00</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Tiada petender ditemui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: Penyata / Akaun Bank --}}
    <div id="tab-akaunBank" class="borang3-tab-pane" style="display: none;">
        <div class="b3-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle p-2 rounded-2 me-3">
                        <i class="bi bi-bank text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Maklumat Baki Akaun Bank Bagi 3 Bulan Lepas</h5>
                        <p class="text-secondary small mb-0">Penyata baki akaun bank petender mengikut bulan dan institusi perbankan.</p>
                    </div>
                </div>
                <span class="section-badge-pill-primary ms-auto">
                    <i class="bi bi-wallet2 me-1"></i>Penyata / Akaun Bank
                </span>
            </div>

            <div class="table-modern-wrapper mb-4">
                <div class="table-responsive">
                    <table class="table-borang3-modern">
                        <thead>
                            <tr>
                                <th colspan="11">MAKLUMAT BAKI AKAUN BANK BAGI 3 BULAN LEPAS</th>
                            </tr>
                            <tr>
                                <th class="subhead-main" style="width: 100px;">Ruj.<br>Petender</th>
                                <th class="subhead-level2" style="width: 90px;">Bulan</th>

                                <th class="subhead-level2">Akaun 1</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2">Akaun 2</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2">Akaun 3</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2">Akaun 4</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2" style="width: 110px;">Jumlah<br>Besar</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $b3DataMap = $b3VendorData ?? [];
                                $mLabels = $b3MonthsLabels ?? ['Bulan 1', 'Bulan 2', 'Bulan 3'];
                            @endphp

                            @forelse($participants as $idx => $p)
                                @php
                                    $vId = $p->vendor_id;
                                    $vData = $b3DataMap[$vId] ?? [];
                                    $ruj = $p->kod_pembekal ?: ($loop->iteration . '/' . count($participants));
                                    $pbAccs = $vData['pb_accounts'] ?? [];
                                    $totalAccs = count($pbAccs);
                                    $pbGrandTotal = $vData['pb_grand_total'] ?? 0;
                                @endphp
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">
                                        {{ $ruj }}
                                        @if($totalAccs > 4)
                                            <div class="mt-1">
                                                <button type="button" class="btn btn-xs btn-outline-danger px-2 py-0.5 rounded-pill fw-semibold" style="font-size: 0.68rem;" onclick="openAllAkaunBankModal({{ $vId }})">
                                                    <i class="bi bi-eye me-1"></i>Papar Semua ({{ $totalAccs }})
                                                </button>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center small text-muted font-monospace">
                                        <div class="lh-sm">
                                            {{ $mLabels[0] ?? 'Bulan 1' }}<br>
                                            {{ $mLabels[1] ?? 'Bulan 2' }}<br>
                                            {{ $mLabels[2] ?? 'Bulan 3' }}
                                        </div>
                                    </td>

                                    @for($slot = 0; $slot < 4; $slot++)
                                        @php
                                            $acc = $pbAccs[$slot] ?? null;
                                        @endphp
                                        <td class="text-end font-monospace">
                                            @if($acc)
                                                <div class="lh-sm">
                                                    {{ number_format($acc['monthly_amounts'][0] ?? 0, 2) }}<br>
                                                    {{ number_format($acc['monthly_amounts'][1] ?? 0, 2) }}<br>
                                                    {{ number_format($acc['monthly_amounts'][2] ?? 0, 2) }}
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center small fw-semibold text-secondary">
                                            @if($acc)
                                                {!! nl2br(e($acc['bank_name'])) !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endfor

                                    <td class="text-end font-monospace fw-bold text-dark">
                                        {{ number_format($pbGrandTotal, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">Tiada petender ditemui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 4: Bon & Saham --}}
    <div id="tab-bonSaham" class="borang3-tab-pane" style="display: none;">
        <div class="b3-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle p-2 rounded-2 me-3">
                        <i class="bi bi-cash-coin text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Maklumat Bon & Saham</h5>
                        <p class="text-secondary small mb-0">Semakan dan penyemakan maklumat bon serta pemegang saham petender.</p>
                    </div>
                </div>
                <span class="section-badge-pill-primary ms-auto">
                    <i class="bi bi-bank2 me-1"></i>Bon & Saham
                </span>
            </div>

            <div class="table-modern-wrapper mb-4">
                <div class="table-responsive">
                    <table class="table-borang3-modern">
                        <thead>
                            <tr>
                                <th colspan="10">MAKLUMAT BON & SAHAM</th>
                            </tr>
                            <tr>
                                <th class="subhead-main" style="width: 100px;">Ruj. Petender</th>

                                <th class="subhead-level2">Akaun 1</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2">Akaun 2</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2">Akaun 3</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2">Akaun 4</th>
                                <th class="subhead-level2">Bank</th>

                                <th class="subhead-level2" style="width: 110px;">Jumlah<br>Besar</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $b3DataMap = $b3VendorData ?? [];
                            @endphp

                            @forelse($participants as $idx => $p)
                                @php
                                    $vId = $p->vendor_id;
                                    $vData = $b3DataMap[$vId] ?? [];
                                    $ruj = $p->kod_pembekal ?: ($loop->iteration . '/' . count($participants));
                                    $bsAccs = $vData['bs_accounts'] ?? [];
                                    $totalBs = count($bsAccs);
                                    $bsGrandTotal = $vData['bs_grand_total'] ?? 0;
                                @endphp
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">
                                        {{ $ruj }}
                                        @if($totalBs > 4)
                                            <div class="mt-1">
                                                <button type="button" class="btn btn-xs btn-outline-danger px-2 py-0.5 rounded-pill fw-semibold" style="font-size: 0.68rem;" onclick="openAllBonSahamModal({{ $vId }})">
                                                    <i class="bi bi-eye me-1"></i>Papar Semua ({{ $totalBs }})
                                                </button>
                                            </div>
                                        @endif
                                    </td>

                                    @for($slot = 0; $slot < 4; $slot++)
                                        @php
                                            $acc = $bsAccs[$slot] ?? null;
                                        @endphp
                                        <td class="text-end font-monospace">
                                            {{ $acc ? number_format($acc['jumlah_deposit'], 2) : '-' }}
                                        </td>
                                        <td class="text-center small fw-semibold text-secondary">
                                            {!! $acc ? nl2br(e($acc['bank_institusi'])) : '-' !!}
                                        </td>
                                    @endfor

                                    <td class="text-end font-monospace fw-bold text-dark">
                                        {{ number_format($bsGrandTotal, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Tiada petender ditemui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- =========================
    MODAL: AKAUN BANK (LIHAT SEMUA)
========================== --}}
<div class="modal fade" id="allAkaunBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-card p-3">
            <div class="modal-header border-bottom pb-2">
                <h6 class="modal-title fw-bold text-dark" id="allAkaunBankModalTitle">
                    <i class="bi bi-bank me-2 text-primary"></i>Kesemua Akaun Bank Petender
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle small mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 40px;">No.</th>
                                <th>Bank / Institusi</th>
                                <th><span id="mLabel1">Bulan 1</span> (RM)</th>
                                <th><span id="mLabel2">Bulan 2</span> (RM)</th>
                                <th><span id="mLabel3">Bulan 3</span> (RM)</th>
                                <th>Jumlah Akaun (RM)</th>
                            </tr>
                        </thead>
                        <tbody id="allAkaunBankTableBody">
                            {{-- Dynamically populated via JS --}}
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end">Jumlah Besar (RM):</td>
                                <td class="text-end font-monospace text-dark" id="allAkaunBankGrandTotal">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top pt-2">
                <button type="button" class="btn btn-sm btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- =========================
    MODAL: BON & SAHAM (LIHAT SEMUA)
========================== --}}
<div class="modal fade" id="allBonSahamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content modal-card p-3">
            <div class="modal-header border-bottom pb-2">
                <h6 class="modal-title fw-bold text-dark" id="allBonSahamModalTitle">
                    <i class="bi bi-cash-coin me-2 text-primary"></i>Kesemua Rekod Bon & Saham Petender
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle small mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 40px;">No.</th>
                                <th>Bank / Institusi</th>
                                <th>Jumlah Deposit / Saham (RM)</th>
                            </tr>
                        </thead>
                        <tbody id="allBonSahamTableBody">
                            {{-- Dynamically populated via JS --}}
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="2" class="text-end">Jumlah Besar (RM):</td>
                                <td class="text-end font-monospace text-dark" id="allBonSahamGrandTotal">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top pt-2">
                <button type="button" class="btn btn-sm btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function switchBorang3Tab(tabId, btn) {
        document.querySelectorAll('.sub-nav-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        document.querySelectorAll('.borang3-tab-pane').forEach(pane => pane.style.display = 'none');
        const targetPane = document.getElementById('tab-' + tabId);
        if (targetPane) targetPane.style.display = 'block';
    }
    window.switchBorang3Tab = switchBorang3Tab;

    function showSuccessModal(){
        const chk = document.getElementById('sahPenilaian');
        if (!chk || !chk.checked) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengesahan Diperlukan',
                    html: '<p class="mb-1 text-secondary fs-6">Sila tandakan <strong>kotak pengesahan</strong> terlebih dahulu sebelum meneruskan ke borang seterusnya dan melengkapkan Borang 3.</p>',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-4 shadow',
                        confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                    }
                });
            } else {
                alert('Sila tandakan kotak pengesahan terlebih dahulu sebelum meneruskan ke borang seterusnya dan melengkapkan Borang 3.');
            }
            return;
        }

        const btn = document.getElementById('btnSimpanMuktamad');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
        }

        fetch('{{ route('penilaianKewanganKerja.borang3.simpanMuktamad', $tenderIdentifier) }}', {
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
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';
            }

            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Borang 3 Disahkan!',
                        text: data.message || 'Borang 3 telah berjaya disimpan dan Borang 4 kini dibuka!',
                        confirmButtonText: 'Seterusnya (Papan Pemuka)',
                        confirmButtonColor: '#047857',
                        customClass: {
                            popup: 'rounded-4 shadow',
                            confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                        }
                    }).then(() => {
                        window.location.href = data.redirect || '{{ $backToTenderUrl }}';
                    });
                } else {
                    alert('Borang 3 Disahkan!');
                    window.location.href = data.redirect || '{{ $backToTenderUrl }}';
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: data.message || 'Gagal mengesahkan Borang 3.',
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    alert(data.message || 'Gagal mengesahkan Borang 3.');
                }
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';
            }
            console.error(err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat Sistem!',
                    text: 'Berlaku masalah semasa berhubung dengan pelayan.',
                    confirmButtonColor: '#dc2626'
                });
            } else {
                alert('Berlaku masalah semasa berhubung dengan pelayan.');
            }
        });
    }

    window.b3VendorData = @json($b3VendorData ?? []);
    window.b3MonthsLabels = @json($b3MonthsLabels ?? ['Bulan 1', 'Bulan 2', 'Bulan 3']);

    function openAllAkaunBankModal(vendorId) {
        const vData = window.b3VendorData[vendorId];
        if (!vData) return;

        const title = document.getElementById('allAkaunBankModalTitle');
        if (title) {
            title.innerHTML = `<i class="bi bi-bank me-2 text-primary"></i>Kesemua Akaun Bank - Ruj: ${vData.kod_pembekal} (${vData.vendor_name})`;
        }

        const m1 = document.getElementById('mLabel1');
        const m2 = document.getElementById('mLabel2');
        const m3 = document.getElementById('mLabel3');
        if (m1 && window.b3MonthsLabels[0]) m1.innerText = window.b3MonthsLabels[0];
        if (m2 && window.b3MonthsLabels[1]) m2.innerText = window.b3MonthsLabels[1];
        if (m3 && window.b3MonthsLabels[2]) m3.innerText = window.b3MonthsLabels[2];

        const tbody = document.getElementById('allAkaunBankTableBody');
        tbody.innerHTML = '';

        const accs = vData.pb_accounts || [];
        let grandTotal = 0;

        accs.forEach((acc, idx) => {
            const m1Amt = parseFloat(acc.monthly_amounts[0] || 0);
            const m2Amt = parseFloat(acc.monthly_amounts[1] || 0);
            const m3Amt = parseFloat(acc.monthly_amounts[2] || 0);
            const accTot = parseFloat(acc.total || (m1Amt + m2Amt + m3Amt));
            grandTotal += accTot;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center font-monospace">${idx + 1}</td>
                <td class="fw-semibold text-dark">${acc.bank_name || '-'}</td>
                <td class="text-end font-monospace">${m1Amt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td class="text-end font-monospace">${m2Amt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td class="text-end font-monospace">${m3Amt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td class="text-end font-monospace fw-bold text-dark">${accTot.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('allAkaunBankGrandTotal').innerText = grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        const modal = new bootstrap.Modal(document.getElementById('allAkaunBankModal'));
        modal.show();
    }

    function openAllBonSahamModal(vendorId) {
        const vData = window.b3VendorData[vendorId];
        if (!vData) return;

        const title = document.getElementById('allBonSahamModalTitle');
        if (title) {
            title.innerHTML = `<i class="bi bi-cash-coin me-2 text-primary"></i>Kesemua Rekod Bon & Saham - Ruj: ${vData.kod_pembekal} (${vData.vendor_name})`;
        }

        const tbody = document.getElementById('allBonSahamTableBody');
        tbody.innerHTML = '';

        const accs = vData.bs_accounts || [];
        let grandTotal = 0;

        accs.forEach((acc, idx) => {
            const dep = parseFloat(acc.jumlah_deposit || 0);
            grandTotal += dep;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center font-monospace">${idx + 1}</td>
                <td class="fw-semibold text-dark">${acc.bank_institusi || '-'}</td>
                <td class="text-end font-monospace fw-bold text-dark">${dep.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('allBonSahamGrandTotal').innerText = grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        const modal = new bootstrap.Modal(document.getElementById('allBonSahamModal'));
        modal.show();
    }

    window.openAllAkaunBankModal = openAllAkaunBankModal;
    window.openAllBonSahamModal = openAllBonSahamModal;
</script>
@endsection
