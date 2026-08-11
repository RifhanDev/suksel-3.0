@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --kewangan-accent: #c41e3a;
        --kewangan-accent-dark: #8b1428;
        --p1-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    }

    .b3-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .b3-header-banner {
        background: var(--p1-gradient);
        padding: 1.75rem 2rem;
        color: #ffffff;
        position: relative;
    }

    .section-title-bar {
        background: #f8fafc;
        border-left: 4px solid #2563eb;
        padding: 0.75rem 1.25rem;
        border-radius: 0 10px 10px 0;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
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
    }

    .sub-nav-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .sub-nav-btn.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .table-borang3-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang3-modern th {
        background: #1e3a8a;
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
        background: #1e40af;
        font-size: 0.75rem;
    }

    .table-borang3-modern th.subhead-level2 {
        background: #1d4ed8;
        font-size: 0.725rem;
        font-weight: 600;
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
        padding: 1.5rem;
    }

    .btn-simpan {
        background: #19c1a7;
        color: #ffffff;
        border: 0;
        padding: 0.6rem 1.75rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 12px rgba(25, 193, 167, 0.25);
    }

    .btn-simpan:hover {
        background: #14a38d;
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(25, 193, 167, 0.35);
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

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
            <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
            <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 3: Analisa Kecukupan Modal</li>
        </ol>
    </nav>

    {{-- Header Banner Card --}}
    <div class="b3-card mb-4">
        <div class="b3-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-primary px-2.5 py-1 rounded-pill small fw-bold">Peringkat Pertama</span>
                    <span class="badge bg-white bg-opacity-20 text-white px-2.5 py-1 rounded-pill small">BORANG 3</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 3 - ANALISA KECUKUPAN MODAL</h3>
                <p class="text-white-50 mb-0 small">Analisis modal pusingan, penyata bank, aset cair & had modal minimum (3% nilai Kerja Pembina).</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ $backToTenderUrl }}" class="btn btn-light btn-sm fw-semibold shadow-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Borang Penilaian
                </a>
            </div>
        </div>
    </div>

    {{-- Sub Navigation Tabs for Borang 3 Components --}}
    <div class="sub-nav-tabs">
        <a href="{{ route('borang3', ['tender' => $tenderParam]) }}" class="sub-nav-btn active">
            <i class="bi bi-calculator"></i>Borang 3 (Ringkasan Modal)
        </a>
        <a href="{{ route('lembaran', ['tender' => $tenderParam]) }}" class="sub-nav-btn">
            <i class="bi bi-journal-text"></i>Lembaran Imbangan
        </a>
        <a href="{{ route('akaunBank', ['tender' => $tenderParam]) }}" class="sub-nav-btn">
            <i class="bi bi-bank"></i>Penyata / Akaun Bank
        </a>
        <a href="{{ route('bonSaham', ['tender' => $tenderParam]) }}" class="sub-nav-btn">
            <i class="bi bi-cash-coin"></i>Bon & Saham
        </a>
    </div>

    {{-- Main Section Card --}}
    <div class="b3-card p-4 mb-4">
        <div class="section-title-bar">
            <div>
                <h6 class="fw-bold text-dark mb-0">JADUAL ANALISA KECUKUPAN MODAL</h6>
                <small class="text-muted">Perbandingan Nisbah Modal Pusingan vs Modal Minimum yang Diperlukan (3% Kerja Pembina).</small>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1.5 rounded-pill">Nisbah Kewangan</span>
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
                                <td class="text-center font-monospace fw-bold text-primary">
                                    {{ $r }}/5
                                </td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace fw-bold text-dark">0.00</td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace text-success">0.00</td>
                                <td class="text-end font-monospace fw-bold text-primary">0.00</td>
                                <td class="text-end font-monospace text-muted">0.00</td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace">0.00</td>
                                <td class="text-end font-monospace fw-bold text-primary">0.00</td>
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
                        <button type="button" class="btn btn-simpan" onclick="showSuccessModal()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
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

            <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-bold w-100" data-bs-dismiss="modal">
                Faham & Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
</script>
@endsection
