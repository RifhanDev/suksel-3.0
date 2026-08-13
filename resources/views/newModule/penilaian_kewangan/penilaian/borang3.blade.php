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
                            @for($r=1; $r<=5; $r++)
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">
                                        {{ $r }}/5
                                    </td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace fw-bold text-dark">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace text-success">0.00</td>
                                    <td class="text-end font-monospace fw-bold">0.00</td>
                                    <td class="text-end font-monospace text-muted">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace fw-bold">0.00</td>
                                    <td class="text-end font-monospace text-muted">0.00</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Form Actions & Confirmation Box --}}
            <div class="confirmation-box">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-12 col-md-8">
                        <div class="form-check p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="sahPenilaian" checked>
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
                            <button type="button" class="btn btn-submit-danger px-4 rounded-3" onclick="showSuccessModal()">
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
                                <th colspan="9">ANALISA KECUKUPAN MODAL</th>
                            </tr>
                            <tr>
                                <th colspan="6" class="subhead-main">
                                    MAKLUMAT DARI LEMBARAN IMBANGAN (BALANCE SHEET)
                                </th>
                                <th colspan="3" class="subhead-main">
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
                                <th class="subhead-level2">Pinjaman Bank</th>
                                <th class="subhead-level2">Yang Akan Diluluskan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i=1; $i<=6; $i++)
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">
                                        {{ $i }}/6
                                    </td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                    <td class="text-end font-monospace">0.00</td>
                                </tr>
                            @endfor
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
                                <th class="subhead-main" style="width: 80px;">Ruj.<br>Petender</th>
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
                                $rows = [
                                    [
                                        'ruj' => '45/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['157,894.95','181,807.61','252,434.78'],
                                        'bank1' => 'RHB BANK',
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '591,937.34',
                                    ],
                                    [
                                        'ruj' => '27/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['287,753.15','509,483.59','514,701.07'],
                                        'bank1' => "HONG LEONG\nBANK",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '1,019,988.71',
                                    ],
                                    [
                                        'ruj' => '34/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['63,607.87','63,699.87','168,297.97'],
                                        'bank1' => 'ALLIANCE BANK',
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '295,605.71',
                                    ],
                                    [
                                        'ruj' => '24/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['305,994.23','411,462.72','94,361.87'],
                                        'bank1' => "MAYBANK\nBERHAD",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '811,817.82',
                                    ],
                                    [
                                        'ruj' => '37/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['148,201.90','350,061.90','300,800.09'],
                                        'bank1' => "CIMB ISLAMIC\nBANK",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '799,063.89',
                                    ],
                                    [
                                        'ruj' => '30/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['201,333.02','206,385.75','96,802.35'],
                                        'bank1' => "RHB BANK",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '515,221.12',
                                    ],
                                    [
                                        'ruj' => '32/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['86,226.19','206,385.75','96,802.35'],
                                        'bank1' => "RHB BANK",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '232,771.76',
                                    ],
                                    [
                                        'ruj' => '2/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['78,297.64','67,647.93','1,000,667.93'],
                                        'bank1' => "CIMB\nPUTRAJAYA",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '2,860,443.50',
                                    ],
                                    [
                                        'ruj' => '48/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['700,667.80','795,239.82','1,063,535.73'],
                                        'bank1' => "CIMB BANK",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '195,177.26',
                                    ],
                                    [
                                        'ruj' => '7/53',
                                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                                        'akaun1' => ['319,282.93','325,213.36','313,853.39'],
                                        'bank1' => "UOB BANK",
                                        'akaun2' => ['','',''],
                                        'bank2' => '',
                                        'akaun3' => ['','',''],
                                        'bank3' => '',
                                        'akaun4' => ['','',''],
                                        'bank4' => '',
                                        'jumlah' => '958,203.98',
                                    ],
                                ];
                            @endphp

                            @foreach($rows as $row)
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">{{ $row['ruj'] }}</td>

                                    <td class="text-center small text-muted font-monospace">
                                        <div class="lh-sm">
                                            {{ $row['bulan'][0] }}<br>
                                            {{ $row['bulan'][1] }}<br>
                                            {{ $row['bulan'][2] }}
                                        </div>
                                    </td>

                                    <td class="text-end font-monospace">
                                        <div class="lh-sm">
                                            {!! implode('<br>', array_filter($row['akaun1'])) ?: '-' !!}
                                        </div>
                                    </td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank1'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace">
                                        <div class="lh-sm">
                                            {!! implode('<br>', array_filter($row['akaun2'])) ?: '-' !!}
                                        </div>
                                    </td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank2'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace">
                                        <div class="lh-sm">
                                            {!! implode('<br>', array_filter($row['akaun3'])) ?: '-' !!}
                                        </div>
                                    </td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank3'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace">
                                        <div class="lh-sm">
                                            {!! implode('<br>', array_filter($row['akaun4'])) ?: '-' !!}
                                        </div>
                                    </td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank4'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace fw-bold text-dark">{{ $row['jumlah'] }}</td>
                                </tr>
                            @endforeach
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
                                $rows = [
                                    ['ruj'=>'45/53','akaun1'=>'157,694.95','bank1'=>'RHB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'157,694.95'],
                                    ['ruj'=>'27/53','akaun1'=>'287,753.15','bank1'=>"HONG LEONG\nBANK",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'287,753.15'],
                                    ['ruj'=>'34/53','akaun1'=>'63,607.87','bank1'=>'ALLIANCE BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'63,607.87'],
                                    ['ruj'=>'24/53','akaun1'=>'305,994.23','bank1'=>"MAYBANK\nBERHAD",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'305,994.23'],
                                    ['ruj'=>'37/53','akaun1'=>'118,201.90','bank1'=>"CIMB ISLAMIC\nBANK",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'118,201.90'],
                                    ['ruj'=>'30/53','akaun1'=>'211,333.02','bank1'=>'RHB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'211,333.02'],
                                    ['ruj'=>'32/53','akaun1'=>'86,226.19','bank1'=>'RHB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'86,226.19'],
                                    ['ruj'=>'2/53','akaun1'=>'86,226.19','bank1'=>"CIMB\nPUTRAJAYA",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'86,226.19'],
                                    ['ruj'=>'48/53','akaun1'=>'1,001,667.90','bank1'=>'CIMB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'1,001,667.90'],
                                    ['ruj'=>'7/53','akaun1'=>'319,229.23','bank1'=>'UOB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'319,229.23'],
                                ];
                            @endphp

                            @foreach($rows as $row)
                                <tr>
                                    <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff;">{{ $row['ruj'] }}</td>

                                    <td class="text-end font-monospace">{{ $row['akaun1'] ?: '-' }}</td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank1'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace">{{ $row['akaun2'] ?: '-' }}</td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank2'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace">{{ $row['akaun3'] ?: '-' }}</td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank3'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace">{{ $row['akaun4'] ?: '-' }}</td>
                                    <td class="text-center small fw-semibold text-secondary">{!! nl2br(e($row['bank4'])) ?: '-' !!}</td>

                                    <td class="text-end font-monospace fw-bold text-dark">{{ $row['jumlah'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            <p class="text-muted small mb-4">Maklumat analisa kecukupan modal telah berjaya disimpan ke dalam sistem.</p>

            <button type="button" class="btn btn-submit-danger px-4 py-2 rounded-3 w-100" data-bs-dismiss="modal">
                Faham & Tutup
            </button>
        </div>
    </div>
</div>

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
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
</script>
@endsection
