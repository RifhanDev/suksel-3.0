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
				<i class="ti ti-file-text me-2"></i>Senarai Tender / Sebutharga
			</h2>
			<br>

			<div class="card">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h3 class="card-title mb-0">
							<i class="ti ti-list me-2"></i>Maklumat Tender
						</h3>
						@if (App\Tender::canCreate())
							<a href="{{ asset('tenders/create') }}" class="btn btn-primary btn-sm">
								<i class="ti ti-plus me-1"></i>Tambah Tender / Sebutharga
							</a>
						@endif
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="/tenders" class="DT-index table table-vcenter table-mobile-md">
							<thead>
								<tr>
									<th>
										<i class="ti ti-file-text me-1"></i>Maklumat Tender
									</th>
									<th class="w-15">
										<i class="ti ti-calendar me-1"></i>Tarikh Jual
									</th>
									<th class="w-15">
										<i class="ti ti-calendar me-1"></i>Tarikh Tutup
									</th>
									<th class="w-15">
										<i class="ti ti-currency-ringgit me-1"></i>Harga Dokumen (RM)
									</th>
									@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
										<th class="w-10">
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
