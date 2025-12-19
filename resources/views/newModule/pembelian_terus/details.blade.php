@extends('layouts.v3.master')

@section('content')

<style>
    /* Progress Bar Styles */
    .progress-wrapper {
        position: relative;
    }

    .progress-step {
        position: relative;
        z-index: 1;
        flex: 1;
    }

    /* Connector line */
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 18px;
        /* center of circle (36px / 2) */
        left: 50%;
        width: 100%;
        height: 3px;
        background: var(--topbar-border, #e5e7eb);
        z-index: 0;
    }

    /* Active / done line */
    .progress-step.active:not(:last-child)::after,
    .progress-step.done:not(:last-child)::after {
        background: var(--sg-red);
    }

    /* Reset future steps */
    .progress-step.active~.progress-step:not(:last-child)::after {
        background: var(--topbar-border, #e5e7eb);
    }

    /* Step circle */
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--topbar-border, #e5e7eb);
        color: var(--topbar-text, #374151);
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Active & done states */
    .progress-step.active .step-number,
    .progress-step.done .step-number {
        background: var(--sg-red);
        color: #fff;
    }

    .step-label {
        font-size: 13px;
        color: var(--topbar-text, #374151);
    }

    .progress-step.active .step-label,
    .progress-step.done .step-label {
        color: var(--sg-red-dark);
    }

    /* Form Styles */
    .form-title {
        font-size: 18px;
        font-weight: bold;
        color: var(--sg-red-dark);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--topbar-border, #e5e7eb);
    }

    .form-label.required::after {
        content: " *";
        color: #dc3545;
    }

    .form-control:read-only,
    .form-select:disabled,
    textarea:read-only {
        background-color: #f9fafb;
        cursor: not-allowed;
        /* light grey */
        color: #6b7280;
        font-weight: 500;
    }

    .form-control:read-only:focus,
    textarea:read-only:focus {
        border-color: #d1d5db;
        box-shadow: none;
    }

    /* Button Styles */
    .btn-kembali {
        background: var(--sg-red);
        color: white;
        border: none;
    }

    .btn-kembali:hover {
        background: var(--sg-red-deep);
        color: white;
    }

    .btn-papar {
        background: #10b981;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-papar:hover {
        background: #059669;
        color: white;
    }

    .btn-sebelumnya {
        background: var(--topbar-text, #374151);
        color: white;
        border: none;
    }

    .btn-sebelumnya:hover {
        background: var(--sg-black);
        color: white;
    }

    .btn-simpan {
        background: var(--sg-bg);
        color: var(--sg-black);
        border: 1px solid var(--sg-black);
    }

    .btn-simpan:hover {
        background: var(--sg-black);
        color: var(--sg-bg);
        border-color: var(--sg-black);
    }

    .btn-seterusnya {
        background: var(--sg-red);
        color: white;
        border: none;
    }

    .btn-seterusnya:hover {
        background: var(--sg-red-deep);
        color: white;
    }

    .btn-or-dan {
        background: var(--sg-red);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: not-allowed;
    }

    .btn-or-dan:hover {
        background: var(--sg-red);
        color: white;
    }

    .spec-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .spec-table thead {
        background: var(--sg-red);
        color: white;
    }

    .spec-table th,
    .spec-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--topbar-border, #e5e7eb);
    }

    .spec-table th {
        font-weight: bold;
        text-align: center;
        color: white;
    }

    .spec-table tbody tr {
        background: white;
    }

    .spec-table tbody tr:hover {
        background: var(--sg-bg);
    }

    .spec-table td {
        color: var(--sg-black);
    }

    .summary-row {
        background: var(--sg-bg) !important;
        font-weight: 600;
    }

    .btn-muat-naik {
        background: #ffcc00 !important;
        color: #000 !important;
        border: none !important;
    }

    .btn-muat-naik:hover,
    .btn-muat-naik:focus,
    .btn-muat-naik:active {
        background: #e6b800 !important;
        color: #000 !important;
    }

    .btn-muat-naik:disabled,
    .btn-muat-naik.disabled {
        background: #ffcc00 !important;
        color: #000 !important;
        opacity: 0.65;
    }

    .btn-muat-naik-dokumen {
        background: var(--sg-yellow, #ffcc00);
        color: var(--sg-black, #000);
        border: none;
    }

    .btn-muat-naik-dokumen:hover {
        background: #e6b800;
        color: var(--sg-black, #000);
    }

    .btn-selesai {
        background: var(--sg-red);
        color: white;
        border: none;
    }

    .btn-selesai:hover {
        background: var(--sg-red-deep);
        color: white;
    }

    .document-link {
        color: var(--sg-red);
        text-decoration: underline;
        cursor: pointer;
    }

    .document-link:hover {
        color: var(--sg-red-deep);
    }

    .modal-title {
        color: var(--sg-red-dark);
    }

    .upload-area {
        border: 2px dashed var(--topbar-border, #e5e7eb);
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        background: var(--sg-bg);
        cursor: not-allowed;
    }

    .upload-area svg {
        width: 48px;
        height: 48px;
        color: var(--topbar-text, #374151);
        margin-bottom: 16px;
    }

    /* Success Modal Styles */
    .success-modal .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .success-modal .modal-body {
        padding: 40px 30px;
        text-align: center;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success-message {
        font-size: 20px;
        font-weight: bold;
        color: #000;
        margin-bottom: 30px;
    }

    .btn-tutup {
        background: var(--sg-black);
        color: var(--sg-bg);
        border: none;
        padding: 10px 30px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 16px;
    }

    .btn-tutup:hover {
        background: var(--sg-black);
        color: var(--sg-bg);
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb Navigation --}}
        <nav class="py-2 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item active fw-semibold">Butiran Projek Pembelian Terus</li>
            </ol>
        </nav>

        {{-- Progress Bar - Step 1 Active --}}
        

        <div class="progress-wrapper d-flex align-items-center my-4 my-md-5 px-3 px-md-4">
            <div class="progress-step text-center active" id="step1Indicator">
                <div class="step-number mx-auto mb-2 fw-bold">1</div>
                <div class="step-label fw-semibold">Paparan Projek</div>
            </div>

            <div class="progress-step text-center" id="step2Indicator">
                <div class="step-number mx-auto mb-2 fw-bold">2</div>
                <div class="step-label fw-semibold">Maklumat Spesifikasi</div>
            </div>

            <div class="progress-step text-center" id="step3Indicator">
                <div class="step-number mx-auto mb-2 fw-bold">3</div>
                <div class="step-label fw-semibold">Harga SST</div>
            </div>
        </div>

        {{-- STEP 1: Paparan Projek --}}
        <div id="step1Content">
            <h4 class="form-title">PAPARAN PROJEK UNTUK PEMBELIAN TERUS</h4>

            <form>

                <div class="d-flex align-items-center mb-3">
                    <label class="form-label required w-25 me-3 text-end mb-0">Tajuk Perolehan</label>
                    <div class="flex-fill">
                        <textarea class="form-control" rows="3" placeholder="Masukkan tajuk perolehan" readonly>{{ $project->tajuk_perolehan ?? 'BEKALAN BARANGAN PERSEKOLAHAN' }}</textarea>
                    </div>
                </div>



                <div class="d-flex align-items-center mb-3">
                    <label class="form-label required w-25 me-3 text-end mb-0">No. Rujukan Fail</label>
                    <div class="flex-fill">
                        <input type="text" class="form-control" value="{{ $project->no_rujukan_fail ?? 'SH/DF/TRG' }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-4 text-end mb-0">Tarikh Buka</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" value="{{ $project->tarikh_buka ?? '17/09/2021' }}" placeholder="DD/MM/YYYY" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-4 text-end mb-0">Tarikh Tutup</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" value="{{ $project->tarikh_tutup ?? '17/10/2021' }}" placeholder="DD/MM/YYYY" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label required w-50 me-4 text-end mb-0">Kategori Perolehan</label>
                            <div class="flex-fill">
                                <select class="form-select" disabled>
                                    <option value="{{ $project->kategori_perolehan ?? 'ICT' }}" selected>{{ $project->kategori_perolehan ?? 'ICT' }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Empty column for alignment --}}
                    </div>
                </div>

                {{-- KOD BIDANG Section --}}
                <h4 class="form-title mt-5">KOD BIDANG</h4>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">
                            <label class="form-label w-25 me-3 text-end mb-0">Kod Bidang MOF</label>
                            <div class="flex-fill">
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control" value="{{ $project->kod_bidang_mof_1 ?? '' }}" readonly>
                                        <button type="button" class="btn btn-or-dan" disabled>Atau</button>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control" value="{{ $project->kod_bidang_mof_2 ?? '' }}" readonly>
                                        <button type="button" class="btn btn-or-dan" disabled>Dan</button>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control" value="{{ $project->kod_bidang_mof_3 ?? '' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-3 text-end mb-0">Gred CIDB</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" value="{{ $project->gred_cidb ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Empty column for alignment --}}
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="showStep(2)">Seterusnya</button>
                </div>
            </form>
        </div>

        {{-- STEP 2: Maklumat Spesifikasi --}}
        <div class="d-none" id="step2Content">
            <h4 class="form-title">MAKLUMAT SPESIFIKASI KAJIAN</h4>

            <table class="spec-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Bil.</th>
                        <th>item</th>
                        <th style="width: 150px;">Kuantiti</th>
                        <th style="width: 120px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody id="specTableBody">
                    @php
                    $specifications = $specifications ?? [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'brand' => 'Acer', 'harga_seunit' => '10,000.00', 'harga_keseluruhan' => '100,000.00', 'harga_sst' => '100,0008.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'brand' => 'HP', 'harga_seunit' => '5,000.00', 'harga_keseluruhan' => '50,000.00', 'harga_sst' => '50,0004.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'brand' => 'Epson', 'harga_seunit' => '8,000.00', 'harga_keseluruhan' => '80,000.00', 'harga_sst' => '80,0006.40']
                    ];
                    @endphp
                    @foreach($specifications as $index => $spec)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $spec['item'] ?? '-' }}</td>
                        <td>{{ $spec['kuantiti'] ?? '-' }}</td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-papar px-3 py-1 rounded fw-bold" data-item-index="{{ $index }}" onclick="openItemModal(this)">Papar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-sebelumnya px-4 py-2 rounded fw-bold" onclick="showStep(1)">Sebelumnya</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold">Simpan</button>
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="showStep(3)">Seterusnya</button>
                </div>
            </div>
        </div>

        {{-- STEP 3: Harga SST --}}
        <div class="d-none" id="step3Content">
            <h4 class="form-title">JUMLAH SEBUT HARGA TERMASUK SST</h4>

            <table class="spec-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Bil.</th>
                        <th>Item</th>
                        <th style="width: 150px;">Kuantiti</th>
                        <th style="width: 200px;">Harga Keseluruhan (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sstItems = $sstItems ?? [
                            ['item' => 'MONITOR', 'kuantiti' => '10', 'harga_keseluruhan' => '100,000.00'],
                            ['item' => 'PRINTER', 'kuantiti' => '10', 'harga_keseluruhan' => '190,000.00'],
                            ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga_keseluruhan' => '90,000.00']
                        ];
                        $totalHarga = 380000.00;
                        $totalHargaSST = 410400.00;
                    @endphp
                    @foreach($sstItems as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $item['item'] ?? '-' }}</td>
                            <td>{{ $item['kuantiti'] ?? '-' }}</td>
                            <td style="text-align: right;">{{ $item['harga_keseluruhan'] ?? '0.00' }}</td>
                        </tr>
                    @endforeach
                    <tr class="summary-row">
                        <td colspan="3" style="text-align: right; padding-right: 20px;">Harga Keseluruhan bagi semua Item</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($totalHarga, 2, '.', ',') }}</td>
                    </tr>
                    <tr class="summary-row">
                        <td colspan="3" style="text-align: right; padding-right: 20px;">Harga Termasuk SST bagi semua Item</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($totalHargaSST, 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Document Upload Section --}}
            <div class="d-flex align-items-center justify-content-end gap-3 mt-4 mb-4">
                <a href="#" class="document-link">DokumenQuotation.pdf</a>
                <button type="button" class="btn btn-muat-naik px-4 py-2 rounded fw-bold">Muat Naik Quotation</button>
            </div>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-sebelumnya px-4 py-2 rounded fw-bold" onclick="showStep(2)">Sebelumnya</button>
                <button type="button" class="btn btn-selesai px-4 py-2 rounded fw-bold" onclick="showSuccessModal()">Selesai</button>
            </div>
        </div>

    </div>
</div>

{{-- Modal: Success --}}
<div class="modal fade success-modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                        <!-- Party Popper Cone (Green) -->
                        <path d="M50 15 L35 45 L50 40 L65 45 Z" fill="#10b981"/>
                        <path d="M50 15 L45 30 L50 25 L55 30 Z" fill="#0d9488"/>
                        <!-- Confetti Stream (Blue) -->
                        <path d="M50 15 Q45 20 42 30 Q40 40 38 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path d="M50 15 Q55 20 58 30 Q60 40 62 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path d="M50 15 Q50 20 50 30 Q50 40 50 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <!-- Scattered Confetti Pieces -->
                        <circle cx="25" cy="35" r="4" fill="#10b981"/>
                        <circle cx="75" cy="40" r="4" fill="#3b82f6"/>
                        <circle cx="30" cy="55" r="3" fill="#3b82f6"/>
                        <circle cx="70" cy="50" r="3" fill="#10b981"/>
                        <rect x="20" y="45" width="5" height="5" fill="#10b981" transform="rotate(45 22.5 47.5)"/>
                        <rect x="75" y="55" width="5" height="5" fill="#3b82f6" transform="rotate(45 77.5 57.5)"/>
                        <circle cx="40" cy="25" r="3" fill="#3b82f6"/>
                        <circle cx="60" cy="28" r="3" fill="#10b981"/>
                    </svg>
                </div>
                <div class="success-message">
                    Sebut Harga Pembelian Terus telah Selesai
                </div>
                <button type="button" class="btn btn-tutup" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Butiran Item --}}
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Butiran Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
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
                        <input type="text" class="form-control" id="modalBrand" value="Acer" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga Seunit (RM)</label>
                        <input type="text" class="form-control" id="modalHargaSeunit" value="10,000.00" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga Keseluruhan (RM)</label>
                        <input type="text" class="form-control" id="modalHargaKeseluruhan" value="100,000.00" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga termasuk SST</label>
                        <input type="text" class="form-control" id="modalHargaSST" value="100,0008.00" readonly>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Muat Naik Dokumen Sokongan</h6>
                        <p class="text-muted small mb-3">Sila Muat Naik Dokumen Sokongan di ruangan bawah.</p>
                        <div class="upload-area">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <button type="button" class="btn btn-muat-naik-dokumen" disabled>Muat Naik Dokumen</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-papar px-4 py-2 rounded fw-bold" data-bs-dismiss="modal">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('[id$="Content"]').forEach(content => {
            content.classList.add('d-none');
            content.classList.remove('d-block');
        });

        // Remove active and done classes from all step indicators
        document.querySelectorAll('.progress-step').forEach(indicator => {
            indicator.classList.remove('active', 'done');
        });

        // Mark previous steps as done - only for steps 1-3
        for (let i = 1; i < step && i <= 3; i++) {
            const prevIndicator = document.getElementById('step' + i + 'Indicator');
            if (prevIndicator) prevIndicator.classList.add('done');
        }

        // Mark current step as active - only for steps 1-3
        if (step <= 3) {
            const stepIndicator = document.getElementById('step' + step + 'Indicator');
            if (stepIndicator) stepIndicator.classList.add('active');
        }

        // Show selected step content
        const stepContent = document.getElementById('step' + step + 'Content');
        if (stepContent) {
            stepContent.classList.remove('d-none');
            stepContent.classList.add('d-block');
        }

        currentStep = step;
    }

    // Make showStep available globally
    window.showStep = showStep;

    function openItemModal(button) {
        const index = parseInt(button.getAttribute('data-item-index')) || 0;

        // Get data from PHP array (passed via data attributes or use default)
        const itemData = [{
                item: 'MONITOR',
                kuantiti: '10',
                brand: 'Acer',
                hargaSeunit: '10,000.00',
                hargaKeseluruhan: '100,000.00',
                hargaSST: '100,0008.00'
            },
            {
                item: 'PRINTER',
                kuantiti: '10',
                brand: 'HP',
                hargaSeunit: '5,000.00',
                hargaKeseluruhan: '50,000.00',
                hargaSST: '50,0004.00'
            },
            {
                item: 'PROJECTOR',
                kuantiti: '10',
                brand: 'Epson',
                hargaSeunit: '8,000.00',
                hargaKeseluruhan: '80,000.00',
                hargaSST: '80,0006.40'
            }
        ];

        const data = itemData[index] || {};

        document.getElementById('modalItemName').value = data.item || '';
        document.getElementById('modalKuantiti').value = data.kuantiti || '';
        document.getElementById('modalBrand').value = data.brand || '';
        document.getElementById('modalHargaSeunit').value = data.hargaSeunit || '';
        document.getElementById('modalHargaKeseluruhan').value = data.hargaKeseluruhan || '';
        document.getElementById('modalHargaSST').value = data.hargaSST || '';

        const modalElement = document.getElementById('itemModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(modalElement).show();
        }
    }

    function showSuccessModal() {
        const modalElement = document.getElementById('successModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    // Initialize step 1 as active when page loads
    document.addEventListener('DOMContentLoaded', function() {
        showStep(1);
    });
</script>

@endsection