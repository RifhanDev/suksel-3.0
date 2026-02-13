@extends('layouts.modernLanding')

@section('styles')
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
	<style>
		.help-category-card {
			transition: all 0.3s ease;
			border: 1px solid #e9ecef;
			cursor: pointer;
		}

		.help-category-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
			border-color: #667eea;
		}

		.help-category-card:hover .ti-chevron-right {
			color: #667eea !important;
			transform: translateX(4px);
		}

		.help-category-card .ti-chevron-right {
			transition: all 0.3s ease;
		}
	</style>
@endsection

@section('content')
<div class="row g-4">
    
    <!-- LEFT -->
    <div class="col-lg-9">
        
        <!-- Header -->
        <div class="page-header-modern">
            <div class="header-content">
                <div class="header-pretitle">Pusat Bantuan</div>
                <h2 class="header-title">Soalan Lazim & Panduan</h2>
            </div>
            
            @if (Auth::user() && Auth::user()->hasRole('Admin'))
                <div class="header-actions">
                    <a href="{{ asset('helpcategories') }}" class="btn btn-warning btn-sm fw-bold d-inline-flex align-items-center gap-1">
                        <i class="ti ti-tags"></i> Kategori
                    </a>
                    <a href="{{ asset('helpcategories') }}" class="btn btn-primary btn-sm fw-bold d-inline-flex align-items-center gap-1">
                        <i class="ti ti-plus"></i> Tambah Topik
                    </a>
                </div>
            @else
                <div class="header-icon-box d-none d-md-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 17l0 .01" /><path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4" /></svg>
                </div>
            @endif
        </div>

	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-help me-2"></i>Sistem Tender Online
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-help me-2"></i>Bantuan
					</h2>
					@if (Auth::user() && Auth::user()->hasRole('Admin'))
						<div class="d-flex gap-2">
							<a href="{{ asset('helpcategories') }}" class="btn btn-warning btn-modern">
								<i class="ti ti-tags me-1"></i>Kategori
							</a>
							<a href="{{ asset('helpcategories') }}" class="btn btn-primary btn-modern">
								<i class="ti ti-plus me-1"></i>Tambah Soalan Lazim
							</a>
						</div>
					@endif
				</div>
			</div>

			<!-- Search Card -->
			<div class="card modern-card mb-4">
				<div class="card-body">
					{!! Former::open(action('HelpsController@search'))->class('d-flex gap-2')->method('GET') !!}
					@csrf
					<div class="flex-grow-1">
						<input type="text" name="q" class="form-control" placeholder="Carian soalan lazim...">
					</div>
					<button type="submit" class="btn btn-primary btn-modern">
						<i class="ti ti-search me-1"></i>Cari
					</button>
					{!! Former::close() !!}
				</div>
			</div>

			<!-- Categories Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
					<h3 class="card-title-modern mb-0">
						<i class="ti ti-category me-2"></i>Kategori Bantuan
					</h3>
				</div>
				<div class="card-body">
					@if (count($categories) == 0)
						<div class="alert alert-info">
							<i class="ti ti-info-circle me-2"></i>Tiada soalan lazim.
						</div>
					@else
						<div class="row">
							@foreach ($categories as $category)
								<div class="col-lg-6 mb-3">
									<a href="{{ asset('helps/' . $category->id) }}" class="text-decoration-none">
										<div class="card modern-card h-100 help-category-card">
											<div class="card-body">
												<div class="d-flex align-items-start mb-2">
													<div class="flex-grow-1">
														<h5 class="mb-1" style="color: #2c3e50; font-weight: 600;">
															<i class="ti ti-folder me-2"></i>{{ $category->name }}
														</h5>
														<small class="text-muted">
															<i class="ti ti-file-text me-1"></i>{{ $category->helps->count() }} artikel
														</small>
													</div>
													<i class="ti ti-chevron-right" style="color: #6c757d; font-size: 1.25rem;"></i>
												</div>
												@if ($category->description)
													<p class="text-muted mb-0" style="font-size: 0.875rem;">
														{{ $category->description }}
													</p>
												@endif
											</div>
										</div>
									</a>
								</div>
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>

		<!-- Sidebar -->
		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>

</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/news.js') }}"></script>
    <script src="{{ asset('js/easy-ticker.js') }}"></script>
    
    <script>
        $(document).ready(function(){
            $('#announcements-ticker').easyTicker({
                direction: 'up',
                easing: 'swing',
                speed: 'slow',
                interval: 3000,
                height: 'auto',
                visible: 5,
                mousePause: 1
            });
        });
    </script>
@endsection