{{-- @extends('layouts.v3.master') --}}
@extends(!Auth::check() || Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
    <style>
        .page-header-modern {
            position: relative;
            background: white;
            border-radius: var(--radius-lg);
            padding: 2.5rem 2.5rem;
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px -10px rgba(196, 30, 58, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header-modern::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background-image:
                radial-gradient(at 90% 10%, rgba(196, 30, 58, 0.08) 0px, transparent 50%),
                radial-gradient(at 10% 90%, rgba(255, 204, 0, 0.08) 0px, transparent 50%);
            z-index: 0;
        }

        .page-header-modern::after {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 150px; height: 150px;
            background: linear-gradient(135deg, var(--sg-red) 0%, transparent 80%);
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }

        .header-content { position: relative; z-index: 2; }

        .header-pretitle {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sg-red);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-pretitle::before {
            content: '';
            display: block;
            width: 20px; height: 2px;
            background: var(--sg-yellow);
        }

        .header-title {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 2rem;
            color: #111827;
            margin: 0;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .header-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-top: 0.5rem;
            max-width: 600px;
        }

        .header-icon-box {
            position: relative;
            z-index: 2;
            width: 80px; height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.8);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            color: var(--sg-red);
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }

        .page-header-modern:hover .header-icon-box { transform: rotate(0deg) scale(1.05); }

        @media (max-width: 768px) {
            .page-header-modern { flex-direction: column; align-items: flex-start; padding: 1.5rem; gap: 1.5rem; }
            .header-icon-box { display: none; }
        }

        .tender-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
            border: none;
            overflow: visible;
        }

        .tender-toolbar {
            padding: 1.25rem 2rem;
            border-bottom: 1px solid #f3f4f6;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 4px solid var(--sg-red);
            padding-left: 1rem;
            min-height: 42px;
        }

        .toolbar-title {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .toolbar-actions { display: flex; gap: 0.5rem; align-items: center; }

        .nav-modern-tabs {
            display: inline-flex;
            background: #f3f4f6;
            padding: 4px;
            border-radius: var(--radius-sm);
            list-style: none;
            margin: 0;
        }
        .nav-modern-tabs .nav-item { margin: 0; }
        .nav-modern-tabs .nav-link {
            border: none;
            padding: 0.5rem 1.25rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            background: transparent;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-modern-tabs .nav-link:hover { color: #1f2937; }
        .nav-modern-tabs .nav-link.active {
            background: white;
            color: var(--sg-red);
            font-weight: 700;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .table-modern thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .table-modern tbody td {
            padding: 0.9rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }

        .table-modern tbody tr:hover { background-color: #fef2f2; }
    </style>
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12">

            <div class="page-header-modern">
                <div class="header-content">
                    <div class="header-pretitle">Direktori Agensi</div>
                    <h2 class="header-title">{{ $organizationunit->name }}</h2>
                    <p class="header-subtitle">Senarai tender dan sebut harga yang diiklankan oleh agensi ini.</p>
                </div>

                <div class="header-icon-box d-none d-md-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
            </div>

            <div class="tender-card">
                <div class="tender-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('agencies.index') }}" class="btn-auth btn-auth-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>

                    <ul class="nav-modern-tabs">
                        <li class="nav-item">
                            <a href="{{ route('agencies.show', $organizationunit->id) }}"
                                class="nav-link {{ !request('type') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 6l11 0"/><path d="M9 12l11 0"/><path d="M9 18l11 0"/>
                                    <path d="M5 6l0 .01"/><path d="M5 12l0 .01"/><path d="M5 18l0 .01"/>
                                </svg>
                                Semua
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('agencies.show', $organizationunit->id) }}?type=tenders"
                                class="nav-link {{ request('type') == 'tenders' ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                    <path d="M9 13l6 0"/><path d="M9 17l6 0"/>
                                </svg>
                                Tender
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('agencies.show', $organizationunit->id) }}?type=quotations"
                                class="nav-link {{ request('type') == 'quotations' ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 6h10.5a.5 .5 0 0 1 .5 .5v11a.5 .5 0 0 1 -.5 .5h-14.5a.5 .5 0 0 1 -.5 -.5v-11a.5 .5 0 0 1 .5 -.5h1.5"/>
                                    <path d="M14 6v-3h-4v3"/><path d="M6 12h2"/><path d="M6 15h2"/>
                                </svg>
                                Sebut Harga
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="DT-show table table-modern table-mobile-md w-100 mb-0" data-path="{{ $path }}">
                            <thead>
                                <tr>
                                    <th class="w-35">No / Tajuk</th>
                                    <th>Kod Bidang</th>
                                    <th class="w-15">Tarikh Jual</th>
                                    <th class="w-15">Tarikh Tutup</th>
                                    <th class="w-15 text-center">Harga Dokumen</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    $('.DT-show').each(function () {
        var target = $(this);
        var path   = target.data('path');

        target.DataTable({
            ajax: path,
            columns: [
                { data: 'name',                name: 'name' },
                { data: 'codes',               name: 'codes' },
                { data: 'document_start_date', name: 'document_start_date' },
                { data: 'submission_datetime', name: 'submission_datetime' },
                { data: 'price',               name: 'price' },
            ],
            serverSide: true,
            stateSave: false,
            language: {
                sEmptyTable:    "Tiada data",
                sInfo:          "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                sInfoEmpty:     "Paparan 0 hingga 0 dari 0 rekod",
                sInfoFiltered:  "(Ditapis dari jumlah _MAX_ rekod)",
                sInfoThousands: ",",
                sLengthMenu:    "Papar _MENU_ rekod",
                sLoadingRecords:"Diproses...",
                sProcessing:    "Sedang diproses...",
                sSearch:        "Carian:",
                sZeroRecords:   "Tiada padanan rekod yang dijumpai.",
                oPaginate: { sFirst: "<<", sPrevious: "<", sNext: ">", sLast: ">>" }
            },
            aaSorting: [],
            pageLength: 25,
        });
    });
</script>
@endsection
