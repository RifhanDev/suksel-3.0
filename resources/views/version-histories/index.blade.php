@extends('layouts.modern')

@section('styles')
	<style>
		.version-history-actions {
			min-width: 120px;
		}

		.version-history-actions form {
			display: inline;
			margin: 0;
		}

		.version-history-actions .btn-action {
			flex-shrink: 0;
		}

		.version-history-actions .btn-action svg {
			color: white !important;
			stroke: white !important;
			flex-shrink: 0;
		}
	</style>
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-history me-2"></i>Sistem Tender Online Selangor
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-versions me-2"></i>Urus Sejarah Perubahan Sistem
					</h2>
					<div class="d-flex gap-2">
						<a href="{{ url('version-histories') }}" class="btn btn-outline-secondary btn-modern" target="_blank">
							<i class="ti ti-eye me-1"></i>Lihat Halaman Awam
						</a>
						<a href="{{ route('version-histories.create') }}" class="btn btn-primary btn-modern">
							<i class="ti ti-plus me-1"></i>Tambah Versi
						</a>
					</div>
				</div>
			</div>

			@if (session('success'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<i class="ti ti-check me-2"></i>{{ session('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif

			<!-- Main Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 1.5rem;">
					<h3 class="card-title-modern mb-0">
						<i class="ti ti-list me-2"></i>Senarai Rekod Versi
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="{{ route('version-histories.index') }}" class="DT-index table modern-table table-hover">
							<thead>
								<tr>
									<th class="text-nowrap" style="width: 100px;">
										<i class="ti ti-tag me-1"></i>Versi
									</th>
									<th class="text-nowrap" style="width: 130px;">
										<i class="ti ti-calendar me-1"></i>Tarikh
									</th>
									<th><i class="ti ti-notes me-1"></i>Nota</th>
									<th class="text-nowrap" style="width: 280px;">
										<i class="ti ti-settings me-1"></i>Tindakan
									</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script src="{{ asset('js/news.js') }}"></script>
	<script>
		$(function() {
			$('.DT-index').each(function() {
				var target = $(this);
				var path = target.data('path');
				var DT = target.DataTable({
					ajax: path,
					columns: [{
							data: 'version',
							name: 'version'
						},
						{
							data: 'released_at',
							name: 'released_at'
						},
						{
							data: 'notes',
							name: 'notes'
						},
						{
							data: 'actions',
							name: 'actions',
							orderable: false,
							searchable: false
						}
					],
					serverSide: true,
					stateSave: true,
					drawCallback: function() {
						$('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
					},
					language: {
						sEmptyTable: "Tiada data",
						sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
						sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
						sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
						sLengthMenu: "Papar _MENU_ rekod",
						sLoadingRecords: "Diproses...",
						sProcessing: "Sedang diproses...",
						sSearch: "Carian:",
						sZeroRecords: "Tiada padanan rekod yang dijumpai.",
						oPaginate: {
							sFirst: "Pertama",
							sPrevious: "Sebelum",
							sNext: "Kemudian",
							sLast: "Akhir"
						}
					},
					order: [
						[1, 'desc']
					],
					pageLength: 25,
					responsive: true
				});
			});
		});
	</script>
@endsection
