@extends('layouts.modern')
@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-category me-2"></i>Sistem Tender Online
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-category me-2"></i>Senarai Kategori Soalan Lazim
					</h2>
					@if (Auth::user() && Auth::user()->hasRole('Admin'))
						<div class="d-flex gap-2">
							<a href="{{ asset('helps') }}" class="btn btn-warning btn-modern">
								<i class="ti ti-help me-1"></i>Soalan Lazim
							</a>
							<a href="{{ route('helpcategories.create') }}" class="btn btn-primary btn-modern">
								<i class="ti ti-plus me-1"></i>Tambah Kategori
							</a>
						</div>
					@endif
				</div>
			</div>

			<!-- Main Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 1.5rem;">
					<h3 class="card-title-modern mb-0">
						<i class="ti ti-list me-2"></i>Kategori Soalan Lazim
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="/helpcategories" class="DT-index table modern-table table-hover">
							<thead>
								<tr>
									<th>
										<i class="ti ti-tag me-1"></i>Nama
									</th>
									<th class="col-lg-3">
										<i class="ti ti-file-text me-1"></i>Jumlah Soalan
									</th>
									<th width="200px">
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
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'count',
						name: 'count'
					},
					{
						data: 'actions',
						name: 'actions'
					}
				],
				serverSide: true,
				stateSave: true,
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
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
				pageLength: 25,
				responsive: true,
				order: [
					[0, 'asc']
				]
			});
		});
	</script>
@endsection
