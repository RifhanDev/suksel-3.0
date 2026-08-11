@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --kewangan-accent: #c41e3a;
        --kewangan-accent-dark: #8b1428;
        --p1-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    }

    .b1-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .b1-header-banner {
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

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .table-modern-b1 {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b1 thead th {
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.775rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.9rem 1.25rem;
        border-bottom: none;
        white-space: nowrap;
    }

    .table-modern-b1 tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern-b1 tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern-b1 tbody td {
        padding: 0.9rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #334155;
    }

    .btn-action-papar {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.38rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .btn-action-papar:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .status-badge-sempurna {
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

    .confirmation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
    }

    /* Modal Styling */
    .modal-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header-custom {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: #ffffff;
        padding: 1.25rem 1.75rem;
    }

    .form-control-modern, .form-select-modern {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.45rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
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
            <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 1: Analisa Kesempurnaan Tender</li>
        </ol>
    </nav>

    {{-- Header Banner Card --}}
    <div class="b1-card mb-4">
        <div class="b1-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-primary px-2.5 py-1 rounded-pill small fw-bold">Peringkat Pertama</span>
                    <span class="badge bg-white bg-opacity-20 text-white px-2.5 py-1 rounded-pill small">BORANG 1</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 1 - ANALISA KESEMPURNAAN TENDER</h3>
                <p class="text-white-50 mb-0 small">Semakan pematuhan kriteria kesempurnaan tender & dokumen asas perolehan kerja.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ $backToTenderUrl }}" class="btn btn-light btn-sm fw-semibold shadow-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Borang Penilaian
                </a>
            </div>
        </div>
    </div>

    {{-- Section 1: Kriteria Kesempurnaan Tender --}}
    <div class="b1-card p-4 mb-4">
        <div class="section-title-bar">
            <div>
                <h6 class="fw-bold text-dark mb-0">KRITERIA KESEMPURNAAN TENDER</h6>
                <small class="text-muted">Sila klik butang <strong>Papar</strong> pada setiap kriteria untuk membuat semakan syarikat.</small>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1.5 rounded-pill">7 Kriteria Utama</span>
        </div>

        <div class="table-modern-wrapper mb-2">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">#</th>
                            <th>Kriteria-Kriteria Kesempurnaan Tender</th>
                            <th style="width: 180px;" class="text-center">Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = [
                                'Borang Tender Ditandatangani',
                                'Penandatangan Diberi kuasa?',
                                'Harga Tender / Tempoh Tercatat di Borang Tender',
                                'Pendaftaran Masih Sah Semasa Tutup Tender',
                                'Mengembalikan Kesemua Dokumen Asas Tender',
                                'Tempoh Tidak Melebihi Tempoh Siap Maksimum',
                                'Surat Akuan Pembida Ditandatangani (Integrity Pact)',
                            ];
                        @endphp

                        @foreach($rows as $i => $label)
                            <tr>
                                <td class="text-center text-muted fw-bold small">{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                            <i class="bi bi-check2-square"></i>
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $label }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn-action-papar btn-papar"
                                        data-title="{{ $label }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#paparModal">
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

    {{-- Section 2: Rumusan & Keputusan Penilaian --}}
    <div class="b1-card p-4 mb-4">
        <div class="section-title-bar">
            <div>
                <h6 class="fw-bold text-dark mb-0">RUMUSAN KEPUTUSAN ANALISA KESEMPURNAAN TENDER</h6>
                <small class="text-muted">Keputusan akhir kesempurnaan mengikut bilangan petender.</small>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1.5 rounded-pill">Keputusan Semakan</span>
        </div>

        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 100px;" class="text-center">Bil</th>
                            <th style="width: 220px;" class="text-center">Keputusan Semakan</th>
                            <th>Catatan & Ulasan Semakan <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center fw-bold text-muted">1/2</td>
                            <td class="text-center">
                                <span class="status-badge-sempurna">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sempurna
                                </span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-modern" value="Semua dokumen lengkap dan mematuhi syarat." placeholder="Isi catatan...">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fw-bold text-muted">2/2</td>
                            <td class="text-center">
                                <span class="status-badge-sempurna">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sempurna
                                </span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-modern" value="Semua dokumen sah dan diperakukan." placeholder="Isi catatan...">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center mb-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                        <i class="bi bi-people me-1"></i>Bilangan Pembekal Dinilai
                    </label>
                    <input type="number" class="form-control form-control-modern font-monospace fw-bold" value="2" style="max-width: 140px;">
                </div>
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" checked>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                            Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </a>
                <button type="button" class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm" onclick="openSavedModal()">
                    <i class="bi bi-floppy me-1 font-bold"></i>Simpan Keputusan
                </button>
            </div>
        </div>

    </div>

</div>

{{-- =========================
    MODAL PAPAR (SEMAKAN SYARIKAT)
========================== --}}
<div class="modal fade" id="paparModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-card">

            <div class="modal-header-custom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-20 p-2 rounded-3 text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-clipboard-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-0" id="paparTitle">JENIS KRITERIA</h6>
                        <small class="text-white-50">Semakan Pematuhan Kriteria Mengikut Syarikat Petender</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 d-flex align-items-center gap-2 py-2.5 px-3 rounded-3 mb-3 text-primary small">
                    <i class="bi bi-info-circle-fill fs-6"></i>
                    <span>Pilih status kesempurnaan bagi setiap petender dan beri catatan jika perlu.</span>
                </div>

                <div class="table-modern-wrapper mb-4">
                    <div class="table-responsive">
                        <table class="table table-modern-b1 align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 80px;" class="text-center">Bil</th>
                                    <th>Maklumat Dokumen / Syarikat</th>
                                    <th style="width: 220px;" class="text-center">Status Kesempurnaan</th>
                                    <th style="width: 280px;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-muted">1/2</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                                                <i class="bi bi-file-earmark-pdf fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">Syarikat Petender A</div>
                                                <small class="text-muted extra-small">Dokumen Sokongan .pdf</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-modern text-center fw-semibold text-success">
                                            <option value="Sempurna" selected>Sempurna</option>
                                            <option value="Tidak">Tidak Sempurna</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-modern" value="Lengkap & ditandatangani" placeholder="Catatan...">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-muted">2/2</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                                                <i class="bi bi-file-earmark-pdf fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">Syarikat Petender B</div>
                                                <small class="text-muted extra-small">Dokumen Sokongan .pdf</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-modern text-center fw-semibold text-success">
                                            <option value="Sempurna" selected>Sempurna</option>
                                            <option value="Tidak">Tidak Sempurna</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-modern" value="Mematuhi tempoh siap" placeholder="Catatan...">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <button type="button" class="btn btn-light px-3 fw-semibold" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" id="btnSimpanDalamModal">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Semakan
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- =========================
    MODAL SIMPAN SUCCESS
========================== --}}
<div class="modal fade" id="savedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content modal-card p-4 text-center">

            <div class="my-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="width: 72px; height: 72px;">
                    <i class="bi bi-check-circle-fill display-5"></i>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-1">Berjaya Disimpan!</h5>
            <p class="text-muted small mb-4">Maklumat analisa kesempurnaan tender telah berjaya disimpan ke dalam sistem.</p>

            <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-bold w-100" data-bs-dismiss="modal">
                Faham & Tutup
            </button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-papar').forEach(btn => {
        btn.addEventListener('click', function(){
            const title = this.dataset.title || 'JENIS KRITERIA';
            document.getElementById('paparTitle').textContent = title;
        });
    });

    // Simpan dalam modal papar -> tutup modal papar -> buka modal success
    document.getElementById('btnSimpanDalamModal').addEventListener('click', function(){
        const paparEl = document.getElementById('paparModal');
        const savedEl = document.getElementById('savedModal');

        const paparModal = bootstrap.Modal.getInstance(paparEl) || new bootstrap.Modal(paparEl);
        paparModal.hide();

        // tunggu modal papar tutup dulu (supaya backdrop tak bertindih)
        paparEl.addEventListener('hidden.bs.modal', function handler(){
            paparEl.removeEventListener('hidden.bs.modal', handler);
            const savedModal = new bootstrap.Modal(savedEl);
            savedModal.show();
        });
    });

    // Simpan dari page utama (tanpa papar) -> terus buka modal success
    function openSavedModal(){
        const savedModal = new bootstrap.Modal(document.getElementById('savedModal'));
        savedModal.show();
    }
    window.openSavedModal = openSavedModal;
</script>
@endsection
