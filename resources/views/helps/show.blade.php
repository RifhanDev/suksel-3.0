@extends('layouts.v3.master')

@section('styles')
	<style>
		.help-accordion .accordion-item {
			border: 1px solid #e9ecef;
			border-radius: 8px;
			margin-bottom: 1rem;
			overflow: hidden;
			transition: all 0.3s ease;
		}

		.help-accordion .accordion-item:hover {
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		}

		.help-accordion .accordion-button {
			background: #f8f9fa;
			font-weight: 600;
			color: #2c3e50;
			border: none;
			padding: 1.25rem 1.5rem;
		}

		.help-accordion .accordion-button:not(.collapsed) {
			background: #f8f9fa;
			color: #2c3e50;
			box-shadow: none;
		}

		.help-accordion .accordion-button:focus {
			box-shadow: none;
			border-color: transparent;
		}

		.help-accordion .accordion-body {
			background: white;
			padding: 1.5rem;
			color: #495057;
			line-height: 1.6;
		}

		.help-accordion .accordion-button::after {
			background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232c3e50'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
		}
	</style>
@endsection

@section('content')
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
							<a href="{{ asset('helps/create') }}" class="btn btn-primary btn-modern">
								<i class="ti ti-plus me-1"></i>Tambah Soalan Lazim
							</a>
						</div>
					@endif
				</div>
			</div>

			<!-- Category Info Card -->
			<div class="card modern-card mb-4">
				<div class="card-body">
					<div class="d-flex align-items-start">
						<div class="flex-grow-1">
							<h4 class="mb-2" style="color: #2c3e50; font-weight: 600;">
								<i class="ti ti-folder me-2"></i>{{ $category->name }}
							</h4>
							<p class="text-muted mb-2">
								<i class="ti ti-file-text me-1"></i>{{ $helps->count() }} artikel
							</p>
							@if ($category->description)
								<p class="text-muted mb-0">{{ $category->description }}</p>
							@endif
						</div>
						<a href="{{ asset('helps') }}" class="btn btn-outline-secondary btn-modern">
							<i class="ti ti-arrow-left me-1"></i>Kembali
						</a>
					</div>
				</div>
			</div>

			<!-- Search Card -->
			<div class="card modern-card mb-4">
				<div class="card-body">
					{!! Former::open(action('HelpsController@search'))->class('d-flex gap-2')->method('GET') !!}
					<div class="flex-grow-1">
						<input type="text" name="q" class="form-control" placeholder="Carian soalan lazim...">
					</div>
					<button type="submit" class="btn btn-primary btn-modern">
						<i class="ti ti-search me-1"></i>Cari
					</button>
					{!! Former::close() !!}
				</div>
			</div>

			<!-- FAQ Items Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
					<h3 class="card-title-modern mb-0">
						<i class="ti ti-question-mark me-2"></i>Soalan Lazim
					</h3>
				</div>
				<div class="card-body">
					@if (count($helps) == 0)
						<div class="alert alert-info">
							<i class="ti ti-info-circle me-2"></i>Tiada soalan lazim.
						</div>
					@else
						<div class="accordion help-accordion" id="helps">
							@foreach ($helps as $help)
								@include('helps.helps')
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
@endsection


@section('scripts')
	<script src="{{ asset('js/news.js') }}"></script>
@endsection
