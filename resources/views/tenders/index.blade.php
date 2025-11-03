@extends('layouts.modern')

@section('content')
	@php
		$sidebarHtml =
		    '<div class="row">
			<div class="col-12 mb-3">' .
		    view('layouts._register')->render() .
		    '</div>
			<div class="col-12">' .
		    view('layouts._news')->render() .
		    '</div>
		</div>';

		$columnsConfig = [
		    ['data' => 'name', 'name' => 'name', 'label' => 'Maklumat Tender', 'icon' => 'ti-file-text'],
		    [
		        'data' => 'document_start_date',
		        'name' => 'document_start_date',
		        'label' => 'Tarikh Jual',
		        'icon' => 'ti-calendar',
		        'width' => 'w-15',
		    ],
		    [
		        'data' => 'submission_datetime',
		        'name' => 'submission_datetime',
		        'label' => 'Tarikh Tutup',
		        'icon' => 'ti-calendar',
		        'width' => 'w-15',
		    ],
		    [
		        'data' => 'price',
		        'name' => 'price',
		        'label' => 'Harga Dokumen (RM)',
		        'icon' => 'ti-currency-ringgit',
		        'width' => 'w-15',
		    ],
		];

		if (Auth::check() && !Auth::user()->hasRole('Vendor')) {
		    $columnsConfig[] = [
		        'data' => 'approver_id',
		        'name' => 'approver_id',
		        'label' => 'Status',
		        'icon' => 'ti-status-change',
		        'width' => 'w-10',
		    ];
		}
	@endphp

	@include('components.modern-index', [
		'title' => 'Senarai Tender / Sebutharga',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-file-text',
		'cardTitle' => 'Maklumat Tender',
		'createUrl' => asset('tenders/create'),
		'createLabel' => 'Tambah Tender / Sebutharga',
		'showCreate' => App\Tender::canCreate(),
		'dataPath' => '/tenders',
		'columns' => $columnsConfig,
		'defaultOrder' => [[1, 'desc']],
		'pageLength' => 25,
		'sidebarContent' => $sidebarHtml,
	])
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');
			var columns = [{
					data: 'name',
					name: 'name'
				},
				{
					data: 'document_start_date',
					name: 'document_start_date'
				},
				{
					data: 'submission_datetime',
					name: 'submission_datetime'
				},
				{
					data: 'price',
					name: 'price'
				}
			];

			@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
				columns.push({
					data: 'approver_id',
					name: 'approver_id'
				});
			@endif

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
					[1, 'desc']
				]
			});
		});
	</script>
@endsection
