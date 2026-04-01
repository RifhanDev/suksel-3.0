@extends('layouts.v3.master')
@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kategori Agensi</h3>
			<p class="text-muted small m-0">Pengurusan dan tetapan susunan senarai kategori agensi.</p>
		</div>
	</div>

	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
						stroke-linecap="round" stroke-linejoin="round">
						<polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
						<polyline points="2 17 12 22 22 17"></polyline>
						<polyline points="2 12 12 17 22 12"></polyline>
					</svg>
				</div>
				<h3 class="m-0 fw-bold text-uppercase" style="font-size: 1rem; color: #64748b;">Senarai Kategori Agensi</h3>
			</div>

			@include('organizationtypes.actions-footer', ['is_list' => true]) {{-- not footer, top actually --}}
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table data-path="/organizationtypes" class="DT-index table table-modern table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4">Nama</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Susunan</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width: 250px; min-width: 250px;">
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
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
					ajax: path,
					<<
					<< << < HEAD
					columns: [{
							data: 'name',
							name: 'name'
						},
						{
							data: 'sort_no',
							name: 'sort_no'
						},
						{
							data: 'actions',
							name: 'actions',
							orderable: false,
							searchable: false
						} ===
						=== =
						columns: [{
								data: 'name',
								name: 'name'
							},
							{
								data: 'sort_no',
								name: 'sort_no'
							},
							{
								data: 'actions',
								name: 'actions'
							}, >>>
							>>> > origin / dan - v2
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
						aaSorting: [], pageLength: 25, responsive: true, order: [
							[1, 'asc']
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
