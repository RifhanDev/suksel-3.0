@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
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

        // ?summary=dokumentasi swaps in the shared, module-neutral clean partial (no Pematuhan
        // column) without touching specification_table.blade.php (still used by the vendor's
        // own live form + admin template preview). Used by both Penilaian Teknikal and
        // Jawatankuasa Pembuka.
        $summaryPartials = [
            'dokumentasi' => 'tenders.dokumen.partials.specification_dokumentasi',
        ];
        $specificationPartial = $summaryPartials[$summary ?? ''] ?? 'tenders.dokumen.partials.specification_table';
    @endphp

    @include($specificationPartial, [
        'content' => $content,
        'dok' => $item,
        'tender' => $tender,
        'mode' => 'admin',
        'vendorCanEdit' => false,
        'standalone' => true,
    ])
@endsection
