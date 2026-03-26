@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">
				Aduan Pemulangan Semula{{ isset($subtitle) ? ': ' . $subtitle : '' }}
			</h3>
			<p class="text-muted small m-0">Pengurusan dan semakan aduan permohonan pemulangan semula pembayaran.</p>
		</div>
	</div>

	<!-- Stats Cards -->
	@include('refunds.complaint._snaps')

	<!-- Table -->
	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-linecap="round" stroke-linejoin="round">
						<line x1="8" y1="6" x2="21" y2="6"></line>
						<line x1="8" y1="12" x2="21" y2="12"></line>
						<line x1="8" y1="18" x2="21" y2="18"></line>
						<line x1="3" y1="6" x2="3.01" y2="6"></line>
						<line x1="3" y1="12" x2="3.01" y2="12"></line>
						<line x1="3" y1="18" x2="3.01" y2="18"></line>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Aduan Pemulangan Semula</h3>
			</div>
		</div>

		<!-- Table Body -->
		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table data-path="{{ action('RefundController@index_complaint') }}{{ isset($status) ? '?state=' . $status : '' }}"
					class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="w-5 text-uppercase text-muted small fw-bold py-3 ps-4">Bil.</th>
							<th class="w-15 text-uppercase text-muted small fw-bold py-3">No. Rujukan</th>
							<th class="w-20 text-uppercase text-muted small fw-bold py-3">Tarikh Permohonan</th>
							<th class="w-20 text-uppercase text-muted small fw-bold py-3">
								{{ isset($status) ? $date_col : 'Tarikh Terima Bukti' }}
							</th>
							<th class="w-15 text-uppercase text-muted small fw-bold py-3">Status</th>
							<th class="w-15 text-uppercase text-muted small fw-bold py-3">Amaun</th>
							<th class="w-10 text-uppercase text-muted small fw-bold py-3 pe-4">Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
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