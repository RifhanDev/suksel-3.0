@extends('layouts.v3.master')

@section('content')
	@php
		$columnsConfig = [
		    ['data' => 'group_name', 'name' => 'group_name', 'label' => 'Kumpulan', 'icon' => 'ti-folder'],
		    ['data' => 'name', 'name' => 'name', 'label' => 'Nama', 'icon' => 'ti-key'],
		    ['data' => 'display_name', 'name' => 'display_name', 'label' => 'Keterangan', 'icon' => 'ti-file-text'],
		    [
		        'data' => 'actions',
		        'name' => 'actions',
		        'label' => 'Tindakan',
		        'icon' => 'ti-settings',
		        'width' => 'w-25',
		        'orderable' => false,
		        'searchable' => false,
		    ],
		];
	@endphp

	@include('components.modern-index', [
		'title' => 'Senarai Kebenaran',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-key',
		'cardTitle' => 'Maklumat Kebenaran',
		'createUrl' => route('permissions.create'),
		'createLabel' => 'Tambah Kebenaran Baru',
		'showCreate' => App\Permission::canCreate(),
		'dataPath' => '/permissions',
		'columns' => $columnsConfig,
		'defaultOrder' => [[0, 'asc']],
		'pageLength' => 25,
	])
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');
			var columns = [{
					data: 'group_name',
					name: 'group_name'
				},
				{
					data: 'name',
					name: 'name'
				},
				{
					data: 'display_name',
					name: 'display_name'
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
					[0, 'asc']
				]
			});
		});
	</script>
@endsection
