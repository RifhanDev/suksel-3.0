@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <style>
        /* Loading overlay */
        #loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }
        #loading-overlay.active { display: flex; }
        .loading-box {
            background: #fff;
            border-radius: 12px;
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .loading-spinner {
            width: 28px;
            height: 28px;
            border: 3px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
        }
        .loading-check {
            display: none;
            width: 28px;
            height: 28px;
            flex-shrink: 0;
            color: #22c55e;
        }
        #loading-overlay.success .loading-spinner { display: none; }
        #loading-overlay.success .loading-check { display: block; }
        #loading-overlay.success .loading-text { color: #16a34a; }

        /* Full grid borders */
        #tbl-spek-kewangan {
            border: 1px solid #e2e8f0;
        }
        #tbl-spek-kewangan th,
        #tbl-spek-kewangan td {
            border-right: 1px solid #e2e8f0 !important;
        }
        /* #tbl-spek-kewangan th:last-child,
        #tbl-spek-kewangan td:last-child {
            border-right: none !important;
        } */

        /* Alternating group */
        #tbl-spek-kewangan tbody .group-alt td {
            background-color: #f8fafc;
        }

        /* Merge col 1 visually */
        #tbl-spek-kewangan tbody .item-row td:first-child,
        #tbl-spek-kewangan tbody .spec-row td:first-child {
            border-bottom: none !important;
        }

        /* ── Tree connector lines ───────────────────────────────── */
        #tbl-spek-kewangan tbody .item-has-specs>td:first-child {
            position: relative;
        }
        #tbl-spek-kewangan tbody .item-has-specs>td:first-child::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            width: 7px;
            height: 1.5px;
            background: #cbd5e1;
            transform: translateY(-50%);
        }
        #tbl-spek-kewangan tbody .item-has-specs>td:first-child::after {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            bottom: 0;
            width: 1.5px;
            background: #cbd5e1;
        }
        #tbl-spek-kewangan tbody .spec-row>td:first-child {
            position: relative;
        }
        #tbl-spek-kewangan tbody .spec-row>td:first-child::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 1.5px;
            background: #cbd5e1;
        }
        #tbl-spek-kewangan tbody .spec-row>td:first-child::after {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            width: 18px;
            height: 1.5px;
            background: #cbd5e1;
            transform: translateY(-50%);
        }
        #tbl-spek-kewangan tbody .spec-last>td:first-child::before {
            bottom: 50%;
        }
    </style>
@endsection

