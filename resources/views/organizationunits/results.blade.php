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
						<a href="{{ route('agencies.edit', $organizationunit->id) }}" class="btn btn-warning btn-sm">
							<i class="ti ti-edit me-1"></i>Kemaskini Agensi
						</a>
					@endif
					@if (Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
						<a href="{{ route('tenders.create') }}" class="btn btn-primary btn-sm">
							<i class="ti ti-plus me-1"></i>Tambah Tender / Sebut Harga
						</a>
					@endif
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
								<a href="{{ action('OrganizationUnitsController@results', $organizationunit->id) }}"
									class="nav-link @if (!Request::get('type')) active @endif" role="tab">
									<i class="ti ti-list me-2"></i>Semua
								</a>
								<a href="{{ action('OrganizationUnitsController@results', [$organizationunit->id, 'type' => 'tenders']) }}"
									class="nav-link @if (Request::get('type') == 'tenders') active @endif" role="tab">
									<i class="ti ti-file-text me-2"></i>Tender
								</a>
								<a href="{{ action('OrganizationUnitsController@results', [$organizationunit->id, 'type' => 'quotations']) }}"
									class="nav-link @if (Request::get('type') == 'quotations') active @endif" role="tab">
									<i class="ti ti-calculator me-2"></i>Sebut Harga
								</a>
							</div>
						</div>

						<div class="col-md-10">
							<div class="table-responsive">
								<table class="DT2 table table-vcenter table-mobile-md">
									<thead>
										<tr>
											<th class="w-15">
												<i class="ti ti-calendar me-1"></i>Tarikh Tutup
											</th>
											<th>
												<i class="ti ti-file-text me-1"></i>No / Tajuk
											</th>
											@if (Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
												<th class="w-15">
													<i class="ti ti-settings me-1"></i>Tindakan
												</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach ($tenders as $tender)
											<tr>
												<td>{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}</td>
												<td>
													<strong>
														@if (Auth::check() && !Auth::user()->hasRole('Vendor') && $tender->invitation)
															<i class="ti ti-lock"></i>
														@endif {{ $tender->ref_number }}
													</strong>
													<br>
													<a href="{{ action('TendersController@show', $tender->id) }}">{{ $tender->name }}</a>
												</td>
												@if (Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
													<td>
														<div class="btn-group">
															@if (empty($tender->approver_id))
																<a class="btn btn-primary btn-sm" href="{{ route('tenders.edit', $tender->id) }}">
																	<i class="ti ti-edit me-1"></i>Kemaskini
																</a>
															@endif
															<a class="btn btn-success btn-sm" href="{{ route('tenders.show', $tender->id) }}">
																<i class="ti ti-eye me-1"></i>Papar
															</a>
															@if ($tender->canCancel() && $tender->publish_winner)
																<a class="btn btn-danger btn-sm" href="{{ route('tenders.publishWinner', $tender->id) }}">
																	<i class="ti ti-x me-1"></i>Batal Siar
																</a>
															@else
																<a class="btn btn-warning btn-sm" href="{{ route('tenders.publishWinner', $tender->id) }}">
																	<i class="ti ti-send me-1"></i>Umum
																</a>
															@endif
														</div>
													</td>
												@endif
											</tr>
										@endforeach
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
		$('.DT2').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
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
