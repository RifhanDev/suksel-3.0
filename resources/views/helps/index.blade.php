@extends('layouts.v3.master')

@section('styles')
	<style>
		.help-theme-card {
			display: flex;
			align-items: center;
			justify-content: space-between;
			background: #ffffff;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
			padding: 20px 24px;
			text-decoration: none !important;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
		}

		.help-theme-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 4px;
			height: 100%;
			background-color: var(--sg-red, #c41e3a);
			opacity: 0.8;
			transition: all 0.3s ease;
		}

		.help-theme-card:hover {
			transform: translateY(-3px);
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
			border-color: rgba(196, 30, 58, 0.2);
		}

		.help-theme-card:hover::before {
			width: 6px;
			opacity: 1;
		}

		.help-card-content {
			display: flex;
			flex-direction: column;
			gap: 4px;
		}

		.help-card-header-row {
			display: flex;
			align-items: baseline;
			gap: 8px;
		}

		.help-card-title {
			font-family: 'Poppins', sans-serif;
			font-size: 1.05rem;
			font-weight: 700;
			color: #1e293b;
			transition: color 0.3s ease;
		}

		.help-theme-card:hover .help-card-title {
			color: var(--sg-red, #c41e3a);
		}

		.help-card-count {
			font-size: 0.8rem;
			font-weight: 500;
			color: #64748b;
		}

		.help-card-desc {
			font-size: 0.875rem;
			color: #64748b;
			transition: color 0.3s ease;
		}

		.help-theme-card:hover .help-card-desc {
			color: #334155;
		}

		.help-card-arrow {
			width: 32px;
			height: 32px;
			border-radius: 50%;
			background-color: #f8fafc;
			color: #94a3b8;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.3s ease;
			border: 1px solid #f1f5f9;
		}

		.help-theme-card:hover .help-card-arrow {
			background-color: var(--sg-red, #c41e3a);
			color: #ffffff;
			border-color: var(--sg-red, #c41e3a);
			transform: translateX(4px);
		}
	</style>
@endsection

@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Soalan Lazim</h3>
			<p class="text-muted small m-0">Pengurusan bagi senarai soalan lazim sistem.</p>
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
				<h3 class="content-card-title">Kategori Soalan Lazim</h3>
			</div>
		</div>

		<div class="content-card-body p-2">

			<div class="row g-4 p-3">
				@forelse($categories as $category)
					<div class="col-md-6">
						<a href="{{ action('HelpsController@show', $category->id) }}" class="help-theme-card">
							<div class="help-card-content">
								<div class="help-card-header-row">
									<span class="help-card-title">{{ $category->name }}</span>
									<span class="help-card-count">({{ $category->helps->count() }} artikel)</span>
								</div>
								<div class="help-card-desc">
									{{ $category->description ?? 'Bantuan untuk ' . strtolower($category->name) }}
								</div>
							</div>
							<div class="help-card-arrow">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="9 18 15 12 9 6"></polyline>
								</svg>
							</div>
						</a>
					</div>
				@empty
					<!-- Fallback Dummy Cards if no database categories exist yet -->
					<div class="col-md-6">
						<a href="{{ route('showSoalanLazim') }}" class="help-theme-card">
							<div class="help-card-content">
								<div class="help-card-header-row">
									<span class="help-card-title">Kontraktor</span>
									<span class="help-card-count">(19 artikel)</span>
								</div>
								<div class="help-card-desc">
									Bantuan Untuk Kontraktor
								</div>
							</div>
							<div class="help-card-arrow">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="9 18 15 12 9 6"></polyline>
								</svg>
							</div>
						</a>
					</div>
					<div class="col-md-6">
						<a href="{{ route('showSoalanLazim') }}" class="help-theme-card">
							<div class="help-card-content">
								<div class="help-card-header-row">
									<span class="help-card-title">Agensi</span>
									<span class="help-card-count">(5 artikel)</span>
								</div>
								<div class="help-card-desc">
									Bantuan untuk agensi
								</div>
							</div>
							<div class="help-card-arrow">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="9 18 15 12 9 6"></polyline>
								</svg>
							</div>
						</a>
					</div>
				@endforelse
			</div>
			<!-- <div class="table-responsive">
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
			</div> -->
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
