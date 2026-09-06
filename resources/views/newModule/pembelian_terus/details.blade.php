@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* --- TIMELINE (3 steps) --- */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 40px;
        }

        .stepper-track {
            position: absolute;
            top: 15px;
            left: 40px;
            right: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            z-index: 0;
            overflow: hidden;
        }

        .stepper-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: var(--sg-red);
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        .stepper-wrapper[data-step="1"] .stepper-progress { width: 0%; }
        .stepper-wrapper[data-step="2"] .stepper-progress { width: 50%; }
        .stepper-wrapper[data-step="3"] .stepper-progress { width: 100%; }

        .stepper-item {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .step-counter {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #cbd5e1;
            color: #94a3b8;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .step-name {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            transition: color 0.3s ease;
            text-align: center;
        }

        .stepper-item.active .step-counter {
            border-color: var(--sg-red);
            background: var(--sg-red);
            color: #fff;
            box-shadow: 0 0 0 5px var(--sg-red-soft), 0 2px 8px rgba(196, 30, 58, 0.3);
            transform: scale(1.05);
        }

        .stepper-item.active .step-name { color: var(--sg-red); }

        .stepper-item.completed .step-counter {
            border-color: #10b981;
            background: #10b981;
            color: #fff;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }

        .stepper-item.completed .step-counter::after { content: '✓'; font-size: 0.9rem; }
        .stepper-item.completed .step-counter span { display: none; }
        .stepper-item.completed .step-name { color: #10b981; }

        /* --- PROJECT SUMMARY --- */
        .project-summary {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid var(--sg-red);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .project-summary-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 22px;
            flex: 1;
            min-width: 240px;
        }

        .project-summary-item .ps-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(196, 30, 58, 0.08);
            color: var(--sg-red);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-summary-item .ps-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .project-summary-item .ps-label {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .project-summary-item .ps-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.35;
        }

        .project-summary .ps-divider {
            width: 1px;
            background: #f1f5f9;
            align-self: stretch;
            margin: 12px 0;
        }

        .spec-table thead th {
            background: #f8fafc;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6b7280;
            border-color: #e5e7eb;
        }

        .spec-table .summary-row {
            background: #f8fafc !important;
            font-weight: 600;
        }

        .item-filled {
            color: #059669;
            font-size: 0.72rem;
            font-weight: 600;
            margin-top: 2px;
        }

        .form-label.required::after { content: " *"; color: #dc3545; }

        .upload-area {
            border: 2px dashed #e5e7eb;
            border-radius: 10px;
            padding: 28px 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
        }

        .upload-area.disabled { cursor: not-allowed; opacity: .65; }
        .upload-area svg { color: #94a3b8; margin-bottom: 12px; }

        .modal-title { color: var(--sg-red-dark); }

        @media (max-width: 767px) {
            .project-summary .ps-divider { display: none; }
            .project-summary-item {
                flex: 1 1 100%;
                border-bottom: 1px solid #f1f5f9;
            }
            .project-summary-item:last-child { border-bottom: none; }
        }

        @media (max-width: 991px) {
            .stepper-wrapper { padding: 0 20px; }
            .stepper-track { left: 40px; right: 40px; }
            .step-name { font-size: 0.65rem; }
            .step-counter { width: 30px; height: 30px; font-size: 0.75rem; }
        }
    </style>
@endsection

@section('content')
    @php
        $p = $p ?? $project ?? (object) [];
        $specifications = $specifications ?? [];
        $mofLabels = $mofLabels ?? [];
        $cidbGradeLabels = $cidbGradeLabels ?? [];
        $kategoriName = $kategoriName ?? '-';
        $ptjName = $ptjName ?? '-';
        $canSubmitOffer = $canSubmitOffer ?? false;
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Sebut Harga Pembelian Terus</h3>
            <p class="text-muted small m-0">Paparan maklumat projek dan penghantaran sebut harga pembelian terus.</p>
        </div>
    </div>

    @if (!empty($eligibilityMessage))
        <div class="alert alert-warning border-0 shadow-sm mb-3" role="alert">{{ $eligibilityMessage }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3" role="alert">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3" role="alert">{{ session('success') }}</div>
    @endif

    <!-- PROJECT SUMMARY -->
    <div class="project-summary mb-4">
        <div class="project-summary-item">
            <div class="ps-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div class="ps-text">
                <span class="ps-label">No. Sebut Harga / Tender</span>
                <span class="ps-value">{{ $p->no_tender ?: ($p->ref_number ?? '-') }}</span>
            </div>
        </div>
        <div class="ps-divider"></div>
        <div class="project-summary-item" style="flex: 2;">
            <div class="ps-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </div>
            <div class="ps-text">
                <span class="ps-label">Tajuk Perolehan</span>
                <span class="ps-value">{{ $p->name ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- TIMELINE -->
    <div class="stepper-wrapper" data-step="1" id="stepper-wrapper">
        <div class="stepper-track">
            <div class="stepper-progress" id="stepper-progress"></div>
        </div>
        <div class="stepper-item active" id="step1-indicator">
            <div class="step-counter"><span>1</span></div>
            <div class="step-name">Paparan Projek</div>
        </div>
        <div class="stepper-item" id="step2-indicator">
            <div class="step-counter"><span>2</span></div>
            <div class="step-name">Maklumat Spesifikasi</div>
        </div>
        <div class="stepper-item" id="step3-indicator">
            <div class="step-counter"><span>3</span></div>
            <div class="step-name">Harga SST</div>
        </div>
    </div>

    <form id="offerForm" action="{{ route('pembelianTerus.submitOffer', $p->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="offerItemsContainer"></div>

        <div class="modern-card">

            {{-- STEP 1 --}}
            <div id="step1-content">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Projek</span>
                </div>

                <div class="p-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Tajuk Perolehan</label>
                            <textarea class="form-control" rows="2" disabled>{{ $p->name ?? '-' }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Disediakan Untuk PTJ</label>
                            <input type="text" class="form-control" value="{{ strtoupper($ptjName) }}" disabled>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Rujukan Fail</label>
                            <input type="text" class="form-control" value="{{ $p->ref_number ?: ($p->no_tender ?? '-') }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Indikatif Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="text" class="form-control text-end"
                                    value="{{ !empty($p->harga_indikatif) ? number_format((float) $p->harga_indikatif, 2) : '' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Buka</label>
                            <input type="text" class="form-control" value="{{ $p->tarikh_buka ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Tutup</label>
                            <input type="text" class="form-control" value="{{ $p->tarikh_tutup ?? '-' }}" disabled>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori Perolehan</label>
                            <input type="text" class="form-control" value="{{ $kategoriName }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gred CIDB</label>
                            <input type="text" class="form-control"
                                value="{{ count($cidbGradeLabels) ? implode(', ', $cidbGradeLabels) : '-' }}" disabled>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Kod Bidang MOF</label>
                        @forelse ($mofLabels as $label)
                            <input type="text" class="form-control mb-2" value="{{ $label }}" disabled>
                        @empty
                            <input type="text" class="form-control" value="-" disabled>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- STEP 2 --}}
            <div id="step2-content" class="d-none">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Spesifikasi Kajian</span>
                </div>

                <div class="p-4">
                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div class="small mt-1">
                            Klik <strong>Papar</strong> bagi setiap item untuk mengisi brand, harga seunit dan dokumen sokongan.
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table spec-table align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="py-3 ps-4 fw-bold" width="70px">Bil.</th>
                                    <th class="py-3 fw-bold">Item</th>
                                    <th class="py-3 fw-bold text-center" width="120px">Kuantiti</th>
                                    <th class="py-3 pe-4 fw-bold text-center" width="140px">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="specTableBody">
                                @forelse ($specifications as $index => $spec)
                                    <tr data-index="{{ $index }}">
                                        <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            {{ $spec['item'] ?? '-' }}
                                            <div class="item-filled d-none" id="filled-badge-{{ $index }}">✓ Dilengkapkan</div>
                                        </td>
                                        <td class="text-center">{{ $spec['kuantiti'] ?? '-' }}</td>
                                        <td class="pe-4 text-center">
                                            <button type="button" class="btn-form btn-form-success"
                                                data-item-index="{{ $index }}" onclick="openItemModal(this)">Papar</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tiada item spesifikasi untuk projek ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- STEP 3 --}}
            <div id="step3-content" class="d-none">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Jumlah Sebut Harga Termasuk SST</span>
                </div>

                <div class="p-4">
                    <div class="table-responsive mb-4">
                        <table class="table spec-table align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="py-3 ps-4 fw-bold" width="70px">Bil.</th>
                                    <th class="py-3 fw-bold">Item</th>
                                    <th class="py-3 fw-bold text-center" width="120px">Kuantiti</th>
                                    <th class="py-3 pe-4 fw-bold text-end" width="200px">Harga Keseluruhan (RM)</th>
                                </tr>
                            </thead>
                            <tbody id="sstTableBody"></tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-3 mb-2">
                        <span id="quotationFileName" class="small fw-semibold text-primary d-none"></span>
                        <label class="btn-form btn-form-create mb-0 {{ $canSubmitOffer ? '' : 'disabled' }}"
                            for="quotationInput" style="cursor: {{ $canSubmitOffer ? 'pointer' : 'not-allowed' }};">
                            Muat Naik Quotation
                        </label>
                        <input type="file" id="quotationInput" name="quotation" class="d-none"
                            accept=".pdf,.doc,.docx,.xls,.xlsx" {{ $canSubmitOffer ? '' : 'disabled' }}>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="bg-light p-4 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn-form btn-form-secondary d-none" id="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Sebelumnya
                </button>

                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn-form btn-form-primary" id="btn-next">
                        Seterusnya
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>

                    <button type="submit" class="btn-form btn-form-success d-none" id="btn-submit"
                        {{ $canSubmitOffer ? '' : 'disabled' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Modal: Butiran Item --}}
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="itemModalLabel">Butiran Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="modalItemIndex">
                    <div class="mb-3">
                        <label class="form-label">Nama Item</label>
                        <input type="text" class="form-control" id="modalItemName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuantiti</label>
                        <input type="text" class="form-control" id="modalKuantiti" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Brand</label>
                        <input type="text" class="form-control" id="modalBrand" {{ $canSubmitOffer ? '' : 'readonly' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga Seunit (RM)</label>
                        <input type="text" class="form-control" id="modalHargaSeunit" inputmode="decimal"
                            {{ $canSubmitOffer ? '' : 'readonly' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga Keseluruhan (RM)</label>
                        <input type="text" class="form-control" id="modalHargaKeseluruhan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga termasuk SST</label>
                        <input type="text" class="form-control" id="modalHargaSST" readonly>
                    </div>

                    <div class="mb-1">
                        <h6 class="fw-bold mb-2">Muat Naik Dokumen Sokongan</h6>
                        <p class="text-muted small mb-3">Sila muat naik dokumen sokongan di ruangan bawah.</p>
                        <label class="upload-area d-block {{ $canSubmitOffer ? '' : 'disabled' }}" for="modalDokumenInput">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <span class="btn-form btn-form-create">Muat Naik Dokumen</span>
                            <div id="modalDokumenName" class="small text-muted mt-2"></div>
                        </label>
                        <input type="file" id="modalDokumenInput" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx"
                            {{ $canSubmitOffer ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-success" id="btnSaveItem"
                        {{ $canSubmitOffer ? '' : 'disabled' }}>Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
(function () {
    const specs = @json($specifications);
    const canSubmit = @json((bool) $canSubmitOffer);
    const SST_RATE = 0.05;
    const TOTAL_STEPS = 3;
    let currentStep = 1;
    let offerData = specs.map(function (s) {
        return {
            id: s.id,
            item: s.item,
            kuantiti: parseFloat(s.kuantiti) || 0,
            sst: !!s.sst,
            brand: s.brand || '',
            harga_seunit: '',
            harga_keseluruhan: '',
            harga_sst: '',
            dokumen: null,
            filled: false
        };
    });

    function money(n) {
        return (parseFloat(n) || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function parseMoney(v) {
        return parseFloat(String(v || '').replace(/,/g, '')) || 0;
    }

    function showStep(step) {
        currentStep = step;
        $('#stepper-wrapper').attr('data-step', currentStep);

        for (var i = 1; i <= TOTAL_STEPS; i++) {
            $('#step' + i + '-content').toggleClass('d-none', i !== currentStep);
        }

        $('#step1-indicator, #step2-indicator, #step3-indicator').removeClass('active completed');
        for (var j = 1; j < currentStep; j++) {
            $('#step' + j + '-indicator').addClass('completed');
        }
        $('#step' + currentStep + '-indicator').addClass('active');

        $('#btn-back').toggleClass('d-none', currentStep === 1);
        $('#btn-next').toggleClass('d-none', currentStep === TOTAL_STEPS);
        $('#btn-submit').toggleClass('d-none', currentStep !== TOTAL_STEPS);

        if (currentStep === 3) {
            renderSstTable();
        }

        $('html, body').animate({ scrollTop: $('#stepper-wrapper').offset().top - 20 }, 400);
    }

    window.openItemModal = function (button) {
        var index = parseInt(button.getAttribute('data-item-index'), 10) || 0;
        var data = offerData[index] || {};
        document.getElementById('modalItemIndex').value = index;
        document.getElementById('modalItemName').value = data.item || '';
        document.getElementById('modalKuantiti').value = data.kuantiti || '';
        document.getElementById('modalBrand').value = data.brand || '';
        document.getElementById('modalHargaSeunit').value = data.harga_seunit ? money(data.harga_seunit) : '';
        document.getElementById('modalHargaKeseluruhan').value = data.harga_keseluruhan ? money(data.harga_keseluruhan) : '';
        document.getElementById('modalHargaSST').value = data.harga_sst ? money(data.harga_sst) : '';
        document.getElementById('modalDokumenName').textContent = data.dokumen ? data.dokumen.name : '';
        new bootstrap.Modal(document.getElementById('itemModal')).show();
    };

    function recalcModal() {
        var index = parseInt(document.getElementById('modalItemIndex').value, 10) || 0;
        var qty = offerData[index] ? offerData[index].kuantiti : 0;
        var unit = parseMoney(document.getElementById('modalHargaSeunit').value);
        var total = unit * qty;
        var withSst = offerData[index] && offerData[index].sst ? total * (1 + SST_RATE) : total;
        document.getElementById('modalHargaKeseluruhan').value = money(total);
        document.getElementById('modalHargaSST').value = money(withSst);
    }

    function renderSstTable() {
        var body = document.getElementById('sstTableBody');
        var rows = '';
        var total = 0;
        var totalSst = 0;
        offerData.forEach(function (row, i) {
            var keseluruhan = parseFloat(row.harga_keseluruhan) || 0;
            var sst = parseFloat(row.harga_sst) || keseluruhan;
            total += keseluruhan;
            totalSst += sst;
            rows += '<tr>' +
                '<td class="ps-4 fw-semibold">' + (i + 1) + '</td>' +
                '<td>' + (row.item || '-') + '</td>' +
                '<td class="text-center">' + row.kuantiti + '</td>' +
                '<td class="pe-4 text-end">' + money(keseluruhan) + '</td>' +
                '</tr>';
        });
        rows += '<tr class="summary-row"><td colspan="3" class="text-end pe-3">Harga Keseluruhan bagi semua Item</td>' +
            '<td class="pe-4 text-end fw-bold">' + money(total) + '</td></tr>';
        rows += '<tr class="summary-row"><td colspan="3" class="text-end pe-3">Harga Termasuk SST bagi semua Item</td>' +
            '<td class="pe-4 text-end fw-bold">' + money(totalSst) + '</td></tr>';
        body.innerHTML = rows || '<tr><td colspan="4" class="text-center text-muted">Tiada data</td></tr>';
    }

    function syncHiddenInputs() {
        var box = document.getElementById('offerItemsContainer');
        box.innerHTML = '';
        var total = 0;
        var totalSst = 0;
        offerData.forEach(function (row, i) {
            total += parseFloat(row.harga_keseluruhan) || 0;
            totalSst += parseFloat(row.harga_sst) || 0;
            ['id', 'brand', 'harga_seunit', 'harga_keseluruhan', 'harga_sst'].forEach(function (key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'offer_items[' + i + '][' + (key === 'id' ? 'item_id' : key) + ']';
                input.value = row[key] || '';
                box.appendChild(input);
            });

            if (row.dokumen instanceof File) {
                var fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = 'offer_items[' + i + '][dokumen_sokongan]';
                fileInput.className = 'd-none';
                var dt = new DataTransfer();
                dt.items.add(row.dokumen);
                fileInput.files = dt.files;
                box.appendChild(fileInput);
            }
        });
        var t1 = document.createElement('input');
        t1.type = 'hidden'; t1.name = 'total_harga'; t1.value = total.toFixed(2);
        box.appendChild(t1);
        var t2 = document.createElement('input');
        t2.type = 'hidden'; t2.name = 'total_harga_sst'; t2.value = totalSst.toFixed(2);
        box.appendChild(t2);
        var t3 = document.createElement('input');
        t3.type = 'hidden'; t3.name = 'harga_tawaran'; t3.value = totalSst.toFixed(2);
        box.appendChild(t3);
    }

    document.getElementById('modalHargaSeunit').addEventListener('input', recalcModal);

    document.getElementById('btnSaveItem').addEventListener('click', function () {
        if (!canSubmit) return;
        var index = parseInt(document.getElementById('modalItemIndex').value, 10) || 0;
        var brand = document.getElementById('modalBrand').value.trim();
        var unit = parseMoney(document.getElementById('modalHargaSeunit').value);
        if (!brand || unit <= 0) {
            alert('Sila isi Brand dan Harga Seunit.');
            return;
        }
        recalcModal();
        offerData[index].brand = brand;
        offerData[index].harga_seunit = unit;
        offerData[index].harga_keseluruhan = parseMoney(document.getElementById('modalHargaKeseluruhan').value);
        offerData[index].harga_sst = parseMoney(document.getElementById('modalHargaSST').value);
        offerData[index].filled = true;
        var badge = document.getElementById('filled-badge-' + index);
        if (badge) badge.classList.remove('d-none');
        bootstrap.Modal.getInstance(document.getElementById('itemModal')).hide();
    });

    document.getElementById('modalDokumenInput').addEventListener('change', function (e) {
        var index = parseInt(document.getElementById('modalItemIndex').value, 10) || 0;
        var file = e.target.files[0] || null;
        offerData[index].dokumen = file;
        document.getElementById('modalDokumenName').textContent = file ? file.name : '';
    });

    document.getElementById('quotationInput').addEventListener('change', function (e) {
        var file = e.target.files[0];
        var label = document.getElementById('quotationFileName');
        if (file) {
            label.textContent = file.name;
            label.classList.remove('d-none');
        } else {
            label.classList.add('d-none');
        }
    });

    $('#btn-next').on('click', function () {
        if (currentStep === 2) {
            var incomplete = offerData.some(function (r) { return !r.filled; });
            if (offerData.length > 0 && incomplete) {
                alert('Sila lengkapkan butiran setiap item terlebih dahulu.');
                return;
            }
        }
        if (currentStep < TOTAL_STEPS) {
            showStep(currentStep + 1);
        }
    });

    $('#btn-back').on('click', function () {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    document.getElementById('offerForm').addEventListener('submit', function (e) {
        if (!canSubmit) {
            e.preventDefault();
            alert('Akaun syarikat tidak layak menghantar tawaran.');
            return;
        }
        var incomplete = offerData.some(function (r) { return !r.filled; });
        if (incomplete) {
            e.preventDefault();
            alert('Sila lengkapkan butiran setiap item terlebih dahulu.');
            showStep(2);
            return;
        }
        syncHiddenInputs();
    });

    showStep(1);
})();
</script>
@endsection
