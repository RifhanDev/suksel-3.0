@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --kewangan-accent: #c41e3a;
        --kewangan-accent-dark: #8b1428;
    }

    .kewangan-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .kewangan-header-banner {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        padding: 2rem 2.25rem;
        color: #ffffff;
        position: relative;
    }

    .kewangan-header-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        top: 0;
        width: 300px;
        background: radial-gradient(circle at right center, rgba(196, 30, 58, 0.25), transparent 70%);
        pointer-events: none;
    }

    .stat-badge-counter {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .form-control-modern, .form-select-modern {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.625rem 0.875rem;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        border-color: var(--kewangan-accent);
        box-shadow: 0 0 0 0.25rem rgba(196, 30, 58, 0.15);
    }

    .btn-kewangan-tapis {
        background: linear-gradient(135deg, var(--kewangan-accent) 0%, var(--kewangan-accent-dark) 100%);
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.625rem 1.5rem;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 12px rgba(196, 30, 58, 0.2);
    }

    .btn-kewangan-tapis:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(196, 30, 58, 0.3);
        color: #ffffff;
    }

    .btn-kewangan-reset {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        font-weight: 500;
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-kewangan-reset:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .table-modern {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-modern tbody tr {
        transition: all 0.15s ease-in-out;
        background: #ffffff;
    }

    .table-modern tbody tr:hover {
        background-color: #fcf8f8;
    }

    .table-modern tbody td {
        padding: 1.1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.925rem;
    }

    .tender-badge {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--kewangan-accent-dark);
        background: rgba(196, 30, 58, 0.08);
        border: 1px solid rgba(196, 30, 58, 0.2);
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    .status-pill-process {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        font-weight: 600;
        font-size: 0.775rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-pill-process .pulse-dot {
        width: 6px;
        height: 6px;
        background-color: #d97706;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4);
        animation: pulse-ring 1.8s infinite;
    }

    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.5);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(217, 119, 6, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(217, 119, 6, 0);
        }
    }

    .action-btn-kewangan {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.45rem 0.9rem;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .action-btn-kewangan:hover {
        background: var(--kewangan-accent);
        border-color: var(--kewangan-accent);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(196, 30, 58, 0.25);
    }

    .kewangan-tender-row {
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
            <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Peringkat Penilaian Kewangan</li>
        </ol>
    </nav>

    {{-- Header Banner Card --}}
    <div class="kewangan-card mb-4">
        <div class="kewangan-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Senarai Tender</span>
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">PENILAIAN KEWANGAN</h3>
                <p class="text-white-50 mb-0 small">Senarai perolehan dan sebut harga yang bersedia untuk proses penilaian kewangan.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-badge-counter">
                    <div class="bg-warning bg-opacity-20 p-2.5 rounded-3 text-white d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-bank2 fs-5"></i>
                    </div>
                    <div>
                        <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Jumlah Rekod</div>
                        <div class="fs-5 fw-bold text-white leading-none">{{ $totalCount ?? count($tenders ?? []) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter/Search Section --}}
    <div class="filter-card mb-4">
        <form method="GET" action="{{ route('penilaianKewangan') }}" class="row g-3 align-items-end">

            <!-- No Tender -->
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                    <i class="bi bi-hash me-1"></i>No. Tender / Ref
                </label>
                <input type="text" name="no_tender" class="form-control form-control-modern" id="searchTenderNo"
                    placeholder="Cari No. Tender..." value="{{ request('no_tender') }}">
            </div>

            <!-- Tajuk -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                    <i class="bi bi-file-earmark-text me-1"></i>Tajuk Perolehan
                </label>
                <input type="text" name="tajuk" class="form-control form-control-modern" id="searchTajuk"
                    placeholder="Cari Tajuk Perolehan..." value="{{ request('tajuk') }}">
            </div>

            <!-- Status -->
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                    <i class="bi bi-funnel me-1"></i>Status
                </label>
                <select name="status" class="form-select form-select-modern" id="searchStatus">
                    <option value="">Semua Status</option>
                    <option value="Dalam Proses" {{ request('status') == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Tarikh -->
            <div class="col-12 col-md-2">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                    <i class="bi bi-calendar3 me-1"></i>Tarikh
                </label>
                <input type="date" name="tarikh" class="form-control form-control-modern" id="searchTarikh"
                    value="{{ request('tarikh') }}">
            </div>

            <!-- Buttons -->
            <div class="col-12 d-flex justify-content-end gap-2 pt-2 border-top">
                @if (request()->hasAny(['no_tender', 'tajuk', 'status', 'tarikh']))
                    <a href="{{ route('penilaianKewangan') }}" class="btn btn-kewangan-reset">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                @endif
                <button type="submit" class="btn btn-kewangan-tapis">
                    <i class="bi bi-search me-1"></i>Tapis Senarai
                </button>
            </div>

        </form>
    </div>

    {{-- Tender Table --}}
    <div class="table-modern-wrapper mb-4">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 22%;">No. Tender / Sebut Harga</th>
                        <th style="width: 43%;">Tajuk Perolehan</th>
                        <th style="width: 15%;" class="text-center">Tarikh Penyerahan</th>
                        <th style="width: 15%;" class="text-center">Status Process</th>
                        <th style="width: 10%;" class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenders ?? [] as $index => $item)
                    <tr class="kewangan-tender-row" data-href="{{ $item['show_url'] }}">
                        <td class="text-center text-muted small fw-medium">{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ $item['show_url'] }}" class="text-decoration-none" onclick="event.stopPropagation();">
                                <span class="tender-badge">
                                    <i class="bi bi-file-text me-1 opacity-75"></i>{{ $item['no_tender'] }}
                                </span>
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark mb-1">{{ $item['tajuk'] }}</div>
                            <span class="text-muted extra-small">ID Tender: #{{ $item['id'] }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace fw-normal">
                                <i class="bi bi-calendar-event text-danger me-1"></i>{{ $item['tarikh'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="status-pill-process">
                                <span class="pulse-dot"></span>
                                {{ $item['status_label'] ?? 'Dalam Proses' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ $item['show_url'] }}" class="action-btn-kewangan" onclick="event.stopPropagation();">
                                <span>Nilai</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <div class="bg-light rounded-circle d-inline-flex p-3 mb-3 text-secondary">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                </div>
                                <h6 class="fw-bold text-secondary mb-1">Tiada Tender Dijumpai</h6>
                                <p class="text-muted small mb-3">Tiada rekod tender dengan status penilaian kewangan (<code>status_process_id = 10</code>) pada masa ini.</p>
                                @if (request()->hasAny(['no_tender', 'tajuk', 'status', 'tarikh']))
                                    <a href="{{ route('penilaianKewangan') }}" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Set Semula Carian
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.kewangan-tender-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('button')) return;
                var href = this.getAttribute('data-href');
                if (href) window.location = href;
            });
        });
    });
</script>

@endsection