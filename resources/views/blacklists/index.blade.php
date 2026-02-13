@extends('layouts.v3.master')

@section('styles')
<style>
    .page-title-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .title-pipe {
        font-size: 1.5rem;
        color: #cbd5e1;
        font-weight: 300;
        margin: 0 15px;
    }

    .vendor-highlight-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--sg-red);
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(196, 30, 58, 0.1); 
    }

    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        position: relative;
    }

    /* Geometric Accent */
    .stats-card::before {
        content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
        background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
    }

    .stats-card-header {
        padding: 20px 24px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }

    .stats-card-title {
        margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;
    }

    .table-modern thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern tbody tr:hover {
        background-color: #fff9f9;
    }

    .icon-svg {
        width: 16px; height: 16px; margin-right: 6px; stroke-width: 2; color: var(--sg-red);
    }

    .link-slide-underline {
        position: relative; text-decoration: none !important; transition: color 0.3s ease-in-out; padding-bottom: 3px;
    }
    .link-slide-underline:hover { color: var(--sg-red) !important; }
    .link-slide-underline::after {
        content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
        background-color: var(--sg-red); transition: width 0.3s ease-in-out;
    }
    .link-slide-underline:hover::after { width: 100%; }
</style>
@endsection

@section('content')

	<!-- Page Header -->
	<div class="page-header-modern">
		<div class="page-pretitle">
			<i class="ti ti-ban me-2"></i>Sistem Tender Online
		</div>
		<h2>
			<i class="ti ti-ban me-2"></i>
			@if (isset($vendor))
				{{ $vendor->name }}
			@else
				Syarikat
			@endif : Senarai Hitam
		</h2>
	</div>

	<!-- Main Card -->
	<div class="card modern-card">
		<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
			<div class="d-flex justify-content-between align-items-center">
				<h3 class="card-title-modern mb-0">
					<i class="ti ti-list"></i>
					Senarai Hitam
				</h3>
				@if (isset($vendor))
					<div class="d-flex gap-2">
						@if (App\VendorBlacklist::canCreate())
							<a href="{{ route('vendor.blacklists.create', $vendor->id) }}" class="btn btn-primary btn-modern">
								<i class="ti ti-plus me-1"></i>Masukkan Senarai Hitam Baru
							</a>
						@endif
						@if ($vendor->canShow())
							<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}"
								class="btn btn-outline-secondary btn-modern">
								<i class="ti ti-building me-1"></i>Maklumat Syarikat
							</a>
						@endif
					</div>
				@endif
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table data-path="{{ $ajax_url }}" class="DT-index table modern-table table-hover">
					<thead>
						<tr>
							@if (!isset($vendor))
								<th>
									<i class="ti ti-building me-1"></i>Syarikat
								</th>
							@endif
							<th>
								<i class="ti ti-building-community me-1"></i>Agensi
							</th>
							<th>
								<i class="ti ti-file-text me-1"></i>Sebab
							</th>
							<th>
								<i class="ti ti-calendar me-1"></i>Tarikh Mula
							</th>
							<th>
								<i class="ti ti-calendar-event me-1"></i>Tarikh Tamat
							</th>
							<th>
								<i class="ti ti-info-circle me-1"></i>Status
							</th>
							<th width="200px">
								<i class="ti ti-settings me-1"></i>Tindakan
							</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			if (path.includes('/vendor')) {
				var columns = [{
						data: 'organization_unit_id',
						name: 'organization_unit_id'
					},
					{
						data: 'reason',
						name: 'reason'
					},
					{
						data: 'start',
						name: 'start'
					},
					{
						data: 'end',
						name: 'end'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'actions',
						name: 'actions'
					},
				];
			} else {

				var columns = [{
						data: 'vendor_id',
						name: 'vendor_id'
					},
					{
						data: 'organization_unit_id',
						name: 'organization_unit_id'
					},
					{
						data: 'reason',
						name: 'reason'
					},
					{
						data: 'start',
						name: 'start'
					},
					{
						data: 'end',
						name: 'end'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'actions',
						name: 'actions'
					},
				];
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
				aaSorting: [],
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
				pageLength: 25,
				responsive: true,
				order: [
					[path.includes('/vendor') ? 2 : 3, 'desc']
				]
			});
		});
	</script>
@endsection