@extends('layouts.modern')

@section('styles')
<style>
/* Modern Form Tabs Styling */
.modern-form-wrapper {
	background: #f8f9fa;
	padding: 2rem 0;
}

.modern-form-card {
	background: white;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.08);
	overflow: hidden;
	margin: 0 auto;
	max-width: 1400px;
}

.modern-tabs-header {
	background: white;
	border-bottom: 2px solid #e9ecef;
	padding: 0;
	margin: 0;
}

.modern-nav-tabs {
	display: flex;
	flex-wrap: wrap;
	list-style: none;
	padding: 1rem 1.5rem 0 1.5rem;
	margin: 0;
	gap: 0.5rem;
}

.modern-nav-tabs li {
	margin-bottom: -2px;
}

.modern-nav-tabs li a {
	display: inline-block;
	padding: 0.75rem 1.5rem;
	color: #6c757d;
	text-decoration: none;
	border-radius: 8px 8px 0 0;
	font-weight: 500;
	font-size: 0.95rem;
	transition: all 0.3s ease;
	border: 2px solid transparent;
	border-bottom: none;
	background: transparent;
	position: relative;
}

.modern-nav-tabs li a:hover {
	color: #dc2626;
	background: #fef2f2;
}

.modern-nav-tabs li.active a {
	color: #dc2626;
	background: white;
	border-color: #e9ecef;
	border-bottom-color: white;
}

.modern-nav-tabs li.active a::after {
	content: '';
	position: absolute;
	bottom: -2px;
	left: 0;
	right: 0;
	height: 3px;
	background: #dc2626;
}

.modern-nav-tabs li.disabled a {
	color: #adb5bd;
	cursor: not-allowed;
	opacity: 0.6;
}

.modern-nav-tabs li.disabled a:hover {
	background: transparent;
	color: #adb5bd;
}

.modern-tab-content {
	padding: 2.5rem;
	background: white;
}

.modern-tab-content .tab-pane {
	animation: fadeIn 0.4s ease-in;
}

@keyframes fadeIn {
	from { opacity: 0; transform: translateY(10px); }
	to { opacity: 1; transform: translateY(0); }
}

/* Form styling improvements */
.modern-tab-content .form-group {
	margin-bottom: 1.5rem;
}

.modern-tab-content .control-label {
	font-weight: 500;
	color: #495057;
}

.modern-tab-content .control-label sup {
	color: #dc2626;
}

/* Table styling */
.modern-tab-content .table thead {
	background: #f8f9fa;
}

.modern-tab-content .table thead th {
	border-bottom: 2px solid #dee2e6;
	font-weight: 600;
	padding: 1rem;
}

.modern-tab-content .table tbody tr:hover {
	background: #f8f9fa;
}

/* Button improvements */
.modern-tab-content .btn {
	border-radius: 6px;
	font-weight: 500;
	transition: all 0.2s ease;
}

.modern-tab-content .btn-primary {
	background: #dc2626;
	border-color: #dc2626;
}

.modern-tab-content .btn-primary:hover {
	background: #b91c1c;
	border-color: #b91c1c;
	transform: translateY(-1px);
	box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
}

/* Form action buttons */
.modern-form-actions {
	background: #f8f9fa;
	padding: 1.5rem 2.5rem;
	border-top: 2px solid #e9ecef;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.modern-form-actions .btn {
	padding: 0.6rem 1.5rem;
	font-size: 1rem;
}

/* Fix missing remove icon for file inputs - use inline SVG */
.file_input .close {
	background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="%23dc2626" d="M2 2l12 12m0-12L2 14" stroke="%23dc2626" stroke-width="2"/></svg>') !important;
	background-repeat: no-repeat !important;
	background-position: center !important;
	text-indent: -9999px !important;
	width: 20px !important;
	height: 20px !important;
	opacity: 0.8 !important;
}

.file_input .close:hover {
	opacity: 1 !important;
	transform: scale(1.1);
}

/* Responsive design */
@media (max-width: 768px) {
	.modern-form-wrapper { padding: 1rem 0; }
	.modern-form-card { border-radius: 0; }
	.modern-nav-tabs {
		flex-direction: column;
		gap: 0.25rem;
		padding: 1rem;
	}
	.modern-nav-tabs li { width: 100%; margin-bottom: 0; }
	.modern-nav-tabs li a {
		border-radius: 6px;
		width: 100%;
		text-align: center;
	}
	.modern-nav-tabs li.active a {
		border: 2px solid #dc2626;
	}
	.modern-nav-tabs li.active a::after {
		display: none;
	}
	.modern-tab-content { padding: 1rem; }
	.modern-form-actions {
		flex-direction: column;
		gap: 0.75rem;
		padding: 1rem;
	}
	.modern-form-actions .btn {
		width: 100%;
	}
}
</style>
@endsection

@section('content')
	<div class="modern-form-wrapper">
		<div class="modern-form-card">
			<div class="modern-tabs-header" style="padding: 1.5rem 2.5rem 0.5rem;">
				<h2 style="margin: 0 0 1rem 0; color: #1e293b; font-weight: 600;">Pendaftaran Syarikat</h2>
			</div>

			{!! Former::open_for_files(action('RegistrationController@storeCompany'))->addClass('form-uppercase jq-validate') !!}
				{!! Former::populate($vendor) !!}
				{!! Former::hidden('_method', 'PUT') !!}
				@include('vendors.form')

				<div class="modern-form-actions">
					<div>
						<a href="{{ asset('dashboard')}}" class="btn btn-default">
							<i class="ti ti-arrow-left"></i> Kembali ke Dashboard
						</a>
					</div>
					<div style="display: flex; gap: 0.75rem;">
						<button type="button" id="next" class="btn btn-primary">
							Seterusnya <i class="ti ti-arrow-right"></i>
						</button>
						<button type="button" id="submit" class="btn btn-success" style="display: none;">
							<i class="ti ti-check"></i> Hantar
						</button>
					</div>
				</div>
			{!! Former::close() !!}
		</div>
	</div>
@endsection