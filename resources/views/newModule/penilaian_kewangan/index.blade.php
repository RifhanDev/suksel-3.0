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

    .kewangan-tender-row {
        cursor: pointer;
    }

    .kewangan-tender-row:hover td {
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
                <li class="breadcrumb-item active" aria-current="page">Peringkat Penilaian Kewangan</li>
            </ol>
        </nav>

        {{-- Page Title --}}
        <h4 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--sg-red-dark);">SENARAI TENDER</h4>

        {{-- Filter/Search Section --}}
        <div class="row g-3 align-items-end mb-4">

            <!-- No Tender -->
            <div class="col-12 col-md">
                <label class="form-label fw-bold">No. Tender</label>
                <input type="text" class="form-control" id="searchTenderNo" placeholder="Cari No. Tender">
            </div>

            <!-- Tajuk -->
            <div class="col-12 col-md">
                <label class="form-label fw-bold">Tajuk Perolehan</label>
                <input type="text" class="form-control" id="searchTajuk" placeholder="Cari Tajuk">
            </div>

            <!-- Status -->
            <div class="col-12 col-md">
                <label class="form-label fw-bold">Status</label>
                <select class="form-select" id="searchStatus">
                    <option value="">Semua Status</option>
                    <option value="Dalam Proses">Dalam Proses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <!-- Tarikh -->
            <div class="col-12 col-md">
                <label class="form-label fw-bold">Tarikh</label>
                <input type="date" class="form-control" id="searchTarikh">
            </div>

            <!-- Button -->
            <div class="col-12 col-md-auto">
                <button type="button" class="btn btn-tapis w-100 px-4">Tapis</button>
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
                        @forelse($tender as $t)
                        <tr class="kewangan-tender-row" data-href="{{ route('penilaianKewangan.show', $t['no']) }}">
                            <td><a href="{{ route('penilaianKewangan.show', $t['no']) }}" class="text-decoration-none"><span class="tender-number">{{ $t['no'] }}</span></a></td>
                            <td><span class="fw-medium">{{ $t['tajuk'] }}</span></td>
                            <td><span class="text-muted small">{{ $t['tamat'] }}</span></td>
                            <td></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No tender found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.kewangan-tender-row').forEach(function(row) {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a')) return;
            var href = this.getAttribute('data-href');
            if (href) window.location = href;
        });
    });
</script>

@endsection