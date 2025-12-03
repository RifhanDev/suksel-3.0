@extends('layouts.modern')
@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<div class="page-title">
					<div class="page-pretitle">
						Sistem Tender Online
					</div>
				</div>
			</div>

			<h2 class="page-title">
				<i class="ti ti-arrow-back-up me-2"></i>Pemulangan Semula
				{{ isset($subtitle) ? ': ' . $subtitle : ': Selesai Pemulangan Semula' }}
			</h2>
			<br>

			@include('refunds.request._snaps')

			<div class="card">
				<div class="card-header">
					<h3 class="card-title mb-0">
						<i class="ti ti-list me-2"></i>Senarai Pemulangan Semula
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="{{ action('RefundController@index_request') }}{{ isset($status) ? '?state=' . $status : '' }}"
							class="DT-index table table-vcenter table-mobile-md">
							<thead>
								<tr>
									<th class="w-5">
										<i class="ti ti-hash me-1"></i>Bil.
									</th>
									<th class="w-15">
										<i class="ti ti-id me-1"></i>No. Rujukan
									</th>
									<th class="w-20">
										<i class="ti ti-calendar me-1"></i>Tarikh Permohonan
									</th>
									<th class="w-20">
										<i class="ti ti-calendar-check me-1"></i>
										{{ isset($status) ? $date_col : 'Tarikh Terima Bukti' }}
									</th>
									<th class="w-15">
										<i class="ti ti-status-change me-1"></i>Status
									</th>
									<th class="w-15">
										<i class="ti ti-currency-ringgit me-1"></i>Amaun
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
						data: 'number',
						name: 'number'
					},
					{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'updated_at',
						name: 'updated_at'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'amount',
						name: 'amount'
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
