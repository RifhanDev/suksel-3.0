@extends('layouts.modern')

@section('content')
	@php
		$columnsConfig = [
		    ['data' => 'name', 'name' => 'name', 'label' => 'Nama Peranan', 'icon' => 'ti-user'],
		    ['data' => 'permissions', 'name' => 'permissions', 'label' => 'Kebenaran Ditetapkan', 'icon' => 'ti-key'],
		    [
		        'data' => 'user_count',
		        'name' => 'user_count',
		        'label' => 'Jumlah Pengguna',
		        'icon' => 'ti-users',
		        'width' => 'w-15',
		    ],
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
		'title' => 'Senarai Peranan',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-user',
		'cardTitle' => 'Maklumat Peranan',
		'createUrl' => route('roles.create'),
		'createLabel' => 'Tambah Peranan Baru',
		'showCreate' => App\Role::canCreate(),
		'dataPath' => '/roles',
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
					data: 'name',
					name: 'name'
				},
				{
					data: 'permissions',
					name: 'permissions'
				},
				{
					data: 'user_count',
					name: 'user_count'
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
