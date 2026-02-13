@extends('layouts.modern')

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-history me-2"></i>Sistem Tender Online Selangor
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-plus me-2"></i>Tambah Rekod Versi
					</h2>
					<a href="{{ route('version-histories.index') }}" class="btn btn-outline-secondary btn-modern">
						<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
					</a>
				</div>
			</div>

			{!! Former::open(route('version-histories.store')) !!}
			{!! Former::populate($versionHistory) !!}

			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
					<h3 class="card-title mb-0">
						<i class="ti ti-file-text me-2"></i>Maklumat Versi
					</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label required">Versi</label>
							{!! Former::text('version')->label(false)->placeholder('Contoh: 1.0')->required()->class('form-control') !!}
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label required">Tarikh</label>
							<input type="date" name="released_at" class="form-control" value="{{ old('released_at') }}" required>
						</div>
						<div class="col-12 mb-3">
							<label class="form-label">Nota (satu baris per item)</label>
							{!! Former::textarea('notes')->label(false)->placeholder("Masukkan setiap perubahan pada baris berasingan.\nContoh:\nLive\nItem perubahan 1\nItem perubahan 2")->rows(10)->class('form-control') !!}
							<small class="text-muted">Setiap baris akan dipaparkan sebagai item dalam senarai bernombor.</small>
						</div>
					</div>
				</div>
				<div class="card-footer d-flex justify-content-between">
					<a href="{{ route('version-histories.index') }}" class="btn btn-secondary btn-modern">
						<i class="ti ti-arrow-left me-1"></i>Batal
					</a>
					<button type="submit" class="btn btn-primary btn-modern">
						<i class="ti ti-check me-1"></i>Simpan
					</button>
				</div>
			</div>
			{!! Former::close() !!}
		</div>

		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/news.js') }}"></script>
@endsection
