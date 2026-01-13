@extends('layouts.modern')
@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-category me-2"></i>Sistem Tender Online
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-edit me-2"></i>Kemaskini Kategori Soalan Lazim
					</h2>
					@if (Auth::user() && Auth::user()->hasRole('Admin'))
						<div class="d-flex gap-2">
							<a href="{{ asset('helps') }}" class="btn btn-warning btn-modern">
								<i class="ti ti-help me-1"></i>Soalan Lazim
							</a>
							<a href="{{ route('helpcategories.index') }}" class="btn btn-primary btn-modern">
								<i class="ti ti-list me-1"></i>Senarai Kategori
							</a>
						</div>
					@endif
				</div>
			</div>

			{!! Former::open(url('helpcategories/' . $category->id)) !!}
			{!! Former::populate($category) !!}
			{!! Former::hidden('_method', 'PUT') !!}

			<!-- Form Card -->
			<div class="card modern-form-card">
				<div class="card-header">
					<h3 class="card-title">
						<i class="ti ti-file-text"></i>
						Maklumat Kategori
					</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 mb-3">
							<label class="form-label required">
								<i class="ti ti-tag"></i>
								Nama
							</label>
							{!! Former::text('name')->label(false)->placeholder('Masukkan nama kategori')->required()->class('form-control') !!}
						</div>

						<div class="col-md-12 mb-3">
							<label class="form-label required">
								<i class="ti ti-file-description"></i>
								Keterangan
							</label>
							{!! Former::text('description')->label(false)->placeholder('Masukkan keterangan kategori')->required()->class('form-control') !!}
						</div>
					</div>
				</div>
			</div>

			<!-- Action Buttons -->
			<div class="card modern-form-card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
						<a href="{{ route('helpcategories.index') }}" class="btn btn-secondary btn-modern">
							<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
						</a>
						<button type="submit" class="btn btn-primary btn-modern">
							<i class="ti ti-check me-1"></i>Simpan
						</button>
					</div>
				</div>
			</div>
			{!! Former::close() !!}
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
