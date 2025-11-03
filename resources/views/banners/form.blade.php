{!! Former::populate($banner) !!}

<div class="card modern-form-card">
	<div class="card-header">
		<h3 class="card-title">
			<i class="ti ti-photo"></i>
			Maklumat Banner
		</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<!-- Title Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-file-text"></i>
					Tajuk
				</label>
				{!! Former::text('title')->label(false)->placeholder('Masukkan tajuk banner')->required()->class('form-control') !!}
			</div>

			<!-- File Upload Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label {{ !$banner->exists() ? 'required' : '' }}">
					<i class="ti ti-upload"></i>
					Fail Banner
				</label>
				<div class="file-upload-wrapper">
					<?php
					$file = Former::file('file')->label(false)->accept('image/png,image/jpg,image/jpeg')->class('form-control');
					if (!$banner->exists()) {
					    $file = $file->required();
					}
					?>
					{!! $file !!}
					<small class="form-hint">Format yang diterima: PNG, JPG, JPEG (Maksimum 5MB)</small>
				</div>

				@if ($banner->exists() && $banner->file)
					<div class="mt-3">
						<div class="preview-banner">
							<label class="form-label">Preview Banner Semasa:</label>
							<img src="{{ $banner->file->url }}/{{ $banner->file->name }}" alt="{{ $banner->title }}"
								class="img-fluid rounded shadow-sm">
						</div>
					</div>
				@endif
			</div>

			<!-- Link Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label">
					<i class="ti ti-link"></i>
					Pautan (URL)
				</label>
				{!! Former::text('link')->label(false)->placeholder('https://contoh.com')->class('form-control') !!}
				<small class="form-hint">URL yang akan dibuka apabila banner diklik</small>
			</div>

			<!-- Published Checkbox -->
			<div class="col-md-12 mb-3">
				<div class="form-check form-switch">
					{!! Former::checkbox('published')->label(false)->class('form-check-input') !!}
					<label class="form-check-label">
						<i class="ti ti-eye"></i>
						Siar banner ini
					</label>
					<small class="form-hint d-block">Aktifkan untuk memaparkan banner di laman utama</small>
				</div>
			</div>
		</div>
	</div>
</div>

@section('scripts')
	@parent
	<script type="text/javascript">
		$(document).ready(function() {
			// File input enhancement
			$('input[type="file"]').on('change', function() {
				var fileName = $(this).val().split('\\').pop();
				if (fileName) {
					var $hint = $(this).siblings('.form-hint');
					if ($hint.length) {
						$hint.text('File dipilih: ' + fileName);
					}
				}
			});
		});
	</script>
@endsection
