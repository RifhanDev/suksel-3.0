@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			{{-- <div class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Sistem Tender Online</div> --}}
			<h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg> --}}
				Syarikat {{ isset($subtitle) ? ': ' . $subtitle : '' }}
			</h2>
		</div>
	</div>

	<!-- Stats Cards -->
	@include('vendors._snaps')

	<!-- Table -->
	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                </div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Syarikat</h3>
			</div>
		</div>

        <!-- Table Body -->
		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table
					data-path="{{ action('VendorsController@index') }}{{ isset($approval_status) ? '?state=' . $approval_status : '' }}"
					class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="w-5 text-uppercase text-muted small fw-bold py-3 ps-4">Bil.</th>
							<th class="w-20 text-uppercase text-muted small fw-bold py-3">No. Pendaftaran</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Nama</th>
							<th class="w-25 text-uppercase text-muted small fw-bold py-3">Alamat Emel</th>
							<th class="w-15 text-uppercase text-muted small fw-bold py-3">
								@if (isset($approval_status))
									Tarikh Didaftarkan
								@else
									Tarikh Diluluskan
								@endif
							</th>
							<th class="w-10 text-uppercase text-muted small fw-bold py-3">Status</th>
							<th class="w-10 text-uppercase text-muted small fw-bold py-3 pe-4">Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
	
	{{-- nanti nk mintak abg wan check kenapa tak boleh msok page --}}
	{{-- <div class="mt-4">
		@include('vendors.actions-footer', ['is_list' => true])
	</div> --}}
@endsection

@section('scripts')
	{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
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