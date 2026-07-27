@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/content-card.css') }}" rel="stylesheet">
    <style>
        .borang-title-bar {
            background: #1e293b;
            color: #fff;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 10px 16px;
            border-radius: 6px 6px 0 0;
        }
        .review-info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px 20px;
        }
        .badge-pematuhan-ya {
            background-color: #dcfce7 !important;
            color: #15803d !important;
            border: 1px solid #bbf7d0 !important;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-pematuhan-tidak {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
            border: 1px solid #fca5a5 !important;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-pematuhan-pending {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.78rem;
            padding: 4px 8px;
            border-radius: 4px;
        }
        #tbl-specifikasi-review {
            border: 1px solid #cbd5e1;
        }
        #tbl-specifikasi-review thead th {
            background: #0f172a;
            color: #fff;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-color: #0f172a !important;
        }
        #tbl-specifikasi-review th,
        #tbl-specifikasi-review td {
            border-right: 1px solid #e2e8f0 !important;
            vertical-align: middle;
        }
        #tbl-specifikasi-review th:last-child,
        #tbl-specifikasi-review td:last-child {
            border-right: none !important;
        }
        .spec-item-row td { background: #f8fafc; }
        .spec-detail-row td { background: #fff; }
        .spec-text-box {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 8px 10px;
            background: #fff;
            font-size: 0.8rem;
            line-height: 1.45;
            min-height: 2.5rem;
        }
        .spec-text-box-sub {
            margin-left: 0.5rem;
            font-size: 0.78rem;
        }
        .spec-price-total-value {
            display: inline-block;
            min-width: 100px;
            padding: 6px 12px;
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            font-weight: 700;
            color: #1e3a8a;
        }
    </style>
@endsection

@section('content')
    @php
        $content = $item['admin_content'] ?? [];
        $vendorName = $vendor['name'] ?? $vendor['nama'] ?? 'Petender';
        $vendorKod  = $vendor['kod'] ?? '-';
        $status     = $item['vendor_status'] ?? ($item['vendor_content']['status'] ?? 'draft');
    @endphp

    {{-- Header Review Metadata Card --}}
    <div class="review-info-card mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.68rem;letter-spacing:0.5px;">Petender / Kod</span>
                <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size:0.95rem;">
                    <i class="bi bi-building text-primary"></i> {{ $vendorName }}
                    @if ($vendorKod && $vendorKod !== '-')
                        <span class="badge bg-secondary font-monospace" style="font-size:0.75rem;">{{ $vendorKod }}</span>
                    @endif
                </h6>
            </div>
            <div class="col-md-4">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.68rem;letter-spacing:0.5px;">Dokumen Spesifikasi</span>
                <span class="fw-semibold text-dark" style="font-size:0.875rem;">{{ $content['document_title'] ?? $item['title'] ?? '-' }}</span>
            </div>
            <div class="col-md-2 text-md-end">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.68rem;letter-spacing:0.5px;">Status Penghantaran</span>
                @if ($status === 'submitted')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:0.78rem;">
                        <i class="bi bi-check-circle me-1"></i>Dihantar
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size:0.78rem;">
                        <i class="bi bi-clock me-1"></i>Draf / Menunggu
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Executive Summary Read-Only Table --}}
    <div class="content-card mb-4 p-0">
        <div class="borang-title-bar d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-check me-2"></i>Ringkasan Maklum Balas Spesifikasi Petender</span>
            <span class="badge bg-light text-dark font-monospace" style="font-size:0.72rem;">Paparan Semakan Jawatankuasa</span>
        </div>
        <div class="content-card-body p-4 pt-3">
            @include('tenders.dokumen.partials.specification_table', [
                'content' => $content,
                'dok' => $item,
                'tender' => $tender,
                'mode' => 'admin',
                'vendorCanEdit' => false,
                'standalone' => true,
            ])
        </div>
    </div>
@endsection
