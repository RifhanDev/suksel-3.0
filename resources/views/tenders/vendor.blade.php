@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
	<style>
		.pdfobject-container {
			height: 70rem;
		}

		.vendor-name-icon {
			width: 44px;
			height: 44px;
			flex-shrink: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 10px;
			background: rgba(196, 30, 58, 0.07);
			color: var(--sg-red, #c41e3a);
		}

		.vendor-name-title {
			font-size: 1.15rem;
			font-weight: 800;
			color: #0f172a;
			letter-spacing: -0.2px;
			line-height: 1.3;
			margin: 0;
		}
	</style>
@endsection
@section('content')

	<div id="left-pane">
		@include('tenders._menu')

		@include('tenders._notification')

		<div class="tender-header-card d-print-none mb-4">
			<div class="tender-page-header">
				<div class="tender-ref-label">
					<span class="tender-type-label">{{ App\Tender::$types[$tender->type] }}</span>
					<span class="tender-ref-sep">·</span>
					<span class="tender-ref-no">{{ $tender->displayNoTender() }}</span>
				</div>
				<h2 class="tender-title-main">{{ $tender->name }}</h2>
			</div>

			@if ($tender->canShowTabs())
				<div class="tender-top-tabs mt-4">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a href="{{ asset('tenders/' . $tender->id) }}" class="nav-link">
								<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20" viewBox="0 0 24 24">
									<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
										<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
										<path d="M11 12h1v4h1" />
									</g>
								</svg>
								Maklumat {{ App\Tender::$types[$tender->type] }}
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="nav-link active">
								<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20" viewBox="0 0 24 24">
									<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
								</svg>
								Maklumat Syarikat
							</a>
						</li>
						@if (Auth::check() &&
								$tender->canException() &&
								auth()->user()->ability(['Admin'], ['ExceptionTender:list']))
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16" viewBox="0 0 24 24"
										fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
									</svg>
									Maklumat Kebenaran Khas
								</a>
							</li>
						@endif
					</ul>
				</div>
			@endif
		</div>

		<div class="tender-tab-card mb-4">
			<div class="card-body d-flex align-items-center justify-content-between gap-3">
				<div class="d-flex align-items-center gap-3">
					<span class="vendor-name-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
						</svg>
					</span>
					<h3 class="vendor-name-title">{{ $vendor->name }}</h3>
				</div>
				<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Kembali
				</a>
			</div>
		</div>

		@include('vendors.vendor')
	</div>

	<div id="right-pane" style="display: none;">
		<button class="btn btn-sm btn-danger pull-right mb-1 btn-file-close">Tutup</button>
		<div id="doc-view"></div>
	</div>

@endsection

@section('scripts')
	<script src="{{ asset('js/pdfobject.min.js') }}"></script>
	<script src="{{ asset('js/show.js') }}"></script>
	<script src="{{ asset('js/displayfile.js') }}"></script>
	<script type="text/javascript">
		$("#vendor_ids").selectize({
			valueField: 'id',
			labelField: 'name',
			searchField: 'name',
			create: false,
			render: {
				option: function(item, escape) {
					return '<div><strong>' + escape(item.registration) + '</strong> ' + escape(item.name) +
						'</div>';
				}
			},
			load: function(query, callback) {
				if (!query.length) return callback();
				$.ajax({
					url: '/vendors/select?q=' + query,
					type: 'GET',
					success: function(res) {
						callback(res);
					},
					error: function() {
						callback();
					}
				})
			}
		});
	</script>
@endsection
