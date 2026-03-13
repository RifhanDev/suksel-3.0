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

    <div class="content-card mb-4">
        <div class="content-card-header">
            <!-- Left -->
            <h3 class="content-card-title d-flex align-items-center gap-2 mb-0">
                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                </div>
                Rekod Senarai Hitam
            </h3>

            <!-- Right -->
            @if (isset($vendor) && App\VendorBlacklist::canCreate())
                <a href="{{ route('vendor.blacklists.create', $vendor->id) }}" class="btn btn-selangor d-flex align-items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Masukkan Senarai Hitam Baru
                </a>
            @endif
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table data-path="{{ $ajax_url }}" class="DT-index table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            @if (!isset($vendor))
                                <th>
                                    <div class="d-flex align-items-center">Syarikat</div>
                                </th>
                            @endif
                            <th>
                                <div class="d-flex align-items-center">Agensi</div>
                            </th>
                            <th>
                                <div class="d-flex align-items-center">Sebab</div>
                            </th>
                            <th>
                                <div class="d-flex align-items-center">Mula</div>
                            </th>
                            <th>
                                <div class="d-flex align-items-center">Tamat</div>
                            </th>
                            <th>
                                <div class="d-flex align-items-center">Status</div>
                            </th>
                            <th width="150px">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @if (isset($vendor) && $vendor->canShow())
        <div class="d-flex justify-content-start pb-5">
            <a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" 
               class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 link-slide-underline">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span class="fw-bold">Maklumat Syarikat</span>
            </a>
        </div>
    @endif

@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
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
