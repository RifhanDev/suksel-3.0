@extends('layouts.modernLanding')
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
				<i class="ti ti-building me-2"></i>Senarai Agensi
				@if (isset($type))
					: {{ $type->name }}
				@endif
				@if (isset($parent))
					: {{ $parent->name }}
				@endif
			</h2>
			<br>

			<div class="card">
				<div class="card-header">
					<h3 class="card-title mb-0">
						<i class="ti ti-list me-2"></i>Direktori Agensi
					</h3>
					<div class="card-actions">
						@if (App\OrganizationUnit::canCreate())
							<a href="{{ asset('agencies/create') }}" class="btn btn-primary btn-sm">
								<i class="ti ti-plus me-1"></i>Masukkan Agensi Baru
							</a>
						@endif
						<div class="btn-group ms-2">
							<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
								aria-expanded="false">
								<i class="ti ti-filter me-1"></i>Pilihan Kategori
							</button>
							<ul class="dropdown-menu dropdown-menu-end">
								@foreach (App\OrganizationType::all() as $ou_type)
									<li><a class="dropdown-item"
											href="{{ route('agencies.index', ['type' => $ou_type->id]) }}">{{ $ou_type->name }}</a></li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table
							data-path="/agencies<?php if(isset($type)) : ?>?type=<?php echo $type->id; ?><?php endif; ?><?php if(isset($parent)) : ?>?parent=<?php echo $parent->id; ?><?php endif; ?>"
							class="DT-index table table-vcenter table-mobile-md">
							<thead>
								<tr>
									<th class="w-25">
										<i class="ti ti-building me-1"></i>Nama
									</th>
									<th>
										<i class="ti ti-map-pin me-1"></i>Alamat
									</th>
									<th class="w-15">
										<i class="ti ti-phone me-1"></i>No. Telefon
									</th>
									@if (!isset($type))
										<th class="w-20">
											<i class="ti ti-category me-1"></i>Kategori
										</th>
									@endif
									<th class="w-10">
										<i class="ti ti-settings me-1"></i>Tindakan
									</th>
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
	<script>
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			if (path.includes('/agencies?type')) {
				var columns = [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'address',
						name: 'address'
					},
					{
						data: 'tel',
						name: 'tel'
					},
					{
						data: 'actions',
						name: 'actions'
					}
				];
			} else if (path.includes('/agencies?parent')) {
				var columns = [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'address',
						name: 'address'
					},
					{
						data: 'tel',
						name: 'tel'
					},
					{
						data: 'type_id',
						name: 'type_id'
					},
					{
						data: 'actions',
						name: 'actions'
					}
				];
			} else {

				var columns = [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'address',
						name: 'address'
					},
					{
						data: 'tel',
						name: 'tel'
					},
					{
						data: 'type_id',
						name: 'type_id'
					},
					{
						data: 'actions',
						name: 'actions'
					}
				];
			}

			var DT = target.DataTable({
				ajax: path,
				columns: columns,
				serverSide: true,
				// stateSave: true,
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
