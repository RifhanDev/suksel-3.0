@extends('layouts.modern')
@section('content')
	<div class="row">
		<div class="col-lg-9">
			<div class="page-header">
				<div class="page-title">
					<div class="page-pretitle">
						Sistem Tender Online
					</div>
				</div>
			</div>

			<h2 class="page-title">
				<i class="ti ti-file-text me-2"></i>Permintaan Kemaskini
				@if (isset($vendor))
					<span class="text-muted">: {{ $vendor->name }}</span>
				@endif
			</h2>
			<br>

			@if (!isset($vendor))
				@include('vendors._snaps')
			@endif

			<div class="card">
				<div class="card-header">
					<h3 class="card-title mb-0">
						<i class="ti ti-list me-2"></i>Senarai Permintaan Kemaskini
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="{{ $ajax_url }}@if (Request::get('state')) ?state={{ Request::get('state') }} @endif"
							class="DT-index table table-vcenter table-mobile-md">
							<thead>
								<tr>
									@if (!isset($vendor))
										<th>
											<i class="ti ti-building me-1"></i>Syarikat
										</th>
									@endif
									<th>
										<i class="ti ti-edit me-1"></i>Kemaskini
									</th>
									<th class="w-15">
										<i class="ti ti-calendar me-1"></i>Tarikh Permintaan
									</th>
									<th class="w-15">
										<i class="ti ti-calendar-event me-1"></i>Tarikh SSM
									</th>
									<th>
										<i class="ti ti-info-circle me-1"></i>Perkara
									</th>
									<th class="w-10">
										<i class="ti ti-status-change me-1"></i>Status
									</th>
									<th class="w-10">
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

		@if (isset($vendor))
			<div class="col-lg-3">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title mb-0">
							<i class="ti ti-plus me-2"></i>Tindakan
						</h3>
					</div>
					<div class="card-body">
						@if (App\CodeRequest::canCreate())
							@if (App\CodeRequest::canCreateFor($vendor->id, 'mof'))
								<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'mof']) }}"
									class="btn btn-primary w-100 mb-2">
									<i class="ti ti-file me-1"></i>Kemaskini MOF
								</a>
							@endif

							@if (App\CodeRequest::canCreateFor($vendor->id, 'cidb'))
								<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'cidb']) }}"
									class="btn btn-primary w-100 mb-2">
									<i class="ti ti-file me-1"></i>Kemaskini CIDB
								</a>
							@endif

							@if (App\CodeRequest::canCreateFor($vendor->id, 'district'))
								<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'district']) }}"
									class="btn btn-primary w-100 mb-2">
									<i class="ti ti-map-pin me-1"></i>Kemaskini Alamat SSM
								</a>
							@endif

							@if (App\CodeRequest::canCreateFor($vendor->id, 'email'))
								<a href="{{ route('vendor.requests.create', [$vendor->id, 'type' => 'email']) }}"
									class="btn btn-primary w-100 mb-2">
									<i class="ti ti-mail me-1"></i>Kemaskini Alamat Emel
								</a>
							@endif
						@endif

						@if ($vendor->canShow())
							<hr>
							<a href="{{ asset(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors/' . $vendor->id) }}"
								class="btn btn-outline-secondary w-100">
								<i class="ti ti-building me-1"></i>Maklumat Syarikat
							</a>
						@endif
					</div>
				</div>
			</div>
		@endif
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
						data: 'type',
						name: 'type'
					},
					{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'ssm_expiry',
						name: 'ssm_expiry'
					},
					{
						data: 'approval_1_id',
						name: 'approval_1_id'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				];
			} else {
				var columns = [{
						data: 'name',
						name: 'vendors.name'
					},
					{
						data: 'type',
						name: 'type'
					},
					{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'ssm_expiry',
						name: 'ssm_expiry'
					},
					{
						data: 'approval_1_id',
						name: 'approval_1_id'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
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
				aaSorting: []
			});
		});
	</script>
@endsection
