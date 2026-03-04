@extends('layouts.v3.master')

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-message-circle me-2"></i>Sistem Tender Online
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-message-circle me-2"></i>Aduan Saya
					</h2>
					<a href="{{ route('aduan.create') }}" class="btn btn-primary btn-modern">
						<i class="ti ti-plus me-1"></i>Hantar Aduan Baru
					</a>
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
						<i class="ti ti-list me-2"></i>Senarai Aduan Saya
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="/my-aduan" class="DT-index table modern-table table-hover">
							<thead>
								<tr>
									<th><i class="ti ti-file-text me-1"></i>Subjek</th>
									<th class="text-nowrap" style="width: 140px;"><i class="ti ti-category me-1"></i>Isu utama / Modul</th>
									<th class="text-nowrap" style="width: 180px;"><i class="ti ti-file-text me-1"></i>Tender</th>
									<th><i class="ti ti-notes me-1"></i>Kandungan</th>
									<th class="text-nowrap text-center" style="width: 120px;"><i class="ti ti-status-change me-1"></i>Status</th>
									<th class="text-nowrap text-center" style="width: 130px;"><i class="ti ti-calendar me-1"></i>Tarikh Aduan</th>
									<th class="text-nowrap" style="width: 120px;"><i class="ti ti-settings me-1"></i>Tindakan</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Sidebar -->
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
							data: 'subject',
							name: 'subject'
						},
						{
							data: 'module',
							name: 'module'
						},
						{
							data: 'tender_id',
							name: 'tender_id'
						},
						{
							data: 'content',
							name: 'content'
						},
						{
							data: 'status',
							name: 'status'
						},
						{
							data: 'created_at',
							name: 'created_at'
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
						},
						oAria: {
							sSortAscending: ": diaktifkan kepada susunan lajur menaik",
							sSortDescending: ": diaktifkan kepada susunan lajur menurun"
						}
					},
					dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
					pageLength: 25,
					responsive: true,
					order: [
						[5, 'desc']
					]
				});
			});
		});
	</script>
@endsection
