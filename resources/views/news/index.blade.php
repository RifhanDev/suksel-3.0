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
				<i class="ti ti-news me-2"></i>Berita
			</h2>
			<br>

			<div class="card">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h3 class="card-title mb-0">
							<i class="ti ti-list me-2"></i>Senarai Berita
						</h3>
						@if (App\News::canCreate())
							<a href="{{ action('NewsController@create') }}" class="btn btn-primary btn-sm">
								<i class="ti ti-plus me-1"></i>Tambah Berita Baru
							</a>
						@endif
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="DT-news table table-vcenter table-mobile-md" data-path="/news">
							<thead>
								<tr>
									<th class="w-15">
										<i class="ti ti-calendar me-1"></i>Tarikh
									</th>
									<th class="w-20">
										<i class="ti ti-building me-1"></i>Agensi
									</th>
									<th>
										<i class="ti ti-news me-1"></i>Berita
									</th>
									<th class="w-10">
										<i class="ti ti-settings me-1"></i>Tindakan
									</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
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
		$('.DT-news').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'organization_unit_id',
						name: 'organization_unit_id'
					},
					{
						data: 'title',
						name: 'title'
					},
					{
						data: 'actions',
						name: 'actions'
					}
				],
				stateSave: true,
				serverSide: true,
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
			DT.search('').columns().search('').draw();
		});
	</script>
@endsection
