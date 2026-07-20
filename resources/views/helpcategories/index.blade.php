@extends('layouts.v3.master')

@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kategori Soalan Lazim</h3>
			<p class="text-muted small m-0">Pengurusan kategori bagi senarai soalan lazim sistem.</p>
		</div>
	</div>

	<div class="content-card">
		<div class="content-card-header">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
						stroke-linecap="round" stroke-linejoin="round">
						<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
						<line x1="7" y1="7" x2="7.01" y2="7" />
					</svg>
				</div>
				<h3 class="content-card-title">Senarai Kategori Soalan Lazim</h3>
			</div>

			@if (Auth::user() && Auth::user()->hasRole('Admin'))
				<div class="d-flex align-items-center gap-2">
					<!-- <a href="{{ asset('helps') }}" class="btn-form btn-form-secondary">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
							<line x1="12" y1="17" x2="12.01" y2="17"></line>
						</svg>
						Soalan Lazim
					</a> -->
					<a href="{{ asset('helpcategories/create') }}" class="btn-form btn-form-create">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="5" x2="12" y2="19"></line>
							<line x1="5" y1="12" x2="19" y2="12"></line>
						</svg>
						Tambah Kategori
					</a>
				</div>
			@endif
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table data-path="/helpcategories" class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4">Nama</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Jumlah Soalan</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width: 200px; min-width: 200px;">Tindakan</th>
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
				columns: [
					{ data: 'name',    name: 'name' },
					{ data: 'count',   name: 'count' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: false }
				],
				serverSide: true,
				stateSave: true,
				language: {
					sEmptyTable:    "Tiada data",
					sInfo:          "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
					sInfoEmpty:     "Paparan 0 hingga 0 dari 0 rekod",
					sInfoFiltered:  "(Ditapis dari jumlah _MAX_ rekod)",
					sInfoPostFix:   "",
					sInfoThousands: ",",
					sLengthMenu:    "Papar _MENU_ rekod",
					sLoadingRecords:"Diproses...",
					sProcessing:    "Sedang diproses...",
					sSearch:        "Carian:",
					sZeroRecords:   "Tiada padanan rekod yang dijumpai.",
					oPaginate: {
						sFirst: "Pertama", sPrevious: "Sebelum", sNext: "Kemudian", sLast: "Akhir"
					},
					oAria: {
						sSortAscending:  ": diaktifkan kepada susunan lajur menaik",
						sSortDescending: ": diaktifkan kepada susunan lajur menurun"
					}
				},
				aaSorting: [],
				pageLength: 25,
				responsive: true,
				order: [[0, 'asc']]
			});
		});
	</script>
@endsection
