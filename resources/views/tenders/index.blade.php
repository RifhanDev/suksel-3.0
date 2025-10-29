@extends('layouts.modern')
@section('content')
	<!-- Modern Page Header -->
	<div class="page-body">
		<div class="page-header d-print-none mb-4">
			<div class="container-xl">
				<div class="row g-2 align-items-center">
					<div class="col">
						<div class="page-pretitle text-muted">
							<i class="ti ti-building-store me-1"></i>
							Sistem Tender Online
						</div>
						<h2 class="page-title mt-1">
							<i class="ti ti-file-text me-2"></i>Senarai Tender / Sebutharga
						</h2>
					</div>
					@if (App\Tender::canCreate())
						<div class="col-auto ms-auto">
							<a href="{{ asset('tenders/create') }}" style="color: black;" class="btn btn-primary d-none d-sm-inline-block">
								<i class="ti ti-plus me-1"></i>
								Tambah Tender Baharu
							</a>
							<a href="{{ asset('tenders/create') }}" style="color: black;" class="btn btn-primary d-sm-none btn-icon">
								<i class="ti ti-plus"></i>
							</a>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="page-body">
		<div class="container-xl">
			<div class="row g-3">
				<!-- Main Content -->
				<div class="col-lg-9">
					<!-- Statistics Cards -->
					<div class="row row-cards mb-3">
						<div class="col-sm-6 col-lg-4">
							<div class="card card-sm">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-auto">
											<span class="bg-primary text-white avatar">
												<i class="ti ti-file-text"></i>
											</span>
										</div>
										<div class="col">
											<div class="font-weight-medium">
												Total Tender
											</div>
											<div class="text-muted">
												<span class="h1 mb-0" id="total-tenders">-</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-lg-4">
							<div class="card card-sm">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-auto">
											<span class="bg-success text-white avatar">
												<i class="ti ti-clock"></i>
											</span>
										</div>
										<div class="col">
											<div class="font-weight-medium">
												Tender Aktif
											</div>
											<div class="text-muted">
												<span class="h1 mb-0" id="active-tenders">-</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-lg-4">
							<div class="card card-sm">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-auto">
											<span class="bg-info text-white avatar">
												<i class="ti ti-calendar-event"></i>
											</span>
										</div>
										<div class="col">
											<div class="font-weight-medium">
												Bulan Ini
											</div>
											<div class="text-muted">
												<span class="h1 mb-0" id="month-tenders">-</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Main Table Card -->
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="ti ti-list me-2"></i>Maklumat Tender
							</h3>
							<div class="card-actions">
								<a href="#" class="btn btn-ghost-secondary btn-icon" title="Refresh">
									<i class="ti ti-refresh"></i>
								</a>
							</div>
						</div>
						<div class="card-body border-bottom py-3">
							<div class="d-flex">
								<div class="text-muted">
									Paparan:
									<div class="mx-2 d-inline-block">
										<select class="form-select form-select-sm" id="page-length">
											<option value="10">10</option>
											<option value="25">25</option>
											<option value="50">50</option>
											<option value="100">100</option>
										</select>
									</div>
								</div>
								<div class="ms-auto text-muted">
									Carian:
									<div class="ms-2 d-inline-block">
										<input type="text" class="form-control form-control-sm" id="search-box" placeholder="Cari tender...">
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table data-path="/tenders" class="DT-index table table-vcenter card-table table-striped">
								<thead>
									<tr>
										<th class="w-50">
											<i class="ti ti-file-text me-1"></i>Maklumat Tender
										</th>
										<th class="w-auto">
											<i class="ti ti-calendar me-1"></i>Tarikh Jual
										</th>
										<th class="w-auto">
											<i class="ti ti-calendar-event me-1"></i>Tarikh Tutup
										</th>
										<th class="w-auto">
											<i class="ti ti-currency-ringgit me-1"></i>Harga (RM)
										</th>
										@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
											<th class="w-auto">
												<i class="ti ti-status-change me-1"></i>Status
											</th>
										@endif
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>

				<!-- Sidebar -->
				<div class="col-lg-3">
					<div class="row row-cards">
						<div class="col-12">
							@include('layouts._register')
						</div>
						<div class="col-12">
							@include('layouts._news')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var DT;

			// Initialize DataTable
			$('.DT-index').each(function() {
				var target = $(this);
				var path = target.data('path');

				DT = target.DataTable({
					ajax: path,
					columns: [{
							data: 'name',
							name: 'name'
						},
						{
							data: 'document_start_date',
							name: 'document_start_date'
						},
						{
							data: 'submission_datetime',
							name: 'submission_datetime'
						},
						{
							data: 'price',
							name: 'price'
						},
						{
							data: 'approver_id',
							name: 'approver_id'
						},
					],
					serverSide: true,
					stateSave: true,
					dom: 'rtip', // Remove default search and length controls
					language: {
						sEmptyTable: "Tiada data",
						sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
						sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
						sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
						sInfoPostFix: "",
						sInfoThousands: ",",
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
					aaSorting: [],
					drawCallback: function(settings) {
						// Update statistics after data is loaded
						updateStatistics(settings.json);
					}
				});

				// Custom search box
				$('#search-box').on('keyup', function() {
					DT.search(this.value).draw();
				});

				// Custom page length selector
				$('#page-length').on('change', function() {
					DT.page.len(this.value).draw();
				});

				// Refresh button
				$('.btn-ghost-secondary.btn-icon').on('click', function(e) {
					e.preventDefault();
					DT.ajax.reload();

					// Add spinning animation
					var icon = $(this).find('i');
					icon.addClass('rotating');
					setTimeout(function() {
						icon.removeClass('rotating');
					}, 1000);
				});
			});

			// Function to update statistics
			function updateStatistics(data) {
				if (data && data.recordsTotal !== undefined) {
					$('#total-tenders').text(data.recordsTotal);

					// You can add more logic here to calculate active tenders
					// For now, using filtered count as active
					$('#active-tenders').text(data.recordsFiltered);

					// For month count, you'd need to add this to your server response
					// Using a placeholder for now
					$('#month-tenders').text('-');
				}
			}
		});
	</script>

	<style>
		/* Modern Table Styling */
		.table-striped>tbody>tr:nth-of-type(odd) {
			background-color: rgba(0, 0, 0, 0.02);
		}

		.table-striped>tbody>tr:hover {
			background-color: rgba(0, 0, 0, 0.04);
			transition: background-color 0.2s ease;
		}

		.card-table {
			margin-bottom: 0;
		}

		/* Rotating animation for refresh icon */
		@keyframes rotate {
			from {
				transform: rotate(0deg);
			}

			to {
				transform: rotate(360deg);
			}
		}

		.rotating {
			animation: rotate 1s linear;
		}

		/* Statistics cards hover effect */
		.card-sm:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
			transition: all 0.3s ease;
		}

		/* Avatar icons */
		.avatar {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 2.5rem;
			height: 2.5rem;
			border-radius: 50%;
		}

		/* Button improvements */
		.btn-primary {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			border: none;
			box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
		}

		.btn-primary:hover {
			background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
			box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
			transform: translateY(-1px);
		}

		/* Custom scrollbar for table */
		.table-responsive::-webkit-scrollbar {
			height: 8px;
		}

		.table-responsive::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 10px;
		}

		.table-responsive::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 10px;
		}

		.table-responsive::-webkit-scrollbar-thumb:hover {
			background: #94a3b8;
		}

		/* Page header styling */
		.page-header {
			background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
			border-bottom: 1px solid #e2e8f0;
			padding: 1.5rem 0;
		}

		.page-pretitle {
			font-size: 0.875rem;
			font-weight: 500;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		/* Card enhancements */
		.card {
			border: 1px solid #e2e8f0;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			border-radius: 0.75rem;
		}

		.card-header {
			background-color: #ffffff;
			border-bottom: 1px solid #e2e8f0;
			padding: 1rem 1.5rem;
		}

		/* Form controls */
		.form-select-sm,
		.form-control-sm {
			border-color: #e2e8f0;
		}

		.form-select-sm:focus,
		.form-control-sm:focus {
			border-color: #dc2626;
			box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.1);
		}

		/* Mobile responsiveness */
		@media (max-width: 768px) {
			.card-body.border-bottom .d-flex {
				flex-direction: column;
			}

			.card-body.border-bottom .ms-auto {
				margin-left: 0 !important;
				margin-top: 0.5rem;
			}
		}
	</style>
@endsection
