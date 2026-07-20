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

    .btn-terima {
        background: #10b981;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-terima:hover {
        background: #059669;
        color: white;
    }

    .btn-tolak {
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-tolak:hover {
        background: #dc2626;
        color: white;
    }

    .btn-surat {
        background: var(--sg-red);
        color: white;
        border: none;
    }

    .btn-surat:hover:not(:disabled) {
        background: var(--sg-red-dark);
        color: white;
    }

    .btn-surat:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: var(--topbar-text, #6b7280);
    }

    .btn-terima:disabled,
    .btn-tolak:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .modal-title {
        color: var(--sg-red-dark);
    }

    .tender-number {
        font-weight: 600;
        color: var(--sg-red-dark);
        font-family: 'Courier New', monospace;
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb Navigation --}}
        <nav class="py-2 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item active fw-semibold">Keputusan Syarikat</li>
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

        {{-- Keputusan Syarikat Table --}}
        <div class="mb-4">
            <h4 class="form-title mb-3">KEPUTUSAN SYARIKAT</h4>

            <div class="table-responsive">
                <table class="spec-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">No. Tender/Sebut Harga</th>
                            <th style="width: 35%;">Tajuk Perolehan</th>
                            <th style="width: 25%;">Nama Syarikat</th>
                            <th style="width: 15%; text-align: right;">Harga Termasuk SST (RM)</th>
                            <th style="width: 10%; text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="resultsTableBody">
                        @foreach($results as $index => $result)
                        <tr data-tender="{{ $result['tender_no'] }}" data-syarikat="{{ $result['nama_syarikat'] }}" data-status="pending">
                            <td>
                                <span class="tender-number">{{ $result['tender_no'] }}</span>
                            </td>
                            <td>{{ $result['tajuk_perolehan'] }}</td>
                            <td>{{ $result['nama_syarikat'] }}</td>
                            <td style="text-align: right;">{{ $result['harga_sst'] }}</td>
                            <td style="text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-terima" data-tender="{{ $result['tender_no'] }}" data-syarikat="{{ $result['nama_syarikat'] }}" onclick="openTerimaModal(this)">Terima</button>
                                    <button type="button" class="btn btn-tolak" data-tender="{{ $result['tender_no'] }}" data-syarikat="{{ $result['nama_syarikat'] }}" onclick="openTolakModal(this)">Tolak</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Action Button --}}
        <div class="d-flex justify-content-end mt-4">
            <button type="button" id="btnSuratSetujuTerima" class="btn btn-surat px-4 py-2 rounded fw-bold" onclick="generateSuratSetujuTerima()" disabled>
                Surat Setuju Terima
            </button>
        </div>

    </div>
</div>

{{-- Modal: Terima (Accept) --}}
<div class="modal fade" id="terimaModal" tabindex="-1" aria-labelledby="terimaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="terimaModalLabel">Terima Syarikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Adakah anda pasti untuk menerima <strong id="terimaSyarikatName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-terima" id="confirmTerimaBtn" onclick="confirmTerima()">Terima</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Tolak (Reject) --}}
<div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tolakModalLabel">Tolak Syarikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Adakah anda pasti untuk menolak <strong id="tolakSyarikatName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-tolak" id="confirmTolakBtn" onclick="confirmTolak()">Tolak</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentTenderNo = '';
    let currentNamaSyarikat = '';
    let currentButton = null;

    function openTerimaModal(button) {
        currentButton = button;
        currentTenderNo = button.getAttribute('data-tender');
        currentNamaSyarikat = button.getAttribute('data-syarikat');
        document.getElementById('terimaSyarikatName').textContent = currentNamaSyarikat;
        
        const modalElement = document.getElementById('terimaModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    function openTolakModal(button) {
        currentButton = button;
        currentTenderNo = button.getAttribute('data-tender');
        currentNamaSyarikat = button.getAttribute('data-syarikat');
        document.getElementById('tolakSyarikatName').textContent = currentNamaSyarikat;
        
        const modalElement = document.getElementById('tolakModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    function confirmTerima() {
        if (!currentButton) return;

        const row = currentButton.closest('tr');
        const tenderNo = currentButton.getAttribute('data-tender');
        
        // Update row status
        row.setAttribute('data-status', 'accepted');
        
        // Disable both buttons in this row
        const buttons = row.querySelectorAll('.btn-terima, .btn-tolak');
        buttons.forEach(btn => {
            btn.disabled = true;
        });

        // Enable "Surat Setuju Terima" button
        document.getElementById('btnSuratSetujuTerima').disabled = false;

        // Close modal
        const modalElement = document.getElementById('terimaModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }

        // Reset variables
        currentButton = null;
        currentTenderNo = '';
        currentNamaSyarikat = '';
    }

    function confirmTolak() {
        if (!currentButton) return;

        const row = currentButton.closest('tr');
        
        // Remove row from table
        row.remove();

        // Check if there are any accepted rows left
        const acceptedRows = document.querySelectorAll('#resultsTableBody tr[data-status="accepted"]');
        if (acceptedRows.length === 0) {
            // Disable "Surat Setuju Terima" button if no accepted rows
            document.getElementById('btnSuratSetujuTerima').disabled = true;
        }

        // Close modal
        const modalElement = document.getElementById('tolakModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }

        // Reset variables
        currentButton = null;
        currentTenderNo = '';
        currentNamaSyarikat = '';
    }

    function generateSuratSetujuTerima() {
        // Download sample PDF
        const tenderNo = '{{ $tender_no }}';
        window.location.href = '{{ route("pembelianTerus.downloadSuratSetujuTerima", ":tender_no") }}'.replace(':tender_no', tenderNo);
    }
</script>

@endsection

