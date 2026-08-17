@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b4-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b4-header-banner {
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

    .table-modern-b1 {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b1 thead th {
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

    .table-modern-b1 tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern-b1 tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern-b1 tbody td {
        padding: 0.85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #334155;
    }

    .table-borang4-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang4-modern th {
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

    .table-borang4-modern th.subhead-main {
        background: #6d6d79ff;
        color: #ffffff;
        font-size: 0.75rem;
    }

    .table-borang4-modern th.subhead-level2 {
        background: #d7d7d9;
        color: #3f3f3f;
        font-size: 0.725rem;
        font-weight: 700;
        border-color: #cbd5e1;
    }

    .table-borang4-modern td {
        padding: 0.75rem 0.8rem;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang4-modern tbody tr:hover {
        background-color: #f8fafc;
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

    .btn-action-papar {
        background-color: #eff6ff;
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
        background-color: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
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
    $b4DataMap = $b4VendorData ?? [];
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 4</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b4-card mb-4">
        <div class="b4-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Pertama</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 4 - Analisa Data-Data Penilaian Prestasi Petender</h3>
                <p class="text-white-50 mb-0 small">Semakan dan penilaian prestasi kerja semasa petender & peratus kemajuan dicapai.</p>
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
                        <div class="info-item-value text-danger font-monospace fw-bold" style="font-size: 0.95rem;">{{ $no_tender_display }}</div>
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
                        <div class="info-item-value text-dark fw-bold" style="font-size: 0.88rem;">{{ $ptj_display }}</div>
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
                                {{ $status_label }}
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
                        <div class="info-item-value text-dark font-monospace fw-bold" style="font-size: 0.95rem;">{{ $sah_laku_tamat }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section Card: Vendor List Table --}}
    <div class="b4-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-speedometer2 text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Untuk Penilaian Prestasi Kerja</h5>
                    <p class="text-secondary small mb-0">Sila klik <strong>Papar & Semak</strong> untuk menyemak dan menganalisis prestasi kerja semasa petender.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($participants) }} Petender Berdaftar
            </span>
        </div>

        {{-- Table Vendor Participants --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">#</th>
                            <th><i class="bi bi-person-vcard text-danger me-1"></i> Kod Pembekal</th>
                            <th style="width: 180px;" class="text-center">Status Semakan</th>
                            <th style="width: 200px;" class="text-center"><i class="bi bi-sliders text-danger me-1"></i> Tindakan Semakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $idx => $p)
                            @php
                                $vId = $p->vendor_id;
                                $vData = $b4DataMap[$vId] ?? [];
                                $kod = $vData['kod_pembekal'] ?? ($p->kod_pembekal ?: ($loop->iteration . '/' . count($participants)));
                                $isEval = $vData['is_evaluated'] ?? false;
                            @endphp
                            <tr>
                                <td class="text-center text-muted fw-bold small">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; margin-right:10px">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold font-monospace text-dark" style="font-size: 0.9rem;">{{ $kod }}</span>
                                            @if(!empty($vData['vendor_name']))
                                                <div class="small text-muted">{{ $vData['vendor_name'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center" id="vendor-status-badge-{{ $vId }}">
                                    @if($isEval)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                                            <i class="bi bi-check-circle me-1"></i>Telah Dinilai
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock me-1"></i>Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-action-papar" onclick="openPrestasiModal({{ $vId }})">
                                        <i class="bi bi-eye me-1"></i>Papar & Semak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tiada petender ditemui.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center mb-3">
                <div class="col-12 col-md-7">
                    <div class="form-check p-3 bg-white rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="sahPenilaian">
                        <label class="form-check-label fw-semibold text-dark small" for="sahPenilaian">
                            Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-5 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" id="btnSimpanMuktamad" class="btn btn-submit-danger px-4 rounded-3" onclick="simpanKeputusanBorang4()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- =========================
    MODAL: PRESTASI PETENDER (modalprestasipetender)
========================== --}}
<div class="modal fade" id="modalprestasipetender" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 94%; width: 94%;">
        <div class="modal-content modal-card">

            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #dbeafe; color: #2563eb;">
                        <i class="bi bi-speedometer2 fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Prestasi Kerja Semasa Petender</h5>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold font-monospace" id="modalKodPembekalBadge">Kod Pembekal: -</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">
                <div class="table-modern-wrapper mb-2">
                    <div class="table-responsive">
                        <table class="table-borang4-modern" id="modalPrestasiTable">
                            <thead id="modalPrestasiThead">
                                {{-- Dynamically populated via JS --}}
                            </thead>

                            <tbody id="modalPrestasiTbody">
                                {{-- Dynamically populated via JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2" style="background: #fffbeb; border: 1px solid #fde68a; font-size: 0.78rem; color: #92400e;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="0" class="flex-shrink-0 me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="white" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                    <strong>Perhatian:</strong> Sila pastikan maklumat peratus kemajuan dan status projek disemak dengan teliti sebelum membuat pengesahan penilaian.
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-top px-4 py-3 justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Tutup
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="btnSimpanPenilaianVendor" class="btn btn-submit-danger px-4 rounded-3" onclick="savePrestasiModal()">
                        <i class="bi bi-floppy me-1"></i>Simpan Penilaian
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.b4VendorData = @json($b4VendorData ?? []);
    window.activeVendorId = null;

    function openPrestasiModal(vendorId) {
        window.activeVendorId = vendorId;
        const vData = window.b4VendorData[vendorId];
        if (!vData) return;

        document.getElementById('modalKodPembekalBadge').textContent = 'Kod Pembekal: ' + vData.kod_pembekal + ' (' + vData.vendor_name + ')';

        const items = (vData.items && vData.items.length > 0) ? vData.items : [{
            nama: '-', no_kontrak: '-', harga: 0, tarikh_tapak: '-', tempoh: 0,
            tarikh_siap: '-', tarikh_penilaian: '-', luputan: 0, kemajuan_sebenar: 0, kemajuan_jadual: 0
        }];

        const colCount = items.length;

        // 1. Build Header
        const thead = document.getElementById('modalPrestasiThead');
        let headerHtml = '<tr><th class="subhead-main text-start ps-4" style="width: 280px;">Perkara</th>';
        for (let i = 0; i < colCount; i++) {
            headerHtml += `<th class="subhead-level2">Kerja ${i + 1}</th>`;
        }
        headerHtml += '</tr>';
        thead.innerHTML = headerHtml;

        // 2. Compute minimum performance across items
        let minKemajuan = 0.00;
        if (items.length > 0) {
            const kemajuanArr = items.map(it => parseFloat(it.kemajuan_sebenar || 0));
            minKemajuan = Math.min(...kemajuanArr);
        }

        // 3. Build Body Rows (15 Rows)
        const tbody = document.getElementById('modalPrestasiTbody');
        let bodyHtml = '';

        // Helper for table cells across N items
        function buildRowCells(valueFormatter, cellClass = '') {
            let cells = '';
            items.forEach((item, idx) => {
                const val = valueFormatter(item, idx);
                cells += `<td class="${cellClass}">${val}</td>`;
            });
            return cells;
        }

        // Row 1: Nama Ringkas Kerja Semasa
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Nama Ringkas Kerja Semasa</td>
            ${buildRowCells(it => it.nama || '-', 'fw-medium text-dark')}
        </tr>`;

        // Row 2: No. Kontrak Kerja Semasa
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">No. Kontrak Kerja Semasa</td>
            ${buildRowCells(it => it.no_kontrak || '-', 'font-monospace fw-semibold text-dark')}
        </tr>`;

        // Row 3: Harga Kontrak (RM)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Harga Kontrak (RM)</td>
            ${buildRowCells(it => parseFloat(it.harga || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}), 'font-monospace fw-bold text-dark')}
        </tr>`;

        // Row 4: Tarikh Pemilikan Tapak
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tarikh Pemilikan Tapak</td>
            ${buildRowCells(it => it.tarikh_tapak || '-', 'font-monospace')}
        </tr>`;

        // Row 5: Tempoh Kontrak (Hari) (P)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tempoh Kontrak (Hari) (P)</td>
            ${buildRowCells(it => it.tempoh || '0', 'font-monospace')}
        </tr>`;

        // Row 6: Tarikh Siap Kontrak (termasuk EOT diluluskan)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tarikh Siap Kontrak (termasuk EOT diluluskan)</td>
            ${buildRowCells(it => it.tarikh_siap || '-', 'font-monospace')}
        </tr>`;

        // Row 7: Tarikh Penilaian Kemajuan
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Tarikh Penilaian Kemajuan</td>
            ${buildRowCells(it => it.tarikh_penilaian || '-', 'font-monospace')}
        </tr>`;

        // Row 8: Luputan Tarikh Siap Kontrak (Hari) (D)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Luputan Tarikh Siap Kontrak (Hari) (D)</td>
            ${buildRowCells(it => it.luputan || '0', 'font-monospace')}
        </tr>`;

        // Row 9: Peratus Kemajuan Sebenar Dicapai (A) (%)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Peratus Kemajuan Sebenar Dicapai (A) (%)</td>
            ${buildRowCells(it => parseFloat(it.kemajuan_sebenar || 0).toFixed(2), 'font-monospace fw-bold text-primary')}
        </tr>`;

        // Row 10: Peratus Kemajuan Mengikut Jadual (S) (%)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Peratus Kemajuan Mengikut Jadual (S) (%)</td>
            ${buildRowCells(it => parseFloat(it.kemajuan_jadual || 0).toFixed(2), 'font-monospace fw-bold text-dark')}
        </tr>`;

        // Row 11: Prestasi Kerja Semasa (A-(S*P-D)/P)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Prestasi Kerja Semasa (A-(S*P-D)/P)</td>
            ${buildRowCells(it => {
                const A = parseFloat(it.kemajuan_sebenar || 0);
                const S = parseFloat(it.kemajuan_jadual || 0);
                const P = parseFloat(it.tempoh || 1) || 1;
                const D = parseFloat(it.luputan || 0);
                const variance = A - ((S * P - D) / P);
                return variance.toFixed(2);
            }, 'font-monospace fw-bold')}
        </tr>`;

        // Row 12: Status Prestasi (Per Item)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Status Prestasi</td>
            ${buildRowCells(it => {
                const A = parseFloat(it.kemajuan_sebenar || 0);
                const S = parseFloat(it.kemajuan_jadual || 0);
                const P = parseFloat(it.tempoh || 1) || 1;
                const D = parseFloat(it.luputan || 0);
                const variance = A - ((S * P - D) / P);
                if (variance >= -20) {
                    return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-bold">MEMUASKAN</span>';
                } else {
                    return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-bold">TIDAK MEMUASKAN</span>';
                }
            })}
        </tr>`;

        // Row 13: Prestasi Kerja Semasa Terendah (%)
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Prestasi Kerja Semasa Terendah (%)</td>
            <td colspan="${colCount}" class="font-monospace fw-bold">${minKemajuan.toFixed(2)}</td>
        </tr>`;

        // Row 14: Semakan Projek Sakit Oleh Pegawai Penilai
        const isSakit = (vData.projek_sakit === 'ADA');
        bodyHtml += `<tr>
            <td class="fw-semibold text-dark ps-4" style="background-color: #efeff0ff;">Semakan Projek Sakit Oleh Pegawai Penilai</td>
            <td colspan="${colCount}">
                <select class="form-select form-select-sm border-secondary-subtle rounded-3" id="selectProjekSakit" style="max-width:160px;" onchange="updateModalOverallStatusBadge()">
                    <option value="TIADA" ${!isSakit ? 'selected' : ''}>TIADA</option>
                    <option value="ADA" ${isSakit ? 'selected' : ''}>ADA</option>
                </select>
            </td>
        </tr>`;

        // Row 15: STATUS PRESTASI (Overall Summary Row)
        bodyHtml += `<tr style="background-color: #f8fafc;">
            <td class="fw-bold text-dark ps-4">STATUS PRESTASI:</td>
            <td colspan="${colCount}" id="modalOverallStatusBadgeCell">
                <!-- Dynamically populated via updateModalOverallStatusBadge() -->
            </td>
        </tr>`;

        // Row 16: Formula Note
        bodyHtml += `<tr style="background-color: #f8fafc;">
            <td class="fw-semibold text-muted small ps-4">Formula Pengiraan Nilai Baki Kerja Semasa Dalam Tangan:</td>
            <td colspan="${colCount}" class="font-monospace text-muted small">
                (100% - %Kerja Sebenar) x Harga Kontrak Kerja
            </td>
        </tr>`;

        tbody.innerHTML = bodyHtml;
        updateModalOverallStatusBadge();

        const modal = new bootstrap.Modal(document.getElementById('modalprestasipetender'));
        modal.show();
    }

    function updateModalOverallStatusBadge() {
        const select = document.getElementById('selectProjekSakit');
        const cell = document.getElementById('modalOverallStatusBadgeCell');
        if (!select || !cell) return;

        const val = select.value;
        if (val === 'ADA') {
            cell.innerHTML = '<span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold fs-6">TIDAK MEMUASKAN</span>';
        } else {
            cell.innerHTML = '<span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold fs-6">MEMUASKAN</span>';
        }
    }

    function savePrestasiModal() {
        if (!window.activeVendorId) return;

        const select = document.getElementById('selectProjekSakit');
        const projekSakit = select ? select.value : 'TIADA';
        const btn = document.getElementById('btnSimpanPenilaianVendor');

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
        }

        fetch('{{ route('penilaianKewanganKerja.borang4.simpanVendor', $tenderIdentifier) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                vendor_id: window.activeVendorId,
                projek_sakit: projekSakit
            })
        })
        .then(response => response.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Penilaian';
            }

            if (data.success) {
                // Update local JS data state
                if (window.b4VendorData[window.activeVendorId]) {
                    window.b4VendorData[window.activeVendorId].is_evaluated = true;
                    window.b4VendorData[window.activeVendorId].projek_sakit = projekSakit;
                }

                // Update main table vendor status badge
                const statusTd = document.getElementById('vendor-status-badge-' + window.activeVendorId);
                if (statusTd) {
                    statusTd.innerHTML = `
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                            <i class="bi bi-check-circle me-1"></i>Telah Dinilai
                        </span>
                    `;
                }

                // Hide modal
                const modalEl = document.getElementById('modalprestasipetender');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();

                // Show Swal notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya Disimpan!',
                        text: 'Maklumat analisa penilaian prestasi petender telah berjaya disimpan.',
                        confirmButtonText: 'Faham & Tutup',
                        confirmButtonColor: '#047857',
                        customClass: {
                            popup: 'rounded-4 shadow',
                            confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                        }
                    });
                } else {
                    alert('Maklumat analisa penilaian prestasi petender telah berjaya disimpan.');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: data.message || 'Gagal menyimpan penilaian.',
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    alert(data.message || 'Gagal menyimpan penilaian.');
                }
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Penilaian';
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

    function simpanKeputusanBorang4() {
        // 1. Check if all vendors are evaluated
        const vendorKeys = Object.keys(window.b4VendorData || {});
        let allEvaluated = true;
        let unevaluatedVendorKod = '';

        for (let i = 0; i < vendorKeys.length; i++) {
            const v = window.b4VendorData[vendorKeys[i]];
            if (!v || !v.is_evaluated) {
                allEvaluated = false;
                unevaluatedVendorKod = v ? v.kod_pembekal : '';
                break;
            }
        }

        if (!allEvaluated) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Penilaian Belum Lengkap',
                    html: '<p class="mb-1 text-secondary fs-6">Sila semak dan lengkapkan penilaian prestasi bagi <strong>kesemua petender berdaftar</strong> terlebih dahulu sebelum menyimpan keputusan.</p>',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-4 shadow',
                        confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                    }
                });
            } else {
                alert('Sila semak dan lengkapkan penilaian prestasi bagi kesemua petender berdaftar terlebih dahulu.');
            }
            return;
        }

        // 2. Check confirmation checkbox
        const chk = document.getElementById('sahPenilaian');
        if (!chk || !chk.checked) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengesahan Diperlukan',
                    html: '<p class="mb-1 text-secondary fs-6">Sila tandakan <strong>kotak pengesahan</strong> terlebih dahulu sebelum meneruskan ke borang seterusnya dan melengkapkan Borang 4.</p>',
                    confirmButtonText: 'Faham',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-4 shadow',
                        confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                    }
                });
            } else {
                alert('Sila tandakan kotak pengesahan terlebih dahulu sebelum meneruskan ke borang seterusnya dan melengkapkan Borang 4.');
            }
            return;
        }

        // 3. Post final completion
        const btn = document.getElementById('btnSimpanMuktamad');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
        }

        fetch('{{ route('penilaianKewanganKerja.borang4.simpanMuktamad', $tenderIdentifier) }}', {
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
                        title: 'Borang 4 Disahkan!',
                        text: data.message || 'Borang 4 telah berjaya disimpan dan Borang 5 kini dibuka!',
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
                    alert('Borang 4 Disahkan!');
                    window.location.href = data.redirect || '{{ $backToTenderUrl }}';
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: data.message || 'Gagal mengesahkan Borang 4.',
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    alert(data.message || 'Gagal mengesahkan Borang 4.');
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
</script>
@endsection
