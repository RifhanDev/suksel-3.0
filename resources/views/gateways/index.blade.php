@extends('layouts.v3.master')

@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Tetapan Pembayaran</h3>
			<p class="text-muted small m-0">Pengurusan dan konfigurasi saluran pembayaran sistem.</p>
		</div>
	</div>

	<div class="content-card">
		<div class="content-card-header">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
						stroke-linecap="round" stroke-linejoin="round">
						<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
						<line x1="1" y1="10" x2="23" y2="10"></line>
					</svg>
				</div>
				<h3 class="content-card-title">Senarai Tetapan Pembayaran</h3>
			</div>

			@if (App\Gateway::canCreate())
				<a href="{{ asset('gateways/create') }}" class="btn-form btn-form-create">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="12" y1="5" x2="12" y2="19"></line>
						<line x1="5" y1="12" x2="19" y2="12"></line>
					</svg>
					Tetapan Baru
				</a>
			@endif
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table data-path="/gateways" class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4">Agensi</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Saluran</th>
							<th class="text-uppercase text-muted small fw-bold py-3">ID Merchant</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Versi</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Aktif</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Utama</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width: 200px; min-width: 200px;">
								Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		$('.DT-index').each(function() {
		var target = $(this);
		var path = target.data('path');

		var DT = target.DataTable({
			ajax: path,
			columns: [{
					data: 'organization_unit_id',
					name: 'organization_unit_id'
				},
				{
					data: 'type',
					name: 'type'
				},
				{
					data: 'merchant_code',
					name: 'merchant_code'
				},
				{
					data: 'version',
					name: 'version'
				},
				{
					data: 'active',
					name: 'active'
				},
				{
					data: 'default',
					name: 'default'
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
				<<
				<< << < HEAD
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
			pageLength: 25,
			responsive: true,
			order: [
				[0, 'asc']
			]
		});
		});
		});
	</script>
	=======
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
	>>>>>>> origin/dan-v2
@endsection
