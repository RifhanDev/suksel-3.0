@extends('layouts.v3.master')
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
					@if (Auth::user()->hasRole('Admin'))
						<a href="{{ asset('agency/' . $organizationunit->id . '/edit') }}" class="btn btn-warning btn-sm">
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

			<div class="card">
				<div class="card-header">
					<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
						<li class="nav-item">
							<a href="{{ action('OrganizationUnitsController@agency', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agency/' . $organizationunit->id) && !request()->get('type')) active @endif">
								<i class="ti ti-file-text me-2"></i>Tender & Sebut Harga
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('OrganizationUnitsController@agencyPrices', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agency/' . $organizationunit->id . '/prices')) active @endif">
								<i class="ti ti-chart-line me-2"></i>Carta Tender
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ action('OrganizationUnitsController@agencyResults', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agency/' . $organizationunit->id . '/results')) active @endif">
								<i class="ti ti-trophy me-2"></i>Penender Berjaya
							</a>
						</li>
						<li class="nav-item ms-auto">
							<a href="{{ action('OrganizationUnitsController@agencyNews', $organizationunit->id) }}"
								class="nav-link @if (request()->is('agency/' . $organizationunit->id . '/news')) active @endif">
								<i class="ti ti-news me-2"></i>Berita
							</a>
						</li>
					</ul>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="DT-news table table-vcenter table-mobile-md" data-path="/agency/{{ $organizationunit->id }}/news">
							<thead>
								<tr>
									<th class="w-15">
										<i class="ti ti-calendar me-1"></i>Tarikh
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
						data: 'title',
						name: 'title'
					},
					{
						data: 'actions',
						name: 'actions'
					}
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
