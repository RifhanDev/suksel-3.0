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

			<!-- Date Range Fields -->
			<div class="col-md-6 mb-3">
				<label class="form-label">
					<i class="ti ti-calendar"></i>
					Tarikh Mula Paparan
				</label>
				{!! Former::text('start')->label(false)->placeholder('Pilih tarikh mula')->class('form-control datepicker') !!}
			</div>

			<div class="col-md-6 mb-3">
				<label class="form-label">
					<i class="ti ti-calendar"></i>
					Tarikh Tamat Paparan
				</label>
				{!! Former::text('end')->label(false)->placeholder('Pilih tarikh tamat')->class('form-control datepicker') !!}
			</div>

			<!-- Published Checkbox -->
			<div class="col-md-12 mb-3">
				<div class="form-check form-switch d-flex align-items-start">
					{!! Former::checkbox('published')->label(false)->class('form-check-input') !!}
					<div class="ms-3">
						<label class="form-check-label mb-0">
							<i class="ti ti-eye me-1"></i>
							Siar banner ini
						</label>
						<small class="form-hint d-block mt-1">Aktifkan untuk memaparkan banner di laman utama</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	/* Datepicker Custom Styling - Light Theme */
	.datepicker {
		background: white !important;
		border: 1px solid #dee2e6 !important;
		border-radius: 8px !important;
		padding: 10px !important;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
	}

	.datepicker table {
		background: white !important;
	}

	.datepicker table tr th,
	.datepicker table tr td {
		background: white !important;
		color: #495057 !important;
		border-radius: 4px !important;
	}

	.datepicker table tr th {
		font-weight: 600 !important;
		color: #667eea !important;
		background: #f8f9fa !important;
	}

	.datepicker thead tr:first-child th {
		background: #667eea !important;
		color: white !important;
		font-weight: 500 !important;
	}

	.datepicker thead tr:first-child th:hover {
		background: #5568d3 !important;
	}

	.datepicker table tr td.day:hover,
	.datepicker table tr td.focused {
		background: #e9ecef !important;
		color: #495057 !important;
		cursor: pointer !important;
	}

	.datepicker table tr td.active,
	.datepicker table tr td.active:hover,
	.datepicker table tr td.active.highlighted {
		background: #667eea !important;
		color: white !important;
		font-weight: 600 !important;
	}

	.datepicker table tr td.today,
	.datepicker table tr td.today:hover {
		background: #ffc107 !important;
		color: white !important;
		font-weight: 600 !important;
	}

	.datepicker table tr td.today.active {
		background: #667eea !important;
		color: white !important;
	}

	.datepicker table tr td.old,
	.datepicker table tr td.new {
		color: #adb5bd !important;
		background: white !important;
	}

	.datepicker table tr td.disabled,
	.datepicker table tr td.disabled:hover {
		color: #dee2e6 !important;
		background: white !important;
		cursor: not-allowed !important;
	}

	.datepicker table tr td span {
		background: white !important;
		color: #495057 !important;
	}

	.datepicker table tr td span:hover,
	.datepicker table tr td span.focused {
		background: #e9ecef !important;
	}

	.datepicker table tr td span.active,
	.datepicker table tr td span.active:hover {
		background: #667eea !important;
		color: white !important;
	}

	.datepicker .datepicker-switch,
	.datepicker .prev,
	.datepicker .next,
	.datepicker tfoot tr th {
		color: #495057 !important;
		background: white !important;
	}

	.datepicker .datepicker-switch:hover,
	.datepicker .prev:hover,
	.datepicker .next:hover,
	.datepicker tfoot tr th:hover {
		background: #e9ecef !important;
	}
</style>

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

			// Initialize datepicker for date fields
			$('.datepicker').datepicker({
				format: 'd M yyyy',
				autoclose: true,
				todayHighlight: true,
				orientation: 'bottom auto'
			});

			// Set minimum end date based on start date
			$('input[name="start"]').on('change', function() {
				var startDate = $(this).datepicker('getDate');
				$('input[name="end"]').datepicker('setStartDate', startDate);
			});
		});
	</script>
@endsection
