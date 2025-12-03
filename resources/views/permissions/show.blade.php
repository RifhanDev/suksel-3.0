@extends('layouts.modern')

@section('styles')
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

		.btn-danger.btn-modern {
			background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
			border: none;
			color: white;
		}

		.btn-danger.btn-modern:hover {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			color: white;
		}
	</style>
@endsection

@section('content')
	@php
		$showEditButton = $permission->canUpdate();
		$showDeleteButton = $permission->canDelete();
	@endphp

	<!-- Page Header -->
	<div class="page-header-modern">
		<div class="page-pretitle">
			<i class="ti ti-key me-2"></i>Sistem Tender Online
		</div>
		<h2>
			<i class="ti ti-eye me-2"></i>Paparan Kebenaran
		</h2>
	</div>

	{!! Former::open() !!}
	{!! Former::populate($permission) !!}
	@include('permissions.form')

	<!-- Action Buttons -->
	<div class="card modern-form-card">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
				<a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-modern">
					<i class="ti ti-arrow-left"></i>
					Kembali ke Senarai
				</a>
				<div class="d-flex gap-2 flex-wrap">
					@if ($showEditButton)
						<a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info btn-modern">
							<i class="ti ti-pencil"></i>
							Kemaskini
						</a>
					@endif
					@if ($showDeleteButton)
						{!! Former::open(url('permissions/' . $permission->id))->class('form-inline d-inline') !!}
						{!! Former::hidden('_method', 'DELETE') !!}
						<button type="button" class="btn btn-danger btn-modern confirm-delete">
							<i class="ti ti-trash"></i>
							Padam
						</button>
						{!! Former::close() !!}
					@endif
				</div>
			</div>
		</div>
	</div>
	{!! Former::close() !!}
@endsection

@section('scripts')
	<script src="{{ asset('js/show.js') }}"></script>
@endsection
