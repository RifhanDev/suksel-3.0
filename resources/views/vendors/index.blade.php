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
				<i class="ti ti-building me-2"></i>Syarikat {{ isset($subtitle) ? ': ' . $subtitle : '' }}
			</h2>
			<br>

			@include('vendors._snaps')

			<div class="card">
				<div class="card-header">
					<h3 class="card-title mb-0">
						<i class="ti ti-list me-2"></i>Senarai Syarikat
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table
							data-path="{{ action('VendorsController@index') }}{{ isset($approval_status) ? '?state=' . $approval_status : '' }}"
							class="DT-index table table-vcenter table-mobile-md">
							<thead>
								<tr>
									<th class="w-5">
										<i class="ti ti-hash me-1"></i>Bil.
									</th>
									<th class="w-20">
										<i class="ti ti-id me-1"></i>No. Pendaftaran
									</th>
									<th>
										<i class="ti ti-building me-1"></i>Nama
									</th>
									<th class="w-25">
										<i class="ti ti-mail me-1"></i>Alamat Emel
									</th>
									<th class="w-15">
										<i class="ti ti-calendar me-1"></i>
										@if (isset($approval_status))
											Tarikh Didaftarkan
										@else
											Tarikh Diluluskan
										@endif
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
			<br>
			@include('vendors.actions-footer', ['is_list' => true])
		</div>

		<div class="col-lg-3">
			<div class="row">
				<div class="col-12">
					@include('layouts._register')
				</div>
				<div class="col-12">
					@include('layouts._news')
				</div>
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
						data: 'id',
						name: null
					},
					{
						data: 'registration',
						name: 'vendors.registration'
					},
					{
						data: 'name',
						name: 'name'
					},
					{
						data: 'email',
						name: 'users.email'
					},
					{
						data: 'approval_date',
						name: 'approval_date'
					},
					{
						data: 'completed',
						name: 'completed'
					},
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				],
				// processing: true,
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
				// columnDefs: [{
				// "searchable": false,
				// "orderable": false,
				// "targets": 0
				// }, {
				// "targets": 1,
				// "name" : "vendors.registration"
				// }],
				// "order": [[1, 'asc']],
				fnDrawCallback: function(oSettings) {
					start = oSettings.oAjaxData.start + 1;
					DT.column(0).nodes().to$().each(function(index) {
						$(this).text(start + index);
					});
				}
			});
		});
	</script>
@endsection
