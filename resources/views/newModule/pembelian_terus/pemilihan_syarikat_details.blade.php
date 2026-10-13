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
        if (typeof showBerjayaModal === 'function') {
            showBerjayaModal({ message: 'Pemilihan Syarikat telah berjaya' });
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

    .summary-row {
        background: var(--sg-bg) !important;
        font-weight: 600;
    }
</style>

@endsection

