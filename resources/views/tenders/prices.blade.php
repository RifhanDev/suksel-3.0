@extends('layouts.modernLanding')
@section('styles')
	<link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
	<style>
		/* =========================================
			CONTENT CARDS
		========================================= */
		.vendor-tender-card {
			background: #fff;
			border-radius: 12px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 1px 4px rgba(0,0,0,0.06);
			overflow: hidden;
			margin-bottom: 1.25rem;
		}

		.vendor-tender-card-header {
			background: #f8fafc;
			border-bottom: 1px solid #e5e7eb;
			padding: 14px 20px;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.vendor-tender-card-header h6 {
			margin: 0;
			font-size: 0.82rem;
			font-weight: 700;
			color: #111827;
			text-transform: uppercase;
			letter-spacing: 0.3px;
		}

		.vendor-tender-card-header .header-icon {
			width: 28px; height: 28px;
			background: rgba(196,30,58,0.08);
			color: #c41e3a;
			border-radius: 7px;
			display: flex; align-items: center; justify-content: center;
			flex-shrink: 0;
		}

		/* =========================================
			INFO TABLE — matches vendor/show style
		========================================= */
		.info-table { width: 100%; }
		.info-table tr { border-bottom: 1px solid #f1f5f9; }
		.info-table tr:last-child { border-bottom: none; }
		.info-table th {
			padding: 10px 20px;
			font-size: 0.75rem;
			font-weight: 600;
			color: #6b7280;
			width: 35%;
			vertical-align: top;
		}
		.info-table td {
			padding: 10px 20px;
			font-size: 0.82rem;
			color: #1f2937;
			font-weight: 500;
		}

		/* =========================================
			SIDEBAR
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
			padding: 0.85rem 1rem;
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
			flex-shrink: 0;
			transition: all 0.2s ease;
		}

		.news-date-small .day { font-weight: 800; color: var(--sg-red); font-size: 0.9rem; }
		.news-date-small .month { font-size: 0.55rem; color: #6b7280; text-transform: uppercase; font-weight: 700; margin-top: 2px; }

		.news-item-sidebar:hover .news-date-small { background: var(--sg-red); border-color: var(--sg-red); }
		.news-item-sidebar:hover .news-date-small .day,
		.news-item-sidebar:hover .news-date-small .month { color: white; }

		.news-info { flex: 1; min-width: 0; }

		.news-tag {
			font-size: 0.6rem;
			font-weight: 600;
			color: var(--sg-red);
			background: #fef2f2;
			padding: 0.15rem 0.4rem;
			border-radius: 2px;
			text-transform: uppercase;
			letter-spacing: 0.3px;
		}

	</style>
@endsection
@section('content')
	<div class="row g-4">
		<div class="col-lg-9">

			{{-- Page Header — matches vendor/show style --}}
			<div class="mb-4">
				<div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
					<span class="text-muted small">{{ App\Tender::$types[$tender->type] ?? 'Tender' }}</span>
					@if ($tender->ref_number)
						<span class="text-muted small">·</span>
						<span class="fw-semibold small text-dark">{{ $tender->ref_number }}</span>
					@endif
				</div>
				<h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">{{ $tender->name }}</h3>
				<div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
					<span class="text-muted small">{{ optional($tender->tenderer)->name }}</span>
				</div>
			</div>

			{{-- Action Buttons --}}
			<div class="d-flex gap-2 flex-wrap mb-4">
				<a href="{{ asset('agencies/' . $tender->tenderer->id) }}" class="tender-agency-link text-capitalize">
					<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
						<path d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
					</svg>
					<span>{{ $tender->type }} oleh <strong>{{ $tender->tenderer->name }}</strong></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="ms-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
						<path d="M9 6l6 6l-6 6" />
					</svg>
				</a>
				@if (Auth::check())
					@if (Auth::user()->hasRole('Admin'))
						<a href="{{ asset('tenders') }}" class="btn-form btn-form-secondary">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l11 0"/><path d="M9 12l11 0"/><path d="M9 18l11 0"/><path d="M5 6l0 .01"/><path d="M5 12l0 .01"/><path d="M5 18l0 .01"/></svg>
							Senarai Tender
						</a>
					@endif
					@if ($tender->canUpdate())
						<a href="{{ asset('tenders/' . $tender->id . '/edit') }}" class="btn-form btn-form-secondary">
							<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
							Kemaskini
						</a>
					@endif
				@endif
			</div>

			{{-- Winner / Carta sub-tabs --}}
			@if ($tender->publish_winner)
				<div class="subtabs-pill-card">
					<div class="card-body">
						<div class="nav-pill-modern" role="tablist">
							<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}"
								class="nav-link @if (!Request::get('show')) active @endif">
								<i class="ti ti-chart-bar me-2"></i>Carta Tender
							</a>
							<a href="{{ route('tenders.vendors', [$tender->id, 'show' => 'winner']) }}"
								class="nav-link @if (Request::get('show') == 'winner') active @endif">
								<i class="ti ti-trophy me-2"></i>Penender Berjaya
							</a>
						</div>
					</div>
				</div>
			@endif

			{{-- Winner Details --}}
			@if (Request::get('show') == 'winner')
				<div class="vendor-tender-card">
					<div class="vendor-tender-card-header">
						<div class="header-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1.002l3.086 -6.253l3.086 6.253l6.9 1.002l-5 4.867l1.179 6.873z" /></svg>
						</div>
						<h6>Penender Berjaya</h6>
					</div>
					@if (isset($winner) && $tender->publish_winner)
						<table class="info-table">
							<tr>
								<th>Nama Syarikat</th>
								<td>{{ $winner->vendor->name }}</td>
							</tr>
							<tr>
								<th>Tempoh Siap</th>
								<td>
									@if ($winner->project_timeline)
										{{ $winner->project_timeline }}
									@else
										<span class="text-muted">Tidak dinyatakan</span>
									@endif
								</td>
							</tr>
							<tr>
								<th>Harga Tawaran</th>
								<td>
									@if ($winner->price)
										<span class="h4 text-success fw-bold mb-0">RM {{ number_format($winner->price, 2) }}</span>
									@else
										<span class="text-muted">Tidak dinyatakan</span>
									@endif
								</td>
							</tr>
						</table>
					@else
						<div class="p-4 text-center text-muted small">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 d-block mx-auto" style="opacity:0.3;"><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1.002l3.086 -6.253l3.086 6.253l6.9 1.002l-5 4.867l1.179 6.873z" /></svg>
							Penender Berjaya Belum Diumumkan.<br>Keputusan tender akan diumumkan kemudian.
						</div>
					@endif
				</div>

			{{-- Carta Tender --}}
			@else
				<div class="vendor-tender-card">
					<div class="vendor-tender-card-header">
						<div class="header-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 13a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm12-4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM9 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 20h14" /></svg>
						</div>
						<h6>Carta Tender</h6>
					</div>
					@if (count($prices) > 0)
						<div class="table-responsive">
							<table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
								<thead style="background:#f8fafc;">
									<tr>
										<th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Label Syarikat</th>
										<th class="py-3 pe-4 text-end" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Harga Tawaran (RM)</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($prices as $purchase)
										<tr style="border-color:#e5e7eb;">
											<td class="ps-4 fw-semibold">{{ $purchase->label }}</td>
											<td class="pe-4 text-end fw-bold text-primary">{{ number_format($purchase->price, 2) }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<div class="p-4 text-center text-muted small">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 d-block mx-auto" style="opacity:0.3;"><path d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" /></svg>
							Tiada syarikat yang menyertai tender ini.
						</div>
					@endif
				</div>
			@endif

			{{-- Bottom Nav: go to Maklumat Tender --}}
			@if ($tender->canShowTabs())
				<div class="mt-3">
					<a href="{{ asset('tenders/' . $tender->id) }}?from=prices" class="btn-form btn-form-secondary">
						<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
						Maklumat {{ App\Tender::$types[$tender->type] ?? 'Tender' }} / Sebut Harga
					</a>
				</div>
			@endif

		</div>

		<div class="col-lg-3">
			<div class="d-flex flex-column gap-3">
				@include('layouts._register')
				@include('layouts._news')
			</div>
		</div>
	</div>

@endsection
@section('scripts')
	<script src="{{ asset('js/tender-vue.js') }}"></script>
	<script src="{{ asset('js/easy-ticker.js') }}"></script>
	<script>
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
