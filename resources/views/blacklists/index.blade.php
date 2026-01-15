@extends('layouts.modern')
@section('content')

	<!-- Page Header -->
	<div class="page-header-modern">
		<div class="page-pretitle">
			<i class="ti ti-ban me-2"></i>Sistem Tender Online
		</div>
		<h2>
			<i class="ti ti-ban me-2"></i>
			@if (isset($vendor))
				{{ $vendor->name }}
			@else
				Syarikat
			@endif : Senarai Hitam
		</h2>
	</div>

	<!-- Main Card -->
	<div class="card modern-card">
		<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
			<div class="d-flex justify-content-between align-items-center">
				<h3 class="card-title-modern mb-0">
					<i class="ti ti-list"></i>
					Senarai Hitam
				</h3>
				@if (isset($vendor))
					<div class="d-flex gap-2">
						@if (App\VendorBlacklist::canCreate())
							<a href="{{ route('vendor.blacklists.create', $vendor->id) }}" class="btn btn-primary btn-modern">
								<i class="ti ti-plus me-1"></i>Masukkan Senarai Hitam Baru
							</a>
						@endif
						@if ($vendor->canShow())
							<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}"
								class="btn btn-outline-secondary btn-modern">
								<i class="ti ti-building me-1"></i>Maklumat Syarikat
							</a>
						@endif
					</div>
				@endif
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table data-path="{{ $ajax_url }}" class="DT-index table modern-table table-hover">
					<thead>
						<tr>
							@if (!isset($vendor))
								<th>
									<i class="ti ti-building me-1"></i>Syarikat
								</th>
							@endif
							<th>
								<i class="ti ti-building-community me-1"></i>Agensi
							</th>
							<th>
								<i class="ti ti-file-text me-1"></i>Sebab
							</th>
							<th>
								<i class="ti ti-calendar me-1"></i>Tarikh Mula
							</th>
							<th>
								<i class="ti ti-calendar-event me-1"></i>Tarikh Tamat
							</th>
							<th>
								<i class="ti ti-info-circle me-1"></i>Status
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

			if (path.includes('/vendor')) {
				var columns = [{
						data: 'organization_unit_id',
						name: 'organization_unit_id'
					},
					{
						data: 'reason',
						name: 'reason'
					},
					{
						data: 'start',
						name: 'start'
					},
					{
						data: 'end',
						name: 'end'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'actions',
						name: 'actions'
					},
				];
			} else {

				var columns = [{
						data: 'vendor_id',
						name: 'vendor_id'
					},
					{
						data: 'organization_unit_id',
						name: 'organization_unit_id'
					},
					{
						data: 'reason',
						name: 'reason'
					},
					{
						data: 'start',
						name: 'start'
					},
					{
						data: 'end',
						name: 'end'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'actions',
						name: 'actions'
					},
				];
			}

			var DT = target.DataTable({
				ajax: path,
				columns: columns,
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
					[path.includes('/vendor') ? 2 : 3, 'desc']
				]
			});
		});
	</script>
@endsection
