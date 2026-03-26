@extends('layouts.v3.master')
@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Kategori Soalan ChatBot</h3>
			<p class="text-muted small m-0">Kemaskini maklumat kategori soalan ChatBot.</p>
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

	<form id="saveForm" name="saveForm" action="{{ route('chatbot-manager.category.update', ['category' => $data->enc_id]) }}"
		method="POST">
		@csrf
		@method('PUT')

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
						<input class="form-control" required id="name" name="name" type="text" value="{{ $data->name }}">
						@error('name')
							<div class="text-danger small mt-1">** {{ $message }}</div>
						@enderror
					</div>

					<!-- Checkbox -->
					<div class="col-12">
						<div class="form-check form-switch d-flex align-items-start gap-3">
							<input type="checkbox" class="form-check-input mt-1" id="show_none_btn" name="show_none_btn" value="1"
								{{ ($data->show_none_btn ?? 0) == '1' ? 'checked' : '' }} style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
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
				<div class="d-flex gap-2">
					<button type="button" class="btn-form btn-form-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="3 6 5 6 21 6"></polyline>
							<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
							<path d="M10 11v6"></path>
							<path d="M14 11v6"></path>
							<path d="M9 6V4h6v2"></path>
						</svg>
						Padam
					</button>
					<button type="submit" form="saveForm" class="btn-form btn-form-primary">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
							<polyline points="17 21 17 13 7 13 7 21"></polyline>
							<polyline points="7 3 7 8 15 8"></polyline>
						</svg>
						Kemaskini
					</button>
				</div>
			</div>
		</div>
	</form>

	<!-- Delete Modal -->
	<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fw-bold" id="deleteModalLabel">Pengesahan Padam</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="mb-0">Adakah anda pasti untuk memadam rekod ini?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Tidak</button>
					<form action="{{ route('chatbot-manager.category.destroy', ['category' => $data->enc_id]) }}" method="POST"
						class="d-inline m-0">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn-form btn-form-danger">Ya, Padam</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
