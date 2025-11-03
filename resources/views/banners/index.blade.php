@extends('layouts.modern')

@section('content')
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
			[
				'data' => 'title',
				'name' => 'title',
				'label' => 'Tajuk',
				'icon' => 'ti-file-text',
			],
			[
				'data' => 'published',
				'name' => 'published',
				'label' => 'Siar',
				'icon' => 'ti-eye',
				'width' => 'w-10',
			],
			[
				'data' => 'created_at',
				'name' => 'created_at',
				'label' => 'Tarikh Muat Naik',
				'icon' => 'ti-calendar',
				'width' => 'w-20',
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
		],
		'defaultOrder' => [[2, 'desc']],
		'pageLength' => 25,
	])
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'title',
						name: 'title'
					},
					{
						data: 'published',
						name: 'published'
					},
					{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					}
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
				pageLength: 25,
				responsive: true,
				order: [
					[2, 'desc']
				]
			});
		});
	</script>
@endsection
