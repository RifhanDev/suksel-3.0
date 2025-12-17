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
        /* background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%) !important; */
        background: var(--sg-red) !important;
        color: white !important;
    }

    .project-table thead th {
        background: transparent !important;
        border: 1px solid var(--topbar-border, #e5e7eb) !important;
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
                <li class="breadcrumb-item active" aria-current="page">Sebut Harga Pembelian Terus</li>
            </ol>
        </nav>

        {{-- Page Title --}}
        <h4 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--sg-red-dark);">SENARAI PROJEK UNTUK PEMBELIAN TERUS</h4>

        {{-- Filter/Search Section --}}
        <div class="d-flex flex-column flex-md-row gap-3 align-items-end mb-4">
            <div class="w-100 flex-md-fill" style="min-width: 200px;">
                <label class="form-label fw-bold">No. Tender</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchTenderNo" placeholder="Search Tender Number" oninput="performLiveSearch()">
                    <span class="input-group-text" style="background: var(--sg-bg); border-color: var(--topbar-border, #e5e7eb);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="w-100 flex-md-fill" style="min-width: 200px;">
                <label class="form-label fw-bold">Tajuk Perolehan</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchTajuk" placeholder="Search Procurement Title" oninput="performLiveSearch()">
                    <span class="input-group-text" style="background: var(--sg-bg); border-color: var(--topbar-border, #e5e7eb);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="w-100 flex-md-fill" style="min-width: 200px;">
                <label class="form-label fw-bold">Tarikh</label>
                <div class="input-group">
                    <input type="date" class="form-control" id="searchTarikh" onchange="performLiveSearch()">
                    <span class="input-group-text" style="background: var(--sg-bg); border-color: var(--topbar-border, #e5e7eb);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-end w-100 w-md-auto">
                <button type="button" class="btn btn-tapis w-100 w-md-auto px-4" onclick="clearAllFilters()">Reset</button>
            </div>
        </div>

        {{-- Projects Table --}}
        <div class="bg-white rounded overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table project-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 25%;">No. Tender/Sebut Harga</th>
                            <th style="width: 50%;">Tajuk Perolehan</th>
                            <th style="width: 25%;">Tarikh</th>
                        </tr>
                    </thead>
                    <tbody id="projectTableBody">
                        <tr data-tender="QT210000000023741" data-tajuk="BEKALAN BARANGAN PERSEKOLAHAN" data-tarikh="3/3/2024">
                            <td>
                                <a class="text-decoration-none" href="{{ route('pembelianTerus.detailProject', 'QT210000000023741') }}">
                                    <span class="tender-number">QT210000000023741</span>
                                </a>
                            </td>
                            <td><span class="fw-medium" style="max-width: 400px; display: inline-block;">BEKALAN BARANGAN PERSEKOLAHAN</span></td>
                            <td><span class="text-muted small">3/3/2024</span></td>
                        </tr>
                        <tr data-tender="QT210000000023740" data-tajuk="TAJUK PEROLEHAN 1" data-tarikh="2/2/2024">
                            <td>
                                <a class="text-decoration-none" href="{{ route('pembelianTerus.detailProject', 'QT210000000023740') }}">
                                    <span class="tender-number">QT210000000023740</span>
                                </a>
                            </td>
                            <td><span class="fw-medium" style="max-width: 400px; display: inline-block;">TAJUK PEROLEHAN 1</span></td>
                            <td><span class="text-muted small">2/2/2024</span></td>
                        </tr>
                        <tr data-tender="QT210000000023739" data-tajuk="BEKALAN PERALATAN PEJABAT" data-tarikh="1/2/2024">
                            <td>
                                <a class="text-decoration-none" href="{{ route('pembelianTerus.detailProject', 'QT210000000023739') }}">
                                    <span class="tender-number">QT210000000023739</span>
                                </a>
                            </td>
                            <td><span class="fw-medium" style="max-width: 400px; display: inline-block;">BEKALAN PERALATAN PEJABAT</span></td>
                            <td><span class="text-muted small">1/2/2024</span></td>
                        </tr>
                        <tr data-tender="QT210000000023738" data-tajuk="PERKHIDMATAN PEMBERSIHAN" data-tarikh="31/1/2024">
                            <td>
                                <a class="text-decoration-none" href="{{ route('pembelianTerus.detailProject', 'QT210000000023738') }}">
                                    <span class="tender-number">QT210000000023738</span>
                                </a>
                            </td>
                            <td><span class="fw-medium" style="max-width: 400px; display: inline-block;">PERKHIDMATAN PEMBERSIHAN</span></td>
                            <td><span class="text-muted small">31/1/2024</span></td>
                        </tr>
                        <tr data-tender="QT210000000023737" data-tajuk="BEKALAN BARANGAN PERSEKOLAHAN" data-tarikh="30/1/2024">
                            <td>
                                <a class="text-decoration-none" href="{{ route('pembelianTerus.detailProject', 'QT210000000023737') }}">
                                    <span class="tender-number">QT210000000023737</span>
                                </a>
                            </td>
                            <td><span class="fw-medium" style="max-width: 400px; display: inline-block;">BEKALAN BARANGAN PERSEKOLAHAN</span></td>
                            <td><span class="text-muted small">30/1/2024</span></td>
                        </tr>
                    </tbody>
                    <tbody id="emptyState" class="d-none">
                        <tr>
                            <td colspan="3" class="text-center py-5" style="background: var(--sg-bg);">
                                <div class="d-flex flex-column align-items-center justify-content-center mx-auto" style="max-width: 500px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="width: 80px; height: 80px; opacity: 0.4; color: var(--topbar-text, #374151);">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <div class="fw-semibold mb-2" style="color: var(--topbar-text, #374151);">Tiada Rekod Dijumpai</div>
                                    <p class="text-muted mb-0 small">Tiada projek dijumpai berdasarkan kriteria carian anda.<br>Sila cuba dengan kata kunci atau tarikh yang berbeza.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    let searchTimeout;

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
    }

    function performLiveSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTable, 300);
    }

    function filterTable() {
        const tenderNo = document.getElementById('searchTenderNo').value.toLowerCase().trim();
        const tajuk = document.getElementById('searchTajuk').value.toLowerCase().trim();
        const tarikhInput = document.getElementById('searchTarikh').value;
        const rows = document.querySelectorAll('#projectTableBody tr[data-tender]');
        const emptyState = document.getElementById('emptyState');
        let visibleCount = 0;

        rows.forEach(row => {
            const matchesTender = !tenderNo || row.getAttribute('data-tender').toLowerCase().includes(tenderNo);
            const matchesTajuk = !tajuk || row.getAttribute('data-tajuk').toLowerCase().includes(tajuk);
            const matchesTarikh = !tarikhInput || row.getAttribute('data-tarikh') === formatDate(tarikhInput);

            if (matchesTender && matchesTajuk && matchesTarikh) {
                row.classList.remove('d-none');
                visibleCount++;
            } else {
                row.classList.add('d-none');
            }
        });

        emptyState.classList.toggle('d-none', visibleCount > 0);
    }

    function clearAllFilters() {
        document.getElementById('searchTenderNo').value = '';
        document.getElementById('searchTajuk').value = '';
        document.getElementById('searchTarikh').value = '';
        filterTable();
    }

    document.addEventListener('DOMContentLoaded', filterTable);
    window.performLiveSearch = performLiveSearch;
    window.clearAllFilters = clearAllFilters;
</script>

@endsection