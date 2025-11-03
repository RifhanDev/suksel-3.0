{{-- 
	Modern Index Page Component
	
	Usage:
	@include('components.modern-index', [
		'title' => 'Senarai Banner',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-photo',
		'cardTitle' => 'Maklumat Banner',
		'createUrl' => asset('banners/create'),
		'createLabel' => 'Masukkan Banner Baru',
		'showCreate' => true,
		'dataPath' => '/banners',
		'columns' => [
			['data' => 'title', 'name' => 'title', 'label' => 'Tajuk', 'icon' => 'ti-file-text'],
			['data' => 'published', 'name' => 'published', 'label' => 'Siar', 'icon' => 'ti-eye', 'width' => 'w-10'],
			['data' => 'created_at', 'name' => 'created_at', 'label' => 'Tarikh', 'icon' => 'ti-calendar', 'width' => 'w-20'],
			['data' => 'actions', 'name' => 'actions', 'label' => 'Tindakan', 'icon' => 'ti-settings', 'width' => 'w-25', 'orderable' => false, 'searchable' => false]
		],
		'sidebarContent' => null // Optional: Pass sidebar content
	])
--}}

@php
	$title = $title ?? 'Senarai Data';
	$pretitle = $pretitle ?? 'Sistem Tender Online';
	$icon = $icon ?? 'ti-list';
	$cardTitle = $cardTitle ?? 'Maklumat';
	$createUrl = $createUrl ?? null;
	$createLabel = $createLabel ?? 'Tambah Baru';
	$showCreate = $showCreate ?? true;
	$dataPath = $dataPath ?? '/data';
	$columns = $columns ?? [];
	$pageLength = $pageLength ?? 25;
	$defaultOrder = $defaultOrder ?? [[0, 'desc']];
	$sidebarContent = $sidebarContent ?? null;
@endphp

<style>
	/* Modern Card Styling */
	.modern-card {
		border: none;
		border-radius: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		transition: all 0.3s ease;
	}

	.modern-card:hover {
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
	}

	.page-header-modern {
		background: linear-gradient(135deg, #e0dfdf 0%, #c44f4f 100%);
		color: white;
		padding: 2rem;
		border-radius: 12px;
		margin-bottom: 2rem;
	}

	.page-header-modern h2 {
		margin: 0;
		font-weight: 600;
		font-size: 1.75rem;
	}

	.page-header-modern .page-pretitle {
		opacity: 0.9;
		font-size: 0.875rem;
		margin-bottom: 0.5rem;
	}

	.modern-table {
		border-collapse: separate;
		border-spacing: 0;
	}

	.modern-table thead th {
		background: #f8f9fa;
		border-bottom: 2px solid #dee2e6;
		font-weight: 600;
		text-transform: uppercase;
		font-size: 0.75rem;
		letter-spacing: 0.05em;
		padding: 1rem;
		color: #495057;
	}

	.modern-table tbody tr {
		transition: all 0.2s ease;
	}

	.modern-table tbody tr:hover {
		background: #f8f9fa;
	}

	.modern-table tbody td {
		padding: 1rem;
		vertical-align: middle;
	}

	.btn-modern {
		border-radius: 8px;
		padding: 0.5rem 1.25rem;
		font-weight: 500;
		transition: all 0.2s ease;
	}

	.btn-modern:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	}

	.card-title-modern {
		font-weight: 600;
		color: #2c3e50;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}
</style>

<div class="row">
	<div class="{{ $sidebarContent ? 'col-lg-9' : 'col-12' }}">
		<!-- Page Header -->
		<div class="page-header-modern">
			<div class="page-pretitle">
				<i class="{{ $icon }} me-2"></i>{{ $pretitle }}
			</div>
			<h2>
				<i class="{{ $icon }} me-2"></i>{{ $title }}
			</h2>
		</div>

		<!-- Main Card -->
		<div class="card modern-card">
			<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
				<h3 class="card-title-modern mb-0">
					<i class="ti ti-list"></i>
					{{ $cardTitle }}
				</h3>
				@if ($showCreate && $createUrl)
					<div class="ms-auto">
						<a href="{{ $createUrl }}" class="btn btn-primary btn-modern">
							<i class="ti ti-plus me-1"></i>{{ $createLabel }}
						</a>
					</div>
				@endif
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table data-path="{{ $dataPath }}" class="DT-index table modern-table table-hover">
						<thead>
							<tr>
								@foreach ($columns as $col)
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

	@if ($sidebarContent)
		<!-- Sidebar -->
		<div class="col-lg-3">
			{!! $sidebarContent !!}
		</div>
	@endif
</div>

@push('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [
					@foreach ($columns as $col)
						{
							data: '{{ $col['data'] }}',
							name: '{{ $col['name'] }}'
							@if (isset($col['orderable']) && !$col['orderable'])
								, orderable: false
							@endif
							@if (isset($col['searchable']) && !$col['searchable'])
								, searchable: false
							@endif
						},
					@endforeach
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
				aaSorting: [],
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
				pageLength: {{ $pageLength }},
				responsive: true,
				order: {!! json_encode($defaultOrder) !!}
			});
		});
	</script>
@endpush
