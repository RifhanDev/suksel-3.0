@extends('layouts.modernLanding')

@section('styles')
<style>
    .page-header-modern {
        position: relative;
        background: white;
        border-radius: var(--radius-lg);
        padding: 2.5rem 2.5rem;
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px -10px rgba(196, 30, 58, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header-modern::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-image: 
            radial-gradient(at 90% 10%, rgba(196, 30, 58, 0.08) 0px, transparent 50%),
            radial-gradient(at 10% 90%, rgba(255, 204, 0, 0.08) 0px, transparent 50%);
        z-index: 0;
    }

    /* Decorative Circle */
    .page-header-modern::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, var(--sg-red) 0%, transparent 80%);
        border-radius: 50%;
        opacity: 0.1;
        z-index: 0;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

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
        width: 20px;
        height: 2px;
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

    /* Floating 3D Icon */
    .header-icon-box {
        position: relative;
        z-index: 2;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        color: var(--sg-red);
        transform: rotate(-5deg);
        transition: transform 0.3s ease;
    }

    .page-header-modern:hover .header-icon-box {
        transform: rotate(0deg) scale(1.05);
    }

    @media (max-width: 768px) {
        .page-header-modern { flex-direction: column; align-items: flex-start; padding: 1.5rem; gap: 1.5rem; }
        .header-icon-box { display: none; } /* Hide icon on mobile */
    }

    /* =========================================
       TENDER CARD & TABLE
       ========================================= */
    .tender-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }

    .tender-toolbar {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid #f3f4f6;
        background: #fff;
    }

    /* Tabs */
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

    /* Table */
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

    /* =========================================
       SIDEBAR COMPONENTS
       ========================================= */
    .sidebar-widget {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05);
        border: 1px solid #f3f4f6;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    
    .sidebar-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
    }

    .sidebar-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .news-item-sidebar {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        gap: 0.75rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    .news-item-sidebar:hover { background: #fef2f2; }
    
    .news-date-small {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: var(--radius-sm);
        min-width: 44px;
        height: 44px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    .news-date-small .day { font-weight: 800; color: var(--sg-red); font-size: 0.9rem; }
    .news-date-small .month { font-size: 0.55rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin-top: 2px;}

    .news-item-sidebar:hover .news-date-small { background: var(--sg-red); border-color: var(--sg-red); }
    .news-item-sidebar:hover .news-date-small .day, 
    .news-item-sidebar:hover .news-date-small .month { color: white; }

</style>
@endsection

@section('content')

    @php
        $columnsConfig = [
            [
                'data' => 'submission_datetime',
                'name' => 'submission_datetime',
                'label' => 'Tarikh Tutup',
                'icon' => 'ti-calendar',
                'width' => 'w-15 text-center',
            ],
            [
                'data' => 'organization_unit_id',
                'name' => 'organization_unit_id',
                'label' => 'Agensi / Petender',
                'icon' => 'ti-building',
                'width' => 'w-25',
            ],
            [
                'data' => 'name',
                'name' => 'name',
                'label' => 'No. Dokumen / Tajuk',
                'icon' => 'ti-file-text',
                'width' => '',
            ],
        ];
    @endphp

	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-chart-line me-2"></i>Sistem Tender Online
				</div>
				<h2>
					<i class="ti ti-chart-line me-2"></i>Carta Tender
				</h2>
			</div>

			<!-- Main Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
					<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
						<li class="nav-item">
							<a href="{{ asset('prices') }}" class="nav-link @if (!Request::get('type')) active @endif">
								<i class="ti ti-list me-2"></i>Semua
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('HomeController@prices', ['type' => 'tenders']) }}"
								class="nav-link @if (Request::get('type') == 'tenders') active @endif">
								<i class="ti ti-file-text me-2"></i>Tender
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('HomeController@prices', ['type' => 'quotations']) }}"
								class="nav-link @if (Request::get('type') == 'quotations') active @endif">
								<i class="ti ti-calculator me-2"></i>Sebut Harga
							</a>
						</li>
					</ul>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="{{ $path }}" class="DT-index table modern-table table-hover">
							<thead>
								<tr>
									@foreach ($columnsConfig as $col)
										<th class="{{ $col['width'] ?? '' }}">
											@if (isset($col['icon']))
												<i class="ti {{ $col['icon'] }} me-1"></i>
											@endif
											{{ $col['label'] ?? ucfirst($col['name']) }}
										</th>
									@endforeach
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Sidebar -->
		<div class="col-lg-3">
			{!! $sidebarHtml !!}
		</div>
	</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/easy-ticker.js') }}"></script>
    
    <script type="text/javascript">
        // Initialize DataTable
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
            var columns = [{
                    data: 'submission_datetime',
                    name: 'submission_datetime'
                },
                {
                    data: 'organization_unit_id',
                    name: 'organization_unit_id'
                },
                {
                    data: 'name',
                    name: 'name'
                }
            ];

            var DT = target.DataTable({
                ajax: path,
                columns: columns,
                serverSide: true,
                stateSave: true,
                language: {
                    sEmptyTable: "Tiada data",
                    sInfo: "_START_ - _END_ dari _TOTAL_",
                    sInfoEmpty: "0",
                    sInfoFiltered: "(Tapis: _MAX_)",
                    sLengthMenu: "_MENU_",
                    sLoadingRecords: "...",
                    sProcessing: "...",
                    sSearch: "",
                    sSearchPlaceholder: "Cari...",
                    oPaginate: {
                        sFirst: "<<",
                        sPrevious: "<",
                        sNext: ">",
                        sLast: ">>"
                    }
                },
                aaSorting: [],
                // dom: '<"row p-2"<"col-6"l><"col-6"f>>rt<"row p-2"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                pageLength: 25,
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });
        });

        // Initialize News Ticker
        $('#announcements-ticker').easyTicker({
			direction: 'up',
			easing: 'swing',
			speed: 'slow',
			interval: 3000,
			height: 'auto',
			visible: 5,
			mousePause: 1
		});
    </script>
@endsection