@extends('layouts.modern')

@section('content')
	<?php $user = Auth::user(); ?>

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
				<i class="ti ti-users me-2"></i>Senarai Pengguna
			</h2>
			<br>

			@if ($user->ability(['Admin', 'Agency Admin'], ['User:approve']))
				<div class="row mb-4">
					<div class="col-sm-4">
						<div class="card card-link">
							<div class="card-body text-center">
								<div class="display-6 fw-bold text-primary mb-2">
									{{ number_format(App\User::pendingApprovalCount(), 0) }}
								</div>
								<div class="text-muted">
									<a href="{{ asset('users/pending-approval') }}" class="text-decoration-none stretched-link">
										<i class="ti ti-user-check me-1"></i>
										Pendaftaran Belum Diluluskan
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body text-center">
								<div class="display-6 fw-bold text-success mb-2">
									{{ number_format(App\User::activeCount(), 0) }}
								</div>
								<div class="text-muted">
									<i class="ti ti-user-check me-1"></i>
									Pengguna Aktif
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body text-center">
								<div class="display-6 fw-bold text-danger mb-2">
									{{ number_format(App\User::inactiveCount(), 0) }}
								</div>
								<div class="text-muted">
									<i class="ti ti-user-x me-1"></i>
									Pengguna Tidak Aktif
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif

			@include('users._review_snaps')

			<div class="card">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h3 class="card-title mb-0">
							<i class="ti ti-list me-2"></i>Senarai Pengguna
						</h3>
						<a href="{{ asset('users/create') }}" class="btn btn-primary btn-sm">
							<i class="ti ti-plus me-1"></i>Masukan Pengguna Baru
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table data-path="{{ route('users.index') }}" class="DT-users table table-vcenter table-mobile-md card-table">
							<thead>
								<tr>
									<th>
										<i class="ti ti-user me-1"></i>Nama
									</th>
									<th class="w-25">
										<i class="ti ti-mail me-1"></i>Alamat Emel
									</th>
									<th class="w-15">
										<i class="ti ti-shield me-1"></i>Peranan
									</th>
									<th>
										<i class="ti ti-building me-1"></i>Agensi / Syarikat
									</th>
									<th class="w-10">
										<i class="ti ti-status-change me-1"></i>Status
									</th>
									<th class="w-10">
										<i class="ti ti-check me-1"></i>Reviewed
									</th>
									<th class="w-15">
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
		$('.DT-users').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'email',
						name: 'email'
					},
					{
						data: 'roles_column',
						name: 'roles_column'
					},
					{
						data: 'organization_unit_id',
						name: 'organization_unit_id'
					},
					{
						data: 'confirmed',
						name: 'confirmed'
					},
					{
						data: 'arr',
						name: 'arr'
					},
					{
						data: 'actions',
						name: 'actions'
					}
				],
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
