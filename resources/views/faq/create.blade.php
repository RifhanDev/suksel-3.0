@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Tambah Soalan ChatBot</h3>
			<p class="text-muted small m-0">Sila lengkapkan maklumat soalan di bawah.</p>
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

	<form action="{{ route('chatbot-manager.question.store') }}" method="POST">
		@csrf

		<div class="content-card">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Soalan</span>
			</div>

			<div class="p-4">
				<div class="row g-3">
					<!-- Soalan -->
					<div class="col-12">
						<label for="question" class="form-label fw-medium small">Soalan <span class="text-danger">*</span></label>
						<input class="form-control" required id="question" name="question" type="text"
							value="{{ old('question') ?? '' }}" placeholder="Masukkan soalan">
						@error('question')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Jawapan -->
					<div class="col-12">
						<label for="answer" class="form-label fw-medium small">Jawapan <span class="text-danger">*</span></label>
						<input class="form-control" required id="answer" name="answer" type="text"
							value="{{ old('answer') ?? '' }}" placeholder="Masukkan jawapan">
						@error('answer')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Kategori Soalan -->
					<div class="col-12">
						<label for="faq_category_id" class="form-label fw-medium small">Kategori Soalan</label>
						<select name="faq_category_id" id="faq_category_id" class="form-select">
							<option value="0">-Sila Pilih-</option>
							@foreach ($faq_categories as $faq_category)
								<option value="{{ $faq_category->id }}" {{ old('faq_category_id') == $faq_category->id ? 'selected' : '' }}>
									{{ $faq_category->name }}
								</option>
							@endforeach
						</select>
						@error('faq_category_id')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Perlukan Jawapan -->
					<div class="col-12">
						<div class="form-check form-switch d-flex align-items-start gap-3">
							<input type="checkbox" class="form-check-input mt-1" id="require_input_text"
								name="require_input_text" value="1"
								{{ ($data->require_input_text ?? 0) == '1' ? 'checked' : '' }}
								style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
							<div>
								<label class="form-check-label fw-medium small mb-0" for="require_input_text">Perlukan Jawapan</label>
								<small class="text-muted d-block mt-1">Penanya perlu menjawab di dalam satu perenggan</small>
							</div>
						</div>
					</div>

					<!-- Perlukan Gambar Lampiran -->
					<div class="col-12">
						<div class="form-check form-switch d-flex align-items-start gap-3">
							<input type="checkbox" class="form-check-input mt-1" id="require_input_attachment"
								name="require_input_attachment" value="1"
								{{ ($data->require_input_attachment ?? 0) == '1' ? 'checked' : '' }}
								style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
							<div>
								<label class="form-check-label fw-medium small mb-0" for="require_input_attachment">Perlukan Gambar Lampiran</label>
								<small class="text-muted d-block mt-1">Penanya perlu memuatnaik gambar sebagai lampiran (Satu Gambar sahaja)</small>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
				<a href="{{ route('chatbot-manager.question.index') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
						fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Batal
				</a>
				<button type="submit" class="btn-form btn-form-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
						fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
