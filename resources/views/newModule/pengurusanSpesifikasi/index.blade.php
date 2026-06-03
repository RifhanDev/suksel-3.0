@extends('layouts.v3.master')

@section('content')

<style>
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
        cursor: pointer;
    }

    .project-table tbody tr:hover {
        background: var(--sg-bg);
    }

    .tender-number {
        font-weight: 600;
        color: var(--sg-red-dark);
        font-family: 'Courier New', monospace;
    }
</style>

<div class="card">
    <div class="card-body p-4">

        <nav aria-label="breadcrumb" class="py-2 mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengurusan Spesifikasi</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--sg-red-dark);">SENARAI TENDER</h4>

        <div class="d-flex flex-column flex-md-row gap-3 align-items-end mb-4 flex-wrap">
            <div class="w-100 flex-md-fill" style="min-width: 180px;">
                <label class="form-label fw-bold">No. Tender</label>
                <input type="text" class="form-control" id="searchTenderNo" placeholder="No. Tender">
            </div>
            <div class="w-100 flex-md-fill" style="min-width: 180px;">
                <label class="form-label fw-bold">Tajuk Perolehan</label>
                <input type="text" class="form-control" id="searchTajuk" placeholder="Tajuk Perolehan">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table project-table mb-0" id="tenderTable">
                <thead>
                    <tr>
                        <th style="width: 5%;">Bil.</th>
                        <th style="width: 20%;">No. Tender</th>
                        <th style="width: 55%;">Tajuk Perolehan</th>
                        <th style="width: 20%;">PTJ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenders as $i => $tender)
                    <tr data-href="{{ route('senaraiTeknikal', $tender->uuid) }}">
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ route('senaraiTeknikal', $tender->uuid) }}" class="text-decoration-none">
                                <span class="tender-number">{{ $tender->no_tender ?? $tender->ref_number ?? '-' }}</span>
                            </a>
                        </td>
                        <td>{{ $tender->name ?? '-' }}</td>
                        <td>{{ optional($tender->tenderer)->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Tiada tender dijumpai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('#tenderTable tbody tr[data-href]').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a')) return;
            window.location = this.getAttribute('data-href');
        });
    });

    document.getElementById('searchTenderNo').addEventListener('input', filterTable);
    document.getElementById('searchTajuk').addEventListener('input', filterTable);

    function filterTable() {
        var noVal = document.getElementById('searchTenderNo').value.toLowerCase();
        var tajukVal = document.getElementById('searchTajuk').value.toLowerCase();

        document.querySelectorAll('#tenderTable tbody tr[data-href]').forEach(function (row) {
            var cells = row.querySelectorAll('td');
            var no = cells[1] ? cells[1].textContent.toLowerCase() : '';
            var tajuk = cells[2] ? cells[2].textContent.toLowerCase() : '';
            row.style.display = (no.includes(noVal) && tajuk.includes(tajukVal)) ? '' : 'none';
        });
    }
</script>

@endsection
