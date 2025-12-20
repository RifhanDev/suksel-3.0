<!-- Form Card -->
<div class="stats-card mb-4">
	<div class="stats-card-header p-4 border-bottom">
		<div class="d-flex align-items-center gap-3">
			<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 20l-7-7-7 7V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
			</div>
			<h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">Maklumat Berita</h3>
		</div>
	</div>
	<div class="card-body p-4">
		<div class="row">
			<!-- Title Field -->
			<div class="col-md-12 mb-3">
				<label for="title" class="form-label fw-semibold">
					Tajuk <span class="text-danger">*</span>
				</label>
				<input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
					value="{{ old('title', isset($news) ? $news->title : '') }}"
					placeholder="Masukkan tajuk berita" required>
				@error('title')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<!-- Content Field -->
			<div class="col-md-12 mb-3">
				<label for="notification" class="form-label fw-semibold">
					Kandungan <span class="text-danger">*</span>
				</label>
				<textarea class="form-control @error('notification') is-invalid @enderror" rows="6" id="notification" name="notification" required>{!! old('notification', isset($news) ? $news->notification : '') !!}</textarea>
				@error('notification')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<!-- Agency Field (Admin only) -->
			@if (Auth::user()->hasRole('Admin'))
				<div class="col-md-12 mb-3">
					<label for="organization_unit_id" class="form-label fw-semibold">
						Agensi <span class="text-danger">*</span>
					</label>
					<select class="@error('organization_unit_id') is-invalid @enderror" id="organization_unit_id" name="organization_unit_id" required>
						<option value="">-- Pilih Agensi --</option>
						@foreach (App\OrganizationUnit::all() as $agency)
							<option value="{{ $agency->id }}" {{ old('organization_unit_id', isset($news) ? $news->organization_unit_id : '') == $agency->id ? 'selected' : '' }}>
								{{ $agency->name }}
							</option>
						@endforeach
					</select>
					@error('organization_unit_id')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
			@endif

			<!-- Tender Field -->
			<div class="col-md-12 mb-3">
				<label for="tender_id" class="form-label fw-semibold">
					Tender <span class="text-muted">(Pilihan)</span>
				</label>
				<select class="@error('tender_id') is-invalid @enderror" id="tender_id" name="tender_id">
					<option value="">Sila cari menggunakan nama tender atau no rujukan...</option>
					@if (isset($news) && $news->tender)
						<option value="{{ $news->tender->id }}" selected>{{ $news->tender->ref_number }} - {{ $news->tender->name }}</option>
					@endif
				</select>
				@error('tender_id')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>
		</div>
	</div>
</div>
