@extends('layouts.modern')
@section('content')
	<!-- Page Header -->
	<div class="page-header-modern">
		<div class="page-pretitle">
			<i class="ti ti-building-community me-2"></i>Sistem Tender Online
		</div>
		<h2>
			<i class="ti ti-building-community me-2"></i>Kategori Agensi
		</h2>
	</div>

	<!-- Main Card -->
	<div class="card modern-card">
		<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 1.5rem;">
			<div class="d-flex justify-content-between align-items-center w-100">
				<h3 class="card-title-modern mb-0">
					<i class="ti ti-list me-2"></i>Senarai Kategori Agensi
				</h3>
				@if (App\OrganizationType::canCreate())
					<a href="{{ route('organizationtypes.create') }}" class="btn btn-primary btn-modern ms-auto">
						<i class="ti ti-plus me-1"></i>Kategori Baru
					</a>
				@endif
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table data-path="/organizationtypes" class="DT-index table modern-table table-hover">
					<thead>
						<tr>
							<th>
								<i class="ti ti-tag me-1"></i>Nama
							</th>
							<th>
								<i class="ti ti-sort-ascending me-1"></i>Susunan
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
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
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
						data: 'sort_no',
						name: 'sort_no'
					},
					{
						data: 'actions',
						name: 'actions'
					},
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
					[1, 'asc']
				]
			});
		});
	</script>
@endsection
