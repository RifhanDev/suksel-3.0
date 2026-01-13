@extends('layouts.modern')

@section('content')
	@php
		$sidebarHtml =
		    '<div class="row">
			<div class="col-12 mb-3">' .
		    view('layouts._register')->render() .
		    '</div>
			<div class="col-12">' .
		    view('layouts._news')->render() .
		    '</div>
		</div>';

		$columnsConfig = [
		    [
		        'data' => 'submission_datetime',
		        'name' => 'submission_datetime',
		        'label' => 'Tarikh Tutup',
		        'icon' => 'ti-calendar',
		        'width' => 'w-15',
		    ],
		    [
		        'data' => 'organization_unit_id',
		        'name' => 'organization_unit_id',
		        'label' => 'Petender',
		        'icon' => 'ti-building',
		        'width' => 'w-25',
		    ],
		    [
		        'data' => 'name',
		        'name' => 'name',
		        'label' => 'No / Tajuk',
		        'icon' => 'ti-file-text',
		    ],
		];
	@endphp

	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-chart-line me-2"></i>Sistem Tender Online
				</div>
				<h2>
					<i class="ti ti-chart-line me-2"></i>Carta Tender
				</h2>
			</div>

			<!-- Main Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
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
						<table data-path="{{ $path }}" class="DT-index table modern-table table-hover">
							<thead>
								<tr>
									@foreach ($columnsConfig as $col)
										<th class="{{ $col['width'] ?? '' }}">
											@if (isset($col['icon']))
												<i class="ti {{ $col['icon'] }} me-1"></i>
											@endif
											{{ $col['label'] ?? ucfirst($col['name']) }}
										</th>
									@endforeach
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Sidebar -->
		<div class="col-lg-3">
			{!! $sidebarHtml !!}
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
			var columns = [{
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
				}
			];

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
				aaSorting: [],
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
				pageLength: 25,
				responsive: true,
				order: [
					[0, 'desc']
				]
			});
		});
	</script>
@endsection
