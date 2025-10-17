@extends('layouts.default')

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						<div class="page-title">
							<div class="page-pretitle">
								Sistem Tender Online
							</div>
							<h2 class="page-title">
								<i class="ti ti-chart-line me-2"></i>Carta Tender
							</h2>
						</div>
					</div>

					<div class="card">
						<div class="card-header">
							<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
								<li class="nav-item">
									<a href="{{ asset('prices') }}" class="nav-link @if (!Request::get('type')) active @endif">
										<i class="ti ti-list me-2"></i>Semua
									</a>
								</li>
								<li class="nav-item">
									<a href="{{ action('HomeController@prices', ['type' => 'tenders']) }}"
										class="nav-link @if (Request::get('type') == 'tenders') active @endif">
										<i class="ti ti-file-text me-2"></i>Tender
									</a>
								</li>
								<li class="nav-item">
									<a href="{{ action('HomeController@prices', ['type' => 'quotations']) }}"
										class="nav-link @if (Request::get('type') == 'quotations') active @endif">
										<i class="ti ti-calculator me-2"></i>Sebut Harga
									</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="DT-index table table-vcenter table-mobile-md" data-path="{{ $path }}">
									<thead>
										<tr>
											<th class="w-1">
												<i class="ti ti-calendar me-1"></i>Tarikh Tutup
											</th>
											<th class="w-25">
												<i class="ti ti-building me-1"></i>Petender
											</th>
											<th>
												<i class="ti ti-file-text me-1"></i>No / Tajuk
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
			<script src="{{ asset('js/news.js') }}"></script>

			<script type="text/javascript">
				$('.DT-index').each(function() {
					var target = $(this);
					var path = target.data('path');
					var DT = target.DataTable({
						ajax: path,
						columns: [{
								data: 'submission_datetime',
								name: 'submission_datetime'
							},
							{
								data: 'organization_unit_id',
								name: 'organization_unit_id'
							},
							{
								data: 'name',
								name: 'name'
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
