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
        content: ''; display: block; width: 20px; height: 2px; background: var(--sg-yellow);
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
        display: flex; align-items: center; justify-content: center;
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

    /* Modern Tabs */
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
        display: flex; align-items: center; gap: 0.5rem;
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
       SIDEBAR
       ========================================= */
    .sidebar-widget {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05);
        border: 1px solid #f3f4f6;
        overflow: hidden;
        margin-bottom: 1rem; /* Reduced margin as per request */
    }
    
    .sidebar-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center;
    }

    .sidebar-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .news-item-sidebar {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex; gap: 0.75rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    .news-item-sidebar:hover { background: #fef2f2; }
    
    .news-date-small {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: var(--radius-sm);
        min-width: 44px; height: 44px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        line-height: 1;
    }
    .news-date-small .day { font-weight: 800; color: var(--sg-red); font-size: 0.9rem; }
    .news-date-small .month { font-size: 0.55rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin-top: 2px;}

    .news-item-sidebar:hover .news-date-small { background: var(--sg-red); border-color: var(--sg-red); }
    .news-item-sidebar:hover .news-date-small .day, 
    .news-item-sidebar:hover .news-date-small .month { color: white; }

    @media (max-width: 768px) {
        .page-header-modern { flex-direction: column; align-items: flex-start; padding: 1.5rem; gap: 1.5rem; }
        .header-icon-box { display: none; }
    }
</style>
@endsection

@section('content')
	<div class="row g-4">
		<!-- LEFT CONTENT -->
		<div class="col-lg-9">
			
			<!-- Header -->
            <div class="page-header-modern">
                <div class="header-content">
                    {{-- TEMP-HIDE (revert to restore): <div class="header-pretitle">Sistem Perolehan Selangor</div> --}}
                    <h2 class="header-title">Penender Berjaya</h2>
                    <p class="header-subtitle">
                        Senarai keputusan rasmi tender dan sebut harga yang telah dianugerahkan kepada vendor yang berjaya.
                    </p>
                </div>
                
                <div class="header-icon-box d-none d-md-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 21l8 0" /><path d="M12 17l0 4" /><path d="M7 4l10 0" /><path d="M17 4v8a5 5 0 0 1 -10 0v-8" /><path d="M5 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /></svg>
                </div>
            </div>

			<!-- Main Card -->
			<div class="tender-card">
				<!-- Tabs -->
				<div class="tender-toolbar">
					<ul class="nav nav-modern-tabs">
						<li class="nav-item">
							<a href="{{ asset('results') }}" class="nav-link @if (!Request::get('type')) active @endif">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l11 0" /><path d="M9 12l11 0" /><path d="M9 18l11 0" /><path d="M5 6l0 .01" /><path d="M5 12l0 .01" /><path d="M5 18l0 .01" /></svg>
								Semua
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('HomeController@results', ['type' => 'tenders']) }}"
								class="nav-link @if (Request::get('type') == 'tenders') active @endif">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
								Tender
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('HomeController@results', ['type' => 'quotations']) }}"
								class="nav-link @if (Request::get('type') == 'quotations') active @endif">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h10.5a.5 .5 0 0 1 .5 .5v11a.5 .5 0 0 1 -.5 .5h-14.5a.5 .5 0 0 1 -.5 -.5v-11a.5 .5 0 0 1 .5 -.5h1.5" /><path d="M14 6v-3h-4v3" /><path d="M6 12h2" /><path d="M6 15h2" /></svg>
								Sebut Harga
							</a>
						</li>
					</ul>
				</div>

				<div class="card-body p-3">
					<div class="table-responsive">
						<table class="DT-index table table-modern w-100 mb-0" data-path="{{ $path }}">
							<thead>
								<tr>
									<th class="w-15 text-center">
										<i class="ti ti-calendar me-1"></i>Tarikh Tutup
									</th>
									<th class="w-25">
										<i class="ti ti-building me-1"></i>Agensi / Petender
									</th>
									<th>
										<i class="ti ti-file-text me-1"></i>No. Dokumen / Tajuk
									</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- RIGHT SIDEBAR -->
		<div class="col-lg-3">
            <div class="d-flex flex-column gap-3">
			    @include('layouts._register')
			    @include('layouts._news')
            </div>
		</div>
	</div>
@endsection

@section('scripts')
	{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}} <!-- Loaded in master -->
	<script src="{{ asset('js/easy-ticker.js') }}"></script>

	<script type="text/javascript">
		// Initialize DataTable
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			var DT = target.DataTable({
				ajax: path,
				columns: [{
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
					},
				],
				serverSide: true,
				stateSave: true,
				language: {
					sEmptyTable: "Tiada data",
					sInfo: "_START_ - _END_ dari _TOTAL_",
					sInfoEmpty: "0",
					sInfoFiltered: "(Tapis: _MAX_)",
					sInfoPostFix: "",
					sInfoThousands: ",",
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