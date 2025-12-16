@extends('layouts.v3.master')

@section('content')

<style>
    /* Custom focus color for form controls */
    .form-control:focus,
    .form-select:focus {
        border-color: #1F3A8A;
        box-shadow: 0 0 0 0.2rem rgba(31, 58, 138, 0.25);
    }

    /* Custom button color */
    .btn-tapis {
        background: #1F3A8A;
        border-color: #1F3A8A;
    }

    .btn-tapis:hover {
        background: #152a6b;
        border-color: #152a6b;
    }

    /* Table Styles - Keep all custom table styling */
    .table-container {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .project-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .project-table thead {
        background: linear-gradient(135deg, #1F3A8A 0%, #2d4ba8 100%);
        color: white;
    }

    .project-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: relative;
    }

    .project-table th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: rgba(255, 255, 255, 0.3);
    }

    .project-table tbody tr {
        background: white;
        border-bottom: 1px solid #e9ecef;
    }

    .project-table tbody tr:last-child {
        border-bottom: none;
    }

    .project-table td {
        padding: 16px 20px;
        text-align: left;
        border: none;
        color: #495057;
        font-size: 14px;
        vertical-align: middle;
    }

    .tender-number {
        font-weight: 600;
        color: #1F3A8A;
        font-family: 'Courier New', monospace;
        font-size: 13px;
    }

    .tajuk-perolehan {
        font-weight: 500;
        color: #212529;
        max-width: 400px;
    }

    .tarikh {
        color: #6c757d;
        font-size: 13px;
    }

    .table-empty-state {
        padding: 80px 20px;
        text-align: center !important;
        background: #f8f9fa;
    }

    .table-empty-state > div {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        max-width: 500px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        opacity: 0.4;
        color: #6c757d;
        display: block;
    }

    .empty-state-title {
        font-size: 18px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        text-align: center;
    }

    .empty-state-message {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
        line-height: 1.5;
        text-align: center;
    }

    /* Responsive table adjustments */
    @media (max-width: 768px) {
        .project-table {
            font-size: 12px;
        }

        .project-table th,
        .project-table td {
            padding: 12px 10px;
        }

        .table-container {
            overflow-x: auto;
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
        <h4 class="fw-bold text-primary mb-4 pb-2 border-bottom">SENARAI PROJEK UNTUK PEMBELIAN TERUS</h4>

        {{-- Filter/Search Section --}}
        <div class="d-flex flex-column flex-md-row gap-3 align-items-end mb-4">
            <div class="w-100 flex-md-fill" style="min-width: 200px;">
                <label class="form-label fw-bold">No. Tender</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchTenderNo" placeholder="Search Tender Number" oninput="performLiveSearch()">
                    <span class="input-group-text">
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
                    <span class="input-group-text">
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
                    <span class="input-group-text">
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
                <button type="button" class="btn btn-tapis text-white w-100 w-md-auto" onclick="clearAllFilters()">Reset</button>
            </div>
        </div>

        {{-- Projects Table --}}
        <div class="table-container">
            <table class="project-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">No. Tender/Sebut Harga</th>
                        <th style="width: 50%;">Tajuk Perolehan</th>
                        <th style="width: 25%;">Tarikh</th>
                    </tr>
                </thead>
                <tbody id="projectTableBody">
                    <tr data-tender="QT210000000023741" data-tajuk="BEKALAN BARANGAN PERSEKOLAHAN" data-tarikh="3/3/2024">
                        <td><span class="tender-number">QT210000000023741</span></td>
                        <td><span class="tajuk-perolehan">BEKALAN BARANGAN PERSEKOLAHAN</span></td>
                        <td><span class="tarikh">3/3/2024</span></td>
                    </tr>
                    <tr data-tender="QT210000000023740" data-tajuk="TAJUK PEROLEHAN 1" data-tarikh="2/2/2024">
                        <td><span class="tender-number">QT210000000023740</span></td>
                        <td><span class="tajuk-perolehan">TAJUK PEROLEHAN 1</span></td>
                        <td><span class="tarikh">2/2/2024</span></td>
                    </tr>
                    <tr data-tender="QT210000000023739" data-tajuk="BEKALAN PERALATAN PEJABAT" data-tarikh="1/2/2024">
                        <td><span class="tender-number">QT210000000023739</span></td>
                        <td><span class="tajuk-perolehan">BEKALAN PERALATAN PEJABAT</span></td>
                        <td><span class="tarikh">1/2/2024</span></td>
                    </tr>
                    <tr data-tender="QT210000000023738" data-tajuk="PERKHIDMATAN PEMBERSIHAN" data-tarikh="31/1/2024">
                        <td><span class="tender-number">QT210000000023738</span></td>
                        <td><span class="tajuk-perolehan">PERKHIDMATAN PEMBERSIHAN</span></td>
                        <td><span class="tarikh">31/1/2024</span></td>
                    </tr>
                    <tr data-tender="QT210000000023737" data-tajuk="BEKALAN BARANGAN PERSEKOLAHAN" data-tarikh="30/1/2024">
                        <td><span class="tender-number">QT210000000023737</span></td>
                        <td><span class="tajuk-perolehan">BEKALAN BARANGAN PERSEKOLAHAN</span></td>
                        <td><span class="tarikh">30/1/2024</span></td>
                    </tr>
                </tbody>
                <tbody id="emptyState" style="display: none;">
                    <tr>
                        <td colspan="3" class="table-empty-state">
                            <div>
                                <svg class="empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <div class="empty-state-title">Tiada Rekod Dijumpai</div>
                                <p class="empty-state-message">Tiada projek dijumpai berdasarkan kriteria carian anda.<br>Sila cuba dengan kata kunci atau tarikh yang berbeza.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    let searchTimeout;

    // Live search function with debouncing
    function performLiveSearch() {
        clearTimeout(searchTimeout);
        
        // Debounce search to avoid too many searches while typing
        searchTimeout = setTimeout(() => {
            filterTable();
        }, 300);
    }

    // Convert date from YYYY-MM-DD to DD/MM/YYYY
    function formatDateForDisplay(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Convert date from DD/MM/YYYY to YYYY-MM-DD
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const parts = dateString.split('/');
        if (parts.length === 3) {
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        return '';
    }

    // Main filter function
    function filterTable() {
        const tenderNo = document.getElementById('searchTenderNo').value.toLowerCase().trim();
        const tajuk = document.getElementById('searchTajuk').value.toLowerCase().trim();
        const tarikhInput = document.getElementById('searchTarikh').value;

        const rows = document.querySelectorAll('#projectTableBody tr[data-tender]');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowTender = row.getAttribute('data-tender').toLowerCase();
            const rowTajuk = row.getAttribute('data-tajuk').toLowerCase();
            const rowTarikh = row.getAttribute('data-tarikh');

            // Check if row matches all search criteria
            const matchesTender = !tenderNo || rowTender.includes(tenderNo);
            const matchesTajuk = !tajuk || rowTajuk.includes(tajuk);
            
            // Handle date matching
            let matchesTarikh = true;
            if (tarikhInput) {
                const formattedDate = formatDateForDisplay(tarikhInput);
                matchesTarikh = rowTarikh === formattedDate;
            }

            if (matchesTender && matchesTajuk && matchesTarikh) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty state
        const emptyState = document.getElementById('emptyState');
        if (visibleCount === 0) {
            emptyState.style.display = '';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Clear individual search field
    function clearSearch(fieldId) {
        document.getElementById(fieldId).value = '';
        performLiveSearch();
    }

    // Clear all filters
    function clearAllFilters() {
        document.getElementById('searchTenderNo').value = '';
        document.getElementById('searchTajuk').value = '';
        document.getElementById('searchTarikh').value = '';
        filterTable();
    }

    // Initialize - perform search on page load
    document.addEventListener('DOMContentLoaded', function() {
        filterTable();
    });

    // Make functions available globally
    window.performLiveSearch = performLiveSearch;
    window.clearSearch = clearSearch;
    window.clearAllFilters = clearAllFilters;
</script>

@endsection
