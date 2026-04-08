@extends('layouts.v3.master')

@section('content')

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: var(--sg-red);
        box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
    }

    .btn-tapis {
        background: var(--sg-red);
        border-color: var(--sg-red);
        color: white;
    }

    .btn-tapis:hover {
        background: var(--sg-red-dark);
        border-color: var(--sg-red-dark);
        color: white;
    }

    .project-table thead {
        background: #1F3A8A !important;
        color: white !important;
    }

    .project-table thead th {
        background: transparent !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        font-weight: bold;
        text-align: center;
        padding: 12px;
    }

    .project-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--topbar-border, #e5e7eb);
        color: var(--sg-black);
    }

    .project-table tbody tr {
        background: white;
    }

    .project-table tbody tr:hover {
        background: var(--sg-bg);
    }

    .tender-number {
        font-weight: 600;
        color: var(--sg-red-dark);
        font-family: 'Courier New', monospace;
    }

    .teknikal-row-link {
        cursor: pointer;
    }

    .teknikal-row-link:hover td {
        background: var(--sg-bg);
    }

    @media (max-width: 768px) {
        .project-table {
            font-size: 12px;
        }

        .project-table th,
        .project-table td {
            padding: 12px 10px;
        }
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb Navigation --}}
        <nav aria-label="breadcrumb" class="py-2 mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Peringkat Penilaian Teknikal</li>
            </ol>
        </nav>

        {{-- Page Title --}}
        <h4 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--sg-red-dark);">SENARAI TENDER</h4>

        {{-- Filter/Search Section --}}
        <div class="d-flex flex-column flex-md-row gap-3 align-items-end mb-4 flex-wrap">
            <div class="w-100 flex-md-fill" style="min-width: 180px;">
                <label class="form-label fw-bold">No. Tender</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" class="form-control border-start-0" id="searchTenderNo" placeholder="Q No. Tender">
                </div>
            </div>

            <div class="w-100 flex-md-fill" style="min-width: 180px;">
                <label class="form-label fw-bold">Tajuk Perolehan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" class="form-control border-start-0" id="searchTajuk" placeholder="Q Tajuk Perolehan">
                </div>
            </div>

            <div class="w-100 flex-md-fill" style="min-width: 180px;">
                <label class="form-label fw-bold">Status</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <select class="form-select border-start-0" id="searchStatus">
                        <option value="">Q Status</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <div class="w-100 flex-md-fill" style="min-width: 180px;">
                <label class="form-label fw-bold">Tarikh</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="background: var(--sg-bg); border-color: var(--topbar-border, #e5e7eb);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </span>
                    <input type="date" class="form-control border-start-0" id="searchTarikh" placeholder="Q Tarikh">
                </div>
            </div>

            <div class="d-flex align-items-end w-100 w-md-auto">
                <button type="button" class="btn btn-tapis w-100 w-md-auto px-4">Tapis</button>
            </div>
        </div>

        {{-- Tender Table --}}
        <div class="bg-white rounded overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table project-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 20%;">No. Tender/Sebut Harga</th>
                            <th style="width: 45%;">Tajuk Perolehan</th>
                            <th style="width: 15%;">Tarikh</th>
                            <th style="width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="teknikal-row-link" data-href="{{ route('penilaianTeknikal.show', 'QT210000000023741') }}">
                            <td><a href="{{ route('penilaianTeknikal.show', 'QT210000000023741') }}" class="text-decoration-none"><span class="tender-number">QT210000000023741</span></a></td>
                            <td><span class="fw-medium">TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX</span></td>
                            <td><span class="text-muted small">3/3/2024</span></td>
                            <td></td>
                        </tr>
                        <tr class="teknikal-row-link" data-href="{{ route('penilaianTeknikal.show', 'QT210000000023740') }}">
                            <td><a href="{{ route('penilaianTeknikal.show', 'QT210000000023740') }}" class="text-decoration-none"><span class="tender-number">QT210000000023740</span></a></td>
                            <td><span class="fw-medium">TAJUK PEROLEHAN 1</span></td>
                            <td><span class="text-muted small">2/2/2024</span></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
document.querySelectorAll('.teknikal-row-link').forEach(function(row) {
    row.addEventListener('click', function(e) {
        if (e.target.closest('a')) return;
        var href = this.getAttribute('data-href');
        if (href) window.location = href;
    });
});
</script>

@endsection
