@extends('layouts.v3.master')
@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Tambah Kategori Soalan ChatBot</h3>
			<p class="text-muted small m-0">Sila lengkapkan maklumat kategori di bawah.</p>
		</div>
	</div>

	@if ($errors->any())
		<div class="alert alert-danger mb-3">
			<ul class="mb-0">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form action="{{ route('chatbot-manager.category.store') }}" method="POST">
		@csrf

		<div class="content-card">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Kategori</span>
			</div>

			<div class="p-4">
				<div class="row g-3">
					<!-- Nama -->
					<div class="col-12">
						<label for="name" class="form-label fw-medium small">Nama <span class="text-danger">*</span></label>
						<input class="form-control" required id="name" name="name" type="text" value="{{ old('name') ?? '' }}"
							placeholder="Masukkan nama kategori">
						@error('name')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Checkbox -->
					<div class="col-12">
						<div class="form-check form-switch d-flex align-items-start gap-3">
							<input type="checkbox" class="form-check-input mt-1" id="show_none_btn" name="show_none_btn" value="1"
								style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
							<div>
								<label class="form-check-label fw-medium small mb-0" for="show_none_btn">
									Papar butang <q>Bukan disenarai diatas</q>
								</label>
								<small class="text-muted d-block mt-1">Papar pilihan tambahan jika soalan yang ingin ditanya tidak wujud di
									pangkalan data</small>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
				<a href="{{ route('chatbot-manager.category.index') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Batal
				</a>
				<button type="submit" class="btn-form btn-form-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
						<polyline points="17 21 17 13 7 13 7 21"></polyline>
						<polyline points="7 3 7 8 15 8"></polyline>
					</svg>
					Simpan
				</button>
			</div>
		</div>
	</form>
@endsection
