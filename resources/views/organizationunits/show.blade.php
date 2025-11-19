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
				<i class="ti ti-building me-2"></i>{{ $organizationunit->name }}
			</h2>
			<br>

			@if (Auth::check())
				<div class="btn-group mb-3">
					@if (Auth()->user()->hasRole('Admin'))
						<a href="{{ asset('agencies/' . $organizationunit->id . '/edit') }}" class="btn btn-warning btn-sm">
							<i class="ti ti-edit me-1"></i>Kemaskini Agensi
						</a>
					@endif
					@if (Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
						<a href="{{ asset('tenders/create') }}" class="btn btn-primary btn-sm">
							<i class="ti ti-plus me-1"></i>Tambah Tender / Sebut Harga
						</a>
					@endif
				</div>
			@endif

			@if (Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
				<div class="row mb-4">
					<div class="col-lg-4">
						<div class="card @if (Request::get('state') == 1) border-success @else border-warning @endif">
							<div class="card-body text-center">
								<div class="h1 mb-2 @if (Request::get('state') == 1) text-success @else text-warning @endif">
									{{ $count_1 }}
								</div>
								<div class="text-muted">
									tender / sebut harga belum disiarkan
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="card @if (Request::get('state') == 2) border-success @else border-warning @endif">
							<div class="card-body text-center">
								<div class="h1 mb-2 @if (Request::get('state') == 2) text-success @else text-warning @endif">
									{{ $count_2 }}
								</div>
								<div class="text-muted">
									tender / sebut harga belum di umumkan carta tender
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="card @if (Request::get('state') == 3) border-success @else border-warning @endif">
							<div class="card-body text-center">
								<div class="h1 mb-2 @if (Request::get('state') == 3) text-success @else text-warning @endif">
									{{ $count_3 }}
								</div>
								<div class="text-muted">
									tender / sebut harga belum diumumkan penender berjaya
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif

			<div class="card">
				<div class="card-header">
					<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
						<li class="nav-item">
							<a href="{{ action('OrganizationUnitsController@show', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agencies/' . $organizationunit->id) && !request()->get('type')) active @endif">
								<i class="ti ti-file-text me-2"></i>Tender & Sebut Harga
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('OrganizationUnitsController@prices', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agencies/' . $organizationunit->id . '/prices')) active @endif">
								<i class="ti ti-chart-line me-2"></i>Carta Tender
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('OrganizationUnitsController@results', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agencies/' . $organizationunit->id . '/results')) active @endif">
								<i class="ti ti-trophy me-2"></i>Penender Berjaya
							</a>
						</li>
						<li class="nav-item ms-auto">
							<a href="{{ action('OrganizationUnitsController@news', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agencies/' . $organizationunit->id . '/news')) active @endif">
								<i class="ti ti-news me-2"></i>Berita
							</a>
						</li>
					</ul>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-2">
							<div class="nav flex-column nav-pills" role="tablist">
								<a href="{{ action('OrganizationUnitsController@show', $organizationunit->id) }}"
									class="nav-link @if (!Request::get('type')) active @endif" role="tab">
									<i class="ti ti-list me-2"></i>Semua
								</a>
								<a href="{{ action('OrganizationUnitsController@show', [$organizationunit->id, 'type' => 'tenders']) }}"
									class="nav-link @if (Request::get('type') == 'tenders') active @endif" role="tab">
									<i class="ti ti-file-text me-2"></i>Tender
								</a>
								<a href="{{ action('OrganizationUnitsController@show', [$organizationunit->id, 'type' => 'quotations']) }}"
									class="nav-link @if (Request::get('type') == 'quotations') active @endif" role="tab">
									<i class="ti ti-calculator me-2"></i>Sebut Harga
								</a>
							</div>
						</div>

						<div class="col-md-10">
							<div class="table-responsive">
								<table class="DT-show table table-vcenter table-mobile-md" data-path="{{ $path }}">
									<thead>
										<tr>
											<th>
												<i class="ti ti-file-text me-1"></i>No / Tajuk
											</th>
											<th class="w-15">
												<i class="ti ti-code me-1"></i>Kod Bidang
											</th>
											<th class="w-15">
												<i class="ti ti-calendar me-1"></i>Tarikh Jual
											</th>
											<th class="w-15">
												<i class="ti ti-calendar me-1"></i>Tarikh Tutup
											</th>
											<th class="w-15">
												<i class="ti ti-currency-ringgit me-1"></i>Harga Dokumen
											</th>
											@if (Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
												<th class="w-10">
													<i class="ti ti-status-change me-1"></i>Status
												</th>
											@endif
											@if (Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
												<th class="w-10">
													<i class="ti ti-calendar me-1"></i>Jadual
												</th>
												<th class="w-10">
													<i class="ti ti-settings me-1"></i>Tindakan
												</th>
											@endif
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
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
	<script>
		var canUpdate = {!! json_encode(Auth::check() && App\Tender::canShowUpdate($organizationunit->id)) !!};

		$('.DT-show').each(function() {
			var target = $(this);
			var path = target.data('path');

			if (canUpdate) {
				var columns = [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'codes',
						name: 'codes'
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
						data: 'actions',
						name: 'actions'
					},
					{
						data: 'report',
						name: 'report'
					},
				];
			} else {
				var columns = [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'codes',
						name: 'codes'
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
