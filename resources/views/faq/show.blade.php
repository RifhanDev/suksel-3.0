@extends('layouts.v3.master')
@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Maklumat Soalan ChatBot</h3>
			<p class="text-muted small m-0">Paparan butiran soalan dan jawapan ChatBot.</p>
		</div>
	</div>

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
					<label for="question" class="form-label fw-medium small">Soalan</label>
					<input class="form-control" id="question" name="question" type="text" value="{{ $data->question }}" disabled>
				</div>

				<!-- Jawapan -->
				<div class="col-12">
					<label for="answer" class="form-label fw-medium small">Jawapan</label>
					<input class="form-control" id="answer" name="answer" type="text" value="{{ $data->answer }}" disabled>
				</div>

				<!-- Kategori Soalan -->
				<div class="col-12">
					<label for="faq_category_id" class="form-label fw-medium small">Kategori Soalan</label>
					<select name="faq_category_id" id="faq_category_id" class="form-select" disabled>
						<option value="0">-Sila Pilih-</option>
						@foreach ($faq_categories as $faq_category)
							<option value="{{ $faq_category->id }}" {{ $data->faq_category_id == $faq_category->id ? 'selected' : '' }}>
								{{ $faq_category->name }}
							</option>
						@endforeach
					</select>
				</div>

				<!-- Perlukan Jawapan -->
				<div class="col-12">
					<div class="form-check form-switch d-flex align-items-start gap-3">
						<input type="checkbox" class="form-check-input mt-1" id="require_input_text" value="1"
							{{ ($data->require_input_text ?? 0) == '1' ? 'checked' : '' }} disabled
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
						<input type="checkbox" class="form-check-input mt-1" id="require_input_attachment" value="1"
							{{ ($data->require_input_attachment ?? 0) == '1' ? 'checked' : '' }} disabled
							style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
						<div>
							<label class="form-check-label fw-medium small mb-0" for="require_input_attachment">Perlukan Gambar
								Lampiran</label>
							<small class="text-muted d-block mt-1">Penanya perlu memuatnaik gambar sebagai lampiran (Satu Gambar
								sahaja)</small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
			<a href="{{ route('chatbot-manager.question.index') }}" class="btn-form btn-form-secondary">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="19" y1="12" x2="5" y2="12"></line>
					<polyline points="12 19 5 12 12 5"></polyline>
				</svg>
				Batal
			</a>
			<a href="{{ route('chatbot-manager.question.edit', ['question' => $data->enc_id]) }}"
				class="btn-form btn-form-primary">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
					<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
				</svg>
				Kemaskini
			</a>
		</div>
	</div>
@endsection
