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
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: var(--sg-red);
        background: #fef2f2;
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
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Petender Berjaya Konvensional</a></li>
                <li class="breadcrumb-item active fw-semibold">Konvensional</li>
            </ol>
        </nav>

        {{-- Header & Project Information --}}
        <div class="mb-4">
            <div class="mb-3">
                <span class="fw-bold me-2">No. Sebut Harga / Tender:</span>
                <span>QT210000000023741</span>
            </div>
            <div class="mb-3">
                <span class="fw-bold me-2">Tajuk Perolehan:</span>
                <span>BEKALAN BARANGAN PERSEKOLAHAN</span>
            </div>
        </div>

        {{-- Penentuan Cut-Off (Supplier List) --}}
        <div class="mb-4">
            <h4 class="form-title mb-3">PENENTUAN CUT-OFF</h4>

            <div class="table-responsive">
                <table class="spec-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Bil.</th>
                            <th>Nama Pembekal</th>
                            <th style="width: 200px; text-align: right;">Harga Termasuk SST (RM)</th>
                            <th style="width: 100px; text-align: center;">Pilih</th>
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
                                <input type="checkbox" class="form-check-input" name="selected_supplier[]" value="{{ $index }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Muat Naik Dokumen (Upload Documents) --}}
        <div class="mb-4">
            <h4 class="form-title mb-3">MUAT NAIK DOKUMEN</h4>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold mb-2">JPICT</label>
                    <div class="upload-area" style="cursor: pointer;" onclick="document.getElementById('jpictFile').click()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p class="text-muted mb-2 mt-3">Sila Muat Naik fail di sini</p>
                        <input type="file" id="jpictFile" class="d-none" accept=".pdf,.doc,.docx">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold mb-2">Minit Bebas</label>
                    <div class="upload-area" style="cursor: pointer;" onclick="document.getElementById('minitFile').click()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p class="text-muted mb-2 mt-3">Sila Muat Naik fail di sini</p>
                        <input type="file" id="minitFile" class="d-none" accept=".pdf,.doc,.docx">
                    </div>
                </div>
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
                        <!-- Party Popper Cone (Green) -->
                        <path d="M50 15 L35 45 L50 40 L65 45 Z" fill="#10b981" />
                        <path d="M50 15 L45 30 L50 25 L55 30 Z" fill="#0d9488" />
                        <!-- Confetti Stream (Blue) -->
                        <path d="M50 15 Q45 20 42 30 Q40 40 38 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round" />
                        <path d="M50 15 Q55 20 58 30 Q60 40 62 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round" />
                        <path d="M50 15 Q50 20 50 30 Q50 40 50 50" stroke="#3b82f6" stroke-width="3" fill="none" stroke-linecap="round" />
                        <!-- Scattered Confetti Pieces -->
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
                    Cut-Off telah Selesai
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
        // Function to generate report
        alert('Laporan sedang dijana...');
    }

    function showSuccessModal() {
        const modalElement = document.getElementById('successModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    // Handle file upload display
    document.getElementById('jpictFile')?.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            const uploadArea = e.target.closest('.upload-area');
            const textElement = uploadArea.querySelector('p');
            if (textElement) {
                textElement.textContent = fileName;
                textElement.classList.remove('text-muted');
                textElement.classList.add('text-success', 'fw-bold');
            }
        }
    });

    document.getElementById('minitFile')?.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            const uploadArea = e.target.closest('.upload-area');
            const textElement = uploadArea.querySelector('p');
            if (textElement) {
                textElement.textContent = fileName;
                textElement.classList.remove('text-muted');
                textElement.classList.add('text-success', 'fw-bold');
            }
        }
    });
</script>

@endsection