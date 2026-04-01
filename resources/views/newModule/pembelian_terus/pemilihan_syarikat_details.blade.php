@extends('layouts.v3.master')

@section('content')

<style>
    /* Form Styles */
    .form-title {
        font-size: 18px;
        font-weight: bold;
        color: var(--sg-red-dark);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--topbar-border, #e5e7eb);
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .document-link:hover {
        color: var(--sg-red-deep);
    }

    .sort-icon {
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .sort-icon:hover {
        opacity: 1;
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb Navigation --}}
        <nav class="py-2 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Petender Berjaya Konvensional</a></li>
                <li class="breadcrumb-item active fw-semibold">Konvensional</li>
            </ol>
        </nav>

        {{-- Header & Project Information --}}
        <div class="mb-4">
            <div class="mb-3">
                <span class="fw-bold me-2">No. Sebut Harga / Tender:</span>
                <span>{{ $tender_no }}</span>
            </div>
            <div class="mb-3">
                <span class="fw-bold me-2">Tajuk Perolehan:</span>
                <span>BEKALAN BARANGAN PERSEKOLAHAN</span>
            </div>
        </div>

        {{-- Pemilihan Syarikat (Supplier List) --}}
        <div class="mb-4">
            <h4 class="form-title mb-3">PEMILIHAN SYARIKAT</h4>

            <div class="table-responsive">
                <table class="spec-table">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">Bil.</th>
                            <th>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Nama Pembekal</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sort-icon">
                                        <path d="M12 5v14"></path>
                                        <path d="M19 12l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </th>
                            <th style="width: 200px; text-align: right;">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <span>Harga termasuk SST (RM)</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sort-icon">
                                        <path d="M12 5v14"></path>
                                        <path d="M19 12l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </th>
                            <th style="width: 100px; text-align: center;">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor: pointer;">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    </svg>
                                    <span>Pilih</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $index => $supplier)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>
                                <span style="cursor: pointer; color: var(--sg-red); text-decoration: underline;" data-supplier-index="{{ $index }}" onclick="openSupplierModal(this)">{{ $supplier['name'] }}</span>
                            </td>
                            <td style="text-align: right;">{{ $supplier['price'] }}</td>
                            <td style="text-align: center;">
                                <input type="radio" class="form-check-input" name="selected_supplier" value="{{ $index }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Dokumen Sokongan (Supporting Documents) --}}
        <div class="mb-4">
            <h4 class="form-title mb-3">DOKUMEN SOKONGAN</h4>

            <div class="mb-3">
                <span class="fw-bold me-2">JPICT:</span>
                <a href="#" class="document-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></line>
                    </svg>
                    JPICT.pdf
                </a>
            </div>

            <div class="mb-3">
                <span class="fw-bold me-2">Minit Bebas:</span>
                <a href="#" class="document-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></line>
                    </svg>
                    MinitBebas.pdf
                </a>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-end gap-3 mt-4">
            <button type="button" class="btn px-4 py-2 rounded fw-bold" style="background: #20b2aa; color: white; border: none;" onclick="generateReport()">
                Laporan
            </button>
            <button type="button" class="btn btn-selesai px-4 py-2 rounded fw-bold" onclick="showSuccessModal()">
                Selesai
            </button>
        </div>

    </div>
</div>

{{-- Modal: Supplier Details --}}
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel">Butiran Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fw-bold mb-2">NAMA PEMBEKAL</label>
                    <div id="modalSupplierName" class="fw-semibold"></div>
                </div>

                <table class="spec-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Bil.</th>
                            <th>Item</th>
                            <th style="width: 120px; text-align: center;">Kuantiti</th>
                            <th style="width: 200px; text-align: right;">Harga Keseluruhan (RM)</th>
                        </tr>
                    </thead>
                    <tbody id="modalItemsBody">
                        <!-- Items will be populated by JavaScript -->
                    </tbody>
                    <tbody>
                        <tr class="summary-row">
                            <td colspan="3" style="text-align: right; padding-right: 20px;">Harga Keseluruhan bagi semua Item</td>
                            <td style="text-align: right; font-weight: bold;" id="modalTotalPrice">0.00</td>
                        </tr>
                        <tr class="summary-row">
                            <td colspan="3" style="text-align: right; padding-right: 20px;">Harga Termasuk SST bagi semua Item</td>
                            <td style="text-align: right; font-weight: bold;" id="modalTotalPriceSST">0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-papar px-4 py-2 rounded fw-bold" data-bs-dismiss="modal">Tutup</button>
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
                        <path d="M50 15 L35 45 L50 40 L65 45 Z" fill="#10b981" />
                        <path d="M50 15 L45 30 L50 25 L55 30 Z" fill="#0d9488" />
                        <path d="M50 15 Q45 20 42 30 Q40 40 38 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round" />
                        <path d="M50 15 Q55 20 58 30 Q60 40 62 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round" />
                        <path d="M50 15 Q50 20 50 30 Q50 40 50 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round" />
                        <circle cx="25" cy="35" r="4" fill="#10b981" />
                        <circle cx="75" cy="40" r="4" fill="#3b82f6" />
                        <circle cx="30" cy="55" r="3" fill="#3b82f6" />
                        <circle cx="70" cy="50" r="3" fill="#10b981" />
                        <rect x="20" y="45" width="5" height="5" fill="#10b981" transform="rotate(45 22.5 47.5)" />
                        <rect x="75" y="55" width="5" height="5" fill="#3b82f6" transform="rotate(45 77.5 57.5)" />
                        <circle cx="40" cy="25" r="3" fill="#3b82f6" />
                        <circle cx="60" cy="28" r="3" fill="#10b981" />
                    </svg>
                </div>
                <div class="success-message">
                    Pemilihan Syarikat telah berjaya
                </div>
                <button type="button" class="btn btn-tutup" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Supplier details data from controller
    const supplierDetails = {!! json_encode($suppliers ?? []) !!};

    function openSupplierModal(element) {
        const index = parseInt(element.getAttribute('data-supplier-index')) || 0;
        const supplier = supplierDetails[index];
        if (!supplier) return;

        // Set supplier name
        document.getElementById('modalSupplierName').textContent = supplier.name;

        // Populate items table
        const itemsBody = document.getElementById('modalItemsBody');
        itemsBody.innerHTML = '';
        supplier.items.forEach((item, idx) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td style="text-align: center;">${idx + 1}</td>
                <td>${item.item}</td>
                <td style="text-align: center;">${item.kuantiti}</td>
                <td style="text-align: right;">${item.harga}</td>
            `;
            itemsBody.appendChild(row);
        });

        // Set totals
        document.getElementById('modalTotalPrice').textContent = supplier.totalPrice;
        document.getElementById('modalTotalPriceSST').textContent = supplier.totalPriceSST;

        // Show modal
        const modalElement = document.getElementById('supplierModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    function generateReport() {
        alert('Laporan sedang dijana...');
    }

    function showSuccessModal() {
        const modalElement = document.getElementById('successModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }
</script>

<style>
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

    .summary-row {
        background: var(--sg-bg) !important;
        font-weight: 600;
    }
</style>

@endsection