@section('content')
    @php
        $kembaliUrl = isset($tender) && !empty($tender->uuid)
            ? route('senaraiKewanganBekalan', ['tenderUuid' => $tender->uuid])
            : (url()->previous() !== url()->current() ? url()->previous() : route('pengurusanSpesifikasi'));
        $tajukTender = optional($tender)->tajuk_tender
            ?: optional($tender)->name
            ?: data_get($pricingData, 'tender.tajuk_tender')
            ?: data_get($pricingData, 'tender.name')
            ?: data_get($pricingData, 'spec_document.title')
            ?: 'Tiada Tajuk';
        $kategoriPerolehan = optional($tender)->kategori_perolehan_name
            ?: data_get($pricingData, 'tender.kategori_perolehan_name');
        $noTender = optional($tender)->no_tender
            ?: optional($tender)->ref_number
            ?: data_get($pricingData, 'tender.no_tender')
            ?: data_get($pricingData, 'tender.ref_number')
            ?: '-';
        $ptj = optional(optional($tender)->tenderer)->name
            ?: data_get($pricingData, 'tender.ptj')
            ?: data_get($pricingData, 'tender.tenderer.name')
            ?: '-';
        $templateTitle = data_get($templateData, 'document_title')
            ?: data_get($templateData, 'checklist_title')
            ?: data_get($pricingData, 'spec_document.title')
            ?: 'Tiada Tajuk';
        $templateItemType = data_get($templateData, 'item_type')
            ?: data_get($pricingData, 'spec_document.item_type')
            ?: $kategoriPerolehan
            ?: '-';
        $templateSpecificationType = data_get($templateData, 'specification_type')
            ?: data_get($pricingData, 'spec_document.specification_type')
            ?: 'Spesifikasi Teknikal';
        $templateGoodsType = strtolower((string) (data_get($templateData, 'goods_type')
            ?: data_get($pricingData, 'spec_document.goods_type')
            ?: 'tempatan'));
        $templateWeightingType = strtolower((string) (data_get($templateData, 'weighting_type')
            ?: data_get($pricingData, 'spec_document.weighting_type')
            ?: 'priority'));
        $physicalSubmissionRaw = data_get($templateData, 'physical_submission');
        if ($physicalSubmissionRaw === null) {
            $physicalSubmissionRaw = data_get($pricingData, 'spec_document.physical_submission', true);
        }
        $hasPhysicalSubmission = filter_var($physicalSubmissionRaw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $hasPhysicalSubmission = $hasPhysicalSubmission ?? (bool) $physicalSubmissionRaw;
        $templateStatus = data_get($templateData, 'document_status')
            ?: data_get($templateData, 'checklist_status')
            ?: data_get($pricingData, 'spec_document.status')
            ?: 'draft';
        $templateStatusKey = strtolower((string) $templateStatus);
        $templateStatusLabel = [
            'draft' => 'Draf',
            'submitted' => 'Selesai',
            'complete' => 'Selesai',
            'completed' => 'Selesai',
        ][$templateStatusKey] ?? ucfirst((string) $templateStatus);
        $templateStatusClass = in_array($templateStatusKey, ['submitted', 'complete', 'completed'], true)
            ? 'badge-status-success'
            : 'badge-status-warning';
        $isImportGoods = in_array($templateGoodsType, ['import', 'imported'], true);
        $isDirectWeighting = in_array($templateWeightingType, ['direct', 'secara terus'], true);
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penetapan Harga Spesifikasi</h3>
            <p class="text-muted small m-0">Kunci masuk harga bagi setiap item spesifikasi tender / sebutharga.</p>
        </div>
    </div>

    <!-- TENDER INFO CARD -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    {{ $tajukTender }}
                    @if($kategoriPerolehan)
                        <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">({{ $kategoriPerolehan }})</span>
                    @endif
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $noTender }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $ptj }}</span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle"
                            style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== SECTION: TEMPLAT SPESIFIKASI (read-only preview) ===================== -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <h3 class="content-card-title mb-0" style="font-size: 1rem;">Templat Spesifikasi</h3>
            </div>
        </div>
        <div class="content-card-body p-4">

            <!-- Guidelines -->
            <div class="guideline-card mb-4">
                <div class="guideline-card-header">
                    <div class="guideline-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <span class="guideline-card-title">Panduan Pengisian</span>
                </div>
                <div class="guideline-list">
                    <div class="guideline-item">
                        <span class="guideline-num">1</span>
                        <span class="guideline-item-text">Sila klik ikon <span class="highlight">Pensil</span> untuk
                            penetapan skor bagi setiap item spesifikasi.</span>
                    </div>
                    <div class="guideline-item">
                        <span class="guideline-num">2</span>
                        <span class="guideline-item-text">Sila klik pautan <span class="highlight">UOM</span> untuk
                            carian nama unit ukuran dan kunci masuk di ruang medan yang disediakan.</span>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="row g-3">

                <!-- Tajuk Dokumen -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Tajuk Dokumen <span class="text-danger">*</span></label>
                    <textarea name="tajuk_dokumen" class="form-control form-control-sm" rows="3" readonly disabled>{{ $templateTitle }}</textarea>
                </div>

                <!-- Jenis Item -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Jenis Item</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $templateItemType }}" disabled>
                </div>

                <!-- Jenis Spesifikasi -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Jenis Spesifikasi</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $templateSpecificationType }}" disabled>
                </div>

                <!-- Jenis Barang -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Jenis Barang <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenisBarang" id="jenisBarang1" disabled @checked(!$isImportGoods)>
                            <label class="form-check-label" for="jenisBarang1">Tempatan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenisBarang" id="jenisBarang2" disabled @checked($isImportGoods)>
                            <label class="form-check-label" for="jenisBarang2">Import</label>
                        </div>
                    </div>
                </div>

                <!-- Wajaran Spesifikasi -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Wajaran Spesifikasi <span
                            class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wajaranSpesifikasi"
                                id="wajaranSpesifikasi1" disabled @checked(!$isDirectWeighting)>
                            <label class="form-check-label" for="wajaranSpesifikasi1">Mengikut Keutamaan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wajaranSpesifikasi"
                                id="wajaranSpesifikasi2" disabled @checked($isDirectWeighting)>
                            <label class="form-check-label" for="wajaranSpesifikasi2">Secara Terus</label>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Status</label>
                    <span class="badge-status {{ $templateStatusClass }}">{{ $templateStatusLabel }}</span>
                </div>

                <!-- Penghantaran Fizikal -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block">Penghantaran Fizikal <span
                            class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penghantaranFizikal"
                                id="penghantaranFizikal1" disabled @checked($hasPhysicalSubmission)>
                            <label class="form-check-label" for="penghantaranFizikal1">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="penghantaranFizikal"
                                id="penghantaranFizikal2" disabled @checked(!$hasPhysicalSubmission)>
                            <label class="form-check-label" for="penghantaranFizikal2">Tidak</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <form id="form-spek-kewangan" action="#" method="POST">
    @csrf

        <!-- ===================== SECTION: ITEM SPESIFIKASI & PENETAPAN HARGA ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            <line x1="8" y1="6" x2="16" y2="6"></line>
                            <line x1="8" y1="10" x2="16" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Item Spesifikasi & Penetapan Harga</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Masukkan harga bagi setiap item</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-3">

                <div class="table-responsive">
                    <table id="tbl-spek-kewangan" class="table table-modern w-100 mb-0">
                        <thead>
                            <tr>
                                <th>
                                    Item
                                    <div style="font-size: 0.68rem; font-weight: 600; text-transform: none; letter-spacing: 0; color: #94a3b8; margin-top: 2px;">
                                        Spesifikasi
                                    </div>
                                </th>
                                <th class="text-center" style="width: 130px;">Kekerapan / Kuantiti</th>
                                <th class="text-center" style="width: 130px;">Unit Ukuran</th>
                                <th class="text-center" style="width: 180px;">Penetapan Harga (RM)</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-spek-body">
                            {{-- Rendered via JS from controller data --}}
                        </tbody>
                    </table>
                </div>

                <!-- Totals area — aligned right under Penetapan Harga column -->
                <div class="d-flex justify-content-end mt-3">
                    <div style="width: 360px;">
                        <!-- Jumlah Harga -->
                        <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 mb-2" style="background:#f8fafc; border:1px solid #e2e8f0;">
                            <span class="fw-semibold text-dark" style="font-size:0.85rem;">Jumlah Harga (RM)</span>
                            <span class="fw-bold text-dark" id="jumlah-harga" style="font-size:1rem;">0.00</span>
                        </div>
                        <!-- Anggaran Jabatan -->
                        <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 mb-2" style="background:#eff6ff; border:1px solid #bfdbfe;">
                            <span class="fw-semibold" style="font-size:0.85rem; color:#1e40af;">Anggaran Jabatan (RM)</span>
                            <span class="fw-bold" id="anggaran-jabatan" style="font-size:1rem; color:#1e40af;">{{ number_format($tender->anggaran_jabatan ?? $pricingData['anggaran_jabatan'] ?? 0, 2) }}</span>
                        </div>
                        <!-- Nota -->
                        <div class="d-flex align-items-start gap-2 px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" style="margin-top:2px;">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span class="text-muted fst-italic" style="font-size:0.75rem; line-height:1.4;">Nota: Pastikan Jumlah Harga adalah sama dengan Anggaran Jabatan</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===================== ACTION BUTTONS ===================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ $kembaliUrl }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="d-flex gap-2">
                <button type="button" id="btn-simpan" class="btn-form btn-form-primary">Simpan</button>
                <button type="button" id="btn-selesai" class="btn-form btn-form-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"></path></svg>
                    Selesai
                </button>
            </div>
        </div>

    </form>

    <!-- Loading overlay -->
    <div id="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <svg class="loading-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M8 12l3 3 5-5"></path>
            </svg>
            <span class="loading-text" id="loading-text">Menyimpan...</span>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // ── Data from controller ─────────────────────────────────────────────────
    var pricingData  = @json($pricingData ?? null);
    var items        = pricingData ? pricingData.items : [];
    var STORE_URL    = '{{ route('spesifikasiKewangan.store', $spesifikasiUuid) }}';
    var SUBMIT_URL   = '{{ route('spesifikasiKewangan.submit', $spesifikasiUuid) }}';
    var KEMBALI_URL  = '{{ $kembaliUrl }}';
    var CSRF_TOKEN   = '{{ csrf_token() }}';

    // ── Render table rows ────────────────────────────────────────────────────
    var $tbody = $('#tbl-spek-body');

    $.each(items, function (itemIdx, item) {
        var groupClass  = itemIdx % 2 === 1 ? ' group-alt' : '';
        var details     = item.details || [];
        var detailCount = details.length;
        var hasDetails  = detailCount > 0;
        var totalRows   = 1 + detailCount;
        var savedHarga  = item.harga > 0 ? parseFloat(item.harga).toFixed(2) : '';

        // ── Parent item row — harga spans all rows ───────────────────
        var $itemRow = $(
            '<tr class="item-row' + groupClass + (hasDetails ? ' item-has-specs' : '') + '">' +
                '<td style="padding-left:20px;">' +
                    '<span class="fw-semibold" style="font-size:0.875rem;">' + $('<span>').text(item.title).html() + '</span>' +
                '</td>' +
                '<td class="text-center">' +
                    '<span class="fw-semibold" style="font-size:0.875rem;">' + item.kuantiti + '</span>' +
                '</td>' +
                '<td class="text-center">' +
                    '<span class="small text-muted">' + $('<span>').text(item.uom || '-').html() + '</span>' +
                '</td>' +
                '<td class="text-center" rowspan="' + totalRows + '" style="vertical-align:middle;">' +
                    '<input type="text" name="harga_item[]" class="form-control form-control-sm text-end amount-input harga-input" placeholder="0.00" data-item-idx="' + itemIdx + '" value="' + savedHarga + '">' +
                '</td>' +
            '</tr>'
        );
        $tbody.append($itemRow);

        // ── Child detail rows (display only, no harga cell) ──────────
        $.each(details, function (dIdx, description) {
            var isLast   = dIdx === detailCount - 1;
            var rowClass = 'spec-row' + groupClass + (isLast ? ' spec-last' : '');

            var $detailRow = $(
                '<tr class="' + rowClass + '">' +
                    '<td style="padding-left:44px;">' +
                        '<span class="text-muted" style="font-size:0.82rem;">' + $('<span>').text(description).html() + '</span>' +
                    '</td>' +
                    '<td></td>' +
                    '<td></td>' +
                '</tr>'
            );
            $tbody.append($detailRow);
        });
    });

    // ── Jumlah Harga calculation ─────────────────────────────────────────────
    function parseAmount(val) {
        return parseFloat(String(val).replace(/,/g, '')) || 0;
    }

    function formatAmount(n) {
        return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateJumlahHarga() {
        var total = 0;
        $('.harga-input').each(function () {
            total += parseAmount($(this).val());
        });
        $('#jumlah-harga').text(formatAmount(total));
    }

    updateJumlahHarga();

    $tbody.on('input change', '.harga-input', function() {
        updateJumlahHarga();
    });

    function buildPayload() {
        var payload = { items: [] };
        $('#tbl-spek-body tr.item-row').each(function() {
            var idx   = $(this).find('.harga-input').data('item-idx');
            var uuid  = items[idx] ? items[idx].uuid : null;
            var harga = $(this).find('.harga-input').val().replace(/,/g, '');
            if (uuid) { payload.items.push({ uuid: uuid, harga: parseFloat(harga) || 0 }); }
        });
        payload.jumlah_harga = parseFloat($('#jumlah-harga').text().replace(/,/g, '')) || 0;
        payload._token = CSRF_TOKEN;
        return payload;
    }

    function blockUI(message) {
        $('#loading-overlay').removeClass('success');
        $('#loading-text').text(message || 'Menyimpan...');
        $('#loading-overlay').addClass('active');
    }

    function unblockUI() {
        $('#loading-overlay').removeClass('active success');
    }

    function showSuccess(message, callback) {
        $('#loading-text').text(message || 'Berjaya!');
        $('#loading-overlay').addClass('success');
        setTimeout(function() {
            unblockUI();
            if (callback) callback();
        }, 1200);
    }

    function doSave(callback) {
        if (!STORE_URL || !pricingData) return;
        $.post(STORE_URL, buildPayload())
            .done(function(res) {
                if (callback) { callback(res); }
            })
            .fail(function() {
                unblockUI();
                alert('Ralat semasa menyimpan. Sila cuba lagi.');
            });
    }

    $('#btn-simpan').on('click', function() {
        blockUI('Menyimpan...');
        doSave(function() {
            showSuccess('Berjaya disimpan!');
        });
    });

    $('#btn-selesai').on('click', function() {
        blockUI('Menghantar...');
        doSave(function() {
            $.post(SUBMIT_URL, { _token: CSRF_TOKEN })
                .done(function(res) {
                    if (res && res.success) {
                        showSuccess('Berjaya dihantar!', function() {
                            window.location.href = KEMBALI_URL;
                        });
                    } else {
                        unblockUI();
                        alert(res.message || 'Ralat semasa menghantar.');
                    }
                })
                .fail(function() {
                    unblockUI();
                    alert('Ralat semasa menghantar. Sila cuba lagi.');
                });
        });
    });

    // ── Amount input formatting ──────────────────────────────────────────────
    $(document).on('focus', '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $(document).on('blur', '.amount-input', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatAmount(parseAmount(val)));
        updateJumlahHarga();
    });

    $(document).on('input', '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

});
</script>
@endsection
