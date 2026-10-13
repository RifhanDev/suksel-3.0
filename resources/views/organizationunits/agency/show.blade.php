@extends('layouts.v3.master')

@php
	// Dikira sekali dan digunakan semula oleh pengepala jadual dan konfigurasi
	// kolum DataTable di bawah — kedua-duanya mesti sepadan susunannya.
	$isInternal = Auth::check() && App\Tender::canViewInternal($organizationunit->id);
	$canUpdate  = Auth::check() && App\Tender::canShowUpdate($organizationunit->id);
@endphp

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('modals')
	@include('partials._pilih_peringkat_modal')
@endpush

@section('content')
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
					<div class="row">
						<div class="col-md-2">
							<div class="nav flex-column nav-pills" role="tablist">
								<a href="{{ action('OrganizationUnitsController@agency', $organizationunit->id) }}"
									class="nav-link @if (!Request::get('type')) active @endif" role="tab">
									<i class="ti ti-list me-2"></i>Semua
								</a>
								<a href="{{ action('OrganizationUnitsController@agency', [$organizationunit->id, 'type' => 'tenders']) }}"
									class="nav-link @if (Request::get('type') == 'tenders') active @endif" role="tab">
									<i class="ti ti-file-text me-2"></i>Tender
								</a>
								<a href="{{ action('OrganizationUnitsController@agency', [$organizationunit->id, 'type' => 'quotations']) }}"
									class="nav-link @if (Request::get('type') == 'quotations') active @endif" role="tab">
									<i class="ti ti-calculator me-2"></i>Sebut Harga
								</a>
							</div>
						</div>

						<div class="col-md-10">
							<div class="table-responsive">
								<table class="DT-show table table-modern w-100 mb-0" data-path="{{ $path }}">
									<thead>
										<tr>
											<th>No / Tajuk</th>
											<th width="180px">Kod Bidang</th>
											<th width="150px">Tarikh Jual</th>
											<th width="150px">Tarikh Tutup</th>
											<th width="150px">Harga Dokumen</th>
											@if ($isInternal)
												<th width="150px">Status</th>
												<th width="170px">Tindakan</th>
											@endif
											@if ($canUpdate)
												<th width="110px">Jadual</th>
											@endif
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
	<script>
		var isInternal = {!! json_encode($isInternal) !!};
		var canUpdate  = {!! json_encode($canUpdate) !!};

		$('.DT-show').each(function() {
			var target = $(this);
			var path = target.data('path');

			// Susunan mesti sepadan dengan <th> dalam jadual di atas.
			var columns = [
				{ data: 'name', name: 'name' },
				{ data: 'codes', name: 'codes' },
				{ data: 'document_start_date', name: 'document_start_date' },
				{ data: 'submission_datetime', name: 'submission_datetime' },
				{ data: 'price', name: 'price' }
			];

			if (isInternal) {
				columns.push({ data: 'status', name: 'status' });
				columns.push({ data: 'actions', name: 'actions', orderable: false, searchable: false });
			}

			if (canUpdate) {
				columns.push({ data: 'report', name: 'report', orderable: false, searchable: false });
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
	@include('partials._pilih_peringkat_script')
@endsection
