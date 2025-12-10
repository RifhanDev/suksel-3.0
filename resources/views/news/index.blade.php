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
		    [
		        'data' => 'created_at',
		        'name' => 'created_at',
		        'label' => 'Tarikh',
		        'icon' => 'ti-calendar',
		        'width' => 'w-15',
		    ],
		    [
		        'data' => 'organization_unit_id',
		        'name' => 'organization_unit_id',
		        'label' => 'Agensi',
		        'icon' => 'ti-building',
		        'width' => 'w-20',
		    ],
		    [
		        'data' => 'title',
		        'name' => 'title',
		        'label' => 'Berita',
		        'icon' => 'ti-news',
		    ],
		    [
		        'data' => 'actions',
		        'name' => 'actions',
		        'label' => 'Tindakan',
		        'icon' => 'ti-settings',
		        'width' => 'w-10',
		        'orderable' => false,
		        'searchable' => false,
		    ],
		];
	@endphp

	@include('components.modern-index', [
		'title' => 'Senarai Berita',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-news',
		'cardTitle' => 'Maklumat Berita',
		'createUrl' => action('NewsController@create'),
		'createLabel' => 'Tambah Berita Baru',
		'showCreate' => App\News::canCreate(),
		'dataPath' => '/news',
		'columns' => $columnsConfig,
		'defaultOrder' => [[0, 'desc']],
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
					data: 'created_at',
					name: 'created_at'
				},
				{
					data: 'organization_unit_id',
					name: 'organization_unit_id'
				},
				{
					data: 'title',
					name: 'title'
				},
				{
					data: 'actions',
					name: 'actions',
					orderable: false,
					searchable: false
				}
			];

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
					[0, 'desc']
				]
			});
		});
	</script>
@endsection
