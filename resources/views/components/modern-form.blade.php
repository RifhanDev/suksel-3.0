{{-- 
	Modern Form Page Component
	
	Usage:
	@include('components.modern-form', [
		'title' => 'Masukkan Banner Baru',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-photo',
		'backUrl' => route('banners.index'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Hantar Banner',
		'showViewButton' => false,
		'viewUrl' => null,
		'viewLabel' => 'Lihat'
	])
--}}

@php
	$title = $title ?? 'Form';
	$pretitle = $pretitle ?? 'Sistem Tender Online';
	$icon = $icon ?? 'ti-file';
	$backUrl = $backUrl ?? url()->previous();
	$backLabel = $backLabel ?? 'Kembali';
	$submitLabel = $submitLabel ?? 'Hantar';
	$showViewButton = $showViewButton ?? false;
	$viewUrl = $viewUrl ?? null;
	$viewLabel = $viewLabel ?? 'Lihat';
@endphp

<style>
	/* Modern Form Layout */
	.page-header-modern {
		background: linear-gradient(135deg, #e0dfdf 0%, #c44f4f 100%);
		color: white;
		padding: 2rem;
		border-radius: 12px;
		margin-bottom: 2rem;
	}

	.page-header-modern h2 {
		margin: 0;
		font-weight: 600;
		font-size: 1.75rem;
	}

	.page-header-modern .page-pretitle {
		opacity: 0.9;
		font-size: 0.875rem;
		margin-bottom: 0.5rem;
	}

	.modern-form-card {
		border: none;
		border-radius: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		margin-bottom: 1.5rem;
	}

	.modern-form-card .card-header {
		background: white;
		border-bottom: 1px solid #e9ecef;
		padding: 1.5rem;
	}

	.modern-form-card .card-title {
		font-weight: 600;
		color: #2c3e50;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}

	.modern-form-card .card-body {
		padding: 2rem;
	}

	.form-label {
		font-weight: 500;
		color: #495057;
		margin-bottom: 0.5rem;
		display: flex;
		align-items: center;
		gap: 0.25rem;
	}

	.form-label.required::after {
		content: '*';
		color: #dc3545;
		margin-left: 4px;
	}

	.form-control {
		border-radius: 8px;
		border: 1px solid #dee2e6;
		padding: 0.625rem 0.875rem;
		transition: all 0.2s ease;
	}

	.form-control:focus {
		border-color: #667eea;
		box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
	}

	.form-hint {
		color: #6c757d;
		font-size: 0.875rem;
		margin-top: 0.25rem;
		display: block;
	}

	.form-check-input {
		width: 3rem;
		height: 1.5rem;
		cursor: pointer;
		flex-shrink: 0;
		margin-top: 0.125rem;
	}

	.form-check-input:checked {
		background-color: #667eea;
		border-color: #667eea;
	}

	.form-check-label {
		cursor: pointer;
		font-weight: 500;
		line-height: 1.5rem;
		display: inline-flex;
		align-items: center;
	}

	.form-switch {
		padding-left: 0 !important;
	}

	.form-switch.d-flex {
		align-items: flex-start !important;
	}

	.form-switch .form-check-input {
		margin-left: 0;
		margin-top: 0;
	}

	.btn-modern {
		border-radius: 8px !important;
		padding: 0.625rem 1.5rem !important;
		font-weight: 500 !important;
		transition: all 0.2s ease !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 0.5rem !important;
		border: none !important;
	}

	.btn-modern:hover {
		transform: translateY(-2px) !important;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
	}

	.btn-primary.btn-modern {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
		border: none !important;
		color: white !important;
	}

	.btn-primary.btn-modern:hover {
		background: linear-gradient(135deg, #5568d3 0%, #65408a 100%) !important;
		color: white !important;
	}

	.btn-secondary.btn-modern {
		background: #6c757d;
		border: none;
	}

	.btn-secondary.btn-modern:hover {
		background: #5a6268;
	}

	.btn-info.btn-modern {
		background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
		border: none;
		color: white;
	}

	.btn-info.btn-modern:hover {
		background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
		color: white;
	}

	.preview-banner,
	.preview-image {
		padding: 1rem;
		background: #f8f9fa;
		border-radius: 8px;
		border: 2px dashed #dee2e6;
	}

	.preview-banner img,
	.preview-image img {
		max-height: 200px;
		object-fit: contain;
		display: block;
		margin: 0.5rem auto 0;
		border-radius: 8px;
	}

	.file-upload-wrapper {
		position: relative;
	}

	.file-upload-wrapper input[type="file"] {
		padding: 0.75rem;
	}
</style>

<!-- Page Header -->
<div class="page-header-modern">
	<div class="page-pretitle">
		<i class="{{ $icon }} me-2"></i>{{ $pretitle }}
	</div>
	<h2>
		<i class="{{ $icon }} me-2"></i>{{ $title }}
	</h2>
</div>

<!-- Form Content (slot) -->
{{ $slot }}

<!-- Action Buttons -->
<div class="card modern-form-card">
	<div class="card-body">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
			<a href="{{ $backUrl }}" class="btn btn-secondary btn-modern">
				<i class="ti ti-arrow-left"></i>
				{{ $backLabel }}
			</a>
			<div class="d-flex gap-2 flex-wrap">
				@if ($showViewButton && $viewUrl)
					<a href="{{ $viewUrl }}" class="btn btn-info btn-modern" target="_blank">
						<i class="ti ti-eye"></i>
						{{ $viewLabel }}
					</a>
				@endif
				<button type="submit" class="btn btn-primary btn-modern">
					<i class="ti ti-check"></i>
					{{ $submitLabel }}
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	// Ensure the submit button works by checking form structure
	document.addEventListener('DOMContentLoaded', function() {
		const submitBtns = document.querySelectorAll('button[type="submit"].btn-modern');
		submitBtns.forEach(btn => {
			// Make sure button can submit the nearest form
			btn.addEventListener('click', function(e) {
				const form = this.closest('form') || document.querySelector('form');
				if (form && !this.hasAttribute('formnovalidate')) {
					// Let the form handle validation and submission naturally
				}
			});
		});
	});
</script>
